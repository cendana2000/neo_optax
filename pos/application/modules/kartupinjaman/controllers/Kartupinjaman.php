<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kartupinjaman extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'kartupinjaman/KartupinjamanModel' => 'kartupinjaman'
		));
	}

	public function index()
	{
		$filter = varPost('filter');
		$this->response(
			$this->select_dt($filter,'kartupinjaman','table', true, array('anggota_id'=>$filter['anggota_id'], 
				'kartu_pinjaman_jenis LIKE "'.$filter['kartu_pinjaman_jenis'].'%"', 
				'kartu_pinjaman_transaksi IS NOT NULL' => null,
				'kartu_pinjaman_referensi_id' => $filter['kartu_pinjaman_referensi_id']))
		);
	}

	function read($value='')
	{
		$this->response($this->kartupinjaman->read(varPost()));
	}

	function select($value='')
	{
		$this->response($this->kartupinjaman->select(array('filters_static'=>varPost())));
	}

	public function store()
	{
		$data = varPost();
		$this->response($this->kartupinjaman->insert(gen_uuid($this->kartupinjaman->get_table()), $data));
	}


	public function update()
	{
		$data = varPost();
		$this->response($this->kartupinjaman->update(varPost('id', varExist($data, $this->kartupinjaman->get_primary(true))), $data));
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->kartupinjaman->delete(varPost('id', varExist($data, $this->kartupinjaman->get_primary(true))));
		$this->response($operation);
	}
	public function generate_laporan(){
		$data = varPost();
		$anggota = $this->db
			->select('anggota_nama,anggota_kode,grup_gaji_nama,grup_gaji_kode')
			->where(['anggota_id' => $data['anggota_id']])
			->get('v_ms_anggota')
			->row_array();
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
					<h5>KARTU PINJAMAN '.($data["kartu_pinjaman_jenis"] == "B" ? "BARANG" : "UANG").' </h5><br>
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
		<table class="laporan" cellspacing="0" style="width:100%; border-collapse: collapse;">
						<tr>
							<th class="t-center" rowspan="2" style="width:10%;">NpJ</th>
							<th class="t-center" rowspan="2" style="width:10%;">TGL</th>
							<th class="t-center" rowspan="2" style="width:5%;">ke(x)</th>
							<th class="t-center" colspan="3" style="width:45%;">Angsuran</th>
							<th class="t-center" rowspan="2" style="width:15%;">Saldo Pokok</th>
						</tr>
						<tr>
							<th class="t-center" >POKOK</th>
							<th class="t-center" >JASA</th>
							<th class="t-center" >JUMLAH</th>
						</tr>
						
						';
							/*<th class="t-center" rowspan="2" style="width:15%;">Tunggakan Jasa</th>*/
		$filter = array(
			'kartu_pinjaman_anggota'=>$data['anggota_id'], 
			'kartu_pinjaman_jenis LIKE "'.$data['kartu_pinjaman_jenis'].'%"', 
			'kartu_pinjaman_referensi_id' => $data['kartu_pinjaman_referensi_id'],
			'kartu_pinjaman_saldo_bayar > 0' => null,
		);

		$data_kartu = $this->db
			->select('pengajuan_no_pinjam,kartu_pinjaman_tanggal,kartu_pinjaman_bayar_ke,kartu_pinjaman_saldo_bayar,kartu_pinjaman_saldo_bunga,kartu_pinjaman_saldo_akhir,pengajuan_tenor,pengajuan_tgl_realisasi,pengajuan_jumlah_pinjaman,kartu_pinjaman_saldo_pinjam')
			->where($filter)
			->join('ksp_pengajuan_pinjaman','ksp_pengajuan_pinjaman.pengajuan_id=ksp_kartu_pinjaman.kartu_pinjaman_referensi_id')
			->order_by('kartu_pinjaman_bayar_ke','ASC')
			->get('ksp_kartu_pinjaman')
			->result_array();
			
		if(sizeof($data_kartu)>0){
			$html .= '
				<tr>
					<td>'.$data_kartu[0]["pengajuan_no_pinjam"].'</td>
					<td>'.date("d/m/Y",strtotime($data_kartu[0]["pengajuan_tgl_realisasi"])).'</td>
					<td>'.$data_kartu[0]["pengajuan_tenor"].'x</td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right"></td>
					<td align="right">'.number_format($data_kartu[0]["pengajuan_jumlah_pinjaman"]).'</td>
				</tr>
			';
			$saldo_bayar = $saldo_bunga = $total = 0;
			foreach ($data_kartu as $value) {
				if(empty($value['kartu_pinjaman_saldo_pinjam'])){
					$saldo_bayar += $value["kartu_pinjaman_saldo_bayar"];
					$saldo_bunga += $value["kartu_pinjaman_saldo_bunga"];
					$total += (int)$value["kartu_pinjaman_saldo_bayar"]+$value["kartu_pinjaman_saldo_bunga"];
					$html .= '
						<tr>
							<td></td>
							<td>'.date("d/m/Y",strtotime($value["kartu_pinjaman_tanggal"])).'</td>
							<td>'.$value["kartu_pinjaman_bayar_ke"].'x</td>
							<td align="right">'.number_format($value["kartu_pinjaman_saldo_bayar"]).'</td>
							<td align="right">'.number_format($value["kartu_pinjaman_saldo_bunga"]).'</td>
							<td align="right">'.number_format((int)$value["kartu_pinjaman_saldo_bayar"]+$value["kartu_pinjaman_saldo_bunga"]).'</td>
							<td align="right">'.number_format($value["kartu_pinjaman_saldo_akhir"]).'</td>
							
						</tr>
					';
					
				}
			}
			$index = (int)sizeof($data_kartu) - 1;
			$html .= '
					<tr>
						<td colspan="3">Total</td>
						<td align="right">'.number_format($saldo_bayar).'</td>
						<td align="right">'.number_format($saldo_bunga).'</td>
						<td align="right">'.number_format($total).'</td>
						<td align="right">'.number_format($data_kartu[$index]["kartu_pinjaman_saldo_akhir"]).'</td>
						
					</tr>
				';
		}else{
			$html .= '
					<tr>
						<td colspan="7" style="text-align:center;">Data Tidak Ada!!</td>
					</tr>
				';
		}

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Kartu Pinjaman '.($data["kartu_pinjaman_jenis"] == "B" ? "Barang" : "Uang"),
			'title'         => 'Kartu Pinjaman '.($data["kartu_pinjaman_jenis"] == "B" ? "Barang" : "Uang"),
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
			'footer'        => 'Hal. {PAGENO} dari {nb}',
		));  
	}


}

/* End of file kartupinjaman.php */
/* Location: ./application/modules/kartupinjaman/controllers/kartupinjaman.php */
