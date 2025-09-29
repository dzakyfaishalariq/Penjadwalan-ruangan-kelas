<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class loginService
{
    // login sebagai mahasiswa
    public function login_mahasiswa($request)
    {
        try {
            //cek validasi login email dan password
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);
            // ambil data mahasiswa
            $user = DB::table('tb_mahasiswa')
                ->join('tb_prodi', 'tb_mahasiswa.prodi_id', '=', 'tb_prodi.id')
                ->join('tb_pemilihan', 'tb_mahasiswa.pemilih_id', '=', 'tb_pemilihan.id')
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.fakultas_id as fakultas_id',
                    'tb_pemilihan.id as pemilihan_id',
                    'tb_mahasiswa.id as mahasiswa_id',
                    'tb_mahasiswa.nama as nama_mahasiswa',
                    'tb_mahasiswa.nim as nim_mahasiswa',
                    'tb_mahasiswa.email as email_mahasiswa',
                    'tb_mahasiswa.username as username_mahasiswa',
                    'tb_mahasiswa.password as password_mahasiswa',
                    'tb_mahasiswa.role as role_mahasiswa',
                    'tb_mahasiswa.api_key as api_key_mahasiswa',
                    'tb_mahasiswa.email_verify_at',
                )
                ->where('email', $credentials['email'])
                ->first();

            // lakukan pengecekan login
            if (! $user || ! Hash::check($credentials['password'], $user->password_mahasiswa)) {
                return response()->json([
                    'success' => false,
                    // 'user' => $user,
                    // 'cek' => Hash::check($credentials['password'], $user->password_mahasiswa),
                    // 'password' => $credentials['password'],
                    // 'hash' => $user->password_mahasiswa,
                    'message' => 'Email atau password salah',
                ], 401);
            }
            // cek apakah email sudah diverifikasi
            if (! $user->email_verify_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email belum diverifikasi',
                ], 401);
            }
            // buat api key baru
            $api_key = Str::random(60);
            DB::beginTransaction();
            // update api key data mahasiswa
            DB::table('tb_mahasiswa')
                ->where('id', $user->mahasiswa_id)
                ->update([
                    'api_key' => $api_key,
                ]);
            DB::commit();
            // memberikan response json dari berhasilnya login
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data'    => [
                    'user'           => [
                        'id'          => $user->mahasiswa_id,
                        'id_prodi'    => $user->prodi_id,
                        'id_fakultas' => $user->fakultas_id,
                        'id_pemilih'  => $user->pemilihan_id,
                        'nama'        => $user->nama_mahasiswa,
                        'nim'         => $user->nim_mahasiswa,
                        'email'       => $user->email_mahasiswa,
                        'username'    => $user->username_mahasiswa,
                        'role'        => $user->role_mahasiswa,
                    ],
                    'api_key'        => $api_key,
                    'token_api_type' => 'Bearer',
                ],

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function login_dosen($request)
    {
        try {
            //cek validasi login email dan password
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);
            // ambil data dosen
            $user = DB::table('tb_dosen')
                ->join('tb_prodi', 'tb_dosen.prodi_id', '=', 'tb_prodi.id')
                ->join('tb_pemilihan', 'tb_dosen.pemilih_id', '=', 'tb_pemilihan.id')
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.nama_prodi as prodi',
                    'tb_dosen.id as id',
                    'tb_dosen.pemilih_id as pemilih_id',
                    'tb_dosen.nama as nama',
                    'tb_dosen.nip as nip',
                    'tb_dosen.email as email',
                    'tb_dosen.username as username',
                    'tb_dosen.password as password',
                    'tb_dosen.api_key as token',
                    'tb_dosen.email_verify_at',
                    'tb_dosen.role as role',
                    'tb_dosen.created_at as created_at',
                    'tb_dosen.created_up as created_up',
                )
                ->where('email', $credentials['email'])
                ->first();

            // lakukan pengecekan login
            if (! $user || ! Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah',
                ], 401);
            }
            // cek apakah email sudah diverifikasi
            if (! $user->email_verify_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email belum diverifikasi',
                ], 401);
            }
            // buat api key baru
            $api_key = Str::random(60);
            DB::beginTransaction();
            // update api key data dosen
            DB::table('tb_dosen')
                ->where('id', $user->id)
                ->update([
                    'api_key' => $api_key,
                ]);
            DB::commit();
            // memberikan response json dari berhasilnya login
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data'    => [
                    'user'           => [
                        'id'          => $user->id,
                        'id_prodi'    => $user->prodi_id,
                        'id_pemilih'  => $user->pemilih_id,
                        'nama'        => $user->nama,
                        'nip'         => $user->nip,
                        'email'       => $user->email,
                        'username'    => $user->username,
                        'role'        => $user->role,
                    ],
                    'api_key'        => $api_key,
                    'token_api_type' => 'Bearer',
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                "line"    => $e->getLine(),
                "file"    => $e->getFile(),
                // "trace"   => $e->getTraceAsString(),
                "code"    => $e->getCode(),
            ], 422);
        }
    }
}
