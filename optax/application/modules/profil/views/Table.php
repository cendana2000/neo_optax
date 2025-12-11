<div class="card card-custom card-stretch">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Informasi Wajib Pajak</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1">Detail Informasi Wajib Pajak</span>
        </div>
        <div class="card-toolbar">
            <button type="button" id="btnUpdate" onclick="onUpdate(this)" class="btn btn-info mr-2 profil_view">Ubah Profil</button>
            <button type="submit" onclick="updateDataMitra('form-mitra-usaha')" id="btnSaveChanges" disabled="" class="btn btn-success mr-2 profil_update">Simpan Perubahan</button>
            <button type="reset" onclick="onCancel(this)" id="btnCancel" disabled="" class="btn btn-secondary profil_update">Batal</button>
        </div>
    </div>
    <!--end::Header-->
    <!--begin::Form-->
    <form class="form" method="POST" name="form-wajibpajak" id="form-wajibpajak" enctype="multipart/form-data">
        <!--begin::Body-->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8 col-md-12 order-lg-1 order-2">
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9">
                            <h5 class="font-weight-bold mb-6">Informasi Wajib Pajak</h5>
                        </div>
                    </div>
                    <input name="wajibpajak_id" id="wajibpajak_id" type="hidden">
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nama Perusahaan</label>
                        <div class="col-lg-9">
                            <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_nama" name="wajibpajak_nama" type="text" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Sektor Nama</label>
                        <div class="col-lg-9">
                            <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_sektor_nama" name="wajibpajak_sektor_nama" type="text" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">NPWPD</label>
                        <div class="col-lg-9">
                            <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_npwpd" name="wajibpajak_npwpd" type="text" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Nama Penanggung Jawab</label>
                        <div class="col-lg-9">
                            <input class="is_edit form-control form-control-lg form-control-solid" type="text" name="wajibpajak_nama_penanggungjawab" id="wajibpajak_nama_penanggungjawab" readonly="true">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-xl-3"></label>
                        <div class="col-lg-9">
                            <h5 class="font-weight-bold mt-10 mb-6">Info Kontak Wajib Pajak</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">No. Telephone</label>
                        <div class="col-lg-9">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-phone"></i>
                                    </span>
                                </div>
                                <input id="wajibpajak_telp" name="wajibpajak_telp" type="text" class="is_edit form-control form-control-lg form-control-solid" readonly="true" placeholder="Phone">
                            </div>
                            <span class="form-text text-muted">isikan dengan format +62</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Email Wajib Pajak</label>
                        <div class="col-lg-9">
                            <div class="input-group input-group-lg input-group-solid">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-at"></i>
                                    </span>
                                </div>
                                <input id="wajibpajak_email" name="wajibpajak_email" type="text" class="is_edit form-control form-control-lg form-control-solid" readonly="true" placeholder="Email">
                            </div>
                            <span class="form-text text-muted">Jika Anda mengganti email, setelah logout dari aplikasi, silahkan lakukan aktivasi email di email Anda</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Alamat Wajib Pajak</label>
                        <div class="col-lg-9">
                            <input id="wajibpajak_alamat" name="wajibpajak_alamat" type="text" class="is_edit form-control form-control-lg form-control-solid" readonly="true">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">Kode Usaha</label>
                        <div class="col-lg-9">
                            <input id="toko_kode" name="toko_kode" type="text" class="is_edit form-control form-control-lg form-control-solid" readonly="true">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 order-lg-2 order-1">
                    <h5 class="font-weight-bold mb-6">Berkas NPWP</h5>
                    <div class="image-input image-input-outline img-fluid w-100" id="kt_profile_avatar" style="height:200px;background-image: url(assets/media/users/blank.png)">
                        <div class="image-input-wrapper show-wajibpajak-image img-fluid w-100" style="height: 100%;background-size:cover;background-position-y:center;background-position-x:center;"></div>
                        <label onchange="onChangeImage(this)" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input disabled="" class="is_edit_picture" id="wajibpajak_image" type="file" name="wajibpajak_image" accept=".png, .jpg, .jpeg">
                            <!-- <input type="hidden" name="profile_avatar_remove" /> -->
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                        <span onclick="onRemoveImage(this)" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                </div>
            </div>
        </div>
        <!--end::Body-->
    </form>
    <!--end::Form-->
</div>

<?php load_view('Javascript') ?>