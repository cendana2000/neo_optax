<div class="modal fade" tabindex="-1" role="dialog" id="modal-selesaikan">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Selesaikan Journey</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>

            <form action="javascript:save('form-journey')" method="post" id="form-journey" autocomplete="off">
                <input type="hidden" name="journey_id" id="journey_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label class="col-form-label" for="journey_tgl_survey">Tanggal Survey</label>
                                <input type="datetime-local" id="journey_tgl_survey" name="journey_tgl_survey" class="form-control datepicker" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="col-form-label" for="journey_identifikasi_masalah">Identifikasi Masalah</label>
                                <input type="text" id="journey_identifikasi_masalah" name="journey_identifikasi_masalah" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="col-form-label" for="journey_penyelesaian">Penyelesaian</label>
                                <textarea name="journey_penyelesaian" id="journey_penyelesaian" class="form-control" required></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="col-form-label" for="journey_hasil">Hasil</label>
                                <input type="text" id="journey_hasil" name="journey_hasil" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label class="col-form-label" for="journey_catatan">Catatan</label>
                                <textarea name="journey_catatan" id="journey_catatan" class="form-control"></textarea>
                            </div>

                        </div>

                        <div class="col-md-6 d-flex justify-content-center align-items-start">
                            <div class="form-group row w-100">
                                <label class="col-lg-3 col-form-label" for="journey_attachment">Foto Dokumentasi</label>
                                <div class="col-lg-9">
                                    <div class="image-input image-input-empty image-input-outline"
                                        id="journey_attachment"
                                        style="background-image: url('./assets/media/noimage.png')">

                                        <div class="image-input-wrapper"></div>

                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                            data-action="change"
                                            data-toggle="tooltip"
                                            title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="journey_attachment" accept=".png, .jpg, .jpeg" required>
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal-detail">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Detail</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="fw-bold">Tanggal Survey</div>
                            <div data-value="journey_tgl_survey"></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Pegawai</div>
                            <div data-value="journey_pegawai"></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Identifikasi Masalah</div>
                            <div data-value="journey_identifikasi_masalah"></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Penyelesaian</div>
                            <div data-value="journey_penyelesaian"></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Hasil</div>
                            <div data-value="journey_hasil"></div>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold">Catatan</div>
                            <div data-value="journey_catatan"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="fw-bold">Foto Dokumentasi</div>
                            <div data-value="journey_attachment"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer text-right">
                <button type="button" class="btn btn-sm btn-danger mx-2" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>