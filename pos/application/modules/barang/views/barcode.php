<div class="kt-portlet kt-portlet--mobile form_barcode" style="display: none">
	<div class="card card-custom">
		<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
			<div class="ribbon-target" style="top: 12px;">
				<span class="ribbon-inner bg-primary"></span>FORM SCAN BARCODE PRODUK
			</div>
		</div>
		<div class="card-body">
			<form class="kt-form" name="barcode_form" action="javascript:searchBarcode('barang_barcode_kode');" id="barcode_form" enctype="multipart/form-data" method="post">
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="barang_barcode_kode" class="col-2 col-form-label">Barcode Produk</label>
						<div class="col-6">
							<input class="form-control" type="text" id="barang_barcode_kode" autocomplete="off" name="barang_barcode_kode" placeholder="Scan Barcode Disini">
						</div>
						<div class="col-4">
							<button type="submit" id="btn_barcode" class="btn btn-success"><i class="la la-search"></i> Cari</button>
							<button type="reset" class="btn btn-secondary" onclick="onReset()"><i class="flaticon2-cancel-music"></i> Batal</button>
						</div>
					</div>
				</div>
			</form>
			<form class="kt-form" name="form_barang" id="form_barang" enctype="multipart/form-data" method="post" action="javascript:save_barcode('')">
				<div class="kt-portlet__body">
					<div class="form-group row">
						<label for="barang_bc" class="col-2 col-form-label">Pilih Produk</label>
						<div class="col-6">
							<input type="hidden" name="barcode_kode" id="barcode_kode">
							<select class="form-control" style="width: 100%;" id="barang_bc" name="barang_bc">
								<option value=""></option>
							</select>
						</div>
						<div class="col-4">
							<button type="button" onclick="save_barcode()" id="btn_simpan" class="btn btn-success"><i class="la la-check"></i> Simpan</button>
						</div>
					</div>
				</div>
			</form>

			<table id="table_barcode" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th style="width:5%;">No.</th>
						<th>Kode</th>
						<th>Nama Produk</th>
						<th>Kelompok Produk</th>
						<th>Sat. 1</th>
						<th>Harga 1</th>
						<th>Sat. 2</th>
						<th>Harga 2</th>
						<th>Stok</th>
						<th>Aksi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>