<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class pemilihanRuanganService
{

    public function getPemilihanRuangan(int $paginate = 10)
    {
        // validatet paginate apabila nilai kurang dari 0
        $paginate = $paginate > 0 ? $paginate : 10;
        // batasi paginate di range 100
        $paginate = max(1, (min($paginate, 100)));

        try {
            // inisialisasi select data
            $data_list = [
                "tpr.id as pemilihan_ruangan_id",
                "tpr.ruangan_id",
                "tr.prodi_id",
                "tr.nama_ruangan",
                "tr.kapasitas",
                "tr.status as status_ruangan",
                "tpr.jadwal_id",
                "tjm.matakuliah_id",
                "tjm.dosen_id",
                "tjm.hari",
                "tjm.jam_mulai",
                "tjm.jam_selesai",
                "tpr.pemilih_id",
                "tp.nama as nama_pemilihan",
                "tp.tipe as tipe_pemilihan",
                "tpr.tanggal_pemilihan",
                "tpr.status_pemilihan",
                "tpr.konfirmasi_kehadiran",
            ];
            // memanggil semua data pemilihan ruangan
            $data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan as tpr')
                ->join("tb_ruangan as tr", "tpr.ruangan_id", "=", "tr.id")
                ->join("tb_jadwal_matakuliah as tjm", "tpr.jadwal_id", "=", "tjm.id")
                ->join("tb_pemilihan as tp", "tpr.pemilih_id", "=", "tp.id")
                ->select($data_list)
                ->orderBy('pemilihan_ruangan_id', 'desc')
                ->paginate($paginate);
            // mengembalikan response json
            $response = new Template(true, 'Data Berhasil di ambil', $data_pemilihan_ruangan);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function getPemilihanRuanganById(int $id)
    {
        try {
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // inisialisasi select data
            $data_list = [
                "tpr.id as pemilihan_ruangan_id",
                "tpr.ruangan_id",
                "tr.prodi_id",
                "tr.nama_ruangan",
                "tr.kapasitas",
                "tr.status as status_ruangan",
                "tpr.jadwal_id",
                "tjm.matakuliah_id",
                "tjm.dosen_id",
                "tjm.hari",
                "tjm.jam_mulai",
                "tjm.jam_selesai",
                "tpr.pemilih_id",
                "tp.nama as nama_pemilihan",
                "tp.tipe as tipe_pemilihan",
                "tpr.tanggal_pemilihan",
                "tpr.status_pemilihan",
                "tpr.konfirmasi_kehadiran",
            ];
            // memanggil semua data pemilihan ruangan
            $data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan as tpr')
                ->join("tb_ruangan as tr", "tpr.ruangan_id", "=", "tr.id")
                ->join("tb_jadwal_matakuliah as tjm", "tpr.jadwal_id", "=", "tjm.id")
                ->join("tb_pemilihan as tp", "tpr.pemilih_id", "=", "tp.id")
                ->select($data_list)
                ->where("tpr.id", "=", $id)
                ->first();
            // mengembalikan response json
            $response = new Template(true, 'Data Berhasil di ambil', $data_pemilihan_ruangan);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }

    public function updatePemilihanRungan($request, int $id)
    {
        try {
            //validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // validasi $requset
            $kondisi = $request->validate([
                "ruangan_id"           => "required|integer",
                "jadwal_id"            => "required|integer",
                "pemilih_id"           => "required|integer",
                "tanggal_pemilihan"    => "required|date",
                "status_pemilihan"     => "required|boolean",
                "konfirmasi_kehadiran" => "required|string|in:Hadir,Tidak Hadir,Pending",
            ]);
            // memanggil data jadwal matakuliah berdasarkan id untuk mengisi data kalender sistem
            $jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->where('id', $request->jadwal_id)->first();
            // kondisi validasi
            if ($kondisi) {
                DB::beginTransaction();
                // update data pemilihan ruangan
                $update_data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan')
                    ->where('id', $id)
                    ->update($request->all());
                // cek apakah data pemilihan ruangan ada atau berhasil di perbarui
                if ($update_data_pemilihan_ruangan === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data tidak ditemukan');
                    return $respons->response();
                }
                // update data kalender sistem
                $update_data_kalender_sistem = DB::table('tb_kalender_sistem')->where('pemilihan_ruangan_id', $id)->update([
                    "tanggal"       => $request->tanggal_pemilihan,
                    "waktu_mulai"   => $jadwal_matakuliah->jam_mulai,
                    "waktu_selesai" => $jadwal_matakuliah->jam_selesai,
                ]);
                // cek apakah data kalender sistem ada atau berhasil di perbarui
                if ($update_data_kalender_sistem === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                // mengembalikan response json data apabila data pemilihan ruangan berhasil di perbarui
                $data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan')->where('id', $id)->first();
                $respons                = new Template(true, 'Data Berhasil di update', $data_pemilihan_ruangan);
                return $respons->response();
            } else {
                // mengembalikan response json data apabila data pemilihan ruangan gagal di perbarui
                $respons = new Template(false, 'Data Gagal di update', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function addPemilihanRungan($request)
    {
        try {
            // validasi $requset
            $kondisi = $request->validate([
                "ruangan_id"           => "required|integer",
                "jadwal_id"            => "required|integer",
                "pemilih_id"           => "required|integer",
                "tanggal_pemilihan"    => "required|date",
                "status_pemilihan"     => "required|boolean",
                "konfirmasi_kehadiran" => "required|string|in:Hadir,Tidak Hadir,Pending",
            ]);
            // memanggil data jadwal matakuliah berdasarkan id untuk mengisi data kalender sistem
            $jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->where('id', $request->jadwal_id)->first();
            // kondisi validasi
            if ($kondisi) {
                DB::beginTransaction();
                // tambah data pemilihan ruangan
                $id_data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan')->insertGetId($request->all());
                // mengisi data kalender sistem
                $id_data_kalender_sistem = DB::table('tb_kalender_sistem')->insertGetId([
                    "pemilihan_ruangan_id" => $id_data_pemilihan_ruangan,
                    "tanggal"              => $request->tanggal_pemilihan,
                    "waktu_mulai"          => $jadwal_matakuliah->jam_mulai,
                    "waktu_selesai"        => $jadwal_matakuliah->jam_selesai,
                ]);
                DB::commit();
                // mengembalikan response json data apabila data pemilihan dan kalender ruangan berhasil di tambahkan
                $data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan')->where('id', $id_data_pemilihan_ruangan)->first();
                $data_kalender_sistem   = DB::table('tb_kalender_sistem')->where('id', $id_data_kalender_sistem)->first();
                $data                   = [
                    "pemilihan_ruangan" => $data_pemilihan_ruangan,
                    "kalender_sistem"   => $data_kalender_sistem,
                ];
                $respons = new Template(true, 'Data Berhasil di tambahkan', $data);
                return $respons->response();
            } else {
                // mengembalikan response json data apabila data pemilihan ruangan gagal di tambahkan
                $respons = new Template(false, 'Data Gagal di tambahkan', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function deletePemilihanRuangan(int $id)
    {
        // validasi id apabila id dibawah nilai 0
        $id = $id > 0 ? $id : 0;
        try {
            // cek apakah data pemilihan ruangan ada
            $data_pemilihan_ruangan = DB::table('tb_pemilihan_ruangan')->where('id', $id)->first();
            if (! $data_pemilihan_ruangan) {
                $response = new Template(false, 'Data Gagal di hapus', 'Data pemilihan ruangan tidak ditemukan');
                return $response->response();
            }
            // melakukan delete data pemilihan ruangan
            DB::beginTransaction();
            // tahapan menghapus data pemilihan ruangan
            // ambil semua data id pemilihan ruangan
            $pemilihanRunganIds = DB::table('tb_pemilihan_ruangan')->where('id', $id)->pluck('id');
            if ($pemilihanRunganIds->isNotEmpty()) {
                // tahapan menghapus data kalender sistem
                $kalenderSistemIds = DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRunganIds)->pluck('id');
                if ($kalenderSistemIds->isNotEmpty()) {
                    // hapus data kalender sistem
                    DB::table('tb_kalender_sistem')->whereIn('id', $kalenderSistemIds)->delete();
                }
                // hapus data pemilihan ruangan
                DB::table('tb_pemilihan_ruangan')->whereIn('id', $pemilihanRunganIds)->delete();
            }
            DB::commit();
            // mengembalikan response json apabila data pemilihan ruangan berhasil di hapus
            $response = new Template(true, 'Data Berhasil di hapus', $data_pemilihan_ruangan);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
}
