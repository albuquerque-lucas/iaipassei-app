<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('username', $request->username)->first();

        if ($user && $user->accountPlan->access_level < 7) {
            return redirect()->back()->with(['error' => 'A sua conta não possui acesso a este conteúdo.']);
        }

        return $next($request);
    }
}
