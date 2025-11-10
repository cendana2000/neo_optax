<script type="text/javascript">
	var gNPWPD = '';

	$(function() {
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

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
			table: BASE_URL + 'realisasipajak/',
			subTable: BASE_URL + 'realisasipajak/sub_table',
			read: BASE_URL + 'realisasipajak/read',
			detail: BASE_URL + 'realisasipajak/realisasi_detail',
			update: BASE_URL + 'realisasipajak/update',
			destroy: BASE_URL + 'realisasipajak/destroy',
			wp_header: BASE_URL + 'realisasipajak/wp_header',
			readWp: BASE_URL + 'realisasipajak/readWp',
		}
		/*HELPER.initTable({
			el : 'table-satuan',
			url: HELPER.api.table,
		})*/
		calcForm();
		wp_header();
		loadTable();
	});

	function wp_header() {
		$.get(HELPER.api.wp_header, function(res) {
			$('#wp_terdaftar').text(res.wp_terdaftar);
			$('#wp_terkoneksi').text(res.wp_terkoneksi);
		});
	}

	function filterBulan() {
		let filterBulan = $('#bulan').val();
		loadTable(filterBulan);
	}

	function filterSubBulan() {
		let filterBulan = $('#sub_bulan').val();
		loadSubTable(gNPWPD, filterBulan);
	}

	function loadTable(filterBulan = null) {
		let data = {};

		if (filterBulan != null) {
			data.filterBulan = filterBulan
		}

		HELPER.initTable({
			el: "table-realisasi",
			url: HELPER.api.table,
			data: data,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [[3, 'asc']],
			columnDefs: [{
					defaultContent: "-",
					targets: "_all"
				}, {
					targets: 1,
					render: function(data, type, full, meta) {
						return full['realisasi_parent_npwpd'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['realisasi_parent_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['realisasi_parent_transaksi_terakhir'] ? moment(full['realisasi_parent_transaksi_terakhir']).format('DD-MM-YYYY HH:mm:ss') : '-';
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return $.number(full['realisasi_parent_jml_transaksi']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_parent_omzet']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_parent_total_pajak']);
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_parent_omzet'] * (full['realisasi_parent_jenis_tarif'] / 100));
					},
				},
				{
					targets: 8,
					render: function(data, type, full, meta) {
						return full['realisasi_parent_tanggal_daftar'];
					},
				},
				{
					targets: 9,
					render: function(data, type, full, meta) {
						return full['realisasi_parent_jenis_pajak'];
					},
				},
				{
					targets: 10,
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						return `<button onclick="onDetail('${full['realisasi_parent_npwpd']}')" class="btn btn-primary btn-sm">Detail</button>`;
					},
				},

			],
		});
	}

	function onDetail(realisasi_npwpd) {
		let realisasiBulan = $('#bulan').val();
		$('#sub_bulan').val(realisasiBulan);

		gNPWPD = realisasi_npwpd;
		$.post(HELPER.api.readWp, {
			wp_npwpd: realisasi_npwpd
		}, function(res) {
			$('#sub_wajibpajak_npwpd').val(res.wajibpajak_npwpd);
			$('#sub_wajibpajak_nama').val(res.wajibpajak_nama);
			$('#sub_wajibpajak_alamat').val(res.wajibpajak_alamat);
			$('#sub_wajibpajak_nama_penanggungjawab').val(res.wajibpajak_nama_penanggungjawab);

			loadSubTable(realisasi_npwpd, realisasiBulan);
			onAdd();
		});
	}


	function loadSubTable(realisasi_npwpd, filterBulan = null) {
		let data = {
			'realisasi_npwpd': realisasi_npwpd,
			'filterBulan': null
		};
		if (filterBulan != null) {
			data.filterBulan = filterBulan;
		}

		HELPER.initTable({
			el: "table-sub-realisasi",
			url: HELPER.api.subTable,
			data: data,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			sorting: 'desc',
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['realisasi_tanggal'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['realisasi_wajibpajak_npwpd'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_sub_total']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_jasa']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_pajak']);
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total']);
					},
				},
				{
					targets: -1,
					render: function(data, type, full, meta) {
						let html = `<button onclick="subRinci('${full['realisasi_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
						let dropdown = `<div class="ml-3 dropdown dropdown-inline">
							<a href="javascript:;" class="btn btn-sm btn-success btn-icon" data-toggle="dropdown">
								<i class="fa fa-cog"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<ul class="nav nav-hoverable flex-column">
								<li class="nav-item"><a class="nav-link text-hover-primary" href="javascript:;" onclick="onEditSub('` + full['realisasi_id'] + `')"><i class="nav-icon fa fa-pen"></i><span>Edit</span></a></li>
								<li class="nav-item"><a class="nav-link text-hover-danger" href="javascript:;" onclick="onDeleteSub('` + full['realisasi_id'] + `')"><i class="nav-icon fa fa-trash"></i><span>Hapus</span></a></li>
							</ul>
							</div>
						</div>`;
						return html + dropdown;
					},
				},

			],
			fnDrawCallback: function(settings){
				var {
					sumtotal: {
						total_jasa = 0,
						total_pajak = 0,
						total_subtotal = 0,
						total_total = 0
					}
				} = settings.json;

				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(total_pajak)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(total_total)}`);
			}
		});
	}

	function subRinci(realisasi_id) {
		$.post(HELPER.api.read, {
			realisasi_id: realisasi_id
		}, function(res) {
			$('#rinci_realisasi_id').val(realisasi_id);
			$('#rinci_wajibpajak_npwpd').val(res.realisasi_wajibpajak_npwpd);
			$('#rinci_wajibpajak_nama').val(res.wajibpajak_nama);
			$('#rinci_wajibpajak_alamat').val(res.wajibpajak_alamat);
			$('#rinci_wajibpajak_nama_penanggungjawab').val(res.wajibpajak_nama_penanggungjawab);
			$('#realisasi_tanggal').text(res.realisasi_tanggal);
			$('#rinci_realisasi_tanggal').val(res.realisasi_tanggal);

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
				realisasi_id: realisasi_id
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
			fnDrawCallback: function(settings){
				var {
					sumtotal: {
						total_jasa = 0,
						total_pajak = 0,
						total_subtotal = 0,
						total_total = 0
					}
				} = settings.json;

				$('#subrealisasi_detail_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_detail_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_detail_total_pajak').text(`Rp. ${$.number(total_pajak)}`);
				$('#subrealisasi_detail_total_total').text(`Rp. ${$.number(total_total)}`);
			}
		});
	}


	function onAdd() {
		HELPER.toggleForm({});
	}

	function onBack() {
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

	function onRefresh(state = 1) {
		if (state == 1) {
			HELPER.refresh({
				table: 'table-realisasi'
			});
		}

		if (state == 2) {
			loadSubTable(gNPWPD);
		}

		if (state == 3) {
			realisasiDetail($('#rinci_realisasi_id').val());
		}

		$("#btnReset").trigger("click");
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

	function onEditSub(id){
		$('#modal-realisasi_id').val(id);
		$('#modal-tanggal').val('');

		HELPER.ajax({
			url: BASE_URL + 'realisasipajak/read',
			data: {
				realisasi_id: id
			},
			success: function(res) {
				$('#modal-tanggal').val(res.realisasi_tanggal);
				$('#modal-wajibpajak_npwpd').val(res.realisasi_wajibpajak_npwpd);
				
				$('#table-rekap-form tbody').html('');

				console.log($('#table-rekap-form tbody').children().html());
				
				if(res.detail.length == 0){
					$('#table-rekap-form tbody').append(`<tr class="d-flex d-md-table-row">
						<td class="col-1 col-md-auto">1</td>
						<td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="receiptno[]" required/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" required/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" required/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" required/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" style="background-color: #eaeaea;" readonly/></td>
						<td class="col-2 col-md-auto">
							<button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2"><i class="fa fa-trash"></i></button>
						</td>
					</tr>`);
				}

				$.each(res.detail, (i, v) => {
					$('#table-rekap-form tbody').append(`<tr class="d-flex d-md-table-row">
						<td class="col-1 col-md-auto">${i+=1}</td>
						<td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required value="${v.realisasi_detail_time}"/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="receiptno[]" required value="${v.realisasi_detail_penjualan_kode}"/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" required value="${$.number(v.realisasi_detail_sub_total)}"/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" required value="${$.number(v.realisasi_detail_jasa)}"/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" required value="${$.number(v.realisasi_detail_pajak)}"/></td>
						<td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" style="background-color: #eaeaea;" readonly/></td>
						<td class="col-2 col-md-auto">
							<button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2"><i class="fa fa-trash"></i></button>
						</td>
					</tr>`);
				})

				calcForm();

				$.each(res.detail, (i, v) => {
					$($('#table-rekap-form tbody').children('tr')[i]).find('input[name="subtotal[]"]').trigger('input');
					$($('#table-rekap-form tbody').children('tr')[i]).find('input[name="service[]"]').trigger('input');
					$($('#table-rekap-form tbody').children('tr')[i]).find('input[name="tax[]"]').trigger('input');
				})
				
				HELPER.toggleForm({
					tohide: 'form_data',
					toshow: 'form_data_edit'
				});
			},
			complete: function(res){
				
			}
		})
	}

	function editSubPeriode(el){
		HELPER.confirm({
			title : 'Pemberitahuan',
			message : 'Apakah anda ingin menyimpan perubahan?',
			callback : function(res) {
				if(res == true){	
					HELPER.block()
					try {
						var formData = $('#form-edit-sub-periode').serializeArray();
						HELPER.ajax({
							url: BASE_URL + 'realisasipajak/edit_sub_periode',
							// data: {
							// 	realisasi_id: $('#modal-realisasi_id').val(),
							// 	tanggal: $('#modal-ubah_tanggal').val(),
							// },
							data: formData,
							complete: function(res) {
								if (res.success) {
									HELPER.showMessage({
										success: true,
										title: 'Success',
										message: res.message
									});
									$('#modal-edit-sub-periode').modal('hide');
									$('#modal-realisasi_id').val('');
									HELPER.toggleForm({
										tohide: 'form_data_edit',
										toshow: 'form_data'
									});
									onRefresh(2);
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
					} catch (error) {
						console.log(error)
					}
				}
			}
		});
	}

	function onDeleteSub(id){
		HELPER.confirm({
			title : 'Pemberitahuan',
			message : 'Apakah anda ingin menghapus periode ini?',
			callback : function(res) {
				if(res == true){	
					HELPER.block()
					HELPER.ajax({
						url: BASE_URL + 'realisasipajak/delete_sub_periode',
						data: {
							realisasi_id: id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: res.message
								});
								onRefresh(2);
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
		});
	}

	function addRow(){
		var countRow = $('#table-rekap-form tbody > tr').length;
		$('#table-rekap-form tbody').append(`<tr class="d-flex d-md-table-row">
			<td class="col-1 col-md-auto">1</td>
			<td class="col-4 col-md-auto"><input type="time" class="form-control" name="time[]" required/></td>
			<td class="col-3 col-md-auto"><input type="text" class="form-control" name="receiptno[]" required/></td>
			<td class="col-3 col-md-auto"><input type="text" class="form-control" name="subtotal[]" required/></td>
			<td class="col-3 col-md-auto"><input type="text" class="form-control" name="service[]" required/></td>
			<td class="col-3 col-md-auto"><input type="text" class="form-control" name="tax[]" required/></td>
			<td class="col-3 col-md-auto"><input type="text" class="form-control" name="total[]" style="background-color: #eaeaea;" readonly/></td>
			<td class="col-2 col-md-auto">
				<button type="button" onclick="deleteRow(this)" class="btn btn-danger btn-icon mr-2"><i class="fa fa-trash"></i></button>
			</td>
		</tr>`);
		for(let i = 0; countRow + 1 >= i; i++){
			let elem = $('#table-rekap-form tbody').children().eq(i);
			elem.children().first().text(i+1);
		}
		calcForm();
	}

	function deleteRow(elem){
		var countRow = $('#table-rekap-form tbody > tr').length;
		if(countRow < 2){
			HELPER.showMessage({
				success: 'warning',
				title: 'Peringatan',
				message: 'Baris pertama tidak dapat dihapus!'
			})
		}else{
			var rowIndex = $(elem).parent().parent().index();
			HELPER.confirm({
				title : 'Pemberitahuan',
				message : `Apakah anda yakin akan menghapus baris ke ${rowIndex + 1}?`,
				callback : function(res) {
					if(res == true){
						$(elem).parent().parent().remove()
						for(let i = 0; countRow + 1 >= i; i++){
							let elem = $('#table-rekap-form tbody').children().eq(i);
							elem.children().first().text(i+1);
						}
					}
				}
			});
		}
	}

	function calcForm(){
		$('input[name="subtotal[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		$('input[name="service[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		$('input[name="tax[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		calcTotal();
	}

	function calcTotal(){
		$('input[name="subtotal[]"]').on('input', function () {
			// Sum Total Row
			var valrow_subtotal = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_tax && valrow_service){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_subtotal = $('input[name^="subtotal[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_subtotal.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_subtotal){
				$('input[name=sum_subtotal]').val($.number(valsum));
			}else{
				$('input[name=sum_subtotal]').val(0);
			}

			// Sum Total
			var valsum_tax = $('input[name=sum_tax]').val();
			var valsum_service = $('input[name=sum_service]').val();
			valsum_tax = valsum_tax.replace(/\D/gi, '');
			valsum_service = valsum_service.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_tax, 10) + parseInt(valsum_service, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="service[]"]').on('input', function () {
			// SUM Total Row
			var valrow_service = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_service && valrow_tax){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_service, 10) + parseInt(valrow_tax, 10);
				row_total.val($.number(sum_total));
			}

			// Sum Service Column
			var valsum_service = $('input[name^="service[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_service.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_service){
				$('input[name=sum_service]').val($.number(valsum));
			}else{
				$('input[name=sum_service]').val(0);
			}

			// Sum Total
			var valsum_tax = $('input[name=sum_tax]').val();
			var valsum_subtotal = $('input[name=sum_subtotal]').val();
			valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			valsum_tax = valsum_tax.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_tax, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="tax[]"]').on('input', function () {
			// SUM Total Row
			var valrow_tax = $(this).val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_tax && valrow_service){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				console.log(valrow_subtotal, valrow_tax, sum_total)
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_tax = $('input[name^="tax[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_tax.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_tax){
				$('input[name=sum_tax]').val($.number(valsum));
			}else{
				$('input[name=sum_tax]').val(0);
			}

			// Sum Total
			var valsum_subtotal = $('input[name=sum_subtotal]').val();
			var valsum_service = $('input[name=sum_service]').val();
			valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			valsum_service = valsum_service.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_service, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		})
	}
</script>