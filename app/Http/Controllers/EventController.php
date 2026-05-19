<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Whitecube\LaravelTimezones\Facades\Timezone;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all(); // This returns a Collection
        $currentTimezone = Timezone::current();
        $storageTimezone = Timezone::storage();
        
        // Debug: Check what type $events is
        // dd(gettype($events), $events->count());
        
        return view('events.index', [
            'events' => $events,
            'currentTimezone' => $currentTimezone,
            'storageTimezone' => $storageTimezone
        ]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'occurred_at' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $event = Event::create($validated);
        
        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'occurred_at' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $event->update($validated);
        
        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}