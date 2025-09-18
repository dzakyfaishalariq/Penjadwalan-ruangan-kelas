<?php
namespace App\Http\Controllers;

use App\Services\pemilihanRuanganService;
use Illuminate\Http\Request;

class pemilihanRuangaController extends Controller
{
    public $pemilihanRuanganService;
    public function __construct(pemilihanRuanganService $pemilihanRuanganService)
    {
        $this->pemilihanRuanganService = $pemilihanRuanganService;
    }

    public function getPemilihanRuangan($paginate)
    {
        return $this->pemilihanRuanganService->getPemilihanRuangan($paginate);
    }

    public function getPemilihanRuanganSemua()
    {
        return $this->pemilihanRuanganService->getPemilihanRuanganSemua();
    }

    public function getPemilihanRuanganByPemilih($pemilih_id, $paginate)
    {
        return $this->pemilihanRuanganService->getPemilihanRuanganByPemilih($pemilih_id, $paginate);
    }

    public function getPemilihanRuanganById($id)
    {
        return $this->pemilihanRuanganService->getPemilihanRuanganById($id);
    }

    public function updatePemilihanRungan(Request $request, $id)
    {
        return $this->pemilihanRuanganService->updatePemilihanRungan($request, $id);
    }

    public function addPemilihanRungan(Request $request)
    {
        return $this->pemilihanRuanganService->addPemilihanRungan($request);
    }

    public function deletePemilihanRuangan($id)
    {
        return $this->pemilihanRuanganService->deletePemilihanRuangan($id);
    }
}
