<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'rekening_id',
			'rekening_no',
			'rekening_nama',
			'rekening_bank',
		];
		HELPER.setRequired([
			'rekening_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'rekening/',
			read: BASE_URL + 'rekening/read',
			store: BASE_URL + 'rekening/store',
			update: BASE_URL + 'rekening/update',
			destroy: BASE_URL + 'rekening/destroy',
		}
		/*HELPER.initTable({
			el : 'table-rekening',
			url: HELPER.api.table,
		})*/
		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('rekening-Update') || HELPER.get_role_access('rekening-Delete'));
		HELPER.initTable({
			el: "table-rekening",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['rekening_nama'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['rekening_no'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['rekening_bank'];
					},
				},
				{
					targets: 4,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('rekening-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['rekening_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('rekening-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['rekening_id'] + `')"">
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

	function onDelete(id) {
		console.log("onDelete : " + id);
	}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				rekening_id: id
			},
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-rekening',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-rekening'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-rekening',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function onDelete(rekening_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'rekening/delete',
						data: {
							id: rekening_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-rekening'
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
			table: 'table-rekening',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}
</script>