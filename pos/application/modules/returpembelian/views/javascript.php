<script type="text/javascript">
	$(function() {
		row = 1;
		satuan = barang = detail = [];
		HELPER.fields = [
			'retur_pembelian_id',
			'pembelian_bayar_grand_total',
			'pembelian_jatuh_tempo',
			'retur_pembelian_kode',
			'retur_pembelian_tanggal',
			'retur_pembelian_pembelian_id',
			'retur_pembelian_supplier_id',
			'retur_pembelian_jumlah_qty',
			'retur_pembelian_jumlah_item',
			'retur_pembelian_total',
			'retur_pembelian_detail_sisa_qty',
		];
		HELPER.setRequired([
			// 'retur_pembelian_pembelian_id',
			'retur_pembelian_tanggal',
		]);
		HELPER.api = {
			table: BASE_URL + 'returpembelian/',
			read: BASE_URL + 'returpembelian/read',
			store: BASE_URL + 'returpembelian/store',
			update: BASE_URL + 'returpembelian/update',
			destroy: BASE_URL + 'returpembelian/destroy',
		}
		// $.post(BASE_URL+'returpembelian/table_detail_barang');
		$('input.number').number(true);

		$("#retur_pembelian_tanggal").datepicker({
			format: "dd/mm/yyyy",
		});

		HELPER.ajaxCombo({
			el: '#retur_pembelian_supplier_id',
			url: BASE_URL + 'supplier/select_ajax',
			displayField: 'text'
		});

		$.post(BASE_URL + 'satuan/select', function(res) {
			satuan = res.data;
		})

		loadTable();
		getSupplier();
		// setBarang();
	});

	function getPembelian() {
		HELPER.block()

		// if(!$('#retur_pembelian_id').val()){
		resetBarang()
		// }
		id = $('#retur_pembelian_pembelian_id').val()
		$.post(BASE_URL + 'transaksipembelian/read', {
			pembelian_id: id
		}, function(res) {
			if (res.pembelian_id) {
				$('#retur_pembelian_supplier_id').val(res.pembelian_supplier_id);
				$('#supplier_telp').text(res.supplier_telp);
				$('#supplier_nama').val(res.supplier_nama);
				$('#pembelian_tanggal').val(moment(res.pembelian_tanggal).format('DD/MM/YYYY'));
				$('#pembelian_bayar_grand_total').val(res.pembelian_bayar_grand_total);
				$('#pembelian_jatuh_tempo').text(moment(res.pembelian_jatuh_tempo).format('DD/MM/YYYY'));

				if (res.pembelian_bayar_grand_total == res.pembelian_bayar_jumlah) {
					HELPER.confirm({
						message: 'Faktur pembelian sudah lunas, apakah anda ingin melanjutkan ?',
						callback: (r) => {
							if (r) {
								$('#status_lunas').css('visibility', 'visible');
								$('#bayar-lunas').val('1');
								$('.alert-lunas').show('500')
							} else {
								$('#supplier_telp').text('No. Telp')
								$('#pembelian_jatuh_tempo').text('JT : dd/mm/yyyy')
								$('#retur_pembelian_pembelian_id').val('').trigger('change')
								$('#form-returpembelianbarang').trigger('reset');
								$('#status_lunas').css('visibility', 'hidden');
								$('#bayar-lunas').val('');
								$('.alert-lunas').hide('500')
							}
						}
					})
				} else {
					$('#status_lunas').css('visibility', 'hidden');
					$('.alert-lunas').hide('500')
				}
			}
			HELPER.unblock()
		})
	}

	function listBarang() {
		if ($('#retur_pembelian_pembelian_id').val() == '' || $('#retur_pembelian_pembelian_id').val() == undefined) {
			swal.fire('Warning', `Pilih no pembelian terlebih dahulu!`, 'warning');
			return;
		}
		$('#daftar_barang').modal();
		HELPER.block();
		if ($.fn.DataTable.isDataTable('#list_barang')) {
			$('#list_barang').DataTable().destroy();
		}

		var tabledetailtr = $("#table-detail_barang tbody tr")
		var barangids = [];
		tabledetailtr.each((key, val) => {
			barangids.push($(val).data("detailid"));
		});

		HELPER.initTable({
			el: "list_barang",
			url: BASE_URL + 'returpembelian/table_detail_barang',
			data: {
				beli: {
					pembelian_detail_parent: $('#retur_pembelian_pembelian_id').val()
				}
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['barang_kode'];
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
						return HELPER.toCurrency(full['pembelian_detail_harga']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return $.number(full['current_stok'])
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return HELPER.toCurrency(full['pembelian_detail_jumlah']);
					},
				},
				{
					targets: 6,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						add = true;
						var isExist = barangids.includes(full.pembelian_detail_id);
						if (isExist) {
							add = false;
						}
						$.each(detail, function(i, v) {
							if (data == v) add = false;
						})
						if (add) {
							aksi = `<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="addThis('${full.pembelian_detail_id}', this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
						} else {
							aksi = `<button type="button" class="btn btn-light-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`;
						}
						return aksi;
					},
				},

			],
		});
		HELPER.unblock();
	}

	function listBarangEdit() {
		if ($('#retur_pembelian_pembelian_id').val() == '' || $('#retur_pembelian_pembelian_id').val() == undefined) {
			swal.fire('Warning', `Pilih no pembelian terlebih dahulu!`, 'warning');
			return;
		}
		$('#daftar_barang').modal();
		HELPER.block();
		if ($.fn.DataTable.isDataTable('#list_barang')) {
			$('#list_barang').DataTable().destroy();
		}

		var tabledetailtr = $("#table-detail_barang tbody tr")
		var barangids = [];
		tabledetailtr.each((key, val) => {
			barangids.push($(val).data("detailid"));
		});

		HELPER.initTable({
			el: "list_barang",
			url: BASE_URL + 'returpembelian/table_detail_barang',
			data: {
				beli: {
					pembelian_detail_parent: $('#retur_pembelian_pembelian_id').val()
				}
			},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['barang_kode'];
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
						return HELPER.toCurrency(full['pembelian_detail_harga']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						// return $.number(full['barang_stok']);
						return $.number(full['current_stok'])
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return HELPER.toCurrency(full['pembelian_detail_jumlah']);
					},
				},
				{
					targets: 6,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						add = true;
						var isExist = barangids.includes(full.pembelian_detail_id);
						if (isExist) {
							add = false;
						}
						$.each(detail, function(i, v) {
							if (data == v) add = false;
						})
						if (add) {
							aksi = `<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="preventEdit()">
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
						} else {
							aksi = `<button type="button" class="btn btn-light-danger btn-pill btn-sm" title="Edit" onclick="preventEdit()">
	                          <i class="la la-remove"></i> batal
	                        </button>`;
						}
						return aksi;
					},
				},

			],
		});
		HELPER.unblock();
	}


	function addThis(id, el) {
		// var parentTr = $(el).parent().parent();


		$.ajax({
			url: BASE_URL + 'transaksipembelian/read_detail',
			data: {
				pembelian_detail_id: id
			},
			type: 'post',
			success: function(dt) {
				$(el).parent().html(`
					<button type="button" class="btn btn-light-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
						<i class="la la-remove"></i> batal
					</button>
				`);
				detail.push(dt.pembelian_detail_id);
				retur_barang = {
					retur_pembelian_detail_id: null,
					retur_pembelian_detail_detail_id: dt.pembelian_detail_id,
					retur_pembelian_detail_barang_id: dt.pembelian_detail_barang_id,
					retur_pembelian_detail_satuan: dt.pembelian_detail_satuan,
					retur_pembelian_detail_satuan_kode: dt.barang_satuan_kode,
					retur_pembelian_detail_harga: dt.pembelian_detail_harga,
					retur_pembelian_detail_qty: 0,
					retur_pembelian_detail_retur_qty: 0,
					retur_pembelian_detail_retur_qty_barang: 0,
					retur_pembelian_detail_sisa_qty: 0,
					retur_pembelian_detail_jumlah: 0,
					pembelian_detail_satuan: dt.pembelian_detail_satuan,
					pembelian_detail_qty: dt.pembelian_detail_qty,
					pembelian_detail_qty_barang: dt.pembelian_detail_qty_barang,
					pembelian_detail_harga: dt.pembelian_detail_harga,
					pembelian_detail_harga_barang: dt.pembelian_detail_harga_barang,
					barang_kode: dt.barang_kode,
					barang_nama: dt.barang_nama,
					barang_stok: dt.current_stok,
					barang_satuan_konversi: dt.barang_satuan_konversi,
				}

				setReturDetail(dt);
				$('tr.no-list').remove();
				$('#table-detail_barang tfoot tr').removeAttr('style');
			}
		})
	}

	function addThis2(el) {
		HELPER.getDataFromTable({
			table: 'list_barang',
			inline: el,
			callback: function(res) {
				$.ajax({
					url: BASE_URL + 'transaksipembelian/read_detail',
					data: {
						pembelian_detail_id: res.pembelian_detail_id
					},
					type: 'post',
					success: function(dt) {
						$(el).parent().html(`<button type="button" class="btn btn-light-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`);

						detail.push(dt.pembelian_detail_id);
						retur_barang = {
							retur_pembelian_detail_id: null,
							retur_pembelian_detail_detail_id: dt.pembelian_detail_id,
							retur_pembelian_detail_barang_id: dt.pembelian_detail_barang_id,
							retur_pembelian_detail_satuan: dt.pembelian_detail_satuan,
							retur_pembelian_detail_harga: dt.pembelian_detail_harga,
							retur_pembelian_detail_qty: 0,
							retur_pembelian_detail_retur_qty: 0,
							retur_pembelian_detail_retur_qty_barang: 0,
							retur_pembelian_detail_sisa_qty: 0,
							retur_pembelian_detail_jumlah: 0,
							pembelian_detail_satuan: dt.pembelian_detail_satuan,
							pembelian_detail_qty: dt.pembelian_detail_qty,
							pembelian_detail_qty_barang: dt.pembelian_detail_qty_barang,
							pembelian_detail_harga: dt.pembelian_detail_harga,
							pembelian_detail_harga_barang: dt.pembelian_detail_harga_barang,
							barang_kode: dt.barang_kode,
							barang_nama: dt.barang_nama,
							barang_satuan_konversi: dt.barang_satuan_konversi,
						}
						addBarang(retur_barang);
						setSatuan(dt.pembelian_detail_barang_id, retur_barang.retur_pembelian_detail_satuan, dt.pembelian_detail_id);
						$('tr.no-list').remove();
						$('#table-detail_barang tfoot tr').removeAttr('style');
					}
				})
			}
		})
	}

	function delThis(el) {

		var parentTr = $(el).parent().parent();
		var checkbox = $(parentTr).find('input[name=checkbox]');
		var encoderecord = atob($(checkbox).data('record'))
		var jsonrecord = "";
		if (encoderecord) {
			try {
				jsonrecord = JSON.parse(encoderecord);
			} catch (e) {}
		}


		remRow(jsonrecord.pembelian_detail_id, 'detailid');

		$(el).parent().html(`
		<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="addThis('${jsonrecord.pembelian_detail_id}',this)" >
			<i class="la la-check-circle"></i> Pilih
		</button>
		`);

		/* 
		 * Backup
		HELPER.getDataFromTable({
			table: 'list_barang',
			inline: $(el),
			callback: function(res) {
				remRow(res.pembelian_detail_id, 'detailid');
				$(el).parent().html(`
				<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="addThis('${res.pembelian_detail_id}',this)" >
					<i class="la la-check-circle"></i> Pilih
				</button>
				`);
			}
		})
		*/

	}

	function setBarang(trow, dt) {
		if (trow) {
			trow = trow.join(', ');
		} else trow = '#retur_pembelian_detail_barang_id_' + row;
		HELPER.ajaxCombo({
			el: trow,
			url: BASE_URL + 'returpembelian/select_ajax',
			// url: BASE_URL + 'stokkartu/barang_ajax',
			wresult: 'bigdrop'
		});
		if (dt) setReturDetail(dt);
		$('input.number').number(true);
	}

	function setSatuan(row) {
		barang_id = $('#retur_pembelian_detail_barang_id_' + row).val();
		stok = $('#retur_pembelian_detail_barang_id_' + row + ' option:selected').data('temp');

		$('#barang_stok_' + row).val(stok);
		$.ajax({
			url: BASE_URL + 'barang/list_satuan',
			data: {
				barang_id: barang_id
			},
			type: 'post',
			success: function(res) {
				html = '';
				satuan = res.data;
				$('#retur_pembelian_detail_satuan_' + row).val(satuan[0].barang_satuan_id);
				// $('#retur_pembelian_detail_satuan_kode_' + row).val(satuan[0].barang_satuan_kode);
				$('#retur_pembelian_detail_harga_' + row).val(satuan[0].barang_satuan_harga_beli);
			}
		})
		// if(view == '1'){
		// }
	}

	function setSatuan2(barang_id, satuan_id, row, retur) {
		$.post(BASE_URL + 'barang/list_satuan', {
			barang_id: barang_id
		}, function(res) {
			html = '';
			$.each(res.data, function(i, v) {
				if (v.barang_satuan_order == 1) html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_beli="` + v.barang_satuan_harga_beli + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `">` + v.barang_satuan_kode + `</option>`
				else {
					if (satuan_id == v.barang_satuan_id) html += `<option value="` + v.barang_satuan_id + `" data-barang_satuan_harga_beli="` + v.barang_satuan_harga_beli + `" data-barang_satuan_konversi="` + v.barang_satuan_konversi + `">` + v.barang_satuan_kode + `</option>`
				}
			})
			$('#retur_pembelian_detail_satuan_' + row).html(html);
			$('#retur_pembelian_detail_satuan_' + row).select2();
			if (satuan_id) {
				$('#retur_pembelian_detail_satuan_' + row).val(satuan_id).trigger('change');
				getHarga(row, retur);
			}
		})
	}

	function getHarga2(row) {
		// dt_satuan = $('#retur_pembelian_detail_satuan_' + row +' option:selected').data();
		satuan = $('#retur_pembelian_detail_satuan_' + row).val();
		beli = $('#retur_pembelian_detail_barang_id_' + row).data()

		qty_barang = qty = parseInt(beli.qty) || 0;
		harga_barang = harga = parseInt(beli.harga) || 0;
		// $('#retur_pembelian_detail_satuan_kode_' + row).val($('#retur_pembelian_detail_satuan_' + row + ' option:selected').text());
		if (beli.satuan_beli != satuan) {
			qty_barang = parseInt(beli.konversi) * qty || 0;
			harga_barang = harga / qty_barang;
		}

		$('#retur_pembelian_detail_qty_' + row).val(qty_barang);
		$('#retur_pembelian_detail_harga_' + row).val(harga_barang);

		// $('#retur_pembelian_detail_harga_' + row).val(harga.barang_satuan_harga_beli)

		/*konversi = parseInt(harga.barang_satuan_konversi) || 0;   
		qty = parseInt($('#pembelian_detail_qty_' + row).val()) || 0;
		qty_barang = konversi*qty;
		$('#pembelian_detail_qty_barang_' + row).val(qty_barang);    
		countRow(row)*/
	}

	function getHarga(row, retur) {
		satuan = $('#retur_pembelian_detail_satuan_' + row).val();
		beli = $('#retur_pembelian_detail_barang_id_' + row).data();

		// $('#retur_pembelian_detail_satuan_kode_' + row).val($('#retur_pembelian_detail_satuan_' + row + ' option:selected').text());
		$('#retur_pembelian_detail_qty_' + row).val((beli.satuan_beli == satuan ? beli.qty : beli.qty_barang));
		$('#retur_pembelian_detail_harga_' + row).val((beli.satuan_beli == satuan ? beli.harga : beli.harga_barang));
		if (retur) {
			$('#retur_pembelian_detail_retur_qty_barang_' + row).val(retur.retur_pembelian_detail_retur_qty_barang)
			$('#retur_pembelian_detail_retur_qty_' + row).val(retur.retur_pembelian_detail_retur_qty)
		} else $('#retur_pembelian_detail_sisa_qty_' + row + ',' + '#retur_pembelian_detail_retur_qty_' + row).val(0)
		countRow(row)
	}

	function getSupplier(argument) {
		// if(!$('#retur_pembelian_id').val()){
		$('#pembelian_tanggal').val('');
		$('#pembelian_bayar_grand_total').val('');
		$('#retur_pembelian_pembelian_id').val('');
		getPembelian();
		// }
		HELPER.ajaxCombo({
			el: '#retur_pembelian_pembelian_id',
			data: {
				pembelian_supplier_id: $('#retur_pembelian_supplier_id').val()
			},
			url: BASE_URL + 'returpembelian/select_pembelian',
			displayField: 'kode'
		});
		// $.post(BASE_URL+'supplier/read',{supplier_id:$('#retur_pembelian_supplier_id').val()}, function(res) {
		// 	$('#supplier_alamat').val(res.supplier_alamat);
		// 	$('#supplier_telp').val(res.supplier_telp);
		// })
	}

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('produk-Update') || HELPER.get_role_access('produk-Delete'));
		HELPER.initTable({
			el: "table-returpembelianbarang	",
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
						return full['retur_pembelian_kode'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_tanggal'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['pembelian_kode'];
					}
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_jumlah_item'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_jumlah_qty'];
					},
				},
				{
					targets: 8,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['retur_pembelian_total']);
					},
				},
				{
					targets: 9,
					render: function(data, type, full, meta) {
						let html = ``;
						if (parseInt(full.pembelian_bayar_jumlah) > 0) {
							html += `<span class="label label-warning label-inline mr-2">Angsur/Lunas</span>`;
						}

						if (full.retur_pembelian_lock == "1") {
							html += `<span class="label label-danger label-inline mr-2">Posting</span>`
						}
						if (parseInt(full.pembelian_bayar_jumlah) <= 0 && full.retur_pembelian_lock != "1") {
							html = `<span class="label label-success label-inline mr-2">Data Dapat Dirubah</span>`;
						}

						return html;
					},
				},
				{
					targets: 10,
					width: '10px',
					orderable: false,
					visible: true,
					className: 'dt-right',
					render: function(data, type, full, meta) {

						let htmlaction = `<a href="javascript:;" class="btn btn-sm btn-info btn-icon mx-1" title="Print" onclick="onPrint('${full['retur_pembelian_id']}')" >
							<span class="svg-icon svg-icon-md">
								<i class="la la-print"></i>
							</span>
            </a>`;

						if (parseInt(full.pembelian_bayar_jumlah) == 0 && full.retur_pembelian_lock != "1") {
							htmlaction += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)" >
								<span class="svg-icon svg-icon-md">
									<i class="fa fa-pen"></i>
								</span>
							</a>
							<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('${full['retur_pembelian_id']}')" title="Hapus">
								<span class="svg-icon svg-icon-md">
									<i class="fa fa-trash"></i>
								</span>
							</a>`;
						} else {
							htmlaction += `<a href="javascript:;" class="btn btn-sm btn-success btn-icon mx-1" title="Detail" onclick="trigDetail(this)">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-info-circle"></i>
							</span>
							</a>
							<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="HELPER.showMessage({info: 'danger', message: 'Data tidak dapat diubah'})" >
								<span class="svg-icon svg-icon-md">
									<i class="fa fa-pen"></i>
								</span>
							</a>
							<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="HELPER.showMessage({info: 'danger', message: 'Data tidak dapat dihapus'})" title="Hapus">
								<span class="svg-icon svg-icon-md">
									<i class="fa fa-trash"></i>
								</span>
							</a>`;
						}

						return htmlaction;
					},
				},

			],
		});
	}

	function onAdd() {
		// Handle  Button List Produk
		$('#button_daftar_beli').show()
		$('#button_daftar_beli_edit').hide()

		HELPER.toggleForm({});
	}

	function countRow(nrow) {
		console.log('countrow run');

		retur = parseInt($('#retur_pembelian_detail_retur_qty_' + nrow).val()) || 0;
		beli = parseInt($('#barang_stok_' + nrow).val()) || 0;
		if (retur <= beli) {
			sub_total = qty = 0;
			jumlah = (parseInt($('#retur_pembelian_detail_harga_' + nrow).val()) * retur) || 0;
			$('#retur_pembelian_detail_jumlah_' + nrow).val(jumlah)
			$('#retur_pembelian_detail_retur_qty_barang_' + nrow).val(retur)
			done = true;
			item = 0;
			$('.qty').each(function(i, v) {
				qty += parseInt($(v).val()) || 0;
				t = parseInt($(v).val());
				if (!t) done = false;
				else item++;
			})
			if (done) addBarang()
			$('#retur_pembelian_detail_sisa_qty_' + nrow).val((beli - retur));
			$('#retur_pembelian_jumlah_qty').val(qty);
		} else {
			$('#retur_pembelian_detail_retur_qty_barang_' + nrow).val(0)
			$('#retur_pembelian_detail_sisa_qty_' + nrow).val(0)
			$('#retur_pembelian_detail_jumlah_' + nrow).val(0)
		}
		$('#retur_pembelian_jumlah_item').val(item);
		countJumlah();
		checkMaxRetur(nrow);
	}

	function countJumlah() {

		sub_total = 0;
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val()) || 0;
		})
		$('#retur_pembelian_total').val(sub_total);
	}

	function checkMaxRetur(id) {
		let retur = $(`#retur_pembelian_detail_retur_qty_${id}`).val();
		let stok = $(`#barang_stok_${id}`).val();


		if (parseInt(retur) > parseInt(stok)) {
			HELPER.showMessage({
				title: 'Peringatan',
				success: 'warning',
				message: 'Retur tidak boleh melebihi stok barang.',
			});
			$('#retur_pembelian_detail_retur_qty_' + id).val(stok);
			countRow(id);
		}
	}

	function setReturDetail(dt) {
		id = row;
		if ($(`#retur_pembelian_detail_barang_id_` + id).val()) {
			addBarang(dt)
			id = row;
		} else {
			$('#retur_pembelian_detail_barang_id_' + id).val(dt.pembelian_detail_barang_id);
			$('#barang_id_fake_' + id).val(dt.barang_kode + ' - ' + dt.barang_nama);
			$(`#retur_pembelian_detail_detail_id_` + id).val(dt.pembelian_detail_id)
			$(`#retur_pembelian_detail_satuan_` + id).val(dt.pembelian_detail_satuan)
			// $(`#retur_pembelian_detail_satuan_kode_` + id).val(dt.barang_satuan_kode)
			$(`#retur_pembelian_detail_harga_` + id).val(dt.pembelian_detail_harga_barang)
			$(`#barang_stok_` + id).val(dt.current_stok)
			// $(`#barang_stok_` + id).val(dt.barang_stok)
			$(`.barang_${id}`).attr('data-detailid', dt.pembelian_detail_id)
			// $('#detail_satuan_' + id).text(dt.barang_satuan_kode);
			addBarang(dt)
		}
	}

	function addBarang(dt, status) {

		row++;
		let remBUtton = ``;
		if (status == 'edit') {
			remBUtton = `<button type="button" data-id="` + row + `" class="btn btn-light-primary btn-sm" title="Hapus" onclick="remRow('` + row + `')" >                      
							<span class="la la-trash"></span> 
						</button>`;
		} else {
			remBUtton = `<button type="button" data-id="` + row + `" class="btn btn-light-warning btn-sm" title="Hapus" onclick="remRow('` + row + `')" >                      
							<span class="la la-trash"></span> 
						</button>`;
		}

		html = `<tr class="barang barang_` + row + `" data-id="` + row + `">
					<td scope="row">
						<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_id[` + row + `]" id="retur_pembelian_detail_id_` + row + `">	
						<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_detail_id[` + row + `]" id="retur_pembelian_detail_detail_id_` + row + `">	
						<input type="hidden" class="form-control" data-id="${row}" readonly name="retur_pembelian_detail_barang_id[${row}]" id="retur_pembelian_detail_barang_id_${row}">
						<input type="hidden" name="row[]" value="${row}">
						<input type="text" class="form-control" readonly id="barang_id_fake_${row}">
					</td>
					<td><input class="form-control number" value="" type="text" name="retur_pembelian_detail_harga[` + row + `]" id="retur_pembelian_detail_harga_` + row + `" onkeyup="countRow('` + row + `')"></td>
					<td><input class="form-control number" type="text" disabled="" value="" name="barang_stok[` + row + `]" id="barang_stok_` + row + `" onchange="countRow('` + row + `')"></td>
					<td>
						<input class="form-control number qty" type="text" name="retur_pembelian_detail_retur_qty[` + row + `]" id="retur_pembelian_detail_retur_qty_` + row + `" value="" onkeyup="countRow('` + row + `')" onchange="countRow('` + row + `')">
						<input class="form-control number" type="hidden" value="" name="retur_pembelian_detail_retur_qty_barang[` + row + `]" id="retur_pembelian_detail_retur_qty_barang_` + row + `">
					</td>
					<td><input class="form-control number" disabled="" type="text" name="retur_pembelian_detail_sisa_qty[` + row + `]" id="retur_pembelian_detail_sisa_qty_` + row + `" value=""></td>
					<td><input class="form-control number jumlah" type="text" name="retur_pembelian_detail_jumlah[` + row + `]" readonly id="retur_pembelian_detail_jumlah_` + row + `"  value="" onchange="countJumlah()"></td>
					<td>
						${remBUtton}					
					</td>
				</tr>	`;
		$('#table-detail_barang').append(html);
		$('input.number').number(true);
	}

	function remRow(id, type = 'row') {
		detail = [];
		$('.barang').each(function(i, v) {
			detail.push($(v).data('id'))
		});

		if (type == "detailid") {
			var trel = $(`tr[data-detailid=${id}]`);
			countRow(trel.data('id'));
			trel.remove();
		} else {
			$('tr.barang_' + id).remove();
			countRow(id);
		}
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-returpembelianbarang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				$('#retur_pembelian_tanggal').val(moment(res.retur_pembelian_tanggal).format('DD/MM/YYYY'));
				$('#retur_pembelian_supplier_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_supplier_id,
						text: res.supplier_kode + ' - ' + res.supplier_nama + ''
					}
				});
				$('#retur_pembelian_pembelian_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_pembelian_id,
						text: res.pembelian_kode + ' - (Rp. ' + res.pembelian_bayar_sisa + ')'
					}
				});
				if (res.html !== '') {
					$('#table-detail_barang tbody').html(res.html);
				}
				barang = [];
				row = 1;
				$.each(res.detail.data, function(i, v) {
					barang.push('#retur_pembelian_detail_barang_id_' + row);
					row++;
				})

				qty = 0;
				done = true;
				item = 0;
				$('.qty').each(function(i, v) {
					qty += parseInt($(v).val()) || 0;
					t = parseInt($(v).val());
					if (!t) done = false;
					else item++;
				})
				if (done) addBarang(null, 'edit')
				$('#retur_pembelian_jumlah_qty').val(qty);
				$('#retur_pembelian_jumlah_item').val(item);
				countJumlah();

				// setBarang();
				$('input.number').number(true);
				onAdd();

				// Handle  Button List Produk
				$('#button_daftar_beli_edit').show()
				$('#button_daftar_beli').hide()
			}
		})
	}

	function trigDetail(el) {
		HELPER.loadData({
			table: 'table-returpembelianbarang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				$('#retur_pembelian_tanggal').val(moment(res.retur_pembelian_tanggal).format('DD/MM/YYYY'));
				$('#retur_pembelian_supplier_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_supplier_id,
						text: res.supplier_kode + ' - ' + res.supplier_nama + ''
					}
				});
				$('#retur_pembelian_pembelian_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_pembelian_id,
						text: res.pembelian_kode + ' - (Rp. ' + res.pembelian_bayar_sisa + ')'
					}
				});
				if (res.html !== '') {
					$('#table-detail_barang tbody').html(res.html);
				}

				$('.form_data input').attr('disabled', true);
				$('.form_data select').attr('disabled', true);
				$('.form_data #table-detail_barang tbody tr td:last-child').remove();
				$('.form_data #table-detail_barang thead tr th:last-child').remove();
				$('.form_data button[onclick="listBarang()"]').remove();
				$('.form_data .card-footer button[type="submit"]').remove();
				$('.form_data .ribbon-target').html('<span class="ribbon-inner bg-primary"></span> FORM RETUR PEMBELIAN BARANG DETAIL')

				barang = [];
				row = 1;
				$.each(res.detail.data, function(i, v) {
					barang.push('#retur_pembelian_detail_barang_id_' + row);
					row++;
				})

				qty = 0;
				done = true;
				item = 0;
				$('.qty').each(function(i, v) {
					qty += parseInt($(v).val()) || 0;
					t = parseInt($(v).val());
					if (!t) done = false;
					else item++;
				})
				if (done) addBarang()
				$('#retur_pembelian_jumlah_qty').val(qty);
				$('#retur_pembelian_jumlah_item').val(item);
				countJumlah();

				/*
				if (row >= 1) {
					setBarang(barang);
					countRow(row - 1);
				}*/
				// setBarang();
				$('input.number').number(true);
				onAdd()
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'returpembelian/get_detail',
			type: 'post',
			data: {
				retur_pembelian_detail_parent: parent
			},
			success: function(res) {
				$.each(res.data, function(i, v) {
					detail.push(v.retur_pembelian_detail_detail_id);
					addBarang(v);
					setSatuan(v.retur_pembelian_detail_barang_id, v.retur_pembelian_detail_satuan, v.retur_pembelian_detail_detail_id, v);
					$('tr.no-list').remove();
					$('#table-detail_barang tfoot tr').removeAttr('style');
				})
			}
		})
	}

	function onBack() {
		HELPER.backMenu();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-returpembelianbarang'
		})
	}

	function save() {
		var tabledetailtr = $("#table-detail_barang tbody tr")
		var barangids = [];
		tabledetailtr.each((key, val) => {
			let detailid = $(val).data("detailid");
			if (detailid) {
				barangids.push(detailid);
			}
		});

		if (barangids.length < 1) {
			HELPER.showMessage({
				title: 'Info',
				success: 'warning',
				message: 'Tidak dapat menyimpan retur, tambah satu barang atau lebih untuk melanjutkan.'
			});
			return;
		}

		HELPER.save({
			form: 'form-returpembelianbarang',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onBack();
					onRefresh();
				}
			}
		})
	}

	function onDelete(id) {
		HELPER.confirm({
			message: 'Apakah anda yakin ingin menghapus retur pembelian',
			callback: function(suc) {
				if (suc) {
					$.ajax({
						url: HELPER.api.destroy,
						data: {
							retur_penjualan_id: id
						},
						type: 'post',
						complete: function(res) {
							var result = res.responseJSON
							if (result.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'Berhasil menghapus retur penjualan'
								});
								onRefresh();
							} else {
								HELPER.showMessage({
									success: 'info',
									title: 'Stop',
									message: res.message
								});
							}
						}
					})
				}
			}
		});
	}

	function onPrint(param) {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'returpembelian/cetak/' + param,
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
				table: 'table-returpembelianbarang',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'returpembelian/cetak/' + data.retur_pembelian_id,
							type: 'get',
							success: function(res) {
								var data = JSON.parse(res);

								HELPER.toggleForm({
									tohide: 'table_data',
									toshow: 'cetak_data'
								});

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
			url: BASE_URL + "returpembelian/loaddetail",
			type: "POST",
			data: {
				retur_pembelian_detail_parent: data.retur_pembelian_id,
			},
			success: function(response) {
				var hasil = $.parseJSON(response);
				$("#hasil_load_detail").empty();
				$("#hasil_load_detail").append(hasil.html);
			}
		});
		return '<div id="hasil_load_detail" style="margin-left: -15px;padding-right: 30px;padding-left: 50px;"></div>';
	}

	function resetBarang() {
		row = 1;
		$('#table-detail_barang tbody').html(`
		<tr class="barang barang_1" data-id="1">
			<td scope="row">
				<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_id[1]" id="retur_pembelian_detail_id_1">
				<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_detail_id[1]" id="retur_pembelian_detail_detail_id_1">
				<!-- <select class="form-control" name="retur_pembelian_detail_barang_id[1]" id="retur_pembelian_detail_barang_id_1" style="width: 100%" data-id="1"></select> -->
				<input type="hidden" class="form-control" readonly name="retur_pembelian_detail_barang_id[1]" id="retur_pembelian_detail_barang_id_1">
				<input type="hidden" name="row[]" value="1">
				<input type="text" class="form-control" readonly id="barang_id_fake_1">
			</td>
			<td>
				<input class="form-control number" value="" type="text" name="retur_pembelian_detail_harga[1]" id="retur_pembelian_detail_harga_1" onkeyup="countRow('1')">
			</td>
			<td><input class="form-control number form-control-solid" type="text" readonly="" value="" name="barang_stok[1]" id="barang_stok_1" onchange="countRow('1')"><span id="detail_satuan_1"></span></td>
			<td>
				<input class="form-control number qty" type="text" name="retur_pembelian_detail_retur_qty[1]" id="retur_pembelian_detail_retur_qty_1" value="" onkeyup="countRow('1')" onchange="countRow('1')">
				<input class="form-control number" type="hidden" value="" name="retur_pembelian_detail_retur_qty_barang[1]" id="retur_pembelian_detail_retur_qty_barang_1">
			</td>
			<td><input class="form-control number form-control-solid" readonly type="text" name="retur_pembelian_detail_sisa_qty[1]" id="retur_pembelian_detail_sisa_qty_1" value=""></td>
			<td><input class="form-control number jumlah" type="text" name="retur_pembelian_detail_jumlah[1]" readonly id="retur_pembelian_detail_jumlah_1" value="" onchange="countJumlah()"></td>
			<td><button type="button" data-id="1" class="btn btn-light-warning btn-sm" title="Edit" onclick="remRow('1')">
					<span class="la la-trash"></span>
				</button></td>
		</tr>
		`);

		$('#retur_pembelian_jumlah_qty').val(0);
		$('#retur_pembelian_jumlah_item').val(0);
		countJumlah();
	}

	function preventEdit() {
		Swal.fire('Warning', 'Tidak dapat melakukan aksi ini saat edit!', 'warning');
	}
</script>