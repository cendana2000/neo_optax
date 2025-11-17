<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= base_url() ?>" class="app-brand-link">
            <!--
            <span class="app-brand-logo demo">
                <img width="24" src="<?= base_url('assets/img/avatars/robot1.jpg') ?>">
            </span>
            -->
            <span class="app-brand-text demo menu-text fw-bolder ms-2">OPTAX SYNC</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <!--
        <li class="menu-item">
            <a href="<?= base_url() ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">DASHBOARD</div>
            </a>
        </li>
        -->

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">HALAMAN ADMIN</span>
        </li>
        <li class="menu-item">
            <a href="<?= base_url('admin/daftar_setting') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-cube-alt"></i>
                <div>Daftar Pengaturan</div>
            </a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</aside>
<!-- / Menu -->