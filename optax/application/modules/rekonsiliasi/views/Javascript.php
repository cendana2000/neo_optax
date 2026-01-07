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
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_npwpd'];
                    },
                },
                {
                    targets: 2,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_nama'];
                    },
                },
                {
                    targets: 3,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['jan_penjualan']) + parseInt(full['jan_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['jan_penjualan']) + parseInt(full['jan_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 6,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['feb_penjualan']) + parseInt(full['feb_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['feb_penjualan']) + parseInt(full['feb_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 9,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['mar_penjualan']) + parseInt(full['mar_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['mar_penjualan']) + parseInt(full['mar_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 12,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['apr_penjualan']) + parseInt(full['apr_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['apr_penjualan']) + parseInt(full['apr_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 15,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['mei_penjualan']) + parseInt(full['mei_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['mei_penjualan']) + parseInt(full['mei_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 18,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['jun_penjualan']) + parseInt(full['jun_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['jun_penjualan']) + parseInt(full['jun_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 21,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['jul_penjualan']) + parseInt(full['jul_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['jul_penjualan']) + parseInt(full['jul_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 24,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['agu_penjualan']) + parseInt(full['agu_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['agu_penjualan']) + parseInt(full['agu_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 27,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['sep_penjualan']) + parseInt(full['sep_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['sep_penjualan']) + parseInt(full['sep_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 30,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['okt_penjualan']) + parseInt(full['okt_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['okt_penjualan']) + parseInt(full['okt_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 33,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['nov_penjualan']) + parseInt(full['nov_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['nov_penjualan']) + parseInt(full['nov_oapi']);
                        return total.toLocaleString();
                    },
                },
                {
                    targets: 36,
                    orderable: false,
                    className: 'text-right',
                    render: function(data, type, full, meta) {
                        const total = parseInt(full['des_penjualan']) + parseInt(full['des_oapi']);
                        return total.toLocaleString();
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
                        const total = parseInt(full['des_penjualan']) + parseInt(full['des_oapi']);
                        return total.toLocaleString();
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