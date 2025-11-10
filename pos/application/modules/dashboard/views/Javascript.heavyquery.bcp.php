<script type="text/javascript">
    // GLOBAL SCOPE VARIABLE
    var statistikPembelian = undefined; 
    var statistikPenjualan = undefined;

    $(function(){
        HELPER.api = {
			totalStok: BASE_URL + 'dashboard/total_stok_barang',
            totalPembelian: BASE_URL + 'dashboard/total_pembelian_barang',
            totalPenjualan: BASE_URL + 'dashboard/total_penjualan_barang',
            statistikPembelian: BASE_URL + 'dashboard/statistik_pembelian',
            statistikPenjualan: BASE_URL + 'dashboard/statistik_penjualan',
            barangTerlaris: BASE_URL + 'dashboard/barang_terlaris',
            pendapatanBersih: BASE_URL + 'dashboard/pendapatan_bersih',
            totalHutang: BASE_URL + 'dashboard/total_hutang',
            totalPiutang: BASE_URL + 'dashboard/total_piutang',
		}

        $("#bulan").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

        pageFirstLoad();
        // getBarangTerlaris();
    });

    function pageFirstLoad(){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        // format Jun 12, 21 - June 19, 21
        var m_awaltanggal = moment(awaltanggal, "YYYY-MM-DD").format("MMM DD, YY");
        var m_akhirtanggal = moment(akhirtanggal, "YYYY-MM-DD").format("MMM DD, YY");
        $("#text-calendar").text(m_awaltanggal+" - "+m_akhirtanggal)

        $("#awal_tanggal").parent().removeClass("d-none");
        $("#akhir_tanggal").parent().removeClass("d-none");
        $("#bulan").parent().addClass("d-none");

        statistikPembelian = chart("chartpembelian");
        statistikPenjualan = chart("chartpenjualan");

        getStatistikPembelian();
        getStatistikPenjualan();
        getTotalStokBarang();
        getTotalPembelianBarang();
        getTotalPenjualanBarang();
        getBarangTerlaris();
        getPendapatanBersih();
        getTotalHutang();
        getTotalPiutang();
    }

    function chart(id, categories = [], tunai = [], hutang = [], changeOption = {}){
        var element = document.getElementById(id);

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Tunai',
                data: tunai
            },{
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
                    formatter: function (val) {
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

    function updateObject(target, update){
        // for each key/value pair in update object
        for ([key,value] of Object.entries(update)){
            // if target has the relevant key and
            // the type in target and update is the same
            if (target.hasOwnProperty(key) && typeof(value) === typeof(target[key])){
                // update value if string,number or boolean
                if (['string','number','boolean'].includes(typeof value) || Array.isArray(value)){
                    target[key] = value;
                } else {
                    // if type is object then go one level deeper
                    if (typeof value === 'object'){
                        updateObject(target[key], value)
                    }
                }
            }
        }
    }

    function getTotalStokBarang(){
        $.ajax({
            url: HELPER.api.totalStok,
            type: 'GET',
            complete: function(res){
                var resjson = res.responseJSON;
                $("#total_stok_barang").text($.number(resjson.total_stok_barang))
            }
        })
    }

    function getTotalPembelianBarang(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();
        $.ajax({
            url: HELPER.api.totalPembelian,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                $("#total_pembelian_barang").text($.number(resjson.total_pembelian_barang))
            }
        })
    }

    function getTotalPenjualanBarang(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();
        $.ajax({
            url: HELPER.api.totalPenjualan,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                $("#total_penjualan_barang").text($.number(resjson.total_penjualan_barang))
            }
        })
    }

    function getBarangTerlaris(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();

        /*
        var dummy = [
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
            {barang_nama: 'TEST DUMMY BARANG', total_kartu_stok_keluar: '2000'},
        ]

        $("#barang_terlaris").html("");
        dummy.map((item, index) => {
            $("#barang_terlaris").append(`
            <div class="d-flex align-items-center ${index == dummy.length-1 ? 'mb-0' : 'mb-2'}">
                <span class="svg-icon svg-icon-primary svg-icon-5x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo3/dist/../src/media/svg/icons/Design/Image.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"/>
                    </g></svg><!--end::Svg Icon-->
                </span>
                <span class="font-weight-bold text-dark ml-2">${item.barang_nama}</span>
                <span class="font-weight-bold text-dark ml-auto">${$.number(item.total_kartu_stok_keluar)}</span>
            </div>
            `)
        })
        */

        // /*
        $.ajax({
            url: HELPER.api.barangTerlaris,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                if(resjson.length > 0){
                    var mapitemhtml = '';
                    resjson.map((item, index) => {
                        mapitemhtml += `
                            <div class="d-flex align-items-center ${index == resjson.length-1 ? 'mb-0' : 'mb-7'}">
                                <span class="svg-icon svg-icon-primary svg-icon-4x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo3/dist/../src/media/svg/icons/Design/Image.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"/>
                                    </g></svg><!--end::Svg Icon-->
                                </span>
                                <span class="font-weight-bold text-dark ml-2">${item.barang_nama}</span>
                                <span class="font-weight-bold text-dark ml-auto">${$.number(item.total_kartu_stok_keluar)}</span>
                            </div>
                        `;
                    })

                    $("#barang_terlaris").html(mapitemhtml);
                }
            }
        });
        // */
    }

    function changeBerdasarkan(el){
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
        if(value == "tanggal"){
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
            elspancalendar.text(m_awaltanggal+" - "+m_akhirtanggal);

            elformtanggal.attr('action', `javascript:filter('tanggal')`);
            filter('tanggal')
        }else if(value == "bulan"){
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

    function filter(type = 'tanggal'){
        if(type == 'tanggal'){
            var m_awaltanggal = moment($("#awal_tanggal").val(), "YYYY-MM-DD").format("MMM DD, YY");
            var m_akhirtanggal = moment($("#akhir_tanggal").val(), "YYYY-MM-DD").format("MMM DD, YY");
            $("#text-calendar").text(m_awaltanggal+" - "+m_akhirtanggal);
        }else if(type == 'bulan'){
            var m_bulan = moment($("#bulan").val(), "YYYY-MM").format("MMMM YY");
            $("#text-calendar").text(m_bulan);
        }

        getStatistikPembelian(type);
        getStatistikPenjualan(type);
        getTotalStokBarang(type);
        getTotalPembelianBarang(type);
        getTotalPenjualanBarang(type);
        getBarangTerlaris(type);
        getPendapatanBersih(type);
        getTotalHutang(type);
        getTotalPiutang(type);
    }

    function getStatistikPembelian(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();

        $("#spinner-statistik-pembelian").removeClass('d-none');

        $.ajax({
            url: HELPER.api.statistikPembelian,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                console.log(res);
                var tunai = resjson.tunai;
                var kredit = resjson.kredit;
                var categories = resjson.categories;

                statistikPembelian.updateOptions({
                    xaxis: {
                        categories
                    }
                });

                statistikPembelian.updateSeries([{
                    name: 'Tunai',
                    data: tunai
                },{
                    name: 'Hutang',
                    data: kredit
                }]);

                $("#spinner-statistik-pembelian").addClass('d-none');

                // chart("chartpembelian", categories, tunai, kredit);
            }
        })
    }

    function getStatistikPenjualan(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();

        $("#spinner-statistik-penjualan").removeClass('d-none');

        $.ajax({
            url: HELPER.api.statistikPenjualan,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                var tunai = resjson.tunai;
                var kredit = resjson.kredit;
                var categories = resjson.categories;

                statistikPenjualan.updateOptions({
                    chart: { 
                        height: 400 
                    },
                    xaxis: {
                        categories
                    }
                });

                statistikPenjualan.updateSeries([{
                    name: 'Tunai',
                    data: tunai
                },{
                    name: 'Hutang',
                    data: kredit
                }]);

                $("#spinner-statistik-penjualan").addClass('d-none');

                // console.log("here")
                // chart("chartpenjualan", categories, tunai, kredit, options);
            }
        })
    }

    function getPendapatanBersih(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();
        // console.log(HELPER.api.pendapatanBersih);
        $.ajax({
            url: HELPER.api.pendapatanBersih,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                $("#pendapatan_bersih").text($.number(resjson.pendapatan_bersih));
                $("#range_pendapatan_bersih").text(resjson.range_date);
            }
        })
    }

    function getTotalHutang(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();
        // console.log(HELPER.api.pendapatanBersih);
        $.ajax({
            url: HELPER.api.totalHutang,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                // console.log(resjson);
                var persentage = resjson.persentage || 0;
                $("#total_hutang").text($.number(resjson.total_pembelian));
                $("#total_hutang_terbayar").text($.number(resjson.total_pembayaran));
                $("#total_hutang_progress").css("width", persentage+'%').attr("aria-valuenow", resjson.persentage);
            }
        })
    }

    function getTotalPiutang(type = 'tanggal'){
        var awaltanggal = $("#awal_tanggal").val();
        var akhirtanggal = $("#akhir_tanggal").val();
        var bulan = $("#bulan").val();
        // console.log(HELPER.api.pendapatanBersih);
        $.ajax({
            url: HELPER.api.totalPiutang,
            type: 'post',
            data: {
                type,
                bulan,
                awal_tanggal: awaltanggal,
                akhir_tanggal: akhirtanggal,
            },
            complete: function(res){
                var resjson = res.responseJSON;
                var persentage = resjson.persentage || 0;
                $("#total_piutang").text($.number(resjson.total_penjualan));
                $("#total_piutang_terbayar").text($.number(resjson.total_pembayaran));
                $("#total_piutang_progress").css("width", persentage+'%').attr("aria-valuenow", resjson.persentage);
            }
        })
    }
</script>