<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class jadwalMatakuliahSevice
{
    public function getJadwalMatakuliah(int $paginate = 10)
    {
        try {
            // validasi paginate apabila kurang dari 0
            $paginate = $paginate > 0 ? $paginate : 10;
            // batasi paginate di range 100
            $paginate = max(1, (min($paginate, 100)));
            // inisialsisasi select data
            $select_data = [
                'tjm.id as jadwal_matakuliah_id',
                'tm.id as matakuliah_id',
                'tm.nama_matakuliah',
                'tm.sks',
                'td.nama as nama_dosen',
                'td.nip as nip_dosen',
                'td.email as email_dosen',
                'tjm.hari',
                'tjm.jam_mulai',
                'tjm.jam_selesai',
            ];
            // memanggil semua data jadwal matakuliah
            $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah as tjm')
                ->join('tb_matakuliah as tm', 'tjm.matakuliah_id', '=', 'tm.id')
                ->join('tb_dosen as td', 'tjm.dosen_id', '=', 'td.id')
                ->select($select_data)
                ->paginate($paginate);
            // mengembalikan response json apabila data sudah siap ditampilkan
            $response = new Template(true, 'Data Berhasil di ambil', $data_jadwal_matakuliah);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function getJadwalMatakuliahById(int $id)
    {
        try {
            // inisialisasi select data
            $select_data = [
                'tjm.id as jadwal_matakuliah_id',
                'tm.id as matakuliah_id',
                'tm.nama_matakuliah',
                'tm.sks',
                'td.nama as nama_dosen',
                'td.nip as nip_dosen',
                'td.email as email_dosen',
                'tjm.hari',
                'tjm.jam_mulai',
                'tjm.jam_selesai',
            ];
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // memanggil data jadwal matakuliah berdasarkan id
            $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah as tjm')
                ->join('tb_matakuliah as tm', 'tjm.matakuliah_id', '=', 'tm.id')
                ->join('tb_dosen as td', 'tjm.dosen_id', '=', 'td.id')
                ->where('tjm.id', '=', $id)
                ->select($select_data)
                ->first();
            // mengembalikan response json apabila data sudah siap ditampilkan
            $response = new Template(true, 'Data Berhasil di ambil', $data_jadwal_matakuliah);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function updateJadwalMatakuliah($request, int $id)
    {
        try {
            // validasi id tidak boleh kurang dari 0
            $id = $id > 0 ? $id : 0;
            // validasi $request
            $kondisi = $request->validate([
                "matakuliah_id" => "required|integer|min:1",
                "dosen_id"      => "required|integer|min:1",
                "hari"          => "required|string|max:255",
                "jam_mulai"     => "required|string|max:255",
                "jam_selesai"   => "required|string|max:255",
            ]);
            if ($kondisi) {
                DB::beginTransaction();
                // melakukan update data jadwal matakuliah
                $data_update_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')
                    ->where('id', '=', $id)
                    ->update($request->all());
                // cek apakah data jadwal matakuliah ada atau berhasil di perbarui
                if ($data_update_jadwal_matakuliah == 0) {
                    DB::rollBack();
                    $response = new Template(false, 'Data Gagal di perbarui', 'Data jadwal matakuliah tidak ditemukan atau sudah pernah di perbarui');
                    return $response->response();
                }
                DB::commit();
                // mengembalikan response json apabila data jadwal matakuliah berhasil di perbarui
                $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->where('id', '=', $id)->first();
                $response               = new Template(true, 'Data Berhasil di perbarui', $data_jadwal_matakuliah);
                return $response->response();
            } else {
                $response = new Template(false, 'Data Gagal di perbarui', 'Data jadwal matakuliah tidak lolos validasi' . json_encode($kondisi));
                return $response->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function addJawalMatakuliah($request)
    {
        try {
            // validasi $request
            $kondisi = $request->validate([
                "matakuliah_id" => "required|integer|min:1",
                "dosen_id"      => "required|integer|min:1",
                "hari"          => "required|string|max:255",
                "jam_mulai"     => "required|string|max:255",
                "jam_selesai"   => "required|string|max:255",
            ]);
            if ($kondisi) {
                DB::beginTransaction();
                // melakukan insert data jadwal matakuliah
                $id_data_update = DB::table('tb_jadwal_matakuliah')->insertGetId($request->all());
                DB::commit();
                // mengembalikan response json apabila data jadwal matakuliah berhasil di perbarui
                $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->where('id', '=', $id_data_update)->first();
                $response               = new Template(true, 'Data Berhasil di perbarui', $data_jadwal_matakuliah);
                return $response->response();
            } else {
                $response = new Template(false, 'Data Gagal di perbarui', 'Data jadwal matakuliah tidak lolos validasi' . json_encode($kondisi));
                return $response->response();
            }
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
    public function deleteJadwalMatakuliah(int $id)
    {
        // validasi id apabila id dibawah nilai 0
        $id = $id > 0 ? $id : 0;
        try {
            // cek apakah data jadwal matakuliah ada
            $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->where('id', $id)->exists();
            if (!$data_jadwal_matakuliah) {
                $response = new Template(false, 'Data Gagal di hapus', 'Data jadwal matakuliah tidak ditemukan');
                return $response->response();
            }
            // melakukan delete data jadwal matakuliah
            DB::beginTransaction();
            // tahapan menghapus data jadwal matakuliah
            // ambil data id jadwal matakuliah
            $jadwalMatakuliahIds = DB::table('tb_jadwal_matakuliah')->where('id', $id)->pluck('id');
            if ($jadwalMatakuliahIds->isNotEmpty()) {
                // ambil data id pemilihan ruangan
                $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->pluck('id');
                if ($pemilihanRuanganIds->isNotEmpty()) {
                    // hapus data kalender sistem
                    DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                }
                // hapus data pemilihan ruangan
                DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->delete();
                // hapus data jadwal matakuliah
                DB::table('tb_jadwal_matakuliah')->whereIn('id', $jadwalMatakuliahIds)->delete();
            }
            DB::commit();
            // mengembalikan response json apabila data jadwal matakuliah berhasil di hapus
            $response = new Template(true, 'Data Berhasil di hapus', $data_jadwal_matakuliah);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }

    public function totalJadwalMatakuliah()
    {
        try {
            // memanggil semua data jadwal matakuliah
            $data_jadwal_matakuliah = DB::table('tb_jadwal_matakuliah')->count();
            // mengembalikan response json apabila data sudah siap ditampilkan
            $response = new Template(true, 'Data Berhasil di ambil', $data_jadwal_matakuliah);
            return $response->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $response = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $response->response();
        }
    }
}
