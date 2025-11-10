<div class="card card-custom gutter-b">
	<!--begin::Header-->
	<div class="card-header h-auto border-0">
		<div class="card-title py-5">
			<div class="row">
				<div class="col-12">
					<h3 class="m-0">
						<span class="d-block text-dark font-weight-bolder" id="dashboardTitle">Dashboard Wajib Pajak</span>
					</h3>

				</div>
			</div>
		</div>
		<div class="card-toolbar">
			<select class="form-control" name="" id="filterYear" onchange="onFilter()">
			</select>
		</div>
	</div>
</div>
<div class="card card-custom gutter-b">
	<!--begin::Header-->
	<div class="card-header h-auto border-0">
		<div class="card-body">
			<div id="chartPajak"></div>
		</div>
	</div>
</div>
<div class="card card-custom gutter-b">
	<!--begin::Header-->
	<div class="card-header h-auto border-0">
		<div class="card-body">
			<div id="chartOmzet"></div>
		</div>
	</div>
</div>

<?php $this->load->view('js'); ?>