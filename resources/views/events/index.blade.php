@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Events List</h3>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('events.create') }}" class="btn btn-success">Create New Event</a>
        </div>
        
        <div class="alert alert-info">
            <strong>Current Timezone:</strong> {{ $currentTimezone ?? 'Not set' }} |
            <strong>Storage Timezone (DB):</strong> {{ $storageTimezone ?? 'UTC' }}
        </div>
        
        @if(empty($events) || $events->count() == 0)
            <div class="alert alert-warning">No events found. Create your first event!</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Occurred At</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->location }}</td>
                            <td>
                                @if($event->occurred_at)
                                    {{ $event->occurred_at->format('Y-m-d H:i:s') }}
                                @endif
                            </td>
                            <td>
                                @if($event->created_at)
                                    {{ $event->created_at->format('Y-m-d H:i:s') }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection