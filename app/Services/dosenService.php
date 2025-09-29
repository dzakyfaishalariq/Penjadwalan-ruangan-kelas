<?php
namespace App\Services;

use App\ApiTemplate\Template;
use App\Mail\AutenticationDosen;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class dosenService
{
    public function getDosen(int $paginate = 10)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
        try {
            // mengambil semua data dosen dengan relasi pada table prodi
            $data_dosen = DB::table('tb_dosen')
                ->join("tb_prodi", "tb_dosen.prodi_id", "=", "tb_prodi.id")
                ->join("tb_pemilihan", "tb_dosen.pemilih_id", "=", "tb_pemilihan.id")
                ->select(
                    'tb_dosen.id as dosen_id',
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.fakultas_id as fakultas_id',
                    'tb_prodi.nama_prodi as prodi',
                    'tb_pemilihan.id as pemilihan_id',
                    'tb_pemilihan.nama as nama_pemilih',
                    'tb_pemilihan.tipe as tipe_pemilih',
                    'tb_dosen.id as dosen_id',
                    'tb_dosen.nama as dosen',
                    'tb_dosen.nip as nip',
                    'tb_dosen.email as email',
                    'tb_dosen.password as password',
                    'tb_dosen.api_key as api_key',
                )->paginate($paginate);
            // chek apakah data dosen tidak ditemukan
            if ($data_dosen == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data dosen tidak ditemukan');
                return $respons->response();
            } else {
                $respons = new Template(true, 'Data Berhasil di ambil', $data_dosen);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getDosenAll()
    {
        try {
            // mengambil semua data dosen
            $data_dosen = DB::table('tb_dosen')->get();
            // chek apakah data dosen tidak ditemukan
            if ($data_dosen == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data dosen tidak ditemukan');
                return $respons->response();
            } else {
                $respons = new Template(true, 'Data Berhasil di ambil', $data_dosen);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getDosenById(int $id)
    {
        try {
            // mengambil data dosen berdasarkan ID dosen
            $data_dosen_by_id = DB::table('tb_dosen')->where('tb_dosen.id', $id)
                ->join('tb_prodi', 'tb_dosen.prodi_id', '=', 'tb_prodi.id')
                ->join('tb_pemilihan', 'tb_dosen.pemilih_id', '=', 'tb_pemilihan.id')
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.fakultas_id as fakultas_id',
                    'tb_prodi.nama_prodi as prodi',
                    'tb_pemilihan.id as pemilihan_id',
                    'tb_pemilihan.nama as nama_pemilih',
                    'tb_pemilihan.tipe as tipe_pemilih',
                    'tb_dosen.id as dosen_id',
                    'tb_dosen.pemilih_id as pemilih_id',
                    'tb_dosen.nama as dosen',
                    'tb_dosen.nip as nip',
                    'tb_dosen.email as email',
                    'tb_dosen.username as username',
                    'tb_dosen.password as password',
                    'tb_dosen.api_key as api_key',
                    'tb_dosen.role as role',
                )->first();
            // chek apakah data dosen tidak ditemukan
            if ($data_dosen_by_id == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data dosen tidak ditemukan');
                return $respons->response();
            } else {
                $respons = new Template(true, 'Data Berhasil di ambil', $data_dosen_by_id);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function createDosen($request)
    {
        try {
            // validasi data yang di inputkan di request
            $kondisi = $request->validate([
                'prodi_id' => 'required|integer',
                'nama'     => 'required|string|max:255',
                'nip'      => 'required|string|max:255',
                'email'    => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:8|max:255',
                'role'     => 'required|string|max:255',
            ]);
            // cek kondisi apabila memenuhi data dosen akan di tambahkan kedalam table dosen jika gagal akan mengembalikan response json dengan pesan error
            if ($kondisi) {
                DB::beginTransaction();
                // menambahkan data pemilihan
                $tambah_data_pemilih = DB::table('tb_pemilihan')
                    ->insertGetId([
                        'nama' => $request->nama,
                        'tipe' => "Dosen",
                    ]);
                // menambahkan data dosen
                $tambah_data_dosen = DB::table('tb_dosen')->insertGetId([
                    'prodi_id'   => $request->prodi_id,
                    'pemilih_id' => $tambah_data_pemilih,
                    'nama'       => $request->nama,
                    'nip'        => $request->nip,
                    'email'      => $request->email,
                    'username'   => $request->username,
                    'password'   => Hash::make($request->password),
                    'api_key'    => Str::random(60),
                    'role'       => $request->role,
                    'created_at' => now(),
                    'created_up' => now(),
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
                Mail::to($kondisi['email'])->send(new AutenticationDosen($token, $kondisi['email']));
                DB::commit();
                $data_pemilih_hasil_tambahan = DB::table('tb_pemilihan')->where('id', $tambah_data_pemilih)->first();
                $data_dosen_hasil_tambahan   = DB::table('tb_dosen')->where('id', $tambah_data_dosen)->first();
                $data                        = [
                    'pemilihan'  => $data_pemilih_hasil_tambahan,
                    'data_dosen' => $data_dosen_hasil_tambahan,
                ];
                $respons = new Template(true, 'Data Berhasil di tambahkan silahkan cek email anda untuk verifikasi', $data);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di tambahkan', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function verifyDosen($request)
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
        // verifikasi dosen
        DB::table('tb_dosen')
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
            'message' => 'Data Berhasil di verifikasi',
        ], 200);
    }
    public function updateDosen($request, int $id)
    {
        // fungsi untuk mengupdate data dosen
        try {
            $data_dosen = DB::table('tb_dosen')->where('id', $id)->first();
            // cek apakah data ada berdasarkan id
            if ($data_dosen == null) {
                $respons = new Template(false, 'Data Gagal di update', 'Data dosen tidak ditemukan');
                return $respons->response();
            }
            // melakukan validasi data yang akan di inputkan
            $kondisi = $request->validate([
                'prodi_id' => 'required|integer',
                // 'tipe'     => 'required|string|max:255',
                'nama'     => 'required|string|max:255',
                'nip'      => 'required|string|max:255|regex:/^[A-Za-z0-9]+$/',
                'email'    => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:8|max:255',
                'role'     => 'required|string|max:255',
            ]);
            // cek kondisi apabila memenuhi data dosen akan di tambahkan kedalam table dosen jika gagal akan mengembalikan response json dengan pesan error
            if ($kondisi) {
                DB::beginTransaction();
                //update data pemilihan
                $data_pemilihan = DB::table('tb_pemilihan')->where('id', $data_dosen->pemilih_id)->update([
                    'nama' => $request->nama,
                    'tipe' => "Dosen",
                ]);
                if ($data_pemilihan === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data pemilihan tidak ditemukan');
                    return $respons->response();
                }
                $data_dosen_update = DB::table('tb_dosen')->where('id', $id)->update([
                    'prodi_id'   => $request->prodi_id,
                    'pemilih_id' => $data_dosen->pemilih_id,
                    'nama'       => $request->nama,
                    'nip'        => $request->nip,
                    'email'      => $request->email,
                    'username'   => $request->username,
                    'password'   => Hash::make($request->password),
                    'role'       => $request->role,
                    'created_up' => now(),
                    // 'api_key'    => Str::random(60),
                ]);
                if ($data_dosen_update === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data dosen tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                $data_pemilih_hasil_update = DB::table('tb_pemilihan')->where('id', $data_dosen->pemilih_id)->first();
                $data_dosen_hasil_update   = DB::table('tb_dosen')->where('id', $id)->first();
                $data                      = [
                    'pemilihan'  => $data_pemilih_hasil_update,
                    'data_dosen' => $data_dosen_hasil_update,
                ];
                $respons = new Template(true, 'Data Berhasil di update', $data);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di update', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function deleteDosen(int $id)
    {
        // fungsi untuk menghapus data dosen
        try {
            $data_dosen = DB::table('tb_dosen')->where('id', $id)->first();
            // cek apakah data ada berdasarkan id
            if ($data_dosen == null) {
                $respons = new Template(false, 'Data Gagal di delete', 'Data dosen tidak ditemukan');
                return $respons->response();
            }
            DB::beginTransaction();
            // tahapan menghapus data dosen
            // ambil semua data nama dosen
            $dosenIds   = DB::table('tb_dosen')->where('id', $id)->pluck('id');
            $dosenNamas = DB::table('tb_dosen')->whereIn('id', $dosenIds)->pluck('nama');
            if ($dosenIds->isNotEmpty()) {
                // hapus data pemilihan
                // ambil id pemilihan
                $pemilihanIds = DB::table('tb_pemilihan')->whereIn('nama', $dosenNamas)->pluck('id');
                if ($pemilihanIds->isNotEmpty()) {
                    // ambil id pemilihan ruangan
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
                // hapus jadwal matakuliah
                $jadwalMatakuliahIds = DB::table('tb_jadwal_matakuliah')->where('dosen_id', $id)->pluck('id');
                if ($jadwalMatakuliahIds->isNotEmpty()) {
                    // ambil id data pemilihan raungan
                    $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->pluck('id');
                    if ($pemilihanRuanganIds->isNotEmpty()) {
                        // hapus data kalender sistem berdasarkan id pemilihan ruangan
                        DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                    }
                    // hapus data pemilihan ruangan
                    DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->delete();
                    // hapus data jadwal matakuliah
                    DB::table('tb_jadwal_matakuliah')->whereIn('id', $jadwalMatakuliahIds)->delete();
                }
                // hapus data dosen
                DB::table('tb_dosen')->whereIn('id', $dosenIds)->delete();
            }
            DB::commit();
            $respons = new Template(true, 'Data Berhasil di delete', $data_dosen);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function totalDosen()
    {
        try {
            // menghitung total dosen
            $data = DB::table('tb_dosen')->count();
            // mengembalikan response json apabila berhasil menampilkan jumlah data dosen
            $respons = new Template(true, 'Total Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
