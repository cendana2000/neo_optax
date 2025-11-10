<script type="text/javascript">
	$(function() {
		row = 1;
		rowPembayaran = 1;
		aktif = '';
		st = satuan = barang = dt_harga = [];
		dt_new = true
		HELPER.fields = [
			'pembelian_id',
			'pembelian_kode',
			'pembelian_supplier_id',
			'pembelian_tanggal',
			'pembelian_faktur',
			'pembelian_bayar_opsi',
			'pembelian_total',
			'pembelian_bayar_jumlah',
			'pembelian_bayar_sisa',
			'pembelian_jatuh_tempo_hari',
			'pembelian_jatuh_tempo',
			'pembelian_akun_id',
			'pembelian_is_pajak',
		];

		HELPER.setRequired([
			'pembelian_tanggal',
			'pembelian_bayar_opsi',
		]);

		HELPER.api = {
			table: BASE_URL + 'transaksipembelian/index3',
			read: BASE_URL + 'transaksipembelian/read',
			store: BASE_URL + 'transaksipembelian/store',
			update: BASE_URL + 'transaksipembelian/update',
			destroy: BASE_URL + 'transaksipembelian/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
		}
		$('input.number, tnumber').number(true);
		$('.disc').number(true, 2);

		HELPER.ajaxCombo({
			el: '#pembelian_supplier_id',
			url: BASE_URL + 'supplier/select_ajax',
			displayField: 'text',
			tags: true
			// chosen: false,
		});
		HELPER.ajaxCombo({
			el: '#pembelian_order_id',
			url: BASE_URL + 'orderpembelian/select_ajax',
			displayField: 'order_kode'
		});
		HELPER.createCombo({
			el: 'pembelian_akun_id',
			url: BASE_URL + 'akun/akun_pembayaran',
			valueField: 'akun_id',
			displayField: 'akun_nama',
			placeholder: '-Pilih-',
			callback: function() {
				$('#pembelian_akun_id').select2();
			}
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
		HELPER.createCombo({
			el: 'detail_barang_satuan_satuan_id_1',
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
			url: BASE_URL + 'satuan/select',
			callback: function(res) {
				satuan = res.data;
				HELPER.setChangeCombo({
					el: 'detail_barang_satuan_satuan_id_2',
					valueField: 'satuan_id',
					displayField: 'satuan_kode',
					data: satuan,
				})
				HELPER.setChangeCombo({
					el: 'detail_barang_satuan_satuan_id_3',
					valueField: 'satuan_id',
					displayField: 'satuan_kode',
					data: satuan,
				})
				$('#detail_barang_satuan_satuan_id_1, #detail_barang_satuan_satuan_id_2,#detail_barang_satuan_satuan_id_3').select2();
			}
		})

		$('form input, form select, body').keydown(function(e) {
			if (e.keyCode == 115) { //f4
				e.preventDefault();
				// addPotongan()
				$('#pembelian_diskon_persen').trigger('focus');
				return false;
			}
		});
		setScanner();
		$('#table-pembayaran').hide();
		$('#btnTunai').hide();
		$('#btnPembayaran').hide();
		$('#order_bayar_opsi').select2();
		loadTable();
	});



	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-pembelianbarang",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[2, 'asc'],
				[3, 'asc'],
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return '';
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['pembelian_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembelian_tanggal']).format("DD-MM-YYYY");
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
						return full['pembelian_faktur'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['pembelian_bayar_grand_total']);
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" title="Report" onclick="onPrint('${full['pembelian_id']}')">
						<span class="svg-icon svg-icon-md">
							<i class="la la-print"></i>
						</span>
                        </a>`;
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['pembelian_id'] + `')"">
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

	function init_table(argument) {
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()

		if ($.fn.DataTable.isDataTable('#table-pembelianbarang')) {
			$('#table-pembelianbarang').DataTable().destroy();
		}

		var table = $('#table-pembelianbarang').DataTable({
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
				url: BASE_URL + 'transaksipembelian/',
				type: 'POST',
				data: {
					tanggal1: awal,
					tanggal2: akhir,
				}
			},
			order: [
				[2, 'desc']
			],
			columnDefs: [
				/*{
					targets: 0,
					orderable: false
				},*/
				{
					targets: 2,
					render: function(data, type, row) {
						return (data).substring(7);
					}
				},
				{
					targets: 3,
					render: function(data, type, row) {
						return moment(data).format("DD-MM-YYYY");
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
					targets: 8,
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
						dt = $.parseJSON(atob($($(row[0])[2]).data('record')));
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-success" onclick="onHarga('` + dt.pembelian_id + `', '` + row[2] + `')" title="Harga">
                          <span class="la la-money"></span> Harga
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
		$('#modal').modal('hide');
		var detailRows = [];

		$('#table-pembelianbarang tbody').on('click', 'tr td.details-control', function() {
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
	}


	function loadTable2() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		awal = $("[name='awal_tanggal']").val()
		akhir = $("[name='akhir_tanggal']").val()

		HELPER.initTable({
			el: "table-pembelianbarang",
			url: BASE_URL + 'transaksipembelian/',
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
						return full['pembelian_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return moment(full['pembelian_tanggal']).format("DD-MM-YYYY");
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
						return full['pembelian_faktur'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return $.number(full['pembelian_bayar_grand_total']);
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" title="Report" onclick="onPrint('${full['pembelian_id']}')">
						<span class="svg-icon svg-icon-md">
							<i class="la la-print"></i>
						</span>
                        </a>`;
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['pembelian_id'] + `')"">
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

	function onDelete(pembelian_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'transaksipembelian/delete',
						data: {
							id: pembelian_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-pembelianbarang'
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



	function checkKode(el) {
		HELPER.block();
		kode = $(el).val();
		$.ajax({
			url: BASE_URL + 'barang/single_read',
			data: {
				barang_kode: kode
			},
			type: 'post',
			success: function function_name(res) {
				if (res.barang_kode) {
					HELPER.showMessage({
						success: true,
						message: 'Kode barang sudah digunakan, silahkan gunakan kode yang lain!.',
						title: 'Informasi'
					});
					$(el).val('');
					$(el).focus();
				}
				HELPER.unblock();
			}
		})
	}

	function setScanner(argument) {
		$(".barcode-scan").keypress(function(event) {
			row = $(this).data('id');
			if (event.which == '10' || event.which == '13') {
				getBarang($(this).val(), row);
				event.preventDefault();
			}
		});
	}

	function getBarang(id, row) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'transaksipembelian/get_barang',
			data: {
				val: id
			},
			type: 'post',
			success: function(res) {
				if (res[0]) {
					v = res[0];
					$('#pembelian_detail_barang_id_' + row).select2("trigger", "select", {
						data: {
							id: v.id,
							text: v.text,
						}
					});
					$('#barang_kode_' + row).val(v.barang_kode);
					// $('#pembelian_detail_satuan_'+row).trigger('focus');
				} else {
					swal.fire('Informasi', 'Data tidak ditemukan', 'warning');
					$('#pembelian_detail_barang_id_' + row).select2('open');
				}
				HELPER.unblock();
			}
		})
	}

	function onHarga(id, kode) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'transaksipembelian/get_harga',
			data: {
				pembelian_id: id
			},
			type: 'post',
			success: r => {
				$('#label_faktur').text(kode);
				$('#print_id').val(id);
				n = 1;
				if (r.total > 0) $('#table-detail_satuan tbody').html('');
				satuan = '<option value="">Pilih</option>';
				$.each(r.satuan, function(i, v) {
					satuan += `<option value="` + v.satuan_id + `">` + v.satuan_kode + `</option>`;
				})
				$.each(r.data, function(i, v) {
					html = `<tr class="barang_` + n + `">
							<td class="sticky-col scol-1" scope="row">
								<input type="hidden" class="form-control" name="hg_barang_id[` + n + `]" id="hg_barang_id_` + n + `" data-id="` + n + `" value="` + v.pembelian_detail_barang_id + `">
								<input type="text" class="form-control" name="hg_barang_kode[` + n + `]" id="hg_barang_kode_` + n + `" value="` + v.barang_kode + `" style="width:20%;display:inline" disabled>
								<input type="text" class="form-control" name="hg_barang_nama[` + n + `]" id="hg_barang_nama_` + n + `" value="` + v.barang_nama + `" style="width:78%;display:inline">
							</td>
							<td class="sticky-col scol-2">
								<input type="hidden" class="form-control" name="hg_barang_satuan_beli[` + n + `]" id="hg_barang_satuan_beli_` + n + `" value="` + v.pembelian_detail_satuan + `">
								<input type="text" class="form-control" name="hg_barang_satuan_kode[` + n + `]" id="hg_barang_satuan_kode_` + n + `" disabled="" value="` + v.barang_satuan_kode + `">
							</td>
							<td class="sticky-col scol-3">
								<input class="form-control number" type="text" name="hg_barang_harga_beli[` + n + `]" id="hg_barang_harga_beli_` + n + `" disabled="" value="` + v.pembelian_detail_harga + `">
								<input class="form-control number" type="hidden" name="hg_barang_harga_barang[` + n + `]" id="hg_barang_harga_barang_` + n + `" disabled="" value="` + v.pembelian_detail_harga_barang + `">
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[` + n + `][1]" id="hg_detail_barang_satuan_id_` + n + `1">
								<input type="hidden" name="hg_detail_barang_satuan_kode[` + n + `][1]" id="hg_detail_barang_satuan_kode_` + n + `1" >
								<select class="form-control select_satuan" name="hg_detail_barang_satuan_satuan_id[` + n + `][1]" id="hg_detail_barang_satuan_satuan_id_` + n + `1" data-id="1" onchange="setSatuanHarga('` + n + `1')" style="width: 100%">` + satuan + `</select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[` + n + `][1]" id="hg_detail_barang_satuan_konversi_` + n + `1" data-id="` + n + `1" readonly>
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[` + n + `][1]" id="hg_detail_barang_satuan_harga_beli_` + n + `1">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width:40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_` + n + `1" name="hg_detail_barang_satuan_keuntungan[` + n + `][1]" onkeyup="countHarga('` + n + `1')">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>	
									<input type="text" class="form-control number" name="hg_detail_barang_satuan_harga_jual[` + n + `][1]" id="hg_detail_barang_satuan_harga_jual_` + n + `1" onkeyup="countLaba('` + n + `1')">
								</div>	
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[` + n + `][2]" id="hg_detail_barang_satuan_id_` + n + `2" data-id="` + n + `2">
								<input type="hidden" name="hg_detail_barang_satuan_kode[` + n + `][2]" id="hg_detail_barang_satuan_kode_` + n + `2" data-id="2">
								<select class="form-control select_satuan" name="hg_detail_barang_satuan_satuan_id[` + n + `][2]" id="hg_detail_barang_satuan_satuan_id_` + n + `2" data-id="2" onchange="setSatuanHarga('` + n + `2')" style="width: 100%">` + satuan + `</select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[` + n + `][2]" id="hg_detail_barang_satuan_konversi_` + n + `2" onkeyup="setHgbeli('` + n + `2')">
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[` + n + `][2]" id="hg_detail_barang_satuan_harga_beli_` + n + `2">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_` + n + `2" name="hg_detail_barang_satuan_keuntungan[` + n + `][2]" onkeyup="countHarga('` + n + `2')">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>	
									<input type="text" class="form-control number" name="hg_detail_barang_satuan_harga_jual[` + n + `][2]" id="hg_detail_barang_satuan_harga_jual_` + n + `2" onkeyup="countLaba('` + n + `2')">
								</div>	
							</td>
							<td>
								<input type="hidden" name="hg_detail_barang_satuan_id[` + n + `][3]" id="hg_detail_barang_satuan_id_` + n + `3" data-id="3">
								<input type="hidden" name="hg_detail_barang_satuan_kode[` + n + `][3]" id="hg_detail_barang_satuan_kode_` + n + `3" data-id="3">
								<select class="form-control select_satuan" name="hg_detail_barang_satuan_satuan_id[` + n + `][3]" id="hg_detail_barang_satuan_satuan_id_` + n + `3" data-id="3" onchange="setSatuanHarga('` + n + `3')" style="width: 100%">` + satuan + `</select>
							</td>
							<td>
								<input class="form-control number" type="text" name="hg_detail_barang_satuan_konversi[` + n + `][3]" id="hg_detail_barang_satuan_konversi_` + n + `3" onkeyup="setHgbeli('` + n + `3')">
								<input class="form-control number" type="hidden" name="hg_detail_barang_satuan_harga_beli[` + n + `][3]" id="hg_detail_barang_satuan_harga_beli_` + n + `3">
							</td>
							<td>
								<div class="input-group">
									<div class="kt-input-icon kt-input-icon--right" style="width: 40%;margin-right: 5px;">
										<input type="text" class="form-control disc" id="hg_detail_barang_satuan_keuntungan_` + n + `3" name="hg_detail_barang_satuan_keuntungan[` + n + `][3]" onkeyup="countHarga('` + n + `3')">
										<span class="kt-input-icon__icon kt-input-icon__icon--right">
											<span>%</span>
										</span>
									</div>	
									<input type="text" class="form-control number" name="hg_detail_barang_satuan_harga_jual[` + n + `][3]" id="hg_detail_barang_satuan_harga_jual_` + n + `3" onkeyup="countLaba('` + n + `3')">
								</div>	
							</td>
						</tr>`;
					$('#table-detail_satuan tbody').append(html);
					$.each(v.satuan, function(k, val) {
						nk = k + 1;
						$('#hg_detail_barang_satuan_satuan_id_' + n + nk).val(val.barang_satuan_satuan_id).trigger('change');
						$('#hg_detail_barang_satuan_id_' + n + '' + nk).val(val.barang_satuan_id);
						$('#hg_detail_barang_satuan_kode_' + n + nk).val(val.barang_satuan_kode);
						$('#hg_detail_barang_satuan_konversi_' + n + nk).val(val.barang_satuan_konversi);
						keuntungan = (val.barang_satuan_harga_jual > 0) ? (val.barang_satuan_harga_jual - (v.pembelian_detail_harga * val.barang_satuan_konversi)) * 100 / v.pembelian_detail_harga : 0;
						$('#hg_detail_barang_satuan_keuntungan_' + n + nk).val(keuntungan);
						harga_beli = v.pembelian_detail_harga_barang * val.barang_satuan_konversi;
						$('#hg_detail_barang_satuan_harga_beli_' + n + nk).val(harga_beli);
						$('#hg_detail_barang_satuan_harga_jual_' + n + nk).val(val.barang_satuan_harga_jual);
					})
					$('.select_satuan').select2()
					n++;
				})
				$('.number').number(true);
				$(".form_data").fadeOut('fast');
				HELPER.toggleForm({
					'toshow': 'form_harga',
					'tohide': 'table_data'
				})
				HELPER.unblock();
			}
		})
	}

	function showIsiDetail(row) {
		satuan_kode = $('#detail_barang_satuan_satuan_id_' + row + ' option:selected').text()
		if (row == '1') $('.lbl_barang_satuan_detail').text(satuan_kode);
		$('#detail_barang_satuan_kode_' + row).val(satuan_kode)
	}

	function detailRow(el) {
		id = $(el).data('id')
		aktif = id;
		st[id] = $('#pembelian_detail_satuan_id_' + id).val();
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'Barang/read',
			data: {
				barang_id: $('#pembelian_detail_barang_id_' + id).val()
			},
			type: 'post',
			success: function(res) {
				HELPER.unblock();
				if (res.barang_id) {
					$('#txtbarang_nama').val(res.barang_nama);
					$('#barang_satuan_parent').val(res.barang_id);
					$.each(res.satuan, function(i, v) {
						n = i + 1;
						$('#detail_barang_satuan_id_' + n).val(v.barang_satuan_id);
						$('#detail_barang_satuan_satuan_id_' + n).val(v.barang_satuan_satuan_id).trigger('change');
						$('#detail_barang_satuan_konversi_' + n).val(v.barang_satuan_konversi);
						$('#detail_barang_satuan_harga_beli_' + n).val(v.barang_satuan_harga_beli);
						$('#detail_barang_satuan_keuntungan_' + n).val(v.barang_satuan_keuntungan);
						$('#detail_barang_satuan_harga_jual_' + n).val(v.barang_satuan_harga_jual);
						$('#detail_barang_satuan_disc_' + n).val(v.barang_satuan_disc);
					})
					$('#detail_barang').modal()
				} else {
					swal.fire('Informasi', 'Detail data harga barang tidak ditemukan!', 'warning');
				}
			}
		})
	}

	function setUntung(row) {
		untung_persen = parseFloat($('#detail_barang_satuan_keuntungan_' + row).val()) || 0;
		hb = parseFloat($('#detail_barang_satuan_harga_beli_' + row).val()) || 0;
		untung = untung_persen * hb / 100;
		hj = untung + hb;
		$('#detail_barang_satuan_harga_jual_' + row).val(hj);
	}

	function setUntungRp(row) {
		hj = parseFloat($('#detail_barang_satuan_harga_jual_' + row).val()) || 0;
		hb = parseFloat($('#detail_barang_satuan_harga_beli_' + row).val()) || 0;
		untung = ((hj - hb) * 100) / hj;
		$('#detail_barang_satuan_keuntungan_' + row).val(untung);
	}

	function setHgbeli(id) {
		idb = id.substring(0, 1);
		hg_barang = parseInt($('#hg_barang_harga_barang_' + idb).val()) || 0;
		isi = parseInt($('#hg_detail_barang_satuan_konversi_' + id).val()) || 0;
		$('#hg_barang_harga_beli_' + id).val(hg_barang * isi);
	}

	function countLaba(id) {
		// hg_detail_barang_satuan_harga_jual_
		idb = id.substring(0, 1);
		hb = parseFloat($('#hg_barang_harga_beli_' + idb).val()) || 0;
		hb = parseFloat($('#hg_barang_harga_beli_' + idb).val()) || 0;
		hj = parseFloat($('#hg_detail_barang_satuan_harga_jual_' + id).val()) || 0;
		untung = (((hj - hb) * 100) / hb) || 0;
		$('#hg_detail_barang_satuan_keuntungan_' + id).val(untung);
	}

	function countHarga(id) {
		idb = id.substring(0, 1);
		untung_persen = parseFloat($('#hg_detail_barang_satuan_keuntungan_' + id).val()) || 0;
		hb = parseFloat($('#hg_barang_harga_beli_' + idb).val()) || 0;
		untung = untung_persen * hb / 100;
		hj = (untung + hb) || 0;
		hasil = Math.round(hj / 100) * 100
		// hasil = hj;
		$('#hg_detail_barang_satuan_harga_jual_' + id).val(hasil);
	}

	function saveHarga() {
		HELPER.confirm({
			title: 'Apakah anda yakin untuk menyimpan detail harga tersebut ?',
			callback: function(res) {
				if (res) {
					HELPER.block();
					$.ajax({
						url: BASE_URL + 'transaksipembelian/save_detail_harga',
						data: $('#form-detailharga').serializeObject(),
						type: 'post',
						success: function(res) {
							HELPER.unblock();
							if (res.success == true) {
								swal.fire('Berhasil', 'Detail data harga barang berhasil diperbarui!', 'success');
								$('#form-detailharga').trigger('reset');
								var cetak = $('#print_checkbox');
								if (cetak.is(":checked")) {
									onPrint($('#print_id').val())
								} else {
									HELPER.back({});
								}
								// onBack();
							} else {
								swal.fire('Gagal', 'Gagal menyimpan data!', 'failed');
							}
						}
					})
				}
			}
		})
	}

	function saveDetail(argument) {
		HELPER.confirm({
			title: 'Apakah anda yakin untuk menyimpan detail harga tersebut ?',
			callback: function(res) {
				if (res) {
					HELPER.block();
					$.ajax({
						url: BASE_URL + 'transaksipembelian/save_haga',
						data: $('#form-detail').serializeObject(),
						type: 'post',
						success: function(res) {
							HELPER.unblock();
							if (res.success == true) {
								swal.fire('Berhasil', 'Detail data harga barang berhasil diperbarui!', 'success');
								$('#form-detail').trigger('reset');
								$('#detail_barang').modal('hide');
								setSatuan(aktif);
							} else {
								swal.fire('Gagal', 'Gagal menyimpan data!', 'failed');
							}
						}
					})
				}
			}
		})
	}

	function setJT() {
		jt = $('#pembelian_jatuh_tempo_hari').val();
		jt_tempo = moment();
		$('#pembelian_jatuh_tempo').val(jt_tempo.add((jt), 'd').format('YYYY-MM-DD'));
	}

	function countJT() {
		tgl_beli = moment($('#pembelian_tanggal').val());
		tgl_jt = moment($('#pembelian_jatuh_tempo').val());
		$('#pembelian_jatuh_tempo_hari').val(tgl_jt.diff(tgl_beli, 'days'));
	}

	function onBayar() {
		$('#jatuh_tempo').hide();
		$('#pembelian_akun_id').prop('disabled', true);
		opsi = $('#pembelian_bayar_opsi').val();
		if (opsi == 'T') $('#pembelian_akun_id').prop('disabled', false);
		else $('#jatuh_tempo').show();
	}

	function newBarang() {
		HELPER.setChangeCombo({
			el: 'barang_satuan_satuan_id_0',
			data: satuan,
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
		});
		HELPER.setChangeCombo({
			el: 'barang_satuan_satuan_id_1',
			data: satuan,
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
		});
		$('#barang_satuan_satuan_id_0, #barang_satuan_satuan_id_1').select2();
		$('#daftar_barang').modal();
	}

	function hgSatuan(row) {
		satuan_kode = $('#hg_detail_barang_satuan_satuan_id_' + row + ' option:selected').text()
		// if(row == '1') $('.lbl_barang_satuan').text(satuan_kode);
		$('#hg_detail_barang_satuan_kode_' + row).val(satuan_kode)
	}

	function setSatuanHarga(row) {
		satuan_kode = $('#hg_detail_barang_satuan_satuan_id_' + row + ' option:selected').text()
		// if(row == '1') $('.lbl_barang_satuan').text(satuan_kode);
		$('#hg_detail_barang_satuan_kode_' + row).val(satuan_kode)
	}

	function showIsi(row) {
		satuan_kode = $('#barang_satuan_satuan_id_' + row + ' option:selected').text()
		if (row == '1') $('.lbl_barang_satuan').text(satuan_kode);
		$('#barang_satuan_kode_' + row).val(satuan_kode)
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
					kategori_kode: kode,
					barang_supplier_id: $('#pembelian_supplier_id').val()
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
									$("#pembelian_detail_barang_id_" + n).select2("trigger", "select", {
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
								$("#pembelian_detail_barang_id_" + row).select2("trigger", "select", {
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
						$('#form-barang select').val("").trigger('change');
					}
				})
			}
		});
	}

	function setBarang(trow) {
		if (trow) {
			trow = trow.join(', ');
		} else trow = '#pembelian_detail_barang_id_' + row;
		supplier = $('#pembelian_supplier_id').val();
		HELPER.ajaxCombo({
			el: trow,
			data: {
				barang_supplier_id: supplier
			},
			url: BASE_URL + 'transaksipembelian/barang_ajax',
			wresult: 'bigdrop'
		});
		$('input.number').number(true);
	}

	function setSatuan(row, est, detail) {
		barang_id = $('#pembelian_detail_barang_id_' + row).val();
		/*konsi = $('#pembelian_detail_barang_id_' + row+' option:selected').data('temp');
		if(konsi == '1') $('#pembelian_is_konsinyasi').val('1');*/
		kode = $('#pembelian_detail_barang_id_' + row + ' option:selected').data('temp');
		$('#barang_kode_' + row).val(kode);
		$.ajax({
			url: BASE_URL + 'barang/list_satuan',
			data: {
				barang_id: barang_id
			},
			type: 'post',
			success: function(res) {
				html = '';
				$.each(res.data, function(i, v) {
					html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_beli="` + v.barang_satuan_harga_beli + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `" data-barang_satuan_keuntungan="` + v.barang_satuan_keuntungan + `" data-new="` + dt_new + `">` + v.barang_satuan_kode + `</option>`
				})
				$('#pembelian_detail_satuan_' + row).html(html);
				$('#pembelian_detail_satuan_' + row).select2();
				/*if(st[row]) $('#pembelian_detail_satuan_' +row).val(st[row]).trigger('change');
				else getHarga(row);*/

				if (est) $('#pembelian_detail_satuan_' + row).val(est).trigger('change');
				else $('#pembelian_detail_satuan_' + row).select2('open');
				getHarga(row, detail);
			}
		})
	}

	function getSupplier(argument) {
		$.post(BASE_URL + 'supplier/read', {
			supplier_id: $('#pembelian_supplier_id').val()
		}, function(res) {
			$('#supplier_nama').val(res.supplier_nama);
			$('#supplier_alamat').val(res.supplier_alamat);
			$('#supplier_telp').val(res.supplier_telp);
		})
		barang = [];
		for (var i = 1; i <= row; i++) {
			barang.push('#pembelian_detail_barang_id_' + i);
		}
		setBarang(barang);
	}

	function getOrder(argument) {
		HELPER.block();
		order = $('#pembelian_order_id').val()
		$.ajax({
			url: BASE_URL + 'orderpembelian/get_order',
			type: 'post',
			data: {
				order_id: order,
			},
			success: function(res) {
				if (res.order_id) {
					$("#pembelian_supplier_id").select2("trigger", "select", {
						data: {
							id: res.order_supplier_id,
							text: res.supplier_kode
						}
					});
					$.each(res.detail, function(i, v) {
						n = i + 1;
						if (n > 1) addBarang();
						// new_st = [v.barang_satuan, v.barang_satuan_opt];
						// $('#pembelian_detail_satuan_' + n).val(v.order_detail_satuan).trigger('change');
						$('#pembelian_detail_qty_' + n).val(v.order_detail_qty);
						$('#pembelian_detail_qty_barang_' + n).val(v.order_detail_qty_barang);
						$('#pembelian_detail_harga_' + n).val(v.order_detail_harga);
						$('#pembelian_detail_harga_barang_' + n).val(v.order_detail_harga_barang);
						$('#pembelian_detail_jumlah_' + n).val(v.order_detail_jumlah);
						$("#pembelian_detail_barang_id_" + n).select2("trigger", "select", {
							data: {
								id: v.order_detail_barang_id,
								text: v.barang_kode + " - " + v.barang_nama
							}
						});
						setSatuan(n, v.order_detail_satuan);
						countRow(n)
					})
				}
				HELPER.unblock()
			}
		})
	}

	function init_table2(argument) {
		var table = $('#table-pembelianbarang').DataTable({
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
				url: BASE_URL + 'transaksipembelian/',
				type: 'POST'
			},
			order: [
				[3, 'desc']
			],
			columnDefs: [
				/*{
					targets: 0,
					orderable: false
				},*/
				{
					targets: 3,
					render: function(data, type, row) {
						return moment(data).format("DD-MM-YYYY");
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
					targets: 8,
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
						dt = $.parseJSON(atob($($(row[0])[2]).data('record')));
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-success" onclick="onHarga('` + dt.pembelian_id + `', '` + row[2] + `')" title="Harga">
                          <span class="la la-money"></span> Harga
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
		var detailRows = [];

		$('#table-pembelianbarang tbody').on('click', 'tr td.details-control', function() {
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
	}


	function onAdd() {
		HELPER.toggleForm({});
	}

	function getHarga(row, detail) {
		harga = $('#pembelian_detail_satuan_' + row + ' option:selected').data();
		if (detail) {
			$('#pembelian_detail_harga_' + row).val(detail.pembelian_detail_harga);
			$('#pembelian_detail_harga_barang_' + row).val(detail.pembelian_detail_harga_barang);
			$('#pembelian_detail_qty_' + row).val(detail.pembelian_detail_qty);
			$('#pembelian_detail_qty_barang_' + row).val(detail.pembelian_detail_qty_barang);
			konversi = parseInt(detail.pembelian_detail_konversi) || parseInt(harga.barang_satuan_konversi);
			$('#pembelian_detail_konversi_' + row).val(konversi);
			$('#pembelian_detail_diskon_' + row).val(detail.pembelian_detail_diskon);
			$('#pembelian_detail_jumlah_' + row).val(detail.pembelian_detail_jumlah);
		} else {
			$('#pembelian_detail_satuan_kode_' + row).val($('#pembelian_detail_satuan_' + row + ' option:selected').text());
			if (harga) {
				console.log(harga);

				konversi = parseInt(harga.barang_satuan_konversi) || 0;


				$('#pembelian_detail_konversi_' + row).val(harga.barang_satuan_konversi)

				qty = parseInt($('#pembelian_detail_qty_' + row).val()) || 0;
				qty_barang = konversi * qty;

				let hargaKonversi = konversi * harga.barang_satuan_harga_beli;
				$('#pembelian_detail_harga_' + row).val(harga.barang_satuan_harga_beli);

				$('#pembelian_detail_qty_barang_' + row).val(qty_barang);
				$('#pembelian_detail_laba_' + row).val(harga.barang_satuan_keuntungan);
			}
		}
		$('#pembelian_detail_harga_' + row).trigger('focus');
		countRow(row)
	}

	function countRow(nrow) {
		satuan_konversi = parseInt($('#pembelian_detail_satuan_' + nrow + ' option:selected').data('barang_satuan_konversi')) || 1;
		nqty = parseInt($('#pembelian_detail_qty_' + nrow).val()) || 0;
		qty_barang = nqty * satuan_konversi;
		nharga = parseInt($('#pembelian_detail_harga_' + nrow).val()) || 0;
		harga_barang = nharga / satuan_konversi;
		$('#pembelian_detail_qty_barang_' + nrow).val(qty_barang);
		$('#pembelian_detail_harga_barang_' + nrow).val(harga_barang);
		jumlah = (nharga * nqty) || 0;

		disc = parseFloat($('#pembelian_detail_diskon_' + nrow).val()) || 0;
		diskon = disc * jumlah / 100;
		jumlah = jumlah - diskon;
		$('#pembelian_detail_jumlah_' + nrow).val(jumlah)

		$('#pembelian_detail_jumlah_' + nrow).val(jumlah)
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
		})
		$('#pembelian_total').val(sub_total);
		$('#pembelian_jumlah_item').val(item);
		$('#pembelian_jumlah_qty').val(qty);
		countPajak()
	}

	function setHarga(nrow) {
		jumlah = parseFloat($('#pembelian_detail_jumlah_' + nrow).val()) || 0;
		qty = parseFloat($('#pembelian_detail_qty_' + nrow).val()) || 0;
		harga = jumlah / qty;
		$('#pembelian_detail_harga_' + nrow).val(harga);
		sub_total = 0;
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val()) || 0;
		})
		$('#pembelian_total').val(sub_total);
		countPajak();
		// countRow(nrow);
	}

	function countRow2(nrow) {
		nqty = qty_barang = parseInt($('#pembelian_detail_qty_' + nrow).val()) || 0;
		harga = harga_barang = parseInt($('#pembelian_detail_harga_' + nrow).val()) || 0;
		satuan_id = $('#pembelian_detail_satuan_' + nrow).val();
		st = $('#pembelian_detail_qty_barang_' + nrow).data('sat');
		if (st) {
			if (satuan_id == st.satuan_opt) {
				qty_barang = st.satuan_isi * nqty;
				harga_barang = harga / st.satuan_isi;
			}
			$('#pembelian_detail_qty_barang_' + nrow).val(qty_barang);
			$('#pembelian_detail_harga_barang_' + nrow).val(harga_barang);
		}
		jumlah = (harga * nqty) || 0;
		disc = parseFloat($('#pembelian_detail_diskon_' + nrow).val()) || 0;
		diskon = disc * jumlah / 100;
		jumlah = jumlah - diskon;
		$('#pembelian_detail_jumlah_' + nrow).val(jumlah)
		sub_total = qty = item = 0;
		done = true;

		$('.jumlah').each(function(i, v) {
			t = parseInt($(v).val()) || 0;
			sub_total += t;
			if (!t) done = false;
			else item++;
		})
		if (done) addBarang()
		$('.qty').each(function(i, v) {
			qty += parseInt($(v).val());
		})
		$('#pembelian_total').val(sub_total);
		$('#pembelian_jumlah_item').val(item);
		$('#pembelian_jumlah_qty').val(qty);
		countDiskon()
	}

	function addPotongan() {
		$('#detail_potongan').modal();
	}

	function countTotal() {
		// disc = parseFloat($('#pembelian_diskon_persen').val()) || 0;
		// pjk = parseFloat($('#pembelian_pajak_persen').val()) || 0;
		// countPajak();
		diskon = parseInt($('#pembelian_diskon').val()) || 0;
		pajak = parseInt($('#pembelian_pajak').val()) || 0;
		total = parseFloat($('#pembelian_total').val());
		grand = (total - diskon + pajak);
		$('#pembelian_bayar_grand_total').val(grand);
		$('#totalbayar').val(grand);

		if (diskon > 0) {
			disc = diskon * 100 / total;
			$('#pembelian_diskon_persen').val(disc)
		}
		/*diskon = disc*total/100;
			$('#pembelian_diskon').val(diskon);
		}else{*/
		// if(diskon>0){
		// 	disc = diskon*100/total;
		// 	$('#pembelian_diskon_persen').val(disc)		
		// }
		// if(pajak>0){
		// 	pjk = pajak*100/total;
		// 	$('#pembelian_pajak_persen').val(pjk)		
		// }
		// p = $('#pembelian_is_pajak')
		// pajak = sub_total*pjk/100; 
		// pajak = 0;
		// if (p.is(":checked")) {	
		/*pajak = parseInt($('#pembelian_pajak').val()) || 0;			
			if(pjk<=0 && pajak<=0){
				pjk = 10;
			}
			$('#pembelian_pajak_persen').val(pjk);
		}else{
			pjk = 0
			$('#pembelian_pajak_persen, #pembelian_pajak').val(0);
		}*/
		// pajak = parseInt($('#pembelian_pajak').val()) || 0;			
		// grand =sub_total + pajak;
		// $('#pembelian_pajak').val(pajak);
	}

	function countPajak(argument) {
		total = parseFloat($('#pembelian_total').val());
		diskon = parseInt($('#pembelian_diskon').val()) || 0;
		pjk = parseFloat($('#pembelian_pajak_persen').val()) || 0;
		sub_total = total - diskon;
		pajak = sub_total * pjk / 100;
		$('#pembelian_pajak').val(pajak);
		countTotal();
	}

	function countDiskon() {
		// hitung diskon
		total = parseFloat($('#pembelian_total').val()) || 0;
		disc = parseFloat($('#pembelian_diskon_persen').val()) || 0;
		diskon = disc * total / 100
		$('#pembelian_diskon').val(diskon);
		countTotal();
		/*sub_total = total-diskon;
		// hitung pajak
		pjk = parseFloat($('#pembelian_pajak_persen').val()) || 0;
		pajak = sub_total*pjk/100; 
		$('#pembelian_pajak').val(pajak);
		// hitung total
		grand = sub_total+pajak;
		$('#pembelian_bayar_grand_total').val(grand);*/

		// diskon = parseInt($('#pembelian_diskon').val()) || 0;	
		// if(pjk>0){
		/*}else{
			pjk = pajak*100/sub_total;
			$('#pembelian_pajak_persen').val(pjk)
		}*/
	}

	function addBarang(reset = 0) {
		if (reset == 0) {
			row++;
		}
		html = `<tr class="barang_` + row + `">
					<td scope="row">
						<input type="hidden" class="form-control" name="pembelian_detail_id[` + row + `]" id="pembelian_detail_id_` + row + `">						
						<input type="text" class="form-control barcode-scan" style="display:none" name="barang_kode[` + row + `]" id="barang_kode_` + row + `"  style="width:18%;display:inline;" data-id="` + row + `" placeholder="Barcode">
						<select class="form-control barang_id" name="pembelian_detail_barang_id[` + row + `]" id="pembelian_detail_barang_id_` + row + `" data-id="` + row + `" onchange="setSatuan('` + row + `')" style="width: 285px;white-space: nowrap"></select></td>
					<td><select class="form-control" name="pembelian_detail_satuan[` + row + `]" id="pembelian_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select></td>
					<td><input class="form-control number" type="text" name="pembelian_detail_harga[` + row + `]" id="pembelian_detail_harga_` + row + `" onkeyup="countRow('` + row + `')" value="0"></td>
					<td>
						<input class="form-control number qty" type="text" name="pembelian_detail_qty[` + row + `]" id="pembelian_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" value="0">
						<input type="hidden" name="pembelian_detail_qty_barang[` + row + `]" id="pembelian_detail_qty_barang_` + row + `" value="0">
						<input type="hidden" name="pembelian_detail_harga_barang[` + row + `]" id="pembelian_detail_harga_barang_` + row + `">
						<input type="hidden" name="pembelian_detail_hpp[` + row + `]" id="pembelian_detail_hpp_` + row + `">
						<input type="hidden" name="pembelian_detail_konversi[` + row + `]" id="pembelian_detail_konversi_` + row + `">
					</td>
					<td>							
						<div class="kt-input-icon kt-input-icon--right">
							<input type="text" class="form-control disc" id="pembelian_detail_diskon_` + row + `" name="pembelian_detail_diskon[` + row + `]" onkeyup="countRow('` + row + `')">
							<span class="kt-input-icon__icon kt-input-icon__icon--right">
								<span>%</span>
							</span>
						</div>	
					</td>
					<td><input class="form-control number jumlah" type="text" name="pembelian_detail_jumlah[` + row + `]" id="pembelian_detail_jumlah_` + row + `" onchange="setHarga('` + row + `')"></td>
					<td>
						<a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning btn-detail" onclick="remRow(this)" title="Hapus" >
                  		<span class="la la-trash"></span></a>						
                  	</td>
				</tr>`;

		$('#table-detail_barang tbody').append(html);
		$('input.number').number(true);
		$('.disc').number(true, 2);
		setBarang();
		setScanner();
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				dt_new = false;


				if (res.pembelian_bayar_opsi == 'T') {
					$('#btnTunai').show();
				}

				$("#pembelian_supplier_id").select2("trigger", "select", {
					data: {
						id: res.pembelian_supplier_id,
						text: res.supplier_kode + ' - ' + res.supplier_nama
					}
				});
				if (res.pembelian_order_id) {
					$("#pembelian_order_id").select2("trigger", "select", {
						data: {
							id: res.pembelian_order_id,
							text: res.order_kode
						}
					});
				}

				if (res.html !== '') {
					$('#table-detail_barang tbody').html($.parseHTML(res.html));
				}

				barang = [];
				row = 1;
				rowPembayaran = 1;

				// Handle Form Detail Barang
				$.each(res.detail, function(i, v) {
					barang.push('#pembelian_detail_barang_id_' + row);
					$('#pembelian_detail_satuan_' + row).select2();
					row++;
				})

				// Handle Form Detail Pembayaran
				$.each(res.pembayaran, function(i, v) {
					if (i > 0) {
						addPembayaranEdit(rowPembayaran);
					}
					$('#order_detail_pembayaran_id_' + rowPembayaran).val(v.order_detail_pembayaran_id);
					$('#order_detail_pembayaran_total_' + rowPembayaran).val(v.order_detail_pembayaran_total);
					$('#order_detail_pembayaran_cara_bayar_' + rowPembayaran).val(v.order_detail_pembayaran_cara_bayar);

					rowPembayaran++;
				})

				console.log('row pembayaran edit :' + rowPembayaran);

				$('#totalbayar').val(res.pembelian_bayar_grand_total);
				$('#totalAppend').val(res.pembelian_bayar_grand_total);



				if (row >= 1) {
					setBarang(barang);
					addBarang(row);
				}
				onBayar();
				onAdd()
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'transaksipembelian/get_detail',
			type: 'post',
			data: {
				pembelian_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					n = i + 1;
					if (n > 1) addBarang();
					$('#pembelian_detail_id_' + n).val(v.pembelian_detail_id);
					st[n] = v.pembelian_detail_satuan;
					$('#pembelian_detail_harga_' + n).val(v.pembelian_detail_harga);
					$('#pembelian_detail_harga_barang_' + n).val(v.pembelian_detail_harga_barang);
					$('#pembelian_detail_qty_' + n).val(v.pembelian_detail_qty);
					$('#pembelian_detail_qty_barang_' + n).val(v.pembelian_detail_qty_barang);
					$('#pembelian_detail_konversi_' + n).val(v.pembelian_detail_konversi);
					$('#pembelian_detail_diskon_' + n).val(v.pembelian_detail_diskon);
					$('#pembelian_detail_jumlah_' + n).val(v.pembelian_detail_jumlah);
					$("#pembelian_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.pembelian_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama,
						}
					});
					setSatuan(n, v.pembelian_detail_satuan, v);
					// countRow(n)
				});

			}
		})
	}

	function resetBarang() {
		row = 1;
		$('#barangHandler').empty();
		addBarang(1);

		$('#supplierDiv').empty();
		$('#supplierDiv').append(`
			<select class="form-control" name="pembelian_supplier_id" id="pembelian_supplier_id" style="width: 100%" onchange="getSupplier()"></select>						
		`);

		HELPER.ajaxCombo({
			el: '#pembelian_supplier_id',
			url: BASE_URL + 'supplier/select_ajax',
			displayField: 'text',
			tags: true
			// chosen: false,
		});
	}

	function remRow(el) {
		id = $(el).data('id');
		$('tr.barang_' + id).remove();
		countRow(id);
	}

	function remRowPembayaran(el, cRow) {
		let cTotal = $('#totalAppend').val();
		let pengurang = $('#order_detail_pembayaran_total_' + cRow).val();
		let cBayar = cTotal - pengurang;

		$('#totalAppend').val(cBayar);


		id = $(el).data('id');
		$('tr.pembayaran_' + id).remove();
		countRow(id);
	}

	function onBack() {
		resetPembayaran();
		resetBarang();
		onRefresh();
		HELPER.toggleForm({
			tohide: 'cetak_data',
			toshow: 'table_data'
		})
		HELPER.back();
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
			<td><a href="javascript:;" data-id="1" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowPembayaran(this, 1)" title="Hapus">
					<span class="la la-trash"></span> Hapus</a></td>
		</tr>
		`);
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-pembelianbarang'
		})
	}

	function save() {
		let pembelian_bayar_opsi = $('#pembelian_bayar_opsi').val();

		if (pembelian_bayar_opsi == 'T') {
			let totalBayar = $('#totalAppend').val();
			let grandTotal = $('#totalbayar').val();

			if (totalBayar < grandTotal) {
				swal.fire('Informasi', 'Jumlah bayar kurang dari nota! Masukkan jumlah bayar sesuai dengan total nota!', 'warning');
				return;
			} else if (totalBayar > grandTotal) {
				swal.fire('Informasi', 'Jumlah bayar lebih dari nota! Masukkan jumlah bayar sesuai dengan total nota!', 'warning');
				return;
			}
		}

		sup = $('#pembelian_supplier_id option:selected').text().split(" - ")[1];
		sup = (sup ? sup : $('#pembelian_supplier_id option:selected').text());
		HELPER.save({
			form: 'form-pembelianbarang',
			data: {
				penerima: sup
			},
			confirm: true,
			callback: function(success, id, record, message) {
				var cetak = $('#cetak_checkbox');
				var harga = $('#edit_checkbox');
				if (success === true) {

					if (harga.is(":checked")) {
						onHarga(record['pembelian_id'], record['pembelian_kode']);
					} else {
						if (cetak.is(":checked")) {
							onPrint(id);
						} else {
							onBack();
						}
					}
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-pembelianbarang',
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
		if (param) {
			$.ajax({
				url: BASE_URL + 'transaksipembelian/cetak/' + param,
				type: 'get',
				success: function(res) {
					var data = JSON.parse(res);
					$(".form_data").fadeOut('fast');
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
				table: 'table-pembelianbarang',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'transaksipembelian/cetak/' + data.pembelian_id,
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

	function format(d) {
		var data = $.parseJSON(atob($($(d[0])[2]).data('record')));
		$.ajax({
			url: BASE_URL + "transaksipembelian/loaddetail",
			type: "POST",
			data: {
				pembelian_detail_parent: data.pembelian_id,
			},
			success: function(response) {
				var hasil = $.parseJSON(response);
				$("#hasil_load_detail").empty();
				$("#hasil_load_detail").append(hasil.html);
			}
		});
		return '<div id="hasil_load_detail" style="margin-left: -15px;padding-right: 30px;padding-left: 50px;"></div>';
	}


	function addPembayaran() {
		rowPembayaran++;

		html = `<tr class="pembayaran_` + rowPembayaran + `">
					<td scope="rowPembayaran">
						<input type="hidden" class="form-control" name="order_detail_pembayaran_id[` + rowPembayaran + `]" id="order_detail_pembayaran_id_` + rowPembayaran + `">
						<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="order_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
					</td>
					<td>
						<select class="form-control" name="order_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="order_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
							<option value="">-Pilih Cara Bayar-</option>
							<option value="Transfer Bank">Transfer Bank</option>
							<option value="Cash">Cash</option>
						</select>
					</td>
					<td>
						<input class="form-control number jumlahNow" type="text" name="order_detail_pembayaran_total[` + rowPembayaran + `]" id="order_detail_pembayaran_total_` + rowPembayaran + `">
					</td>
					<td><a href="javascript:;" data-id="` + rowPembayaran + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
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
			// 	swal.fire('Informasi', 'Jumlah bayar lebih dari nota! Masukkan jumlah bayar sesuai dengan total nota!', 'warning');
			// 	$("#order_detail_pembayaran_total_" + rowPembayaran).val(0);
			// } else {
			// 	total += sum;
			// }
			total += sum;

			$('#totalAppend').val(total);
		});
	}

	function addPembayaranEdit() {

		html = `<tr class="pembayaran_` + rowPembayaran + `">
					<td scope="rowPembayaran">
						<input type="hidden" class="form-control" name="order_detail_pembayaran_id[` + rowPembayaran + `]" id="order_detail_pembayaran_id_` + rowPembayaran + `">
						<input type="date" class="form-control" name="order_detail_pembayaran_tanggal[` + rowPembayaran + `]" value="<?= date('Y-m-d'); ?>" id="order_detail_pembayaran_tanggal_` + rowPembayaran + `" style="width: 100%;">
					</td>
					<td>
						<select class="form-control" name="order_detail_pembayaran_cara_bayar[` + rowPembayaran + `]" id="order_detail_pembayaran_cara_bayar_` + rowPembayaran + `" style="width: 100%" onchange="countSisa()">
							<option value="">-Pilih Cara Bayar-</option>
							<option value="Transfer Bank">Transfer Bank</option>
							<option value="Cash">Cash</option>
						</select>
					</td>
					<td>
						<input class="form-control number jumlahNow" type="text" name="order_detail_pembayaran_total[` + rowPembayaran + `]" id="order_detail_pembayaran_total_` + rowPembayaran + `">
					</td>
					<td><a href="javascript:;" data-id="` + rowPembayaran + `" id="remlast${rowPembayaran}" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRowPembayaran(this, ${rowPembayaran})" title="Hapus">
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
			// 	$("#order_detail_pembayaran_total_" + rowPembayaran).val(0);
			// } else {
			// }
			total += sum;

			$('#totalAppend').val(total);
		});
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
			$('#table-detail_barang').show(100);
			$('#btnBarang').show(100);
			$('#table-pembayaran').hide();
			$('#btnPembayaran').hide();
		} else if (choose == 'T') {
			jatuhTempo.hide();
			lab_jatuhTempo.hide();
			btnTunai.show(100);
		} else {
			btnTunai.hide(100);
			$('#table-pembayaran').hide();
			$('#btnPembayaran').hide();
			jatuhTempo.hide();
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

	function handleChange(getHargaParam) {
		getHarga(getHargaParam);
	}

	function setBayar() {
		let totalBayar = $('#totalbayar').val();
		let totalSatuan = $('[id^=order_detail_pembayaran_total_]');

		totalSatuan.val(totalBayar);
		$('#totalAppend').val(total);
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
		// 	$("#order_detail_pembayaran_total_" + rowPembayaran).val(0);
		// } else {
		// 	total += sum;
		// }

		total += sum;

		$('#totalAppend').val(total);
	});
</script>