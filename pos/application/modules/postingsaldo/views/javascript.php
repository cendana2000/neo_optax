<style type="text/css">
	.number {
		text-align: right;
	}
</style>
<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'posting_bulan',
			'posting_persediaan_photobox',
			'posting_persediaan_photocopy',
		];

		loadTable();

		HELPER.ajaxCombo({
			el: '#kartu_barang_id',
			url: BASE_URL + 'barang/select_ajax',
		});
		$('#kartu_transaksi').select2();
		$('.number').number(true);

		$("#posting_bulan").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

	})

	function onBack() {
		HELPER.backMenu();
	}

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('supplier-Update') || HELPER.get_role_access('supplier-Delete'));
		HELPER.initTable({
			el: "table-postingsaldo",
			url: BASE_URL + 'postingsaldo/',
			searchAble: true,
			destroyAble: true,
			responsive: false,
			select: true,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['posting_bulan'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['posting_awal_nilai'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['posting_masuk_nilai']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['posting_keluar_nilai']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['posting_stok_nilai']);
					},
				},
				// {
				// 	targets: 6,
				// 	render: function(data, type, full, meta) {
				// 		return 'Rp.' + $.number(full['posting_laba']);
				// 	},
				// },
				{
					targets: 6,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						btn_aksi += `
						<button type="button" class="btn btn-info btn-sm btn-elevate btn-elevate-air" onclick="onPrint('${full['posting_id']}')">
							<i class="la la-print"></i> Cetak
						</button>
						`;
						return btn_aksi;
					},
				},

			],
		});
	}


	function init_table() {
		if ($.fn.DataTable.isDataTable('#table-postingsaldo')) {
			$('#table-postingsaldo').DataTable().destroy();
		}
		dt = $.extend($('#filter-postingsaldo').serializeObject(), {
			'posting_aktif': '1'
		});
		var table = $('#table-postingsaldo').DataTable({
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
				url: BASE_URL + 'postingsaldo/',
				type: 'POST',
				data: {
					filter: dt
				}
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
						return `
	                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Detail" onclick="onEdit(this)" >
	                  <i style="margin-right:4px" class="flaticon-list-2"></i> Detail
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

	function save2() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'postingsaldo/read',
			data: $('#filter-postingsaldo').serializeObject(),
			type: 'post',
			success: function(dt) {
				HELPER.unblock();
				if (!dt.posting_id) {
					HELPER.confirm({
						title: 'Information',
						message: 'Are you sure you want to process this data ?',
						callback: function(cfr) {
							if (cfr) {
								saving();
							}
						}
					})
				} else {
					HELPER.showMessage({
						success: true,
						title: 'Information',
						message: 'Data ' + $('#posting_bulan').val() + ' telah diproses, silahkan ganti periode bulan untuk memposting persediaan!',
					})
				}
			}
		})
	}

	function save() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'postingsaldo/save_posting',
			data: $('#form-postingsaldo').serializeObject(),
			type: 'post',
			success: function(res) {
				HELPER.showMessage({
					success: true,
					title: 'Sukses',
					message: 'Data ' + $('#posting_bulan').val() + ' berhasil diproses.',
					callback: function (){
						onBack();
					}
				})
			},
			complete: function (res) {
				HELPER.unblock();
			}
		})
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function onEdit(el) {
		HELPER.block();
		HELPER.loadData({
			table: 'table-postingsaldo',
			url: BASE_URL + 'postingsaldo/read',
			server: true,
			inline: $(el),
			callback: function(res) {
				$('.disc').number(true);
				$('.rekap-detail').show('500')
				$('#posting_periode').attr('readonly', true);
				onAdd();
				// countBayar();
			}
		})

	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-postingsaldo'
		})
	}


	function onPrint(posting_id) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'laporansaldo/get_laporan',
			type: 'post',
			data: {
				'posting_id': posting_id
			},
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

		// HELPER.getDataFromTable({
		// 	table: 'table-postingsaldo',
		// 	callback: function(data) {
		// 		console.log(data);
		// 		if (data) {
		// 		} else {
		// 			HELPER.unblock();
		// 		}
		// 	}
		// })
	}
</script>