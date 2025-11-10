<div class="row">
  <div class="col-12">
    <div class="card card-custom">
			<div class="card-header">
			<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN SURVEY</h3>
				</div>
				
			</div>
      <form action="javascript:getLaporan()" name="laporan_survey" id="laporan_survey">
        <div class="card-body table-responsive">
					<div class="form-group row">
            <label for="jenis_retur" class="col-2 col-form-label">Survey</label>
            <div class="col-10">
							<select class="form-control select2" name="data_survey" id="data_survey">
							</select>
            </div>
          </div>
          <div class="form-group row">
            <label for="jenis_retur" class="col-2 col-form-label">Jenis Laporan</label>
            <div class="col-10">
							<select class="form-control select2" name="jenis_laporan">
								<option value="rekapan">Rekap</option>
								<option value="grafis">Info Grafis</option>
							</select>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success"><i class="flaticon-paper-plane-1"></i> Proses</button>
        </div>
      </form>
		</div>
  </div>

	<div class="col-12 mt-8 d-none" id="laporan_rekapan">
		<div class="card card-custom">
			<div class="card-header">
			<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN REKAP</h3>
				</div>
				
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
					</div>
				</div>
			</div>
			<div class="card-body table-responsive" id="place_table_rekapan">
			</div>
		</div>
	</div>

	<div class="col-12 mt-8 d-none" id="laporan_grafis">
		<div class="card card-custom">
			<div class="card-header">
			<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">LAPORAN INFO GRAFIS</h3>
				</div>				
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
					</div>
				</div>
			</div>
			<div class="card-body table-responsive" id="place_grafis">
				
			</div>
		</div>
	</div>

  <!--<div class="col-12 mt-8">
		<div class="card card-custom">
			<div class="card-header">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner bg-primary"></span>HASIL SURVEY
				</div>
				<div class="card-toolbar">
					<div class="example-tools justify-content-center">
						<button class="btn btn-warning btn-sm" onclick="onRefresh('table-hasil-survey')"><i class="flaticon-refresh icon-md"></i> Muat Ulang</button>
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-hasil-survey">
					<thead>
						<tr>
							<th style="width:5%;">No.</th>
							<th>Nama Survey</th>
							<th>Tgl Publish</th>
							<th>Tgl Selesai</th>
							<th>Jml Pertanyaan</th>
							<th>Jml Peserta</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th>No.</th>
							<th>Nama Survey</th>
							<th>Tgl Publish</th>
							<th>Tgl Selesai</th>
							<th>Jml Pertanyaan</th>
							<th>Jml Peserta</th>
							<th>Aksi</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>-->
</div>

<?php $this->load->view('Javascript'); ?>