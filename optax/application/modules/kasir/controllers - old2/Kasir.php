<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'konfigurasi/KasirModel' => 'kasir',
			'api/KeranjangModel' 	 => 'keranjang'
		));
	}

	public function index()
	{
		$ip = $this->input->ip_address();
		// echo $ip;exit;
		$kasir = $this->kasir->read(array('kasir_ip' => $ip));
		if($kasir['kasir_id']){
			if (!$this->session->userdata('is_login')) {
				$this->load->view('login/login_form');
			}else{
				$data = array(
					'user' 	=> $this->session->userdata('user_id'),
					'kasir' => $kasir
				);
				$this->load->view('kasir', $data);
			}
		}else{
			$this->session->sess_destroy();
			$this->load->view('login/login_form', ['message' => 'Alamat IP tidak terdaftar !']);			
		}
		// }
	}

	public function select_pesanan(){
		$pesanan = $this->db
			->select('anggota_id,anggota_nama,keranjang_tgl_pesan')
			->get('v_pos_pesanan')->result_array();
		$this->response($pesanan);
	}

	public function select_daftar_pesanan($id){
		$this->response($this->db->get_where('v_pos_keranjang',['anggota_id'=>$id,'keranjang_status'=>'2'])->result_array());
	}
	public function proses_pesanan(){
		$data = varPost();

		$this->db->where(['anggota_id'=>$data['id'],'keranjang_status'=>'2'])->update('pos_keranjang',['keranjang_status' => 0]);
		pushnotif([
			'tipe' => 'pesanan',
			'judul' => 'Pesanan Siap Diambil',
			'notifikasi' => 'Pesananmu sudah siap diambil nih, buruan datang ke EKA MART ya',
			'sentto' => $data['id'],

		]);
		$this->response(['success'=>true]);
	}

	public function count_pesanan(){
		$pesanan = $this->db->select('v_pos_keranjang.anggota_id,anggota_nama,keranjang_tgl_pesan')->group_by('v_pos_keranjang.anggota_id')->order_by('keranjang_tgl_pesan','DESC')->get_where('v_pos_keranjang',['keranjang_status'=>'2']);
		$this->response($pesanan->num_rows());
	}
}