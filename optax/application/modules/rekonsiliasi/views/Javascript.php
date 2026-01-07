<script type="text/javascript">
    $(function() {
        HELPER.api = {
            table: BASE_URL + 'rekonsiliasi'
        }

        $('#tahun').datepicker({
            format: 'yyyy',
            startView: 'years',
            minViewMode: 'years'
        });

        $('#form-search').on('submit', function() {
            loadTable();
            return false;
        })

        loadTable();
    })

    function onBack() {
        HELPER.toggleForm({
            tohide: 'report_data_pdf',
            toshow: 'table_data'
        });
    }

    function loadTable() {
        HELPER.initTable({
            el: "table-rekonsiliasi",
            url: HELPER.api.table,
            searchAble: true,
            destroyAble: true,
            responsive: false,
            data: {
                "filter_tahun": $("#tahun").val()
            },
            order: [0, 'asc'],
            columnDefs: [{
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_npwpd'];
                    },
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_nama'];
                    },
                },
                {
                    targets: 3,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['januari']).toLocaleString();
                    },
                },
                {
                    targets: 4,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 5,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['januari']).toLocaleString();
                    },
                },
                {
                    targets: 6,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['februari']).toLocaleString();
                    },
                },
                {
                    targets: 7,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 8,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['februari']).toLocaleString();
                    },
                },
                {
                    targets: 9,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['maret']).toLocaleString();
                    },
                },
                {
                    targets: 10,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 11,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['maret']).toLocaleString();
                    },
                },
                {
                    targets: 12,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['april']).toLocaleString();
                    },
                },
                {
                    targets: 13,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 14,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['april']).toLocaleString();
                    },
                },
                {
                    targets: 15,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['mei']).toLocaleString();
                    },
                },
                {
                    targets: 16,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 17,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['mei']).toLocaleString();
                    },
                },
                {
                    targets: 18,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['juni']).toLocaleString();
                    },
                },
                {
                    targets: 19,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 20,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['juni']).toLocaleString();
                    },
                },
                {
                    targets: 21,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['juli']).toLocaleString();
                    },
                },
                {
                    targets: 22,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 23,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['juli']).toLocaleString();
                    },
                },
                {
                    targets: 24,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['agustus']).toLocaleString();
                    },
                },
                {
                    targets: 25,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 26,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['agustus']).toLocaleString();
                    },
                },
                {
                    targets: 27,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['september']).toLocaleString();
                    },
                },
                {
                    targets: 28,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 29,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['september']).toLocaleString();
                    },
                },
                {
                    targets: 30,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['oktober']).toLocaleString();
                    },
                },
                {
                    targets: 31,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 32,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['oktober']).toLocaleString();
                    },
                },
                {
                    targets: 33,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['november']).toLocaleString();
                    },
                },
                {
                    targets: 34,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 35,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['november']).toLocaleString();
                    },
                },
                {
                    targets: 36,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['desember']).toLocaleString();
                    },
                },
                {
                    targets: 37,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return 0;
                    },
                },
                {
                    targets: 38,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        return parseInt(full['desember']).toLocaleString();
                    },
                },
            ],
        });
    }

    function getPdf() {
        HELPER.block();

        $.ajax({
            url: BASE_URL + 'rekonsiliasi/pdf',
            type: 'POST',
            dataType: 'JSON',
            data: {
                tahun: $('#tahun').val(),
            },
            success: function(res) {
                let htmlobject = $('#pdf-laporan').html();
                $("#pdf-laporan object").remove();
                $("#pdf-laporan").append(htmlobject);
                $("#pdf-laporan object").attr("data", res.record);
                HELPER.toggleForm({
                    tohide: 'table_data',
                    toshow: 'report_data_pdf'
                });
                HELPER.unblock();
            }
        })
    }

    function getExcel() {
        HELPER.block();

        $.ajax({
            url: BASE_URL + 'rekonsiliasi/spreadsheet',
            type: 'POST',
            dataType: 'JSON',
            data: {
                tahun: $('#tahun').val(),
            },
            success: function(res) {
                if (res.success) {
                    let fileLocation = BASE_ASSETS + 'laporan/rekonsiliasi/' + res.file;
                    window.location.href = fileLocation;
                }
            },
            complete: function(res) {
                HELPER.unblock();
            }
        })
    }
</script>