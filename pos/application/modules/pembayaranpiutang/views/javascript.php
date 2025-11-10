<script type="text/javascript">
	$(function() {
		row = 1;
		rowPembayaran = 1;
		satuan = barang = detail = [];
		produkSelect = {};
		HELPER.fields = [
			'pembayaran_piutang_id',
			'pembayaran_piutang_kode',
			'pembayaran_piutang_tanggal',
			'pembayaran_piutang_customer_id',
			'pembayaran_piutang_referensi',
			'pembayaran_piutang_keterangan',
			'pembayaran_piutang_tagihan',
			'pembayaran_piutang_bayar',
			'pembayaran_piutang_sisa',
			'pembayaran_piutang_akun_id',
			'pembayaran_piutang_status',
			'pembayaran_piutang_invoice',
			'pembayaran_piutang_tanggal_invoice',
			'pembayaran_piutang_sales',
		];
		HELPER.setRequired([
			'pembayaran_piutang_customer_id',
			'pembayaran_piutang_akun_id',
			'pembayaran_piutang_tanggal',
		]);
		HELPER.api = {
			table: BASE_URL + 'pembayaranpiutang/',
			read: BASE_URL + 'pembayaranpiutang/read',
			store: BASE_URL + 'pembayaranpiutang/store',
			update: BASE_URL + 'pembayaranpiutang/update',
			destroy: BASE_URL + 'pembayaranpiutang/destroy',
		}

		$('input.number').number(true);

		HELPER.ajaxCombo({
			el: '#pembayaran_piutang_customer_id',
			url: BASE_URL + 'customer/select_ajax',
		});

		/*HELPER.create_combo_akun({
			el : 'pembayaran_piutang_akun_id',
    		valueField : 'id',
    		displayField : 'text',
    		parentField : 'parent',
    		childField : 'child',
    		url : BASE_URL+'akun/go_tree',
    		withNull : true,
    		nesting : true,
    		chosen : false,
    		callback : function(){}
		});*/
		// getSales()
		// init_table();

		// Form hide when load
		// $('#table-opsi-pembayaran').hide();
		// $('#btnPembayaran').hide();
		loadTable();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('customer-Update') || HELPER.get_role_access('customer-Delete'));
		HELPER.initTable({
			el: "table-pembayaran",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return '';
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembayaran_piutang_tanggal']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['customer_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_tagihan'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_bayar'];
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" title="Print" onclick="onPrint('${full['pembayaran_piutang_id']}')" >
							<span class="svg-icon svg-icon-md">
								<i class="la la-print"></i>
							</span>
            </a>	
						<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
						</a>
						<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" title="Delete" onclick="onDelete('${full['pembayaran_piutang_id']}')">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-trash"></i>
							</span>
						</a>
						`;
						return btn_aksi;
					},
				},

			],
		});
	}

	function loadTable2() {
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()

		HELPER.initTable({
			el: "table-pembayaran",
			url: BASE_URL + 'pembayaranpiutang/index2',
			data: {
				tanggal1: awal,
				tanggal2: akhir,
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return '';
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembayaran_piutang_tanggal']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['customer_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_tagihan'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['pembayaran_piutang_bayar'];
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>
						<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" title="Delete" onclick="onDelete('${full['pembayaran_piutang_id']}')">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-trash"></i>
						</span>
                        </a>
						`;
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDestroy(this)" title="Hapus" >
						// <span class="svg-icon svg-icon-md">
						// 	<i class="fa fa-trash"></i>
						// </span>
						// </a>`;
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
						//   <span class="la la-trash"></span> Hapus
						// </a>`;

						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="coba(${full['pembayaran_piutang_id']})"">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-trash"></i>
						// 					</span>
						// 				</a>`;
						return btn_aksi;
					},
				},

			],
		});
	}

	function setJT() {
		jt = $('#penjualan_jatuh_tempo_hari').val();
		jt_tempo = moment();
		$('#penjualan_jatuh_tempo').val(jt_tempo.add((jt), 'd').format('YYYY-MM-DD'));
	}

	function jenisBayar() {
		let choose = $('#penjualan_bayar_opsi').val();
		let jatuhTempo = $('#jatuh_tempo');
		let lab_jatuhTempo = $('#label_jatuh_tempo');
		let btnTunai = $('#btnTunai');
		if (choose == 'K') {
			jatuhTempo.show();
			lab_jatuhTempo.show();
			btnTunai.hide(100);
			$('#table-detail_beli').show(100);
			$('#btnBarang').show(100);
			$('#table-opsi-pembayaran').hide();
			$('#btnPembayaran').hide();
		} else if (choose == 'T') {
			jatuhTempo.hide();
			lab_jatuhTempo.hide();
			btnTunai.show(100);
		} else {
			btnTunai.hide(100);
			$('#table-opsi-pembayaran').hide();
			$('#btnPembayaran').hide();
			jatuhTempo.hide();
			lab_jatuhTempo.hide();

		}
	}

	function pilihan(param) {
		if (param === 'listProduk') {
			$('#table-opsi-pembayaran').hide();
			$('#btnPembayaran').hide();
			$('#table-detail_beli').show(100);
			$('#btnBarang').show(100);
		} else if (param === 'pembayaran') {
			$('#table-detail_beli').hide();
			$('#btnBarang').hide();
			$('#table-opsi-pembayaran').show(100);
			$('#btnPembayaran').show(100);
		}
	}

	function addPembayaran() {
		rowPembayaran++;

		html = `<tr class="pembayaran_piutang_` + rowPembayaran + `">
					<td scope="rowPembayaran">
						<input type="hidden" class="form-control" name="pembayaran_piutang_detail_pembayaran_id[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_id_` + rowPembayaran + `">
						<input type="date" class="form-control" name="pembayaran_piutang_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="pembayaran_piutang_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
					</td>
					<td>
						<select class="form-control" name="pembayaran_piutang_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
							<option value="">-Pilih Cara Bayar-</option>
							<option value="Transfer Bank">Transfer Bank</option>
							<option value="Cash">Cash</option>
						</select>
					</td>
					<td>
						<input class="form-control number jumlahNow" type="text" name="pembayaran_piutang_detail_pembayaran_total[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_total_` + rowPembayaran + `">
					</td>
					<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
							<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('.number').number(true);
		$('#tbody-pembayaran').append(html);

		$('.jumlahNow').keyup(function() {
			let totalBayar = $('#totalbayar').val();
			let sum = 0;
			let total = 0;

			$('.jumlahNow').each(function() {
				sum += Number($(this).val());
			});
			total += sum;

			$('#totalAppend').val(total);
		});
	}

	function addPembayaranEdit() {

		html = `<tr class="pembayaran_piutang_` + rowPembayaran + `">
			<td scope="rowPembayaran">
				<input type="hidden" class="form-control" name="pembayaran_piutang_detail_pembayaran_id[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_id_` + rowPembayaran + `">
				<input type="date" class="form-control" name="pembayaran_piutang_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="pembayaran_piutang_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
			</td>
			<td>
				<select class="form-control" name="pembayaran_piutang_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
					<option value="">-Pilih Cara Bayar-</option>
					<option value="Transfer Bank">Transfer Bank</option>
					<option value="Cash">Cash</option>
				</select>
			</td>
			<td>
				<input class="form-control number jumlahNow" type="text" name="pembayaran_piutang_detail_pembayaran_total[` + rowPembayaran + `]" id="pembayaran_piutang_detail_pembayaran_total_` + rowPembayaran + `">
			</td>
			<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
					<span class="la la-trash"></span> Hapus</a></td>
		</tr>`;
		$('.number').number(true);
		$('#tbody-pembayaran').append(html);

		$('.jumlahNow').keyup(function() {
			let totalBayar = $('#totalbayar').val();
			let sum = 0;
			let total = 0;

			$('.jumlahNow').each(function() {
				sum += Number($(this).val());
			});
			// if (sum > totalBayar) {
			// 	$("#pembayaran_piutang_detail_pembayaran_total_" + rowPembayaran).val(0);
			// } else {
			// }
			total += sum;

			$('#totalAppend').val(total);
		});
	}

	function setBayar() {
		let totalBayar = $('#totalbayar').val();
		let totalSatuan = $('[id^=pembayaran_piutang_detail_pembayaran_total_]');

		totalSatuan.val(totalBayar);
		$('#totalAppend').val(totalBayar);
	}

	$('.jumlahNow').keyup(function() {
		let totalBayar = $('#totalbayar').val();
		let sum = 0;
		let total = 0;

		$('.jumlahNow').each(function() {
			sum += Number($(this).val());
		});
		// if (sum > totalBayar) {
		// 	swal.fire('Informasi', 'Nilai Pembayaran Tidak dapat Lebih dari Total', 'warning');
		// 	$("#pembayaran_piutang_detail_pembayaran_total_" + rowPembayaran).val(0);
		// } else {
		// 	total += sum;
		// }

		total += sum;

		$('#totalAppend').val(total);
	});

	function remRowPembayaran(el, cRow) {
		let cTotal = $('#totalAppend').val();
		let pengurang = $('#pembayaran_piutang_detail_pembayaran_total_' + cRow).val();
		let cBayar = cTotal - pengurang;

		$('#totalAppend').val(cBayar);


		id = $(el).data('id');
		$('tr.pembayaran_piutang_' + id).remove();
		countRow(id);
	}


	// =================================================================================================
	function listBeli() {
		$('#daftar_beli').modal();

		var tabledetailtr = $("#table-detail_beli tbody tr")
		var barangids = [];
		tabledetailtr.each((key, val) => {
			barangids.push($(val).data("id"));
		});

		HELPER.initTable({
			el: "list_beli",
			url: BASE_URL + 'transaksipenjualan/table_faktur',
			data: {
				penjualan_customer_id: $('#pembayaran_piutang_customer_id').val(),
			},
			searchAble: true,
			destroyAble: true,
			responsive: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['penjualan_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['barang_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['customer_nama'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return moment(full['penjualan_jpenjualan_tanggalatuh_tempo']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return moment(full['penjualan_jatuh_tempo']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['penjualan_total_grand'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['penjualan_total_retur'];
					},
				},
				{
					targets: 8,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						add = true;
						var isExist = barangids.includes(full.penjualan_detail_id);
						if (isExist) {
							add = false;
						}
						if (add) {
							aksi = `<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis('${full['penjualan_id']}',this)" >
                      <i class="la la-remove"></i> batal
                    </button>`;
						} else {
							aksi = `<button type="button" class="btn btn-primary btn-pill btn-sm" title="Pilih" onclick="addThis('${full['penjualan_id']}',this)" >
				              <i class="la la-check-circle"></i> Pilih
				            </button>`;
						}
						return aksi;
					},
				},

			],
		});

	}


	function getCustomer() {

		/*$('#pembayaran_piutang_sales').autoComplete({
	 		minChars : 0,
	 		// minWidth : 520,
	 		setScroll : 'scroll',
        	source: function(term, response){
        		HELPER.ajax({
        			url : BASE_URL+'customer/get_sales',
        			data : {
        				customer_id : $('#pembayaran_piutang_customer_id').val(),
        				q 			: term
        			},
        			success : function(data) {
        				response(data);
        			}
        		})
	        },
	        renderItem: function (item, search){
	            // escape special characters
	            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
	            var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
	            // return '<div class="autocomplete-suggestion" style="overflow:initial;" data-val="' + item[0] + '" ><span class="list-complete" style="width:200px">' + item[0].replace(re, "<b>$1</b>") +' </span></div>';
	            return '<div class="autocomplete-suggestion" style="overflow:initial;" data-id="'+ item[1] +'" data-val="' + item[0] + '" ><span class="list-complete" style="width:200px">' + item[0].replace(re, "<b>$1</b>") +' </span></div>';
	        },
	        onSelect: function(e, term, item){
	        	// $('#pembayaran_piutang_sales_id').val(data)
	        }
	    });*/

	}

	function onPrint(param) {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'pembayaranpiutang/print_nota/' + param,
				type: 'get',
				success: function(res) {
					var data = JSON.parse(res);

					HELPER.toggleForm({
						tohide: 'table_data',
						toshow: 'cetak_data'
					})
					$("#pdf-laporan object").attr("data", data.record);
					HELPER.unblock();
				}
			})
		} else {
			HELPER.getDataFromTable({
				table: 'table-pembayaran',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'pembayaranpiutang/print_nota/' + data.pembayaran_piutang_id,
							type: 'get',
							success: function(res) {
								var data = JSON.parse(res);
								HELPER.toggleForm({
									tohide: 'table_data',
									toshow: 'cetak_data'
								})
								$("#pdf-laporan object").attr("data", data.record);
								HELPER.unblock();
							}
						})
					} else {
						HELPER.unblock();
					}
				}
			})
		}

	}

	function onPrintTT(param) {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'pembayaranpiutang/print_tanda_terima/',
				type: 'post',
				data: {
					pembayaran_piutang_id: param
				},
				success: function(res) {
					var data = JSON.parse(res);

					HELPER.toggleForm({
						tohide: 'form_data',
						toshow: 'cetak_data'
					})
					$("#pdf-laporan object").attr("data", data.record);
					HELPER.unblock();
				}
			})
		} else {
			HELPER.getDataFromTable({
				table: 'table-pembayaran',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'pembayaranpiutang/print_tanda_terima/',
							type: 'post',
							data: data,
							success: function(res) {
								var data = JSON.parse(res);

								HELPER.toggleForm({
									tohide: 'table_data',
									toshow: 'cetak_data'
								})
								$("#pdf-laporan object").attr("data", data.record);
								HELPER.unblock();
							}
						})
					} else {
						HELPER.unblock();
					}
				}
			})
		}

	}

	function getpenjualan() {
		id = $('#pembayaran_piutang_penjualan_id').val()
		$.post(BASE_URL + 'transaksipenjualan/read', {
			penjualan_id: id
		}, function(res) {
			$('#pembayaran_piutang_customer_id').val(res.penjualan_customer_id);
			$('#customer_telp').val(res.customer_telp);
			$('#customer_nama').val(res.customer_nama);
			$('#customer_alamat').val(res.customer_alamat);
			$('#penjualan_jatuh_tempo').val(res.penjualan_jatuh_tempo);
		})
	}

	function addThis(id, el) {
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/read',
			data: {
				penjualan_id: id
			},
			type: 'post',
			success: function(dt) {
				$(el).parent().html(`<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis('` + id + `',this)" >
                      <i class="la la-remove"></i> batal
                    </button>`);

				detail.push(dt.penjualan_id);
				beli = {
					pembayaran_piutang_detail_id: null,
					pembayaran_piutang_detail_penjualan_id: dt.penjualan_id,
					pembayaran_piutang_detail_tagihan: dt.penjualan_total_kredit,
					pembayaran_piutang_detail_jatuh_tempo: dt.penjualan_jatuh_tempo,
					penjualan_kode: dt.penjualan_kode,
					penjualan_tanggal: dt.penjualan_tanggal,
					penjualan_total_kredit: dt.penjualan_total_kredit,
					pembayaran_piutang_jatuh_tempo: dt.penjualan_jatuh_tempo,
					pembayaran_piutang_detail_retur: dt.penjualan_total_retur,
					penjualan_bayar_sisa: dt.penjualan_bayar_sisa,
					barang_nama: dt.barang_nama,
				}
				addBeli(beli);
				$('tr.no-list').remove();
				$('#table-detail_beli tfoot tr').removeAttr('style');
			}
		})
	}

	function delThis(id, el) {
		remRow(id);
		$(el).parent().html(`<button type="button" class="btn btn-primary btn-pill btn-sm" title="Edit" onclick="addThis('` + id + `',this)" >
                      <i class="la la-check-circle"></i> Pilih
                    </button>`);
	}

	function setBarang() {
		HELPER.ajaxCombo({
			el: '#pembayaran_piutang_detail_barang_id_' + row,
			url: BASE_URL + 'barang/select_ajax',
		});

		/*HELPER.setChangeCombo({
			el : 'pembayaran_piutang_detail_satuan_'+row,
            data : satuan,
            valueField : 'satuan_id',
            displayField : 'satuan_kode',
		});
		$('#pembayaran_piutang_detail_satuan_'+row).select2();*/
		$('input.number').number(true);
	}
	/*
		function setSatuan(row, res) {
			if(res){
				new_satuan = [];
				$.each(satuan, function(i, v) {
					$.each(res, function(k, t) {
						if(v.satuan_id == t) new_satuan.push(v);
					});
				})

				HELPER.setChangeCombo({
					el : 'pembayaran_piutang_detail_satuan_'+row,
		            data : new_satuan,
		            valueField : 'satuan_id',
		            displayField : 'satuan_kode',
				});
			}
			$('#pembayaran_piutang_detail_satuan_'+row).select2();
		}*/

	function getcustomer(argument) {
		$.post(BASE_URL + 'customer/read', {
			customer_id: $('#pembayaran_piutang_customer_id').val()
		}, function(res) {
			$('#customer_alamat').val(res.customer_alamat);
			$('#customer_telp').val(res.customer_telp);
		})
	}

	function init_table(argument) {
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()
		if ($.fn.DataTable.isDataTable('#table-pembayaran')) {
			$('#table-pembayaran').DataTable().destroy();
		}
		var table = $('#table-pembayaran').DataTable({
			responsive: true,
			pageLength: 50,
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
				url: BASE_URL + 'pembayaranpiutang/',
				type: 'POST',
				data: {
					tanggal1: awal,
					tanggal2: akhir,
				}
			},
			order: [
				[3, 'desc']
			],
			columnDefs: [
				/*{
					targets : 0,
					orderable : false
				},*/
				{
					targets: 3,
					render: function(data, type, row) {
						return moment(data).format("DD-MM-YYYY");
					}
				},
				{
					targets: 5,
					render: function(data, type, row) {
						return data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets: 6,
					render: function(data, type, row) {
						return data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets: 7,
					render: function(data, type, row) {
						return data.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					className: "details-control",
					targets: 1,
					"data": null,
					"defaultContent": ""
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-danger btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
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
		var detailRows = [];

		$('#table-pembayaran tbody').on('click', 'tr td.details-control', function() {
			var tr = $(this).closest('tr');
			var row = table.row(tr);
			var idx = $.inArray(tr.attr('id'), detailRows);

			if (row.child.isShown()) {
				tr.removeClass('details');
				row.child.hide();
				tr.addClass('tutup');
				tr.removeClass('shown');
				// Remove from the 'open' array
				detailRows.splice(idx, 1);
			} else {
				tr.addClass('details');
				row.child(format(row.data())).show();
				tr.addClass('shown');
				tr.removeClass('tutup');
				// Add to the 'open' array
				if (idx === -1) {
					detailRows.push(tr.attr('id'));
				}
			}
		});
		table.on('draw', function() {
			$.each(detailRows, function(i, id) {
				$('#' + id + ' td.details-control').trigger('click');
			});
		});
		$('#modal').modal('hide');
		var detailRows = [];
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow() {
		total_tagihan = total_bayar = total_potongan = total_retur = total_sisa = 0
		$('.beli').each(function(i, v) {
			nrow = $(v).data('id');
			tagihan = parseInt($('#pembayaran_piutang_detail_tagihan_' + nrow).val()) || 0;
			retur = parseInt($('#pembayaran_piutang_detail_retur_' + nrow).val()) || 0;
			potongan = parseInt($('#pembayaran_piutang_detail_potongan_' + nrow).val()) || 0;
			sisa = $('#pembayaran_piutang_detail_sisa_' + nrow).val();
			// $('#pembayaran_piutang_detail_sisa_' + nrow).val(sisa);
			// dt_bayar = $('#pembayaran_piutang_detail_bayar_'+nrow).data('edit');

			total_tagihan += tagihan;
			total_retur += retur;
			total_potongan += potongan;
			total_sisa += sisa;
		})
		$('#pembayaran_piutang_tagihan').val(total_tagihan);
		$('#pembayaran_piutang_retur').val(total_retur);
		$('#pembayaran_piutang_potongan').val(total_potongan);
		$('#pembayaran_piutang_sisa').val(total_sisa);
		countBayar()
	}

	function countBayar(argument) {
		total_bayar = 0;
		$('.beli').each(function(i, v) {
			nrow = $(v).data('id');
			total_bayar += parseInt($('#pembayaran_piutang_detail_bayar_' + nrow).val()) || 0;
		});
		$('#pembayaran_piutang_bayar').val(total_bayar);
		$('#totalbayar').val(total_bayar);
	}


	function remRow(id) {
		$('tr.beli_' + id).remove();
		detail = [];
		$('.beli').each(function(i, v) {
			detail.push($(v).data('id'))
		});

		if ($('#table-detail_beli tbody tr').length == 0) {
			$('#table-detail_beli tbody').append(`<tr class="no-list">
						<td colspan="9" class="text-center">Silahkan Pilih Data penjualan Barang</td>
					</tr>`);
			$('#table-detail_beli tfoot tr').css('display', 'none');
		}
		countRow(id);
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-pembayaran',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				getDetailBeli(res.pembayaran_piutang_id);
				getDetailPembayaran(res.pembayaran_piutang_id);
				$("#pembayaran_piutang_customer_id").select2("trigger", "select", {
					data: {
						id: res.pembayaran_piutang_customer_id,
						text: res.customer_kode + ' - ' + res.customer_nama
					}
				});
				$("#pembayaran_piutang_akun_id").select2("trigger", "select", {
					data: {
						id: res.pembayaran_piutang_akun_id,
						text: res.akun_kode + ' - ' + res.akun_nama
					}
				});
				if (res.pembayaran_piutang_status == 1) {
					$('#save_draft').attr('disabled', true)
				}

				$('#totalbayar').val($('#pembayaran_piutang_bayar').val());

				$('#totalbayar').val(res.pembayaran_piutang_bayar);
				$('#totalAppend').val(res.pembayaran_piutang_bayar);

				$('#table-detail_beli tfoot tr').removeAttr('style');
				onAdd()
			}
		})
	}

	function getDetailBeli(parent) {
		$.ajax({
			url: BASE_URL + 'pembayaranpiutang/get_detail',
			type: 'post',
			data: {
				pembayaran_piutang_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					addBeli(v);
				})
			}
		})
	}

	function addBeli(dt) {
		console.log('detect add beli');
		console.log(dt);

		id = dt.pembayaran_piutang_detail_penjualan_id;
		$('.no-list').remove();
		html = `<tr class="beli beli_` + id + `" data-id="` + id + `">
					<td scope="row">
						<input type="hidden" value="` + dt.pembayaran_piutang_detail_id + `" class="form-control" name="pembayaran_piutang_detail_id[` + id + `]" id="pembayaran_piutang_detail_id_` + id + `">						
						<input type="hidden" value="` + dt.pembayaran_piutang_detail_penjualan_id + `" class="form-control" name="pembayaran_piutang_detail_penjualan_id[` + id + `]" id="pembayaran_piutang_detail_penjualan_id_` + id + `">						
						<input class="form-control" type="text" value="` + dt.penjualan_kode + `" name="penjualan_kode[]" id="penjualan_kode_` + id + `" disabled="">	
									
					</td>
					<td>
						<input class="form-control" type="text" value="` + dt.barang_nama + `"  id="barang_nama_` + id + `" disabled="">	
					</td>
					<td><input type="date" disabled value="` + dt.penjualan_tanggal + `" class="form-control" name="penjualan_tanggal[` + id + `]" id="penjualan_tanggal_` + id + `" ></td>
					<td><input type="date" readonly value="` + dt.pembayaran_piutang_detail_jatuh_tempo + `" class="form-control" name="pembayaran_piutang_detail_jatuh_tempo[` + id + `]" id="pembayaran_piutang_detail_jatuh_tempo_` + id + `" ></td>
					<td><input class="form-control number" value="` + dt.pembayaran_piutang_detail_tagihan + `" type="text" readonly="" name="pembayaran_piutang_detail_tagihan[` + id + `]" id="pembayaran_piutang_detail_tagihan_` + id + `" onchange="countRow('` + id + `')" value="` + dt.pembayaran_piutang_detail_tagihan + `"></td>
					<td><input class="form-control number" value="` + dt.pembayaran_piutang_detail_retur + `" type="text" readonly="" name="pembayaran_piutang_detail_retur[` + id + `]" id="pembayaran_piutang_detail_retur_` + id + `" onkeyup="countRow('` + id + `')" value="` + dt.pembayaran_piutang_detail_retur + `" ></td>
					<td><input class="form-control number" value="` + dt.pembayaran_piutang_detail_potongan + `" type="text" readonly="" name="pembayaran_piutang_detail_potongan[` + id + `]" id="pembayaran_piutang_detail_potongan_` + id + `" onkeyup="countRow('` + id + `')" value="` + dt.pembayaran_piutang_detail_potongan + `"></td>
					<td><input class="form-control number" value="` + dt.penjualan_bayar_sisa + `" type="text" name="pembayaran_piutang_detail_sisa[` + id + `]" id="pembayaran_piutang_detail_sisa_` + id + `" value="` + dt.penjualan_bayar_sisa + `" readonly></td>
					<td>
						<input class="form-control number" value="` + dt.pembayaran_piutang_detail_bayar + `" type="text" name="pembayaran_piutang_detail_bayar[` + id + `]" id="pembayaran_piutang_detail_bayar_` + id + `" onkeyup="countBayar('` + id + `')" data-edit="false">
						<input class="form-control number" value="` + dt.pembayaran_piutang_detail_bayar + `" type="hidden" name="pembayaran_piutang_detail_bayar_last[` + id + `]" id="pembayaran_piutang_detail_bayar_last_` + id + `" onkeyup="countRow('` + id + `')">
					</td>
					<td><a href="javascript:;" data-id="` + id + `" class="btn btn-light-warning btn-sm" onclick="remRow('` + id + `')" title="Hapus" >
                  		<span class="la la-trash"></span></a></td>
				</tr>`;
		$('#table-detail_beli').append(html);
		// $('#table-detail_beli').append(`<h1>test</h1>`);
		$('#table-detail_beli tfoot').show()
		$('input.number').number(true);
		countRow();
		countBayar();
	}


	function getDetailPembayaran(parent) {
		let result;
		$.ajax({
			url: BASE_URL + 'pembayaranpiutang/get_detail_pembayaran',
			type: 'post',
			data: {
				pembayaran_piutang_detail_pembayaran_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					if (i > 0) {
						addPembayaranEdit(rowPembayaran);
					}
					$('#pembayaran_piutang_detail_pembayaran_id_' + rowPembayaran).val(v.pembayaran_piutang_detail_pembayaran_id);
					$('#pembayaran_piutang_detail_pembayaran_total_' + rowPembayaran).val(v.pembayaran_piutang_detail_pembayaran_total);
					$('#pembayaran_piutang_detail_pembayaran_cara_bayar_' + rowPembayaran).val(v.pembayaran_piutang_detail_pembayaran_cara_bayar);
					rowPembayaran++;
				})
			}
		})
	}

	function onBack() {
		// row = 1;
		// rowPembayaran = 1;
		// resetBarang();
		// resetPembayaran();
		// onRefresh();
		// HELPER.toggleForm({
		// 	tohide: 'cetak_data',
		// 	toshow: 'table_data'
		// })
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-pembayaran'
		})
	}

	function save(form, tipe) {
		let totalBayar = $('#totalAppend').val();
		let grandTotal = $('#totalbayar').val();

		if (totalBayar < grandTotal) {
			swal.fire('Informasi', 'Jumlah bayar kurang dari nota! Masukkan jumlah bayar sesuai dengan total nota!', 'warning');
			return;
		} else if (totalBayar > grandTotal) {
			swal.fire('Informasi', 'Jumlah bayar lebih dari nota! Masukkan jumlah bayar sesuai dengan total nota!', 'warning');
			return;
		}


		next = true;
		if (tipe == 0) {
			HELPER.confirm({
				message: 'Save as draft ?',
				callback: function(res) {
					console.log(res)
					if (res) {
						next = false;
						saving(tipe);
					}
				}
			})
		} else {
			saving(tipe);
		}
	}

	function saving(tipe) {
		HELPER.save({
			form: 'form-pembayaran',
			data: {
				pembayaran_piutang_status: tipe
			},
			confirm: (tipe == '1' ? true : false),
			callback: function(success, id, record, message) {
				var cetak = $('#cetak_checkbox');
				if (success === true) {
					if (cetak.is(":checked")) {
						if (tipe == '1') onPrint(id)
						else onPrintTT(id);
					} else {
						HELPER.back({});
					}
				}
				if (success === true) {
					onBack();
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-pembayaran',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function format(d) {
		var data = $.parseJSON(atob($($(d[0])[2]).data('record')));
		$.ajax({
			url: BASE_URL + "pembayaranpiutang/loaddetail",
			type: "POST",
			data: {
				pembayaran_piutang_detail_parent: data.pembayaran_piutang_id,
			},
			success: function(response) {
				var hasil = $.parseJSON(response);
				$("#hasil_load_detail").empty();
				$("#hasil_load_detail").append(hasil.html);
			}
		});
		return '<div id="hasil_load_detail" style="margin-left: -15px;padding-right: 30px;padding-left: 50px;"></div>';
	}

	function onDelete(pembayaran_piutang_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'pembayaranpiutang/destroy',
						data: {
							id: pembayaran_piutang_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-pembayaran'
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

	function resetBarang() {
		$('#tbody-barang').empty();
		$('#tbody-barang').append(`
			<tr class="no-list">
				<td colspan="8" class="text-center">Silahkan Pilih Faktur penjualan Barang</td>
			</tr>
		`);
		$('#table-detail_beli tfoot tr').css('display', 'none');
	}

	function resetPembayaran() {
		$('#tbody-pembayaran').empty();
		$('#tbody-pembayaran').append(`
		<tr class="pembayaran_piutang_1">
			<td scope="row">
				<input type="hidden" class="form-control" name="order_detail_pembayaran_piutang_id[1]" id="order_detail_pembayaran_piutang_id_1">
				<input type="date" class="form-control" name="order_detail_pembayaran_piutang_tanggal[1]" id="order_detail_pembayaran_piutang_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">

			</td>
			<td>
				<select class="form-control caraBayar" name="order_detail_pembayaran_piutang_cara_bayar[1]" id="order_detail_pembayaran_piutang_cara_bayar_1" style="width: 100%" onchange="setBayar()">
					<option value="">-Pilih Cara Bayar-</option>
					<option value="Transfer Bank">Transfer Bank</option>
					<option value="Cash">Cash</option>
				</select>
			</td>
			<td>
				<input class="form-control number jumlahNow" type="text" name="order_detail_pembayaran_piutang_total[1]" id="order_detail_pembayaran_piutang_total_1">
			</td>
			<td><a href="javascript:;" data-id="1" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, 1)" title="Hapus">
					<span class="la la-trash"></span> Hapus</a></td>
		</tr>
		`);
	}
</script>