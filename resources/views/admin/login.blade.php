@extends('layouts.sneat')

@section('content')
<div class="container">
    <h1>Admin Login</h1>
    <form id="login-form" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="{{ route('admin.register') }}" class="btn btn-link">Register</a>
    </form>

    <!-- Modal for login notifications -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center" id="loginModalMessage"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    const modalEl = document.getElementById('loginModal');
    const modal = new bootstrap.Modal(modalEl);
    const messageEl = document.getElementById('loginModalMessage');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(resp => {
            if (resp.ok) {
                return resp.json().catch(() => ({ success: true }));
            }
            throw new Error('fail');
        })
        .then(() => {
            messageEl.textContent = 'Login Success';
            modal.show();
            setTimeout(() => {
                modal.hide();
                window.location.href = "{{ route('drivers.index') }}";
            }, 2000);
        })
        .catch(() => {
            messageEl.textContent = 'Login Fail';
            modal.show();
            setTimeout(() => {
                modal.hide();
                form.reset();
            }, 2000);
        });
    });
});
</script>
@endsection
