<script type="text/javascript">
var wizard;
$(function (){
  HELPER.fields = [
    'survey_responden_id',
    'survey_responden_nama',
    'survey_responden_email',
    'survey_responden_alamat',
  ];
  HELPER.setRequired([]);
  HELPER.api = {
    store: BASE_URL+'survey/storejawaban',
    update: BASE_URL+'survey/storejawaban',
    read: BASE_URL+'survey/getsurveytoko',
  }

  wizard = new KTWizard('kt_wizard_v1', {
    startStep: 1, // Initial active step number
    clickableSteps: false,  // Allow step clicking
    navigation: false,
  });

  $(wizard.btnPrev).on('click', function(){
    wizard.goPrev();
  })

  $(wizard.btnNext).on('click', function(){
    if(next()){
      wizard.goNext();
    }
  })

  $(wizard.btnSubmit).on('click',function(){
    save();
  });

  console.log(wizard);

  /*
  const urlSearchParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlSearchParams.entries());
  console.log(params);
  onReadSurvey(params.q);
  */
  let survey = '<?= $survey ?>';
  let toko = '<?= $toko ?>';
  onReadSurvey(survey, toko);
});

function onReadSurvey(survey, toko){
  HELPER.loadData({
    url: HELPER.api.read,
    server: true,
    data: {
      survey,
      toko,
    },
    callback: function(res){
      if(res.toko){
        $('#step_1_toko').text(res.toko.toko_nama);
        $('#step_2_toko').text(res.toko.toko_nama);
      }

      console.log(res.survey_banner)
      if(res.survey_banner){
        $('#banner').show();
        $('#banner img').attr('src', BASE_URL_NO_INDEX + res.survey_banner)
      }else{
        $('#banner').hide();
      }

      // console.log(res);
      $('#survey_id').text(res.survey_id);
      $('#step_1_judul').text(res.survey_judul);
      $('#step_2_judul').text(res.survey_judul);
      $('#step_1_deskripsi').text(res.survey_deskripsi);
      $('#step_2_deskripsi').text(res.survey_deskripsi);      

      fillFormSurvey(res);

      statusSurvey(moment(res.survey_tgl_selesai).isAfter(moment()), res.survey_status);

      showDataResponden(res);
    }
  })
}

function showDataResponden(res){
  let required = [];
  console.log(res)
  if(res.survey_pengaturan_nama == "1"){
    $("#survey_responden_nama").parent().removeClass('d-none');
    required.push('survey_responden_nama');
  }else{
    $("#survey_responden_nama").parent().addClass('d-none');
  }

  if(res.survey_pengaturan_email == "1"){
    $("#survey_responden_email").parent().removeClass('d-none');
    required.push('survey_responden_email');
  }else{
    $("#survey_responden_email").parent().addClass('d-none');
  }

  if(res.survey_pengaturan_alamat == "1"){
    $("#survey_responden_alamat").parent().removeClass('d-none');
  }else{
    $("#survey_responden_alamat").parent().addClass('d-none');
  }

  HELPER.setRequired(required);
}

function statusSurvey(val = false, status = '0'){
  if(status == "1"){
    if(val){
      $('button[data-wizard-type="action-next"]').attr('disabled', false);
      $('input').attr('disabled', false);
      $('textarea').attr('disabled', false);
      $('#alert_tidak_aktif').addClass('d-none');
    }else{
      $('button[data-wizard-type="action-next"]').attr('disabled', true);
      $('input').attr('disabled', true);
      $('textarea').attr('disabled', true);
      $('#alert_tidak_aktif').removeClass('d-none');
    }
  }else{
    $('button[data-wizard-type="action-next"]').attr('disabled', true);
    $('input').attr('disabled', true);
    $('textarea').attr('disabled', true);
    $('#alert_tidak_aktif').removeClass('d-none');
  }
}

function fillFormSurvey(res){
  if(res && res.pertanyaan.length > 0){
    res.pertanyaan.map((item, index) => {
      $('#place_pertanyaan').append(htmlPertanyaan(index, item));
      // console.log(item.survey_pertanyaan_tipe);
      if(item.survey_pertanyaan_tipe === "0"){ // PILIHAN GANDA
        // console.log(item)
        item.opsi.map((oitem, oindex) => {
          $(`#answer_input_${index}`).append(htmlPilihanTunggal(index, oindex, oitem));
        });
      }else if(item.survey_pertanyaan_tipe === "1"){
        // console.log(item)
        item.opsi.map((oitem, oindex) => {
          $(`#answer_input_${index}`).append(htmlPilihanGanda(index, oindex, oitem));
        });
      }else if(item.survey_pertanyaan_tipe === "2"){ // PARAGRAF
        $(`#answer_input_${index}`).html(htmlParagraf(index));
      }
    })
  }
}

function htmlPertanyaan(row, item){
  return `
  <div class="form-group border-top pt-5">
    <input type="hidden" name="survey_pertanyaan_id" value="${item.survey_pertanyaan_id}"/>
    <label class="h5" id="judul_pertanyaan_${row}">${row+1}. ${item.survey_pertanyaan_judul} ${item.survey_pertanyaan_wajib_yn === 'Y' ? '<span class="text-danger">*</span> ' : ''}</label>
    <div id="answer_input_${row}" data-row="${row}" data-wajib="${item.survey_pertanyaan_wajib_yn}" data-tipe="${item.survey_pertanyaan_tipe}" data-judul="${item.survey_pertanyaan_judul}">
    </div>
  </div>
  `;
}

function htmlPilihanTunggal(row, index, item){
  return `
  <div class="radio-list mt-5">
    <label class="radio radio-success">
      <input type="radio" name="answer[${row}]" id="answer_input_${row}_${index}" value="${item.survey_pertanyaan_opsi_id}"/>
      <span></span>
      ${item.survey_pertanyaan_opsi_judul}
    </label>
  </div>
  `;
}

function htmlPilihanGanda(row, index, item){
  return `
  <div class="checkbox-list mt-5">
    <label class="checkbox checkbox-success">
      <input type="checkbox" name="answer[${row}]" id="answer_input_${row}_${index}" value="${item.survey_pertanyaan_opsi_id}"/>
      <span></span>
      ${item.survey_pertanyaan_opsi_judul}
    </label>
  </div>
  `;
}

function htmlParagraf(row){
  return `
    <textarea class="form-control mt-5" rows="5" placeholder="Masukan Jawaban" name="answer[${row}]" id="paragraf_${row}"></textarea>
  `;
}

function save(){
  let elswajib = $('div[data-wajib=Y]');
  let emptywajib = elswajib.map((index, item) => {
    let row = $(item).data('row');
    let tipe = $(item).data('tipe');
    let judul = $(item).data('judul');
    if(tipe === 0){
      if(!$(`input[name="answer[${row}]"]`).is(':checked')){
        return row;
      }
    }else if(tipe === 1){
      if(!$(`input[name="answer[${row}]"]`).is(':checked')){
        return row;
      }
    }else if(tipe === 2){
      if($(`textarea[name="answer[${row}]"]`) === ""){
        return row;
      }
    }
  }).get();
  if(emptywajib.length > 0){
    HELPER.showMessage({
      'message': 'Mohon isi bidang yang wajib!'
    });
    return;
  }
  // console.log(emptywajib)
  // console.log("here");

  HELPER.save({			
    form: 'form-survey',
    confirm: true,
    type: 'post',
    callback: function(success,id,record,message)
    {
      if (success===true) {
        $('.swal2-confirm').on('click', function() {
          window.location.reload();
        })
        // HELPER.back({});
      }
    }	
  })
}

function next(){
  var req = $('input[required]');
  console.log(req);
  var reqexist = req.map((index, item) =>{
    if($(item).val()){
      return;
    }else{
      return $(item).attr('name');
    }
  }).get().join(', ');
  if(reqexist){
     HELPER.showMessage({
      message: 'Pastikan bidang yang wajib sudah terisi!'
    });
    return false;
  }
  
  if($('#survey_responden_email').val()){
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(!$('#survey_responden_email').val().match(mailformat)){
      HELPER.showMessage({
        message: 'Email tidak valid! contoh: example@domain.com'
      });
      return false;
    }
  }

  return true
}

function imgError(image){
  image.onerror = "";
  image.src = `${BASE_URL_NO_INDEX}assets/media/noimage.png`;
}

</script>