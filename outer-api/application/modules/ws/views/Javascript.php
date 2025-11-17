<script>
    var isMobile = false;
    var dataTable = null;
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
            if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4)) || window.innerWidth < 798) {
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
                $('#dataTable').DataTable().clear().destroy();
                <?php if (@$load_data_bonbil) : ?>
                    dataTableDaftarBonbilTemplateFunction();
                <?php endif ?>
                currentInnerWidth = window.innerWidth;
            }
        });

        function dataTableDaftarBonbilTemplateFunction() {
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
                    [1, 'desc']
                ],
                // serverSide: true,
                ajax: {
                    url: "<?= base_url('admin/get_all_daftar_bonbil') ?>",
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
                        //KOLOM NAMA
                        targets: 1,
                        render: function(data, type, row, meta) {
                            return row.username;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true
                    },
                    {
                        //KOLOM ALAMAT
                        targets: 2,
                        render: function(data, type, row, meta) {
                            return row.alamat;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true
                    },
                    {
                        //KOLOM NOMOR TELP
                        targets: 3,
                        render: function(data, type, row, meta) {
                            return row.no_telp;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true
                    },
                    {
                        //KOLOM TANGGAL REQUEST
                        targets: 4,
                        render: function(data, type, row, meta) {
                            // Create a Date object from the input string
                            var date = new Date(row.created_at);

                            // Get the year, month, and day components
                            var year = date.getFullYear();
                            var month = String(date.getMonth() + 1).padStart(2, '0'); // Add 1 to month because it's zero-based
                            var day = String(date.getDate()).padStart(2, '0');

                            // Create the output date string in "Y-m-d" format
                            var outputDateString = year + "-" + month + "-" + day;
                            return outputDateString;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true
                    },
                    {
                        //KOLOM NOMOR UNDIAN
                        targets: 5,
                        render: function(data, type, row, meta) {
                            return (row.no_undian == null) ? "-" : row.no_undian;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true,
                    },
                    {
                        //KOLOM STATUS
                        targets: 6,
                        render: function(data, type, row, meta) {
                            return (row.is_valid == "f") ? "<span class='badge bg-warning'>Menunggu Validasi</span>" : "<span class='badge bg-success'>Valid</span>";
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true,
                    },
                    {
                        //KOLOM AKSI
                        targets: 7,
                        render: function(data, type, row, meta) {
                            return `
                                <button type="button" class="btn btn-sm btn-primary" onclick="validation_modal_show(` + row.id_bonbil + `)"><i class="fa-solid fa-search"></i> Validasi</button>
                                <button type="button" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                            `;
                        },
                        searchable: true,
                        visible: (isMobile) ? false : true,
                    },
                    {
                        //KOLOM RESPONSIVE
                        targets: 8,
                        render: function(data, type, row, meta) {
                            // Create a Date object from the input string
                            var date = new Date(row.created_at);

                            // Get the year, month, and day components
                            var year = date.getFullYear();
                            var month = String(date.getMonth() + 1).padStart(2, '0'); // Add 1 to month because it's zero-based
                            var day = String(date.getDate()).padStart(2, '0');

                            // Create the output date string in "Y-m-d" format
                            var outputDateString = year + "-" + month + "-" + day;

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
                                    <div class="col-8">` + ((row.no_undian == null) ? "-" : row.no_undian) + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">Status</div>
                                    <div class="col-8">` + ((row.is_valid == "f") ? "<span class='badge bg-warning'>Menunggu Validasi</span>" : "<span class='badge bg-success'>Valid</span>") + `</div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-4">action</div>
                                    <div class="col-8">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="validation_modal_show(` + row.id_bonbil + `)"><i class="fa-solid fa-search"></i> Validasi</button>
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            `;
                            return html;
                        },
                        searchable: true,
                        visible: (isMobile) ? true : false,
                    }
                ],
                responsive: true,
                initComplete: function() {
                    this.api().columns().every(function() {
                        $.fn.DataTable.ext.pager.numbers_length = 9;
                    });
                },
                "lengthMenu": pagination,
                "language": (isMobile) ? responsiveLanguage : defaultLanguage,
            });
        }

        <?php if (@$load_data_bonbil) : ?>
            dataTableDaftarBonbilTemplateFunction();
        <?php endif ?>

    });

    var viewer = null;

    function validation_modal_show(id_bonbil) {
        var is_done = true;

        if (is_done) {
            is_done = false;
            $.ajax({
                url: "<?= base_url('admin/detail_bonbil_by_id') ?>",
                method: "POST",
                data: {
                    "id_bonbil": id_bonbil
                },
                dataType: "json",
                success: function(e) {
                    is_done = true;

                    // Create a Date object from the input string
                    var date = new Date(e.data.tanggal_bonbil_upload);

                    // Get the year, month, and day components
                    var year = date.getFullYear();
                    var month = String(date.getMonth() + 1).padStart(2, '0'); // Add 1 to month because it's zero-based
                    var day = String(date.getDate()).padStart(2, '0');

                    // Create the output date string in "Y-m-d" format
                    var outputDateString = day + "-" + month + "-" + year + " " + date.getHours() + ":" + date.getMinutes();

                    //DETAIL BONBIL
                    $(".detail-nama").html(e.data.username);
                    $(".detail-alamat").html(e.data.alamat);
                    $(".detail-no_telp").html(e.data.no_telp);
                    $(".detail-tanggal-kirim").html(outputDateString);

                    //AMBIL DATA WP ETAX
                    get_data_wp_etax();

                    //BONBIL PREVIEW MANAGER
                    background_url = "<?= base_url('assets/img/bonbil') ?>/" + e.data.tahun + "/" + e.data.gambar_bonbil;
                    if (viewer == null) {
                        viewer = OpenSeadragon({
                            id: "openseadragon1",
                            prefixUrl: "../assets/plugin/openseadragon/images/",
                            tileSources: {
                                url: background_url,
                                type: 'image'
                            },
                        });
                    } else {
                        viewer.destroy();
                        viewer = OpenSeadragon({
                            id: "openseadragon1",
                            prefixUrl: "../assets/plugin/openseadragon/images/",
                            tileSources: {
                                url: background_url,
                                type: 'image'
                            },
                        });
                    }

                    // TAMPILKAN MODAL
                    // $.fn.modal.Constructor.prototype._enforceFocus = function() {};
                    $("#exLargeModal").modal("show");
                },
                error: function(e) {
                    is_done = true;
                }
            });
        }
    }

    function get_data_wp_etax() {
        var is_done_get_data_wp_etax = true;

        if (is_done_get_data_wp_etax) {
            is_done_get_data_wp_etax = false;
            $.ajax({
                url: "<?= base_url('admin/get_data_wp_etax') ?>",
                type: "POST",
                data: {},
                dataType: "json",
                success: function(e) {
                    var options = "<option value=''>Cari Lokasi Etax</option>";
                    for (var i = 0; i < e.data.length; i++) {
                        options += `<option value="` + e.data[i].NPWPD + `">` + e.data[i].NAMA_WP + `</option>`;
                    }
                    $(".select2").html(options);
                    $("#daftar_etax_resi").select2({
                        dropdownParent: $("#daftar_etax_resi_parent"),
                        tags: true
                    });

                    $("#daftar_etax_tanggal").select2({
                        dropdownParent: $("#daftar_etax_tanggal_parent"),
                        tags: true
                    });

                    $(".select2").removeAttr("disabled");

                    is_done_get_data_wp_etax = true;
                },
                error: function(e) {
                    is_done_get_data_wp_etax = true;
                }
            });
        }
    }
</script>