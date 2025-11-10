<script type="text/javascript">
    $(function() {
        // Chart Pembelian
        var options = {
          series: [{
          name: 'series1',
          data: [31, 40, 28, 51, 42, 109, 100]
        }, {
          name: 'series2',
          data: [11, 32, 45, 32, 34, 52, 41]
        }],
          chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
        };

        var chart = new ApexCharts(document.querySelector("#chartpembelian"), options);
        chart.render();

        var start = moment().subtract(29, 'days');
        var end = moment();
        $('#daterangepicker').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',

            startDate: start,
            endDate: end,
            locale: {
                format: 'DD-MM-YYYY',
                separator: '/'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end, label) {
            $('#daterangepicker').val(start.format('DD-MM-YYYY') + ' / ' + end.format('DD-MM-YYYY'));
        });

        // thisLogUser()

    })

    function onFilter() {
        $('#chart1').empty()
        $('#chart2').empty()
        if ($('#daterangepicker').val() != "") {

            chartDrillProgress($('#daterangepicker').val())
            chartDrillCumul($('#daterangepicker').val())
        } else {
            HELPER.showMessage({
                success: 'warning',
                title: 'Info',
                message: 'Fill out the date range first'
            })
        }
    }

    function thisLogUser() {
        if (<?= $this->session->userdata('hak_akses_is_super') ?> == 1) {
            projectProgress()
            $('#content-user').hide()
        } else {
            $('#content-admin').hide()
            chartHole()
            detailHead()
            chartDrillProgress($('#daterangepicker').val())
            chartDrillCumul($('#daterangepicker').val())
        }
    }

    function detailHead() {
        HELPER.ajax({
            url: BASE_URL + 'dashboard/readDetail',
            complete: function(res) {
                $('.card-data_total_hole').text(`${res.total_hole} (${res.hole_plan}%)`)
                var meterage = (Number(res.project_hole_akm) + Number(res.project_core_akm)).toFixed(2)
                now = moment()
                start = moment(res.project_start_date)
                total_day = now.diff(start, 'days')

                planStart = moment(res.project_start_date)
                planEnd = moment(res.project_end_date)
                plan_day = planEnd.diff(planStart, 'days')

                res_day = ((total_day / plan_day) * 100)
                $('.card-data_meter').text(`${meterage} (${res.meter_plan}%)`)
                $('.card-total_day').text(`${total_day} (${res_day.toFixed(2)}%)`)
                $('.card-total_sample').text(`${res.total_sample} (${res.sample_plan}%)`)

                $('#btn_link').attr('href', res.project_map_link)
            }
        })
    }

    function chartDrillProgress(date) {
        HELPER.ajax({
            url: BASE_URL + 'dashboard/readChartDay',
            data: {
                date: date
            },
            complete: function(res) {
                if (res.success) {
                    var options = {
                        series: [{
                            name: 'Total',
                            data: res.total_meter
                        }],
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '100%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: res.dates,
                        },
                        yaxis: {
                            title: {
                                text: 'Meter'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        grid: {
                            show: false
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " Meter"
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart1"), options);
                    chart.render();
                }
            }
        })
    }

    function chartDrillCumul(date) {
        HELPER.ajax({
            url: BASE_URL + 'dashboard/readChartCumul',
            data: {
                date: date
            },
            complete: function(res) {
                if (res.success) {
                    var options = {
                        series: [{
                            name: '',
                            data: res.total_meter
                        }],
                        chart: {
                            height: 350,
                            type: 'line',
                            toolbar: {
                                show: true,
                                tools: {
                                    download: false,
                                    zoom: false
                                }
                            },
                            zoom: {
                                enabled: true,
                                type: 'x',
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        xaxis: {
                            type: 'date',
                            categories: res.dates,
                            range: 7
                        },
                        tooltip: {
                            x: {
                                format: 'dd/MM'
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val + " Meter"
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chart2"), options);
                    chart.render();
                }
            }
        })
    }

    function chartHole() {

        HELPER.ajax({
            url: BASE_URL + 'dashboard/readChart',
            complete: function(res) {
                var options = {
                    series: [{
                        name: 'Coring',
                        data: res.core_length
                    }, {
                        name: 'Open Hole',
                        data: res.open_hole
                    }, ],
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom',
                                offsetX: -10,
                                offsetY: 0
                            }
                        }
                    }],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 10
                        },
                    },
                    xaxis: {
                        type: 'text',
                        categories: res.name,
                    },
                    legend: {
                        position: 'right',
                        offsetY: 40
                    },
                    fill: {
                        opacity: 1
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart3"), options);
                chart.render();
            }
        })
    }

    function projectProgress() {
        HELPER.ajax({
            url: BASE_URL + 'dashboard/chartProject',
            complete: function(res) {
                var options = {
                    series: [{
                        name: 'Borehole Plan',
                        data: res.project_borehole_plan
                    }, {
                        name: 'Actual Borehole',
                        data: res.project_borehole_akm
                    }, {
                        name: 'Meter Plan',
                        data: res.project_meter_plan
                    }, {
                        name: 'Actual Meter',
                        data: res.project_akm_meterage
                    }, {
                        name: 'Sample Plan',
                        data: res.project_total_sample_plan
                    }, {
                        name: 'Actual Sample',
                        data: res.project_total_sample_akm
                    }],
                    colors: ['#3F51B5', '#008FFB', '#00E396', '#C7F464', '#7D02EB', '#A300D6'],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: res.project_code,
                    },
                    yaxis: {
                        title: {
                            text: ''
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chart_admin"), options);
                chart.render();
            }
        })

    }
</script>