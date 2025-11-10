<script type="text/javascript">
    var chartPajak = undefined;
    var chartOmzet = undefined;
    $(() => {
        // getTotalTagihan();
        execChart();

        for (let year = 2010; year < 2050; year++) {
            if (year == moment().year()) {
                $('#filterYear').append(`<option selected value="${year}">${year}</option>`);
            } else {
                $('#filterYear').append(`<option value="${year}">${year}</option>`);
            }
        }
    });

    function getTotalTagihan() {
        $.ajax({
            url: BASE_URL + 'historypelaporan/total_tagihan',
            data: {},
            dataType: "JSON",
            success: function(e) {
                var span_total_tagihan = '<span class="menu-text-alert">' + e.jml_belum_lunas + '</span>';
                if (e.jml_belum_lunas > 0) {
                    $(".menu-text-alert").remove();
                    $("#btn-HistoryPelaporan").append(span_total_tagihan);
                    $("#kt_header_mobile > .d-flex.align-items-center > .notification-round").remove();
                    $("#kt_header_mobile > .d-flex.align-items-center").append('<div class="notification-round"></div>');
                }
            },
            error: function(e) {}
        });
    }

    function execChart(filter = moment().year()) {
        HELPER.block()
        HELPER.ajax({
            url: BASE_URL + 'dashboard/dashboardwp',
            datatype: 'json',
            type: 'POST',
            data: {
                filter: filter
            },
            complete: (res) => {
                var pajak = {
                    key: [],
                    value: [],
                };
                var omzet = {
                    key: [],
                    value: [],
                };

                $.map(res.data.pajak, (v, i) => {
                    pajak['key'][i] = moment(v.key).format('MMMM');
                    pajak['value'][i] = v.value;
                });

                $.map(res.data.omzet, (v, i) => {
                    omzet['key'][i] = moment(v.key).format('MMMM');
                    omzet['value'][i] = v.value;
                });

                chartPajak(pajak);
                chartOmzet(omzet);
                HELPER.unblock();
            }
        });
    }

    chartPajak = (data = null) => {
        var options = {
            series: [{
                name: "Pajak",
                data: data.value
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
            title: {
                text: 'Chart History Pajak',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: data.key,
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return "Rp. " + $.number(val) + ""
                    }
                }
            },

        };

        var chart = new ApexCharts(document.querySelector("#chartPajak"), options);
        chart.render();
    }
    chartOmzet = (data = null) => {
        var options = {
            series: [{
                name: "Omzet",
                data: data.value
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
            title: {
                text: 'Chart Omzet',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: data.key,
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return "Rp. " + $.number(val) + ""
                    }
                }
            },

            colors: ['#00e396']
        };



        var chart = new ApexCharts(document.querySelector("#chartOmzet"), options);
        chart.render();
    }

    function chart(id, categories = [], series = [], changeOption = {}) {
        var element = document.getElementById(id);

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: "Desktops",
                data: []
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
            title: {
                text: 'Product Trends by Month',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: [],
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return "Rp. " + $.number(val) + ""
                    }
                }
            },

        };

        var chart = new ApexCharts(element, options);
        chart.render();

        return chart;
    }

    onFilter = () => {
        const filter = $('#filterYear').val();
        HELPER.block();
        HELPER.ajax({
            url: BASE_URL + 'dashboard/dashboardwp',
            datatype: 'json',
            type: 'POST',
            data: {
                filter: filter
            },
            complete: (res) => {
                var pajak = {
                    key: [],
                    value: [],
                };
                var omzet = {
                    key: [],
                    value: [],
                };

                $.map(res.data.pajak, (v, i) => {
                    console.log(v);
                    pajak['key'][i] = moment(v.key).format('MMMM');
                    pajak['value'][i] = v.value;
                });

                $.map(res.data.omzet, (v, i) => {
                    console.log(v);
                    omzet['key'][i] = moment(v.key).format('MMMM');
                    omzet['value'][i] = v.value;
                });

                chartPajak = chart('chartPajak');
                chartOmzet = chart('chartOmzet');


                chartPajak.updateOptions({
                    series: [{
                        name: "Pajak",
                        data: pajak.value
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
                    title: {
                        text: 'Chart History Pajak',
                        align: 'left'
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: pajak.key,
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        },
                        y: {
                            formatter: function(val) {
                                return "Rp. " + $.number(val) + ""
                            }
                        }
                    },

                });

                chartOmzet.updateOptions({
                    series: [{
                        name: "Omzet",
                        data: omzet.value
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
                    title: {
                        text: 'Chart Omzet',
                        align: 'left'
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: omzet.key,
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px',
                            fontFamily: KTApp.getSettings()['font-family']
                        },
                        y: {
                            formatter: function(val) {
                                return "Rp. " + $.number(val) + ""
                            }
                        }
                    },

                    colors: ['#00e396']
                });


                HELPER.unblock();
            }
        });
    }
</script>