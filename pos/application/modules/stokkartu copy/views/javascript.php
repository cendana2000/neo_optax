<script type="text/javascript">
$(function(){
	setPeriode()
	// init_table();
	HELPER.ajaxCombo({
		el: '#kartu_barang_id',
		url: BASE_URL + 'stokkartu/barang_ajax',
		wresult : 'bigdrop'
	});
	$('#kartu_transaksi').select2();
})

function setPeriode() {
	$('.tanggal, .bulan').hide();
	periode = $('#periode').val()
	$('.'+periode).show()
}

function init_table() {
	if ($.fn.DataTable.isDataTable('#table-kartustok')) {
	  $('#table-kartustok').DataTable().destroy();
	}
	dt = $('#filter-kartu_stok').serializeObject();
	var table = $('#table-kartustok').DataTable({
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
			url: BASE_URL + 'stokkartu/',
			type: 'POST',
			data : {filter : dt}
		},
		order: [
			[11, 'asc']
		],
		columnDefs: [{
				targets: 0,
				orderable: false
			},
		],		
        fnRowCallback: function(row, data, index, rowIndex){
        	// console.log(data);
        	if(data[9] == 'Retur Pembelian') $(row).css('background', 'yellow');
        },	
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
	$('.table-data').show();
}
</script>