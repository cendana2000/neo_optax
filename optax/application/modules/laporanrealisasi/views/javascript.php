<script type="text/javascript">
	$(function() {
		$('.bulan').css('display', 'none');
		$('.supplier').css('display', 'none');
		/*$('#tanggal').datepicker({
	      	dateFormat: 'dd/mm/yyyy'
	    }); */
		/*$('#tanggal').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
        });*/

		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

		HELPER.createCombo({
			el: 'jenis_pajak',
			valueField: 'jenis_id',
			displayField: 'jenis_nama',
			url: BASE_URL + 'jenis/select',
			callback: function() {
				$('#jenis_pajak').select2();
			}
		})
	})

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

	function setPeriode(el) {
		period = $(el).val();
		$('.bulan, .tanggal').css('display', 'none');
		if (period == 'tanggal') $('.tanggal').css('display', 'block');
		else $('.bulan').css('display', 'block');

	}

	function getLaporan() {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'laporanrealisasi/get_laporan',
			data: $('#lap-pembelian').serializeObject(),
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
		$.ajax({
			url: BASE_URL + 'laporanrealisasi/get_laporan_rekap',
			data: $('#lap-pembelian').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.kt-laporan-rekap').show();
				$('.kt-laporan').hide();
				$("#pdf-laporan-rekap object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}
function getLaporanRekapExcelRI() {
	event.preventDefault();
	HELPER.block();
	$.ajax({
		url: BASE_URL + '/laporanrealisasi/spreadsheet_laporan',
		type: 'post',
		data: $('#lap-pembelian').serializeObject(),
		dataType: 'JSON',
		success: function(res) {
			if (res.success) {
				let fileLocation = BASE_ASSETS + 'laporan/laporan_realisasi/' + res.file;
				window.location.href = fileLocation;
			}
		},
		complete: function(res) {
			HELPER.unblock();
		}
	})
}
function getLaporanRekapExcelRE() {
	event.preventDefault();
	HELPER.block();
	$.ajax({
		url: BASE_URL + '/laporanrealisasi/spreadsheet_rekap',
		type: 'post',
		data: $('#lap-pembelian').serializeObject(),
		dataType: 'JSON',
		success: function(res) {
			if (res.success) {
				let fileLocation = BASE_ASSETS + 'laporan/laporan_realisasi/' + res.file;
				window.location.href = fileLocation;
			}
		},
		complete: function(res) {
			HELPER.unblock();
		}
	})
}
</script>