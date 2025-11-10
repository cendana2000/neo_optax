<script>
    $(() => {
        HELPER.fields = [
            'toko_id',
            'toko_status',
        ];
        HELPER.api = {
            table: BASE_URL + 'registrasitoko/',
            read: BASE_URL + 'registrasitoko/read',
            store: BASE_URL + 'registrasitoko/store',
            update: BASE_URL + 'registrasitoko/update',
            destroy: BASE_URL + 'registrasitoko/destroy',
        }
        Toko();
    });

    function Toko() {
        HELPER.block();
        $.ajax({
            url: BASE_URL + 'registrasitoko/read',
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
                    else if (res.toko_status == 3) fstatus = '<span class="label label-info label-inline mr-2">Ditolak</span>';
                    else if (res.toko_status == 2) fstatus = '<span class="label label-success label-inline mr-2">DiTerima</span>';
                }
                $('.status-toko').html(fstatus);
                if (res.toko_wajibpajak_id) {
                    $('.card-footer').hide()
                    $('#label-changeimage').attr('onchange', 'onChangeImageUpdate(this)');
                    HELPER.toggleForm({
                        toshow: "form_data",
                        tohide: "table_data",
                    });
                }
                if(res.toko_logo){
                    $('.show-mitra-image').css('background-image', `url(${BASE_URL_NO_INDEX + res.toko_logo})`);
                }
                HELPER.unblock();
            }
        })
    }

    function showToko() {
        HELPER.block();
        $.ajax({
            url: BASE_URL + 'registrasitoko/read',
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
                    else if (res.toko_status == 3) fstatus = '<span class="label label-info label-inline mr-2">Ditolak</span>';
                    else if (res.toko_status == 2) fstatus = '<span class="label label-info label-inline mr-2">DiTerima</span>';
                }
                $('.status-toko').html(fstatus);
                $('#footer-insert').show();
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
            data: new FormData($('#form-toko')[0]),
            confirm: true,
            contentType: false,
            processData: false,
            cache: false,
            type: 'post',
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

    function handleUpdate(){
        var formData = new FormData();
        formData.append('logo_toko', $('#logo_toko').prop('files')[0]);
        formData.append('toko_id', $('#toko_id').val());
        
        HELPER.confirm({
            message: 'Apakah anda yakin ingin mengubah informasi eToko?',
            callback: function(suc) {
                if (suc) {
                $.ajax({
                    data: formData,
                    url: HELPER.api.update,
                    confirm: true,
                    contentType: false,
                    processData: false,
                    cache: false,
                    type: 'post',
                    complete: function(res) {
                    var result = res.responseJSON
                    if (result.success) {
                        HELPER.showMessage({
                            success: true,
                            title: 'Success',
                            message: 'Berhasil mengubah info wajib pajak'
                        });
                    } else {
                        HELPER.showMessage({
                            success: 'info',
                            title: 'Stop',
                            message: res.message
                        });
                    }
                    Toko();
                    HELPER.unblock(100);
                    }
                })
                }
            }
        })
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            // $('#blah').attr('src', e.target.result);
            $('.show-mitra-image').css('background-image', 'url(' + e.target.result + ')');
        }

        reader.readAsDataURL(input.files[0]);
        }
    }

    function onChangeImage(el) {
        readURL($('#logo_toko')[0]);
    }

    function onChangeImageUpdate(el) {
        readURL($('#logo_toko')[0]);
        $('#footer-update').show()
    }

    function onRemoveImage(el) {
        if ($('#logo_toko').val()) {
            $($('#logo_toko').val(''));
            Toko();
        }
        $('#footer-update').hide()
    }

    function onBack(){
        $('#footer-insert').hide();
        HELPER.toggleForm({
            tohide: "form_data",
            toshow: "table_data",
        })
    }
</script>