<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jenis extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'jenis/jenisModel' => 'jenis'
		));
	}

	public function index()
	{

		// print_r($this->jenis->select([]));
		$where['jenis_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'jenis', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->jenis->read(varPost()));
	}

	function select($value = '')
	{
		$where['jenis_deleted_at'] = null;
		$this->response($this->jenis->select(array('filters_static' => $where)));
	}

	function get_parent($value = '')
	{
		$where['jenis_deleted_at'] = null;
		$where['jenis_tipe'] = 'parent';
		$this->response($this->jenis->select(array('filters_static' => $where)));
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(jenis_id) total FROM pajak_jenis WHERE jenis_deleted_at IS NULL AND concat(jenis_kode, jenis_nama) like \'%' . $data['q'] . '%\'')->result_array();

		$return = $this->db->query('SELECT jenis_id as id, concat(jenis_kode, \' - \', jenis_nama) as text, jenis_kode FROM pajak_jenis WHERE jenis_deleted_at IS NULL AND concat(jenis_kode, jenis_nama) like \'%' . $data['q'] . '%\' ORDER BY jenis_kode, jenis_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function parent_select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(jenis_id) total FROM pajak_jenis WHERE jenis_deleted_at IS NULL AND jenis_tipe = \'parent\' AND concat(jenis_kode, jenis_nama) like \'%' . $data['q'] . '%\'')->result_array();

		$return = $this->db->query('SELECT jenis_id as id, concat(jenis_kode, \' - \', jenis_nama) as text, jenis_kode FROM pajak_jenis WHERE jenis_deleted_at IS NULL AND jenis_tipe = \'parent\' AND concat(jenis_kode, jenis_nama) like \'%' . $data['q'] . '%\' ORDER BY jenis_kode, jenis_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function store()
	{
		$data = varPost();
		$data['jenis_status'] = '1';
		$data['jenis_created_at'] = date('Y-m-d H:i:s');
		$data['jenis_created_by'] = $this->session->userdata('user_pegawai_id');
		$this->response($this->jenis->insert(gen_uuid($this->jenis->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$data['jenis_updated_at'] = date('Y-m-d H:i:s');
		$data['jenis_updated_by'] = $this->session->userdata('user_pegawai_id');
		$this->response($this->jenis->update(varPost('id', varExist($data, $this->jenis->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['jenis_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->jenis->update($data['id'], $data);
		$this->response($operation);
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->jenis->delete(varPost('id', varExist($data, $this->jenis->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file jenis.php */
/* Location: ./application/modules/jenis/controllers/jenis.php */