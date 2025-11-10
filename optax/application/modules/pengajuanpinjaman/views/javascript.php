<script type="text/javascript">
	$(function(){
		$('.number').number(true);
		HELPER.fields = [
			'pengajuan_id',
			'pengajuan_tgl',
			'pengajuan_no',
			'pengajuan_create_at',
			'pengajuan_create_by',
			'pengajuan_anggota',
			'pengajuan_gaji_bersih',
			'pengajuan_gaji_lainnya',
			'pengajuan_sisa_pinjaman_kpri',
			'pengajuan_sisa_pinjaman_lainnya',
			'pengajuan_status',
			'pengajuan_jml_tanggungan',
			'pengajuan_waktu_pensiun',
			'pengajuan_telp',
			'pengajuan_jumlah_pinjaman',
			'pengajuan_keperluan_tunai',
			'pengajuan_angsuran',
			'pengajuan_tenor',
			'pengajuan_alamat',
			'pengajuan_tgl_lahir',
			'pengajuan_jenis',
			'pengajuan_pekerjaan',
		];
		/*HELPER.setRequired([
			'pengajuan_jumlah_pinjaman',
			'pengajuan_jenis',
			'pengajuan_tenor',
			'pengajuan_keperluan',
			'pengajuan_anggota',
		]);*/
		HELPER.api = {
			table 	: BASE_URL+'pengajuanpinjaman/',
			read 	: BASE_URL+'pengajuanpinjaman/read',
			store 	: BASE_URL+'pengajuanpinjaman/store',
			update 	: BASE_URL+'pengajuanpinjaman/update',
			destroy : BASE_URL+'pengajuanpinjaman/destroy',
		}

		HELPER.createCombo({
			el : 'pengajuan_anggota',
            valueField : 'anggota_id',
            displayField : 'anggota_kode',
            displayField2 : 'anggota_nama',
            displayField3 : 'nama_grup',
            grouped : true,
            url : BASE_URL+'anggota/select',
            callback: function(res) {
				$('#pengajuan_anggota').select2();
            }
		})
		HELPER.createCombo({
			el : 'pengajuan_juru_bayar_id',
            valueField : 'juru_bayar_id',
            displayField : 'juru_bayar_nama',
            url : BASE_URL+'jurubayar/select',
            callback: function(res) {
				$('#pengajuan_juru_bayar_id').select2();
            }
		})
		init_table();	
	});

	function init_table(argument) {
		awal=$("[name='awal_tanggal']").val()
		akhir=$("[name='akhir_tanggal']").val()

		if ( $.fn.DataTable.isDataTable('#table-pengajuan') ) {
		  $('#table-pengajuan').DataTable().destroy();
		}

		var table = $('#table-pengajuan').DataTable({
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
				url: BASE_URL+'pengajuanpinjaman/',
				type: 'POST',
				data:{
					tanggal1: awal,
					tanggal2: akhir,
				}
			},
			order : [[1,'desc']],
			columnDefs: [
				{
					targets : 0,
					orderable : false
				},
				{
					targets : 1,
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
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-danger" onclick="onDestroy(this)" title="Hapus" >
                          <span class="la la-trash"></span> Hapus
                        </a>
                        `;
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
		$('#modal').modal('hide');
		var detailRows = [];
	}

	function onAdd() {		
		HELPER.toggleForm({});
	}

	function onEdit(el) {
		HELPER.loadData({
			table 	: 'table-pengajuan',
            url 	: HELPER.api.read,
            server 	: true,
            inline	: $(el),
			callback : function (res) {
				onAdd();
				$('#pengajuan_anggota').val(res.pengajuan_anggota).trigger('change');
				$('#pengajuan_tag_jenis').val(res.pengajuan_tag_jenis).trigger('change');
				$('#pengajuan_pengurus').val(res.pengajuan_pengurus).trigger('change');
				$('#pengajuan_status').val(res.pengajuan_status).trigger('change');
			}
		})
	}
	
	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table : 'table-pengajuan'
		})
	}

	function save() {
		HELPER.save({			
			form 	 : 'form-pengajuan',
			confirm	 : true,
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
			table 	: 'table-pengajuan',
			inline  : el,
			confirm	: true,
			callback: function(success, id, record, message) {
				if(success == true){
					onRefresh()
				}
			}
		})
	}

	function showDataNasabah() {
		id = $('#pengajuan_anggota').val();
		if(id){
			$.ajax({
				url: BASE_URL+'anggota/select',
				data:{
					anggota_id: id,
				},
				type: 'POST',
				success : function (res) {
					if(res.data[0].anggota_is_proteksi==1){
						HELPER.showMessage({
			                success: false,
			                message: 'Anggota terproteksi! Tidak dapat meminjam',
			                title: 'Informasi',
						})
						$('#pengajuan_anggota').val("").trigger('change');
					}else{
						$.each(res.data[0], function(i, v){
							$('#'+i).val(v);
						})
						$('#pengajuan_telp').val(res.data[0].anggota_telp);
						hitung_sisa_hutang(id);

					}
					// $('#grup_gaji_nama').val(res.data[0].kelompok_anggota_nama+" - "+res.data[0].grup_gaji_nama)
				}
			})
		}
	}

	function hitung_sisa_hutang(id) {
		$.ajax({
			url: BASE_URL+'pengajuanpinjaman/selectAll',
			data:{
				pengajuan_anggota	: id,
				pengajuan_status 	: 2,
				// pengajuan_aktif	: 1
			},
			type: 'POST',
			success : function (res) {
				$('#pengajuan_sisa_pinjaman_kpri').val(res);
			}
		})
	}

	function perhitunganAngsuran() {
		jml_pinjaman = $('#pengajuan_jumlah_pinjaman').val();
		tenor = $('#pengajuan_tenor').val();
		$.ajax({
			url : BASE_URL+'pengajuanpinjaman/select_kredit',
			data:{
				jml_pinjaman: jml_pinjaman,
				tenor : tenor
			},
			type: 'POST',
			success: function (resp){
				arr_proteksi = [];
				total = resp.proteksi.length;
				if(total == 0){
					swal.fire({
	                    title: 'Information',
	                    text: "Cek periode pinjaman dan jumlah pinjaman !",
	                    type: 'warning',
	                    confirmButtonText: '<i class="fa fa-check"></i> Ok',
	                    confirmButtonClass: 'btn btn-focus btn-success m-btn m-btn--pill m-btn--air',    
	                }).then(function(result) {
	                   $('#pengajuan_tenor').val('');
	                   $('#pengajuan_jumlah_pinjaman').val('');
	                });
				}else{
					proteksi = resp.proteksi[0]['pengaturan_kredit_proteksi'];
					jasa = resp.jasa;
					jasa_round = 0;
					jml_jasa = parseInt(jml_pinjaman*jasa/100);
					angsuran_pokok = parseInt(jml_pinjaman/tenor);
					if((angsuran_pokok).toString().substr(-1)==0){
						if((angsuran_pokok).toString().substr(-2)>0){
							angsuran_round = parseInt(angsuran_pokok+500-angsuran_pokok.toString().substr(-3));
						}else angsuran_round = angsuran_pokok;
					}
					else if((angsuran_pokok).toString().substr(-3)<500 && (angsuran_pokok).toString().substr(-1)>0){
						angsuran_round = parseInt(angsuran_pokok+500-angsuran_pokok.toString().substr(-3));
					}else if((angsuran_pokok).toString().substr(-3)>500){
						angsuran_round = Math.round(angsuran_pokok/1000)*1000;
					}
					if((jml_jasa).toString().substr(-1)==0){
						if((jml_jasa).toString().substr(-2)>0 || (jml_jasa).toString().substr(-3)>1){
							jasa_round = parseInt(jml_jasa+500-jml_jasa.toString().substr(-3));
						}else{
							jasa_round = jml_jasa;
						}
					}
					else if((jml_jasa).toString().substr(-3)<500 && (jml_jasa).toString().substr(-1)>0){
						jasa_round = parseInt(jml_jasa+500-jml_jasa.toString().substr(-3));
					}else if((jml_jasa).toString().substr(-3)>500){
						jasa_round = (Math.round(jml_jasa/1000)*1000);
					}
					console.log(angsuran_round);
					console.log(jasa_round);
					$('#pengajuan_pokok_bulanan').val(angsuran_round);
					// $('#pengajuan_jasa_bulanan').val(Math.round(jml_jasa/100)*100);
					$('#pengajuan_jasa_bulanan').val(jasa_round);
					$('#pengajuan_jasa').val(jasa);
					jenis = $('#pengajuan_jenis').val();
					$('#pengajuan_proteksi').val(proteksi);
					$('#pengajuan_proteksi_nilai').val(proteksi/100*jml_pinjaman);
				}
			}
		})
	}

	function loadPrint(data){
		HELPER.block();
		$.ajax({
			url: BASE_URL+'pengajuanpinjaman/print',
			type : "POST",
			dataType: 'json',
			data: data,
			success: function (resp) {
				$('.form_data2').show();
				$('.table_data').hide();
				$("#pdf-laporan object").attr("data", resp.record);
				HELPER.unblock();
			}
		})
	}

	function onPrint(){
		HELPER.getDataFromTable({
			table: 'table-pengajuan',
			callback: function(res) {
				loadPrint(res);
			}
		})
	}
</script>