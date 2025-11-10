<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'custom_menu_id',
			'custom_menu_nama',
			'custom_menu_harga',
			'custom_menu_created_at',
			'custom_menu_deleted_at',
			'custom_menu_updated_at',
		];
		HELPER.setRequired([
			'custom_menu_nama',
			'custom_menu_harga',
		]);
		HELPER.api = {
			table: BASE_URL + 'custommenu/',
			read: BASE_URL + 'custommenu/read',
			store: BASE_URL + 'custommenu/store',
			update: BASE_URL + 'custommenu/update',
			destroy: BASE_URL + 'custommenu/destroy',
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
			el: "table-custom-menu",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['custom_menu_nama'];
					},
				}, {
					targets: 2,
					render: function(data, type, full, meta) {
						return 'Rp. ' + $.number(full['custom_menu_harga']);
					},
				},
				{
					targets: 3,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('custom-menu-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['custom_menu_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('custom-menu-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['custom_menu_id'] + `')"">
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

	function onDelete(custom_menu_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'custommenu/delete',
						data: {
							id: custom_menu_id
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
				custom_menu_id: id
			},
		})
	}

	function onBack() {
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-custom-menu'
		});
		$("#btnReset").trigger("click");
	}

	function save() {
		HELPER.save({
			form: 'form-custom-menu',
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
			url: BASE_URL + 'custommenu/import',
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