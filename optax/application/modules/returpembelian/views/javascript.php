
<script type="text/javascript">
	$(function(){
		row = 1;
		satuan = barang = detail = [];
		HELPER.fields = [
			'retur_pembelian_id',
			'pembelian_bayar_grand_total',
			'pembelian_jatuh_tempo',
			'retur_pembelian_kode',
			'retur_pembelian_tanggal',
			'retur_pembelian_pembelian_id',
			'retur_pembelian_supplier_id',
			'retur_pembelian_jumlah_qty',
			'retur_pembelian_jumlah_item',
			'retur_pembelian_total',
		];
		HELPER.setRequired([
			// 'retur_pembelian_pembelian_id',
			'retur_pembelian_tanggal',
		]);
		HELPER.api = {
			table 	: BASE_URL+'returpembelian/',
			read 	: BASE_URL+'returpembelian/read',
			store 	: BASE_URL+'returpembelian/store',
			update 	: BASE_URL+'returpembelian/update',
			destroy : BASE_URL+'returpembelian/destroy',
		}
		// $.post(BASE_URL+'returpembelian/table_detail_barang');
		$('input.number').number(true);

		HELPER.ajaxCombo({
			el: '#retur_pembelian_supplier_id',
			url: BASE_URL + 'supplier/select_ajax',
			displayField: 'text'
		});

		$.post(BASE_URL+'satuan/select', function(res) {
			satuan = res.data;
		})

		init_table();	
		getSupplier();
		setBarang();
	});

	function getPembelian() {	
		HELPER.block()
		id = $('#retur_pembelian_pembelian_id').val()
		$.post(BASE_URL+'transaksipembelian/read', {pembelian_id:id}, function(res) {
			if(res.pembelian_id){
				$('#retur_pembelian_supplier_id').val(res.pembelian_supplier_id);
				$('#supplier_telp').text(res.supplier_telp);
				$('#supplier_nama').val(res.supplier_nama);
				$('#pembelian_tanggal').val(res.pembelian_tanggal);
				$('#pembelian_bayar_grand_total').val(res.pembelian_bayar_grand_total);
				$('#pembelian_jatuh_tempo').text(res.pembelian_jatuh_tempo);

				if(res.pembelian_bayar_grand_total == res.pembelian_bayar_jumlah){
					HELPER.confirm({
						message 	: 'Faktur pembelian sudah lunas, apakah anda ingin melanjutkan ?',
						callback 	: (r) =>{
							if(r){
								$('#status_lunas').css('visibility','visible');
								$('#bayar-lunas').val('1');
								$('.alert-lunas').show('500')
							}
							else{
								$('#supplier_telp').text('No. Telp')
								$('#pembelian_jatuh_tempo').text('JT : dd/mm/yyyy')
								$('#retur_pembelian_pembelian_id').val('').trigger('change')
								$('#form-returpembelianbarang').trigger('reset');
								$('#status_lunas').css('visibility','hidden');
								$('#bayar-lunas').val('');
								$('.alert-lunas').hide('500')
							}
						}
					})
				}else{
					$('#status_lunas').css('visibility','hidden');
					$('.alert-lunas').hide('500')
				}
			}
			HELPER.unblock()
		})
	}	

	function listBarang() {
		$('#daftar_barang').modal();
		HELPER.block();
		if ( $.fn.DataTable.isDataTable('#list_barang') ) {
		  $('#list_barang').DataTable().destroy();
		}
		
		HELPER.initTable({
			el: "list_barang",
			url: BASE_URL+'transaksipembelian/table_detail_barang',
			data:{beli:{pembelian_detail_parent:$('#retur_pembelian_pembelian_id').val()}},
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
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
						return full['barang_satuan_kode'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['pembelian_detail_harga'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return full['pembelian_detail_qty'];
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['pembelian_detail_jumlah'];
					},
				},
				{
					targets: 7,
					width: '10px',
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {						
						add = true;
						$.each(detail, function(i, v) {
							if(data == v) add = false; 
						})
						if(add){
	                        aksi = `<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="addThis('${full.pembelian_detail_id}', this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
	                    }else{
	                    	aksi = `<button type="button" class="btn btn-light-danger btn-pill btn-sm" title="Edit" onclick="delThis('${full.pembelian_detail_id}', this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`;
	                    }
	                    return aksi;
					},
				},

			],
		});
		HELPER.unblock();
		/* var table = $('#list_barang').DataTable({
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
				url: BASE_URL+'transaksipembelian/table_detail_barang',
				type: 'POST',
				data:{beli:{pembelian_detail_parent:$('#retur_pembelian_pembelian_id').val()}},
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
						$.each(detail, function(i, v) {
							if(data == v) add = false; 
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
		}); */
	}

	function addThis(id, el) {
		$.ajax({
            url     : BASE_URL+'transaksipembelian/read_detail',
            data    : {pembelian_detail_id: id},
            type    : 'post',
            success : function(dt) {
                $(el).parent().html(`
                	<button type="button" class="btn btn-outline-danger btn-round btn-sm" title="Edit" onclick="delThis(this)" >
                      <i class="icon wb-close"></i> batal
                    </button>`);
                detail.push(dt.pembelian_detail_id);
                retur_barang = {
                	retur_pembelian_detail_id		 : null,
					retur_pembelian_detail_detail_id : dt.pembelian_detail_id,
					retur_pembelian_detail_barang_id : dt.pembelian_detail_barang_id,
					retur_pembelian_detail_satuan 	 : dt.pembelian_detail_satuan,
					retur_pembelian_detail_satuan_kode : dt.barang_satuan_kode,
					retur_pembelian_detail_harga 	 : dt.pembelian_detail_harga,
					retur_pembelian_detail_qty 		 : 0,
					retur_pembelian_detail_retur_qty : 0,
					retur_pembelian_detail_retur_qty_barang : 0,
					retur_pembelian_detail_sisa_qty  : 0,
					retur_pembelian_detail_jumlah 	 : 0,
					pembelian_detail_satuan 		 : dt.pembelian_detail_satuan,
					pembelian_detail_qty 		 	 : dt.pembelian_detail_qty,
					pembelian_detail_qty_barang 	 : dt.pembelian_detail_qty_barang,
					pembelian_detail_harga 		 	 : dt.pembelian_detail_harga,
					pembelian_detail_harga_barang 	 : dt.pembelian_detail_harga_barang,
					barang_kode 					 : dt.barang_kode,
					barang_nama 					 : dt.barang_nama,
					barang_stok 					 : dt.barang_stok,
					barang_satuan_konversi 	 		 : dt.barang_satuan_konversi,
                }
                // addBarang();				
				// setSatuan(dt.pembelian_detail_barang_id, retur_barang.retur_pembelian_detail_satuan, dt.pembelian_detail_id);
				setReturDetail(dt);
                $('tr.no-list').remove();
                $('#table-detail_barang tfoot tr').removeAttr('style');
            }
        })	
	}

	function addThis2(el) {
		HELPER.getDataFromTable({
			table 	: 'list_barang',
			inline 	: el,
			callback: function(res) {
				$.ajax({
                    url     : BASE_URL+'transaksipembelian/read_detail',
                    data    : {pembelian_detail_id: res.pembelian_detail_id},
                    type    : 'post',
                    success : function(dt) {
                        $(el).parent().html(`<button type="button" class="btn btn-outline-danger btn-pill btn-sm" title="Edit" onclick="delThis(this)" >
	                          <i class="la la-remove"></i> batal
	                        </button>`);

                        detail.push(dt.pembelian_detail_id);
                        retur_barang = {
		                	retur_pembelian_detail_id		 : null,
							retur_pembelian_detail_detail_id : dt.pembelian_detail_id,
							retur_pembelian_detail_barang_id : dt.pembelian_detail_barang_id,
							retur_pembelian_detail_satuan 	 : dt.pembelian_detail_satuan,
							retur_pembelian_detail_harga 	 : dt.pembelian_detail_harga,
							retur_pembelian_detail_qty 		 : 0,
							retur_pembelian_detail_retur_qty : 0,
							retur_pembelian_detail_retur_qty_barang : 0,
							retur_pembelian_detail_sisa_qty  : 0,
							retur_pembelian_detail_jumlah 	 : 0,
							pembelian_detail_satuan 		 : dt.pembelian_detail_satuan,
							pembelian_detail_qty 		 	 : dt.pembelian_detail_qty,
							pembelian_detail_qty_barang 	 : dt.pembelian_detail_qty_barang,
							pembelian_detail_harga 		 	 : dt.pembelian_detail_harga,
							pembelian_detail_harga_barang 	 : dt.pembelian_detail_harga_barang,
							barang_kode 					 : dt.barang_kode,
							barang_nama 					 : dt.barang_nama,
							barang_satuan_konversi 	 		 : dt.barang_satuan_konversi,
                        }
                        addBarang(retur_barang);
						setSatuan(dt.pembelian_detail_barang_id, retur_barang.retur_pembelian_detail_satuan, dt.pembelian_detail_id);
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
				remRow(res.pembelian_detail_id);	
				$(el).parent().html(`<button type="button" class="btn btn-light-success btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`);	
			}
		})	
	}

	function setBarang(trow, dt) {
		if(trow) {
			trow = trow.join(', ');
		}
		else trow = '#retur_pembelian_detail_barang_id_' + row;
		HELPER.ajaxCombo({
			el : trow,
			url: BASE_URL+'returpembelian/select_ajax',
			// url: BASE_URL + 'stokkartu/barang_ajax',
			wresult : 'bigdrop'
		});
		if(dt) setReturDetail(dt);
		$('input.number').number(true);
	}

	function setSatuan(row) {  
	    barang_id = $('#retur_pembelian_detail_barang_id_' + row).val();
	    stok = $('#retur_pembelian_detail_barang_id_' + row+' option:selected').data('temp');	 
	    
		$('#barang_stok_'+row).val(stok);
	    $.ajax({
	    	url 	: BASE_URL+'barang/list_satuan',
	    	data 	: {barang_id : barang_id}, 
	    	type 	: 'post',
	    	success : function(res) {
		      	html = '';
		      	satuan = res.data;
		      	$('#retur_pembelian_detail_satuan_' + row).val(satuan[0].barang_satuan_id);
		      	$('#retur_pembelian_detail_satuan_kode_' + row).val(satuan[0].barang_satuan_kode);
				$('#retur_pembelian_detail_harga_'+row).val(satuan[0].barang_satuan_harga_beli);
		    }
	    })
		// if(view == '1'){
		// }
  	}

	function setSatuan2(barang_id, satuan_id, row, retur) {  
	    $.post(BASE_URL+'barang/list_satuan',{barang_id : barang_id}, function(res) {
	      	html = '';
	      	$.each(res.data, function (i,v) {
	      		if(v.barang_satuan_order == 1) html += `<option value="`+v.barang_satuan_id+`" data-barang_satuan_harga_beli="`+v.barang_satuan_harga_beli+`" data-barang_satuan_konversi="`+v.barang_satuan_konversi+`">`+v.barang_satuan_kode+`</option>`
	      		else{
	      			if(satuan_id == v.barang_satuan_id) html += `<option value="`+v.barang_satuan_id+`" data-barang_satuan_harga_beli="`+v.barang_satuan_harga_beli+`" data-barang_satuan_konversi="`+v.barang_satuan_konversi+`">`+v.barang_satuan_kode+`</option>`
	      		}
	      	})
	      	$('#retur_pembelian_detail_satuan_' + row).html(html);
	      	$('#retur_pembelian_detail_satuan_' + row).select2();
			if(satuan_id) {
				$('#retur_pembelian_detail_satuan_' +row).val(satuan_id).trigger('change');
				getHarga(row, retur);
			}
	    })
  	}

  	function getHarga2(row) {
	    // dt_satuan = $('#retur_pembelian_detail_satuan_' + row +' option:selected').data();
	    satuan = $('#retur_pembelian_detail_satuan_' + row).val();
  		beli = $('#retur_pembelian_detail_barang_id_'+row).data()
  		
    	qty_barang = qty = parseInt(beli.qty) || 0;
    	harga_barang = harga = parseInt(beli.harga) || 0;
	    $('#retur_pembelian_detail_satuan_kode_' + row).val($('#retur_pembelian_detail_satuan_' + row +' option:selected').text());
	    if(beli.satuan_beli != satuan){
	    	qty_barang = parseInt(beli.konversi)*qty || 0;
	    	harga_barang = harga/qty_barang;
	    }

		$('#retur_pembelian_detail_qty_' + row).val(qty_barang);
	    $('#retur_pembelian_detail_harga_' + row).val(harga_barang);

	    // $('#retur_pembelian_detail_harga_' + row).val(harga.barang_satuan_harga_beli)

	    /*konversi = parseInt(harga.barang_satuan_konversi) || 0;   
	    qty = parseInt($('#pembelian_detail_qty_' + row).val()) || 0;
	    qty_barang = konversi*qty;
	    $('#pembelian_detail_qty_barang_' + row).val(qty_barang);    
	    countRow(row)*/
  	}

  	function getHarga(row, retur) {  		
	    satuan = $('#retur_pembelian_detail_satuan_' + row).val();
  		beli = $('#retur_pembelian_detail_barang_id_'+row).data();

	    $('#retur_pembelian_detail_satuan_kode_' + row).val($('#retur_pembelian_detail_satuan_' + row +' option:selected').text());
		$('#retur_pembelian_detail_qty_' + row).val((beli.satuan_beli == satuan ? beli.qty : beli.qty_barang));
	    $('#retur_pembelian_detail_harga_' + row).val((beli.satuan_beli == satuan ? beli.harga : beli.harga_barang));
	    if(retur){
	    	$('#retur_pembelian_detail_retur_qty_barang_' + row).val(retur.retur_pembelian_detail_retur_qty_barang)	
	    	$('#retur_pembelian_detail_retur_qty_' + row).val(retur.retur_pembelian_detail_retur_qty)	
	    }
	    else $('#retur_pembelian_detail_sisa_qty_' + row+',' +'#retur_pembelian_detail_retur_qty_' + row).val(0)
	    countRow(row)
  	}

	function getSupplier(argument) {		
		HELPER.ajaxCombo({
			el : '#retur_pembelian_pembelian_id',
			data: {pembelian_supplier_id : $('#retur_pembelian_supplier_id').val()},		
			url: BASE_URL+'returpembelian/select_pembelian',
			displayField: 'kode'
		});
		// $.post(BASE_URL+'supplier/read',{supplier_id:$('#retur_pembelian_supplier_id').val()}, function(res) {
		// 	$('#supplier_alamat').val(res.supplier_alamat);
		// 	$('#supplier_telp').val(res.supplier_telp);
		// })
	}

	function init_table(argument) {	
		awal=$("[name='awal_tanggal']").val()
		akhir=$("[name='akhir_tanggal']").val()

		if ( $.fn.DataTable.isDataTable('#table-returpembelianbarang') ) {
		  $('#table-returpembelianbarang').DataTable().destroy();
		}
		HELPER.initTable({
			el: "table-returpembelianbarang",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_kode'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_tanggal'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['pembelian_kode'];
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['supplier_nama'];
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'retur_pembelian_jumlah_item';
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_jumlah_qty'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['retur_pembelian_total'];
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
		var detailRows = [];
 
	    $('#table-returpembelianbarang tbody').on( 'click', 'tr td.details-control', function () {
	        var tr = $(this).closest('tr');
	        var row = table.row( tr );
	        var idx = $.inArray( tr.attr('id'), detailRows );
	 
	        if ( row.child.isShown() ) {
	            tr.removeClass( 'details' );
	            row.child.hide();
	            tr.addClass('tutup');
				tr.removeClass('shown');
	            // Remove from the 'open' array
	            detailRows.splice( idx, 1 );
	        }
	        else {
	            tr.addClass( 'details' );
	            row.child( format( row.data() ) ).show();
	 			tr.addClass('shown');
				tr.removeClass('tutup'); 
	            // Add to the 'open' array
	            if ( idx === -1 ) {
	                detailRows.push( tr.attr('id') );
	            }
	        }
	    } );
		/* table.on( 'draw', function () {
	        $.each( detailRows, function ( i, id ) {
	            $('#'+id+' td.details-control').trigger( 'click' );
	        });
	    }); */
	    $('#modal').modal('hide');
		var detailRows = [];
	}

	function init_table2(argument) {
		awal=$("[name='awal_tanggal']").val()
		akhir=$("[name='akhir_tanggal']").val()

		if ( $.fn.DataTable.isDataTable('#table-returpembelianbarang') ) {
		  $('#table-returpembelianbarang').DataTable().destroy();
		}

		var table = $('#table-returpembelianbarang').DataTable({
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
				url: BASE_URL+'returpembelian/',
				type: 'POST',
				data:{
					tanggal1:awal,
					tanggal2:akhir,
				}
			},
			order : [[3,'desc'],[2,'desc']],
			columnDefs: [
				/*{
					targets : 0,
					orderable : false
				},*/
				{
					targets : 3,
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
				{  className: "details-control", targets: 1, "data": null, "defaultContent": ""},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {
                        return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>`;
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
		var detailRows = [];
 
	    $('#table-returpembelianbarang tbody').on( 'click', 'tr td.details-control', function () {
	        var tr = $(this).closest('tr');
	        var row = table.row( tr );
	        var idx = $.inArray( tr.attr('id'), detailRows );
	 
	        if ( row.child.isShown() ) {
	            tr.removeClass( 'details' );
	            row.child.hide();
	            tr.addClass('tutup');
				tr.removeClass('shown');
	            // Remove from the 'open' array
	            detailRows.splice( idx, 1 );
	        }
	        else {
	            tr.addClass( 'details' );
	            row.child( format( row.data() ) ).show();
	 			tr.addClass('shown');
				tr.removeClass('tutup'); 
	            // Add to the 'open' array
	            if ( idx === -1 ) {
	                detailRows.push( tr.attr('id') );
	            }
	        }
	    } );
		table.on( 'draw', function () {
	        $.each( detailRows, function ( i, id ) {
	            $('#'+id+' td.details-control').trigger( 'click' );
	        });
	    });
	    $('#modal').modal('hide');
		var detailRows = [];
	}
	function onAdd() {
		HELPER.toggleForm({});
	}
	function countRow(nrow) {
		retur = parseInt($('#retur_pembelian_detail_retur_qty_'+nrow).val()) || 0;
		beli = parseInt($('#barang_stok_'+nrow).val()) || 0;
		if(retur <= beli){			
			sub_total = qty = 0;
			jumlah = (parseInt($('#retur_pembelian_detail_harga_'+nrow).val())*retur) || 0;
			$('#retur_pembelian_detail_jumlah_'+nrow).val(jumlah)

  			/*konv = $('#retur_pembelian_detail_satuan_'+nrow+' option:selected').data();
  			if(konv){
  				qty_barang = retur*parseInt(konv.barang_satuan_konversi);
				$('#retur_pembelian_detail_retur_qty_barang_'+nrow).val(qty_barang)
  			}*/
			$('#retur_pembelian_detail_retur_qty_barang_'+nrow).val(retur)
	    	done = true;
	    	item = 0;
			$('.qty').each(function(i, v) {
				qty += parseInt($(v).val()) || 0;
	      		t = parseInt($(v).val());
		      	if(!t) done = false;
		      	else item++;
			})
	    	if(done) addBarang()
			$('#barang_stok_sisa_'+nrow).val((beli-retur));
			$('#retur_pembelian_jumlah_qty').val(qty);
		}else{
			$('#retur_pembelian_detail_retur_qty_barang_'+nrow).val(0)
			$('#retur_pembelian_detail_sisa_qty_'+nrow).val(0)
			$('#retur_pembelian_detail_jumlah_'+nrow).val(0)
		}
		$('#retur_pembelian_jumlah_item').val(item);
		countJumlah();
	}
	function countJumlah() {	
		sub_total=0;
		$('.jumlah').each(function(i, v) {
			sub_total += parseInt($(v).val()) || 0;
		})
		$('#retur_pembelian_total').val(sub_total);
	}
	function setReturDetail(dt) {
		id = row;
		console.log($(`#retur_pembelian_detail_barang_id_`+id).val());
		console.log(dt);
		console.log(id);
		if($(`#retur_pembelian_detail_barang_id_`+id).val()){
			addBarang(dt)
			id = row;
		}else{
			$('#retur_pembelian_detail_barang_id_'+id).select2("trigger", "select", {
					data: {
						id: dt.pembelian_detail_barang_id,
						text: dt.barang_kode+' - '+dt.barang_nama
					}
				});
			$(`#retur_pembelian_detail_detail_id_`+id).val(dt.pembelian_detail_id)
			$(`#retur_pembelian_detail_satuan_`+id).val(dt.pembelian_detail_satuan)
			$(`#retur_pembelian_detail_satuan_kode_`+id).val(dt.barang_satuan_kode)
			$(`#retur_pembelian_detail_harga_`+id).val(dt.pembelian_detail_harga_barang)
			$(`#barang_stok_`+id).val(dt.barang_stok)
			addBarang()
		}
	}
	
	function addBarang(dt) {
		row++;
		html = `<tr class="barang barang_`+row+`" data-id="`+row+`">
					<td scope="row">
						<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_id[`+row+`]" id="retur_pembelian_detail_id_`+row+`">	
						<input type="hidden" value="" class="form-control" name="retur_pembelian_detail_detail_id[`+row+`]" id="retur_pembelian_detail_detail_id_`+row+`">	
						<select class="form-control" name="retur_pembelian_detail_barang_id[`+row+`]" id="retur_pembelian_detail_barang_id_`+row+`" style="width: 100%" data-id="`+row+`" onchange="setSatuan('`+row+`')"></select>
					</td>
					<td>
						<input type="hidden" class="form-control" name="retur_pembelian_detail_satuan[`+row+`]" id="retur_pembelian_detail_satuan_`+row+`">	
						<input type="text" class="form-control" name="retur_pembelian_detail_satuan_kode[`+row+`]" id="retur_pembelian_detail_satuan_kode_`+row+`" readonly="">	
					</td>
					<td><input class="form-control number" value="" type="text" name="retur_pembelian_detail_harga[`+row+`]" id="retur_pembelian_detail_harga_`+row+`" onkeyup="countRow('`+row+`')"></td>
					<td><input class="form-control number" type="text" disabled="" value="" name="barang_stok[`+row+`]" id="barang_stok_`+row+`" onchange="countRow('`+row+`')"></td>
					<td>
						<input class="form-control number qty" type="text" name="retur_pembelian_detail_retur_qty[`+row+`]" id="retur_pembelian_detail_retur_qty_`+row+`" value="" onkeyup="countRow('`+row+`')">
						<input class="form-control number" type="hidden" value="" name="retur_pembelian_detail_retur_qty_barang[`+row+`]" id="retur_pembelian_detail_retur_qty_barang_`+row+`">
					</td>
					<td><input class="form-control number" disabled="" type="text" name="barang_stok_sisa[`+row+`]" id="barang_stok_sisa_`+row+`" value=""></td>
					<td><input class="form-control number jumlah" type="text" name="retur_pembelian_detail_jumlah[`+row+`]" id="retur_pembelian_detail_jumlah_`+row+`"  value="" onchange="countJumlah()"></td>
					<td><button type="button" data-id="`+row+`" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" title="Edit" onclick="remRow('`+row+`')" >                      
              			<span class="la la-trash"></span> 
                    </button></td>
				</tr>	`;
		$('#table-detail_barang').append(html);
		$('input.number').number(true);
		setBarang(false, dt)
	}

	function remRow(id){
		$('tr.barang_'+id).remove();
		detail = [];
		$('.barang').each(function(i, v){
			detail.push($(v).data('id'))
		});

		// if($('#table-detail_barang tbody tr').length == 0){
  //           $('#table-detail_barang tbody').append(`<tr class="no-list">
		// 				<td colspan="8" class="text-center">Silahkan Pilih Data Pembelian Barang</td>
		// 			</tr>`);
  //           $('#table-detail_barang tfoot tr').css('display', 'none');
  //       }
		countRow(id);
	}

	function onEdit(el) {
		HELPER.loadData({
			table 	: 'table-returpembelianbarang',
            url 	: HELPER.api.read,
            server 	: true,
            inline	: $(el),
			callback : function (res) {
				$('#retur_pembelian_pembelian_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_pembelian_id,
						text: res.pembelian_kode+' - (Rp. '+res.pembelian_bayar_sisa+')'
					}
				});
				$('#retur_pembelian_supplier_id').select2("trigger", "select", {
					data: {
						id: res.retur_pembelian_supplier_id,
						text: res.supplier_kode+' - '+res.supplier_nama+''
					}
				});
				// pembelian_kode, " - (Rp. ", pembelian_bayar_sisa, ")"
				// getDetailBarang(res.retur_pembelian_id);

				if(res.html !== ''){
					$('#table-detail_barang tbody').html(res.html);
				}
				barang = [];
				row = 1;
				$.each(res.detail.data, function(i, v) {
					barang.push('#retur_pembelian_detail_barang_id_'+row);
					row++;
				})		
				// console.log(barang);
				if(row>=1){
					setBarang(barang);
					countRow(row-1);
				}
				setBarang();
				onAdd()
			}
		})
	}
	function getDetailBarang(parent) {
		$.ajax({
			url : BASE_URL+'returpembelian/get_detail',
			type: 'post',
			data: {retur_pembelian_detail_parent:parent},
			success : function(res) {
				$.each(res.data, function(i, v) {					
                    detail.push(v.retur_pembelian_detail_detail_id);
                    addBarang(v);
					setSatuan(v.retur_pembelian_detail_barang_id, v.retur_pembelian_detail_satuan, v.retur_pembelian_detail_detail_id, v);
                    $('tr.no-list').remove();
                    $('#table-detail_barang tfoot tr').removeAttr('style');
				})
			}
		})
	}
	function onBack() {
		HELPER.back();
	}
	function onRefresh() {
		HELPER.refresh({
			table : 'table-pembelianbarang'
		})
	}

	function save() {
		HELPER.save({			
			form 		: 'form-returpembelianbarang',
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
			table 	: 'table-pembelianbarang',
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
		if (param) {
			$.ajax({
				url: BASE_URL + 'returpembelian/cetak/' + param,
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
				table: 'table-returpembelianbarang',
				callback: function(data) {
					if (data) {
						$.ajax({
							url: BASE_URL + 'returpembelian/cetak/' + data.retur_pembelian_id,
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

	function format ( d ) {
		var data = $.parseJSON(atob($($(d[0])[2]).data('record')));	 
		$.ajax({
			url: BASE_URL + "returpembelian/loaddetail",
			type: "POST",
			data:{
				retur_pembelian_detail_parent: data.retur_pembelian_id,
			},
			success:function(response){
				var hasil = $.parseJSON(response);
				$("#hasil_load_detail").empty();
				$("#hasil_load_detail").append(hasil.html);
			}
		});
		return '<div id="hasil_load_detail" style="margin-left: -15px;padding-right: 30px;padding-left: 50px;"></div>';
	}
</script>