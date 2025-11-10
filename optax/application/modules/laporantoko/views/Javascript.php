<script type="text/javascript">
	$(function() {
		$('.bulan').css('display', 'none');
		$('.supplier').css('display', 'none');

		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

		// HELPER.createCombo({
		// 	el: 'wp_select',
		// 	valueField: 'wajibpajak_id',
		// 	displayField: 'wajibpajak_nama',
		// 	url: BASE_URL + 'wajibpajak/select',
		// 	callback: function() {
		// 		$('#wp_select').select2();
		// 	}
		// })

		HELPER.ajaxCombo({
			el: '#toko_select',
			url: BASE_URL + 'laporantoko/gettoko_ajax',
		});
	})

	setFilter = () => {
		let mode = $('#reportMode').val();

		if (mode == 'Single') {
			$('#singleMode').show();
			$('#btnRekap').hide();
			$('#btnRekapExcel').hide();
			$('#btnSingle').show();
		} else if (mode == 'Rekap') {
			$('#singleMode').hide();
			$('#btnSingle').hide();
			$('#btnRekap').show();
			$('#btnRekapExcel').show();
		}
	}

	function jenisLaporan() {
		let laporan = $('#laporan').val();

		if (laporan == '_supplier') {
			$('.supplier').show(100);
			$('#supplierForm').show(100);

			HELPER.createCombo({
				el: 'supplier_id',
				url: BASE_URL + 'supplier/select',
				valueField: 'supplier_id',
				displayField: 'supplier_kode',
				displayField2: 'supplier_nama',
				grouped: true
			});
		} else if (laporan == '_rekap' || laporan == '') {
			$('.supplier').hide(100);
			$('#supplierForm').hide(100);
		}
	}


	function getJenis(el) {
		jenis = $(el).val();
		if (jenis == '_supplier') {
			$('.supplier').removeAttr('style');
			HELPER.createCombo({
				el: 'supplier_id',
				url: BASE_URL + 'supplier/select',
				valueField: 'supplier_id',
				displayField: 'supplier_kode',
				displayField2: 'supplier_nama',
				grouped: true
			});
		} else {
			$('.supplier').css('display', 'none');
		}

		jenisLaporan();
	}

	function getLaporan() {
		let dataSend = {
			toko_id: $('#toko_select').val()
		}

		HELPER.block();
		HELPER.ajax({
			url: BASE_URL + 'laporantoko/get_laporan_single',
			data: dataSend,
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.kt-laporan').show();
				$('.kt-laporan-rekap').hide();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function getLaporanRekap() {
		HELPER.block();
		HELPER.ajax({
			url: BASE_URL + 'laporantoko/get_laporan_rekap',
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.kt-laporan').show();
				$('.kt-laporan-rekap').hide();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function getLaporanRekapExcel() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/laporantoko/spreadsheet_laporan',
			type: 'post',
			dataType: 'JSON',
			success: function(res) {
				console.log(res);
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/tempat_usaha/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}
</script>