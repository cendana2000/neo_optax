<div class="row form_data" style="display: none">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>DETAIL REALISASI PAJAK
                </div>
            </div>
            <form action="">
                <div class="card-body ">
                    <h3 class="mb-5">Tanggal Upload : <span id="realisasi_tanggal"></span></h3>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">NPWPD</label>
                                        <input class="form-control" type="text" name="wajibpajaak_npwpd" id="wajibpajaak_npwpd" autocomplete="off" style="background: ghostwhite;" />
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">Nama Perusahaan</label>
                                        <input class="form-control" type="text" name="wajibpajak_nama" id="wajibpajak_nama" autocomplete="off" readonly="" style="background: ghostwhite;" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">Alamat</label>
                                        <input class="form-control" type="text" name="wajibpajak_alamat" id="wajibpajak_alamat" autocomplete="off" readonly="" style="background: ghostwhite;" />
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">Nama Penangung Jawab</label>
                                        <input class="form-control" type="text" name="wajibpajak_nama_penanggungjawab" id="wajibpajak_nama_penanggungjawab" autocomplete="off" style="background: ghostwhite;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-realisasi-detail">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">No.</th>
                                        <th>Waktu</th>
                                        <th>Kode Penjualan</th>
                                        <th>Omzet</th>
                                        <th>Jasa</th>
                                        <th>Pajak</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No.</th>
                                        <th>Waktu</th>
                                        <th>Kode Penjualan</th>
                                        <th>Omzet</th>
                                        <th>Jasa</th>
                                        <th>Pajak</th>
                                        <th>Total</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-4 text-left">
                        <button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                </div>
            </form>

        </div>
    </div>
</div>