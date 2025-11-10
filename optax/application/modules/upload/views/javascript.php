<script type="text/javascript">
	$(function() {
		//tambahan
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months",
			defaultDate: '-2m'
		});


		$(".monthpicker_custom").datepicker({
			format: "yyyy-mm-dd",
			startView: "months",
			minViewMode: "days",
			endDate: "-0d",
			beforeShowMonth: function(date) {
				var currentDate = new Date("<?= date("Y-m-d") ?>");
				var year = date.getFullYear();
				var month = date.getMonth() + 1; // Adding 1 to match the month format
				var date_ = date.getDate();
				return {
					endDate: '-' + date_ + 'd'
				};
			}
		})

		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

		HELPER.fields = [
			// 'realisasi_id',
			// 'realisasi_no',
			// 'realisasi_wajibpajak_id',
			// 'realisasi_wajibpajak_npwpd',
			// 'realisasi_tanggal',
			// 'realisasi_sub_total',
			// 'realisasi_jasa',
			// 'realisasi_pajak',
			// 'realisasi_total',
			// 'realisasi_created_at',
			// 'realisasi_created_by',
			// 'realisasi_updated_at',
			// 'realisasi_updated_by',
			'realisasi_wajibpajak_npwpd',
			'realisasi_masa_pajak',
			'realisasi_tanggal_upload_terakhir',
			'realisasi_total_sub_total',
			'realisasi_total_jasa',
			'realisasi_total_pajak',
			'realisasi_total_grand_total',
		];
		HELPER.setRequired([]);
		HELPER.api = {
			table: BASE_URL + 'upload/',
			read: BASE_URL + 'upload/read',
			store: BASE_URL + 'upload/store',
			update: BASE_URL + 'upload/store',
			destroy: BASE_URL + 'upload/destroy',
			detail: BASE_URL + 'upload/detail',
		}
		/*HELPER.initTable({
			el : 'table-upload',
			url: HELPER.api.table,
		})*/

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		loadTable();
	});

	function filterBulan() {
		let filterBulan = $('#bulan').val();
		loadTable(filterBulan);
	}

	function loadTable(filterBulan = null) {
		let data = {
			'filterBulan': null
		};

		if (filterBulan != null) {
			data.filterBulan = filterBulan
		}

		HELPER.initTable({
			el: "table-upload",
			url: HELPER.api.table,
			data: data,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[1, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return moment(full['realisasi_masa_pajak']).format('MMMM-YYYY');
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['realisasi_tanggal_upload_terakhir'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total_sub_total']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total_jasa']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total_pajak']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total_grand_total']);
					},
				},
				{
					targets: -1,
					orderable: false,
					visible: false,
					render: function(data, type, full, meta) {
						return `<button onclick="subRinci('${full['realisasi_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
					},
				},

			],
			fnDrawCallback: function(settings) {
				var {
					sumtotal: {
						last_total_jasa = 0,
						last_total_pajak = 0,
						last_total_subtotal = 0,
						last_total_grand_total = 0
					}
				} = settings.json;

				// <<<<<<< HEAD
				var total_pajak_custom = (parseInt(last_total_subtotal) + parseInt(last_total_jasa)) * 10 / 100;
				var total_pajak_custom = parseInt(last_total_pajak);
				var total_total_custom = parseInt(last_total_subtotal) + parseInt(last_total_jasa) + total_pajak_custom;
				var total_sum = parseInt(last_total_subtotal) + parseInt(last_total_jasa) + parseInt(total_pajak_custom);
				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(last_total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(last_total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(total_pajak_custom)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(total_total_custom)}`);
				$('#subrealisasi_total_sum').text(`Rp. ${$.number(total_sum)}`);
				// =======
				var total_pajak_custom = (parseInt(last_total_subtotal) + parseInt(last_total_jasa)) * 10 / 100;
				var total_total_custom = parseInt(last_total_subtotal) + parseInt(last_total_jasa) + total_pajak_custom;

				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(last_total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(last_total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(last_total_pajak)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(last_total_grand_total)}`);
				// >>>>>>> feat/prodsept15

				$("#sub_wajibpajak_npwpd").val(settings.json.npwpd);
			}
		});
	}

	function subRinci(realisasi_id) {
		$.post(HELPER.api.detail, {
			realisasi_detail_parent: realisasi_id
		}, function(res) {
			// $('#rinci_realisasi_id').val(realisasi_id);
			// $('#rinci_wajibpajak_npwpd').val(res.realisasi_wajibpajak_npwpd);
			// $('#rinci_wajibpajak_nama').val(res.wajibpajak_nama);
			// $('#rinci_wajibpajak_alamat').val(res.wajibpajak_alamat);
			// $('#rinci_wajibpajak_nama_penanggungjawab').val(res.wajibpajak_nama_penanggungjawab);
			// $('#realisasi_tanggal').text(res.realisasi_tanggal);
			// $('#rinci_realisasi_tanggal').val(res.realisasi_tanggal);

			realisasiDetail(realisasi_id);

			HELPER.toggleForm({
				tohide: 'form_data',
				toshow: 'sub_rinci'
			});
		});
	}

	function realisasiDetail(realisasi_id) {
		HELPER.initTable({
			el: "table-realisasi-detail",
			url: HELPER.api.detail,
			data: {
				realisasi_detail_parent: realisasi_id
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['realisasi_detail_time']
						// moment(full['realisasi_detail_time']).format('DD-MM-YYYY');
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['realisasi_detail_penjualan_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_detail_sub_total']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_detail_jasa']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_detail_pajak']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_detail_total']);
					},
				},

			],
			fnDrawCallback: function(settings) {
				var {
					sumtotal: {
						total_jasa = 0,
						total_pajak = 0,
						total_subtotal = 0,
						total_total = 0
					}
				} = settings.json;

				var total_sum = parseInt(total_subtotal) + parseInt(total_jasa) + parseInt(total_pajak);

				$('#subrealisasi_detail_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_detail_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_detail_total_pajak').text(`Rp. ${$.number(total_pajak)}`);
				$('#subrealisasi_detail_total_total').text(`Rp. ${$.number(total_total)}`);
				$('#subrealisasi_detail_total_sum').text(`Rp. ${$.number(total_sum)}`);
			}
		});
	}

	function onDelete(realisasi_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'upload/delete',
						data: {
							id: realisasi_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								onRefresh();
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

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				realisasi_id: id
			},
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-upload'
		});
		$('#periode_upload').val('');
		$('#laporan_realisasi').val('');
	}

	function save() {
		var form = $('#form-realisasi')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
		HELPER.save({
			form: 'form-realisasi',
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					if ($('#btn_save').val() == "1") {
						onPrint(id);
					} else {
						$('.body-form').removeClass('animated fadeInUp portlet-fullscreen');
						$('.body-form').addClass('animated fadeOutDown');
					}
					HELPER.loadPage($("#btn-Upload"))
					onRefresh();
					HELPER.back({});
				} else {
					HELPER.showMessage({
						success: false,
						title: "Gagal",
						message: "Form isian periode dan lampiran file tidak boleh kosong!"
					})
				}
			}
		})

	}

	function getPdfSubRealisasi() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'realisasipajak/pdf_subrealisasi_merge',
			data: {
				realisasi_npwpd: $('#sub_wajibpajak_npwpd').val(),
				filterBulan: $('#bulan').val(),
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

	function getSpreadsheetSubRealisasi() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/realisasipajak/spreadsheet_subrealisasi',
			type: 'post',
			data: {
				realisasi_npwpd: $('#sub_wajibpajak_npwpd').val(),
				filterBulan: $('#bulan').val(),
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
</script>