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


		HELPER.createCombo({
			el: 'nota_awal',
			valueField: 'penjualan_kode',
			displayField: 'penjualan_kode',
			url: BASE_URL + 'laporanpenjualan/select',
			data: {
				tanggal: $("#tanggal").val()
			},
			callback: function(res) {
				$('#nota_awal').select2();
			}
		});
		HELPER.createCombo({
			el: 'nota_akhir',
			valueField: 'penjualan_kode',
			displayField: 'penjualan_kode',
			url: BASE_URL + 'laporanpenjualan/select',
			data: {
				tanggal: $("#tanggal").val()
			},
			callback: function(res) {
				$('#nota_akhir').select2();
			}
		});
	})

	function onNota() {
		HELPER.createCombo({
			el: 'nota_awal',
			valueField: 'penjualan_kode',
			displayField: 'penjualan_kode',
			url: BASE_URL + 'laporanpenjualan/select',
			data: {
				tanggal: $("#tanggal").val()
			},
			callback: function(res) {
				$('#nota_awal').select2();
			}
		});

		HELPER.createCombo({
			el: 'nota_akhir',
			valueField: 'penjualan_kode',
			displayField: 'penjualan_kode',
			url: BASE_URL + 'laporanpenjualan/select',
			data: {
				tanggal: $("#tanggal").val()
			},
			callback: function(res) {
				$('#nota_akhir').select2();
			}
		});
	}

	function setPeriode(el) {
		period = $(el).val();
		$('.bulan, .tanggal').css('display', 'none');
		if (period == 'tanggal') {
			$('.tanggal').css('display', 'block');
			$('.nota').removeAttr('style');
			$('.select2').removeAttr('style');

			$("#btn-rekap").show()
		} else {
			$('.bulan').css('display', 'block');
			$('.nota').css('display', 'none');
			$('.select2').css('display', 'none');

			$("#btn-rekap").hide();
		}

	}

	function getLaporan() {
		HELPER.block();
		if ($('#periode').val() == 'tanggal') {
			$.ajax({
				url: BASE_URL + 'laporanpenjualan/get_laporan',
				data: $('#lap-penjualan').serializeObject(),
				type: 'post',
				dataType: 'json',
				success: function(res) {
					$('.kt-laporan').show();
					$("#pdf-laporan object").attr("data", res.record);
					HELPER.unblock();
				}
			})
		} else {
			$.ajax({
				url: BASE_URL + 'laporanpenjualan/get_laporan_rekap',
				data: $('#lap-penjualan').serializeObject(),
				type: 'post',
				dataType: 'json',
				success: function(res) {
					$('.kt-laporan').show();
					$("#pdf-laporan object").attr("data", res.record);
					HELPER.unblock();
				}
			})
		}
	}

	function getLaporanExcel() {
		event.preventDefault();
		HELPER.block();
		if ($('#periode').val() == 'tanggal') {
			$.ajax({
				url: BASE_URL + '/laporanpenjualan/spreadsheet_laporan',
				type: 'post',
				data: $('#lap-penjualan').serializeObject(),
				dataType: 'JSON',
				success: function(res) {
					if (res.success) {
						let fileLocation = BASE_ASSETS + 'laporan/laporan_penjualan/' + res.file;
						window.location.href = fileLocation;
					}
				},
				complete: function(res) {
					HELPER.unblock();
				}
			})
		} else {
			$.ajax({
				url: BASE_URL + '/laporanpenjualan/spreadsheet_rekap_laporan',
				type: 'post',
				data: $('#lap-penjualan').serializeObject(),
				dataType: 'JSON',
				success: function(res) {
					if (res.success) {
						let fileLocation = BASE_ASSETS + 'laporan/laporan_penjualan/' + res.file;
						window.location.href = fileLocation;
					}
				},
				complete: function(res) {
					HELPER.unblock();
				}
			})
		}
	}

	function tprintRekap(param) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'laporanpenjualan/tprint_rekap',
			data: $('#lap-penjualan').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				HELPER.unblock();
				if (res.tprint) {
					$('#printArea').html(atob(res.tprint));
					var WinPrint = window.open('', '', 'width=900,height=650');
					WinPrint.document.write($('#printArea').html());
					WinPrint.document.close();
					WinPrint.focus();
					WinPrint.print();
					WinPrint.close();
				}
			}
		})

		// HELPER.getDataFromTable({
		// 	table: 'table-penjualanbarang',
		// 	callback: function(data) {
		// 		if (data) {
		// 			$.extend(data, {
		// 				json: true
		// 			});
		// 			$.ajax({
		// 				url: BASE_URL + 'laporanpenjualan/tprint_rekap/' + data.penjualan_tanggal,
		// 				data: data,
		// 				type: 'post',
		// 				success: function(res) {
		// 					HELPER.unblock();
		// 					if (res.tprint) {
		// 						$('#printArea').html(res.tprint);
		// 						var WinPrint = window.open('', '', 'width=900,height=650');
		// 						WinPrint.document.write($('#printArea').html());
		// 						WinPrint.document.close();
		// 						WinPrint.focus();
		// 						WinPrint.print();
		// 						WinPrint.close();
		// 					}
		// 				}
		// 			})
		// 		} else {

		// 		}
		// 	}
		// })

	}
</script>