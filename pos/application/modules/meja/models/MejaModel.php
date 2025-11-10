<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MejaModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_meja',
				'primary' => 'meja_id',
				'fields' => array(
					array('name' => 'meja_id'),
					array('name' => 'meja_nama'),
					array('name' => 'meja_kode'),
					array('name' => 'meja_created_at'),
					array('name' => 'meja_updated_at'),
					array('name' => 'meja_deleted_at'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'meja_id', 'meja_kode', 'meja_nama', 'meja_created_at',
						'meja_updated_at',
						'meja_deleted_at',
					)
				)
			)
		);
		parent::__construct($model);
	}

  public function gen_kode($value = false)
	{
		return parent::generate_kode(array(
			'pattern'       => 'MJA/{#}',
			'field'         => 'meja_kode',
			'index_format'  => '0000',
			'index_mask'    => $value
		));
	}
}


/* End of file mejaanggotaModel.php */
/* Location: ./application/modules/mejaanggota/models/mejaanggotaModel.php */