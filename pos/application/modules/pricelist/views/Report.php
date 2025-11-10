<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">PRICELIST</h3>
				</div>
			</div>
			<div class="card-body table-responsive" style="background: #a2b2ff;border-radius: 0;">
				<form action="javascript:init_table()" name="pricelist-form" id="pricelist-form">
					<div class="row kt-margin-b-10">
						<div class="col-2">
							<label class="text-white" for="barang_kategori_barang">Pilih Berdasarkan</label>
							<select class="form-control" id="pilih" style="width: 100%" onchange="ganti(this)">
								<!-- <option value	="">-Pilih-</option> -->
								<option value="semua">Semua</option>
								<option value="kategori">Kelompok Barang</option>
								<option value="barang">Barang</option>
							</select>
						</div>
						<div class="col-4 kategori">
							<label class="text-white" for="barang_kategori_barang">Kelompok Barang</label>
							<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%" disabled="">
							</select>
						</div>
						<div class="col-4 barang">
							<label class="text-white" for="barang_id">Barang</label>
							<select class="form-control" id="barang_id" name="barang_id" disabled="">
							</select>
						</div>
						<div class="col-3 mt-4" style="margin-top: 1.9rem!important">
							<select class="form-control" style="width: 120px;" id="satuan">
								<option value="1" selected="">Satuan 1</option>
								<option value="2">Satuan 2</option>
								<option value="3 ">Satuan 3</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-6" style="margin-top: 25px;" align="left">
							<button type="button" class="btn btn-primary btn-elevate" id="kt_search" onclick="init_table(this)">
								<span>
									<i class="la la-check"></i>
									<span>Proses</span>
								</span>
							</button>
							<button type="button" class="btn btn-success btn-elevate" id="kt_print" onclick="print_table(this)">
								<span>
									<i class="la la-print"></i>
									<span>Cetak</span>
								</span>
							</button>
							<button type="button" class="btn btn-info btn-elevate" id="kt_print_card" onclick="print_table_card(this)">
								<span>
									<i class="la la-print"></i>
									<span>Cetak Price Card</span>
								</span>
							</button>
							<span class="border rounded" style="color:#fff; padding: 8px 7px; vertical-align: middle;">Warna : <input type="color" name="print_color" id="print_color" value="#ffffff" style="border-width: 1px;"></span>
						</div>
					</div>
					<div class="kt-separator kt-separator--md kt-separator--dashed"></div>
				</form>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-checkable table-condensed" id="table-pricelist" style="width: 100%">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Barang</th>
							<th>Kelompok Barang</th>
							<th>Stok</th>
							<th>Sat. 1</th>
							<th>Harga 1</th>
							<th>Sat. 2</th>
							<th>Harga 2</th>
							<th>sat. 3</th>
							<th>Harga 3</th>
						</tr>
					</thead>
					<tbody>
						<tr class="no-list">
							<td colspan="11" class="text-center">Price List Tidak Tersedia</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Kode</th>
							<th>Nama Barang</th>
							<th>Kelompok Barang</th>
							<th>Stok</th>
							<th>Sat. 1</th>
							<th>Harga 1</th>
							<th>Sat. 2</th>
							<th>Harga 2</th>
							<th>sat. 3</th>
							<th>Harga 3</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="kt-portlet kt-portlet--mobile">
	<div class="kt-portlet__body print_table" style="display: none">
		<div class="kt-form">
			<div class="kt-portlet__body form" id="pdf-laporan">
				<object data="" type="application/pdf" width="100%" height="500px"></object>
			</div>
		</div>
	</div>
</div>
<?php load_view('javascript.php'); ?>