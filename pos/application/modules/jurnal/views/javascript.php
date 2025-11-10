<script type="text/javascript">
	$(function(){
		idx = 1;
		debit = kredit = 0;

		/*var quill = new Quill('#jurnal_umum_keterangan', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block']
                ]
            },
            // placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });*/
		CKEDITOR.replace( 'jurnal_umum_keterangan' );

		HELPER.fields = [
			'jurnal_umum_id',
			'jurnal_umum_tanggal',
			'jurnal_umum_nobukti',
			'jurnal_umum_keterangan',
			'jurnal_umum_total',
			'jurnal_umum_closed',
			// 'jurnal_umum_akun_jenis',
			// 'jurnal_umum_lawan_transaksi'
		];
		/*HELPER.setRequired([
			'agama_nama',
		]);*/
		HELPER.api = {
			table 	: BASE_URL+'jurnal/',
			read 	: BASE_URL+'jurnal/read',
			store 	: BASE_URL+'jurnal/store',
			update 	: BASE_URL+'jurnal/update',
			destroy : BASE_URL+'jurnal/destroy',
			lawan_transaksi : BASE_URL+'bank/get_lawan_transaksi',
			get_data_detail : BASE_URL+'jurnal/get_data_detail',
		}
		
		init_table();
		// $('#jurnal_umum_lawan_transaksi, #bank_akun_kredit, #bank_akun_debit').select2();
		$('.number').number(true);
		// , 'jurnal_umum_detail_lawan_transaksi'
		HELPER.create_combo({
			el : ['jurnal_umum_lawan_transaksi'],
    		valueField : 'id',
    		displayField : 'text',
    		parentField : 'parent',
    		childField : 'child',
    		url : HELPER.api.lawan_transaksi,
    		withNull : true,
    		nesting : true,
    		chosen : false,
    		callback : function(){}
		});
		HELPER.createCombo({
			el : 'jurnal_umum_unit',
			url : BASE_URL+'unit/select',
			valueField : 'unit_id',
			displayField : 'unit_nama',
			callback : function() {
				$('#jurnal_umum_unit').select2();
			}
		})
		row = 0;
		setAkun();
	});

	function changeUnit(akun_id){		
		HELPER.block();
		HELPER.createCombo({
			el : 'jurnal_umum_akun_id',
			url : BASE_URL+'akun/selectBank',
			data : {
				akun_unit : $('#jurnal_umum_unit').val(),
				akun_is_kas_bank : 1
			},
			valueField 	: 'akun_id',
			displayField  : 'akun_nama',
			callback:function(resp) {
				HELPER.unblock();
				$('#jurnal_umum_akun_id').select2();
				if(akun_id){
					$('#jurnal_umum_akun_id').val(akun_id).trigger('change');
				}
			}
		})
		for (var i = 1; i <= row; i++) {
			setAkun(i);
		}
	}

	function setPenerima() {
		penerima = $('#jurnal_umum_lawan_transaksi option:selected').text();
		$('#jurnal_umum_penerima').val(penerima);
	}
	function init_table(argument) {
		var table = $('#table-jurnal').DataTable({
			responsive: true,
			select : 'single',			
			buttons: [
				'print',
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
			],
			scrollY: '50vh',
			scrollX: true,
			scrollCollapse: true,
			processing: true,
			serverSide: true,
			ajax: {
				url: BASE_URL+'jurnal/',
				type: 'POST'
			},
			order : [[1, 'desc'],[2, 'desc']],
			columnDefs: [
				/*{
					targets : 0,
					orderable : false
				},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {
                        return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>`;
					},
				}*/
				{
					targets : 1,
					render: function (data,type,row) {
						return moment(data).format("DD-MM-YYYY");
					}
				},
				{
					targets :4,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
			],
            fnDrawCallback: function(oSettings)                
            {     
                var cnt = 0;
                $("tr", this).css('cursor', 'pointer');
				$("tbody tr", this).each(function(i, v){
                    $(v).on('click', function() {
                        if ( $(this).hasClass('selected') ) {
                            --cnt;
                            $(v).removeClass('selected');    
                            $(v).removeAttr('checked'); 
                            $('input[name=checkbox]',v).prop('checked',false);
                            $(v).removeClass('row_selected');
                        }else{
                            ++cnt;
                            $('input[name=checkbox]',v).prop('checked',true);
                            $('input[name=checkbox]',v).data('checked','aja');
                            $(v).addClass('selected');
                            $(v).addClass('row_selected asli');
                        }  

                        if (cnt>0) {
                            $('.disable').attr('disabled',false);
                        }else{
                            $('.disable').attr('disabled',true);
                        }
                    });
                });
			},
		});
	}

	function onAdd() {
		HELPER.toggleForm({
			tohide : 'table_data',
			toshow : 'form_data',
		});
	}

	function addDetail() {
		row++;
					// <td><select class="form-control" id="jurnal_umum_detail_lawan_transaksi_` + row + `" name="jurnal_umum_detail_lawan_transaksi[` + row + `]"></select></td>

		html = `<tr class="detail_` + row + `">
					<td><select class="form-control" id="jurnal_umum_detail_akun_` + row + `" name="jurnal_umum_detail_akun[` + row + `]" style="width: 100%"></select></td>
					<td><input type="text" class="form-control" id="jurnal_umum_detail_uraian_` + row + `" name="jurnal_umum_detail_uraian[` + row + `]"></td>
					<td><input type="text" class="form-control number detail_debit" id="jurnal_umum_detail_debit_` + row + `" name="jurnal_umum_detail_debit[` + row + `]" data-id="`+row+`" onkeyup="getTotal()"></td>
					<td><input type="text" class="form-control number detail_kredit" id="jurnal_umum_detail_kredit_` + row + `" name="jurnal_umum_detail_kredit[` + row + `]" data-id="`+row+`" onkeyup="getTotal()"></td>
					<td><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus"><span class="la la-trash"></span></a></td>
				</tr>`;
		$('#table-details').append(html);
		setAkun();
		$('.number').number(true);
	}
	
	function getTotal(el) {
		debit =  kredit = total_debit = total_kredit = 0;		
		done = true;
		$('.detail_debit').each(function(i,v) {
			id = $(v).data('id');
			console.log(id);
			debit = parseInt($('#jurnal_umum_detail_debit_'+id).val()) || 0;
			total_debit += debit;
			kredit = parseInt($('#jurnal_umum_detail_kredit_'+id).val()) || 0;
			total_kredit += kredit;
			$('#jurnal_umum_detail_debit_'+id+', #jurnal_umum_detail_kredit_'+id).prop('readonly',false);
			if(debit || kredit){
				if(debit) $('#jurnal_umum_detail_kredit_'+id).prop('readonly',true);
				else $('#jurnal_umum_detail_debit_'+id).prop('readonly',true);
			}else{
				done = false;
			}
			if(!$('#jurnal_umum_detail_akun_'+id).val()) done = false;
		})
		console.log(total_debit+" - "+total_kredit);
		$('#total_jurnal_debit').html($.number(total_debit));
		$('#total_jurnal_kredit').html($.number(total_kredit));
		if(total_debit == total_kredit) $('#jurnal_umum_total').val(total_debit);
		$('#jurnal_umum_total_debit').val(total_debit)
		$('#jurnal_umum_total_kredit').val(total_kredit)
	    if(done) addDetail()
	}

	function remRow(el) {
		id = $(el).data('id');
		// console.log(id);
		$('tr.detail_' + id).remove();
		getTotal();
	}

	function setAkun(cRow) {
		if(cRow) row = cRow;
		HELPER.ajaxCombo({
			el: '#jurnal_umum_detail_akun_' + row,
			url: BASE_URL + 'akun/select_ajax',
		});
		$('input.number').number(true);
	}

	function onEdit()
	{
		HELPER.loadData({
			table 	: 'table-jurnal',
			server 	: true,
			url 	: BASE_URL+'jurnal/read',
			callback : function(data) {				
				// $('#jurnal_umum_lawan_transaksi').val(data.jurnal_umum_lawan_transaksi).trigger('change');
				$('#jurnal_umum_nobukti').prop('disabled', true);
				CKEDITOR.instances['jurnal_umum_keterangan'].setData(data.jurnal_umum_keterangan)				
				$.each(data.detail.data, function(i, v) {
					$("#jurnal_umum_detail_kredit_" + i).val(v.jurnal_umum_detail_kredit)
					$("#jurnal_umum_detail_debit_" + i).val(v.jurnal_umum_detail_debit)
					$("#jurnal_umum_detail_uraian_" + i).val(v.jurnal_umum_detail_uraian)
					$("#jurnal_umum_detail_akun_" + i).select2("trigger", "select", {
						data: {
							id: v.jurnal_umum_detail_akun,
							text: v.jurnal_umum_detail_akun_kode + " - " + v.jurnal_umum_detail_akun_nama,
						}
					});
					getTotal();
				})
				$('[for=jurnal_umum_keterangan]').addClass('active');
				onAdd();
			}
		})
	}

	function onHapusDetail(el) {
		id = $(el).data('id');
		$('tr#'+id).remove();
		countKreditDebit();
		if($('#table-details tbody tr').length == 0) $('#table-details tbody').append('<tr id="blank"><td colspan="6" style="text-align: center;">No data available in table</td></tr>');
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table : 'table-jurnal'
		})
	}

	function save(el) {
		if ($("#jurnal_umum_closed").val()==1) {
			swal.fire('Gagal', 'Transaksi tidak bisa disimpan, karena sudah masuk tutup buku', 'warning');
		}else{
			var tanggal = $("#jurnal_umum_tanggal").val();
			// if(check_balance()){
			HELPER.save({
				form : 'form-jurnal',
				confirm: true,
				callback : function(success,id,record,message) {
					var cetak = $('#cetak');				
					if (success == true) {
						if (cetak.is(":checked")) onPrint(id)
						else onBack();
					}
				}
			});
			// }
		}	
	}

	function check_balance(){
		countKreditDebit();
		debit = parseInt($('#jurnal_umum_total_debit').val()) || 0;
		kredit = parseInt($('#jurnal_umum_total_kredit').val()) || 0;
		if(debit == kredit){
			$('#jurnal_umum_total').val(debit);
			return true;
		}else{
			swal.fire('Peringatan', 'Jumlah Kredit dan Debit tidak sama', 'warning');
			return false;
		}
	}

	function onDestroy(el){
        HELPER.destroy({
			table 	: 'table-jurnal',
			confirm	: true,
			callback: function(success, id, record, message) {
				if(success == true){
					onRefresh()
				}
			}
		})
	}

	function onBtnTambah_Click() {
		var debit = kredit = 0;
		if($('#jurnal_umum_detail_tipe').val() == 'kredit'){
			kredit = $('#jurnal_umum_detail_total').val();
		}else{
			debit = $('#jurnal_umum_detail_total').val();
		}
		var detailrincian = {
			jurnal_umum_detail_id 				: ($('#jurnal_umum_detail_id').val())?$('#jurnal_umum_detail_id').val():'',
			jurnal_umum_detail_uraian 			: ($('#jurnal_umum_detail_uraian').val())?$('#jurnal_umum_detail_uraian').val():'',
			jurnal_umum_detail_akun 			: ($('#jurnal_umum_detail_akun').val())?$('#jurnal_umum_detail_akun').val():'',
			jurnal_umum_detail_akun_text 		: ($('#jurnal_umum_detail_akun').val())?$('#jurnal_umum_detail_akun option:selected').text():'',
			jurnal_umum_detail_debit 			: debit,
			jurnal_umum_detail_kredit 			: kredit,
			jurnal_umum_detail_lawan_transaksi 	: ($('#jurnal_umum_detail_lawan_transaksi').val())?$('#jurnal_umum_detail_lawan_transaksi').val():'',
			lawan_transaksi_nama 				: ($('#jurnal_umum_detail_lawan_transaksi').val())?$('#jurnal_umum_detail_lawan_transaksi option:selected').text():'',
		};
		var listdetailrincian = btoa(JSON.stringify(detailrincian));
		html = '<tr id="'+idx+'" class="detail_rincian"><td style="text-align:left!important;" class="nama_rinci"><input type="hidden" name="data_rincian[]" value="'+listdetailrincian+'" />'+detailrincian.jurnal_umum_detail_uraian+'</td>'
			+'<td>'+detailrincian.jurnal_umum_detail_akun_text+'</td>'
			+'<td style="text-align:right!important;" class="jumlah_kredit" data-val="'+kredit+'">'+$.number(kredit)+'</td>'
			+'<td style="text-align:right!important;" class="jumlah_debit" data-val="'+debit+'">'+$.number(debit)+'</td>'
			+'<td>'+detailrincian.lawan_transaksi_nama+'</td>'
			+'<td style="text-align: center!important;"><a data-id="'+idx+'" href="javascript:void(0)" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="onHapusDetail(this)" title="Hapus"><span class="la la-trash"></span> Hapus</a></td>'
			+'</tr>';
		$("tr#blank").remove();	
		// console.log(html);
		$("#table-details").append(html);	
		idx++;

		$('#jurnal_umum_detail_uraian, #jurnal_umum_detail_akun, #jurnal_umum_detail_lawan_transaksi, #jurnal_umum_detail_total, #jurnal_umum_detail_tipe').val('').trigger('change');
		$('#jurnal_umum_detail_tipe').val('debit').trigger('change');
		$('#jurnal_umum_detail_tipe').focus();
		
		countKreditDebit()
	}

	function countKreditDebit() {
		debit = kredit = 0;
		$('.jumlah_debit').each(function(i,v){			
			debit += parseInt($(v).data('val'));
		})
		$('#total_debit').html($.number(debit));
		$('.jumlah_kredit').each(function(i,v){			
			kredit += parseInt($(v).data('val'));
		})
		$('#total_kredit').html($.number(kredit));
	}

	function onPrint(param) {
		HELPER.block();
		if (param) {
			$.ajax({
				url: BASE_URL + 'jurnal/cetak/' + param,
				type: 'get',
				success: function(res) {
					var data = JSON.parse(res);
					HELPER.toggleForm({
						tohide: 'form_data',
						toshow: 'cetak_data'
					})
					$("#pdf-laporan object").attr("data", data.record);
					HELPER.unblock();
				}
			})
		} else {
			HELPER.getDataFromTable({
				table: 'table-jurnal',
				callback: function(data) {
					// console.log(data);
					if (data) {
						$.ajax({
							url: BASE_URL + 'jurnal/cetak/' + data.jurnal_umum_id,
							type: 'get',
							success: function(res) {
								var data = JSON.parse(res);

								HELPER.toggleForm({
									tohide: 'table_data',
									toshow: 'cetak_data'
								})
								$("#pdf-laporan object").attr("data", data.record);
								HELPER.unblock();
							}
						})
					} else {
						HELPER.unblock();
					}
				}
			})
		}

	}
</script>