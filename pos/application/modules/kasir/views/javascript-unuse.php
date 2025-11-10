<script type="module">
	// import {
	// 	io
	// } from "<?= base_url() ?>socketserver/node_modules/socket.io/client-dist/socket.io.esm.min.js";

	// var socket = io("<?= $_ENV['SOCKET_CONNECT']; ?>"); //Server Sekawan
	// // var socket = io("wss://socket.monitorpajak.com"); //Server AWS
	// // var socket = io("https://192.168.100.59:3000"); //IP Sena

	// window.socket = socket;

	// // Web socket
	// const userdata = <?= json_encode($this->session->userdata()); ?>;
	// let dataDiri = {
	// 	'user_id': userdata.user_id,
	// 	'toko_id': userdata.toko.toko_id,
	// 	'toko_nama': userdata.toko.toko_nama,
	// 	'user_nama': userdata.user_nama,
	// };

	// window.userdata = dataDiri;

	// socket.on("hello", (arg) => {});
</script>

<script type="text/javascript">
	$('.datepickermt').datepicker({
		rtl: KTUtil.isRTL(),
		todayHighlight: true,
		format: 'yyyy-mm-dd',
	}).datepicker("setDate", new Date());

	let idBayar = [];
	let totalBayar = [];
	let totalBayarIncludePajak = [];
	let totalQty = [];
	let totalItem = 0;
	let setRequired = [
		'penjualan_metode',
		'penjualan_total_bayar',
	];
	let lazyLoad = {
		currentpage: 1,
		nextpage: 2,
		possiblenext: true,
		isLoading: false,
	}

	let divBank = $('#divBank');
	let divBayarBank = $('#divBayarBank');
	let divBayarTunai = $('#divBayarTunai');
	let divJatuhTempo = $('#divJatuhTempo');
	let countOnline = 0;
	let isRent = false;
	divJatuhTempo.hide();

	$(function() {
		$('#penjualan_metode').val('B');
		$('.select2').select2();
		$('#dropdown-filter-menu').on('click', function(e) {
			e.stopPropagation();
		});

		// Disable behaviour of button inside form
		$(':button').click(function() {
			event.preventDefault();
		});

		// Uncheck exclude pajak
		$("#includePajak").prop("checked", false);

		// Setup numbering
		let saldo_voucher;
		$('#penjualan_total_potongan_persen').number(true);
		$('#penjualan_pajak_persen').number(true);
		$('#penjualan_total_bayar').number(true);
		$('#penjualan_total_bayar_bank').number(true);
		$('#penjualan_total_bayar_tunai').number(true);
		$('#penjualan_total_bayar_bank').number(true);
		$('#penjualan_total_bayar_tunai').number(true);
		$('#penjualan_jasa').number(true);
		$('#pengajuan_jasa').val("<?php echo $this->config->item('base_jasa_pinjaman'); ?>");
		$('#kt_dashboard_daterangepicker_date').html(moment('<?php echo $this->config->item('base_penjualan_tanggal') ?>').format('MMM D'))
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
			'penjualan_jasa',
			'penjualan_total_bayar_bank'
		];

		HELPER.setRequired([
			'penjualan_metode',
			'penjualan_total_bayar'
		]);

		HELPER.api = {
			table: BASE_URL + 'transaksipenjualan/',
			read: BASE_URL + 'transaksipenjualan/edit_detail',
			store: BASE_URL + 'transaksipenjualan/store',
			update: BASE_URL + 'transaksipenjualan/update',
			destroy: BASE_URL + 'transaksipenjualan/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
			rekening: BASE_URL + 'rekening/select_ajax',
			shortcutBayar: BASE_URL + 'transaksipenjualan/shortcutBayar',
			checkStock: BASE_URL + 'barang/read',
		}
		$('input.number').number(true);
		$('.disc').number(true, 2);

		HELPER.ajaxCombo({
			el: '#penjualan_anggota_id',
			url: BASE_URL + 'transaksipenjualan/select_ajax',
			placeholder: 'nasabah',
			clear: false
		});

		HELPER.ajaxCombo({
			el: '#pos_penjualan_customer_id',
			url: BASE_URL + 'customer/select_ajax',
			clear: true
		});

		// HELPER.create_combo_akun({
		// 	el: 'category',
		// 	valueField: 'id',
		// 	displayField: 'text',
		// 	parentField: 'parent',
		// 	childField: 'child',
		// 	url: HELPER.api.get_parent,
		// 	withNull: false,
		// 	nesting: true,
		// 	chosen: true,
		// 	callback: function() {
		// 		$('#category').select2('destroy');
		// 		$('#category').prepend('<option selected=""></option>').select2({
		// 			placeholder: 'Pilih Kategori',
		// 			allowClear: true,
		// 		});
		// 	}
		// });

		HELPER.ajax({
			url: HELPER.api.get_parent,
			type: 'GET',
			success: function(response) {
				$('#filter-by-category').append(`
				<input type="text" value="0" id="category" name="category" hidden>
				<button type="button" class="btn btn-outline-primary rounded-pill active" data-filter="0" onclick="changeFilterByCategory(this)">Semua</button>
				`);
				$.each(response.data, function(index, category) {
					const text = capitalize(category.text.replace(/[^a-z0-9]/gi, ''));
					$('#filter-by-category').append(`
						<button type="button" class="btn btn-outline-primary rounded-pill" data-filter="${category.id}" onclick="changeFilterByCategory(this)">${text}</button>
					`);
				});

				$('#filter-by-category').append(`
				<div class="dropdown dropdown-inline">
					<button type="button" class="btn btn-outline-primary rounded-pill d-flex flex-center dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Lainnya
						<span class="material-icons-outlined">arrow_drop_down</span>
					</button>
					<div class="dropdown-menu dropdown-menu-md dropdown-menu-filter">
						<ul class="navi navi-hover"></ul>
					</div>
				</div>
				`);

				
				$.each(response.data, function(categoryIndex, category) {
					let children = [];
					if (category.children) children = getChildren(category);
					
					$.each(children, function(childIndex, child) {
						const text = capitalize(child.text.replace(/[^a-z0-9]/gi, ''));
						$('#filter-by-category').find('ul.navi').append(`
						<li class="navi-item">
							<button type="button" class="navi-link btn w-100 text-left" data-filter="${child.id}" onclick="changeFilterByCategory(this)">
								<span class="navi-text">${text}</span>
							</button>
						</li>
						`);
					});
				});
			}
		});

		$.post(BASE_URL + 'satuan/select', function(res) {
			satuan = res.data;
			setBarang();
		})

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
		});

		$("#menuHandler").scroll(function(event) {
			var element = event.target;
			if (element.scrollHeight - element.scrollTop === element.clientHeight) {
				if (lazyLoad.possiblenext && !lazyLoad.isLoading) {
					loadMenu(lazyLoad.nextpage);
				}
			}
		});

		HELPER.ajaxCombo({
			el: '#penjualan_bank',
			url: HELPER.api.rekening,
		});

		// $('#order_by').select2({
		// 	placeholder: "Pilih Berdasarkan",
		// 	allowClear: true
		// });

		$('#qty').trigger('focus');
		var myVar = setInterval(myTimer, 1000);
		st_edit = false;
		vdt = {};
		setDate();
		loadMenu();

		function capitalize(string) {
			return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
		}

		function getChildren(parent){
			let children = [];

			$.each(parent.child, function(index, child) {
				child.tipe === 'parent' && children.push(child);
				const newChild = getChildren(child);
				if (newChild.length > 0) children = [...children, ...newChild];
			});

			return children;
		}
	});

	//Function Section
	function handleIncludePajak(params) {
		$('.hargaBeli').each(function(i, obj) {
			let cRow = parseInt($(`#${obj.id}`).data('row'));
			let cHarga = parseInt($(`#${obj.id}`).val());
			let nilaiPajak = parseInt($('#penjualan_pajak_persen').val());
			let nilaiPotong = cHarga * (100 / (100 + nilaiPajak));
			let includePajak = cHarga - nilaiPotong;

			if ($('#includePajak').is(":checked")) {
				$(`#${obj.id}`).data('inpajak', includePajak);
				let res = cHarga - includePajak;
				$(`#${obj.id}`).val(res);
				totalBayar[cRow] = res;
				console.log(totalBayar);
			} else {
				includePajak = $(`#${obj.id}`).data('inpajak')
				let res = cHarga + includePajak;
				$(`#${obj.id}`).val(res);
				totalBayar[cRow] = res;
				console.log(totalBayar);
			}

			countPrice(cRow, 0, 0, 0);
		});
	}

	function initMenu(param) {
		if (param.stok_now == null || param.stok_now == '') {
			param.stok_now = 0.00;
		}
		let jenisStok;
		if (param.jenis_include_stok == 1) {
			jenisStok = `<span class="
							${parseInt(param.stok_now) < 1 && "text-danger"} 
							${(parseInt(param.stok_now) > 0 && parseInt(param.stok_now) <= parseInt(param.barang_stok_min)) && "text-warning"} 
							${(parseInt(param.stok_now) > 10) && "text-success"}
							font-weight-bolder">${HELPER.toCurrency(param.stok_now)}</span>`;
		} else if (param.jenis_include_stok == 2) {
			if (param.barang_aktif == 2) {
				jenisStok = `<span class="font-weight-bolder" style="font-color:#1bc5bd"><i style="font-color:#1bc5bd" class="fas fa-sm fa-check text-success"></i></span>`;
			} else {
				jenisStok = `<span class="font-weight-bolder" style="font-color:#1bc5bd"><i style="font-color:#1bc5bd" class="fas fa-sm fa-times text-danger"></i></span>`;
			}
		} else {
			jenisStok = `<span class="font-weight-bolder" style="font-color:#1bc5bd"><i style="font-color:#1bc5bd" class="fas fa-sm fa-infinity"></i></span>`;
		}

		const product = {
			id: String(param.id), 
			barang_nama: String(param.barang_nama), 
			barang_harga: String(param.barang_harga), 
			stok_now: param.stok_now, 
			jenis_include_stok: param.jenis_include_stok,
			barang_aktif: param.barang_aktif, 
		};

		const html = `
			<div class="col-md-3 p-2">
				<div class="card card-custom bgi-no-repeat border" 
				role="button"
				onclick='initOrder(${JSON.stringify(product)});'>
					<div class="card-body p-5">
						<div class="row">
							<div class="col-12">
								<span class="d-block font-weight-bolder">${param.barang_nama.toUpperCase()}</span>
								<span class="d-block font-weight-bold text-muted font-size-xs">Kode : ${param.barang_kode.toUpperCase()}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-8 d-flex flex-column">
								<span class="font-size-xs d-block mb-0 mt-auto">
									Stok : 
									${jenisStok}
								</span>
								<span class="font-size-sm text-primary font-weight-bolder"> ${HELPER.toCurrency(param.barang_harga)}</span>
							</div>
							<div class="col-4 p-0">
								<img loading="lazy" class="img-fluid rounded" style="width:50px; height:50px; object-fit:cover;" src="${param.barang_thumbnail != null ? '..'+param.barang_thumbnail : ''}" onerror="imgError(this)"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		`;
		$('#menuHandler').append(html);
	}

	function setSatuan(row, barang_id, satuanEdit = 0) {
		jual = parseFloat($('#penjualan_detail_qty_' + row).val());
		$.post(BASE_URL + 'barang/list_satuan_harga', {
			barang_id: barang_id
		}, function(res) {
			html = '';
			$('#penjualan_detail_satuan_kode_' + row).val(res.data[0].barang_satuan_kode);
			$.each(res.data, function(i, v) {
				if (satuanEdit == 0) {
					html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_jual="` + v.barang_satuan_harga_jual + `" data-barang_satuan_harga_beli="` + v.barang_harga_pokok + `" data-barang_satuan_disc="` + v.barang_satuan_disc + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `" data-barang_kategori="` + v.kategori_barang_parent + `">` + v.barang_satuan_kode + `(` + v.barang_satuan_konversi + `)` + `</option>`
				} else {
					let selected = '';
					if (v.barang_satuan_id == satuanEdit) {
						selected = 'selected';
					}
					html += `<option ${selected} value="` + v.barang_satuan_id + `" data-barang_satuan_harga_jual="` + v.barang_satuan_harga_jual + `" data-barang_satuan_harga_beli="` + v.barang_harga_pokok + `" data-barang_satuan_disc="` + v.barang_satuan_disc + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `" data-barang_kategori="` + v.kategori_barang_parent + `">` + v.barang_satuan_kode + `(` + v.barang_satuan_konversi + `)` + `</option>`
				}
			})
			$('#penjualan_detail_satuan_' + row).html(html);
			$('#penjualan_detail_satuan_' + row).select2();
		})
	}

	function satuanKonversi(param, row, qty, jenis_include_stok) { // param stok_now temporary unuse
		let data = {
			barang_satuan_id: param.value
		};

		$.post(BASE_URL + 'transaksipenjualan/get_barang_satuan_data', data, function(data) {
			let barang_harga = data.barang_satuan_harga_jual;
			let konversi = data.barang_satuan_konversi;
			let satuanKode = data.barang_satuan_kode;

			$('#penjualan_detail_harga_beli_' + row).val(barang_harga);

			$('#updateHarga_' + row).empty();
			$('#updateHarga_' + row).append(`
			<input type="hidden" readonly name="jenis_include_stok[${row}]" id="jenis_include_stok${row}" value="${jenis_include_stok}">
			<input type="hidden" readonly name="penjualan_detail_satuan_kode[${row}]" value="${satuanKode}" id="penjualan_detail_satuan_kode_${row}">
			<input type="hidden" readonly name="penjualan_detail_id[${row}]" id="penjualan_detail_id_${row}">
			<input type="hidden" readonly name="konversi_barang[${row}]" value="${konversi}" id="konversi_barang_${row}">
			<input type="number" value="${qty}" id="penjualan_detail_qty_barang_${row}" onchange="countPrice(${row}, ${barang_harga}, 0)" name="penjualan_detail_qty_barang[${row}]" class="form-control qty">
			`);

			countPrice(row, barang_harga, 0);
		});
	}

	function initOrder({id, barang_nama, barang_harga, stok_now, jenis_include_stok, barang_aktif}) {
		$('#includePajak').prop('checked', false);
		let LSToko = JSON.parse(localStorage.getItem('toko'));

		if (isRent == true && jenis_include_stok != 2) {
			swal.fire('Tambah Order Gagal', `Tidak bisa mencampur transaksi rental dengan yang lain`, 'warning');
			return;
		}

		if (jenis_include_stok == 1) {
			if (stok_now <= 0) {
				swal.fire('Tambah Order Gagal', `Stok produk yang dipilih habis	`, 'warning');
				return;
			}
		}

		if (jenis_include_stok == 2) {
			onReset();
			isRent = true;
			onRent();
			if (barang_aktif == 3) {
				swal.fire('Tambah Order Gagal', `Produk saat ini tidak tersedia	`, 'warning');
				return;
			}
		}

		let qty = 1;
		setSatuan(row, id);

		inputHarga = `
		<input id="penjualan_detail_harga_beli_${row}" type="text" data-row="${row}" data-inpajak="${parseInt(barang_harga *(LSToko.jenis_tarif / 100))}" data-defaultHarga="${barang_harga}"  name="penjualan_detail_harga_beli[${row}]" onkeyup="countPriceFlexiblePrice(${row}, ${barang_harga}, 0, ${stok_now}, ${jenis_include_stok})" value="${barang_harga}" class="form-control number hargaBeli">
		`;

		inputQty = `<input type="number" value="${qty}" id="penjualan_detail_qty_barang_${row}" onchange="countPriceFlexiblePrice(${row}, ${barang_harga}, 0, ${stok_now}, ${jenis_include_stok})" name="penjualan_detail_qty_barang[${row}]" class="form-control qty">`;

		let html = `
			<tr class="order_${row}">
				<td id="barang_nama_${row}" style="vertical-align:middle;">
					<input type="hidden" readonly name="penjualan_detail_barang_id[${row}]" id="penjualan_detail_barang_id_${row}" value="${id}">
						<span>${barang_nama}</span>
				</td>
				<td>
				<select class="form-control" onchange="satuanKonversi(this, ${row}, ${qty}, ${jenis_include_stok})" name="penjualan_detail_satuan[${row}]" id="penjualan_detail_satuan_${row}">
				<option value="">-Pilih Satuan-</option>
				</select>
				</td>
				<td>
					${inputHarga}
				</td>
				<td id="updateHarga_${row}">
					<input type="hidden" readonly name="jenis_include_stok[${row}]" id="jenis_include_stok${row}" value="${jenis_include_stok}">
					<input type="hidden" readonly name="penjualan_detail_satuan_kode[${row}]" id="penjualan_detail_satuan_kode_${row}">
					<input type="hidden" readonly name="penjualan_detail_id[${row}]" id="penjualan_detail_id_${row}">
					<input type="hidden" readonly name="konversi_barang[${row}]" value="1" id="konversi_barang_${row}">
					${inputQty}
				</td>
				<td>
					<input type="text" readonly="" style="background-color: #eaeaea;" class="form-control number" id="total_row_${row}" name="penjualan_detail_subtotal[${row}]" value="${barang_harga * qty}"/>
				</td>
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

		if (flag < 1) { // jika belum ada data dalam table order
			idBayar[row] = id;
			totalBayar[row] = barang_harga;
			totalBayarIncludePajak[row] = barang_harga;
			totalQty[row] = qty;
			$('#orderHandler').append(html);
			$('#orders').append(`
			<div class="order_${row} align-items-center" style="display: grid; grid-template-columns: 40px 1fr 1fr 1fr; gap: 10px;">
				<div class="w-100" style="height: 40px;">
					<img src="https://assets.klikindomaret.com/products/10005660/10005660_1.jpg" alt="" class="w-100 h-100 rounded" style="object-fit: cover;">
				</div>
				<div class="font-weight-black" style="font-size: 1.2rem" id="barang_nama_${row}">
					<input type="hidden" readonly name="penjualan_detail_barang_id[${row}]" id="penjualan_detail_barang_id_${row}" value="${id}">
					<span>${barang_nama}</span>
				</div>
				<div class="d-flex align-items-center" style="gap: 15px;">
					<button class="btn btn-order">
						<div class="bg-white rounded d-flex align-items-center justify-content-center" style="width: 25px; height: 25px;">
							<svg width="13" height="2" viewBox="0 0 13 2" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.5 1.83171H1.49996C1.27895 1.83171 1.06698 1.74391 0.910704 1.58763C0.754424 1.43135 0.666626 1.21939 0.666626 0.998373C0.666626 0.777359 0.754424 0.565397 0.910704 0.409117C1.06698 0.252837 1.27895 0.165039 1.49996 0.165039H11.5C11.721 0.165039 11.9329 0.252837 12.0892 0.409117C12.2455 0.565397 12.3333 0.777359 12.3333 0.998373C12.3333 1.21939 12.2455 1.43135 12.0892 1.58763C11.9329 1.74391 11.721 1.83171 11.5 1.83171Z" fill="currentColor"/>
							</svg>
						</div>
					</button>
					<span class="h5 font-weight-bolder mb-0">1</span>
					<button class="btn btn-order">
						<div class="bg-white rounded d-flex align-items-center justify-content-center" style="width: 25px; height: 25px;">
							<svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.5001 6.83171H7.33341V10.9984C7.33341 11.2194 7.24562 11.4313 7.08934 11.5876C6.93306 11.7439 6.7211 11.8317 6.50008 11.8317C6.27907 11.8317 6.06711 11.7439 5.91083 11.5876C5.75455 11.4313 5.66675 11.2194 5.66675 10.9984V6.83171H1.50008C1.27907 6.83171 1.06711 6.74391 0.910826 6.58763C0.754546 6.43135 0.666748 6.21939 0.666748 5.99837C0.666748 5.77736 0.754546 5.5654 0.910826 5.40912C1.06711 5.25284 1.27907 5.16504 1.50008 5.16504H5.66675V0.998372C5.66675 0.777359 5.75455 0.565397 5.91083 0.409117C6.06711 0.252836 6.27907 0.165039 6.50008 0.165039C6.7211 0.165039 6.93306 0.252836 7.08934 0.409117C7.24562 0.565397 7.33341 0.777359 7.33341 0.998372V5.16504H11.5001C11.7211 5.16504 11.9331 5.25284 12.0893 5.40912C12.2456 5.5654 12.3334 5.77736 12.3334 5.99837C12.3334 6.21939 12.2456 6.43135 12.0893 6.58763C11.9331 6.74391 11.7211 6.83171 11.5001 6.83171Z" fill="currentColor"/>
							</svg>
						</div>
					</button>
				</div>
				<div class="font-weight-black" style="font-size: 1.3rem">Rp. <span>25.000</span></div>
			</div>
			`);
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
			$("#total_row_" + cIndex).val(`${$.number(barang_harga * cQty)}`);
			countPrice(cIndex, barang_harga, 0, stok_now);
		}
		countDiscount();
		countKembalian();
		countQty();

		$('.number').number(true);
		$('#penjualan_total_item').val(totalItem);
	}

	function countPrice(cRow, cHarga, s_press = 0, stokNow = 0) {
		let cQty = $('#penjualan_detail_qty_barang_' + cRow).val();
		cHarga = $(`#penjualan_detail_harga_beli_${cRow}`).val();

		if (cQty < 0) {
			$('#penjualan_detail_qty_barang_' + cRow).val(0);
			cQty = 0;
			$("#total_row_" + cRow).val("0");
		} else {
			let cTotal = cQty * cHarga;

			$("#total_row_" + cRow).val(`${cTotal}`);

			totalBayar[cRow] = cTotal;
			totalQty[cRow] = cQty;
		}
		countQty();
		countDiscount();
		countKembalian();
	}

	const checkStock = (barang_id, qtyBuy, cRow) => {
		HELPER.ajax({
			url: HELPER.api.checkStock,
			method: 'POST',
			data: {
				barang_id: barang_id,
			},
			datatype: 'json',
			success: (res) => {
				if (parseInt(res.jenis_include_stok) == 1) {
					if (qtyBuy > parseInt(res.barang_stok)) {
						remOrder(null, 1, cRow);
						Swal.fire({
							title: 'Stok Barang',
							text: `Pembelian tidak dapat melebihi stok barang`,
							icon: 'info',
							confirmButtonText: 'OK',
						});
					}
				}

			}
		});
	}

	function countPriceFlexiblePrice(cRow, cHarga, s_press = 0, stokNow = 0, jenis_include_stok) {
		$('#includePajak').prop('checked', false);
		cHarga = parseInt($(`#penjualan_detail_harga_beli_${cRow}`).val());
		let cHargaIncludePajak = $(`#penjualan_detail_harga_beli_${cRow}`).data('defaultharga');
		let cQty = $(`#penjualan_detail_qty_barang_${cRow}`).val();
		let barang_id = $(`#penjualan_detail_barang_id_${cRow}`).val();

		// Check Stock
		let resCheckStock = checkStock(barang_id, cQty, cRow);

		if (jenis_include_stok == 2) {
			// moment get current date
			let date = moment().format('YYYY-MM-DD');
			$('#penjualan_jatuh_tempo').val(moment(date, "YYYY-MM-DD").add(cQty, 'days').format('YYYY-MM-DD'));
		}

		if (cQty < 0) {
			$('#penjualan_detail_qty_barang_' + cRow).val(0);
			cQty = 0;
			$("#total_row_" + cRow).val("0");
		} else {
			let cTotal = cQty * cHarga;
			let cTotalIncludePajak = cQty * cHargaIncludePajak;
			$("#total_row_" + cRow).val(`${cTotal}`);

			totalBayar[cRow] = cTotal;
			totalBayarIncludePajak[cRow] = cTotalIncludePajak;
			totalQty[cRow] = cQty;
		}

		countQty();
		countDiscount();
		countKembalian();
	}


	function countTotal(s_press = 0) {
		let total = 0;
		let jasa = parseInt($('#penjualan_jasa').val());

		$.each(totalBayar, function(i, v) {
			if (v !== undefined) {
				total += parseInt(v);
			}
		});
		$('#pembelian_total_bayar_display').text(total);
		$('#penjualan_total_harga').val(total);
		$('#penjualan_total_grand').val(total);
		return total;
	}

	function countJasa() {
		let result = $('#penjualan_jasa').val();
		if (result == 0 || result == '' || result == undefined) {
			return 0;
		} else {
			return parseInt(result);
		}
	}

	function countPajak() {
		let total = countTotal() + (countTotal() * (countJasa() / 100));
		let inputPajak = $('#penjualan_pajak_persen').val();
		let result = total * (inputPajak / 100);
		result = Math.round(result / 100) * 100;
		return result;
	}

	function countDiscount() {
		let total = countTotal() + (countTotal() * (countJasa() / 100));
		let pajak = countPajak();
		let inputDiscount = $('#penjualan_total_potongan_persen').val();
		let discount = total * (inputDiscount / 100);
		let result = total - discount + pajak;
		result = Math.round(result / 100) * 100;
		let penjualanMetode = $('#penjualan_metode').val();

		$('#pembelian_total_bayar_display').text(HELPER.toCurrency(result));
		$('#penjualan_total_grand').val(result);

		if (penjualanMetode == 'K') {
			$('#penjualan_total_bayar').val(result);
		}

		return result;
	}

	function countPriceIncludePajak(cRow, cHarga, s_press = 0, stokNow = 0) {
		cHarga = parseInt($(`#penjualan_detail_harga_beli_${cRow}`).val());
		let cQty = $(`#penjualan_detail_qty_barang_${cRow}`).val();

		if (cQty < 0) {
			$('#penjualan_detail_qty_barang_' + cRow).val(0);
			cQty = 0;
			$("#total_row_" + cRow).val("0");
		} else {
			let cTotal = cQty * cHarga;

			$("#total_row_" + cRow).val(`${cTotal}`);

			totalBayar[cRow] = cTotal;
			totalQty[cRow] = cQty;
		}

		countQty();
		countDiscountIncludePajak();
		countKembalianIncludePajak();
	}


	function countTotalIncludePajak(s_press = 0) {
		let total = 0;
		let jasa = parseInt($('#penjualan_jasa').val());

		$.each(totalBayarIncludePajak, function(i, v) {
			if (v !== undefined) {
				total += parseInt(v);
			}
		});
		$('#pembelian_total_bayar_display').text(total);
		$('#penjualan_total_harga').val(total);
		$('#penjualan_total_grand').val(total);
		return total;
	}

	function countPajakIncludePajak() {
		let total = countTotalIncludePajak() + (countTotalIncludePajak() * (countJasa() / 100));
		let inputPajak = $('#penjualan_pajak_persen').val();
		let result = total * (inputPajak / 100);
		result = Math.round(result / 100) * 100;
		return result;
	}

	function countDiscountIncludePajak() {
		let total = countTotalIncludePajak() + (countTotalIncludePajak() * (countJasa() / 100));
		let pajak = countPajakIncludePajak();
		let inputDiscount = $('#penjualan_total_potongan_persen').val();
		let discount = total * (inputDiscount / 100);
		let result = total - discount + pajak;
		result = Math.round(result / 100) * 100;
		let penjualanMetode = $('#penjualan_metode').val();

		$('#pembelian_total_bayar_display').text(HELPER.toCurrency(result));
		$('#penjualan_total_grand').val(result);

		if (penjualanMetode == 'K') {
			$('#penjualan_total_bayar').val(result);
		}

		return result;
	}

	function countKembalianIncludePajak() {
		let total = countDiscountIncludePajak();
		let totalBayar = $('#penjualan_total_bayar').val();
		let kembalian = totalBayar - total;
		kembalian = Math.round(kembalian / 100) * 100;

		if (kembalian > 0) {
			$('#penjualan_total_kembalian').val(kembalian);
		} else {
			$('#penjualan_total_kembalian').val(0);
		}

		return kembalian;
	}


	function countKembalian() {
		let total = countDiscount();
		let totalBayar = $('#penjualan_total_bayar').val();
		let kembalian = totalBayar - total;
		kembalian = Math.round(kembalian / 100) * 100;

		if (kembalian > 0) {
			$('#penjualan_total_kembalian').val(kembalian);
		} else {
			$('#penjualan_total_kembalian').val(0);
		}

		return kembalian;
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

	function loadMenu(page = 1) {
		loadingMenu(true);
		lazyLoad.isLoading = true;
		let valSearch = $('#valSearch').val();
		let valOrder = $("#order_by").val();
		let valKategori = $("#category").val();
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/load_menu',
			data: {
				valOrder,
				valKategori,
				valSearch,
				page,
			},
			type: 'post',
			success: function(res) {

				if (res.total_count < 1) {
					$('#menuHandler').append('<p class="mx-auto my-auto">Belum ada produk yang di tambahkan</p>');
					return;
				}

				let menu = res.items;

				lazyLoad.currentpage = page;
				lazyLoad.nextpage = page + 1;
				lazyLoad.possiblenext = true;
				lazyLoad.isLoading = false;
				if (res.total_count == 0 || res.total_count < 12) {
					lazyLoad.possiblenext = false;
				}


				$.each(menu, function(index, value) {
					initMenu(value);
				});
			},
			complete: function() {
				loadingMenu(false);
			}
		})
	}

	function changeFilterByCategory(el) {
		$("#category").val($(el).data('filter'));
		$("#filter-by-category").find('button').removeClass('active')
		$(el).addClass('active');
		$(el).hasClass('navi-link') && $(el).closest('.dropdown').find('.dropdown-toggle').addClass('active');
		searchMenu();
	}

	function changeOrderBy(el) {
		$("#order_by").val($(el).data('order'));
		$(el).closest('ul.navi').find('.navi-link').removeClass('active');
		$(el).addClass('active');
		searchMenu();
	}

	function searchMenu() {
		let valSearch = $('#valSearch').val();
		let valOrder = $("#order_by").val();
		let valKategori = $("#category").val();
		
		$('#menuHandler').empty();
		$('#menuHandler').append('<p class="mx-auto my-auto">Mohon tunggu</p>');

		lazyLoad = {
			currentpage: 1,
			nextpage: 2,
			possiblenext: true,
			isLoading: true,
		}


		$.ajax({
			url: BASE_URL + 'transaksipenjualan/load_menu',
			data: {
				valOrder,
				valKategori,
				valSearch: valSearch,
				page: lazyLoad.currentpage,
			},
			method: 'POST',
			datatype: 'json',
			timeout: 10000,
			success: function(res) {
				lazyLoad = {
					currentpage: lazyLoad.nextpage,
					nextpage: lazyLoad.currentpage + 1,
					possiblenext: true,
					isLoading: false,
				}
				if (res.total_count == 0 || res.total_count < 12) {
					lazyLoad.possiblenext = false;
				}

				if (res.total_count > 0) {
					$('#menuHandler').empty();
					let menu = res.items;

					$.each(menu, function(index, value) {
						initMenu(value);
					});
				} else {
					$('#menuHandler').empty();
					$('#menuHandler').append('<p class="mx-auto my-auto">Menu tidak ditemukan</p>');
				}
			},
			error: function(res) {
				swal.fire('Pencarian Gagal', 'Waktu pencarian melebihi batas, periksa koneksi anda', 'warning');
				$('#menuHandler').empty();
				$('#menuHandler').append('<p class="mx-auto my-auto">Timeout</p>');
			}
		});

		return false;
	}

	function remOrder(el, mode = '0', pCrow) {
		// event.preventDefault();
		isRent = false;
		onRent();

		if (mode == '0') {
			HELPER.confirm({
				message: 'Are you sure you want to delete?',
				callback: function(suc) {
					if (suc) {
						cRow = $(el).data('id');
						$('tr.order_' + cRow).remove();

						totalBayar[cRow] = undefined;
						totalBayarIncludePajak[cRow] = undefined;
						idBayar[cRow] = undefined;
						totalQty[cRow] = undefined;
						totalItem--;
						$('#penjualan_total_item').val(totalItem);

						countDiscount();
						countKembalian();
						countQty();
					}
				}
			});
		} else {
			cRow = pCrow;
			$('tr.order_' + cRow).remove();
			totalBayar[cRow] = undefined;
			totalBayarIncludePajak[cRow] = undefined;
			idBayar[cRow] = undefined;
			totalQty[cRow] = undefined;
			totalItem--;
			$('#penjualan_total_item').val(totalItem);

			countDiscount();
			countKembalian();
			countQty();
		}


	}

	function resetOrder() {
		idBayar = [];
		totalBayar = [];
		totalQty = [];
		totalItem = 0;
		orderHandler = $('#orderHandler');
		orderHandler.empty();


		$('#pembelian_total_bayar_display').text(0);

	}

	function onReset(callbackCombo = null) {
		st_edit = false;
		HELPER.api.store = BASE_URL + 'transaksipenjualan/store';
		$.each(HELPER.fields, function(i, v) {
			if (v !== 'penjualan_pajak_persen') {
				$('#' + v).val('');
			}
		});
		$('#orderHandler').empty();
		$('#customerDiv').empty();
		$('#penjualan_bank').empty();
		$('#customerDiv').append(`
			<select name="pos_penjualan_customer_id" id="pos_penjualan_customer_id" class="form-control h-100"></select>
		`);
		HELPER.ajaxCombo({
			el: '#pos_penjualan_customer_id',
			url: BASE_URL + 'customer/select_ajax',
			clear: true,
		});

		idBayar = [];
		totalBayar = [];
		totalQty = [];
		totalItem = 0;
		orderHandler = $('#orderHandler');
		orderHandler.empty();
		$('#divBank').hide();
		$('#pembelian_total_bayar_display').text(0);
		$('#penjualan_metode').val('B').trigger('change');
	}

	function loadTable() { //load daftar penjualan 
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
						return '' + HELPER.toCurrency(full['penjualan_total_grand']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						let platform = '<button onclick="event.preventDefault()" class="btn btn-primary btn-sm btn-block" style="width:100px"><span class="fas fa-desktop"></span> Desktop</button>';
						if (full['penjualan_platform'] == 'Mobile') {
							platform = '<button onclick="event.preventDefault()" class="btn btn-primary btn-sm btn-block" style="width:100px"><span class="fas fa-mobile-alt"></span> Mobile</button>';
						}
						return platform;
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						var html = `
							<a href="javascript:;" class="btn btn-sm btn-info" title="Print" onclick="onPrint('` + full['penjualan_id'] + `')" >
								<span class="fas fa-print"></span>
							</a>`;
						// html += `
						// 	<a href="javascript:;" class="btn btn-sm btn-primary" title="Edit" onclick="onEdit(this)" >
						// 		<span class="fas fa-pen"></span> 
						// 	</a>`;
						// html += `
						// 	<a href="javascript:;" class="btn btn-sm btn-danger" onclick="batalTransaksi('` + full['penjualan_id'] + `')" title="Batal Transaksi" >
						// 		<span class="fas fa-times"></span>
						// 	</a>	
						// `
						return html;
					},
				},

			],
		});
	}

	function loadTableRental() { //load daftar penjualan 
		HELPER.initTable({
			el: "table-rental",
			url: BASE_URL + 'transaksipenjualan/loadRental',
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
						return full['barang_nama'];
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
						return full['penjualan_total_potongan_persen'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return '' + HELPER.toCurrency(full['penjualan_total_grand']);
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						let platform = '<button onclick="event.preventDefault()" class="btn btn-primary btn-sm btn-block" style="width:100px"><span class="fas fa-desktop"></span> Desktop</button>';
						if (full['penjualan_platform'] == 'Mobile') {
							platform = '<button onclick="event.preventDefault()" class="btn btn-primary btn-sm btn-block" style="width:100px"><span class="fas fa-mobile-alt"></span> Mobile</button>';
						}
						return platform;
					},
				},
				{
					targets: 8,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						var html = `
							<a href="javascript:;" class="btn btn-sm btn-info" title="Print" onclick="onPrint('` + full['penjualan_id'] + `')" >
								<span class="fas fa-print"></span>
							</a>`;

						if (full['penjualan_bayar_sisa'] > 0) {
							html += `
							<a href="javascript:;" class="btn btn-sm btn-warning" title="Print" onclick="onPay('` + full['penjualan_id'] + `')" >
								<span class="fas fa-money-bill"></span>
								Bayar Transaksi
							</a>`;
						} else {
							html += `
							<a href="javascript:;" class="btn btn-sm btn-success" title="Print" onclick="Swal.fire({
							title: 'Transaksi',
							text: 'Transaksi ini telah lunas',
							icon: 'info',
						});" >
								<span class="fas fa-check"></span>
								Transaksi Lunas
							</a>`;
						}


						return html;
					},
				},

			],
		});
	}

	function batalTransaksi(id_penjualan) {
		Swal.fire({
			title: 'Warning',
			text: "Apakah anda yakin akan membatalkan Transaksi?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Batalkan Transaksi!',
			cancelButtonText: 'Batal',
			reverseButtons: true,
		}).then((result) => {
			if (result.isConfirmed) {
				$.post(BASE_URL + 'transaksipenjualan/batal_transaksi/', {
					penjualan_id: id_penjualan
				}, function(res) {
					$('#menuHandler').empty();
					loadMenu();
					if (res.status == true) {
						Swal.fire(
							'Berhasil!',
							'Transaksi telah dibatalkan.',
							'success'
						)
						$('#modal-penjualan').modal('hide');
					} else {
						Swal.fire(
							'Gagal!',
							'Transaksi gagal dibatalkan.',
							'warning'
						)
					}
				});
			}
		})
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				onReset(() => {});
				if (res.parent.customer) {
					$("#pos_penjualan_customer_id").select2("trigger", "select", {
						data: {
							id: res.parent.customer.customer_id,
							text: `${res.parent.customer.customer_kode} - ${res.parent.customer.customer_nama}`
						}
					});
				}
				// return;
				st_edit = true;
				HELPER.api.store = HELPER.api.update;
				idBayar = [];
				totalBayar = [];
				totalQty = [];
				totalItem = 0;
				let detail = res.detail.data;


				// Set input parent
				$('#penjualan_total_item').val(res.parent.penjualan_total_item);
				$('#penjualan_id').val(res.parent.penjualan_id);
				$('#penjualan_kode').val(res.parent.penjualan_kode);
				$('#penjualan_metode').val(res.parent.penjualan_metode);
				$('#penjualan_total_potongan_persen').val(res.parent.penjualan_total_potongan_persen);
				$('#penjualan_jatuh_tempo').val(res.parent.penjualan_jatuh_tempo);
				$('#penjualan_total_bayar_tunai').val(res.parent.penjualan_total_bayar_tunai);
				$('#penjualan_total_bayar_bank').val(res.parent.penjualan_total_bayar_bank);
				$('#penjualan_total_bayar_bank').val(res.parent.penjualan_total_bayar_bank);
				$('#penjualan_jasa').val(res.parent.penjualan_jasa);
				$('#penjualan_total_bayar').val(res.parent.penjualan_jasa);
				$('#penjualan_bank').val(res.parent.penjualan_bank);
				$('#penjualan_tuanggal').val(res.parent.penjualan_tanggal);


				$('#penjualan_total_bayar_bank').trigger('change');
				handlePembayaran();

				$('#modal-penjualan').modal('hide');
				// return;

				$.each(detail, function(i, v) {
					idBayar[row] = v.penjualan_detail_barang_id;
					totalBayar[row] = v.barang_satuan_harga_beli;
					totalQty[row] = v.penjualan_detail_qty_barang;

					setSatuan(row, v.penjualan_detail_barang_id, v.penjualan_detail_satuan);


					$('#orderHandler').append(
						`
						<tr class="order_${row}">
							<td id="barang_nama_${row}" style="vertical-align:middle;">
								<input type="hidden" readonly name="penjualan_detail_barang_id[${row}]" id="penjualan_detail_barang_id_${row}" value="${v.penjualan_detail_barang_id}">
									<span>${v.barang_nama}</span>
							</td>
							<td>
							<select class="form-control" onchange="satuanKonversi(this, ${row}, ${v.penjualan_detail_qty_barang}, ${v.barang_stok})" name="penjualan_detail_satuan[${row}]" id="penjualan_detail_satuan_${row}">
							<option value="">-Pilih Satuan-</option>
							</select>
							</td>
							<td>
								<input id="penjualan_detail_harga_beli_${row}" type="text" readonly style="background-color: #eaeaea;" name="penjualan_detail_harga_beli[${row}]" value="${HELPER.toCurrency(v.barang_harga)}" class="form-control">
								
							</td>
							<td id="updateHarga_${row}">
								<input type="hidden" readonly name="penjualan_detail_satuan_kode[${row}]" id="penjualan_detail_satuan_kode_${row}">
								<input type="hidden" readonly name="penjualan_detail_id[${row}]" id="penjualan_detail_id_${row}">
								<input type="hidden" readonly name="konversi_barang[${row}]" value="1" id="konversi_barang_${row}">
								<input type="number" value="${parseInt(v.penjualan_detail_qty_barang)}" id="penjualan_detail_qty_barang_${row}" onchange="countPrice(${row}, ${v.barang_harga}, 0, ${v.barang_stok})" name="penjualan_detail_qty_barang[${row}]" class="form-control qty">
							</td>
							<td>
								<input type="text" readonly="" style="background-color: #eaeaea;" class="form-control" id="total_row_${row}" value="${HELPER.toCurrency(v.barang_harga * v.penjualan_detail_qty_barang)}"/>
							</td>
							<td>
								<span class="btn btn-transparent-warning font-weight-bold" data-id="${row}" onclick="remOrder(this);" btn-sm ml-1">Hapus</button>
							</td>
						</tr>
						`
					);
					countPrice(row, v.barang_harga);
					row++;
					totalItem++;
				});


				$('#modal-penjualan').modal('hide');
				HELPER.unblock()
			}
		})
	}


	function sumBayar() {

		let bayarBank = parseInt($('#penjualan_total_bayar_bank').val());
		let bayarTunai = parseInt($('#penjualan_total_bayar_tunai').val());
		let totalBayar = $('#penjualan_total_bayar');

		// check if one of variable is less than 0
		if (bayarBank < 0 || bayarTunai < 0) {
			Swal.fire({
				icon: 'info',
				title: 'Oops...',
				text: 'Total bayar tidak boleh kurang dari 0',
			});

			// set tag input to 0
			$('#penjualan_total_bayar_bank').val(0);
			$('#penjualan_total_bayar_tunai').val(0);
			return;
		}


		totalBayar.val(parseInt(bayarBank + bayarTunai));
		countKembalian();
	}

	function handlePembayaran() {
		let metode = $('#penjualan_metode');
		let divBank = $('#divBank');
		let divBayarBank = $('#divBayarBank');
		let divBayarTunai = $('#divBayarTunai');
		let divJatuhTempo = $('#divJatuhTempo');
		let penjualanTotalBayar = $('#penjualan_total_bayar');
		let penjualanTotalKembalian = $('#penjualan_total_kembalian');
		let bayardanKembalian = $('#bayarKembalian');


		if (metode.val() == 'B') {
			divBank.show();
			divBayarTunai.show();
			divBayarBank.show();
			bayardanKembalian.show();
			divJatuhTempo.hide();

			penjualanTotalBayar.val(0);
		} else if (metode.val() == 'K') {
			let date = moment().add(1, 'days').format('YYYY-MM-DD');
			$('#penjualan_jatuh_tempo').val(date);

			$('#penjualan_total_bayar_bank').val(0);
			$('#penjualan_total_bayar_tunai').val(0);

			let getTotal = $('#penjualan_total_grand').val();
			penjualanTotalBayar.val(getTotal);

			divBank.hide();
			divBayarBank.hide();
			divBayarTunai.hide();
			bayardanKembalian.hide();
			divJatuhTempo.show();
		} else if (metode.val() == 'C') {
			divBank.hide();
			divBayarBank.hide();
			divBayarTunai.show();
			divJatuhTempo.hide();

			$('#penjualan_total_bayar_bank').val(0);
			sumBayar()

			penjualanTotalBayar.val(0);
		} else {
			divBank.hide();
			divBayarBank.hide();
			divBayarTunai.hide();
			divJatuhTempo.hide();

			penjualanTotalBayar.val(0);
		}
		countKembalian();
	}


	//==================================================================================================


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
						return HELPER.toCurrency(row[5]);
					}
				},
				{
					targets: 7,
					render: function(data, type, row) {
						return HELPER.toCurrency(row[7]);
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

	function onRental() {
		loadTableRental();
		$('#modal-rental').modal();
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
				$('#	_' + row).val($('#penjualan_detail_satuan_' + row + ' option:selected').text());
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
			countDiscount()
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
		countDiscount()
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



		countDiscount()
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
		countDiscount();
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
		countDiscount();
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
		countDiscount();
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
		countDiscount();
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
			countDiscount()
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
			countDiscount()
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
			countDiscount()
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
		$('#sisa_saldo').text('Sisa Saldo : ' + HELPER.toCurrency(sisa_saldo));
		voucher_khusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
		sisa_saldo_khusus = voucher_khusus;
		$('#sisa_saldo_voucher').text('Sisa Saldo : ' + HELPER.toCurrency(sisa_saldo_khusus));
		voucher_lain = parseInt($('#penjualan_total_bayar_voucher_lain').val()) || 0;
		sisa_saldo_voucher_lain = voucher_lain;
		$('#sisa_saldo_voucher_lain').text('Sisa Saldo : ' + HELPER.toCurrency(sisa_saldo_voucher_lain));
		// sisa_saldo_voucher_lain
		tbayar = bayar + voucher + voucher_khusus + voucher_lain;
		kredit = grand - tbayar;
		kredit = (kredit >= 0) ? kredit : 0;

		kembalian = (tbayar - grand) >= 0 ? (tbayar - grand) : 0;
		$('.total_harga').val(grand);



		$('#v_penjualan_total_grand').text(HELPER.toCurrency(kredit));
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
		$('#bulan_cicil').text(HELPER.toCurrency(cicil) + ' /bulan');
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
						<input type="hidden" readonly class="form-control" name="penjualan_detail_id[` + row + `]" id="penjualan_detail_id_` + row + `">						
						<input type="hidden" readonly class="form-control" name="penjualan_detail_jenis_barang[` + row + `]" id="penjualan_detail_jenis_barang_` + row + `">						
						<select class="form-control barang_id" name="penjualan_detail_barang_id[` + row + `]" id="penjualan_detail_barang_id_` + row + `" data-id="` + row + `" onchange="setSatuan('` + row + `')" style="width: 260px;white-space: nowrap"></select></td>
					<td><select class="form-control" name="penjualan_detail_satuan[` + row + `]" id="penjualan_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select>
						<input type="hidden" readonly class="form-control" name="	[` + row + `]" id="	_` + row + `" >						
					</td>					
					<td><input class="form-control number penjualanHarga" type="text" name="penjualan_detail_harga[` + row + `]" id="penjualan_detail_harga_` + row + `" onchange="countRow('` + row + `')" readonly="">
					</td>
					<td>
						<input class="form-control qty" type="number" name="penjualan_detail_qty[` + row + `]" id="penjualan_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" onchange="countRow('` + row + `')" value="1">
						<input class="form-control number" type="hidden" readonly name="penjualan_detail_qty_barang[` + row + `]" id="penjualan_detail_qty_barang_` + row + `">						
                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_harga_beli[` + row + `]" id="penjualan_detail_harga_beli_` + row + `">
                        <input class="form-control number" type="text" style="display:none"  name="penjualan_detail_hpp[` + row + `]" id="penjualan_detail_hpp_` + row + `">	
					</td>
					<td>
						<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[` + row + `]" id="penjualan_detail_potongan_persen_` + row + `" onkeyup="countRow('` + row + `')">
						<input class="form-control number" type="hidden" readonly name="penjualan_detail_potongan[` + row + `]" id="penjualan_detail_potongan_` + row + `">
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
						$('#uang_kembali').text(' ' + HELPER.toCurrency(record.penjualan_total_kembalian));
						$('#uang_bayar').text('Bayar  ' + HELPER.toCurrency(record.penjualan_total_bayar_tunai));
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
		event.preventDefault();
		// enabling penjualan metode
		$('#penjualan_metode').prop("disabled", false);

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

		// Check pembayaran lunas
		let penjualanMetode = $('#penjualan_metode').val();
		if (penjualanMetode == 'B') {
			let penjualanBayarBank = $('#penjualan_total_bayar_bank').val();
			let penjualanBank = $('#penjualan_bank');
			if (parseInt(penjualanBayarBank) > 0) {
				if (penjualanBank.val() == 0 || penjualanBank.val() == '' || penjualanBank.val() == undefined) {
					penjualanBank.addClass('border border-danger');
					return;
				}
			}
		}

		// check total item
		let totalItem = $('#penjualan_total_item').val();
		if (totalItem == 0 || totalItem == '' || totalItem == undefined) {
			Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Pilih produk terlebih dahulu!',
			})
			return;
		}

		// check customer if kredit
		if (penjualanMetode == 'K') {
			let customer = $('#pos_penjualan_customer_id').val();
			if (customer == 0 || customer == '' || customer == undefined) {
				Swal.fire({
					icon: 'warning',
					title: 'Oops...',
					text: 'Customer wajib di isi jika melakukan metode pembayaran kredit!',
				})
				return;
			}
		}


		let totalNota = parseInt($('#penjualan_total_grand').val());
		let totalBayar = parseInt($('#penjualan_total_bayar').val());
		if (totalBayar < totalNota) {
			Swal.fire({
				icon: 'warning',
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
						if (countOnline == 0) {
							// window.socket.emit('user_data', window.userdata);
							countOnline += 1;
						}
						$('#menuHandler').empty();
						loadMenu();
						onReset();
						// setReset();
						handlePembayaran();
						if (cetak.val() == 1) {
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
						$('#uang_kembali').text(' ' + HELPER.toCurrency(record.penjualan_total_kembalian));
						$('#uang_bayar').text('Bayar  ' + HELPER.toCurrency(record.penjualan_total_bayar_tunai));
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

		var settdata = localStorage.getItem('print_settings');
		var settdataobj = JSON.parse(settdata);
		var settings_show = [];
		if (settdataobj) {
			var isarrsetshow = settdataobj.settings_show;
			if (Array.isArray(isarrsetshow)) {
				settings_show = isarrsetshow
			} else {
				settings_show.push(isarrsetshow);
			}
		}

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
						settings_show,
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

	function onCancelModal() {
		$('#modal-pesanan').modal();
		$('#modal-daftar-pesanan').modal('hide');
	}

	function imgError(image) {
		image.onerror = "";
		image.src = "../assets/media/default_product.png";
	}

	function loadingMenu(isLoading = true) {
		if (isLoading) {
			$("#menuHandler").append(`
			<div class="col-12 text-center" id="loadingMenuHandler">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
			`);
			return;
		} else {
			$("#loadingMenuHandler").remove();
		}
	}

	function onRent() {
		if (isRent == true) {
			$('#penjualan_metode').val('K');
			$('#penjualan_metode').attr('disabled', 'disabled');
			handlePembayaran();
		}

		if (isRent == false) {
			$('#penjualan_metode').val('B');
			$('#penjualan_metode').prop("disabled", false);
			handlePembayaran();
		}
	}

	function onPengaturanCetak() {
		var settdata = localStorage.getItem('print_settings');
		var settdataobj = JSON.parse(settdata);
		$('input[name="settings_show"]').prop('checked', false);
		if (settdataobj) {
			var settings_show = settdataobj.settings_show;
			if (Array.isArray(settings_show)) {
				settings_show.forEach((item, index) => {
					$(`#settings_show_${item}`).prop('checked', true);
				});
			} else {
				$(`#settings_show_${settings_show}`).prop('checked', true);
			}
		}
		$('#modal-pengaturan-cetak').modal('show');
	}

	function onSimpanPengaturanCetak() {
		var data = $('#form-pegaturan-cetak').serializeObject();
		localStorage.setItem("print_settings", JSON.stringify(data));
		HELPER.showMessage({
			success: true,
			message: 'Berhasil simpan pengaturan cetak.',
		})
		$('#modal-pengaturan-cetak').modal('hide');
	}

	function handleAddCustomer() {
		$('#modal-add-customer').modal('show');
	}

	function onChangeInvalid(el) {
		$(el).removeClass('is-invalid');
	}

	function onSaveCustomer() {
		var customer_kode = $('input[name="customer_kode"]').val();
		var customer_nama = $('input[name="customer_nama"]').val();

		var flag = 0;
		if (!customer_kode || customer_kode.length < 2) {
			flag += 1;
			$('input[name="customer_kode"]').addClass('is-invalid');
		}

		if (!customer_nama || customer_nama.length < 2) {
			flag += 1;
			$('input[name="customer_nama"]').addClass('is-invalid');
		}

		if (flag > 0) {
			return;
		}

		if (customer_kode.length > 20) {
			HELPER.showMessage({
				title: 'Peringatan',
				success: 'warning',
				message: 'Tidak dapat menyimpan. Kode melebihi 20 huruf.',
			});
			return;
		}
		HELPER.save({
			form: 'form-customer',
			confirm: true,
			url: BASE_URL + 'customer/store',
			callback: function(success, id, record, message) {
				if (success === true) {
					if (record) {
						$("#pos_penjualan_customer_id").select2("trigger", "select", {
							data: {
								id: record.customer_id,
								text: `${record.customer_kode} - ${record.customer_nama}`
							}
						});
					}

					$('#modal-add-customer').modal('hide');
					$('#form-customer').trigger("reset");
				}
			}
		})
	}

	const onPay = (penjualan_id) => {
		HELPER.confirm({
			message: 'Apakah anda yakin ingin melakukan pembayaran?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: HELPER.api.shortcutBayar,
						data: {
							penjualan_id: penjualan_id
						},
						datatype: 'json',
						success: (res) => {
							if (res.success) {
								$('#modal-rental').modal('hide');
								loadMenu();
								onReset();
								searchMenu()
								HELPER.showMessage({
									title: 'Berhasil',
									message: 'Pembayaran berhasil.',
									success: true
								});
								$('#modal-bayar').modal('hide');
								$('#modal-daftar-pesanan').modal('hide');
								//hide modal modal-rental

							} else {
								HELPER.showMessage({
									title: 'Gagal',
									message: 'Pembayaran gagal.',
									success: false
								});
							}
						}
					});

				}
			}
		});
	}
</script>