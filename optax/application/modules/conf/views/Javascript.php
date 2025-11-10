<script type="text/javascript">
	
	$(function () {
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
						
						if (iData == 'jadwal') {

							$.each(vData, function(i, v) {
								var is_checked = '';
								if (v.conf_value == 'true') { is_checked = 'checked' }
								var resJadwal = `
									<div class="col">
										<div class="form-group text-center">
					                        <label for="${v.conf_code}">${v.conf_title}</label>
					                        <label class="checkbox checkbox-success">
											<input type="checkbox" name="${v.conf_code}" value="true" ${is_checked}>
											<span class="mx-auto"></span></label>
					                    </div>
									</div>
								`;
								$('#list-form-jadwal').append(resJadwal)

							});

						}else if (iData == 'jarak_absen') {
							
							$('#jarak_absen_driver').val(vData[0]['conf_value'])

						}else if (iData == 'jam_kerja') {
							
							$.each(vData, function(i, v) {
								$('#'+v.conf_code).timepicker({
						            defaultTime: v.conf_value,
						            minuteStep: 5,
						            showSeconds: false,
						            showMeridian: false
						        });
							});

						}else if (iData == 'conf_app') {
							
							$.each(vData, function(i, v) {
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
					// onBack();
					// onReset();
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