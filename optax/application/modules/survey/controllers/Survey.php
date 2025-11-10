<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends Base_Controller
{

  public function __construct()
  {

    parent::__construct();
    //Do your magic here
    $this->load->model(array(
      'SurveyModel' => 'survey',
      'SurveyPertanyaanModel' => 'surveypertanyaan',
      'SurveyPertanyaanOpsiModel' => 'surveypertanyaanopsi',
      'SurveyRespondenModel' => 'surveyresponden',
      'SurveyJawabanModel' => 'surveyjawaban',
      'toko/TokoModel' => 'toko',
    ));
  }

  /*
   * 
   * RESPONDEN
   * 
   */
  public function index($value = '')
  {
    show_404();
    // $this->load->view('FormJawaban');
  }

  public function form($survey = '', $toko = '')
  {
    $data = array(
      'survey' => $survey,
      'toko' => $toko,
    );
    $this->load->view('FormJawaban', $data);
  }


  public function storejawaban()
  {
    $data = varPost();

    // print_r($data);exit;

    $dataresponen = array(
      'survey_responden_survey_id' => $data['survey_id'],
      'survey_responden_email' => strtolower($data['survey_responden_email']),
      'survey_responden_nama' => $data['survey_responden_nama'],
      'survey_responden_alamat' => $data['survey_responden_alamat'],
      'survey_responden_created_at' => date('Y-m-d H:i:s'),
    );

    $operation = $this->surveyresponden->insert(gen_uuid($this->surveyresponden->get_table()), $dataresponen, function ($res) use ($data) {
      foreach ($data['survey_pertanyaan_id'] as $key => $val) {
        if (is_array($data['answer'][$key])) {
          foreach ($data['answer'][$key] as $akey => $aval) {
            $datajawaban = array(
              'survey_jawaban_survey_pertanyaan_id' => $val,
              'survey_jawaban_survey_responden_id' => $res['record']['survey_responden_id'],
              'survey_jawaban_jawaban' => $aval,
              'survey_jawaban_created_at' => date('Y-m-d H:i:s'),
            );
            $this->surveyjawaban->insert(gen_uuid($this->surveyjawaban->get_table()), $datajawaban);
          }
        } else {
          $datajawaban = array(
            'survey_jawaban_survey_pertanyaan_id' => $val,
            'survey_jawaban_survey_responden_id' => $res['record']['survey_responden_id'],
            'survey_jawaban_jawaban' => $data['answer'][$key],
            'survey_jawaban_created_at' => date('Y-m-d H:i:s'),
          );
          $this->surveyjawaban->insert(gen_uuid($this->surveyjawaban->get_table()), $datajawaban);
        }
      }
    });

    $this->response($operation);
  }

  public function getsurveytoko()
  {
    $data = varPost();
    $result = $this->survey->read(array('survey_judul' => $data['survey']));
    $where_pertanyaan = array(
      'survey_pertanyaan_survey_id' => $result['survey_id']
    );
    $result['pertanyaan'] = $this->surveypertanyaan->select(array('filters_static' => $where_pertanyaan, 'sort_static' => 'survey_pertanyaan_index asc'))['data'];
    foreach ($result['pertanyaan'] as $key => $value) {
      if ($value['survey_pertanyaan_tipe'] == "0" || $value['survey_pertanyaan_tipe'] == "1") {
        $where_opsi = array(
          'survey_pertanyaan_opsi_survey_pertanyaan_id' => $value['survey_pertanyaan_id']
        );
        $result['pertanyaan'][$key]['opsi'] = $this->surveypertanyaanopsi->select(array('filters_static' => $where_opsi, 'sort_static' => 'survey_pertanyaan_opsi_index asc'))['data'];
      }
    }

    $result['toko'] = $this->toko->read(array('toko_kode' => $data['toko']));

    $this->response($result);
  }

  public function get_active_survey($kodetoko = '')
  {
    $survey = $this->survey->read(array('survey_status' => '1'));
    if (!empty($survey)) {
      redirect(base_url() . 'index.php/survey/form/' . rawurlencode($survey['survey_judul']) . '/' . $kodetoko);
    } else {
      redirect(base_url() . 'index.php/survey/form/inactive/' . $kodetoko);
    }
  }

  /*
   * 
   * SURVEY CMS
   * 
   */
  public function select_ajax($value = '')
  {
    $data = varPost();
    $return = $this->survey->select(array(
      'custom_fields' => 'survey_id as id, survey_judul as text'
    ))['data'];
    $this->response(array('items' => $return, 'total_count' => count($return)));
  }

  public function gettabledata()
  {
    $this->response($this->select_dt(varPost(), 'survey', 'table'));
  }

  public function gettablehasil()
  {
    $operation = $this->db->query("SELECT *, 
    (SELECT COUNT(*) FROM pajak_survey_responden psr where survey_responden_survey_id = ps.survey_id)  as survey_jml_jawaban
    FROM pajak_survey ps
    ORDER BY survey_status asc, survey_tgl_selesai desc, survey_created_at desc");
    // $this->response($this->select_dt(varPost(), 'survey', 'table'));
    $reshasil = $operation->result_array();
    foreach ($reshasil as $key => $val) {
      $number = $key + 1;
      $reshasil[$key]['0'] = "<span class=\"not-checkbox\">$number.</span>";
      $tgl_selesai = strtotime($reshasil[$key]['survey_tgl_selesai']);
      $tgl_now = strtotime(date('Y-m-d H:i:s'));
      // print_r($tgl_selesai > $tgl_now);exit;
      $reshasil[$key]['survey_status'] = $tgl_selesai > $tgl_now  ? $reshasil[$key]['survey_status'] : '0';
    }
    $this->response(array(
      'iTotalRecords'      => count($operation->result_array()),
      'iTotalDisplayRecords'   => count($operation->result_array()),
      "sEcho" => 0,
      "sColumns" => "",
      'aaData' => $reshasil
    ));
  }

  public function read()
  {
    // die(varPost('survey_id'));
    $result = $this->survey->read(varPost('survey_id'));
    $where_pertanyaan = array(
      'survey_pertanyaan_survey_id' => varPost('survey_id')
    );
    $result['pertanyaan'] = $this->surveypertanyaan->select(array('filters_static' => $where_pertanyaan, 'sort_static' => 'survey_pertanyaan_index asc'))['data'];
    foreach ($result['pertanyaan'] as $key => $value) {
      if ($value['survey_pertanyaan_tipe'] == "0" || $value['survey_pertanyaan_tipe'] == "1") {
        $where_opsi = array(
          'survey_pertanyaan_opsi_survey_pertanyaan_id' => $value['survey_pertanyaan_id']
        );
        $result['pertanyaan'][$key]['opsi'] = $this->surveypertanyaanopsi->select(array('filters_static' => $where_opsi, 'sort_static' => 'survey_pertanyaan_opsi_index asc'))['data'];
      }
    }
    // (SELECT COUNT(*) FROM pajak_survey_responden psr where survey_responden_survey_id = ps.survey_id)
    $result['jml_responden'] = $this->db->select('count(*) as jml')->get_where('pajak_survey_responden', array('survey_responden_survey_id' => varPost('survey_id')))->row('jml');
    // print_r($result);exit;

    $this->response($result);
  }

  public function store()
  {
    $data = varPost();

    // Upload Banner
    $uploadImage = $this->uploadImage();
		$uploadpath = $uploadImage['uploadpath'];

    // print_r('<pre>');print_r($uploadImage);print_r('</pre>');exit;

    $currentdate = strtotime(date('Y-m-d H:i:s'));
    $enddate = strtotime($data['survey_tgl_selesai']);

    if ($currentdate < $enddate && $data['survey_status'] == '1') {
      // Change all survey_status to 0
      $this->survey->update(array('survey_id IS NOT NULL' => null), array('survey_status' => '0'));
    }

    $datasurvey = array(
      'survey_judul' => $data['survey_judul'],
      'survey_tgl_publish' => date("Y-m-d H:i:s", strtotime($data['survey_tgl_publish'])),
      'survey_tgl_selesai' => date("Y-m-d H:i:s", strtotime($data['survey_tgl_selesai'])),
      'survey_status' => $data['survey_status'],
      'survey_deskripsi' => $data['survey_deskripsi'],
      'survey_created_at' => date('Y-m-d H:i:s'),
      'survey_jml_pertanyaan' => count($data['tipe_pertanyaan']),
      'survey_pengaturan_nama' => isset($data['survey_pengaturan_nama']) ? 1 : 0,
      'survey_pengaturan_email' => isset($data['survey_pengaturan_email']) ? 1 : 0,
      'survey_pengaturan_alamat' => isset($data['survey_pengaturan_alamat']) ? 1 : 0,
      'survey_banner' => $uploadpath,
    );

    $datasurvey = cvarNull($datasurvey);

    $operation = $this->survey->insert(gen_uuid($this->survey->get_table()), $datasurvey, function ($res) use ($data) {
      foreach ($data['tipe_pertanyaan'] as $key => $val) {
        $datapertanyaan = array(
          'survey_pertanyaan_survey_id' => $res['record']['survey_id'],
          'survey_pertanyaan_judul' => $data['judul_pertanyaan'][$key],
          'survey_pertanyaan_tipe' => $val,
          'survey_pertanyaan_wajib_yn' => $data['checkwajib'][$key],
          'survey_pertanyaan_created_at' => date('Y-m-d H:i:s'),
          'survey_pertanyaan_index' => $key,
        );

        $datapertanyaan = cvarNull($datapertanyaan);

        $this->surveypertanyaan->insert(gen_uuid($this->surveypertanyaan->get_table()), $datapertanyaan, function ($res) use ($data, $key) {
          if ($res['record']['survey_pertanyaan_tipe'] == "0" || $res['record']['survey_pertanyaan_tipe'] == "1") {
            foreach ($data['jawaban']['row_' . $data['row'][$key]] as $jkey => $val) {
              $dataopsi = array(
                'survey_pertanyaan_opsi_survey_pertanyaan_id' => $res['record']['survey_pertanyaan_id'],
                'survey_pertanyaan_opsi_judul' => $val,
                'survey_pertanyaan_opsi_nilai' => $data['nilai'][$data['row'][$key]][$jkey],
                'survey_pertanyaan_opsi_created_at' => date('Y-m-d H:i:s'),
                'survey_pertanyaan_opsi_index' => $jkey,
              );

              $dataopsi = cvarNull($dataopsi);

              $this->surveypertanyaanopsi->insert(gen_uuid($this->surveypertanyaanopsi->get_table()), $dataopsi);
            }
          }
        });
      }
    });

    $this->response($operation);
  }

  public function update()
  {
    $data = varPost();

    // Upload Banner
    $uploadImage = $this->uploadImage();
		$uploadpath = $uploadImage['uploadpath'];

    $currentdate = strtotime(date('Y-m-d H:i:s'));
    $enddate = strtotime($data['survey_tgl_selesai']);

    if ($currentdate < $enddate && $data['survey_status'] == '1') {
      // Change all survey_status to 0
      $this->survey->update(array('survey_id IS NOT NULL' => null), array('survey_status' => '0'));
    }

    // GET ID PERTANYAAN AND DELETE OPSI
    $where_pertanyaan = array('survey_pertanyaan_survey_id' => $data['survey_id']);
    $select_pertanyaan = $this->surveypertanyaan->select(array('filters_static' => $where_pertanyaan));

    if (isset($select_pertanyaan) && count($select_pertanyaan['data']) > 0) {
      foreach ($select_pertanyaan['data'] as $key => $val) {
        // DEL PERTANYAAN
        $del_opsi = $this->surveypertanyaanopsi->delete(array('survey_pertanyaan_opsi_survey_pertanyaan_id' => $val['survey_pertanyaan_id']));
      }
    }
    $del_pertanyaan = $this->surveypertanyaan->delete(array('survey_pertanyaan_survey_id' => $data['survey_id']));

    $datasurvey = array(
      'survey_judul' => $data['survey_judul'],
      'survey_tgl_publish' => date("Y-m-d H:i:s", strtotime($data['survey_tgl_publish'])),
      'survey_tgl_selesai' => date("Y-m-d H:i:s", strtotime($data['survey_tgl_selesai'])),
      'survey_status' => $data['survey_status'],
      'survey_deskripsi' => $data['survey_deskripsi'],
      'survey_updated_at' => date('Y-m-d H:i:s'),
      'survey_jml_pertanyaan' => count($data['tipe_pertanyaan']),
      'survey_pengaturan_nama' => isset($data['survey_pengaturan_nama']) ? 1 : 0,
      'survey_pengaturan_email' => isset($data['survey_pengaturan_email']) ? 1 : 0,
      'survey_pengaturan_alamat' => isset($data['survey_pengaturan_alamat']) ? 1 : 0,
    );

    if (isset($uploadpath)) {
			$datasurvey['survey_banner'] = $uploadpath;
		}

    // print_r($datasurvey);
    // die();

    // UPDATE SURVEY
    $operation = $this->survey->update(varPost('survey_id', varExist($data, $this->survey->get_primary(true))), $datasurvey, function ($res) use ($data) {
      foreach ($data['tipe_pertanyaan'] as $key => $val) {
        $datapertanyaan = array(
          'survey_pertanyaan_survey_id' => $res['record']['survey_id'],
          'survey_pertanyaan_judul' => $data['judul_pertanyaan'][$key],
          'survey_pertanyaan_tipe' => $val,
          'survey_pertanyaan_wajib_yn' => $data['checkwajib'][$key],
          'survey_pertanyaan_created_at' => date('Y-m-d H:i:s'),
          'survey_pertanyaan_index' => $key,
        );
        $pertanyaan_id = gen_uuid($this->surveypertanyaan->get_table());
        if (isset($data['id_pertanyaan'][$key]) && !empty($data['id_pertanyaan'][$key])) {
          $pertanyaan_id = $data['id_pertanyaan'][$key];
        }

        // CREATE PERTANYAAN
        $this->surveypertanyaan->insert($pertanyaan_id, $datapertanyaan, function ($res) use ($data, $key) {
          if ($res['record']['survey_pertanyaan_tipe'] == "0" || $res['record']['survey_pertanyaan_tipe'] == "1") {
            foreach ($data['jawaban']['row_' . $data['row'][$key]] as $jkey => $val) {
              $dataopsi = array(
                'survey_pertanyaan_opsi_survey_pertanyaan_id' => $res['record']['survey_pertanyaan_id'],
                'survey_pertanyaan_opsi_judul' => $val,
                'survey_pertanyaan_opsi_nilai' => $data['nilai'][$data['row'][$key]][$jkey],
                'survey_pertanyaan_opsi_created_at' => date('Y-m-d H:i:s'),
                'survey_pertanyaan_opsi_index' => $jkey,
              );
              $opsi_id = gen_uuid($this->surveypertanyaanopsi->get_table());
              if (isset($data['id_opsi'][$data['row'][$key]])) {
                if (isset($data['id_opsi'][$data['row'][$key]][$jkey]) && !empty($data['id_opsi'][$data['row'][$key]][$jkey])) {
                  $opsi_id = $data['id_opsi'][$data['row'][$key]][$jkey];
                }
              }
              // CREATE OPSI
              $this->surveypertanyaanopsi->insert($opsi_id, $dataopsi);
            }
          }
        });
      }
    });

    $this->response($operation);
  }

  public function destroy()
  {
    $data = varPost();

    $operation = $this->survey->delete(varPost('survey_id', varExist($data, $this->survey->get_primary(true))));
    $pertanyaan = $this->surveypertanyaan->select(array('filters_static' => array('survey_pertanyaan_survey_id' => $data['survey_id'])))['data'];

    foreach ($pertanyaan as $key => $value) {
      if ($value['survey_pertanyaan_tipe'] == "0" || $value['survey_pertanyaan_tipe'] == "1") {
        $this->surveypertanyaanopsi->delete(array('survey_pertanyaan_opsi_survey_pertanyaan_id' => $value['survey_pertanyaan_id']));
      }
    }

    $this->surveypertanyaan->delete(array('survey_pertanyaan_survey_id' => $data['survey_id']));

    $this->response($operation);
  }

  public function changeStatus()
  {
    $data = varPost();
    // print_r('<pre>');print_r($data);print_r('</pre>');exit;

    $survey = $this->survey->read($data['id']);

    $currentdate = strtotime(date('Y-m-d H:i:s'));
    $enddate = strtotime($survey['survey_tgl_selesai']);

    if ($data['status'] == "true") {
      $op = $this->survey->update($data['id'], array('survey_status' => '0'));
      $this->response($op);
      return;
    } else {
      if ($currentdate < $enddate) {
        // Change all survey_status to 0
        $this->survey->update(array('survey_id IS NOT NULL' => null), array('survey_status' => '0'));

        $op = $this->survey->update($data['id'], array('survey_status' => '1'));

        $this->response($op);
        return;
      }
    }


    $this->response(array(
      'success' => false,
      'message' => 'Tidak dapat mengubah status. Tanggal selesai sudah terlewati, mohon perpanjang tanggal selesai.',
    ));
  }

  function uploadImage()
	{
		$file = $_FILES['survey_banner']['name'];
		$config['upload_path']  = './assets/survey/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = 2048;
		$config['file_name'] = uniqid('srv_', false) . '.' . pathinfo($file, PATHINFO_EXTENSION);

		$this->upload->initialize($config);

		if (!$this->upload->do_upload('survey_banner')) {
			$uploadstatus = "failed";
			$uploadmessage = $this->upload->display_errors('', '');
		} else {
			$uploadstatus = "success";
			$uploadmessage = "Success upload gambar";

			$uploadedImage = $this->upload->data();
			// $genthumbnail = $this->generateThumbnail($uploadedImage['file_name'], $config['upload_path']);

			$uploadpath = ltrim($config['upload_path'], '.') . $uploadedImage['file_name'];
		}

		return array(
			"uploadpath" => $uploadpath,
			// "genthumbnail" => $genthumbnail,
			"uploadstats" => (object)[
				"status" => $uploadstatus,
				"message" => $uploadmessage,
			]
		);
	}
}
