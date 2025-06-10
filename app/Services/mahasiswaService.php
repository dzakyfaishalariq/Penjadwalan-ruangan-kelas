<?php
namespace App\Services;

use App\ApiTemplate\Template;
use App\Mail\Autentication;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class mahasiswaService
{
    public function getMahasiswa(int $paginate = 10)
    {
        // mengecek apakah $paginate bernilai lebih dari 0
        $paginate = $paginate > 0 ? $paginate : 10;
        // mengambil semua data mahasiswa
        try {
            $data_mahasiswa = DB::table('tb_mahasiswa')
                ->join('tb_prodi', 'tb_mahasiswa.prodi_id', '=', 'tb_prodi.id')
                ->join('tb_pemilihan', 'tb_mahasiswa.pemilih_id', '=', 'tb_pemilihan.id')
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.fakultas_id as fakultas_id',
                    'tb_prodi.nama_prodi as prodi',
                    'tb_pemilihan.id as pemilihan_id',
                    'tb_pemilihan.nama as nama_pemilih',
                    'tb_pemilihan.tipe as tipe_pemilih',
                    'tb_mahasiswa.id as mahasiswa_id',
                    'tb_mahasiswa.nama as nama_mahasiswa',
                    'tb_mahasiswa.nim as nim_mahasiswa',
                    'tb_mahasiswa.email as email_mahasiswa',
                    'tb_mahasiswa.username as username_mahasiswa',
                    'tb_mahasiswa.password as password_mahasiswa',
                    'tb_mahasiswa.role as role_mahasiswa',
                    'tb_mahasiswa.api_key as api_key_mahasiswa',
                )
                ->paginate($paginate);
            $respons = new Template(true, 'Data Berhasil di ambil', $data_mahasiswa);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getMahasiswaById(int $id)
    {
        // mengambil data mahasiswa berdasarkan ID mahasiswa
        try {
            $data_mahasiswa_by_id = DB::table('tb_mahasiswa')
                ->join('tb_prodi', 'tb_mahasiswa.prodi_id', '=', 'tb_prodi.id')
                ->join('tb_pemilihan', 'tb_mahasiswa.pemilih_id', '=', 'tb_pemilihan.id')
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.fakultas_id as fakultas_id',
                    'tb_prodi.nama_prodi as prodi',
                    'tb_pemilihan.id as pemilihan_id',
                    'tb_pemilihan.nama as nama_pemilih',
                    'tb_pemilihan.tipe as tipe_pemilih',
                    'tb_mahasiswa.id as mahasiswa_id',
                    'tb_mahasiswa.nama as nama_mahasiswa',
                    'tb_mahasiswa.nim as nim_mahasiswa',
                    'tb_mahasiswa.email as email_mahasiswa',
                    'tb_mahasiswa.username as username_mahasiswa',
                    'tb_mahasiswa.password as password_mahasiswa',
                    'tb_mahasiswa.role as role_mahasiswa',
                    'tb_mahasiswa.api_key as api_key_mahasiswa',
                )
                ->where('tb_mahasiswa.id', $id)
                ->first();
            $respons = new Template(true, 'Data Berhasil di ambil', $data_mahasiswa_by_id);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function createMahasiswa($request)
    {
        // memanggil fungsi createMahasiswa untuk menambahkan data mahasiswa
        try {
            $data_validasi = $request->validate([
                "prodi_id" => "required|integer",
                "nama"     => "required|string|max:255",
                "nim"      => "required|string|max:255",
                "email"    => "required|string|max:255",
                "username" => "required|string|max:255",
                "password" => "required|string|min:8|max:255",
                "role"     => "required|string|max:255",
            ]);
            if ($data_validasi) {
                DB::beginTransaction();
                // validasi
                $id_pemilihan = DB::table('tb_pemilihan')->insertGetId([
                    'nama' => $request->nama,
                    'tipe' => "Mahasiswa",
                ]);
                // tambah data atau daftar
                $id_mahasiswa = DB::table('tb_mahasiswa')->insertGetId([
                    'prodi_id'   => $request->prodi_id,
                    'pemilih_id' => $id_pemilihan,
                    'nama'       => htmlspecialchars($request->nama),
                    'nim'        => htmlspecialchars($request->nim),
                    'email'      => $request->email,
                    'username'   => htmlspecialchars($request->username),
                    'password'   => Hash::make($request->password),
                    'role'       => $request->role,
                    'api_key'    => Str::random(60),
                    'created_at' => now(),
                    'created_up' => now(),
                ]);
                // Buat token verifikasi
                $token = Str::random(60);
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $data_validasi['email']],
                    [
                        'token'      => $token,
                        'created_at' => now(),
                    ]
                );
                // kirim ke email untuk verifikasi
                Mail::to($data_validasi['email'])->send(new Autentication($token, $data_validasi['email']));
                DB::commit();
                // mengambil data mahasiswa dan menampilkannya
                $data_mahasiswa_by_id = DB::table('tb_mahasiswa')->where('id', $id_mahasiswa)->first();
                unset($data_mahasiswa_by_id->password);
                $respons = new Template(true, 'Register Berhasil, Silahkan Cek Email anda untuk verifikasi', $data_mahasiswa_by_id);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Register Gagal', $data_validasi);
                return $respons->response();
            }
        } catch (Exception $e) {
            $respons = new Template(false, 'Register Gagal', $e->getMessage());
            return $respons->response();
        }
    }

    public function verifyMahasiswa($request)
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
        // verifikasi mahasiswa
        DB::table('tb_mahasiswa')
            ->where('email', $request->email)
            ->update([
                'email_verify_at' => now(),
            ]);
        // hapus token yang sudah digunakan
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();
        DB::commit();
        return response()->json([
            'status'  => true,
            'message' => 'Email verified successfully, Silahkan Login',
        ]);
    }

    public function updateMahasiswa($request, int $id)
    {
        // memanggil fungsi updateMahasiswa untuk mengupdate data mahasiswa
        try {
            $data_mahasiswa = DB::table('tb_mahasiswa')->where('id', $id)->first();
            if (! $data_mahasiswa) {
                $respons = new Template(false, 'Data Gagal di update', 'Data mahasiswa tidak ditemukan');
                return $respons->response();
            }
            $data_validasi = $request->validate([
                "prodi_id" => "required|integer",
                "nama"     => "required|string|max:255",
                "nim"      => "required|string|max:255|regex:/^[A-Za-z0-9]+$/",
                "email"    => "required|string|max:255",
                "username" => "required|string|max:255",
                "password" => "required|string|min:8|max:255",
                "role"     => "required|string|max:255",
            ]);
            if ($data_validasi) {
                DB::beginTransaction();
                $data_pemilihan = DB::table('tb_pemilihan')->where('id', $data_mahasiswa->pemilih_id)->update([
                    'nama' => $request->nama,
                    'tipe' => "Mahasiswa",
                ]);
                if ($data_pemilihan === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data pemilihan tidak ditemukan');
                    return $respons->response();
                }
                $data_mahasiswa_update = DB::table('tb_mahasiswa')->where('id', $id)->update([
                    'prodi_id'   => $request->prodi_id,
                    'pemilih_id' => $data_mahasiswa->pemilih_id,
                    'nama'       => $request->nama,
                    'nim'        => $request->nim,
                    'email'      => $request->email,
                    'username'   => $request->username,
                    'password'   => Hash::make($request->password),
                    'role'       => $request->role,
                    'api_key'    => Str::random(60),
                ]);
                if ($data_mahasiswa_update === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data mahasiswa tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                $data_mahasiswa_by_id = DB::table('tb_mahasiswa')->where('id', $id)->first();
                $respons              = new Template(true, 'Data Berhasil di update', $data_mahasiswa_by_id);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di update', $data_validasi);
                return $respons->response();
            }
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di tambahkan', $e->getMessage());
            return $respons->response();
        }
    }

    public function deleteMahasiswa(int $id)
    {
        // memanggil fungsi deleteMahasiswa untuk menghapus data mahasiswa
        try {
            $data_mahasiswa = DB::table('tb_mahasiswa')->where('id', $id)->first();
            if (! $data_mahasiswa) {
                $respons = new Template(false, 'Data Gagal di hapus', 'Data mahasiswa tidak ditemukan');
                return $respons->response();
            }
            DB::beginTransaction();
            // tahapan menghapus data mahasiswa
            // ambil semua data nama dan id di data mahasiswa
            // hapus data pemilihan
            $mahasiswaIds   = DB::table('tb_mahasiswa')->where('id', $id)->pluck('id');
            $mahasiswaNamas = DB::table('tb_mahasiswa')->where('id', $id)->pluck('nama');
            if ($mahasiswaIds->isNotEmpty()) {
                // ambil id pemilihan berdasarkan nama mahasiswa
                $pemilihanIds = DB::table('tb_pemilihan')->whereIn('nama', $mahasiswaNamas)->pluck('id');
                if ($pemilihanIds->isNotEmpty()) {
                    // ambil semua id pemilihan ruangan
                    $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanIds)->pluck('id');
                    if ($pemilihanRuanganIds->isNotEmpty()) {
                        // hapus data kalender sistem berdasarkan id pemilihan ruangan
                        DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                    }
                    // hapus data pemilihan ruangan
                    DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanIds)->delete();
                    // hapus data pemilihan
                    DB::table('tb_pemilihan')->whereIn('id', $pemilihanIds)->delete();
                }
                // hapus data mahasiswa
                DB::table('tb_mahasiswa')->whereIn('id', $mahasiswaIds)->delete();
            }
            DB::commit();
            $respons = new Template(true, 'Data Berhasil di hapus', $data_mahasiswa);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di hapus', $e->getMessage());
            return $respons->response();
        }
    }
    public function totalMahasiswa()
    {
        try {
            // menghitung total mahasiswa
            $data = DB::table('tb_mahasiswa')->count();
            // mengembalikan response json apabila berhasil menampilkan jumlah data mahasiswa
            $respons = new Template(true, 'Total Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
