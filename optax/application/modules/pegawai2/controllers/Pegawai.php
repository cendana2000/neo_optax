<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'pegawai/pegawaiModel' => 'kasir'
		));
	}

	public function index()
	{

		// print_r($this->kasir->select([]));
		$where['kasir_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'kasir', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->kasir->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->kasir->select(array('filters_static' => varPost())));
	}

	public function store()
	{
		$data = varPost();
		print_r($data);
		exit;
		$this->response($this->kasir->insert(gen_uuid($this->kasir->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->kasir->update(varPost('id', varExist($data, $this->kasir->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['kasir_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->kasir->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->kasir->delete(varPost('id', varExist($data, $this->kasir->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file kasir.php */
/* Location: ./application/modules/kasir/controllers/kasir.php */