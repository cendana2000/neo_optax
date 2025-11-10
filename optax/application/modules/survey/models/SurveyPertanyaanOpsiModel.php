<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveyPertanyaanOpsiModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_survey_pertanyaan_opsi',
				'primary' => 'survey_pertanyaan_opsi_id',
				'fields' => array(
					array('name' => 'survey_pertanyaan_opsi_id'),
					array('name' => 'survey_pertanyaan_opsi_survey_pertanyaan_id'),
					array('name' => 'survey_pertanyaan_opsi_judul'),
					array('name' => 'survey_pertanyaan_opsi_index'),
					array('name' => 'survey_pertanyaan_opsi_nilai'),
					array('name' => 'survey_pertanyaan_opsi_created_at'),
					array('name' => 'survey_pertanyaan_opsi_updated_at'),
					array('name' => 'survey_pertanyaan_opsi_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'survey_pertanyaan_opsi_id', 
            'survey_pertanyaan_opsi_survey_pertanyaan_id', 
            'survey_pertanyaan_opsi_judul',
            'survey_pertanyaan_opsi_index',
            'survey_pertanyaan_opsi_nilai',
            'survey_pertanyaan_opsi_created_at',
						'survey_pertanyaan_opsi_updated_at',
						'survey_pertanyaan_opsi_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file SurveyPertanyaanOpsiModel.php */
/* Location: ./application/modules/survey/models/SurveyPertanyaanOpsiModel.php */