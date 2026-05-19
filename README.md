# PHP_Laravel12_Timezones

## Project Description
PHP_Laravel12_Timezones is a Laravel 12 based application that demonstrates how to handle timezones effectively using the whitecube/laravel-timezones package.

This project provides a practical implementation of:

- Event CRUD Operations
- Dynamic Timezone Switching
- UTC Database Storage
- Automatic Timezone Conversion
- Live Timezone Display
- User Timezone Preferences

The application allows users to create, update, delete, and view events while automatically converting dates between user-selected timezones and UTC storage.

## Features

### Event CRUD
- Create Event
- View Event List
- Edit Event
- Delete Event

### Timezone Management
- Dynamic timezone switching
- UTC storage in database
- Automatic conversion to user timezone
- Session-based timezone persistence

### UI Features
- Responsive Bootstrap UI
- Clean and simple interface
- Event table layout with timezone info
- Timezone demo page
- Real-time timezone conversion demos

## Technologies Used
- PHP 8.2
- Laravel 12
- MySQL
- Blade Template Engine
- Bootstrap 5
- HTML/CSS
- Whitecube Laravel Timezones
- Composer
- Laravel Artisan CLI

## Package Used

Install package:
```bash
composer require whitecube/laravel-timezones
```

Package: [whitecube/laravel-timezones](https://github.com/whitecube/laravel-timezones)

## Installation Steps

### STEP 1: Create Laravel 12 Project

Open terminal / CMD and run:
```bash
composer create-project laravel/laravel PHP_Laravel12_Timezones "12.*"
```

Go inside project:
```bash
cd PHP_Laravel12_Timezones
```

**Explanation:** Creates a fresh Laravel 12 application and moves into the project directory.

### STEP 2: Database Setup

Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=PHP_Laravel12_Timezones
DB_USERNAME=root
DB_PASSWORD=

APP_TIMEZONE=UTC
```

Create database in MySQL/phpMyAdmin:
```sql
CREATE DATABASE PHP_Laravel12_Timezones;
```

Run migration:
```bash
php artisan migrate
```

**Explanation:** Configures MySQL database connection and sets UTC as the storage timezone.

### STEP 3: Install Laravel Timezones Package

Run:
```bash
composer require whitecube/laravel-timezones
```

**Explanation:** Installs the Laravel Timezones package for handling timezone conversions automatically.

### STEP 4: Create Event Model + Migration

Run:
```bash
php artisan make:model Event -m
```

Open migration file `database/migrations/xxxx_create_events_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->dateTime('occurred_at');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
```

Run migration:
```bash
php artisan migrate
```

Open `app/Models/Event.php`:

```php
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
```

**Explanation:** Creates the Event model and database table with timezone casting for automatic conversion.

### STEP 5: Create Timezone Middleware

Run:
```bash
php artisan make:middleware SetApplicationTimezone
```

Open `app/Http/Middleware/SetApplicationTimezone.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Whitecube\LaravelTimezones\Facades\Timezone;

class SetApplicationTimezone
{
    public function handle($request, Closure $next)
    {
        $timezone = session('user_timezone', 'Asia/Kolkata');
        
        Timezone::set($timezone);
        
        return $next($request);
    }
}
```

Register middleware in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetApplicationTimezone::class,
    ]);
})
```

**Explanation:** Creates middleware to set the application timezone dynamically based on user session.

### STEP 6: Create Controller

Run:
```bash
php artisan make:controller EventController --resource
php artisan make:controller TimezoneDemoController
```

Open `app/Http/Controllers/EventController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Whitecube\LaravelTimezones\Facades\Timezone;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        $currentTimezone = Timezone::current();
        $storageTimezone = Timezone::storage();
        
        return view('events.index', compact('events', 'currentTimezone', 'storageTimezone'));
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

        Event::create($validated);
        
        return redirect()->route('events.index')->with('success', 'Event created successfully!');
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
        
        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        
        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}
```

Open `app/Http/Controllers/TimezoneDemoController.php`:

```php
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
        ];
        
        return view('timezone-demo', compact('currentTimezone', 'storageTimezone', 'currentTime', 'timezones'));
    }
    
    public function setTimezone(Request $request)
    {
        $request->validate(['timezone' => 'required|string']);
        
        session(['user_timezone' => $request->timezone]);
        Timezone::set($request->timezone);
        
        return redirect()->back()->with('success', "Timezone changed to {$request->timezone}");
    }
}
```

**Explanation:** Creates controllers to handle event CRUD operations and timezone demonstrations.

### STEP 7: Route Setup

Edit `routes/web.php`:

```php
<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TimezoneDemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('events.index');
});

Route::resource('events', EventController::class);
Route::get('/timezone-demo', [TimezoneDemoController::class, 'demo'])->name('timezone.demo');
Route::post('/set-timezone', [TimezoneDemoController::class, 'setTimezone'])->name('set.timezone');
```

**Explanation:** Defines routes for CRUD operations and timezone switching.

### STEP 8: Blade Views

Create `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Timezones Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('events.index') }}">Timezones Demo</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('events.index') }}">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('events.create') }}">Create Event</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('timezone.demo') }}">Timezone Demo</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

Create `resources/views/events/index.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3>Events List</h3>
        <a href="{{ route('events.create') }}" class="btn btn-light mt-2">Create Event</a>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Current Timezone:</strong> {{ $currentTimezone }} |
            <strong>DB Storage:</strong> {{ $storageTimezone }}
        </div>
        
        @if($events->isEmpty())
            <div class="alert alert-warning">No events found.</div>
        @else
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Occurred At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td>{{ $event->id }}</td>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->location }}</td>
                        <td>{{ $event->occurred_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
```

Create `resources/views/events/create.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white">
        <h3>Create New Event</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Event Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Location:</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Event Date & Time:</label>
                <input type="datetime-local" name="occurred_at" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
```

Create `resources/views/events/edit.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-warning text-white">
        <h3>Edit Event</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('events.update', $event) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Event Name:</label>
                <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
            </div>
            <div class="mb-3">
                <label>Location:</label>
                <input type="text" name="location" class="form-control" value="{{ $event->location }}" required>
            </div>
            <div class="mb-3">
                <label>Event Date & Time:</label>
                <input type="datetime-local" name="occurred_at" class="form-control" value="{{ $event->occurred_at->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="mb-3">
                <label>Description:</label>
                <textarea name="description" class="form-control" rows="3">{{ $event->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
```

Create `resources/views/events/show.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h3>Event Details</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Name:</th><td>{{ $event->name }}</td></tr>
            <tr><th>Location:</th><td>{{ $event->location }}</td></tr>
            <tr><th>Occurred At:</th><td>{{ $event->occurred_at->format('Y-m-d H:i:s') }}</td></tr>
            <tr><th>Description:</th><td>{{ $event->description ?? 'No description' }}</td></tr>
            <tr><th>Created At:</th><td>{{ $event->created_at->format('Y-m-d H:i:s') }}</td></tr>
        </table>
        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>
@endsection
```

Create `resources/views/timezone-demo.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-dark text-white">
        <h3>Timezone Demo</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4>Current Configuration</h4>
            <p><strong>Display Timezone:</strong> {{ $currentTimezone }}</p>
            <p><strong>Storage Timezone (DB):</strong> {{ $storageTimezone }}</p>
            <p><strong>Current Time:</strong> {{ $currentTime->format('Y-m-d H:i:s') }}</p>
        </div>

        <form action="{{ route('set.timezone') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Select Timezone:</label>
                <select name="timezone" class="form-control">
                    @foreach($timezones as $value => $label)
                        <option value="{{ $value }}" {{ session('user_timezone', 'Asia/Kolkata') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Change Timezone</button>
        </form>
        
        <div class="mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
        </div>
    </div>
</div>
@endsection
```

**Explanation:** Creates Blade views for displaying events, forms, and timezone demo interface.

### STEP 9: Run Project

Start development server:
```bash
php artisan serve
```

Open browser:

**Events Page:**
```
http://127.0.0.1:8000/events
```
<img width="1312" height="656" alt="image" src="https://github.com/user-attachments/assets/a08d2acb-3f76-45ad-801f-4888cae9816f" />

**Timezone Demo:**
```
http://127.0.0.1:8000/timezone-demo
```
<img width="1320" height="651" alt="image" src="https://github.com/user-attachments/assets/051a1ca8-79a0-498b-8a24-2a6d5edf5c40" />

## Expected Output

### Events Page
- Displays list of events with timezone information
- Shows current display timezone and database storage timezone
- Dates automatically convert to selected timezone

### Timezone Demo Page
- Current time in selected timezone
- Timezone switching dropdown
- UTC to local conversion examples
- Storage conversion demonstration

## Project Folder Structure

```
PHP_Laravel12_Timezones/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── EventController.php
│   │   │   └── TimezoneDemoController.php
│   │   └── Middleware/
│   │       └── SetApplicationTimezone.php
│   │
│   └── Models/
│       └── Event.php
│
├── bootstrap/
│   └── app.php
│
├── database/
│   ├── migrations/
│   │   └── xxxx_create_events_table.php
│   └── seeders/
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── events/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── timezone-demo.blade.php
│
├── routes/
│   └── web.php
│
├── .env
├── artisan
├── composer.json
└── README.md
```


For more updates, follow Laravel and Whitecube packages.
