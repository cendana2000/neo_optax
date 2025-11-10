<script type="text/javascript">
	var select_dokumen = [];
	var select_dokumen_all = [];
	$(function() {
		showAllNotif()

		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.addEventListener('message', function (event) {
				showAllNotif();
			});
		}
	})

  function showAllNotif() {
		showCountNotif()
		showNotifBelumDibaca()
		showNotifDibaca()
		// showNotifKonfirmasi()
	}

  function showCountNotif() {
		HELPER.ajax({
			url: BASE_URL + 'main/countNotif',
			success: function(res) {
				if (parseInt(res.total) > 0) {
					$('#badge-notif-top').text(res.total).removeClass('d-none')
					$('#pulse-notif-top').removeClass('d-none')
				}else{
					$('#badge-notif-top').text(0).addClass('d-none')
					$('#pulse-notif-top').addClass('d-none')
        }
			}
		})
	}

  function showNotifBelumDibaca() {
		$('#div_notif_unread').html('')
		HELPER.initLoadMore({
			perPage: 20,
			urlExist: BASE_URL + 'main/notifBelumDibacaExist',
			dataExist: null,
			urlMore: BASE_URL + 'main/notifBelumDibacaMore',
			dataMore: null,
			callbackExist: function(data) {
				if (data.hasOwnProperty('success')) {
					$('#div_notif_unread').html(`<div class="d-flex flex-center text-center text-muted min-h-200px">No new notifications.</div>`)
					$('#btn_notif_unread').hide()
					$('#badge-notif-unread').text(0).hide()
				} else {
					$('#badge-notif-unread').text(data).show()
					$('#btn_notif_unread').show()
				}
			},
			callbackMore: function(data) {
				var myQueue = new Queue()
				myQueue.enqueue(function(next) {
					KTApp.block($('#topbar_notifications_unread'))
					next()
				}, '1').enqueue(function(next) {
					var data_notif = $.parseJSON(data.responseText);
					if(data_notif.data.length > 0){
						$('#div_notif_unread').html('')
					}
					$.each(data_notif.data, function(i, val) {
						var hasil = `
							<div class="d-flex align-items-center mb-6">
								<div class="symbol symbol-50 symbol-light-primary mr-5 text-center">
									<span class="symbol-label text-center" style="font-size:10px;">
										${moment(val.inbox_datetime).format('DD-MM HH:mm')}
									</span>
								</div>
								<div class="d-flex flex-column font-weight-bold">
									<a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg" onclick="onClickNotifTopbar(this)" data-notif="${btoa(JSON.stringify(val))}">${HELPER.nullConverter(val.inbox_title)}</a>
									<span class="text-muted text-break">${HELPER.nullConverter(val.inbox_message)}</span>
								</div>
							</div>
						`;
						$('#div_notif_unread').append(hasil)

					});
					next()
				}, '2').enqueue(function(next) {
					KTApp.unblock($('#topbar_notifications_unread'))
					next()
				}, '3').dequeueAll()
			},
			scrollCek: function(callLoadMore) {
				$('#btn_notif_unread').on('click', function() {
					KTApp.block($('#topbar_notifications_unread'))
					callLoadMore()
				});
			},
			callbackEnd: function() {
				$('#btn_notif_unread').hide()
				$('#btn_notif_unread').off('click');
			}
		})
	}

	function showNotifDibaca() {
		$('#div_notif_read').html('')
		HELPER.initLoadMore({
			perPage: 20,
			urlExist: BASE_URL + 'main/notifDibacaExist',
			dataExist: null,
			urlMore: BASE_URL + 'main/notifDibacaMore',
			dataMore: null,
			callbackExist: function(data) {
				if (data.hasOwnProperty('success')) {
					$('#div_notif_read').html(`<div class="d-flex flex-center text-center text-muted min-h-200px">No new notifications.</div>`)
					$('#btn_notif_read').hide()
				} else {
					$('#btn_notif_read').show()
				}
			},
			callbackMore: function(data) {
				var myQueue = new Queue()
				myQueue.enqueue(function(next) {
					KTApp.block($('#topbar_notifications_read'))
					next()
				}, '1').enqueue(function(next) {
					var data_notif = $.parseJSON(data.responseText);
					if(data_notif.data.length > 0){
						$('#div_notif_read').html('')
					}
					$.each(data_notif.data, function(i, val) {
						var hasil = `
							<div class="d-flex align-items-center mb-6">
								<div class="symbol symbol-50 symbol-light-primary mr-5 text-center">
									<span class="symbol-label text-center" style="font-size:10px;">
										${moment(val.inbox_datetime).format('DD-MM HH:mm')}
									</span>
								</div>
								<div class="d-flex flex-column font-weight-bold">
									<a href="javascript:void(0)" class="text-dark text-hover-primary mb-1 font-size-lg">${HELPER.nullConverter(val.inbox_title)}</a>
									<span class="text-muted text-break">${HELPER.nullConverter(val.inbox_message)}</span>
								</div>
							</div>
						`;
						$('#div_notif_read').append(hasil)

					});
					next()
				}, '2').enqueue(function(next) {
					KTApp.unblock($('#topbar_notifications_read'))
					next()
				}, '3').dequeueAll()
			},
			scrollCek: function(callLoadMore) {
				$('#btn_notif_read').on('click', function() {
					KTApp.block($('#topbar_notifications_read'))
					callLoadMore()
				});
			},
			callbackEnd: function() {
				$('#btn_notif_read').hide()
				$('#btn_notif_read').off('click');
			}
		})
	}

  function onClickNotifTopbar(el) {
		var data = JSON.parse(atob($(el).data('notif')))
		if (HELPER.isNull(data['inbox_opened'])) {
			setReadNotif(data['inbox_id'])
		}
    // if (data['inbox_feature_type'] === 'SPTPD') {
      // $('[data-menu=VerifikasiSptpd-Table]').click();
    // } else {
      HELPER.showMessage({
				success: 'info',
				title: HELPER.nullConverter(data.inbox_title),
				message: HELPER.nullConverter(data.inbox_message),
			})
    // }
  }

  function setReadNotif(idd) {
		HELPER.ajax({
			url: BASE_URL + 'main/setReadNotif',
			data: {
				id: idd
			}
		})
	}
</script>
