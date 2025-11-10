<script type="text/javascript">	
	$(function() {
		row = 1;
		$('.disc').number(true, 2);
		$('.number').number(true);
		//ip address
        /*$(".kasir_ip").inputmask({
            "mask": "9{1,3}.9{1,3}.9{1,3}.9{1,3}"
        });*/  
        	
		HELPER.fields = [
			'konfigurasi_id',
			'konfigurasi_jasa_simpanan',
			'konfigurasi_jasa_pinjaman',
			'konfigurasi_gudang_id',
			'konfigurasi_perusahaan_nama',
			'konfigurasi_perusahaan_alamat',
			'konfigurasi_perusahaan_telp',
			'konfigurasi_updated',
		];

		HELPER.setRequired([
			'konfigurasi_perusahaan_nama',
			'konfigurasi_gudang_id',
			'konfigurasi_jasa_simpanan',
			'konfigurasi_jasa_pinjaman',
		]);

		HELPER.api = {
			read 	: BASE_URL + 'konfigurasi/',
			update 	: BASE_URL + 'konfigurasi/update',
			store 	: BASE_URL + 'konfigurasi/store',
		}
		$('.disc').number(true, 2);
		HELPER.createCombo({
            el: 'konfigurasi_gudang_id',
            valueField: 'gudang_id',			
            displayField: 'gudang_nama',			
            url: BASE_URL+ 'gudang/select',
            callback: function() {
            	edit();
            }
		})
		n = 1;

	});
	function addKasir() {
		html = `<tr id="kasir_`+n+`">
				<td><input type="text" class="form-control kasir_ip" id="kasir_ip_`+n+`" name="kasir_ip[`+n+`]"></td>
				<td><input type="text" class="form-control" id="kasir_nama_`+n+`" name="kasir_nama[`+n+`]"></td>
				<td><input type="text" class="form-control" id="kasir_kode_`+n+`" name="kasir_kode[`+n+`]"></td>
				<td><a href="javascript:;" data-id="`+n+`" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" onclick="remRow(this)" title="Hapus">
                                <span class="la la-trash"></span> Hapus</a></td>
			</tr>`;
		$('#table-kasir').append(html);		
        /*$(".kasir_ip").inputmask({
            "mask": "9{1,3}.9{1,3}.9{1,3}.9{1,3}"
        });*/  
		n++;
	}
	function remRow(el) {
		id = $(el).data('id');
		$('tr#kasir_'+id).remove();
		if($('#table-kasir tbody tr').length == 0) addKasir();
	}
	function edit() {
		$.ajax({
			url 	: HELPER.api.read,
			type 	: 'post',
			success : function(res) {
				$.each(res, function(i, v) {
					if ($("[name="+ i +"]").find("option:selected").length)
                    {
                        if ($('[name="'+ i +'"]').hasClass('select2-hidden-accessible')) {
                            $('[name="'+ i +'"]').val(v).trigger('change');
                        }
                    }else{
                        $('[name="'+ i +'"]').val(v).trigger('change');
                    }
				})
				if(res.kasir){
					$.each(res.kasir, function(i, v) {
                        $('#kasir_id_'+ i).val(v.kasir_id);
                        $('#kasir_ip_'+ i).val(v.kasir_ip).trigger('change');
                        $('#kasir_nama_'+ i).val(v.kasir_nama);
                        $('#kasir_kode_'+ i).val(v.kasir_kode);						
                        addKasir();
					});
				}
				$('.kasir_ip').mask('099.099.099.099');
			}
		})
	}

	function save() {
		HELPER.save({
			form: 'form-konfigurasi',
			confirm: true,
			callback: function(success, id, record, message, res) {
				HELPER.back({});
			}
		})
	}
</script>