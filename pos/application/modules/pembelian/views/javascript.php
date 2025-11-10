<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'satuan_id',
			'satuan_kode',
			'satuan_nama',
		];
		HELPER.setRequired([
			'satuan_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'pembelian/',
			read: BASE_URL + 'pembelian/read',
			store: BASE_URL + 'pembelian/store',
			update: BASE_URL + 'pembelian/update',
			destroy: BASE_URL + 'pembelian/destroy',
		}
		/*HELPER.initTable({
			el : 'table-satuan',
			url: HELPER.api.table,
		})*/
		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('satuan-Update') || HELPER.get_role_access('satuan-Delete'));
		HELPER.initTable({
			el: "table-satuan",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['satuan_nama'];
					},
				}, {
					targets: 2,
					render: function(data, type, full, meta) {
						return full['satuan_kode'];
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

	function onDelete(id) {
		console.log("onDelete : " + id);
	}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				satuan_id: id
			},
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-satuan',
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
			table: 'table-satuan'
		})
	}

	function save() {
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

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-satuan',
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