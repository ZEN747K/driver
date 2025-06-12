@extends('layouts.layout')
@section('style')
@section('content')
<div class="container">
    <h1 class="mt-4">Drivers</h1>
    <a href="{{ route('drivers.create') }}" class="btn btn-primary mb-3">Add Driver</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Service</th>
                <th>Status</t   h>
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
                <td>{{ $driver->status }}</td>
                <td>
                    <a href="{{ route('drivers.show', $driver) }}" class="btn btn-sm btn-info">More information</a>
                    <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="No_approve">
                        <button class="btn btn-sm btn-danger">No approve</button>
                    </form>
                    <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Pending">
                        <button class="btn btn-sm btn-warning">Pending</button>
                    </form>
                    <form action="{{ route('drivers.approve', $driver) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Approved">
                        <button class="btn btn-sm btn-success">Approve</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
