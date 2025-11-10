<div class="row">
	<div class="col-12 col-md-5 mb-3 " data-roleable="false" data-role="customer-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>FORM CUSTOMER
				</div>
			</div>
			<form action="javascript:save('form-customer')" method="post" id="form-customer" name="form-customer" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="customer_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="customer_kode">Kode</label>
								<div class="col-lg-8">
									<input type="text" name="customer_kode" class="form-control customer_kode" placeholder="Kode Customer" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="customer_nama">Nama</label>
								<div class="col-lg-8">
									<input type="text" name="customer_nama" class="form-control customer_nama" placeholder="Nama Customer" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="customer_membership">Membership</label>
								<div class="col-lg-8">
									<select name="customer_membership" class="form-control customer_membership" style="width: 100%;">
										<option value="">-- Pilih Membership --</option>
										<option value="Bronze">Bronze</option>
										<option value="Silver">Silver</option>
										<option value="Gold">Gold</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-6 text-left">
							<button type="reset" class="btn btn-sm btn-danger" onclick="onBack()"><i class="fa fa-redo"></i>Reset</button>
						</div>
						<div class="col text-right">
							<button type="submit" id="btnSave" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="col">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>DATA CUSTOMER
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-customer">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Membership</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Kode</th>
							<th>Nama</th>
							<th>Membership</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>