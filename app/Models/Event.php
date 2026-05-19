<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Whitecube\LaravelTimezones\Casts\TimezonedDatetime;
use Whitecube\LaravelTimezones\Concerns\HasTimezonedTimestamps;

class Event extends Model
{
    use HasTimezonedTimestamps;

    protected $fillable = [
        'name',
        'location',
        'occurred_at',
        'description'
    ];

    protected $casts = [
        'occurred_at' => TimezonedDatetime::class,
        'created_at' => TimezonedDatetime::class,
        'updated_at' => TimezonedDatetime::class,
    ];
}