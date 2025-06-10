<?php
namespace App\Http\Controllers;

use App\Services\loginService;
use Illuminate\Http\Request;

class loginController extends Controller
{
    // inisialisasi variabel yang menampung login sevice
    public $loginService;
    // Meletakan di constructor class
    public function __construct(loginService $loginService)
    {
        $this->loginService = $loginService;
    }
    public function loginMahasiswa(Request $request)
    {
        // memanggil fungsi login_mahasiswa pada class loginService
        $response = $this->loginService->login_mahasiswa($request);

        return $response;
    }
}
