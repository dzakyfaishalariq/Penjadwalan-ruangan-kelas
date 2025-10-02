<?php
namespace App\Http\Controllers;

use App\Services\adminService;
use Illuminate\Http\Request;

class adminController extends Controller
{
    public $adminService;
    public function __construct(adminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function addAdmin(Request $request)
    {
        return $this->adminService->addAdmin($request);
    }

    public function verifyAdmin(Request $request)
    {
        return $this->adminService->verifyAdmin($request);
    }
}
