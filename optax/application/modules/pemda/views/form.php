<div class="modal fade" tabindex="-1" role="dialog" id="modalData">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Pemda</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="javascript:save('form-pemda')" method="post" id="form-pemda" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="pemda_kode">Kode Pemda</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pemda_kode" name="pemda_kode" class="form-control" readonly style="background-color: #eaeaea;" placeholder=".###">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="pemda_nama">Nama Pemda</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pemda_nama" name="pemda_nama" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="select_provinsi">Select Provinsi</label>
                                <div class="col-lg-8">
                                    <select class="form-select select2 form-control" id="select_provinsi" name="select_provinsi" required></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="select_kabkota">Select Kab./Kota</label>
                                <div class="col-lg-8">
                                    <select class="form-select select2 form-control" id="select_kabkota" name="select_kabkota" required></select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="pemda_alamat">Alamat Pemda</label>
                                <div class="col-lg-8">
                                    <textarea name="pemda_alamat" id="pemda_alamat" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-4 col-form-label text-left" for="pemda_coord">Koordinat</label>
                                <div class="col-lg-8">
                                    <input type="text" id="pemda_coord" name="pemda_coord" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex justify-content-center align-items-start">
                            <div class="form-group row w-100">
                                <label class="col-lg-3 col-form-label" for="pemda_logo">Logo Pemda</label>
                                <div class="col-lg-9">
                                    <div class="image-input image-input-empty image-input-outline"
                                        id="pemda_logo"
                                        style="background-image: url('./assets/media/noimage.png')">

                                        <div class="image-input-wrapper"></div>

                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="change"
                                            data-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="pemda_logo" accept=".png, .jpg, .jpeg" required>
                                            <input type="hidden" name="profile_avatar_remove">
                                        </label>

                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="cancel"
                                            data-toggle="tooltip"
                                            title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div id="map" style="height: 512px;width: 100%;border-radius: 8px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-sm btn-danger mx-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success mx-2"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>