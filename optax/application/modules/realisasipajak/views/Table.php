<div class="row table_data">
    <div class="col">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">DATA REKAP PAJAK</h3>
                </div>
                <div class="card-toolbar">
                    <div class="mr-3 pb-3 pb-lg-0">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control monthpicker" name="bulan" id="bulan" onchange="filterBulan()" value="<?= date("Y-m") ?>" placeholder="Pilih Bulan" />
                            <div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm" onclick="getSpreadsheetRealisasi()"><i class="far fa-file-excel"></i> Excel</button>
                        <button class="btn btn-danger btn-sm" onclick="getPdfRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <h5>Jumlah WP Terdaftar</h5>
                    </div>
                    <div class="col-sm-5">
                        <h5 id="wp_terdaftar">-</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-realisasi">
                        <thead>
                            <tr>
                                <th style="width:5%;">No.</th>
                                <th>NPWPD</th>
                                <th>Nama NPWPD</th>
                                <th>Tanggal Transaksi</th>
                                <th>Sub Total</th>
                                <th>Pajak</th>
                                <th>Total</th>
                                <th>Tanggal Pemasangan</th>
                                <th>Jenis Pajak</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>NPWPD</th>
                                <th>Nama NPWPD</th>
                                <th>Transaksi Terakhir</th>
                                <th>Sub Total</th>
                                <th>Pajak</th>
                                <th>Total</th>
                                <th>Tanggal Pemasangan</th>
                                <th>Jenis Pajak</th>
                                <th>Detail</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php load_view('form') ?>
<?php load_view('formedit') ?>
<?php load_view('javascript') ?>