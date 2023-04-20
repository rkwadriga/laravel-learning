<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check is user has an admin role
        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole(RoleEnum::ADMIN)) {
            throw new AccessDeniedException("It's only for admins");
        }

        return $next($request);
    }
}
