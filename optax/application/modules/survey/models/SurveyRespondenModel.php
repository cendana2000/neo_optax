<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveyRespondenModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_survey_responden',
				'primary' => 'survey_responden_id',
				'fields' => array(
					array('name' => 'survey_responden_id'),
					array('name' => 'survey_responden_survey_id'),
					array('name' => 'survey_responden_email'),
          array('name' => 'survey_responden_nama'),
          array('name' => 'survey_responden_alamat'),
					array('name' => 'survey_responden_created_at'),
					array('name' => 'survey_responden_updated_at'),
					array('name' => 'survey_responden_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'survey_responden_id', 
            'survey_responden_survey_id', 
            'survey_responden_email',
            'survey_responden_nama',
            'survey_responden_alamat',
            'survey_responden_created_at',
						'survey_responden_updated_at',
						'survey_responden_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file SurveyRespondenModel.php */
/* Location: ./application/modules/survey/models/SurveyRespondenModel.php */