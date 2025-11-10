<div class="row table_data">
    <div class="col">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>DATA REALISASI PAJAK
                </div>
                <div class="card-toolbar">
                    <div class="mr-3">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control monthpicker" name="bulan" id="bulan" onchange="filterBulan()" value="" placeholder="Pilih Bulan" />
                            <div class="input-group-append"><span class="input-group-text"><i class="la la-calendar-check-o "></i></span></div>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-success btn-sm" onclick="getSpreadsheetRealisasi()"><i class="far fa-file-excel"></i> Excel</button>
                        <button class="btn btn-danger btn-sm" onclick="getPdfRealisasi()"><i class="far fa-file-pdf"></i> PDF</button>
                        <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-sm-3">
                        <h5>Jumlah WP Terdaftar</h5>
                    </div>
                    <div class="col-sm-5">
                        <h5 id="wp_terdaftar">-</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h5>Jumlah WP Terkoneksi</h5>
                    </div>
                    <div class="col-sm-5">
                        <h5 id="wp_terkoneksi">-</h5>
                    </div>
                </div>
                <table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-realisasi">
                    <thead>
                        <tr>
                            <th style="width:5%;">No.</th>
                            <th>NPWPD</th>
                            <th>Nama NPWPD</th>
                            <th>Tanggal Laporan</th>
                            <th>Jml Transaksi</th>
                            <th>Omzet(Rp)</th>
                            <th>Pajak(Upload)</th>
                            <th>Pajak(Realita)</th>
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
                            <th>Jml Transaksi</th>
                            <th>Omzet(Rp)</th>
                            <th>Pajak(Upload)</th>
                            <th>Pajak(Realita)</th>
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
<?php load_view('form') ?>
<?php load_view('formedit') ?>
<?php load_view('javascript') ?>