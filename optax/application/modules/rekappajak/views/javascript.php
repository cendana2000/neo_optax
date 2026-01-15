<script type="text/javascript">
	var gNPWPD = '';
	var selectedStart = null;
	var selectedEnd = null;

	$(function() {
		HELPER.fields = [
			'satuan_id',
			'satuan_kode',
			'satuan_nama',
		];
		HELPER.setRequired([
			'satuan_kode',
			'satuan_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'rekappajak/',
			wp_header: BASE_URL + 'rekappajak/wp_header',
			readWp: BASE_URL + 'rekappajak/readWp',
			kecamatan: BASE_URL + 'rekappajak/get_kecamatan',
			loadData: BASE_URL + 'rekappajak/loadData',
			detailTransaksi: BASE_URL + 'rekappajak/detailTransaksi',
		}

		$('#btnPeriode').on('click', function() {
			$('#modalPeriode').modal('show');
		});

		$(document).on('click', '.list-range', function() {
			var type = $(this).data('range');
			var start, end;

			if (type === 'today') {
				start = moment();
				end = moment();
			} else if (type === 'yesterday') {
				start = moment().subtract(1, 'days');
				end = moment().subtract(1, 'days');
			} else {
				start = moment().subtract(type - 1, 'days');
				end = moment();
			}
			setPeriode(start, end);
		});

		$('#customRange').daterangepicker({
			autoUpdateInput: false,
			opens: 'right',
			showDropdowns: true,
			linkedCalendars: false,
			locale: {
				format: 'DD/MM/YYYY',
				cancelLabel: 'Batal',
				applyLabel: 'Pilih'
			}
		});

		$('#customRange').on('apply.daterangepicker', function(ev, picker) {
			setPeriode(picker.startDate, picker.endDate);
		});

		$('#btnApplyPeriode').on('click', function() {
			if (!selectedStart || !selectedEnd) {
				alert('Silakan pilih periode terlebih dahulu');
				return;
			}

			const $btn = $(this);
			$btn.prop('disabled', true);
			$btn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Memproses...');

			$('#modalPeriode').modal('hide');

			loadRinciRekap(
				window.current_wp_id,
				window.current_sumber_data,
				$('#periode').val()
			);

			setTimeout(() => {
				$btn.prop('disabled', false);
				$btn.html('<i class="la la-check mr-1"></i> Apply');
			}, 600);
		});



		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

		$('#btnFilterRekap').on('click', function() {
			filterRekap();
		});

		loadTable();
		loadKecamatan();
	});

	function setPeriode(start, end) {
		selectedStart = start.clone();
		selectedEnd = end.clone();

		var label = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
		var value = start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD');

		$('#periode').val(value);
		$('#label-periode').text(label);
		$('#customRange').val(label);
	}

	function filterRekap() {
		let kecamatan = $('#select_kecamatan').val();
		let jenisPajak = $('#filter_jenis_pajak').val();
		let jenisDevice = $('#filter_jenis_device').val();

		loadTable(kecamatan, jenisPajak, jenisDevice);
	}

	function loadTable(kecamatan = null, jenisPajak = null, jenisDevice = null) {
		let data = {};

		if (jenisDevice) {
			data.jenis_device = jenisDevice;
		}

		if (kecamatan) {
			data.kecamatan = kecamatan;
		}

		if (jenisPajak) {
			data.jenis_pajak = jenisPajak;
		}

		HELPER.initTable({
			el: "table-rekappajak",
			url: HELPER.api.table,
			data: data,
			searchAble: false,
			destroyAble: true,
			responsive: false,
			order: [
				[2, 'asc']
			],
			search: {
				return: true
			},
			columnDefs: [{
					defaultContent: "-",
					targets: "_all"
				}, {
					targets: 1,
					render: function(data, type, full, meta) {
						return full['npwpd'];
					},
					name: "npwpd",
					searchable: true,
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['nama_wp'];
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
						return full['kecamatan_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['tanggal_last_transaksi'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['jenis_device'];
					},
				},
				{
					targets: -1,
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						return `<button onclick="onDetail('${full['id_wp']}', '${full['sumber_data']}')" class="btn btn-sm btn-success btn-icon"><i class="fa fa-info-circle"></i></button>`;
					},
				},
			],
		});
	}

	function onDetail(id_wp, sumber_data) {
		HELPER.block();
		var filterBulan = $('#periode').val();
		if (!filterBulan) {
			const today = moment();
			setPeriode(today, today);
			filterBulan = $('#periode').val();
		}
		$.post(HELPER.api.readWp, {
			wajibpajak_id: id_wp
		}, function(res) {
			$('#sub_wajibpajak_npwpd').text(res.wajibpajak_npwpd);
			$('#sub_wajibpajak_nama').text(res.wajibpajak_nama);
			$('#sub_wajibpajak_alamat').text(res.wajibpajak_alamat);
			$('#sub_wajibpajak_nama_penanggungjawab').text(res.wajibpajak_nama_penanggungjawab);

			loadRinciRekap(id_wp, sumber_data, filterBulan);
			onAdd();
		});
	}

	function loadRinciRekap(id_wp, sumber_data, filterBulan) {
		HELPER.unblock();

		window.current_wp_id = id_wp;
		window.current_sumber_data = sumber_data;
		let data = {
			'wajibpajak_id': id_wp,
			'sumber_data': sumber_data,
			'periode': filterBulan
		};
		HELPER.initTable({
			el: "table-rincirekappajak",
			url: HELPER.api.loadData,
			data: data,
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
						return moment(full['trx_tgl']).format('DD-MM-YYYY');
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						var timestamp = full['trx_time'].substring(20, 11);
						return timestamp;
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp. ' + $.number(full['trx_total']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['trx_kode'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full) {
						const map = {
							aktif: '<span class="label label-inline label-success mr-2">Aktif</span>',
							batal: '<span class="label label-inline label-warning mr-2">Batal</span>',
							posting: '<span class="label label-inline label-info mr-2">Sudah Lapor Pajak</span>',
							retur: '<span class="label label-inline label-danger mr-2">Retur</span>'
						};

						let html = '';
						(full.trx_status || []).forEach(s => {
							html += map[s] || '';
						});

						return html;
					}
				},
				{
					targets: -1,
					render: function(data, type, full, meta) {
						return `
								<button type="button" class="btn btn-secondary btn-sm btn-elevate" style="margin-right:10px;" id="btn-detail" onclick="onDetailTransaksi('${full['trx_id']}', '${full['wajibpajak_id']}')">
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
						total_nominal_penjualan = 0,
					}
				} = settings.json;

				$('#transaksiwp_total_nominal_penjualan').text(`Rp. ${$.number(total_nominal_penjualan)}`);
			}
		});
	}

	$('.dataTables_filter input').on('keydown', function(e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			dataTable.search($(this).val()).draw();
		}
	});

	function onDetailTransaksi(trx_id, wajibpajak_id) {
		HELPER.ajax({
			url: HELPER.api.detailTransaksi,
			type: 'POST',
			data: {
				penjualan_id: trx_id,
				wajibpajak_id: wajibpajak_id,
				sumber_data: window.current_sumber_data
			},
			success: function(response) {
				const wp = response.data.wp;
				const trx = response.data.trx;

				$('#pengaturan_title').html(wp.wajibpajak_nama);
				$('#alamat_wp').html(wp.wajibpajak_alamat);

				$('#kode_penjualan').html(trx.trx_kode);
				$('#tanggal').html(moment(trx.trx_tgl).format('DD-MM-YYYY'));
				$('#waktu').html(moment(trx.trx_time).format('HH:mm'));

				$('#sub_total').number(trx.trx_subtotal);
				let jasa = parseFloat(trx.trx_jasa) || 0;
				let diskon = parseFloat(trx.trx_diskon) || 0;
				$('#service').number(jasa);
				$('#diskon').number(diskon);
				$('#pajak').number(trx.trx_subtotal / 10);
				$('#grand_total').number(trx.trx_total);

				$('#modal-detail-transaksi').modal('show');
			}
		});
	}


	function onAdd() {
		HELPER.toggleForm({});
	}

	function onBack() {
		onRefresh();
		HELPER.backMenu();
	}

	function onBackCard(val = 0) {
		switch (val) {
			case 1:
				HELPER.toggleForm({
					tohide: 'report_data_pdf',
					toshow: 'table_data'
				});
				break;
			case 2:
				HELPER.toggleForm({
					tohide: 'form_data',
					toshow: 'table_data'
				});
				break;
			case 3:
				HELPER.toggleForm({
					tohide: 'sub_rinci',
					toshow: 'form_data'
				});
				break;
			case 4:
				HELPER.toggleForm({
					tohide: 'subreport_data_pdf',
					toshow: 'form_data'
				});
				break;
			case 5:
				HELPER.toggleForm({
					tohide: 'rincireport_data_pdf',
					toshow: 'sub_rinci'
				});
				break;
			case 6:
				HELPER.toggleForm({
					tohide: 'form_data_edit',
					toshow: 'form_data'
				});
				break;

			default:
				onBack()
				break;
		}
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-rekappajak'
		})
	}

	function getExcelRekap() {
		HELPER.block();

		let form = $('<form>', {
			action: BASE_URL + '/rekappajak/spreadsheet_rekap',
			method: 'POST',
			target: '_blank'
		});

		form.append($('<input>', {
			type: 'hidden',
			name: 'kecamatan',
			value: $('#select_kecamatan').val()
		}));

		form.append($('<input>', {
			type: 'hidden',
			name: 'jenis_pajak',
			value: $('#filter_jenis_pajak').val()
		}));

		form.append($('<input>', {
			type: 'hidden',
			name: 'jenis_device',
			value: $('#filter_jenis_device').val()
		}));

		$('body').append(form);
		form.submit();
		form.remove();

		setTimeout(() => {
			HELPER.unblock();
		}, 1000);
	}

	function getExcelRinciRekap() {
		HELPER.block();

		let form = $('<form>', {
			action: BASE_URL + '/rekappajak/spreadsheet_rincirekap',
			method: 'POST',
			target: '_blank'
		});

		form.append($('<input>', {
			type: 'hidden',
			name: 'periode',
			value: $('#periode').val()
		}));

		form.append($('<input>', {
			type: 'hidden',
			name: 'wajibpajak_id',
			value: window.current_wp_id
		}));

		form.append($('<input>', {
			type: 'hidden',
			name: 'sumber_data',
			value: window.current_sumber_data
		}));

		$('body').append(form);
		form.submit();
		form.remove();

		setTimeout(() => {
			HELPER.unblock();
		}, 1000);
	}

	function getPdfRekap() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'rekappajak/pdf_rekap',
			type: 'post',
			dataType: 'json',
			data: {
				kecamatan: $('#select_kecamatan').val(),
				jenis_pajak: $('#filter_jenis_pajak').val(),
				jenis_device: $('#filter_jenis_device').val(),
				mode: 'preview'
			},
			success: function(res) {
				$("#pdf-laporan object").remove();
				$("#pdf-laporan").append(
					'<object type="application/pdf" width="100%" height="600"></object>'
				);
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'report_data_pdf'
				});
				HELPER.unblock();
			}
		});
	}


	function getPdfRinciRekap() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'rekappajak/pdf_rincirekap',
			type: 'post',
			dataType: 'json',
			data: {
				wajibpajak_id: window.current_wp_id,
				sumber_data: window.current_sumber_data,
				periode: $('#periode').val(),
				mode: 'preview'
			},
			success: function(res) {
				$("#subpdf-laporan object").remove();
				$("#subpdf-laporan").append(
					'<object type="application/pdf" width="100%" height="600"></object>'
				);
				$("#subpdf-laporan object").attr("data", res.record);
				HELPER.toggleForm({
					tohide: 'form_data',
					toshow: 'subreport_data_pdf'
				});
				HELPER.unblock();
			}
		});
	}

	function loadKecamatan() {
		$.ajax({
			url: HELPER.api.kecamatan,
			type: 'POST',
			dataType: 'json',
			success: function(res) {
				var $select = $('#select_kecamatan');

				$select.empty();
				$select.append('<option value="">-- Semua --</option>');

				if (res.length > 0) {
					$.each(res, function(i, item) {
						$select.append(
							'<option value="' + item.kecamatan_id + '">' +
							item.kecamatan_nama +
							'</option>'
						);
					});
				}
			},
			error: function() {
				alert('Gagal mengambil data kecamatan');
			}
		});
	}
</script>