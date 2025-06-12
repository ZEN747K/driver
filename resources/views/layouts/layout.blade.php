<!DOCTYPE html>
<html lang="th" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>SoFin</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Sans+Thai:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <style>
        body {
            font-family: "Noto Sans Thai", sans-serif;
            font-optical-sizing: auto;
        }
    </style>
    @yield('style')
</head>
<body>
    <audio id="notifySound" src="{{ asset('sound/test.mp3') }}" preload="auto"></audio>

    <!-- วางโค้ดแสดง SweetAlert โดยใช้ session flash message ไว้ภายใน body -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: '{{ Session::get("success") }}',
                });
            @endif

            @if(Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: '{{ Session::get("error") }}',
                });
            @endif
        });
    </script>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.menu')
            <div class="layout-page">
                @include('layouts.navheader')

                <div class="content-wrapper">
                    <div class="container-xxl py-4">
                        <div class="card shadow-sm border-0" style="background-color: #ffffff; min-height: 80vh;">
                            <div class="card-body d-flex flex-column justify-content-between" style="min-height: 80vh;">
                                <div class="flex-grow-1">
                                    @yield('content')
                                </div>

                                <footer class="mt-4 pt-3 border-top text-center text-muted small"></footer>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="mt-4 pt-3 border-top text-center text-muted fs-5">
                    © <script>document.write(new Date().getFullYear());</script>, So Fin By So Smart Solution
                </footer>
                <div class="content-backdrop fade"></div>
            </div>
        </div>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @yield('script')
</body>
</html>