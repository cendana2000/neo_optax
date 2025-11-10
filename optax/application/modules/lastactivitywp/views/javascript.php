<style type="text/css">
	.noBorders {
		width: 100%;

	}

	.noBorders td {
		padding: 30px;
	}
</style>
<script type="text/javascript">
	$(function() {
		HELPER.api = {
			getactive: BASE_URL + 'lastactivitywp/get/active',
			getinactive: BASE_URL + 'lastactivitywp/get/inactive',
			getoffline: BASE_URL + 'lastactivitywp/get/offline',
			getclose: BASE_URL + 'lastactivitywp/get/close',
			getall: BASE_URL + 'lastactivitywp/get',
			openToko: BASE_URL + 'lastactivitywp/openToko',
			closeToko: BASE_URL + 'lastactivitywp/closeToko',
		}

		init_table('active');
		init_table('inactive');
		init_table('offline');
		init_table('close');
		init_table();
	});

	function init_table(val = 'all') {
		var url = HELPER.api.getall;
		if (val == 'active') url = HELPER.api.getactive;
		if (val == 'inactive') url = HELPER.api.getinactive;
		if (val == 'offline') url = HELPER.api.getoffline;
		if (val == 'close') url = HELPER.api.getclose;

		HELPER.initTable({
			el: "table-lastactivitywp-" + val,
			url: url,
			data: {},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[4, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['toko_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['toko_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['toko_wajibpajak_npwpd'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						if (full['tanggal_last_transaksi']) {
							return moment(full['tanggal_last_transaksi']).format('DD-MM-YYYY');
						} else {
							return '-';
						}
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						let mstatus = {
							'Active': '<span class="label label-inline label-success">Active</span>',
							'Inactive': '<span class="label label-inline label-warning">Inactive</span>',
							'Offline': '<span class="label label-inline label-danger">Offline</span>',
							'Close': '<span class="label label-inline label-dark">Close</span>',
						}
						return mstatus[full['status_active']];
					},
				},
				{
					targets: -1,
					render: function(data, type, full, meta) {
						if (full['status_active'] != 'Close') {
							return `<button type="button" class="btn btn-light-danger btn-sm" onclick="closeToko('${full['toko_id']}')"><i class="flaticon2-cancel icon-sm"></i> Close</button>`
						} else {
							return `<button type="button" class="btn btn-light-success btn-sm" onclick="openToko('${full['toko_id']}')"><i class="flaticon2-check-mark icon-sm"></i> Open</button>`
						}
					}
				}
			],
			fnDrawCallback: function(settings) {
				var {
					iTotalRecords = 0
				} = settings.json;
				$(`.${val}-counter`).text(iTotalRecords);
			}
		});
	}

	function getSpreadsheetLastActivityWp() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/lastactivitywp/spreadsheet',
			type: 'post',
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/lastactivitywp/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}

	function getPdfLastActivityWp() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'lastactivitywp/pdf',
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('#pdf-laporan-lastactivitywp object').remove();
				$('#pdf-laporan-lastactivitywp').html('<object data="' + res.record + '" type="application/pdf" width="100%" height="500px"></object>');
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'report_data_pdf'
				});
				HELPER.unblock();
			}
		})
	}

	function onBackCard(val = 0) {
		switch (val) {
			case 1:
				HELPER.toggleForm({
					tohide: 'report_data_pdf',
					toshow: 'table_data'
				});
				break;

			default:
				onBack()
				break;
		}
	}

	function onRefresh(state = 1) {
		if (state == 1) {
			HELPER.refresh({
				table: ['table-lastactivitywp-all', 'table-lastactivitywp-active', 'table-lastactivitywp-inactive', 'table-lastactivitywp-offline', 'table-lastactivitywp-close']
			});
		}

		$("#btnReset").trigger("click");
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

							onRefresh();
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

							onRefresh();
						}
					});
				}
			}
		});
	}
</script>