<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateDosenAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // cek token
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Token Dosen Tidak Tersedia',
            ], 401);
        }

        // ambil data user berdasarkan token
        $userDosen = DB::table('tb_dosen')->where('api_key', $token)->first();
        // jika token tidak valid
        if (! $userDosen) {
            return response()->json([
                'success' => false,
                'message' => 'Token Dosen Tidak Valid',
            ], 401);
        }
        // tambahkan data user ke request
        $request->attributes->add([
            'userDosen' => $userDosen,
        ]);
        return $next($request);
    }
}
