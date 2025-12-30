<script type="text/javascript">
    $(function() {
        HELPER.api = {
            index: BASE_URL + 'laporannotrx',
            select_wp: BASE_URL + 'laporannotrx/select_wp'
        }

        HELPER.createCombo({
            el: 'kecamatan_id',
            url: BASE_URL + 'map/kecamatan',
            valueField: 'kecamatan_id',
            displayField: 'kecamatan_nama',
            placeholder: '-Semua-',
            callback: function() {
                $('#kecamatan_id').select2()
            }
        })

        HELPER.ajaxCombo({
            el: '#wajibpajak_id',
            url: HELPER.api.select_wp
        });

        $('#kelurahan_id').select2()

        $(".daterange").daterangepicker({
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('#kecamatan_id').on('change', function() {
            HELPER.createCombo({
                el: 'kelurahan_id',
                url: BASE_URL + 'map/kelurahan',
                data: {
                    kecamatan_id: this.value
                },
                valueField: 'kelurahan_id',
                displayField: 'kelurahan_nama',
                placeholder: '-Semua-',
                callback: function(resp) {
                    $('#kelurahan_id').select2();
                }
            })
        })

        $('#form-laporan-no-trx').on('submit', function() {
            init_table();
            return false
        });

        init_table();
    })

    function init_table() {
        HELPER.initTable({
            el: 'table-no-trx',
            url: HELPER.api.index,
            data: {
                periode: $('#periode').val(),
                kecamatan_id: $('#kecamatan_id').val(),
                kelurahan_id: $('#kelurahan_id').val(),
                wajibpajak_id: $('#wajibpajak_id').val(),
            },
            searchAble: true,
            destroyAble: true,
            responsive: false,
            order: [
                [1, 'desc']
            ],
            columnDefs: [{
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return full['pos_no_trx_tanggal']
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_npwpd']
                    }
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_nama']
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        return full['wajibpajak_alamat']
                    }
                },
                {
                    targets: 5,
                    render: function(data, type, full, meta) {
                        return full['kecamatan_nama']
                    }
                }
            ]
        })
    }
</script>