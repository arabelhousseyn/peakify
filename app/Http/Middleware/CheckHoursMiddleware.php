<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckHoursMiddleware
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
        $user = Auth::user();
        $time = Carbon::now()->format('H:i');
        if($time < $user->start_at || $time > $user->end_at && $user->start_at !== null && $user->end_at !== null)
        {
            Auth::guard('web')->logout();
            $errors = ['message' => 'Accès expiré vous avez accès à votre compte du: ' . $user->start_at . ' au: ' . $user->end_at];
            return response($errors,401);
        }
        return $next($request);
    }
}
