<?php

use App\Http\Controllers\dosenController;
use App\Http\Controllers\fakultasController;
use App\Http\Controllers\mahassiwaController;
use App\Http\Controllers\matakuliahController;
use App\Http\Controllers\pemilihanController;
use App\Http\Controllers\prodiController;
use App\Http\Controllers\ruaganController;
use Illuminate\Support\Facades\Route;

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
// rute untuk memperbarui data dosen berdasarkan ID
Route::middleware('api')->put('/dosen/update/{id}', [dosenController::class, 'updateDosen']);
// rute untuk menghapus data dosen berdasarkan ID
Route::middleware('api')->delete('/dosen/delete/{id}', [dosenController::class, 'deleteDosen']);

//Ruter User MAHASISWA
// rute untuk memanggil semua data mahasiswa
Route::middleware('api')->get('/mahasiswa/{paginate}', [mahassiwaController::class, 'getMahasiswa']);
// rute untuk memanggil data mahasiswa berdasarkan id mahasiswa
Route::middleware('api')->get('/mahasiswaById/{id}', [mahassiwaController::class, 'getMahasiswaById']);
// rute untuk menambahkan data mahasiswa dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/mahasiswa/add', [mahassiwaController::class, 'createMahasiswa']);
// rute untuk memperbarui data mahasiswa berdasarkan ID
Route::middleware('api')->put('/mahasiswa/update/{id}', [mahassiwaController::class, 'updateMahasiswa']);
// rute untuk menghapus data mahasiswa berdasarkan ID
Route::middleware('api')->delete('/mahasiswa/delete/{id}', [mahassiwaController::class, 'deleteMahasiswa']);

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

// Router MATAKULIAH
// rute untuk memanggil semua data matkul
Route::middleware('api')->get('/matkul/{paginate}', [matakuliahController::class, 'getMatkul']);
// rute untuk memanggil data matkul berdasarkan id matkul
Route::middleware('api')->get('/matkulById/{id}', [matakuliahController::class, 'getMatkulById']);
// rute update data matkul berdasarkan id matkul
Route::middleware('api')->put('/matkul/update/{id}', [matakuliahController::class, 'updateMatkul']);
// rute create data matkul
Route::middleware('api')->post('/matkul/add', [matakuliahController::class, 'createMatkul']);
// rute delete data matkul berdasarkan id matkul
Route::middleware('api')->delete('/matkul/delete/{id}', [matakuliahController::class, 'deleteMatkul']);
