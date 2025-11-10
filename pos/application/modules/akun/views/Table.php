<div class="row table_data">
	<div class="col-md-12">
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						Data Akun
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-toolbar-wrapper">
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-success btn-elevate" onclick="onAdd()"><i class="fa fa-plus"></i>Tambah</button>
							<button type="button" class="btn btn-info btn-elevate btn-elevate-air" onclick="onRefresh()">
								<i class="la la-refresh"></i> Refresh
							</button>
							<button type="button" class="btn btn-twitter btn-elevate btn-elevate-air" onclick="setSaldo()">
								<i class="fa fa-dollar-sign"></i> Set Saldo
							</button>
							<button type="button" class="btn btn-primary btn-elevate btn-elevate-air" onclick="onPrint()">
								<i class="la la-print"></i> Cetak
							</button>
						</div>
						<!-- <div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-outline-success btn-elevate btn-elevate-air btn-icon" onclick="onAdd()" title="Klik untuk menambah data"><i class="la la-plus"></i></button>
							<button type="button" class="btn btn-outline-warning btn-elevate btn-elevate-air btn-icon" onclick="onEdit()" title="Klik untuk mengedit data"><i class="la la-pencil"></i></button>
							<button type="button" class="btn btn-outline-danger btn-elevate btn-elevate-air btn-icon" onclick="onDestroy()" title="Klik untuk menambah data"><i class="la la-trash"></i></button>
							<button type="button" class="btn btn-outline-primary btn-elevate btn-elevate-air btn-icon" onclick="onRefresh()" title="Klik untuk merefresh data"><i class="la la-refresh"></i></button>
						</div> -->
					</div>
				</div>
			</div>
			<div class="kt-portlet__body">
				<!--begin: Datatable -->
				<!-- <div id="tree1">
				</div> -->
				<!--end: Datatable -->
				<div class="form-group row justify-content-end">
						<label for="jurnal_umum_reference" class="col-1 col-form-label">Cari Akun</label>
						<div class="col-3">
							<input type="text" name="akun_search" id="akun_search" class="form-control" onkeyup="onSearch(this)">
						</div>
					</div>
				<div id="jstree1">
					<div id="jstree"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
	view(['javascript', 'form', 'saldo']); 
?>