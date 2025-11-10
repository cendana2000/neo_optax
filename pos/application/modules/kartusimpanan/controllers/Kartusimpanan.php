<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartusimpanan extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'kartusimpanan/KartusimpananModel' => 'kartusimpanan',
			'anggota/AnggotaModel'				=> 'anggota'
		));
	}

	public function index()
	{
		$filter = varPost('filter');
		$where['kartu_simpanan_anggota'] = $filter['anggota_id'];
		$where['kartu_simpanan_transaksi'] = $filter['jenis_transaksi'];
		$where['(kartu_simpanan_saldo_masuk >0 || kartu_simpanan_saldo_keluar >0)'] = null;
		
		if($filter['periode']==1){

		}else if($filter['periode']==2){
			$where['DATE_FORMAT(kartu_simpanan_tanggal, "%Y-%m")='] = $filter['bulan'];
		}else{
			$where['DATE_FORMAT(kartu_simpanan_tanggal, "%Y")='] = $filter['tahun'];
		}
		
		$op = $this->select_dt(varPost(),'kartusimpanan','table', true, $where);
		
		$this->response($op);
	}

	function read($value='')
	{
		$this->response($this->kartusimpanan->read(varPost()));
	}

	function cetak_kartu(){
		$data = varPost();
		if($data['periode']=="1"){

		}else if($data['periode']=="2"){
			$where['DATE_FORMAT(kartu_simpanan_tanggal, "%Y-%m")='] = $data['bulan'];
		}else{
			$where['DATE_FORMAT(kartu_simpanan_tanggal, "%Y")='] = $data['tahun'];
		}
		$where['kartu_simpanan_anggota'] = $data['anggota_id'];
		$where['kartu_simpanan_transaksi'] = $data['jenis_transaksi'];
		$where['(kartu_simpanan_saldo_masuk >0 || kartu_simpanan_saldo_keluar >0)'] = null;
		$kartu = $this->kartusimpanan->select(array('filters_static'=>$where, 'sort_static'=>'kartu_simpanan_order desc'));
		$anggota = $this->anggota->read(array('anggota_id'=>$data['anggota_id']));

		$html = '<style>
			*, table, p, li{
				line-height:1.5;
				font-size:9px;
			}
			.kop{
				text-align: center;
				display:block;
				margin:0 auto;
			}
			.kop h5{
				font-size: 9px;
			}

			.left{
				padding:2px;
			}

			.right{

				text-align:right;
				padding: 2px;
			}
			.t-center{
				vertical-align:middle!important;
				text-align:center;
				background-color : #5a8ed1;
			}
			.t-block{
				background-color : #ccc;
			}

			.divider{
				border-right: 1px solid black;
			}

			.laporan td {
				border: 1px solid black;
				border-collapse: collapse;
				padding:0px 10px;
				line-height:18px;
			}

			.ttd{
				border: 1px solid black;
				border-collapse: collapse;
				padding : 0px 3px;
				text-align:center;
				vertical-align:top;
			}

			.ttd td {
				border : 0px 1px solid black;
				border-collapse: collapse;
				padding:0px 3px;
				height:40px;
			}

			.ttd .top{
				text-align:center;
				vertical-align:top;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.ttd .bottom{
				text-align:center;
				vertical-align:bottom;
				border-right : 1px solid black;
				border-collapse: collapse;
			}

			.laporan .total {
				border-top: 1px solid black;
				border-bottom: 1px solid black;
				border-collapse: collapse;
				padding: 0px 10px;
			}	

			table{
				border-collapse: collapse;
				width:100%;
			}
			.laporan th {
				border: 1px solid black;
				border-collapse: collapse;
			}
		</style>
		<table style="width:100%;">
			<tr>
				<td class="left">
					<p>KPRI EKO KAPTI</p>
					<p><u>KANTOR KEMENAG KAB.MALANG</u></p>
				</td>
				<td class="right" ><p>'.date("d/m/Y").'</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h5>KARTU '.strtoupper($data["jenis_transaksi"]).' </h5><br>
				</td>
			</tr>
			<tr>
				<td>
					('.$anggota["anggota_kode"].") ".$anggota["anggota_nama"].'
				</td>
				<td class="right">
					('.$anggota["grup_gaji_kode"].") ".$anggota["grup_gaji_nama"].'
				</td>
			</tr>
		</table>
		<table class="laporan" cellspacing="0" style="width:100%; border-collapse: collapse; margin-top: 20px">
			<tr>
				<th class="t-center" style="width:10%;">Tgl</th>
				<th class="t-center" style="width:15%;">Sal Awal</th>
				<th class="t-center" style="width:15%;">Masuk</th>
				<th class="t-center" style="width:15%;">Keluar</th>
				<th class="t-center" style="width:15%;">Sal Akhir</th>
				<th class="t-center" style="width:15%;">No. Bukti</th>
				<th class="t-center" style="width:15%;">Ket</th>
			</tr>
		';
		foreach ($kartu['data'] as $key => $value) {
			$html.='<tr>
				<td>'.date("d-m-Y", strtotime($value['kartu_simpanan_tanggal'])).'</td>
				<td class="t-right">'.number_format($value['kartu_simpanan_saldo_awal'],0,"",".").'</td>
				<td class="t-right">'.number_format($value['kartu_simpanan_saldo_masuk'],0,"",".").'</td>
				<td class="t-right">'.number_format($value['kartu_simpanan_saldo_keluar'],0,"",".").'</td>
				<td class="t-right">'.number_format($value['kartu_simpanan_saldo_akhir'],0,"",".").'</td>
				<td>'.$value['kartu_simpanan_transaksi_kode'].'</td>
				<td>'.$value['kartu_simpanan_deskripsi'].'</td>
			</tr>';
		}
		$html.='</table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Kartu '.$data['kartu_simpanan_transaksi'],
			'title'     	=> 'Kartu '.$data['kartu_simpanan_transaksi'],
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
			'footer'        => 'Hal. {PAGENO} dari {nb}',
		));  

	}

	function select($value='')
	{
		$this->response($this->kartusimpanan->select(array('filters_static'=>varPost())));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->kartusimpanan->insert(gen_uuid($this->kartusimpanan->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->kartusimpanan->update(varPost('id', varExist($data, $this->kartusimpanan->get_primary(true))), $data));
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->kartusimpanan->delete(varPost('id', varExist($data, $this->kartusimpanan->get_primary(true))));
		$this->response($operation);
	}

}

/* End of file kartusimpanan.php */
/* Location: ./application/modules/kartusimpanan/controllers/kartusimpanan.php */