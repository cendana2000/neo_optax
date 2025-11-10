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
		$('#barang_stok').number(true);
		$('.disc').number(true, 2);
		HELPER.fields = [
			'barang_id',
			'barang_kode',
			'barang_nama',
			'barang_jenis_barang',
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
			'barang_jenis_barang',
			'barang_harga',
			'barang_kategori_barang',
		]);
		HELPER.api = {
			table: BASE_URL + 'barang/',
			read: BASE_URL + 'barang/read',
			store: BASE_URL + 'barang/store',
			update: BASE_URL + 'barang/update',
			destroy: BASE_URL + 'barang/destroy',
			get_parent: BASE_URL + 'kategori/go_tree',
			setAktif: BASE_URL + 'barang/setAktif',
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

		let jenis = [];

		HELPER.createCombo({
			el: 'barang_jenis_barang',
			valueField: 'jenis_id',
			displayField: 'jenis_nama',
			url: BASE_URL + 'jenis/select',
			callback: function(res) {
				$('#barang_jenis_barang').select2();
				jenis = res.data;
			}
		})


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

		$('#file_import').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

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
		untung = ((hj - hb) / hb) * 100;
		$('#barang_satuan_keuntungan_' + row).val(untung);
	}

	setAktif = (barang_id, status_barang) => {
		HELPER.ajax({
			url: HELPER.api.setAktif,
			data: {
				data: {
					barang_id: barang_id,
					barang_status: status_barang
				}
			},
			datatype: 'json',
			success: (res) => {
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
		});
	};


	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('barang-Update') || HELPER.get_role_access('barang-Delete'));
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
						return full['jenis_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['barang_harga'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return parseInt(full['barang_stok_min']);
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						let stok = parseInt(full['barang_stok']);


						if (stok == null || Number.isNaN(stok)) {
							stok = 0
						}

						if (full['jenis_include_stok'] == 0 || full['jenis_include_stok'] == 2) {
							if (full['barang_aktif'] == 1) {
								stok = '<i class="fas fa-infinity fa-sm"></i>';
							}

							if (full['barang_aktif'] == 2) {
								stok = '<i class="fas fa-check fa-sm text-success"></i>';
							}
							if (full['barang_aktif'] == 3) {
								stok = '<i class="fas fa-times fa-sm text-danger"></i>';
							}
						}
						return stok;
					},
				},
				{
					targets: 8,
					render: function(data, type, full, meta) {
						let barang = '';

						if (full['barang_aktif'] == 0) {
							barang = `<span class="label label-lg label-light-danger label-inline pointer" style="cursor: pointer;" onclick="setAktif('${full['barang_id']}', '${full['barang_aktif']}')" data-value="1">Tidak Aktif</span>`;
						}

						if (full['barang_aktif'] == 1) {
							barang = `<span class="label label-lg label-light-success label-inline pointer" style="cursor: pointer;" onclick="setAktif('${full['barang_id']}', '${full['barang_aktif']}')" data-value="1">Aktif</span>`;
						}

						if (full['barang_aktif'] == 2) {
							barang = `<span class="label label-lg label-light-success label-inline pointer" style="cursor: pointer;" onclick="setAktif('${full['barang_id']}', '${full['barang_aktif']}')" data-value="1">Aktif</span>`;
						}
						if (full['barang_aktif'] == 3) {
							barang = `<span class="label label-lg label-light-danger label-inline pointer" style="cursor: pointer;" onclick="setAktif('${full['barang_id']}', '${full['barang_aktif']}')" data-value="1">Tidak Aktif</span>`;
						}

						return barang;
					},
				},
				{
					targets: -1,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						return `
						 <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Custom Menu" onclick="onLoadCustomMenu('${full['barang_id']}')" >
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-check-square"></i>
							</span>
                        </a>

                        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)" >
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
                        </a>
						`;
						// <a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['barang_id'] + `')"">
						// 	<span class="svg-icon svg-icon-md">
						// 		<i class="fa fa-trash"></i>
						// 	</span>
						// </a>
					},
				},

			],
		});
	}

	function onAdd() {
		$('#barang_satuan_satuan_id_2, #barang_satuan_satuan_id_3').select2();
		HELPER.toggleForm({});
	}

	function showIsi(row) {
		satuan_kode = $('#barang_satuan_satuan_id_' + row + ' option:selected').text()
		if (row == '1') $('#barang_stok_satuan').val(satuan_kode);
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
				if (res.barang_image) {
					$('#preview-image').attr('src', "./" + res.barang_image);
					$('#title-thumbnail').text(res.barang_image.split("/").pop());
				}
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
					setUntungRp(n);
				})
				onAdd()
			}
		})
	}

	function onDelete(barang_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'barang/delete',
						data: {
							id: barang_id
						},
						complete: function(res) {
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
		// $('#btn-Barang').trigger('click');
		// onClear();
		HELPER.backMenu();
	}

	function onClear() {
		$('#supplier_div').empty();
		$('#supplier_div').append(`
			<select class="form-control" id="barang_supplier_id" name="barang_supplier_id" style="width: 100%"></select>
		`);
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

		$('#kategori_div').empty();
		$('#kategori_div').append(`			
			<select class="form-control" name="barang_kategori_barang" id="barang_kategori_barang" style="width: 100%"></select>
		`);
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

		$('#jenisbayar_div').empty();
		$('#jenisbayar_div').append(`						
			<select class="form-control" name="barang_jenis_barang" id="barang_jenis_barang" style="width: 100%"></select>
		`);
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

		$('#satuan1_div').empty();
		$('#satuan1_div').append(`	
			<select class="form-control" name="barang_satuan_satuan_id[1]" id="barang_satuan_satuan_id_1" style="width: 100%" onchange="showIsi('1')"></select>
		`);
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

	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-barang'
		})
	}

	function save() {
		if ($('#barang_satuan_satuan_id_1').val() == '') {
			Swal.fire('Warning', 'Satuan pertama tidak boleh kosong', 'warning');
			return;
		}


		var form = $('#form-barang')[0];

		var formData = new FormData(form);

		kategori = (($('#barang_kategori_barang option:selected').text()).trim()).split(" - ");
		kode = kategori[0] || '';

		formData.append("kategori_kode", kode);

		var comafield = [
			"barang_stok",
			"barang_harga_pokok",
			"barang_satuan_konversi",
			"barang_satuan_harga_beli",
			"barang_satuan_keuntungan",
			"barang_satuan_harga_jual",
			"barang_satuan_disc",
		];

		// Display the key/value pairs
		for (var pair of formData.entries()) {

			if (comafield.filter(f => pair[0].startsWith(f)).length > 0) {
				formData.set(pair[0], pair[1].replace(/,/g, ''));
			}
		}

		HELPER.save({
			form: 'form-barang',
			// data: {
			// 	kategori_kode: kode,
			/*barang_satuan_kode 		: satuan_kode,
			barang_satuan_opt_kode 	: satuan_opt_kode,*/
			// },
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			cache: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
					HELPER.back({});
					$(":reset").trigger("click");
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
			},
			error: function(e) {
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
					"defaultContent": "-",
					"targets": "_all"
				}, {
					targets: 0,
					orderable: false
				}, {
					targets: 1,
					render: function(data, type, full, meta) {
						return full['barang_barcode_tanggal'];
					},
				}, {
					targets: 2,
					render: function(data, type, full, meta) {
						return full['barang_barcode_kode'];
					},
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						xdata = $.parseJSON(atob($($(row[0])[2]).data('record')));
						return `<button type="button" class="btn btn-sm btn-light-warning kt-font-bold kt-font-danger" onclick="remBarcode('` + xdata.barang_barcode_id + `')" title="Hapus" >
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
						// setTimeout(function() {
						// 	$('#editData').click();
						// }, 100);
					} else {
						HELPER.showMessage({
							title: 'Informasi',
							message: 'Barcode Tidak Ditemukan',
						});
						$('[name="barang_barcode_kode"]').prop("readonly", true);
						$('#barang_barcode_kode').val(barcode.barang_barcode_kode);
						$('#form_barang').show();
						const table = $("#table_barcode").DataTable();
						table.clear().draw();
						table.destroy();

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
								barang_barcode_kode: $('#barang_barcode_kode').val(),
								barang_barcode_parent: $('#barang_bc').val(),
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
				"defaultContent": "-",
				"targets": "_all"
			}, {
				targets: 0,
				orderable: false
			}, {
				targets: 1,
				render: function(data, type, full, meta) {
					return full['barang_kode'];
				},
			}, {
				targets: 2,
				render: function(data, type, full, meta) {
					return full['barang_nama'];
				},
			}, {
				targets: 3,
				render: function(data, type, full, meta) {
					return full['kategori_barang_nama'];
				},
			}, {
				targets: 4,
				render: function(data, type, full, meta) {
					return full['barang_satuan_kode'] === "-Pilih-" ? "-" : full['barang_satuan_kode'];
				},
			}, {
				targets: 5,
				render: function(data, type, full, meta) {
					return full['barang_harga'];
				},
			}, {
				targets: 6,
				render: function(data, type, full, meta) {
					return full['barang_satuan_opt_kode'] === "-Pilih-" ? "-" : full['barang_satuan_opt_kode'];
				},
			}, {
				targets: 7,
				render: function(data, type, full, meta) {
					return full['barang_harga_opt'];
				},
			}, {
				targets: 8,
				render: function(data, type, full, meta) {
					return full['barang_stok'];
				},
			}, {
				targets: -1,
				orderable: false,
				render: function(data, type, row) {
					xdata = $.parseJSON(atob($($(row[0])[2]).data('record')));
					return `<a href="javascript:;" id="editData" class="btn btn-sm btn-light-primary" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>`;
				},
			}],
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

	function remRow(row) {
		onJenis();
		let readonly = 'readonly';
		let satVal = 1;
		if (row != 1) {
			readonly = '';
			satVal = '';
		}
		$('.detail_' + row).empty();
		$('.detail_' + row).append(`
			<td scope="row" id="satuan1_div">
			<input type="hidden" class="form-control" name="barang_satuan_id[${row}]" id="barang_satuan_id_${row}">
				<input type="hidden" class="form-control" name="barang_satuan_kode[${row}]" id="barang_satuan_kode_${row}">
				<select class="form-control" name="barang_satuan_satuan_id[${row}]" id="barang_satuan_satuan_id_${row}" style="width:100%" onchange="showIsi('${row}')"></select>
			</td>
			<td>
				<input class="form-control tnumber" type="text" name="barang_satuan_konversi[${row}]" id="barang_satuan_konversi_${row}" ${readonly} value="${satVal}"></td>
			<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_beli[${row}]" id="barang_satuan_harga_beli_${row}" value="0" onkeyup="setUntung('${row}')"></td>
			<td>
				<div class="kt-input-icon kt-input-icon--right">
					<input class="form-control disc" type="text" onkeyup="setUntung('${row}')" name="barang_satuan_keuntungan[${row}]" value="0" id="barang_satuan_keuntungan_${row}">
				</div>
			</td>
			<td><input class="form-control tnumber" type="text" name="barang_satuan_harga_jual[${row}]" id="barang_satuan_harga_jual_${row}" onkeyup="setUntungRp('${row}')"></td>
			<td>
				<div class="kt-input-icon kt-input-icon--right">
					<input class="form-control disc" type="text" name="barang_satuan_disc[${row}]" id="barang_satuan_disc_${row}">
				</div>
			</td>
			<td>
				<a href="javascript:;" data-id="${row}" class="btn btn-light-warning btn-sm" onclick="remRow()" title="Reset">
					<span class="la la-rotate-right"></span> Reset</a>
			</td>							
		`);
		HELPER.createCombo({
			el: 'barang_satuan_satuan_id_' + row,
			valueField: 'satuan_id',
			displayField: 'satuan_kode',
			url: BASE_URL + 'satuan/select',
			callback: function(res) {
				satuan = res.data;
				$('#barang_satuan_satuan_id_' + row).select2();
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
	}

	function readImage(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			const fsize = input.files[0].size;
			const file = Math.round((fsize / 1024));
			if (file > 2048) {
				HELPER.showMessage({
					success: false,
					message: 'File Melebihi 2 MB',
				});
				$('#title-thumbnail').text("Choose file");
				$('#preview-image').attr('src', 'assets/media/noimage.png');
				$(input).val('');
			} else {
				reader.onload = function(e) {
					$('#title-thumbnail').text($(input).val().split(/(\\|\/)/g).pop());
					$('#preview-image').attr('src', e.target.result)

					$('#modal-preview').modal('show');

					// $('#blah').attr('src', e.target.result);
					// $('.show-wajibpajak-image').css('background-image', 'url('+e.target.result+')');
				}

				reader.readAsDataURL(input.files[0]);
			}

		}
	}

	function onChangeThumbnail(el) {
		readImage($(el)[0]);
	}

	function imgError(image) {
		image.onerror = "";
		image.src = "assets/media/noimage.png";
	}

	function fieldInvalid(el) {
		$(el).addClass("is-invalid");
	}


	function fieldChange(el) {
		if ($(el).val() === "") {
			$(el).addClass("is-invalid");
		} else {
			$(el).removeClass("is-invalid");
		}
	}

	async function getTemplate() {
		let fileurl = await (new Promise((resolve, reject) => {
			$.get(BASE_URL + '/barang/get_template', function(res) {
				let fileLocation = BASE_ASSETS + 'laporan/' + res.file;
				return resolve(fileLocation);
			}).fail((xhr, textStatus, errorThrown) => {
				console.log(xhr.responseText);
				reject(false);
			});
		}));

		downloadURI(fileurl, 'produk_template_' + moment().format('YYYY-MM-DD HH:mm:ss'));
	}

	function downloadURI(uri, name) {
		var link = document.createElement("a");
		link.download = name;
		link.href = uri;
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
		delete link;
	}


	function upload() {
		var form = $('#form-import')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);

		HELPER.save({
			form: 'form-import',
			url: BASE_URL + 'barang/import',
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					$('#importModal').modal('hide');
					$('.modal-backdrop').remove();
					onRefresh();
				}
			}
		})
	}

	function onJenis() {
		const jenis_id = $('#barang_jenis_barang').val();
		$.post(BASE_URL + 'jenis/read', {
			jenis_id: jenis_id
		}, function(res) {
			if (res.jenis_include_stok == 2) {
				$('#barang_harga_pokok').val('0');
				$('#barang_stok').val('0');
				$('#barang_harga_pokok').prop('readonly', true);
				$('#barang_satuan_harga_beli_1').val('0');
				$('#barang_satuan_harga_beli_1').prop('readonly', true);
				$('#barang_satuan_harga_beli_2').val('0');
				$('#barang_satuan_harga_beli_2').prop('readonly', true);
				$('#barang_satuan_harga_beli_3').val('0');
				$('#barang_satuan_harga_beli_3').prop('readonly', true);
				$('#barang_satuan_konversi_2').prop('readonly', true);
				$('#barang_satuan_konversi_3').prop('readonly', true);
				$('#barang_satuan_keuntungan_2').prop('readonly', true);
				$('#barang_satuan_keuntungan_3').prop('readonly', true);
				$('#barang_satuan_harga_jual_2').prop('readonly', true);
				$('#barang_satuan_harga_jual_3').prop('readonly', true);
				$('#barang_satuan_disc_2').prop('readonly', true);
				$('#barang_satuan_disc_3').prop('readonly', true);
				$('#barang_stok').prop('readonly', true);
				$('#barang_satuan_satuan_id_2').attr('disabled', 'disabled');
				$('#barang_satuan_satuan_id_3').attr('disabled', 'disabled');

			} else {
				$('#barang_satuan_satuan_id_2').removeAttr('disabled');
				$('#barang_satuan_satuan_id_3').removeAttr('disabled');
				$('#barang_harga_pokok').removeAttr('readonly');
				$('#barang_satuan_harga_beli_1').removeAttr('readonly');
				$('#barang_satuan_harga_beli_2').removeAttr('readonly');
				$('#barang_satuan_harga_beli_3').removeAttr('readonly');
				$('#barang_satuan_konversi_2').removeAttr('readonly');
				$('#barang_satuan_konversi_3').removeAttr('readonly');
				$('#barang_satuan_keuntungan_2').removeAttr('readonly');
				$('#barang_satuan_keuntungan_3').removeAttr('readonly');
				$('#barang_satuan_harga_jual_2').removeAttr('readonly');
				$('#barang_satuan_harga_jual_3').removeAttr('readonly');
				$('#barang_satuan_disc_2').removeAttr('readonly');
				$('#barang_satuan_disc_3').removeAttr('readonly');
				$('#barang_stok').removeAttr('readonly');
			}
		});
	}

	excludePajak = () => {
		event.preventDefault();
		let barang_satuan_harga_jual_1 = $('#barang_satuan_harga_jual_1').val();
		let barang_satuan_harga_jual_2 = $('#barang_satuan_harga_jual_2').val();
		let barang_satuan_harga_jual_3 = $('#barang_satuan_harga_jual_3').val();

		let LStoko = JSON.parse(localStorage.getItem('toko'));

		$('#barang_satuan_harga_jual_1').val(parseInt($('#barang_satuan_harga_jual_1').val() - ($('#barang_satuan_harga_jual_1').val() * (LStoko.jenis_tarif / 100))));
		$('#barang_satuan_harga_jual_2').val(parseInt($('#barang_satuan_harga_jual_2').val() - ($('#barang_satuan_harga_jual_2').val() * (LStoko.jenis_tarif / 100))));
		$('#barang_satuan_harga_jual_3').val(parseInt($('#barang_satuan_harga_jual_3').val() - ($('#barang_satuan_harga_jual_3').val() * (LStoko.jenis_tarif / 100))));
	};

	onLoadCustomMenu = (barang_id) => {
		HELPER.block();
		$('#custommenu-holder').empty();
		HELPER.ajax({
			url: BASE_URL + 'custommenu/select_barang',
			datatype: 'json',
			data: {
				barang_id: barang_id
			},
			complete: (res) => {
				$.map(res.data, (v, i) => {
					const html = `
					<div class="form-check mt-1">
					<input class="form-check-input" type="checkbox" value="${v.custom_menu_id}" name="custom_menu" id="checkbox${i}" data-harga="${v.custom_menu_harga}">
					<label class="form-check-label" for="checkbox${i}">${v.custom_menu_nama.toUpperCase()} - Rp. ${$.number(v.custom_menu_harga)}</label>
					</div>`;

					$('#custommenu-holder').append(html);
				});

				let validValues = [];
				$.map(res.data_filter, (v, i) => {
					validValues[i] = v.custom_menu_id;
				});


				const checkboxes = $("input[name='custom_menu']");
				if (checkboxes.length > 0) {
					for (let i = 0; i < checkboxes.length; i++) {
						if (validValues.indexOf(checkboxes[i].value) !== -1) {
							$(checkboxes[i]).prop("checked", true);
						}
					}
				}

				$('#modal-custom-menu').modal('show');

				$('#barang_custom_menu_barang_id').val(barang_id);
				HELPER.unblock();
			}
		});
	}

	onSaveCustomMenu = () => {
		const barang_id = $('#barang_custom_menu_barang_id').val();
		let selected = $("input[name='custom_menu']:checked").map(function() {
			return this.value;
		}).get();

		HELPER.ajax({
			url: BASE_URL + 'barang/store_custom_menu',
			datatype: 'json',
			data: {
				barang_id: barang_id,
				data: selected,
			},
			complete: (res) => {
				HELPER.showMessage({
					title: 'Berhasil',
					message: res.message,
					success: res.success
				});
				$('#modal-custom-menu').modal('hide');

			}
		});


	}
</script>