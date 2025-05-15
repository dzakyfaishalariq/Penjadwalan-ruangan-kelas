<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class matakuliahService
{
    // fungsi untuk memanggil semua data matakuliah
    public function getMatkul(int $paginate = 10)
    {
        // validasi nilai paginate harus lebih dari 0
        $paginate = $paginate > 0 ? $paginate : 10;
        // batasi paginate di range 100
        $paginate = max(1, (min($paginate, 100)));
        try {
            // cek apabilai data matakuliah kosong
            if (DB::table('tb_matakuliah')->count() == 0) {
                $respons = new Template(false, 'Data Matakuliah Kosong', 'Data Matakuliah Kosong');
                return $respons->response();
            }
            // inisialisasi select data
            $select_data = [
                "tm.prodi_id",
                "tp.nama_prodi",
                "tm.id as matakuliah_id",
                "tm.nama_matakuliah",
                "tm.sks",
            ];
            // memanggil semua data matakuliah
            $data_matakuliah = DB::table('tb_matakuliah as tm')
                ->join('tb_prodi as tp', 'tm.prodi_id', '=', 'tp.id')
                ->select($select_data)
                ->orderBy('matakuliah_id', 'desc')
                ->paginate($paginate);
            // mengembalikan response json apabila data ditemukan
            $respons = new Template(true, 'Data Berhasil di ambil', $data_matakuliah);
            return $respons->response();
        } catch (Exception $e) {
            //mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    // fungsi untuk memanggil data matakuliah berdasarkan id
    public function getMatkulById(int $id)
    {
        //validasi id tidak boleh kurang dari 0
        $id = $id > 0 ? $id : 0;
        try {
            // inisialisasi select data
            $select_data = [
                "tm.prodi_id",
                "tp.nama_prodi",
                "tm.id as matakuliah_id",
                "tm.nama_matakuliah",
                "tm.sks",
            ];
            // memanggil semua data matakuliah berdasarkan id
            $data_matakuliah = DB::table('tb_matakuliah as tm')->where('tm.id', $id)
                ->join('tb_prodi as tp', 'tm.prodi_id', '=', 'tp.id')
                ->select($select_data)
                ->first();
            // cek apakah data nya ada atau tidak
            if ($data_matakuliah == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data tidak ditemukan');
                return $respons->response();
            }
            // mengembalikan response json apabila data ditemukan
            $respons = new Template(true, 'Data Berhasil di ambil', $data_matakuliah);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function updateMatkul($request, int $id)
    {
        try {
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // validasi data yang di kirim dari request
            $kondisi = $request->validate([
                'prodi_id'        => 'required|integer',
                'nama_matakuliah' => 'required|string|max:255',
                'sks'             => 'required|integer',
            ]);
            // lakukan pengecekan apakah data valid
            if ($kondisi) {
                DB::beginTransaction();
                // update data matakuliah
                $update_data_matakuliah = DB::table('tb_matakuliah')
                    ->where('id', $id)
                    ->update($request->all());
                // cek apakah data matakuliah ada atau berhasil di perbarui
                if ($update_data_matakuliah === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                // mengembalikan response json data apabila data matakuliah berhasil di perbarui
                $data_matakuliah = DB::table('tb_matakuliah')->where('id', $id)->first();
                $respons         = new Template(true, 'Data Berhasil di update', $data_matakuliah);
                return $respons->response();
            } else {
                // mengembalikan response json data apabila data gagal validasi
                $respons = new Template(false, 'Data Gagal di update', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // kembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function addMatkul($request)
    {
        try {
            // validasi data yang di kirim dari request
            $kondisi = $request->validate([
                "prodi_id"        => "required|integer",
                "nama_matakuliah" => "required|string|max:255",
                "sks"             => "required|integer",
            ]);
            // lakukan pengecekan apakah data valid
            if ($kondisi) {
                // memulai menambahkan data
                DB::beginTransaction();
                $tambah_data = DB::table('tb_matakuliah')->insertGetId($request->all());
                DB::commit();
                // menampilkan response json apabila data sudah di tambahkan
                $data_matakuliah_tambah = DB::table('tb_matakuliah')->where('id', $tambah_data)->first();
                $respons                = new Template(true, 'Data Berhasil di tambahkan', $data_matakuliah_tambah);
                return $respons->response();
            } else {
                // memberikan response json apabila data tidak valid
                $respons = new Template(false, 'Data Gagal di tambahkan', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function deleteMatkul(int $id)
    {
        try {
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // cek apakah data matakuliah ada
            $data_matakuliah = DB::table('tb_matakuliah')->where('id', $id)->first();
            if ($data_matakuliah == null) {
                $respons = new Template(false, 'Data Gagal di hapus', 'Data tidak ditemukan');
                return $respons->response();
            }
            // menghapus data matakuliah.
            DB::table('tb_matakuliah')->where('id', $id)->delete();
            // kembalikan response json data yang sudah di hapus.
            $respons = new Template(true, 'Data Berhasil di hapus', $data_matakuliah);
            return $respons->response();
        } catch (Exception $e) {
            // kembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function totalMatkul(){
        try {
            // menghitung total prodi
            $data = DB::table('tb_matakuliah')->count();
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
