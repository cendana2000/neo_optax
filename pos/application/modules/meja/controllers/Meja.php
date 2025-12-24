<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meja extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'meja/mejaModel' => 'meja'
		));
	}

	public function index()
	{

		// print_r($this->meja->select([]));
		$where['meja_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'meja', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->meja->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->meja->select(array('filters_static' => ['meja_deleted_at' => null])));
	}

	public function select_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$filter = trim(varPost('valSearch'));
			if ($filter != NULL) {
				$this->db->like('meja_nama', $filter);
			} else {
				$filter = "";
			}

			$store_code = explode('_', varPost('mobileDb'))[1];
			$toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => $store_code])->row_array();

			if (!$toko) {
				return $this->response(array(
					'success' => true,
					'data' => [],
					'msg' => 'Data Meja Tidak Ditemukan'
				));
			}

			if ($wp_id = $toko['toko_wajibpajak_id']) {
				$this->db->where('wajibpajak_id', $wp_id);
			}

			$this->response($this->db->get_where("pos_meja", ['meja_deleted_at' => null])->result_array());
		}
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
		}
		$total = $this->db->query('SELECT count(meja_id) total FROM pos_meja WHERE meja_deleted_at IS NULL AND concat(meja_kode, meja_nama) like \'%' . $data['q'] . '%\' ' . $where . '')->result_array();

		$return = $this->db->query('SELECT meja_id as id, concat(meja_kode, \' - \', meja_nama) as text, meja_kode FROM pos_meja 
		WHERE meja_deleted_at IS NULL
		AND concat(meja_kode, meja_nama) like \'%' . $data['q'] . '%\' ' . $where . '
		LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}


	public function store()
	{
		$data = varPost();
		$data['meja_created_at'] = date('Y-m-d H:i:s');
		$data['meja_kode'] = $this->meja->gen_kode(false);
		$this->response($this->meja->insert(gen_uuid($this->meja->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$data['meja_updated_at'] = date('Y-m-d H:i:s');
		$this->response($this->meja->update(varPost('id', varExist($data, $this->meja->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();
		$data['meja_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->meja->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->meja->delete(varPost('id', varExist($data, $this->meja->get_primary(true))));
		$this->response($operation);
	}

	public function import()
	{
		// upload file
		$new_name = 'meja_import' . date('d-m-y-H-i-s') . '.xlsx';
		$config['upload_path']  = FCPATH . 'assets/laporan/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size'] = 10000;
		$config['file_name'] = $new_name;

		$this->upload->initialize($config);
		if ($this->upload->do_upload('file_import')) {

			// $excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$file_import = FCPATH . 'assets/laporan/' . $new_name;
			// $spreadSheet  = $excel->load($file_import);

			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($file_import);
			$sheet = $spreadsheet->getActiveSheet();

			$dataDetail = [];
			foreach ($sheet->getRowIterator() as $row) {
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);

				$rowData = [];
				foreach ($cellIterator as $cell) {
					$rawValue = $cell->getValue();
					$dataType = $cell->getDataType();

					if ($dataType === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) {
						$formatted = $cell->getStyle()->getNumberFormat()->getFormatCode();

						if (preg_match('/^0+$/', (string)$rawValue) || str_contains($formatted, '0')) {
							$rawValue = (string) $cell->getFormattedValue();
						} else {
							$rawValue = (string) $rawValue;
						}
					}

					$rowData[] = trim($rawValue);
				}
				$dataDetail[] = $rowData;
			}
			// $dataAsAssocArray = $spreadSheet->getActiveSheet()->toArray();
			// $dataDetail  = $dataAsAssocArray;
			$batch = [];

			// Insert Detail Realisasi
			unset($dataDetail[0], $dataDetail[1], $dataDetail[2], $dataDetail[3]);
			$no = 0;
			foreach ($dataDetail as $value) {
				$batch[] = [
					'meja_id' => gen_uuid($this->meja->get_table()),
					'meja_kode' => $this->meja->gen_kode(false) . '/' . $no++,
					'meja_nama' => $value[1],
					'meja_created_at' => date('Y-m-d H:i:s'),
					'wajibpajak_id' => $this->session->userdata('wajibpajak_id')
				];
			}
			$this->db->insert_batch('pos_meja', $batch);
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

/* End of file meja.php */
/* Location: ./application/modules/meja/controllers/meja.php */