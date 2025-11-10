
<script type="text/javascript">
	$(function(){
		row = 1;
		satuan = barang = detail = [];
		HELPER.fields = [
			'retur_penjualan_id',
			'retur_penjualan_kode',
			'retur_penjualan_tanggal',
			'retur_penjualan_penjualan_id',
			'retur_penjualan_nilai',
			'retur_penjualan_total_qty',
			'retur_penjualan_total_item',
			'retur_penjualan_total',
			'customer_nama',
			'penjualan_kode',
		];
		HELPER.setRequired([
			'retur_penjualan_penjualan_id',
			'retur_penjualan_tanggal',
		]);
		HELPER.api = {
			table 	: BASE_URL+'returpenjualan/',
			read 	: BASE_URL+'returpenjualan/read',
			store 	: BASE_URL+'returpenjualan/store',
			update 	: BASE_URL+'returpenjualan/update',
			destroy : BASE_URL+'returpenjualan/destroy',
		}
		$('input.number').number(true);

		HELPER.ajaxCombo({
			el : '#retur_penjualan_penjualan_id',
			url: BASE_URL+'returpenjualan/select_penjualan',
		});

		$.post(BASE_URL+'satuan/select', function(res) {
			satuan = res.data;
		})

		init_table();	
	});

	function getPenjualan() {
		id = $('#retur_penjualan_penjualan_id').val();
		$.post(BASE_URL+'transaksipenjualan/read', {penjualan_id:id}, function(res) {
			$('#retur_penjualan_customer_id').val(res.penjualan_customer_id);
			$('#customer_nama').val(res.customer_nama);
			$('#penjualan_tanggal').val(res.penjualan_tanggal);
			$('#penjualan_total_grand').val(res.penjualan_total_grand);
			if(res.penjualan_total_cicilan_qty >0) $('#status').text('Kredit');
			else $('#status').text('Lunas');
			$('#anggota_nip').text(res.anggota_nip);
		})
	}	

	function listBarang() {
		$('#daftar_barang').modal();
		HELPER.block();
		if ( $.fn.DataTable.isDataTable('#list_barang') ) {
		  $('#list_barang').DataTable().destroy();
		}
		HELPER.initTable({
			el 		: "list_barang",
			url 	: BASE_URL+'transaksipenjualan/table_detail_barang',
			data 	:{jual:{penjualan_detail_parent:$('#retur_penjualan_penjualan_id').val()}},
			searchAble 	: true,
			destroyAble : true,
			responsive 	: false,
			columnDefs 	: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['barang_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['barang_nama'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['barang_satuan'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['penjualan_detail_harga'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['penjualan_detail_qty'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['penjualan_detail_jumlah'];
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						add = true;
						cnf = {data : full}
						// dt = HELPER.getRowData(cnf);
						$.each(detail, function(i, v) {
							if(full.penjualan_detail_id == v) add = false;
						})
						if(add){
	                        aksi = `<button type="button" class="btn btn-outline-brand btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
	                    }else{
	                    	aksi = `<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`;
	                    }
	                    return aksi;
					},
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

		HELPER.unblock();
	}

	function listBarang2() {
		$('#daftar_barang').modal();
		HELPER.block();
		if ( $.fn.DataTable.isDataTable('#list_barang') ) {
		  $('#list_barang').DataTable().destroy();
		}
		var table = $('#list_barang').DataTable({
			responsive: true,
			select : 'single',	
			processing: true,
			serverSide: true,
			ajax: {
				url: BASE_URL+'transaksipenjualan/table_detail_barang',
				type: 'POST',
				data:{jual:{penjualan_detail_parent:$('#retur_penjualan_penjualan_id').val()}},
			},
			order : [[1,'asc']],
			columnDefs: [
				{
					targets : 0,
					orderable : false
				},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {
						add = true;
						cnf = {data : row}
						dt = HELPER.getRowData(cnf);
						$.each(detail, function(i, v) {
							if(dt.penjualan_detail_id == v) add = false;
						})
						if(add){
	                        aksi = `<button type="button" class="btn btn-outline-brand btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
	                    }else{
	                    	aksi = `<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`;
	                    }
	                    return aksi;
					},
				}
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
	        "initComplete":function( settings, json){
	            HELPER.unblock();
	            // call your function here
	        }
		});
	}

	function addThis(el){
		HELPER.getDataFromTable({
			table 	: 'list_barang',
			inline 	: el,
			callback: function(res) {
				$.ajax({
                    url     : BASE_URL+'transaksipenjualan/read_detail',
                    data    : {penjualan_detail_id: res.penjualan_detail_id},
                    type    : 'post',
                    success : function(dt) {
                        $(el).parent().html(`<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`);

                        detail.push(dt.penjualan_detail_id);
                        retur_barang = {
                        	retur_penjualan_detail_id		 : null,
							retur_penjualan_detail_detail_id : dt.penjualan_detail_id,
							retur_penjualan_detail_barang_id : dt.penjualan_detail_barang_id,
							retur_penjualan_detail_satuan_id : dt.penjualan_detail_satuan,
							barang_kode 					 : dt.barang_kode,
							barang_satuan 					 : dt.barang_satuan,
							barang_satuan_opt 				 : dt.barang_satuan_opt,
							barang_nama 					 : dt.barang_nama,
							satuan_kode 	 				 : dt.satuan_kode,
							barang_satuan_konversi 	 		 : dt.barang_satuan_konversi,
							retur_penjualan_detail_harga 	 : dt.penjualan_detail_harga,
							retur_penjualan_detail_qty 		 : dt.penjualan_detail_qty,
							penjualan_detail_qty_barang	 	 : dt.penjualan_detail_qty_barang,
							retur_penjualan_detail_retur_qty : 0,
							retur_penjualan_detail_retur_qty_barang : 0,
							retur_penjualan_detail_sisa_qty  : 0,
							retur_penjualan_detail_jumlah 	 : 0,
                        }

                        addBarang(retur_barang);
						setSatuan(dt.penjualan_detail_barang_id, dt.penjualan_detail_satuan, dt.penjualan_detail_id, retur_barang);
						// setSatuan(barang_id, satuan_id, row, retur) 
						// setSatuan(dt.penjualan_detail_id, dt.penjualan_detail_satuan);
						$('#retur_penjualan_detail_satuan_id_'+dt.penjualan_detail_id).val(dt.penjualan_detail_satuan).trigger('change');
                        $('tr.no-list').remove();
                        $('#table-detail_barang tfoot tr').removeAttr('style');
                    }
                })				
			}
		})		
	}

	function delThis(el) {
		HELPER.getDataFromTable({
			table 	: 'list_barang',
			inline 	: el,
			callback: function(res) {
				remRow(res.penjualan_detail_id);	
				$(el).parent().html(`<button type="button" class="btn btn-outline-brand btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`);	
			}
		})	
	}

	function setBarang() {
		HELPER.ajaxCombo({
			el : '#retur_penjualan_detail_barang_id_'+row,
			url: BASE_URL+'barang/select_ajax',
		});
		$('input.number').number(true);
	}
	// setSatuan(dt.penjualan_detail_barang_id, dt.penjualan_detail_satuan, dt.penjualan_detail_id, retur_barang);

	function setSatuan(barang_id, satuan_id, row, retur) {  
	    $.post(BASE_URL+'barang/list_satuan',{barang_id : barang_id}, function(res) {
	      	html = '';
	      	$.each(res.data, function (i,v) {
	      		if(v.barang_satuan_order == 1) html += `<option value="`+v.barang_satuan_id+`" data-barang_satuan_harga_beli="`+v.barang_satuan_harga_beli+`" data-barang_satuan_konversi="`+v.barang_satuan_konversi+`">`+v.barang_satuan_kode+`</option>`
	      		else{
	      			if(satuan_id == v.barang_satuan_id) html += `<option value="`+v.barang_satuan_id+`" data-barang_satuan_harga_beli="`+v.barang_satuan_harga_beli+`" data-barang_satuan_konversi="`+v.barang_satuan_konversi+`">`+v.barang_satuan_kode+`</option>`
	      		}
	      	})
	      	$('#retur_penjualan_detail_satuan_id_' + row).html(html);
	      	$('#retur_penjualan_detail_satuan_id_' + row).select2();
			if(satuan_id) {
				$('#retur_penjualan_detail_satuan_id_' +row).val(satuan_id).trigger('change');
				getHarga(row, retur);
			}
	    })
  	}

  	function getHarga(row, retur) {  		
	    satuan = $('#retur_penjualan_detail_satuan_id_' + row).val();
  		jual = $('#retur_penjualan_detail_barang_id_'+row).data();
	    $('#retur_penjualan_detail_satuan_kode_' + row).val($('#retur_penjualan_detail_satuan_id_' + row +' option:selected').text());
		$('#retur_penjualan_detail_qty_' + row).val((jual.satuan == satuan ? jual.qty : jual.qty_barang));
	    $('#retur_penjualan_detail_harga_' + row).val((jual.satuan == satuan ? jual.harga : jual.harga_barang));
	    if(retur){
	    	console.log(retur)
	    	$('#retur_penjualan_detail_retur_qty_barang_' + row).val(retur.retur_penjualan_detail_retur_qty_barang)	
	    	$('#retur_penjualan_detail_retur_qty_' + row).val(retur.retur_penjualan_detail_retur_qty)	
	    }
	    else $('#retur_penjualan_detail_sisa_qty_' + row+',' +'#retur_penjualan_detail_retur_qty_' + row).val(0)
	    countRow(row)
  	}

	function getSupplier(argument) {
		$.post(BASE_URL+'supplier/read',{supplier_id:$('#retur_penjualan_supplier_id').val()}, function(res) {
			$('#supplier_alamat').val(res.supplier_alamat);
			$('#supplier_telp').val(res.supplier_telp);
		})
	}

	function init_table(argument) {
		
		HELPER.initTable({
			el: "table-returpenjualan",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['retur_penjualan_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['retur_penjualan_tanggal'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['penjualan_kode'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['customer_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'retur_penjualan_total_item';
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['retur_penjualan_total_qty'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['retur_penjualan_total'];
					},
				},
				{
					targets: 8,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						let btn_aksi = "";
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" onclick="onEdit('` + full['barang_id'] + `')">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-pen"></i>
						// 					</span>
						// 				</a>`;
						// btn_aksi += `<a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['barang_id'] + `')"">
						// 					<span class="svg-icon svg-icon-md">
						// 						<i class="fa fa-trash"></i>
						// 					</span>
						// 				</a>`;
						// return btn_aksi;
						return `
                        <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit(this)" >
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-pen"></i>
							</span>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('` + full['barang_id'] + `')"">
							<span class="svg-icon svg-icon-md">
								<i class="fa fa-trash"></i>
							</span>
						</a>
						`;
					},
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

	function init_table2(argument) {
		var table = $('#table-returpenjualan').DataTable({
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
				url: BASE_URL+'returpenjualan/',
				type: 'POST'
			},
			order : [[1,'desc']],
			columnDefs: [
				{
					targets : 0,
					orderable : false
				},
				{
					targets : 2,
					render: function (data,type,row) {
						return moment(data).format("DD-MM-YYYY");
					}
				},
				{
					targets : 8,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {
                        return `
                        <button type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </button> | 
                        <button type="button" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </button>`;
					},
				}
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
		HELPER.toggleForm({});
	}

	function countRow2(nrow) {
		retur = parseInt($('#retur_penjualan_detail_qty_'+nrow).val()) || 0;
		beli = parseInt($('#penjualan_detail_qty_'+nrow).val()) || 0;
		if(retur <= beli){			
			sub_total = qty = 0;
			jumlah = (parseInt($('#retur_penjualan_detail_harga_'+nrow).val())*retur) || 0;
			$('#retur_penjualan_detail_jumlah_'+nrow).val(jumlah)
			$('.qty').each(function(i, v) {
				qty += parseInt($(v).val());
			})
			$('#retur_penjualan_detail_qty_sisa_'+nrow).val((beli-retur));
			$('#retur_penjualan_jumlah_qty').val(qty);
		}else{
			$('#retur_penjualan_detail_jumlah_'+nrow).val(0)
		}
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val());
		})
		$('#retur_penjualan_total').val(sub_total);
		$('#retur_penjualan_jumlah_item').val($('#table-detail_barang tbody tr').length);
	}
		
	function countRow(nrow) {
		retur = parseInt($('#retur_penjualan_detail_retur_qty_'+nrow).val()) || 0;
		beli = parseInt($('#retur_penjualan_detail_qty_'+nrow).val()) || 0;
		if(retur <= beli){			
			sub_total = qty = 0;
			jumlah = (parseInt($('#retur_penjualan_detail_harga_'+nrow).val())*retur) || 0;
			$('#retur_penjualan_detail_jumlah_'+nrow).val(jumlah)

  			konv = $('#retur_penjualan_detail_satuan_id_'+nrow+' option:selected').data();
  			if(konv){
  				qty_barang = retur*parseInt(konv.barang_satuan_konversi);
				$('#retur_penjualan_detail_retur_qty_barang_'+nrow).val(qty_barang)
  			}
			$('.qty').each(function(i, v) {
				qty += parseInt($(v).val()) || 0;
			})
			$('#retur_penjualan_detail_sisa_qty_'+nrow).val((beli-retur));
			$('#retur_penjualan_jumlah_qty').val(qty);
		}else{
			$('#retur_penjualan_detail_retur_qty_barang_'+nrow).val(0)
			$('#retur_penjualan_detail_sisa_qty_'+nrow).val(0)
			$('#retur_penjualan_detail_jumlah_'+nrow).val(0)
		}
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val());
		})
		$('#retur_penjualan_total').val(sub_total);
		$('#retur_penjualan_jumlah_item').val($('#table-detail_barang tbody tr').length);
	}
	function addBarang(dt) {
		id = dt.retur_penjualan_detail_detail_id;
        $('#table-detail_barang tbody tr.no-list').remove()

		html = `<tr class="barang barang_`+id+`" data-id="`+id+`">
					<td scope="row">
						<input type="hidden" value="`+dt.retur_penjualan_detail_id+`" class="form-control" name="retur_penjualan_detail_id[`+id+`]" id="retur_penjualan_detail_id_`+id+`">						
						<input type="hidden" value="`+dt.retur_penjualan_detail_detail_id+`" class="form-control" name="retur_penjualan_detail_detail_id[`+id+`]" id="retur_penjualan_detail_detail_id_`+id+`">	
						<input type="hidden" value="`+dt.retur_penjualan_detail_barang_id+`" data-satuan_jual="`+dt.retur_penjualan_detail_satuan_id+`" data-konversi="`+dt.barang_satuan_konversi+`" data-qty="`+dt.retur_penjualan_detail_qty+`" data-qty_barang="`+dt.penjualan_detail_qty_barang+`" data-harga="`+dt.penjualan_detail_harga+`" data-harga_barang="`+dt.retur_penjualan_detail_harga+`"  class="form-control" name="retur_penjualan_detail_barang_id[`+id+`]" id="retur_penjualan_detail_barang_id_`+id+`">	
						<input class="form-control" type="text" value="`+dt.barang_kode+` - `+dt.barang_nama+`" name="barang_nama[]" id="barang_nama_`+id+`" disabled="">					
					</td>
					<td><select class="form-control" name="retur_penjualan_detail_satuan_id[`+id+`]" id="retur_penjualan_detail_satuan_id_`+id+`" onchange="chSatuan('`+id+`')"></select></td>
					<td><input class="form-control number" value="`+dt.retur_penjualan_detail_harga+`" type="text" readonly="" name="retur_penjualan_detail_harga[`+id+`]" id="retur_penjualan_detail_harga_`+id+`" onkeyup="countRow('`+id+`')"></td>
					<td>
						<input class="form-control number" type="text" readonly="" name="retur_penjualan_detail_qty[`+id+`]" id="retur_penjualan_detail_qty_`+id+`" value="`+dt.retur_penjualan_detail_qty+`" onkeyup="countRow('`+id+`')">
					</td>
					<td>
						<input class="form-control number qty" type="text" name="retur_penjualan_detail_retur_qty[`+id+`]" id="retur_penjualan_detail_retur_qty_`+id+`" value="`+dt.retur_penjualan_detail_retur_qty+`" onkeyup="countRow('`+id+`')">
						<input class="form-control number" type="hidden" name="retur_penjualan_detail_retur_qty_barang[`+id+`]" id="retur_penjualan_detail_retur_qty_barang_`+id+`" value="`+dt.retur_penjualan_detail_retur_qty_barang+`" onkeyup="countRow('`+id+`')">
					</td>
					<td>
						<input class="form-control number" readonly="" type="text" name="retur_penjualan_detail_sisa_qty[`+id+`]" id="retur_penjualan_detail_sisa_qty_`+id+`" value="`+dt.retur_penjualan_detail_sisa_qty+`">
					</td>
					<td><input class="form-control number jumlah" type="text" name="retur_penjualan_detail_jumlah[`+id+`]" id="retur_penjualan_detail_jumlah_`+id+`" value="`+dt.retur_penjualan_detail_jumlah+`" readonly=""></td>
					<td><a href="javascript:;" data-id="`+id+`" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow('`+id+`')" title="Hapus" >
                  		<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-detail_barang').append(html);
		$('input.number').number(true);
	}

	function remRow(id){
		$('tr.barang_'+id).remove();
		detail = [];
		$('.barang').each(function(i, v){
			detail.push($(v).data('id'))
		});

		if($('#table-detail_barang tbody tr').length == 0){
            $('#table-detail_barang tbody').append(`<tr class="no-list">
						<td colspan="8" class="text-center">Silahkan Pilih Data Penjualan Barang</td>
					</tr>`);
            $('#table-detail_barang tfoot tr').css('display', 'none');
        }
		countRow(id);
	}

	function chSatuan(el) {		
		$('#retur_penjualan_detail_barang_id_'+el).data();
	}

	function onEdit(el) {
		HELPER.loadData({
			table 	: 'table-barang',
            url 	: HELPER.api.read,
            server 	: true,
            inline	: $(el),
			callback : function (res) {
				getDetailBarang(res.retur_penjualan_id);
				$("#retur_penjualan_penjualan_id").select2("trigger", "select", {
						data: {
							id: res.retur_penjualan_penjualan_id,
							text: res.penjualan_kode + " - " + res.anggota_nama,
						}
					});
				onAdd();
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url  	: BASE_URL+'returpenjualan/get_detail',
			type 	: 'post',
			data 	: {retur_penjualan_detail_parent:parent},
			success : function(res) {
				if(res.total > 0) $('#table-detail_barang tfoot tr').show();
				$.each(res.data, function(i, v) {
					addBarang(v);
					setSatuan(v.retur_penjualan_detail_barang_id, v.retur_penjualan_detail_satuan_id, v.retur_penjualan_detail_detail_id, v);
				})
			}
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table : 'table-penjualanbarang'
		})
	}

	function save() {
		HELPER.save({			
			form 		: 'form-returpenjualan',
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
			table 	: 'table-penjualanbarang',
			inline  : el,
			confirm	: true,
			callback: function(success, id, record, message) {
				if(success == true){
					onRefresh()
				}
			}
		})
	}

	function onPrint(param) {
		HELPER.block();
		HELPER.getDataFromTable({
			table: 'table-returpenjualan',
			callback: function(data) {
				if (data) {
					$.extend(data, {tjson : true});
					$.ajax({
						url 	: BASE_URL+'returpenjualan/tprint/'+data.retur_penjualan_id,
						data 	: data,
						type 	: 'post',
						success : function(res) {
							HELPER.unblock();
							if (res.tprint) {	
								$('#printArea').html(res.tprint);
								var WinPrint = window.open('', '', 'width=900,height=650');
							    WinPrint.document.write($('#printArea').html());
							    WinPrint.document.close();
							    WinPrint.focus();
							    WinPrint.print();
							    WinPrint.close();
				            }
						}
					})
				}else{

				}
			}
		})

	}
</script>