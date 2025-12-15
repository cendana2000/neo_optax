<script type="text/javascript">
	var statistikNominal = undefined;
	var statistikTotal = undefined;
	var pos_statistikpenjualan = undefined;
	var tokoBaruRendered = false;

	$(function() {
		HELPER.fields = [
			'dashboard_id',
			'dashboard_kode',
			'dashboard_nama',
		];

		HELPER.setRequired([
			'dashboard_kode',
			'dashboard_nama',
		]);

		HELPER.api = {
			table: BASE_URL + 'dashboard/',
			read: BASE_URL + 'dashboard/read',
			store: BASE_URL + 'dashboard/store',
			update: BASE_URL + 'dashboard/update',
			destroy: BASE_URL + 'dashboard/destroy',
			dashboardPOS: BASE_URL + 'dashboard/dashboardPOS',
			onlineActivityUser: BASE_URL + 'dashboard/onlineActivityUser',
		}

		HELPER.ajaxCombo({
			el: '#filter-jenis_usaha',
			url: BASE_URL + 'jenis/parent_select_ajax',
			displayField: 'text',
			placeholder: 'Pilih Berdasarkan Jenis Usaha',
			tags: true
		});

		HELPER.ajaxCombo({
			el: '#filter-jenis_usaha-pos',
			url: BASE_URL + 'jenis/parent_select_ajax',
			displayField: 'text',
			placeholder: 'Pilih Berdasarkan Jenis Usaha',
			tags: true
		});

		$("#bulan, #bulan-pos").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});

		$('[data-toggle="popover"]').popover({
			html: true,
			trigger: 'click',
			container: 'body'
		})

		$('[data-toggle="tooltip"]').tooltip()

		$('body').on('click', function(e) {
			//did not click a popover toggle or popover
			if ($(e.target).data('toggle') !== 'popover' &&
				$(e.target).parents('.popover.in').length === 0) {
				$('[data-toggle="popover"]').popover('hide');
			}
		});

		filterByPeriod($('#weekly-filter')[0]);

		statistikNominal = chart("chartrealisasipajak");
		statistikTotal = chart("chartrealisasipajakupload");
		chartNominal = chart("charttotalpajak");
		pos_statistikpenjualan = barChart([])

		loadTable();
		loadTanggal();
		switchDashboard();
		// loadUserActiveDashboard();
		onlineActivityUser();
	});

	function loadTanggal() {
		var awaltanggal = $("#awal_tanggal").val();
		var akhirtanggal = $("#akhir_tanggal").val();
		// format Jun 12, 21 - June 19, 21
		var m_awaltanggal = moment(awaltanggal, "YYYY-MM-DD").format("MMM DD, YY");
		var m_akhirtanggal = moment(akhirtanggal, "YYYY-MM-DD").format("MMM DD, YY");
		$("#text-calendar").text(m_awaltanggal + " - " + m_akhirtanggal)
		$("#text-calendar-pos").text(m_awaltanggal + " - " + m_akhirtanggal)

		$("#awal_tanggal").parent().removeClass("d-none");
		$("#akhir_tanggal").parent().removeClass("d-none");
		$("#bulan").parent().addClass("d-none");
	}

	function loadTable(type = "tanggal") {
		tokoBaruRendered = false;
		var awaltanggal = $("#awal_tanggal").val();
		var akhirtanggal = $("#akhir_tanggal").val();
		var bulan = $("#bulan").val();

		$("#spinner-statistik-nominal").removeClass('d-none');
		$("#spinner-statistik-total").removeClass('d-none');

		$('#sektor_usaha').html('');
		$('#toko_baru').html('');
		$('#toko_baru_total').html('');

		$.ajax({
			url: HELPER.api.table,
			data: {
				type,
				bulan,
				awal_tanggal: awaltanggal,
				akhir_tanggal: akhirtanggal,
			},
			dataType: 'json',
			success: function(res) {
				$('#total_pajak_masuk').text($.number(res.total_pajak_masuk));
				$('#total_pajak_resto').text($.number(res.total_pajak_resto));
				$('#total_pajak_hotel').text($.number(res.total_pajak_hotel));
				$('#total_transaksi').text($.number(res.total_pajak_masuk * 10));
				$('#total_transaksi_resto').text($.number(res.total_pajak_resto * 10));
				$('#total_transaksi_hotel').text($.number(res.total_pajak_hotel * 10));
				$('#total_realisasi_wajib_pajak').text(res.total_realisasi_wajib_pajak);
				$('#total_wajib_pajak').text(res.total_wajib_pajak);
				$('#total_wp_resto').text(res.total_wp_resto);
				$('#total_wp_hotel').text(res.total_wp_hotel);
				$('#target_pajak').text($.number(res.target_pajak));
				$('#pajak_belum_bayar').text($.number(res.target_pajak - res.total_pajak_masuk_pertahun));
				$('#target_pajak_tahun').text(res.target_pajak_tahun);
				$('#table-transaksi-terakhir tbody').html('');

				$.each(res.sektor_usaha, function(i, v) {
					$('#sektor_usaha').append(`
						<div class="d-flex align-items-center mb-0">
							<span class="svg-icon svg-icon-primary svg-icon-4x">
								<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo3/dist/../src/media/svg/icons/Design/Image.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<polygon points="0 0 24 0 24 24 0 24"></polygon>
										<path d="M6,5 L18,5 C19.6568542,5 21,6.34314575 21,8 L21,17 C21,18.6568542 19.6568542,20 18,20 L6,20 C4.34314575,20 3,18.6568542 3,17 L3,8 C3,6.34314575 4.34314575,5 6,5 Z M5,17 L14,17 L9.5,11 L5,17 Z M16,14 C17.6568542,14 19,12.6568542 19,11 C19,9.34314575 17.6568542,8 16,8 C14.3431458,8 13,9.34314575 13,11 C13,12.6568542 14.3431458,14 16,14 Z" fill="#000000"></path>
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
							<span class="font-weight-bold text-dark ml-2">${v.jenis_nama}</span>
							<span class="font-weight-bold text-dark ml-auto">${v.total}</span>
						</div>
					`);
				});

				if (!tokoBaruRendered) {
					renderTokoBaru(res.toko_baru);
					tokoBaruRendered = true;
				}

				$.each(res.toko_baru_total, function(i, v) {
					$('#toko_baru_total').append(`
						<div class="symbol-group symbol-hover flex-nowrap">
							<div
								class="symbol symbol-35px symbol-circle"
								data-bs-toggle="tooltip"
								title="wp_">
								<span
									class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
							</div>							
							<a
								href="#"
								class="symbol symbol-35px symbol-circle"
								data-bs-toggle="modal"
								data-bs-target="#kt_modal_view_users">
								<span
									class="symbol-label bg-light text-gray-400 fs-8 fw-bold">+42</span>
							</a>
						</div>
					`);
				});

				if (res.transaksi_terakhir && res.transaksi_terakhir.length > 0) {
					$.each(res.transaksi_terakhir, function(i, v) {
						$('#table-transaksi-terakhir tbody').append(`
						<tr>
							<td class="text-nowrap">${v.nama_wp}</td>
							<td>${v.npwpd}</td>
							<td>${v.no_transaksi}</td>
							<td class="text-end">${$.number(v.sub_total)}</td>
							<td class="text-end">${$.number(v.jumlah_pajak)}</td>
							<td class="text-end text-nowrap">${v.tanggal_transaksi}</td>
						</tr>
					`);
					});
				} else {
					$('#table-transaksi-terakhir tbody').append(`
					<tr><td colspan="5" class="text-center text-muted">Tidak ada transaksi terakhir</td></tr>
				`);
				}

				initChartTotalNominal(res.chart_nominal_pajak);
				initChartTotalUpload(res.chart_upload_pajak);

				// fun baru				
				// initChartNominalPajak(res.chart_nominal_pajak)
			},
			complete: function() {
				$("#spinner-statistik-nominal").addClass('d-none');
				$("#spinner-statistik-total").addClass('d-none');
			}
		})
	}

	function renderTokoBaru(data) {
		$("#toko_baru").empty();
		$.each(data, function(i, v) {
			$('#toko_baru').append(`
            <div
				class="symbol symbol-35px symbol-circle toko_icon"
				data-bs-toggle="tooltip"
				title="${v.wajibpajak_nama}">
				<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" style="style= width: 10px; border-radius: 999px; background-color: #003A97;" ; />
			</div>
        `);
		});
	}

	function initChartTotalNominal(data) {
		let x = [];
		let y = [];

		$.each(data, function(i, v) {
			x.push(v.realisasi_tanggal);
			y.push(v.total_pajak_masuk);
		});
		if (statistikNominal) {
			statistikNominal.updateOptions({
				xaxis: {
					categories: x || [],
				},
				chart: {
					height: 465,
				},
			})

			statistikNominal.updateSeries([{
				name: 'Pajak Masuk',
				data: y || []
			}]);
		}
	}

	function initChartTotalUpload(data) {
		let x = [];
		let y = [];

		$.each(data, function(i, v) {
			x.push(v.realisasi_tanggal);
			y.push(v.total_upload);
		});
		if (statistikTotal) {
			statistikTotal.updateOptions({
				xaxis: {
					categories: x || [],
				},
				chart: {
					height: 465,
				},
				tooltip: {
					y: {
						formatter: function(val) {
							return $.number(val)
						}
					}
				},
			})

			statistikTotal.updateSeries([{
				name: 'Upload Pajak',
				data: y || []
			}]);
		}
	}


	function filterJenisUsaha(type = 'tanggal', el) {
		var awaltanggal = $("#awal_tanggal").val();
		var akhirtanggal = $("#akhir_tanggal").val();
		var bulan = $("#bulan").val();

		$("#spinner-statistik-nominal").removeClass('d-none');

		$.ajax({
			url: BASE_URL + 'dashboard/stats_nominal_jenis_usaha',
			data: {
				id: $(el).val(),
				type,
				bulan,
				awal_tanggal: awaltanggal,
				akhir_tanggal: akhirtanggal,
			},
			type: 'post',
			dataType: 'json',
			success: function(res) {
				initChartTotalNominal(res.chart_nominal_pajak);
			},
			complete: function(res) {
				$("#spinner-statistik-nominal").addClass('d-none');
			}
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-dashboard'
		});
		$("#btnReset").trigger("click");
	}

	function chart(id, categories = [], series = [], changeOption = {}) {
		var element = document.getElementById(id);

		if (!element) {
			return;
		}

		var options = {
			series,
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
				curve: 'straight',
				show: true,
				width: 3,
				colors: [
					KTApp.getSettings()['colors']['theme']['base']['primary'],
					// KTApp.getSettings()['colors']['theme']['base']['warning']
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
						color: KTApp.getSettings()['colors']['theme']['base']['primary'],
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
				KTApp.getSettings()['colors']['theme']['light']['primary'],
				// KTApp.getSettings()['colors']['theme']['light']['warning']
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
					KTApp.getSettings()['colors']['theme']['base']['primary'],
					// KTApp.getSettings()['colors']['theme']['base']['warning']
				],
				strokeWidth: 3
			}
		};

		updateObject(options, changeOption);


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

			$('#filter-jenis_usaha').attr('onchange', 'filterJenisUsaha(\'tanggal\',this)')
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

			$('#filter-jenis_usaha').attr('onchange', 'filterJenisUsaha(\'bulan\',this)')
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
		loadTable(type);
	}

	function imgError(image) {
		image.onerror = "";
		image.src = `${BASE_URL_NO_INDEX}assets/media/noimage.png`;
	}

	switchDashboard = () => {
		let toggle = $('#toggleDashboard').is(":checked");

		if (toggle) {
			$('#dashboardTitle').text('Dashboard POS');
			$('#dashboardPOS').show();
			$('#dashboardPajak').hide();
			$('#toolbar-pos').show();
			$('#toolbar-pajak').hide();
			$("#handlePosBulan").hide();
			dashboardPOS();
		} else {
			$('#dashboardTitle').text('Dashboard Pajak');
			$('#dashboardPOS').hide();
			$('#dashboardPajak').show();
			$('#toolbar-pajak').show();
			$('#toolbar-pos').hide();
		}
	};

	function changeBerdasarkanPos(el) {
		// get value
		var jel = $(el);
		var value = jel.data("value");
		var status = jel.data("status-pos");
		var elbtntanggal = $("#btn-show-tanggal-pos");
		var elbtnbulan = $("#btn-show-bulan-pos");
		var elinputawal = $("#awal_tanggal-pos");
		var elinputakhir = $("#akhir_tanggal-pos");
		var elinputbulan = $("#bulan-pos");
		var elspancalendar = $("#text-calendar-pos");
		var elformtanggal = $("#tanggal-pos");

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

			elformtanggal.attr('action', `javascript:filterPos('tanggal')`);
			filterPos('tanggal');

			$('#filter-jenis_usaha-pos').attr('onchange', 'dashboardPOS(\'tanggal\',this)')
		} else if (value == "bulan") {
			$("#handlePosBulan").show();
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

			elformtanggal.attr('action', `javascript:filterPos('bulan')`);
			filterPos('bulan');

			$('#filter-jenis_usaha-pos').attr('onchange', 'dashboardPOS(\'bulan\',this)')
		}
	}

	// Pick Daily, Weekly, Monthly [Rizal]
	var filterByPeriod = (element) => {
		$('.btn-filter-by-period').removeClass('active')
		$(element).addClass('active')

		let newDate = null;
		if ($(element).data('filter') === 'daily') {
			newDate = "<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-1 day'), 'Y-m-d'); ?>"
		} else if ($(element).data('filter') === 'weekly') {
			newDate = "<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-7 day'), 'Y-m-d'); ?>"
		} else {
			newDate = "<?php echo date_format((new DateTime(date('Y-m-d')))->modify('-30 day'), 'Y-m-d'); ?>"
		}

		$('#awal_tanggal').val(newDate)
		$('#akhir_tanggal').val("<?php echo date('Y-m-d'); ?>")
		$('#tanggal').submit()
	}


	dashboardPOS = (type = "tanggal", el = null) => {
		var awaltanggal = $("#awal_tanggal-pos").val();
		var akhirtanggal = $("#akhir_tanggal-pos").val();
		var bulan = $("#bulan-pos").val();
		var id = null;
		if (el) {
			id = $(el).val()
		}

		HELPER.ajax({
			url: HELPER.api.dashboardPOS,
			data: {
				id,
				type,
				bulan,
				awal_tanggal: awaltanggal,
				akhir_tanggal: akhirtanggal,
			},
			datatype: 'json',
			success: (res) => {
				HELPER.block();
				$('#total_penjualan_barang').text($.number(res.total_penjualan.res));
				// barChart(res.barChart);
				let x = [];
				let y = [];
				$.map(res.barChart, (v, i) => {
					x.push(v.tanggal);
					y.push(v.total);
				});
				if (pos_statistikpenjualan) {
					pos_statistikpenjualan.updateOptions({
						xaxis: {
							categories: x || [],
						},
						chart: {
							// height: 465,
						},
					})

					pos_statistikpenjualan.updateSeries([{
						name: 'Nominal Penjualan',
						data: y || []
					}]);
				}
			},
			complete: () => {
				HELPER.unblock();
			}
		});
	};

	barChart = (data, filter) => {
		let x = [];
		let y = [];

		$.map(data, (v, i) => {
			x.push(v.tanggal);
			y.push(v.total);
		});
		var options = {
			chart: {
				type: 'bar'
			},
			series: [{
				name: 'Nominal Penjualan',
				data: y
			}],
			xaxis: {
				categories: x
			},
			tooltip: {
				y: {
					formatter: function(val) {
						return 'Rp. ' + $.number(val)
					}
				}
			},
			dataLabels: {
				enabled: true,
				formatter: function(val, opts) {
					return 'Rp. ' + $.number(val)
				},
			},
		}
		var chart = new ApexCharts(document.querySelector("#barChart"), options);
		chart.render();

		return chart;
	};

	filterPos = (type = 'tanggal') => {
		// console.log('filterpos');
		let filter = {};
		if (type == 'tanggal') {
			var m_awaltanggal = moment($("#awal_tanggal-pos").val(), "YYYY-MM-DD").format("MMM DD, YY");
			var m_akhirtanggal = moment($("#akhir_tanggal-pos").val(), "YYYY-MM-DD").format("MMM DD, YY");
			$("#text-calendar").text(m_awaltanggal + " - " + m_akhirtanggal);

			let filter = {
				type: type,
				date: {
					m_awaltanggal: m_awaltanggal,
					m_akhirtanggal: m_akhirtanggal
				}
			};
		} else if (type == 'bulan') {
			var m_bulan = moment($("#bulan").val(), "YYYY-MM").format("MMMM YY");
			$("#text-calendar").text(m_bulan);

			let filter = {
				type: type,
				date: {
					m_bulan: m_bulan
				}
			};
		}

		dashboardPOS(type);
	}

	function loadUserActiveDashboard() {
		$('#user_active_dashboard').html('');
		$.get(BASE_URL + 'main/getTokoStatus', function(res) {
			$('#user_active_dashboard').html('');
			res.map((item, index) => {
				$('#user_active_dashboard').append(`
				<div>
					<div class="d-flex flex-row align-items-center py-5 bg-hover-light" title="${item.toko_nama}" onclick="collapseToko(this)" data-toko_kode="${item.toko_kode}" data-target="#collapse_dashboard_${index}" aria-expanded="false" aria-controls="collapse_dashboard_${index}">
						<div class="symbol symbol-circle symbol-40 mr-3">
							<img alt="Pic" src="<?= base_url() ?>${item.toko_logo}" onerror="imgMainError(this)">
						</div>
						<div 
							class="text-break" 
							style="
								display:inline-block;
								white-space: nowrap;
								overflow: hidden;
								text-overflow: ellipsis;
								max-width: 25ch;
							"
						>
							<a href="#" class="text-dark-75 text-hover-primary font-weight-bold">${item.toko_nama}</a>
						</div>
						${item.history_is_online == "1" ?
						`<div class="flex-grow-1 text-right">
							<span class="font-weight-bold text-muted font-size-sm">Online</span>
							<span class="label label-dot label-primary"></span>
						</div>`
						:
						`<div class="flex-grow-1 text-right">
							<span class="font-weight-bold text-muted font-size-sm">Offline</span>
							<span class="label label-dot label-secondary"></span>
						</div>`
					}
					</div>
					<div class="collapse" id="collapse_dashboard_${index}">
					</div>
				</div>
				`)
			})
		})
	}

	function onlineActivityUser(page = 0) {
		HELPER.loadData({
			url: HELPER.api.onlineActivityUser,
			server: true,
			data: {
				page,
			},
			callback: function(res) {
				// console.log(res);
				if (res.data) {
					$('#count-online-user-activity').text(`${res.total} Users`);
					if (page == 0) {
						$('#online-user').html('');
					}
					$('#load-more-online-activity').remove();
					res.data.map((item, index) => {
						var status = 'text-secondary';
						if (item.status_active == 'Active') status = 'text-success';
						if (item.status_active == 'Inactive') status = 'text-warning';
						if (item.status_active == 'Offline') status = 'text-danger';
						if (item.status_active == 'Close') status = 'text-dark';
						$('#online-user').append(`
							<div class="card-user">
								<img src="<?= base_url('dokumen/dashboard_rzl/shop.png'); ?>" alt="" />
								<span class="nama">${item.toko_nama}</span>

								<div class="d-flex align-items-center mt-1">
									<span class="status-dot" style="background:${getColor(status)}"></span>
									<span class="status-label" style="color:${getColor(status)}">${item.status_active}</span>
								</div>
							</div>
						`);
						$('[data-toggle="tooltip"]').tooltip()
					});
					if (((page + 1) * res.limit) < res.total) {
						$('#online-user').append(`<button class="btn btn-secondary" id="load-more-online-activity" onclick="onlineActivityUser(${page + 1})">Load More</button>`)
					}
				}
			}
		});
	}

	function getColor(status) {
		if (status === 'text-success') return '#17c653';
		if (status === 'text-warning') return '#f6c000';
		if (status === 'text-danger') return '#f1416c';
		if (status === 'text-dark') return '#181c32';
		return '#888';
	}

	function onDetailTransaksiTerakhir() {
		HELPER.block();
		$.ajax({
			url: HELPER.api.table,
			dataType: 'json',
			success: function(res) {
				const tbody = $('#table-transaksi-terakhir-detail tbody');
				tbody.html('');

				HELPER.toggleForm({
					tohide: 'dashboard_data',
					toshow: 'transaksi_detail_data'
				});

				HELPER.unblock();

				if (res.transaksi_terakhir_all && res.transaksi_terakhir_all.length > 0) {
					$.each(res.transaksi_terakhir_all, function(i, v) {
						tbody.append(`
						<tr>
							<td class="text-center">${i + 1}</td>
							<td class="text-nowrap">${v.nama_wp}</td>
							<td>${v.npwpd}</td>
							<td>${v.no_transaksi}</td>
							<td class="text-end">${$.number(v.sub_total)}</td>
							<td class="text-end">${$.number(v.jumlah_pajak)}</td>
							<td class="text-end text-nowrap">${v.tanggal_transaksi}</td>
						</tr>
					`);
					});
				} else {
					tbody.append(`
					<tr>
						<td colspan="6" class="text-center text-muted">
							Tidak ada transaksi terakhir
						</td>
					</tr>
				`);
				}
			},
			error: function() {
				HELPER.unblock();
			}
		});
	}


	function backToDashboard() {
		HELPER.toggleForm({
			tohide: 'transaksi_detail_data',
			toshow: 'dashboard_data'
		});
	}

	document.addEventListener("DOMContentLoaded", function() {
		var options = {
			chart: {
				type: 'line',
				height: 300
			},
			series: [{
				name: 'Sales',
				data: [10, 20, 30, 40, 50, 60, 70]
			}],
			xaxis: {
				categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
			}
		};

		var chart = new ApexCharts(document.querySelector("#kt_charts_widget_3"), options);
		chart.render();
	});
</script>
<!-- <script type="module">
	import {
		io
	} from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";
	var socket = io('<?= $_ENV['SOCKET_CONNECT'] ?>'); //Server Sekawan
	// var socket = io("https://192.168.100.59:3000"); //IP Sena
	socket.on("refreshUserOnline", (arg) => {
		if (arg) {
			loadUserActiveDashboard();
		}
	});
</script> -->