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
        // fungsi memanggil semua data prodi
        $data = DB::table('tb_prodi')->paginate(5);
        // mengembalikan response json
        return response()->json($data);
    }
    public function getProdiToFakultas()
    {
        // fungsi memanggil semua data prodi dengan relasi pada table fakultas
        $data = DB::table('tb_prodi')
            ->join('tb_fakultas', 'tb_prodi.fakultas_id', '=', 'tb_fakultas.id')
            ->select('tb_prodi.id', 'tb_fakultas.nama_fakultas', 'tb_prodi.nama_prodi')
            ->paginate(5);
        // mengembalikan response json
        return response()->json($data);
    }

    public function getFakultasToProdi()
    {
        // memanggil semua data fakultas dengan relasi pada table prodi yang mana tiap fakultas akan menampilkan data prodi di dalamnya
        $data_fakultas_to_prodi = DB::table('tb_fakultas')
            ->leftJoin('tb_prodi', 'tb_fakultas.id', '=', 'tb_prodi.fakultas_id')
            ->select('tb_fakultas.id as fakultas_id', 'tb_fakultas.nama_fakultas', 'tb_prodi.id as prodi_id', 'tb_prodi.nama_prodi')
            ->get();
        // melakukan pengelompokan data prodi berdasarkan fakultas
        $result = [];
        foreach ($data_fakultas_to_prodi as $item) {
            $fakultas_id = $item->fakultas_id;
            // melakukan pengecekan apakah fakultas sudah ada di array
            if (! isset($result[$fakultas_id])) {
                $result[$fakultas_id] = [
                    'fakultas_id'   => $fakultas_id,
                    'nama_fakultas' => $item->nama_fakultas,
                    'prodi'         => [],
                ];
            }
            // melakukan pengecekan apakah prodi sudah ada di array
            if ($item->prodi_id !== null) {
                $result[$fakultas_id]['prodi'][] = [
                    "id"         => $item->prodi_id,
                    "nama_prodi" => $item->nama_prodi,
                ];
            }
        }
        // mengembalikan response json
        return response()->json([
            "data_relasi_fakultas_to_prodi" => array_values($result),
        ]);
    }

    public function getProdiById($id)
    {
        // memanggil data prodi berdasarkan id prodi
        $data_prodi = DB::table('tb_prodi')
            ->join('tb_fakultas', 'tb_prodi.fakultas_id', '=', 'tb_fakultas.id')
            ->where('tb_prodi.id', $id)
            ->select(
                'tb_prodi.id as prodi_id',
                'tb_prodi.nama_prodi',
                'tb_fakultas.id as fakultas_id',
                'tb_fakultas.nama_fakultas',
            )
            ->first();

        // mengembalikan response json
        return response()->json([
            'name_data' => "Data prodi berdasarkan id : " . $id,
            'data'      => [
                'id'       => $data_prodi->prodi_id,
                'prodi'    => $data_prodi->nama_prodi,
                'fakultas' => [
                    'id'            => $data_prodi->fakultas_id,
                    'nama_fakultas' => $data_prodi->nama_fakultas,
                ],
            ],
        ]);
    }

}
