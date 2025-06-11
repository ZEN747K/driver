@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Driver</h1>
    <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Birthdate</label>
            <input type="date" name="birthdate" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">ID Card</label>
            <input type="file" name="id_card" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Driver License</label>
            <input type="file" name="driver_license" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Face Photo</label>
            <input type="file" name="face_photo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Vehicle Registration</label>
            <input type="file" name="vehicle_registration" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Compulsory Insurance</label>
            <input type="file" name="compulsory_insurance" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Vehicle Insurance</label>
            <input type="file" name="vehicle_insurance" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Service Type</label>
            <select name="service_type" class="form-select" required>
                <option value="car">Car</option>
                <option value="motorcycle">Motorcycle</option>
                <option value="delivery">Delivery</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection
