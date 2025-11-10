<script type="text/javascript">
	let idBayar = [];
	let totalBayar = [];
	let totalQty = [];
	let totalItem = 0;
	let setRequired = [
		'penjualan_metode',
		'penjualan_total_bayar',
	];

	$(function() {
		// Disable behaviour of button inside form
		$(':button').click(function() {
			event.preventDefault();
		});

		let saldo_voucher;
		$('#penjualan_total_potongan_persen').number(true);
		$('#penjualan_pajak_persen').number(true);
		$('#penjualan_total_bayar').number(true);

		$('#divBank').hide();

		// vjasa = <?php echo $this->config->item('base_jasa_pinjaman') ?>;
		$('.barang_jual').css('max-height', screen.height - 368);
		/*$('#table-detail_barang').arrowTable({
		  // enabledKeys: ['left', 'right', 'up', 'down'],
		  // listenTarget:'input'
		});*/
		$('#pengajuan_jasa').val("<?php echo $this->config->item('base_jasa_pinjaman'); ?>");
		$('#kt_dashboard_daterangepicker_date').html(moment('<?php echo $this->config->item('base_penjualan_tanggal') ?>').format('MMM D'))
		// $('#kt_dashboard_daterangepicker_date').html(moment().format('MMM D'))		
		arrows = {
			leftArrow: '<i class="la la-angle-right"></i>',
			rightArrow: '<i class="la la-angle-left"></i>'
		}
		$('#kt_dashboard_daterangepicker').datepicker({
			rtl: KTUtil.isRTL(),
			todayBtn: "linked",
			autoclose: true,
			clearBtn: true,
			todayHighlight: true,
			templates: arrows
		});
		row = 0;
		satuan = barang = [];
		HELPER.fields = [
			'penjualan_id',
			'penjualan_tanggal',
			'penjualan_kode',
			'penjualan_anggota_id',
			'penjualan_total_item',
			'penjualan_total_qty',
			'penjualan_total_harga',
			'penjualan_total_grand',
			'penjualan_total_bayar',
			'penjualan_total_bayar_tunai',
			'penjualan_total_bayar_voucher',
			'penjualan_total_bayar_voucher_khusus',
			'penjualan_total_potongan',
			'penjualan_total_potongan_persen',
			'penjualan_total_kembalian',
			'penjualan_total_kredit',
			'penjualan_total_cicilan',
			'penjualan_jatuh_tempo',
			'penjualan_keterangan',
			'penjualan_bank',
			'pos_penjualan_customer_id',
			'penjualan_pajak_persen',
			'penjualan_total_potongan_persen',
			'penjualan_metode',
		];

		HELPER.setRequired([
			'penjualan_bank',
			'penjualan_metode',
			'penjualan_total_bayar'
		]);


		HELPER.api = {
			table: BASE_URL + 'transaksipenjualan/',
			read: BASE_URL + 'transaksipenjualan/edit_detail',
			store: BASE_URL + 'transaksipenjualan/store',
			update: BASE_URL + 'transaksipenjualan/update',
			destroy: BASE_URL + 'transaksipenjualan/destroy',
			get_parent: BASE_URL + 'kelompokbarang/go_tree',
		}
		$('input.number').number(true);
		$('.disc').number(true, 2);

		HELPER.ajaxCombo({
			el: '#penjualan_anggota_id',
			url: BASE_URL + 'transaksipenjualan/select_ajax',
			placeholder: 'nasabah',
			clear: false
		});

		HELPER.createCombo({
			el: 'pos_penjualan_customer_id',
			valueField: 'customer_id',
			displayField: 'customer_nama',
			url: BASE_URL + 'customer/select',
			callback: function(res) {
				$('#pos_penjualan_customer_id').select();
			}
		})

		$.post(BASE_URL + 'satuan/select', function(res) {
			satuan = res.data;
			setBarang();
		})
		// $('#penjualan_bayar_opsi').select2();
		$('form input, form select, body').keydown(function(e) {
			if (e.keyCode == 35) { //end
				e.preventDefault();
				$('#penjualan_anggota_id').select2('close');
				$('#penjualan_detail_barang_id_' + row).select2('close');
				$('#penjualan_total_bayar_tunai').trigger('focus');
				return false;
			}
			if (e.keyCode == 113) {
				e.preventDefault();
				$('#barang').trigger('focus');
				$('#penjualan_anggota_id').select2('close');
				return false;
			}
			if (e.keyCode == 115) { //f4
				e.preventDefault();
				$('#penjualan_detail_barang_id_' + row).select2('close');
				$('#penjualan_anggota_id').select2('close');
				cariBarang()
				return false;
			}
			if (e.keyCode == 118) { //f7
				e.preventDefault();
				$('#penjualan_detail_barang_id_' + row).select2('close');
				$('#penjualan_anggota_id').select2('open');
				return false;
			}
			if (e.keyCode == 120) { //f9
				e.preventDefault();
				$('#penjualan_detail_barang_id_' + row).select2('close');
				$('#penjualan_anggota_id').select2('close');
				// $('#barang').trigger('focus');
				$('#penjualan_total_potongan_persen').trigger('focus');
				return false;
			}
			if (e.keyCode == 66 && e.ctrlKey) {
				e.preventDefault();
				// $('#penjualan_kode').trigger('focus');
				$('#penjualan_anggota_id').select2('close');
				$('#penjualan_detail_barang_id_' + row).select2('open');
				return false;
			}
		});
		$(".use_barcode").keypress(function(event) {
			if (event.which == '10' || event.which == '13') {
				getBarang($('.use_barcode').val());
				$('#barang').trigger('focus');
				event.preventDefault();
			}
		});
		$("#kode_anggota").keypress(function(event) {
			if (event.which == '13') {
				event.preventDefault();
				HELPER.block();
				$.post(BASE_URL + 'anggota/read', {
					anggota_id: $('#kode_anggota').val()
				}, function(res) {
					if (res.success == false) {
						HELPER.showMessage({
							success: false,
							message: 'Kode anggota tidak ditemukan!',
							title: 'Informasi',
						})
					} else {
						$("#penjualan_anggota_id").select2("trigger", "select", {
							data: {
								id: res.anggota_id,
								text: res.anggota_kode + ' - ' + res.anggota_nama + ' - ' + (res.anggota_alamat).substring(0, 10)
							}
						});
						getNasabah();
					}
					HELPER.unblock();
				})
			}
			setTimeout(() => {
				go_full_screen();
			}, 3000);
		});

		HELPER.createCombo({
			el: 'penjualan_bank_id',
			valueField: 'akun_id',
			displayField: 'akun_nama',
			url: BASE_URL + 'akun/select_bank',
			callback: function(res) {
				$('#penjualan_bank_id').select2();
			}
		});

		$('#qty').trigger('focus');
		var myVar = setInterval(myTimer, 1000);
		st_edit = false;
		vdt = {};
		setDate();
		// hitungPesanan();
		$('#divBank').hide();
		loadMenu();
	});

	//==================================================================================================
	function initMenu(param) {
		if (param.stok_now == null || param.stok_now == '') {
			param.stok_now = param.stok_clean;
		}

		if (param.stok_clean == null || param.stok_clean == '') {
			param.stok_now = 0;
		}

		const html = `
						<div class="col-md-3 mt-2 p-1">
							<div class="card cardCustom" style="height:100px">
								<div class="m-4" style="height:100% !important;">
									<div class="row" style="height:90% !important;">
										<div class="col-8" style="font-size:13px">
										${param.barang_nama.toUpperCase()}
										</div>
										<div class="col-4">
										<button onclick="initOrder('${param.id}', '${param.barang_nama}', '${param.barang_harga}', ${param.stok_now});" class="btn btn-icon btn-circle" style="background-color: white; color:var(--color1); "><i class="fas fa-plus"></i></button>
										</div>
										</div>
										<div class="row pb-3">
											<div class="col-12">
												<span style="font-size:11px; ">Rp. ${$.number(param.barang_harga)} || Barang Stok : ${parseInt(param.stok_now)}</span>
											</div>
									</div>
								</div>
							</div>
						</div>
		`;
		$('#menuHandler').append(html);
	}

	function setSatuan(row, barang_id, stok) {
		// barang_id = $('#penjualan_detail_barang_id_' + row).val();
		// barang_id = $('#penjualan_detail_barang_id_' + row).val();
		// stok = parseFloat($('#penjualan_detail_barang_id_' + row + ' option:selected').data('temp'));
		jual = parseFloat($('#penjualan_detail_qty_' + row).val());
		$.post(BASE_URL + 'barang/list_satuan_harga', {
			barang_id: barang_id
		}, function(res) {
			html = '';
			$.each(res.data, function(i, v) {
				html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_jual="` + v.barang_satuan_harga_jual + `" data-barang_satuan_harga_beli="` + v.barang_harga_pokok + `" data-barang_satuan_disc="` + v.barang_satuan_disc + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `" data-barang_kategori="` + v.kategori_barang_parent + `">` + v.barang_satuan_kode + `(` + v.barang_satuan_konversi + `)` + `</option>`
			})
			$('#penjualan_detail_satuan_' + row).html(html);
			$('#penjualan_detail_satuan_' + row).select2();
			// if (st) $('#penjualan_detail_satuan_' + row).val(st).trigger('change');
			// getHarga(row, detail);
		})
		// if (stok < jual) {
		// 	$('.barang_' + row).css('background', '#fdfda8');
		// 	$('.barang_' + row).addClass('barang_false');
		// 	$('#stok-warning').html(stok);
		// 	$('#kt_toast_4').toast({
		// 		delay: 5000
		// 	});
		// 	$('#kt_toast_4').toast('show');
		// } else {
		// 	$('.barang_' + row).removeClass('barang_false');
		// 	$('.barang_' + row).css('background', 'transparent');
		// }
	}

	function satuanKonversi(param, row) {
		let data = {
			barang_satuan_id: param.value
		};

		// console.log(param.value + ' row : ' + row);
		$.post(BASE_URL + 'transaksipenjualan/get_barang_satuan_data', data, function(data) {
			console.log(data.barang_satuan_harga_jual);
			$('#penjualan_detail_harga_beli_' + row).val(data.barang_satuan_harga_jual);

			$('tr #updateHarga')
		});
	}

	let harga_dyn = 123;

	function initOrder(id, barang_nama, barang_harga, stok_now) {
		event.preventDefault();
		let qty = 1;
		setSatuan(row, id, stok_now);

		$('#orderHolder').remove();
		let html = `
			<tr class="order_${row}">
				<td id="barang_nama_${row}">
					<input type="hidden" name="penjualan_detail_barang_id[${row}]" id="penjualan_detail_barang_id_${row}" value="${id}">
						${barang_nama} (<span id="stokNow${row}">${stok_now}</span>)
				</td>
				<td>
					<select class="form-control" onchange="satuanKonversi(this, ${row})" name="penjualan_detail_satuan_[${row}]" id="penjualan_detail_satuan_${row}">
						<option value="">-Pilih Satuan-</option>
					</select>
				</td>
				<td id="updateHarga_${row}">
					<input type="hidden" name="penjualan_detail_id[${row}]" id="penjualan_detail_id_${row}">

					<input type="number" value="${qty}" id="penjualan_detail_qty_barang_${row}" onchange="countPrice(${row}, ${barang_harga}, 0, ${stok_now})" name="penjualan_detail_qty_barang[${row}]" class="form-control qty">
				</td>
				<td>
				<input id="penjualan_detail_harga_beli_${row}" type="text" readonly style="background-color: #eaeaea;" name="penjualan_detail_harga_beli[${row}]" value="${barang_harga}" class="form-control"></td>
				<td>
					<button class="btn btn-transparent-warning font-weight-bold" data-id="${row}" onclick="remOrder(this);" btn-sm ml-1">Hapus</button>
				</td>
			</tr>
		`;


		let flag = 0;
		$.each(idBayar, function(i, v) {
			if (v == id) {
				flag += 1;
			}
		});

		if (flag < 1) { // jika belum ada data dalam table
			idBayar[row] = id;
			totalBayar[row] = barang_harga;
			totalQty[row] = qty;
			$('#orderHandler').append(html);
			row++;
			totalItem++;
		} else {
			let cIndex = $.inArray(id, idBayar);
			let cQty;
			$('#penjualan_detail_qty_barang_' + cIndex).val(function(i, oldval) {
				let newQty = ++oldval;
				cQty = newQty;
				return newQty;
			});
			countPrice(cIndex, barang_harga, 0, stok_now);
		}
		countDiscount();
		countKembalian();
		countQty();
		$('#penjualan_total_item').val(totalItem);
	}

	function countPrice(cRow, cHarga, s_press = 0, stokNow = 0) {

		let cQty = $('#penjualan_detail_qty_barang_' + cRow).val();

		if (cQty < 0) {
			$('#penjualan_detail_qty_barang_' + cRow).val(0);
			cQty = 0;
		} else {
			let cTotal = cQty * cHarga;
			let cStok = stokNow - cQty;
			$('#stokNow' + cRow).text(cStok);

			totalBayar[cRow] = cTotal;
			totalQty[cRow] = cQty;
		}
		countQty();
		countDiscount();
		countKembalian();
	}

	function countTotal(s_press = 0) {
		let total = 0;
		$.each(totalBayar, function(i, v) {
			if (v !== undefined) {
				total += parseInt(v);
			}
		});
		$('#pembelian_total_bayar_display').text(total);
		$('#penjualan_total_harga').val(total);
		return total;
	}

	function countPajak() {
		let total = countTotal();
		let inputPajak = $('#penjualan_pajak_persen').val();
		let result = total * (inputPajak / 100);

		return result;
	}

	function countDiscount() {
		let total = countTotal();
		let pajak = countPajak();
		let inputDiscount = $('#penjualan_total_potongan_persen').val();
		let discount = total * (inputDiscount / 100);
		let result = total - discount + pajak;

		$('#pembelian_total_bayar_display').text($.number(result));
		$('#penjualan_total_harga').val(result);
		return result;
	}

	function countKembalian() {
		let total = countDiscount();
		let inputKembalian = $('#penjualan_total_bayar').val();
		let kembalian = inputKembalian - total;

		if (kembalian > 0) {
			$('#pembelian_total_kembalian').val(kembalian);
		} else {
			$('#pembelian_total_kembalian').val(0);
		}
	}

	function countQty() {
		let cQty = 0;
		$.each(totalQty, function(i, v) {
			if (v !== undefined) {
				cQty += parseInt(v);
			}
		});

		$('#penjualan_total_qty').val(cQty);
	}

	function loadMenu() {
		$.get(BASE_URL + 'transaksipenjualan/load_menu', function(res) {
			let menu = res.items;

			$.each(menu, function(index, value) {
				initMenu(value);
			});
		});
	}

	function remOrder(el) {
		event.preventDefault();

		cRow = $(el).data('id');
		$('tr.order_' + cRow).remove();

		totalBayar[cRow] = undefined;
		idBayar[cRow] = undefined;
		totalQty[cRow] = undefined;
		totalItem--;
		$('#penjualan_total_item').val(totalItem);

		countDiscount();
		countKembalian();
		countQty();

		if (totalBayar[1] === undefined) {
			$('#orderHandler').append(`
				<tr id="orderHolder">
					<td ></td>
				</tr>
			`);
		}
	}

	function resetOrder() {
		let orderHandler = $('#orderHandler');
		orderHandler.empty();
		$('#orderHandler').append(`
			<tr id="orderHolder">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		`);
	}

	function onReset() {
		st_edit = false;
		HELPER.api.store = BASE_URL + 'transaksipenjualan/store';
		$.each(HELPER.fields, function(i, v) {
			$('#' + v).val('');
		});
		$('#orderHandler').empty();
		$('#customerDiv').empty();
		$('#customerDiv').append(`
			<select name="pos_penjualan_customer_id" id="pos_penjualan_customer_id" class="form-control h-100"></select>
		`);
		HELPER.createCombo({
			el: 'pos_penjualan_customer_id',
			valueField: 'customer_id',
			displayField: 'customer_nama',
			url: BASE_URL + 'customer/select',
			callback: function(res) {
				$('#pos_penjualan_customer_id').select();
			}
		})
		$('#divBank').hide();
		$('#pembelian_total_bayar_display').text(0);
	}

	function loadTable() { //load daftar penjualan 
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-penjualanbarang",
			url: BASE_URL + 'transaksipenjualan/',
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['penjualan_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['penjualan_tanggal'];
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
						return full['penjualan_total_potongan_persen'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['penjualan_total_harga']);
					},
				},
				{
					targets: 6,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-primary" title="Edit" onclick="onEdit(this)" >
                          <i class="fas fa-pen"></i> Edit
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-info" title="Print" onclick="onPrint('` + full['penjualan_id'] + `')" >
                          <i class="fas fa-print"></i> Print
                        </a>
                        `;
					},
				},

			],
		});
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				st_edit = true;
				HELPER.api.store = HELPER.api.update;
				idBayar = [];
				totalBayar = [];
				totalQty = [];
				totalItem = 0;

				let detail = res.detail.data;
				resetOrder();
				$.each(detail, function(i, v) {
					idBayar[row] = v.penjualan_detail_barang_id;
					totalBayar[row] = v.barang_satuan_harga_beli;
					totalQty[row] = v.penjualan_detail_qty_barang;

					$('#orderHandler').append(
						`
						<tr class="order_${row}">
							<td id="barang_nama_${row}">
								<input type="hidden" name="penjualan_detail_barang_id[${row}]" id="penjualan_detail_barang_id_${row}" value="${v.penjualan_detail_barang_id}">
									${v.barang_nama}
							</td>
							<td>
								<input type="hidden" name="penjualan_detail_id[${row}]" id="penjualan_detail_id_${row}">

								<input type="number" value="${parseInt(v.penjualan_detail_qty_barang)}" id="penjualan_detail_qty_barang_${row}" onchange="countPrice(${row}, ${v.penjualan_detail_harga_beli})" name="penjualan_detail_qty_barang[${row}]" class="form-control qty">
							</td>
							<td>
							<input id="penjualan_detail_harga_beli_${row}" type="text" readonly style="background-color: #eaeaea;" name="penjualan_detail_harga_beli[${row}]" value="${v.penjualan_detail_harga_beli}" class="form-control"></td>
							<td>
								<button class="btn btn-transparent-warning font-weight-bold" data-id="${row}" onclick="remOrder(this);" btn-sm ml-1">Hapus</button>
							</td>
						</tr>
						`
					);

					countPrice(row, v.penjualan_detail_harga_beli);
					row++;
					totalItem++;
				});

				$('#penjualan_total_item').val(totalItem);
				$('#penjualan_id').val(res.parent.penjualan_id);
				$('#penjualan_kode').val(res.parent.penjualan_kode);
				$('#penjualan_total_bayar_tunai').val(res.parent.penjualan_total_bayar_tunai);
				$('#penjualan_metode').val(res.parent.penjualan_metode);
				$('#penjualan_total_potongan_persen').val(res.parent.penjualan_total_potongan_persen);
				$('#penjualan_total_bayar').val(res.parent.penjualan_total_bayar);
				$('#pos_penjualan_customer_id').val(res.parent.pos_penjualan_customer_id).trigger('change');

				$('#modal-penjualan').modal('hide');
				HELPER.unblock()
			}
		})
	}




	$(document).on('keypress', function(e) {
		if (e.which == 13) {
			// resetOrder();
		}
	});


	//==================================================================================================


	function addThis(el) {
		HELPER.getDataFromTable({
			table: 'table-barang',
			inline: el,
			callback: function(res) {
				$.ajax({
					url: BASE_URL + 'barang/single_read',
					data: {
						barang_id: res.barang_id
					},
					type: 'post',
					success: function(res) {
						add = true;
						$.each($('.barang_id'), function(n, r) {
							if (res.barang_id == $(r).val()) {
								trow = $(r).data('id');
								add = false;
							}
						})
						qty = parseInt($('#qty').val()) || 1;
						if (!add) {
							$('#penjualan_detail_qty_' + trow).val(qty + parseFloat($('#penjualan_detail_qty_' + trow).val()));
							countRow(trow);
						} else {
							if ($('#penjualan_detail_barang_id_' + row).val()) addBarang();
							$('#penjualan_detail_barang_id_' + row).select2("trigger", "select", {
								data: {
									id: res.barang_id,
									text: res.barang_kode + ' - ' + res.barang_nama,
								}
							});
							$('#penjualan_detail_qty_' + row).val(qty);
						}
						$('#barang').val('');
						$('#qty').val('1');
						$('#modal-barang').modal('hide');
					}
				})
			}
		})
	}

	function myTimer() {
		var d = moment();
		$("#Timestamp").text(d.format('HH:mm:ss'));
	}

	function setReset() {
		tanggal = $('#penjualan_tanggal').val();
		$('#form-penjualanbarang, #form-bayar').trigger('reset');
		getMetode();
		$("#penjualan_anggota_id").select2("trigger", "select", {
			data: {
				id: '',
				text: ''
			}
		});

		$('#penjualan_anggota_id').attr('disabled', false);
		$('#v_penjualan_total_grand').text(0);
		$('#sisa_saldo, #sisa_saldo_voucher').text('Sisa saldo : ');
		$('#table-detail_barang tbody tr').remove();
		row = 0;
		saldo_voucher = saldo_voucher_khusus = 0;
		$('#penjualan_tanggal').val(tanggal);
		addBarang();
	}

	function setDate() {
		dt = '<?php echo $this->config->item('base_penjualan_tanggal') ?>';
		view = moment(dt).format('MMM D');
		$('#penjualan_tanggal').val(moment(dt).format('YYYY-MM-DD'));
		$('#kt_dashboard_daterangepicker_date').html(view)
	}

	function getMetode() {
		metode = $('#penjualan_metode').val();
		$('.bank').hide();
		$('.kt-payment').css('padding-bottom', '400px')
		if (metode == 'B') {
			$('.kt-payment').css('padding-bottom', '420px')
			$('.bank').show();
		}
		if (metode == 'K' && !$('#penjualan_anggota_id').val()) {
			$('#penjualan_metode').val('T').trigger('change');
			swal.fire('Informasi', 'Silahkan pilih nasabah terlebih dahulu!', 'warning');
		}
	}

	function getBarang(id) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/get_barang',
			data: {
				val: id
			},
			type: 'post',
			success: function(res) {
				if (res[0]) {
					v = res[0];
					add = true;
					trow = '';
					$.each($('.barang_id'), function(n, r) {
						if (v.id == $(r).val()) {
							trow = $(r).data('id');
							add = false;
						}
					})
					if (!add) {
						jual = parseInt($('#qty').val()) + parseFloat($('#penjualan_detail_qty_' + trow).val());
						$('#penjualan_detail_qty_' + trow).val(jual);
						countRow(trow);
					} else {
						if ($('#penjualan_detail_barang_id_' + row).val()) addBarang();
						$('#penjualan_detail_barang_id_' + row).select2("trigger", "select", {
							data: {
								id: v.id,
								text: v.text,
								saved: v.saved
							}
						});
						jual = $('#qty').val();
						$('#penjualan_detail_qty_' + row).val(jual);
						trow = row;
					}
					if (v.saved < jual) {
						$('.barang_' + trow).css('background', '#fdfda8');
						$('.barang_' + trow).addClass('barang_false');
						$('#stok-warning').html(v.saved);
						$('#kt_toast_4').toast({
							delay: 15000
						});
						$('#kt_toast_4').toast('show');
					} else {
						$('.barang_' + trow).removeClass('barang_false');
						$('.barang_' + trow).css('background', 'transparent');
					}
					$('#barang').val('');
					$('#qty').val('1');
					$('#barang').trigger('focus');
					// countRow(row);
				} else {
					swal.fire('Informasi', 'Data tidak ditemukan', 'warning');
					cariBarang();
				}
				HELPER.unblock();
			}
		})
	}

	function cariBarang() {
		HELPER.block();
		if ($.fn.DataTable.isDataTable('#table-barang')) {
			$('#table-barang').DataTable().destroy();
		}
		var table = $('#table-barang').DataTable({
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
				url: BASE_URL + 'barang/',
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
					targets: 5,
					render: function(data, type, row) {
						return $.number(row[5]);
					}
				},
				{
					targets: 7,
					render: function(data, type, row) {
						return $.number(row[7]);
					}
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						aksi = ` < button type = "button"
		class = "btn btn-outline-brand btn-pill btn-sm"
		title = "Edit"
		onclick = "addThis(this)" >
			<
			i class = "la la-check-circle" > < /i> Pilih <
			/button>`;
						return aksi
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
		HELPER.unblock();
		$('#modal-barang').modal();
	}

	function onTables() {
		loadTable();
		$('#modal-penjualan').modal();
	}



	function init_table(argument) {
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()
		if ($.fn.DataTable.isDataTable('#table-penjualanbarang')) {
			$('#table-penjualanbarang').DataTable().destroy();
		}
		var table = $('#table-penjualanbarang').DataTable({
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
				url: BASE_URL + 'transaksipenjualan/',
				type: 'POST',
				data: {
					tanggal1: awal,
					tanggal2: akhir,
				}
			},
			order: [
				[1, 'desc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
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
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>| 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-active" onclick="onPrint('` + data + `')" title="Print" >
                          <span class="la la-print"></span> Print
                        </a>`;

					},
				},
				{
					targets: 2,
					render: function(data, type, row) {
						return (data ? moment(data).format("DD-MM-YYYY") : '');
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


	function onBayar() {
		save()
	}

	function setBarang(trow) {
		if (trow) {
			trow = trow.join(', ');
		} else trow = '#penjualan_detail_barang_id_' + row;
		HELPER.ajaxCombo({
			el: trow,
			url: BASE_URL + 'transaksipenjualan/barang_ajax',
			wresult: 'bigdrop'
		});
		$('input.number').number(true);

		$(".select2-search__field").keypress(function(event) {
			if (event.which == '10' || event.which == '13') {
				event.preventDefault();
			}
		});
	}


	function getHarga(row, detail) {
		if (detail) {

			$('#penjualan_detail_harga_' + row).val(detail.penjualan_detail_harga);
			//tambahan
			$('#penjualan_detail_harga_beli_' + row).val(detail.penjualan_detail_harga_beli);
			$('#penjualan_detail_hpp_' + row).val(detail.penjualan_detail_hpp);
			//end tambahan
			$('#penjualan_detail_qty_' + row).val(detail.penjualan_detail_qty);
			$('#penjualan_detail_qty_barang_' + row).val(detail.penjualan_detail_qty_barang);
			$('#penjualan_detail_potongan_persen_' + row).val(detail.penjualan_detail_potongan_persen);
			$('#penjualan_detail_potongan_' + row).val(detail.penjualan_detail_potongan);
			$('#penjualan_detail_subtotal_' + row).val(detail.penjualan_detail_subtotal);
		} else {
			harga = $('#penjualan_detail_satuan_' + row + ' option:selected').data();
			konversi = harga_beli = 0;
			if (harga) {
				$('#penjualan_detail_satuan_kode_' + row).val($('#penjualan_detail_satuan_' + row + ' option:selected').text());
				$('#penjualan_detail_harga_beli_' + row).val(harga.barang_satuan_harga_beli)
				$('#penjualan_detail_harga_' + row).val(harga.barang_satuan_harga_jual)
				$('#penjualan_detail_potongan_persen_' + row).val(harga.barang_satuan_disc)
				$('#penjualan_detail_jenis_barang_' + row).val(harga.barang_kategori)
				konversi = parseInt(harga.barang_satuan_konversi) || 0;
				//tambahan
				harga_beli = parseInt(harga.barang_satuan_harga_beli) || 0;
				//end tambahan
			}
			qty = parseFloat($('#penjualan_detail_qty_' + row).val()) || 0;
			qty_barang = konversi * qty;
			$('#penjualan_detail_qty_barang_' + row).val(qty_barang);
			//tambahan
			hpp = qty_barang * harga_beli;
			$('#penjualan_detail_hpp_' + row).val(hpp);
			//end tambahan


		}
		countRow(row)
	}

	function setPrice(row) {
		dt = $('#barang_satuan_opt_' + row).data();
		harga = parseInt(dt.harga);
		isi = parseInt(dt.isi) || 1;
		qty = parseFloat($('#penjualan_detail_qty_' + row).val()) || 0;
		if (dt.satuan == $('#penjualan_detail_satuan_' + row).val()) {
			qty = isi * qty;
			harga = harga * isi;
		}
		$('#penjualan_detail_qty_barang_' + row).val(qty);
		$('#penjualan_detail_harga_' + row).val(harga);
		countRow(row);
	}

	function getNasabah() {
		$.post(BASE_URL + 'anggota/read', {
			anggota_id: $('#penjualan_anggota_id').val()
		}, function(res) {
			$('#anggota_nip').text((res.grup_gaji_kode ? res.anggota_nip + ' - (' + res.grup_gaji_kode + ') ' + res.grup_gaji_nama : ''));
			$('#anggota_grup_gaji').text('GOL. (' + (res.grup_gaji_kode ? res.grup_gaji_kode + ') ' + res.grup_gaji_nama : '-) '));
			$('#anggota_update_at').html('Update ' + (res.anggota_update_at == null ? '-' : moment(res.anggota_update_at).format('DD MMM YYYY')));
			$('#anggota_update_at').css('display', '');
			$('.voucher').val(res.anggota_saldo_simp_titipan_belanja);
			$('#anggota_kode, #kode_anggota').val(res.anggota_kode);
			$('#anggota_is_proteksi').val(res.anggota_is_proteksi);
			if (res.anggota_is_proteksi == 1) {
				$('#proteksi-anggota').show();
			} else {
				$('#proteksi-anggota').hide();
			}

			saldo_voucher = parseInt(res.anggota_saldo_simp_titipan_belanja) || 0;
			if (res.anggota_saldo_bhr > 0) {
				saldo_voucher_khusus = parseInt(res.anggota_saldo_bhr) || 0;
				$('#anggota_saldo_voucher').val(res.anggota_saldo_bhr);
				$('#saldo-voucher-anggota').css('display', '');
				$('#saldo-voucher-anggota').text('Exp. ' + moment(res.anggota_saldo_bhr_exp_date).format('D MMM YYYY'));
			} else {
				$('#saldo-voucher-anggota').css('display', 'none');
			}
			if (res.anggota_saldo_voucher > 0) {
				saldo_voucher_lain = parseInt(res.anggota_saldo_voucher) || 0;
				$('#anggota_saldo_voucher_lain').val(res.anggota_saldo_voucher);
				$('#saldo-voucher-lain-anggota').css('display', '');
				$('#saldo-voucher-lain-anggota').text('Exp. ' + moment(res.anggota_saldo_voucher_exp_date).format('D MMM YYYY'));
			} else {
				$('#saldo-voucher-lain-anggota').css('display', 'none');
			}
			if (res.anggota_id) {
				$('#clear-anggota, .nasabah').css('display', '');
				$('.bayar-voucher, .bayar-voucher-anggota, .bayar-voucher-lain-anggota').attr('disabled', false);
			} else {
				$('.bayar-voucher, .bayar-voucher-anggota').val('');
				$('.bayar-voucher, .bayar-voucher-anggota').attr('disabled', true);
				$('#clear-anggota, .nasabah').css('display', 'none');
				$('#anggota_nip, .voucher, #anggota_saldo_voucher').val(0);
				$('#anggota_grup_gaji').text('')
				saldo_voucher = saldo_voucher_khusus = 0;
			}
			if (st_edit == true) {
				$('#penjualan_anggota_id').attr('disabled', true);
				$('#clear-anggota').css('display', 'none');
				saldo_voucher += parseInt(vdt['titipan_belanja']) || 0;
				saldo_voucher_khusus += parseInt(vdt['voucher']) || 0;
			}
			countDisDiscount()
			st_edit = false;
		})
	}

	function clearAnggota() {
		$("#kode_anggota").val('');
		$("#penjualan_anggota_id").select2("trigger", "select", {
			data: {
				id: '',
				text: ''
			}
		});
		getNasabah();
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow2(nrow) {
		nqty = parseFloat($('#penjualan_detail_qty_' + nrow).val()) || 0;
		jumlah = (parseInt($('#penjualan_detail_harga_' + nrow).val()) * nqty) || 0;
		pot_persen = parseFloat($('#penjualan_detail_potongan_persen_' + nrow).val()) || 0;
		potongan = pot_persen * jumlah / 100;
		$('#penjualan_detail_potongan_' + nrow).val(potongan);
		jumlah -= potongan;
		$('#penjualan_detail_subtotal_' + nrow).val(jumlah)
		dt = $('#barang_satuan_opt_' + nrow).data();
		if (dt.satuan == $('#penjualan_detail_satuan_' + nrow).val()) {
			isi = parseInt(dt.isi) || 1;
			nqty = isi * nqty;
		}
		$('#penjualan_detail_qty_barang_' + nrow).val(nqty);
		sub_total = qty = item = 0;
		done = true;
		$('.jumlah').each(function(i, v) {
			t = parseInt($(v).val()) || 0;
			sub_total += t;
			if (!t) done = false;
			else item++;
		})
		$('.qty').each(function(i, v) {
			qty += parseFloat($(v).val());
		})
		if (done) addBarang()
		$('#penjualan_total_harga').val(sub_total);
		$('#penjualan_total_item').val(item);
		$('#penjualan_total_qty').val(qty);
		countDisDiscount()
	}

	function countRow(nrow) {


		stok = parseFloat($('#penjualan_detail_barang_id_' + nrow + ' option:selected').data('temp'));
		jual = parseFloat($('#penjualan_detail_qty_' + nrow).val());
		if (stok < jual) {
			$('.barang_' + nrow).css('background', '#fdfda8');
			$('.barang_' + nrow).addClass('barang_false');
			$('#stok-warning').html(stok);
			$('#kt_toast_4').toast({
				delay: 5000
			});
			$('#kt_toast_4').toast('show');
		} else {
			$('.barang_' + nrow).removeClass('barang_false');
			$('.barang_' + nrow).css('background', 'transparent');
		}

		qty = parseFloat($('#penjualan_detail_qty_' + nrow).val()) || 0;
		harga = parseInt($('#penjualan_detail_harga_' + nrow).val()) || 0;
		//tambahan
		harga_beli = parseInt($('#penjualan_detail_harga_beli_' + nrow).val()) || 0;
		//end tambahan
		qty_barang = harga_barang = 0;
		st = $('#penjualan_detail_satuan_' + nrow + ' option:selected').data();
		if (st) {
			konversi = parseInt(st.barang_satuan_konversi) || 1;
			qty_barang = konversi * qty;
			harga_barang = harga * konversi;
		}

		//tambahan
		hpp = qty_barang * harga_beli;
		$('#penjualan_detail_hpp_' + nrow).val(hpp);
		// $('#penjualan_detail_harga_barang_'+ nrow).val(harga_barang);
		//end tambahan
		$('#penjualan_detail_harga_barang_' + nrow).val(harga_barang);
		$('#penjualan_detail_qty_barang_' + nrow).val(qty_barang);
		jumlah = (harga * konversi * qty) || 0;
		disc = parseFloat($('#penjualan_detail_potongan_persen_' + nrow).val()) || 0;
		diskon = disc * jumlah / 100;
		diskon = Math.round(diskon / 100) * 100;
		$('#penjualan_detail_potongan_' + nrow).val(diskon);


		jumlah = jumlah - diskon;
		$('#penjualan_detail_subtotal_' + nrow).val(jumlah)
		sub_total = qty = item = 0;
		done = true;

		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val()) || 0;
			t = parseInt($(v).val());
			if (!t) done = false;
			else item++;
		})
		if (done) addBarang()
		for (var i = 1; i <= row; i++) {
			if ($('#penjualan_detail_subtotal_' + i).val()) qty += parseFloat($('#penjualan_detail_qty_' + i).val());
		}
		$('#penjualan_total_harga').val(sub_total);
		$('#penjualan_total_item').val(item);
		$('#penjualan_total_qty').val(qty);



		countDisDiscount()
	}


	function setBayar(el) {
		bayar = parseInt($(el).val()) || 0;
		if (bayar > 0) {
			$('.bayar-tunai').val(bayar);
			tunai = parseInt($('#penjualan_total_grand').val());
			nilai = parseInt($('.bayar-tunai').val());
		} else {
			sub_total = parseInt($('#penjualan_total_harga').val()) || 0;
			diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
			voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0
			voucher_khusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0
			kurang = sub_total - diskon - voucher - voucher_khusus;
			// $(el).val(kurang);
		}
		countDisDiscount();
	}
	/*off sementara 02/10*/
	function setVoucher(el) {
		voucher = parseInt($(el).val()) || 0;
		saldo = saldo_voucher;
		vk = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
		lain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		jumlah = (parseInt($('#penjualan_total_harga').val()) || 0) - vk - lain - diskon;
		/*if((saldo_voucher-voucher)<0){
			swal.fire('Informasi', 'Jumlah penggunaan titipan belanja melebihi saldo yang tesedia!','warning');
			voucher = saldo;
		}*/
		if (voucher > jumlah) {
			swal.fire('Informasi', 'Jumlah penggunaan titipan belanja melebihi total penjualan!', 'warning');
			voucher = jumlah;
		}
		$('.bayar-voucher').val(voucher)
		sisa = saldo_voucher - voucher
		$('#anggota_saldo_simp_titipan_belanja').val(sisa)
		countDisDiscount();
	}

	function setVoucherKhusus(el) {
		voucher = parseInt($(el).val()) || 0;
		saldo = saldo_voucher_khusus;
		v = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
		lain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		jumlah = (parseInt($('#penjualan_total_harga').val()) || 0) - v - lain - diskon;
		if ((saldo_voucher_khusus - voucher) < 0) {
			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi saldo yang tesedia!', 'warning');
			voucher = saldo;
		}
		if (voucher > jumlah) {
			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi total penjualan!', 'warning');
			voucher = jumlah;
		}
		$('.bayar-voucher-anggota').val(voucher)
		sisa = saldo_voucher_khusus - voucher
		$('#anggota_saldo_voucher').val(sisa)
		countDisDiscount();
	}

	function setVoucherLain(el) {
		voucher = parseInt($(el).val()) || 0;
		saldo = saldo_voucher_lain;
		v = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
		vk = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		jumlah = (parseInt($('#penjualan_total_harga').val()) || 0) - v - vk - diskon;
		if ((saldo_voucher_lain - voucher) < 0) {
			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi saldo yang tesedia!', 'warning');
			voucher = saldo;
		}
		if (voucher > jumlah) {
			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi total penjualan!', 'warning');
			voucher = jumlah;
		}
		$('.bayar-voucher-lain-anggota').val(voucher);
		sisa = saldo_voucher_lain - voucher;
		$('#anggota_saldo_voucher_lain').val(sisa)
		countDisDiscount();
	}

	function fillVoucher(el) {
		v = parseInt($(el).val()) || 0;
		if (v <= 0) {
			saldo = parseInt(saldo_voucher) || 0;
			disc = parseInt($('#penjualan_total_potongan').val()) || 0;
			vkhusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
			voucherlain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
			bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
			total = parseInt($('#penjualan_total_harga').val()) || 0;
			if (saldo > 0) {
				sisa = total - bayar - vkhusus - voucherlain - disc;
				if (sisa > 0) {
					if (saldo_voucher > sisa) bv = sisa;
					else bv = saldo
					$('.bayar-voucher').val(bv);
					$('#anggota_saldo_simp_titipan_belanja').val((saldo_voucher - bv))
				}
			}
			countDisDiscount()
		}
	}

	function fillVoucherKhusus(el) {
		voucher_khusus = parseInt($(el).val()) || 0;
		if (voucher_khusus <= 0) {
			saldo = parseInt(saldo_voucher_khusus) || 0;
			disc = parseInt($('#penjualan_total_potongan').val()) || 0;
			voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
			voucherlain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
			bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
			total = parseInt($('#penjualan_total_harga').val()) || 0;
			if (saldo > 0) {
				sisa = total - bayar - voucher - voucherlain - disc;
				if (sisa > 0) {
					if (saldo_voucher_khusus > sisa) bv = sisa;
					else bv = saldo
					$('.bayar-voucher-anggota').val(bv);
					$('#anggota_saldo_voucher').val((saldo_voucher_khusus - bv))
					/*if(saldo>sisa) $('.bayar-voucher-anggota').val(sisa);
					else $('.bayar-voucher-anggota').val(saldo)*/
				}
			}
			countDisDiscount()
		}
	}

	function fillVoucherLain(el) {
		voucher_lain = parseInt($(el).val()) || 0;
		if (voucher_lain <= 0) {
			saldo = parseInt(saldo_voucher_lain) || 0;
			disc = parseInt($('#penjualan_total_potongan').val()) || 0;
			voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
			vkhusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
			bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
			total = parseInt($('#penjualan_total_harga').val()) || 0;
			if (saldo > 0) {
				sisa = total - bayar - voucher - vkhusus - disc;
				if (sisa > 0) {
					if (saldo_voucher_lain > sisa) bv = sisa;
					else bv = saldo
					$('.bayar-voucher-lain-anggota').val(bv);
					$('#anggota_saldo_voucher_lain').val((saldo_voucher_lain - bv))
					/*if(saldo>sisa) $('.bayar-voucher-anggota').val(sisa);
					else $('.bayar-voucher-anggota').val(saldo)*/
				}
			}
			countDisDiscount()
		}
	}

	function countDiskon() {
		sub_total = parseInt($('#penjualan_total_harga').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		diskon_p = parseInt($('#penjualan_total_potongan_persen').val()) || 1;
		if (diskon_p) {
			diskon = diskon_p * sub_total / 100;
			$('#penjualan_total_potongan').val(diskon)
		}
		grand = sub_total - diskon;
		bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
		voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
		sisa_saldo = voucher;
		$('#sisa_saldo').text('Sisa Saldo : ' + $.number(sisa_saldo));
		voucher_khusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
		sisa_saldo_khusus = voucher_khusus;
		$('#sisa_saldo_voucher').text('Sisa Saldo : ' + $.number(sisa_saldo_khusus));
		voucher_lain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
		sisa_saldo_voucher_lain = voucher_lain;
		$('#sisa_saldo_voucher_lain').text('Sisa Saldo : ' + $.number(sisa_saldo_voucher_lain));
		// sisa_saldo_voucher_lain
		tbayar = bayar + voucher + voucher_khusus + voucher_lain;
		kredit = grand - tbayar;
		kredit = (kredit >= 0) ? kredit : 0;

		kembalian = (tbayar - grand) >= 0 ? (tbayar - grand) : 0;
		$('.total_harga').val(grand);



		$('#v_penjualan_total_grand').text($.number(kredit));
		$('#penjualan_total_bayar').val(tbayar);
		$('#penjualan_total_kredit').val(kredit);
		$('#penjualan_total_kembalian').val(kembalian)
		countCicilan()
	}

	function setChecked(el) {
		var cetak = $(el);
		if (cetak.is(":checked")) $('.cetak').prop('checked', true);
		else $('.cetak').prop('checked', false);
	}

	function countCicilan() {
		n_cicil = parseInt($('#penjualan_total_cicilan_qty').val()) || 1;
		if (n_cicil > 1) {
			$('#penjualan_total_jasa').attr('disabled', false);
			if ($('#penjualan_total_jasa').val() == 0) {
				$('#penjualan_total_jasa').val(vjasa);
			}
		} else {
			$('#penjualan_total_jasa').val(0);
			$('#penjualan_total_jasa').attr('disabled', true);
		}
		kredit = parseInt($('#penjualan_total_kredit').val()) || 0;
		$('#pengajuan_jumlah_pinjaman').val(kredit);
		n_jasa = parseFloat($('#penjualan_total_jasa').val()) || 0;
		pokok = (kredit / n_cicil);
		jasa = (kredit * n_jasa / 100);
		jasa = Math.ceil(jasa / 50) * 50;
		pokok = Math.ceil(pokok / 100) * 100;

		// jasa_2 = Math.round(jasa / 500) * 500;
		// pokok_2 = Math.round(pokok / 500) * 500;
		// if(jasa < jasa_2) jasa = jasa_2;
		// if(pokok < pokok_2) pokok = pokok_2;
		cicil = pokok + jasa;
		$('#penjualan_total_cicilan').val(pokok);
		$('#penjualan_total_jasa_nilai').val(jasa);
		$('#bulan_cicil').text($.number(cicil) + ' /bulan');
		if ($('#penjualan_tanggal').val()) {
			tgl = moment($('#penjualan_tanggal').val());
		} else {
			tgl = moment();
			$('#penjualan_tanggal').val(tgl.format('YYYY-MM-D'));
		}
		n = (tgl.format('D') <= 20) ? 0 : 1;
		jt_awal = moment({
			y: tgl.format('YYYY'),
			M: (tgl.format('M') - 1),
			d: 21
		}).add(n, 'M');
		jt_tempo = moment({
			y: tgl.format('YYYY'),
			M: (tgl.format('M') - 1),
			d: 21
		}).add((n + n_cicil), 'M');
		$('#penjualan_kredit_awal').val(jt_awal.format('YYYY-MM-DD'))
		$('#penjualan_jatuh_tempo').val(jt_tempo.format('YYYY-MM-DD'))
		// $('#pengajuan_tag_bulan').val(jt_tempo.format('YYYY-MM'))
	}

	function addBarang() {
		row++;
		html = `<tr class="barang_` + row + `">
					<td scope="row">
						<input type="hidden" class="form-control" name="penjualan_detail_id[` + row + `]" id="penjualan_detail_id_` + row + `">						
						<input type="hidden" class="form-control" name="penjualan_detail_jenis_barang[` + row + `]" id="penjualan_detail_jenis_barang_` + row + `">						
						<select class="form-control barang_id" name="penjualan_detail_barang_id[` + row + `]" id="penjualan_detail_barang_id_` + row + `" data-id="` + row + `" onchange="setSatuan('` + row + `')" style="width: 260px;white-space: nowrap"></select></td>
					<td><select class="form-control" name="penjualan_detail_satuan[` + row + `]" id="penjualan_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select>
						<input type="hidden" class="form-control" name="penjualan_detail_satuan_kode[` + row + `]" id="penjualan_detail_satuan_kode_` + row + `" >						
					</td>					
					<td><input class="form-control number" type="text" name="penjualan_detail_harga[` + row + `]" id="penjualan_detail_harga_` + row + `" onchange="countRow('` + row + `')" readonly=""></td>
					<td>
						<input class="form-control qty" type="number" name="penjualan_detail_qty[` + row + `]" id="penjualan_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" onchange="countRow('` + row + `')" value="1">
						<input class="form-control number" type="hidden" name="penjualan_detail_qty_barang[` + row + `]" id="penjualan_detail_qty_barang_` + row + `">						
                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_harga_beli[` + row + `]" id="penjualan_detail_harga_beli_` + row + `">
                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_hpp[` + row + `]" id="penjualan_detail_hpp_` + row + `">					
					</td>
					<td>
						<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[` + row + `]" id="penjualan_detail_potongan_persen_` + row + `" onkeyup="countRow('` + row + `')">
						<input class="form-control number" type="hidden" name="penjualan_detail_potongan[` + row + `]" id="penjualan_detail_potongan_` + row + `">
					</td>
					<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[` + row + `]" id="penjualan_detail_subtotal_` + row + `" readonly=""></td>
					<td style="text-align: center;"><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
                  		<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-detail_barang').append(html);
		setBarang();
		$('.disc').number(true, 2);
	}

	function addByBarcode() {
		bcd = $('#form-barcode').serializeObject()
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/get_detail',
			type: 'post',
			data: {
				penjualan_detail_parent: parent
			},
			success: function(res) {
				$('#table-detail_barang body').html('');
				$.each(res.data, function(i, v) {
					n = i + 1;
					if (n > 1) addBarang();
					$('#penjualan_detail_id_' + n).val(v.penjualan_detail_id);
					$('#penjualan_detail_harga_' + n).val(v.penjualan_detail_harga);
					$('#penjualan_detail_qty_' + n).val(v.penjualan_detail_qty);
					$('#penjualan_detail_qty_barang_' + n).val(v.penjualan_detail_qty_barang);
					$('#penjualan_detail_potongan_persen_' + n).val(v.penjualan_detail_potongan_persen);
					$('#penjualan_detail_potongan_' + n).val(v.penjualan_detail_potongan);
					$('#penjualan_detail_subtotal_' + n).val(v.penjualan_detail_subtotal);
					$("#penjualan_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.penjualan_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama,
						}
					});
					// harga
					// new_st = [v.barang_satuan, v.barang_satuan_opt, v.barang_isi, v.barang_harga];
					setSatuan(n, v.penjualan_detail_satuan, v);
					// viewHarga(n, v)
				})
			}
		})
	}

	function remRow(el) {
		id = $(el).data('id');
		$('tr.barang_' + id).remove();
		countRow(id);
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-penjualanbarang'
		})
	}

	function save() {
		br_false = $('.barang_false').length;
		if (br_false == 0) {
			if ($('#penjualan_metode').val() == 'K') {
				if ($('#anggota_is_proteksi').val() == '1') {
					HELPER.showMessage({
						success: false,
						message: 'Anggota terproteksi! Tidak dapat meminjam',
						title: 'Informasi',
					})
				} else {
					$('#modal-bayar').modal();
				}
				anggota = $('#anggota_kode').val();
				if (anggota.substr(-1) == "K" || anggota.substr(-1) == "G" || anggota.substr(-1) == "D") {
					$('#penjualan_jenis_potongan').val(0).trigger('change');
				} else {
					$('#penjualan_jenis_potongan').val(1).trigger('change');
				}
			} else {
				if (parseInt($('#penjualan_total_kredit').val()) > 0) {
					HELPER.showMessage({
						success: false,
						message: 'Silahkan lengkapi pembayaran terlebih dulu!',
						title: 'Informasi',
					})
				} else {
					saving();
				}
			}
		} else {
			HELPER.showMessage({
				success: false,
				message: 'Barang yang dijual melebihi stok yang ada, silahkan periksa kembali penjualan barang anda!.',
				title: 'Warning',
			})
		}
	}

	function saving() {
		if (parseInt($('#penjualan_total_harga').val()) > 0) {
			HELPER.save({
				form: 'form-penjualanbarang',
				data: $('#form-bayar').serializeObject(),
				confirm: true,
				callback: function(success, id, record, message, res) {
					var cetak = $('#cetak');
					if (success === true) {
						setReset();
						if (cetak.is(":checked")) {
							print = res.responseJSON.print;
							if (print) {
								$('#printArea').html(print);
								var WinPrint = window.open('', '', 'width=900,height=650');
								WinPrint.document.write($('#printArea').html());
								WinPrint.document.close();
								WinPrint.focus();
								WinPrint.print();
								WinPrint.close();
							}
							$('#modal-bayar').modal('hide');
						}
						$('#uang_kembali').text('Rp. ' + $.number(record.penjualan_total_kembalian));
						$('#uang_bayar').text('Bayar Rp. ' + $.number(record.penjualan_total_bayar_tunai));
						$('#kt_toast_2').toast({
							delay: 50000
						});
						$('#kt_toast_2').toast('show');
					}
				}
			})
		} else {
			HELPER.showMessage({
				success: false,
				message: 'Silahkan lengkapi penjualan terlebih dahulu!.',
				title: 'Warning',
			})
		}
	}

	function savingKasirBaru() {
		// checkRequired
		let flagRequired = 0;
		$.each(setRequired, function(i, v) {
			if ($('#' + v).val() == '' || $('#' + v).val() == undefined) {
				$('#' + v).addClass('border border-danger');
				flagRequired++;
			}
		});
		if (flagRequired > 0) {
			return;
		}

		let penjualanMetode = $('#penjualan_metode').val();
		if (penjualanMetode == 'B') {
			let penjualanBank = $('#penjualan_bank');
			if (penjualanBank.val() == 0 || penjualanBank.val() == '' || penjualanBank.val() == undefined) {
				penjualanBank.addClass('border border-danger');
				return;
			}
		}

		// check total item
		let totalItem = $('#penjualan_total_item').val();
		if (totalItem == 0 || totalItem == '' || totalItem == undefined) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Pilih produk terlebih dahulu!',
			})
			return;
		}


		let totalNota = parseInt($('#penjualan_total_harga').val());
		let totalBayar = parseInt($('#penjualan_total_bayar').val());
		if (totalBayar < totalNota) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Jumlah uang yang dibayar kurang!',
			})
		} else {
			HELPER.save({
				form: 'form-penjualanbarang',
				data: $('#form-penjualanbarang').serializeObject(),
				confirm: true,
				callback: function(success, id, record, message, res) {
					var cetak = $('#cetak');
					if (success === true) {
						onReset();
						setReset();
						if (cetak.is(":checked")) {
							print = res.responseJSON.print;
							if (print) {
								$('#printArea').html(print);
								var WinPrint = window.open('', '', 'width=900,height=650');
								WinPrint.document.write($('#printArea').html());
								WinPrint.document.close();
								WinPrint.focus();
								WinPrint.print();
								WinPrint.close();
							}
							$('#modal-bayar').modal('hide');
						}
						$('#uang_kembali').text('Rp. ' + $.number(record.penjualan_total_kembalian));
						$('#uang_bayar').text('Bayar Rp. ' + $.number(record.penjualan_total_bayar_tunai));
						$('#kt_toast_2').toast({
							delay: 50000
						});
						$('#kt_toast_2').toast('show');
					}
				}
			})
		}
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-penjualanbarang',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function onPrint(param) {
		HELPER.block();
		HELPER.getDataFromTable({
			table: 'table-penjualanbarang',
			callback: function(data) {
				id = data.penjualan_id;
				if (param) {
					id = param
				}
				$.ajax({
					url: BASE_URL + 'transaksipenjualan/tprint/' + id,
					data: {
						tjson: true
					},
					type: 'post',
					success: function(res) {
						HELPER.unblock();
						if (res.tprint) {
							$('#printArea').html(res.tprint);
							var WinPrint = window.open('', '', 'width=900,height=650');
							WinPrint.document.write($('#printArea').html());
							WinPrint.document.close();
							WinPrint.focus();
							WinPrint.print();
							WinPrint.close();
						}
					}
				})
			}
		})

	}

	function onPesanan() {
		$('#modal-pesanan').modal();
		init_pesanan()
	}

	function init_pesanan(argument) {
		$.ajax({
			url: BASE_URL + 'kasir/select_pesanan',
			type: 'get',
			success: (res) => {
				$('#pesanan').html('');
				if (!(res.hasOwnProperty('success'))) {
					no = 0;
					$.each(res, (v, data) => {
						no += 1;
						$('#pesanan').append(`
							<tr>
								<td>` + no + `</td>
								<td>` + data.anggota_nama + `</td>
								<td>` + moment(data.keranjang_tgl_pesan).format("DD MMMM YYYY HH:mm") + `</td>
								<td><a href="javascript:;" onclick="daftarPesanan('` + data.anggota_id + `')">Lihat Pesanan</a></td>
							</tr>
							`);
					});
				} else {
					$('#pesanan').append(`
							<tr>
								<td colspan="4" align="center">Tidak Ada Pesanan!!</td>
							</tr>
							`);
				}
			}
		})
	}

	function daftarPesanan(id) {
		$('#modal-pesanan').modal('hide');
		$('#modal-daftar-pesanan').modal();
		$.ajax({
			url: BASE_URL + 'kasir/select_daftar_pesanan/' + id,
			type: 'get',
			success: (res) => {
				$('#pesanan-masuk').html('');
				no = 0;
				subtotal = 0;
				$.each(res, (v, data) => {
					no += 1;
					$('#pesanan-masuk').append(`
						<tr>
							<td>` + no + `</td>
							<td>` + data.barang_kode + `</td>
							<td>` + data.barang_nama + `</td>
							<td>` + data.barang_qty + `</td>
							<td>Rp. ` + ($.number(data.barang_harga)) + `</td>
							<td>Rp. ` + $.number(data.barang_harga * data.barang_qty) + `</td>
						</tr>
						`);

					subtotal = subtotal + (data.barang_harga * data.barang_qty);
					anggota_id = data.anggota_kode + ' - ' + data.anggota_nama;
					id_anggota = data.anggota_id;
				});
				$('#subtotal').html('Rp. ' + ($.number(subtotal)))
				$('#button-proses').html(`<button type="button" class="btn btn-sm btn-primary float-right" onclick="prosesPesanan('` + id_anggota + `','` + anggota_id + `')">Proses</button><button type="button" class="btn btn-sm btn-success float-right mr-4" onclick="printPesanan('` + id_anggota + `')">Print</button>`);
			}
		})
	}

	function prosesPesanan(id, anggota) {
		Swal.fire({
			title: 'Proses Pesanan',
			text: 'Ingin memproses pesanan ini?',
			icon: "question",
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Proses',
			cancelButtonText: 'Batal',
		}).then(function(res) {
			if (res.value) {
				$('#modal-daftar-pesanan').modal('hide');
				$('#input-pesanan').val('pesanan');
				addPesanan(id);
				$("#penjualan_anggota_id").select2("trigger", "select", {
					data: {
						id: id,
						text: anggota
					}
				});
				// $.ajax({
				// 	url : BASE_URL+'kasir/proses_pesanan',
				// 	type : "POST",
				// 	data : {id : id},
				// 	dataType : 'json',
				// 	success : (res) => {
				// 		if(res.success){
				// 			HELPER.showMessage({
				// 				success : true,
				// 				title : 'Berhasil',
				// 				message : 'Pesanan Berhasil di Proses',
				// 				callback : (res) =>{
				// 					hitungPesanan()
				// 					$('#modal-daftar-pesanan').modal('hide');
				// 				}
				// 			})
				// 		}
				// 	}
				// })
			}
		})
	}

	function onCancelModal() {
		$('#modal-pesanan').modal();
		$('#modal-daftar-pesanan').modal('hide');
	}

	function hitungPesanan() {
		$.get(BASE_URL + 'kasir/count_pesanan', (res) => {
			if (res.success == false) {
				res = 0;
			}
			$('.notif').html(res)
		});
	}

	function addPesanan(id) {
		$('#table-detail_barang tbody').html('');
		$.ajax({
			url: BASE_URL + 'kasir/select_daftar_pesanan/' + id,
			type: 'get',
			success: (res) => {
				$.each(res, (i, data) => {
					row++;
					$('#table-detail_barang').append(`<tr class="barang_` + row + `">
						<td scope="row">
							<input type="hidden" class="form-control" name="penjualan_detail_id[` + row + `]" id="penjualan_detail_id_` + row + `">						
							<input type="hidden" class="form-control" name="penjualan_detail_jenis_barang[` + row + `]" id="penjualan_detail_jenis_barang_` + row + `">						
							<select class="form-control barang_id" name="penjualan_detail_barang_id[` + row + `]" id="penjualan_detail_barang_id_` + row + `" data-id="` + row + `" style="width: 260px;white-space: nowrap;"><option value="` + data.barang_id + `">` + data.barang_nama + `</option></select></td>
						<td><select class="form-control" name="penjualan_detail_satuan[` + row + `]" id="penjualan_detail_satuan_` + row + `" style="width: 100%" ></select>
							<input type="hidden" class="form-control" name="penjualan_detail_satuan_kode[` + row + `]" id="penjualan_detail_satuan_kode_` + row + `" >						
						</td>					
						<td><input class="form-control number" type="text" name="penjualan_detail_harga[` + row + `]" id="penjualan_detail_harga_` + row + `" readonly=""></td>
						<td>
							<input class="form-control number qty" type="text" name="penjualan_detail_qty[` + row + `]" id="penjualan_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" value="` + data.barang_qty + `">
							<input class="form-control number" type="hidden" name="penjualan_detail_qty_barang[` + row + `]" id="penjualan_detail_qty_barang_` + row + `">							
	                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_harga_beli[` + row + `]" id="penjualan_detail_harga_beli_` + row + `">
	                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_hpp[` + row + `]" id="penjualan_detail_hpp_` + row + `">				
						</td>
						<td>
							<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[` + row + `]" id="penjualan_detail_potongan_persen_` + row + `" onkeyup="countRow('` + row + `')">
							<input class="form-control number" type="hidden" name="penjualan_detail_potongan[` + row + `]" id="penjualan_detail_potongan_` + row + `">
						</td>
						<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[` + row + `]" id="penjualan_detail_subtotal_` + row + `" readonly=""></td>
						<td style="text-align: center;"><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
	                  		<span class="la la-trash"></span> Hapus</a></td>
					</tr>`);
					setSatuan(row)
					getHarga(row)
					countRow(row)
					setBarang(row);
					$('.disc').number(true, 2);
				})
			}
		});
	}

	function printPesanan(id) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'kasir/tprint/' + id,
			type: 'post',
			success: function(res) {
				HELPER.unblock();
				if (res.tprint) {
					$('#printArea').html(res.tprint);
					var WinPrint = window.open('', '', 'width=900,height=650');
					WinPrint.document.write($('#printArea').html());
					WinPrint.document.close();
					WinPrint.focus();
					WinPrint.print();
					WinPrint.close();
				}
			}
		})
	}

	function handlePembayaran() {
		let metode = $('#penjualan_metode');
		let divBank = $('#divBank');


		if (metode.val() == 'B') {
			divBank.show();
		} else {
			divBank.hide();
		}
	}
</script>