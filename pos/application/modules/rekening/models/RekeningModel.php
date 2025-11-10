<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekeningModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_rekening',
				'primary' => 'rekening_id',
				'fields' => array(
					array('name' => 'rekening_id'),
					array('name' => 'rekening_nama'),
					array('name' => 'rekening_no'),
					array('name' => 'rekening_bank'),
					array('name' => 'rekening_created_at'),
					array('name' => 'rekening_updated_at'),
					array('name' => 'rekening_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'rekening_id',
						'rekening_no',
						'rekening_nama',
						'rekening_nama',
						'rekening_bank',
						'rekening_created_at',
						'rekening_updated_at',
						'rekening_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file rekeninganggotaModel.php */
/* Location: ./application/modules/rekeninganggota/models/rekeninganggotaModel.php */