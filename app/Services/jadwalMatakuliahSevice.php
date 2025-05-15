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
}
