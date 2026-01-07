<div class="row table_data">
    <div class="col-md-12">
        <div class="card card-custom mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Rekonsiliasi</h4>
            </div>
            <form id="form-search" method="post">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Tahun</label>
                        <div class="input-group">
                            <input type="text" class="form-control yearpicker" name="tahun" id="tahun" value="<?= date('Y') ?>" placeholder="Pilih Tahun" required />
                            <div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-custom mb-3">
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
                    <table class="table table-premium table-bordered table-hover" id="table-rekonsiliasi">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2" style="width:5%;">No.</th>
                                <th class="text-center" rowspan="2">NPWPD</th>
                                <th class="text-center" rowspan="2">Objek Pajak</th>
                                <th class="text-center" colspan="3">Januari</th>
                                <th class="text-center" colspan="3">Februari</th>
                                <th class="text-center" colspan="3">Maret</th>
                                <th class="text-center" colspan="3">April</th>
                                <th class="text-center" colspan="3">Mei</th>
                                <th class="text-center" colspan="3">Juni</th>
                                <th class="text-center" colspan="3">Juli</th>
                                <th class="text-center" colspan="3">Agustus</th>
                                <th class="text-center" colspan="3">September</th>
                                <th class="text-center" colspan="3">Oktober</th>
                                <th class="text-center" colspan="3">November</th>
                                <th class="text-center" colspan="3">Desember</th>
                            </tr>
                            <tr>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <th class="text-center">Optax</th>
                                    <th class="text-center">Pembayaran</th>
                                    <th class="text-center">Selisih</th>
                                <?php endfor ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
                    <h3 class="card-label">Rekonsiliasi</h3>
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

<?php $this->load->view('Javascript');
