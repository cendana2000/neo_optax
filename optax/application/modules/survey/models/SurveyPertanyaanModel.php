<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveyPertanyaanModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_survey_pertanyaan',
				'primary' => 'survey_pertanyaan_id',
				'fields' => array(
					array('name' => 'survey_pertanyaan_id'),
					array('name' => 'survey_pertanyaan_survey_id'),
					array('name' => 'survey_pertanyaan_judul'),
          array('name' => 'survey_pertanyaan_tipe'),
          array('name' => 'survey_pertanyaan_wajib_yn'),
          array('name' => 'survey_pertanyaan_index'),
					array('name' => 'survey_pertanyaan_created_at'),
					array('name' => 'survey_pertanyaan_updated_at'),
					array('name' => 'survey_pertanyaan_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'survey_pertanyaan_id', 
            'survey_pertanyaan_survey_id', 
            'survey_pertanyaan_judul',
            'survey_pertanyaan_tipe',
            'survey_pertanyaan_wajib_yn',
            'survey_pertanyaan_index',
            'survey_pertanyaan_created_at',
						'survey_pertanyaan_updated_at',
						'survey_pertanyaan_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file SurveyPertanyaanModel.php */
/* Location: ./application/modules/survey/models/SurveyPertanyaanModel.php */