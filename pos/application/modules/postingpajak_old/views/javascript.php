<script type="text/javascript">
	$(function() {
		HELPER.fields = [
			'realisasi_id',
			'realisasi_no',
			'realisasi_wajibpajak_id',
			'realisasi_wajibpajak_npwpd',
			'realisasi_tanggal',
			'realisasi_sub_total',
			'realisasi_jasa',
			'realisasi_pajak',
			'realisasi_total',
			'realisasi_created_at',
			'realisasi_created_by',
			'realisasi_updated_at',
			'realisasi_updated_by',
		];
		HELPER.setRequired([]);
		HELPER.api = {
			table: BASE_URL + 'postingpajak/getDtNew',
			read: BASE_URL + 'postingpajak/read',
			store: BASE_URL + 'postingpajak/store',
			update: BASE_URL + 'postingpajak/store',
			destroy: BASE_URL + 'postingpajak/destroy',
			getlaporan: BASE_URL + 'postingpajak/getlaporan',
			getLaporanRekap: BASE_URL + 'postingpajak/get_laporan_rekap',
		}
		/*HELPER.initTable({
			el : 'table-upload',
			url: HELPER.api.table,
		})*/

		$('#laporan_realisasi').on('change', function() {
			//get the file name
			var fileName = $(this).val();
			//replace the "Choose a file" label
			$(this).next('.custom-file-label').html(fileName);
		})

		var arrows;
		if (KTUtil.isRTL()) {
			arrows = {
				leftArrow: '<i class="la la-angle-right"></i>',
				rightArrow: '<i class="la la-angle-left"></i>'
			}
		} else {
			arrows = {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		}

		$('.datepicker').datepicker({
			rtl: KTUtil.isRTL(),
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			orientation: "bottom left",
			templates: arrows,
			format: "yyyy-mm",
			endDate: "yesterday",
			startView: "months",
			minViewMode: "months"
		});

		loadTable();
		// loadDatepicker();
	});

	function loadDatepicker() {
		$.ajax({
			url: BASE_URL + 'postingpajak/select',
			type: 'post',
			dataType: 'json',
			success: function(res) {
				var alreadyposted = [];

				if (res.success) {
					res.data.forEach(item => {
						alreadyposted.push(item.realisasi_tanggal)
					})
				}

				var arrows;
				if (KTUtil.isRTL()) {
					arrows = {
						leftArrow: '<i class="la la-angle-right"></i>',
						rightArrow: '<i class="la la-angle-left"></i>'
					}
				} else {
					arrows = {
						leftArrow: '<i class="la la-angle-left"></i>',
						rightArrow: '<i class="la la-angle-right"></i>'
					}
				}

				$('.datepicker').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					orientation: "bottom left",
					templates: arrows,
					format: "dd/mm/yyyy",
					endDate: "yesterday",
					startDate: "<?= date('d/m/Y', strtotime($this->session->userdata('toko')['toko_verified_at'])) ?>",
					beforeShowDay: function(date) {
						let calender_date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + ('0' + date.getDate()).slice(-2);

						var search_index = $.inArray(calender_date, alreadyposted);

						if (search_index > -1) {
							return {
								classes: 'highlight',
								tooltip: 'Sudah Posting Pajak.'
							};
						}
					}
				});
			},
			complete: function(res) {

			}
		})

	}

	function loadTable() {
		// let show_aksi = (HELPER.get_role_access('satuan-Update') || HELPER.get_role_access('satuan-Delete'));
		HELPER.initTable({
			el: "table-upload",
			url: HELPER.api.table,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			sorting: 'desc',
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['realisasi_created_at'];
					},
				},{
					targets: 2,
					render: function(data, type, full, meta) {
						return moment(full['realisasi_parent_transaksi_terakhir']).format('MMMM/YYYY');
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return $.number(full['realisasi_parent_jml_transaksi']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_parent_omzet']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['realisasi_parent_total_pajak']);
					},
				}
			],
			fnDrawCallback: function(settings){
				var {
					sumtotal: {
						omzet = 0,
						pajak = 0,
						jml_trf = 0,
					}
				} = settings.json;

				$('#postingpajak_omzet').text(`Rp. ${$.number(omzet)}`);
				$('#postingpajak_pajak').text(`Rp. ${$.number(pajak)}`);
				$('#postingpajak_jml_trf').text(`${$.number(jml_trf)}`);
			}
		});
	}

	function onDelete(realisasi_id) {
		HELPER.confirm({
			message: 'Are you sure you want to delete?',
			callback: function(suc) {
				if (suc) {
					HELPER.ajax({
						url: BASE_URL + 'upload/delete',
						data: {
							id: realisasi_id
						},
						complete: function(res) {
							console.log(res);
							if (res.success) {
								HELPER.showMessage({
									success: true,
									title: 'Success',
									message: 'You have successfully deleted data.'
								})

								onRefresh();
							} else {
								HELPER.showMessage({
									success: 'info',
									title: 'Stop',
									message: res.message
								})
							}
							HELPER.unblock(100)
						}
					})
				}
			}
		})
	}

	function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				realisasi_id: id
			},
		})
	}

	function onBack() {
		HELPER.back();
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-upload'
		});
		$('#periode_upload').val('');
		$('#laporan_realisasi').val('');
	}

	function getlaporan() {
		HELPER.block();
		HELPER.ajax({
			url: HELPER.api.getlaporan,
			data: $('#form-realisasi-laporan').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {
				$('#tanggal').val($('#periode_tanggal').val());
				$('.hasil-laporan').show();
				$("#pdf-laporan object").attr("data", res.record);
				HELPER.unblock();
			}
		})
	}

	function save() {
		var form = $('#form-realisasi')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
		HELPER.save({
			form: 'form-realisasi',
			data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success, id, record, message) {
				if (success === true) {
					if ($('#btn_save').val() == "1") {
						onPrint(id);
					} else {
						$('.body-form').removeClass('animated fadeInUp portlet-fullscreen');
						$('.body-form').addClass('animated fadeOutDown');
					}
					// onRefresh();
					// HELPER.back({});
					$('#btn-PostingPajak').trigger('click')
				}
			}
		})
	}

	function onForm(val = false, el) {
		if (val) {
			$("#col-form-posting-pajak").removeClass('d-none');
			$(el).attr('class', 'btn btn-warning btn-sm');
			$(el).find('.fa').attr('class', 'fa fa-minus');
		} else {
			$("#col-form-posting-pajak").addClass('d-none');
			$(el).attr('class', 'btn btn-success btn-sm');
			$(el).find('.fa').attr('class', 'fa fa-plus');
		}
		$(el).attr('onclick', `onForm(${!val}, this)`);
	}

	function onAdd() {
		HELPER.toggleForm({});
		$('#tanggal').val("");
		$('.hasil-laporan').hide();
		$("#pdf-laporan object").attr("data", "");
	}
</script>