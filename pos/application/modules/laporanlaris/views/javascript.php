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

		$('.bulan').css('display', 'none');

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

		// HELPER.create_combo_akun({
		// 	el : 'barang_kategori_barang',
		// 	valueField : 'id',
		// 	displayField : 'text',
		// 	parentField : 'parent',
		// 	childField : 'child',
		// 	url : HELPER.api.kategori,
		// 	withNull : false,
		// 	nesting : true,
		// 	chosen : false,
		// });	
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
			url: BASE_URL + 'laporanlaris/get_laporan',
			data: $('#lap-saldo').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.kt-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}
</script>