<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(
                [
                    'message'  => __("unauthenticated")
                ],
                401
            );
        }

        foreach ($roles as $roleName) {
            $role = $user->roles()
                ->where('name', $roleName)
                ->first();

            if ($role) {
                return $next($request);
            }
        }

        $roleList = implode(', ', $roles);
        return response()->json(
            [
                'message' =>  "you have not role of ($roleList)  to access this resource"
            ],
            401
        );
    }
}
