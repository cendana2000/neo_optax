<script type="text/javascript">
	$(function(){
		HELPER.fields = [
			'anggota_id',
			'anggota_kode',
			'anggota_grup_gaji',
			'anggota_kelompok',
			'anggota_nip',
			'anggota_nama',
			'anggota_nomor_ktp',
			'anggota_jk',
			'anggota_kota',
			'anggota_kecamatan',
			'anggota_kelurahan',
			'anggota_alamat',
			'anggota_agama',
			'anggota_user',
			'anggota_password',
			'anggota_pekerjaan',
			'anggota_simp_pokok',
			'anggota_simp_manasuka',
			'anggota_simp_wajib',
			'anggota_simp_wajib_khusus',
			'anggota_simp_tabungan_hari_tua',
			'anggota_simp_titipan_belanja',
			'anggota_tgl_gabung',
			'anggota_tgl_keluar',
			'anggota_is_aktif',
		];
		/*HELPER.setRequired([
			'agama_nama',
		]);*/
		HELPER.api = {
			table 	: BASE_URL+'anggota/',
			read 	: BASE_URL+'anggota/read',
			store 	: BASE_URL+'anggota/store',
			update 	: BASE_URL+'anggota/update',
			destroy : BASE_URL+'anggota/destroy',
			kelompok : BASE_URL+'kelompokanggota/select',
			grupgaji : BASE_URL+'grupgaji/select',
			kota 	: BASE_URL+'anggota/kabupaten',
			kecamatan 	: BASE_URL+'anggota/kecamatan',
			kelurahan 	: BASE_URL+'anggota/kelurahan',
		}
		init_table();
		$('input.number').number(true);
		
		HELPER.createCombo({
			el : 'anggota_grup_gaji',
			url : HELPER.api.grupgaji,
			valueField 	: 'grup_gaji_id',
			grouped: true,
			displayField  : 'grup_gaji_kode',
			displayField2  : 'grup_gaji_nama',
			callback:function(resp) {
				$('#anggota_grup_gaji').select2();
			}
		})
		HELPER.createCombo({
			el : 'anggota_kelompok',
			url : HELPER.api.kelompok,
			valueField 	: 'kelompok_anggota_id',
			grouped: true,
			displayField  : 'kelompok_anggota_kode',
			displayField2  : 'kelompok_anggota_nama',
			callback:function(resp) {
				$('#anggota_kelompok').select2();
			}
		})
		HELPER.createCombo({
			el : 'anggota_agama',
			url : BASE_URL+'agama/select',
			valueField 	: 'agama_nama',
			displayField  : 'agama_nama',
			callback:function(resp) {
				$('#anggota_agama').select2();
			}
		})
		/*HELPER.createCombo({
			el : 'anggota_pekerjaan',
			url : BASE_URL+'pekerjaan/select',
			valueField 	: 'pekerjaan_nama',
			displayField  : 'pekerjaan_nama',
			callback:function(resp) {
				$('#anggota_pekerjaan').select2();
			}
		})*/

		$('#anggota_jk').select2();
		$('#anggota_kota').select2({
			placeholder: 'Masukkan nama Kota',
			ajax : {
				url: HELPER.api.kota,
				type: 'POST',
				dataType : 'json',
				result: function(data){
					return {
		              results: data
		            };
				},
				processResults: function (data, page) {
	              return {
	                results: data
	              };
	            },
			}
		})
	});

	function init_table(argument) {
		var table = $('#table-anggota').DataTable({
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
				url: BASE_URL+'anggota/',
				type: 'POST'
			},
			order : [[1,'asc']],
			columnDefs: [
				{
					targets : 0,
					orderable : false
				},
				{
					targets : 3,
					render: function (data,type,row) {
						return moment(data).format("DD-MM-YYYY");
					}
				},
				{
					targets: -1,
					orderable : false,
					render : function(data, type, row) {

                        return `
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" onclick="onEdit(this)" >
                          <i class="la la-edit"></i> Edit
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Non-Aktif" >
                          <span class="la la-trash"></span> Non-Aktifkan
                        </a>
                        `;
                       /* if(data=="Y"){
                        	return``
                        }*/
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
		$.ajax({
			url: BASE_URL+'pengaturansimpanan/select',
			type: 'POST',
			success: function (res) {
				data = res.data[0];
				total = parseInt(data.tagihan_simpanan_manasuka)+parseInt(data.tagihan_simpanan_pokok)+parseInt(data.tagihan_simpanan_wajib)+parseInt(data.tagihan_simpanan_wajib_khusus)+parseInt(data.tagihan_simpanan_hari_tua)+parseInt(data.tagihan_simpanan_voucher);
				$('#anggota_simp_manasuka').val(data.tagihan_simpanan_manasuka)
				$('#anggota_simp_pokok').val(data.tagihan_simpanan_pokok)
				$('#anggota_simp_wajib').val(data.tagihan_simpanan_wajib)
				$('#anggota_simp_wajib_khusus').val(data.tagihan_simpanan_wajib_khusus)
				$('#anggota_simp_tabungan_hari_tua').val(data.tagihan_simpanan_hari_tua)
				$('#anggota_simp_titipan_belanja').val(data.tagihan_simpanan_voucher)
				$('#total_simpanan').val(total)
			}
		})
	}

	function isRedundant() {
		kode = $('#anggota_kode').val();
		$.ajax({
			url:BASE_URL+'anggota/isRedundant',
			type:'POST',
			data:{
				anggota_kode : kode
			},
			success: function(resp){
				if(resp.success==true){

				}else{
					swal.fire("Warning", "Kode Anggota sudah dipakai! Silahkan coba kode lain!", "warning")
					$('#anggota_kode').val('');
				}
			}
		})
	}

	function isRedundantEdit() {
		kode = $('#anggota_kode').val();
		nama = $('#anggota_nama').val();
		console.log(kode);
		$.ajax({
			url:BASE_URL+'anggota/isRedundantEdit',
			type:'POST',
			data:{
				anggota_kode : kode,
				anggota_nama : nama,
			},
			success: function(resp){
				if(resp.success==true){

				}else{
					swal.fire("Warning", "Kode Anggota sudah dipakai! Silahkan coba kode lain!", "warning")
					$('#anggota_kode').val('');
				}
			}
		})
	}

	function onEdit(el) {
		HELPER.loadData({
			table 	: 'table-anggota',
            url 	: HELPER.api.read,
            server 	: true,
            inline	: $(el),
			callback : function (res) {
				/*if(res.anggota_is_aktif=="Y"){
					swal.fire("Warning", "Anggota sudah tidak aktif! Tidak dapat mengedit!", "warning")
				}else{*/
					/*$('#updated').removeAttr('style');
					$('#updated').append(`<label for="anggota_is_aktif" class="col-2 col-form-label">Status Aktif</label>
						<div class="col-4">
							<select class="form-control" id="anggota_is_aktif" name="anggota_is_aktif" style="width: 100%">
								<option value="" selected="">Aktif</option>
								<option value="Y">Tidak Aktif</option>
							</select>
						</div>
						<label for="anggota_is_proteksi" class="col-2 col-form-label">Proteksi</label>
						<div class="col-4">
							<select class="form-control" id="anggota_is_proteksi" name="anggota_is_proteksi" style="width: 100%">
							</select>
						</div>`);*/
					/*if(res.anggota_is_proteksi == 0){
						$('#anggota_is_proteksi').append(`
							<option value="1">Ya</option>
							<option value="0" selected="">Tidak</option>
							`);
					}else{
						$('#anggota_is_proteksi').append(`
							<option value="1" selected="">Ya</option>
							<option value="0">Tidak</option>
							`);
					}*/
					$('#anggota_kode').removeAttr('onchange');
					$('#anggota_kode').attr('onchange','isRedundantEdit()');
					$('#anggota_grup_gaji').val(res.anggota_grup_gaji).trigger('change');
					$('#anggota_pekerjaan').val(res.anggota_pekerjaan).trigger('change');
					$('#anggota_agama').val(res.anggota_agama).trigger('change');
					$('#anggota_jk').val(res.anggota_jk).trigger('change');
					$("#anggota_kota").select2("trigger", "select", {
					    data: {id:res.anggota_kota,text:res.anggota_kota}
					});
					$("#anggota_kecamatan").select2("trigger", "select", {
					    data: {id:res.anggota_kecamatan,text:res.anggota_kecamatan}
					});
					$("#anggota_kelurahan").select2("trigger", "select", {
					    data: {id:res.anggota_kelurahan,text:res.anggota_kelurahan}
					});
					subTotal();
					HELPER.toggleForm({});
				/*}*/
			}
		})
	}
	function onBack() {
		HELPER.back();
	}
	function onRefresh() {
		HELPER.refresh({
			table : 'table-anggota'
		})
	}

	function save() {
		HELPER.save({			
			form 		: 'form-anggota',
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
       /* HELPER.destroy({
			table 	: 'table-anggota',
			inline  : el,
			confirm	: true,
			callback: function(success, id, record, message) {
				if(success == true){
					onRefresh()
				}
			}
		})*/

		swal.fire({
            title: 'Info',
            text: "Apakah anda yakin ingin menonaktifkan anggota?",
            type: 'info',
            confirmButtonText: '<i class="fa fa-check"></i> Yes',
            confirmButtonClass: 'btn btn-focus btn-success m-btn m-btn--pill m-btn--air',
            // reverseButtons: true,
            showCancelButton: true,
            cancelButtonText: '<i class="fa fa-times"></i> No',
            cancelButtonClass: 'btn btn-focus btn-danger m-btn m-btn--pill m-btn--air'
        }).then(function(result) {
            if (result.value) {
				HELPER.loadData({
					table 	: 'table-postingjasa',
		            url 	: BASE_URL+'anggota/read',
		            server 	: true,
		            inline	: $(el),
					callback : function (res) {
						$.ajax({
							url: BASE_URL+'anggota/destroy',
							type:'POST',
							data:{
								anggota_id : res.anggota_id
							},
							success: function(resp){
								if(resp.success==true){
									swal.fire("Success!", "Data berhasil diubah!", "success"); 
									onRefresh();
								}else{
									swal.fire("Warning!", "Data gagal diubah!", "warning"); 
								}
							}
						})
					}
				})
            }else{
                
            }
        });
	}

	function onKecamatan(param){
		kab = $('#anggota_kota').val();
		$('#anggota_kecamatan').select2({
			placeholder: 'Masukkan nama Kecamatan',
			ajax : {
				url: HELPER.api.kecamatan,
				type: 'POST',
				dataType : 'json',
				data: function(params) {
	                return {
	                  kab_nama  : kab,
	                  search 	: params.term
	                }
	            },
				result: function(data){
					return {
		              results: data
		            };
				},
				processResults: function (data, page) {
	              return {
	                results: data
	              };
	            },
			}
		})
	}

	function onKelurahan(param){
		kec = $('#anggota_kecamatan').val();
		kab = $('#anggota_kota').val();
		$('#anggota_kelurahan').select2({
			placeholder: 'Masukkan nama Kelurahan',
			ajax : {
				url: HELPER.api.kelurahan,
				type: 'POST',
				dataType : 'json',
				data: function(params) {
	                return {
	                  kab_nama  : kab,
	                  kec_nama  : kec,
	                  search 	: params.term
	                }
	            },
				result: function(data){
					return {
		              results: data
		            };
				},
				processResults: function (data, page) {
	              return {
	                results: data
	              };
	            },
			}
		})
	}

	//fungsi untuk memanggil modal
	function onPrint(){
		$('#myModal').modal('show');
	}
	function loadPreview(){
		HELPER.block();
		data = $('#filterAnggota').serializeArray();
		$.ajax({
			url 	: BASE_URL+'anggota/preview',
			data 	: data,
			type 	: 'post',
			dataType: 'json',
			success : function(res){
				HELPER.toggleForm({
					tohide: 'table_data',
                	toshow: 'form_data2',
				});
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function loadPrint(){
		HELPER.block();
		$data = $('#filterAnggota').serializeArray();
		$.ajax({
			url 	: BASE_URL+'anggota/print',
			data 	: data,
			type 	: 'post',
			dataType: 'json',
			success: function(res){
				HELPER.unblock();
				window.location.assign(res.url);
			}
		});
	}

	function subTotal(argument) {
		total = 0;
		$('.simpanan').each(function(i, v) {
			if($(v).val()){
				total+=parseInt($(v).val());
			}
		});
		$('#total_simpanan').val(total);
	}

</script>