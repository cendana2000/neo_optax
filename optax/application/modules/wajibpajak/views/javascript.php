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
			singleOapiConsume: BASE_URL + 'oapi/setQueueSingle',
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

		let CURRENT_NPWPD = null;

		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('wajibpajak-Update') || HELPER.get_role_access('wajibpajak-Delete'));
		mstatus = {
			0: '<span class="label label-inline label-warning">Tidak Aktif</span>',
			1: '<span class="label label-inline ">Permohonan</span>',
			2: '<span class="label label-inline label-success">Aktif</span>',
			3: '<span class="label label-inline label-danger">Ditolak</span>',
			4: '<span class="label label-inline label-danger">Ditolak Dengan Revisi</span>',
			5: '<span class="label label-inline label-primary">Dummy</span>',
		}
		posstatus = {
			posActive: '<span class="label label-inline label-secondary ml-2">POS</span>',
			posInactive: '<span class="label label-inline label-secondary ml-2">POS Tidak Aktif</span>',
			oapiActive: '<span class="label label-inline label-secondary ml-2">API Reader</span>',
			oapiInactive: '<span class="label label-inline label-secondary ml-2">API Tidak Aktif</span>',
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
						return full['wajibpajak_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['jenis_nama'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama_penanggungjawab'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['wajibpajak_created_at'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return mstatus[full['wajibpajak_status']] + (full['toko_is_oapi'] === 'ACTIVE' ? posstatus['oapiActive'] : '');
					},
				},
				{
					targets: -1,
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
										<a class="dropdown-item" href="#" onclick="onDetail('` + full['wajibpajak_id'] + `')">Detail</a>
										<a class="dropdown-item" href="#" onclick="onPosOapi('` + full['wajibpajak_id'] + `')">${full['toko_is_oapi'] === 'ACTIVE' ? 'Edit' : 'Aktifkan'} API Reader</a>
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
				CURRENT_NPWPD = res.wajibpajak_npwpd;
				BASE_URL_NO_INDEX + 'assets/media/berkasnpwpd/' + res.wajibpajak_berkas
				const imagePath = BASE_URL_NO_INDEX + 'assets/media/berkasnpwpd/' + res.wajibpajak_berkas;

				$('#wajibpajak_usaha_nama').val(res.jenis_nama);
				$('input[name=wajibpajak_nama]').val(res.wajibpajak_nama);
				$('input[name=wajibpajak_npwpd]').val(res.wajibpajak_npwpd);
				if (res.toko_is_oapi === 'ACTIVE') {
					$('#btn-sync-oapi-detail').removeClass('d-none');
				} else {
					$('#btn-sync-oapi-detail').addClass('d-none');
				}
				testImageLoad(imagePath, function(success) {
					if (success) {
						$('#wajibpajak_berkas_npwp').css({
							'background-image': 'url(' + imagePath + ')',
							'background-size': 'cover',
							'background-position': 'center',
							'height': '200px'
						});
					} else {
						$('#wajibpajak_berkas_npwp').css({
							'background-image': 'url(assets/media/users/blank.png)',
							'background-size': 'cover',
							'background-position': 'center',
							'height': '200px'
						});
					}
				});

				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				});
			}
		});
	}

	function testImageLoad(url, callback) {
		const img = new Image();
		img.onload = function() {
			console.log('Gambar berhasil dimuat:', url);
			callback(true);
		};
		img.onerror = function() {
			console.error('GAGAL memuat gambar:', url);
			callback(false);
		};
		img.src = url;
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

	function onPosOapiSyncFromDetail() {
		if (!CURRENT_NPWPD) {
			Swal.fire('Error', 'NPWPD tidak ditemukan', 'error');
			return;
		}
		onPosOapiSync(CURRENT_NPWPD);
	}

	var is_proses_syncrone_done = true;

	function onPosOapiSync(npwpd) {
		HELPER.unblock();
		Swal.fire({
			title: 'Syncron OAPI',
			text: 'Syncron OAPI untuk NPWPD ' + npwpd + '?',
			html: '<label for="datepicker_sinkron"><b>Periode Sinkron</b></label><input type="text" id="datepicker_sinkron" class="swal2-input" value="<?= date("Y-m-d") ?>">',
			didOpen: () => {
				// Initialize jQuery UI Datepicker
				$('#datepicker_sinkron').datepicker({
					format: "yyyy-mm-dd",
					showOn: 'focus',
					autoclose: true
				})
			},
			preConfirm: () => {
				return $('#datepicker_sinkron').val();
			},
			icon: 'info',
			showCancelButton: true,
			buttonsStyling: false,
			customClass: {
				confirmButton: 'btn btn-info',
				cancelButton: 'btn btn-secondary mx-2',
			},
			confirmButtonText: 'Proses Syncronisasi OAPI',
			cancelButtonText: 'Batal',
		}).then((result) => {
			if (result.isConfirmed) {
				if (is_proses_syncrone_done) {
					is_proses_syncrone_done = false;
					$.ajax({
						url: HELPER.api.singleOapiConsume,
						method: "post",
						data: {
							npwpd: npwpd,
							periode: result.value
						},
						dataType: "json",
						success: function(e) {
							if (e.success) {
								Swal.fire({
									title: 'Berhasil!',
									text: e.message,
									icon: 'success',
									confirmButtonText: 'Ok',
									customClass: {
										confirmButton: 'btn btn-success btn-lg'
									},
								});
							} else {
								Swal.fire({
									title: 'Warning!',
									text: e.message,
									icon: 'warning',
									confirmButtonText: 'Ok',
									customClass: {
										confirmButton: 'btn btn-warning btn-lg'
									},
								});
							}
							is_proses_syncrone_done = true;
						},
						complete: function(e) {
							is_proses_syncrone_done = true;
						}
					});
				} else {
					Swal.fire({
						title: 'Warning!',
						text: 'Tunggu proses sinkronisasi data selesai!, dan coba lagi!',
						icon: 'warning',
						confirmButtonText: 'Ok',
						customClass: {
							confirmButton: 'btn btn-warning btn-lg'
						},
					});
				}
			}
		});
	}
</script>