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
			subTable: BASE_URL + 'rekappajak/sub_table',
			read: BASE_URL + 'rekappajak/read',
			detail: BASE_URL + 'rekappajak/realisasi_detail',
			update: BASE_URL + 'rekappajak/update',
			destroy: BASE_URL + 'rekappajak/destroy',
			wp_header: BASE_URL + 'rekappajak/wp_header',
			readWp: BASE_URL + 'rekappajak/readWp',
			kecamatan: BASE_URL + 'rekappajak/get_kecamatan',
			loadDataPos: BASE_URL + 'rekappajak/loadDataPos',
			detailTransaksi: BASE_URL + 'rekappajak/detailTransaksi',
		}

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
			const modalEl = document.getElementById('modalPeriode');
			const modal = bootstrap.Modal.getInstance(modalEl);
			if (modal) modal.hide();
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
				[2, 'desc']
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
			url: HELPER.api.loadDataPos,
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
								<button type="button" class="btn btn-secondary btn-sm btn-elevate" style="margin-right:10px;" id="btn-detail" onclick="onDetailTransaksi('${full['penjualan_id']}', '${full['wajibpajak_id']}')">
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

	function getSpreadsheetRealisasi() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/realisasipajak/spreadsheet_realisasi',
			type: 'post',
			data: {
				filterBulan: $('#bulan').val()
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/monitor_realisasi/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}

	function getSpreadsheetSubRealisasi() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/realisasipajak/spreadsheet_subrealisasi',
			type: 'post',
			data: {
				realisasi_npwpd: $('#sub_wajibpajak_npwpd').val(),
				filterBulan: $('#sub_bulan').val(),
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/monitor_realisasi/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}

	function getSpreadsheetRinciRealisasi() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/realisasipajak/spreadsheet_rincirealisasi',
			type: 'post',
			data: {
				realisasi_id: $('#rinci_realisasi_id').val(),
				wp_npwpd: $('#rinci_wajibpajak_npwpd').val(),
				realisasi_tanggal: $('#rinci_realisasi_tanggal').val(),
			},
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/monitor_realisasi/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}

	function getPdfRealisasi() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'realisasipajak/pdf_realisasi',
			data: {
				filterBulan: $('#bulan').val()
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				let htmlobject = $('#pdf-laporan').html();
				$("#pdf-laporan object").remove();
				$("#pdf-laporan").append(htmlobject);
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'report_data_pdf'
				});
				HELPER.unblock();
			}
		})
	}

	function getPdfSubRealisasi() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'realisasipajak/pdf_subrealisasi',
			data: {
				realisasi_npwpd: $('#sub_wajibpajak_npwpd').val(),
				filterBulan: $('#sub_bulan').val(),
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				let htmlobject = $('#subpdf-laporan').html();
				$("#subpdf-laporan object").remove();
				$("#subpdf-laporan").append(htmlobject);
				$("#subpdf-laporan object").attr("data", res.record);
				HELPER.toggleForm({
					tohide: 'form_data',
					toshow: 'subreport_data_pdf'
				});
				HELPER.unblock();
			}
		})
	}

	function getPdfRinciRealisasi() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'realisasipajak/pdf_rincirealisasi',
			data: {
				realisasi_id: $('#rinci_realisasi_id').val(),
				wp_npwpd: $('#rinci_wajibpajak_npwpd').val(),
				realisasi_tanggal: $('#rinci_realisasi_tanggal').val(),
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				let htmlobject = $('#rincipdf-laporan').html();
				$("#rincipdf-laporan object").remove();
				$("#rincipdf-laporan").append(htmlobject);
				$("#rincipdf-laporan object").attr("data", res.record);
				HELPER.toggleForm({
					tohide: 'sub_rinci',
					toshow: 'rincireport_data_pdf'
				});
				HELPER.unblock();
			}
		})
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