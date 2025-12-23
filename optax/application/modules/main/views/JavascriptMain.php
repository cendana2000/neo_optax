<script type="text/javascript">
	var avatar5 = new KTImageInput('user_foto');
	var fv;

	$(function() {
		moment.locale('id');
		HELPER.set_role_access(<?= $role ?>)
		// console.log(<?= $role ?>)
		$('#cari-menu-sidebar').donetyping(function() {
			searchMenu($(this).val())
		})
		var firstClick = "<?= $firstClick ?>"
		$("#" + firstClick).click();

		// detailProject()
		loadUserActive();

		HELPER.createCombo({
			el: 'global_pemda_id',
			url: BASE_URL + 'pemda/select',
			valueField: 'pemda_id',
			displayField: 'pemda_nama',
			displayField2: 'pemda_nama',
			withNull: true,
			grouped: false,
			select2: true,
			allowClear: false,
			placeholder: '-Semua Pemda-',
			callback: function(){
				$('#global_pemda_id').val('<?= $this->session->userdata('pemda_id') ?>').trigger('change')
			}
		})

		$('#global_pemda_id').on('change', function(){
			$.ajax({
				url: BASE_URL + '/pemda/set_pemda/' + this.value,
				success: function(){
					$(".menu-item-active>a").click();
				}
			})
		});
	})

	function loadUserActive() {
		$('#user_active').html('');
		$.get(BASE_URL + 'main/getTokoStatus', function(res) {
			$('#user_active').html('');
			res.map((item, index) => {
				$('#user_active').append(`
				<div>
					<div class="d-flex flex-row align-items-center py-5 bg-hover-light" title="${item.toko_nama}" onclick="collapseToko(this)" data-toko_kode="${item.toko_kode}" data-target="#collapse_${index}" aria-expanded="false" aria-controls="collapse_${index}">
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
					<div class="collapse" id="collapse_${index}">
					</div>
				</div>
				`)
			})
		})
	}

	function collapseToko(el) {
		let target = $(el).data('target');
		let toko_kode = $(el).data('toko_kode');
		if ($(`${target}.show`).length) {
			$(target).collapse('hide');
		} else {
			$(target).html('');
			$.post(BASE_URL + 'main/getTokoUser', {
				toko_kode
			}, function(res) {
				res.map((item, index) => {
					$(target).append(`
					<div 
						class="d-flex flex-row align-items-center py-2 pl-5 ml-5" 
						style="border: 0;
							border-left-color: currentcolor;
							border-left-style: none;
							border-left-width: 0px;
							border-left: 4px solid #EEE5FF;
							border-top-left-radius: 0;
							border-bottom-left-radius: 0;
						"
						title="${item.user_nama}"
					>
						<div class="symbol symbol-circle symbol-40 mr-3">
							<img alt="Pic" src="<?= site_url() ?>../../dev/pos/dokumen/user/${item.user_foto}" onerror="imgMainError(this)">
						</div>
						<div 
							class="text-break"
							style="
								display:inline-block;
								white-space: nowrap;
								overflow: hidden;
								text-overflow: ellipsis;
								max-width: 20ch;
							"
						>
							<a href="#" class="text-dark-75 text-hover-primary font-weight-bold">${item.user_nama}</a>
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
					`)
				})
				$(target).collapse('show');
			});
		}
	}

	function showOnlineUser() {
		$('#kt_quick_user_toggle').trigger('click')
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

	function imgMainError(image) {
		image.onerror = "";
		image.src = `${BASE_URL_NO_INDEX}assets/media/noimage.png`;
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
</script>

<!-- <script type="module">
	import {
		io
	} from "https://cdn.socket.io/4.3.2/socket.io.esm.min.js";
	var socket = io('<?= $_ENV['SOCKET_CONNECT'] ?>'); //Server Sekawan
	// var socket = io("https://192.168.100.59:3000"); //IP Sena

	socket.on("refreshUserOnline", (arg) => {
		if (arg) {
			loadUserActive();
		}
	});
</script> -->