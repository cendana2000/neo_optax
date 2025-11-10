<script type="text/javascript">
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
			table_verifikasi: BASE_URL + 'verifikasisptpd/loadVerifikasi',
			table_verifikasi_setuju: BASE_URL + 'verifikasisptpd/loadVerifikasiSetuju',
			table_verifikasi_tolak: BASE_URL + 'verifikasisptpd/loadVerifikasiTolak',
			table_verifikasi_pembayaran: BASE_URL + 'verifikasisptpd/loadVerifikasiPembayaran',
			table_verifikasi_all: BASE_URL + 'verifikasisptpd/loadVerifikasiAll',
			// read: BASE_URL + 'upload/read',
			store: BASE_URL + 'verifikasisptpd/store',
			update: BASE_URL + 'verifikasisptpd/update',
			// destroy: BASE_URL + 'upload/destroy',
			detail: BASE_URL + 'verifikasisptpd/detail',
		}

		HELPER.fields = [
			'sptpd_status',
			'sptpd_id'
		];

		HELPER.setRequired([
			'sptpd_status',
			'sptpd_id'
		]);

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
				[3, 'desc']
			],
			columnDefs: [{
					targets: 1,
					render: function(data, type, full, meta) {
						return full['sptpd_npwpd'];
					},
				},
				{
					targets: 2,
					render: function(data, type, full, meta) {
						return full['wajibpajak_nama_penanggungjawab'];
					},
				},
				{
					targets: 3,
					render: function(data, type, full, meta) {
						return `${full['sptpd_bulan_pajak']}-${full['sptpd_tahun_pajak']}`;
					},
				},
				{
					targets: 4,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['sptpd_nominal_omzet']);
					},
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						return 'Rp.' + $.number(full['sptpd_nominal_pajak']);
					},
				},
				{
					targets: 6,
					render: function(data, type, full, meta) {
						return full['sptpd_tanggal_verifikasi'];
					},
				},
				{
					targets: 7,
					render: function(data, type, full, meta) {
						return full['sptpd_status'] == null ? '<span class="label label-warning label-pill label-inline mr-2">Pending</span>' : full['sptpd_status'] == 1 ? '<span class="label label-success label-pill label-inline mr-2">Disetujui</span>' : '<span class="label label-danger label-pill label-inline mr-2">Ditolak</span>';
					},
				},
				{
					targets: 8,
					orderable: false,
					visible: true,
					render: function(data, type, full, meta) {
						if(full['sptpd_status'] == null){
							return `<button onclick="detail('${full['sptpd_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
						} else {
							return `<button onclick="detail_verifikasi_done('${full['sptpd_id']}')" class="btn btn-primary btn-sm">Detail</button>`;
						}
					},
				},

			]
		});
	}


	function createSptpdForm() {
		HELPER.toggleForm({
			tohide: 'table_data',
			toshow: 'form_create'
		});
	}

	function tableData(tohide) {
		// $('#sptpd_npwpd').val('');
		HELPER.toggleForm({
			tohide: tohide,
			toshow: 'table_data',
		});
	}

	function onRefresh(tohide) {
		$("#form-create-sptpd")[0].reset();
		HELPER.toggleForm({
			tohide: 'form_create',
			toshow: 'table_data',
		});
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

	// method detail digunakan untuk memanggil detail data yang belum diverifikasi oleh pegawai (sptpd_status == null)
	function detail(sptpd_id) {

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
			$('#detail_sptpd_tahun_bulan').val(`${res.sptpd_tahun_pajak}-${res.sptpd_bulan_pajak}`);
			$('#detail_sptpd_nominal_omzet').val('Rp. ' + $.number(res.sptpd_nominal_omzet));
			$('#detail_sptpd_nominal_etax_omzet').val(res.sptpd_etax_omzet == null ? '--' : 'Rp .' + $.number(res.sptpd_etax_omzet));
			$('#detail_sptpd_nominal_pajak').val('Rp. ' + $.number(res.sptpd_nominal_pajak));
			$('#detail_sptpd_nominal_etax_pajak').val(res.sptpd_etax_pajak == null ? '--' : 'Rp . ' + $.number(res.sptpd_etax_pajak));
			$('#detail_sptpd_tanggal_permohonan').val(res.sptpd_created_at);
			if (sptpd_status.status == null) {

			} else {
				$("#detail_sptpd_status").text(sptpd_status.status);
				$("#detail_sptpd_status_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
				$("#detail_sptpd_status_button").addClass(sptpd_status.classStyle);
			}
			$('#detail_sptpd_nomor_va').val(res.sptpd_va_jatim == null ? '--' : res.sptpd_va_jatim);
			$('#detail_sptpd_tanggal_bayar').val(res.sptpd_tanggal_bayar == null ? '--' : res.sptpd_tanggal_bayar);
			$("#detail_sptpd_status_pembayaran_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
			$("#detail_sptpd_status_pembayaran_button").addClass(sptpd_status_pembayaran.classStyle);
			$("#detail_sptpd_status_pembayaran").text(sptpd_status_pembayaran.status);
			$("#sptpd_id").val(res.sptpd_id);
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
		});
	}

	// detail yang ini untuk yang sudah diverifikasi
	function detail_verifikasi_done(sptpd_id) {
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

			$('#detail_sptpd_done_npwpd').val(res.sptpd_npwpd);
			$('#detail_sptpd_done_tahun_bulan').val(`${res.sptpd_tahun_pajak}-${res.sptpd_bulan_pajak}`);
			$('#detail_sptpd_done_nominal_omzet').val('Rp. ' + $.number(res.sptpd_nominal_omzet));
			$('#detail_sptpd_done_nominal_etax_omzet').val(res.sptpd_etax_omzet == null ? '--' : 'Rp .' + $.number(res.sptpd_etax_omzet));
			$('#detail_sptpd_done_nominal_pajak').val('Rp. ' + $.number(res.sptpd_nominal_pajak));
			$('#detail_sptpd_done_nominal_etax_pajak').val(res.sptpd_etax_pajak == null ? '--' : 'Rp . ' + $.number(res.sptpd_etax_pajak));
			$('#detail_sptpd_done_tanggal_permohonan').val(res.sptpd_created_at);
			$('#detail_sptpd_done_tanggal_verifikasi').val(res.sptpd_tanggal_verifikasi);
			$('#detail_sptpd_done_nama_verifikator').val(res.pegawai_nama);
			$("#detail_sptpd_done_status").text(sptpd_status.status);
			$("#detail_sptpd_done_status_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
			$("#detail_sptpd_done_status_button").addClass(sptpd_status.classStyle);
			$('#detail_sptpd_done_nomor_va').val(res.sptpd_va_jatim == null ? '--' : res.sptpd_va_jatim);
			$('#detail_sptpd_done_tanggal_bayar').val(res.sptpd_tanggal_bayar == null ? '--' : res.sptpd_tanggal_bayar);
			$("#detail_sptpd_done_status_pembayaran_button").removeClass('btn-outline-success btn-outline-danger btn-outline-warning');
			$("#detail_sptpd_done_status_pembayaran_button").addClass(sptpd_status_pembayaran.classStyle);
			$("#detail_sptpd_done_status_pembayaran").text(sptpd_status_pembayaran.status);
			$("#sptpd_id").val(res.sptpd_id);
			// console.log(res.sptpd_npwpd);
			// $('#rinci_wajibpajak_nama').val(res.wajibpajak_nama);
			// $('#rinci_wajibpajak_alamat').val(res.wajibpajak_alamat);
			// $('#rinci_wajibpajak_nama_penanggungjawab').val(res.wajibpajak_nama_penanggungjawab);
			// $('#realisasi_tanggal').text(res.realisasi_tanggal);
			// $('#rinci_realisasi_tanggal').val(res.realisasi_tanggal);

			HELPER.toggleForm({
				tohide: 'table_data',
				toshow: 'detail_sptpd_done'
			});
		});
	}

	function update() {
		var sptpd_id = $('#sptpd_id').val();
		var sptpd_status = $('#detail_sptpd_status_select').val();
		HELPER.save({
			form: 'form-verifikasi-sptpd',
			url: HELPER.api.update,
			data: {
				sptpd_id,
				sptpd_status,
			},
			confirm: true,
			callback: function(success, id, record, message) {
				if (success === true) {
					tableData('detail_sptpd');
				}
				loadTable('table-verifikasi');
			}
		})
	}
</script>