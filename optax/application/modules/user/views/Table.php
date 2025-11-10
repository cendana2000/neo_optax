<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header">
				<div class="card-title">
					<span class="card-icon">
						<i class="fas fa-table text-primary"></i>
					</span>
					<h3 class="card-label">User Data</h3>
				</div>
				<div class="card-toolbar">
					<div class="btn-group" id="dropdown-div">
						<button class="btn btn-info btn-sm m-3 radius-5" onclick="onCreate()" data-roleable="true" data-role="User-Create"><i class="fa fa-plus"></i>Create New</button>
						<!-- <button class="btn btn-warning btn-sm m-3 radius-5" onclick="onRefresh()"><i class="flaticon-refresh"></i>Refresh</button> -->
					</div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-striped table-checkable table-condensed table-hover" id="table-user">
					<thead>
						<tr>
							<th>No</th>
							<th>Name</th>
							<th>Phone</th>
							<th>Email</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php load_view('user/Form') ?>
<?php load_view('user/Javascript') ?>