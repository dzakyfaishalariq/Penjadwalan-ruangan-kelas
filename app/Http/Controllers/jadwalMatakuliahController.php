<?php
namespace App\Http\Controllers;

use App\Services\jadwalMatakuliahSevice;
use Illuminate\Http\Request;

class jadwalMatakuliahController extends Controller
{
    public $jadwalMatakuliahService;
    public function __construct(jadwalMatakuliahSevice $jadwalMatakuliahService)
    {
        $this->jadwalMatakuliahService = $jadwalMatakuliahService;
    }

    public function getJadwalMatakuliah()
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->getJadwalMatakuliah();
        return $jadwalMatakuliah;
    }
    public function getJadwalMatakuliahById($id)
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->getJadwalMatakuliahById($id);
        return $jadwalMatakuliah;
    }

    public function createJadwalMatakuliah(Request $request)
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->addJawalMatakuliah($request);
        return $jadwalMatakuliah;
    }

    public function updateJadwalMatakuliah(Request $request, $id)
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->updateJadwalMatakuliah($request, $id);
        return $jadwalMatakuliah;
    }

    public function deleteJadwalMatakuliah($id)
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->deleteJadwalMatakuliah($id);
        return $jadwalMatakuliah;
    }

    public function totalJadwalMatakuliah()
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->totalJadwalMatakuliah();
        return $jadwalMatakuliah;
    }

    public function getJadwalMatakuliahTersedia()
    {
        $jadwalMatakuliah = $this->jadwalMatakuliahService->getJadwalMatakuliahTersedia();
        return $jadwalMatakuliah;
    }
}
