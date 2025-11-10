<script type="text/javascript">
	$(function() {
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
			table: BASE_URL + 'rekaptransaksi/',
			read: BASE_URL + 'rekaptransaksi/read',
			store: BASE_URL + 'rekaptransaksi/store',
			update: BASE_URL + 'rekaptransaksi/store',
			destroy: BASE_URL + 'rekaptransaksi/destroy',
		}
		/*HELPER.initTable({
			el : 'table-rekaptransaksi',
			url: HELPER.api.table,
		})*/

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		
		calcForm();
		loadTable();
	});

	function loadTable() {
		HELPER.initTable({
			el: "table-rekaptransaksi",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
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
		});
	}

	function onDelete(realisasi_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'rekaptransaksi/delete',
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
			table: 'table-rekaptransaksi'
		});
		$('#periode_rekaptransaksi').val('');
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

	function addRow(){
		var countRow = $('#table-rekap-form tbody > tr').length;
		var formHtml = $('#table-rekap-form tbody tr:first-child').html();
		$('#table-rekap-form tbody').append(`<tr class="d-flex d-md-table-row">${formHtml}</tr>`);
		for(let i = 0; countRow + 1 >= i; i++){
			let elem = $('#table-rekap-form tbody').children().eq(i);
			elem.children().first().text(i+1);
		}
		calcForm();
	}

	function deleteRow(elem){
		var countRow = $('#table-rekap-form tbody > tr').length;
		if(countRow < 2){
			HELPER.showMessage({
				success: 'warning',
				title: 'Peringatan',
				message: 'Baris pertama tidak dapat dihapus!'
			})
		}else{
			var rowIndex = $(elem).parent().parent().index();
			HELPER.confirm({
				title : 'Pemberitahuan',
				message : `Apakah anda yakin akan menghapus baris ke ${rowIndex + 1}?`,
				callback : function(res) {
					if(res == true){
						$(elem).parent().parent().remove()
						for(let i = 0; countRow + 1 >= i; i++){
							let elem = $('#table-rekap-form tbody').children().eq(i);
							elem.children().first().text(i+1);
						}
					}
				}
			});
		}
	}

	function calcForm(){
		$('input[name="subtotal[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		$('input[name="service[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		$('input[name="tax[]"]').on('input', function () {
			if($(this).val() == ''){
				$(this).val(0);
				return;
			};
			var val = $(this).val().replace(/\D/gi, '');
			if(val){
				val = parseInt(val, 10);
			}
			$(this).val($.number(val));
		})

		calcTotal();
	}

	function calcTotal(){
		$('input[name="subtotal[]"]').on('input', function () {
			// Sum Total Row
			var valrow_subtotal = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_tax && valrow_service){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_subtotal = $('input[name^="subtotal[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_subtotal.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_subtotal){
				$('input[name=sum_subtotal]').val($.number(valsum));
			}else{
				$('input[name=sum_subtotal]').val(0);
			}

			// Sum Total
			var valsum_tax = $('input[name=sum_tax]').val();
			var valsum_service = $('input[name=sum_service]').val();
			valsum_tax = valsum_tax.replace(/\D/gi, '');
			valsum_service = valsum_service.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_tax, 10) + parseInt(valsum_service, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="service[]"]').on('input', function () {
			// SUM Total Row
			var valrow_service = $(this).val();
			var valrow_tax = $(this).parent().parent().find('input[name="tax[]"').val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_service && valrow_tax){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_service, 10) + parseInt(valrow_tax, 10);
				row_total.val($.number(sum_total));
			}

			// Sum Service Column
			var valsum_service = $('input[name^="service[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_service.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_service){
				$('input[name=sum_service]').val($.number(valsum));
			}else{
				$('input[name=sum_service]').val(0);
			}

			// Sum Total
			var valsum_tax = $('input[name=sum_tax]').val();
			var valsum_subtotal = $('input[name=sum_subtotal]').val();
			valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			valsum_tax = valsum_tax.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_tax, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		});

		$('input[name="tax[]"]').on('input', function () {
			// SUM Total Row
			var valrow_tax = $(this).val();
			var valrow_subtotal = $(this).parent().parent().find('input[name="subtotal[]"').val();
			var valrow_service = $(this).parent().parent().find('input[name="service[]"').val();
			var row_total = $(this).parent().parent().find('input[name="total[]"');

			valrow_subtotal = valrow_subtotal.replace(/\D/gi, '');
			valrow_tax = valrow_tax.replace(/\D/gi, '');
			valrow_service = valrow_service.replace(/\D/gi, '');
			if(valrow_subtotal && valrow_tax && valrow_service){
				var sum_total = parseInt(valrow_subtotal, 10) + parseInt(valrow_tax, 10) + parseInt(valrow_service, 10);
				console.log(valrow_subtotal, valrow_tax, sum_total)
				row_total.val($.number(sum_total));
			}

			// Sum TAX Column
			var valsum_tax = $('input[name^="tax[]"]').map((idx, elem) => {
				if($(elem).val() != ''){
					return $(elem).val().replace(/\D/gi, '');
				}
			}).get();
			valsum = valsum_tax.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
			if(valsum_tax){
				$('input[name=sum_tax]').val($.number(valsum));
			}else{
				$('input[name=sum_tax]').val(0);
			}

			// Sum Total
			var valsum_subtotal = $('input[name=sum_subtotal]').val();
			var valsum_service = $('input[name=sum_service]').val();
			valsum_subtotal = valsum_subtotal.replace(/\D/gi, '');
			valsum_service = valsum_service.replace(/\D/gi, '');
			var valsum_total = parseInt(valsum_subtotal, 10) + parseInt(valsum_service, 10) + valsum;
			$('input[name=sum_total]').val($.number(valsum_total));
		})
	}
</script>