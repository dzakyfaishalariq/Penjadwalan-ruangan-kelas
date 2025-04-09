<?php
namespace App\Http\Controllers;

use App\Services\pemilihanService;
use Illuminate\Http\Request;

class pemilihanController extends Controller
{
    // inisialisasi variabel penampung class pemilihanService
    public $pemilihanService;
    public function __construct(pemilihanService $pemilihanService)
    {
        // memanggil class pemilihanService dan disimpan di variabel pemilihanService sebagai atribut
        $this->pemilihanService = $pemilihanService;
    }
    public function getPemilihan($paginate)
    {
        // memanggil fungsi getPemilihan dari pemilihanService
        $pemilihan = $this->pemilihanService->getPemilihan($paginate);

        return $pemilihan;
    }
    public function getPemilihanById($id)
    {
        // memanggil fungsi getPemilihanById dari pemilihanService
        $pemilihan = $this->pemilihanService->getPemilihanById($id);

        return $pemilihan;
    }
    public function createPemilihan(Request $request)
    {
        // memanggil fungsi createPemilihan dari pemilihanService
        $pemilihan = $this->pemilihanService->createPemilihan($request);

        return $pemilihan;
    }

    public function updatePemilihan(Request $request, $id)
    {
        // memanggil fungsi updatePemilihan dari pemilihanService
        $pemilihan = $this->pemilihanService->updatePemilihan($request, $id);

        return $pemilihan;
    }

    public function deletePemilihan($id)
    {
        // memanggil fungsi deletePemilihan dari pemilihanService
        $pemilihan = $this->pemilihanService->deletePemilihan($id);

        return $pemilihan;
    }
}
