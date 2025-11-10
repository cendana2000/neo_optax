<script type="text/javascript">
	$(function() {
		row = 1;
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
			'penjualan_total_potongan',
			'penjualan_total_potongan_persen',
			'penjualan_total_kembalian',
			'penjualan_total_kredit',
			'penjualan_total_cicilan',
			'penjualan_jatuh_tempo',
			'penjualan_keterangan',
			'anggota_nip',
			'anggota_kode',
			'anggota_nama',
			'anggota_saldo_simp_titipan_belanja',
		];

		HELPER.setRequired([
			'penjualan_tanggal',
		]);

		HELPER.api = {
			table: BASE_URL + 'transaksipenjualan/',
			read: BASE_URL + 'transaksipenjualan/read',
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
		});

		HELPER.create_combo_akun({
			el: 'barang_kategori_barang',
			valueField: 'id',
			displayField: 'text',
			parentField: 'parent',
			childField: 'child',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: false,
			callback: function() {
				$('#barang_kategori_barang').select2();
			}
		});

		$.post(BASE_URL + 'satuan/select', function(res) {
			satuan = res.data;
			setBarang();
		})
		// $('#penjualan_bayar_opsi').select2();
		$('form input, form select, body').keydown(function(e) {
			if (e.keyCode == 35) {
				e.preventDefault();
				onBayar();
				return false;
			}
		});
		$(".use_barcode").keypress(function(event) {
			if (event.which == '10' || event.which == '13') {
				getBarang($('.use_barcode').val());
				event.preventDefault();
			}
		});
		$('.select2-search__field').keypress(function(event) {
			if (event.which == '10' || event.which == '13') {
				event.preventDefault();
			}
		});
	});

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
						$('#penjualan_detail_qty_' + trow).val(parseInt($('#qty').val()) + parseInt($('#penjualan_detail_qty_' + trow).val()));
					} else {
						if ($('#penjualan_detail_barang_id_' + row).val()) addBarang();
						$('#penjualan_detail_barang_id_' + row).select2("trigger", "select", {
							data: {
								id: v.id,
								text: v.text,
								// saved: v.saved
							}
						});
						$('#penjualan_detail_qty_' + row).val($('#qty').val());
					}
					$('#barang').val('');
					$('#qty').val('1');
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
		if ($.fn.DataTable.isDataTable('#list_barang')) {
			$('#list_barang').DataTable().destroy();
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
						aksi = `<button type="button" class="btn btn-outline-brand btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
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

	function onBayar() {
		$('#modal-bayar').modal();
	}

	function setBarang() {
		HELPER.ajaxCombo({
			el: '#penjualan_detail_barang_id_' + row,
			url: BASE_URL + 'transaksipenjualan/barang_ajax',
		});
		$('input.number').number(true);
	}

	function setSatuan(row, st) {
		barang_id = $('#penjualan_detail_barang_id_' + row).val();
		$.post(BASE_URL + 'barang/list_satuan', {
			barang_id: barang_id
		}, function(res) {
			html = '';
			$.each(res.data, function(i, v) {
				html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_jual="` + v.barang_satuan_harga_jual + `" data-barang_satuan_disc="` + v.barang_satuan_disc + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `">` + v.barang_satuan_kode + `</option>`
			})
			$('#penjualan_detail_satuan_' + row).html(html);
			$('#penjualan_detail_satuan_' + row).select2();
			if (st) $('#penjualan_detail_satuan_' + row).val(st).trigger('change');
			getHarga(row);
		})
	}

	function getHarga(row) {
		harga = $('#penjualan_detail_satuan_' + row + ' option:selected').data();
		konversi = 0;
		if (harga) {
			$('#penjualan_detail_satuan_kode_' + row).val($('#penjualan_detail_satuan_' + row + ' option:selected').text());
			$('#penjualan_detail_harga_' + row).val(harga.barang_satuan_harga_jual)
			$('#penjualan_detail_disc_' + row).val(harga.barang_satuan_disc)
			konversi = parseInt(harga.barang_satuan_konversi) || 0;
		}
		qty = parseInt($('#penjualan_detail_qty_' + row).val()) || 0;
		qty_barang = konversi * qty;
		$('#penjualan_detail_qty_barang_' + row).val(qty_barang);
		countRow(row)
	}

	function setPrice(row) {
		dt = $('#barang_satuan_opt_' + row).data();
		harga = parseInt(dt.harga);
		isi = parseInt(dt.isi) || 1;
		qty = parseInt($('#penjualan_detail_qty_' + row).val()) || 0;
		if (dt.satuan == $('#penjualan_detail_satuan_' + row).val()) {
			qty = isi * qty;
			harga = harga * isi;
		}
		$('#penjualan_detail_qty_barang_' + row).val(qty);
		$('#penjualan_detail_harga_' + row).val(harga);
		countRow(row);
	}

	function getNasabah(argument) {
		$.post(BASE_URL + 'anggota/read', {
			anggota_id: $('#penjualan_anggota_id').val()
		}, function(res) {
			$('#anggota_nip').val(res.anggota_nip + ' - (' + res.grup_gaji_kode + ') ' + res.grup_gaji_nama);
			$('#anggota_grup_gaji').text('GOL. (' + res.grup_gaji_kode + ') ' + res.grup_gaji_nama);
			$('.voucher').val(res.anggota_saldo_simp_titipan_belanja);
			if (res) $('.nasabah').removeAttr('style');
			else $('.nasabah').css('display', 'none');
		})
	}

	function init_table(argument) {
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
					targets: 2,
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
				/*{
					targets : 7,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},*/
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
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

	function onTable() {
		HELPER.toggleForm({
			tohide: 'form_data',
			toshow: 'table_data',
		});
		init_table();
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow2(nrow) {
		nqty = parseInt($('#penjualan_detail_qty_' + nrow).val()) || 0;
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
			qty += parseInt($(v).val());
		})
		if (done) addBarang()
		$('#penjualan_total_harga').val(sub_total);
		$('#penjualan_total_item').val(item);
		$('#penjualan_total_qty').val(qty);
		countDiskon()
	}

	function countRow(nrow) {
		qty = parseInt($('#penjualan_detail_qty_' + nrow).val()) || 0;
		harga = parseInt($('#penjualan_detail_harga_' + nrow).val()) || 0;
		qty_barang = harga_barang = 0;
		st = $('#penjualan_detail_satuan_' + nrow + ' option:selected').data();
		if (st) {
			konversi = parseInt(st.barang_satuan_konversi) || 1;
			qty_barang = konversi * qty;
			harga_barang = harga / konversi;
		}
		$('#penjualan_detail_harga_barang_' + nrow).val(harga_barang);
		$('#penjualan_detail_qty_barang_' + nrow).val(qty_barang);
		jumlah = (harga * qty) || 0;
		disc = parseFloat($('#penjualan_detail_potongan_persen_' + nrow).val()) || 0;
		diskon = disc * jumlah / 100;
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
		$('.qty').each(function(i, v) {
			qty += parseInt($(v).val());
		})
		$('#penjualan_total_harga').val(sub_total);
		$('#penjualan_total_item').val(item);
		$('#penjualan_total_qty').val(qty);
		countDiskon()
	}

	function countDiskon() {
		sub_total = parseInt($('#penjualan_total_harga').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		diskon_p = parseInt($('#penjualan_total_potongan_persen').val()) || 0;
		if (diskon_p) {
			diskon = diskon_p * sub_total / 100;
			$('#penjualan_total_potongan').val(diskon)
		} else {
			diskon_p = diskon * 100 / sub_total;
			$('#penjualan_total_potongan_persen').val(diskon_p)
		}
		grand = sub_total - diskon
		bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0
		voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0
		sisa_saldo = parseInt($('#anggota_saldo_simp_titipan_belanja').val()) - voucher;
		$('#sisa_saldo').text('Sisa Saldo : ' + $.number(sisa_saldo))
		tbayar = bayar + voucher;
		kredit = grand - tbayar;
		kredit = (kredit >= 0) ? kredit : 0;

		kembalian = (tbayar - grand) >= 0 ? (tbayar - grand) : 0;
		$('.total_harga').val(grand);

		$('#penjualan_total_bayar').val(tbayar);
		$('#penjualan_total_kredit').val(kredit);
		$('#penjualan_total_kembalian').val(kembalian)
		countCicilan()
	}

	function countCicilan() {
		if (parseInt($('penjualan_total_cicilan_qty')) > 1) {
			kredit = parseInt($('#penjualan_total_kredit').val()) || 0;
			n_jasa = parseFloat($('#penjualan_total_jasa').val()) || 0;
			n_cicil = parseInt($('#penjualan_total_cicilan_qty').val()) || 0;
			cicil = (kredit / n_cicil) + (kredit * n_jasa / 100);
			$('#penjualan_total_cicilan').val(cicil);
			$('#bulan_cicil').text($.number(cicil, true) + ' /bulan');
			$('#penjualan_total_jasa').attr('disabled', false);
		} else {
			$('#penjualan_total_jasa').attr('disabled', true);
		}
	}

	function addBarang() {
		row++;
		html = `<tr class="barang_` + row + `">
					<td scope="row">
						<input type="hidden" class="form-control" name="penjualan_detail_id[` + row + `]" id="penjualan_detail_id_` + row + `">						
						<select class="form-control barang_id" name="penjualan_detail_barang_id[` + row + `]" id="penjualan_detail_barang_id_` + row + `" data-id="` + row + `" onchange="setSatuan('` + row + `')" style="width: 100%;white-space: nowrap"></select></td>
					<td><select class="form-control" name="penjualan_detail_satuan[` + row + `]" id="penjualan_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select>
						<input type="hidden" class="form-control" name="penjualan_detail_satuan_kode[` + row + `]" id="penjualan_detail_satuan_kode_` + row + `" >						
					</td>					
					<td><input class="form-control number" type="text" name="penjualan_detail_harga[` + row + `]" id="penjualan_detail_harga_` + row + `" onchange="countRow('` + row + `')" readonly=""></td>
					<td>
						<input class="form-control number qty" type="text" name="penjualan_detail_qty[` + row + `]" id="penjualan_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" value="1">
						<input class="form-control number" type="hidden" name="penjualan_detail_qty_barang[` + row + `]" id="penjualan_detail_qty_barang_` + row + `">						
					</td>
					<td>
						<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[` + row + `]" id="penjualan_detail_potongan_persen_` + row + `" onkeyup="countRow('` + row + `')">
						<input class="form-control number" type="hidden" name="penjualan_detail_potongan[` + row + `]" id="penjualan_detail_potongan_` + row + `">
					</td>
					<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[` + row + `]" id="penjualan_detail_subtotal_` + row + `" readonly=""></td>
					<td><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
                  		<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-detail_barang').append(html);
		setBarang();
		$('.disc').number(true, 2);
	}

	function addByBarcode() {
		bcd = $('#form-barcode').serializeObject()
		console.log(bcd);
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				getDetailBarang(res.penjualan_id);
				$("#penjualan_anggota_id").select2("trigger", "select", {
					data: {
						id: res.penjualan_anggota_id,
						text: res.anggota_kode + ' - ' + res.anggota_nama
					}
				});
				onAdd()
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/get_detail',
			type: 'post',
			data: {
				penjualan_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					n = i + 1;
					if (n > 1) addBarang();
					$('#penjualan_detail_id_' + n).val(v.penjualan_detail_id);
					$('#penjualan_detail_harga_' + n).val(v.penjualan_detail_harga);
					$('#penjualan_detail_qty_' + n).val(v.penjualan_detail_qty);
					$('#penjualan_detail_qty_barang_' + n).val(v.penjualan_detail_qty_barang);
					$('#penjualan_detail_subtotal_' + n).val(v.penjualan_detail_subtotal);
					$("#penjualan_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.penjualan_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama,
						}
					});
					// new_st = [v.barang_satuan, v.barang_satuan_opt, v.barang_isi, v.barang_harga];
					setSatuan(n, v.penjualan_detail_satuan);
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
		HELPER.save({
			form: 'form-penjualanbarang',
			data: $('#form-bayar').serializeObject(),
			confirm: true,
			callback: function(success, id, record, message, res) {
				var cetak = $('#cetak');
				if (success === true) {
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
					}
					// HELPER.back({});
				}
			}
		})
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
				if (data) {
					$.extend(data, {
						tjson: true
					});
					$.ajax({
						url: BASE_URL + 'transaksipenjualan/tprint/' + data.penjualan_id,
						data: data,
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
				} else {

				}
			}
		})

	}
</script>