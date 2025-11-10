<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WpOuterModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'wp_outer',
				'primary' => 'sales_id',
				'fields' => array(
					array('name' => 'sales_id'),
					array('name' => 'sales_code'),
					array('name' => 'sales_total_item'),
					array('name' => 'sales_total_qty'),
					array('name' => 'sales_sub_total'),
					array('name' => 'sales_service'),
					array('name' => 'sales_tax'),
					array('name' => 'sales_grand_total'),
					array('name' => 'sales_customer_name'),
					array('name' => 'sales_cashier_name')
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array(
						'sales_id', 
						'sales_code', 
						'sales_total_item',
						'sales_total_qty',
						'sales_sub_total',
						'sales_service',
						'sales_tax',
						'sales_grand_total',
						'sales_customer_name',
						'sales_cashier_name'
					)
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	function generateRandomString($length = 32) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */