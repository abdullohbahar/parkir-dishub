<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class CheckProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userID = auth()->user()->id;

        $user = User::with('hasOneProfile')->where('id', $userID)->first();

        if ($user->hasOneProfile?->no_telepon == null) {
            return to_route('profile.edit', $userID);
        }

        return $next($request);
    }
}
