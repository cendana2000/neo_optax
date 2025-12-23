<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends Base_Controller
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

	public function select_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$data = varPost();
			$filter = trim(varPost('valSearch'));
			if ($filter != NULL) {
				$this->db->like('customer_nama', $filter);
			} else {
				$filter = "";
			}
			if ($wp_id = $this->session->userdata('wajibpajak_id')) {
				$this->db->where('wajibpajak_id', $wp_id);
			}
			$this->response($this->db->get_where("pos_customer", ['customer_deleted_at' => null])->result_array());
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
		$total = $this->db->query('SELECT count(customer_id) total FROM pos_customer WHERE customer_deleted_at IS NULL AND concat(customer_kode, customer_nama) like \'%' . $data['q'] . '%\' ' . $where . '')->result_array();

		$return = $this->db->query('SELECT customer_id as id, concat(customer_kode, \' - \', customer_nama) as text, customer_kode FROM pos_customer 
		WHERE customer_deleted_at IS NULL 
		AND concat(customer_kode, customer_nama) like \'%' . $data['q'] . '%\' ' . $where . '
		LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}


	public function store()
	{
		$data = varPost();
		$data['customer_created_at'] = date('Y-m-d H:i:s');
		$this->response($this->customer->insert(gen_uuid($this->customer->get_table()), $data));
	}

	public function store_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$data = varPost();
			unset($data['mobileDb']);
			$data['customer_id'] = gen_uuid();
			$data['customer_created_at'] = date('Y-m-d H:i:s');
			if ($wp_id = $this->session->userdata('wajibpajak_id')) {
				$data['wajibpajak_id'] = $wp_id;
			}
			if ($this->db->insert('pos_customer', $data)) {
				$res = [
					'success' => true,
					'message' => 'Berhasil menambah customer baru'
				];
			} else {
				$res = [
					'success' => false,
				];
			}
			$this->response($res);
		}
	}
	public function update_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$data = varPost();
			unset($data['mobileDb']);
			$data['customer_created_at'] = date('Y-m-d H:i:s');

			$this->db->where('customer_id', $data['customer_id']);
			$this->db->set([
				'customer_nama' => $data['customer_nama'],
				'customer_kode' => $data['customer_kode'],
			]);

			if ($this->db->update('pos_customer')) {
				$res = [
					'success' => true,
					'message' => 'Berhasil merubah data customer'
				];
			} else {
				$res = [
					'success' => false,
				];
			}
			$this->response($res);
		}
	}

	public function delete_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$data = varPost();

			if ($this->customer->checkRelasi($data) > 0) {
				$this->response([
					'success' => false,
					'message' => 'Hapus customer gagal karena data sudah terintegrasi dengan transaksi'
				]);
			} else {
				$this->db->where('customer_id', $data['id']);
				$this->db->set('customer_deleted_at', date("Y-m-d H:i:s"));
				if ($this->db->update('pos_customer')) {
					$res = [
						'success' => true,
						'message' => 'Berhasil menghapus customer'
					];
				} else {
					$res = [
						'success' => false,
						'message' => 'Terjadi kesalahan, silahkan hubungi administrator'
					];
				}
				$this->response($res);
			}
		}
	}

	function read_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {
			$this->response([
				'success' => true,
				'data' => $this->db->get_where('pos_customer', ['customer_id' => varPost('id')])->row_array()
			]);
		}
	}

	public function update()
	{
		$data = varPost();
		$data['customer_updated_at'] = date('Y-m-d H:i:s');
		$this->response($this->customer->update(varPost('id', varExist($data, $this->customer->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();

		if ($this->customer->checkRelasi($data) > 0) {
			$this->response([
				'success' => false,
				'message' => 'Hapus customer gagal karena data sudah terintegrasi dengan transaksi'
			]);
		} else {
			$data['customer_deleted_at'] = date("Y-m-d H:i:s");
			$operation = $this->customer->update($data['id'], $data);
			$this->response($operation);
		}
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->customer->delete(varPost('id', varExist($data, $this->customer->get_primary(true))));
		$this->response($operation);
	}

	public function import()
	{
		// upload file
		$new_name = 'customer_import' . date('d-m-y-H-i-s') . '.xlsx';
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
					'customer_id' => gen_uuid($this->customer->get_table()),
					'customer_kode' => $value[1],
					'customer_nama' => $value[2],
					'customer_created_at' => date('Y-m-d H:i:s'),
					'wajibpajak_id' => $this->session->userdata('wajibpajak_id')
				];
			}

			$this->db->insert_batch('pos_customer', $batch);
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

/* End of file customer.php */
/* Location: ./application/modules/customer/controllers/customer.php */