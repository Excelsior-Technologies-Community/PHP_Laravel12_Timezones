<?php

namespace App\Http\Middleware;

use Closure;
use Whitecube\LaravelTimezones\Facades\Timezone;

class SetApplicationTimezone
{
    public function handle($request, Closure $next)
    {
        // Get timezone from session, user settings, or default
        $timezone = session('user_timezone', 'Asia/Kolkata'); // India timezone
        
        // Or get from user model if authenticated
        if ($request->user() && $request->user()->timezone) {
            $timezone = $request->user()->timezone;
        }
        
        Timezone::set($timezone);
        
        return $next($request);
    }
}