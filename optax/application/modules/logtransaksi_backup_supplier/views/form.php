<div class="row form_data" style="display: none">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>FORM SUPPLIER
                </div>
            </div>
            <form class="kt-form" action="javascript:save('form-supplier')" name="form-supplier" id="form-supplier">
                <div class="card-body">

                    <input type="hidden" id="supplier_id" name="supplier_id">
                    <div class="kt-portlet__body">
                        <div class="kt-section">
                            <div class="kt-section__content kt-section__content--border">
                                <div class="form-group row">
                                    <label for="supplier_kode" class="col-2 col-form-label">Kode Supplier</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="supplier_kode" name="supplier_kode">
                                    </div>
                                    <label for="supplier_nama" class="col-2 col-form-label">Nama Supplier</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" id="supplier_nama" name="supplier_nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="supplier_telp" class="col-2 col-form-label">Telp</label>
                                    <div class="col-3">
                                        <input class="form-control" type="text" id="supplier_telp" name="supplier_telp">
                                    </div>
                                    <label for="supplier_hp" class="col-2 col-form-label">No. HP</label>
                                    <div class="col-5">
                                        <input class="form-control" type="text" id="supplier_hp" name="supplier_hp">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="supplier_alamat" class="col-2 col-form-label">Alamat</label>
                                    <div class="col-10">
                                        <textarea class="form-control" id="supplier_alamat" name="supplier_alamat"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="supplier_rekening" class="col-2 col-form-label">Rekening</label>
                                    <div class="col-3">
                                        <textarea class="form-control" id="supplier_rekening" name="supplier_rekening"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="kt-section" style="padding-top: 20px">
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
                                                    <input type="hidden" class="form-control" name="sales_id[1]" id="sales_id_1">
                                                    <input type="text" class="form-control nama" name="sales_nama[1]" id="sales_nama_1" onkeyup="add_check()">
                                                </td>
                                                <td><input class="form-control" type="text" name="sales_telp[1]" id="sales_telp_1"></td>
                                                <td><input class="form-control" type="text" name="sales_hp[1]" id="sales_hp_1"></td>
                                                <td><input class="form-control" type="text" name="sales_keterangan[1]" id="sales_keterangan_1"></td>
                                                <td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('1')" title="Hapus">
                                                        <span class="la la-trash"></span> Hapus</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-4 text-left">
                            <!-- <button type="button" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-arrow-left"></i> Back</button> -->
                            <button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
                            <button type="reset" style="display: none;" id="triggerReset"><i class="fa fa-arrow-left"></i> Batal</button>

                        </div>
                        <div class="col-8 text-right">
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
                            <!-- <button type="submit" class="btn btn-sm btn-success"><i class="flaticon2-cancel-music"></i> Save</button> -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>