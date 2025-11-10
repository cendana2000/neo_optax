<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'customer_id',
			'customer_kode',
			'customer_nama',
			'customer_telp',
			'customer_alamat',
			'customer_membership',
		];
		HELPER.setRequired([
			'customer_kode',
			'customer_nama',
			// 'customer_telp',
			// 'customer_alamat',
			// 'customer_membership',
		]);
		HELPER.api = {
			table: BASE_URL + 'customer/',
			read: BASE_URL + 'customer/read',
			store: BASE_URL + 'customer/store',
			update: BASE_URL + 'customer/update',
			destroy: BASE_URL + 'customer/destroy',
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
		// let show_aksi = (HELPER.get_role_access('customer-Update') || HELPER.get_role_access('customer-Delete'));
		HELPER.initTable({
			el: "table-customer",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['customer_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['customer_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['customer_telp'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return (full['customer_membership'] !== null ? full['customer_membership'] : 0) + '%';
					},
				},
				{
					targets: -1,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('customer-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['customer_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('customer-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['customer_id'] + `')"">
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
				customer_id: id
			},
			callback: function(res) {}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-customer',
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
			table: 'table-customer'
		})
	}

	function save() {
		var customer_kode = $('input[name="customer_kode"]').val();
		if (customer_kode.length > 20) {
			HELPER.showMessage({
				title: 'Peringatan',
				success: 'warning',
				message: 'Tidak dapat menyimpan. Kode melebihi 20 huruf.',
			});
			return;
		}
		HELPER.save({
			form: 'form-customer',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onBack();
				}
			}
		})
	}

	function onDelete(customer_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'customer/delete',
						data: {
							id: customer_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-customer'
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
			table: 'table-customer',
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
			url: BASE_URL + 'customer/import',
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