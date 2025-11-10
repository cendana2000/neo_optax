<div class="card card-custom card-stretch ">
    <!--begin::Header-->
    <div class="card-header py-3">
        <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Registrasi Toko1</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1">Detail Informasi Registrasi Toko</span>
        </div>
        <!-- <div class="card-toolbar">
          <button type="button" id="btnUpdate" onclick="onUpdate(this)" class="btn btn-info mr-2 profil_view">Update Profil</button>
          <button type="submit" onclick="updateDataMitra('form-mitra-usaha')" id="btnSaveChanges" disabled="" class="btn btn-success mr-2 profil_update">Save Changes</button>
          <button type="reset" onclick="onCancel(this)" id="btnCancel" disabled="" class="btn btn-secondary profil_update">Cancel</button>
      </div> -->
    </div>
    <!--end::Header-->
    <!--begin::Form-->
    <!--begin::Body-->
    <div class="card-body table_data">
        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
            <div class="alert-icon">
                <span class="svg-icon svg-icon-primary svg-icon-2x">
                    <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo7\dist/../src/media/svg/icons\Shopping\Cart1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" fill="#000000" opacity="0.3" />
                            <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" fill="#000000" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </div>
            <div class="alert-text">
                <h4 class="alert-heading">eToko.</h4>
                <p class="my-5">Anda dapat meregistrasikan eToko untuk transaksi toko anda silahkan klik tombol dibawah ini. Untuk informasi lebih lanjut bisa menghubungi
                    <a class="font-weight-bold" href="https://getbootstrap.com/docs/4.6/components/alerts/" target="_blank">BAPPEDA KOTA MALANG</a>.
                </p>
                <div class="border-bottom border-dark opacity-10 mb-5"></div>
                <button type="button" name="btn-registrasi" class="btn btn-success btn-sm mr-3" onclick="showToko()">Registrasi Toko</button>
            </div>
        </div>
    </div>

    <!--begin::Body-->
    <div class="card-body form_data" style="display: none;">
        <form class="form" method="POST" name="form-toko" id="form-toko" action="javascript:save('form-toko')">
            <div class="row">
                <label class="col-xl-3"></label>
                <div class="col-lg-9">
                    <h5 class="font-weight-bold mb-6">Informasi eToko</h5>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Logo Outlet</label>
                <div class="col-lg-9">
                    <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url(assets/media/users/blank.png)">
                        <div class="image-input-wrapper show-mitra-image" style="background-image: url(&quot;https://pmu-pttimah.sekawanmedia.co.id/dev/panel/dokumen/mitra/d75431c265a281b84bd1289f046d50d0.png&quot;);"></div>
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input disabled="" class="is_edit_picture" id="wajibpajak_image" type="file" name="wajibpajak_image" accept=".png, .jpg, .jpeg">
                            <!-- <input type="hidden" name="profile_avatar_remove" /> -->
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                </div>
            </div>
            <input name="toko_id" id="toko_id" type="hidden">
            <input name="toko_wajibpajak_id" id="wajibpajak_id" type="hidden">
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">NPWPD</label>
                <div class="col-lg-9">
                    <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_npwpd" name="wajibpajak_npwpd" type="text" readonly="true">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Nama</label>
                <div class="col-lg-9">
                    <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_nama" name="wajibpajak_nama" type="text" readonly="true">
                </div>
            </div>
            <div class="form-group row" style="display: none;">
                <label class="col-xl-3 col-lg-3 col-form-label">Sektor Nama</label>
                <div class="col-lg-9">
                    <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_sektor_nama" name="wajibpajak_sektor_nama" type="text" disabled="true">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Sektor Nama</label>
                <div class="col-lg-9">
                    <input class="form-control is_edit form-control-lg form-control-solid" id="jenis_nama" name="jenis_nama" type="text" disabled="true">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                <div class="col-lg-9 status-toko">
                    <!-- <input class="form-control is_edit form-control-lg form-control-solid" id="wajibpajak_nama" name="wajibpajak_nama" type="text" readonly="true"> -->
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-4 text-left">
                        <button type="reset" class="btn btn-sm btn-secondary" onclick="onBack()"><i class="fa fa-arrow-left"></i> Batal</button>
                        <button type="reset" style="display: none;" id="triggerReset"><i class="fa fa-arrow-left"></i> Batal</button>

                    </div>
                    <div class="col-8 text-right">
                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--end::Body-->
</div>

<?php load_view('Javascript') ?>