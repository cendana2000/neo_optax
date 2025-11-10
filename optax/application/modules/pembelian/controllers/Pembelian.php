<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'pembelian/pembelianModel' => 'pembelian'
		));
	}

	public function index()
	{

		// print_r($this->pembelian->select([]));
		$this->response(
			$this->select_dt(varPost(), 'pembelian', 'table')
		);
	}

	function read($value = '')
	{
		$this->response($this->pembelian->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->pembelian->select(array('filters_static' => varPost())));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->pembelian->insert(gen_uuid($this->pembelian->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->pembelian->update(varPost('id', varExist($data, $this->pembelian->get_primary(true))), $data));
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->pembelian->delete(varPost('id', varExist($data, $this->pembelian->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file pembelian.php */
/* Location: ./application/modules/pembelian/controllers/pembelian.php */