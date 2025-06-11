<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" data-theme="theme-default">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Driver Admin</title>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/css/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}">
    @stack('head')
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <nav class="layout-navbar navbar navbar-expand-lg align-items-center bg-navbar-theme">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('drivers.index') }}">Admin Panel</a>
                    <ul class="navbar-nav ms-auto">
                        @auth('admin')
                        <li class="nav-item d-flex align-items-center me-2">
                            {{ auth('admin')->user()->email }}
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link px-2">Logout</button>
                            </form>
                        </li>
                        @endauth
                    </ul>
                </div>
            </nav>
            <div class="layout-page">
                <div class="content-wrapper">
                    @if(session('success'))
                        <div class="container-xxl">
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <footer class="content-footer footer bg-light">
                        <div class="container-xxl">
                            <div class="footer-container d-flex justify-content-between py-2 flex-md-row flex-column">
                                <div>© {{ date('Y') }} Sneat</div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('vendor/js/menu.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
