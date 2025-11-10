<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'wajibpajak_id',
			'wajibpajak_npwpd',
			'wajibpajak_nama',
			'wajibpajak_nama_penanggungjawab',
		];
		HELPER.setRequired([
			'wajibpajak_kode',
			'wajibpajak_nama',
			'wajibpajak_membership',
		]);
		HELPER.api = {
			table: BASE_URL + 'wajibpajak/',
			read: BASE_URL + 'permohonan/read',
			store: BASE_URL + 'permohonan/store',
			update: BASE_URL + 'permohonan/update',
			destroy: BASE_URL + 'permohonan/destroy',
		}

		HELPER.createCombo({
			el: 'wajibpajak_preset',
			valueField: 'preset_id',
			displayField: 'preset_nama',
			url: BASE_URL + 'preset/select',
			callback: function() {
				$('#wajibpajak_preset').select2();
			}
		})

		$(".sort-by-status .nav-link").click(function(e) {
			$(".sort-by-status .nav-link").removeClass("active");
			$(this).addClass("active");
			loadTable();
		});

		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('wajibpajak-Update') || HELPER.get_role_access('wajibpajak-Delete'));
		mstatus = {
			0: '<span class="label label-inline label-warning">Tidak Aktif</span>',
			1: '<span class="label label-inline ">Permohonan</span>',
			2: '<span class="label label-inline label-success">Disetujui</span>',
			3: '<span class="label label-inline label-danger">Ditolak</span>',
			4: '<span class="label label-inline label-danger">Ditolak Dengan Revisi</span>',
			5: '<span class="label label-inline label-primary">Dummy</span>',
		}
		posstatus = {
			posActive: '<span class="label label-inline label-secondary ml-2">POS</span>',
			posInactive: '<span class="label label-inline label-secondary ml-2">POS Tidak Aktif</span>',
			oapiActive: '<span class="label label-inline label-info ml-2">Outer API</span>',
			oapiInactive: '<span class="label label-inline label-secondary ml-2">Outer API Tidak Aktif</span>',
		}
		HELPER.initTable({
			el: "table-wajibpajak",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			data: {
				"filter_status": $(".sort-by-status .nav-link.active").attr("data")
			},
			order: [
				[6, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['wajibpajak_npwpd'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['toko_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['jenis_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama_penanggungjawab'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['wajibpajak_created_at'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return mstatus[full['wajibpajak_status']] + (full['toko_is_pos'] === 'ACTIVE' ? posstatus['posActive'] : '') + (full['toko_is_oapi'] === 'ACTIVE' ? posstatus['oapiActive'] : '');
					},
				},
				{
					targets: 8,
					// width: '50px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<div class="dropdown dropdown-inline mr-4">
							<button type="button" class="btn btn-sm btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Detail
							</button>
							<div class="dropdown-menu">
										<a class="dropdown-item" href="#" onclick="onDetail('` + full['wajibpajak_id'] + `')">Detail WP</a>
										<a class="dropdown-item" href="#" onclick="onPosOapi('` + full['wajibpajak_id'] + `')">${full['toko_is_oapi'] === 'ACTIVE' ? 'Edit' : 'Aktifkan'} POS OAPI</a>
							</div>
						</div>`;
						return btn_aksi;
					},
				},

			],
		});
	}

	function onDelete(id) {
		console.log("onDelete : " + id);
	}

	function onDetail(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				wajibpajak_id: id
			},
			callback: function(res) {
				$('#wajibpajak_usaha_nama').val(res.jenis_nama);
				$('input[name=wajibpajak_nama]').val(res.wajibpajak_nama);
				$('#wajibpajak_berkas_npwp').attr('style', 'height:200px;background-image: url(' + BASE_URL_NO_INDEX + res.wajibpajak_berkas + ')');
				// console.log(res);
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				})
			}
		})
	}

	function onEdit2(el) {
		HELPER.loadData({
			table: 'table-wajibpajak',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				// console.log(res)
			}
		})
	}

	function onBack() {
		$('.menu-item-active>a').click();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-wajibpajak'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-wajibpajak',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onBack();
				}
			}
		})
	}

	function onDelete(wajibpajak_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'wajibpajak/delete',
						data: {
							id: wajibpajak_id
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
									table: 'table-wajibpajak'
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
			table: 'table-wajibpajak',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function onPosOapi(wajibpajak_id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				wajibpajak_id: wajibpajak_id
			},
			callback: function(res) {
				$('#wajibpajak_usaha_nama').val(res.jenis_nama);
				$('input[name=wajibpajak_nama]').val(res.wajibpajak_nama);
				$('#wajibpajak_endpoint').val(res.toko_api_penjualan);
				$('#wajibpajak_schedule_before').val(res.toko_jadwal_before);
				$('#wajibpajak_preset').val(res.toko_preset_id).change()
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_oapi'
				})
			}
		})
	}

	function saveOAPI() {
		if (!$("#wajibpajak_preset").val() || !$("#wajibpajak_endpoint").val()) {
			HELPER.showMessage({
				success: false,
				title: 'Informasi',
				message: 'Inputan preset dan endpoint harus di isi!'
			})
			return;
		}

		HELPER.confirm({
			message: 'Anda yakin ingin membuat WP ini menjadi POS OAPI ?',
			callback: function(suc) {
				if (suc) {
					HELPER.block();
					HELPER.ajax({
						url: BASE_URL + 'permohonantoko/genOapiPos',
						type: 'POST',
						data: {
							wajibpajak_id: $('#wajibpajak_id').val(),
							wajibpajak_endpoint: $('#wajibpajak_endpoint').val(),
							wajibpajak_preset: $('#wajibpajak_preset').val(),
							wajibpajak_schedule_before: $('#wajibpajak_schedule_before').val(),
						},
						datatype: 'json',
						complete: (res) => {
							HELPER.showMessage({
								success: res.success,
								title: 'Informasi',
								message: res.message
							})
							HELPER.unblock();
							onBack();
						}
					});
				}
			}
		})
	}

	function onSyncOAPI() {
		HELPER.confirm({
			message: 'Anda yakin ingin Sinkronkan POS OAPI ?',
			callback: function(suc) {
				if (suc) {
					HELPER.block();
					HELPER.ajax({
						url: BASE_URL + 'oapi/setQueue',
						type: 'POST',
						complete: (res) => {
							HELPER.showMessage({
								success: res.success,
								title: 'Informasi',
								message: res.message
							})
							HELPER.unblock();
						}
					});
				}
			}
		})
	}
</script>