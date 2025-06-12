@extends('layouts.layout')

@section('content')
<div class="container">
    <h1 class="mt-4">Driver Details</h1>
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">Back</a>

    <form action="{{ route('drivers.update', $driver) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Full Name --}}
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input
                type="text"
                name="full_name"
                class="form-control"
                value="{{ old('full_name', $driver->full_name) }}"
                required>
        </div>

        {{-- Phone --}}
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input
                type="text"
                name="phone"
                class="form-control"
                value="{{ old('phone', $driver->phone) }}"
                required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email', $driver->email) }}">
        </div>

        {{-- Password for Profile --}}
        <div class="mb-3">
            <label class="form-label">Password for Profile</label>
            <input
                type="password"
                name="password_for_profile"
                class="form-control"
                value="{{ old('password_for_profile', $driver->password_for_profile) }}">
        </div>

        {{-- Bank Account --}}
        <div class="mb-3">
            <label class="form-label">Bank Account</label>
            <input
                type="text"
                name="bank_account"
                class="form-control"
                maxlength="14"
                value="{{ old('bank_account', $driver->bank_account) }}"
                required>
        </div>

        {{-- Birthdate --}}
        <div class="mb-3">
            <label class="form-label">Birthdate</label>
            <input
                type="date"
                name="birthdate"
                class="form-control"
                value="{{ old('birthdate', $driver->birthdate) }}">
        </div>

        {{-- Gender (single input with suggestions) --}}
        <div class="mb-3">
            <label class="form-label">Gender</label>
            <input
                list="genderList"
                name="gender"
                class="form-control"
                value="{{ old('gender', $driver->gender) }}"
                placeholder="Type or select gender">
            <datalist id="genderList">
                <option value="male">
                <option value="female">
                <option value="other">
            </datalist>
        </div>

        {{-- Service Type --}}
        <div class="mb-3">
            <label class="form-label">Service Type</label>
            <select name="service_type" class="form-select" required>
                <option value="car"        @selected(old('service_type', $driver->service_type)=='car')>Car</option>
                <option value="motorcycle" @selected(old('service_type', $driver->service_type)=='motorcycle')>Motorcycle</option>
                <option value="delivery"   @selected(old('service_type', $driver->service_type)=='delivery')>Delivery</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="No_approve" @selected(old('status', $driver->status)=='No_approve')>No Approve</option>
                <option value="Pending"    @selected(old('status', $driver->status)=='Pending')>Pending</option>
                <option value="Approved"   @selected(old('status', $driver->status)=='Approved')>Approve</option>
            </select>
        </div>

        {{-- File Uploads --}}
        <div class="mb-3">
            <label class="form-label">ID Card</label>
            <input type="file" name="id_card" class="form-control">
            @if($driver->id_card_path)
                <a href="{{ route('drivers.download', [$driver, 'id_card']) }}"
                   class="d-block mt-1">{{ basename($driver->id_card_path) }}</a>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Driver License</label>
            <input type="file" name="driver_license" class="form-control">
            @if($driver->driver_license_path)
                <a href="{{ route('drivers.download', [$driver, 'driver_license']) }}"
                   class="d-block mt-1">{{ basename($driver->driver_license_path) }}</a>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Face Photo</label>
            <input type="file" name="face_photo" class="form-control">
            @if($driver->face_photo_path)
                <a href="{{ route('drivers.download', [$driver, 'face_photo']) }}"
                   class="d-block mt-1">{{ basename($driver->face_photo_path) }}</a>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Vehicle Registration</label>
            <input type="file" name="vehicle_registration" class="form-control">
            @if($driver->vehicle_registration_path)
                <a href="{{ route('drivers.download', [$driver, 'vehicle_registration']) }}"
                   class="d-block mt-1">{{ basename($driver->vehicle_registration_path) }}</a>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Compulsory Insurance</label>
            <input type="file" name="compulsory_insurance" class="form-control">
            @if($driver->compulsory_insurance_path)
                <a href="{{ route('drivers.download', [$driver, 'compulsory_insurance']) }}"
                   class="d-block mt-1">{{ basename($driver->compulsory_insurance_path) }}</a>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Vehicle Insurance</label>
            <input type="file" name="vehicle_insurance" class="form-control">
            @if($driver->vehicle_insurance_path)
                <a href="{{ route('drivers.download', [$driver, 'vehicle_insurance']) }}"
                   class="d-block mt-1">{{ basename($driver->vehicle_insurance_path) }}</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection