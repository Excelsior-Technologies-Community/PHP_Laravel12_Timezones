@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
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