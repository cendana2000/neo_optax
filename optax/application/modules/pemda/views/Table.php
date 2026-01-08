<div class="row table_data">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="d-flex align-items-center">
                        <span class="card-icon mr-0">
                            <i class="fas fa-table text-primary"></i>
                        </span>
                        <h3 class="card-label mb-0">MANAGEMENT PEMDA</h3>
                    </div>
                    <div class="card-toolbar">
                        <div class="btn-group" id="dropdown-div">
                            <button class="btn btn-light-primary btn-sm m-3 radius-5" onclick="onCreate()" data-roleable="true" data-role="User-Create">
                                <i class="fa fa-plus"></i> Create New
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-fill border border-primary sort-by-status rounded mb-5">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0)" data="aktif">Aktif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)" data="tidak_aktif">Tidak Aktif</a>
                    </li>
                </ul>
                <div class="table-responsive">
                    <table class="table table-premium table-bordered table-hover" id="table-pemda">
                        <thead>
                            <tr>
                                <th style="width:5%;">No.</th>
                                <th>Nama Pemda</th>
                                <th>Kode Pemda</th>
                                <th>Provinsi</th>
                                <th>Kab/Kota</th>
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

<div class="row form_data" style="display:none">
    <div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fas fa-table text-primary"></i>
                    </span>
                    <h3 class="card-label">DETAIL MANAGEMENT PEMDA</h3>
                </div>
            </div>
            <form action="javascript:save('form-pemda')" method="post" id="form-pemda" name="form-pemda" autocomplete="off">
                <div class="card-body ">
                    <div class="row">
                        <input class="form-control" type="hidden" name="pemda_id" autocomplete="off" />
                        <input type="hidden" name="remove_logo" id="remove_logo" value="0">
                        <div class="col-xl-8">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">Kode Pemda</label>
                                        <input class="form-control" type="text" name="pemda_kode" autocomplete="off" readonly style="background: ghostwhite;" />
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="text-dark">Nama Pemda</label>
                                        <input class="form-control" type="text" name="pemda_nama" id="pemda_nama" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="text-dark">Provinsi</label>
                                        <select class="form-select select2 form-control" id="provinsi_id" name="provinsi_id" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="text-dark">Kab./Kota</label>
                                        <select class="form-select select2 form-control" id="kabkota_id" name="kabkota_id" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="text-dark">Alamat Pemda</label>
                                        <textarea name="pemda_alamat" id="pemda_alamat" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="text-dark">Koordinat</label>
                                        <input class="form-control" type="text" name="pemda_coord" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <h5 class="font-weight-bold mb-6">Logo Pemda</h5>
                            <div class="image-input image-input-outline img-fluid w-100"
                                id="pemda_logo_box"
                                style="height:200px;background-color:#f8f9fa">
                                <!-- preview image -->
                                <div class="image-input-wrapper show-pemda-logo img-fluid w-100"
                                    style="
                                        height:100%;
                                        background-size:contain;
                                        background-repeat:no-repeat;
                                        background-position:center;
                                    ">
                                </div>
                                <!-- change -->
                                <label onchange="onChangeLogoPemda(this)"
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change"
                                    title="Ganti Logo">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file"
                                        id="pemda_logo_file"
                                        name="pemda_logo"
                                        accept=".png,.jpg,.jpeg"
                                        hidden>
                                </label>
                                <!-- remove -->
                                <span onclick="onRemoveLogoPemda(this)"
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-danger btn-shadow"
                                    data-action="remove"
                                    title="Hapus Logo">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            <span class="form-text text-muted">
                                Allowed file types: png, jpg, jpeg.
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div id="map_1" style="height: 512px;width: 100%;border-radius: 8px;"></div>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                    <div class="row">
                        <div class="col-lg-9 col-xl-6">
                            <h5 class="font-weight-bold mb-6">Status:</h5>
                        </div>
                    </div>
                    <!--begin::Form Group-->
                    <div class="form-group row mb-0">
                        <label class="col-xl-2 col-lg-2 col-form-label">Status Pemda</label>
                        <div class="col-lg-9 col-xl-6">
                            <select class="form-control" name="pemda_status" id="pemda_status">
                                <option value=""> -- Pilih -- </option>
                                <option value="active"> Aktif </option>
                                <option value="inactive"> Tidak Aktif </option>
                            </select>
                            <p class="form-text text-muted pt-2">Silahkan pilih status pemda.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4 text-left">
                            <button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Kembali</button>
                        </div>
                        <div class="col-8 text-right">
                            <button type="button" onclick="update()" id="btnSave" class="btn btn-sm btn-success ml-4"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('form'); ?>
<?php $this->load->view('javascript'); ?>