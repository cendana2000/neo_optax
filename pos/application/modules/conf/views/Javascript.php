<script type="text/javascript">
	var avatar1 = new KTImageInput('struk_logo_img');
	
	$(function () {
		HELPER.fields = [
		];

		loadConfig()
	})

	function openTab(el, name) {
		$('.nav-conf').removeClass('active')
		$('.tab-conf').hide()
		setTimeout(function () {
			$(el).addClass('active')
            $(".tab-"+name).show()
		}, 200)
	}

	function loadConfig() {
		HELPER.ajax({
			url: BASE_URL + 'conf/get',
			complete: function (res) {
				if (res.success) {
					
					$.each(res.data, function(iData, vData) {
						if (iData == 'struk_header') {
							
							$.each(vData, function(i, v) {
								if(v.conf_code == 'struk_is_antrian'){
									if(v.conf_value == 'true'){
										$('#struk_is_antrian').prop('checked', true)
									}else{
										$('#struk_is_antrian').prop('checked', false)
									}
									$('#struk_is_antrian').val('true')
								}else if(v.conf_code == 'struk_is_title_show'){
									if(v.conf_value == 'true'){
										$('#struk_is_title_show').prop('checked', true)
									}else{
										$('#struk_is_title_show').prop('checked', false)
									}
									$('#struk_is_title_show').val('true')
								}else if(v.conf_code == 'struk_is_logo'){
									if(v.conf_value == 'true'){
										$('#struk_is_logo').prop('checked', true)
									}else{
										$('#struk_is_logo').prop('checked', false)
									}
									$('#struk_is_logo').val('true')
								}else if(v.conf_code == 'struk_logo'){
									$('#struk_logo_img').attr('style', `background-image: url(${BASE_URL_NO_INDEX}assets/master/kasir/${v.conf_value})`)
								}

								$('#'+v.conf_code).val(v.conf_value)
							})

						}else{
							$.each(vData, function(i, v) {
								$('#'+v.conf_code).val(v.conf_value)
							})
						}

					});

				}
			}
		})
	}

	function save(name) {
		var form = $('#'+name)[0];
		var formData = new FormData(form);
		HELPER.save({
			url: BASE_URL + 'conf/store',
			cache : false,
			data  : formData, 
			contentType : false, 
			processData : false,
			form : name,
			confirm: true,
			callback : function(success,id,record,message)
			{
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Sukses",
						message: "Successfully saved data"
					});
					// onBack()
					// onReset()
					// loadTable()
				}else{
					HELPER.showMessage({
						success: false
					})
				}
				HELPER.unblock(100);
			},
			oncancel: function (result) {
				HELPER.unblock(100);
			}
		});
	}

</script>