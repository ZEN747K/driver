@extends('layouts.layout')
@extends('layouts.menu')

@section('content')
<div class="container">
    <h1 class="mt-4">ADD Drivers</h1>
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">Back</a>
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
            <label class="form-label">Password for Profile</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Bank Account</label>
            <input type="text" name="bank_account" class="form-control" maxlength="14" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Birthdate</label>
            <input type="date" name="birthdate" class="form-control">
        </div>
     <div class="mb-3">
    <label class="form-label">Gender</label>
    <select name="gender" class="form-select" id="genderSelect">
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option> <!-- เพิ่มตัวเลือก "Other" -->
    </select>
</div>

<!-- ฟิลด์สำหรับกรอกเพศเอง -->
<div class="mb-3" id="customGenderField" style="display: none;">
    <label class="form-label">Please specify</label>
    <input type="text" id="customGenderInput" class="form-control" placeholder="Enter gender">
</div>

<script>
    document.getElementById('genderSelect').addEventListener('change', function() {
        const customGenderField = document.getElementById('customGenderField');
        const customGenderInput = document.getElementById('customGenderInput');

        if (this.value === 'other') {
            customGenderField.style.display = 'block';
            customGenderInput.setAttribute('name', 'gender'); // เปลี่ยน name เพื่อให้ส่งค่าไปยังฟอร์ม
        } else {
            customGenderField.style.display = 'none';
            customGenderInput.removeAttribute('name'); // ลบ name ออกเมื่อไม่ได้เลือก "Other"
        }
    });
</script>
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
