<script type="text/javascript">
  $(function(){
    HELPER.api = {
      tablehasil: BASE_URL+'survey/gettablehasil',
      tabledata: BASE_URL+'survey/gettabledata',
      survey_select: BASE_URL+'survey/select_ajax',
      getlaporan: BASE_URL+'laporansurvey/getLaporan'
		}

    HELPER.setRequired([
      'data_survey'
    ])

    // $(".select2").select2();

    getAllSurvey();

    $('#laporan_rekap').DataTable();
    
  });

  function getAllSurvey(){
    HELPER.ajaxCombo({
			el: '#data_survey',
			url: HELPER.api.survey_select,
		});
  }

  function getLaporan() {
		HELPER.block();
		$.ajax({
			url: HELPER.api.getlaporan,
			data: $('#laporan_survey').serializeObject(),
			type: 'post',
			dataType: 'json',
			success: function(res) {

        $('#laporan_rekapan').addClass('d-none');
        $('#laporan_grafis').addClass('d-none');
        
        if(res.jenis == "rekapan"){
          getRekapan(res);
        }else if(res.jenis == "grafis"){
          getGrafis(res);
        }

				HELPER.unblock();
			}
		})
	}

  function getRekapan(res){
    $('#laporan_rekapan').removeClass('d-none');

    let htmltable = `
    <div class="mb-5">
      <div class="row">
          <div class="col-2 font-weight-bold">Nama Survey <span class="float-right">:</span></div>
          <div class="col-10">${res.result.survey_judul}</div>
      </div>
      <div class="row">
          <div class="col-2 font-weight-bold">Deskripsi <span class="float-right">:</span></div>
          <div class="col-10">${res.result.survey_deskripsi}</div>
      </div>
      <div class="row">
          <div class="col-2 font-weight-bold">Jumlah Peserta <span class="float-right">:</span></div>
          <div class="col-10">${res.result.jml_responden}</div>
      </div>
    </div>
    <table class="table table-bordered" id="laporan_rekap">
      <thead>
        <tr>
          <th rowspan="2" class="align-middle" width="1%">No.</th>
          <th rowspan="2" class="align-middle" width="5%">Tanggal Input</th>
          <th rowspan="2" class="align-middle">Nama</th>
          <th rowspan="2" class="align-middle">Email</th>
          <th rowspan="2" class="align-middle">Alamat</th>`;
          res.result.pertanyaan.map((item, index) => {
            htmltable += `<th class="align-middle">${index+1}</th>`
          })
          htmltable += `<th rowspan="2" class="align-middle">Total Nilai</th>
        </tr>
        <tr>`;
        res.result.pertanyaan.map((item, index) => {
          htmltable += `<th class="align-middle">${item.survey_pertanyaan_judul}</th>`
        })
        htmltable += `</tr>
      </thead>
      <tbody id="laporan_rekap_tbody">
      </tbody>
    </table>
    `;
    $("#place_table_rekapan").html(htmltable);

    // set datatable
    let tableRekap = $('#laporan_rekap').DataTable({
      columnDefs: [{
        "defaultContent": "-",
        "targets": "_all"
      }]
    });

    // clear data datatable
    console.log("clear");
    tableRekap.clear().draw();
    
    res.result.responden.forEach((item, index) => {
      let resultarr = [
        index+1,
        moment(item.survey_jawaban_created_at).format('DD/MM/YYYY'),
        item.survey_responden_nama,
        item.survey_responden_email,
        item.survey_responden_alamat,
      ];

      // item.jawaban.forEach((jitem, index) => {
        // console.log(typeof jitem)
      // })

      $.each(item.jawaban, (key, val) => {
        if(Array.isArray(val)){
          resultarr.push(val.map((item, index) => {return item.survey_pertanyaan_opsi_judul }).join(", "));
          return;
        }
        resultarr.push(val.survey_jawaban_jawaban);
      })

      resultarr.push(item.jawaban_nilai)

      tableRekap.row.add(resultarr).draw( false );
    })
  }

  function getGrafis(res){
    $('#laporan_grafis').removeClass('d-none');

    $("#place_grafis").html("");

    res.result.pertanyaan.map((item, index) => {
      $("#place_grafis").append(htmlPertanyaan(index, item));

      let labels = [];
      let series = [];
      if(item.survey_pertanyaan_tipe == "0"){
        item.opsi.forEach((item, index) => {
          labels.push(item.survey_pertanyaan_opsi_judul);
          series.push(item.count_jawaban);
        });
        getPieChart(`graph_${index}`, labels, series);
        $(`#graph_${index}`).addClass('d-flex justify-content-center')
      }else if(item.survey_pertanyaan_tipe == "1"){
        item.opsi.forEach((item, index) => {
          labels.push(item.survey_pertanyaan_opsi_judul);
          series.push(item.count_jawaban);
        });
        getBarChart(`graph_${index}`, labels, series);
      }else if(item.survey_pertanyaan_tipe == "2"){
        item.jawaban.map((jitem, jindex) => {
          $(`#graph_${index}`).append(htmlJawabanParagraf(jitem));
        });
        $(`#graph_${index}`)
          .addClass('d-flex flex-column border p-5 rounded pb-0')
          .attr('style', 'height:auto; max-height:350px; overflow-x:hidden; overflow-y: auto;');
      }
    })
  }

  function htmlPertanyaan(row, item){
    return `
    <div class="d-flex flex-column border-bottom mb-10 pb-10" id="pertanyaan_${row}">
      <label class="font-weight-bolder h3 mb-2" id="judul_${row}">${row+1}. ${item.survey_pertanyaan_judul}</label>
      <span class="text-muted font-weight-bold font-size-sm mb-5" id="respon_${row}">${item.responden} Responden</span>
      <div id="graph_${row}"></div>
    </div>
    `
  }

  function htmlJawabanParagraf(jawaban){
    return `<span class="p-2 px-3 border rounded mb-2">${jawaban}</span>`
  }

  function getPieChart(attrid, labels, series){
    var options = {
      series,
      chart: {
      width: 380,
      type: 'pie',
    },
    labels,
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        },
        legend: {
          position: 'bottom'
        }
      }
    }]
    };

    var chart = new ApexCharts(document.querySelector(`#${attrid}`), options);
    chart.render();
  }

  function getBarChart(attrid, categories, dataSeries){
    var options = {
      series: [{
      data: dataSeries
    }],
      chart: {
      type: 'bar',
      height: 350
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      categories,
    }
    };

    var chart = new ApexCharts(document.querySelector(`#${attrid}`), options);
    chart.render();
  }

  /*
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
  */
</script>