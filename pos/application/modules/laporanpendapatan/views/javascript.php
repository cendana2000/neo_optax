<script type="text/javascript">
    $(function() {
        $('.bulan').css('display', 'none');
        $('.supplier').css('display', 'none');
        /*$('#tanggal').datepicker({
	      	dateFormat: 'dd/mm/yyyy'
	    }); */
        /*$('#tanggal').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
        });*/

        $(".monthpicker").datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months"
        });
    })

    function jenisLaporan() {
        let laporan = $('#laporan').val();

        if (laporan == '_supplier') {
            $('.supplier').show(100);
            $('#supplierForm').show(100);

            HELPER.createCombo({
                el: 'supplier_id',
                url: BASE_URL + 'supplier/select',
                valueField: 'supplier_id',
                displayField: 'supplier_kode',
                displayField2: 'supplier_nama',
                grouped: true
            });
        } else if (laporan == '_rekap' || laporan == '') {
            $('.supplier').hide(100);
            $('#supplierForm').hide(100);
        }
    }

    function getJenis(el) {
        jenis = $(el).val();
        if (jenis == '_supplier') {
            $('.supplier').removeAttr('style');
            HELPER.createCombo({
                el: 'supplier_id',
                url: BASE_URL + 'supplier/select',
                valueField: 'supplier_id',
                displayField: 'supplier_kode',
                displayField2: 'supplier_nama',
                grouped: true
            });
        } else {
            $('.supplier').css('display', 'none');
        }

        jenisLaporan();
    }

    function setPeriode(el) {
        period = $(el).val();
        $('.bulan, .tanggal').css('display', 'none');
        if (period == 'tanggal') $('.tanggal').css('display', 'block');
        else $('.bulan').css('display', 'block');

    }

    function getLaporan() {
        HELPER.block();
        $.ajax({
            url: BASE_URL + 'laporanpendapatan/get_laporan',
            data: $('#lap-pembelian').serializeObject(),
            type: 'post',
            dataType: 'json',
            success: function(res) {
                $('.kt-laporan').show();
                $("#pdf-laporan object").attr("data", res.record);
                HELPER.unblock();
            }
        })
    }

    function getLaporanExcel() {
		event.preventDefault();
		HELPER.block();
        $.ajax({
            url: BASE_URL + '/laporanpendapatan/spreadsheet_laporan',
            type: 'post',
            data: $('#lap-pembelian').serializeObject(),
            dataType: 'JSON',
            success: function(res) {
                if (res.success) {
                    let fileLocation = BASE_ASSETS + 'laporan/laporan_pendapatan/' + res.file;
                    window.location.href = fileLocation;
                }
            },
            complete: function(res) {
                HELPER.unblock();
            }
        })
	}
</script>

