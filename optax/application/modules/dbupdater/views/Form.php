<div class="row">
	<div class="col-12 col-md-12 mb-3" data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>POS Database
				</div>
			</div>
			<form class="form" action="javascript:save('form-sync_dbpos')" method="post" id="form-sync_dbpos" name="form-sync_dbpos" autocomplete="off">
				<div class="card-body">
					<div class="alert alert-warning mb-5 p-5" role="alert">
						<h4 class="alert-heading">Peringatan!</h4>
						<p>Melakukan aksi ini dapat ..., Pastikan kueri yg anda eksekusi tidak menggangu jalannya aplikasi POS.</p>
						<div class="border-bottom border-white opacity-20 mb-5"></div>
						<p class="mb-0">Hubungi Teknisi jika anda mengalami kendala.</p>
					</div>
					<div class="form-group mt-5">
						<label for="exec_jenis">Jenis Perubahan</label>
						<select id="exec_jenis" name="exec_jenis" class="form-control select2">
							<option value="tables">Tables</option>
							<option value="views">Views</option>
						</select>
					</div>
					<div class="form-group mt-5">
						<label for="exec_query">Executed Query</label>
						<input type="hidden" id="exec_id" name="exec_id">
						<textarea class="form-control" id="exec_query" name="exec_query" rows="3"></textarea>
					</div>
				</div>
				<div class="card-footer">
					<button type="submit" class="btn btn-success"><i class="flaticon-paper-plane"></i> Simpan</button>
				</div>
			</form>
    </div>
  </div>
</div>

<?php $this->load->view('Javascript'); ?>