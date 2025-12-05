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
			index: BASE_URL + 'logoapi/',
			get_parent: BASE_URL + 'logoapi/go_tree',
			ajax_toko: BASE_URL + 'logoapi/select_wp',
			deleteTransaksi: BASE_URL + 'logoapi/deleteTransaksi',
			detailTransaksi: BASE_URL + 'logoapi/detailTransaksi',
		}
		$('.select2').select2();
		$(".daterange").daterangepicker({
			startDate: moment().startOf('month'),
			endDate: moment().endOf('month'),
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Last Year': [moment().startOf('year').subtract(1, 'years'),
					moment().endOf('year').subtract(1, 'years')
				]
			}
		});

		HELPER.ajaxCombo({
			el: '#select_toko',
			url: HELPER.api.ajax_toko
		});

		$(".barang").hide();
		$(".kategori").hide();
		$("#cetak").hide();
	});

	function init_table(code_store) {
		HELPER.initTable({
			el: "table-logoapi",
			url: HELPER.api.index,
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
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
						return full['realisasi_wajibpajak_npwpd'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return moment(full['realisasi_tanggal']).format('DD-MM-YYYY');
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp. ' + $.number(full['realisasi_total']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['realisasi_no'];
					},
				},
				{
					targets: -1,
					render: function(data, type, full, meta) {
						var toko_kode = full['toko_kode'];
						return `
								<button type="button" class="btn btn-secondary btn-sm btn-elevate" style="margin-right:10px;" id="btn-detail" onclick="onDetailTransaksi('${full['realisasi_id']}', '${toko_kode}')">
									<span>
										<i class="fas fa-file-invoice"></i>										
									</span>
								</button>
						`;
					},
				},
			],
			fnDrawCallback: function(settings) {
				var {
					sumtotal: {
						total_penjualan = 0,
					}
				} = settings.json;

				$('#logoapi_total_nominal_penjualan').text(`Rp. ${$.number(total_penjualan)}`);
			}
		});
	}

	function onChangeToko(el) {
		var val = $(el).val();

		if (val) {

			$('#pricelist-form').attr('action', `javascript:init_table('${val}')`);
			$('#btn-prosess').attr('onclick', `init_table('${val}')`);

			$('#next-action').show();
			$('#button-tool').show();
		} else {
			$('#next-action').hide();
			$('#button-tool').hide();
		}
	}

	function onRefresh(state = 1) {
		if (state == 1) {
			init_table();
		}

		$("#btnReset").trigger("click");
	}

	function onDetailTransaksi(realisasi_id, code_store) {
		console.log(HELPER.api.detailTransaksi);
		HELPER.ajax({
			url: HELPER.api.detailTransaksi,
			datatype: 'json',
			type: 'POST',
			data: {
				realisasi_id: realisasi_id,
				code_store: code_store
			},
			success: function(response) {
				$('#pengaturan_title').html(response.toko_nama);
				$('#alamat_wp').html(response.wajibpajak_alamat);
				$('#kode_penjualan').html(response.realisasi_no);
				$('#tanggal').html(response.realisasi_tanggal);
				$('#waktu').html(response.realisasi_tanggal.substring(20, 11));
				$('#sub_total').number(response.realisasi_sub_total);
				$('#pajak').number(response.realisasi_pajak);
				$('#grand_total').number(response.realisasi_total);
			}
		});
		$('#modal-detail-transaksi').modal('show');
	}

	function getSpreadsheetTransaksiWp() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/logoapi/spreadsheet',
			type: 'post',
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/logoapi/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}

	function getPdfTransaksiWp() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'logoapi/pdf',
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('#pdf-laporan-transaksiwp object').remove();
				$('#pdf-laporan-transaksiwp').html('<object data="' + res.record + '" type="application/pdf" width="100%" height="500px"></object>');
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
</script>