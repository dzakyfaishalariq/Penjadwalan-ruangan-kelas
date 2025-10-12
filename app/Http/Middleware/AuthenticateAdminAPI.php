<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdminAPI
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
                'message' => 'Token Admin Tidak Tersedia',
            ], 401);
        }

        // ambil data user berdasarkan token
        $userAdmin = DB::table('users')->where('remember_token', $token)->first();
        // jika token tidak valid
        if (! $userAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Token Admin Tidak Valid',
            ], 401);
        }
        // tambahkan data user ke request
        $request->attributes->add([
            'userDosen' => $userAdmin,
        ]);
        return $next($request);
    }
}
