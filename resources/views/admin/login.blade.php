@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Login</h1>
    <form method="POST" action="{{ route('admin.login.submit') }}">
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
</div>
@endsection
