<?php
namespace App\Services;

use App\ApiTemplate\Template;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class fakultasService
{
    // mengatur logic dari fakultas controller agar tidak terbebani dengan pemanggilan logika yang berhubungan dengan database secara langsung.
    public function getFakultas(int $paginate = 10)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
        // ubah variabel paginate ke integer
        try {
            $paginate = (int) $paginate;
            // memanggil data fakultas denga paginate 5
            $data_get_fakultas = DB::table('tb_fakultas')->paginate($paginate);
            // mengembalikan response json
            $template_respons = new Template(true, 'Data Berhasil di ambil', $data_get_fakultas);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $template_respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $template_respons->response();
        }
    }
    public function getFakultasById(int $id)
    {
        try {
            // memanggil data fakultas berdasarkan id
            // return DB::table('tb_fakultas')->where('id', $id)->first();
            $data_get_fakultas_by_id = DB::table('tb_fakultas')->where('id', $id)->first();
            // mengembalikan response json apabila data berhasil di ambil
            $template_respons = new Template(true, 'Data Berhasil di ambil dari id', $data_get_fakultas_by_id);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $template_respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $template_respons->response();
        }
    }
    public function getFakultasByName(int $paginate = 10, string $name)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
        /**
         * memanggil data fakultas berdasarkan name
         * yang mana param $name dapat disi bebas
         */
        try {
            $paginate                  = (int) $paginate;
            $data_get_fakultas_by_name = DB::table('tb_fakultas')
                ->whereRaw('LOWER(nama_fakultas) LIKE ?', ['%' . strtolower($name) . '%'])
                ->paginate($paginate);
            // mengembalikan response json
            $template_respons = new Template(true, 'Data Berhasil di ambil', $data_get_fakultas_by_name);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $template_respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $template_respons->response();
        }
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
            $data = [
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->errors(),
            ];
            $template_respons = new Template(false, 'Data Gagal di kirimkan', $data);
            return $template_respons->response();
        }

        // melakukan query builder untuk menambahkan data fakultas
        try {
            DB::beginTransaction();
            $insert_data = DB::table('tb_fakultas')
                ->insertGetId([
                    'nama_fakultas' => $request->nama_fakultas,
                ]);
            DB::commit();
            // mengembalikan response json berupa pesan fakultas berhasil di tambahkan dan menampilkan data fakultas yang suda ditambahkan.
            $data = [
                'message' => 'Fakultas berhasil ditambahkan',
                'data'    => DB::table('tb_fakultas')->where('id', $insert_data)->first(),
            ];
            $template_respons = new Template(true, 'Data Berhasil di kirimkan', $data);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $data = [
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->getMessage(),
            ];
            $template_respons = new Template(false, 'Data Gagal di kirimkan', $data);
            return $template_respons->response();
        }
    }
    public function updateFakultas($request, int $id)
    {
        try {
            // melakukan pengecekan parameter nama_fakultas yang wajib di isi dan bertipe string
            $request->validate([
                'nama_fakultas' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // jika pengecekan gagal maka akan mengembalikan response json error
            $data = [
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->errors(),
            ];
            $template_respons = new Template(false, 'Data Gagal di kirimkan', $data);
            return $template_respons->response();
        }

        try {
            // cek apakah data fakultas dengan id tersebut ada
            if (DB::table('tb_fakultas')->where('id', $id)->first() == null) {
                // mengembalikan response json apabila data fakultas tidak ditemukan
                $data = [
                    'message' => 'Data fakultas tidak ditemukan',
                    'data'    => null,
                ];
                $template_respons = new Template(false, 'Data tidak ditemukan', $data);
                return $template_respons->response();
            }
            // melakukan query builder untuk memperbarui data fakultas
            DB::beginTransaction();
            $update_data = DB::table('tb_fakultas')->where('id', $id)->update([
                'nama_fakultas' => $request->nama_fakultas,
            ]);
            // melakukan pengecekan apakah data berhasil di perbarui
            if ($update_data === 0) {
                DB::rollBack();
                $respon = [
                    'message' => 'Fakultas gagal di perbarui',
                    'errors'  => 'Data dengan id ' . $id . ' tidak ditemukan',
                ];
                $template_respons = new Template(false, 'Data Gagal di kirimkan', $respon);
                return $template_respons->response();
            }
            DB::commit();
            // mengembalikan response json berupa pesan fakultas berhasil di perbarui dan menampilkan data fakultas yang suda di perbarui.
            $data = [
                'message' => 'Fakultas berhasil diubah',
                'data'    => DB::table('tb_fakultas')->where('id', $id)->first(),
            ];
            $template_respons = new Template(true, 'Data Berhasil di kirimkan', $data);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $data = [
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->getMessage(),
            ];
            $template_respons = new Template(false, 'Data Gagal di kirimkan', $data);
            return $template_respons->response();
        }
        // melakukan query builder untuk memperbarui data fakultas

    }
    public function deleteFakultas(int $id)
    {
        try {
            if (DB::table('tb_fakultas')->where('id', $id)->first() == null) {
                // mengembalikan response json apabila data fakultas tidak ditemukan
                $data = [
                    'message' => 'Data fakultas tidak ditemukan',
                    'data'    => null,
                ];
                $template_respons = new Template(false, 'Data tidak ditemukan', $data);
                return $template_respons->response();
            }
            // melakukan query builder untuk menghapus data fakultas
            DB::table('tb_fakultas')->where('id', $id)->delete();
            // mengembalikan response json berupa pesan fakultas berhasil di hapus
            $data = [
                'message' => 'Fakultas berhasil dihapus',
                'data'    => null,
            ];
            $template_respons = new Template(true, 'Data Berhasil di kirimkan', $data);
            return $template_respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $data = [
                'message' => 'Data gagal di kirimkan',
                'errors'  => $e->getMessage(),
            ];
            $template_respons = new Template(false, 'Data Gagal di kirimkan', $data);
            return $template_respons->response();
        }
    }
}
