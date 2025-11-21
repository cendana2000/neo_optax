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

	const FILE_FIELD_NAME = 'user_foto';
	const MAX_SIZE = 1900000; // Maksimal 1.9MB (agar aman di bawah batas 2MB NGINX)

	// 1. Fungsi Pengiriman AJAX Kustom (menggantikan HELPER.save untuk upload)
	function sendCustomAjaxRequest(formData) {
		// Tunjukkan loading block jika belum
		HELPER.block();

		// Asumsi URL 'profile/update' sudah ada di dalam form action atau kamu ambil dari BASE_URL
		$.ajax({
			url: BASE_URL + 'profile/update',
			data: formData,
			type: 'post',
			contentType: false, // Wajib untuk FormData
			processData: false, // Wajib untuk FormData
			success: function(res) {
				// Lakukan konfirmasi dan tampilkan pesan seperti HELPER.save kamu

				// Asumsi `res` adalah objek JSON dari controller
				if (res && res.success) {
					HELPER.showMessage({
						success: true,
						title: "Success",
						message: "Successfully saved data"
					});
					loadProfile(); // Asumsi fungsi ini ada
					$('.edit-profile').hide();
					$('.detail-profile').show();
					$('.btn-edit').show();
				} else {
					HELPER.showMessage({
						success: false,
						message: res.message || "Gagal menyimpan data."
					});
				}
			},
			error: function(xhr, status, error) {
				// Logika penanganan error server/jaringan
				HELPER.showMessage({
					success: false,
					title: "Error",
					message: "Terjadi kesalahan server atau koneksi."
				});
			},
			complete: function() {
				HELPER.unblock(100);
			}
		});
	}

	// 2. Fungsi Kompresi dan Pemrosesan File
	function compressAndSend(file, formData, fileName) {
		const reader = new FileReader();

		reader.onload = function(event) {
			const img = new Image();
			img.onload = function() {
				const canvas = document.createElement('canvas');
				let compressedBlob;
				let quality = 0.9; // Mulai dari kualitas tinggi

				// Loop kompresi (opsional, untuk memastikan ukuran)
				do {
					canvas.width = img.width;
					canvas.height = img.height;
					const ctx = canvas.getContext('2d');
					ctx.drawImage(img, 0, 0, img.width, img.height);

					// Dapatkan Blob yang terkompresi (Synchronous/Blocking approach)
					let dataURL = canvas.toDataURL('image/jpeg', quality);

					// Konversi Data URL ke Blob (fungsi helper sederhana)
					compressedBlob = dataURLToBlob(dataURL);

					quality -= 0.1;
					if (quality < 0.3) break; // Batasi penurunan kualitas

				} while (compressedBlob.size > MAX_SIZE);

				// Hapus file asli dan tambahkan yang terkompresi
				formData.delete(FILE_FIELD_NAME);
				formData.append(FILE_FIELD_NAME, compressedBlob, fileName.replace(/\.[^/.]+$/, "") + '.jpg');

				// Lanjut ke pengiriman AJAX
				sendCustomAjaxRequest(formData);

			};
			img.src = event.target.result;
		};
		reader.readAsDataURL(file);
	}

	// 3. Simple Helper untuk konversi DataURL ke Blob
	function dataURLToBlob(dataurl) {
		var arr = dataurl.split(','),
			mime = arr[0].match(/:(.*?);/)[1],
			bstr = atob(arr[1]),
			n = bstr.length,
			u8arr = new Uint8Array(n);
		while (n--) {
			u8arr[n] = bstr.charCodeAt(n);
		}
		return new Blob([u8arr], {
			type: mime
		});
	}

	function save(name) {
		// 1. Dapatkan Form dan FormData
		var form = $('#' + name)[0];
		var formData = new FormData(form);

		// 2. Cek apakah ada file (sama seperti sebelumnya)
		const FILE_FIELD_NAME = 'user_foto'; // Ganti jika berbeda
		const fileInput = document.getElementById(FILE_FIELD_NAME + '_input') || form.querySelector(`input[name="${FILE_FIELD_NAME}"]`);
		const file = fileInput && fileInput.files[0];

		// 3. Ganti confirm() dengan SweetAlert2
		Swal.fire({
			title: 'Konfirmasi Simpan Data',
			text: "Apakah Anda yakin ingin menyimpan perubahan data profile ini?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Simpan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			// Hanya lanjutkan jika tombol 'Ya, Simpan!' ditekan
			if (result.isConfirmed) {

				// 4. Lanjutkan Logika Kompresi & Kirim
				if (file) {
					// Jika ada file, kompres dan kirim (fungsi compressAndSend akan memanggil sendCustomAjaxRequest)
					compressAndSend(file, formData, file.name);
				} else {
					// Jika tidak ada file, kirim langsung
					sendCustomAjaxRequest(formData);
				}
			}
			// Jika dibatalkan, tidak ada yang terjadi
		});
	}

	// Catatan: Pastikan fungsi compressAndSend dan sendCustomAjaxRequest 
	// yang kamu buat sebelumnya sudah tersedia di file JS ini.

	// save old
	// function save(name) {
	// 	var form = $('#' + name)[0];
	// 	var formData = new FormData(form);
	// 	HELPER.save({
	// 		cache: false,
	// 		data: formData,
	// 		contentType: false,
	// 		processData: false,
	// 		form: name,
	// 		confirm: true,
	// 		callback: function(success, id, record, message) {
	// 			if (success) {
	// 				HELPER.showMessage({
	// 					success: true,
	// 					title: "Success",
	// 					message: "Successfully saved data"
	// 				});
	// 				loadProfile()
	// 				$('.edit-profile').hide()
	// 				$('.detail-profile').show()
	// 				$('.btn-edit').show()
	// 			} else {
	// 				HELPER.showMessage({
	// 					success: false
	// 				})
	// 			}
	// 			HELPER.unblock(100);
	// 		},
	// 		oncancel: function(result) {
	// 			HELPER.unblock(100);
	// 		}
	// 	});
	// }
</script>