<style>
    .bg-menu-theme .menu-header:before {
        width: 0rem !important;
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
    <div class="menu-inner-shadow">

    </div>
    <ul class="menu-inner py-1">
    <li class="menu-item">
        <a href="{{ route('drivers.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-circle"></i>
            <div data-i18n="Analytics">Dashboard</div>
        </a>
    </li>

    @if(auth('admin')->check() && auth('admin')->user()->is_super)
    <li class="menu-item">
        <a href="{{ route('admins.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user"></i>
            <div data-i18n="Analytics"> Admin </div>
        </a>
    </li>
@endif

</ul>

</aside>
