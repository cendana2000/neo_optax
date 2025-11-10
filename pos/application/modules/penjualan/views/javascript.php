<script type="text/javascript">
	$(function() {
		row = 1;
		HELPER.fields = [
			'penjualan_id',
			'penjualan_tanggal',
			'penjualan_kode',
			'penjualan_customer_id',
			'penjualan_total_item',
			'penjualan_total_qty',
			'penjualan_total_harga',
			'penjualan_total_grand',
			'penjualan_total_bayar',
			'penjualan_total_bayar_tunai',
			'penjualan_total_bayar_voucher',
			'penjualan_total_bayar_voucher_khusus',
			'penjualan_total_bayar_voucher_lain',
			'penjualan_total_potongan_persen',
			'penjualan_total_potongan',
			'penjualan_total_kembalian',
			'penjualan_total_kredit',
			'penjualan_total_cicilan',
			'penjualan_total_cicilan_qty',
			'penjualan_total_retur',
			'penjualan_kredit_awal',
			'penjualan_jatuh_tempo',
			'penjualan_user_id',
			'penjualan_created_by',
			'penjualan_created_at',
			'penjualan_updated_by',
			'penjualan_updated_at',
			'penjualan_user_nama',
			'penjualan_keterangan',
			'penjualan_total_jasa',
			'penjualan_total_jasa_nilai',
			'penjualan_jenis_potongan',
			'penjualan_is_konsinyasi',
			'penjualan_metode',
			'penjualan_kasir',
			'penjualan_bank_id',
			'penjualan_bank_ref',
			'penjualan_jenis_barang',
			'penjualan_lock',
			'penjualan_no_transaksi',
			'penjualan_jenis_penjualan',
			'penjualan_tanggal_jatuh_tempo',
		];
		HELPER.setRequired([
			'supplier_nama',
		]);
		HELPER.api = {
			table: BASE_URL + 'penjualan/',
			read: BASE_URL + 'penjualan/read',
			store: BASE_URL + 'penjualan/store',
			update: BASE_URL + 'penjualan/update',
			destroy: BASE_URL + 'penjualan/destroy',
		}
		$.get(HELPER.api.table, function (data) {
			console.log(data);
		});
		loadTable();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-penjualan",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [
				{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['penjualan_no_transaksi'];
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
						return full['penjualan_customer_id'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['penjualan_customer_id'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['penjualan_customer_id'];
					},
				},
				{
					targets: 2,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['penjualan_id'] + `')"">
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

	function resetSales() {
		let html = `
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
		$("#table-sales").replaceWith(html);
	}

	function onEdit(el) {
		$('#table-sales').empty();
		HELPER.loadData({
			table: 'table-supplier',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				console.log(res);
				return;
				if (res.sales.total > 0) {
					sales = res.sales.data
					row = 1;
					$.each(sales, function(i, v) {
						if (i > 0) addSales();
						$('#sales_id_' + row).val(v.sales_id);
						$('#sales_nama_' + row).val(v.sales_nama);
						$('#sales_telp_' + row).val(v.sales_telp);
						$('#sales_hp_' + row).val(v.sales_hp);
						$('#sales_keterangan_' + row).val(v.sales_keterangan);
					});
				}
				onAdd();
			}
		})
	}

	function onDelete(supplier_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'penjualan/delete',
						data: {
							id: supplier_id
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

	function onBack() {
		// resetSales();
		$('#table-sales').empty();
		HELPER.back2();
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
					onRefresh();
					resetSales();
					HELPER.back({});
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
			url: BASE_URL + 'penjualan/select',
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
			url: BASE_URL + 'penjualan/select',
			callback: function() {
				$('#supplier2').select2();
			}
		})
		$('#myModal').modal('show');
	}

	function loadPreview() {
		HELPER.block();
		data = $('#cetakSupplier').serializeArray();
		$.ajax({
			url: BASE_URL + 'penjualan/preview',
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
</script>