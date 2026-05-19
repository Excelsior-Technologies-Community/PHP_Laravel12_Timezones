@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Event Details</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <tr>
                <th>Name:</th>
                <td>{{ $event->name }}</td>
            </tr>
            <tr>
                <th>Location:</th>
                <td>{{ $event->location }}</td>
            </tr>
            <tr>
                <th>Occurred At:</th>
                <td>{{ $event->occurred_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <th>Description:</th>
                <td>{{ $event->description ?? 'No description' }}</td>
            </tr>
        </table>
        
        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</div>
@endsection