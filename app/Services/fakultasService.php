<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
    public function cretateFakultas($request)
    {
        /**
         * Menambahkan data fakultas dengan validasi dan parameter inputan POST
         * - nama_fakultas (required) denga type data string
         *
         */
        try {
            $request->validate([
                'nama_fakultas' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'data gagal di kirimkan',
                'errors'  => $e->errors(),
            ]);
        }

        DB::table('tb_fakultas')
            ->insert([
                'nama_fakultas' => $request->nama_fakultas,
            ]);
        return response()->json([
            'message' => 'Fakultas berhasil ditambahkan',
            'data'    => DB::table('tb_fakultas')->where('id', DB::getPdo()->lastInsertId())->first(),
        ]);
    }
}
