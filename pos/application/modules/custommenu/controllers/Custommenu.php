<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custommenu extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'custommenu/CustomMenuModel' => 'custommenu',
			'custommenu/CustomMenuBarangModel' => 'custommenubarang'
		));
	}

	public function index()
	{
		$where['custom_menu_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'custommenu', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->custommenu->read(varPost()));
	}

	function select($value = '')
	{
		$where['custom_menu_deleted_at'] = null;
		$this->response($this->custommenu->select(array('filters_static' => $where)));
	}

	function select_kasir($value = '')
	{
		$data = varPost();

		if (array_key_exists('barang_id', $data)) {
			$where['barang_id'] = $data['barang_id'];
		}

		$where['custom_menu_deleted_at'] = null;
		$this->response($this->custommenubarang->select(array('filters_static' => $where)));
	}

	function select_barang($value = '')
	{
		$data = varPost();
		$where['custom_menu_deleted_at'] = null;
		$operation = $this->custommenu->select(array('filters_static' => $where));
		$this->db->select('custom_menu_id');
		$operation['data_filter'] = $this->db->get_where('v_pos_barang_custom', ['barang_id' => $data['barang_id']])->result_array();

		$this->response(
			$operation
		);
	}

	public function select_mobile()
	{
		if (array_key_exists('mobileDb', varPost())) {

			$data = varPost();

			if ($wp_id = $this->session->userdata('wajibpajak_id')) {
				$this->db->where('wajibpajak_id', $wp_id);
			}
			$this->response($this->db->get_where("v_pos_barang_custom", ['custom_menu_deleted_at' => null, 'barang_id' => $data['barang_id']])->result_array());
		}
	}


	public function store()
	{
		$data = varPost();

		$data['custom_menu_created_at'] = date('Y-m-d h:i:s');
		$this->response($this->custommenu->insert(gen_uuid($this->custommenu->get_table()), $data));
	}

	public function update()
	{
		$data = varPost();
		$data['custom_menu_updated_at'] = date('Y-m-d h:i:s');
		$this->response($this->custommenu->update(varPost('id', varExist($data, $this->custommenu->get_primary(true))), $data));
	}

	public function delete()
	{
		$data = varPost();

		$data['custom_menu_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->custommenu->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->custommenu->delete(varPost('id', varExist($data, $this->custommenu->get_primary(true))));
		$this->response($operation);
	}

	public function import()
	{
		// upload file
		$new_name = 'custom_menu_import' . date('d-m-y-H-i-s') . '.xlsx';
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
				if (!$value[1] == null && !$value['2'] == null) {
					$batch[] = [
						'custom_menu_id' => gen_uuid($this->custommenu->get_table()),
						'custom_menu_nama' => $value[1],
						'custom_menu_harga' => $value[2],
						'custom_menu_created_at' => date('Y-m-d H:i:s'),
						'wajibpajak_id' => $this->session->userdata('wajibpajak_id')
					];
				}
			}

			$this->db->insert_batch('pos_custom_menu', $batch);
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

/* End of file custommenu.php */
/* Location: ./application/modules/custommenu/controllers/custommenu.php */