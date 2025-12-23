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

	public function select_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$data = varPost();
			$filter = trim(varPost('valSearch'));
			if ($filter != NULL) {
				$this->db->like('satuan_nama', $filter);
			} else {
				$filter = "";
			}
			if ($wp_id = $this->session->userdata('wajibpajak_id')) {
				$this->db->where('wajibpajak_id', $wp_id);
			}
			$this->response($this->db->get_where("pos_satuan", ['satuan_deleted_at' => null])->result_array());
		}
	}


	public function store()
	{
		$data = varPost();


		$data['satuan_aktif'] = 1;
		$data['satuan_created_at'] = date('Y-m-d h:i:s');
		$this->response($this->satuan->insert(gen_uuid($this->satuan->get_table()), $data));
	}

	public function update()
	{
		$data = varPost();
		$data['satuan_updated_at'] = date('Y-m-d h:i:s');
		$this->response($this->satuan->update(varPost('id', varExist($data, $this->satuan->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();

		if ($this->satuan->checkRelasi($data) > 0) {
			$this->response([
				'success' => false,
				'message' => 'Hapus satuan gagal karena data sudah terintegrasi dengan transaksi'
			]);
		} else {
			$data['satuan_deleted_at'] = date("Y-m-d H:i:s");
			$operation = $this->satuan->update($data['id'], $data);
			$this->response($operation);
		}
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->satuan->delete(varPost('id', varExist($data, $this->satuan->get_primary(true))));
		$this->response($operation);
	}

	public function import()
	{
		// upload file
		$new_name = 'satuan_import' . date('d-m-y-H-i-s') . '.xlsx';
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
					'satuan_id' => gen_uuid($this->satuan->get_table()),
					'satuan_kode' => $value[1],
					'satuan_nama' => $value[2],
					'satuan_aktif' => 1,
					'satuan_created_at' => date('Y-m-d H:i:s'),
					'wajibpajak_id' => $this->session->userdata('wajibpajak_id')
				];
			}
			$this->db->insert_batch('pos_satuan', $batch);
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

/* End of file satuan.php */
/* Location: ./application/modules/satuan/controllers/satuan.php */