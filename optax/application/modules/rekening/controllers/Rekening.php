<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rekening extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'rekening/rekeningModel' => 'rekening'
		));
	}

	public function index()
	{

		// print_r($this->rekening->select([]));
		$where['rekening_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'rekening', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->rekening->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->rekening->select(array('filters_static' => varPost())));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->rekening->insert(gen_uuid($this->rekening->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->rekening->update(varPost('id', varExist($data, $this->rekening->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['rekening_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->rekening->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->rekening->delete(varPost('id', varExist($data, $this->rekening->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file rekening.php */
/* Location: ./application/modules/rekening/controllers/rekening.php */