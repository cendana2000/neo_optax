<script type="text/javascript">
	$(function() {
		//tambahan
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

		HELPER.fields = [
			'realisasi_id',
			'realisasi_no',
			'realisasi_wajibpajak_id',
			'realisasi_wajibpajak_npwpd',
			'realisasi_tanggal',
			'realisasi_sub_total',
			'realisasi_jasa',
			'realisasi_pajak',
			'realisasi_total',
			'realisasi_created_at',
			'realisasi_created_by',
			'realisasi_updated_at',
			'realisasi_updated_by',
		];
		HELPER.setRequired([]);
		HELPER.api = {
			table: BASE_URL + 'rekaptransaksi/',
			read: BASE_URL + 'rekaptransaksi/read',
			store: BASE_URL + 'rekaptransaksi/store',
			update: BASE_URL + 'rekaptransaksi/store',
			destroy: BASE_URL + 'rekaptransaksi/destroy',
		}
		/*HELPER.initTable({
			el : 'table-rekaptransaksi',
			url: HELPER.api.table,
		})*/

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})


		calcForm();
		onDetail();
		loadTable();
	});


	function filterBulan() {
		let filterBulan = $('#bulan').val();
		loadTable(filterBulan);
	}

	function onDetail() {
		let realisasiBulan = $('#bulan').val();
		$('#bulan').val(realisasiBulan);
		loadTable(realisasiBulan);
		onAdd();
	}

	function loadTable(filterBulan = null) {
		let data = {
			'filterBulan': null
		};

		if (filterBulan != null) {
			data.filterBulan = filterBulan
		}

		HELPER.initTable({
			el: "table-rekaptransaksi",
			url: HELPER.api.table,
			data: data,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			//sorting: 'desc',
			order: [
				[1, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						// console.log(meta);
						return moment(full['realisasi_tanggal']).format('MM-DD-YYYY');
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_sub_total']);
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_jasa']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						// var realisasi_pajak = (parseInt(full['realisasi_sub_total'], 10) + parseInt(full['realisasi_jasa'])) * 10 / 100;
						// return 'Rp.' + $.number(full['realisasi_pajak']);
						var realisasi_pajak = parseInt(full['realisasi_sub_total'], 10) * 10 / 100;
						return 'Rp.' + $.number(realisasi_pajak);
						// return 'Rp.' + $.number(full['realisasi_pajak']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total']);
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

				// var total_pajak_custom = (parseInt(total_subtotal) + parseInt(total_jasa)) * 10 / 100;
				// var total_pajak_custom = parseInt(total_pajak);
				var total_pajak_custom_old = (parseInt(total_subtotal) + parseInt(total_jasa)) * 10 / 100;
				var total_pajak_custom = parseInt(total_subtotal) * 10 / 100;
				var total_total_custom = parseInt(total_subtotal) + parseInt(total_jasa) + total_pajak_custom;

				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(total_pajak_custom)}`);
				// $('#subrealisasi_total_total').text(`Rp. ${$.number(total_total_custom)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(total_total)}`);

				$('.tarif').html(settings.json.tarif + "%");
				$("#sub_wajibpajak_npwpd").val(settings.json.npwpd);
			}
		});
	}

	function onDelete(realisasi_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'rekaptransaksi/delete',
						data: {
							id: realisasi_id
						},
						complete: function(res) {
							// console.log(res);
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

	function onAdd() {
		HELPER.toggleForm({});
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
			table: 'table-rekaptransaksi'
		});
		$('#periode_rekaptransaksi').val('');
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
					HELPER.loadPage($("#btn-RekapTransaksi"))
					onRefresh();
					HELPER.back({});
				}
			}
		})

	}

	function addRow() {
		var countRow = $('#table-rekap-form tbody > tr').length;
		if (countRow <= 99) {
			var formHtml = $('#table-rekap-form tbody tr:first-child').html();
			$('#table-rekap-form tbody').append(`<tr class="d-flex d-md-table-row">${formHtml}</tr>`);
			for (let i = 0; countRow + 1 >= i; i++) {
				let elem = $('#table-rekap-form tbody').children().eq(i);
				elem.children().first().text(i + 1);
			}
			calcForm();
		} else {
			Swal.fire({
				title: 'Warning!',
				text: 'Jumlah Baris Melebihi Maximum ' + countRow + ' Baris, Klik Tombol Simpan Terlebih Dahulu Dan Anda Bisa Melanjutkan Input Transaksi Kembali!',
				icon: 'warning',
				confirmButtonText: 'Ok'
			})
		}
	}

	function deleteRow(elem) {
		var countRow = $('#table-rekap-form tbody > tr').length;
		if (countRow < 2) {
			HELPER.showMessage({
				success: 'warning',
				title: 'Peringatan',
				message: 'Baris pertama tidak dapat dihapus!'
			})
		} else {
			var rowIndex = $(elem).parent().parent().index();
			HELPER.confirm({
				title: 'Pemberitahuan',
				message: `Apakah anda yakin akan menghapus baris ke ${rowIndex + 1}?`,
				callback: function(res) {
					if (res == true) {
						$(elem).parent().parent().remove()
						for (let i = 0; countRow + 1 >= i; i++) {
							let elem = $('#table-rekap-form tbody').children().eq(i);
							elem.children().first().text(i + 1);
						}
						$('input[name="subtotal[]"]').trigger("input");
					}
				}
			});
		}
	}

	function calcForm() {
		$('input[name="subtotal[]"]').off();
		$('input[name="service[]"]').off();
		$('input[name="tax[]"]').off();

		$('input[name="subtotal[]"]').on('input', function() {
			var val = $(this).val().replace(/\D/gi, '');
			if (val) {
				val = parseInt(val, 10);
			}

			if ($(this).val() == '') {
				$(this).val(0);
				var subtotal = val;
				var service = $(this).parent().parent().find('input[name="service[]"]').val().replace(/\D/gi, '');
				calcTax($(this), subtotal, service);
				return;
			};

			$(this).val($.number(val));

			var subtotal = val;
			var service = $(this).parent().parent().find('input[name="service[]"]').val().replace(/\D/gi, '');
			calcTax($(this), subtotal, service);
		});

		$('input[name="service[]"]').on('input', function(e) {
			var val = $(this).val().replace(/\D/gi, '');
			if (val) {
				val = parseInt(val, 10);
			}
			if ($(this).val() == '') {
				$(this).val(0);
				var subtotal = $(this).parent().parent().find('input[name="subtotal[]"]').val().replace(/\D/gi, '');
				var service = val;
				calcTax($(this), subtotal, service);
				return;
			};

			$(this).val($.number(val));

			var subtotal = $(this).parent().parent().find('input[name="subtotal[]"]').val().replace(/\D/gi, '');
			var service = val;
			calcTax($(this), subtotal, service);
		});

		$($('input[name="service[]"]')[$('input[name="service[]"]').length - 1]).on('keydown', function(e) {
			if (e.keyCode == 9) {
				addRow();
			}
		});

		$('input[name="tax[]"]').on('input', function() {
			if ($(this).val() == '') {
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if (val) {
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		});

		calcTotal();
	}

	function generateReceiptNO(elem) {
		var receiptNumber = $(elem).parent().parent().find('input[name="receiptno[]"');
		var timestamp = Date.now();
		receiptNumber.val(timestamp);
	}

	function calcTax(elem, subtotal, service) {
		//auto hitung pajak
		// subtotal = subtotal.replace(/\D/gi, '');
		if (subtotal) {
			subtotal = parseInt(subtotal, 10);
		}

		// service = service.replace(/\D/gi, '');
		if (service) {
			service = parseInt(service, 10);
		}

		//KONDISI PAKE JUMLAH KARAKTER GENAP ATAU GANJIL
		// if (!(parseInt((subtotal + service)).toString().length % 2 == 0)) {
		// 	var tax = Math.ceil(parseFloat((subtotal + service) * 10 / 100));
		// } else {
		// 	var tax = Math.floor(parseFloat((subtotal + service) * 10 / 100));
		// }

		//KONDISI PAKE HASIL PENAMBAHAN SUBTOTAL DAN JASA GENAP ATAU GANJIL
		var tax = 0;
		if (!(parseInt((subtotal + service)) % 2 == 0)) {
			tax = Math.floor(parseFloat((subtotal + service) * 10 / 100));
		} else {
			tax = Math.ceil(parseFloat((subtotal + service) * 10 / 100));
		}

		//KONDISI PAKE PREDIKSI HASIL TOTAL PAJAK
		// parseInt((45455 + 9091) + (45455 + 9091) * 10 / 100)
		// if (!(parseInt((subtotal + service) + (subtotal + service) * 10 / 100) > (subtotal + service + tax))) {
		// 	console.log("tax ceiled");
		// 	tax = Math.ceil(parseFloat((subtotal + service) * 10 / 100));
		// } else {
		// 	console.log("tax floored");
		// 	tax = Math.floor(parseFloat((subtotal + service) * 10 / 100));
		// }

		// KONDISI PAKE PREDIKSI HASIL TOTAL PAJAK GENAP ATAU GANJIL
		// console.log(parseInt(subtotal + service + tax));
		if (!(parseInt(subtotal + service + tax) % 2 == 0)) {
			tax1 = Math.floor(parseFloat((subtotal + service) * 10 / 100));
			tax2 = Math.ceil(parseFloat((subtotal + service) * 10 / 100));
			tax = (parseInt(subtotal + service + tax1) % 2 == 0) ? tax1 : tax2;
			// console.log(tax1, tax2, tax);
		}

		//KONDISI PAKE

		elem.parent().parent().find('input[name="tax[]"]').val($.number(tax));
	}

	function round(value, precision) {
		var multiplier = Math.pow(10, precision || 0);
		return Math.round(value * multiplier) / multiplier;
	}

	$("#termasuk_pajak").click(function(e) {
		var ischeked = $(this).is(":checked");
		if (ischeked) {
			var valsum_subtotal = $('input[name^="subtotal[]"]').map((idx, elem) => {
				if ($(elem).val() != '') {
					var subTotalBefore = parseInt($(elem).val().replace(/\D/gi, ''));
					if (!(subTotalBefore.toString().length % 2 == 0)) {
						var subTotal = Math.ceil(subTotalBefore - parseFloat(subTotalBefore / 11));
					} else {
						var subTotal = Math.floor(subTotalBefore - parseFloat(subTotalBefore / 11));
					}


					var serviceChargeElem = $(elem).parent().parent().find('input[name="service[]"]');
					var serviceSetelahPajak = serviceChargeElem.val().replace(/\D/gi, '');

					if (!(serviceSetelahPajak.toString().length % 2 == 0)) {
						var serviceSetelahPajak = Math.ceil(parseFloat(serviceSetelahPajak) - parseFloat(serviceSetelahPajak / 11));
					} else {
						var serviceSetelahPajak = Math.floor(parseFloat(serviceSetelahPajak) - parseFloat(serviceSetelahPajak / 11));
					}


					$(elem).val($.number(subTotal));
					serviceChargeElem.val($.number(serviceSetelahPajak));
					return parseInt($(elem).val().replace(/\D/gi, ''));
				}
			}).get();
		} else {
			var valsum_subtotal = $('input[name^="subtotal[]"]').map((idx, elem) => {
				if ($(elem).val() != '') {
					var serviceChargeElem = $(elem).parent().parent().find('input[name="service[]"]');

					var subTotal = parseInt($(elem).val().replace(/\D/gi, ''));
					var service = parseInt(serviceChargeElem.val().replace(/\D/gi, ''));

					if (!(parseInt(subTotal + (10 * subTotal / 100)) % 2 == 0)) {
						subTotal = Math.ceil(subTotal + (10 * subTotal / 100));
					} else {
						subTotal = Math.floor(subTotal + (10 * subTotal / 100));
					}

					if (!(parseInt(service + (10 * service / 100)) % 2 == 0)) {
						service = Math.ceil(service + (10 * service / 100));
					} else {
						service = Math.floor(service + (10 * service / 100));
					}

					$(elem).val($.number(subTotal));
					serviceChargeElem.val($.number(service));
					return parseInt($(elem).val().replace(/\D/gi, ''));
				}
			}).get();
		}

		$('input[name="subtotal[]"]').trigger("input");
	});

	function calcTotal() {
		// $('input[name="subtotal[]"]').off();
		// $('input[name="service[]"]').off();
		// $('input[name="tax[]"]').off();

		$('input[name="subtotal[]"]').on('input', function() {
			// Sum Total Row
			var valrow_subtotal = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = (!valrow_subtotal.replace(/\D/gi, '')) ? "0" : valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = (!valrow_tax.replace(/\D/gi, '')) ? "0" : valrow_tax.replace(/\D/gi, '');
			valrow_service = (!valrow_service.replace(/\D/gi, '')) ? "0" : valrow_service.replace(/\D/gi, '');
			if (valrow_subtotal && valrow_tax && valrow_service) {
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_subtotal = $('input[name^="subtotal[]"]').map((idx, elem) => {
				if ($(elem).val() != '') {
					return parseInt($(elem).val().replace(/\D/gi, ''));
				}
			}).get();
			var valsum_subtotal_all = valsum_subtotal.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);

			if (valsum_subtotal) {
				$('input[name=sum_subtotal]').val($.number(valsum_subtotal_all));
			} else {
				$('input[name=sum_subtotal]').val(0);
			}

			$($('input[name="service[]"]')[$('input[name="service[]"]').length - 1]).trigger("input");
			// console.log("AAAAAA");

			// Sum Total
			// var valsum_tax = $('input[name=sum_tax]').val();
			// var valsum_service = $('input[name=sum_service]').val();
			// valsum_tax = valsum_tax.replace(/\D/gi, '');
			// valsum_service = valsum_service.replace(/\D/gi, '');
			// var valsum_total = parseInt(valsum_tax, 10) + parseInt(valsum_service, 10) + valsum_subtotal_all;

			// $('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="service[]"]').on('input', function() {
			// SUM Total Row
			var valrow_service = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = (!valrow_subtotal.replace(/\D/gi, '')) ? "0" : valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = (!valrow_tax.replace(/\D/gi, '')) ? "0" : valrow_tax.replace(/\D/gi, '');
			valrow_service = (!valrow_service.replace(/\D/gi, '')) ? "0" : valrow_service.replace(/\D/gi, '');
			if (valrow_subtotal && valrow_service && valrow_tax) {
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_service, 10) + parseInt(valrow_tax, 10);
				row_total.val($.number(sum_total));
			}

			// $('input[name="tax[]"').trigger("input");
			$($('input[name="tax[]"')[$('input[name="tax[]"').length - 1]).trigger("input");
			// console.log("BBBBBB");

			// Sum Service Column
			var valsum_service = $('input[name^="service[]"]').map((idx, elem) => {
				if ($(elem).val() != '') {
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum_service_all = valsum_service.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if (valsum_service) {
				$('input[name=sum_service]').val($.number(valsum_service_all));
			} else {
				$('input[name=sum_service]').val(0);
			}

			// Sum Total
			// var valsum_tax = $('input[name=sum_tax]').val();
			// var valsum_subtotal = $('input[name=sum_subtotal]').val();
			// valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			// valsum_tax = valsum_tax.replace(/\D/gi, '');
			// var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_tax, 10) + valsum_service_all;
			// $('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="tax[]"]').on('input', function() {
			// console.log("CCCCCC");
			// SUM Total Row
			var valrow_tax = $(this).val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = (!valrow_subtotal.replace(/\D/gi, '')) ? "0" : valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = (!valrow_tax.replace(/\D/gi, '')) ? "0" : valrow_tax.replace(/\D/gi, '');
			valrow_service = (!valrow_service.replace(/\D/gi, '')) ? "0" : valrow_service.replace(/\D/gi, '');
			if (valrow_subtotal && valrow_tax && valrow_service) {
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_tax = $('input[name^="tax[]"]').map((idx, elem) => {
				if ($(elem).val() != '') {
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum_tax_all = valsum_tax.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if (valsum_tax) {
				$('input[name=sum_tax]').val($.number(valsum_tax_all));
			} else {
				$('input[name=sum_tax]').val(0);
			}

			// Sum Total
			var valsum_subtotal = $('input[name=sum_subtotal]').val();
			var valsum_service = $('input[name=sum_service]').val();
			valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			valsum_service = valsum_service.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_service, 10) + valsum_tax_all;
			$('input[name=sum_total]').val($.number(valsum_total));
		});
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


	$(document).off("keydown");
	$('input[name="subtotal[]"]').off();
	$('input[name="service[]"]').off();
	$('input[name="tax[]"]').off();

	$(document).keydown(function(event) {
		if (event.ctrlKey && event.shiftKey && event.key === 'ArrowDown') {
			addRow();
			// console.log('Ctrl + Shift + Arrow Down pressed');
		}
	});
</script>