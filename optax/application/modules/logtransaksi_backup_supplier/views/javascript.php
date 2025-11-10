<!-- script old -->
<!-- <script type="text/javascript">
	$(function() {
		row = 1;
		HELPER.fields = [
			'supplier_id',
			'supplier_kode',
			'supplier_nama',
			'supplier_alamat',
			'supplier_telp',
			'supplier_rekening',
		];
		HELPER.setRequired([
			'supplier_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'supplier/',
			read: BASE_URL + 'supplier/read',
			store: BASE_URL + 'supplier/store',
			update: BASE_URL + 'supplier/update',
			destroy: BASE_URL + 'supplier/destroy',
		}
		// init_table();
		loadTable();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-supplier",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			select: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['supplier_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['supplier_telp'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['supplier_alamat'];
					},
				},

				{
					targets: 5,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['supplier_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="fa fa-trash"></i>
											</span>
										</a>`;
						return btn_aksi;
					},
				},

			],
		});
	}


	function remRow(el) {
		$('tr.sales_' + el).remove();
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function add_check() {
		done = true;
		$('.nama').each(function(i, v) {
			if (!$(v).val()) done = false;
		})
		if (done) addSales();
	}

	function resetSales() {
		const html = `
				<tr class="sales_1">
					<td scope="row">
						<input type="hidden" class="form-control" name="sales_id[1]" id="sales_id_1">
						<input type="text" class="form-control nama" name="sales_nama[1]" id="sales_nama_1" onkeyup="add_check()">
					</td>
					<td><input class="form-control" type="text" name="sales_telp[1]" id="sales_telp_1"></td>
					<td><input class="form-control" type="text" name="sales_hp[1]" id="sales_hp_1"></td>
					<td><input class="form-control" type="text" name="sales_keterangan[1]" id="sales_keterangan_1"></td>
					<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('1')" title="Hapus">
							<span class="la la-trash"></span> Hapus</a></td>
				</tr>
		`;

		$('#table-sales').empty();
		$('#table-sales').append(html);
		row = 1;
	}

	// $(document).keypress(function(event) {
	// 	var keycode = (event.keyCode ? event.keyCode : event.which);
	// 	if (keycode == '13') {
	// 		resetSales();
	// 	}
	// });

	function addSales() {
		row++;
		html = `<tr class="sales_` + row + `">
					<td scope="row">
						<input type="hidden" class="form-control" name="sales_id[` + row + `]" id="sales_id_` + row + `">
						<input type="text" class="form-control nama" name="sales_nama[` + row + `]" id="sales_nama_` + row + `">
					</td>
					<td><input class="form-control" type="text" name="sales_telp[` + row + `]" id="sales_telp_` + row + `"></td>
					<td><input class="form-control" type="text" name="sales_hp[` + row + `]" id="sales_hp_` + row + `"></td>
					<td><input class="form-control" type="text" name="sales_keterangan[` + row + `]" id="sales_keterangan_` + row + `"></td>
					<td><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('` + row + `')" title="Hapus">
							<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-sales').append(html);
		$('input.number').number(true);
		$('.disc').number(true, 2);
	}


	function init_table(argument) {
		var table = $('#table-supplier').DataTable({
			responsive: true,
			select: 'single',
			buttons: [
				'print',
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
			],
			processing: true,
			serverSide: true,
			ajax: {
				url: BASE_URL + 'supplier/',
				type: 'POST'
			},
			order: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>`;
					},
				}
			],
			fnDrawCallback: function(oSettings) {
				var cnt = 0;
				$("tr", this).css('cursor', 'pointer');
				$("tbody tr", this).each(function(i, v) {
					$(v).on('click', function() {
						if ($(this).hasClass('selected')) {
							--cnt;
							$(v).removeClass('selected');
							$(v).removeAttr('checked');
							$('input[name=checkbox]', v).prop('checked', false);
							$(v).removeClass('row_selected');
						} else {
							++cnt;
							$('input[name=checkbox]', v).prop('checked', true);
							$('input[name=checkbox]', v).data('checked', 'aja');
							$(v).addClass('selected');
							$(v).addClass('row_selected asli');
						}

						if (cnt > 0) {
							$('.disable').attr('disabled', false);
						} else {
							$('.disable').attr('disabled', true);
						}
					});
				});
			},
		});
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-supplier',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				resetSales();
				if (res.sales.total > 0) {
					sales = res.sales.data
					$.each(sales, function(i, v) {
						if (i > 0) addSales();
						$('#sales_id_' + row).val(v.sales_id);
						$('#sales_nama_' + row).val(v.sales_nama);
						$('#sales_telp_' + row).val(v.sales_telp);
						$('#sales_hp_' + row).val(v.sales_hp);
						$('#sales_keterangan_' + row).val(v.sales_keterangan);
						row++;
					})
				}
				onAdd();

			}
		})
	}

	function onBack() {
		$('#triggerReset').trigger("click");
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-supplier'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-supplier',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					HELPER.refresh({
						table: 'table-supplier'
					});
					HELPER.back({});
					$('#triggerReset').trigger("click");
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-supplier',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function onPrint() {
		HELPER.createCombo({
			el: 'supplier1',
			valueField: 'supplier_id',
			displayField: 'supplier_kode',
			displayField2: 'supplier_nama',
			grouped: true,
			url: BASE_URL + 'supplier/select',
			callback: function() {
				$('#supplier1').select2();
			}
		})

		HELPER.createCombo({
			el: 'supplier2',
			valueField: 'supplier_id',
			displayField: 'supplier_kode',
			displayField2: 'supplier_nama',
			grouped: true,
			url: BASE_URL + 'supplier/select',
			callback: function() {
				$('#supplier2').select2();
			}
		})
		$('#myModal').modal('show');
	}

	function onDelete(supplier_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'supplier/delete',
						data: {
							id: supplier_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-supplier'
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


	function loadPreview() {
		HELPER.block();
		data = $('#cetakSupplier').serializeArray();
		$.ajax({
			url: BASE_URL + 'supplier/preview',
			data: data,
			type: 'post',
			dataType: 'json',
			success: function(res) {
				HELPER.toggleForm({
					tohide: 'table_data',
					toshow: 'form_data2',
				});
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}
</script> -->

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
			table: BASE_URL + 'logtransaksi/',
			read: BASE_URL + 'logtransaksi/read',
			store: BASE_URL + 'logtransaksi/store',
			update: BASE_URL + 'logtransaksi/store',
			destroy: BASE_URL + 'logtransaksi/destroy',
		}

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})
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
			el: "table-logtransaksi",
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

				var total_pajak_custom_old = (parseInt(total_subtotal) + parseInt(total_jasa)) * 10 / 100;
				var total_pajak_custom = parseInt(total_subtotal) * 10 / 100;
				var total_total_custom = parseInt(total_subtotal) + parseInt(total_jasa) + total_pajak_custom;

				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(total_pajak_custom)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(total_total)}`);

				$('.tarif').html(settings.json.tarif + "%");
				$("#sub_wajibpajak_npwpd").val(settings.json.npwpd);
			}
		});
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-logtransaksi'
		});
		$('#periode_rekaptransaksi').val('');
		$('#laporan_realisasi').val('');
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