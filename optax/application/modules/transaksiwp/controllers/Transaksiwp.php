<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksiwp extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'barang/BarangModel' 				 			=> 'barang',
			'barang/BarangsatuanModel'			 	=> 'barangsatuan',
			'barang/BarangbarcodeModel'			 	=> 'barangbarcode',
			'kategori/KategoriModel' 			 		=> 'kelompokbarang',
			'transaksiwp/TransaksiwpModel' 		=> 'transaksiwp',
			'transaksiwp/TransaksiwpPosModel' => 'transaksiwppos',
			'wajibpajak/WajibpajakModel' => 'wajibpajak',

		));
	}

	public function index()
	{
		$codestore = varPost('log_penjualan_code_store');
		$periode = varPost('periode');
		$periodearr = explode(' - ', $periode);
		$enddate = date('Y-m-d');
		$startdate = date('Y-m-d');
		if (is_array($periodearr) && count($periodearr) > 1) {
			$startdate = date('Y-m-d 00:00:00', strtotime($periodearr[0]));
			$enddate = date('Y-m-d 23:59:59', strtotime($periodearr[1]));
		}

		// $where['log_penjualan_code_store'] = $codestore;
		$where['penjualan_tanggal >= \'' . $startdate . '\' AND penjualan_tanggal <= \'' . $enddate . '\''] = null;
		$where['penjualan_deleted_at IS NULL'] = null;
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where['EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_penjualan.wajibpajak_id AND pemda_id=' . $pemda_id . ')'] = null;
		}
		$opr = $this->select_dt(varPost(), 'transaksiwppos', 'table', false, $where, null, null);
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_penjualan.wajibpajak_id AND pemda_id=' . $pemda_id . ')', NULL, FALSE);
		}
		$get_total = $this->db
			->select("sum(penjualan_total_grand) as total_nominal_penjualan")
			->where($where)
			->get('pos_penjualan')
			->row();
		$opr['sumtotal'] = $get_total;

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('pemda_id', $pemda_id);
		}
		$wp = $this->db
			->select('wajibpajak_nama_penanggungjawab, 
		wajibpajak_npwpd, 
		toko_kode,
		toko_nama')
			->get_where('v_pajak_toko', [
				'toko_kode' => $codestore,
			])->row();
		$opr['wajibpajak'] = $wp;
		$this->response(
			$opr
		);
	}

	// backup index untuk menu baru
	// public function index()
	// {
	// 	$data = varPost();
	// 	$periode = varPost('periode');
	// 	$periodearr = explode(' - ', $periode);
	// 	$enddate = date('Y-m-d');
	// 	$startdate = date('Y-m-d');
	// 	if (is_array($periodearr) && count($periodearr) > 1) {
	// 		$startdate = date('Y-m-d', strtotime($periodearr[0]));
	// 		$enddate = date('Y-m-d', strtotime($periodearr[1]));
	// 	}
	// 	$where['log_penjualan_wp_penjualan_tanggal >= \'' . $startdate . '\' AND log_penjualan_wp_penjualan_tanggal <= \'' . $enddate . '\''] = null;
	// 	$opr = $this->select_dt(varPost(), 'transaksiwp', 'table', true, $where);
	// 	$get_total = $this->db
	// 		->select("sum(log_penjualan_wp_total) as total_nominal_penjualan")
	// 		->where($where)
	// 		->get('log_penjualan_wp')
	// 		->row();
	// 	$opr['sumtotal'] = $get_total;
	// 	$wp = $this->db
	// 		->select('wajibpajak_nama_penanggungjawab, 
	// 				toko_wajibpajak_npwpd, 
	// 				log_penjualan_code_store,
	// 				toko_nama')
	// 		->where($where)
	// 		->get('v_pajak_penjualan_wp')
	// 		->row();
	// 	$opr['wajibpajak'] = $wp;
	// 	// print_r($opr);
	// 	// die;
	// 	$this->response(
	// 		$opr
	// 	);
	// }



	public function readWp()
	{
		$data = varPost();
		$ops = $this->wajibpajak->read(['wajibpajak_npwpd' => $data['wp_npwpd']]);
		$this->response($ops);
	}

	public function select_wp($value = '')
	{
		$data = varPost();
		$where = ' AND toko_status = \'2\'';
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where .= 'AND pajak_toko.pemda_id=' . $this->db->escape($pemda_id);
		}
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$total = $this->db->query('SELECT count(toko_id) total FROM pajak_toko 
		WHERE LOWER(concat(toko_kode, toko_nama))::text like \'%' . strtolower($data['q']) . '%\' ' . $where)->result_array();

		$where = ' AND toko_status = \'2\'';
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$where .= ' AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=v_pajak_toko.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ') ';
		}
		$return = $this->db->query('SELECT toko_kode as id, concat(toko_kode, \' - \', toko_nama) as text FROM v_pajak_toko 
		WHERE LOWER(concat(toko_kode, toko_nama))::text like \'%' . strtolower($data['q']) . '%\' ' . $where . ' LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}


	public function select_ajax($value = '')
	{
		$data = varPost();
		if (!empty($value)) {

			$where = ($data['fdata']['barang_kategori_barang']) ? 'AND barang_kategori_barang = \'' . $data['fdata']['barang_kategori_barang'] . '\'' : '';
			$where .= ' AND barang_deleted_at is null';
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where .= " AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_barang.wajibpajak_id pemda_id=" . $this->db->escape($pemda_id) . ") ";
			}
			// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
			$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
			$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE concat(barang_kode, barang_nama) like \'%' . $data['q'] . '%\' ' . $where)->result_array();

			$where = ($data['fdata']['barang_kategori_barang']) ? 'AND barang_kategori_barang = \'' . $data['fdata']['barang_kategori_barang'] . '\'' : '';
			$where .= ' AND barang_deleted_at is null';
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where .= " AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=v_pos_barang.wajibpajak_id pemda_id=" . $this->db->escape($pemda_id) . ") ";
			}
			$return = $this->db->query('SELECT barang_id as id, concat(barang_kode, \' - \', barang_nama) as text 
      FROM v_pos_barang 
      WHERE concat(barang_kode, barang_nama) like \'%' . $data['q'] . '%\' ' . $where . ' 
      LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
			$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
		} else {
			$this->response(array('items' => [], 'total_count' => '0'));
		}
	}

	public function go_tree($value = '')
	{
		if (!empty($value)) {
			$where = '';
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where = " AND EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=pos_kategori.wajibpajak_id AND pemda_id=" . $this->db->escape($pemda_id) . ") ";
			}
			$kelompokbarang = $this->db->query('SELECT *
      FROM
        pos_kategori
      WHERE
        kategori_barang_aktif = \'1\' ' . $where . '
      ORDER BY
        kategori_barang_nama asc')->result_array();
			$opr = $this->buildTree($kelompokbarang);
			$operation = array(
				'success'   => true,
				'data'      => $opr
			);
			$this->response($operation);
		} else {
			$this->response(array(
				'success' => false,
				'data' => []
			));
		}
	}

	function buildTree(array $elements, $parentId = '#')
	{
		$branch = array();
		foreach ($elements as $element) {
			if ($element['kategori_barang_parent'] == $parentId) {
				$children = $this->buildTree($elements, $element['kategori_barang_id']);
				$element_new = array(
					'id'        => $element['kategori_barang_id'],
					'parent'    => $element['kategori_barang_parent'],
					'text'      => '->' . $element['kategori_barang_nama'],
					'tipe'      => $element['kategori_barang_tipe']
				);
				if ($children) {
					$element_new['children'] = true;
					$element_new['child'] = $children;
				}
				$branch[] = $element_new;
			}
		}
		return $branch;
	}

	public function tprint($value = '')
	{
		$dataToko = $this->db->get_where('pajak_toko', ['toko_kode' => $value])->row_array();
		if (!empty($value)) {
			$data = varPost();
			$tanggal = date('d/m/Y');
			$hal = 1;
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
      </style>';
			$dtCaption = '';
			$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
			$html .= '<table style="width:100%;">
        <tr>
          <td class="kop">
            <p>' . $this->session->userdata('toko_nama') . '</p>
            <p><h5>DAFTAR HARGA BARANG</h5></p>
            <p><h5>' . $dataToko['toko_nama'] . '</h5></p>
          </td>
        </tr>
        <tr>
          <td class="kop">
            <p>Tgl Cetak ' . $tanggal . ' </p>
          </td>
        </tr>
      </table>
      <br>
      <table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
        <tr>
          <th class="t-center">No</th>
          <th class="t-center">Kode</th>
          <th class="t-center">Barang</th>
          <th class="t-center">Kelompok Barang</th>
          <th class="t-center">Sat.1</th>
          <th class="t-center">Harga 1</th>
          <th class="t-center">Sat.2</th>
          <th class="t-center">Harga 2</th>
          <th class="t-center">Sat.3</th>
          <th class="t-center">Harga 3</th>
          <th class="t-center">Keterangan</th>
        </tr>';
			$where = [];
			if ($data['barang_kategori_barang']) {
				$where[] = 'barang_kategori_barang = \'' . $data['barang_kategori_barang'] . '\' OR barang_kategori_parent = \'' . $data['barang_kategori_barang'] . '\' ';
			}
			if ($data['barang_id']) $where[] = 'barang_id = \'' . $data['barang_id'] . '\'';
			$where[] = 'barang_deleted_at is null';
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$where[] = 'pemda_id = ' . $this->db->escape($pemda_id);
			}
			$where = (count($where) > 0) ? 'where ' . implode(' AND ', $where) : '';
			$stok = $this->db->query('SELECT barang_kode, barang_nama, kategori_barang_nama, barang_satuan_kode, 
                    barang_harga, barang_satuan_opt_kode, barang_harga_opt, barang_satuan_opt2_kode, barang_harga_opt2 FROM v_pos_barang  ' . $where . ' ORDER BY barang_nama asc')->result_array();
			// echo $this->db->last_query();exit;
			if (count($stok) == 0) {
				$html .= '<tr><td colspan="11" style="text-align:center">Belum ada daftar barang!</td></tr>';
			}
			$no = 1;
			foreach ($stok as $key => $value) {
				$html .= '<tr>
            <td>' . $no . '</td>
            <td>' . $value['barang_kode'] . '</td>
            <td>' . $value['barang_nama'] . '</td>
            <td>' . $value['kategori_barang_nama'] . '</td>
            <td>' . $value['barang_satuan_kode'] . '</td>
            <td style="text-align: right;">' . number_format($value['barang_harga']) . '</td>
            <td style="text-align: right;">' . number_format($value['barang_satuan_opt_kode']) . '</td>
            <td style="text-align: right;">' . number_format($value['barang_harga_opt']) . '</td>
            <td style="text-align: right;">' . number_format($value['barang_satuan_opt2_kode']) . '</td>
            <td style="text-align: right;">' . number_format($value['barang_harga_opt2']) . '</td>
            <td></td>
          </tr>';
				$no++;
			}
			$html .= '</table>';
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4-L',
				'file_name'     => 'Daftar Harga Barang',
				'title'         => 'Daftar Harga Barang',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
			));
		} else {
			$this->response(array(
				'success' => false
			));
		}
	}


	public function tprint_card2()
	{
		ini_set('memory_limit', '5048M');
		$data = varPost();

		$tanggal = date('d/m/Y');
		$hal = 1;
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
			.strikethrough {
			  position: relative;
			}
			.strikethrough:before {
			  position: absolute;
			  content: "";
			  left: 0;
			  top: 50%;
			  right: 0;
			  border-top: 1px solid;
			  border-color: inherit;

			  -webkit-transform:rotate(-5deg);
			  -moz-transform:rotate(-5deg);
			  -ms-transform:rotate(-5deg);
			  -o-transform:rotate(-5deg);
			  transform:rotate(-5deg);
			}
		</style>';

		$where = [];
		if ($data['ftype'] == 'form') {
			if ($data['barang_kategori_barang']) {
				$where[] = 'barang_kategori_barang = \'' . $data['barang_kategori_barang'] . '\' OR barang_kategori_parent = \'' . $data['barang_kategori_barang'] . '\' ';
			}
			if ($data['barang_id']) $where[] = 'barang_id = \'' . $data['barang_id'] . '\'';
			$where = (count($where) > 0) ? 'where ' . implode(' AND ', $where) : '';
		} else {
			$dt = [];
			foreach ($data['table'] as $key => $value) {
				$dt[] = $value['barang_id'];
			}
			$where = 'where barang_id in (\'' . implode('\', \'', $dt) . '\')';
		}

		if ($pemda_id = $this->session->userdata('pemda_id')) {
			if ($where) {
				$where .= ' AND ';
			} else {
				$where .= ' WHERE ';
			}

			$where .= " EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id=v_pos_barang.wajibpajak_id AND pemda_id=" . $this->db->escape($pemda_id) . ") ";
		}

		$barang = $this->db->query('
			SELECT 
				barang_id, 
				barang_kode, 
				barang_nama, 
				kategori_barang_nama, 
				barang_satuan_kode,
				barang_harga, 
				barang_satuan_opt_kode, 
				barang_harga_opt, 
				barang_satuan_opt2_kode, 
				barang_harga_opt2 
			FROM 
				v_pos_barang
			' . $where . ' 
			ORDER BY 
				barang_nama asc')
			->result_array();
		$no = 1;
		/*<tr><td rowspan="2"><img src="'.base_url('assets/barcode.php').'?text='.$value['barang_barcode'].'&print=true&size=300" style="height:35px;width:100px" alt=""></td>
					<td>'.number_format($value['barang_harga_opt']).' /'.$value['barang_satuan_opt_kode'].'</td>
				</tr>			
				<tr>
					<td>'.number_format($value['barang_harga_opt2']).' /'.$value['barang_satuan_opt2_kode'].'</td>
				</tr>
				<tr><td style="padding-left:40px">'.$value['barang_kode'].'</td><td>Tgl Cetak : '.$tanggal.'</td></tr>*/
		$html .= '<table style="width:92%!important; max-height:2.8cm"><tr>';
		foreach ($barang as $key => $value) {
			$disc = $this->db->query('SELECT barang_satuan_disc FROM pos_barang_satuan where barang_satuan_parent ="' . $value['barang_id'] . '"  AND barang_satuan_order ="' . $data['satuan'] . '" LIMIT 1')->result_array();
			$html .= '<td style="width:33%!important;padding-left:5px;padding-right:5px;padding-bottom:10px"><table style="border:1px solid #6f6f6f;">
				<tr><td style="font-size:' . (strlen($value['barang_nama']) > 25 ? '13' : '15') . 'px!important; padding:3px 5px; text-transform:uppercase;">' . $value['barang_nama'] . '</td><td style="text-align:right; padding:3px 5px"><img src="' . base_url('assets/base_image/eka3.png') . '" alt="" style="height:28px;width:38px"></td></tr>';
			if ($data['satuan'] == 1) {
				$harga = $value['barang_harga'] ? $value['barang_harga'] : '0.00';
			} else if ($data['satuan'] == 2) {
				$harga = $value['barang_harga_opt'] ? $value['barang_harga_opt'] : '0.00';
			} else {
				$harga = $value['barang_harga_opt2'] ? $value['barang_harga_opt2'] : '0.00';
			}
			$height = '105';
			$html .= '<tr style="padding-top:0px"><td style="font-size:14px;padding-left:5px;border-bottom:none; background-color:' . $data['print_color'] . '; height:30px; padding-top:0;vertical-align:top;"><span style="background-color:#fff;">&nbsp;' . $value['barang_kode'] . '&nbsp;</span></td>';
			if ($disc[0]['barang_satuan_disc']) {
				$html .= '<td style="text-align:right;font-size:18px;background-color:' . $data['print_color'] . '; padding-top:2px; padding-right:15px;"><s class="strikethrough"><sup> Rp.</sup> ' . number_format($harga) . '</s></td>';
				$disc = $disc[0]['barang_satuan_disc'] ? ($disc[0]['barang_satuan_disc'] * $harga) / 100 : 0;
				$harga -= $disc;
			} else {
				$html .= '<td style="background-color:' . $data['print_color'] . ';"></td>';
			}
			$height = '55';
			$html .= '<tr><td colspan="2" style="text-align:center;font-size:32px; border-top:none; height:' . $height . 'px; font-weight:bold; background-color:' . $data['print_color'] . ';"><sup style="">Rp.</sup> ' . number_format($harga) . '</td></tr>	
			</table></td>';

			if ($no % 3 == 0) $html .= '</tr><tr>';
			$no++;
		}
		if (count($barang) <= 2) {
			$html .= '<td style="width:' . (33 * (3 - count($barang))) . '%!important;padding-left:5px;padding-right:5px"><table></table></td>
				<td style="width:100px!important;padding-left:5px;padding-right:5px"><table></table></td>';
		}

		$html .= '</tr></table>';
		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4-L',
			'file_name'     => 'Price Card',
			'title'         => 'Price Card',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			'font_size'     => '10',
			'font_face'     => 'sans_fonts',

		));
	}

	public function deleteTransaksi()
	{
		$data = varPost();

		$data['code_store'] = $_ENV['PREFIX_DBPOS'] . $data['code_store'];
		$this->response($this->transaksiwp->deleteTransaksi($data));
	}

	//tambahan method detailTransaksi
	function detailTransaksi()
	{
		$data = varPost();
		$data['code_store'] = $_ENV['PREFIX_DBPOS'] . $data['code_store'];
		$this->response($this->transaksiwp->detailTransaksi($data));
	}

	public function spreadsheet()
	{
		$data = varPost();

		$codestore = $data['log_penjualan_code_store'];
		$periode = $data['periode'];
		$periodearr = explode(' - ', $periode);
		$enddate = date('Y-m-d');
		$startdate = date('Y-m-d');
		if (is_array($periodearr) && count($periodearr) > 1) {
			$startdate = date('Y-m-d 00:00:00', strtotime($periodearr[0]));
			$enddate = date('Y-m-d 23:59:59', strtotime($periodearr[1]));
		}

		try {
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			// Set Header
			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'eaeaea',
					],
					'endColor' => [
						'argb' => 'eaeaea',
					],
				],
			];
			$sheet->mergeCells('A1:G1');
			$sheet->setCellValue('A1', 'TRANSAKSI WAJIB PAJAK');
			$sheet->getStyle('A1')->applyFromArray($styleArray);

			foreach (range('A', 'J') as $columnID) {
				$sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

			$sheet->mergeCells('A3:C3');
			$sheet->mergeCells('A4:C4');
			$sheet->mergeCells('A5:C5');
			$sheet->setCellValue('A3', 'Kode Toko');
			$sheet->setCellValue('A4', 'Nama Toko');
			$sheet->setCellValue('A5', 'Periode');

			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				],
			];

			$namatoko = $this->db->query("SELECT toko_nama from pajak_toko  where toko_kode  = '" . $codestore . "'")->row_array()['toko_nama'];
			$sheet->mergeCells('D3:G3');
			$sheet->mergeCells('D4:G4');
			$sheet->mergeCells('D5:G5');
			$sheet->setCellValue('D3', $codestore);
			$sheet->setCellValue('D4', $namatoko);
			$sheet->setCellValue('D5', $periode);
			$sheet->getStyle('D3:D5')->applyFromArray($styleArray);

			// Set Table Header
			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'eaeaea',
					],
					'endColor' => [
						'argb' => 'eaeaea',
					],
				],
			];
			$sheet->getStyle('A7:I7')->applyFromArray($styleArray);
			$sheet->setCellValue('A7', 'No');
			$sheet->setCellValue('B7', 'Kode Toko');
			$sheet->setCellValue('C7', 'Nama WP');
			$sheet->setCellValue('D7', 'NPWPD');
			$sheet->setCellValue('E7', 'Penjualan Tanggal');
			$sheet->setCellValue('F7', 'Nominal Penjualan');
			$sheet->setCellValue('G7', 'Kode Penjualan');
			$sheet->setCellValue('H7', 'Status');
			$sheet->setCellValue('I7', 'Status Lapor Pajak');

			// Set Borders
			$styleArray = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			];
			$styleArray_nominal = [
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				],
			];
			$where = [];
			// $where['log_penjualan_code_store'] = $codestore;
			$where['penjualan_tanggal >= \'' . $startdate . '\' AND penjualan_tanggal <= \'' . $enddate . '\''] = null;
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id = pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')', NULL, FALSE);
			}
			$ops = $this->db->select("*")
				->where($where)
				->order_by('penjualan_tanggal desc')
				->get('pos_penjualan')
				->result_array();
			if ($pemda_id = $this->session->userdata('pemda_id')) {
				$this->db->where('pemda_id', $pemda_id);
			}
			$wp = $this->db
				->select('wajibpajak_nama_penanggungjawab, 
			wajibpajak_npwpd, 
			toko_kode,
			toko_nama')
				->get_where('v_pajak_toko', [
					'toko_kode' => $codestore,
				])->row_array();
			$no = 7;
			foreach ($ops as $key => $value) {
				foreach ($value as $vkey => $vvalue) {
					if (is_null($vvalue)) {
						$value[$vkey] = "-";
					}
				}
				$status = '';
				$statuslapor = '';
				if ($value['penjualan_status_aktif'] != '-') {
					$status = 'Batal';
				} else {
					$status = 'Aktif';
				}
				if ($value['penjualan_lock'] == '1') {
					$statuslapor = 'Sudah';
				} else {
					$statuslapor = 'Belum';
				}

				$no += 1;
				$sheet->setCellValue('A' . $no, $key + 1);
				$sheet->setCellValue('B' . $no, $wp['toko_kode']);
				$sheet->setCellValue('C' . $no, $wp['toko_nama']);
				$sheet->setCellValue('D' . $no, $wp['wajibpajak_npwpd']);
				$sheet->setCellValue('E' . $no, $value['penjualan_tanggal']);
				$sheet->setCellValue('F' . $no, 'Rp ' . number_format($value['penjualan_total_grand']));
				$sheet->setCellValue('G' . $no, $value['penjualan_kode']);
				$sheet->setCellValue('H' . $no, $status);
				$sheet->setCellValue('I' . $no, $statuslapor);
				$sheet->getStyle('I' . $no)->applyFromArray($styleArray_nominal);
			}
			$sheet->getStyle('A7:I' . $no)->applyFromArray($styleArray);

			$styleArray = [
				'font' => [
					'bold' => true,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
				'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'startColor' => [
						'argb' => 'eaeaea',
					],
					'endColor' => [
						'argb' => 'eaeaea',
					],
				],
			];
			$get_total = $this->db
				->select("sum(penjualan_total_grand) as total_nominal_penjualan")
				->where($where)
				->get('pos_penjualan')
				->row();
			$no += 1;
			$sheet->mergeCells('A' . $no . ':E' . $no);
			$sheet->setCellValue('A' . $no, 'Total');
			$sheet->setCellValue('F' . $no, 'Rp. ' . number_format($get_total->total_nominal_penjualan));
			$sheet->setCellValue('G' . $no, '');
			$sheet->getStyle('A' . $no . ':I' . $no)->applyFromArray($styleArray);
			$sheet->getStyle('F' . $no)->applyFromArray($styleArray_nominal);

			// Write a new .xlsx file
			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

			// Save the new .xlsx file
			$filename = 'transaksiwp-' . $codestore . '-' . date('d-m-y-H:i:s') . '.xlsx';
			if (!file_exists(FCPATH . 'assets/laporan/wajibpajak/')) {
				mkdir(FCPATH . 'assets/laporan/wajibpajak/', 0777, true);
			}
			$file = FCPATH . 'assets/laporan/wajibpajak/' . $filename;
			$writer->save($file);

			$this->response([
				'success' => true,
				'file' => $filename
			]);
		} catch (\Throwable $th) {
			print_r('<pre>');
			print_r($th);
			print_r('</pre>');
			exit;
			$this->response([
				'success' => false,
			]);
		}
	}

	public function pdf()
	{
		$data = varPost();

		$codestore = $data['log_penjualan_code_store'];
		$periode = $data['periode'];
		$periodearr = explode(' - ', $periode);
		$enddate = date('Y-m-d');
		$startdate = date('Y-m-d');
		if (is_array($periodearr) && count($periodearr) > 1) {
			$startdate = date('Y-m-d 00:00:00', strtotime($periodearr[0]));
			$enddate = date('Y-m-d 23:59:59', strtotime($periodearr[1]));
		}
		// if (empty($data['filterBulan'])) {
		// 	$masapajak = 'All';
		// } else {
		// 	$bulan = explode('-', $data['filterBulan']);
		// 	$masapajak = phpChgMonth(intval($bulan[1])) . ' ' . $bulan[0];
		// }
		$hal = 1;
		$html = '<style>
			*, table, p, li{
				line-height:1.6;
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

			th, td{
				padding: 0 10px;
			}
		</style>';

		$namatoko = $this->db->query("SELECT toko_nama from pajak_toko  where toko_kode  = '" . $codestore . "'")->row_array()['toko_nama'];

		$html .= '<table style="width:100%;">
			<tr>
				<td class="left">
					<p>OPTAX</p>
				</td>
				<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
			</tr>
			<tr>
				<td colspan="2" class="kop">
						<h4>TRANSAKSI WAJIB PAJAK</h4><br>
				</td>
			</tr>
		</table>
		<table style="width:100%;">
			<tr>
				<td width="20%">Kode Toko</td>
				<td>: ' . $codestore . '</td>
			</tr>
			<tr>
				<td width="20%">Nama Toko</td>
				<td>: ' . $namatoko . '</td>
			</tr>
			<tr>
				<td width="20%">Periode</td>
				<td>: ' . $periode . '</td>
			</tr>
		</table>
		<br>
		<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
			<tr>
				<th class="t-center">No</th>
				<th class="t-center">Kode Toko</th>
				<th class="t-center">Nama WP</th>
				<th class="t-center">NPWPD</th>
				<th class="t-center">Penjualan Tanggal</th>
				<th class="t-center">Nominal Penjualan</th>
				<th class="t-center">Kode Penjualan</th>
				<th class="t-center">Status</th>
				<th class="t-center">Status Lapor Pajak</th>
			</tr>';
		$where = [];
		// $where['log_penjualan_code_store'] = $codestore;
		$where['penjualan_tanggal >= \'' . $startdate . '\' AND penjualan_tanggal <= \'' . $enddate . '\''] = null;
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id = pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')', NULL, FALSE);
		}
		$ops = $this->db
			->where($where)
			->order_by('penjualan_tanggal desc')
			->get('pos_penjualan')
			->result_array();
		if ($pemda_id = $this->session->userdata('pemda_id')) {
			$this->db->where('pemda_id', $pemda_id);
		}
		$wp = $this->db
			->select('wajibpajak_nama_penanggungjawab, 
		wajibpajak_npwpd, 
		toko_kode,
		toko_nama')
			->get_where('v_pajak_toko', [
				'toko_kode' => $codestore,
			])->row_array();
		$no = $total = $tbl_no = 1;
		// print_r('<pre>');print_r($ops);print_r('</pre>');exit;
		foreach ($ops as $key => $value) {
			// print_r('<pre>');print_r($value);print_r('</pre>');exit;
			$status = '';
			$statuslapor = '';
			if ($value['penjualan_status_aktif']) {
				$status = 'Batal';
			} else {
				$status = 'Aktif';
			}
			if ($value['penjualan_lock'] == '1') {
				$statuslapor = 'Sudah';
			} else {
				$statuslapor = 'Belum';
			}

			$html .= '<tr>
					<td>' . $tbl_no . '</td>
					<td>' . $wp['toko_kode'] . '</td>
					<td>' . $wp['toko_nama'] . '</td>
					<td>' . $wp['wajibpajak_npwpd'] . '</td>
					<td>' . $value['penjualan_tanggal'] . '</td>
					<td style="text-align: right;">Rp. ' . number_format($value['penjualan_total_grand']) . '</td>
					<td>' . $value['penjualan_kode'] . '</td>
					<td>' . $status . '</td>
					<td>' . $statuslapor . '</td>
				</tr>';
			$tbl_no++;
			$no++;
		}

		$get_total = $this->db
			->select("sum(penjualan_total_grand) as total_nominal_penjualan")
			->where($where)
			->get('pos_penjualan')
			->row();
		$html .= '<tr>
			<th class="t-center" colspan="5">Total</th>
			<th class="t-center" style="text-align: right;">Rp. ' . number_format($get_total->total_nominal_penjualan) . '</th>
			<th class="t-center" colspan="3"></th>
		</tr>';

		$html .= '</table>';

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Transaksi Wajib Pajak - ' . $codestore,
			'title'         => 'Transaksi Wajib Pajak - ' . $codestore,
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}
}

/* End of file Pricelist.php */
/* Location: ./application/modules/pricelist/controllers/Pricelist.php */