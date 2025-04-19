<?php
namespace App\Http\Controllers;

use App\Services\dosenService;
use Illuminate\Http\Request;

class dosenController extends Controller
{
    public $dosenService;
    //memanggil class dosenService untuk menggunkan fungsi di dalamnya
    public function __construct(dosenService $dosenService)
    {
        $this->dosenService = $dosenService;
    }
    public function getDosen($paginate)
    {
        // memanggil fungsi getDosen untuk menampilkan semua data dosen pada class dosenService

        $respons = $this->dosenService->getDosen($paginate);

        return $respons;
    }
    public function getDosenById($id)
    {
        // memanggil fungsi getDosenById untuk menampilkan data dosen berdasarkan id pada class dosenService
        $respons = $this->dosenService->getDosenById($id);

        return $respons;
    }
    public function createDosen(Request $request)
    {
        // memanggil fungsi createDosen untuk menambahkan data dosen pada class dosenService
        $respons = $this->dosenService->createDosen($request);

        return $respons;
    }

    public function updateDosen(Request $request, $id)
    {
        $respons = $this->dosenService->updateDosen($request, $id);

        return $respons;
    }
}
