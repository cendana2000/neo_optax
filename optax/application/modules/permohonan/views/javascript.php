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
			table: BASE_URL + 'permohonan/',
			read: BASE_URL + 'permohonan/read',
			store: BASE_URL + 'permohonan/store',
			update: BASE_URL + 'permohonan/update',
			destroy: BASE_URL + 'permohonan/destroy',
		}
		$('#wajibpajak_status').select2({
			placeholder: "Pilih",
			allowClear: true
		});
		/*HELPER.initTable({
			el : 'table-wajibpajak',
			url: HELPER.api.table,
		})*/
		loadTable();
	});

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('wajibpajak-Update') || HELPER.get_role_access('wajibpajak-Delete'));
		mstatus = {
			0: '<span class="label label-inline">Deleted</span>',
			1: '<span class="label label-inline ">Permohonan</span>',
			2: '<span class="label label-inline label-success">Disetujui</span>',
			3: '<span class="label label-inline label-danger">Ditolak</span>',
		}
		HELPER.initTable({
			el: "table-wajibpajak",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['wajibpajak_npwpd'];
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
						return mstatus[full['wajibpajak_status']];
					},
				},
				{
					targets: 6,
					// width: '50px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// if (HELPER.get_role_access('wajibpajak-Update')) {
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary" onclick="onDetail('` + full['wajibpajak_id'] + `')">
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
				wajibpajak_id: id
			},
			callback: function(res) {
				$('#wajibpajak_usaha_nama').val(res.jenis_nama);
				$('input[name=wajibpajak_nama]').val(res.wajibpajak_nama);
				$('#wajibpajak_berkas_npwp').attr('style', 'height:200px;background-image: url(' + BASE_URL_NO_INDEX + res.wajibpajak_berkas + ')');
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data'
				})
			}
		})
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
</script>