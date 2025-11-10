<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporanpricelist extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'barang/BarangModel' 				 => 'barang',
			'barang/BarangsatuanModel'			 => 'barangsatuan',
			'barang/BarangbarcodeModel'			 => 'barangbarcode',
			'kategori/KategoriModel' 			 => 'kelompokbarang',
		));
	}

	public function index()
	{
		$data = varPost();
		if (!empty($data['codestore'])) {
			$filter = varPost('filter');
			unset($filter['print_color']);
			if (!$filter) $filter = [];
			$filter['barang_deleted_at'] = null;
			$this->response($this->select_dt(varPost(), 'barang', 'pricelist', false, $filter, null, $_ENV['PREFIX_DBPOS'] . $data['codestore']));
		} else {
			$this->response(array(
				'success' => false
			));
		}
	}

	public function select_wp($value = '')
	{
		$data = varPost();
		$where = ' AND toko_status = \'2\'';
		// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$total = $this->db->query('SELECT count(toko_id) total FROM pajak_toko 
		WHERE concat(toko_kode, toko_nama) like \'%' . $data['q'] . '%\' ' . $where)->result_array();

		$return = $this->db->query('SELECT toko_kode as id, concat(toko_kode, \' - \', toko_nama) as text FROM v_pajak_toko 
		WHERE concat(toko_kode, toko_nama) like \'%' . $data['q'] . '%\' ' . $where . ' 
		LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function select_ajax($value = '')
	{
		$data = varPost();
		if (!empty($value)) {
			$this->db = $this->load->database(multidb_connect($_ENV['PREFIX_DBPOS'] . $value), true);

			$where = ($data['fdata']['barang_kategori_barang']) ? 'AND barang_kategori_barang = \'' . $data['fdata']['barang_kategori_barang'] . '\'' : '';
			$where .= ' AND barang_deleted_at is null';
			// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
			$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
			$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE concat(barang_kode, barang_nama) like \'%' . $data['q'] . '%\' ' . $where)->result_array();

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
			$this->db = $this->load->database(multidb_connect($_ENV['PREFIX_DBPOS'] . $value), true);
			$kelompokbarang = $this->db->query('SELECT *
      FROM
        pos_kategori
      WHERE
        kategori_barang_aktif = \'1\'
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
			$this->db = $this->load->database(multidb_connect($_ENV['PREFIX_DBPOS'] . $value), true);
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
}

/* End of file Pricelist.php */
/* Location: ./application/modules/pricelist/controllers/Pricelist.php */