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
			index: BASE_URL + 'transaksiwp',
			// rincian: BASE_URL + 'transaksiwp/rincian_transaksi',
			// readWp: BASE_URL + 'transaksiwp/readWp',
			get_parent: BASE_URL + 'transaksiwp/go_tree',
			ajax_toko: BASE_URL + 'transaksiwp/select_wp',
			deleteTransaksi: BASE_URL + 'transaksiwp/deleteTransaksi',
			detailTransaksi: BASE_URL + 'transaksiwp/detailTransaksi',
		}
		// $('.select2').select2();
		$(".daterange").daterangepicker({
			startDate: moment().startOf('month'),
			endDate: moment().endOf('month'),
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		});

		HELPER.ajaxCombo({
			el: '#select_toko',
			url: HELPER.api.ajax_toko
		});

		// init_table();
		// loadTable();
		$(".barang").hide();
		$(".kategori").hide();
		$("#cetak").hide();
	});

	function init_table(code_store) {
		HELPER.initTable({
			el: "table-transaksiwp",
			url: HELPER.api.index,
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[2, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						var {
							wajibpajak
						} = meta.settings.json;
						return wajibpajak.toko_nama;
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return moment(full['penjualan_tanggal']).format('DD-MM-YYYY');
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						var timestamp = full['penjualan_created'].substring(20, 11);
						return timestamp;
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp. ' + $.number(full['penjualan_total_grand']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['penjualan_kode'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						let html = '';
						let mstatus = {
							aktif: '<span class="label label-inline label-success mr-2">Aktif</span>',
							batal: '<span class="label label-inline label-warning mr-2">Batal</span>',
							posting: '<span class="label label-inline label-info mr-2">Sudah Lapor Pajak</span>',
							retur: '<span class="label label-inline label-danger mr-2">Retur</span>',
						}
						if (full['penjualan_status_aktif']) {
							html += mstatus.batal
						} else {
							html += mstatus.aktif
						}
						if (full['penjualan_total_retur'] != null) {
							html += mstatus.retur
						}
						if (full['penjualan_lock'] == '1') {
							html += mstatus.posting
						}
						return html;
					},
				},
				{
					targets: -1,
					render: function(data, type, full, meta) {
						var {
							wajibpajak: {
								toko_kode
							}
						} = meta.settings.json;
						return `
								<button type="button" class="btn btn-secondary btn-sm btn-elevate" style="margin-right:10px;" id="btn-detail" onclick="onDetailTransaksi('${full['penjualan_id']}', '${toko_kode}')">
									<span>
										<i class="fas fa-file-invoice"></i>										
									</span>
								</button>

								<!-- <button type="button" class="btn btn-danger btn-sm btn-elevate" id="btn-prosess" onclick="onDeleteTransaksi('${full['penjualan_id']}', '${toko_kode}')">
									<span>
										<i class="fas fa-trash"></i>										
									</span>
								</button> -->
						`;
					},
				},
			],
			fnDrawCallback: function(settings) {
				var {
					sumtotal: {
						total_nominal_penjualan = 0,
					}
				} = settings.json;

				$('#transaksiwp_total_nominal_penjualan').text(`Rp. ${$.number(total_nominal_penjualan)}`);
			}
		});
	}

	function onChangeToko(el) {
		var val = $(el).val();

		if (val) {
			HELPER.create_combo_akun({
				el: 'barang_kategori_barang',
				valueField: 'id',
				displayField: 'text',
				parentField: 'parent',
				childField: 'child',
				url: HELPER.api.get_parent + '/' + val,
				withNull: false,
				nesting: true,
				chosen: false,
			});

			HELPER.ajaxCombo({
				el: '#barang_id',
				url: BASE_URL + 'laporanpricelist/select_ajax' + '/' + val,
			});

			$('#pricelist-form').attr('action', `javascript:init_table('${val}')`);
			$('#btn-prosess').attr('onclick', `init_table('${val}')`);
			$('#kt_print').attr('onclick', `print_table('${val}')`);

			$('#next-action').show();
			$('#button-tool').show();
		} else {
			$('#next-action').hide();
			$('#button-tool').hide();
		}
	}

	onDeleteTransaksi = (penjualan_id, code_store) => {
		HELPER.confirm({
			title: 'Konfirmasi',
			message: 'Apakah anda yakin ingin menghapus transaksi ini?',
			callback: (confirm) => {
				HELPER.block();
				if (confirm) {
					HELPER.ajax({
						url: HELPER.api.deleteTransaksi,
						datatype: 'json',
						type: 'POST',
						data: {
							penjualan_id: penjualan_id,
							code_store: code_store
						},
						complete: (res) => {
							if (res.success == true) {
								HELPER.showMessage({
									title: 'Berhasil',
									message: res.message,
									success: true
								});
								HELPER.backMenu();
							} else {
								HELPER.showMessage({
									title: 'Gagal',
									message: res.message,
									success: false
								});
							}
							HELPER.unblock();
						}
					});
				}
			}
		});
	}

	function getSpreadsheetTransaksiWp() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/transaksiwp/spreadsheet',
			type: 'post',
			data: {
				log_penjualan_code_store: $('#select_toko').val(),
				periode: $('#periode').val(),
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/wajibpajak/' + res.file;
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
			url: BASE_URL + 'transaksiwp/pdf',
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

	function onRefresh(state = 1) {
		if (state == 1) {
			init_table();
		}

		$("#btnReset").trigger("click");
	}

	//tambahan detail transaksi
	function onDetailTransaksi(penjualan_id, code_store) {
		console.log(HELPER.api.detailTransaksi);
		HELPER.ajax({
			url: HELPER.api.detailTransaksi,
			datatype: 'json',
			type: 'POST',
			data: {
				penjualan_id: penjualan_id,
				code_store: code_store
			},
			success: function(response) {
				// Isi elemen-elemen dengan data dari response				
				$('#pengaturan_title').html(response.data_wp[0].wajibpajak_nama);
				$('#alamat_wp').html(response.data_wp[0].wajibpajak_alamat);
				$('#kode_penjualan').html(response.data[0].penjualan_kode);
				$('#tanggal').html(response.data[0].penjualan_tanggal);
				$('#waktu').html(response.data[0].penjualan_created.substring(20, 11));
				$('#sub_total').number(response.data[0].penjualan_total_harga);
				$('#pajak').number(response.data[0].penjualan_total_harga / 10);
				$('#grand_total').number(response.data[0].penjualan_total_grand);
				// $('#status').html(response.data[0].penjualan_status_aktif);
				// ... lanjutkan untuk elemen-elemen lainnya
			}
		});
		$('#modal-detail-transaksi').modal('show');
	}
</script>