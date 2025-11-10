<script type="text/javascript">
	var gNPWPD = '';
	var isMobile = false;

	ismobile();

	function ismobile() {
		if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
			$("#sidebarToggleTop").trigger("click");
			isMobile = true;
			return true;
		}
	}

	var currentInnerWidth = window.innerWidth;
	window.onresize = (function(e) {
		if (ismobile() || window.innerWidth < 798) {
			isMobile = true;
		} else {
			isMobile = false;
		}
		if (currentInnerWidth != window.innerWidth) {
			$('#table-history-pelaporan').DataTable().clear().destroy();
			loadTable();
			currentInnerWidth = window.innerWidth;
		}
	});

	$(function() {
		$(".monthpicker").datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months"
		});
		$(".datepicker").datepicker({
			format: "yyyy-mm-dd"
		})

		HELPER.api = {
			table: BASE_URL + 'historypelaporan/',
			tagihan: BASE_URL + 'historypelaporan/total_tagihan',
			download_sptpd: BASE_URL + 'historypelaporan/cetak_sptpd',
			download_tbp: BASE_URL + 'historypelaporan/cetak_sspd',
		}
		loadTable();
		getTotalTagihan()
	});

	function filterBulan() {
		let filterBulan = $('#bulan').val();
		loadTable(filterBulan);
	}

	function getTotalTagihan() {
		$.ajax({
			url: HELPER.api.tagihan,
			method: "POST",
			data: {},
			dataType: "JSON",
			success: function(e) {
				var span_total_tagihan = '<span class="menu-text-alert">' + e.jml_belum_lunas + '</span>';
				if (e.jml_belum_lunas > 0) {
					$(".menu-text-alert").remove();
					$("#btn-HistoryPelaporan").append(span_total_tagihan);
					$("#kt_header_mobile > .d-flex.align-items-center > .notification-round").remove();
					$("#kt_header_mobile > .d-flex.align-items-center").append('<div class="notification-round"></div>');
				}
			},
			error: function(e) {}
		});
	}

	function loadTable(filterBulan = null) {
		let data = {};

		if (filterBulan != null) {
			data.filterBulan = filterBulan
		}

		HELPER.initTable({
			el: "table-history-pelaporan",
			url: HELPER.api.table,
			data: data,
			searchAble: false,
			destroyAble: true,
			responsive: false,
			ordering: false,
			dataSrc: "rows",
			columnDefs: [{
					targets: 0,
					render: function(data, type, full, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					},
					searchable: true,
					visible: (isMobile) ? false : true,
				},
				{
					//KOLOM MASA PAJAK
					targets: 1,
					render: function(data, type, full, meta) {
						var namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
						var bulan = parseInt(full.PERIODE_AWAL.substr(5, 2));
						return "<div class='d-flex justify-content-between'><span>" + namaBulan[bulan - 1] + " " + full.PERIODE_AWAL.substr(0, 4) + "</span><a href='" + HELPER.api.download_sptpd + "/" + full.ID_SPT + "' target = '_blank' ><i class='fa fa-download'></i></a></div>";
					},
					visible: (isMobile) ? false : true,
				},
				{
					//KOLOM JUMLAH PAJAK
					targets: 2,
					render: function(data, type, full, meta) {
						return $.number(full.JUMLAH_PAJAK).replaceAll(",", ".");
					},
					visible: (isMobile) ? false : true,
				},
				{
					//KOLOM VIRTUAL ACCOUNT
					targets: 3,
					render: function(data, type, full, meta) {
						return "<div class='d-flex'>" + "<span>" + full.VA_JATIM + "</span><span class='ml-3 copy-va' style='cursor:pointer' data='" + full.VA_JATIM + "' onclick='copyVA(this)'><i class='fa fa-copy'></i></span>" + "</div>";
					},
					visible: (isMobile) ? false : true,
				},
				{
					//KOLOM STATUS
					targets: 4,
					render: function(data, type, full, meta) {
						var class_ = (full.TANGGAL_LUNAS) ? "badge-success" : "badge-danger";
						var status = (full.TANGGAL_LUNAS) ? "<span><a class='text-white' href='" + HELPER.api.download_tbp + "/" + full.ID_TBP + "' target='_blank'>Lunas</a></span>" : "Belum Lunas";
						// var download_sspd = (full.TANGGAL_LUNAS) ? "<span class='ml-3'><a href='" + HELPER.api.download_tbp + "/" + full.ID_TBP + "' target='_blank'><i class='fa fa-download'></i></a></span>" : "";
						var html = '<span class="badge ' + class_ + '">' + status + '</span>';
						return html;
					},
					visible: (isMobile) ? false : true,
				},
				{
					targets: 5,
					render: function(data, type, full, meta) {
						var namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
						var bulan = parseInt(full.PERIODE_AWAL.substr(5, 2));
						var masaPajak = namaBulan[bulan - 1] + " " + full.PERIODE_AWAL.substr(0, 4);

						var html = "";
						html += "<div class='row'>";

						html += "<div class='col-6 fw-bold'>Masa Pajak</div>";
						html += "<div class='col-6'><span class='mr-3'>" + masaPajak + "</span><a href='" + HELPER.api.download_sptpd + "/" + full.ID_SPT + "' target = '_blank' ><i class='fa fa-download'></i></a></div>";

						html += "<div class='col-6 fw-bold'>Jumlah Pajak</div>";
						html += "<div class='col-6'>" + $.number(full.JUMLAH_PAJAK).replaceAll(",", ".") + "</div>";

						html += "<div class='col-6 fw-bold'>Virtual Account</div>";
						var elVA = "<div class='d-flex flex-between'>" + "<span>" + full.VA_JATIM + "</span><span class='ml-3 copy-va' style='cursor:pointer' data='" + full.VA_JATIM + "' onclick='copyVA(this)'><i class='fa fa-copy'></i></span>" + "</div>";
						html += "<div class='col-6'>" + elVA + "</div>";

						html += "<div class='col-6 fw-bold'>Status</div>";
						var class_ = (full.TANGGAL_LUNAS) ? "badge-success" : "badge-danger";
						var status = (full.TANGGAL_LUNAS) ? "<a class='text-white' href='" + HELPER.api.download_tbp + "/" + full.ID_TBP + "' target='_blank'>Lunas</a>" : "Belum Lunas";
						status = '<span class="badge ' + class_ + '">' + status + '</span>';
						html += "<div class='col-6'>" + status + "</div>";

						html += "</div>";
						return html;
					},
					visible: (!isMobile) ? false : true,
				},
			],
		});
	}

	function onRefresh() {
		HELPER.refresh({
			table: 'table-history-pelaporan'
		});
	}

	function copyVA(elem) {
		$(".va").html($(elem).attr("data"));
		copyToClipboard($(".va")[0]);
	}

	function copyToClipboard(elem) {
		// create hidden text element, if it doesn't already exist
		var targetId = "_hiddenCopyText_";
		var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
		var origSelectionStart, origSelectionEnd;
		if (isInput) {
			// can just use the original source element for the selection and copy
			target = elem;
			origSelectionStart = elem.selectionStart;
			origSelectionEnd = elem.selectionEnd;
		} else {
			// must use a temporary form element for the selection and copy
			target = document.getElementById(targetId);
			if (!target) {
				var target = document.createElement("textarea");
				target.style.position = "absolute";
				target.style.left = "-9999px";
				target.style.top = "0";
				target.id = targetId;
				document.body.appendChild(target);
			}
			target.textContent = elem.textContent;
		}
		// select the content
		var currentFocus = document.activeElement;
		target.focus();
		target.setSelectionRange(0, target.value.length);

		// copy the selection
		var succeed;
		try {
			succeed = document.execCommand("copy");
		} catch (e) {
			succeed = false;
		}
		// restore original focus
		if (currentFocus && typeof currentFocus.focus === "function") {
			currentFocus.focus();
		}

		if (isInput) {
			// restore prior selection
			elem.setSelectionRange(origSelectionStart, origSelectionEnd);
		} else {
			// clear temporary content
			target.textContent = "";
		}
		alert("VirtualAccount Berhasil Disalin.");
		return succeed;
	}
</script>