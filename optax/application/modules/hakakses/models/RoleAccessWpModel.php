<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RoleAccessWpModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pajak_menu_role_wp',
				'primary' => 'menu_role_wp_id',
				'fields' => array(
					array('name'=>'menu_role_wp_id', 'unique' => true),
					array('name'=>'menu_role_wp_menu'),
					array('name'=>'menu_role_wp_wajibpajak_id'),
					array('name'=>'menu_kode', 'view'=>true),
					array('name'=>'menu_title', 'view'=>true),
					array('name'=>'menu_order', 'view'=>true),
					array('name'=>'menu_parent', 'view'=>true),
					array('name'=>'menu_link', 'view'=>true),
					array('name'=>'menu_isaktif', 'view'=>true),
					array('name'=>'menu_level', 'view'=>true),
					array('name'=>'menu_icon', 'view'=>true),
					array('name'=>'menu_hassub', 'view'=>true),
					array('name'=>'menu_main', 'view'=>true),
					array('name'=>'menu_description', 'view'=>true),
					array('name'=>'wajibpajak_id', 'view'=>true),
				)
			),
			'view' => array(
				'name' => 'v_pajak_menu_role_wp',
				'mode' => array(
					'datatable' => array(
						'menu_role_wp_id',
						'menu_role_wp_menu',
						'menu_role_wp_wajibpajak_id',
						'menu_kode',
						'menu_title',
						'menu_order',
						'menu_parent',
						'menu_link',
						'menu_isaktif',
						'menu_level',
						'menu_icon',
						'menu_hassub',
						'menu_main',
						'menu_description',
						'wajibpajak_id',
					)
				)
			)
		);
		parent::__construct($model);		
	}
}