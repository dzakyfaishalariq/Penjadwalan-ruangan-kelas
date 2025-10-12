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
    public function getProdi($request, int $paginate = 10)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
        // insisialisasi pencarian
        $serch = $request->input('serch', '');
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // fungsi memanggil semua data prodi
            $data = DB::table('tb_prodi as tp');
            // lakukan pencarian data
            if ($serch != '') {
                $data = $data->where('tp.nama_prodi', 'like', '%' . $serch . '%');
            }
            $data = $data->join('tb_fakultas as tf', 'tp.fakultas_id', '=', 'tf.id')
                ->select(
                    'tp.id',
                    'tp.nama_prodi',
                    'tf.id as fakultas_id',
                    'tf.nama_fakultas'
                )
                ->orderBy('tp.id', 'desc')
                ->paginate($paginate);
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getProdiAll()
    {
        try {
            // fungsi memanggil semua data prodi
            $data = DB::table('tb_prodi')->get();
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
    public function getProdiToFakultas(int $paginate = 10)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
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

    public function getFakultasToProdi(int $paginate = 10)
    {
        try {
            // ubah variabel paginate ke integer
            $paginate = (int) $paginate;
            // lakukan pengecekan apakah nilai paginate bernilai > 0
            $paginate = $paginate > 0 ? $paginate : 10;
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

    public function getProdiById(int $id)
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
    public function getProdiByName(int $paginate = 10, string $name)
    {
        // lakukan pengecekan apakah nilai pagination negatif atau tidak
        $paginate = $paginate > 0 ? $paginate : 10;
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
                DB::beginTransaction();
                $tambah_data = DB::table('tb_prodi')->insertGetId([
                    'fakultas_id' => $request->fakultas_id,
                    'nama_prodi'  => $request->nama_prodi,
                ]);
                DB::commit();
                $data_prodi_tambah = DB::table('tb_prodi')->where('id', $tambah_data)->first();
                $respons           = new Template(true, 'Data Berhasil di tambahkan', $data_prodi_tambah);
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
    public function updateProdi($request, int $id)
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
                DB::beginTransaction();
                $data_upadate = DB::table('tb_prodi')->where('id', $id)->update([
                    'fakultas_id' => $request->fakultas_id,
                    'nama_prodi'  => $request->nama_prodi,
                ]);
                if ($data_upadate === 0) {
                    DB::rollBack();
                    $respons = new Template(false, 'Data Gagal di update', 'Data tidak ditemukan');
                    return $respons->response();
                }
                DB::commit();
                $data    = DB::table('tb_prodi')->where('id', $id)->first();
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
    public function deleteProdi(int $id)
    {
        try {
            // jika id tidak ditemukan maka akan mengembalikan response json dengan pesan error
            $id   = (int) $id;
            $data = DB::table('tb_prodi')->where('id', $id)->first();
            if ($data == null) {
                $respons = new Template(false, 'Data Gagal di hapus', 'Data tidak ditemukan');
                return $respons->response();
            }
            // menghapus data prodi dan relasinya berdasarkan id
            DB::beginTransaction();
            // hapus data matakuliah
            $matakuliahIds = DB::table('tb_matakuliah')->where('prodi_id', $id)->pluck('id');
            if ($matakuliahIds->isNotEmpty()) {
                // tahapan hapus data jadwal matakuliah
                $jadwalMatakuliahIds = DB::table('tb_jadwal_matakuliah')->whereIn('matakuliah_id', $matakuliahIds)->pluck('id');
                if ($jadwalMatakuliahIds->isNotEmpty()) {
                    // ambil semua id pemilihan ruangan
                    $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->pluck('id');
                    if ($pemilihanRuanganIds->isNotEmpty()) {
                        // hapus data kalender sistem berdasarkan id dari pemilihan ruangan
                        DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                    }
                    // hapus data pemilihan ruangan
                    DB::table('tb_pemilihan_ruangan')->whereIn('jadwal_id', $jadwalMatakuliahIds)->delete();
                    // hapus data jadwal matakuliah
                    DB::table('tb_jadwal_matakuliah')->whereIn('matakuliah_id', $matakuliahIds)->delete();
                }
                DB::table('tb_matakuliah')->whereIn('id', $matakuliahIds)->delete();
            }
            // tahapan hapus data ruangan
            $ruanganIds = DB::table('tb_ruangan')->where('prodi_id', $id)->pluck('id');
            if ($matakuliahIds->isNotEmpty()) {
                // ambil semua id pemilihan ruangan
                $pemilihanRunganIds = DB::table('tb_pemilihan_ruangan')->whereIn('ruangan_id', $ruanganIds)->pluck('id');
                if ($pemilihanRunganIds->isNotEmpty()) {
                    // hapus data kalender sistem
                    DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRunganIds)->delete();
                }
                // hapus data pemuilahan ruangan
                DB::table('tb_pemilihan_ruangan')->whereIn('ruangan_id', $ruanganIds)->delete();
                // hapus data ruangan
                DB::table('tb_ruangan')->whereIn('id', $ruanganIds)->delete();
            }
            // tahapan hapus data dosen
            $dosenIds   = DB::table('tb_dosen')->where('prodi_id', $id)->pluck('id');
            $dosenNames = DB::table('tb_dosen')->where('prodi_id', $id)->pluck('nama');
            if ($dosenIds->isNotEmpty()) {
                // ambil semua id pemilihan dosen
                $pemilihanDosenIds = DB::table('tb_pemilihan')->whereIn('nama', $dosenNames)->pluck('id');
                if ($pemilihanDosenIds->isNotEmpty()) {
                    // ambil id dari data pemilihan ruangan
                    $pemilihanRuanganIds = DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanDosenIds)->pluck('id');
                    if ($pemilihanRuanganIds->isNotEmpty()) {
                        // hapus data kalender sistem
                        DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihanRuanganIds)->delete();
                    }
                    // hapus data pemilihan ruangan
                    DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanDosenIds)->delete();
                    // hapus data pemilihan dosen
                    DB::table('tb_pemilihan')->whereIn('id', $pemilihanDosenIds)->delete();
                }
                // hapus data jadwal matakuliah
                DB::table('tb_jadwal_matakuliah')->whereIn('dosen_id', $dosenIds)->delete();
                // hapus data dosen
                DB::table('tb_dosen')->whereIn('id', $dosenIds)->delete();
            }
            // tahap hapus data mahasiswa
            $mahasiswaIds   = DB::table('tb_mahasiswa')->where('prodi_id', $id)->pluck('id');
            $mahasiswaNames = DB::table('tb_mahasiswa')->where('prodi_id', $id)->pluck('nama');
            if ($mahasiswaIds->isNotEmpty()) {
                // ambil semua id pemilihan mahasiswa
                $pemilihanMahasiswaIds = DB::table('tb_pemilihan')->whereIn('nama', $mahasiswaNames)->pluck('id');
                if ($pemilihanMahasiswaIds->isNotEmpty()) {
                    // ambil id dari data pemilihan ruangan
                    $pemilihRuagnanIds = DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanMahasiswaIds)->pluck('id');
                    if ($pemilihRuagnanIds->isNotEmpty()) {
                        // hapus data kalender sistem
                        DB::table('tb_kalender_sistem')->whereIn('pemilihan_ruangan_id', $pemilihRuagnanIds)->delete();
                    }
                    // hapus data pemilihan ruangan
                    DB::table('tb_pemilihan_ruangan')->whereIn('pemilih_id', $pemilihanMahasiswaIds)->delete();
                    // hapus data pemilihan mahasiswa
                    DB::table('tb_pemilihan')->whereIn('id', $pemilihanMahasiswaIds)->delete();
                }
                // hapus data mahasiswa
                DB::table('tb_mahasiswa')->whereIn('id', $mahasiswaIds)->delete();
            }
            DB::table('tb_prodi')->where('id', $id)->delete();
            DB::commit();
            // mengembalikan response json
            $respons = new Template(true, 'Data Berhasil di hapus', $data);
            return $respons->response();
        } catch (Exception $e) {
            //throw $e;
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di hapus', $e->getMessage());
            return $respons->response();
        }
    }
    public function totalProdi()
    {
        try {
            // menghitung total prodi
            $data = DB::table('tb_prodi')->count();
            // mengembalikan response json apabila berhasil menampilkan jumlah data prodi
            $respons = new Template(true, 'Total Berhasil di ambil', $data);
            return $respons->response();
        } catch (Exception $e) {
            // mengembalikan response json apabila terjadi error
            $respons = new Template(false, 'Data Gagal di ambil', $e->getMessage());
            return $respons->response();
        }
    }
}
