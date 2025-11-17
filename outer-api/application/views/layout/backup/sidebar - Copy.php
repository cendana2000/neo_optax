<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="<?= base_url() ?>" class="app-brand-link">
            <!--
            <span class="app-brand-logo demo">
                <img width="24" src="<?= base_url('assets/img/avatars/robot1.jpg') ?>">
            </span>
            -->
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Bapenda Mbois</span>
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
            <a href="<?= base_url('admin/daftar_undian') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-cube-alt"></i>
                <div>Daftar Undian</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxs-data"></i>
                <div data-i18n="Account Settings">Master</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('master/daftar_alasan_tolak') ?>" class="menu-link">
                        <div data-i18n="Account">Daftar Tolak</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div data-i18n="Account Settings">Akun</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('user_akun/daftar_akun') ?>" class="menu-link">
                        <div data-i18n="Account">Daftar Akun</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('user_akun/form_pendaftaran') ?>" class="menu-link">
                        <div data-i18n="Account">Register Akun</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item">
            <a href="<?= base_url('report') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div>Report</div>
            </a>
        </li>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</aside>
<!-- / Menu -->