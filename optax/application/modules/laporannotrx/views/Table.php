<div class="row table_data">
    <div class="container mt-4">
        <h3 class="page-title-custom">Laporan Tidak Ada Transaksi</h3>
        <!-- FILTER CARD -->
        <div class="filter-card card card-premium mb-5">
            <form id="form-laporan-no-trx">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-filter me-2 text-primary"></i> Filter
                    </h5>
                    <div class="row g-3">
                        <!-- KECAMATAN -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Periode</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterange" name="periode" id="periode" value="" placeholder="Pilih Bulan" />
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="wajibpajak_id">Wajib Pajak</label>
                            <select class="form-select" id="wajibpajak_id">
                                <option value="">- Semua -</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="kecamatan_id">Kecamatan</label>
                            <select class="form-select" id="kecamatan_id">
                                <option value="">- Semua -</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark" for="kelurahan_id">Kelurahan</label>
                            <select class="form-select" id="kelurahan_id">
                                <option value="">- Semua -</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary fw-bold">
                        <i class="fa-solid fa-search me-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
        <div class="col">
            <div class="card card-custom">
                <div class="card-header d-flex align-items-center">
                    <h4 class="fw-bold mb-0"><i class="fa fa-table"></i> Hasil</h4>
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
                        <table class="table table-premium table-bordered table-hover" id="table-no-trx">
                            <thead>
                                <tr>
                                    <th style="width:5%;">No.</th>
                                    <th>Tanggal</th>
                                    <th>NPWPD</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 report_data_pdf" style="display: none;">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fa fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">Laporan Tidak Ada Transaksi</h3>
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

<?php $this->load->view('javascript'); ?>