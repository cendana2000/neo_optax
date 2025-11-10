<style type="text/css">
	.nominal {
		text-align: right;
	}
</style>
<script type="text/javascript">
	$(function() {
		row = 1;
		view = '1';
		satuan = barang = [];
		HELPER.fields = [
			'opname_id',
			'opname_kode',
			'opname_supplier_id',
			'opname_tanggal',
			'opname_faktur',
			'opname_bayar_opsi',
			'opname_total',
			'opname_bayar_jumlah',
			'opname_bayar_sisa',
		];
		HELPER.setRequired([
			'opname_tanggal',
		]);
		HELPER.api = {
			table: BASE_URL + 'stokopname/',
			read: BASE_URL + 'stokopname/read',
			store: BASE_URL + 'stokopname/store',
			update: BASE_URL + 'stokopname/update',
			destroy: BASE_URL + 'stokopname/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
		}

		HELPER.create_combo_akun({
			el: 'opname_kategori_barang',
			valueField: 'id',
			displayField: 'text',
			parentField: 'parent',
			childField: 'child',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: false,
			callback: function() {
				$('#opname_kategori_barang').select2();
			}
		});
		HELPER.createCombo({
			el: 'opname_operator',
			valueField: 'pegawai_id',
			displayField: 'pegawai_nama',
			url: BASE_URL + 'pegawai/select',
			callback: function() {
				$('#opname_operator').select2();
			}
		})

		$('input.nominal').number(true);
		$('input.number').number(true, 2);
		setBarang();
		// init_table();
		loadTable();
		$('#opname_bayar_opsi').select2();
	});

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-stokopname",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			processing: true,
			serverSide: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['opname_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return moment(full['opname_tanggal']).format("DD-MM-YYYY");
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['opname_total_item'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['opname_total_qty_data'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['opname_total_qty_fisik'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['opname_total_qty_koreksi'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['opname_total_nilai'];
					},
				},
				{
					targets: 8,
					render: function(data, type, full, meta) {
						return full['opname_keterangan'];
					},
				},

				{
					targets: 9,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" onclick="onPrint('` + full['opname_id'] + `')"">
											<span class="svg-icon svg-icon-md">
												<i class="la la-print"></i>
											</span>
										</a>`;
						btn_aksi += `	<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)">
						<span class="svg-icon svg-icon-md">
							<i class="fa fa-pen"></i>
						</span>
                        </a>`;
						btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['opname_id'] + `')"">
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

	function showIsi() {
		$('.lbl_barang_satuan').text($('#barang_satuan option:selected').text());
	}

	function setBarang(trow) {
		if (trow) {
			trow = trow.join(', ');
		} else trow = '#opname_detail_barang_id_' + row;
		// trow = '#opname_detail_barang_id_' + row;
		HELPER.ajaxCombo({
			el: trow,
			data: {
				barang_kategori_barang: $('#opname_kategori_barang').val()
			},
			url: BASE_URL + 'stokopname/select_ajax',
		});
		$('input.number').number(true, 2);
	}

	function allBarang() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'stokopname/getKelompok',
			data: {
				barang_kategori_barang: $('#opname_kategori_barang').val()
			},
			type: 'post',
			success: function(res) {
				view = '2';
				if (res.success == true) {
					$('#table-detail_barang tbody').html(res.html);
				}
				barang = [];
				row = 1;
				$.each(res.barang, function(i, v) {
					barang.push('#opname_detail_barang_id_' + row);
					row++;
				})
				setBarang(barang);
				countRow(row);
				HELPER.unblock();
				view = '1';
			}
		})
	}

	function cariBarang() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("cari_barang");
		filter = input.value;
		filter = filter.toLowerCase();
		table = document.getElementById("table-detail_barang");
		tr = table.getElementsByTagName("tr");
		for (i = 1; i < tr.length; i++) {
			td = tr[i].getElementsByTagName("td")[0];
			if (td) {
				stext = td.getElementsByClassName("txt-search");
				// txtinner = td.
				txtValue = ($(stext).val()).toLowerCase();
				// console.log(txtValue);
				if (txtValue.indexOf(filter) > -1) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";
				}
			}
		}
	}

	function changeKategori() {
		barang = [];
		for (var i = 1; i <= row; i++) {
			barang.push('#opname_detail_barang_id_' + i);
		}
		setBarang(barang);
		/*
		for (var i = 1; i <= row; i++) {
			console.log(row)
			console.log(i)
			setBarang(i);
		}		*/
	}

	/*	function setSatuan(row) {
			sat = $('#opname_detail_barang_id_' + row + ' option:selected').data('temp');
			if(sat){
				res = (atob(sat)).split("||");
				$('#opname_detail_satuan_id_'+row).val(res[0]);
				$('#opname_detail_satuan_kode_'+row).val(res[1]);
				$('#opname_detail_harga_'+row).val(res[2]);
				$('#opname_detail_qty_data_'+row).val(res[3]);
			}	
		}
		*/

	function setSatuan(row) {
		if (view == '1') {
			barang_id = $('#opname_detail_barang_id_' + row).val();
			stok = $('#opname_detail_barang_id_' + row + ' option:selected').data('temp');
			txt = $('#opname_detail_barang_id_' + row + ' option:selected').text();

			$('#txt_search_' + row).val(txt);
			$('#opname_detail_qty_data_' + row).val(stok);
			$.ajax({
				url: BASE_URL + 'barang/list_satuan',
				data: {
					barang_id: barang_id
				},
				type: 'post',
				success: function(res) {
					html = '';
					satuan = res.data;
					$('#opname_detail_satuan_id_' + row).val(satuan[0].barang_satuan);
					$('#opname_detail_satuan_kode_' + row).val(satuan[0].barang_satuan_kode);
					$('#opname_detail_harga_' + row).val(satuan[0].barang_satuan_harga_beli);
				}
			})
		}
	}

	function init_table(argument) {
		var table = $('#table-stokopname').DataTable({
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
				url: BASE_URL + 'stokopname/',
				type: 'POST'
			},
			opname: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 0,
					opnameable: false
				},
				{
					targets: -1,
					opnameable: false,
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

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow(nrow) {
		item = qty = data = fisik = koreksi = nilai = 0;
		qdata = parseFloat($('#opname_detail_qty_data_' + nrow).val()) || 0;
		qfisik = parseFloat($('#opname_detail_qty_fisik_' + nrow).val()) || 0;
		harga = parseFloat($('#opname_detail_harga_' + nrow).val()) || 0;
		qkoreksi = qfisik - qdata;
		$('#opname_detail_qty_koreksi_' + nrow).val(qkoreksi);
		$('#opname_detail_nilai_' + nrow).val(qkoreksi * harga);
		done = true;

		$('.qty').each(function(i, v) {
			t = parseFloat($(v).val()) || 0;
			qty += t;
			if (!t) done = false;
			else item++;
		})
		if (done) addBarang()
		$('.data').each(function(i, v) {
			data += parseFloat($(v).val()) || 0;
		})
		$('.koreksi').each(function(i, v) {
			koreksi += parseFloat($(v).val()) || 0;
		})
		$('.nilai').each(function(i, v) {
			nilai += parseFloat($(v).val()) || 0;
		})
		$('#opname_total_qty_data').val(data);
		$('#opname_total_qty_fisik').val(qty);
		$('#opname_total_qty_koreksi').val(koreksi);
		$('#opname_total_nilai').val(nilai);
		$('#opname_total_item').val(item);
	}

	function addBarang() {
		row++;
		html = `<tr class="barang_` + row + `">
					<td scope="row">
						<input type="hidden" class="txt-search" name="txt_search[` + row + `]" id="txt_search_` + row + `" value="">
						<input type="hidden" class="form-control" name="opname_detail_id[` + row + `]" id="opname_detail_id_` + row + `">
						<select class="form-control barang_id" name="opname_detail_barang_id[` + row + `]" id="opname_detail_barang_id_` + row + `" data-id="` + row + `" style="width: 100%;white-space: nowrap" onchange="setSatuan('` + row + `')"></select>
					</td>
					<td>
						<input class="form-control" type="hidden" name="opname_detail_satuan_id[` + row + `]" id="opname_detail_satuan_id_` + row + `">
						<input class="form-control" type="text" name="opname_detail_satuan_kode[` + row + `]" id="opname_detail_satuan_kode_` + row + `" readonly="">
					</td>
					<td><input class="form-control nominal" type="text" name="opname_detail_harga[` + row + `]" id="opname_detail_harga_` + row + `" readonly=""></td>
					<td><input class="form-control number data" type="text" name="opname_detail_qty_data[` + row + `]" id="opname_detail_qty_data_` + row + `" readonly=""></td>
					<td><input class="form-control number qty" type="text" name="opname_detail_qty_fisik[` + row + `]" id="opname_detail_qty_fisik_` + row + `" onkeyup="countRow('` + row + `')"></td>
					<td><input class="form-control number koreksi" type="text" name="opname_detail_qty_koreksi[` + row + `]" id="opname_detail_qty_koreksi_` + row + `" readonly=""></td>
					<td><input class="form-control nominal nilai" type="text" name="opname_detail_nilai[` + row + `]" id="opname_detail_nilai_` + row + `" readonly=""></td>
					<td><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
							<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-detail_barang').append(html);
		setBarang();
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-stokopname',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				$('#opname_kategori_barang').val(res.opname_kategori_barang).trigger('change');
				$('#opname_operator').val(res.opname_operator).trigger('change');
				if (res.html !== '') {
					$('#table-detail_barang tbody').html(res.html);
				}
				barang = [];
				row = 1;
				$.each(res.detail.data, function(i, v) {
					barang.push('#opname_detail_barang_id_' + row);
					row++;
				})
				if (row >= 1) {
					setBarang(barang);
					countRow(row);
				}
				setBarang();
				$('input.nominal').number(true);
				$('input.number').number(true, 2);
				// getDetailBarang(res.opname_id);
				onAdd()
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'stokopname/get_detail',
			type: 'post',
			data: {
				opname_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					n = i + 1;
					if (n > 1) addBarang();
					$('#opname_detail_id_' + n).val(v.opname_detail_id);
					$('#opname_detail_qty_fisik_' + n).val(v.opname_detail_qty_fisik);
					$('#opname_detail_qty_koreksi_' + n).val(v.opname_detail_qty_koreksi);
					$('#opname_detail_nilai_' + n).val(v.opname_detail_nilai);
					$('#opname_detail_harga_' + n).val(v.opname_detail_harga);
					$("#opname_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.opname_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama
						}
					});
					$('#opname_detail_qty_data_' + n).val(v.opname_detail_qty_data);
					$('#opname_detail_satuan_id_' + n).val(v.opname_detail_satuan_id);
					$('#opname_detail_satuan_kode_' + n).val(v.opname_detail_satuan_kode);
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
		HELPER.toggleForm({
			tohide: 'cetak_data',
			toshow: 'table_data'
		})
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-stokopname'
		})
	}

	function save() {
		HELPER.save({
			form: 'form-stokopname',
			confirm: true,
			callback: function(success, id, record, message) {
				var cetak = $('#cetak_checkbox');
				if (success === true) {
					onRefresh();
					if (cetak.is(":checked")) {
						onPrint(id, 'save')
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
									$("#opname_detail_barang_id_" + n).select2("trigger", "select", {
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
								$("#opname_detail_barang_id_" + row).select2("trigger", "select", {
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

	function onDelete(stokopname_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'stokopname/destroy',
						data: {
							id: stokopname_id
						},
						complete: function(res) {
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								HELPER.refresh({
									table: 'table-stokopname'
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


	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-stokopname',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	$(document).on('keypress', function(e) {
		if (e.which == 13) {
			$('.table_data').hide();
		}
	});

	function onPrint(param, st_print = 'cetak') {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'stokopname/cetak/' + param,
				type: 'get',
				success: function(res) {
					var data = JSON.parse(res);

					if (st_print == 'cetak') {
						HELPER.toggleForm({
							tohide: 'table_data',
							toshow: 'cetak_data'
						})
					} else if (st_print == 'save') {
						HELPER.toggleForm({
							tohide: 'form_data',
							toshow: 'cetak_data'
						})
					}
					$("#pdf-laporan object").attr("data", data.record);
					HELPER.unblock();
				}
			})
		} else {
			HELPER.getDataFromTable({
				table: 'table-stokopname',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'stokopname/cetak/' + data.opname_id,
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
</script>