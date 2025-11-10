<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'preset_id',
			'preset_nama',
			'preset_created_at',
			'preset_updated_at',
			'preset_deleted_at',
		];
		HELPER.setRequired([
			'preset_nama',
		]);

		HELPER.api = {
			table: BASE_URL + 'preset/',
			read: BASE_URL + 'preset/read',
			store: BASE_URL + 'preset/store',
			update: BASE_URL + 'preset/update',
			destroy: BASE_URL + 'preset/destroy',
			get_parent: BASE_URL + 'preset/get_parent',
		}

		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('preset-Update') || HELPER.get_role_access('preset-Delete'));
		HELPER.initTable({
			el: "table-preset",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['preset_nama'];
					},
				},
				{
					targets: -1,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('preset-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['preset_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('preset-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['preset_id'] + `')"">
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

	function onEdit(id) {
		HELPER.block();
		HELPER.ajax({
			url: HELPER.api.read,
			datatype: 'json',
			data: {
				preset_id: id
			},
			complete: (res) => {
				$('#preset_id').val(res.data.parent.preset_id);
				$('#preset_nama').val(res.data.parent.preset_nama);

				$.map(res.data.detail, (v, i) => {
					console.log(i);

					$('#preset_detail_right_' + i).val(v.preset_detail_right);
				});
				HELPER.unblock();
			}
		});
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-preset',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onBack() {
		$('.menu-item-active > a').click();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-preset'
		})
		$(":reset").trigger("click");
	}

	function save() {
		HELPER.save({
			form: 'form-preset',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function onDelete(preset_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'preset/delete',
						data: {
							id: preset_id
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
									table: 'table-preset'
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
			table: 'table-preset',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	var row = 0

	function addRow(index) {
		if ((row + 1) == index) {
			let html = `
			<div class="row ml-2 mt-1" id="row_preset_${index}">
				<div id="detail_holder"></div>
				<div class="col-5"><input name="preset_detail_left[]" type="text" onchange="addRow(${index + 1})" placeholder="Key Persada" class="form-control nospace"></div>
				<div class="col-5"><input name="preset_detail_right[]" type="text" onchange="addRow(${index + 1})" placeholder="Key API WP" class="form-control nospace"></div>
				<div class="col-2">
					<button onclick="remRow(${index})" class="btn btn-danger"><span class="fas fa-trash"></span></button>
				</div>
			</div>`;
			row = index;
			$('#detail_holder').append(html);
		}
	}

	function remRow(index) {
		HELPER.confirm({
			message: 'Anda yakin ingin melakukan proses ini ?',
			callback: (result) => {
				if (result) {
					$('#row_preset_' + index).remove();
				}
			}
		});
	}
</script>