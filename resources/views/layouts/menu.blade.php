<style>
    .menu-item.active > .menu-link {
        background-color: #f0f0f0;
        border-left: 3px solid #0d6efd; 
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder"><?= 'SoFin' ?></span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        <!--  Dashboard -->
        <li class="menu-item {{ request()->routeIs('drivers.index') ? 'active' : '' }}">
            <a href="{{ route('drivers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <!--  หน้า add Admin -->
        @if(auth('admin')->check() && auth('admin')->user()->is_super)
            <li class="menu-item {{ request()->routeIs('admins.index') ? 'active' : '' }}">
                <a href="{{ route('admins.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div data-i18n="Analytics">Admin</div>
                </a>
            </li>
        @endif

    </ul>
</aside>