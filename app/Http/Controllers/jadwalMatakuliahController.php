<?php
namespace App\Http\Controllers;

use App\Services\jadwalMatakuliahSevice;

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
}
