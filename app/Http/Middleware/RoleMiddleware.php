<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|int  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Convert role names to role_ids if needed
        $roleMap = [
            'admin' => 1,
            'sekretaris' => 1,
            'pengacara' => 2,
            'klien' => 3,
        ];

        $allowedRoleIds = [];
        foreach ($roles as $role) {
            if (isset($roleMap[strtolower($role)])) {
                $allowedRoleIds[] = $roleMap[strtolower($role)];
            } elseif (is_numeric($role)) {
                $allowedRoleIds[] = (int)$role;
            }
        }

        if (!in_array((int)$user->id_role, $allowedRoleIds)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk membuka halaman ini.');
        }

        return $next($request);
    }
}

