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
}
