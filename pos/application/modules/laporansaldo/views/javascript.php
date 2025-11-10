<script type="text/javascript">
	$(function() {
		HELPER.api = {
			get_parent: BASE_URL + 'kategori/go_tree',
		}

		$("#bulan").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

		$('.select2').select2();
		$('.bulan').css('display', 'none');

		// HELPER.createCombo({
		// 	el: 'posting_detail_kategori_id',
		// 	valueField: 'kategori_barang_id',
		// 	displayField: 'kategori_barang_nama',
		// 	// url: BASE_URL + 'laporansaldo/get_kelompok',
		// 	url: BASE_URL + 'kategori/go_tree',
		// 	callback: function(res) {
		// 		$('#posting_detail_kategori_id').select2();
		// 	}
		// });

		HELPER.create_combo_akun({
			el: 'posting_detail_kategori_id',
			valueField: 'id',
			displayField: 'text',
			parentField: 'parent',
			childField: 'child',
			url: HELPER.api.get_parent,
			withNull: true,
			nesting: true,
			chosen: false,
			callback: function() {
				$('#posting_detail_kategori_id').select2();
			}
		});

	})

	function setPeriode(el) {
		period = $(el).val();
		$('.bulan, .tanggal').css('display', 'none');
		if (period == 'tanggal') $('.tanggal').css('display', 'block');
		else $('.bulan').css('display', 'block');

	}

	function getLaporan() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'laporansaldo/get_laporan' + ($('#jenis').val() == 'detail' ? '_' + $('#jenis').val() : ''),
			data: $('#lap-saldo').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				HELPER.toggleForm({
					toshow: 'kt-laporan',
					tohide: 'table_data'
				});
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function setLaporan(el) {
		if ($(el).val() == 'detail') {
			$('#tampil').prop('hidden', false);
			$('label[for=posting_detail_kategori_id]').show();
			$('#posting_detail_kategori_id').parent().show();
		} else {
			$('#tampil').prop('hidden', true);
			$('label[for=posting_detail_kategori_id]').hide();
			$('#posting_detail_kategori_id').parent().hide();
		}
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-saldodetail'
		})
	}

	function getTable() {
		HELPER.block();
		if ($.fn.DataTable.isDataTable('#table-saldodetail')) {
			$('#table-saldodetail').DataTable().destroy();
		}
		var table = $('#table-saldodetail').DataTable({
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
				url: BASE_URL + 'laporansaldo/',
				type: 'POST',
				data: {
					posting_detail_bulan: $('#bulan').val(),
					posting_detail_kategori_id: $('#posting_detail_kategori_id').val(),
				}
			},
			order: [
				[2, 'desc']
			],
			columnDefs: [{
				defaultContent: "-",
				targets: "_all"
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
					return $.number(full['posting_detail_awal_stok']);
				},
			}, {
				targets: 4,
				render: function(data, type, full, meta) {
					return $.number(full['saldo_masuk']);
				},
			}, {
				targets: 5,
				render: function(data, type, full, meta) {
					return $.number(full['saldo_keluar']);
				},
			}, {
				targets: 6,
				render: function(data, type, full, meta) {
					return $.number(full['posting_detail_opname_qty']);
				},
			}, {
				targets: 7,
				render: function(data, type, full, meta) {
					return $.number(full['posting_detail_akhir_stok']);
				},
			}, {
				targets: 8,
				render: function(data, type, full, meta) {
					return $.number(full['posting_detail_hpp']);
				},
			}, {
				targets: 9,
				render: function(data, type, full, meta) {
					return $.number(full['posting_detail_akhir_nilai']);
				},
			}, ],
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
			initComplete: function(settings, json) {
				HELPER.toggleForm({
					tohide: 'kt-laporan',
					toshow: 'table_data'
				});
				HELPER.unblock();
			}
		});
	}

	function getLaporanExcel() {
		event.preventDefault();
		HELPER.block();
		if ($('#jenis').val() == 'rekap') {
			$.ajax({
				url: BASE_URL + '/laporansaldo/spreadsheet_laporan',
				type: 'post',
				data: $('#lap-saldo').serializeObject(),
				dataType: 'JSON',
				success: function(res) {
					if (res.success) {
						let fileLocation = BASE_ASSETS + 'laporan/laporan_saldo/' + res.file;
						window.location.href = fileLocation;
					}
				},
				complete: function(res) {
					HELPER.unblock();
				}
			})
		} else {
			$.ajax({
				url: BASE_URL + '/laporansaldo/spreadsheet_perincian_laporan',
				type: 'post',
				data: $('#lap-saldo').serializeObject(),
				dataType: 'JSON',
				success: function(res) {
					if (res.success) {
						let fileLocation = BASE_ASSETS + 'laporan/laporan_saldo/' + res.file;
						window.location.href = fileLocation;
					}
				},
				complete: function(res) {
					HELPER.unblock();
				}
			})
		}
	}
</script>