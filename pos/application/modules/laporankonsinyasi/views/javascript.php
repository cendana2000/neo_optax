<script type="text/javascript">
	$(function(){
		$('.bulan').css('display','none');
	})

	function setPeriode(el) {
		period = $(el).val();
		$('.bulan, .tanggal').css('display','none');
		if(period == 'tanggal') $('.tanggal').css('display','block');
		else $('.bulan').css('display','block');
	}

	function getLaporan() {
		HELPER.block();
		$.ajax({
			url 	: BASE_URL+$('#jenis_laporan').val()+'konsinyasi',
			data 	: $('#lap-konsinyasi').serializeObject(),
			type 	: 'post',
			dataType: 'json',
			success : function(res) {
				$('.kt-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();				
			}
		})
	}
</script>