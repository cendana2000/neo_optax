<div class="modal fade" id="modal_hak_akses" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textLabelModal">Form Hak Akses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form_data">
                <form class="kt-form kt-form--fit kt-form--label-align-right" action="javascript:save('category_form')" name="category_form" id="category_form" enctype="multipart/form-data" method="post">
                    <div class="kt-portlet__body col-12 row">
                        <input type="hidden" name="seller_category_id" id="seller_category_id">
                        <div class="col-12">
                            <div class="form-group kt-form__group">
								<label for="hak_akses_kode">Kode</label>
								<input type="text" id="hak_akses_kode" name="hak_akses_kode" class="form-control hak_akses_kode" placeholder="Kode" aria-describedby="hak_akses_code" autocomplete="off" required data-fv-not-empty___message="This field is required">
                            </div>
                            <div class="form-group kt-form__group">
								<label for="hak_akses_nama">Nama</label>
								<input type="text" id="hak_akses_nama" name="hak_akses_nama" class="form-control hak_akses_nama" placeholder="Nama" aria-describedby="hak_akses_nama" autocomplete="off" required data-fv-not-empty___message="This field is required">
							</div>
							<div class="form-group  kt-form__group">
								<label for="hak_akses_keterangan">Keterangan</label>
								<textarea type="textarea" name="hak_akses_keterangan" class="form-control hak_akses_keterangan" placeholder="Keterangan"></textarea>
							</div>	
                        </div>
                    </div>
                    <div class="card-footer">
						<div class="row">
							<div class="col-12 text-right">
								<button type="button" class="btn btn-warning btn-sm" onclick="onReset()"><i class="flaticon-refresh"></i>Reset</button>
								<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i>Simpan</button>
							</div>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="col-8">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>Data Hak Akses
				</div>
				<div class="card-toolbar">
					<!-- <button class="btn btn-primary" onclick="onReset()"><i class="fa fa-plus"></i> Tambah Baru</button> -->
				</div>
			</div>
			<div class="card-body">
				<table class="table table-striped table-hover" id="table-jenis">
					<thead>
						<tr>
							<th>No</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Keterangan</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php load_view('Javascript') ?>