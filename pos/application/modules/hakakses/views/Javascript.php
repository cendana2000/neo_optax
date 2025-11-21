<script type="text/javascript">
	var fv;
	$(function() {

		HELPER.fields = [
			'hak_akses_id',
			'hak_akses_kode',
			'hak_akses_nama',
			'hak_akses_keterangan',
		];
		HELPER.api = {
			table: BASE_URL + 'HakAkses/loadTable',
			store: BASE_URL + 'HakAkses/store',
			update: BASE_URL + 'HakAkses/update',
			read: BASE_URL + 'HakAkses/read',
			delete: BASE_URL + 'HakAkses/delete',
		};

		fv = HELPER.newHandleValidation({
			el: 'form-jenis',
			setting: [{
					name: "Jenis Kode",
					selector: ".hak_akses_kode",
					rule: {
						required: true,
						maxlength: 10,
						minlength: 3
					},
				},
				{
					name: "Jenis Nama",
					selector: ".hak_akses_nama",
					rule: {
						required: true,
					},
				}
			]
		});


		loadTable();
	})


	function loadTable() {

		HELPER.initTable({
			el: "table-jenis",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['hak_akses_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['hak_akses_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return `<span title="` + full['hak_akses_keterangan'] + `">` +
							HELPER.text_truncate(full['hak_akses_keterangan'], 30) + `</span>`;
					},
				},
				{
					targets: 4,
					width: '20px',
					render: function(data, type, full, meta) {
						return `
							<a href="javascript:;" class="btn btn-sm btn-clean btn-icon" onclick="onEdit('` + full['hak_akses_id'] + `')">\
	                            <span class="svg-icon svg-icon-md">\
	                                <i class="fa fa-pen"></i>
	                            </span>\
	                        </a>\
	                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" onclick="onDelete('` + full['hak_akses_id'] + `')"">\
	                            <span class="svg-icon svg-icon-md">\
	                                <i class="fa fa-trash"></i>
	                            </span>\
	                        </a>\
						`;
					},
				},
			],
		});
	}

	function onReset() {
		fv.resetForm()
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('')
		});
	}

	function save(name) {
		HELPER.block(0);
		$('#modal_hak_akses').modal('hide');
		var form = $('#' + name)[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			data: formData,
			contentType: false,
			processData: false,
			form: "jenis-form",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Sukses",
						message: "Berhasil menyimpan data"
					});
					onReset()
					loadTable()
				} else {
					$('#modal_hak_akses').modal('show');
					HELPER.showMessage({
						success: false
					})
				}
				HELPER.unblock(100);
			},
			oncancel: function(result) {
				if (result) {
					$('#modal_hak_akses').modal('show');
				}
			}
		});
		HELPER.unblock(100);
	}

	function onEdit(hak_akses_id) {
		HELPER.ajax({
			url: HELPER.api.read,
			data: {
				id: hak_akses_id
			},
			complete: function(res) {
				// $('#hak_akses_id').val(res.hak_akses_id);
				$('#hak_akses_kode').val(res.hak_akses_kode);
				$('#hak_akses_nama').val(res.hak_akses_nama);
				$('#hak_akses_keterangan').val(res.hak_akses_keterangan);
				$('.form_data').show()
			}
		});
		$('#modal_hak_akses').modal('show')
	}

	function onDelete(hak_akses_id) {
		HELPER.confirm({
			message: 'Apakah Anda yakin ingin menghapus ?',
			callback: function(suc) {
				if (suc) {
					HELPER.block()

					HELPER.ajax({
						url: HELPER.api.delete,
						data: {
							id: hak_akses_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'Anda berhasil menghapus.'
								})

								HELPER.refresh({
									table: 'table-jenis'
								});
							} else {
								HELPER.showMessage()
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}
</script>