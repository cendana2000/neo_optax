<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'pegawai_id',
			'pegawai_nik',
			'pegawai_nama',
			'pegawai_jabatan',
			'pegawai_hp',
			'pegawai_jk',
			'pegawai_alamat',
		];
		/*HELPER.setRequired([
			'agama_nama',
		]);*/
		HELPER.api = {
			table: BASE_URL + 'pegawai/',
			read: BASE_URL + 'pegawai/read',
			store: BASE_URL + 'pegawai/store',
			update: BASE_URL + 'pegawai/update',
			destroy: BASE_URL + 'pegawai/destroy',
			jabatan: BASE_URL + 'jabatan/select',
		}

		$('#file_import').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		loadTable();
		$('#user_pegawai_id, #user_role_id').select2();

	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-pegawai",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['pegawai_nik'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['pegawai_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return (full['pegawai_jk'] == 'L') ? 'Laki-Laki' : 'Perempuan';
					},
				},
				{
					targets: 4,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['pegawai_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-trash"></i>
											</span>
										</a>`;
						return btn_aksi;
					},
				},

			],
		});
	}


	function init_table(argument) {
		var table = $('#table-pegawai').DataTable({
			responsive: true,
			select: 'single',
			buttons: [
				'print',
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
			],
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			processing: true,
			serverSide: true,
			ajax: {
				url: BASE_URL + 'pegawai/',
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

	function onAdd() {
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_data',
		});
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-pegawai',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				onAdd();
				$('#pegawai_jabatan').val(res.pegawai_jabatan).trigger('change');
				$('#pegawai_jk').val(res.pegawai_jk).trigger('change');
			}
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onDelete(pegawai_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'pegawai/delete',
						data: {
							id: pegawai_id
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
									table: 'table-pegawai'
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


	function onRefresh() {
		HELPER.refresh({
			table: 'table-pegawai'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-pegawai',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					HELPER.backMenu();
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-pegawai',
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
			url: BASE_URL + 'pegawai/import',
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