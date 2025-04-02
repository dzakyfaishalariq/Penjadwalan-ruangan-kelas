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
    public function createFakultas($request)
    {
        /**
         * Menambahkan data fakultas dengan validasi dan parameter inputan POST
         * - nama_fakultas (required) denga type data string
         *
         */
        try {
            // melakukan pengecekan parameter nama_fakultas yang wajib di isi dan bertipe string
            $request->validate([
                'nama_fakultas' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // jika pengecekan gagal maka akan mengembalikan response json error
            return response()->json([
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->errors(),
            ], 404);
        }

        // melakukan query builder untuk menambahkan data fakultas
        DB::table('tb_fakultas')
            ->insert([
                'nama_fakultas' => $request->nama_fakultas,
            ]);

        // mengembalikan response json berupa pesan fakultas berhasil di tambahkan dan menampilkan data fakultas yang suda ditambahkan.
        return response()->json([
            'message' => 'Fakultas berhasil ditambahkan',
            'data'    => DB::table('tb_fakultas')->where('id', DB::getPdo()->lastInsertId())->first(),
        ]);
    }
    public function updateFakultas($request, $id)
    {
        try {
            // melakukan pengecekan parameter nama_fakultas yang wajib di isi dan bertipe string
            $request->validate([
                'nama_fakultas' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // jika pengecekan gagal maka akan mengembalikan response json error
            return response()->json([
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->errors(),
            ], 404);
        }
        // melakukan query builder untuk memperbarui data fakultas
        DB::table('tb_fakultas')->where('id', $id)->update([
            'nama_fakultas' => $request->nama_fakultas,
        ]);
        // mengembalikan response json berupa pesan fakultas berhasil di perbarui dan menampilkan data fakultas yang suda di perbarui.
        return response()->json([
            'message' => 'Fakultas berhasil diubah',
            'data'    => DB::table('tb_fakultas')->where('id', $id)->first(),
        ]);

    }
}
