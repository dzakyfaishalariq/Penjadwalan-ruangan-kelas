<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class fakultasController extends Controller
{
    // Memanggil data fakultas dari poltekes kemenkes bengkulu di database
    public function getFakultas(Request $request)
    {
        $fakultas = DB::table('tb_fakultas')->get();

        return response()->json($fakultas);
    }
}
