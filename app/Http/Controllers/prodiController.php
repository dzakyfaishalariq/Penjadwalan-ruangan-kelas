<?php
namespace App\Http\Controllers;

use App\Services\prodiService;

class prodiController extends Controller
{
    /**
     * inisialisiasi constractor yang menampung variabel class prodi service untuk memanggil fungsi yang berkaitan dengan manajemen data prodi.
     */
    public function __construct(prodiService $prodiService)
    {
        $this->prodiService = $prodiService;
    }

    public function getProdi()
    {
        // memanggil data prodi dengan relasi pada table fakultas
        $prodi = $this->prodiService->getProdi();

        return $prodi;
    }
}
