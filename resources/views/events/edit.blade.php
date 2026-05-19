@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
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
                <input type="datetime-local" name="occurred_at" class="form-control" 
                       value="{{ $event->occurred_at->format('Y-m-d\TH:i') }}" required>
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