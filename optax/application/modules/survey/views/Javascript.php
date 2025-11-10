<script type="text/javascript">
  $(function(){
    HELPER.fields = [
			'survey_id',
			'survey_judul',
			'survey_tgl_publish',
			'survey_tgl_selesai',
			'survey_status',
			'survey_deskripsi',
		];
    HELPER.setRequired([
      'survey_judul',
		]);
		HELPER.api = {
			index: BASE_URL+'survey/',
      tabledata: BASE_URL+'survey/gettabledata',
      tablehasil: BASE_URL+'survey/gettablehasil',
      store: BASE_URL+'survey/store',
      update: BASE_URL+'survey/update',
      destroy: BASE_URL+'survey/destroy',
      read: BASE_URL+'survey/read',
		}

    $(".select2").select2();

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
      templates: arrows
    });

    $('#survey_judul').on('blur', function(){
      this.value = $(this).val().replace(/[^a-zA-Z0-9_-][\W]*/g, " ");
    });
    $('#survey_judul').keyup(function(){
      this.value = $(this).val().replace(/[^a-zA-Z0-9_-][\W]*/g, " ");
    })

    init_table_data();
    // init_table_hasil();
  })
  
  function onAdd() {
    HELPER.toggleForm({});
    changePertanyaanNumber();
	}

  function init_table_data(argument) {
		HELPER.initTable({
			el: "table-data-survey",
			// url: HELPER.api.tabledata,
      url: HELPER.api.tablehasil,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
        defaultContent: "-",
        targets: "_all"
      },{
        targets: 0,
        orderable: false
      },{
        targets: 1,
        render: function(data, type, full, meta) {
          return full.survey_judul.length > 20 ? `${full.survey_judul.substring(0, 20)}...` : full.survey_judul;
        },
      },{
        targets: 2,
        render: function(data, type, full, meta) {
          return moment(full.survey_tgl_publish, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY');
        },
      },{
        targets: 3,
        render: function(data, type, full, meta) {
          return moment(full.survey_tgl_selesai, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY');
        },
      },{
        targets: 4,
        render: function(data, type, full, meta) {
          return full.survey_deskripsi && full.survey_deskripsi.length > 75 ? `${full.survey_deskripsi.substring(0, 75)}...` : full.survey_deskripsi;
        },
      },{
        targets: 5,
        orderable: false,
        visible: true,
        render: function(data, type, full, meta) {
          return full.survey_status == '1' ? 
            `<button class="btn btn-success btn-sm btn-block" onclick="changeStatus('${full.survey_id}', true)">Aktif</button>`
          :
            `<button class="btn btn-danger btn-sm btn-block" onclick="changeStatus('${full.survey_id}', false)">Tidak Aktif</button>`;
        },
      },
      {
        targets: 6,
        width: '10px',
        orderable: false,
        visible: true,
        render: function(data, type, full, meta) {
          return `
          <a href="${BASE_URL}survey/form/${full.survey_judul}/toko" target="_blank" class="btn btn-sm btn-success btn-icon mx-1" title="Lihat Survey" >
              <i class="fa fa-eye"></i>
          </a>
          <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit('${full.survey_id}')" >
            <span class="svg-icon svg-icon-md">
              <i class="fa fa-pen"></i>
            </span>
          </a>
          <a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('${full.survey_id}', '${full.survey_jml_jawaban}')">
            <span class="svg-icon svg-icon-md">
              <i class="fa fa-trash"></i>
            </span>
          </a>
          `;
        },
      }]
		});
	}

  function init_table_hasil(argument) {
		HELPER.initTable({
			el: "table-hasil-survey",
			url: HELPER.api.tablehasil,
			searchAble: true,
			destroyAble: true,
			responsive: false,
			columnDefs: [{
        targets: 1,
        render: function(data, type, full, meta) {
          return full.survey_judul;
        },
      },{
        targets: 2,
        render: function(data, type, full, meta) {
          return moment(full.survey_tgl_publish, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY');
        },
      },{
        targets: 3,
        render: function(data, type, full, meta) {
          return moment(full.survey_tgl_selesai, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY');
        },
      },{
        targets: 4,
        render: function(data, type, full, meta) {
          return full.survey_jml_pertanyaan;
        },
      },{
        targets: 5,
        render: function(data, type, full, meta) {
          return full.survey_jml_jawaban;
        },
      },
      {
        targets: 6,
        width: '10px',
        orderable: false,
        visible: true,
        render: function(data, type, full, meta) {
          return `
          <a href="${BASE_URL}survey?q=${full.survey_id}" target="_blank" class="btn btn-sm btn-success btn-icon mx-1" title="Lihat Survey" >
              <i class="fa fa-eye"></i>
          </a>
          <a href="javascript:;" class="btn btn-sm btn-primary btn-icon mx-1" title="Edit" onclick="onEdit('${full.survey_id}')" >
            <span class="svg-icon svg-icon-md">
              <i class="fa fa-pen"></i>
            </span>
          </a>
          <a href="javascript:;" class="btn btn-sm btn-danger btn-icon mx-1" onclick="onDelete('${full.survey_id}')"">
            <span class="svg-icon svg-icon-md">
              <i class="fa fa-trash"></i>
            </span>
          </a>
          `;
        },
      }]
		});
	}

  function onRefresh(table) {
		HELPER.refresh({
			table,
		})
	}

  function changeStatus(id, status = false){
    HELPER.block();
    $.ajax({
      url: BASE_URL + 'survey/changeStatus',
      data: {
        id,
        status
      },
      type: 'post',
      dataType: 'json',
      success: function (res) {
        if(res.success){
          HELPER.showMessage({
            success: true,
            message: 'Berhasil mengubah status.',
            callback: function(){
              onRefresh('table-data-survey');
            }
          });
        }else{
          HELPER.showMessage({
            success: 'warning',
            title: 'Peringatan',
            message: res.message,
          })
        }
      },
      complete: function (res){
        HELPER.unblock();
      }
    })
  }

  function onDelete(id, countasw = 0){
    if(countasw > 0){
      HELPER.showMessage({
        success: 'info',
        title: 'Stop',
        message: `Terdapat ${countasw} responden, Survey tidak dapat dihapus.`
      });
      return;
    }
		HELPER.confirm({
      message: 'Apakah anda yakin ingin menghapus survey?',
      callback: function(suc){
				if(suc){
					$.ajax({
						url: HELPER.api.destroy,
						data: {
							survey_id: id
						},
						type: 'post',
						complete: function(res){
							var result = res.responseJSON
							// console.log(res);
							if(result.success){
                HELPER.showMessage({
                  success: true,
                  title: 'Success',
                  message: `Berhasil menghapus survey ${result.record.survey_judul}`
                });
								onRefresh('table-data-survey');
              }else{
                HELPER.showMessage({
                  success: 'info',
                  title: 'Stop',
                  message: res.message
                });
              }
						}
					})
				}
			}
		});
  }

  function onEdit(id) {
		HELPER.loadData({
			url: HELPER.api.read,
			server: true,
			data: {
				survey_id: id
			},
      callback: function(res){
        $(".form_data").data('responden', res.jml_responden);

        var $datepicker = $('#datepicker');
        $datepicker.datepicker();
        $datepicker.datepicker('setDate', new Date());
        $("#survey_tgl_publish").datepicker('setDate', moment(res.survey_tgl_publish, 'YYYY-MM-DD H:mm:ss').format('MM-DD-YYYY'));
        $("#survey_tgl_selesai").datepicker('setDate', moment(res.survey_tgl_selesai, 'YYYY-MM-DD H:mm:ss').format('MM-DD-YYYY'));

        if(res.survey_pengaturan_nama === "1"){
          $('#survey_pengaturan_nama').prop('checked', true);
        }
        if(res.survey_pengaturan_email === "1"){
          $('#survey_pengaturan_email').prop('checked', true);
        }
        if(res.survey_pengaturan_alamat === "1"){
          $('#survey_pengaturan_alamat').prop('checked', true);
        }

        if (res.survey_banner) {
					$('#preview-image').attr('src', BASE_URL_NO_INDEX + res.survey_banner);
					$('#title-banner').text(res.survey_banner.split("/").pop());
				}

        fillInputPertanyaan(res);

        onAdd();
      }
		})
	}

  function fillInputPertanyaan(res){
    $('#place_pertanyaan').html("");
    res.pertanyaan.map((item, index) => {
      $("#place_pertanyaan").append(htmlPertanyaan(index));
      $(`#judul_pertanyaan_${index}`).val(item.survey_pertanyaan_judul);
      $(`#tipe_pertanyaan_${index}`)
        .val(item.survey_pertanyaan_tipe)
        .attr('onchange', `changeTipePertanyaan(${index}, ${item.survey_pertanyaan_tipe}, this)`);
      $(`#id_pertanyaan_${index}`).val(item.survey_pertanyaan_id);
      if(item.survey_pertanyaan_tipe == "0" || item.survey_pertanyaan_tipe == "1"){
        $(`#jawaban_${index}`).html("");
        item.opsi.map((item, oindex) => {
          $(`#jawaban_${index}`).append(htmlJawabanGanda(index, oindex));
          $(`#id_opsi_${index}_${oindex}`).val(item.survey_pertanyaan_opsi_id);
          $(`#jawaban_${index}_${oindex}`).val(item.survey_pertanyaan_opsi_judul);
          $(`#nilai_${index}_${oindex}`).val(item.survey_pertanyaan_opsi_nilai);
          changeJawabanNumber(index);
        });
        $(`#bawah_jawaban_${index}`).html(htmlTambahOpsi(index, item.opsi.length));
      }else if(item.survey_pertanyaan_tipe == "2"){
        $(`#jawaban_${index}`).html(htmlJawabanParagraf(index));
        $(`#bawah_jawaban_${index}`).html("");
      }
      if(item.survey_pertanyaan_wajib_yn === "Y"){
        $(`#checkshow_${index}`).prop('checked', true);
      }else{
        $(`#checkshow_${index}`).prop('checked', false);
      }
      changeWajibCheck(index, $(`#checkshow_${index}`));
    });

    $('#add_pertanyaan').data('rows', res.pertanyaan.length);

    $('.select2').select2();
  }

  function htmlJawabanGanda(row = 0, index = 0){
    return `
      <div class="col-11 offset-1 row mb-3">
        <div class="col-8 d-flex flex-row justify-content-center px-0">
          <span class="jawaban_number border rounded mr-3 text-muted" style="padding:8px 12px;">A.</span>
          <input class="form-control mr-3" style="width:60px;	" type="text" name="nilai[${row}][]" id="nilai_${row}_${index}" onkeyup="numberOnly(this)" placeholder="Nilai"/>
          <input type="hidden" name="id_opsi[${row}][]" id="id_opsi_${row}_${index}" value=""/>
          <div class="input-group">
            <input class="form-control" name="jawaban[row_${row}][]" id="jawaban_${row}_${index}" placeholder="Masukan Jawaban"/>
            <div class="input-group-append">
              <span class="input-group-text bg-white" role="button" id="delopsi_${row}_${index}" onclick="delOpsi(${row}, ${index},this)">
                <i class="la la-close text-danger"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function htmlTambahOpsi(row, index = 1){
    return `
      <div class="col-11 offset-1 row">
        <div class="col-8 d-flex flex-row justify-content-center px-0">
          <span role="button" onclick="addOpsi(${row}, this)" data-rows="${index}" id="addopsi_icon_${row}" class="rounded mr-3 text-muted" style="padding:8px 12px;border: 1px dashed #1BC5BD;"><i class="fas fa-plus icon-sm text-success"></i></span>
          <span role="button" onclick="addOpsi(${row}, this)" data-rows="${index}" id="addopsi_${row}" class="form-control text-success" readonly style="padding:8px 12px;border: 1px dashed #1BC5BD;">Tambah opsi</span>
        </div>
      </div>
    `;
  }

  function htmlJawabanParagraf(row){
    return  `
    <div class="col-11 offset-1 row mb-3">
      <textarea class="form-control col-8" rows="3" readonly disabled placeholder="Jawaban Panjang"></textarea>
    </div>
    `;
  }

  function htmlPertanyaan(row){
    return `
    <div class="card card-custom border mt-8" id="item_pertanyaan_${row}">
      <div class="card-body">
        <!-- FIELD PERTANYAAN JUDUL & TYPE -->
        <div class="row align-items-center">
          <div class="col-1 text-center">
            <span class="badge badge-success font-size-lg" id="no_pertanyaan_${row}">1</span>
          </div>
          <div class="col-11">
            <span class="font-weight-bold font-size-lg">Pertanyaan</span>
          </div>
          <div class="col-11 offset-1 row mt-3">
            <input type="hidden" name="row[]" value="${row}"/>
            <input type="hidden" name="id_pertanyaan[]" id="id_pertanyaan_${row}" value=""/>
            <input class="form-control col-8" name="judul_pertanyaan[]" id="judul_pertanyaan_${row}" placeholder="Ketikan Pertanyaan"/>
            <div class="col-4 pr-0">
              <select class="form-control select2" name="tipe_pertanyaan[]" id="tipe_pertanyaan_${row}" onchange="changeTipePertanyaan(${row}, 0, this)">
                <option value="0">Pilihan Tunggal</option>
                <option value="1">Pilihan Ganda</option>
                <option value="2">Paragraf</option>
              </select> 
            </div>
          </div>
        </div>
        <!-- END FIELD PERTANYAAN JUDUL & TYPE -->
        <!-- FIELD JAWABAN -->
        <div class="row mt-3" id="jawaban_${row}">
          <div class="col-11 offset-1 row mb-3">
            <div class="col-8 d-flex flex-row justify-content-center px-0">
              <span class="jawaban_number border rounded mr-3 text-muted" style="padding:8px 12px;">A.</span>
              <input class="form-control mr-3" style="width:60px;	" type="text" name="nilai[${row}][]" id="nilai_${row}_0" onkeyup="numberOnly(this)" placeholder="Nilai"/>
              <input type="hidden" name="id_opsi[${row}][]" id="id_opsi_${row}_0" value=""/>
              <div class="input-group">
                <input class="form-control" name="jawaban[row_${row}][]" id="jawaban_${row}_0" placeholder="Masukan Jawaban"/>
                <div class="input-group-append">
                  <span class="input-group-text bg-white" role="button" id="delopsi_${row}_0" onclick="delOpsi(${row}, 0, this)">
                    <i class="la la-close text-danger"></i>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END JAWABAN -->
        <!-- FIELD ADD PILIHAN -->
        <div class="row" id="bawah_jawaban_${row}">
          <div class="col-11 offset-1 row">
            <div class="col-8 d-flex flex-row justify-content-center px-0">
              <span role="button" onclick="addOpsi(${row} , this)" data-rows="1" id="addopsi_icon_0" class="rounded mr-3 text-muted" style="padding:8px 12px;border: 1px dashed #1BC5BD;"><i class="fas fa-plus icon-sm text-success"></i></span>
              <span role="button" onclick="addOpsi(${row} , this)" data-rows="1" id="addopsi_0" class="form-control text-success" readonly style="padding:8px 12px;border: 1px dashed #1BC5BD;">Tambah opsi</span>
            </div>
          </div>
        </div>
        <!-- END ADD PILIHAN -->
      </div>
      <div class="card-footer d-flex flex-row justify-content-end">
        <span class="far fa-copy icon-lg mr-5" role="button" onclick="handleDuplicate(${row}, this)" id="duplicate_pertanyaan_${row}" data-toggle="tooltip" data-placement="top" title="Duplicate"></span>
        <span class="fa fa-trash icon-lg mr-5" role="button" onclick="delPertanyaan(${row}, this)" id="hapus_pertanyaan_${row}" data-toggle="tooltip" data-placement="top" title="Hapus"></span>
        <span class="text-muted mr-5 font-size-lg">|</span>
        <label class="checkbox checkbox-success">
          <input type="hidden" name="checkwajib[]" value="N" id="checkhide_${row}"/>
          <input type="checkbox" name="checkwajib[]" value="Y" id="checkshow_${row}" onchange="changeWajibCheck(${row}, this)"/>
          <span></span>&nbsp;&nbsp;
          Wajib diisi
        </label>
      </div>
    </div>`;
  }

  function htmlTipePertanyaan(row, val = 0){
    return `
      <select class="form-control select2" name="tipe_pertanyaan[]" id="tipe_pertanyaan_${row}" onchange="changeTipePertanyaan(${row}, ${val}, this)">
        <option value="0">Pilihan Tunggal</option>
        <option value="1">Pilihan Ganda</option>
        <option value="2">Paragraf</option>
      </select> 
    `
  }

  function changeTipePertanyaan(row, lastval, el){
    const jml_resp = $(".form_data").data('responden');
    if(jml_resp > 0){
      if(lastval != $(el).val()){
        if($(`#id_pertanyaan_${row}`).val()){
          HELPER.showMessage({
            success: 'info',
            title: 'Stop',
            message: `Terdapat ${jml_resp} responden, tidak mengganti tipe pertanyaan.`
          });
          $(el).val(lastval).trigger('change');
          return;
        }
      }else{
        return;
      }
    }

    var value = $(el).val();
    // value: 0 = Pilihan Tunggal | 1 = Pilihan Ganda | 2 = Paragraf
    var jawabanHtml = "";
    var bawahJawabanHtml = ""; // bisa digunakan pada saat pilihan ganda untuk menambahkan opsi
    if(value == "0"){
      jawabanHtml = htmlJawabanGanda(row, 0);
      bawahJawabanHtml = htmlTambahOpsi(row);
    }else if(value == "1"){
      jawabanHtml = htmlJawabanGanda(row, 0);
      bawahJawabanHtml = htmlTambahOpsi(row);
    }else if(value == "2"){
      jawabanHtml = htmlJawabanParagraf(row);
      bawahJawabanHtml = ``;
    }

    $(`#jawaban_${row}`).html(jawabanHtml);
    $(`#bawah_jawaban_${row}`).html(bawahJawabanHtml);
  }

  function addOpsi(row, el){
    let countRow = $(el).data('rows');
    $(`#jawaban_${row}`).append(htmlJawabanGanda(row, countRow));
    $(`#addopsi_${row}`).data('rows', countRow+1);
    $(`#addopsi_icon_${row}`).data('rows', countRow+1);
    changeJawabanNumber(row);
  }

  function delOpsi(row, index, el){
    const jml_resp = $(".form_data").data('responden');
    if(jml_resp > 0 && $(`#id_opsi_${row}_${index}`).val()){
      HELPER.showMessage({
        success: 'info',
        title: 'Stop',
        message: `Terdapat ${jml_resp} responden, Opsi tidak dapat dihapus.`
      });
      return;
    }
    HELPER.confirm({
			message: 'Apakah kamu yakin ingin menghapus opsi jawaban ini?',
			callback: function(suc) {
				if (suc) {
          var lengthOpsi = $(`#jawaban_${row}`).children().length;
          if(lengthOpsi > 1){
            $(el).parent().parent().parent().parent().remove();
            changeJawabanNumber(row);
            return;
          }

          HELPER.showMessage({
            success: 'info',
            title: 'Stop',
            message: 'Tidak dapat dihapus, tersisa satu opsi jawaban.',
          });
        }
      }
    });
  }

  function delPertanyaan(row, el){
    const jml_resp = $(".form_data").data('responden');
    if(jml_resp > 0 && $(`#id_pertanyaan_${row}`).val()){
      HELPER.showMessage({
        success: 'info',
        title: 'Stop',
        message: `Terdapat ${jml_resp} responden, Pertanyaan tidak dapat dihapus.`
      });
      return;
    }
    HELPER.confirm({
			message: 'Apakah kamu yakin ingin menghapus pertanyaan ini?',
			callback: function(suc) {
				if (suc) {
          var lengthPertanyaan = $("#place_pertanyaan").children().length;
          if(lengthPertanyaan > 1){
            $(`#item_pertanyaan_${row}`).remove();
            changePertanyaanNumber();
            return;
          }

          HELPER.showMessage({
            success: 'info',
            title: 'Stop',
            message: 'Tidak dapat dihapus, tersisa satu pertanyaan.',
          });
        }
      }
    })
  }

  function addPertanyaan(el){
    var countRow = $(el).data("rows");
    $("#place_pertanyaan").append(htmlPertanyaan(countRow));
    $(".select2").select2();
    // console.log(countRow);
    $(el).data("rows", countRow + 1);
    changePertanyaanNumber();
  }

  function save(){
    var form = $('#form-survey')[0]; // You need to use standard javascript object here
		var formData = new FormData(form);
    HELPER.save({
			form: 'form-survey',
      data: formData,
			confirm: true,
			contentType: false,
			processData: false,
			callback: function(success,id,record,message)
			{
				if (success===true) {
					// HELPER.back({});
					HELPER.loadPage($('#btn-Survey'));
				}
			}	
		})
  }

  function changeWajibCheck(row, el){
    if($(el).is(':checked')){
      $(`#checkhide_${row}`).prop("disabled", true);
    }else{
      $(`#checkhide_${row}`).prop("disabled", false);
    }
  }

  function changePertanyaanNumber(){
    let pertanyaanChild = $("#place_pertanyaan").children();
    if(pertanyaanChild.length > 0){
      pertanyaanChild.map((index, item) => {
        $('span.badge', item).html(index+1)
      })
    }
  }

  function changeJawabanNumber(row){
    let jawabanChild = $(`#jawaban_${row}`).children();
    if(jawabanChild.length > 0){
      jawabanChild.map((index, item) => {
        $('span.jawaban_number', item).html(`${numToSSColumn(index+1)}.`);
      })
    }
  }

  function numToSSColumn(num){
    let s = '', t;

    while (num > 0) {
      t = (num - 1) % 26;
      s = String.fromCharCode(65 + t) + s;
      num = (num - t)/26 | 0;
    }
    return s || undefined;
  }

  function handleDuplicate(row, el){
    var currentRow = $("#add_pertanyaan").data("rows");
    const clonePertanyaan = $(`#item_pertanyaan_${row}`).clone();

    // Manipulate IDs & Attributes of PERTANYAAN
    clonePertanyaan.prop('id', `item_pertanyaan_${currentRow}`);
    clonePertanyaan.find(`#no_pertanyaan_${row}`).attr('id', `no_pertanyaan_${currentRow}`);
    clonePertanyaan.find(`#id_pertanyaan_${row}`).attr('id', `id_pertanyaan_${currentRow}`).val("");
    clonePertanyaan.find(`#judul_pertanyaan_${row}`).attr('id', `judul_pertanyaan_${currentRow}`);
    clonePertanyaan.find(`#tipe_pertanyaan_${row}`).parent()
      .html(htmlTipePertanyaan(currentRow, $(`#tipe_pertanyaan_${row}`).val()))
      .children()
        .val($(`#tipe_pertanyaan_${row}`).val())
        .attr('onchange', `changeTipePertanyaan(${currentRow}, ${$(`#tipe_pertanyaan_${row}`).val()}, this)`)
      .select2();
    clonePertanyaan.find(`input[name="row[]"]`).val(currentRow);
    clonePertanyaan.find(`#jawaban_${row}`).attr('id', `jawaban_${currentRow}`);
    clonePertanyaan.find(`#bawah_jawaban_${row}`).attr('id', `bawah_jawaban_${currentRow}`);
    clonePertanyaan.find(`#addopsi_icon_${row}`)
      .attr('id', `addopsi_icon_${currentRow}`)
      .attr('onclick', `addOpsi(${currentRow}, this)`);
    clonePertanyaan.find(`#addopsi_${row}`)
      .attr('id', `addopsi_${currentRow}`)
      .attr('onclick', `addOpsi(${currentRow}, this)`);
    clonePertanyaan.find(`#checkhide_${row}`).attr('id', `checkhide_${currentRow}`);
    clonePertanyaan.find(`#checkshow_${row}`).attr('id', `checkshow_${currentRow}`);
    clonePertanyaan.find(`#duplicate_pertanyaan_${row}`)
      .attr('id', `duplicate_pertanyaan_${currentRow}`)
      .attr('onclick', `handleDuplicate(${currentRow}, this)`);
    clonePertanyaan.find(`#hapus_pertanyaan_${row}`)
      .attr('id', `hapus_pertanyaan_${currentRow}`)
      .attr('onclick', `delPertanyaan(${currentRow}, this)`);

    // Manipulate IDs & Attribute of OPSI
    let childjawaban = clonePertanyaan.find(`#jawaban_${currentRow}`).children();
    childjawaban.map((index, item) => {
      $(item).find(`#id_opsi_${row}_${index}`)
        .attr('id', `id_opsi_${currentRow}_${index}`)
        .attr('name', `id_opsi[${currentRow}][]`)
        .val("");
      $(item).find(`#nilai_${row}_${index}`)
        .attr('id', `nilai_${currentRow}_${index}`)
        .attr('name', `nilai[${currentRow}][]`);
      $(item).find(`#jawaban_${row}_${index}`)
        .attr('id', `jawaban_${currentRow}_${index}`)
        .attr('name', `jawaban[row_${currentRow}][]`);
      $(item).find(`#delopsi_${row}_${index}`)
        .attr('id', `delopsi_${currentRow}_${index}`)
        .attr('onclick', `delOpsi(${currentRow}, ${index}, this)`);
    });

    // console.log(childjawaban);

    // Append clone result
    clonePertanyaan.appendTo("#place_pertanyaan");

    changePertanyaanNumber();
    $("#add_pertanyaan").data("rows", currentRow+1);
  }

  function fieldInvalid(el){
		$(el).addClass("is-invalid");
	}

  function fieldChange(el){
		if($(el).val() === ""){
			$(el).addClass("is-invalid");
		}else{
			$(el).removeClass("is-invalid");
		}
	}

  function onReset(el){
    HELPER.confirm({
      message: "Semua data yang disi akan hilang, apakah anda yakin membatalkan survey ini?",
      callback: function(suc){
				if(suc){
          $("#form-survey").trigger('reset');
          $("#place_pertanyaan").html(htmlPertanyaan(0));
          $("#add_pertanyaan").data("rows", 1);
          $(".select2").select2();

          HELPER.showMessage({
            success: true,
            title: 'Success',
            message: 'Berhasil membatalkan survey.',
          })
        }
      }
    })
  }

  function numberOnly(el){
    let val = $(el).val().replace(/[^0-9][\D]*/g, '');
    $(el).val(val);
  }

  function readImage(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			const fsize = input.files[0].size;
			const file = Math.round((fsize / 1024));
			if (file > 2048) {
				HELPER.showMessage({
					success: false,
					message: 'File Melebihi 2 MB',
				});
				$('#title-banner').text("Choose file");
				$('#preview-image').attr('src', 'assets/media/noimage.png');
				$(input).val('');
			} else {
				reader.onload = function(e) {
					$('#title-banner').text($(input).val().split(/(\\|\/)/g).pop());
					$('#preview-image').attr('src', e.target.result)

					$('#modal-preview').modal('show');

					// $('#blah').attr('src', e.target.result);
					// $('.show-wajibpajak-image').css('background-image', 'url('+e.target.result+')');
				}

				reader.readAsDataURL(input.files[0]);
			}

		}
	}

	function onChangeBanner(el) {
		readImage($(el)[0]);
	}
</script>