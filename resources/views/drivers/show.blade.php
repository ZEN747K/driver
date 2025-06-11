@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Driver Details</h1>
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">Back</a>
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $driver->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $driver->full_name }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $driver->phone }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $driver->email }}</td>
        </tr>
        <tr>
            <th>Birthdate</th>
            <td>{{ $driver->birthdate }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $driver->gender }}</td>
        </tr>
        <tr>
            <th>Service</th>
            <td>{{ $driver->service_type }}</td>
        </tr>
        <tr>
            <th>Approved</th>
            <td>{{ $driver->approved ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>ID Card</th>
            <td>{{ $driver->id_card_path }}</td>
        </tr>
        <tr>
            <th>Driver License</th>
            <td>{{ $driver->driver_license_path }}</td>
        </tr>
        <tr>
            <th>Face Photo</th>
            <td>{{ $driver->face_photo_path }}</td>
        </tr>
        <tr>
            <th>Vehicle Registration</th>
            <td>{{ $driver->vehicle_registration_path }}</td>
        </tr>
        <tr>
            <th>Compulsory Insurance</th>
            <td>{{ $driver->compulsory_insurance_path }}</td>
        </tr>
        <tr>
            <th>Vehicle Insurance</th>
            <td>{{ $driver->vehicle_insurance_path }}</td>
        </tr>
    </table>
</div>
@endsection
