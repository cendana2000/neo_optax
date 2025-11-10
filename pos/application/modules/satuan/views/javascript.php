<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'satuan_id',
			'satuan_kode',
			'satuan_nama',
		];
		HELPER.setRequired([
			'satuan_kode',
			'satuan_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'satuan/',
			read: BASE_URL + 'satuan/read',
			store: BASE_URL + 'satuan/store',
			update: BASE_URL + 'satuan/update',
			destroy: BASE_URL + 'satuan/destroy',
		}

		$('#file_import').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		loadTable();
	});

	function loadTable() {
		HELPER.initTable({
			el: "table-satuan",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['satuan_kode'];
					},
				}, {
					targets: 2,
					render: function(data, type, full, meta) {
						return full['satuan_nama'];
					},
				},
				{
					targets: 3,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('satuan-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['satuan_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('satuan-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['satuan_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-trash"></i>
											</span>
										</a>`;
						// }
						return btn_aksi;
					},
				},

			],
		});
	}

	function onDelete(satuan_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'satuan/delete',
						data: {
							id: satuan_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								onRefresh();
							} else {
								HELPER.showMessage({
									success: 'info',
									title: 'Stop',
									message: res.message
								})
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				satuan_id: id
			},
		})
	}

	function onBack() {
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-satuan'
		});
		$("#btnReset").trigger("click");
	}

	function save() {
		var satuan_kode = $('input[name="satuan_kode"]').val();
		if (satuan_kode.length > 10) {
			HELPER.showMessage({
				title: 'Peringatan',
				success: 'warning',
				message: 'Tidak dapat menyimpan. Satuan melebihi 10 huruf.',
			});
			return;
		}
		HELPER.save({
			form: 'form-satuan',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function upload() {
		var form = $('#form-import')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);

		HELPER.save({
			form: 'form-import',
			url: BASE_URL + 'satuan/import',
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					$('#importModal').modal('hide');
					$('.modal-backdrop').remove();
					onRefresh();
				}
			}
		})
	}
</script>