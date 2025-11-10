<script type="text/javascript">
	$(function(){
		HELPER.fields = [
			
		];
		
		HELPER.api = {
			table 	: BASE_URL+'kartupinjaman/',
			read 	: BASE_URL+'kartupinjaman/read',
			store 	: BASE_URL+'kartupinjaman/store',
			update 	: BASE_URL+'kartupinjaman/update',
			destroy : BASE_URL+'kartupinjaman/destroy',
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
		$('#btn-print').hide();
	});


	function init_table(argument) {
		$('.kt-laporan').hide();

		if ( $.fn.DataTable.isDataTable('#table_grup') ) {
		  $('#table_grup').DataTable().destroy();
		}
		$('#table_pengajuan').DataTable().destroy();

		dt = $('#form-kartu').serializeObject();
		var table = $('#table_grup').DataTable({
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
				url: BASE_URL+'kartupinjaman/',
				type: 'POST',
				data:{
					filter:dt
				}
			},
			order : [[1,'desc'], [3,'desc']],
			columnDefs: [{
				targets: 0,
				orderable: false
			},
			{
				targets : 1,
				render: function (data,type,row) {
					return moment(data).format("DD-MM-YYYY");
				}
			},
			{
				targets : 4,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},
			/*{
				targets : 4,
				render: function (data,type,row) {
					if(data){
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				}
			},*/
			/*{
				targets : 5,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},*/
			{
				targets : 7,
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

	function init_table_pengajuan(argument) {
		$('.kt-laporan').hide();
		if ( $.fn.DataTable.isDataTable('#table_pengajuan') ) {
		  $('#table_pengajuan').DataTable().destroy();
		}
		$('#table_grup').DataTable().destroy();

		dt = $('#form-kartu').serializeObject();
		var table = $('#table_pengajuan').DataTable({
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
				url: BASE_URL+'pengajuanpinjaman/index_pinjaman',
				type: 'POST',
				data:{
					filter:dt
				}
			},
			order : [[1,'desc']],
			columnDefs: [{
				targets: 0,
				orderable: false
			},
			{
				targets : 1,
				render: function (data,type,row) {
					return moment(data).format("DD-MM-YYYY");
				}
			},
			{
				targets : 3,
				render: function (data,type,row) {
					return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
				}
			},
			{
				targets : 5,
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
			table 	: 'table_grup_gaji',
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
			table : 'table_grup_gaji'
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
				
				$('#kartu_pinjaman_referensi_id').removeAttr('disabled');
				HELPER.createCombo({
					el : 'kartu_pinjaman_referensi_id',
					url : BASE_URL+'pengajuanpinjaman/select_pinjaman',
					valueField 	: 'pengajuan_id',
					grouped : true,
					displayField  : 'pengajuan_no_pinjam',
					displayField2 : 'pengajuan_jumlah_pinjaman',
					displayField3 : 'pengajuan_status_keterangan',
					data : {pengajuan_anggota : $('#anggota_id').val(),
					pengajuan_jenis : $('#kartu_pinjaman_jenis').val()
					},
					callback:function(resp) {
						$('#kartu_pinjaman_referensi_id').select2();
						$('#kartu_pinjaman_referensi_id').attr('onchange','checkPrint()');
					}
				})
			}
		})
	}

	function filter(arg){
		dt = $('#form-kartu').serializeObject();
		if(dt.kartu_pinjaman_referensi_id.length>0){
			$('#table_pengajuan').css('display','none');
			$('#table_grup').removeAttr('style');
			init_table();
		}else{
			$('#table_grup').css('display','none');
			$('#table_pengajuan').removeAttr('style');
			init_table_pengajuan()
		}
	}
	
	function onPrint(){
		HELPER.block();
		data = $('#form-kartu').serializeObject();
		$.ajax({
			url 	: BASE_URL+'kartupinjaman/generate_laporan',
			data 	: data,
			type 	: 'post',
			dataType: 'json',
			success : function(res) {
				$('#table_grup').DataTable().destroy();
				$('#table_pengajuan').DataTable().destroy();
				$('#table_pengajuan').hide();
				$('#table_grup').hide();
				$('.kt-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();				
			}
		})
	}
	function checkPrint(){
		if($('#kartu_pinjaman_referensi_id').val().length > 0){
			$('#btn-print').show();
		}else{
			$('#btn-print').hide();
		}
	}
</script>