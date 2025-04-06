<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * prodiService bertujuan untuk menyimpan fungsi-fungsi yang berkaitan dengan prodi dan langsung berhubungan dengan database table prodi dan mengurangi beban di controller
 */
class prodiService
{
    public function getProdi($paginate)
    {
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // fungsi memanggil semua data prodi
            $data = DB::table('tb_prodi')->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getProdiToFakultas($paginate)
    {
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // fungsi memanggil semua data prodi dengan relasi pada table fakultas
            $data = DB::table('tb_prodi')
                ->join('tb_fakultas', 'tb_prodi.fakultas_id', '=', 'tb_fakultas.id')
                ->select('tb_prodi.id', 'tb_fakultas.nama_fakultas', 'tb_prodi.nama_prodi')
                ->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function getFakultasToProdi($paginate)
    {
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // memanggil semua data fakultas dengan relasi pada table prodi yang mana tiap fakultas akan menampilkan data prodi di dalamnya
            $data_fakultas_to_prodi = DB::table('tb_fakultas')
                ->leftJoin('tb_prodi', 'tb_fakultas.id', '=', 'tb_prodi.fakultas_id')
                ->select('tb_fakultas.id as fakultas_id', 'tb_fakultas.nama_fakultas', 'tb_prodi.id as prodi_id', 'tb_prodi.nama_prodi')
                ->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data_fakultas_to_prodi);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }

    public function getProdiById($id)
    {
        // memanggil data prodi berdasarkan id prodi yang mana data dalam prodi dapat berelasi pada table fakultas dan data fakultas dapat di panggil ke dalam data prodi
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
        $data = [
            'name_data' => "Data prodi berdasarkan id : " . $id,
            'data'      => [
                'id'       => $data_prodi->prodi_id,
                'prodi'    => $data_prodi->nama_prodi,
                'fakultas' => [
                    'id'            => $data_prodi->fakultas_id,
                    'nama_fakultas' => $data_prodi->nama_fakultas,
                ],
            ],
        ];
        $respons = new Template(true, 'Data Berhasil di ambil', $data);
        return $respons->response();
    }
    public function getProdiByName($paginate, $name)
    {
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // memanggil data prodi berdasarkan name prodi yang mana data dalam prodi dapat berelasi pada table fakultas dan data fakultas dapat di panggil ke dalam data prodi
            $data_prodi = DB::table('tb_prodi')
                ->join('tb_fakultas', 'tb_prodi.fakultas_id', '=', 'tb_fakultas.id')
                ->whereRaw('LOWER(tb_prodi.nama_prodi) like ?', ['%' . $name . '%'])
                ->select(
                    'tb_prodi.id as prodi_id',
                    'tb_prodi.nama_prodi',
                    'tb_fakultas.id as fakultas_id',
                    'tb_fakultas.nama_fakultas',
                )
                ->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data_prodi);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function createProdi($request)
    {
        // membuat code untuk menambahkan data prodi dengan validasi dan parameter inputan POST yang berlerasi dengan fakultas.
        try {
            // validasi data yang dikirim teridiri dari dua parameter fakultas_id (integer) dan nama_prodi (string)
            $kondisi = $request->validate([
                'fakultas_id' => 'required|integer',
                'nama_prodi'  => 'required|string',
            ]);
            // melakukan perkondisian apabila kondisi memenuhi data prodi akan ditambahkan kedalam table prodi jika gagal akan mengembalikan response json dengan pesan error.
            if ($kondisi) {
                DB::table('tb_prodi')->insert([
                    'fakultas_id' => $request->fakultas_id,
                    'nama_prodi'  => $request->nama_prodi,
                ]);
                $data = [
                    'message' => 'Data Berhasil di tambahkan',
                    'data'    => [
                        'fakultas_id' => $request->fakultas_id,
                        'nama_prodi'  => $request->nama_prodi,
                    ],
                ];
                $respons = new Template(true, 'Data Berhasil di tambahkan', $data);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di tambahkan', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di tambahkan', $e->getMessage());
            return $respons->response();
        }
    }
    public function updateProdi($request, $id)
    {
        try {
            // validasi data yang dikirim teridiri dari tiga parameter yang mana dua dari varieabel request dan id (integer)
            $id      = (int) $id;
            $kondisi = $request->validate([
                'fakultas_id' => 'required|integer',
                'nama_prodi'  => 'required|string|max:255',
            ]);
            // melakukan perkondisian apabila kondisi memenuhi data prodi akan di update kedalam table prodi jika gagal akan mengembalikan response json dengan pesan error.
            if ($kondisi) {
                DB::table('tb_prodi')->where('id', $id)->update([
                    'fakultas_id' => $request->fakultas_id,
                    'nama_prodi'  => $request->nama_prodi,
                ]);
                $data = [
                    'fakultas_id' => $request->fakultas_id,
                    'nama_prodi'  => $request->nama_prodi,
                ];
                $respons = new Template(true, 'Data Berhasil di update', $data);
                return $respons->response();
            } else {
                $respons = new Template(false, 'Data Gagal di update', $kondisi);
                return $respons->response();
            }
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di update', $e->getMessage());
            return $respons->response();
        }
    }
    public function deleteProdi($id)
    {
        try {
            // menghapus data prodi berdasarkan id
            DB::table('tb_prodi')->where('id', $id)->delete();
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di hapus', null);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di hapus', $e->getMessage());
            return $respons->response();
        }
    }
}
