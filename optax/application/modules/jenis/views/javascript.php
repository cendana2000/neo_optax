<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'jenis_id',
			'jenis_deskripsi',
			'jenis_nama',
		];
		HELPER.setRequired([
			'jenis_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'jenis/',
			read: BASE_URL + 'jenis/read',
			store: BASE_URL + 'jenis/store',
			update: BASE_URL + 'jenis/update',
			destroy: BASE_URL + 'jenis/destroy',
			get_parent: BASE_URL + 'jenis/get_parent',
		}

		HELPER.createCombo({
			el: 'jenis_parent',
			valueField: 'jenis_id',
			displayField: 'jenis_nama',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: true,
			callback: function() {
				$('#jenis_parent').select2();
			}
		});
		/*HELPER.initTable({
			el : 'table-jenis',
			url: HELPER.api.table,
		})*/
		loadTable();
		showInduk();
	});


	function showInduk() {
		tipe = $('[name=jenis_tipe]').val();
		if (tipe !== 'parent') {
			$('.induk').show('500');
		} else {
			$('.induk').hide('500');
			$('#jenis_parent').val('').trigger('change');
		}
	}

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('jenis-Update') || HELPER.get_role_access('jenis-Delete'));
		HELPER.initTable({
			el: "table-jenis",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['jenis_nama'];
					},
				}, {
					targets: 2,
					render: function(data, type, full, meta) {
						return full['jenis_tarif'] ? full['jenis_tarif'] + ' %' : '';
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['jenis_keterangan'];
					},
				},
				{
					targets: 4,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('jenis-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['jenis_id'] + `')">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-pen"></i>
											</span>
										</a>`;
						// }
						// if (HELPER.get_role_access('jenis-Delete')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['jenis_id'] + `')"">
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

	function init_table(argument) {
		var table = $('#table-jenis').DataTable({
			responsive: true,
			select: 'single',
			buttons: [
				'print',
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
			],
			processing: true,
			serverSide: true,
			ajax: {
				url: BASE_URL + 'jenis/',
				type: 'POST'
			},
			order: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>`;
					},
				}
			],
			fnDrawCallback: function(oSettings) {
				var cnt = 0;
				$("tr", this).css('cursor', 'pointer');
				$("tbody tr", this).each(function(i, v) {
					$(v).on('click', function() {
						if ($(this).hasClass('selected')) {
							--cnt;
							$(v).removeClass('selected');
							$(v).removeAttr('checked');
							$('input[name=checkbox]', v).prop('checked', false);
							$(v).removeClass('row_selected');
						} else {
							++cnt;
							$('input[name=checkbox]', v).prop('checked', true);
							$('input[name=checkbox]', v).data('checked', 'aja');
							$(v).addClass('selected');
							$(v).addClass('row_selected asli');
						}

						if (cnt > 0) {
							$('.disable').attr('disabled', false);
						} else {
							$('.disable').attr('disabled', true);
						}
					});
				});
			},
		});
	}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				jenis_id: id
			},
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-jenis',
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
			table: 'table-jenis'
		})
		$(":reset").trigger("click");
	}

	function save() {
		HELPER.save({
			form: 'form-jenis',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function onDelete(jenis_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'jenis/delete',
						data: {
							id: jenis_id
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
									table: 'table-jenis'
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
			table: 'table-jenis',
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