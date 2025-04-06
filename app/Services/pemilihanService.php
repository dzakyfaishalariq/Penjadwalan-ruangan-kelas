<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class pemilihanService
{
    public function getPemilihan($paginate)
    {
        try {
            // mengambil data tabel pemilihan dan menampilkan nya dengan batasan paginate
            $data_pemiliha = DB::table('tb_pemilihan')
                ->paginate($paginate);
            $respons = new Template(true, 'Data Berhasil di ambil', $data_pemiliha);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
