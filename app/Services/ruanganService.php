<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class ruanganService
{

    public function getRuangan(int $paginate)
    {
        // batasi jika nilai paginate kurang dari 0
        $paginate = $paginate > 0 ? $paginate : 10;
        // validasi paginate
        $paginate = max(1, (min($paginate, 100)));
        try {
            // buat array select data
            $select_data = [
                'tr.prodi_id',
                'tp.nama_prodi',
                'tr.id as ruangan_id',
                'tr.nama_ruangan',
                'tr.kapasitas',
                'tr.status',
            ];
            // memanggil semua data ruangan
            $data_ruangan = DB::table('tb_ruangan as tr')
                ->join('tb_prodi as tp', 'tr.prodi_id', '=', 'tp.id')
                ->select($select_data)
                ->orderBy('ruangan_id', 'desc')
                ->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data_ruangan);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getRuanganById(int $id)
    {
        // validasi id tidak boleh kurang dari 0
        $id = $id > 0 ? $id : 0;
        try {
            // buat array select data
            $select_data = [
                'tr.prodi_id',
                'tp.nama_prodi',
                'tr.id as ruangan_id',
                'tr.nama_ruangan',
                'tr.kapasitas',
                'tr.status',
            ];
            //cek apakah id tidak ditemukan
            $data = DB::table('tb_ruangan')->where('id', $id)->first();
            if ($data == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data tidak ditemukan');
                return $respons->response();
            }
            // memanggil data ruangan berdasarkan id
            $data_ruangan = DB::table('tb_ruangan as tr')
                ->join('tb_prodi as tp', 'tr.prodi_id', '=', 'tp.id')
                ->select($select_data)
                ->where('tr.id', $id)
                ->first();
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data_ruangan);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function updateRuangan($request, int $id)
    {
        try {
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // validasi data yang dikirim terdiri dari 4 parameter
            $kondisi = $request->validate([
                'prodi_id'     => 'required|integer',
                'nama_ruangan' => 'required|string|max:255',
                'kapasitas'    => 'required|integer',
                'status'       => 'required|boolean',
            ]);
            if ($kondisi) {
                DB::beginTransaction();
                $update_data_ruangan = DB::table('tb_ruangan')
                    ->where('id', $id)
                    ->update($request->all());
                if ($update_data_ruangan === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                $data_ruangan = DB::table('tb_ruangan')->where('id', $id)->first();
                $respons      = new Template(true, 'Data Berhasil di update', $data_ruangan);
                return $respons->response();
            }
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function createRuangan($request)
    {
        try {
            $kondisi = $request->validate([
                'prodi_id'     => 'required|integer',
                'nama_ruangan' => 'required|string|max:255',
                'kapasitas'    => 'required|integer',
                'status'       => 'required|boolean',
            ]);
            if ($kondisi) {
                DB::beginTransaction();
                $tambah_data = DB::table('tb_ruangan')->insertGetId($request->all());
                DB::commit();
                $data_ruangan_tambah = DB::table('tb_ruangan')->where('id', $tambah_data)->first();
                $respons             = new Template(true, 'Data Berhasil di tambahkan', $data_ruangan_tambah);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di tambahkan', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function deleteRuangan(int $id)
    {
        try {
            // jika id tidak ditemukan maka akan mengembalikan response json dengan pesan error
            $id   = (int) $id;
            $id   = $id > 0 ? $id : 0;
            $data = DB::table('tb_ruangan')->where('id', $id)->exists();
            if (!$data) {
                $respons = new Template(false, 'Data Gagal di hapus', 'Data tidak ditemukan');
                return $respons->response();
            }
            // menghapus data prodi berdasarkan id
            DB::beginTransaction();
            // tahapan menghapus data ruangan
            // ambil data ruangan berdasarkan id
            $ruanganIds = DB::table('tb_ruangan')->where('id', $id)->pluck('id');
            if ($ruanganIds->isNotEmpty()) {
                // ambil semua id pemilihan ruangan
                $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('ruangan_id', $ruanganIds)->pluck('id');
                if ($pemilihanRuanganIds->isNotEmpty()) {
                    // hapus data kalender sistem berdasarkan id dari pemilihan ruangan
                    DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                }
                // hapus data pemilihan ruangan
                DB::table('tb_pemilihan_ruangan')->whereIn('ruangan_id', $ruanganIds)->delete();
                // hapus data ruangan
                DB::table('tb_ruangan')->whereIn('id', $ruanganIds)->delete();
            }
            DB::commit();
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di hapus', $data);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di hapus', $e->getMessage());
            return $respons->response();
        }
    }
    public function totalRuangan()
    {
        try {
            // menghitung total prodi
            $data = DB::table('tb_ruangan')->count();
            // mengembalikan response json apabila berhasil menampilkan jumlah data prodi
            $respons = new Template(true, 'Total Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
