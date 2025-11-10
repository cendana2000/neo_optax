<script type="text/javascript">
	$(function() {
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-top-right",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "300",
			"timeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		satuan = [];
		$('.tnumber').number(true);
		$('.disc').number(true, 2);
		HELPER.fields = [
			'barang_id',
			'barang_kode',
			'barang_nama',
			'barang_isi',
			'barang_kategori_barang',
			'barang_satuan',
			'barang_satuan_opt',
			'barang_stok_min',
			'barang_stok',
			'barang_harga',
			'barang_persen_untung',
			'barang_barcode',
			'barang_bc'
		];
		HELPER.setRequired([
			'barang_nama',
			'barang_satuan',
			'barang_harga',
		]);
		HELPER.api = {
			table: BASE_URL + 'produk/',
			read: BASE_URL + 'produk/read',
			store: BASE_URL + 'produk/store',
			update: BASE_URL + 'produk/update',
			destroy: BASE_URL + 'produk/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
		}
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
		HELPER.createCombo({
			el: 'barang_satuan_satuan_id_1',
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
			url: BASE_URL + 'satuan/select',
			callback: function(res) {
				satuan = res.data;
				$('#barang_satuan_satuan_id_1').select2();
				HELPER.setChangeCombo({
					el: 'barang_satuan_satuan_id_2',
					valueField: 'satuan_id',
					displayField: 'satuan_kode',
					data: satuan,
				})
				HELPER.setChangeCombo({
					el: 'barang_satuan_satuan_id_3',
					valueField: 'satuan_id',
					displayField: 'satuan_kode',
					data: satuan,
				})
			}
		})
		HELPER.createCombo({
			el: 'barang_supplier_id',
			valueField: 'supplier_id',
			displayField: 'supplier_kode',
			displayField2: 'supplier_nama',
			grouped: true,
			url: BASE_URL + 'supplier/select',
			callback: function() {
				$('#barang_supplier_id').select2();
			}
		})
		// HELPER.createCombo({
		// 	el : 'barang_bc',
		//           valueField : 'barang_id',
		//           displayField : 'barang_kode',
		//           displayField2 : 'barang_nama',
		//           grouped : true,
		//           url : BASE_URL+'barang/select',
		//           callback: function() {
		// 		$('#barang_bc').select2();
		//           }
		// })

		HELPER.createCombo({
			el: 'barang_jenis_barang',
			valueField: 'jenis_id',
			displayField: 'jenis_deskripsi',
			displayField2: 'jenis_nama',
			grouped: true,
			url: BASE_URL + 'jenis/select',
			callback: function() {
				$('#barang_jenis_barang').select2();
			}
		})


		HELPER.ajaxCombo({
			el: '#barang_bc',
			url: BASE_URL + 'barang/select_ajax',
		});
		$('#barang_barcode').keypress(function(event) {
			if (event.which == '10' || event.which == '13') {
				getBarcode($('#barang_barcode').val());
				event.preventDefault();
			}
		});

		// init_table();
		loadTable();
		$("#btn_simpan").hide();
	});

	function setUntung(row) {
		untung_persen = parseFloat($('#barang_satuan_keuntungan_' + row).val()) || 0;
		hb = parseFloat($('#barang_satuan_harga_beli_' + row).val()) || 0;
		untung = untung_persen * hb / 100;
		hj = untung + hb;
		$('#barang_satuan_harga_jual_' + row).val(hj);
	}

	function setUntungRp(row) {
		hj = parseFloat($('#barang_satuan_harga_jual_' + row).val()) || 0;
		hb = parseFloat($('#barang_satuan_harga_beli_' + row).val()) || 0;
		untung = ((hj - hb) * 100) / hj;
		$('#barang_satuan_keuntungan_' + row).val(untung);
	}

	function loadTable() {
		let show_aksi = (HELPER.get_role_access('produk-Update') || HELPER.get_role_access('produk-Delete'));
		HELPER.initTable({
			el: "table-barang",
			url: HELPER.api.table,
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
						return full['kategori_barang_nama'];
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
						return 'Hold';
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['barang_harga'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['barang_stok'];
					},
				},
				{
					targets: 8,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['barang_id'] + `')">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-pen"></i>
						// 					</span>
						// 				</a>`;
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['barang_id'] + `')"">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-trash"></i>
						// 					</span>
						// 				</a>`;
						// return btn_aksi;
						return `
                        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)" >
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['barang_id'] + `')"">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-trash"></i>
							</span>
						</a>
						`;
					},
				},

			],
		});
	}

	function onDelete(barang_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'produk/delete',
						data: {
							id: barang_id
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
									table: 'table-barang'
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

	function init_table(argument) {
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
						// return `
						// <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
						//   <i class="la la-edit"></i> Edit
						// </a>
						// <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
						//   <span class="la la-trash"></span> Hapus
						// </a>`;
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>
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
		$('#barang_satuan_satuan_id_2, #barang_satuan_satuan_id_3').select2();
		HELPER.toggleForm({});
	}

	function showIsi(row) {
		satuan_kode = $('#barang_satuan_satuan_id_' + row + ' option:selected').text()
		// console.log(row+' : '+satuan_kode)
		if (row == '1') $('.lbl_barang_satuan').text(satuan_kode);
		$('#barang_satuan_kode_' + row).val(satuan_kode)
	}

	function onEdit(el) {
		HELPER.toggleForm({
			tohide: 'form_barcode'
		});
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			callback: function(res) {
				$('#barang_jenis_barang').val(res.barang_jenis_barang).trigger('change');
				$('#barang_kategori_barang').val(res.barang_kategori_barang).trigger('change');
				$('#barang_supplier_id').val(res.barang_supplier_id).trigger('change');
				$.each(res.satuan, function(i, v) {
					// $('#barang_satuan_opt').val(res.barang_satuan_opt).trigger('change');
					n = i + 1;
					$('#barang_satuan_id_' + n).val(v.barang_satuan_id);
					// $('#barang_satuan_kode_'+n).val(v.barang_satuan_kode);
					$('#barang_satuan_satuan_id_' + n).val(v.barang_satuan_satuan_id).trigger('change');
					$('#barang_satuan_konversi_' + n).val(v.barang_satuan_konversi);
					$('#barang_satuan_harga_beli_' + n).val(v.barang_satuan_harga_beli);
					$('#barang_satuan_keuntungan_' + n).val(v.barang_satuan_keuntungan);
					$('#barang_satuan_harga_jual_' + n).val(v.barang_satuan_harga_jual);
					$('#barang_satuan_disc_' + n).val(v.barang_satuan_disc);
					$('#barcode_form').hide();
					// showIsi(n);
				})
				onAdd()
			}
		})
	}

	function setHarga() {
		prs = parseFloat($('#barang_persen_untung').val()) || 0;
		hpp = parseFloat($('#barang_harga_pokok').val()) || 0;
		isi = parseFloat($('#barang_isi').val()) || 0;
		v_untung = (prs * hpp) / 100;
		harga = hpp + v_untung;
		harga_2 = harga * isi;
		$('#barang_harga').val(harga);
		$('#barang_harga_opt').val(harga_2);
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-barang'
		})
	}

	function save() {
		kategori = (($('#barang_kategori_barang option:selected').text()).trim()).split(" - ");
		kode = kategori[0] || '';
		HELPER.save({
			form: 'form-barang',
			data: {
				kategori_kode: kode,
				/*barang_satuan_kode 		: satuan_kode,
				barang_satuan_opt_kode 	: satuan_opt_kode,*/
			},
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					HELPER.back({});
				}
			}
		})
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-barang',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function saveBarcode(el) {
		if ($("#barang_id").val()) {
			if ($('#' + el).val()) {
				HELPER.confirm({
					title: 'Tambah Barcode',
					message: "Simpan sebagai barcode baru ?",
					type: 'warning',
					callback: function(success, id, record) {
						if (success == true) {
							HELPER.block();
							$.ajax({
								url: BASE_URL + 'barang/createBarcode',
								data: {
									barang_barcode_kode: $('#' + el).val(),
									barang_barcode_parent: $("#barang_id").val(),
								},
								type: 'post',
								success: function(res) {
									$('#' + el).val('');
									if (res.success) toastr.success(res.message);
									else toastr.warning(res.message);
									init_barcode()
									onRefresh();
									HELPER.unblock();
								},
							})
						}
					}
				})
			} else {
				HELPER.showMessage({
					title: "Informasi",
					message: 'Silahkan masukkan kode barcode terlebih dulu',
				});
			}
		} else {
			HELPER.confirm({
				title: 'Informasi',
				message: "Data barang belum tersimpan, simpan sebagai barang baru ?",
				type: 'warning',
				callback: function(success, id, record) {
					if (success == true) {
						$('#btn_form').click();
					}
				}
			});
		}
	}

	function getBarcode(kode, modal = false, el) {
		if (!kode) kode = $(el).val();
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'barang/get_barcode',
			data: {
				barang_barcode_kode: kode,
				// barang_barcode_parent   : $("#barang_id").val(),				
			},
			type: 'post',
			success: function(res) {
				if (modal) {
					init_barcode(kode)
					if (res.barang_barcode_kode) $('#btn_barcode').attr('disabled', 'disabled');
					else $('#btn_barcode').removeAttr('disabled');
				} else {
					if (res.barang_barcode_id) {
						HELPER.showMessage({
							title: "Informasi",
							message: 'Kode Barcode Telah Terpakai',
						});
						$("#btn_simpan").hide();
						$("#btn_daftar").show();
					} else {
						$("#btn_simpan").show();
						$("#btn_daftar").hide();
					}
				}
				HELPER.unblock();
			}
		})
	}

	function tampilModal() {
		$('#kode_form').val('');
		$('#textModalLabel').text('Detail');
		$('#m_createModal').modal('show');
		init_barcode();
	}

	function init_barcode(barcode_kode = '') {
		if ($.fn.DataTable.isDataTable('#table-barcode')) {
			$('#table-barcode').DataTable().destroy();
		}
		fill = {
			barang_barcode_parent: $('#barang_id').val()
		};
		if (barcode_kode) {
			fill = {
				barang_barcode_parent: $('#barang_id').val(),
				barang_barcode_kode: barcode_kode,
			};
		}

		$('#table-barcode').DataTable({
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
				url: BASE_URL + 'barang/index_barcode',
				type: 'POST',
				data: {
					tfilter: fill
				},
			},
			order: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						xdata = $.parseJSON(atob($($(row[0])[2]).data('record')));
						return `<button type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="remBarcode('` + xdata.barang_barcode_id + `')" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </button>`;
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

	function remBarcode(id) {
		HELPER.confirm({
			title: 'Information',
			message: "Are you sure you want to delete the data?",
			type: 'warning',
			callback: function(success, cc, record) {
				if (success == true) {
					HELPER.block();
					$.ajax({
						url: BASE_URL + 'barang/delete_barcode',
						type: 'post',
						data: {
							barang_barcode_id: id
						},
						success: function(res) {
							if (res.success) {
								init_barcode();
								toastr.success(res.message);
							} else {
								toastr.warning(res.message);
							}
							HELPER.unblock();
						}
					})
				}
			}
		});
	}

	function scanBarcode() {
		// $('#barang_bc').select2();
		$('#form_barang').hide();
		init_barcode();
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_barcode'
		});
	}

	function searchBarcode() {
		var barcode = $('#barcode_form').serializeObject();
		if (barcode.barang_barcode_kode.length > 0) {
			HELPER.block();
			$.ajax({
				url: BASE_URL + 'barang/get_barcode',
				type: 'post',
				data: {
					barang_barcode_kode: barcode.barang_barcode_kode
				},
				success: function(res) {
					if (res.barang_barcode_kode) {
						initBarcode(res.barang_barcode_parent);
						setTimeout(function() {
							$('#editData').click();
						}, 100);
					} else {
						HELPER.showMessage({
							title: 'Informasi',
							message: 'Barcode Tidak Ditemukan',
						});
						$('[name="barang_barcode_kode"]').prop("readonly", true);
						$('#barcode_kode').val(barcode.barang_barcode_kode);
						$('#form_barang').show();
					}
					HELPER.unblock();
				}

			});
		} else {
			HELPER.showMessage({
				title: 'Informasi',
				message: 'Barcode Tidak Boleh Kosong!'
			});
		}
	}

	function save_barcode() {
		if ($("#barang_bc").val()) {
			HELPER.confirm({
				title: 'Tambah Barcode',
				message: "Simpan sebagai barcode baru ?",
				type: 'warning',
				callback: function(success, id, record) {
					if (success == true) {
						HELPER.block();
						$.ajax({
							url: BASE_URL + 'barang/createBarcode',
							data: {
								barang_barcode_kode: $('#barcode_kode').val(),
								barang_barcode_parent: $("#barang_bc").val(),
							},
							type: 'post',
							success: function(res) {
								if (res.success) toastr.success(res.message);
								else toastr.warning(res.message);

								initBarcode(res.record.barang_barcode_parent);
								$('#form_barang').hide();
								HELPER.unblock();
							},
						})
					}
				}
			});
		} else {
			swal.fire('Informasi', 'Silahkan lengkapi data terlebih dahulu!.', "error");
		}
	}

	function initBarcode(parent = '') {
		if ($.fn.DataTable.isDataTable('#table_barcode')) {
			$('#table_barcode').DataTable().destroy();
		}
		fill = {
			barang_id: parent
		};

		$('#table_barcode').DataTable({
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
				url: BASE_URL + 'barang/index_barang_barcode',
				type: 'POST',
				data: {
					tfilter: fill
				},
			},
			order: [
				[1, 'asc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						xdata = $.parseJSON(atob($($(row[0])[2]).data('record')));
						return `<a href="javascript:;" id="editData" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
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

	function onReset() {
		$('#form_barang').hide();
		$('#barang_barcode_kode').val('');
		$('#barang_bc').select2("trigger", "select", {
			data: {
				id: '',
				text: ''
			}
		});
		$('[name="barang_barcode_kode"]').prop("readonly", false);
		initBarcode();
	}

	$('#resetdetail1').click(function() {
		alert('test')
	});

	function remRow() {
		console.log('detect rem row');
		$('#barang_satuan_konversi_1').val('');
		$('#barang_satuan_harga_beli_1').val('');
	}
</script>