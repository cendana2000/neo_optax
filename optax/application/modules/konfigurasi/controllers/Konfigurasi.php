<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfigurasi extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'konfigurasiModel' => 'konfigurasi',
			'konfigurasi/KasirModel' => 'kasir',
		));
	}

	public function index()
	{
		$konf = $this->db->query('SELECT * FROM sys_conf')->result_array();
		$kasir = $this->db->query('SELECT * FROM sys_kasir order by kasir_created')->result_array();
		if($konf[0]){
			$konf[0]['kasir'] = $kasir;
		}
		$this->response(
			($konf[0]?$konf[0]:array('success' => false))
		);
	}

	public function store()
	{
		$data = varPost();
		$data['konfigurasi_updated'] = date('Y-m-d H:i:s');
		$data['konfigurasi_user'] = $this->session->userdata('user_id');
		$data['konfigurasi_user_nama'] = $this->session->userdata('user_alias');
		$operation = $this->konfigurasi->insert(gen_uuid($this->konfigurasi->get_table()), $data);
		if($operation['success']){
			foreach ($data['kasir_ip'] as $key => $value) {
				if($value){
					$this->kasir->insert(gen_uuid($this->kasir->get_table()), array(
						'kasir_ip' 		=> $value,
						'kasir_nama' 	=> $data['kasir_nama'][$key],
						'kasir_kode' 	=> $data['kasir_kode'][$key],
						'kasir_created' => date('Y-m-d'),
						'kasir_user_id' => $this->session->userdata('user_id'),
						'kasir_user_nama' => $this->session->userdata('user_alias'),
					));
				}
			}
		}
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		// print_r($data);exit;
		
		/*$data['konfigurasi_updated'] = date('Y-m-d H:i:s');
		$data['konfigurasi_user'] = $this->session->userdata('user_id');
		$data['konfigurasi_user_nama'] = $this->session->userdata('user_alias');*/
		$operation = $this->konfigurasi->update(varPost('id', varExist($data, $this->konfigurasi->get_primary(true))), array(
			'konfigurasi_register_kode'		=> $data['konfigurasi_register_kode'],
			'konfigurasi_jasa_pinjaman'		=> $data['konfigurasi_jasa_pinjaman'],
			// 'konfigurasi_gudang_id' 		=> $data['konfigurasi_gudang_id'],
			'konfigurasi_perusahaan_nama' 	=> $data['konfigurasi_perusahaan_nama'],
			'konfigurasi_perusahaan_alamat' => $data['konfigurasi_perusahaan_nama'],
			'konfigurasi_perusahaan_telp' 	=> $data['konfigurasi_perusahaan_telp'],
			'konfigurasi_jasa_msk' 			=> $data['konfigurasi_jasa_msk'],
			'konfigurasi_jasa_tht' 			=> $data['konfigurasi_jasa_tht'],
			'konfigurasi_jasa_swk' 			=> $data['konfigurasi_jasa_swk'],
			'konfigurasi_jasa_simp_khusus' 	=> $data['konfigurasi_jasa_simp_khusus'],
			'konfigurasi_updated'			=> date('Y-m-d H:i:s'),
			'konfigurasi_user'				=> $this->session->userdata('user_id'),
			'konfigurasi_user_nama'			=> $this->session->userdata('user_alias'),
			'konfigurasi_jml_talangan'		=> $data['konfigurasi_jml_talangan'],
			'konfigurasi_jml_tenor'			=> $data['konfigurasi_jml_tenor'],
			'konfigurasi_jml_porsi'			=> $data['konfigurasi_jml_porsi']
			));
		$id = [];
		if($operation['success']){
			foreach ($data['kasir_ip'] as $key => $value) {
				if($value){
					if($data['kasir_id'][$key]){
						$this->kasir->update(gen_uuid($this->kasir->get_table()), array(
							'kasir_ip' 		=> $value,
							'kasir_nama' 	=> $data['kasir_nama'][$key],
							'kasir_kode' 	=> $data['kasir_kode'][$key],
							'kasir_created' => date('Y-m-d'),
							'kasir_user_id' => $this->session->userdata('user_id'),
							'kasir_user_nama' => $this->session->userdata('user_alias'),
						));
						$id[] = $data['kasir_id'][$key];
					}else{
						$kasir = $this->kasir->insert(gen_uuid($this->kasir->get_table()), array(
							'kasir_ip' 		=> $value,
							'kasir_nama' 	=> $data['kasir_nama'][$key],
							'kasir_kode' 	=> $data['kasir_kode'][$key],
							'kasir_created' => date('Y-m-d'),
							'kasir_user_id' => $this->session->userdata('user_id'),
							'kasir_user_nama' => $this->session->userdata('user_alias'),
						));
						$id[] = $kasir['record']['kasir_id'];
					}
				}
			}
			$id = implode('","', $id);
			$this->kasir->delete(array('kasir_id not in ("'.$id.'")'=>null));
		}
		$this->response($operation);
	}

}

/* End of file Konfigurasi.php */
/* Location: ./application/modules/Konfigurasi/controllers/Konfigurasi.php */