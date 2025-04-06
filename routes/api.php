<?php

use App\Http\Controllers\fakultasController;
use App\Http\Controllers\prodiController;
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
