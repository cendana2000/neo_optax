<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PoolingModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_pooling_config',
				'primary' => 'pajak_pooling_config_id',
				'fields' => array(
					['name' => 'pajak_pooling_config_id'],
					['name' => 'pajak_pooling_config_tipe_connection_db'],
					['name' => 'pajak_pooling_config_hostname'],
					['name' => 'pajak_pooling_config_db_name'],
					['name' => 'pajak_pooling_config_port_db'],
					['name' => 'pajak_pooling_config_db_username'],
					['name' => 'pajak_pooling_config_db_password'],
					['name' => 'pajak_pooling_config_jadwal_count'],
					['name' => 'pajak_pooling_config_durasi'],
					['name' => 'pajak_pooling_config_kode_store'],
					['name' => 'pajak_pooling_config_tipe_pooling'],
					['name' => 'pajak_pooling_config_directory_name'],
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'pajak_pooling_config_id',
						'pajak_pooling_config_tipe_connection_db',
						'pajak_pooling_config_hostname',
						'pajak_pooling_config_db_name',
						'pajak_pooling_config_port_db',
						'pajak_pooling_config_db_username',
						'pajak_pooling_config_db_password',
						'pajak_pooling_config_jadwal_count',
						'pajak_pooling_config_durasi',
						'pajak_pooling_config_kode_store',
						'pajak_pooling_config_tipe_pooling',
						'pajak_pooling_config_directory_name',
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}
}

/* End of file PegawaiModel.php */
/* Location: ./application/modules/Pegawai/models/PegawaiModel.php */