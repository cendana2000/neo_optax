<div class="row form_barang" style="display: none">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>FORM PRODUK
                </div>
            </div>
            <form class="kt-form" action="javascript:simpanBarang('form-barang2')" name="form-barang2" id="form-barang2">
                <div class="card-body">
                    <input type="hidden" id="barang_id" name="barang_id">
                    <div class="kt-portlet__body">
                        <div class="form-group row">
                            <label for="barang_kode" class="col-2 col-form-label">Kode Produk</label>
                            <div class="col-3">
                                <input class="form-control" readonly style="background-color: #eaeaea;" type="text" id="barang_kode" name="barang_kode" placeholder=".###">
                            </div>
                            <div class="col-1"></div>
                            <label for="barang_nama" class="col-2 col-form-label">Nama Produk</label>
                            <div class="col-4">
                                <input class="form-control" type="text" id="barang_nama" name="barang_nama" placeholder=".###" oninvalid="fieldInvalid(this)" onchange="fieldChange(this)">
                                <div class="invalid-feedback">Bidang ini wajib disi</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="barang_kategori_barang_2" class="col-2 col-form-label">Kategori Produk</label>
                            <div class="col-3" id="kategori_div">
                                <select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang_2" style="width: 100%"></select>
                            </div>
                            <div class="col-1"></div>

                            <label for="barang_stok" class="col-2 col-form-label ">Stok Min</label>
                            <div class="col-4 ">
                                <div class="row">
                                    <div class="col-6">
                                        <input class="form-control" placeholder="min" type="text" id="barang_stok" name="barang_stok_min">
                                    </div>
                                    <div class="col-6">
                                        <input class="form-control" style="background-color: #eaeaea;" placeholder="satuan" readonly type="text" id="barang_stok_satuan" name="barang_stok_satuan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="barang_jenis_barang" class="col-2 col-form-label">Jenis Produk</label>
                            <div class="col-3" id=jenisbayar_div>
                                <select class="form-control" name="barang_jenis_barang" id="barang_jenis_barang" style="width: 100%"></select>
                            </div>
                            <div class="col-1"></div>
                            <label for="barang_harga_pokok" class="col-2 col-form-label">Harga Pokok</label>
                            <div class="col-4">
                                <input type="text" class="form-control tnumber" name="barang_harga_pokok" id="barang_harga_pokok">
                            </div>
                        </div>

                        <div class="table-responsive" id="tableDetailSatuan">
                            <table class="table table-bordered table-hover" id="table-sales">
                                <thead style="background: #cae3f9;">
                                    <tr>
                                        <th style="width: 20%!important">Satuan</th>
                                        <th style="width: 10%">Konversi</th>
                                        <th style="width: 20%">Harga Beli</th>
                                        <th style="width: 10%">Keuntungan %</th>
                                        <th style="width: 20%">Harga Jual</th>
                                        <th style="width: 10%">Disc %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="detail_1">
                                        <td scope="row" id="satuan1_div">
                                            <select class="form-control" name="barang_satuan_satuan_id[1]" id="barang_satuan_satuan_id_1" style="width: 100%"></select>
                                        </td>
                                        <td>
                                            <input class="form-control tnumber" type="text" name="barang_satuan_konversi[1]" id="barang_satuan_konversi_1" readonly="" value="1">
                                        </td>
                                        <td>
                                            <input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[1]" id="barang_satuan_harga_beli_1" value="0" onkeyup="setUntung2('1')">
                                        </td>
                                        <td>
                                            <div class="kt-input-icon kt-input-icon--right">
                                                <input class="form-control disc" type="text" onkeyup="setUntung2('1')" name="barang_satuan_keuntungan[1]" value="0" id="barang_satuan_keuntungan_1">
                                            </div>
                                        </td>
                                        <td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[1]" id="barang_satuan_harga_jual_1" onkeyup="setUntungRp2('1')"></td>
                                        <td>
                                            <div class="kt-input-icon kt-input-icon--right">
                                                <input class="form-control disc" type="text" name="barang_satuan_disc[1]" id="barang_satuan_disc_1" value="0">
                                            </div>
                                        </td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-4 text-left">
                            <button type="reset" class="btn btn-sm btn-secondary" onclick="backToForm()"><i class="fa fa-arrow-left"></i> Batal</button>
                        </div>
                        <div class="col-8 text-right">
                            <!-- <button type="reset" onclick="onClear()" class="btn btn-sm btn-danger"><i class="fas fa-sync-alt"></i> Reset</button> -->
                            <button id="btn_form" type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-preview" tabindex="-1" aria-labelledby="modal-previewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-previewLabel">Preview Thumbnail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="assets/media/noimage.png" id="preview-image" class="img-fluid" alt="thumbnail preview" onerror="imgError(this);">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('javascript_barang'); ?>