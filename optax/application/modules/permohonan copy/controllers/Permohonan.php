<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permohonan extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'customer/customerModel' => 'customer'
		));
	}

	public function index()
	{

		// print_r($this->customer->select([]));
		$where['customer_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'customer', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->customer->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->customer->select(array('filters_static' => ['customer_deleted_at' => null])));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->customer->insert(gen_uuid($this->customer->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->customer->update(varPost('id', varExist($data, $this->customer->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['customer_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->customer->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->customer->delete(varPost('id', varExist($data, $this->customer->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file customer.php */
/* Location: ./application/modules/customer/controllers/customer.php */