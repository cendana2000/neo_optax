<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Satuan extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'satuan/satuanModel' => 'satuan'
		));
	}

	public function index()
	{

		// print_r($this->satuan->select([]));
		$where['satuan_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'satuan', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->satuan->read(varPost()));
	}

	function select($value = '')
	{
		$where['satuan_deleted_at'] = null;
		$this->response($this->satuan->select(array('filters_static' => $where)));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->satuan->insert(gen_uuid($this->satuan->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->satuan->update(varPost('id', varExist($data, $this->satuan->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['satuan_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->satuan->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->satuan->delete(varPost('id', varExist($data, $this->satuan->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file satuan.php */
/* Location: ./application/modules/satuan/controllers/satuan.php */