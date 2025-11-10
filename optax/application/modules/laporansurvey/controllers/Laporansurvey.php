<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporansurvey extends Base_Controller {
  
  public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'survey/SurveyModel' => 'survey',
      'survey/SurveyPertanyaanModel' => 'surveypertanyaan',
      'survey/SurveyPertanyaanOpsiModel' => 'surveypertanyaanopsi',
      'survey/SurveyRespondenModel' => 'surveyresponden',
      'survey/SurveyJawabanModel' => 'surveyjawaban',
		));
	}

  public function index(){
    $this->response("HELLO WORLD");
  }

	public function getLaporan(){
		$data = varPost();

		
		$jenis = $data['jenis_laporan'];
		
		if($jenis == "rekapan"){
			$data_survey = $this->read();
			$result = $data_survey;
		}else{
			$result = $this->getInfoGrafis();
		}

		$this->response(array(
			'jenis' => $data['jenis_laporan'],
			'result' => $result
		));
	}

	public function read(){
		$survey_id = varPost('data_survey');
    $result = $this->survey->read($survey_id);
    $where_pertanyaan = array(
      'survey_pertanyaan_survey_id' => $survey_id
    );
    $result['pertanyaan'] = $this->surveypertanyaan->select(array('filters_static' => $where_pertanyaan, 'sort_static' => 'survey_pertanyaan_index asc'))['data'];
    foreach($result['pertanyaan'] as $key => $value){
      if($value['survey_pertanyaan_tipe'] == "0" || $value['survey_pertanyaan_tipe'] == "1"){
        $where_opsi = array(
          'survey_pertanyaan_opsi_survey_pertanyaan_id' => $value['survey_pertanyaan_id']
        );
        $result['pertanyaan'][$key]['opsi'] = $this->surveypertanyaanopsi->select(array('filters_static' => $where_opsi, 'sort_static' => 'survey_pertanyaan_opsi_index asc'))['data'];
      }
    }
    // (SELECT COUNT(*) FROM pajak_survey_responden psr where survey_responden_survey_id = ps.survey_id)
    $result['jml_responden'] = $this->db->select('count(*) as jml')->get_where('pajak_survey_responden', array('survey_responden_survey_id' => $survey_id))->row('jml');
    

		$result['responden'] = $this->surveyresponden->select(array(
			'filters_static' => array('survey_responden_survey_id' => $survey_id),
			'sort_static' => 'survey_responden_created_at desc'
		))['data'];

		foreach($result['responden'] as $key => $val){
			$jawaban = $this->surveyjawaban->select(array(
				'filters_static' => array(
					'survey_jawaban_survey_responden_id' => $val['survey_responden_id'],
				), 
				'sort_static' => 'survey_pertanyaan_index asc, survey_pertanyaan_opsi_index asc'))['data'];
			$groupduplicatejawaban = array();

			$nilai = 0;
			foreach($result['pertanyaan'] as $pkey => $pval){
				if($pval['survey_pertanyaan_tipe'] == "0" || $pval['survey_pertanyaan_tipe'] == "1"){
					$groupduplicatejawaban[$pval['survey_pertanyaan_id']] = [];
				}else{
					$groupduplicatejawaban[$pval['survey_pertanyaan_id']] = new stdClass();
				}
				foreach($jawaban as $jkey => $jval){
					if($pval['survey_pertanyaan_id'] == $jval['survey_jawaban_survey_pertanyaan_id']){
						if($jval['survey_pertanyaan_tipe'] == "0" || $jval['survey_pertanyaan_tipe'] == "1"){
							$groupduplicatejawaban[$jval['survey_jawaban_survey_pertanyaan_id']][] = $jval;
						}else{
							$groupduplicatejawaban[$jval['survey_jawaban_survey_pertanyaan_id']] = $jval;
						}
						$nilai += $jval['survey_pertanyaan_opsi_nilai'];
					}
				}
			}

			$result['responden'][$key]['jawaban'] = $groupduplicatejawaban;

			$result['responden'][$key]['jawaban_nilai'] = $nilai;
		}

		return $result;
  }

	public function getInfoGrafis(){
		$survey_id = varPost('data_survey');
    $result = $this->survey->read($survey_id);
    $where_pertanyaan = array(
      'survey_pertanyaan_survey_id' => $survey_id
    );
    $result['pertanyaan'] = $this->surveypertanyaan->select(array('filters_static' => $where_pertanyaan, 'sort_static' => 'survey_pertanyaan_index asc'))['data'];
    foreach($result['pertanyaan'] as $key => $value){
      if($value['survey_pertanyaan_tipe'] == "0" || $value['survey_pertanyaan_tipe'] == "1"){
        $where_opsi = array(
          'survey_pertanyaan_opsi_survey_pertanyaan_id' => $value['survey_pertanyaan_id']
        );
        $result['pertanyaan'][$key]['opsi'] = $this->surveypertanyaanopsi->select(array('filters_static' => $where_opsi, 'sort_static' => 'survey_pertanyaan_opsi_index asc'))['data'];
      }
    }
    // (SELECT COUNT(*) FROM pajak_survey_responden psr where survey_responden_survey_id = ps.survey_id)
    $result['jml_responden'] = $this->db->select('count(*) as jml')->get_where('pajak_survey_responden', array('survey_responden_survey_id' => $survey_id))->row('jml');

		// /*
		$responden = $this->surveyresponden->select(array(
			'filters_static' => array('survey_responden_survey_id' => $survey_id),
			'sort_static' => 'survey_responden_created_at desc'
		))['data'];

		foreach($responden as $key => $val){
			$jawaban = $this->surveyjawaban->select(array(
				'filters_static' => array(
					'survey_jawaban_survey_responden_id' => $val['survey_responden_id'],
				), 
				'sort_static' => 'survey_pertanyaan_index asc, survey_pertanyaan_opsi_index asc'))['data'];
			$groupduplicatejawaban = array();

			$nilai = 0;
			foreach($jawaban as $jkey => $jval){
				if($jval['survey_pertanyaan_tipe'] == "0" || $jval['survey_pertanyaan_tipe'] == "1"){
					$groupduplicatejawaban[$jval['survey_jawaban_survey_pertanyaan_id']][] = $jval;
				}else{
					// $jval_final = $jval;
					$groupduplicatejawaban[$jval['survey_jawaban_survey_pertanyaan_id']] = $jval;
				}
				$nilai += $jval['survey_pertanyaan_opsi_nilai'];
			}

			$responden[$key]['jawaban'] = $groupduplicatejawaban;

			$responden[$key]['jawaban_nilai'] = $nilai;
		}

		foreach($result['pertanyaan'] as $key => $val){
			$result['pertanyaan'][$key]['responden'] = 0;
			$result['pertanyaan'][$key]['jawaban'] = [];

			foreach($responden as $rkey => $rval){
				if(is_array($rval['jawaban'][$val['survey_pertanyaan_id']])){
					if(isset($rval['jawaban'][$val['survey_pertanyaan_id']]['survey_jawaban_jawaban']) && $rval['jawaban'][$val['survey_pertanyaan_id']]['survey_jawaban_jawaban'] != ""){
						$result['pertanyaan'][$key]['responden'] += 1;
						$result['pertanyaan'][$key]['jawaban'][] = $rval['jawaban'][$val['survey_pertanyaan_id']]['survey_jawaban_jawaban']; 
					}

					if(!isset($rval['jawaban'][$val['survey_pertanyaan_id']]['survey_jawaban_jawaban'])){
						$result['pertanyaan'][$key]['responden'] += 1;
						foreach($result['pertanyaan'][$key]['opsi'] as $okey => $oval){
							
							if(!isset($result['pertanyaan'][$key]['opsi'][$okey]['count_jawaban'])){
								$result['pertanyaan'][$key]['opsi'][$okey]['count_jawaban'] = 0;
							}
							foreach($rval['jawaban'][$val['survey_pertanyaan_id']] as $jkey => $jval){
								if($oval['survey_pertanyaan_opsi_id'] == $jval['survey_jawaban_jawaban']){
									$result['pertanyaan'][$key]['opsi'][$okey]['count_jawaban'] += 1;
								}
							}
						}
					}

				}
			}
		}
		// */

		return $result;
	}
}