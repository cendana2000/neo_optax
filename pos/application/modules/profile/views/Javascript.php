<script type="text/javascript">
	var avatar5 = new KTImageInput('user_foto');

	var fv;
	$(function() {
		HELPER.fields = [
			'user_id',
			'user_company_id',
			'user_hak_akses_id',
			'user_nama',
			'user_alamat',
			'user_telepon',
			'user_email',
			'user_status',
			'user_foto',
			'user_lat',
			'user_long',
			'user_password'
		];
		HELPER.api = {
			store: BASE_URL + 'profile/store',
			update: BASE_URL + 'profile/update',
			changePassword: BASE_URL + 'profile/changePassword',
			cekEmail: BASE_URL + 'profile/cekEmail',
		};

		fv = HELPER.newHandleValidation({
			el: 'form-profile',
			useRegex: true,
			declarative: true,
			setting: [{
				name: "Email User",
				selector: ".user_email",
				rule: {
					promise: {
						promise: function(input) {

							return new Promise(function(resolve, reject) {

								var cekValid = FormValidation.validators.emailAddress().validate({
									value: $(input.element).val(),
								});

								if (cekValid.valid) {
									HELPER.ajax({
										url: HELPER.api.cekEmail,
										data: {
											email: $(input.element).val()
										},
										success: function(res) {
											if (res.success) {
												resolve({
													valid: true // Required
												});
											} else {
												if ($('#user_id').val() == res.id) {
													resolve({
														valid: true // Required
													});
												} else {
													resolve({
														valid: false, // Required
														message: 'Email must be unique', // Optional
													});
												}
											}
										},
										error: function() {
											resolve({
												valid: false, // Required
												message: 'Error Checking.', // Optional
											});
										}
									})
								} else {
									resolve({
										valid: false, // Required
										message: 'Invalid email.', // Optional
									});
								}

							});

						}
					}
				},
			}]
		});

		fv = HELPER.newHandleValidation({
			el: 'form-edit-password',
			useRegex: true,
			declarative: true,
			setting: [{
				name: "Konfirmasi Password",
				selector: "#password_repeat",
				rule: {
					identical: {
						compare: function() {
							return $('#password_new').val()
						}
					}
				}
			}]
		});
		loadProfile()
	})

	function loadProfile() {
		HELPER.ajax({
			url: BASE_URL + 'profile/load',
			complete: function(res) {
				if (res.success) {
					var data = res.data;
					var userimage = 'assets/media/noimage.png';
					if (data.user_foto) {
						userimage = 'dokumen/user/' + data.user_foto
					};
					$('.show-foto').css('background-image', "url('" + userimage + "')");
					$('.show-nama').text(data.user_nama)
					$('.show-hak_akses').text(res.role.role_access_nama)
					$('.show-region').text(data.region_nama)
					$('.show-email').text(data.user_email)
					$('.show-notelp').text(data.user_telepon)
					$('.show-alamat').text(data.user_alamat)
					$('#profile_id').val(data.user_id)
					$.each(data, function(i, v) {
						$('.detail-' + i).text(HELPER.nullConverter(v))
					});
					$('.detail-hak_akses_nama').text(res.role.role_access_nama);
					var lastChange = data.user_last_change_password ? moment(data.user_last_change_password).format('LLL') : "-";
					$('.detail-last_change_password').text(lastChange);

				}
			}
		})
	}

	function onReset() {
		$('#password_old, #password_new, #password_repeat').val('')
	}

	function savePassword(name) {
		var form = $('#' + name)[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			url: HELPER.api.changePassword,
			data: formData,
			contentType: false,
			processData: false,
			form: name,
			confirm: true,
			callback: function(success, id, record, message) {
				HELPER.unblock();
				if (success) {
					onReset()
				}
			},
			oncancel: function(result) {
				HELPER.unblock(100);
			}
		});
	}

	function onEdit() {

		HELPER.ajax({
			url: BASE_URL + 'profile/read',
			data: {
				id: $('#profile_id').val()
			},
			complete: function(res) {
				$('.edit-profile').show()
				$('.detail-profile').hide()
				$('.btn-edit').hide()
				HELPER.populateForm($('#form-profile'), res);

				if (res.user_foto) {
					$('#user_foto').css('background-image', "url('dokumen/user/" + res.user_foto + "')");
				}
			}
		})
	}

	function save(name) {
		var form = $('#' + name)[0];
		var formData = new FormData(form);
		HELPER.save({
			cache: false,
			data: formData,
			contentType: false,
			processData: false,
			form: name,
			confirm: true,
			callback: function(success, id, record, message) {
				if (success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "Successfully saved data"
					});
					loadProfile()
					$('.edit-profile').hide()
					$('.detail-profile').show()
					$('.btn-edit').show()
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

	function onBack() {
		$('.edit-profile').hide()
		$('.detail-profile').show()
		$('.btn-edit').show()
	}

	function onOldPassSee(name) {
		type = $('#' + name).attr('type')
		if (type == 'password') {
			$('#icon-' + name).attr('class', 'fa fa-eye-slash');
			$('#' + name).prop('type', 'text')
		} else {
			$('#icon-' + name).attr('class', 'fa fa-eye');
			$('#' + name).prop('type', 'password')
		}
	}
</script>