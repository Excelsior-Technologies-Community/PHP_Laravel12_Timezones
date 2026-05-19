<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Whitecube\LaravelTimezones\Facades\Timezone;

class TimezoneDemoController extends Controller
{
    public function demo()
    {
        $currentTimezone = Timezone::current();
        $storageTimezone = Timezone::storage();
        $currentTime = Timezone::now();
        
        $timezones = [
            'Asia/Kolkata' => 'India (IST)',
            'America/New_York' => 'USA (EST)',
            'Europe/London' => 'UK (GMT)',
            'Europe/Brussels' => 'Belgium (CET)',
            'Asia/Tokyo' => 'Japan (JST)',
            'Australia/Sydney' => 'Australia (AEDT)'
        ];
        
        return view('timezone-demo', compact(
            'currentTimezone',
            'storageTimezone',
            'currentTime',
            'timezones'
        ));
    }
    
    public function setTimezone(Request $request)
    {
        $request->validate([
            'timezone' => 'required|string'
        ]);
        
        session(['user_timezone' => $request->timezone]);
        Timezone::set($request->timezone);
        
        return redirect()->back()->with('success', "Timezone changed to {$request->timezone}");
    }
}