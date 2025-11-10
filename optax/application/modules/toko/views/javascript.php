<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'toko_id',
			'toko_wajibpajak_id',
			'toko_nama',
			'wajibpajak_nama',
			'wajibpajak_npwpd',
			'wajibpajak_sektor_nama',
			'wajibpajak_alamat',
			'wajibpajak_penanggungjawab_nama',
			'wajibpajak_telp',
			'wajibpajak_email',
		];
		HELPER.api = {
			table: BASE_URL + 'toko/',
			getpermohonan: BASE_URL + 'toko/getpermohonan',
			read: BASE_URL + 'toko/read',
			openToko: BASE_URL + 'toko/openToko',
			closeToko: BASE_URL + 'toko/closeToko',
			// store: BASE_URL + 'permohonantoko/store',
			// update: BASE_URL + 'permohonantoko/update',
			// destroy: BASE_URL + 'permohonantoko/destroy',
		}

		$(".select2").select2({
			placeholder: "Pilih Status",
			allowClear: true
		});

		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('wajibpajak-Update') || HELPER.get_role_access('wajibpajak-Delete'));
		mstatus = {
			0: '<span class="label label-inline">Deleted</span>',
			1: '<span class="label label-inline ">Permohonan</span>',
			2: '<span class="label label-inline label-success">Disetujui</span>',
			3: '<span class="label label-inline label-danger">Ditolak</span>',
			4: '<span class="label label-inline label-success">Disetujui</span>',
		}
		HELPER.initTable({
			el: "table-toko",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[3, 'desc']
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
						return full['toko_kode'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['toko_registered_at'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return mstatus[full['toko_status']];
					},
				},
				// {
				// 	targets: 6,
				// 	render: function(data, type, full, meta) {
				// 		let mstatus_activity = {
				// 			'Active': '<span class="label label-inline label-success">Active</span>',
				// 			'Inactive': '<span class="label label-inline label-warning">Inactive</span>',
				// 			'Offline': '<span class="label label-inline label-danger">Offline</span>',
				// 			'Close': '<span class="label label-inline label-dark">Close</span>',
				// 		}
				// 		return mstatus_activity[full['status_active']];
				// 	},
				// },
				{
					targets: -1,
					// width: '50px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// console.log(data)
						// if (HELPER.get_role_access('wajibpajak-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary" onclick="onDetail('` + full['toko_id'] + `')">
											Detail
										</a>`;
						// }
						// if (HELPER.get_role_access('wajibpajak-Delete')) {
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['wajibpajak_id'] + `')"">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-trash"></i>
						// 					</span>
						// 				</a>`;
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

	function onDetail(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				toko_id: id
			},
			callback: function(res) {
				// $('.card-footer').hide();
				HELPER.fields.map(item => {
					$('#' + item).val(res[item]).trigger('change');
				});
				$('#btnSave').remove();
				$('#logo_toko').attr('src', `${BASE_URL_NO_INDEX + res.toko_logo}`);

				var {
					status_active = 'Offline'
				} = res;


				if (status_active != 'Close') {
					$('#close-toko-btn').attr('onclick', `closeToko('${id}')`);
					$('#close-toko-btn').html(`<i class="flaticon2-cancel"></i> Close Toko`);
					$('#close-toko-btn').addClass('btn-danger');
					$('#close-toko-btn').removeClass('btn-success');
				} else {
					$('#close-toko-btn').attr('onclick', `openToko('${id}')`);
					$('#close-toko-btn').html(`<i class="flaticon2-check-mark"></i> Open Toko`);
					$('#close-toko-btn').addClass('btn-success');
					$('#close-toko-btn').removeClass('btn-danger');
				}

				loadPosUser(res.toko_kode);
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				})
			}
		})
	}

	function loadPosUser(id) {
		HELPER.initTable({
			el: "table-user",
			url: BASE_URL + 'toko/pos_user',
			data: {
				toko_kode: id
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['user_nama'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['user_telepon'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['user_email'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						status = full['user_status'];
						if (status == 1) {
							return '<span class="badge badge-success">Active</span>'
						}
						return '<span class="badge badge-danger">Not Active</span>'
					},
				}
			],
		});
	}

	/* function saveStatus() {
		Swal.fire({
          title: "Information",
          text: "Are you sure you want to save this data?",
          icon: "info",
          confirmButtonText: '<i class="fa fa-check"></i> Yes',
          confirmButtonClass: "btn btn-focus btn-success m-btn m-btn--pill m-btn--air",
          reverseButtons: true,
          showCancelButton: true,
          cancelButtonText: '<i class="fa fa-times"></i> No',
          cancelButtonClass: "btn btn-focus btn-danger m-btn m-btn--pill m-btn--air",
        }).then(function (result) {
          if (result.value) {
            $.ajax({
				url 	: BASE_URL+'permohonan/save_status',
				data 	: {
					wajibpajak_id : $('wajibpajak_id').val(),
					wajibpajak_status : $('wajibpajak_status').val(),
				},
				type 	: 'post',
				success : (res)=>{
					if(res)
				}
			})
          } else {
            onBack()
          }
        });		
	} */
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
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-toko'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-toko',
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

	function imgError(image) {
		image.onerror = "";
		image.src = `${BASE_URL_NO_INDEX}assets/media/noimage.png`;
	}

	function closeToko(id) {
		HELPER.confirm({
			title: 'Pemberitahuan',
			message: 'Apakah anda yakin akan menutup toko?',
			callback: function(res) {
				if (res == true) {
					HELPER.loadData({
						url: HELPER.api.closeToko,
						server: true,
						data: {
							toko_id: id
						},
						callback: function(res) {
							HELPER.showMessage({
								success: true,
								title: 'Success',
								message: 'Berhasil menutup toko.'
							})

							$('#close-toko-btn').attr('onclick', `openToko('${id}')`);
							$('#close-toko-btn').html(`<i class="flaticon2-check-mark"></i> Open Toko`);
							$('#close-toko-btn').addClass('btn-success');
							$('#close-toko-btn').removeClass('btn-danger');
						}
					});
				}
			}
		});
	}

	function openToko(id) {
		HELPER.confirm({
			title: 'Pemberitahuan',
			message: 'Apakah anda yakin akan membuka toko?',
			callback: function(res) {
				if (res == true) {
					HELPER.loadData({
						url: HELPER.api.openToko,
						server: true,
						data: {
							toko_id: id
						},
						callback: function(res) {
							HELPER.showMessage({
								success: true,
								title: 'Success',
								message: 'Berhasil membuka toko.'
							})

							$('#close-toko-btn').attr('onclick', `closeToko('${id}')`);
							$('#close-toko-btn').html(`<i class="flaticon2-cancel"></i> Close Toko`);
							$('#close-toko-btn').addClass('btn-danger');
							$('#close-toko-btn').removeClass('btn-success');
						}
					});
				}
			}
		});
	}
</script>