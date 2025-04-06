<?php
namespace App\Http\Controllers;

use App\Services\pemilihanService;

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

}
