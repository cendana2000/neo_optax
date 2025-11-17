<script src="<?= base_url('assets/plugin/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugin/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugin/sweetalert/sweetalert2.all.min.js') ?>"></script>
<script>
    var isMobile = false;
    var dataTable = null;
    var edit_array = [];
    var defaultLanguage = {
        "emptyTable": "Tidak ada data tersedia didalam tabel",
        "zeroRecords": "Maaf, Tidak ada data ditemukan",
        "info": "<span class='total-data'>_MAX_ data</span> - _PAGE_ dari _PAGES_ halaman",
        "infoEmpty": "Tidak ada data tersedia",
        "lengthMenu": "Jumlah Data Ditampilkan _MENU_",
        "loadingRecords": "<i class='fa-solid fa-circle-notch fa-spin'></i> Memuat Data...",
        "processing": "<i class='fa-solid fa-circle-notch fa-spin'></i> Memproses Data...",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Next",
            "previous": "Prev"
        },
        // "infoFiltered": "(disaring dari _MAX_ total data)",
        "search": "<span class='col-12 me-3'><i class=\"fa-solid fa-magnifying-glass\"></i> CARI</span>",
    };

    var responsiveLanguage = {
        "emptyTable": "Tidak ada data tersedia didalam tabel",
        "zeroRecords": "Maaf, Tidak ada data ditemukan",
        "info": "<span class='total-data'>_MAX_ data</span> - _PAGE_ dari _PAGES_ halaman",
        "infoEmpty": "Tidak ada data tersedia",
        "lengthMenu": "Jumlah Data Ditampilkan _MENU_",
        "loadingRecords": "<i class='fa-solid fa-circle-notch fa-spin'></i> Memuat Data...",
        "processing": "<i class='fa-solid fa-circle-notch fa-spin'></i> Memproses Data...",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Next",
            "previous": "Prev"
        },
        "infoFiltered": "(disaring dari _MAX_ total data)",
        "search": "<div class='col-12'><i class=\"fa-solid fa-magnifying-glass\"></i> CARI</div>",
    };

    var background_url = "";

    $(document).ready(function(e) {
        ismobile();

        function ismobile() {
            if (window.innerWidth < 798) {
                isMobile = true;
                return true;
            } else {
                return false;
            }
        }

        var currentInnerWidth = window.innerWidth;
        window.onresize = (function(e) {
            if (ismobile() || window.innerWidth < 798) {
                isMobile = true;
            } else {
                isMobile = false;
            }
            if (currentInnerWidth != window.innerWidth) {
                reinitDatatable();
                currentInnerWidth = window.innerWidth;
            }
        });

        $("#npwpd").focusout(function(e) {
            get_nama_wp_by_npwpd();
        });

        $("#npwpd").keyup(function(e) {
            if (e.keyCode == 13) {
                get_nama_wp_by_npwpd();
            }
        });

        $("#need_token").change(function(e) {
            if ($(this).prop("checked")) {
                $(".form-check-label[for=need_token]").html("Ya");
                $(".form-tambahan").show();
            } else {
                $(".form-check-label[for=need_token]").html("Tidak");
                $(".form-tambahan").hide();
            }
        });

        $(".btn-simpan-setting").on("click", function(e) {
            $(".form-setting").addClass("was-validated");
            if ($("#nama_setting").val() == "") {
                return false;
            }

            if ($("#npwpd").val() == "") {
                return false;
            }

            if ($("#nama_wp").val() == "") {
                return false;
            }

            if ($("#link_curl").val() == "") {
                return false;
            }

            data = {
                id_setting: $("#id_setting").val(),
                nama_setting: $("#nama_setting").val(),
                npwpd: $("#npwpd").val(),
                nama_wp: $("#nama_wp").val(),
                method: $("#method").val(),
                link_curl: $("#link_curl").val(),
                curlopt_header: $("#curlopt_header").val(),
                preset_curl: $("#preset_curl").val(),

                need_token: ($("#need_token").prop("checked") ? "1" : "0"),
                token_req_url: $("#token_req_url").val(),
                token_req_payload: $("#token_req_payload").val(),
                token_req_method: $("#token_req_method").val(),

                mode_simpan: $("#id_setting").val() == "" ? "simpan" : "update"
            }

            simpan_setting(data);
        });

        <?php if (@$load_data) : ?>
            load_table();
        <?php endif ?>

    });

    function get_nama_wp_by_npwpd() {
        $.ajax({
            url: "<?= base_url('admin/get_nama_wp_by_npwpd') ?>",
            method: "POST",
            data: {
                "npwpd": $("#npwpd").val()
            },
            dataType: "json",
            success: function(e) {
                if (e.status) {
                    $("#nama_wp").val(e.data.nama_wp);
                }
            },
            error: function(e) {}
        });
    }

    function reinitDatatable() {
        $('#dataTable').DataTable().clear().destroy();
        load_table();
    }

    function load_table() {
        var pagination = (isMobile) ? [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ] : [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ]
        var dataTable = $('#dataTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            processing: true,
            serverSide: true,
            "dom": "<'row'<'col-sm-12'ftr>><'row table-navigation'<'col-sm-12 col-md-5 dataTables_info'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
            order: [
                [4, 'desc']
            ],
            // serverSide: true,
            ajax: {
                url: "<?= base_url('admin/get_all_daftar_setting') ?>",
                type: 'POST',
                "data": function(outData, d) {
                    // yang dikirim dari server
                    return outData;
                },
                dataFilter: function(inData, d) {
                    // di kirim dari server jika tidak error
                    $.fn.DataTable.ext.pager.numbers_length = 5;
                    loadState = false;
                    return inData;
                },
                error: function(err, status) {
                    // $('#dataTable').DataTable().clear().destroy();
                    // dataTableDaftarUserTemplateFunction();
                },
                data: function(data) {
                    var page = (data.start + data.length) / data.length;
                    data.page = page;
                    data.rows = data.length;
                    data.sord = $(".sorting_desc, .sorting_asc").attr("data");
                },
                "dataSrc": "rows"
            },
            "drawCallback": function(settings) {
                var page_info = dataTable.page.info();
                page = page_info.page;
                length = page_info.length;
            },
            columnDefs: [{
                    //KOLOM NOMOR BARIS
                    targets: 0,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true,
                },
                {
                    //KOLOM NAMA PENGATURAN
                    targets: 1,
                    render: function(data, type, row, meta) {
                        return row.nama_setting;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true
                },
                {
                    //KOLOM NPWPD
                    targets: 2,
                    render: function(data, type, row, meta) {
                        return row.npwpd;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true
                },
                {
                    //KOLOM NAMA WP
                    targets: 3,
                    render: function(data, type, row, meta) {
                        return row.nama_wp;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true
                },
                {
                    //KOLOM PRESET CURL
                    targets: 4,
                    render: function(data, type, row, meta) {
                        return row.preset_curl;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true
                },
                {
                    //KOLOM AKSI
                    targets: 5,
                    render: function(data, type, row, meta) {
                        var button = "";
                        edit_array[row.id_setting] = {
                            "id_setting": row.id_setting,
                            "nama_setting": row.nama_setting,
                            "npwpd": row.npwpd,
                            "nama_wp": row.nama_wp,
                            "method": row.method,
                            "link_curl": row.link_curl,
                            "curlopt_header": row.curlopt_header,
                            "preset_curl": row.preset_curl,
                            "need_token": row.need_token,
                            "token_req_url": row.token_req_url,
                            "token_req_payload": row.token_req_payload,
                            "token_req_method": row.token_req_method,
                        }
                        button = `
                            <button type="button" class="btn btn-sm btn-info" onclick="edit_setting(` + row.id_setting + `)"><i class="fas fa-edit"></i> Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="hapus_setting(` + row.id_setting + `)"><i class="fas fa-trash"></i> Hapus</button>
                        `;
                        return button;
                    },
                    searchable: true,
                    visible: (isMobile) ? false : true,
                    orderable: false
                },
                {
                    //KOLOM RESPONSIVE
                    targets: 6,
                    render: function(data, type, row, meta) {
                        // Create a Date object from the input string
                        var date = new Date(row.tanggal_bonbil_upload);

                        // Get the year, month, and day components
                        var year = date.getFullYear();
                        var month = String(date.getMonth() + 1).padStart(2, '0'); // Add 1 to month because it's zero-based
                        var day = String(date.getDate()).padStart(2, '0');

                        // Create the output date string in "Y-m-d" format
                        var outputDateString = year + "-" + month + "-" + day;

                        var status_validasi = "";
                        if (row.is_valid == "0") {
                            status_validasi = "<span class='badge bg-warning'>Menunggu Validasi</span>";
                        } else if (row.is_valid == "1") {
                            status_validasi = "<span class='badge bg-success'>Valid</span>";
                        } else {
                            status_validasi = "<span class='badge bg-danger'>Ditolak</span>";
                        }

                        var button = "";
                        if (row.is_valid == "0") {
                            button = `<button type="button" class="btn btn-sm btn-primary" onclick="validation_modal_show(` + row.id_bonbil + `)"><i class="fa-solid fa-search"></i> Validasi</button>`;
                        } else {
                            button = `<button type="button" class="btn btn-sm btn-info" onclick="validation_modal_show(` + row.id_bonbil + `)"><i class="fa-solid fa-info"></i> Detail</button>`;
                        }

                        var html = `
                                <div class="row mb-1">
                                    <div class="col-4">Nama</div>
                                    <div class="col-8">` + row.username + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">Alamat</div>
                                    <div class="col-8">` + row.alamat + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">No. Telp</div>
                                    <div class="col-8">` + row.no_telp + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">Tanggal Req</div>
                                    <div class="col-8">` + outputDateString + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">No Undian</div>
                                    <div class="col-8">` + ((row.no_undian == null || row.no_undian == "") ? "-" : row.no_undian) + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">Status</div>
                                    <div class="col-8">` + status_validasi + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">Action</div>
                                    <div class="col-8">
                                        ` + button + `
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapus_bonbil(` + row.id_bonbil + `)"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            `;
                        return html;
                    },
                    searchable: true,
                    visible: (isMobile) ? true : false,
                    orderable: false
                }
            ],
            responsive: true,
            initComplete: function(e, json) {
                var menunggu = json.menunggu;
                if (menunggu > 0) {
                    $(".total-antrian").html(menunggu);
                }
                $("#dataTable_filter input[type=search]").off();
                $("#dataTable_filter input[type=search]").keyup(function(e) {
                    if (e.keyCode == 13) {
                        dataTable.search($(this).val()).draw();
                    }
                });
                this.api().columns().every(function() {
                    $.fn.DataTable.ext.pager.numbers_length = 9;
                });
            },
            "lengthMenu": pagination,
            "language": (isMobile) ? responsiveLanguage : defaultLanguage,
        });
    }

    $(".btn-refresh").click(function(e) {
        reinitDatatable();
    });

    //fungsi untuk membersihkan isian form ketika tombol reset diklik
    function clearForm() {
        //mengosongkan inputan
        $("#id_setting").val("");
        $("#nama_setting").val("");
        $("#npwpd").val("");
        $("#nama_wp").val("");
        $("#link_curl").val("");
        $("#curlopt_header").val("");

        $("#preset_curl").val("MAJOO");
        $("#need_token").prop("checked", true);
        $("#token_req_url").val("");
        $("#token_req_payload").val("");
        $("#token_req_method").val("GET");

        //memfocuskan cursor ke inputan nama setting
        $("#nama_setting").focus();

        //menghapus efek unvalid pada form
        $(".form-setting").removeClass("was-validated");
    }

    var is_done_simpan_setting = true;
    var mode_simpan = "simpan";

    function simpan_setting(data) {
        if (is_done_simpan_setting) {
            is_done_simpan_setting = false;

            $.ajax({
                url: "<?= base_url('admin/simpan_setting') ?>",
                method: "POST",
                data: data,
                dataType: "json",
                success: function(e) {
                    is_done_simpan_setting = true;
                    if (e.status) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: e.msg,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: e.msg,
                            icon: 'danger',
                            confirmButtonText: 'Ok'
                        });
                    }
                    clearForm();
                    reinitDatatable();
                },
                error: function(e) {
                    is_done_simpan_setting = true;
                }
            });
        }
    }

    //fungsi action setelah user melakukan klik pada tombol edit
    function edit_setting(id_setting) {
        var editarray = edit_array[id_setting];
        // mengisi value pada field form sesuai data yang dipilih di datatables
        $("#id_setting").val(editarray.id_setting);
        $("#nama_setting").val(editarray.nama_setting);
        $("#npwpd").val(editarray.npwpd);
        $("#nama_wp").val(editarray.nama_wp);
        $("#method").val(editarray.method);
        $("#link_curl").val(editarray.link_curl);
        $("#curlopt_header").val(editarray.curlopt_header);

        $("#preset_curl").val(editarray.preset_curl);
        $("#need_token").prop("checked", (editarray.need_token == '1') ? true : false);
        $("#need_token").trigger("change");
        $("#token_req_url").val(editarray.token_req_url);
        $("#token_req_payload").val(editarray.token_req_payload);
        $("#token_req_method").val(editarray.token_req_method);

        scrollToAnchor("form_setting");
        $("#id_setting").focus();
    }

    //fungsi untuk menghapus data
    var is_hapus_setting_done = true;

    function hapus_setting(id_setting) {
        //mengambil data id yang sudah diklik dari tombol hapus
        var post_data = {
            "id_setting": id_setting
        };

        // menampilkan tombol konfirmasi hapus ya atau tidak
        Swal.fire({
            title: 'Hapus setting?',
            text: 'Setting Yang Dipilih Akan Dihapus Dari Daftar.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                if (is_hapus_setting_done) {
                    is_hapus_setting_done = false;
                    $.ajax({
                        url: "<?= base_url('admin/hapus_setting') ?>",
                        method: "POST",
                        data: post_data,
                        dataType: "json",
                        success: function(e) {
                            is_hapus_setting_done = true;
                            if (e.status) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: e.msg,
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: e.msg,
                                    icon: 'danger',
                                    confirmButtonText: 'Ok'
                                });
                            }
                            clearForm();
                            reinitDatatable();
                        },
                        error: function(e) {
                            is_hapus_setting_done = true;
                        }
                    });
                }
            }
        });
    }

    dragableModal();

    //DRAGABLE MODAL
    function dragableModal() {
        if ($(".modal").length > 0) {
            var modal = $(".modal");
            var modalHeader = $(".modal .modal-header");

            var isDragging = false;
            var offset = {
                x: 0,
                y: 0
            };

            for (var i = 0; i < $(".modal").length; i++) {
                (function(index) {
                    $(modalHeader[index]).mousedown(function(e) {
                        isDragging = true;
                        offset.x = e.clientX - modal[index].offsetLeft;
                        offset.y = e.clientY - modal[index].offsetTop;
                    });
                })(i);

                (function(index) {
                    $(modalHeader[index]).mousemove(function(e) {
                        if (isDragging) {
                            var x = e.clientX - offset.x;
                            var y = e.clientY - offset.y;
                            modal[index].style.left = x + 'px';
                            modal[index].style.top = y + 'px';
                        }
                    });
                })(i);

                $(document).mouseup(function(e) {
                    isDragging = false;
                });
            }
        }
    }

    function scrollToAnchor(aid) {
        const destination = $("#" + aid);
        $('html,body').animate({
            scrollTop: destination.offset().top
        }, 'slow');
    }
</script>