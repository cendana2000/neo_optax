<script type="text/javascript">
	// let LSToko = JSON.parse(localStorage.getItem('toko'));

	$(function() {
		//tambahan
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months",
			defaultDate: '-2m'
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

		HELPER.api = {
			table_verifikasi: BASE_URL + 'sptpd/loadVerifikasi',
			table_verifikasi_setuju: BASE_URL + 'sptpd/loadVerifikasiSetuju',
			table_verifikasi_tolak: BASE_URL + 'sptpd/loadVerifikasiTolak',
			table_verifikasi_pembayaran: BASE_URL + 'sptpd/loadVerifikasiPembayaran',
			table_verifikasi_all: BASE_URL + 'sptpd/loadVerifikasiAll',
			// read: BASE_URL + 'upload/read',
			store: BASE_URL + 'sptpd/store',
			// update: BASE_URL + 'upload/store',
			// destroy: BASE_URL + 'upload/destroy',
			detail: BASE_URL + 'sptpd/detail',
			get_omzet_ajax: BASE_URL + 'sptpd/get_omzet_ajax',
			print: BASE_URL + 'sptpd/print_sptpd',
		}

		HELPER.fields = [
			'sptpd_npwpd',
			'sptpd_bulan_tahun_pajak',
			'sptpd_nominal_omzet',
			'sptpd_nominal_pajak',
		];

		HELPER.setRequired([
			'sptpd_npwpd',
			'sptpd_bulan_tahun_pajak',
			'sptpd_nominal_omzet',
			'sptpd_nominal_pajak',
		]);

		$('#sptpd_nominal_omzet').on('keyup', () => {
			let data = $('#sptpd_nominal_omzet').val();
			let pajak_tarif = "<?= $this->session->userdata('jenis_tarif') ?>";

			$('#sptpd_nominal_pajak').val(data * (pajak_tarif/100))
		})

		loadTable('table-verifikasi');
	});

	function loadTable(table) {

		var table_id;
		var table_controller;

		switch (table) {

			case 'table-verifikasi-disetujui':
				table_controller = HELPER.api.table_verifikasi_setuju;
				break;

			case 'table-verifikasi-ditolak':
				table_controller = HELPER.api.table_verifikasi_tolak;
				break;

			case 'table-verifikasi-lihat-semua':
				table_controller = HELPER.api.table_verifikasi_all;
				break;

			case 'table-verifikasi-pembayaran':
				table_controller = HELPER.api.table_verifikasi_pembayaran;
				break;

			default:
				console.log('default');
				table_controller = HELPER.api.table_verifikasi;
				break;
		}

		HELPER.initTable({
			el: table,
			url: table_controller,
			type: 'POST',
			searchAble: true,
			destroyAble: true,
			responsive: false,
			order: [
				[1, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						// Parse the date string
						var date = moment(full['sptpd_bulan_tahun_pajak'], 'YYYY-MM-DD');

						// Format the date to "YYYY-MM"
						return date.format('YYYY-MM');
						return full['sptpd_bulan_tahun_pajak'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['sptpd_nominal_omzet']);
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['sptpd_nominal_pajak']);
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return full['sptpd_status'] == null ? '<span class="label label-warning label-pill label-inline mr-2">Pending</span>' : full['sptpd_status'] == 1 ? '<span class="label label-success label-pill label-inline mr-2">Disetujui</span>' : '<span class="label label-danger label-pill label-inline mr-2">Ditolak</span>';
					},
				},
				{
					targets: 5,
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						return `<button onclick="detail('${full['sptpd_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
					},
				},

			]
		});
	}

	function print_sptpd(){
		HELPER.block();
		var id = $('#detail_sptpd_id').val();

		HELPER.ajax({
			url: HELPER.api.print,
			type: 'POST',
			data: {
				id: id,
			},
			success: function(res){
				var jsonResponse = JSON.parse(res);
				$('.kt-laporan').show();
				$('.kt-laporan-rekap').hide();
				$("#pdf-laporan object").attr("data", jsonResponse.record);
				console.log(typeof jsonResponse)
				HELPER.unblock();
			},
			error: function(){
				swal.fire('Terdapat Kesalahan', 'Silahkan hubungi admin', 'warning');
			}
		});
	}

	function createSptpdForm() {
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_create'
		});

		const currentDate = new Date();

		// Buat string dengan format YYYY-MM (misal: 2023-08 untuk Agustus 2023)
		const currentYear = currentDate.getFullYear();
		const currentMonth = (currentDate.getMonth()).toString().padStart(2, '0');
		const defaultMonthYear = `${currentYear}-${currentMonth}`;

		// Set nilai default pada elemen input bulan
		$('#sptpd_bulan_tahun_pajak').val(defaultMonthYear);
		get_omzet_ajax();
	}

	function tableData(tohide) {
		// $('#sptpd_npwpd').val('');
		$('.kt-laporan').hide();
		HELPER.toggleForm({
			tohide: tohide,
			toshow: 'table_data',
		});
	}

	function onRefresh() {
		$("#form-create-sptpd")[0].reset();
		HELPER.toggleForm({
			tohide: 'form_create',
			toshow: 'table_data',
		});
		loadTable('table-verifikasi');
	}

	function save() {
		HELPER.save({
			form: 'form-create-sptpd',
			url: BASE_URL + 'sptpd/store',
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					onRefresh();
				}
			}
		})
	}

	function get_omzet_ajax() {
		HELPER.ajax({
			url: HELPER.api.get_omzet_ajax,
			type: 'POST',
			data: {
				sptpd_tanggal: $('#sptpd_bulan_tahun_pajak').val(),
				sptpd_npwpd: $('#sptpd_npwpd').val(),
			},
			success: function(res) {
				console.log(`berhasil : ${res}`);
				$('#sptpd_nominal_omzet').val() != null ? $('#sptpd_nominal_omzet').val(res.realisasi_parent_sub_total) : $('#sptpd_nominal_omzet').val(0);
				$('#sptpd_nominal_pajak').val() != null ? $('#sptpd_nominal_pajak').val((res.realisasi_parent_jenis_tarif / 100) * res.realisasi_parent_sub_total) : $('#sptpd_nominal_pajak').val(0);

			},
			error: function() {
				$('#sptpd_nominal_omzet').val(0);
				$('#sptpd_nominal_pajak').val(0);
				console.log(`gagal`);
			}
		})
	}

	function detail(sptpd_id) {

		HELPER.block();
		$.post(HELPER.api.detail, {
			sptpd_id: sptpd_id
		}, function(res) {

			// deklarasi object untuk sptpd status
			var sptpd_status = {
				status: null,
				classStyle: null
			};

			var sptpd_status_pembayaran = {
				status: null,
				classStyle: null
			};

			// ternary untuk mengisi sptpd status
			sptpd_status.status = res.sptpd_status == '1' ? 'Disetujui' : res.sptpd_status == '0' ? 'Ditolak' : 'Diproses';
			sptpd_status.classStyle = res.sptpd_status == '1' ? 'btn-outline-success' : res.sptpd_status == '0' ? 'btn-outline-danger' : 'btn-outline-warning';
			sptpd_status_pembayaran.status = res.sptpd_status_pembayaran == '1' ? 'Disetujui' : res.sptpd_status_pembayaran == '0' ? 'Ditolak' : 'Diproses';
			sptpd_status_pembayaran.classStyle = res.sptpd_status_pembayaran == '1' ? 'btn-outline-success' : res.sptpd_status_pembayaran == '0' ? 'btn-outline-danger' : 'btn-outline-warning';

			$('#detail_sptpd_npwpd').val(res.sptpd_npwpd);
			$('#detail_sptpd_id').val(res.sptpd_id);
			$('#detail_sptpd_tahun_bulan').val(`${res.sptpd_tahun_pajak}-${res.sptpd_bulan_pajak}`);
			$('#detail_sptpd_nominal_omzet').val('Rp .' + $.number(res.sptpd_nominal_omzet));
			$('#detail_sptpd_nominal_pajak').val('Rp . ' + $.number(res.sptpd_nominal_pajak));
			$('#detail_sptpd_tanggal_permohonan').val(res.sptpd_created_at);
			$("#detail_sptpd_status").text(sptpd_status.status);
			$("#detail_sptpd_status_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
			$("#detail_sptpd_status_button").addClass(sptpd_status.classStyle);
			$('#detail_sptpd_nomor_va').val(res.sptpd_va_jatim == null ? '--' : res.sptpd_va_jatim);
			$('#detail_sptpd_kode_billing').val(res.detail_sptpd_kode_billing == null ? '--' : res.detail_sptpd_kode_billing);
			$('#detail_sptpd_tanggal_bayar').val(res.sptpd_tanggal_bayar == null ? '--' : res.sptpd_tanggal_bayar);
			$("#detail_sptpd_status_pembayaran_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
			$("#detail_sptpd_status_pembayaran_button").addClass(sptpd_status_pembayaran.classStyle);
			$("#detail_sptpd_status_pembayaran").text(sptpd_status_pembayaran.status);
			// console.log(res.sptpd_npwpd);
			// $('#rinci_wajibpajak_nama').val(res.wajibpajak_nama);
			// $('#rinci_wajibpajak_alamat').val(res.wajibpajak_alamat);
			// $('#rinci_wajibpajak_nama_penanggungjawab').val(res.wajibpajak_nama_penanggungjawab);
			// $('#realisasi_tanggal').text(res.realisasi_tanggal);
			// $('#rinci_realisasi_tanggal').val(res.realisasi_tanggal);

			HELPER.toggleForm({
				tohide: 'table_data',
				toshow: 'detail_sptpd'
			});
			HELPER.unblock();
		});
	}

	$('#sptpd_nominal_omzet').on('change', function() {
		changedValue = $('#sptpd_nominal_omzet').val() * (LSToko.jenis_tarif / 100);
		console.log(changedValue);
		$('#sptpd_nominal_pajak').val(changedValue);
	});

</script>