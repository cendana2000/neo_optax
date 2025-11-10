<script type="text/javascript">
	$(function() {
		row = 1;
		rowPembayaran = 1;
		satuan = barang = [];
		HELPER.fields = [
			'order_id',
			'order_kode',
			'order_supplier_id',
			'order_tanggal',
			'order_kode',
			'order_bayar_opsi',
			'order_total',
			'order_total_item',
			'order_total_qty',
			'order_no_transaksi',
			'order_jenis_pembayaran',
			'order_jatuh_tempo'
		];

		HELPER.setRequired([
			'order_supplier_id',
			'order_tanggal',
			'order_total_qty',
			'order_total_item',
			'order_total',
		]);
		HELPER.api = {
			table: BASE_URL + 'orderpembelian/',
			read: BASE_URL + 'orderpembelian/read',
			store: BASE_URL + 'orderpembelian/store',
			update: BASE_URL + 'orderpembelian/update',
			destroy: BASE_URL + 'orderpembelian/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
		}
		$('input.number').number(true);
		HELPER.createCombo({
			el: 'order_supplier_id',
			valueField: 'supplier_id',
			displayField: 'supplier_kode',
			displayField2: 'supplier_nama',
			grouped: true,
			url: BASE_URL + 'supplier/select',
			callback: function() {
				$('#order_supplier_id').select2();
			}
		})
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
		// $.post(BASE_URL + 'satuan/select', function(res) {
		// 	satuan = res.data;
		// 	setBarang();
		// })
		// init_table();
		$('#table-pembayaran').hide();
		$('#btnTunai').hide();
		$('#btnPembayaran').hide();
		$('#order_bayar_opsi').select2();
		loadTable();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('order-Update') || HELPER.get_role_access('order-Delete'));
		HELPER.initTable({
			el: "table-orderpembelian",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['order_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['order_tanggal'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'hold';
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['order_total'];
					},
				},
				{
					targets: 6,
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
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['order_id'] + `')"">
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

	function newBarang() {
		HELPER.setChangeCombo({
			el: 'barang_satuan',
			data: satuan,
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
		});
		HELPER.setChangeCombo({
			el: 'barang_satuan_opt',
			data: satuan,
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
		});
		$('#barang_satuan, #barang_satuan_opt').select2();
		$('#daftar_barang').modal();
	}

	function showIsi() {
		$('.lbl_barang_satuan').text($('#barang_satuan option:selected').text());
	}

	function setBarang(el) {
		row = (el) ? el : row;
		temp = [{
			key: 'detail',
			val: 'true'
		}];
		supplier = $('#order_supplier_id').val();
		HELPER.ajaxCombo({
			el: '#order_detail_barang_id_' + row,
			data: {
				barang_supplier_id: supplier
			},
			url: BASE_URL + 'barang/select_ajax',
			tempData: temp
		});
		$('input.number').number(true);
	}

	function setSatuan(row, st) {
		barang_id = $('#order_detail_barang_id_' + row).val();
		$.post(BASE_URL + 'barang/list_satuan', {
			barang_id: barang_id
		}, function(res) {
			html = '';
			// console.log(res.data)
			$.each(res.data, function(i, v) {
				html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_beli="` + v.barang_satuan_harga_beli + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `">` + v.barang_satuan_kode + `</option>`
			})
			$('#order_detail_satuan_' + row).html(html);
			$('#order_detail_satuan_' + row).select2();
			if (st) $('#order_detail_satuan_' + row).val(st).trigger('change');
			getHarga(row);
		})
	}

	function getHarga(row) {
		harga = $('#order_detail_satuan_' + row + ' option:selected').data();
		$('#order_detail_satuan_kode_' + row).val($('#order_detail_satuan_' + row + ' option:selected').text());
		$('#order_detail_harga_' + row).val(harga.barang_satuan_harga_beli)
		konversi = parseInt(harga.barang_satuan_konversi) || 0;
		qty = parseInt($('#order_detail_qty_' + row).val()) || 0;
		qty_barang = konversi * qty;
		$('#order_detail_qty_barang_' + row).val(qty_barang);
		countRow()
	}

	function getSupplier(argument) {
		$.post(BASE_URL + 'supplier/read', {
			supplier_id: $('#order_supplier_id').val()
		}, function(res) {
			$('#supplier_alamat').val(res.supplier_alamat);
			$('#supplier_telp').val(res.supplier_telp);
		})
		for (var i = 1; i <= row; i++) {
			setBarang(i);
		}
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow(nrow) {
		satuan_konversi = parseInt($('#order_detail_satuan_' + nrow + ' option:selected').data('barang_satuan_konversi')) || 1;
		nqty = parseInt($('#order_detail_qty_' + nrow).val()) || 0;
		qty_barang = nqty * satuan_konversi;
		nharga = parseInt($('#order_detail_harga_' + nrow).val()) || 0;
		harga_barang = satuan_konversi * nharga;
		$('#order_detail_qty_barang_' + nrow).val(qty_barang);
		$('#order_detail_harga_barang_' + nrow).val(harga_barang);
		jumlah = (nharga * nqty) || 0;
		$('#order_detail_jumlah_' + nrow).val(jumlah)
		sub_total = item = qty = 0;
		done = true;
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val()) || 0;
			t = parseInt($(v).val());
			if (!t) done = false;
			else item++;
		})
		if (done) addBarang()
		$('.qty').each(function(i, v) {
			qty += parseInt($(v).val()) || 0;
			console.log(qty);
		})
		$('#order_total').val(sub_total);
		$('#order_total_item').val(item);
		$('#order_total_qty').val(qty);
	}

	function onDelete(order_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'orderpembelian/delete',
						data: {
							id: order_id
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
									table: 'table-orderpembelian'
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


	function onEdit(el) {
		HELPER.loadData({
			table: 'table-orderpembelian',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				let barang = res.barang;

				$.each(barang, function(i, v) {
					console.log(i);
					if (i > 0) addBarang();
					if (i > 0) console.log(v.barang_nama);
					$('#order_detail_id_' + row).val(v.barang_nama);
					$('#order_detail_barang_id_' + row).val(v.barang_nama);
					$('#order_detail_satuan_' + row).val(v.barang_nama);
					$('#order_detail_harga_' + row).val(v.barang_nama);
					$('#order_detail_harga_barang_' + row).val(1000);
					$('#order_detail_qty_' + row).val(v.barang_nama);
					$('#order_detail_qty_barang_' + row).val(v.barang_nama);
					$('#order_detail_jumlah_' + row).val(v.barang_nama);

				});
				onAdd();
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'orderpembelian/get_detail',
			type: 'post',
			data: {
				order_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					n = i + 1;
					if (n > 1) addBarang();
					$('#order_detail_id_' + n).val(v.order_detail_id);
					$("#order_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.order_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama
						}
					});
					setSatuan(n, v.order_detail_satuan);
					$('#order_detail_qty_' + n).val(v.order_detail_qty);
					$('#order_detail_qty_barang_' + n).val(v.order_detail_qty_barang);
					$('#order_detail_harga_' + n).val(v.order_detail_harga);
					$('#order_detail_harga_barang_' + n).val(v.order_detail_harga_barang);
					$('#order_detail_jumlah_' + n).val(v.order_detail_jumlah);
				})
			}
		})
	}

	function remRowBarang(el) {
		id = $(el).data('id');
		$('tr.barang_' + id).remove();
		countRow(id);
	}

	function remRowPembayaran(el) {
		id = $(el).data('id');
		$('tr.pembayaran_' + id).remove();
		countRow(id);
	}

	function onBack() {
		resetTableBarang();
		resetTablePembayaran();
		$('#formCetak').hide();
		$('#form-orderpembelian').trigger('reset');
		onRefresh();
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-orderpembelian'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-orderpembelian',
			confirm: true,
			callback: function(success, id, record, message) {
				var cetak = $('#cetak_checkbox');

				if (success === true) {
					if (cetak.is(":checked")) {
						onPrint(id)
					} else {
						HELPER.back({});
					}

				}
			}
		})
	}

	function saveBarang() {
		swal.fire({
			title: 'Informasi',
			text: "Simpan sebagai barang baru ?",
			type: 'info',
			confirmButtonText: '<i class="fa fa-check"></i> Yes',
			confirmButtonClass: 'btn btn-focus btn-success m-btn m-btn--pill m-btn--air',
			showCancelButton: true,
			cancelButtonText: '<i class="fa fa-times"></i> No',
			cancelButtonClass: 'btn btn-focus btn-danger m-btn m-btn--pill m-btn--air'
		}).then(function(result) {
			if (result.value) {
				kategori = (($('#barang_kategori_barang option:selected').text()).trim()).split(" - ");
				kode = kategori[0] || '';
				barang = $('#form-barang').serializeObject();
				barang = $.extend({
					kategori_kode: kode
				}, barang);
				$.ajax({
					url: BASE_URL + 'barang/store',
					data: barang,
					type: 'post',
					success: function(res) {
						if (res.success == true) {
							set = false;
							dt = res.record;
							new_st = [dt.barang_satuan, dt.barang_satuan_opt];
							st = btoa(new_st);
							$('.barang_id').each(function(i, v) {
								if (!$(v).val()) {
									n = $(v).data('id');
									$("#order_detail_barang_id_" + n).select2("trigger", "select", {
										data: {
											id: dt.barang_id,
											text: dt.barang_kode + " - " + dt.barang_nama,
											coba: st
										}
									});
									setSatuan(n, new_st);
									set = true;
								}
							});
							if (!set) {
								addBarang();
								$("#order_detail_barang_id_" + row).select2("trigger", "select", {
									data: {
										id: dt.barang_id,
										text: dt.barang_kode + " - " + dt.barang_nama,
										coba: st
									}
								});
								setSatuan(row, new_st);
								set = true;
							}
						}
						$('#daftar_barang').modal('hide');
						$('#form-barang').trigger("reset");
						$('#form-barang select').select2("val", "");
					}
				})
			}
		});
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-orderpembelian',
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
		if (param) {
			$.ajax({
				url: BASE_URL + 'orderpembelian/cetak/' + param,
				type: 'get',
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
				table: 'table-orderpembelian',
				callback: function(data) {
					console.log(data);
					if (data) {
						$.ajax({
							url: BASE_URL + 'orderpembelian/cetak/' + data.order_id,
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

	function addBarang() {
		row++;
		html = `<tr class="barang_` + row + `">
				<td scope="row">
					<input type="hidden" class="form-control" name="order_detail_id[` + row + `]" id="order_detail_id_` + row + `">
					<select class="form-control barang_id" name="order_detail_barang_id[` + row + `]" id="order_detail_barang_id_` + row + `" data-id="` + row + `" style="width: 100%;white-space: nowrap" onchange="setSatuan('` + row + `')"></select></td>
				<td><select class="form-control" name="order_detail_satuan[` + row + `]" id="order_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select></td>
				<td>
					<input class="form-control number" type="text" name="order_detail_harga[` + row + `]" id="order_detail_harga_` + row + `" onkeyup="countRow('` + row + `')">
					<input class="form-control number" type="hidden" name="order_detail_harga_barang[` + row + `]" id="order_detail_harga_barang_` + row + `">
				</td>
				<td>
					<input class="form-control number qty" type="text" name="order_detail_qty[` + row + `]" id="order_detail_qty_` + row + `" onkeyup="countRow('` + row + `')">
					<input class="form-control number" type="hidden" name="order_detail_qty_barang[` + row + `]" id="order_detail_qty_barang_` + row + `">
				</td>
				<td><input class="form-control number jumlah" type="text" name="order_detail_jumlah[` + row + `]" id="order_detail_jumlah_` + row + `" readonly=""></td>
				<td><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowBarang(this)" title="Hapus" >
              		<span class="la la-trash"></span> Hapus</a></td>
			</tr>`;
		$('#tbody-barang').append(html);
		setBarang();
	}

	function addPembayaran() {
		rowPembayaran++;
		html = `<tr class="pembayaran_` + rowPembayaran + `">
					<td scope="rowPembayaran">
						<input type="hidden" class="form-control" name="order_detail_id[` + rowPembayaran + `]" id="order_detail_id_` + rowPembayaran + `">
						<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[` + rowPembayaran + `]" id="order_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
					</td>
					<td>
						<select class="form-control" name="order_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="order_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="getHarga('` + rowPembayaran + `')">
							<option value="">-Pilih Cara Bayar-</option>
							<option value="Transfer Bank">Transfer Bank</option>
							<option value="Cash">Cash</option>
						</select>
					</td>
					<td>
						<input type="text" class="form-control" name="order_detail_pembayaran_akun[` + row + `]" id="order_detail_pembayaran_akun_` + row + `" style="width: 100%;">
					</td>
					<td>
						<input class="form-control number jumlah" type="text" name="order_detail_pembayaran_total[` + rowPembayaran + `]" id="order_detail_pembayaran_total_` + rowPembayaran + `" readonly="">
					</td>
					<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowPembayaran(this)" title="Hapus">
							<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#tbody-pembayaran').append(html);
	}

	function resetTableBarang() {
		let html = `
		<tr class="barang_1">
			<td scope="row">
				<input type="hidden" class="form-control" name="order_detail_id[1]" id="order_detail_id_1">
				<select class="form-control barang_id" name="order_detail_barang_id[1]" id="order_detail_barang_id_1" data-id="1" style="width: 100%;white-space: nowrap" onchange="setSatuan('1')"></select>
			</td>
			<td><select class="form-control" name="order_detail_satuan[1]" id="order_detail_satuan_1" style="width: 100%" onchange="getHarga('1')"></select></td>
			<td>
				<input class="form-control number" type="text" name="order_detail_harga[1]" id="order_detail_harga_1" onkeyup="countRow('1')">
				<input class="form-control number" type="hidden" name="order_detail_harga_barang[1]" id="order_detail_harga_barang_1">
			</td>
			<td>
				<input class="form-control number qty" type="text" name="order_detail_qty[1]" id="order_detail_qty_1" onkeyup="countRow('1')">
				<input class="form-control number" type="hidden" name="order_detail_qty_barang[1]" id="order_detail_qty_barang_1">
			</td>
			<td><input class="form-control number jumlah" type="text" name="order_detail_jumlah[1]" id="order_detail_jumlah_1" readonly=""></td>
			<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowBarang(this)" title="Hapus">
					<span class="la la-trash"></span> Hapus</a></td>
		</tr>	                                       
		`;
		$("#tbody-barang").replaceWith(html);
	}

	function resetTablePembayaran() {
		let html = `
		<tr class="pembayaran_1">
			<td scope="row">
				<input type="hidden" class="form-control" name="order_detail_id[1]" id="order_detail_id_1">
				<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[1]" id="order_detail_pembayaran_tanggal_1" style="width: 100%;">
			</td>
			<td>
				<select class="form-control" name="order_detail_pembayaran_cara_bayar[1]" id="order_detail_pembayaran_cara_bayar_1" style="width: 100%" onchange="getHarga('1')">
					<option value="">-Pilih Cara Bayar-</option>
					<option value="Transfer Bank">Transfer Bank</option>
					<option value="Cash">Cash</option>
				</select>
			</td>
			<td>
				<input type="text" class="form-control" name="order_detail_pembayaran_akun[` + row + `]" id="order_detail_pembayaran_akun_` + row + `" style="width: 100%;">
			</td>
			<td>
				<input class="form-control number jumlah" type="text" name="order_detail_pembayaran_total[1]" id="order_detail_pembayaran_total_1" readonly="">
			</td>
			<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowBarang(this)" title="Hapus">
					<span class="la la-trash"></span> Hapus</a></td>
		</tr>                                       
		`;
		$("#tbody-pembayaran").replaceWith(html);
	}

	function jenisBayar() {
		let choose = $('#order_jenis_pembayaran').val();
		let jatuhTempo = $('#order_jatuh_tempo');
		let lab_jatuhTempo = $('#label_jatuh_tempo');
		let btnTunai = $('#btnTunai');
		if (choose == 'Kredit') {
			jatuhTempo.show();
			lab_jatuhTempo.show();
			$('#table-detail_barang').show(100);
			btnTunai.hide(100);
			$('#table-pembayaran').hide();
			$('#btnPembayaran').hide();
		} else {
			jatuhTempo.hide();
			btnTunai.show(100);
			lab_jatuhTempo.hide();
		}
	}

	function pilihan(param) {
		if (param === 'listProduk') {
			$('#table-pembayaran').hide();
			$('#btnPembayaran').hide();
			$('#table-detail_barang').show(100);
			$('#btnBarang').show(100);
		} else if (param === 'pembayaran') {
			$('#table-detail_barang').hide();
			$('#btnBarang').hide();
			$('#table-pembayaran').show(100);
			$('#btnPembayaran').show(100);
		}
	}
</script>