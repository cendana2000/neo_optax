<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveyJawabanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_survey_jawaban',
				'primary' => 'survey_jawaban_id',
				'fields' => array(
					array('name' => 'survey_jawaban_id'),
					array('name' => 'survey_jawaban_survey_pertanyaan_id'),
					array('name' => 'survey_jawaban_survey_responden_id'),
          array('name' => 'survey_jawaban_jawaban'),
					array('name' => 'survey_jawaban_created_at'),
					array('name' => 'survey_jawaban_updated_at'),
					array('name' => 'survey_jawaban_deleted_at'),
					array('name' => 'survey_pertanyaan_judul', 'view' => true),
					array('name' => 'survey_pertanyaan_tipe', 'view' => true),
					array('name' => 'survey_pertanyaan_wajib_yn', 'view' => true),
					array('name' => 'survey_pertanyaan_index', 'view' => true),
					array('name' => 'survey_pertanyaan_opsi_judul', 'view' => true),
					array('name' => 'survey_pertanyaan_opsi_index', 'view' => true),
					array('name' => 'survey_pertanyaan_opsi_nilai', 'view' => true),
				)
			),
			'view' => array(
				'name' => 'v_pajak_survey_jawaban',
				'mode' => array(
					'table' => array(
						'survey_jawaban_id', 
            'survey_jawaban_survey_pertanyaan_id', 
            'survey_jawaban_survey_responden_id',
            'survey_jawaban_jawaban',
            'survey_jawaban_created_at',
						'survey_jawaban_updated_at',
						'survey_jawaban_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file SurveyJawabanModel.php */
/* Location: ./application/modules/survey/models/SurveyJawabanModel.php */