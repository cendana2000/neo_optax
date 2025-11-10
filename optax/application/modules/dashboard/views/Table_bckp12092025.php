<link rel="stylesheet" href="<?= base_url('assets/css/custom_dashboard.css'); ?>">

<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div
                id="kt_app_toolbar_container"
                class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div
                    class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1
                        class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard Pemerintah Daerah
                    </h1>
                    <!--end::Title-->

                    <!--begin::Breadcrumb-->
                    <ul
                        class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a
                                href="https://preview.keenthemes.com/metronic8/demo1/index.html"
                                class="text-muted text-hover-primary">
                                Home
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Dashboards</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!-- Custom Filters -->
                <!-- <div class="menu-item p-0 m-0">					
					<a href="https://preview.keenthemes.com/metronic8/demo1/asides/aside-1.html" class="menu-link ">
						<span class="menu-bullet"><span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span></span>
						<span class="menu-title">Filters</span>
					</a>					
				</div> -->
                <div class="dropdown dropdown-inline dd-filter" data-toggle="tooltip" title="Quick actions" data-placement="left">
                    <a href="#" class="btn btn-warning btn-filter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-calendar text-white px-4"></i>
                        <span class="pr-4">Filter</span>
                    </a>
                    <div class="dropdown-menu mt-1 p-0 m-0 dropdown-menu-md dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi navi-hover">
                            <li class="navi-header font-weight-bold py-4">
                                <span class="font-size-lg">Tampilkan berdasarkan:</span>
                            </li>
                            <li class="navi-separator mb-3 opacity-70"></li>
                            <!-- Date Picker Start -->
                            <li class="navi-item p-2">
                                <form id="tanggal" name="tanggal" action="javascript:filter()">
                                    <div class="form-group" id='weekly'>
                                        <label style="font-size: 12px;">Tanggal :</label>
                                        <input type="date" name="awal_tanggal" id="awal_tanggal" class="form-control m" value="<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-30 day'), 'Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label style="font-size: 12px;">Sampai Dengan :</label>
                                        <input type="date" name="akhir_tanggal" id="akhir_tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <!-- <div class="form-group">
									<label style="font-size: 12px;">Bulan :</label>
									<input type="month" class="form-control" name="bulan" id="bulan"
										value="<?php echo date('Y-m') ?>">
								</div> -->
                                    <div class="form-group">
                                        <button type="submit" id="submit-btn" class="btn btn-blue btn-sm my-0 w-100">
                                            <span class="fas fa-search" style="margin-right: 15px;"></span>
                                            Tampilkan
                                        </button>
                                    </div>
                                </form>
                            </li>
                            <!-- Date Picker Ebd -->
                        </ul>
                        <!--end::Navigation-->
                    </div>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div
                id="kt_app_content_container"
                class="app-container container-xxl">
                <!--begin::Row-->
                <div class="row gx-5 gx-xl-10 mb-xl-10">
                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
                        <!--begin::Card widget 7-->
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Amount-->
                                    <span id="total_wajib_pajak"
                                        class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                    <!--end::Amount-->

                                    <!--begin::Subtitle-->
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Objek Pajak Terkoneksi</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Card body-->
                            <div
                                class="card-body d-flex flex-column justify-content-end pe-0">
                                <!--begin::Title-->
                                <span id="toko_baru_total"
                                    class="fs-6 fw-bolder text-gray-800 d-block mb-2">Tempat Usaha Terbaru</span>
                                <!--end::Title-->

                                <!--begin::Users group-->
                                <div class="symbol-group symbol-hover flex-nowrap">
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Alan Warden">
                                        <span
                                            class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                                    </div>
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Michael Eberon">
                                        <!-- <img
											alt="Pic"
											src="assets/assets_custom/media/avatars/300-11.jpg" /> -->
                                        <img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
                                    </div>
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Susan Redwood">
                                        <span
                                            class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                                    </div>
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Melody Macy">
                                        <!-- <img
											alt="Pic"
											src="assets/assets_custom/media/avatars/300-2.jpg" /> -->
                                        <img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
                                    </div>
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Perry Matthew">
                                        <span
                                            class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
                                    </div>
                                    <div
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="tooltip"
                                        title="Barry Walter">
                                        <!-- <img
											alt="Pic"
											src="assets/assets_custom/media/avatars/300-12.jpg" /> -->
                                        <img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
                                    </div>
                                    <a
                                        href="#"
                                        class="symbol symbol-35px symbol-circle"
                                        data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_view_users">
                                        <span
                                            class="symbol-label bg-light text-gray-400 fs-8 fw-bold">+42</span>
                                    </a>
                                </div>
                                <!--end::Users group-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 7-->

                        <!--begin::Card widget 4-->
                        <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">

                                        <!--begin::Amount-->
                                        <span id="total_transaksi"
                                            class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                        <!--end::Amount-->

                                        <!--begin::Badge-->
                                        <!-- <span class="badge badge-light-success fs-base">
											<i
												class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
											2.2%
										</span> -->
                                        <!--end::Badge-->
                                    </div>
                                    <!--end::Info-->

                                    <!--begin::Subtitle-->
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Transaksi</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Card body-->
                            <div
                                class="card-body pt-2 pb-4 d-flex align-items-center">
                                <!--begin::Chart-->
                                <div class="d-flex flex-center me-5 pt-2">
                                    <div
                                        id="kt_card_widget_4_chart"
                                        style="min-width: 70px; min-height: 70px"
                                        data-kt-size="70"
                                        data-kt-line="11"></div>
                                </div>
                                <!--end::Chart-->

                                <!--begin::Labels-->
                                <div
                                    class="d-flex flex-column content-justify-center w-100">
                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Restoran
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->

                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center my-3">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Hotel
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->

                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 me-3"
                                            style="background-color: #e4e6ef"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Lainnya
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 4-->

                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
                        <!--begin::List widget 25-->
                        <div class="card card-flush h-lg-50 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <h3 class="card-title text-gray-800">Total Objek Pajak</h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar d-none">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" class="btn btn-sm btn-light d-flex align-items-center px-4">
                                        <!--begin::Display range-->
                                        <div class="text-gray-600 fw-bold">
                                            Loading date range...
                                        </div>
                                        <!--end::Display range-->

                                        <i class="ki-duotone ki-calendar-8 fs-1 ms-2 me-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                    </div>
                                    <!--end::Daterangepicker-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body pt-5">
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Restoran</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span id="total_wp_resto" class="text-gray-900 fw-bolder fs-6">0</span>
                                        <!--end::Number-->

                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Hotel</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-down-right fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span id="total_wp_hotel" class="text-gray-900 fw-bolder fs-6">0</span>
                                        <!--end::Number-->


                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Separator-->
                                <div class="separator separator-dashed my-3"></div>
                                <!--end::Separator-->

                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <!--begin::Section-->
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Lainnya</div>
                                    <!--end::Section-->

                                    <!--begin::Statistics-->
                                    <div class="d-flex align-items-senter">
                                        <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>

                                        <!--begin::Number-->
                                        <span class="text-gray-900 fw-bolder fs-6">0</span>
                                        <!--end::Number-->


                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Item-->



                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::LIst widget 25-->

                        <!--begin::Card widget 5-->
                        <div class="card card-flush h-md-50 mb-xl-10">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Amount-->
                                        <span id="total_pajak_masuk"
                                            class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">0</span>
                                        <!--end::Amount-->

                                    </div>
                                    <!--end::Info-->

                                    <!--begin::Subtitle-->
                                    <span class="text-gray-500 pt-1 fw-semibold fs-6">Total Pajak Masuk</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Card body-->
                            <div
                                class="card-body pt-2 pb-4 d-flex align-items-center">
                                <!--begin::Chart-->
                                <div class="d-flex flex-center me-5 pt-2">
                                    <div
                                        id="kt_card_widget_4_chart"
                                        style="min-width: 70px; min-height: 70px"
                                        data-kt-size="70"
                                        data-kt-line="11"></div>
                                </div>
                                <!--end::Chart-->

                                <!--begin::Labels-->
                                <div
                                    class="d-flex flex-column content-justify-center w-100">
                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Restoran
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->

                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center my-3">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Hotel
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->

                                    <!--begin::Label-->
                                    <div
                                        class="d-flex fs-6 fw-semibold align-items-center">
                                        <!--begin::Bullet-->
                                        <div
                                            class="bullet w-8px h-6px rounded-2 me-3"
                                            style="background-color: #e4e6ef"></div>
                                        <!--end::Bullet-->

                                        <!--begin::Label-->
                                        <div class="text-gray-500 flex-grow-1 me-4">
                                            Lainnya
                                        </div>
                                        <!--end::Label-->

                                        <!--begin::Stats-->
                                        <div class="fw-bolder text-gray-700 text-xxl-end">
                                            0
                                        </div>
                                        <!--end::Stats-->
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Labels-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 5-->
                    </div>
                    <!--end::Col-->

                    <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
                        <!--begin::Chart widget 3-->
                        <div class="card card-flush overflow-hidden h-md-100">
                            <!--begin::Header-->
                            <div class="card-header py-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Grafik Transaksi Objek Pajak</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-5">Pertumbuhan grafik beberapa bulan terakhir</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Menu-->
                                    <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                        <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    </button>

                                    <!--begin::Menu 2-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">
                                                Quick Actions
                                            </div>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu separator-->
                                        <div class="separator mb-3 opacity-75"></div>
                                        <!--end::Menu separator-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                New Ticket
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                New Customer
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                                            <!--begin::Menu item-->
                                            <a href="#" class="menu-link px-3">
                                                <span class="menu-title">New Group</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <!--end::Menu item-->

                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Admin Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Staff Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">
                                                        Member Group
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                New Contact
                                            </a>
                                        </div>
                                        <!--end::Menu item-->

                                        <!--begin::Menu separator-->
                                        <div class="separator mt-3 opacity-75"></div>
                                        <!--end::Menu separator-->

                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3 py-3">
                                                <a class="btn btn-primary btn-sm px-4" href="#">
                                                    Generate Reports
                                                </a>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 2-->

                                    <!--end::Menu-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                                <!--begin::Statistics-->
                                <div class="px-9 mb-5">
                                    <!--begin::Statistics-->
                                    <div class="d-flex mb-2">
                                        <span id="total_pajak_masuk" class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">0</span>
                                    </div>
                                    <!--end::Statistics-->
                                </div>
                                <!--end::Statistics-->

                                <!--begin::Chart-->
                                <div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6" style="height: 300px; min-height: 315px;">
                                    <div id="apexchartsokxz9egx" class="apexcharts-canvas apexchartsokxz9egx apexcharts-theme-light" style="width: 991.5px; height: 300px;"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" width="991.5" height="300">
                                            <foreignObject x="0" y="0" width="991.5" height="300">
                                            </foreignObject>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <g class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                                            <g class="apexcharts-yaxis" rel="0" transform="translate(37.765625, 0)">
                                                <g class="apexcharts-yaxis-texts-g"><text x="20" y="34" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
                                                        <tspan>$24K</tspan>
                                                        <title>$24K</title>
                                                    </text><text x="20" y="89.455" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
                                                        <tspan>$20.5K</tspan>
                                                        <title>$20.5K</title>
                                                    </text><text x="20" y="144.91" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
                                                        <tspan>$17K</tspan>
                                                        <title>$17K</title>
                                                    </text><text x="20" y="200.365" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
                                                        <tspan>$13.5K</tspan>
                                                        <title>$13.5K</title>
                                                    </text><text x="20" y="255.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-yaxis-label " style="font-family: inherit;">
                                                        <tspan>$10K</tspan>
                                                        <title>$10K</title>
                                                    </text></g>
                                            </g>
                                            <g class="apexcharts-inner apexcharts-graphical" transform="translate(67.765625, 30)">
                                                <defs>
                                                    <clipPath id="gridRectMaskokxz9egx">
                                                        <rect width="920.734375" height="228.82" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectBarMaskokxz9egx">
                                                        <rect width="920.734375" height="228.82" x="-3.5" y="-3.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="gridRectMarkerMaskokxz9egx">
                                                        <rect width="920.734375" height="221.82" x="-3.5" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                    </clipPath>
                                                    <clipPath id="forecastMaskokxz9egx"></clipPath>
                                                    <clipPath id="nonForecastMaskokxz9egx"></clipPath>
                                                    <linearGradient x1="0" y1="0" x2="0" y2="1" id="SvgjsLinearGradient1004">
                                                        <stop stop-opacity="0.4" stop-color="rgba(23,198,83,0.4)" offset="0"></stop>
                                                        <stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="0.8"></stop>
                                                        <stop stop-opacity="0" stop-color="rgba(255,255,255,0)" offset="1"></stop>
                                                    </linearGradient>
                                                </defs>
                                                <g class="apexcharts-grid">
                                                    <g class="apexcharts-gridlines-horizontal">
                                                        <line x1="0" y1="55.455" x2="913.734375" y2="55.455" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="110.91" x2="913.734375" y2="110.91" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                        <line x1="0" y1="166.365" x2="913.734375" y2="166.365" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    </g>
                                                    <g class="apexcharts-gridlines-vertical"></g>
                                                    <line x1="0" y1="221.82" x2="913.734375" y2="221.82" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                    <line x1="0" y1="1" x2="0" y2="221.82" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                </g>
                                                <g class="apexcharts-grid-borders">
                                                    <line x1="0" y1="0" x2="913.734375" y2="0" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                    <line x1="0" y1="221.82" x2="913.734375" y2="221.82" stroke="#dbdfe9" stroke-dasharray="4" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                </g>
                                                <g class="apexcharts-area-series apexcharts-plot-series">
                                                    <g class="apexcharts-series" zIndex="0" seriesName="Sales" data:longestSeries="true" rel="1" data:realIndex="0">
                                                        <path d="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138C 913.7343750000001 31.68857142857138 913.7343750000001 31.68857142857138 913.7343750000001 221.82 L 0 221.82z" fill="url(#SvgjsLinearGradient1004)" fill-opacity="1" stroke="none" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskokxz9egx)" pathTo="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138C 913.7343750000001 31.68857142857138 913.7343750000001 31.68857142857138 913.7343750000001 221.82 L 0 221.82z" pathFrom="M 0 221.82 L 0 221.82 L 50.763020833333336 221.82 L 101.52604166666667 221.82 L 152.2890625 221.82 L 203.05208333333334 221.82 L 253.81510416666669 221.82 L 304.578125 221.82 L 355.34114583333337 221.82 L 406.1041666666667 221.82 L 456.86718750000006 221.82 L 507.63020833333337 221.82 L 558.3932291666667 221.82 L 609.15625 221.82 L 659.9192708333334 221.82 L 710.6822916666667 221.82 L 761.4453125 221.82 L 812.2083333333334 221.82 L 862.9713541666667 221.82 L 913.7343750000001 221.82z"></path>
                                                        <path d="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138" fill="none" fill-opacity="1" stroke="#17c653" stroke-opacity="1" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMaskokxz9egx)" pathTo="M 0 95.06571428571425C 17.767057291666667 95.06571428571425 32.99596354166667 95.06571428571425 50.763020833333336 95.06571428571425C 68.530078125 95.06571428571425 83.75898437500001 63.377142857142815 101.52604166666667 63.377142857142815C 119.29309895833333 63.377142857142815 134.52200520833333 63.377142857142815 152.2890625 63.377142857142815C 170.05611979166667 63.377142857142815 185.28502604166667 95.06571428571425 203.05208333333334 95.06571428571425C 220.81914062500002 95.06571428571425 236.048046875 95.06571428571425 253.81510416666669 95.06571428571425C 271.58216145833336 95.06571428571425 286.8110677083333 31.68857142857138 304.578125 31.68857142857138C 322.3451822916667 31.68857142857138 337.5740885416667 31.68857142857138 355.34114583333337 31.68857142857138C 373.10820312500005 31.68857142857138 388.337109375 63.377142857142815 406.1041666666667 63.377142857142815C 423.87122395833336 63.377142857142815 439.1001302083334 63.377142857142815 456.86718750000006 63.377142857142815C 474.63424479166673 63.377142857142815 489.8631510416667 95.06571428571425 507.63020833333337 95.06571428571425C 525.397265625 95.06571428571425 540.6261718750001 95.06571428571425 558.3932291666667 95.06571428571425C 576.1602864583334 95.06571428571425 591.3891927083333 63.377142857142815 609.15625 63.377142857142815C 626.9233072916667 63.377142857142815 642.1522135416667 63.377142857142815 659.9192708333334 63.377142857142815C 677.686328125 63.377142857142815 692.9152343750001 95.06571428571425 710.6822916666667 95.06571428571425C 728.4493489583334 95.06571428571425 743.6782552083333 95.06571428571425 761.4453125 95.06571428571425C 779.2123697916667 95.06571428571425 794.4412760416667 63.377142857142815 812.2083333333334 63.377142857142815C 829.975390625 63.377142857142815 845.2042968750001 63.377142857142815 862.9713541666667 63.377142857142815C 880.7384114583334 63.377142857142815 895.9673177083334 31.68857142857138 913.7343750000001 31.68857142857138" pathFrom="M 0 221.82 L 0 221.82 L 50.763020833333336 221.82 L 101.52604166666667 221.82 L 152.2890625 221.82 L 203.05208333333334 221.82 L 253.81510416666669 221.82 L 304.578125 221.82 L 355.34114583333337 221.82 L 406.1041666666667 221.82 L 456.86718750000006 221.82 L 507.63020833333337 221.82 L 558.3932291666667 221.82 L 609.15625 221.82 L 659.9192708333334 221.82 L 710.6822916666667 221.82 L 761.4453125 221.82 L 812.2083333333334 221.82 L 862.9713541666667 221.82 L 913.7343750000001 221.82" fill-rule="evenodd"></path>
                                                        <g class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown" data:realIndex="0">
                                                            <g class="apexcharts-series-markers">
                                                                <path d="M 0, 0 
																	m -0, 0 
																	a 0,0 0 1,0 0,0 
																	a 0,0 0 1,0 -0,0" fill="#17c653" fill-opacity="1" stroke="#17c653" stroke-opacity="0.9" stroke-linecap="butt" stroke-width="3" stroke-dasharray="0" cx="0" cy="0" shape="circle" class="apexcharts-marker wl1zeh2z7 no-pointer-events" default-marker-size="0"></path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                    <g class="apexcharts-datalabels" data:realIndex="0"></g>
                                                </g>
                                                <line x1="0" y1="0" x2="0" y2="221.82" stroke="#17c653" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="221.82" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line>
                                                <line x1="0" y1="0" x2="913.734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                                <line x1="0" y1="0" x2="913.734375" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                                <g class="apexcharts-xaxis" transform="translate(0, 0)">
                                                    <g class="apexcharts-xaxis-texts-g" transform="translate(0, -10)"><text x="0" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="50.76302083333333" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="101.52604166666667" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="152.28906250000003" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 153.412109375 238.32000732421875)" style="font-family: inherit;">
                                                            <tspan>Apr 04</tspan>
                                                            <title>Apr 04</title>
                                                        </text><text x="203.05208333333337" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="253.8151041666667" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="304.578125" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 305.677734375 238.32000732421875)" style="font-family: inherit;">
                                                            <tspan>Apr 07</tspan>
                                                            <title>Apr 07</title>
                                                        </text><text x="355.3411458333333" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="406.10416666666663" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="456.86718749999994" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 458.0751953125 238.32000732421875)" style="font-family: inherit;">
                                                            <tspan>Apr 10</tspan>
                                                            <title>Apr 10</title>
                                                        </text><text x="507.6302083333333" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="558.3932291666667" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="609.1562500000001" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 610.15625 238.32000732421875)" style="font-family: inherit;">
                                                            <tspan>Apr 13</tspan>
                                                            <title>Apr 13</title>
                                                        </text><text x="659.9192708333335" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="710.6822916666669" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="761.4453125000002" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 762.4453125 238.32000732421875)" style="font-family: inherit;">
                                                            <tspan>Apr 16</tspan>
                                                            <title>Apr 16</title>
                                                        </text><text x="812.2083333333336" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="862.971354166667" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text><text x="913.7343750000003" y="243.82" text-anchor="end" dominant-baseline="auto" font-size="12px" font-family="inherit" font-weight="400" fill="#99a1b7" class="apexcharts-text apexcharts-xaxis-label " transform="rotate(0 1 -1)" style="font-family: inherit;">
                                                            <tspan></tspan>
                                                            <title></title>
                                                        </text></g>
                                                </g>
                                                <g class="apexcharts-yaxis-annotations"></g>
                                                <g class="apexcharts-xaxis-annotations"></g>
                                                <g class="apexcharts-point-annotations"></g>
                                            </g>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                            <rect width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
                                        </svg>
                                        <div class="apexcharts-legend" style="max-height: 150px;"></div>
                                        <div class="apexcharts-tooltip apexcharts-theme-light">
                                            <div class="apexcharts-tooltip-title" style="font-family: inherit; font-size: 12px;"></div>
                                            <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0" style="order: 1;"><span class="apexcharts-tooltip-marker" shape="circle" style="color: rgb(23, 198, 83);"></span>
                                                <div class="apexcharts-tooltip-text" style="font-family: inherit; font-size: 12px;">
                                                    <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label"></span><span class="apexcharts-tooltip-text-y-value"></span></div>
                                                    <div class="apexcharts-tooltip-goals-group"><span class="apexcharts-tooltip-text-goals-label"></span><span class="apexcharts-tooltip-text-goals-value"></span></div>
                                                    <div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="apexcharts-xaxistooltip apexcharts-xaxistooltip-bottom apexcharts-theme-light">
                                            <div class="apexcharts-xaxistooltip-text" style="font-family: inherit; font-size: 12px;"></div>
                                        </div>
                                        <div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                            <div class="apexcharts-yaxistooltip-text"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Chart-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 3-->
                    </div>
                </div>
                <!--end::Row-->

                <!--begin::Row-->
                <div class="row gy-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::List widget 5-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Activity Users</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">Status online users</span>
                                </h3>
                                <!--end::Title-->

                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <a
                                        href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/sales/details.html"
                                        class="btn btn-sm btn-light">Details</a>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Scroll-->
                                <div
                                    class="hover-scroll-overlay-y pe-6 me-n6"
                                    style="height: 415px">
                                    <!--begin::Item-->

                                    <div class="grid-user" id="online-user">
                                        <!-- <div class="d-flex flex-stack mb-3">
											<div class="me-3">
												<img
													src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>"
													class="w-50px ms-n1 me-1"
													alt="" />
												<span class="text-gray-500 fw-bold">Nama_WP</span>
											</div>
										</div>
										<div class="d-flex flex-stack">
											<span class="text-gray-500 fw-bold">Status:
											</span>
											<span class="badge badge-light-success">Delivered</span>
										</div> -->
                                    </div>

                                    <!--end::Item-->
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 5-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <!--begin::Table Widget 5-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Transaksi Terakhir</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Table-->
                                <table
                                    class="table align-middle table-row-dashed fs-6 gy-3"
                                    id="kt_table_widget_5_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr
                                            class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">Objek Pajak</th>
                                            <th class="text-end pe-3 min-w-100px">
                                                NPWPD
                                            </th>
                                            <th class="text-end pe-3 min-w-150px">
                                                Sub Total
                                            </th>
                                            <th class="text-end pe-3 min-w-100px">Pajak</th>
                                            <th class="text-end pe-3 min-w-100px">
                                                Total
                                            </th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->

                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">Macbook Air M1</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#XGY-356</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">02 Apr, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$1,230</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="58">
                                                <span class="text-gray-900 fw-bold">58 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">Surface Laptop 4</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#YHD-047</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">01 Apr, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$1,060</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="0">
                                                <span class="text-gray-900 fw-bold">0 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">Logitech MX 250</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#SRR-678</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">24 Mar, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$64</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="290">
                                                <span class="text-gray-900 fw-bold">290 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">AudioEngine HD3</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#PXF-578</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">24 Mar, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$1,060</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-danger">Out of Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="46">
                                                <span class="text-gray-900 fw-bold">46 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">HP Hyper LTR</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#PXF-778</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">16 Jan, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$4500</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="78">
                                                <span class="text-gray-900 fw-bold">78 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">Dell 32 UltraSharp</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#XGY-356</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">22 Dec, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$1,060</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-warning">Low Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="8">
                                                <span class="text-gray-900 fw-bold">8 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                        <tr>
                                            <!--begin::Item-->
                                            <td>
                                                <a
                                                    href="https://preview.keenthemes.com/metronic8/demo1/apps/ecommerce/catalog/edit-product.html"
                                                    class="text-gray-900 text-hover-primary">Google Pixel 6 Pro</a>
                                            </td>
                                            <!--end::Item-->

                                            <!--begin::Product ID-->
                                            <td class="text-end">#XVR-425</td>
                                            <!--end::Product ID-->

                                            <!--begin::Date added-->
                                            <td class="text-end">27 Dec, 2025</td>
                                            <!--end::Date added-->

                                            <!--begin::Price-->
                                            <td class="text-end">$1,060</td>
                                            <!--end::Price-->

                                            <!--begin::Status-->
                                            <td class="text-end">
                                                <span
                                                    class="badge py-3 px-4 fs-7 badge-light-primary">In Stock</span>
                                            </td>
                                            <!--end::Status-->

                                            <!--begin::Qty-->
                                            <td class="text-end" data-order="124">
                                                <span class="text-gray-900 fw-bold">124 PCS</span>
                                            </td>
                                            <!--end::Qty-->
                                        </tr>
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Table Widget 5-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

    <!--begin::Footer-->
    <div id="kt_app_footer" class="app-footer">
        <!--begin::Footer container-->
        <div
            class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        </div>
        <!--end::Footer container-->
    </div>
    <!--end::Footer-->
</div>
<!--end:::Main-->


<!-- Start Card Section -->
<div class="row">
    <div class="col-lg-4">
        <div class="card card-custom  card-stretch gutter-b bg-warning text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/oren.svg'); ?>); ">
            <!--begin::Body-->
            <div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
                <div class="w-100 d-flex justify-content-between">
                    <div class="d-flex align-item-center">
                        <span class=" font-weight-bold">Total Pajak Masuk</span>
                    </div>
                    <!-- <span class="material-icons-outlined" role="button">
						more_vert
					</span> -->
                </div>
                <div class=" d-flex align-items-center">
                    <span id="total_pajak_masuk" class="card-title font-weight-bolder font-size-h2 mb-0 mr-3 h1">0</span>
                </div>
                <!-- <span class="font-weight-bold ">+2 Barang Baru Dalam 7 Hari</span> -->
            </div>
            <!--end::Body-->
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card card-custom card-stretch gutter-b bg-info text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/purple.svg'); ?>);">
            <!--begin::Body-->
            <div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
                <div class="w-100 d-flex justify-content-between">
                    <div class="d-flex align-item-center">
                        <span class=" font-weight-bold d-block">Total Realisasi Wajib Pajak</span>
                    </div>
                    <!-- <span class="material-icons-outlined curso" role="button">
						more_vert
					</span> -->
                </div>
                <div class="d-flex flex-column align-items-center">
                    <!-- <span id="" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h3">Total WP Resto : </span>
					<span id="total_wp_resto" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h3">0</span>
					<span id="" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h3">Total WP Hotel : </span>
					<span id="total_wp_hotel" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h3">0</span> -->
                    <span id="total_realisasi_wajib_pajak" class="card-title font-weight-bolder font-size-h2 mb-0 mr-3 h1">0</span>
                </div>
                <!-- <span class="font-weight-bold ">+4 Data Baru Dalam 7 Hari</span> -->
            </div>
            <!--end::Body-->
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom card-stretch gutter-b bg-primary text-white rounded-big card-pajak" style="background-image: url(<?= base_url('dokumen/dashboard_rzl/blue.svg'); ?>); ">
            <!--begin::Body-->
            <div class="card-body card-pajak d-flex flex-column align-items-start justify-content-between">
                <div class="w-100 d-flex justify-content-between">
                    <div class="d-flex align-item-center">
                        <span class=" font-weight-bold d-block">Total Wajib Pajak</span>
                    </div>
                    <!-- <span class="material-icons-outlined" role="button">
						more_vert
					</span> -->
                </div>
                <div class="d-flex align-items-center w-100">
                    <span id="total_wajib_pajak" class="card-title font-weight-bolder font-size-h1 mb-0 mr-40 h1">0</span>
                    <div class="d-flex" style="height: 10px; color: #FFF;">
                        <div class="vr"></div>
                    </div>
                    <div class="d-flex flex-column-reverse mr-7">
                        <span>Wajib Pajak Resto</span>
                        <span id="total_wp_resto" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h4">0</span>
                    </div>
                    <div class="d-flex flex-column-reverse mr-7">
                        <span>Wajib Pajak Hotel</span>
                        <span id="total_wp_hotel" class="card-title font-weight-bolder font-size-h3 mb-0 mr-3 h4">0</span>
                    </div>
                </div>
                <!-- <span class="font-weight-bold ">+2 Data Baru Dalam 7 Hari</span> -->
            </div>
            <!--end::Body-->
        </div>
    </div>
</div>
<!-- End Card Section -->

<!-- Dashboard Pajak -->
<div id="dashboardPajak">

    <!-- Start Panel Section-->
    <div class="row h-auto container-panel form-custom-padding">
        <div class="col-lg-4 gutter-b gutter-x dashboard">
            <div class="card card-custom gutter-b d-flex flex-column justify-content-between py-4 card_dashboard">
                <div class="h-auto d-flex flex-column ">
                    <div class="card-header border-b-2 h-25 pt-4">
                        <p class='font-weight-bolder text-dark h1 d-block'>Dashboard</p>
                        <div class="w-100 d-flex justify-content-between p-0 m-0">
                            <span class='text-muted'>Wajib Pajak Pengguna POS</span>
                        </div>
                    </div>
                    <div class="card card-custom gutter-b d-flex flex-column rounded-big" style="height: 465px;">
                        <div class="card-header h-auto border-0 mx-0">
                            <div class="card-title py-5 w-100 px-0 mx-0">
                                <h5 class="card-label d-flex flex-row w-100 justify-content-center align-items-center m-0 p-0">
                                    <div>
                                        <span class="d-block text-dark font-weight-bolder mb-4">Activity User</span>
                                        <span class="text-muted font-size-h6 mt-4" id="count-online-user-activity">6 Users</span>
                                    </div>
                                    <div class="ml-auto">
                                        <i role="button" class="far fa-question-circle" data-toggle="popover" title="Status Information" data-content='
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-success">
												lens
											</span>
											<span class="ml-auto">Active</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-warning">
												lens
											</span>
											<span class="ml-auto">Inactive</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-danger">
												lens
											</span>
											<span class="ml-auto">Offline</span>
										</div>
										<div class="d-flex flex-row align-items-center">
											<span class="material-icons status text-dark">
												lens
											</span>
											<span class="ml-auto">Close</span>
										</div>
										'></i>
                                    </div>
                                </h5>
                            </div>
                            <div class="w-100 pb-10 d-flex align-items-center">
                                <p class="w-25 pl-3">Foto</p>
                                <p class="w-75">Nama</p>
                            </div>
                        </div>
                        <div class="card-body pt-0" style="height:465px;overflow-y:auto;overflow-x:hidden;">
                            <div class="grid-user" id="online-user-activity">
                                <!-- <div class="card-user">
									<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" />
									<span class="nama">Dialoogi</span>
									<span class="material-icons status text-success">
										lens
									</span>
								</div> -->
                            </div>
                        </div>
                    </div>
                    <!-- <div class="card-header shadow-sm side-chart">
						<div class="w-100 d-flex justify-content-between p-0 m-0">
							<span class="text-black font-weight-bold">Target Pajak</span>
							<b id="target_pajak_tahun"></b>
						</div>
						<span id="target_pajak" class="font-weight-bolder text-dark h1 d-block">0</span>
					</div> -->
                    <!-- <div class="card-header shadow-sm side-chart">
						<div class="p-0 m-0">
							<span class="d-block text-black font-weight-bold">Pajak Belum dibayar</span>
						</div>
						<span id="pajak_belum_bayar" class="font-weight-bolder text-dark h1 d-block">0</span>
					</div> -->
                </div>
                <!-- <button class="btn btn-blue mb-4 align-self-center monitoring-btn">Monitoring Pajak</button> -->
            </div>
        </div>
        <div class="col-lg-8 card_chart">
            <div class="card card-custom card-stretch gutter-b card_chart">
                <div class="card-header border-0">
                    <div class="card-title py-5" id="filter-by">
                        <button class="btn-filter-by-period btn" data-filter="daily" id="daily-filter" onclick="filterByPeriod(this)">Daily</button>
                        <button class="btn-filter-by-period btn" data-filter="weekly" id="weekly-filter" onclick="filterByPeriod(this)">Weekly</button>
                        <button class="btn-filter-by-period btn active" data-filter="monthly" id="monthly-filter" onclick="filterByPeriod(this)">Monthly</button>
                    </div>
                    <div class="card-toolbar">
                        <div>
                            <select class="form-control select2" id="filter-jenis_usaha" onchange="filterJenisUsaha('tanggal',this)">
                            </select>
                        </div>
                        <div id="spinner-statistik-nominal d-none" class="spinner-border text-primary d-none mx-5" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="position: relative;">
                    <div id="chartrealisasipajak" style="min-height: 365px;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Panel Section -->

    <!-- Start Panel User dan Toko -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b d-flex flex-column pb-4 rounded-big" style="height: 500px;">
                <div class="card-header h-auto border-0">
                    <div class="card-title py-5">
                        <h5 class="card-label">
                            <span class="d-block text-dark font-weight-bolder mb-4">Tempat Usaha Baru</span>
                        </h5>
                    </div>
                </div>
                <div class="pt-0 card-body" style="max-height:430px; overflow-x: hidden; overflow-y: auto;">
                    <div id="toko_baru" class="grid-toko">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Panel User dan toko -->
</div>

<?php $this->load->view('javascript'); ?>