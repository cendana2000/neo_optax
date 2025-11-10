<div class="row form-custom-padding">
	<div class="col-12 col-md-6 mb-3 " data-roleable="false" data-role="preset-Create" data-action="hide">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">FORM PRESET</h3>
				</div>
			</div>
			<form action="javascript:save('form-preset')" method="post" id="form-preset" name="form-preset" autocomplete="off">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<input type="hidden" name="preset_id" id="preset_id">
							<div class="form-group row">
								<label class="col-lg-4 col-form-label text-left" for="preset_nama">Nama Preset</label>
								<div class="col-lg-8">
									<input type="text" name="preset_nama" id="preset_nama" class="form-control preset_nama" placeholder="Masukan nama preset" required minlength="2" maxlength="150">
								</div>
							</div>
							<div class="row ml-1 mb-2">
								<h4>Detail Preset</h4>

							</div>
							<div id="detail_holder">
								<div class="row ml-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_tanggal" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_0" type="text" placeholder="Key API WP" class="form-control nospace"></div>
								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_kode" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_1" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_total_item" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_2" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_total_qty" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_3" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_sub_total" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_4" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_total_nilai_pajak" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_5" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_total_grand" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_6" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_nama_customer" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_7" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_user_nama" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_8" type="text" placeholder="Key API WP" class="form-control nospace"></div>

								</div>
								<div class="row ml-2 mt-2" id="row_preset_0">
									<div id="detail_holder"></div>
									<div class="col-6"><input name="preset_detail_left[]" readonly style="background-color: #D3D3D3;" value="penjualan_jasa" type="text" placeholder="Key Persada" class="form-control nospace"></div>
									<div class="col-6"><input name="preset_detail_right[]" required id="preset_detail_right_9" type="text" placeholder="Key API WP" class="form-control nospace"></div>

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
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">DATA PRESET</h3>
				</div>
				<!-- <div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh()"><i class="flaticon-refresh"></i> Muat Ulang</button>
					</div>
				</div> -->
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-preset">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Preset</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Preset</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript') ?>