<div class="row table_data">
    <div class="col">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">HISTORY PELAPORAN</h3>
                </div>
                <div class="card-toolbar">
                    <div class="btn-group">
                        <button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <marquee>Apabila ada kendala mengenai virtual account harap hubungi Call Center Pelayanan Pajak Daerah di nomor 0812-5545-5955</marquee>
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-history-pelaporan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Masa Pajak</th>
                                <th>Nominal Pajak</th>
                                <th>Virtual Account</th>
                                <th>Status</th>
                                <th>Tagihan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="va" type="text" style="visibility:hidden;"></div>
            </div>
        </div>
    </div>
</div>
<?php load_view('javascript') ?>