<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'konfigurasi/KasirModel' => 'kasir',
			// 'api/KeranjangModel' 	 => 'keranjang'
		));
	}

	public function index()
	{
		if ($this->session->userdata('jenis_wp') === 'RESTO') {
			$this->load->view('Kasir2');
			// print_r($this->session->userdata());
		} else {
			$this->load->view('KasirRental');
		}

		// $ip = $this->input->ip_address();
		// $kasir = $this->kasir->read(array('kasir_ip' => $ip));


		// // print_r($ip);
		// // echo '<br>';
		// // print_r($kasir);
		// // exit;
		// if ($kasir['kasir_id']) {
		// 	// if (!$this->session->userdata('is_login')) {
		// 	// 	echo 'login dulu';
		// 	// 	exit;
		// 	// 	$this->load->view('login/login_form');
		// 	// } else {
		// 	// }
		// 	$data = array(
		// 		'user' 	=> $this->session->userdata('user_id'),
		// 		'kasir' => $kasir
		// 	);

		// 	$this->load->view('Kasir2', $data);
		// } else {
		// 	$this->session->sess_destroy();
		// 	$this->load->view('login/login_form', ['message' => 'Alamat IP tidak terdaftar !']);
		// }

		// $this->load->view('kasir/Kasir');
	}

	public function resto()
	{
		$this->load->view('Kasir2');
	}

	public function rental()
	{
		$this->load->view('KasirRental');
	}

	// public function kasirlama()
	// {
	// 	$ip = $this->input->ip_address();
	// 	$kasir = $this->kasir->read(array('kasir_ip' => $ip));


	// 	// print_r($ip);
	// 	// echo '<br>';
	// 	// print_r($kasir);
	// 	// exit;
	// 	if ($kasir['kasir_id']) {
	// 		// if (!$this->session->userdata('is_login')) {
	// 		// 	echo 'login dulu';
	// 		// 	exit;
	// 		// 	$this->load->view('login/login_form');
	// 		// } else {
	// 		// }
	// 		$data = array(
	// 			'user' 	=> $this->session->userdata('user_id'),
	// 			'kasir' => $kasir
	// 		);

	// 		$this->load->view('Kasir', $data);
	// 	} else {
	// 		$this->session->sess_destroy();
	// 		$this->load->view('login/login_form', ['message' => 'Alamat IP tidak terdaftar !']);
	// 	}

	// 	// $this->load->view('kasir/Kasir');
	// }

	public function select_pesanan()
	{
		$pesanan = $this->db
			->select('anggota_id,anggota_nama,keranjang_tgl_pesan')
			->get('v_pos_pesanan')->result_array();
		$this->response($pesanan);
	}

	public function select_daftar_pesanan($id)
	{
		$this->response($this->db->get_where('v_pos_keranjang', ['anggota_id' => $id, 'keranjang_status' => '2'])->result_array());
	}
	public function proses_pesanan()
	{
		$data = varPost();

		$this->db->where(['anggota_id' => $data['id'], 'keranjang_status' => '2'])->update('pos_keranjang', ['keranjang_status' => 0]);
		pushnotif([
			'tipe' => 'Pesanan',
			'notif_type' => 'pesanan',
			'judul' => 'Pesanan Siap Diambil',
			'notifikasi' => 'Pesananmu sudah siap diambil nih, buruan datang ke EKA MART ya',
			'sentto' => $data['id'],

		]);
		$this->response(['success' => true]);
	}

	public function count_pesanan()
	{
		$pesanan = $this->db->select('v_pos_keranjang.anggota_id,anggota_nama,keranjang_tgl_pesan')->group_by('v_pos_keranjang.anggota_id')->order_by('keranjang_tgl_pesan', 'DESC')->get_where('v_pos_keranjang', ['keranjang_status' => '2']);
		$this->response($pesanan->num_rows());
	}

	public function tprint($id)
	{
		$jual = $this->keranjang->select([
			'filters_static' => array(
				'anggota_id' => $id,
				'keranjang_status' => 2
			),
			'sort_static' => 'barang_nama asc'
		]);
		$html = '';
		$html .= '
            <style>
            @media print {
            	*{
            		font-family: "arial";
            		
            	}
                .section .print{
                    width: 6cm;

                }
                @page {
                    size: 7cm 10in portrait;
                    margin:0;
                }
            }
            .print table{
                font-size: 11px;
            }
            .text-left{
                text-align: left;
            }
            .text-right{
                text-align: right;
            }
            .print table{
                width: 100%;
            }
            </style>

            <div class="section print">';
		$html .= '<h1 style="font-size:13px;text-align:center;margin-bottom:0">POS PTPIS</h1>
			
			<hr style="border-top: 1px dashed black;">
			<h2 style="text-align:center;font-size:13px;">* DRAFT PESANAN *</h2>
            <table>
                <tbody>
                    <tr>
                    	<td align="right" >Tgl</td>
                    	<td>:</td>
                    	<td>' . date('d/m/Y', strtotime($jual['data'][0]['keranjang_tgl_pesan'])) . '</td>
                    	<td align="right">Nasabah </td>
                    	<td>:</td>
                    	<td style="text-transform:uppercase">' . $jual['data'][0]['anggota_nama'] . '</td>
                    </tr>
                </tbody>
        	</table>';
		$html .= '<table>
        	<hr style="border-top: 1px dashed;margin:0">
        	<tr>
                <td style="width:20%">Kode</td>
                <td style="width:60%">Nama Barang</td>
                <td style="width:20%">Qty</td>
            </tr>
        	';
		$total = 0;
		foreach ($jual['data'] as $value) {
			$total += $value['barang_qty'];
			$html .= '<tr>
                    <td class="text-left" style="vertical-align:top">' . substr($value['barang_kode'], 0, 13) . '</td>
                    <td class="text-left" style="word-wrap: break-word; vertical-align:top">' . $value['barang_nama'] . '</td>
                    <td class="text-left" style="vertical-align:top">' . $value['barang_qty'] . '</td>
                </tr>';
		}
		$html .= '
            <tr style="border-top:1px solid #000;">
                <td colspan="2">Total</td>
                <td>' . $total . '</td>
            </tr>';

		$html .= '
            </table>
            <hr style="border-top: 1px dashed black;">';
		$html .= '</div>';


		$this->response(array('tprint' => $html));
	}
}
