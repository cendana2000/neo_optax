<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'meja_id',
			'meja_nama',
		];
		HELPER.setRequired([
			'meja_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'meja/',
			read: BASE_URL + 'meja/read',
			store: BASE_URL + 'meja/store',
			update: BASE_URL + 'meja/update',
			destroy: BASE_URL + 'meja/destroy',
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
		// let show_aksi = (HELPER.get_role_access('Meja-Update') || HELPER.get_role_access('Meja-Delete'));
		HELPER.initTable({
			el: "table-meja",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['meja_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['meja_nama'];
					},
				},
				{
					targets: -1,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('Meja-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['meja_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('Meja-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['meja_id'] + `')"">
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

	function onDelete(id) {}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				meja_id: id
			},
			callback: function(res) {}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-meja',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {}
		})
	}

	function onBack() {
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-meja'
		})
	}

	function save() {
		var meja_kode = $('input[name="meja_kode"]').val();
		if(meja_kode.length > 20){
			HELPER.showMessage({
				title: 'Peringatan',
				success: 'warning',
				message: 'Tidak dapat menyimpan. Kode melebihi 20 huruf.',
			});
			return;
		}
		HELPER.save({
			form: 'form-meja',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onBack();
				}
			}
		})
	}

	function onDelete(meja_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'meja/delete',
						data: {
							id: meja_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-meja'
								});
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


	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-meja',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function upload() {
		var form = $('#form-import')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);

		HELPER.save({
			form: 'form-import',
			url: BASE_URL + 'meja/import',
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