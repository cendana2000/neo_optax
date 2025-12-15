<style>
    /* ====== TITLE STYLE ======= */
    .page-title-custom {
        font-size: 25px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        text-align: center;
        color: #1a202c;
        margin-bottom: 25px;
    }

    /* ====== CARD STYLE ======= */
    .card-premium {
        border: none !important;
        border-radius: 14px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .card-premium .card-header {
        background: #ffffff !important;
        border-bottom: 1px solid #e3e6ef;
        padding: 20px;
    }

    /* ====== STATUS BOX ======= */
    .status-box {
        width: 200px;
        padding: 10px 18px;
        font-weight: 700;
        font-size: 14px;
        border-radius: 5px;
        display: inline-block;
        min-width: 140px;
        text-align: center;
        color: #fff;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.12);
    }

    .status-online {
        background: linear-gradient(135deg, #00b36b 0%, #00d983 100%);
        animation: pulseGreen 1.5s infinite;
    }

    .status-offline {
        background: linear-gradient(135deg, #e63946 0%, #ff5162 100%);
    }

    .status-idle {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    .status-dark {
        background: linear-gradient(135deg, #242424ff, #0e0e0eff);
    }

    /* ====== TABLE STYLING ======= */
    .table-premium thead th {
        background: #f3f4f6 !important;
        font-weight: 800 !important;
        color: #374151;
        font-size: 14px;
        text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb !important;
    }

    .table-premium tbody td {
        padding: 14px 12px !important;
        vertical-align: middle;
    }

    /* Hover effect */
    .table-premium tbody tr:hover {
        background: #edf2f7 !important;
    }

    /* Info button style */
    .btn-info-custom {
        background: #2563eb;
        padding: 6px 14px;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-info-custom:hover {
        background: #1d4ed8;
    }

    .summary-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 26px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: #ffffff;
        padding: 20px 20px;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        overflow: hidden;
    }

    .summary-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .summary-row {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    /* ukuran box mengikuti status-box datatable */
    .summary-box {
        width: auto;
        min-width: 135px;
        padding: 10px 10px;
        display: flex;
        align-items: center;
        color: #fff;
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.12);
    }

    .summary-icon {
        font-size: 19px;
        margin-right: 14px;
    }

    .box-info {
        text-align: right;
        width: 100%;
    }

    .label {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: -4px;
    }

    .value {
        font-size: 25px;
        font-weight: 800;
        margin-top: 0px;
    }

    /* warna solid */
    .box-green {
        background: #109748ff;
    }

    .box-yellow {
        background: #f1c40f;
    }

    .box-red {
        background: #e74c3c;
    }

    /* efek hover */
    .summary-box:hover {
        transform: none;
        /* atau minimal scale saja */
        filter: brightness(1.05);
    }

    .filter-card {
        border-radius: 18px;
        padding: 10px 15px;
    }

    .filter-card .form-label {
        font-size: 0.9rem;
    }

    .filter-card select {
        border-radius: 10px;
        padding: 10px;
    }

    #btnFilter {
        padding: 12px 30px;
        border-radius: 12px;
        font-size: 1rem;
    }

    /* ====== ANIMATION ======= */
    @keyframes pulseGreen {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 200, 120, .7);
        }

        70% {
            transform: scale(1.02);
            box-shadow: 0 0 0 10px rgba(0, 200, 120, 0);
        }

        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(0, 200, 120, 0);
        }
    }

    @media (max-width: 1400px) {
        .summary-wrapper {
            grid-template-columns: 2fr;
        }

        .summary-row {
            gap: 12px;
            justify-content: center;
            align-items: center;
        }

        .summary-box {
            width: 250px;
            min-width: 250px;
            padding: 10px 10px;
            display: flex;
            align-items: center;
            color: #fff;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.12);
        }

        .summary-card {
            background: #ffffff;
            padding: 20px 20px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .label {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: -4px;
        }

        .value {
            font-size: 26px;
            font-weight: 800;
            margin-top: 0px;
        }
    }

    @media (max-width: 768px) {
        .summary-row {
            flex-direction: column;
            gap: 12px;
            justify-content: center;
            align-items: center;
        }
    }
</style>

<div class="container mt-4">
    <h3 class="page-title-custom">Status Device <i class="fa-solid fa-laptop-file"></i></h3>

    <div class="summary-wrapper">
        <!-- DEVICE SUMMARY -->
        <div class="summary-card">
            <h5 class="summary-title">
                <i class="fa-solid fa-mobile-screen me-2"></i> Device Summary
            </h5>
            <div class="summary-row">
                <div class="summary-box box-green">
                    <i class="fa-solid fa-circle-check summary-icon"></i>
                    <div class="box-info">
                        <div class="label">ONLINE</div>
                        <div class="value" id="sum-device-online">0</div>
                    </div>
                </div>
                <div class="summary-box box-yellow">
                    <i class="fa-solid fa-circle-exclamation summary-icon"></i>
                    <div class="box-info">
                        <div class="label">WARNING</div>
                        <div class="value" id="sum-device-warning">0</div>
                    </div>
                </div>
                <div class="summary-box box-red">
                    <i class="fa-solid fa-circle-xmark summary-icon"></i>
                    <div class="box-info">
                        <div class="label">OFFLINE</div>
                        <div class="value" id="sum-device-offline">0</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TRANSACTION SUMMARY -->
        <div class="summary-card">
            <h5 class="summary-title">
                <i class="fa-solid fa-chart-line me-2"></i> Transaction Summary
            </h5>
            <div class="summary-row">
                <div class="summary-box box-green">
                    <i class="fa-solid fa-circle-check summary-icon"></i>
                    <div class="box-info">
                        <div class="label">ACTIVE</div>
                        <div class="value" id="sum-data-active">0</div>
                    </div>
                </div>
                <div class="summary-box box-yellow">
                    <i class="fa-solid fa-circle-exclamation summary-icon"></i>
                    <div class="box-info">
                        <div class="label">INACTIVE</div>
                        <div class="value" id="sum-data-inactive">0</div>
                    </div>
                </div>
                <div class="summary-box box-red">
                    <i class="fa-solid fa-circle-xmark summary-icon"></i>
                    <div class="box-info">
                        <div class="label">OFFLINE</div>
                        <div class="value" id="sum-data-offline">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="filter-card card card-premium mb-5">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="fa-solid fa-filter me-2 text-primary"></i> Filter Status Device
            </h5>
            <div class="row g-3">
                <!-- STATUS DEVICE -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Status Device</label>
                    <select class="form-select" id="filter_status_device">
                        <option value="">-- Semua --</option>
                        <option value="online">Online</option>
                        <option value="warning">Warning</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>

                <!-- STATUS DATA -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Status Data</label>
                    <select class="form-select" id="filter_status_data">
                        <option value="">-- Semua --</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>

                <!-- JENIS DEVICE -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-dark">Jenis Device</label>
                    <select class="form-select" id="filter_jenis_device">
                        <option value="">-- Semua --</option>
                        <option value="API Reader">API Reader</option>
                        <option value="Website POS">Website POS</option>
                        <option value="Mobile POS">Mobile POS</option>
                        <!-- <option value="Desktop Pooling">Desktop Pooling</option> //save dulu nanti kalau sudah ada wp pakai desktop pooling -->
                    </select>
                </div>
                <div class="col-md-12 mt-3 d-flex justify-content-end">
                    <button id="btnFilter" class="btn btn-primary fw-bold px-5">
                        <i class="fa-solid fa-search me-2"></i> Terapkan Filter
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="card card-premium">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h4 class="fw-bold mb-0">Daftar Status Device</h4>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-file-export me-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="javascript:getExcel()">Excel</a></li>
                    <li><a class="dropdown-item" href="javascript:getPdf()">PDF</a></li>
                </ul>
            </div>
            <div class="col-12">
                <div class="mb-3 d-none"> <label for="select_toko" class="form-label fw-semibold text-dark">Pilih Toko</label> <select class="form-select" id="select_toko" name="code_store">
                        <option value="">-- Pilih Toko --</option>
                    </select> </div>
                <div class="mb-3 d-none">
                    <label class="form-label">Tanggal</label>
                    <input class="form-control" value="<?= date('Y-m-d') ?>" type="text" id="tanggal" name="tanggal" />
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-premium table-bordered table-hover" id="table-lastactivity">
                <thead>
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Wajib Pajak</th>
                        <th class="text-nowrap">Perangkat</th>
                        <th class="text-nowrap">Status Device</th>
                        <th class="text-nowrap">Status Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-3 report_data_pdf" style="display: none;">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">DATA LAST ACTIVITY (POS MOBILE)</h3>
                </div>

                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div class="kt-portlet kt-portlet--mobile ">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">

                            </h3>
                        </div>
                    </div>
                    <div class="kt-form" id="pdf-laporan">
                        <div class="kt-portlet__body form" id="pdf-laporan object">
                            <object data="" type="application/pdf" width="100%" height="500px"></object>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet kt-portlet--mobile"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail</h4>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <div class="fw-bold">Device Model</div>
                    <div data-value="device_model">-</div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold">Tempat Usaha</div>
                    <div data-value="toko">-</div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold">Telp</div>
                    <div data-value="wp_telp">-</div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold">Alamat</div>
                    <div data-value="wp_alamat">-</div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold">Statistik Hari Ini</div>
                    <div id="penggunaan-hari-ini"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    table.dataTable>thead>tr>td:not(.sorting_disabled),
    table.dataTable>thead>tr>th:not(.sorting_disabled) {
        padding-right: 1.25rem;
    }
</style>

<?php load_view('javascript') ?>