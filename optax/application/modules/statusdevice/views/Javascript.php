<script>
    // interval auto refresh datatable(buka jika diperlukan)
    // var auto_refresh_interval = null;
    // var autoRefreshTable = function(interval) {
    //     if (interval <= 0) return;
    //     if (!auto_refresh_interval) {
    //         auto_refresh_interval = setInterval(() => {
    //             if ($('#table-lastactivity').length <= 0) {
    //                 clearInterval(auto_refresh_interval);
    //                 auto_refresh_interval = null
    //                 return;
    //             }
    //             loadTable()
    //         }, interval * 60 * 5000);
    //     }
    // };

    $(function() {
        var chart;

        new tempusDominus.TempusDominus(document.getElementById("tanggal"), {
            display: {
                components: {
                    decades: false,
                    year: true,
                    month: true,
                    date: true,
                    hours: false,
                    minutes: false,
                    seconds: false
                }
            },
            restrictions: {
                maxDate: new Date()
            },
            localization: {
                format: 'yyyy-MM-dd'
            }
        });

        HELPER.api = {
            table: BASE_URL + 'statusdevice',
            ajax_toko: BASE_URL + 'statusdevice/select_wp',
        }

        HELPER.ajaxCombo({
            el: '#select_toko',
            url: HELPER.api.ajax_toko
        });

        $('#table-lastactivity').on('click', '.btn-detail', function() {
            const id = $(this).data('id');

            $.ajax({
                url: BASE_URL + 'statusdevice/detail/' + id,
                success: function(response) {
                    if (!response.is_success) {
                        alert(response.msg);
                        return;
                    }

                    $('#modal-detail').find('[data-value="device_model"]').text(response.data.log_device_model || '-');
                    $('#modal-detail').find('[data-value="toko"]').text(response.data.toko_nama || '-');
                    $('#modal-detail').find('[data-value="wp_telp"]').text(response.data.wajibpajak_telp || '-');
                    $('#modal-detail').find('[data-value="wp_alamat"]').text(response.data.wajibpajak_alamat || '-');

                    if (chart) {
                        chart.destroy()
                        chart = null;
                    };

                    chart = new ApexCharts(document.querySelector('#penggunaan-hari-ini'), {
                        series: [{
                            name: 'Hari ini',
                            data: [
                                response.data.log_jam_0 || 0,
                                response.data.log_jam_1 || 0,
                                response.data.log_jam_2 || 0,
                                response.data.log_jam_3 || 0,
                                response.data.log_jam_4 || 0,
                                response.data.log_jam_5 || 0,
                                response.data.log_jam_6 || 0,
                                response.data.log_jam_7 || 0,
                                response.data.log_jam_8 || 0,
                                response.data.log_jam_9 || 0,
                                response.data.log_jam_10 || 0,
                                response.data.log_jam_11 || 0,
                                response.data.log_jam_12 || 0,
                                response.data.log_jam_13 || 0,
                                response.data.log_jam_14 || 0,
                                response.data.log_jam_15 || 0,
                                response.data.log_jam_16 || 0,
                                response.data.log_jam_17 || 0,
                                response.data.log_jam_18 || 0,
                                response.data.log_jam_19 || 0,
                                response.data.log_jam_20 || 0,
                                response.data.log_jam_21 || 0,
                                response.data.log_jam_22 || 0,
                                response.data.log_jam_23 || 0,
                            ]
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        grid: {
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                        xaxis: {
                            categories: [
                                'Jam 0', 'Jam 1', 'Jam 2', 'Jam 3', 'Jam 4', 'Jam 5', 'Jam 6', 'Jam 7', 'Jam 8', 'Jam 9', 'Jam 10', 'Jam 11', 'Jam 12',
                                'Jam 13', 'Jam 14', 'Jam 15', 'Jam 16', 'Jam 17', 'Jam 18', 'Jam 19', 'Jam 20', 'Jam 21', 'Jam 22', 'Jam 23'
                            ]
                        }
                    })

                    chart.render();

                    $('#modal-detail').modal('show');
                }
            });
        });

        $("#btnFilter").on("click", function() {
            let filters = {
                status_device: $("#filter_status_device").val(),
                status_data: $("#filter_status_data").val(),
                jenis_device: $("#filter_jenis_device").val()
            };
            loadTable(filters);
        });

        loadTable();

        $.ajax({
            url: BASE_URL + 'statusdevice/get_interval',
            dataType: 'JSON',
            success: function(response) {
                if (!response.success) return;

                const interval = parseInt(response.data);
                autoRefreshTable(interval);
            }
        })

        $.ajax({
            url: BASE_URL + 'statusdevice',
            dataType: 'JSON',
            type: 'POST',
            success: function(res) {
                $('#sum-device-online').text(res.summary.device.online);
                $('#sum-device-warning').text(res.summary.device.warning);
                $('#sum-device-offline').text(res.summary.device.offline);
                $('#sum-data-active').text(res.summary.data.active);
                $('#sum-data-inactive').text(res.summary.data.inactive);
                $('#sum-data-offline').text(res.summary.data.offline);
            }
        })
    });

    function loadTable(filters = {}) {
        HELPER.initTable({
            el: "table-lastactivity",
            url: HELPER.api.table,
            searchAble: true,
            destroyAble: true,
            responsive: false,
            data: {
                status_device: filters.status_device || "",
                status_data: filters.status_data || "",
                jenis_device: filters.jenis_device || ""
            },
            order: [
                [2, 'asc']
            ],
            columnDefs: [{
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, full, meta) {
                        return full['toko_nama'];
                    }
                },
                {
                    targets: 2,
                    render: function(data, type, full, meta) {
                        return full['jenis_device'];
                    }
                },
                {
                    targets: 3,
                    width: '220px',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return full['status_device'];
                    }
                },
                {
                    targets: 4,
                    width: '220px',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return full['status_data'];
                    }
                },
                {
                    targets: 5,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `<button data-id="${full['toko_id']}" class="btn btn-sm btn-light-primary btn-detail"><i class="fa fa-info-circle"></i></button>`;
                    }
                },
            ]
        });
    }

    function onBack() {
        HELPER.toggleForm({
            tohide: 'report_data_pdf',
            toshow: 'table_data'
        });
    }

    function getPdf() {
        HELPER.block();

        $.ajax({
            url: BASE_URL + 'statusdevice/pdf',
            data: {
                tanggal: $('#tanggal').val(),
                code_store: $('#select_toko').val(),
            },
            type: 'post',
            dataType: 'json',
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
            url: BASE_URL + '/statusdevice/spreadsheet',
            type: 'post',
            data: {
                tanggal: $('#tanggal').val(),
                code_store: $('#select_toko').val(),
            },
            dataType: 'JSON',
            success: function(res) {
                console.log(res);
                if (res.success) {
                    let fileLocation = BASE_ASSETS + 'laporan/monitor_realisasi/' + res.file;
                    window.location.href = fileLocation;
                }
            },
            complete: function(res) {
                HELPER.unblock();
            }
        })
    }
</script>