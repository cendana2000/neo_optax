<script>
    $(() => {
        HELPER.fields = [
            'toko_id',
            'toko_status',
        ];
        HELPER.api = {
            table: BASE_URL + 'toko/',
            read: BASE_URL + 'toko/read',
            store: BASE_URL + 'toko/store',
            update: BASE_URL + 'toko/update',
            destroy: BASE_URL + 'toko/destroy',
        }
        Toko();
    });

    function Toko() {
        HELPER.block();
        $.ajax({
            url: BASE_URL + 'toko/read',
            type: 'post',
            success: (res) => {
                $.each(res, (i, v) => {
                    $('#' + i).val(v);
                })
                $('#toko_wajibpajak_id').val(res.wajibpajak_id);

                if (!res.toko_id) {
                    fstatus = '<p class="pt-3">-</p>';
                } else {
                    if (res.toko_status == 1) fstatus = '<span class="label label-info label-inline mr-2">In process</span>';
                    else if (res.toko_status == 2) fstatus = '<span class="label label-info label-inline mr-2">Ditolak</span>';
                    else if (res.toko_status == 3) fstatus = '<span class="label label-info label-inline mr-2">DiTerima</span>';
                }
                $('.status-toko').html(fstatus);
                if (res.toko_wajibpajak_id) {
                    $('.card-footer').hide()
                    HELPER.toggleForm({
                        toshow: "form_data",
                        tohide: "table_data",
                    })
                }
                HELPER.unblock();
            }
        })
    }

    function showToko() {
        HELPER.block();
        $.ajax({
            url: BASE_URL + 'toko/read',
            type: 'post',
            success: (res) => {
                $.each(res, (i, v) => {
                    $('#' + i).val(v);
                })
                $('#toko_wajibpajak_id').val(res.wajibpajak_id);
                $('#jenis_nama').val(res.jenis_nama);

                if (!res.toko_id) {
                    fstatus = '<p class="pt-3">-</p>';
                } else {
                    if (res.toko_status == 1) fstatus = '<span class="label label-info label-inline mr-2">In process</span>';
                    else if (res.toko_status == 2) fstatus = '<span class="label label-info label-inline mr-2">Ditolak</span>';
                    else if (res.toko_status == 3) fstatus = '<span class="label label-info label-inline mr-2">DiTerima</span>';
                }
                $('.status-toko').html(fstatus);
                HELPER.toggleForm({
                    toshow: "form_data",
                    tohide: "table_data",
                })
                HELPER.unblock();
            }
        })
    }

    function save() {
        HELPER.save({
            form: 'form-toko',
            confirm: true,
            callback: function(success, id, record, message) {
                if (success === true) {
                    $('.menu-item-active>a').click();
                    // HELPER.refresh({
                    // 	table: 'table-supplier'
                    // });
                    // $('#triggerReset').trigger("click");
                }
            }
        })
    }
</script>