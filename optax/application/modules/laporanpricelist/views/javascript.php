<style type="text/css">
	.noBorders {
		width: 100%;

	}

	.noBorders td {
		padding: 30px;
	}
</style>
<script type="text/javascript">
	$(function() {
		HELPER.api = {
			get_parent: BASE_URL + 'laporanpricelist/go_tree',
			ajax_toko: BASE_URL + 'laporanpricelist/select_wp',
		}
    // $('.select2').select2();

    HELPER.ajaxCombo({
			el: '#select_toko',
			url: HELPER.api.ajax_toko
		});

		// init_table();
		$(".barang").hide();
		$(".kategori").hide();
		$("#cetak").hide();
	});

	function init_table(code_store) {
		$('.view_table').show();
		$('.print_table').hide();
		if ($.fn.DataTable.isDataTable('#table-pricelist')) {
			$('#table-pricelist').DataTable().destroy();
		}
		$("#cetak").show();
		dt = $('#pricelist-form').serializeObject();

		var tanggallengkap = new String();
		var namahari = ("Minggu Senin Selasa Rabu Kamis Jumat Sabtu");
		namahari = namahari.split(" ");
		var namabulan = ("Januari Februari Maret April Mei Juni Juli Agustus September Oktober November Desember");
		namabulan = namabulan.split(" ");
		var tgl = new Date();
		var hari = tgl.getDay();
		var tanggal = tgl.getDate();
		var bulan = tgl.getMonth();
		var tahun = tgl.getFullYear();
		tanggallengkap = "Tanggal Cetak : " + namahari[hari] + ", " + tanggal + " " + namabulan[bulan] + " " + tahun;
		tangggal = "<p style='font-size:16px'>Tanggal Cetak : " + namahari[hari] + ", " + tanggal + " " + namabulan[bulan] + " " + tahun + "</p>";
		// var nama = "<p style='font-size:40px;'></p>"
		var judul = ("<center>UKM MART EKO KAPTI</center>");
		var table = $('#table-pricelist').DataTable({
			responsive: true,
			select: 'multi',
			processing: true,
			// serverSide: true,
			ajax: {
				url: BASE_URL + 'laporanpricelist/',
				type: 'post',
				data: {
					filter: dt,
          codestore: code_store
				}
			},
			columnDefs: [{
					defaultContent: "-",
					targets: "_all"
				},{
					targets: 0,
					orderable: false
				},{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['barang_kode'];
					},
				},{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['barang_nama'];
					},
				},{
					targets: 3,
					render: function(data, type, full, meta) {
						return full['kategori_barang_nama'];
					},
				},{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['barang_satuan_kode'];
					},
				},{
					targets: 5,
					render: function(data, type, full, meta) {
						return $.number(full['barang_harga']);
					},
				},{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['barang_satuan_opt_kode'];
					},
				},{
					targets: 7,
					render: function(data, type, full, meta) {
						return $.number(full['barang_harga_opt']);
					},
				},{
					targets: 8,
					render: function(data, type, full, meta) {
						return full['barang_satuan_opt2_kode'];
					},
				},{
					targets: 9,
					render: function(data, type, full, meta) {
						return $.number(full['barang_harga_opt2']);
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

	function print_table(codestore) {
		HELPER.block();
		$.ajax({
			url: BASE_URL + 'laporanpricelist/tprint' + '/' + codestore,
			data: $('#pricelist-form').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('.view_table').hide()
				$('.print_table').show()
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function ganti(el) {
		pilih = $(el).val();
		if (pilih == "kategori") {
			$(".barang").hide();
			$(".kategori").show();
			$("#cetak").hide();
			$("#barang_id").prop("disabled", true);
			$("#barang_kategori_barang").prop("disabled", false);
		} else if (pilih == "barang") {
			$(".kategori").hide();
			$(".barang").show();
			$("#cetak").hide();
			$("#barang_kategori_barang").prop("disabled", true);
			$("#barang_id").prop("disabled", false);
		} else if (pilih == "semua") {
			$(".barang").hide();
			$(".kategori").hide();
			$("#cetak").hide();
			$("#barang_kategori_barang").prop("disabled", true);
			$("#barang_id").prop("disabled", true);

		}
	}

	function lawas() {
		/*dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
			buttons: [
			  	{
			      	extend: 'print',
			      	title :judul,
			      	messageTop : tangggal,
			  	},
			  	{
	                extend: 'excelHtml5',
	                title: 'UKM MART EKO KAPTI',
	                messageTop: tanggallengkap,
	                customize: function ( xlsx ){
	                	console.log(xlsx)
		                var sheet = xlsx.xl.worksheets['sheet1.xml'];
		                $('row c[r*="A2"]', sheet).attr( 's', '25' );    
	            	}
	            },
	            {
			      	extend: 'pdf',
			      	title: 'UKM MART EKO KAPTI',
			      	messageTop: tanggallengkap ,
		         	customize: function (doc) {
		         		console.log(doc)
					    doc.defaultStyle.fontSize = 7.5;
					    doc.styles.tableHeader.fontSize = 8;
					    doc.styles.title.fontSize = 10;
					    doc.content[0].text = doc.content[0].text.trim();
					    var objLayout = {};
					    // Horizontal line thickness
					    objLayout['hLineWidth'] = function(i) { return .5; };
					    // Vertikal line thickness
					    objLayout['vLineWidth'] = function(i) { return .5; };
					    // Horizontal line color
					    objLayout['hLineColor'] = function(i) { return '#aaa'; };
					    // Vertical line color
					    objLayout['vLineColor'] = function(i) { return '#aaa'; };
					    // Left padding of the cell
					    objLayout['paddingLeft'] = function(i) { return 7; };
					    objLayout['paddingTop'] = function(i) { return 7; };
					    // Right padding of the cell
					    objLayout['paddingRight'] = function(i) { return 7; };
					    objLayout['paddingBottom'] = function(i) { return 7; };
					    // Inject the object in the document
					    doc.content[2].layout = objLayout;
					    // console.log(Object.keys(doc.content[2].table).length);
					    // console.log(doc.content[2].table.widths);
					    // doc.content[2].table.widths[3].width = 100;
					    doc.content[2].table.widths=["3%","15%","23%","17%","7%","7%","7%","7%","7%","7%"];
					}
		      	},
			],*/
	}

  function onChangeToko(el){
    var val = $(el).val();

    if(val){
      HELPER.create_combo_akun({
        el: 'barang_kategori_barang',
        valueField: 'id',
        displayField: 'text',
        parentField: 'parent',
        childField: 'child',
        url: HELPER.api.get_parent + '/' + val,
        withNull: false,
        nesting: true,
        chosen: false,
      });

      HELPER.ajaxCombo({
        el: '#barang_id',
        url: BASE_URL + 'laporanpricelist/select_ajax' + '/' + val,
      });

      $('#pricelist-form').attr('action', `javascript:init_table('${val}')`);
      $('#btn-prosess').attr('onclick', `init_table('${val}')`);
      $('#kt_print').attr('onclick', `print_table('${val}')`);

      $('#next-action').show();
    }else{
      $('#next-action').hide();
    }
  }
</script>