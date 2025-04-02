<?php

use App\Http\Controllers\fakultasController;
use Illuminate\Support\Facades\Route;

// rute untuk memanggil semua data fakultas
Route::middleware('api')->get('/fakultas', [fakultasController::class, 'getFakultas']);
// rute untuk memanggil data fakultas berdasarkan id
Route::middleware('api')->get('/fakultas/{id}', [fakultasController::class, 'getFakultasById']);
// rute untuk memanggil data fakultas berdasarkan name fakultas
Route::middleware('api')->get('/fakultas/name/{name?}', [fakultasController::class, 'getFakultasByName']);
// rute untuk menambahkan data fakultas dengan validasi dan paramter inputan POST
Route::middleware('api')->post('/fakultas/add', [fakultasController::class, 'createFakultas']);
// rute untuk memperbarui data fakultas berdasarkan ID
Route::middleware('api')->put('/fakultas/update/{id}', [fakultasController::class, 'updateFakultas']);
// rute untuk menghapus data fakultas berdasarkan ID
Route::middleware('api')->delete('/fakultas/delete/{id}', [fakultasController::class, 'deleteFakultas']);
