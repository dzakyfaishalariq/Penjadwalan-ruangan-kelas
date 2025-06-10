<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

class kalenderSistemService
{
    public function getKalenderSistem(int $paginate = 10)
    {
        // akses data kalender sistem
        try {
            $kalenderSistem = DB::table('tb_kalender_sistem as tks')
                ->join('tb_pemilihan_ruangan as tpr', 'tks.pemilihan_ruangan_id', '=', 'tpr.id')
                ->paginate($paginate);
            $respons = new Template(true, 'Data Berhasil di ambil', $kalenderSistem);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function getKalenderSistemById(int $id)
    {
        // akses data kalender sistem berdasarkan id
        try {
            $kalenderSistem = DB::table('tb_kalender_sistem as tks')
                ->join('tb_pemilihan_ruangan as tpr', 'tks.pemilihan_ruangan_id', '=', 'tpr.id')
                ->where('tks.id', $id)
                ->first();
            $respons = new Template(true, 'Data Berhasil di ambil', $kalenderSistem);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
