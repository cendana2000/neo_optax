<script type="text/javascript">
	$(function() {
		//tambahan
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		});
		HELPER.fields = [
			'realisasi_id',
			'realisasi_no',
			'realisasi_wajibpajak_id',
			'realisasi_wajibpajak_npwpd',
			'realisasi_tanggal',
			'realisasi_sub_total',
			'realisasi_jasa',
			'realisasi_pajak',
			'realisasi_total',
			'realisasi_created_at',
			'realisasi_created_by',
			'realisasi_updated_at',
			'realisasi_updated_by',
		];
		HELPER.setRequired([]);
		HELPER.api = {
			table: BASE_URL + 'upload/',
			read: BASE_URL + 'upload/read',
			store: BASE_URL + 'upload/store',
			update: BASE_URL + 'upload/store',
			destroy: BASE_URL + 'upload/destroy',
		}
		/*HELPER.initTable({
			el : 'table-upload',
			url: HELPER.api.table,
		})*/

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		calcForm();
		onDetail();
		loadTable();
	});

	function filterBulan() {
		let filterBulan = $('#bulan').val();
		loadTable(filterBulan);
	}

	function onDetail() {
		let realisasiBulan = $('#bulan').val();
		$('#bulan').val(realisasiBulan);
		loadTable(realisasiBulan);
		onAdd();
	}

	function loadTable(filterBulan = null) {
		let data = {
			'filterBulan': null
		};

		if (filterBulan != null) {
			data.filterBulan = filterBulan
		}

		HELPER.initTable({
			el: "table-upload",
			url: HELPER.api.table,
			data: data,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			//sorting: 'desc',
			order: [
				[4, 'asc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['realisasi_tanggal'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_sub_total']);
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_jasa']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_pajak']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_total']);
					},
				},

			],
			fnDrawCallback: function(settings) {
				var {
					sumtotal: {
						total_jasa = 0,
						total_pajak = 0,
						total_subtotal = 0,
						total_total = 0
					}
				} = settings.json;

				$('#subrealisasi_total_omzet').text(`Rp. ${$.number(total_subtotal)}`);
				$('#subrealisasi_total_jasa').text(`Rp. ${$.number(total_jasa)}`);
				$('#subrealisasi_total_pajak').text(`Rp. ${$.number(total_pajak)}`);
				$('#subrealisasi_total_total').text(`Rp. ${$.number(total_total)}`);
			}
		});
	}

	function onDelete(realisasi_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'upload/delete',
						data: {
							id: realisasi_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								onRefresh();
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

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				realisasi_id: id
			},
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-upload'
		});
		$('#periode_upload').val('');
		$('#laporan_realisasi').val('');
	}

	function save() {
		var form = $('#form-realisasi')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
		HELPER.save({
			form: 'form-realisasi',
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					if ($('#btn_save').val() == "1") {
						onPrint(id);
					} else {
						$('.body-form').removeClass('animated fadeInUp portlet-fullscreen');
						$('.body-form').addClass('animated fadeOutDown');
					}
					onRefresh();
					HELPER.back({});
				}
			}
		})

	}
</script>