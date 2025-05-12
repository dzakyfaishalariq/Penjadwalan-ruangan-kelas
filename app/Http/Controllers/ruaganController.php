<?php
namespace App\Http\Controllers;

use App\Services\ruanganService;

class ruaganController extends Controller
{
    public $ruanganService;
    public function __construct(ruanganService $ruanganService)
    {
        $this->ruanganService = $ruanganService;
    }
    public function getRuangan($paginate)
    {
        $ruangan = $this->ruanganService->getRuangan($paginate);
        return $ruangan;
    }
}
