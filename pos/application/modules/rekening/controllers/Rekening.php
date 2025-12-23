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

	public function get_data()
	{
		$id = varPost('rekening_id');
		if ($id == 'Cash') {
			$data = [
				'rekening_id' => 'Cash',
				'paket' => 'Cash',
			];
		} else {
			$where = '';
			if ($wp_id = $this->session->userdata('wajibpajak_id')) {
				$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
			}
			$data = $this->db->query("SELECT rekening_id, CONCAT(rekening_bank, ' - ', rekening_no,' - ', rekening_nama) as paket FROM pos_rekening WHERE rekening_id = '$id' $where")->row_array();
		}

		$this->response($data);
	}

	function select($value = '')
	{
		$data['success'] = true;
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}
		$data['data'] = $this->db->query("SELECT rekening_id, CONCAT(rekening_bank, ' - ', rekening_no,' - ', rekening_nama) as paket FROM pos_rekening WHERE 1=1 $where")->result_array();
		array_unshift($data['data'], ['rekening_id' => 'Cash', 'paket' => 'Cash']);
		$this->response($data);
	}

	public function store()
	{
		$data = varPost();
		$data['rekening_created_at'] = date('Y-m-d H:i:s');
		$this->response($this->rekening->insert(gen_uuid($this->rekening->get_table()), $data));
	}

	public function select_ajax($value = '')
	{

		$data = varPost();

		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}
		$return = $this->db->query("SELECT rekening_id as id, CONCAT(rekening_bank, ' - ', rekening_no, ' - ', rekening_nama) as text FROM pos_rekening WHERE rekening_deleted_at IS NULL $where")->result_array();
		$this->response(array('items' => $return, 'total_count' => count($return)));
	}

	public function update()
	{
		$data = varPost();
		$data['rekening_updated_at'] = date('Y-m-d H:i:s');
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

	public function import()
	{
		// upload file
		$new_name = 'rekening_import' . date('d-m-y-H-i-s') . '.xlsx';
		$config['upload_path']  = FCPATH . 'assets/laporan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size'] = 10000;
		$config['file_name'] = $new_name;

		$this->upload->initialize($config);
		if ($this->upload->do_upload('file_import')) {

			$excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$file_import = FCPATH . 'assets/laporan/' . $new_name;
			$spreadSheet  = $excel->load($file_import);
			$dataAsAssocArray = $spreadSheet->getActiveSheet()->toArray();
			$dataDetail  = $dataAsAssocArray;
			$batch = [];

			// Insert Detail Realisasi
			unset($dataDetail[0], $dataDetail[1], $dataDetail[2], $dataDetail[3]);
			foreach ($dataDetail as $value) {
				$batch[] = [
					'rekening_id' => gen_uuid($this->rekening->get_table()),
					'rekening_nama' => $value[1],
					'rekening_no' => $value[2],
					'rekening_bank' => $value[3],
					'rekening_created_at' => date('Y-m-d H:i:s'),
					'wajibpajak_id' => $this->session->userdata('wajibpajak_id')
				];
			}
			$this->db->insert_batch('pos_rekening', $batch);
			$response = [
				'success' => true,
				'message' => 'Successfully saved data.',
			];
		} else {
			$response = [
				'success' => false,
			];
		}

		$this->response($response);
	}
}

/* End of file rekening.php */
/* Location: ./application/modules/rekening/controllers/rekening.php */