<link rel="stylesheet" href="<?= base_url('assets/css/custom_statusdevice.css'); ?>">
<div class="row table_data">
    <div class="container mt-4">
        <h3 class="page-title-custom">Rekap Pajak <i class="fa-solid fa-file-signature"></i></h3>
        <!-- FILTER CARD -->
        <div class="filter-card card card-premium mb-5">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fa-solid fa-filter me-2 text-primary"></i> Filter Rekap Pajak
                </h5>
                <div class="row g-3">
                    <!-- KECAMATAN -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Kecamatan</label>
                        <select class="form-select" id="select_kecamatan">
                            <option value="">-- Semua --</option>
                        </select>
                    </div>
                    <!-- JENIS PAJAK -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Jenis Pajak</label>
                        <select class="form-select" id="filter_jenis_pajak">
                            <option value="">-- Semua --</option>
                            <option value="PAJAK HOTEL">PAJAK HOTEL</option>
                            <option value="PAJAK RESTORAN">PAJAK RESTORAN</option>
                            <option value="PAJAK HIBURAN">PAJAK HIBURAN</option>
                            <option value="PAJAK PARKIR">PAJAK PARKIR</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Jenis Device</label>
                        <select class="form-select" id="filter_jenis_device">
                            <option value="">-- Semua --</option>
                            <option value="Website POS">Website POS</option>
                            <option value="Mobile POS">Mobile POS</option>
                            <option value="API Reader">API Reader</option>
                        </select>
                    </div>
                    <div class="col-md-12 mt-3 d-flex justify-content-end">
                        <button id="btnFilterRekap" class="btn btn-sm btn-primary fw-bold px-5">
                            <i class="fa-solid fa-search me-2"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-custom">
                <div class="card-header d-flex align-items-center">
                    <h4 class="fw-bold mb-0">Daftar Rekap Pajak</h4>
                    <div class="ml-auto dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="exportDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a class="dropdown-item" href="javascript:getExcel()"><i class="far fa-file-excel text-success me-2"></i> Excel</a></li>
                            <li><a class="dropdown-item" href="javascript:getPdf()"><i class="far fa-file-pdf text-danger me-2"></i> PDF</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-premium table-bordered table-hover" id="table-rekappajak">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>NPWPD</th>
                                    <th>Objek Pajak</th>
                                    <th>Jenis Pajak</th>
                                    <th>Kecamatan</th>
                                    <th>Transaksi Terakhir</th>
                                    <th>Jenis Device</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>NPWPD</th>
                                    <th>Objek Pajak</th>
                                    <th>Jenis Pajak</th>
                                    <th>Kecamatan</th>
                                    <th>Transaksi Terakhir</th>
                                    <th>Jenis Device</th>
                                    <th>Detail</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Filter Tanggal -->
<div class="modal fade" id="modalPeriode" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="la la-calendar mr-2 text-primary"></i> Periode
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- LEFT : QUICK RANGE -->
                    <div class="col-md-4 border-right">
                        <h6 class="mb-3 font-weight-bold">Range</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-range" data-range="today">Today</li>
                            <li class="list-group-item list-range" data-range="yesterday">Yesterday</li>
                            <li class="list-group-item list-range" data-range="7">Last 7 days</li>
                            <li class="list-group-item list-range" data-range="30">Last 30 days</li>
                            <li class="list-group-item list-range" data-range="90">Last 90 days</li>
                            <li class="list-group-item list-range" data-range="365">Last 365 days</li>
                        </ul>
                    </div>
                    <!-- RIGHT -->
                    <div class="col-md-8">
                        <h6 class="mb-3 font-weight-bold">Custom Range</h6>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="customRange"
                                placeholder="Pilih range tanggal">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="la la-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            Gunakan custom range untuk memilih tanggal tertentu
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-sm" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal PDF -->
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
<?php load_view('form') ?>
<?php load_view('formedit') ?>
<?php load_view('javascript') ?>