<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'pegawai/PegawaiModel' => 'pegawai'
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(), 'pegawai', 'table', true, array('pegawai_is_aktif' => 1, 'pegawai_deleted_at' => null))
		);
	}

	public function read($value = '')
	{
		$this->response($this->pegawai->read(varPost()));
	}

	public function select($value = '')
	{
		$data = varPost();
		$data['pegawai_is_aktif'] = 1;
		$this->response($this->pegawai->select(array('filters_static' => $data)));
	}

	public function store()
	{
		$data = varPost();
		$data['pegawai_is_aktif'] = 1;
		$this->response($this->pegawai->insert(gen_uuid($this->pegawai->get_table()), $data));
	}

	public function update($savemode = false)
	{
		$data = varPost();
		$operation = $this->pegawai->update($data['pegawai_id'], $data);
		$this->response($operation);
	}

	public function destroy($value = '')
	{
		$data = varPost();
		$operation = $this->pegawai->update($data['id'], array('pegawai_is_aktif' => 0));
		$this->response($operation);
	}

	public function delete()
	{
		$data = varPost();
		$data['pegawai_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->pegawai->update($data['id'], $data);
		$this->response($operation);
	}
}

/* End of file pegawai.php */
/* Location: ./application/modules/pegawai/controllers/pegawai.php */