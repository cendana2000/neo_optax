<script type="text/javascript" src="<?php echo $this->config->item('base_theme') ?>plugins/jstreetable.js"></script>
<style type="text/css">
/*d7daf8*/
	.jstree-table-header-regular {
	    background-color: #eff0f6;
	    height: 44px;
	    line-height: 44px;
		box-shadow: 0px 4px 7px -5px #c2dbed;
	}
	.jstree-table-separator-regular {
	    border-color: #eff0f6;
	}
	.jstree-table-headerwrapper {
	    margin-bottom: 10px;
	}
	#jstree1 .jstree-default .jstree-anchor, #jstree1 .jstree-table-cell-root-jstree {
	    line-height: 45px;
	    height: 45px;
    	background: #e6e7ef;
		box-shadow: 0px 1px 7px -1px #c2dbed;
	} 
	.jstree-default .jstree-node {
	    margin-left: 0px;
	    padding-left: 24px;
	}
	#jstree1 .jstree-default.jstree-table-column.jstree-table-column-0.jstree-table-column-root-jstree{
		vertical-align: top;
	}
	#jstree1 .jstree-table-midwrapper a.jstree-hovered{
	    background-color: #eff0f6;		
	}
	#jstree1 .jstree-table-midwrapper a.jstree-clicked,
	#jstree1 .jstree-default .jstree-clicked{
    	background: #858cd4;
    	color: #fff;
    	border-radius: 0;
    }
    #jstree1 .jstree-default .jstree-clicked a, #jstree1 .jstree-default .jstree-clicked a > i{
		color: #fff;
    }
    #jstree1 .jstree-default .jstree-clicked a:hover, .jstree-default .jstree-clicked a:hover > i{
		color: #5d78ff;
    }

    .jstree-table-column-0{
	    width :700px!important;
	    min-width :600px!important;
	    max-width :600px!important;
	}

	.jstree-table-column-1,.jstree-table-column-2{
	    width : 200px!important;
	    min-width : 200px!important;
	    max-width : 200px!important;
	}

</style>
<script type="text/javascript">
	$(function(){
		HELPER.fields = [
			'akun_id',
			'akun_kode',
			'akun_nama', 
			'akun_parent', 
			'akun_tipe',
			'akun_is_pembayaran',
			'akun_is_bank',
			'akun_is_kas_bank',
			'akun_bank_jenis_id',
			'akun_bank_rekening',
			'akun_unit',
		];
		HELPER.api = {
			table 	: BASE_URL+'akun/',
			read 	: BASE_URL+'akun/read',
			store 	: BASE_URL+'akun/store',
			update 	: BASE_URL+'akun/update',
			destroy : BASE_URL+'akun/destroy',
			get_akun_parent : BASE_URL+'akun/go_tree',
		}

		HELPER.create_combo_akun({
			el : 'akun_parent',
    		valueField : 'id',
    		displayField : 'text',
    		parentField : 'parent',
    		childField : 'child',
    		url : HELPER.api.get_akun_parent,
    		withNull : true,
    		nesting : true,
    		chosen : false,
    		callback : function(){}
		});
		HELPER.createCombo({
			el : 'akun_bank_jenis_id',
			url : BASE_URL+'bankjenis/select',
			valueField : 'bank_jenis_id',
			displayField : 'bank_jenis_nama',
			callback : function() {
				$('#akun_bank_jenis_id').select2()
			}
		})
		HELPER.createCombo({
			el : 'akun_unit',
			url : BASE_URL+'unit/select',
			valueField : 'unit_id',
			displayField : 'unit_nama',
			callback : function() {
				$('#akun_unit').select2();
			}
		})
		$("#jstree").jstree({
			search: {
            	show_only_matches: true,
            	show_only_matches_children: true,
            },
            plugins : [ 'table', 'state', 'wholerow', 'search' ],
			state : { "key" : "demo2" },
			core: {
				data: {
					url : BASE_URL+'akun/select_tree',
					data : function (node) {
						return { 'id' : node.id };
					},
				},
				check_callback: true
			},
			// configure tree table
			table: {
				columns: [
					{width: 600, header: "Name"},
					{width: 200, value: "saldo", header: "Saldo", format: function(v) {if (v){ return $.number(v) }}},
					{width: 200, value: "id", header: "Aksi", format: function(v) {
						// console.log(v);
						return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit('`+v+`')" >
                          <i class="la la-edit"></i> Edit
                        </a> | 
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy('`+v+`')" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>`;
					} }
				],
				contextmenu: false,
				// resizable: true,
				// draggable: true,
				// height: 500
			}
		});
		$("#jstree").on('changed.jstree', function (e, data) {
			var node_selected = data.instance.get_node(data.selected[0]);
			$('#akun_id').val(node_selected.id);
	     	addStyle();
		});
		$('#jstree').on('load_node.jstree',function(){
			addStyle();
		});
		$('#jstree').on('load.jstree',function(){
			addStyle();
		});
		$('#jstree, .jstree-icon').on('click', function() {
			addStyle();
		})
		$('.number').number(true);
		setInduk();

		// setBank();
		/*var interval_id = setInterval(function(){
	     // $("li#"+id).length will be zero until the node is loaded
		    if($("li#jstree").length != 0){
		        clearInterval(interval_id)
		        // "exit" the interval loop with clearInterval command
		        // since the node is loaded, now we can open it without an error
		        $("#jstree").jstree("open_node", $("li#jstree"))
		     	addStyle();
		    }
		}, 5);*/

		// setTimeout(addStyle(), 10000);
	})

	function onSearch(el) {
		q = $(el).val();
		// $('#jstree').jstree('search', q);
		$("#jstree").jstree(true).search(q);
	}

	function setInduk(nama_akun='', rekening='') {
		parent = $('#akun_parent').val();
		$('.bank_akun, .nama_akun').hide()
		if(nama_akun){
			$('#akun_nama').val(nama_akun);
			$('#akun_bank_rekening').val(rekening);
		}else{
			$('#akun_nama,#akun_bank_rekening').val('');
			$('#akun_bank_jenis_id').select2('val','');
		}
		if(parent == '1112') $('.bank_akun').show(500)
		else $('.nama_akun').show(500)
	}

	function addStyle(){
		$('.jstree-table-column-0').css({
		    'width':'700px',
		    'min-width':'700px',
		    'max-width':'700px',
		})

		$('.jstree-table-column-1,.jstree-table-column-2').css({
		    'width':'200px',
		    'min-width':'200px',
		    'max-width':'200px',
		})

		$('.jstree-table-header-cell:first-child').css({
		    'width':'600px',
		    'min-width':'600px',
		    'max-width':'600px',
		})
		
		$('.jstree-table-header-cell:eq(1),.jstree-table-header-cell:eq(2)').css({
		    'width':'200px',
		    'min-width':'200px',
		    'max-width':'200px',
		})
		
		$('#jstree1 .jstree-default .jstree-anchor, #jstree1 .jstree-table-cell-root-jstree').css(
   		 	'margin-bottom' ,'10px'
		);
	}

	function setBank(edit=false) {
		bank = $('#akun_is_bank')
		$('.bank_akun, .nama_akun').hide()
		if(!edit){
			$('#akun_nama,#akun_bank_rekening').val('');
			$('#akun_bank_jenis_id').select2('val','');
		}
		if(bank.prop('checked')) $('.bank_akun').show(500)
		else $('.nama_akun').show(500)
	}

	function setNama() {
		bank = $('#akun_is_bank')
		parent = $('#akun_parent').val();
		if(parent == '1112'){
			akun_nama = $('#akun_bank_jenis_id option:selected').text() +($('#akun_bank_rekening').val()?' #' + $('#akun_bank_rekening').val():'');
			$('#akun_nama').val(akun_nama);
		}
	}

	function onEdit(id) {
		$.ajax({
			url 	: BASE_URL+'akun/read',
			type 	: 'post',
			data 	: {akun_id : id},
			success : function(resp_object) {

				if(resp_object.akun_id){
					if(resp_object.akun_lock == '1'){
						swal.fire('Informasi', 'Maaf akun yang anda pilih tidak dapat dirubah/hapus dikarenakan keperluan otomatisasi jurnal.', 'warning');
					}else{
						$.each(HELPER.fields, function(i,v) {
							if ($("[name=" + v + "]").attr('type') == 'checkbox') 
	                        {
	                            $('[name="' + v + '"][value="' + resp_object[v] + '"]').prop('checked', true);
	                        }
	                        else if ($("[name=" + v + "]").attr('type') == 'radio')
	                        {
	                            $('[name="' + v + '"][value="' + resp_object[v] + '"]').prop('checked', true);
	                        }
	                        else
	                        {
	                            $("[name=" + v + "]").val(resp_object[v]).trigger('change');
	                        }
						});
						setInduk(resp_object.akun_nama, resp_object.akun_bank_rekening);
						setNama()
						onAdd(true);
					}
				}
			}
		})
	}

	function setSaldo() {
		HELPER.block();
		$.ajax({
			url 	: BASE_URL+'akun/go_saldo',
			type 	: 'post',
			success : function(res) {
				$('.form_data').hide();
				HELPER.toggleForm({
					tohide : 'table_data',
					toshow : 'form_saldo',
				});
				HELPER.unblock();
				if(res.success){
					$('#akun_id_saldo').val(res.reference_id);
					$('#saldo_periode').val(res.periode)
					html = akunSaldo(res.data);
					$('#table-saldo').append(html)
					$('.number').number(true);
					totalSaldo()
				}
			}
		})
		$('.form_data').hide();
		HELPER.toggleForm({
			tohide : 'table_data',
			toshow : 'form_saldo',
		});
	}

	function akunSaldo(dt_akun) {
		html = '';
		$.each(dt_akun, function(i, v) {
			akun = (v.text).split(" - ");
			html += `<tr>
				<td>`+akun[0]+`</td>
				<td>`+akun[1]+`</td>
				<td>
					<input type="hidden" name="saldo_akun_id[`+v.id+`]" value="`+akun[1]+`" `+(v.tipe == `parent`?`disabled`:``)+` />
					<input class="form-control saldo_debit number" type="text" name="saldo_debit[`+v.id+`]" onkeyup="totalSaldo()" `+(v.tipe == `parent`?`disabled`:``)+` value="`+v.akun_debit+`"/></td>
				<td><input class="form-control saldo_kredit number" type="text" name="saldo_kredit[`+v.id+`]" onkeyup="totalSaldo()" `+(v.tipe == `parent`?`disabled`:``)+` value="`+v.akun_kredit+`" /></td>
			</tr>`;
			if(v.children) html += akunSaldo(v.child)
		})
		return html;
	}

	function totalSaldo() {
		saldo_debit = saldo_kredit = 0;
		$('.saldo_debit').each(function(i,v) {
			saldo_debit += parseInt($(v).val()) || 0;
		})
		$('.saldo_kredit').each(function(i,v) {
			saldo_kredit += parseInt($(v).val()) || 0;
		})
		$('#total_saldo_debit').val(saldo_debit)
		$('[for=total_saldo_debit]').text($.number(saldo_debit));
		$('#total_saldo_kredit').val(saldo_kredit)
		$('[for=total_saldo_kredit]').text($.number(saldo_kredit));
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.back();
	}

	function onAdd(ed = false) {
		HELPER.toggleForm({
			tohide : 'table_data',
			toshow : 'form_data',
		});
		$('#akun_tipe').select2();
		$('#akun_parent').select2();
		if(ed == false) $('#akun_id').val('');
	}

	function save() {
		HELPER.save({			
			form 		: 'form-akun',
			confirm		: true,
			callback : function(success,id,record,message)
			{
				if (success===true) {
					HELPER.back({});
				}
			}	
		})
	}
	function saveSaldo(){
		swal.fire({   
            title: "Pemberitahuan",   
            text: "Apakah anda yakin akan menyimpan data tersebut?",   
            // type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#337ab7",   
            cancelButtonText: "Tidak",   
            confirmButtonText: "Ya",   
        }).then(function(r) {  		
        	if(r.value){
	        	HELPER.block();
	        	$.ajax({
	        		url 	: BASE_URL+'akun/save_saldo',
	        		type 	: 'post',
	        		data 	: $('#form-saldo').serialize(),
	        		success : function(response) {
	        			if (response.success===true) {
							HELPER.back({});
						}
	        		}
	        	})
	        }
		})
	}

    function onDestroy(el){
    	HELPER.confirm({
			title : 'Pemberitahuan',
			message : 'Apakah anda yakin akan menghapus data tersebut?',
			callback : function(res) {
				if(res == true){		
		        	HELPER.block();
					$.ajax({
						url 	: BASE_URL+'akun/destroy',
						data 	: {id : el},
						type 	: 'post',
						success : function(response) {
							HELPER.unblock();
							if(response.success == true){
								swal.fire('Sukses', 'Berhasil menghapus data!', 'success');
								onBack();
							}else{
								swal.fire('Gagal', 'Gagal menghapus data!', 'warning');					
							}
						}
					})
				}
			}
    	})
	}
</script>