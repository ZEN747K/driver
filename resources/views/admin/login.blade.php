@extends('layouts.sneat')

@section('title', 'Admin Login')

@section('content')
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center">
            <a href="#" class="app-brand-link gap-2">
              <span class="app-brand-text demo text-header fw-bolder">SoFin</span>
            </a>
          </div>

          <h4 class="mb-2">เข้าสู่ระบบ Admin</h4>

          <form id="login-form" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" name="email" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input id="password" type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      @if ($errors->any())
        Swal.fire({
          icon: 'error',
          title: 'เกิดข้อผิดพลาด',
          html: `{!! implode("<br>", $errors->all()) !!}`
        });
      @endif

      @if (Session::has('login_error'))
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: "{{ Session::get('login_error') }}"
        });
      @endif

      @if (Session::has('login_success'))
        Swal.fire({
          icon: 'success',
          title: 'Success',
          text: "{{ Session::get('login_success') }}"
        });
      @endif
    });
  </script>
@endsection
