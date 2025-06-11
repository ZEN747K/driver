@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Drivers</h1>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary mb-3">Add Driver</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Approved</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
            <tr>
                <td>{{ $driver->id }}</td>
                <td>{{ $driver->full_name }}</td>
                <td>{{ $driver->phone }}</td>
                <td>{{ $driver->service_type }}</td>
                <td>{{ $driver->approved ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('drivers.show', $driver) }}" class="btn btn-sm btn-info">More information</a>
                    @if(!$driver->approved)
                    <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
