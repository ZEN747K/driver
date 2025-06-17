@extends('layouts.layout')
@extends('layouts.menu')

@section('content')
<div class="container">
    <h1 class="mt-4">Add Driver</h1>
    <a href="{{ route('drivers.index') }}" class="btn btn-secondary mb-3">Back</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Password for Profile</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Bank Account</label>
            <input type="text" name="bank_account" maxlength="14" class="form-control" value="{{ old('bank_account') }}" required>
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
        <option value="other">Other</option> 
    </select>
</div>
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
            customGenderInput.setAttribute('name', 'gender'); 
        } else {
            customGenderField.style.display = 'none';
            customGenderInput.removeAttribute('name'); 
        }
    });
</script>
     
        <div class="mb-3">
            <label class="form-label">ID card</label>
            <input type="file" name="id_card" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Driver's license</label>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const genderSelect = document.getElementById('genderSelect');
    const customGenderField = document.getElementById('customGenderField');
    const customGenderInput = document.getElementById('customGenderInput');
    const finalGender = document.getElementById('finalGender');

    function updateGender() {
        if (genderSelect.value === 'other') {
            customGenderField.style.display = 'block';
            finalGender.value = customGenderInput.value;
        } else {
            customGenderField.style.display = 'none';
            finalGender.value = genderSelect.value;
        }
    }

    genderSelect.addEventListener('change', updateGender);
    customGenderInput.addEventListener('input', function () {
        if (genderSelect.value === 'other') {
            finalGender.value = customGenderInput.value;
        }
    });

    updateGender();
});
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ',
        text: '{{ session("success") }}',
        timer: 2500,
        timerProgressBar: true,
        showConfirmButton: false
    });
</script>
@elseif($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        showConfirmButton: true,
    });
</script>
@endif
@endsection
