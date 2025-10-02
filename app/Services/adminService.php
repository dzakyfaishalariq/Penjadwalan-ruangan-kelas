<?php
namespace App\Services;

use App\ApiTemplate\Template;
use App\Mail\AutenticationAdmin;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class adminService
{
    public function addAdmin($request)
    {
        try {
            // validasi data yang diinputkan admin
            $kondisi = $request->validate([
                'name'     => 'required|string',
                'email'    => 'required|email',
                'password' => 'required|string|min:8',
            ]);
            // cek kondisi apabila memenuhi data admin akan di tambahkan kedalam table admin jika gagal akan mengembalikan response json dengan pesan error
            if ($kondisi) {
                DB::beginTransaction();
                // menambahkan data admin
                $tambah_data_admin = DB::table('users')->insertGetId([
                    'name'           => $request->name,
                    'email'          => $request->email,
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'created_at'     => now(),
                    'updated_at'     => now(),
                    'role'           => 'admin',
                ]);
                // buat token verifikasi
                $token = Str::random(60);
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $kondisi['email']],
                    [
                        'token'      => $token,
                        'created_at' => now(),
                    ]
                );
                // kirim ke email untuk verifikasi
                Mail::to($kondisi['email'])->send(new AutenticationAdmin($token, $kondisi['email']));
                DB::commit();
                $data_admin_hasil_tambah = DB::table('users')->where('id', $tambah_data_admin)->first();
                $respons                 = new Template(true, 'Data Berhasil di tambahkan', $data_admin_hasil_tambah);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function verifyAdmin($request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->first();
        if (! $tokenData) {
            return response()->json([
                'message' => 'Invalid token',
            ], 422);
        }
        DB::beginTransaction();
        // verifikasi admin
        DB::table('users')
            ->where('email', $request->email)
            ->update([
                'email_verified_at' => now(),
            ]);
        // hapus token yang sudah digunakan
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
        DB::commit();
        // return response()->json([
        //     'status'  => true,
        //     'message' => 'Email verified successfully',
        // ], 200);
        // mengembalikan response dengan menampilkan halaman hasil-verifikasi-email.blade.php dengan
        // mengirimkan data status dan message
        return view('emails.hasil-verifikasi-email', [
            'status'  => true,
            'message' => 'Email verified successfully',
        ]);

    }
}
