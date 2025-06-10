<?php
namespace App\Http\Controllers;

use App\Services\kalenderSistemService;

class kalenderSistemController extends Controller
{
    // akses function kalender sistem service
    public $kalenderSistemService;
    public function __construct(kalenderSistemService $kalenderSistemService)
    {
        $this->kalenderSistemService = $kalenderSistemService;
    }

    // akses data kalender
    public function getKalenderSistem(int $paginate = 10)
    {
        return $this->kalenderSistemService->getKalenderSistem($paginate);
    }

    // akses data kalender berdasarkan id
    public function getKalenderSistemById(int $id)
    {
        return $this->kalenderSistemService->getKalenderSistemById($id);
    }
}
