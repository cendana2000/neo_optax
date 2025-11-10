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
				// log_penjualan_code_store: code_store,
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
</script>