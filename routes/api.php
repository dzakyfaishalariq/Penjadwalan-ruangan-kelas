<?php

use App\Http\Controllers\dosenController;
use App\Http\Controllers\fakultasController;
use App\Http\Controllers\jadwalMatakuliahController;
use App\Http\Controllers\kalenderSistemController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\mahassiwaController;
use App\Http\Controllers\matakuliahController;
use App\Http\Controllers\pemilihanController;
use App\Http\Controllers\pemilihanRuangaController;
use App\Http\Controllers\prodiController;
use App\Http\Controllers\ruaganController;
use Illuminate\Support\Facades\Route;

// Router LOGIN
Route::middleware('api')->post('/mahasiswa_login', [loginController::class, 'loginMahasiswa']);
Route::middleware('api')->post('/dosen_login', [loginController::class, 'loginDosen']);
// hak akses mahasiswa
Route::middleware('authMahasiswa.api')->group(function () {
    // akses prodi
    Route::middleware('api')->get('/mahasiswa_akses_prodi', [prodiController::class, 'getProdiAll']);
    // akses mahasiswa sesuai id yang login
    Route::middleware('api')->get('/mahasiswa_akses_by_id/{id}', [mahassiwaController::class, 'getMahasiswaById']);
    // rute untuk melakukan update data mahasiswa berdasarkan ID bertujuan untuk pembaruan data mahasiswa oleh siswa sendiri
    Route::middleware('api')->put('/mahasiswa_akses/update/{id}', [mahassiwaController::class, 'updateMahasiswa']);
    // akses ruangan
    Route::middleware('api')->get('/mahasiswa_akses_ruangan/{paginate}', [ruaganController::class, 'getRuangan']);
    // akses rungan berdasarkan id
    Route::middleware('api')->get('/mahasiswa_akses_ruangan_by_id/{id}', [ruaganController::class, 'getRuanganById']);
    // akses total ruangan
    Route::middleware('api')->get('/mahasiswa_akses_total/ruangan', [ruaganController::class, 'totalRuangan']);
    // total ruangan terpakai
    Route::middleware('api')->get('/mahasiswa_akses_total/ruangan_terpakai', [ruaganController::class, 'totalRuanganTerpakai']);
    // memboking ruangan
    Route::middleware('api')->post('/pemilihan_ruangan_mahasiswa_akses/booking', [pemilihanRuangaController::class, 'addPemilihanRungan']);
    // konfirmasi pemilihan ruangan
    Route::middleware('api')->put('/pemilihan_ruangan_mahasiswa_akses/konfirmasi/{id}', [pemilihanRuangaController::class, 'konfirmasiKehadiranRuangan']);
    // batalkan pemilihan ruangan
    Route::middleware('api')->put('/pemilihan_ruangan_mahasiswa_akses/batalkan/{id}', [pemilihanRuangaController::class, 'batalkanPemilihanRuangan']);
    // menampilkan semua history pemilihan ruangan
    Route::middleware('api')->get('/pemilihan_ruangan_mahasiswa_akses/{paginate}', [pemilihanRuangaController::class, 'getPemilihanRuangan']);
    // menampilkan semua pemilihan ruangan semuanya untuk kalender
    Route::middleware('api')->get('/pemilihan_ruangan_mahasiswa_akses_semua', [pemilihanRuangaController::class, 'getPemilihanRuanganSemua']);
    // menampilkan pemilihan ruangan berdasarkan pemilihan id
    Route::middleware('api')->get('/pemilihan_ruangan_mahasiswa_akses_by_id/{id}/{paginate}', [pemilihanRuangaController::class, 'getPemilihanRuanganByPemilih']);
    // menampilkan semua nama ruangan
    Route::middleware('api')->get('/mahasiswa_akses_nama_ruangan', [ruaganController::class, 'getNamaRuangan']);
    // akses jadwal matakuliah
    Route::middleware('api')->get('/mahasiswa_akses_jadwal/{paginate}', [jadwalMatakuliahController::class, 'getJadwalMatakuliah']);
    // akses jadwal matakuliah tersedia
    Route::middleware('api')->get('/mahasiswa_akses_matkul_tersedia', [jadwalMatakuliahController::class, 'getJadwalMatakuliahTersedia']);
    // akses jadwal matakuliah berdasarkan id
    Route::middleware('api')->get('/mahasiswa_akses_jadwal_by_id/{id}', [jadwalMatakuliahController::class, 'getJadwalMatakuliahById']);

    // akses kalender sistem
    Route::middleware('api')->get('/mahasiswa_akses_kalender{paginate}', [kalenderSistemController::class, 'getKalenderSistem']);
    // akses kalender berdasarkan id
    Route::middleware('api')->get('/mahasiswa_akses_kalender/{id}', [kalenderSistemController::class, 'getKalenderSistemById']);
    // akses matakuliah
    Route::middleware('api')->get('/mahasiswa_akses_matkul/{paginate}', [matakuliahController::class, 'getMatkul']);
});

// hak akses dosen
Route::middleware('authDosen.api')->group(function () {
    // akses prodi
    Route::middleware('api')->get('/akses_prodi_untuk_dosen', [prodiController::class, 'getProdiAll']);
    // akses semua data dosen
    Route::middleware('api')->get('/data_akses_dosen', [dosenController::class, 'getDosenAll']);
    // akses dosen sesuai id yang login
    Route::middleware('api')->get('/dosen_akses_by_id/{id}', [dosenController::class, 'getDosenById']);
    // update data dosen
    Route::middleware('api')->put('/dosen_akses/update/{id}', [dosenController::class, 'updateDosen']);
    // rute untuk melakukan update data dosen berdasarkan ID bertujuan untuk pembaruan data dosen oleh dosen sendiri
    Route::middleware('api')->post('/dosen_akses/add', [dosenController::class, 'createDosen']);
    // akses ruangan
    Route::middleware('api')->get('/dosen_akses_ruangan/{paginate}', [ruaganController::class, 'getRuangan']);
    // akses hasil pemilihan rungan berdasarkan pemilih id
    Route::middleware('api')->get('/pemilih_ruangan_dosen_akses_by_id/{id}/{paginate}', [pemilihanRuangaController::class, 'getPemilihanRuanganByPemilih']);
    // akses total ruangan
    Route::middleware('api')->get('/dosen_akses_total/ruangan', [ruaganController::class, 'totalRuangan']);
    // akses total ruangan terpakai
    Route::middleware('api')->get('/dosen_akses_total/ruangan_terpakai', [ruaganController::class, 'totalRuanganTerpakai']);
    // akses nama ruangan
    Route::middleware('api')->get('/dosen_akses_nama_ruangan', [ruaganController::class, 'getNamaRuangan']);
    // akses jadwal matakuliah tersedia
    Route::middleware('api')->get('/dosen_akses_matkul_tersedia', [jadwalMatakuliahController::class, 'getJadwalMatakuliahTersedia']);
    // akses jadwal matakuliah
    Route::middleware('api')->get('/dosen_akses_jadwal/{paginate}', [jadwalMatakuliahController::class, 'getJadwalMatakuliah']);
    // memboking ruangan
    Route::middleware('api')->post('/pemilihan_ruangan_dosen_akses/booking', [pemilihanRuangaController::class, 'addPemilihanRungan']);
    // akses ruangan berdasarkan id
    Route::middleware('api')->get('/dosen_akses_ruangan_by_id/{id}', [ruaganController::class, 'getRuanganById']);
    // akses jadwal matakuliah berdasarkan id
    Route::middleware('api')->get('/dosen_akses_jadwal_by_id/{id}', [jadwalMatakuliahController::class, 'getJadwalMatakuliahById']);
    // konfirmasi pemilihan ruangan
    Route::middleware('api')->put('/pemilihan_ruangan_dosen_akses/konfirmasi/{id}', [pemilihanRuangaController::class, 'konfirmasiKehadiranRuangan']);
    // batalkan pemilihan ruangan
    Route::middleware('api')->put('/pemilihan_ruangan_dosen_akses/batalkan/{id}', [pemilihanRuangaController::class, 'batalkanPemilihanRuangan']);
    // menampilkan semua pemilihan ruangan semuanya untuk kalender
    Route::middleware('api')->get('/pemilihan_ruangan_dosen_akses_semua', [pemilihanRuangaController::class, 'getPemilihanRuanganSemua']);
    // tambha jadwal matakuliah
    Route::middleware('api')->post('/dosen_akses_jadwal/add', [jadwalMatakuliahController::class, 'createJadwalMatakuliah']);
});

// ROUTER FAKULTAS
// rute untuk memanggil semua data fakultas
Route::middleware('api')->get('/fakultas/{paginate}', [fakultasController::class, 'getFakultas']);
// rute untuk memanggil data fakultas berdasarkan id
Route::middleware('api')->get('/fakultasById/{id}', [fakultasController::class, 'getFakultasById']);
// rute untuk memanggil data fakultas berdasarkan name fakultas
Route::middleware('api')->get('/fakultasByName/{paginate}/name/{name?}', [fakultasController::class, 'getFakultasByName']);
// rute untuk menambahkan data fakultas dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/fakultas/add', [fakultasController::class, 'createFakultas']);
// rute untuk memperbarui data fakultas berdasarkan ID
Route::middleware('api')->put('/fakultas/update/{id}', [fakultasController::class, 'updateFakultas']);
// rute untuk menghapus data fakultas berdasarkan ID
Route::middleware('api')->delete('/fakultas/delete/{id}', [fakultasController::class, 'deleteFakultas']);
// rute untuk melihat total fakultas
Route::middleware('api')->get('/total/fakultas', [fakultasController::class, 'jumlahFakultas']);

// ROUTER PRODI
// rute untuk memanggil semua data prodi
Route::middleware('api')->get('/prodi/{paginate}', [prodiController::class, 'getProdi']);
// rute untuk memanggil semua data prodi dan relasi ke tabel fakultas
Route::middleware('api')->get('/prodi/prodiToFakultas/{paginate}', [prodiController::class, 'getProdiToFakultas']);
// rute untuk memanggil semua data fakultas yang berlerasi dengan prodi dengan relasi satu ke banyak
Route::middleware('api')->get('/prodi/fakultasToProdi/{paginate}', [prodiController::class, 'getFakultasToProdi']);
// rute untuk memanggil data prodi berdasarkan id prodi
Route::middleware('api')->get('/prodiById/{id}', [prodiController::class, 'getProdiById']);
// rute untuk memanggil data prodi berdasarkan name prodi
Route::middleware('api')->get('/prodiByName/{paginate}/name/{name?}', [prodiController::class, 'getProdiByName']);
// rute untuk menambahkan data prodi dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/prodi/add', [prodiController::class, 'createProdi']);
// rute untuk memperbarui data prodi berdasarkan ID
Route::middleware('api')->put('/prodi/update/{id}', [prodiController::class, 'updateProdi']);
// rute untuk menghapus data prodi berdasarkan ID
Route::middleware('api')->delete('/prodi/delete/{id}', [prodiController::class, 'deleteProdi']);
// rute untuk melihat total prodi
Route::middleware('api')->get('/total/prodi', [prodiController::class, 'totalProdi']);

// ROUTER PEMILIHAN
// rute untuk memanggil semua data pemilihan
Route::middleware('api')->get('/pemilihan/{paginate}', [pemilihanController::class, 'getPemilihan']);
// rute untuk memanggil data pemilihan berdasarkan id pemilihan
Route::middleware('api')->get('/pemilihanById/{id}', [pemilihanController::class, 'getPemilihanById']);
// rute untuk menambahkan data pemilihan dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/pemilihan/add', [pemilihanController::class, 'createPemilihan']);
// rute untuk memperbarui data pemilihan berdasarkan ID
Route::middleware('api')->put('/pemilihan/update/{id}', [pemilihanController::class, 'updatePemilihan']);
// rute untuk menghapus data pemilihan berdasarkan ID
Route::middleware('api')->delete('/pemilihan/delete/{id}', [pemilihanController::class, 'deletePemilihan']);

//Router User DOSEN
// rute untuk memanggil semua data dosen
Route::middleware('api')->get('/dosen/{paginate}', [dosenController::class, 'getDosen']);
// rute untuk memanggil data dosen berdasarkan id dosen
Route::middleware('api')->get('/dosenById/{id}', [dosenController::class, 'getDosenById']);
// rute untuk menambahkan data dosen dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/dosen/add', [dosenController::class, 'createDosen']);
// verifikasi email dosen
Route::middleware('api')->get('/dosen-verify-email', [dosenController::class, 'verifyDosen']);
// rute untuk memperbarui data dosen berdasarkan ID
Route::middleware('api')->put('/dosen/update/{id}', [dosenController::class, 'updateDosen']);
// rute untuk menghapus data dosen berdasarkan ID
Route::middleware('api')->delete('/dosen/delete/{id}', [dosenController::class, 'deleteDosen']);
// rute untuk melihat total dosen
Route::middleware('api')->get('/total/dosen', [dosenController::class, 'totalDosen']);

//Ruter User MAHASISWA
// rute untuk memanggil semua data mahasiswa
Route::middleware('api')->get('/mahasiswa/{paginate}', [mahassiwaController::class, 'getMahasiswa']);
// rute untuk memanggil data mahasiswa berdasarkan id mahasiswa
Route::middleware('api')->get('/mahasiswaById/{id}', [mahassiwaController::class, 'getMahasiswaById']);
// rute untuk menambahkan data mahasiswa dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/mahasiswa/add', [mahassiwaController::class, 'createMahasiswa']);
// verifikasi email mahasiswa
Route::middleware('api')->get('/verify-email', [mahassiwaController::class, 'verifyMahasiswa']);
// rute untuk memperbarui data mahasiswa berdasarkan ID
Route::middleware('api')->put('/mahasiswa/update/{id}', [mahassiwaController::class, 'updateMahasiswa']);
// rute untuk menghapus data mahasiswa berdasarkan ID
Route::middleware('api')->delete('/mahasiswa/delete/{id}', [mahassiwaController::class, 'deleteMahasiswa']);
// rute untuk melihat total mahasiswa
Route::middleware('api')->get('/total/mahasiswa', [mahassiwaController::class, 'totalMahasiswa']);

// Router RUANGAN
// rute untuk memanggil semua data ruangan
Route::middleware('api')->get('/ruangan/{paginate}', [ruaganController::class, 'getRuangan']);
// rute untuk memanggil data ruangan berdasarkan id ruangan
Route::middleware('api')->get('/ruanganById/{id}', [ruaganController::class, 'getRuanganById']);
// rute update data ruangan berdasarkan id ruangan
Route::middleware('api')->put('/ruangan/update/{id}', [ruaganController::class, 'updateRuangan']);
// rute create data ruangan
Route::middleware('api')->post('/ruangan/add', [ruaganController::class, 'createRuangan']);
// rute delete data ruangan berdasarkan id ruangan
Route::middleware('api')->delete('/ruangan/delete/{id}', [ruaganController::class, 'deleteRuangan']);
// rute untuk melihat total ruangan
Route::middleware('api')->get('/total/ruangan', [ruaganController::class, 'totalRuangan']);

// Router MATAKULIAH
// rute untuk memanggil semua data matkul
Route::middleware('api')->get('/matkul/{paginate}', [matakuliahController::class, 'getMatkul']);
// rute untuk memanggil data matkul berdasarkan id matkul
Route::middleware('api')->get('/matkulById/{id}', [matakuliahController::class, 'getMatkulById']);
// rute update data matkul berdasarkan id matkul
Route::middleware('api')->put('/matkul/update/{id}', [matakuliahController::class, 'updateMatkul']);
// rute create data matkul
Route::middleware('api')->post('/matkul/add', [matakuliahController::class, 'addMatkul']);
// rute delete data matkul berdasarkan id matkul
Route::middleware('api')->delete('/matkul/delete/{id}', [matakuliahController::class, 'deleteMatkul']);
// rute untuk melihat total matkul
Route::middleware('api')->get('/total/matkul', [matakuliahController::class, 'totalMatkul']);

// Route JADWAL MATAKULIAH
// rute untuk memanggil semua data jadwal matkul
Route::middleware('api')->get('/jadwal/{paginate}', [jadwalMatakuliahController::class, 'getJadwalMatakuliah']);
// rute untuk memanggil data jadwal matkul berdasarkan id jadwal matkul
Route::middleware('api')->get('/jadwalById/{id}', [jadwalMatakuliahController::class, 'getJadwalMatakuliahById']);
// rute update data jadwal matkul berdasarkan id jadwal matkul
Route::middleware('api')->put('/jadwal/update/{id}', [jadwalMatakuliahController::class, 'updateJadwalMatakuliah']);
// rute create data jadwal matkul
Route::middleware('api')->post('/jadwal/add', [jadwalMatakuliahController::class, 'createJadwalMatakuliah']);
// rute delete data jadwal matkul berdasarkan id jadwal matkul
Route::middleware('api')->delete('/jadwal/delete/{id}', [jadwalMatakuliahController::class, 'deleteJadwalMatakuliah']);
// rute untuk melihat total jadwal matkul
Route::middleware('api')->get('/total/jadwal', [jadwalMatakuliahController::class, 'totalJadwalMatakuliah']);

// Route PEMILIHAN RUANGAN
// rute untuk memanggil semua data pemilihan ruangan
Route::middleware('api')->get('/pemilihanRuangan/{paginate}', [pemilihanRuangaController::class, 'getPemilihanRuangan']);
// rute untuk memanggil data pemilihan ruangan berdasarkan id pemilihan ruangan
Route::middleware('api')->get('/pemilihanRuanganById/{id}', [pemilihanRuangaController::class, 'getPemilihanRuanganById']);
// rute update data pemilihan ruangan berdasarkan id pemilihan ruangan
Route::middleware('api')->put('/pemilihanRuangan/update/{id}', [pemilihanRuangaController::class, 'updatePemilihanRungan']);
// rute create data pemilihan ruangan
Route::middleware('api')->post('/pemilihanRuangan/add', [pemilihanRuangaController::class, 'addPemilihanRungan']);
// rute delete data pemilihan ruangan berdasarkan id pemilihan ruangan
Route::middleware('api')->delete('/pemilihanRuangan/delete/{id}', [pemilihanRuangaController::class, 'deletePemilihanRuangan']);
