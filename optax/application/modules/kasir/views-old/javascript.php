
<script type="text/javascript">
	$(function() {
		$('.barang_jual').css('height',screen.height - 368);
		/*$('#table-detail_barang').arrowTable({
		  // enabledKeys: ['left', 'right', 'up', 'down'],
		  // listenTarget:'input'
		});*/
		$('#pengajuan_jasa').val(<?php echo $this->config->item('base_jasa_pinjaman');?>);		
		$('#kt_dashboard_daterangepicker_date').html(moment().format('MMM D'))		
		arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
		$('#kt_dashboard_daterangepicker').datepicker({
            rtl: KTUtil.isRTL(),
            todayBtn: "linked",
            autoclose : true,
            clearBtn: true,
            todayHighlight: true,
            templates: arrows
        });
		row = 1;
		satuan = barang = [];
		HELPER.fields = [
			'penjualan_id',
			'penjualan_tanggal',
			'penjualan_kode',
			'penjualan_anggota_id',
			'penjualan_total_item',
			'penjualan_total_qty',
			'penjualan_total_harga',
			'penjualan_total_grand',
			'penjualan_total_bayar',
			'penjualan_total_bayar_tunai',
			'penjualan_total_bayar_voucher',
			'penjualan_total_bayar_voucher_khusus',
			'penjualan_total_potongan',
			'penjualan_total_potongan_persen',
			'penjualan_total_kembalian',
			'penjualan_total_kredit',
			'penjualan_total_cicilan',
			'penjualan_jatuh_tempo',
			'penjualan_keterangan',
			'anggota_nip',
			'anggota_kode',
			'anggota_nama',
			'anggota_saldo_simp_titipan_belanja',
		];

		HELPER.setRequired([
			'penjualan_tanggal',
		]);

		HELPER.api = {
			table 	: BASE_URL + 'transaksipenjualan/',
			read 	: BASE_URL + 'transaksipenjualan/read',
			store 	: BASE_URL + 'transaksipenjualan/store',
			update 	: BASE_URL + 'transaksipenjualan/update',
			destroy : BASE_URL + 'transaksipenjualan/destroy',
			get_parent : BASE_URL + 'kelompokbarang/go_tree',
		}
		$('input.number').number(true);
		$('.disc').number(true, 2);

		HELPER.ajaxCombo({
			el 	: '#penjualan_anggota_id',
			url : BASE_URL + 'transaksipenjualan/select_ajax',
			placeholder : 'nasabah',
			clear : false
		});
		
		$.post(BASE_URL + 'satuan/select', function(res) {
			satuan = res.data;
			setBarang();
		})
		// $('#penjualan_bayar_opsi').select2();
		$('form input, form select, body').keydown(function (e) {
		    if (e.keyCode == 35) { //end
		        e.preventDefault();
		        $('#penjualan_total_potongan_persen').trigger('focus');
		        $('#penjualan_anggota_id').select2('close');
	       	 	$('#penjualan_detail_barang_id_'+row).select2('close');
		        return false;
		    } 
		    if (e.keyCode == 113) {
		        e.preventDefault();
		        $('#barang').trigger('focus');
		        $('#penjualan_anggota_id').select2('close');
		        return false;
		    }
		    if (e.keyCode == 120) {
		        e.preventDefault();
	        	$('#penjualan_detail_barang_id_'+row).select2('close');
		        $('#penjualan_anggota_id').select2('open');
		        return false;
		    }
		    if (e.keyCode == 115) { //f9
		        e.preventDefault();
	        	$('#penjualan_detail_barang_id_'+row).select2('close');
		        $('#penjualan_anggota_id').select2('close');
		        cariBarang()
		        return false;
		    }
		    if (e.keyCode == 66 && e.ctrlKey) {
		        e.preventDefault();
		        // $('#penjualan_kode').trigger('focus');
		        $('#penjualan_anggota_id').select2('close');
		        $('#penjualan_detail_barang_id_'+row).select2('open');
		        return false;
		    }
		});
		$(".use_barcode").keypress(function(event){
		    if (event.which == '10' || event.which == '13') {
		    	getBarang($('.use_barcode').val());
		        $('#barang').trigger('focus');
		        event.preventDefault();
		    }
		});
		HELPER.createCombo({
			el : 'penjualan_bank_id',
            valueField : 'akun_id',
            displayField : 'akun_nama',
            url : BASE_URL+'akun/select_bank',
            callback: function(res) {
				$('#penjualan_bank_id').select2();
            }
		})
        $('#qty').trigger('focus');
        var myVar = setInterval(myTimer, 1000);
        saldo_voucher = saldo_voucher_khusus = 0;
        st_edit = false;
        vdt = {};
	});

	function addThis(el) {
		HELPER.getDataFromTable({
			table 	: 'table-barang',
			inline 	: el,
			callback: function(res) {
				$.ajax({
					url 	: BASE_URL+'barang/single_read',
					data 	: {barang_id : res.barang_id},
					type 	: 'post',
					success : function(res) {
						add = true;
		                $.each($('.barang_id'), function(n, r) {
							if(res.barang_id == $(r).val()) {
								trow = $(r).data('id');
								add = false;
							}
						})
						qty = parseInt($('#qty').val()) || 1;
						if(!add){
							$('#penjualan_detail_qty_'+trow).val( qty + parseInt($('#penjualan_detail_qty_'+trow).val()));
							countRow(trow);
						}else{						
							if($('#penjualan_detail_barang_id_'+row).val()) addBarang();
							$('#penjualan_detail_barang_id_'+row).select2("trigger", "select", {
								data: {
									id 	 : res.barang_id,
									text : res.barang_kode+' - '+res.barang_nama,
								}
							});
							$('#penjualan_detail_qty_'+row).val(qty);
						}
						$('#barang').val('');
						$('#qty').val('1');
						$('#modal-barang').modal('hide');
					}
				})
			}
		})		
	}
	function myTimer() {
	  var d = moment();
	  $("#Timestamp").text(d.format('HH:mm:ss'));
	}
	function setReset() {
		$('#form-penjualanbarang, #form-bayar').trigger('reset');
		getMetode();
		$("#penjualan_anggota_id").select2("trigger", "select", {
			data: {
				id: '',
				text: ''
			}
		});

		$('#penjualan_anggota_id').attr('disabled', false);
		$('#v_penjualan_total_grand').text(0);
		$('#sisa_saldo, #sisa_saldo_voucher').text('Sisa saldo : ');
		$('#table-detail_barang tbody tr').remove();
		row = 0;
		addBarang();
	}
	function setDate() {
		dt = $('#penjualan_tanggal').val();
		view = moment(dt).format('MMM D');
		// alert(view);
		$('#kt_dashboard_daterangepicker_date').html(view)
	}
	function getMetode() {
		metode = $('#penjualan_metode').val();
		$('.bank').hide();
		$('.kt-payment').css('padding-bottom', '280px')
		if(metode == 'B'){
			$('.kt-payment').css('padding-bottom', '340px')
			$('.bank').show();
		}	
		if(metode == 'K' && !$('#penjualan_anggota_id').val()){
			$('#penjualan_metode').val('T').trigger('change');
			swal.fire('Informasi', 'Silahkan pilih nasabah terlebih dahulu!','warning');
		}
	}

	function getBarang(id) {
		HELPER.block();
		$.ajax({
			url 	: BASE_URL+'transaksipenjualan/get_barang',
			data 	: {val : id},
			type 	: 'post',
			success : function(res) {
				if(res[0]){
					v = res[0];
					add =  true;
					trow = '';
					$.each($('.barang_id'), function(n, r) {
						if(v.id == $(r).val()) {
							trow = $(r).data('id');
							add = false;
						}
					})
					if(!add){
						$('#penjualan_detail_qty_'+trow).val(parseInt($('#qty').val()) + parseInt($('#penjualan_detail_qty_'+trow).val()));
						countRow(trow);
					}else{						
						if($('#penjualan_detail_barang_id_'+row).val()) addBarang();
						$('#penjualan_detail_barang_id_'+row).select2("trigger", "select", {
							data: {
								id 	 : v.id,
								text : v.text,
								// saved: v.saved
							}
						});
						$('#penjualan_detail_qty_'+row).val($('#qty').val());
					}
					$('#barang').val('');
					$('#qty').val('1');
		        	$('#barang').trigger('focus');
					// countRow(row);
				}else{
					swal.fire('Informasi', 'Data tidak ditemukan', 'warning');
					cariBarang();					
				}
				HELPER.unblock();				
			}
		})
	}

	function cariBarang() {		
		HELPER.block();
		if($.fn.DataTable.isDataTable('#table-barang')){
			$('#table-barang').DataTable().destroy();
		}
		var table = $('#table-barang').DataTable({
			responsive : true,
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
				url: BASE_URL+'barang/',
				type: 'POST'
			},
			order : [[1,'asc']],
			columnDefs: [
				{
					targets : 0,
					orderable : false
				},
				{
					targets : 5,
					render : function(data, type, row) {
						return $.number(row[5]);
					}
				},
				{
					targets : 7,
					render : function(data, type, row) {
						return $.number(row[7]);
					}
				},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {
                        aksi = `<button type="button" class="btn btn-outline-brand btn-pill btn-sm" title="Edit" onclick="addThis(this)" >
	                          <i class="la la-check-circle"></i> Pilih
	                        </button>`;
	                    return aksi
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
		HELPER.unblock();
		$('#modal-barang').modal();
	}
	function onBayar() {
		save()
	}
	function setBarang() {
		HELPER.ajaxCombo({
			el 		: '#penjualan_detail_barang_id_' + row,
			url 	: BASE_URL + 'transaksipenjualan/barang_ajax',
			wresult : 'bigdrop'
		});
		$('input.number').number(true);
	}
	function setSatuan(row, st, detail) {   
	    barang_id = $('#penjualan_detail_barang_id_' + row).val();
	    $.post(BASE_URL+'barang/list_satuan',{barang_id : barang_id}, function(res) {
	      	html = '';
	      	$.each(res.data, function(i,v){
	        	html += `<option value="`+v.barang_satuan_id+`" data-barang_satuan_harga_jual="`+v.barang_satuan_harga_jual+`" data-barang_satuan_disc="`+v.barang_satuan_disc+`" data-barang_satuan_konversi="`+v.barang_satuan_konversi+`" data-barang_kategori="`+v.kategori_barang_parent+`">`+v.barang_satuan_kode+`</option>`
	      	})
	      	$('#penjualan_detail_satuan_' + row).html(html);
	      	$('#penjualan_detail_satuan_' + row).select2();
			if(st) $('#penjualan_detail_satuan_' +row).val(st).trigger('change');
	      	getHarga(row, detail);
	    })
  	}
  	function getHarga(row, detail) {
  		if(detail){  			
			$('#penjualan_detail_harga_' +row).val(detail.penjualan_detail_harga);
			$('#penjualan_detail_qty_' +row).val(detail.penjualan_detail_qty);
			$('#penjualan_detail_qty_barang_' +row).val(detail.penjualan_detail_qty_barang);
			$('#penjualan_detail_potongan_persen_' +row).val(detail.penjualan_detail_potongan_persen);					
			$('#penjualan_detail_potongan_' +row).val(detail.penjualan_detail_potongan);
			$('#penjualan_detail_subtotal_' +row).val(detail.penjualan_detail_subtotal);
  		}else{
		    harga = $('#penjualan_detail_satuan_' + row +' option:selected').data();
		    konversi = 0;
		    if(harga){
			    $('#penjualan_detail_satuan_kode_' + row).val($('#penjualan_detail_satuan_' + row +' option:selected').text());
			    $('#penjualan_detail_harga_' + row).val(harga.barang_satuan_harga_jual)
			    $('#penjualan_detail_potongan_persen_' + row).val(harga.barang_satuan_disc)
			    $('#penjualan_detail_jenis_barang_' + row).val(harga.barang_kategori)
			    konversi = parseInt(harga.barang_satuan_konversi) || 0;   
			}
		    qty = parseInt($('#penjualan_detail_qty_' + row).val()) || 0;
		    qty_barang = konversi*qty;
		    $('#penjualan_detail_qty_barang_' + row).val(qty_barang);    
  		}
	    countRow(row)
  	}

	function setPrice(row) {
		dt = $('#barang_satuan_opt_'+row).data();
		harga = parseInt(dt.harga);
		isi = parseInt(dt.isi) || 1;
		qty = parseInt($('#penjualan_detail_qty_'+row).val()) || 0;
		if(dt.satuan == $('#penjualan_detail_satuan_'+row).val()){
			qty = isi*qty;
			harga = harga*isi;
		}
		$('#penjualan_detail_qty_barang_' + row).val(qty);		
		$('#penjualan_detail_harga_' + row).val(harga);		
		countRow(row);
	}

	function getNasabah() {
		$.post(BASE_URL+'anggota/read',{anggota_id:$('#penjualan_anggota_id').val()}, function(res) {
			$('#anggota_nip').val((res.grup_gaji_kode?res.anggota_nip+' - ('+res.grup_gaji_kode+') '+res.grup_gaji_nama:''));
			$('#anggota_grup_gaji').text('GOL. ('+(res.grup_gaji_kode?res.grup_gaji_kode+') '+res.grup_gaji_nama:'-) '));
			$('.voucher').val(res.anggota_saldo_simp_titipan_belanja);
			saldo_voucher = parseInt(res.anggota_saldo_simp_titipan_belanja) || 0;
			if(res.anggota_saldo_bhr>0){
				saldo_voucher_khusus = parseInt(res.anggota_saldo_bhr)||0;
				$('#anggota_saldo_voucher').val(res.anggota_saldo_bhr);
				$('#saldo-voucher-anggota').css('display', '');
				$('#saldo-voucher-anggota').text('Exp. '+moment(res.anggota_saldo_bhr_exp_date).format('D MMM YYYY'));
			}else{				
				$('#saldo-voucher-anggota').css('display', 'none');
			}
			console.log(saldo_voucher)
			if(res.anggota_id){
				$('#clear-anggota, .nasabah').css('display', '');
				$('.bayar-voucher, .bayar-voucher-anggota').attr('disabled',false);
			}else{
				$('.bayar-voucher, .bayar-voucher-anggota').val('');
				$('.bayar-voucher, .bayar-voucher-anggota').attr('disabled',true);
				$('#clear-anggota, .nasabah').css('display', 'none');
				$('#anggota_nip, .voucher, #anggota_saldo_voucher').val(0);
				$('#anggota_grup_gaji').text('')
        		saldo_voucher = saldo_voucher_khusus = 0;
			}
			if(st_edit == true){				
				$('#penjualan_anggota_id').attr('disabled', true);
				$('#clear-anggota').css('display', 'none');
				saldo_voucher += parseInt(vdt['titipan_belanja']) || 0;
				saldo_voucher_khusus += parseInt(vdt['voucher']) || 0;
			}
			countDiskon()
			st_edit = false;
		})
	}

	function clearAnggota() {
		$("#penjualan_anggota_id").select2("trigger", "select", {
			data: {
				id: '',
				text: ''
			}
		});
		getNasabah();
	}

	function init_table(argument) {
		awal=$("[name='awal_tanggal']").val()
		akhir=$("[name='akhir_tanggal']").val()
		if ($.fn.DataTable.isDataTable('#table-penjualanbarang')) {
		  	$('#table-penjualanbarang').DataTable().destroy();
		}
		var table = $('#table-penjualanbarang').DataTable({
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
				url: BASE_URL + 'transaksipenjualan/',
				type: 'POST',
				data:{
					tanggal1:awal,
					tanggal2:akhir,
				}
			},
			order: [
				[1, 'desc']
			],
			columnDefs: [{
					targets: 0,
					orderable: false
				},
				{
					targets :5,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets :6,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets :7,
					render: function (data,type,row) {
						return data.replace( /\B(?=(\d{3})+(?!\d))/g, ",");
					}
				},
				{
					targets: -1,
					orderable: false,
					render: function(data, type, row) {
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>| 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-active" onclick="onPrint('`+data+`')" title="Print" >
                          <span class="la la-print"></span> Print
                        </a>`;
					},
				},
				{
					targets: 2,
					render: function(data, type, row) {
						return (data?moment(data).format("DD-MM-YYYY"):'');
					},
				}
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

	function onTables() {
		init_table();
		$('#modal-penjualan').modal();
	}

	function onAdd() {
		HELPER.toggleForm({});
	}

	function countRow2(nrow) {
		nqty = parseInt($('#penjualan_detail_qty_' + nrow).val()) || 0;
		jumlah = (parseInt($('#penjualan_detail_harga_' + nrow).val()) * nqty)  || 0;
		pot_persen = parseFloat($('#penjualan_detail_potongan_persen_' + nrow).val()) || 0;
		potongan = pot_persen*jumlah/100;
		$('#penjualan_detail_potongan_' + nrow).val(potongan);
		jumlah -= potongan;
		$('#penjualan_detail_subtotal_' + nrow).val(jumlah)
		dt = $('#barang_satuan_opt_'+nrow).data();
		if(dt.satuan == $('#penjualan_detail_satuan_'+nrow).val()){
			isi = parseInt(dt.isi) || 1;
			nqty = isi*nqty;
		}
		$('#penjualan_detail_qty_barang_' + nrow).val(nqty);	
		sub_total = qty = item = 0;
		done = true;
		$('.jumlah').each(function(i, v) {
			t = parseInt($(v).val()) || 0;
			sub_total += t;
			if(!t) done = false;
			else item++;
		})
		$('.qty').each(function(i, v) {
			qty += parseInt($(v).val());
		})
		if(done) addBarang()
		$('#penjualan_total_harga').val(sub_total);
		$('#penjualan_total_item').val(item);
		$('#penjualan_total_qty').val(qty);
		countDiskon()
	}

  	function countRow(nrow) {
	    qty = parseInt($('#penjualan_detail_qty_' + nrow).val()) || 0;
	    harga = parseInt($('#penjualan_detail_harga_' + nrow).val()) || 0;
	    qty_barang = harga_barang=0;
	    st = $('#penjualan_detail_satuan_'+nrow+' option:selected').data();
	    if(st){
		    konversi = parseInt(st.barang_satuan_konversi) || 1;   
		    qty_barang = konversi*qty;
		    harga_barang = harga/konversi;
	   	}
	    $('#penjualan_detail_harga_barang_'+ nrow).val(harga_barang);
	    $('#penjualan_detail_qty_barang_' + nrow).val(qty_barang); 
	    jumlah = (harga * qty) || 0;
	    disc =  parseFloat($('#penjualan_detail_potongan_persen_' + nrow).val()) || 0;
	    diskon = disc * jumlah/100;
		diskon = Math.round(diskon / 100) * 100;
		$('#penjualan_detail_potongan_' + nrow).val(diskon);

	    jumlah = jumlah - diskon;
	    $('#penjualan_detail_subtotal_' + nrow).val(jumlah)
	    sub_total = qty = item = 0;
	    done = true;

	    $('.jumlah').each(function(i, v) {
	      sub_total += parseInt($(v).val()) || 0;
	      t = parseInt($(v).val());
	      if(!t) done = false;
	      else item++;
	    })
	    if(done) addBarang()
	    for (var i = 1; i <= row; i++) {
	      	if($('#penjualan_detail_subtotal_'+i).val()) qty += parseInt($('#penjualan_detail_qty_'+i).val());
	    }
	    $('#penjualan_total_harga').val(sub_total);
	    $('#penjualan_total_item').val(item);
	    $('#penjualan_total_qty').val(qty);
	    countDiskon()
  	}

  	function setBayar(el) {
  		bayar = $(el).val();
  		$('.bayar-tunai').val(bayar);
  		tunai = parseInt($('#penjualan_total_grand').val());
  		nilai = parseInt($('.bayar-tunai').val());
  		/*if (nilai >= tunai) {
  			swal.fire('Informasi', 'Jumlah tunai melebihi total harga!','warning');
  			$('.bayar-tunai').val('');
  		}*/
  		countDiskon();
  	}

  	function setVoucher(el) {
  		voucher = parseInt($(el).val()) || 0;
  		saldo = saldo_voucher;
  		vk = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
  		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
  		jumlah = (parseInt($('#penjualan_total_harga').val()) || 0)-vk-diskon;
  		if((saldo_voucher-voucher)<0){
  			swal.fire('Informasi', 'Jumlah penggunaan titipan belanja melebihi saldo yang tesedia!','warning');
  			voucher = saldo;
  		}
  		if(voucher > jumlah){
  			swal.fire('Informasi', 'Jumlah penggunaan titipan belanja melebihi total penjualan!','warning');
  			voucher = jumlah;
  		}
  		$('.bayar-voucher').val(voucher)
  		sisa = saldo_voucher-voucher
		$('#anggota_saldo_simp_titipan_belanja').val(sisa)
  		countDiskon();
  	}

  	function setVoucherKhusus(el) {
  		voucher = parseInt($(el).val()) || 0;
  		saldo = saldo_voucher_khusus;
  		// console.log(saldo_voucher_khusus);
  		v = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
  		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
  		jumlah = (parseInt($('#penjualan_total_harga').val()) || 0)-v-diskon;
  		if((saldo_voucher_khusus-voucher) < 0){
  			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi saldo yang tesedia!','warning');
  			voucher = saldo;
  		}
  		if(voucher > jumlah){
  			swal.fire('Informasi', 'Jumlah penggunaan voucher melebihi total penjualan!','warning');
  			voucher = jumlah;
  		}
		// console.log($('#anggota_saldo_voucher').val())
		// console.log($('#anggota_saldo_voucher').val())
  		$('.bayar-voucher-anggota').val(voucher)
  		sisa = saldo_voucher_khusus-voucher
		$('#anggota_saldo_voucher').val(sisa)
  		countDiskon();
  	}

  	function fillVoucher(el) {
  		v = parseInt($(el).val()) || 0;
  		if(v<=0){
  			// alert('hallo');
	  		saldo = parseInt(saldo_voucher) || 0;
	  		disc = parseInt($('#penjualan_total_potongan').val()) || 0;
	  		vkhusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0;
	  		bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
	  		total = parseInt($('#penjualan_total_harga').val()) || 0;
			// console.log(saldo_voucher+' - '+bv);
	  		if(saldo>0) {
	  			sisa = total-bayar-vkhusus-disc;
	  			if(sisa>0){
		  			if(saldo_voucher>sisa) bv = sisa;
		  			else bv = saldo 
		  			$('.bayar-voucher').val(bv);
		  			$('#anggota_saldo_simp_titipan_belanja').val((saldo_voucher-bv))
  					// console.log(saldo_voucher+' - '+bv);
		  		}
	  		}
	  		countDiskon()
	  	}
  	}

  	function fillVoucherKhusus(el) {
  		voucher_khusus = parseInt($(el).val()) || 0;
  		if(voucher_khusus<=0){
	  		saldo = parseInt(saldo_voucher_khusus) || 0;
	  		disc = parseInt($('#penjualan_total_potongan').val()) || 0;
	  		voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0;
	  		bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0;
	  		total = parseInt($('#penjualan_total_harga').val()) || 0;
	  		if(saldo>0) {
	  			sisa = total-bayar-voucher-disc;
	  			if(sisa>0){
		  			if(saldo_voucher_khusus>sisa) bv = sisa;
		  			else bv = saldo 
		  			$('.bayar-voucher-anggota').val(bv);
		  			$('#anggota_saldo_voucher').val((saldo_voucher_khusus-bv))
		  			// console.log($('#anggota_saldo_voucher').val())
		  			/*if(saldo>sisa) $('.bayar-voucher-anggota').val(sisa);
		  			else $('.bayar-voucher-anggota').val(saldo)*/
		  			// console.log(saldo_voucher_khusus+' - '+bv);
		  		}
	  		}
	  		countDiskon()
	  	}
  	}

	function countDiskon() {		
		sub_total = parseInt($('#penjualan_total_harga').val()) || 0;
		diskon = parseInt($('#penjualan_total_potongan').val()) || 0;
		diskon_p = parseInt($('#penjualan_total_potongan_persen').val()) || 0;
		if(diskon_p){
			diskon = diskon_p*sub_total/100;
			$('#penjualan_total_potongan').val(diskon)
		}
		/*else{
			diskon_p = diskon*100/sub_total;
			$('#penjualan_total_potongan_persen').val(diskon_p)
		}*/
		grand = sub_total - diskon
		bayar = parseInt($('#penjualan_total_bayar_tunai').val()) || 0
		voucher = parseInt($('#penjualan_total_bayar_voucher').val()) || 0
		sisa_saldo =  parseInt(saldo_voucher || 0)-voucher;
		$('#sisa_saldo').text('Sisa Saldo : '+$.number(sisa_saldo))
		voucher_khusus = parseInt($('#penjualan_total_bayar_voucher_khusus').val()) || 0
		sisa_saldo_khusus =  parseInt(saldo_voucher_khusus || 0)-voucher_khusus;
		$('#sisa_saldo_voucher').text('Sisa Saldo : '+$.number(sisa_saldo_khusus))
		tbayar = bayar + voucher + voucher_khusus;
		kredit = grand - tbayar;
		kredit = (kredit>=0)?kredit:0;

		kembalian = (tbayar - grand)>=0?(tbayar - grand):0;
		$('.total_harga').val(grand);

		$('#v_penjualan_total_grand').text($.number(kredit));
		$('#penjualan_total_bayar').val(tbayar);
		$('#penjualan_total_kredit').val(kredit);
		$('#penjualan_total_kembalian').val(kembalian)
		countCicilan() 
	}
	function setChecked(el) {
		var cetak = $(el);				
		if (cetak.is(":checked")) $('.cetak').prop('checked',true);
		else $('.cetak').prop('checked',false);
	}
	function countCicilan() {
		n_cicil = parseInt($('#penjualan_total_cicilan_qty').val()) || 1;
		if(n_cicil>1){
			$('#penjualan_total_jasa').attr('disabled', false);
			if($('#penjualan_total_jasa').val()<0) $('#penjualan_total_jasa').val(<?php echo $this->config->item('base_jasa_pinjaman');?>);		
		}else{
			$('#penjualan_total_jasa').val(0);
			$('#penjualan_total_jasa').attr('disabled', true);
		}
		kredit = parseInt($('#penjualan_total_kredit').val()) || 0;
		$('#pengajuan_jumlah_pinjaman').val(kredit);
		n_jasa =  parseFloat($('#penjualan_total_jasa').val()) || 0;
		pokok = (kredit / n_cicil);
		jasa = (kredit*n_jasa/100);
		jasa = Math.round(jasa / 100) * 100;
		cicil = pokok+jasa;
		$('#penjualan_total_cicilan').val(pokok);
		$('#penjualan_total_jasa_nilai').val(jasa);
		$('#bulan_cicil').text($.number(cicil)+' /bulan');
		if($('#penjualan_tanggal').val()){
			tgl = moment($('#penjualan_tanggal').val());
		}else{
			tgl = moment();
			$('#penjualan_tanggal').val(tgl.format('YYYY-MM-D'));
		}
		n = (tgl.format('D') <= 20)?0:1;
		jt_awal = moment({ y:tgl.format('YYYY'), M:(tgl.format('M') - 1), d:21}).add(n, 'M');
		jt_tempo = moment({ y:tgl.format('YYYY'), M:(tgl.format('M') - 1), d:21}).add((n+n_cicil), 'M');
		$('#penjualan_kredit_awal').val(jt_awal.format('YYYY-MM-DD'))
		$('#penjualan_jatuh_tempo').val(jt_tempo.format('YYYY-MM-DD'))
		// $('#pengajuan_tag_bulan').val(jt_tempo.format('YYYY-MM'))
	}
	
	function addBarang() {
		row++;
		html = `<tr class="barang_` + row + `">
					<td scope="row">
						<input type="hidden" class="form-control" name="penjualan_detail_id[` + row + `]" id="penjualan_detail_id_` + row + `">						
						<input type="hidden" class="form-control" name="penjualan_detail_jenis_barang[` + row + `]" id="penjualan_detail_jenis_barang_` + row + `">						
						<select class="form-control barang_id" name="penjualan_detail_barang_id[` + row + `]" id="penjualan_detail_barang_id_` + row + `" data-id="` + row + `" onchange="setSatuan('` + row + `')" style="width: 260px;white-space: nowrap"></select></td>
					<td><select class="form-control" name="penjualan_detail_satuan[` + row + `]" id="penjualan_detail_satuan_` + row + `" style="width: 100%" onchange="getHarga('` + row + `')"></select>
						<input type="hidden" class="form-control" name="penjualan_detail_satuan_kode[` + row + `]" id="penjualan_detail_satuan_kode_` + row + `" >						
					</td>					
					<td><input class="form-control number" type="text" name="penjualan_detail_harga[` + row + `]" id="penjualan_detail_harga_` + row + `" onchange="countRow('` + row + `')" readonly=""></td>
					<td>
						<input class="form-control number qty" type="text" name="penjualan_detail_qty[` + row + `]" id="penjualan_detail_qty_` + row + `" onkeyup="countRow('` + row + `')" value="1">
						<input class="form-control number" type="hidden" name="penjualan_detail_qty_barang[` + row + `]" id="penjualan_detail_qty_barang_` + row + `">						
					</td>
					<td>
						<input class="form-control disc" type="text" name="penjualan_detail_potongan_persen[` + row + `]" id="penjualan_detail_potongan_persen_` + row + `" onkeyup="countRow('` + row + `')">
						<input class="form-control number" type="hidden" name="penjualan_detail_potongan[` + row + `]" id="penjualan_detail_potongan_` + row + `">
					</td>
					<td><input class="form-control number jumlah" type="text" name="penjualan_detail_subtotal[` + row + `]" id="penjualan_detail_subtotal_` + row + `" readonly=""></td>
					<td style="text-align: center;"><a href="javascript:;" data-id="` + row + `" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus" >
                  		<span class="la la-trash"></span> Hapus</a></td>
				</tr>`;
		$('#table-detail_barang').append(html);
		setBarang();
		$('.disc').number(true, 2);
	}

	function addByBarcode() {
		bcd = $('#form-barcode').serializeObject()
	}

	function onEdit(el) {
		HELPER.loadData({
			table: 'table-barang',
			url: HELPER.api.read,
			server: true,
			inline: $(el),
			// inline_type : 'row',
			callback: function(res) {
				st_edit = true;
		  		$('.bayar-voucher').val(res.penjualan_total_bayar_voucher)
		  		$('.bayar-voucher-anggota').val(res.penjualan_total_bayar_voucher_khusus)
				// getNasabah(true,);
				vdt =  {titipan_belanja : res.penjualan_total_bayar_voucher, voucher:res.penjualan_total_bayar_voucher_khusus};
				getDetailBarang(res.penjualan_id);
				$('#modal-penjualan').modal('hide');
				$("#penjualan_anggota_id").select2("trigger", "select", {
					data: {
						id: res.penjualan_anggota_id,
						text: res.anggota_kode+' - '+res.anggota_nama
					}
				});
				onAdd()
				$('#modal-penjualan').modal('hide');
			}
		})
	}

	function getDetailBarang(parent) {
		$.ajax({
			url: BASE_URL + 'transaksipenjualan/get_detail',
			type: 'post',
			data: {
				penjualan_detail_parent: parent
			},
			success: function(res) {
				$('#table-detail_barang body').html('');
				$.each(res.data, function(i, v) {
					n = i + 1;
					if(n > 1) addBarang();
					$('#penjualan_detail_id_' + n).val(v.penjualan_detail_id);
					$('#penjualan_detail_harga_' + n).val(v.penjualan_detail_harga);
					$('#penjualan_detail_qty_' + n).val(v.penjualan_detail_qty);
					$('#penjualan_detail_qty_barang_' + n).val(v.penjualan_detail_qty_barang);
					$('#penjualan_detail_potongan_persen_' + n).val(v.penjualan_detail_potongan_persen);					
					$('#penjualan_detail_potongan_' + n).val(v.penjualan_detail_potongan);
					$('#penjualan_detail_subtotal_' + n).val(v.penjualan_detail_subtotal);
					$("#penjualan_detail_barang_id_" + n).select2("trigger", "select", {
						data: {
							id: v.penjualan_detail_barang_id,
							text: v.barang_kode + " - " + v.barang_nama,
						}
					});
					// harga
					// new_st = [v.barang_satuan, v.barang_satuan_opt, v.barang_isi, v.barang_harga];
					setSatuan(n, v.penjualan_detail_satuan, v);
					// viewHarga(n, v)
				})
			}
		})
	}

	function remRow(el) {
		id = $(el).data('id');
		$('tr.barang_' + id).remove();
		countRow(id);
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-penjualanbarang'
		})
	}

	function save() {
		if($('#penjualan_metode').val() == 'K'){
			$('#modal-bayar').modal();
		}else{
			if(parseInt($('#penjualan_total_kredit').val()) > 0){
				HELPER.showMessage({
	                success: false,
	                message: 'Silahkan lengkapi pembayaran terlebih dulu!',
	                title: 'Informasi',
				})
			}else{
				saving();
			}
		}
			
	}

	function saving() {
		HELPER.save({
			form: 'form-penjualanbarang',
			data : $('#form-bayar').serializeObject(),
			confirm: true,
			callback: function(success, id, record, message, res) {
				var cetak = $('#cetak');				
				if (success === true) {
					setReset();
					if (cetak.is(":checked")) {
                    	print = res.responseJSON.print;
						if (print) {	
							$('#printArea').html(print);
							var WinPrint = window.open('', '', 'width=900,height=650');
						    WinPrint.document.write($('#printArea').html());
						    WinPrint.document.close();
						    WinPrint.focus();
						    WinPrint.print();
						    WinPrint.close();
			            }
			            $('#modal-bayar').modal('hide');
					} 
				}
			}
		})
		
	}

	function onDestroy(el) {
		HELPER.destroy({
			table: 'table-penjualanbarang',
			inline: el,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success == true) {
					onRefresh()
				}
			}
		})
	}

	function onPrint(param) {
		HELPER.block();
		HELPER.getDataFromTable({
			table: 'table-penjualanbarang',
			callback: function(data) {
				id = data.penjualan_id;
				if (param) {
					console.log('param');
					id = param
				}

				// $.extend(data, {tjson : true});
				$.ajax({
					url 	: BASE_URL+'transaksipenjualan/tprint/'+id,
					data 	: {tjson : true},
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
			}
		})

	}
</script>