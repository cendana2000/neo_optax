<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProjectModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'project',
				'primary' => 'project_id',
				'fields' => array(
					array('name' => 'project_id', 'unique' => true),
					array('name' => 'project_project_owner_id'),
					array('name' => 'project_drill_company_id'),
					array('name' => 'project_code'),
					array('name' => 'project_name'),
					array('name' => 'project_location'),
					array('name' => 'project_description'),
					array('name' => 'project_hole_plan'),
					array('name' => 'project_core_plan'),
					array('name' => 'project_borehole_plan'),
					array('name' => 'project_total_sample_plan'),
					array('name' => 'project_start_date'),
					array('name' => 'project_end_date'),
					array('name' => 'project_hole_akm'),
					array('name' => 'project_core_akm'),
					array('name' => 'project_borehole_akm'),
					array('name' => 'project_akm_meterage'),
					array('name' => 'project_total_sample_akm'),
					array('name' => 'project_map_link'),
					array('name' => 'project_created_at'),
					array('name' => 'project_updated_at'),
					array('name' => 'project_deleted_at'),
				)
			),
			'view' => array(
				'name' => 'project',
				'mode' => array(
					'datatable' => array(
						'project_id',
						'project_project_owner_id',
						'project_drill_company_id',
						'project_code',
						'project_name',
						'project_location',
						'project_description',
						'project_hole_plan',
						'project_core_plan',
						'project_borehole_plan',
						'project_total_sample_plan',
						'project_start_date',
						'project_end_date',
						'project_hole_akm',
						'project_core_akm',
						'project_borehole_akm',
						'project_akm_meterage',
						'project_total_sample_akm',
						'project_map_link',
						'project_created_at',
						'project_updated_at',
						'project_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
	}
}
