<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KasirModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_kasir',
				'primary' => 'kasir_id',
				'fields' => array(
					array('name' => 'kasir_id'),
					array('name' => 'kasir_kode'),
					array('name' => 'kasir_nama'),
					array('name' => 'kasir_ip'),
					array('name' => 'kasir_created'),
					array('name' => 'kasir_user_id'),
					array('name' => 'kasir_user_nama'),
				)
			),
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file KasirModel.php */
/* Location: ./application/modules/penjualan/models/KonfigurasiModel.php */