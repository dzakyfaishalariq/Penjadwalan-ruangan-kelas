<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class mahasiswaService
{
    public function getMahasiswa($paginate)
    {
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
    public function getMahasiswaById($id)
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
                $id_pemilihan = DB::table('tb_pemilihan')->insertGetId([
                    'nama' => $request->nama,
                    'tipe' => "Mahasiswa",
                ]);
                $id_mahasiswa = DB::table('tb_mahasiswa')->insertGetId([
                    'prodi_id'   => $request->prodi_id,
                    'pemilih_id' => $id_pemilihan,
                    'nama'       => $request->nama,
                    'nim'        => $request->nim,
                    'email'      => $request->email,
                    'username'   => $request->username,
                    'password'   => Hash::make($request->password),
                    'role'       => $request->role,
                    'api_key'    => Str::random(60),
                ]);
                DB::commit();
                $data_mahasiswa_by_id = DB::table('tb_mahasiswa')->where('id', $id_mahasiswa)->first();
                $respons              = new Template(true, 'Data Berhasil di tambahkan', $data_mahasiswa_by_id);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di tambahkan', $data_validasi);
                return $respons->response();
            }
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di tambahkan', $e->getMessage());
            return $respons->response();
        }
    }
}
