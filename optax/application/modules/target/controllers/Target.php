<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Target extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'target/targetModel' => 'target'
		));
	}

	public function index()
	{

		// print_r($this->target->select([]));
		$where['target_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'target', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->target->read(varPost()));
	}

	function select($value = '')
	{
		$where['target_deleted_at'] = null;
		$this->response($this->target->select(array('filters_static' => $where)));
	}

	function get_parent($value = '')
	{
		$where['target_deleted_at'] = null;
		$where['target_tipe'] = 'parent';
		$this->response($this->target->select(array('filters_static' => $where)));
	}

	public function store()
	{
		$data = varPost();
		$data['target_status'] = '1';
		$data['target_created_at'] = date('Y-m-d H:i:s');
		$data['target_created_by'] = $this->session->userdata('user_pegawai_id');
		$this->response($this->target->insert(gen_uuid($this->target->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$data['target_updated_at'] = date('Y-m-d H:i:s');
		$data['target_updated_by'] = $this->session->userdata('user_pegawai_id');
		$this->response($this->target->update(varPost('id', varExist($data, $this->target->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['target_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->target->update($data['id'], $data);
		$this->response($operation);
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->target->delete(varPost('id', varExist($data, $this->target->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file target.php */
/* Location: ./application/modules/target/controllers/target.php */