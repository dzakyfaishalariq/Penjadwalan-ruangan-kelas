<?php
namespace App\Http\Controllers;

use App\Services\fakultasService;
use Illuminate\Http\Request;

class fakultasController extends Controller
{
    // membuat constructor untuk menampung variabel class services
    public $fakultasService;
    public function __construct(fakultasService $fakultasService)
    {
        $this->fakultasService = $fakultasService;
    }
    // Memanggil data fakultas dari poltekes kemenkes bengkulu di database
    public function getFakultas($paginate)
    {
        $fakultas = $this->fakultasService->getFakultas($paginate);

        return $fakultas;
    }
    // Memanggil data fakultas berdasarkan id
    public function getFakultasById($id)
    {
        $fakultas = $this->fakultasService->getFakultasById($id);

        return $fakultas;
    }
    // Memanggil data fakultas berdasarkan name
    public function getFakultasByName($paginate, $name)
    {
        $fakultas = $this->fakultasService->getFakultasByName($paginate, $name);

        return $fakultas;
    }
    // memanggil fungsi untuk menambahkan data fakultas dengan validasi dan paramter inputan POST
    public function createFakultas(Request $request)
    {
        $fakultas = $this->fakultasService->createFakultas($request);

        return $fakultas;
    }

    // memanggil fungsi untuk memperbarui data fakultas berdasarkan ID
    public function updateFakultas(Request $request, $id)
    {
        $fakultas = $this->fakultasService->updateFakultas($request, $id);

        return $fakultas;
    }

    // memanggil fungsi untuk menghapus data fakultas berdasarkan ID
    public function deleteFakultas($id)
    {
        $fakultas = $this->fakultasService->deleteFakultas($id);

        return $fakultas;
    }
}
