<script type="text/javascript">
	var avatar5 = new KTImageInput('user_foto');
	var fv;

	$(function() {
		moment.locale('id');
		HELPER.set_role_access(<?= $role ?>)

		$('#cari-menu-sidebar').donetyping(function() {
			searchMenu($(this).val())
		})

		var firstClick = "<?= $firstClick ?>"
		$("#" + firstClick).click();

		loadUserActive();

		initializePemdaDropdown();
		window.selectPemda = function(pemdaId, pemdaName, element) {
			$.ajax({
				url: BASE_URL + 'pemda/set_pemda/' + pemdaId,
				method: 'GET',
				beforeSend: function() {
					$(element).addClass('loading');
				},
				success: function(response) {
					if (response.success && response.data) {
						updateSelectedPemdaDisplay(
							response.data.pemda_id,
							response.data.pemda_nama
						);
					}
					$('.pemda-card').removeClass('card-selected');
					$('.pemda-card').css('border', '1px solid #e4e6ef');
					$(element).find('.pemda-card').addClass('card-selected');
					$(element).find('.pemda-card').css('border', '2px solid #3699ff');
					if (typeof $(".menu-item-active>a").click === 'function') {
						$(".menu-item-active>a").click();
					}
					setTimeout(function() {
						$('#pemda-dropdown .dropdown-toggle').dropdown('hide');
					}, 300);
					toastr.success('Pemda berhasil dipilih: ' + pemdaName, 'Sukses', {
						timeOut: 2000,
						progressBar: true
					});
				},
				error: function() {
					toastr.error('Gagal memilih pemda', 'Error');
				},
				complete: function() {
					$(element).removeClass('loading');
				}
			});
		}

		window.selectAllPemda = function() {
			$.ajax({
				url: BASE_URL + 'pemda/set_pemda/0',
				method: 'GET',
				success: function() {
					$('#selected-pemda-name').text('Semua Pemda');
					$('#pemda-dropdown .symbol-label').html('<i class="fas fa-globe text-primary fs-4"></i>');
					$('.pemda-card').removeClass('card-selected');
					$('.pemda-card').css('border', '1px solid #e4e6ef');
					if (typeof $(".menu-item-active>a").click === 'function') {
						$(".menu-item-active>a").click();
					}
					$('#pemda-dropdown .dropdown-toggle').dropdown('hide');
					toastr.success('Menampilkan semua pemda', 'Sukses');
				}
			});
		}

	});

	function initializePemdaDropdown() {
		const pemdaId = '<?= $this->session->userdata("pemda_id") ?>';
		const pemdaNama = '<?= $this->session->userdata("pemda_nama") ?>';
		const pemdaLogo = '<?= $this->session->userdata("pemda_logo") ?>';
		loadPemdaData(function() {
			if (pemdaId && pemdaId !== '0' && pemdaNama) {
				$('#selected-pemda-name').text(pemdaNama);

				if (pemdaLogo) {
					$('#pemda-dropdown .symbol-label').html(`
						<img src="${BASE_URL_NO_INDEX}dokumen/pemda/${pemdaLogo}"
							style="width:30px;height:30px;object-fit:contain">
					`);
				} else {
					$('#pemda-dropdown .symbol-label').html(
						'<i class="fas fa-city text-primary fs-4"></i>'
					);
				}
			} else {
				$('#selected-pemda-name').text('Semua Pemda');
				$('#pemda-dropdown .symbol-label').html(
					'<i class="fas fa-globe text-primary fs-4"></i>'
				);
			}
		});
	}

	function loadPemdaData() {
		$.ajax({
			url: BASE_URL + 'pemda/select',
			method: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('#pemda-horizontal-list').html(`
                <div class="text-center py-10 w-100">
                    <div class="spinner spinner-primary"></div>
                    <div class="text-muted mt-2">Memuat data pemda...</div>
                </div>
            `);
			},
			success: function(response) {
				if (response.success && response.data) {
					renderPemdaHorizontalList(response.data);
					if (typeof callback === 'function') {
						callback();
					}
				}
			},
			error: function() {
				$('#pemda-horizontal-list').html(`
                <div class="text-center py-10 w-100">
                    <i class="flaticon2-warning icon-2x text-danger"></i>
                    <div class="text-danger mt-2">Gagal memuat data pemda</div>
                </div>
            `);
			}
		});
	}

	function renderPemdaHorizontalList(pemdaList) {
		let html = '';

		if (!pemdaList || pemdaList.length === 0) {
			html = `
            <div class="col-12 text-center py-10">
                <i class="flaticon2-file icon-2x text-muted"></i>
                <div class="text-muted mt-2">Tidak ada data pemda</div>
            </div>
        `;
		} else {
			const activePemda = pemdaList.filter(function(pemda) {
				return pemda.pemda_deleted_at === null || pemda.pemda_deleted_at === '';
			});

			activePemda.forEach(function(pemda, index) {
				const isSelected = pemda.pemda_id === '<?= $this->session->userdata("pemda_id") ?>';
				let logoUrl = BASE_URL_NO_INDEX + 'assets/media/noimage.png';
				if (pemda.pemda_logo) {
					logoUrl = BASE_URL_NO_INDEX + 'dokumen/pemda/' + pemda.pemda_logo;
				}

				html += `
                <!-- Kolom dengan 5 item per baris -->
                <div class="col-lg-2-4 col-md-3 col-sm-4 col-6 mb-4 pemda-grid-item" 
                     data-pemda-id="${pemda.pemda_id}"
                     data-pemda-name="${pemda.pemda_nama.replace(/"/g, '&quot;')}">
                    <div class="card card-custom shadow-sm h-100 pemda-card ${isSelected ? 'card-selected' : ''}" 
                         style="cursor: pointer; border-radius: 8px; border: ${isSelected ? '2px solid #3699ff' : '1px solid #e4e6ef'}; height: 200px;"
                         onclick="selectPemda('${pemda.pemda_id}', '${pemda.pemda_nama.replace(/'/g, "\\'")}', this)">
                        <div class="card-body d-flex flex-column align-items-center p-3">
                            <!-- Logo container dengan ukuran 80x80 -->
                            <div class="logo-container mb-3" style="width: 80px; height: 80px;">
                                <div class="position-relative w-100 h-100" 
                                     style="overflow: hidden; background: transparent;">
                                    <!-- Image dengan ukuran 80x80 -->
                                    <img src="${logoUrl}" 
                                         class="pemda-logo h-100 w-100"
                                         alt="${pemda.pemda_nama}"
                                         data-pemda-id="${pemda.pemda_id}"
                                         style="object-fit: cover;"
                                         onload="handleImageLoad(this)"
                                         onerror="handleImageError(this)">
                                    <!-- Placeholder icon -->
                                    <div class="position-absolute top-50 left-50 translate-middle placeholder-icon" 
                                         style="z-index: 1; transform: translate(-50%, -50%);">
                                        <i class="fas fa-city text-primary fs-3"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Nama Pemda -->
                            <div class="text-center w-100 flex-grow-1">
                                <h6 class="font-weight-bold text-dark mb-1 text-truncate" 
                                    style="font-size: 0.8rem; line-height: 1.2; max-width: 100%;">
                                    ${pemda.pemda_nama}
                                </h6>
                            </div>
                            
                            <!-- Status Badge - SELALU AKTIF -->
                            <div class="mt-2">
                                <span class="badge badge-success badge-pill font-weight-bold" 
                                      style="font-size: 0.6rem; padding: 2px 6px;">
                                    AKTIF
                                </span>
                            </div>
                            
                            <!-- Selected Checkmark -->
                            ${isSelected ? `
                                <div class="mt-1">
                                    <i class="fas fa-check-circle text-success"></i>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
			});
		}

		$('#pemda-grid').html(html);
		preloadPemdaImages();
	}

	function handleImageLoad(imgElement) {
		$(imgElement).css('opacity', '1');
		$(imgElement).siblings('.placeholder-icon').hide();
	}

	function handleImageError(imgElement) {
		const pemdaId = $(imgElement).data('pemda-id');
		const defaultImage = BASE_URL_NO_INDEX + 'assets/media/noimage.png';
		if (imgElement.src !== defaultImage) {
			$(imgElement).css('opacity', '0');
			setTimeout(() => {
				imgElement.src = defaultImage + '?t=' + new Date().getTime();
				$(imgElement).css('opacity', '1');
				$(imgElement).css({
					'width': '80px',
					'height': '80px',
					'object-fit': 'cover'
				});
			}, 10);
		}

		$(imgElement).siblings('.placeholder-icon').show();
	}

	function preloadPemdaImages() {
		$('.pemda-logo').each(function() {
			const img = this;
			const originalSrc = img.src;
			const defaultImagePath = BASE_URL_NO_INDEX + 'assets/media/noimage.png';
			if (originalSrc !== defaultImagePath && !originalSrc.includes('noimage.png')) {
				const testImage = new Image();
				testImage.onload = function() {
					if (img.src === originalSrc) {
						handleImageLoad(img);
					}
				};
				testImage.onerror = function() {
					if (img.src === originalSrc) {
						handleImageError(img);
					}
				};
				testImage.src = originalSrc;
			} else {
				$(img).css({
					'width': '80px',
					'height': '80px',
					'object-fit': 'cover'
				});
			}
		});
	}

	function updateSelectedPemdaDisplay(pemdaId, pemdaName) {
		$('#selected-pemda-name').text(pemdaName);
		const pemdaItem = $(`.pemda-grid-item[data-pemda-id="${pemdaId}"]`);
		const logoElement = pemdaItem.find('.pemda-logo');

		if (logoElement.length > 0) {
			const logoUrl = logoElement.attr('src');
			const testImage = new Image();
			testImage.onload = function() {
				$('#pemda-dropdown .symbol-label').html(`
                <img src="${logoUrl}" 
                     class="h-100 w-100" 
                     style="object-fit: cover; width: 30px; height: 30px;"
                     onerror="this.onerror=null; this.src='${BASE_URL_NO_INDEX}assets/media/noimage.png'">
            `);
			};
			testImage.onerror = function() {
				$('#pemda-dropdown .symbol-label').html(`
                <i class="fas fa-city text-primary fs-4"></i>
            `);
			};
			testImage.src = logoUrl;
		} else {
			$('#pemda-dropdown .symbol-label').html(`
            <i class="fas fa-city text-primary fs-4"></i>
        `);
		}
	}

	function loadUserActive() {
		$('#user_active').html('');
		$.get(BASE_URL + 'main/getTokoStatus', function(res) {
			$('#user_active').html('');
			if (!Array.isArray(res)) return;
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
</script>