<script type="text/javascript">
	var fv
	var wizardObj;
	$(function() {
		/*setTimeout(function() {
			AOS.init();
		}, 1000)*/

		$('.datepicker').datepicker({
			todayHighlight: 'TRUE',
			autoclose: true,
			format: 'dd-mm-yyyy',
			startDate: '-0d',
		});

		// initWizard()

		HELPER.api = {
			store: BASE_URL + 'login/store',
			update: BASE_URL + 'login/update',
		};

		HELPER.fields = [
			'project_request_id',
		];

		fv = HELPER.newHandleValidation({
			el: 'kt_login_signin_form',
			declarative: true
		})

		fv = HELPER.newHandleValidation({
			el: 'form-user',
			declarative: true
		})
	})

	function doLogin() {
		/*fv.validate().then(function (status) {
			console.log(status);
			if (status = "Valid") {*/
		// if (grecaptcha.getResponse()) {
		console.log(BASE_URL)
		HELPER.block()
		var email = $('#email').val();
		var password = $('#password').val();
		var token = $('#token').val();
		$.ajax({
			type: "POST",
			url: BASE_URL + "login/doLogin",
			data: {
				email: email,
				password: password,
				token: token,
				// recaptcha: grecaptcha.getResponse()
			},
			success: function(response) {
				// grecaptcha.reset();
				$('#password').val('')
				if (response.success) {
					// if (response.hasOwnProperty('is_super')) {
					window.location.reload();
					// }else{
					// 	wizardObj.goNext()
					// 	$('.btn-next').hide()
					// 	loadProject()
					// }
				} else {
					HELPER.unblock()
					HELPER.showMessage({
						success: false,
						message: response.message
					})
				}
			}
		});
		// } else {
		// 	HELPER.showMessage({
		// 		success: 'info',
		// 		message: 'Please fill in the captcha first.'
		// 	})
		// }
		/*}
		})*/
	}

	function initWizard() {
		// Base elements
		var wizardEl = KTUtil.getById('kt_login');
		var form = KTUtil.getById('kt_login_signin_form');
		var validations = [];

		if (!form) {
			return;
		}


		// Initialize form wizard
		wizardObj = new KTWizard(wizardEl, {
			startStep: 1, // initial active step number
			clickableSteps: false // to make steps clickable this set value true and add data-wizard-clickable="true" in HTML for class="wizard" element
		});

		// Change event
		wizardObj.on('change', function(wizard) {
			if (wizard.getStep() == 2) {
				$('.btn-next').show()
			} else {
				$('.btn-next').hide()
			}
			KTUtil.scrollTop();
		});
	}

	function loadProject() {
		$('#div_list_project').html('')
		setTimeout(function() {
			HELPER.ajax({
				url: BASE_URL + 'login/loadProject',
				data: {
					email: $('#email').val(),
				},
				success: function(res) {
					HELPER.unblock()
					if (res.success) {
						if (parseInt(res.total) > 0) {
							$.each(res.data, function(index, val) {
								var content = `
		    						<div class="col-12">
			    						<div class="d-flex flex-wrap align-items-center pb-10">
											<!--begin::Title-->
											<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">
												<a href="javascript:void(0)" class="text-dark font-weight-bold text-hover-primary mb-1 font-size-lg" onclick="nextLogin('${val.user_project_project_id}')">${val.project_code}</a>
												<span class="text-muted font-weight-bold" title="${val.project_description}">${HELPER.text_truncate(val.project_description, 50)}</span>
											</div>
											<!--end::Title-->
											<!--begin::Btn-->
											<a href="javascript:void(0)" class="btn btn-icon btn-light" onclick="nextLogin('${val.user_project_project_id}')">
												<span class="svg-icon svg-icon-success">
													<span class="svg-icon svg-icon-md">
														<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<polygon points="0 0 24 0 24 24 0 24" />
																<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
																<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
															</g>
														</svg>
														<!--end::Svg Icon-->
													</span>
												</span>
											</a>
											<!--end::Btn-->
										</div>
		    						</div>
		    					`
								$('#div_list_project').append(content)
							});
						} else {
							$('#div_list_project').html('<h3>You are not registered with any Project.</h3>')
						}

					} else {
						HELPER.showMessage()
					}
				},
				error: function(argument) {
					HELPER.showMessage()
					HELPER.unblock()
				}
			})
		}, 300)
	}

	function nextLogin(project_id) {
		HELPER.block()
		HELPER.ajax({
			url: BASE_URL + 'login/nextLogin',
			data: {
				email: $('#email').val(),
				project_id: project_id
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

	function onRegister() {
		$("#modalData").modal('show')
		$('#form-user').trigger('reset');
		$.each(HELPER.fields, function(i, v) {
			$('[name="' + v + '"]').val('').trigger('change');
		});
	}

	function save(name) {
		var form = $('#' + name)[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			data: formData,
			contentType: false,
			processData: false,
			form: "form-project",
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "successfully saved data"
					});
					$("#modalData").modal('hide')
					$('#form-user').trigger('reset');
				} else {
					HELPER.showMessage({
						success: false
					})
				}
				HELPER.unblock(100);
			},
			oncancel: function(result) {
				HELPER.unblock(100);
			}
		});
	}
</script>