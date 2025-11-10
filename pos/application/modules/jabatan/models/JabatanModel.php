<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class JabatanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ms_jabatan',
				'primary' => 'jabatan_id',
				'fields' => array(
					array('name'=>'jabatan_id', 'unique' => true),
					array('name'=>'jabatan_nama'),
					array('name'=>'jabatan_created_at'),
					array('name'=>'jabatan_updated_at'),
					array('name'=>'jabatan_created_by'),
					array('name'=>'jabatan_updated_by'),
					array('name'=>'jabatan_status'),
				)
			),
			'view' => array(
				'name' => 'ms_jabatan',
				'mode' => array(
					'datatable' => array(
						'jabatan_id',
						'jabatan_nama',
						'jabatan_created_at',
						'jabatan_updated_at',
						'jabatan_created_by',
						'jabatan_updated_by',
						'jabatan_status',
					)
				)
			)
		);
		parent::__construct($model);		
	}
}