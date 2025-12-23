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
			$this->select_dt(varPost(), 'pegawai', 'table', true, array('pegawai_is_aktif' => '1', 'pegawai_deleted_at' => null))
		);
	}

	public function read($value = '')
	{
		$this->response($this->pegawai->read(varPost()));
	}

	public function select($value = '')
	{
		$data = varPost();
		$data['pegawai_is_aktif'] = '1';
		$this->response($this->pegawai->select(array('filters_static' => $data)));
	}

	public function store()
	{
		$data = varPost();
		$data['pegawai_is_aktif'] = '1';
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

	public function import()
	{
		// upload file
		$new_name = 'pegawai_import' . date('d-m-y-H-i-s') . '.xlsx';
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
					'pegawai_id' => gen_uuid($this->pegawai->get_table()),
					'pegawai_nik' => $value[1],
					'pegawai_nama' => $value[2],
					'pegawai_alamat' => $value[3],
					'pegawai_hp' => $value[4],
					'pegawai_jk' => $value[5],
					'pegawai_is_aktif' => 1,
					'wajibpajak_id' => $this->sesion->userdata('wajibpajak_id')
				];
			}
			$this->db->insert_batch('pos_pegawai', $batch);
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

/* End of file pegawai.php */
/* Location: ./application/modules/pegawai/controllers/pegawai.php */