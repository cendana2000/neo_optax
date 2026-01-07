<script>
    var avatar5 = new KTImageInput('journey_attachment');

    $(function() {
        HELPER.api = {
            table: BASE_URL + 'journey',
            read: BASE_URL + 'journey/read',
            update: BASE_URL + 'journey/update'
        }

        $(".sort-by-status .nav-link").click(function(e) {
            $(".sort-by-status .nav-link").removeClass("active");
            $(this).addClass("active");
            loadTable();
        });

        // $(".datepicker").datepicker({
        //     format: "yyyy-mm-dd"
        // })

        loadTable()
    })

    function onDetail(id) {
        HELPER.ajax({
            url: HELPER.api.read,
            data: {
                id: id
            },
            complete: function(res) {
                $('#modal-detail').find('[data-value="journey_tgl_survey"]').text(res.journey_tgl_survey || '-')
                $('#modal-detail').find('[data-value="journey_pegawai"]').text(res.pegawai_nama || '-')
                $('#modal-detail').find('[data-value="journey_identifikasi_masalah"]').text(res.journey_identifikasi_masalah || '-')
                $('#modal-detail').find('[data-value="journey_penyelesaian"]').text(res.journey_penyelesaian || '-')
                $('#modal-detail').find('[data-value="journey_hasil"]').text(res.journey_hasil || '-')
                $('#modal-detail').find('[data-value="journey_catatan"]').text(res.journey_catatan || '-')

                var att = './assets/media/noimage.png';
                if (res.journey_attachment) {
                    att = `${BASE_CONTENT}/journey/${res.journey_attachment}`;
                }
                $('#modal-detail').find('[data-value="journey_attachment"]').html(`<img draggable="false" width="256" src="${att}"/>`)

                $('#modal-detail').modal('show');
            }
        })
    }

    function onSelesaikan(id) {
        $('#journey_id').val(id);
        $('#modal-selesaikan').modal('show');
    }

    function loadTable() {
        var mstatus = {
            'pending': '<span class="label label-inline label-warning">Pending</span>',
            'selesai': '<span class="label label-inline label-success">Selesai</span>',
        }

        const is_act_visible = '<?= $this->session->userdata('pegawai_role_access_id') === '123' ?>';

        HELPER.initTable({
            el: 'table-journey',
            url: HELPER.api.table,
            searchAble: true,
            destroyAble: true,
            responsive: false,
            data: {
                filter_status: $(".sort-by-status .nav-link.active").attr("data")
            },
            columnDefs: [{
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_nama'];
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_alamat'];
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta) {
                        return full['journey_trigger_action'];
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return full['journey_identifikasi_masalah'];
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return mstatus[full['journey_status']];
                    }
                },
                {
                    targets: 6,
                    orderable: false,
                    visible: is_act_visible == 1,
                    render: function(data, type, full, meta) {
                        let button = '';

                        if (full['journey_status'] == 'pending') {
                            button += `<a class="dropdown-item" href="#" onclick="onSelesaikan('${full['journey_id']}')">Selesaikan</a>`;
                        } else {
                            button += `<a class="dropdown-item" href="#" onclick="onDetail('${full['journey_id']}')">Detail</a>`;
                        }

                        let btn_aksi = `
                            <div class="dropdown dropdown-inline mr-4">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Opsi
                                </button>
                                <div class="dropdown-menu">
                                    ${button}
                                </div>
                            </div>
                        `;

                        return btn_aksi;
                    }
                }
            ]
        })
    }

    function save(id) {
        var form = $('#' + id)[0];
        var formData = new FormData(form);

        HELPER.save({
            cache: false,
            data: formData,
            contentType: false,
            processData: false,
            form: 'user_app-form',
            confirm: true,
            url: HELPER.api.update,
            callback: function(success, id, record, message) {
                if (success) {
                    HELPER.showMessage({
                        success: true,
                        title: "Success",
                        message: "Successfully saved data"
                    });

                    $("#modal-selesaikan").modal('hide')
                    loadTable()
                } else {
                    HELPER.showMessage({
                        success: false
                    })
                }
                HELPER.unblock(100);
            },
            oncancel: function(result) {
                HELPER.unblock(100);
            }
        });
    }
</script>