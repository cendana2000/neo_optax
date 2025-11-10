<script type="text/javascript">
    // GLOBAL SCOPE VARIABLE
    var statistikPembelian = undefined;
    var statistikPenjualan = undefined;

    $(function() {
        HELPER.api = {
            allPenjualanPembelian: BASE_URL + 'dashboard/get_all_penjualan_pembelian',
            stokTerlarisBersih: BASE_URL + 'dashboard/get_stok_terlaris_bersih',
        }

        $("#bulan").datepicker({
            format: "yyyy-mm",
            startView: "months",
            minViewMode: "months"
        });

        pageFirstLoad();
    });

    function pageFirstLoad() {
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        // format Jun 12, 21 - June 19, 21
        var m_awaltanggal = moment(awaltanggal, "YYYY-MM-DD").format("MMM DD, YY");
        var m_akhirtanggal = moment(akhirtanggal, "YYYY-MM-DD").format("MMM DD, YY");
        $("#text-calendar").text(m_awaltanggal + " - " + m_akhirtanggal)

        $("#awal_tanggal").parent().removeClass("d-none");
        $("#akhir_tanggal").parent().removeClass("d-none");
        $("#bulan").parent().addClass("d-none");

        statistikPembelian = chart("chartpembelian");
        statistikPenjualan = chart("chartpenjualan");

        getAllPenjualanPembelian();
    }

    function getAllPenjualanPembelian(type = "tanggal") {
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();

        $("#spinner-statistik-pembelian").removeClass('d-none');
        $("#spinner-statistik-penjualan").removeClass('d-none');

        $.ajax({
            url: HELPER.api.allPenjualanPembelian,
            type: 'get',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res) {
                var resjson = res.responseJSON;

                // STATISTIK PEMBELIAN
                statistikPembelian.updateOptions({
                    xaxis: {
                        categories: resjson.statistik_categories || [],
                    }
                });

                statistikPembelian.updateSeries([{
                    name: 'Tunai',
                    data: resjson.statistik_pembelian.tunai || []
                }, {
                    name: 'Hutang',
                    data: resjson.statistik_pembelian.hutang || []
                }]);

                // STATISTIK PENJUALAN
                statistikPenjualan.updateOptions({
                    xaxis: {
                        categories: resjson.statistik_categories || [],
                    }
                });

                statistikPenjualan.updateSeries([{
                    name: 'Tunai',
                    data: resjson.statistik_penjualan.tunai || []
                }, {
                    name: 'Hutang',
                    data: resjson.statistik_penjualan.hutang || []
                }]);

                $("#spinner-statistik-pembelian").addClass('d-none');
                $("#spinner-statistik-penjualan").addClass('d-none');

                // TOP WIDGET
                // - stok barang
                $("#total_stok_barang").text($.number(resjson.total_stok));
                // - total pembelian barang
                $("#total_pembelian_barang").text($.number(resjson.total_pembelian_barang))
                // - total penjualan barang
                $("#total_penjualan_barang").text($.number(resjson.total_penjualan_barang))

                // BOTTOM WIDGET
                // - hutang
                $("#total_hutang").text($.number(resjson.total_hutang.total));
                $("#total_hutang_terbayar").text($.number(resjson.total_hutang.terbayar));
                $("#total_hutang_progress").css("width", resjson.total_hutang.persentage + '%').attr("aria-valuenow", resjson.total_hutang.persentage);
                // - piutang
                $("#total_piutang").text($.number(resjson.total_piutang.total));
                $("#total_piutang_terbayar").text($.number(resjson.total_piutang.terbayar));
                $("#total_piutang_progress").css("width", resjson.total_piutang.persentage + '%').attr("aria-valuenow", resjson.total_piutang.persentage);
                // - pendapatan bersih
                $("#pendapatan_bersih").text($.number(resjson.pendapatan_bersih.total));
                $("#range_pendapatan_bersih").text(resjson.pendapatan_bersih.range_date);

                // BARANG TERLARIS
                if (resjson.barang_terlaris.length > 0) {
                    var mapitemhtml = '';
                    resjson.barang_terlaris.map((item, index) => {
                        mapitemhtml += `
                            <div class="d-flex align-items-center pr-3 ${index == resjson.barang_terlaris.length-1 ? 'mb-0' : 'mb-7'}">
                                <img 
                                    src="${BASE_URL_NO_INDEX+item.barang_thumbnail}" 
                                    class="rounded mr-5" 
                                    style="width:65px; height:65px; object-fit:cover;" 
                                    alt="${item.barang_nama}" 
                                    onerror="imgError(this);"
                                />
                                <span class="font-weight-bold text-dark ml-2">${item.barang_nama}</span>
                                <span class="font-weight-bold text-dark ml-auto mr-3">Rp. ${$.number(item.barang_harga)}</span>
                            </div>
                        `;
                    });

                    $("#barang_terlaris").html(mapitemhtml);
                }
            }
        })
    }

    function chart(id, categories = [], tunai = [], hutang = [], changeOption = {}) {
        var element = document.getElementById(id);

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Tunai',
                data: tunai
            }, {
                name: 'Hutang',
                data: hutang
            }],
            chart: {
                id,
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {

            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'gradient',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                    KTApp.getSettings()['colors']['theme']['base']['warning']
                ]
            },
            xaxis: {
                categories: categories,
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                },
                crosshairs: {
                    position: 'front',
                    stroke: {
                        color: KTApp.getSettings()['colors']['theme']['base']['success'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
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
            colors: [
                KTApp.getSettings()['colors']['theme']['light']['success'],
                KTApp.getSettings()['colors']['theme']['light']['warning']
            ],
            grid: {
                borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                //size: 5,
                //colors: [KTApp.getSettings()['colors']['theme']['light']['danger']],
                strokeColor: [
                    KTApp.getSettings()['colors']['theme']['base']['success'],
                    KTApp.getSettings()['colors']['theme']['base']['warning']
                ],
                strokeWidth: 3
            }
        };

        updateObject(options, changeOption);

        // console.log(options)

        var chart = new ApexCharts(element, options);
        chart.render();

        return chart;
    }

    function updateObject(target, update) {
        // for each key/value pair in update object
        for ([key, value] of Object.entries(update)) {
            // if target has the relevant key and
            // the type in target and update is the same
            if (target.hasOwnProperty(key) && typeof(value) === typeof(target[key])) {
                // update value if string,number or boolean
                if (['string', 'number', 'boolean'].includes(typeof value) || Array.isArray(value)) {
                    target[key] = value;
                } else {
                    // if type is object then go one level deeper
                    if (typeof value === 'object') {
                        updateObject(target[key], value)
                    }
                }
            }
        }
    }

    function changeBerdasarkan(el) {
        // get value
        var jel = $(el);
        var value = jel.data("value");
        var status = jel.data("status");
        var elbtntanggal = $("#btn-show-tanggal");
        var elbtnbulan = $("#btn-show-bulan");
        var elinputawal = $("#awal_tanggal");
        var elinputakhir = $("#akhir_tanggal");
        var elinputbulan = $("#bulan");
        var elspancalendar = $("#text-calendar");
        var elformtanggal = $("#tanggal");

        // change & action btn active
        if (value == "tanggal") {
            elbtntanggal.removeClass("btn-default");
            elbtntanggal.addClass("btn-primary");

            elbtnbulan.removeClass("btn-primary");
            elbtnbulan.addClass("btn-default");

            elinputawal.parent().removeClass("d-none");
            elinputakhir.parent().removeClass("d-none");
            elinputbulan.parent().addClass("d-none");

            // FORMAT Jun 12, 21 - Jun 19, 21
            // elspancalendar.text(elinputawal.val()+" - "+elinputakhir.val());
            var m_awaltanggal = moment(elinputawal.val(), "YYYY-MM-DD").format("MMM DD, YY");
            var m_akhirtanggal = moment(elinputakhir.val(), "YYYY-MM-DD").format("MMM DD, YY");
            elspancalendar.text(m_awaltanggal + " - " + m_akhirtanggal);

            elformtanggal.attr('action', `javascript:filter('tanggal')`);
            filter('tanggal')
        } else if (value == "bulan") {
            elbtntanggal.removeClass("btn-primary");
            elbtntanggal.addClass("btn-default");

            elbtnbulan.removeClass("btn-default");
            elbtnbulan.addClass("btn-primary");

            elinputawal.parent().addClass("d-none");
            elinputakhir.parent().addClass("d-none");
            elinputbulan.parent().removeClass("d-none");

            // FORMAT JUNE 2019
            // elspancalendar.text(elinputbulan.val())
            var m_bulan = moment(elinputbulan.val(), "YYYY-MM").format("MMMM YY");
            elspancalendar.text(m_bulan)

            elformtanggal.attr('action', `javascript:filter('bulan')`);
            filter('bulan')
        }
    }

    function filter(type = 'tanggal') {
        if (type == 'tanggal') {
            var m_awaltanggal = moment($("#awal_tanggal").val(), "YYYY-MM-DD").format("MMM DD, YY");
            var m_akhirtanggal = moment($("#akhir_tanggal").val(), "YYYY-MM-DD").format("MMM DD, YY");
            $("#text-calendar").text(m_awaltanggal + " - " + m_akhirtanggal);
        } else if (type == 'bulan') {
            var m_bulan = moment($("#bulan").val(), "YYYY-MM").format("MMMM YY");
            $("#text-calendar").text(m_bulan);
        }

        getAllPenjualanPembelian(type);
    }

    function imgError(image) {
        image.onerror = "";
        image.src = `${BASE_URL_NO_INDEX}assets/media/default_product.png`;
    }
</script>