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

	public function select_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$this->db = $this->load->database(multidb_connect(varPost('mobileDb')), true);
			$data = varPost();
			$filter = trim(varPost('valSearch'));
			if ($filter != NULL) {
				$this->db->like('jenis_nama', $filter);
			} else {
				$filter = "";
			}
			$this->response($this->db->get_where("pos_jenis", ['jenis_deleted_at' => null])->result_array());
		}
	}

	public function store()
	{
		$data = varPost();
		$data['jenis_created_at'] = date('Y-m-d H:i:s');
		$this->response($this->jenis->insert(gen_uuid($this->jenis->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$data['jenis_updated_at'] = date('Y-m-d H:i:s');
		$this->response($this->jenis->update(varPost('id', varExist($data, $this->jenis->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();

		if ($this->jenis->checkRelasi($data) > 0) {
			$this->response([
				'success' => false,
				'message' => 'Hapus jenis gagal karena data sudah terintegrasi dengan transaksi'
			]);
		} else {
			$data['jenis_deleted_at'] = date("Y-m-d H:i:s");
			$operation = $this->jenis->update($data['id'], $data);
			$this->response($operation);
		}
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->jenis->delete(varPost('id', varExist($data, $this->jenis->get_primary(true))));
		$this->response($operation);
	}

	public function import()
	{
		// upload file
		$new_name = 'jenis_import' . date('d-m-y-H-i-s') . '.xlsx';
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
					'jenis_id' => gen_uuid($this->jenis->get_table()),
					'jenis_nama' => $value[1],
					'jenis_deskripsi' => $value[2],
					'jenis_include_stok' => $value[3],
					'jenis_created_at' => date('Y-m-d H:i:s'),
				];
			}
			$this->db->insert_batch('pos_jenis', $batch);
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

/* End of file jenis.php */
/* Location: ./application/modules/jenis/controllers/jenis.php */