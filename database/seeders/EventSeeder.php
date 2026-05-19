<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Whitecube\LaravelTimezones\Facades\Timezone;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Set to India timezone for seeding
        Timezone::set('Asia/Kolkata');
        
        $events = [
            [
                'name' => 'Laravel Conference 2024',
                'location' => 'Mumbai, India',
                'occurred_at' => '2024-12-15 10:00:00',
                'description' => 'Annual Laravel Developer Conference'
            ],
            [
                'name' => 'Tech Workshop',
                'location' => 'Bangalore, India',
                'occurred_at' => '2024-12-20 14:30:00',
                'description' => 'Hands-on workshop on Modern PHP'
            ],
            [
                'name' => 'New Year Celebration',
                'location' => 'Delhi, India',
                'occurred_at' => '2025-01-01 00:00:00',
                'description' => 'Welcome 2025 with tech community'
            ],
        ];
        
        foreach ($events as $event) {
            Event::create($event);
        }
    }
}