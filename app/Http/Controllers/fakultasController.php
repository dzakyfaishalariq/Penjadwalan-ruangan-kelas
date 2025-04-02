<?php
namespace App\Http\Controllers;

use App\Services\fakultasService;
use Illuminate\Http\Request;

class fakultasController extends Controller
{
    // membuat constructor untuk menampung variabel class services
    public function __construct(fakultasService $fakultasService)
    {
        $this->fakultasService = $fakultasService;
    }
    // Memanggil data fakultas dari poltekes kemenkes bengkulu di database
    public function getFakultas()
    {
        $fakultas = $this->fakultasService->getFakultas();

        return response()->json($fakultas);
    }
    // Memanggil data fakultas berdasarkan id
    public function getFakultasById($id)
    {
        $fakultas = $this->fakultasService->getFakultasById($id);

        return response()->json($fakultas);
    }
    // Memanggil data fakultas berdasarkan name
    public function getFakultasByName($name)
    {
        $fakultas = $this->fakultasService->getFakultasByName($name);

        return response()->json($fakultas);
    }
    // menambahkan data fakultas dengan validasi dan paramter inputan POST
    public function createFakultas(Request $request)
    {
        $fakultas = $this->fakultasService->createFakultas($request);

        return response()->json($fakultas);
    }

    // memperbarui data fakultas berdasarkan ID
    public function updateFakultas(Request $request, $id)
    {
        $fakultas = $this->fakultasService->updateFakultas($request, $id);

        return response()->json($fakultas);
    }
}
