<div class="row">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
        <div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">Konfigurasi Hak Akses Wajib Pajak</h3>
				</div>
				<div class="card-toolbar">
				</div>
			</div>
			<div class="card-body table-responsive">
				<form class="kt-form" action="javascript:save_role_menu()" name="form-tree" id="form-tree">
					<input type="hidden" id="tree_role_id" name="tree_role_id"/>
          <div class="form-group row">
            <label class="col-3 col-form-label">Terapkan Semua WP</label>
            <div class="col-3">
            <span class="switch">
              <label>
                <input type="checkbox" name="switch_semua_wp" id="switch_semua_wp"/>
                <span></span>
              </label>
            </span>
            </div>
          </div>
          <div class="form-group">
            <label>Wajib Pajak</label>
            <select class="form-control" id="wajibpajak" name="wajibpajak">
              <option value="">Pilih WP</option>
              <option value="semua">Semua WP</option>
            </select>
          </div>
          <div class="form-group">
            <label>Hak Akses</label>
            <!-- JS Tree -->
            <div id="tree1">
            </div>
            <!-- End JS Tree -->
          </div>
					<button id="btn-save-config" type="submit" class="btn btn-success btn-block d-none mt-5"><i class="flaticon-paper-plane-1"></i> Simpan</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal-form-hakakses" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Hak Akses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form class="kt-form" action="javascript:save('form-hak-akses')" name="form-hak-akses" id="form-hak-akses">
              <div class="modal-body">
                <div class="form-group pb-0 mb-0">
                  <label>Nama Hak Akses</label>
                  <input type="hidden" id="role_access_id" name="role_access_id"/>
                  <input type="text" class="form-control" id="role_access_nama" name="role_access_nama" placeholder="Masukan Nama Hak Akses"/>
                </div>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                  <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary font-weight-bold">Simpan</button>
              </div>
            </form>
        </div>
    </div>
</div>

<?php load_view('Javascript') ?>