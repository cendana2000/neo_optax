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
			getactive: BASE_URL + 'lastactivitywprealisasi/get/active',
			getinactive: BASE_URL + 'lastactivitywprealisasi/get/inactive',
			getoffline: BASE_URL + 'lastactivitywprealisasi/get/offline',
			getall: BASE_URL + 'lastactivitywprealisasi/get',
		}

		init_table('active');
		init_table('inactive');
		init_table('offline');
		init_table();
	});

	function init_table(val = 'all') {
		var url = HELPER.api.getall;
		if (val == 'active') url = HELPER.api.getactive;
		if (val == 'inactive') url = HELPER.api.getinactive;
		if (val == 'offline') url = HELPER.api.getoffline;

		HELPER.initTable({
			el: "table-lastactivitywprealisasi-" + val,
			url: url,
			data: {},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[3, 'desc']
			],
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
						if (full['realisasi_tanggal']) {
							return moment(full['realisasi_tanggal']).format('DD-MM-YYYY');
						} else {
							return '-';
						}
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						let mstatus = {
							'Active': '<span class="label label-inline label-success">Active</span>',
							'Inactive': '<span class="label label-inline label-warning">Inactive</span>',
							'Offline': '<span class="label label-inline label-danger">Offline</span>',
						}
						return mstatus[full['status_active']];
					},
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

	// function getSpreadsheetLastActivityWp() {
	// 	event.preventDefault();
	// 	HELPER.block();
	// 	$.ajax({
	// 		url: BASE_URL + '/lastactivitywp_realisasi/spreadsheet',
	// 		type: 'post',
	// 		data: {
	// 			log_penjualan_code_store: $('#select_toko').val(),
	// 			periode: $('#periode').val(),
	// 		},
	// 		dataType: 'JSON',
	// 		success: function(res) {
	// 			console.log(res);
	// 			if (res.success) {
	// 				let fileLocation = BASE_ASSETS + 'laporan/lastactivitywp_realisasi/' + res.file;
	// 				window.location.href = fileLocation;
	// 			}
	// 		},
	// 		complete: function(res) {
	// 			HELPER.unblock();
	// 		}
	// 	})
	// }

	// function getPdfLastActivityWp() {
	// 	HELPER.block();
	// 	$.ajax({
	// 		url: BASE_URL + 'lastactivitywp_realisasi/pdf',
	// 		data: {
	// 			log_penjualan_code_store: $('#select_toko').val(),
	// 			periode: $('#periode').val(),
	// 		},
	// 		type: 'post',
	// 		dataType: 'json',
	// 		success: function(res) {
	// 			$('#pdf-laporan-lastactivitywp_realisasi object').remove();
	// 			$('#pdf-laporan-lastactivitywp_realisasi').html('<object data="' + res.record + '" type="application/pdf" width="100%" height="500px"></object>');
	// 			HELPER.toggleForm({
	// 				tohide: 'table_data',
	// 				toshow: 'report_data_pdf'
	// 			});
	// 			HELPER.unblock();
	// 		}
	// 	})
	// }

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
				table: ['table-lastactivitywprealisasi-all', 'table-lastactivitywprealisasi-active', 'table-lastactivitywprealisasi-inactive', 'table-lastactivitywprealisasi-offline']
			});
		}

		$("#btnReset").trigger("click");
	}
</script>