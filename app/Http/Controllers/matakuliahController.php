<?php
namespace App\Http\Controllers;

use App\Services\matakuliahService;
use Illuminate\Http\Request;

class matakuliahController extends Controller
{
    public $matakuliahService;
    public function __construct(matakuliahService $matakuliahService)
    {
        $this->matakuliahService = $matakuliahService;
    }

    public function getMatkul(Request $request, $paginate)
    {
        $matakuliah = $this->matakuliahService->getMatkul($request, $paginate);
        return $matakuliah;
    }

    public function getMatkulById($id)
    {
        $matakuliah = $this->matakuliahService->getMatkulById($id);
        return $matakuliah;
    }

    public function addMatkul(Request $request)
    {
        $matakuliah = $this->matakuliahService->addMatkul($request);
        return $matakuliah;
    }

    public function updateMatkul(Request $request, $id)
    {
        $matakuliah = $this->matakuliahService->updateMatkul($request, $id);
        return $matakuliah;
    }

    public function deleteMatkul($id)
    {
        $matakuliah = $this->matakuliahService->deleteMatkul($id);
        return $matakuliah;
    }

    public function totalMatkul()
    {
        $matakuliah = $this->matakuliahService->totalMatkul();
        return $matakuliah;
    }
}
