<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SurveyModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_survey',
				'primary' => 'survey_id',
				'fields' => array(
					array('name' => 'survey_id'),
					array('name' => 'survey_judul'),
					array('name' => 'survey_tgl_publish'),
          array('name' => 'survey_tgl_selesai'),
          array('name' => 'survey_deskripsi'),
          array('name' => 'survey_status'),
          array('name' => 'survey_jml_pertanyaan'),
          array('name' => 'survey_pengaturan_nama'),
          array('name' => 'survey_pengaturan_email'),
          array('name' => 'survey_pengaturan_alamat'),
					array('name' => 'survey_created_at'),
					array('name' => 'survey_updated_at'),
					array('name' => 'survey_deleted_at'),
					array('name' => 'survey_banner'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'survey_id', 
            'survey_judul', 
            'survey_tgl_publish',
            'survey_tgl_selesai',
            'survey_deskripsi',
            'survey_status',
            'survey_jml_pertanyaan',
            'survey_created_at',
						'survey_updated_at',
						'survey_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file SurveyModel.php */
/* Location: ./application/modules/survey/models/SurveyModel.php */