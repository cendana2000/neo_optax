<link rel="stylesheet" href="<?= base_url('assets/css/custom_statusdevice.css'); ?>">
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
                        <div class="label-summary">ONLINE</div>
                        <div class="value" id="sum-device-online">0</div>
                    </div>
                </div>
                <div class="summary-box box-yellow">
                    <i class="fa-solid fa-circle-exclamation summary-icon"></i>
                    <div class="box-info">
                        <div class="label-summary">WARNING</div>
                        <div class="value" id="sum-device-warning">0</div>
                    </div>
                </div>
                <div class="summary-box box-red">
                    <i class="fa-solid fa-circle-xmark summary-icon"></i>
                    <div class="box-info">
                        <div class="label-summary">OFFLINE</div>
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
                        <div class="label-summary">ACTIVE</div>
                        <div class="value" id="sum-data-active">0</div>
                    </div>
                </div>
                <div class="summary-box box-yellow">
                    <i class="fa-solid fa-circle-exclamation summary-icon"></i>
                    <div class="box-info">
                        <div class="label-summary">INACTIVE</div>
                        <div class="value" id="sum-data-inactive">0</div>
                    </div>
                </div>
                <div class="summary-box box-red">
                    <i class="fa-solid fa-circle-xmark summary-icon"></i>
                    <div class="box-info">
                        <div class="label-summary">OFFLINE</div>
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
        <div class="card-header d-flex align-items-center">
            <h4 class="fw-bold mb-0">Daftar Status Device</h4>
            <div class="ml-auto dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-file-export mr-1"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="javascript:getExcel()">Excel</a></li>
                    <li><a class="dropdown-item" href="javascript:getPdf()">PDF</a></li>
                </ul>
            </div>
            <div class="col-2" style="display: none;">
                <div class="mb-3"> <label for="select_toko" class="form-label fw-semibold text-dark">Pilih Toko</label> <select class="form-select" id="select_toko" name="code_store">
                        <option value="">-- Pilih Toko --</option>
                    </select> </div>
                <div class="mb-3">
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

<?php load_view('Javascript') ?>