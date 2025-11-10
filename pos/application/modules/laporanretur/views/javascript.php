<script type="text/javascript">
	$(function() {
		$('.bulan').css('display', 'none');
		/*$('#tanggal').datepicker({
	      	dateFormat: 'dd/mm/yyyy'
	    }); */
		/*$('#tanggal').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
        });*/

		$("#bulan").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
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
			url: BASE_URL + 'laporanretur/laporan_retur_' + $('#jenis_retur').val(),
			data: $('#lap-retur').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.kt-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function getLaporanExcel() {
		event.preventDefault();
		HELPER.block();
		$.ajax({
			url: BASE_URL + '/laporanretur/spreadsheet_laporan',
			type: 'post',
			data: $('#lap-retur').serializeObject(),
			dataType: 'JSON',
			success: function(res) {
				if (res.success) {
					let fileLocation = BASE_ASSETS + 'laporan/laporan_retur/' + res.file;
					window.location.href = fileLocation;
				}
			},
			complete: function(res) {
				HELPER.unblock();
			}
		})
	}
</script>