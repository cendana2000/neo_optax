<?php

use OldSound\RabbitMqBundle\RabbitMq\RpcServer;

defined('BASEPATH') or exit('No direct script access allowed');

class Preset extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'preset/presetModel' => 'preset'
		));
	}

	public function index()
	{
		$where['preset_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'preset', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$data = varPost();

		$operation = [
			'success' => true,
			'data' => [
				'parent' => $this->db->get_where('pajak_api_preset', $data)->row_array(),
				'detail' => $this->db->get_where('pajak_preset_detail_api', ['preset_detail_parent_id' => $data['preset_id']])->result_array()
			]
		];

		$this->response($operation);
	}

	function testread($value = '')
	{
		$data = varPost();
		$data['preset_id'] = 'd9be32f7e5188f421066627bacc5afc8';

		$operation = [
			'success' => true,
			'data' => [
				'parent' => $this->db->get_where('pajak_api_preset', $data)->row_array(),
				// 'detail' => $this->db->get_where('pajak_preset_detail_api', ['preset_detail_parent_id' => $data['preset_id']])->result_array()
			]
		];

		$this->response($operation);
	}

	function select($value = '')
	{
		$where['preset_deleted_at'] = null;
		$this->response($this->preset->select(array('filters_static' => $where)));
	}

	function get_parent($value = '')
	{
		$where['preset_deleted_at'] = null;
		$where['preset_tipe'] = 'parent';
		$this->response($this->preset->select(array('filters_static' => $where)));
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(preset_id) total FROM pajak_preset WHERE preset_deleted_at IS NULL AND concat(preset_kode, preset_nama) like \'%' . $data['q'] . '%\'')->result_array();

		$return = $this->db->query('SELECT preset_id as id, concat(preset_kode, \' - \', preset_nama) as text, preset_kode FROM pajak_preset WHERE preset_deleted_at IS NULL AND concat(preset_kode, preset_nama) like \'%' . $data['q'] . '%\' ORDER BY preset_kode, preset_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function parent_select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(preset_id) total FROM pajak_preset WHERE preset_deleted_at IS NULL AND preset_tipe = \'parent\' AND concat(preset_kode, preset_nama) like \'%' . $data['q'] . '%\'')->result_array();

		$return = $this->db->query('SELECT preset_id as id, concat(preset_kode, \' - \', preset_nama) as text, preset_kode FROM pajak_preset WHERE preset_deleted_at IS NULL AND preset_tipe = \'parent\' AND concat(preset_kode, preset_nama) like \'%' . $data['q'] . '%\' ORDER BY preset_kode, preset_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function store()
	{
		$data = varPost();
		$data['preset_created_at'] = date('Y-m-d H:i:s');
		$operation = $this->preset->insert(gen_uuid($this->preset->get_table()), $data, function (&$res) use ($data) {
			foreach ($data['preset_detail_left'] as $key => $value) {
				$dataSend = [
					'preset_detail_id' => gen_uuid('pajak_preset_detail_api'),
					'preset_detail_parent_id' => $res['record']['preset_id'],
					'preset_detail_left' => $value,
					'preset_detail_right' => $data['preset_detail_right'][$key],
				];
				$this->db->insert('pajak_preset_detail_api', $dataSend);
			}
		});
		$this->response($operation);
	}


	public function update()
	{
		$data = varPost();

		$data['preset_updated_at'] = date('Y-m-d H:i:s');
		$operation = $this->preset->update(varPost('id', varExist($data, $this->preset->get_primary(true))), $data, function (&$res) use ($data) {
			$this->db->delete('pajak_preset_detail_api', ['preset_detail_parent_id' => $res['record']['preset_id']]);
			foreach ($data['preset_detail_left'] as $key => $value) {
				$dataSend = [
					'preset_detail_id' => gen_uuid('pajak_preset_detail_api'),
					'preset_detail_parent_id' => $res['record']['preset_id'],
					'preset_detail_left' => $value,
					'preset_detail_right' => $data['preset_detail_right'][$key],
				];
				$this->db->insert('pajak_preset_detail_api', $dataSend);
			}
		});

		$this->response($operation);
	}

	public function delete()
	{
		$data = varPost();
		$data['preset_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->preset->update($data['id'], $data);
		$this->response($operation);
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->preset->delete(varPost('id', varExist($data, $this->preset->get_primary(true))));
		$this->response($operation);
	}
}
