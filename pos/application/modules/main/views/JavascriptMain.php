<script type="text/javascript">
	var pingTimer = null;
	var BASE_URL_MONITORING = '<?= $_ENV['PAJAK_URL'] ?>'
	$(function() {
		moment.locale('id');
		HELPER.set_role_access(<?= $role ?>)
		$('#cari-menu-sidebar').donetyping(function() {
			searchMenu($(this).val())
		})
		var firstClick = "<?= $firstClick ?>"
		$("#" + firstClick).click();
		// next();
		// var myQ = new Queue()
		// myQ.enqueue(function(next) {
		// 	HELPER.block()
		// 	/*$('body').on('click', function (e) {
		// 		$('[data-toggle="popover"]').each(function () {
		// 		    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
		// 		        $(this).popover('hide');
		// 		    }
		// 		});
		// 	});*/
		// 	next()
		// }, 'satu').enqueue(function(next) {
		// 	setTimeout(function() {
		// 		$("#" + firstClick).click();
		// 		next()
		// 	}, 500)
		// }, 'dua').enqueue(function(next) {
		// 	HELPER.unblock(500)
		// 	next()
		// }, 'tiga').dequeueAll()

		// detailProject()
		// loadProject()
		startPingInterval();
		// loadQr();
	})

	function loadQr() {
		let dataqr = btoa('<?= $this->session->userdata('toko')['toko_wajibpajak_id'] . '.' . $this->session->userdata('user_id') ?>');
		new QRCode(document.getElementById("qrcode"), dataqr);
	}

	function detailProject() {
		HELPER.ajax({
			url: BASE_URL + 'main/detailProject',
			data: {
				id: '<?= $this->session->userdata('user_project_id') ?>'
			},
			complete: function(res) {
				$('.project_header').text(`Project : ${res.project_code} (<?= $this->session->userdata('hak_akses_nama') ?>)`)
				$('.main-project_code').text(res.project_code)
				$('.main-project_location').text(res.project_location)
				$('.main-project_start_date').text(moment(res.project_start_date).format('DD MMMM YYYY'))
				$('.main-project_end_date').text(moment(res.project_end_date).format('DD MMMM YYYY'))
			}
		})
	}


	function searchMenu(val) {
		$('li.sidebar').removeClass('dapet menu-item-open')
		setTimeout(function() {
			var value = val.toUpperCase();
			if (value) {
				$('#kt_aside_menu').scrollTop(0)
				$('.menu-section').hide()
				$.each($('li.sidebar'), function(i, v) {
					if ($(v).text().toUpperCase().indexOf(value) > -1) {
						$(v).addClass('dapet').show()
						$(v).find('li').show()
						$(v).parents('li').show()
						if ($(v).hasClass('menu-item-submenu')) {
							$(v).addClass('menu-item-open')
						} else {
							$(v).parents('li').addClass('menu-item-open')
						}
					} else {
						if (!$(v).find('li').hasClass('dapet') && !$(v).parents('li').hasClass('dapet')) {
							$(v).hide()
						}
					}
				});
			} else {
				$('.menu-section').show()
				$('li.sidebar').show().removeClass('dapet menu-item-open')
			}
		}, 400)
	}

	function onChangeProject(idd, code) {
		HELPER.confirm({
			title: `Switch to Project ${code}`,
			message: `Are you sure you want to switch this project ?`,
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'main/changeProject',
						data: {
							email: '<?= $this->session->userdata('user_email') ?>',
							project_id: idd
						},
						success: function(res) {
							if (res.success) {
								window.location.reload();
							} else {
								HELPER.showMessage()
							}
						}
					})
				}
			}
		})
	}



	function setOffline(cbSuccess) {
		$.post(BASE_URL + "history_login/offline", function(res) {
			if (res.success == true) {
				if (!!cbSuccess) {
					cbSuccess();
				}
			}
		});
	}

	function onLogout() {
		Swal.fire({
			title: 'Warning!',
			text: "Apakah anda yakin akan logout?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya!',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.isConfirmed == true) {
				setOffline(function() {
					window.location.href = BASE_URL + 'login/logout';
				});
			}
		})
	}

	function onChangeLog() {
		$('#list-changelog').empty()
		html = ''
		HELPER.ajax({
			url: BASE_URL + 'main/changeLog',
			success: function(res) {
				$.each(res.data, (key, val) => {
					html_list_change = ''
					$.each(JSON.parse(val.change_log_change_list), (lkey, lval) => {
						html_list_change += `<li>${lval}</li>`
					})
					html += `
							<div class="timeline-item">
								<div class="timeline-badge">
									<div class="bg-success"></div>
								</div>
								<div class="timeline-label">
									<span class="font-weight-bold">${val.change_log_number}</span>
									<p class="mb-0"><span class="text-primary font-weight-bold">${val.change_log_name}</span>, <span class="text-muted">${moment(val.change_log_change_date).format('DD-MM-YYYY')}</span></p>
									<span class="text-muted">${val.change_log_description}</span>
								</div>

								<div class="timeline-content ml-3">
									<ul class="pl-3">
										${html_list_change}
									</ul>
								</div>
							</div>
					`
				})
				$('#list-changelog').append(html);
				$("#modalChangelog").modal('show')
			}
		})
	}

	function startPingInterval() {
		$.ajax({
			url: BASE_URL_MONITORING + "/conf/get",
			method: "POST",
			success: function(res) {
				if (!res.success) return;
				let mobileConfig = res.data.mobile_interval_ping || [];
				let pingConf = mobileConfig.find(c => c.conf_code === "mobile_interval_ping");

				let intervalMinute = pingConf ? parseInt(pingConf.conf_value) : 10;
				let intervalMs = intervalMinute * 60 * 1000;

				// console.log("Ping interval setiap", intervalMinute, "menit");

				if (pingTimer) {
					clearInterval(pingTimer);
				}
				pingTimer = setInterval(() => {
					sendPing();
				}, intervalMs);
			}
		});
	}

	function sendPing() {
		$.ajax({
			url: BASE_URL + "main/ping",
			method: "POST",
			xhrFields: {
				withCredentials: true
			},
			success: function(res) {
				console.log("Ping OK:", res.message);
			},
			error: function() {
				console.log("Ping failed");
			}
		});
	}
</script>