<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * prodiService bertujuan untuk menyimpan fungsi-fungsi yang berkaitan dengan prodi dan langsung berhubungan dengan database table prodi dan mengurangi beban di controller
 */
class prodiService
{
    public function getProdi()
    {
        // fungsi memanggil semua data prodi dengan relasi pada table fakultas
        $data = DB::table('tb_prodi')
            ->join('tb_fakultas', 'tb_prodi.fakultas_id', '=', 'tb_fakultas.id')
            ->select('tb_fakultas.nama_fakultas', 'tb_prodi.nama_prodi')
            ->paginate(5);
        return response()->json($data);
    }
}
