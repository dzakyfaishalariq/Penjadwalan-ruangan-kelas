<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class fakultasService
{
    // mengatur logic dari fakultas controller agar tidak terbebani dengan pemanggilan logika yang berhubungan dengan database secara langsung.
    public function getFakultas()
    {
        // memanggil data fakultas denga paginate 5
        return DB::table('tb_fakultas')->paginate(5);
    }
    public function getFakultasById($id)
    {
        // memanggil data fakultas berdasarkan id
        return DB::table('tb_fakultas')->where('id', $id)->first();
    }
    public function getFakultasByName($name)
    {
        /**
         * memanggil data fakultas berdasarkan name
         * yang mana param $name dapat disi bebas
         */
        return DB::table('tb_fakultas')
            ->whereRaw('LOWER(nama_fakultas) LIKE ?', ['%' . strtolower($name) . '%'])
            ->paginate(5);
    }
}
