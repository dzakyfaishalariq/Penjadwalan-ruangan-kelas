<?php
namespace App\Http\Controllers;

use App\Services\ruanganService;
use Illuminate\Http\Request;

class ruaganController extends Controller
{
    public $ruanganService;
    public function __construct(ruanganService $ruanganService)
    {
        $this->ruanganService = $ruanganService;
    }
    public function getRuangan($paginate, Request $request)
    {
        $ruangan = $this->ruanganService->getRuangan($paginate, $request);
        return $ruangan;
    }
    public function getRuanganById($id)
    {
        $ruangan = $this->ruanganService->getRuanganById($id);
        return $ruangan;
    }
    public function updateRuangan(Request $request, $id)
    {
        $ruangan = $this->ruanganService->updateRuangan($request, $id);
        return $ruangan;
    }
    public function createRuangan(Request $request)
    {
        $ruangan = $this->ruanganService->createRuangan($request);
        return $ruangan;
    }
    public function deleteRuangan($id)
    {
        $ruangan = $this->ruanganService->deleteRuangan($id);
        return $ruangan;
    }
    public function totalRuangan()
    {
        $ruangan = $this->ruanganService->totalRuangan();
        return $ruangan;
    }

    public function totalRuanganTerpakai()
    {
        $ruangan = $this->ruanganService->totalRuanganTerpakai();
        return $ruangan;
    }

    public function totalRuanganTersedia()
    {
        $ruangan = $this->ruanganService->totalRuanganTersedia();
        return $ruangan;
    }

    public function totalSemuaKapasitasRuangan()
    {
        $ruangan = $this->ruanganService->totalSemuaKapasitasRuangan();
        return $ruangan;
    }

    public function getNamaRuangan()
    {
        $ruangan = $this->ruanganService->getNamaRuangan();
        return $ruangan;
    }
}
