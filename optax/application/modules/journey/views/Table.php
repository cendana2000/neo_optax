<div class="row">
    <div class="col-md-12">
        <div class="card card-custom mb-3">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fa fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">JOURNEY ACTIVITY</h3>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill border border-primary sort-by-status rounded mb-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0)" data="all">Semua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" data="pending">Perlu tindak lanjut</a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-journey">
                        <thead>
                            <tr>
                                <th style="width:5%;">No.</th>
                                <th>Nama Wajib Pajak</th>
                                <th>Alamat Wajib Pajak</th>
                                <th>Pemicu</th>
                                <th>Identifikasi Masalah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>