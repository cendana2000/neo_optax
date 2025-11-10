<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanlaris extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'kategori/KategoriModel'				=> 'kategori',
			'transaksipenjualan/TransaksipenjualanModel' 		=> 'transaksipenjualan',
			'transaksipenjualan/TransaksipenjualandetailModel' 	=> 'transaksipenjualandetail',
		));
	}

	public function get_laporan()
	{
		$data = varPost();
		// $data = [
		// 	"bulan" => "2023-01",
		// 	"barang_kategori_barang" => "70d94c3907bd9e63dd930c26883df0da"
		// ];
		$bulan = explode('-', $data['bulan']);
		$tanggal = date('d/m/Y', strtotime(varPost('tanggal')));
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.5;
				font-size:11px;
			}
			.kop{
				text-align: center;
				display:block;
				margin:0 auto;
			}
			.kop h1{
				font-size: 10px;
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

			.divider{
				border-right: 1px solid black;
			}

			.laporan td {
				border: 1px solid black;
				border-collapse: collapse;
				padding:0px 10px;
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
			.noborder{
				border:none;
			}
		</style>';
		$dtCaption = '';
		$bln = explode('-', $data['bulan']);
		$bln[1] = intval($bln[1]);
		$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>' . $this->session->userdata('toko_nama') . '</p>
					<p><u>ALAMAT</u></p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
					<h4> LAPORAN BARANG LARIS</h4><br>
					<h5> ' . $bulan[$bln[1]] . ' ' . $bln[0] . ' </h5>
				</td>
			</tr>
			<tr>
				<td>' . $dtCaption . '</td>
				<td class="right">Hal. : ' . $hal . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">NO</th>
				<th class="t-center">KODE</th>
				<th class="t-center">BARANG</th>
				<th class="t-center">KELOMPOK BARANG</th>
				<th class="t-center">HARGA</th>
				<th class="t-center">ITEM PENJUALAN</th>
				<th class="t-center">KETERANGAN</th>
			</tr>';

		$_where = 'to_date(cast(penjualan_detail_tanggal as TEXT), \'YYYY-MM\') = to_date(cast(\'' . $data['bulan'] . '\' as TEXT), \'YYYY-MM\')';
		$where = 'WHERE ' . $_where;
		if ($data['barang_kategori_barang']) {
			$where .= ' AND barang_kategori_barang = \'' . $data['barang_kategori_barang'] . '\'';
			$where .= ' OR ';
			$where .= $_where . ' AND barang_kategori_parent = \'' . $data['barang_kategori_barang'] . '\'';
		}
		// print_r('<pre>');print_r($where);print_r('</pre>');exit;
		$stok = $this->db->query('SELECT barang_kode, barang_nama, kategori_barang_nama, 
		barang_harga, SUM(penjualan_detail_qty_barang) total 
		FROM pos_barang 
		LEFT JOIN pos_kategori ON barang_kategori_barang = kategori_barang_id 
		LEFT JOIN pos_penjualan_detail ON penjualan_detail_barang_id = barang_id ' . $where . ' 
		GROUP BY barang_kode, barang_nama, kategori_barang_nama, barang_harga,barang_id 
		ORDER BY total ASC 
		LIMIT 100')->result_array();
		$n = 1;
		foreach ($stok as $key => $value) {
			$html .= '<tr>
				<td>' . $n . '</td>
				<td>' . $value['barang_kode'] . '</td>
				<td>' . $value['barang_nama'] . '</td>
				<td>' . $value['kategori_barang_nama'] . '</td>
				<td style="text-align: right;">' . number_format($value['barang_harga'], 0, ',', '.') . '</td>
				<td>' . number_format($value['total'], 0, ',', '.') . '</td>
				<td></td>
			</tr>';
			$n++;
		}

		$html .= '</table><div style="page-break-after: always"></div>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Laporan Barang Laris',
			'title'         => 'Laporan Barang Laris',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}
}
