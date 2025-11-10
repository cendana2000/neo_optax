<div class="row form_data" style="display: none">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>FORM PENJUALAN
                </div>
            </div>
            <form class="kt-form" action="javascript:save('form-penjualan')" name="form-penjualan" id="form-penjualan">
                <div class="card-body">

                    <input type="hidden" id="penjualan_id" name="penjualan_id">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <div class="form-group row">
                                    <label for="penjualan_no_transaksi" class="col-2 col-form-label">No Transaksi</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="penjualan_no_transaksi" name="penjualan_no_transaksi">
                                    </div>
                                    <div class="col-1"></div>
                                    <label for="supplier_nama" class="col-2 col-form-label">Jenis Penjualan</label>
                                    <div class="col-4">
                                        <select name="penjualan_jenis_penjualan" id="penjualan_jenis_penjualan" class="form-control">
                                            <option value="">-Pilih Jenis Pembayaran-</option>
                                            <option value="Kredit">Kredit</option>
                                            <option value="Tunai">Tunai</option>
                                        </select>
                                    </div>
                                </div>                                                  
                                <div class="form-group row">
                                    <label for="penjualan_no_transaksi" class="col-2 col-form-label">Customer</label>
                                    <div class="col-3">
                                        <select class="form-control" name="penjualan_no_transaksi" id="penjualan_no_transaksi"></select>
                                    </div>
                                    <div class="col-1"></div>
                                    <label for="penjualan_tanggal_jatuh_tempo" class="col-2 col-form-label">Jatuh Tempo</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control" name="penjualan_tanggal_jatuh_tempo" id="penjualan_tanggal_jatuh_tempo" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                </div> 
                                <div class="form-group row">
                                <label for="penjualan_tanggal" class="col-2 col-form-label">Tanggal Penjualan</label>
                                    <div class="col-3">
                                        <input type="date" class="form-control" name="penjualan_tanggal" id="penjualan_tanggal" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                </div>                                                 
                            </div>
                        </div>
                        <!-- <div class="kt-section" style="padding-top: 20px">
                            <div class="form-group row">
                                <div class="col-2">
                                    <div class="kt-section__desc">Data Sales Supplier</div>
                                </div>
                                <div class="col-7"></div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary" onclick="addSales()"><i class="flaticon-add"></i> Tambah Sales Supplier</button>
                                </div>
                            </div>
                            <div class="kt-section__content kt-section__content--border">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead style="background: #cae3f9;">
                                            <tr>
                                                <th style="width: 30%!important">Nama Sales</th>
                                                <th style="width: 20%">Telp</th>
                                                <th style="width: 20%">HP</th>
                                                <th style="width: 25%">Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-sales">
                                            <tr class="sales_1">
                                                <td scope="row">
                                                    <input type="hidden" class="form-control" name="sales_id[]" id="sales_id_1">
                                                    <input type="text" class="form-control nama" name="sales_nama[]" id="sales_nama_1" onkeyup="add_check()">
                                                </td>
                                                <td><input class="form-control" type="text" name="sales_telp[]" id="sales_telp_1"></td>
                                                <td><input class="form-control" type="text" name="sales_hp[]" id="sales_hp_1"></td>
                                                <td><input class="form-control" type="text" name="sales_keterangan[]" id="sales_keterangan_1"></td>
                                                <td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('1')" title="Hapus">
                                                        <span class="la la-trash"></span> Hapus</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-4 text-left">
                            <!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
                            <button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button>

                        </div>
                        <div class="col-8 text-right">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Save</button>
                            <!-- <button type="submit" class="btn btn-sm btn-success"><i class="flaticon2-cancel-music"></i> Save</button> -->
                        </div>
                    </div>
                </div>
                </forml>
        </div>
    </div>
</div>