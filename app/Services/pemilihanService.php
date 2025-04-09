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
    public function getPemilihanById($id)
    {
        try {
            // melakukan pengecekan id pemilihan apabila ada atau tidak
            if (DB::table('tb_pemilihan')->where('id', $id)->first() == null) {
                $respons = new Template(false, 'Data Gagal di ambil', 'Data pemilihan tidak ditemukan');
                return $respons->response();
            }
            // mengambil data pemilihan berdasarkan id
            $data_pemilihan_by_id = DB::table('tb_pemilihan')->where('id', $id)->first();
            $respons              = new Template(true, 'Data Berhasil di ambil', $data_pemilihan_by_id);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function createPemilihan($request)
    {
        try {
            // melakukan pengecekan parameter nama dan tipe pemilihan yang wajib di isi dan bertipe string
            $cek_validasi = $request->validate([
                'nama' => 'required|string|max:255',
                'tipe' => 'required|string',
            ]);
            if (! $cek_validasi) {
                $respons = new Template(false, 'Data Gagal di tambahkan karena ada kesalahan saat menginput', $cek_validasi->errors());
                return $respons->response();
            }
            // melakukan pengecekan apabila data pemilihan sudah ada
            if (DB::table('tb_pemilihan')->where('nama', $request->nama)->first() != null) {
                $respons = new Template(false, 'Data Gagal di tambahkan', 'Data pemilihan sudah ada');
                return $respons->response();
            }
            // melakukan insert data pemilihan kedalam table pemilihan
            DB::beginTransaction();
            $tambah_data_pemilihan = DB::table('tb_pemilihan')->insertGetId([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
            ]);
            DB::commit();
            $data_pemilihan_hasil_tambahan = DB::table('tb_pemilihan')->where('id', $tambah_data_pemilihan)->first();
            $respons                       = new Template(true, 'Data Berhasil di tambahkan', $data_pemilihan_hasil_tambahan);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di tambahkan', $e->getMessage());
            return $respons->response();
        }
    }
    public function updatePemilihan($request, $id)
    {
        try {
            // melakukan pengecekan id pemilihan apabila ada atau tidak
            if (DB::table('tb_pemilihan')->where('id', $id)->first() == null) {
                $respons = new Template(false, 'Data Gagal di ubah', 'Data pemilihan tidak ditemukan');
                return $respons->response();
            }
            // melakukan pengecekan parameter nama dan tipe pemilihan yang wajib di isi dan bertipe string
            $cek_validasi = $request->validate([
                'nama' => 'required|string|max:255',
                'tipe' => 'required|string',
            ]);
            if (! $cek_validasi) {
                $respons = new Template(false, 'Data Gagal di ubah karena ada kesalahan saat menginput', $cek_validasi->errors());
                return $respons->response();
            }
            // melakukan update data pemilihan berdasarkan id
            DB::beginTransaction();
            $data_update_pemilihan = DB::table('tb_pemilihan')->where('id', $id)->update([
                'nama' => $request->nama,
                'tipe' => $request->tipe,
            ]);
            if ($data_update_pemilihan === 0) {
                DB::rollBack();
                $respons = new Template(false, 'Data Gagal di ubah', 'Data pemilihan tidak ditemukan');
                return $respons->response();
            }
            DB::commit();
            $data_pemilihan_hasil_update = DB::table('tb_pemilihan')->where('id', $id)->first();
            $respons                     = new Template(true, 'Data Berhasil di ubah', $data_pemilihan_hasil_update);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di ubah', $e->getMessage());
            return $respons->response();
        }
    }
    public function deletePemilihan($id)
    {
        try {
            // melakukan pengecekan id pemilihan apabila ada atau tidak
            if (DB::table('tb_pemilihan')->where('id', $id)->first() == null) {
                $respons = new Template(false, 'Data Gagal di hapus', 'Data pemilihan tidak ditemukan');
                return $respons->response();
            }
            // menghapus data pemilihan berdasarkan id
            DB::table('tb_pemilihan')->where('id', $id)->delete();
            $respons = new Template(true, 'Data Berhasil di hapus', null);
            return $respons->response();
        } catch (Exception $e) {
            $respons = new Template(false, 'Data Gagal di hapus', $e->getMessage());
            return $respons->response();
        }
    }   
}
