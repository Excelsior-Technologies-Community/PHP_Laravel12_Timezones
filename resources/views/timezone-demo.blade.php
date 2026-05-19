@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-info text-white">
        <h3 class="mb-0">Timezone Demo</h3>
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
                <label for="timezone" class="form-label">Select Timezone:</label>
                <select name="timezone" id="timezone" class="form-control">
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