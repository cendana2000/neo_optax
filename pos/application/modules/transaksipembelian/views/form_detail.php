<style type="text/css">
    .btn-detail {
        height: 2.3rem !important;
        width: 1.6rem !important;
    }

    .btn-detail:hover,
    .btn-detail:visited,
    .btn-detail:focus {
        background: none !important;
        border: 1px solid #ffcfa9;
    }

    #table-detail_satuan thead th {
        text-align: center;
        vertical-align: middle;
    }

    .number {
        direction: ltr;
    }

    .bigdrop {
        width: 600px !important;
    }
</style>

<div class="row form_detail" style="display: none">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
                <div class="ribbon-target" style="top: 12px;">
                    <span class="ribbon-inner bg-primary"></span>FORM PEMBELIAN BARANG DETAIL
                </div>
                <div class="card-toolbar">
                    <div class="example-tools justify-content-center">
                        <button type="reset" class="btn btn-secondary btn-sm m-3" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
                    </div>
                </div>
            </div>
            <input readonly type="hidden" id="pembelian_id" name="pembelian_id">
            <input readonly type="hidden" id="pembelian_is_konsinyasi" name="pembelian_is_konsinyasi">
            <input readonly type="hidden" id="detail_id" name="detail_id">
            <div class="card-body">
                <div class="form-group row">
                    <label for="pembelian_tanggal" class="col-md-2 col-form-label">Tgl Transaksi</label>
                    <div class="col-md-3">
                        <input readonly class="form-control" type="date" id="pembelian_tanggal" name="pembelian_tanggal" value="<?php echo date('Y-m-d') ?>">
                    </div>
                    <div class="col-1"></div>
                    <label for="pembelian_kode" class="col-md-2 col-form-label">No Transaksi</label>
                    <div class="col-md-4">
                        <input readonly class="form-control" type="text" id="pembelian_kode" name="pembelian_kode" readonly="" placeholder="##.AUTO">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pembelian_faktur" class="col-md-2 col-form-label">No Faktur</label>
                    <div class="col-md-3">
                        <input readonly class="form-control" maxlength="12" type="text" id="pembelian_faktur" name="pembelian_faktur">
                    </div>

                    <!-- <label for="pembelian_order_id" class="col-md-2 col-form-label">No Order</label>
                    <div class="col-md-3">
                        <select class="form-control" name="pembelian_order_id" id="pembelian_order_id" style="width: 100%" onchange="getOrder()"></select>
                    </div> -->
                    <div class="col-1"></div>
                    <label for="pembelian_supplier_id2" class="col-md-2 col-form-label">Supplier</label>
                    <div class="col-md-4" id="supplierDiv">
                        <input type="text" class="form-control" readonly id="pembelian_supplier_id2">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pembelian_bayar_opsi" class="col-md-2 col-form-label">Jenis Pembelian</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" readonly id="pembelian_bayar_opsi2">
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <label for="pembelian_jatuh_tempo" class="col-md-2 col-form-label" id="label_jatuh_tempo">Jatuh Tempo (hari)</label>
                    <div class="col-md-4" id="jatuh_tempo">
                        <div class="input-group">
                            <div class="input-group-prepend" style="width: 28%">
                                <input readonly class="form-control" type="number" id="pembelian_jatuh_tempo_hari" name="pembelian_jatuh_tempo_hari" onkeyup="setJT()">
                            </div>
                            <input readonly class="form-control" type="date" id="pembelian_jatuh_tempo" name="pembelian_jatuh_tempo" value="<?php echo date('Y-m-d', strtotime('+1 month')) ?>" style="width: 72%" onkeyup="countJT()">
                        </div>
                    </div>
                </div>

                <!-- Table Barang -->
                <h3>Detail Barang</h3>
                <table class="table table-border">
                    <thead>
                        <th>Barang</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Disc</th>
                        <th>Jumlah</th>
                    </thead>
                    <tbody id="detailBarang">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="kt-portlet kt-portlet--mobile form_harga" style="display: none">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Detail Harga Barang Faktur No. <span id="label_faktur"></span>
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="table-detail_satuan" style="width:160%">
                <thead style="background: #cae3f9;">
                    <tr>
                        <th class="sticky-col scol-1" style="width: 15%!important;" rowspan="2">Barang</th>
                        <th class="sticky-col scol-md-2" style="width: 4%;" rowspan="2">Satuan Beli</th>
                        <th class="sticky-col scol-md-3" style="width: 7%" rowspan="2">Harga</th>
                        <th style="" colspan="3">Satuan I</th>
                        <th style="" colspan="3">Satuan II</th>
                        <th style="" colspan="3">Satuan III</th>
                    </tr>
                    <tr>
                        <th style="width: 6%">Satuan</th>
                        <th style="width: 3%;">Isi</th>
                        <th style="width: 10%;">Laba/Harga</th>
                        <th style="width: 6%">Satuan</th>
                        <th style="width: 3%;">Isi</th>
                        <th style="width: 10%;">Laba/Harga</th>
                        <th style="width: 6%">Satuan</th>
                        <th style="width: 3%">Isi</th>
                        <th style="width: 10%">Laba/Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="barang_1">
                        <td class="sticky-col scol-1" scope="row">
                            <input readonly type="hidden" class="form-control" name="hg_barang_id[1]" id="hg_barang_id_1" data-id="1">
                            <input readonly type="text" class="form-control" name="hg_barang_nama[1]" id="hg_barang_nama_1" disabled="">
                        <td class="sticky-col scol-md-2">
                            <input readonly type="hidden" class="form-control" name="hg_barang_satuan_beli[1]" id="hg_barang_satuan_beli_1">
                            <input readonly type="text" class="form-control" name="hg_barang_kode[1]" id="hg_barang_kode_1" disabled="">
                        </td>
                        <td class="sticky-col scol-md-3">
                            <input readonly class="form-control number" type="text" name="hg_barang_harga_beli[1]" id="hg_barang_harga_beli_1" disabled="">
                            <input readonly class="form-control number" type="hidden" name="hg_barang_harga_barang[1]" id="hg_barang_harga_barang_1" disabled="">
                        </td>
                        <td>
                            <input readonly type="hidden" name="hg_detail_barang_satuan_id[1][1]" id="hg_detail_barang_satuan_id_1" data-id="1">
                            <input readonly type="hidden" name="hg_detail_barang_satuan_kode[1][1]" id="hg_detail_barang_satuan_kode_1" data-id="1">
                            <select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][1]" id="hg_detail_barang_satuan_satuan_id_1" data-id="1" onchange="setSatuanHarga('1')" style="width: 100%"></select>
                        </td>
                        <td>
                            <input readonly class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][1]" id="hg_detail_barang_satuan_konversi_1" onkeyup="setHargaDetail('')">
                            <input readonly class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][1]" id="hg_detail_barang_satuan_harga_beli_1">
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="kt-input-icon kt-input-icon--right" style="width:40%;margin-right: 5px;">
                                    <input readonly type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_1" name="hg_detail_barang_satuan_keuntungan[1][1]" onkeyup="countHarga()">
                                </div>
                                <input readonly type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][1]" name="hg_detail_barang_satuan_harga_jual_1" onkeyup="countLaba()">
                            </div>
                        </td>
                        <td>
                            <input readonly type="hidden" name="hg_detail_barang_satuan_id[1][2]" id="hg_detail_barang_satuan_id_2" data-id="2">
                            <input readonly type="hidden" name="hg_detail_barang_satuan_kode[1][2]" id="hg_detail_barang_satuan_kode_2" data-id="2">
                            <select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][2]" id="hg_detail_barang_satuan_satuan_id_2" data-id="2" onchange="setSatuanHarga('2')" style="width: 100%"></select>
                        </td>
                        <td>
                            <input readonly class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][2]" id="hg_detail_barang_satuan_konversi_2">
                            <input readonly class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][2]" id="hg_detail_barang_satuan_harga_beli_2">
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
                                    <input readonly type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_2" name="hg_detail_barang_satuan_keuntungan[1][2]" onkeyup="countHarga()">
                                </div>
                                <input readonly type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][2]" name="hg_detail_barang_satuan_harga_jual_2" onkeyup="countLaba()">
                            </div>
                        </td>
                        <td>
                            <input readonly type="hidden" name="hg_detail_barang_satuan_id[1][3]" id="hg_detail_barang_satuan_id_3" data-id="3">
                            <input readonly type="hidden" name="hg_detail_barang_satuan_kode[1][3]" id="hg_detail_barang_satuan_kode_3" data-id="3">
                            <select class="form-control" name="hg_detail_barang_satuan_satuan_id[1][3]" id="hg_detail_barang_satuan_satuan_id_3" data-id="3" onchange="setSatuanHarga('3')" style="width: 100%"></select>
                        </td>
                        <td>
                            <input readonly class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[1][3]" id="hg_detail_barang_satuan_konversi_3">
                            <input readonly class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[1][3]" id="hg_detail_barang_satuan_harga_beli_3">
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
                                    <input readonly type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_3" name="hg_detail_barang_satuan_keuntungan[1][3]" onkeyup="countHarga()">
                                </div>
                                <input readonly type="text" class="form-control number" id="hg_detail_barang_satuan_harga_jual[1][3]" name="hg_detail_barang_satuan_harga_jual_3" onkeyup="countLaba()">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>


        </div>

    </div>
    <div class="kt-portlet__foot">
        <div class="kt-form__actions">
            <div class="row">
                <div class="col-md-2" style="padding-top: 8px;text-align: right;">
                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--success">
                        <input readonly type="hidden" name="print_id" id="print_id">
                        <input readonly type="checkbox" name="print_checkbox" id="print_checkbox" value="cetak" checked="checked"> <i class="flaticon2-print"></i> Cetak
                        <span></span>
                    </label>
                </div>
                <div class="col-10">
                    <button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Simpan</button>
                    <button type="reset" class="btn btn-secondary" onclick="onBack()"><i class="flaticon2-cancel-music"></i> Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>