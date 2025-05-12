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
}
