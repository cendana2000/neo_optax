<script type="text/javascript">
	$(function() {
		row = 1;
		rowPembayaran = 1;
		satuan = barang = detail = [];
		produkSelect = {};
		HELPER.fields = [
			'pembayaran_id',
			'pembayaran_kode',
			'pembayaran_tanggal',
			'pembayaran_supplier_id',
			'pembayaran_referensi',
			'pembayaran_keterangan',
			'pembayaran_tagihan',
			'pembayaran_bayar',
			'pembayaran_sisa',
			'pembayaran_akun_id',
			'pembayaran_status',
			'pembayaran_invoice',
			'pembayaran_tanggal_invoice',
			'pembayaran_sales',
		];
		HELPER.setRequired([
			'pembayaran_supplier_id',
			'pembayaran_akun_id',
			'pembayaran_tanggal',
		]);
		HELPER.api = {
			table: BASE_URL + 'pembayaran/',
			read: BASE_URL + 'pembayaran/read',
			store: BASE_URL + 'pembayaran/store',
			update: BASE_URL + 'pembayaran/update',
			destroy: BASE_URL + 'pembayaran/destroy',
		}

		$('input.number').number(true);

		HELPER.ajaxCombo({
			el: '#pembayaran_supplier_id',
			url: BASE_URL + 'supplier/select_ajax',
		});

		HELPER.ajaxCombo({
			el: '#pembayaran_akun_id',
			data: {
				akun_unit: 1
			},
			url: BASE_URL + 'akun/select_ajax',
		});

		/*HELPER.create_combo_akun({
			el : 'pembayaran_akun_id',
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
		$('#table-opsi-pembayaran').hide();
		$('#btnPembayaran').hide();
		loadTable();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
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
						return full['pembayaran_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembayaran_tanggal']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						// return 'Rp.' + $.number(full['pembayaran_tagihan']);
						return 'Rp.' + $.number(full['pembayaran_sisa']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['pembayaran_bayar']);
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" title="Print" onclick="onPrintTT('${full['pembayaran_id']}')" >
							<span class="svg-icon svg-icon-md">
								<i class="la la-print"></i>
							</span>
            </a>	
						<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
						</a>
						<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" title="Delete" onclick="onDelete('${full['pembayaran_id']}')">
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

						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="coba(${full['pembayaran_id']})"">
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

	function loadTable2() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()

		HELPER.initTable({
			el: "table-pembayaran",
			url: BASE_URL + 'pembayaran/index2',
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
						return full['pembayaran_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembayaran_tanggal']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['pembayaran_tagihan'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['pembayaran_bayar'];
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
						<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" title="Delete" onclick="onDelete('${full['pembayaran_id']}')">
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

						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="coba(${full['pembayaran_id']})"">
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
		jt = $('#pembelian_jatuh_tempo_hari').val();
		jt_tempo = moment();
		$('#pembelian_jatuh_tempo').val(jt_tempo.add((jt), 'd').format('YYYY-MM-DD'));
	}

	function jenisBayar() {
		let choose = $('#pembelian_bayar_opsi').val();
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

		html = `<tr class="pembayaran_` + rowPembayaran + `">
					<td scope="rowPembayaran">
						<input type="hidden" class="form-control" name="pembayaran_detail_pembayaran_id[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_id_` + rowPembayaran + `">
						<input type="date" class="form-control" name="pembayaran_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="pembayaran_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
					</td>
					<td>
						<select class="form-control" name="pembayaran_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
							<option value="">-Pilih Cara Bayar-</option>
							<option value="Transfer Bank">Transfer Bank</option>
							<option value="Cash">Cash</option>
						</select>
					</td>
					<td>
						<input class="form-control number jumlahNow" type="text" name="pembayaran_detail_pembayaran_total[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_total_` + rowPembayaran + `">
					</td>
					<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
							<span class="la la-trash"></span> </a></td>
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

		html = `<tr class="pembayaran_` + rowPembayaran + `">
			<td scope="rowPembayaran">
				<input type="hidden" class="form-control" name="pembayaran_detail_pembayaran_id[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_id_` + rowPembayaran + `">
				<input type="date" class="form-control" name="pembayaran_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="pembayaran_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
			</td>
			<td>
				<select class="form-control" name="pembayaran_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
					<option value="">-Pilih Cara Bayar-</option>
					<option value="Transfer Bank">Transfer Bank</option>
					<option value="Cash">Cash</option>
				</select>
			</td>
			<td>
				<input class="form-control number jumlahNow" type="text" name="pembayaran_detail_pembayaran_total[` + rowPembayaran + `]" id="pembayaran_detail_pembayaran_total_` + rowPembayaran + `">
			</td>
			<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
					<span class="la la-trash"></span> </a></td>
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
			// 	$("#pembayaran_detail_pembayaran_total_" + rowPembayaran).val(0);
			// } else {
			// }
			total += sum;

			$('#totalAppend').val(total);
		});
	}

	function setBayar() {
		let totalBayar = $('#totalbayar').val();
		let totalSatuan = $('[id^=pembayaran_detail_pembayaran_total_]');

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
		// 	$("#pembayaran_detail_pembayaran_total_" + rowPembayaran).val(0);
		// } else {
		// 	total += sum;
		// }

		total += sum;

		$('#totalAppend').val(total);
	});

	function remRowPembayaran(el, cRow) {
		let cTotal = $('#totalAppend').val();
		let pengurang = $('#pembayaran_detail_pembayaran_total_' + cRow).val();
		let cBayar = cTotal - pengurang;

		$('#totalAppend').val(cBayar);


		id = $(el).data('id');
		$('tr.pembayaran_' + id).remove();
		countRow(id);
	}

	// =================================================================================================
	function listBeli() {
		var tabledetailtr = $("#table-detail_beli tbody tr")
		var barangids = [];
		tabledetailtr.each((key, val) => {
			barangids.push($(val).data("id"));
		});

		$('#daftar_beli').modal();
		HELPER.initTable({
			el: "list_beli",
			url: BASE_URL + 'transaksipembelian/table_faktur',
			data: {
				pembelian_supplier_id: $('#pembayaran_supplier_id').val(),
			},
			searchAble: true,
			destroyAble: true,
			responsive: true,
			columnDefs: [{
					targets: 0,
					render: function(data, type, full, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					},
				},

				{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['pembelian_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['pembelian_faktur'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembelian_jpembelian_tanggalatuh_tempo']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return moment(full['pembelian_jatuh_tempo']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						// return 'Rp.' + $.number(parseInt(full['pembelian_bayar_grand_total']) + parseInt(full['pembelian_retur']));
						return 'Rp.' + $.number(parseInt(full['pembelian_bayar_grand_total']));
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['pembelian_retur']);
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['pembelian_bayar_jumlah']);
					},
				},
				{
					targets: 8,
					render: function(data, type, full, meta) {
						// return 'Rp.' + $.number(full['pembelian_bayar_grand_total'] - full['pembelian_bayar_jumlah']);
						return 'Rp.' + $.number(full['pembelian_bayar_sisa']);
					},
				},
				{
					targets: 9,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						console.log(full);
						console.log("===================================")
						add = true;
						var isExist = barangids.includes(full.pembelian_id);
						if (isExist) {
							add = false;
						}
						$.each(detail, function(i, v) {
							if (data == v) add = false;
						})
						if (add) {
							aksi = `<button type="button" class="btn btn-primary btn-pill btn-sm" title="Pilih" onclick="addThis('${full['pembelian_id']}',this)" >
								<i class="la la-check-circle"></i> Pilih
							</button>`;
						} else {
							aksi = `<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis('${full['pembelian_id']}',this)" >
								<i class="la la-remove"></i> batal
							</button>`;
						}
						return aksi;
					},
				},

			],
		});

	}


	function getSales() {

		/*$('#pembayaran_sales').autoComplete({
	 		minChars : 0,
	 		// minWidth : 520,
	 		setScroll : 'scroll',
        	source: function(term, response){
        		HELPER.ajax({
        			url : BASE_URL+'supplier/get_sales',
        			data : {
        				supplier_id : $('#pembayaran_supplier_id').val(),
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
	        	// $('#pembayaran_sales_id').val(data)
	        }
	    });*/

	}

	function onPrint(param) {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'pembayaran/print_nota/' + param,
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
							url: BASE_URL + 'pembayaran/print_nota/' + data.pembayaran_id,
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
				url: BASE_URL + 'pembayaran/print_tanda_terima/',
				type: 'post',
				data: {
					pembayaran_id: param
				},
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
							url: BASE_URL + 'pembayaran/print_tanda_terima/',
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

	function getPembelian() {
		id = $('#pembayaran_pembelian_id').val()
		$.post(BASE_URL + 'transaksipembelian/read', {
			pembelian_id: id
		}, function(res) {
			$('#pembayaran_supplier_id').val(res.pembelian_supplier_id);
			$('#supplier_telp').val(res.supplier_telp);
			$('#supplier_nama').val(res.supplier_nama);
			$('#supplier_alamat').val(res.supplier_alamat);
			$('#pembelian_jatuh_tempo').val(res.pembelian_jatuh_tempo);
		})
	}

	function addThis(id, el) {
		$.ajax({
			url: BASE_URL + 'transaksipembelian/read',
			data: {
				pembelian_id: id
			},
			type: 'post',
			success: function(dt) {
				$(el).parent().html(`<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis('` + id + `',this)" >
                      <i class="la la-remove"></i> batal
                    </button>`);

				detail.push(dt.pembelian_id);
				console.log(dt);
				beli = {
					pembayaran_detail_id: null,
					pembayaran_detail_pembelian_id: dt.pembelian_id,
					// pembayaran_detail_tagihan: dt.pembelian_bayar_sisa,
					// pembayaran_detail_tagihan: parseInt(dt.pembelian_bayar_grand_total) + parseInt(dt.pembelian_retur),
					pembayaran_detail_tagihan: dt.pembelian_bayar_grand_total, 
					pembayaran_detail_jatuh_tempo: dt.pembelian_jatuh_tempo,
					pembelian_kode: dt.pembelian_kode,
					pembelian_tanggal: dt.pembelian_tanggal,
					pembelian_referensi: dt.pembelian_referensi,
					pembayaran_jatuh_tempo: dt.pembelian_jatuh_tempo,
					pembayaran_detail_retur: dt.pembelian_retur,
					pembayaran_detail_sisa: dt.pembelian_bayar_sisa,
					pembayaran_detail_bayar: dt.pembelian_bayar_sisa,

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
			el: '#pembayaran_detail_barang_id_' + row,
			url: BASE_URL + 'barang/select_ajax',
		});

		/*HELPER.setChangeCombo({
			el : 'pembayaran_detail_satuan_'+row,
            data : satuan,
            valueField : 'satuan_id',
            displayField : 'satuan_kode',
		});
		$('#pembayaran_detail_satuan_'+row).select2();*/
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
					el : 'pembayaran_detail_satuan_'+row,
		            data : new_satuan,
		            valueField : 'satuan_id',
		            displayField : 'satuan_kode',
				});
			}
			$('#pembayaran_detail_satuan_'+row).select2();
		}*/

	function getSupplier(argument) {
		$.post(BASE_URL + 'supplier/read', {
			supplier_id: $('#pembayaran_supplier_id').val()
		}, function(res) {
			$('#supplier_alamat').val(res.supplier_alamat);
			$('#supplier_telp').val(res.supplier_telp);
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
				url: BASE_URL + 'pembayaran/',
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
                          <span class="la la-trash"></span> 
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

	function checkMaxBayar(id) {
		let cPembayaran_detail_tagihan = parseInt($('#pembayaran_detail_tagihan_' + id).val());
		let cPembayaran_detail_bayar = parseInt($('#pembayaran_detail_bayar_' + id).val());


		console.log('tagihan :' + cPembayaran_detail_tagihan);
		console.log('bayar :' + cPembayaran_detail_bayar);

		if (cPembayaran_detail_bayar > cPembayaran_detail_tagihan) {
			Swal.fire('Peringatan', 'Pembayaran tidak boleh melebihi banyak hutang', 'warning');
			$('#pembayaran_detail_bayar_' + id).val(cPembayaran_detail_tagihan);
		}
	}

	function countRow() {
		total_tagihan = total_bayar = total_potongan = total_retur = total_sisa = 0
		$('.beli').each(function(i, v) {
			nrow = $(v).data('id');
			checkMaxBayar(id);
			tagihan = parseInt($('#pembayaran_detail_tagihan_' + nrow).val()) || 0;
			retur = parseInt($('#pembayaran_detail_retur_' + nrow).val()) || 0;
			potongan = parseInt($('#pembayaran_detail_potongan_' + nrow).val()) || 0;
			sisa = tagihan - retur - potongan;
			// EDIT
			// if($('#pembayaran_id').val()){
			// 	$('#pembayaran_detail_sisa_' + nrow).val(sisa);
			// 	if($(`#pembayaran_detail_bayar_${nrow}`).val() == "0"){
			// 		$(`#pembayaran_detail_bayar_${nrow}`).val(sisa)
			// 	}
			// }
			// dt_bayar = $('#pembayaran_detail_bayar_'+nrow).data('edit');

			total_tagihan += tagihan;
			total_retur += retur;
			total_potongan += potongan;
			// total_sisa += sisa;
			total_sisa += parseInt($('#pembayaran_detail_sisa_' + nrow).val()) || 0;
		})
		$('#pembayaran_tagihan').val(total_tagihan);
		$('#pembayaran_retur').val(total_retur);
		$('#pembayaran_potongan').val(total_potongan);
		$('#pembayaran_sisa').val(total_sisa);
		countBayar()
	}

	function countBayar(argument) {
		total_bayar = 0;
		$('.beli').each(function(i, v) {
			nrow = $(v).data('id');
			checkMaxBayar(nrow);
			total_bayar += parseInt($('#pembayaran_detail_bayar_' + nrow).val()) || 0;
		});
		$('#pembayaran_bayar').val(total_bayar);
		$('#totalbayar').val(total_bayar);
	}

	function addBeli(dt) {
		id = dt.pembayaran_detail_pembelian_id;
		$('.no-list').remove();
		html = `<tr class="beli beli_` + id + `" data-id="` + id + `">
					<td scope="row">
						<input type="hidden" value="` + dt.pembayaran_detail_id + `" class="form-control" name="pembayaran_detail_id[` + id + `]" id="pembayaran_detail_id_` + id + `">						
						<input type="hidden" value="` + dt.pembayaran_detail_pembelian_id + `" class="form-control" name="pembayaran_detail_pembelian_id[` + id + `]" id="pembayaran_detail_pembelian_id_` + id + `">						
						<input class="form-control" type="text" value="` + dt.pembelian_kode + `" name="pembelian_kode[${id}]" id="pembelian_kode_` + id + `" readonly="">					
					</td>
					<td><input type="date" disabled value="` + dt.pembelian_tanggal + `" class="form-control" name="pembelian_tanggal[` + id + `]" id="pembelian_tanggal_` + id + `" ></td>
					<td><input type="date" readonly value="` + dt.pembayaran_detail_jatuh_tempo + `" class="form-control" name="pembayaran_detail_jatuh_tempo[` + id + `]" id="pembayaran_detail_jatuh_tempo_` + id + `" ></td>
					<td><input class="form-control number" value="` + dt.pembayaran_detail_tagihan + `" type="text" readonly="" name="pembayaran_detail_tagihan[` + id + `]" id="pembayaran_detail_tagihan_` + id + `" onchange="countRow('` + id + `')" value="` + dt.pembayaran_detail_tagihan + `"></td>
					<td><input class="form-control number" value="` + dt.pembayaran_detail_retur + `" type="text" readonly="" name="pembayaran_detail_retur[` + id + `]" id="pembayaran_detail_retur_` + id + `" onkeyup="countRow('` + id + `')" value="` + dt.pembayaran_detail_retur + `" ></td>
					<td><input class="form-control number" value="` + dt.pembayaran_detail_potongan + `" type="text" readonly="" name="pembayaran_detail_potongan[` + id + `]" id="pembayaran_detail_potongan_` + id + `" onkeyup="countRow('` + id + `')" value="` + dt.pembayaran_detail_potongan + `"></td>
					<td><input class="form-control number" value="` + dt.pembayaran_detail_sisa + `" type="text" name="pembayaran_detail_sisa[` + id + `]" id="pembayaran_detail_sisa_` + id + `" value="` + dt.pembayaran_detail_sisa + `" readonly></td>
					<td>
						<input class="form-control number" value="` + dt.pembayaran_detail_bayar + `" type="text" name="pembayaran_detail_bayar[` + id + `]" id="pembayaran_detail_bayar_` + id + `" onkeyup="countBayar('` + id + `')" data-edit="false">
						<input class="form-control number" value="` + dt.pembayaran_detail_bayar + `" type="hidden" name="pembayaran_detail_bayar_last[` + id + `]" id="pembayaran_detail_bayar_last_` + id + `" onkeyup="countRow('` + id + `')">
					</td>
					<td><a href="javascript:;" data-id="` + id + `" class="btn btn-light-warning btn-sm" onclick="remRow('` + id + `')" title="Hapus" >
                  		<span class="la la-trash"></span></a></td>
				</tr>`;
		$('#table-detail_beli').append(html);
		$('#table-detail_beli tfoot').show()
		$('input.number').number(true);
		countRow();
		if(dt.pembayaran_detail_bayar){
			$(`#pembayaran_detail_bayar_${id}`).val(dt.pembayaran_detail_bayar);
		}
		countBayar();
	}

	function remRow(id) {
		$('tr.beli_' + id).remove();
		detail = [];
		$('.beli').each(function(i, v) {
			detail.push($(v).data('id'))
		});

		if ($('#table-detail_beli tbody tr').length == 0) {
			$('#table-detail_beli tbody').append(`<tr class="no-list">
						<td colspan="7" class="text-center">Silahkan Pilih Data Pembelian Barang</td>
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
				getDetailBeli(res.pembayaran_id);
				getDetailPembayaran(res.pembayaran_id);
				$("#pembayaran_supplier_id").select2("trigger", "select", {
					data: {
						id: res.pembayaran_supplier_id,
						text: res.supplier_kode + ' - ' + res.supplier_nama
					}
				});
				$("#pembayaran_akun_id").select2("trigger", "select", {
					data: {
						id: res.pembayaran_akun_id,
						text: res.akun_kode + ' - ' + res.akun_nama
					}
				});
				if (res.pembayaran_status == 1) {
					$('#save_draft').attr('disabled', true)
				}
				
				$('#totalbayar').val($('#pembayaran_bayar').val());

				$('#totalbayar').val(res.pembayaran_bayar);
				$('#totalAppend').val(res.pembayaran_bayar);

				$('#table-detail_beli tfoot tr').removeAttr('style');
				onAdd()
			}
		})
	}

	function getDetailBeli(parent) {
		$.ajax({
			url: BASE_URL + 'pembayaran/get_detail',
			type: 'post',
			data: {
				pembayaran_detail_parent: parent
			},
			success: function(res) {
				// console.log(res)
				$.each(res.data, function(i, v) {
					addBeli(v);
				})
			}
		})
	}

	function getDetailPembayaran(parent) {
		let result;
		$.ajax({
			url: BASE_URL + 'pembayaran/get_detail_pembayaran',
			type: 'post',
			data: {
				pembayaran_detail_pembayaran_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					if (i > 0) {
						addPembayaranEdit(rowPembayaran);
					}
					$('#pembayaran_detail_pembayaran_id_' + rowPembayaran).val(v.pembayaran_detail_pembayaran_id);
					$('#pembayaran_detail_pembayaran_total_' + rowPembayaran).val(v.pembayaran_detail_pembayaran_total);
					$('#pembayaran_detail_pembayaran_cara_bayar_' + rowPembayaran).val(v.pembayaran_detail_pembayaran_cara_bayar);
					rowPembayaran++;
				})
			}
		})
	}

	function onBack() {
		row = 1;
		rowPembayaran = 1;
		resetBarang();
		resetPembayaran();
		onRefresh();
		HELPER.toggleForm({
			tohide: 'cetak_data',
			toshow: 'table_data'
		})
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
				pembayaran_status: tipe
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
			url: BASE_URL + "pembayaran/loaddetail",
			type: "POST",
			data: {
				pembayaran_detail_parent: data.pembayaran_id,
			},
			success: function(response) {
				var hasil = $.parseJSON(response);
				$("#hasil_load_detail").empty();
				$("#hasil_load_detail").append(hasil.html);
			}
		});
		return '<div id="hasil_load_detail" style="margin-left: -15px;padding-right: 30px;padding-left: 50px;"></div>';
	}

	function onDelete(pembayaran_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'pembayaran/batalBayar',
						data: {
							id: pembayaran_id
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
				<td colspan="8" class="text-center">Silahkan Pilih Faktur Pembelian Barang</td>
			</tr>
		`);
		$('#table-detail_beli tfoot tr').css('display', 'none');
	}

	function resetPembayaran() {
		$('#tbody-pembayaran').empty();
		$('#tbody-pembayaran').append(`
		<tr class="pembayaran_1">
			<td scope="row">
				<input type="hidden" class="form-control" name="order_detail_pembayaran_id[1]" id="order_detail_pembayaran_id_1">
				<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[1]" id="order_detail_pembayaran_tanggal_1" value="<?= date('Y-m-d'); ?>" style="width: 100%;">

			</td>
			<td>
				<select class="form-control caraBayar" name="order_detail_pembayaran_cara_bayar[1]" id="order_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="setBayar()">
					<option value="">-Pilih Cara Bayar-</option>
					<option value="Transfer Bank">Transfer Bank</option>
					<option value="Cash">Cash</option>
				</select>
			</td>
			<td>
				<input class="form-control number jumlahNow" type="text" name="order_detail_pembayaran_total[1]" id="order_detail_pembayaran_total_1">
			</td>
			<td><a href="javascript:;" data-id="1" class="btn btn-light-warning btn-sm" onclick="remRowPembayaran(this, 1)" title="Hapus">
					<span class="la la-trash"></span> </a></td>
		</tr>
		`);
	}
</script>