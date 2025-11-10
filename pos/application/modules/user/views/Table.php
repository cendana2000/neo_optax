<div class="row table_data">
	<div class="col-12">
		<div class="card card-custom">
			<div class="card-header card-header-right ribbon ribbon-clip ribbon-left">
				<div class="ribbon-target" style="top: 12px;">
					<span class="ribbon-inner btn-primary"></span>User Data
				</div>
				<div class="card-toolbar">
					<div class="btn-group" id="dropdown-div">
                        <button class="btn btn-primary btn-sm m-3 radius-5" onclick="onCreate()" data-roleable="true" data-role="User-Create"><i class="fa fa-plus"></i>Create New</button>
						<button class="btn btn-warning btn-sm m-3 radius-5" onclick="onRefresh()"><i class="flaticon-refresh"></i>Refresh</button>
                    </div>
				</div>
			</div>
			<div class="card-body table-responsive">
				<table class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover" id="table-user">
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