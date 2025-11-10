<script type="text/javascript">
	$(function(){
		HELPER.fields = [
			'grup_gaji_id',
			'grup_gaji_kode',
			'grup_gaji_nama',
			'grup_gaji_keterangan',
		];
		HELPER.setRequired([
			'satuan_nama',
		]);
		HELPER.api = {
			table 	: BASE_URL+'kartusimpanan/',
			read 	: BASE_URL+'kartusimpanan/read',
			store 	: BASE_URL+'kartusimpanan/store',
			update 	: BASE_URL+'kartusimpanan/update',
			destroy : BASE_URL+'kartusimpanan/destroy',
		}
		init_table();	

		HELPER.createCombo({
			el : 'anggota_id',
			url : BASE_URL+'anggota/selectAll',
			valueField 	: 'anggota_id',
			grouped : true,
			displayField  : 'anggota_kode',
			displayField2  : 'anggota_nama',
			displayField3  : 'grup_gaji_nama',
			callback:function(resp) {
				$('#anggota_id').select2();
			}
		})
	});


	function init_table(argument) {
		$('.kt-laporan').hide();
		$('.tabel_simpanan').show();

		if ( $.fn.DataTable.isDataTable('#table_kartu') ) {
		  $('#table_kartu').DataTable().destroy();
		}

		dt = $('#form-kartu').serializeObject();
		
		var table = $('#table_kartu').DataTable({
			responsive: true,
			select : 'single',
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
				url: BASE_URL+'kartusimpanan/',
				type: 'POST',
				data:{
					filter:dt
				}
			},
			order : [[1,'desc']],
			columnDefs: [
			/*{
				targets: 0,
				orderable: false
			},*/
			{
				targets : 2,
				render: function (data,type,row) {
					return moment(data).format("DD-MM-YYYY");
				}
			},
			{
				targets : 1,
				render: function (data,type,row) {
					return data.substr(3,14)
				}
			},
			{
				targets : 3,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},
			/*{
				targets : 4,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},*/
			{
				targets : 5,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},
			{
				targets : 6,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},

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

	function onEdit(el) {
		HELPER.loadData({
			table 	: 'table_kartu',
            url 	: HELPER.api.read,
            server 	: true,
            inline	: $(el),
			callback : function (res) {
			}
		})
	}
	function onBack() {
		HELPER.back();
	}
	function onRefresh() {
		HELPER.refresh({
			table : 'table_kartu'
		})
	}

	function pilihPeriode(){
		periode = $('#periode').val();
		if(periode=="1"){
			$('.bulan_p, .tahun_p').hide();
		}else if(periode=="2"){
			$('.bulan_p').show();
			$('.tahun_p').hide();
		}else{
			$('.bulan_p').hide();
			$('.tahun_p').show();
		}
	}

	function save() {
		HELPER.save({			
			form 		: 'form-kartu',
			confirm		: true,
			callback : function(success,id,record,message)
			{
				if (success===true) {
					HELPER.back({});
				}
			}	
		})
	}

	function onDestroy(el){
        HELPER.destroy({
			table 	: 'table_kartu',
			inline  : el,
			confirm	: true,
			callback: function(success, id, record, message) {
				if(success == true){
					onRefresh()
				}
			}
		})
	}

	function pilihNasabah(argument) {
		$.ajax({
			url: BASE_URL+'anggota/select',
			type:'POST',
			data:{
				anggota_id : $('#anggota_id').val()
			},
			success: function (resp){
				$.each(resp.data[0], function(i, v){
					$('#'+i).val(v);
				})
			}
		})
	}

	function cetakKartu(){
		data = $('#form-kartu').serializeObject();
		HELPER.block();
		$.ajax({
			url: BASE_URL+'kartusimpanan/cetak_kartu',
			type:'POST',
			data: data,
			dataType: 'json',
			success: function (res){
				$('#table_kartu').DataTable().destroy();
				$('.tabel_simpanan').hide();
				$('.kt-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();		
			}
		})
	}
</script>