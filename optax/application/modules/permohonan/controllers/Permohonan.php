<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permohonan extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'wajibpajak/wajibpajakModel' => 'wajibpajak',
			'wajibpajak/wajibpajakModelV2' => 'wajibpajakV2'
		));
	}

	public function index()
	{
		$where['wajibpajak_deleted_at'] = null;
		$where['wajibpajak_status'] = '1';
		$this->response(
			$this->select_dt(varPost(), 'wajibpajak', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->wajibpajakV2->read(varPost()));
	}

	function select($value = '')
	{
		$this->response($this->wajibpajak->select(array('filters_static' => ['wajibpajak_deleted_at' => null])));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->wajibpajak->insert(gen_uuid($this->wajibpajak->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$operation = $this->wajibpajak->update(varPost('id', varExist($data, $this->wajibpajak->get_primary(true))), $data);

		if ($data['wajibpajak_status'] == '2') {
			$oprs = $this->db->select('menu_role_wp_menu')->get_where('pajak_menu_role_wp', [
				'menu_role_wp_wajibpajak_id' => 'default'
			])->result_array();
			$datarole = [];
			foreach ($oprs as $key => $val) {
				$itemrole = [
					"menu_role_wp_id" => gen_uuid('pajak_wajibpajak'),
					"menu_role_wp_menu" => $val['menu_role_wp_menu'],
					"menu_role_wp_wajibpajak_id" => $data['wajibpajak_id']
				];
				array_push($datarole, $itemrole);
			}
			if (count($datarole) > 0) {
				$this->db->insert_batch('pajak_menu_role_wp', $datarole);
			}
		}

		if ($operation['success'] == true) {
			$dataSendEmail = [
				'to_email'      => strtolower($data['wajibpajak_email']),
				'subject'       => 'Verifikasi Akun Wajib Pajak',
				'template'      => 'ConfirmRegister',
				'data'          => [
					'to_email'          => strtolower($data['wajibpajak_email']),
					'link'              => base_url() . 'index.php/mitralogin/EmailVerification?id=' . $operation['record']['wajibpajak_id'],
					'wajibpajak'        => $data['wajibpajak_nama'],
					'penanggungjawab'   => $data['wajibpajak_penanggungjawab'],
					'base_url' 					=> base_url(),
					'status_verifikasi' => $data['wajibpajak_status'],
					'link_revisi'				=> base_url() . 'index.php/mitra/revisi/' . base64url_encode($operation['record']['wajibpajak_email']),
					'emailfrom'         => $this->config->item('app_email'),
				]
			];
			$to         = $dataSendEmail['to_email'];
			$subject    = $dataSendEmail['subject'];
			$message    = $this->load->view($dataSendEmail['template'], ['data' => $dataSendEmail['data']], TRUE);
			$dataEmail  = [
				'message' => $message
			];
			$this->sendEmail($to, $subject, $dataEmail);
		}

		$this->response($operation);
	}

	public function delete()
	{
		$data = varPost();
		$data['wajibpajak_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->wajibpajak->update($data['id'], $data);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->wajibpajak->delete(varPost('id', varExist($data, $this->wajibpajak->get_primary(true))));
		$this->response($operation);
	}

	public function save_status(Type $var = null)
	{
		$data = varPost();
		$operation = $this->wajibpajak->update(varPost('id', varExist($data, $this->wajibpajak->get_primary(true))));
		$this->response($operation);
	}
}

/* End of file wajibpajak.php */
/* Location: ./application/modules/wajibpajak/controllers/wajibpajak.php */