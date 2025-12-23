<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembayaran extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'pembayaranModel' 		=> 'pembayaran',
			'pembayarandetailModel' => 'pembayarandetail',
			'PembayarandetailpembayaranModel' 	=> 'multipayment',
			'transaksipembelian/transaksipembelianModel' => 'transaksipembelian',
			'jurnal/JurnalModel' 	=> 'jurnal',
		));
	}

	public function index()
	{
		$var = varPost();
		$this->response(
			$this->select_dt($var, 'pembayaran', 'table', true, array(
				'pembayaran_aktif' => '1',
				'pembayaran_kode IS NOT NULL' => null,
			))
		);
	}


	public function index2()
	{
		$var = varPost();


		if (!array_key_exists('tanggal1', $var) && !array_key_exists('tanggal2', $var)) {
			$var['tanggal1'] = '2021-01-01';
			$var['tanggal2'] = date('Y-m-d');
		}
		$this->response(
			$this->select_dt($var, 'pembayaran', 'table', true, array(
				'pembayaran_aktif' => '1',
				'pembayaran_tanggal BETWEEN \'' . $var['tanggal1'] . '\' AND \'' . $var['tanggal2'] . '\' ' => null,
			))
		);
	}

	function read($value = '')
	{
		$this->response($this->pembayaran->read(varPost()));
	}


	public function table_faktur()
	{
		$filter = varPost('pembelian_supplier_id');


		if ($filter != '') {
			// Query Baru
			$where1 = '';
			if ($wp_id = $this->session->userdat('wajibpajak_id')) {
				$where1 = ' AND wajibpajak_id=' . $this->db->escape($wp_id);
			}
			$data['aaData'] = $this->db->query("SELECT * FROM 
				v_pos_pembelian_barang 
			WHERE 
				pembelian_supplier_id = '$filter' 
				AND pembelian_bayar_opsi = 'K' 
				AND pembelian_bayar_sisa > 0
				$where1
			")->result_array();
			$data["iTotalRecords"] = count($data['aaData']);
			$data["iTotalDisplayRecords"] = count($data['aaData']);
			$data["sEcho"] = 0;
			$data["sColumns"] = "";
		} else {
			$data['aaData'] = [];
			$data["iTotalDisplayRecords"] = 0;
			$data["sEcho"] = 0;
			$data["sColumns"] = 0;
		}
		$this->response($data);
	}

	public function store()
	{
		$data = varPost();
		$data['pembayaran_kode'] = $this->pembayaran->gen_kode_pembayaran();
		$data['pembayaran_user'] = $this->session->userdata('user_id');
		$data['pembayaran_aktif'] = '1';
		$data['pembayaran_created_at'] = date('Y-m-d');
		$error = [];
		$sales = $this->db->select('sales_id')
			->get_where('pos_sales', array(
				'sales_nama' 		=> $data['pembayaran_sales'],
				'sales_supplier_id' => $data['pembayaran_supplier_id'],
			))
			->result_array();
		if (count($sales) < 1) {
			$this->db->insert('pos_sales', array(
				'sales_id' 			=> gen_uuid($this->pembayaran->get_table()),
				'sales_supplier_id' => $data['pembayaran_supplier_id'],
				'sales_nama' 		=> $data['pembayaran_sales'],
				'wajibpajak_id'		=> $this->session->userdata('wajibpajak_id')
			));
		}
		$operation = $this->pembayaran->insert(gen_uuid($this->pembayaran->get_table()), $data, function ($res) use ($data) {
			$detail = [];
			foreach ($data['pembayaran_detail_pembelian_id'] as $key => $value) {
				$detail = [
					'pembayaran_detail_parent' 		=> $res['record']['pembayaran_id'],
					'pembayaran_detail_pembelian_id' => $value,
					'pembayaran_detail_jatuh_tempo'	=> $data['pembayaran_detail_jatuh_tempo'][$key],
					'pembayaran_detail_tagihan' 	=> $data['pembayaran_detail_tagihan'][$key],
					'pembayaran_detail_retur' 		=> $data['pembayaran_detail_retur'][$key],
					'pembayaran_detail_potongan' 	=> $data['pembayaran_detail_potongan'][$key],
					'pembayaran_detail_sisa' 		=> $data['pembayaran_detail_sisa'][$key],
					'pembayaran_detail_bayar' 		=> $data['pembayaran_detail_bayar'][$key],
					'pembayaran_detail_pembelian_kode' => $data['pembelian_kode'][$key],
				];
				$tag = $data['pembayaran_detail_tagihan'][$key] - $data['pembayaran_detail_retur'][$key];
				// bayar tidak boleh melebihi tagihan
				$bayar = intval($data['pembayaran_detail_bayar'][$key]);
				$sisa = $tag - $bayar;
				$det_opr = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()), $detail);
				if (!$det_opr['success']) $error[$key]['detail'] = $det_opr;
				else {
					if ($data['pembayaran_status'] == '1') {
						$this->update_pembelian($value);
						$beli = $this->db->affected_rows();
						if ($this->db->affected_rows() <= 0) $error[$key]['beli'] = $det_opr;
					}
				}
			}

			// if ($data['pembayaran_status'] == '1') {
			// 	$kredit = [];
			// 	$bfr_diskon = $data['pembayaran_bayar'];
			// 	if ($data['pembayaran_retur'] || $data['pembayaran_potongan']) {
			// 		$kredit = [
			// 			'5005' => ($data['pembayaran_retur'] + $data['pembayaran_potongan']),
			// 		];
			// 		$bfr_diskon += ($data['pembayaran_retur'] + $data['pembayaran_potongan']);
			// 		if ($data['pembayaran_retur'] && $data['pembayaran_potongan']) {
			// 			$kredit_keterangan = [
			// 				'5005' => 'Retur dan Potongan Pembelian ' . $res['record']['supplier_nama'],
			// 			];
			// 		} else {
			// 			if ($data['pembayaran_retur']) {
			// 				$kredit_keterangan = [
			// 					'5005' => 'Retur Pembelian ' . $res['record']['supplier_nama'],
			// 				];
			// 			} else {
			// 				$kredit_keterangan = [
			// 					'5005' => 'Potongan Pembelian ' . $res['record']['supplier_nama'],
			// 				];
			// 			}
			// 		}
			// 	}
			// 	$kredit[$data['pembayaran_akun_id']] = $data['pembayaran_bayar'];
			// 	$debit = [
			// 		'2112' => $data['pembayaran_tagihan']
			// 	];
			// 	$debit_keterangan = [
			// 		'2112' => 'Pemby Brg JT ' . $res['record']['supplier_nama']
			// 	];
			// 	$kredit_keterangan[$data['pembayaran_akun_id']] = 'Pembayaran Hutang Dagang ' . $res['record']['supplier_nama'];
			// 	$trans = [
			// 		'jurnal_umum_nobukti' 			=> $this->jurnal->generate_kode('BKK', $data['pembayaran_tanggal']),
			// 		'jurnal_umum_tanggal' 			=> $data['pembayaran_tanggal'],
			// 		'jurnal_umum_penerima' 			=> $data['pembayaran_supplier_id'],
			// 		'jurnal_umum_lawan_transaksi'   => $data['pembayaran_supplier_id'],
			// 		'jurnal_umum_keterangan'		=> 'Pembayaran Barang Dagang',
			// 		'jurnal_umum_reference'			=> 'persediaan_barang',
			// 		'jurnal_umum_reference_id'		=> $res['record']['pembayaran_id'],
			// 		'jurnal_umum_reference_kode'	=> $data['pembayaran_kode'],
			// 		'jurnal_umum_unit'				=> '1',
			// 	];
			// 	$this->jurnal->add_jurnal($debit, $kredit, $trans, $debit_keterangan, $kredit_keterangan);
			// }

			// Multi Payment
			foreach ($data['pembayaran_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'pembayaran_piutang_detail_pembayaran_id' => $value,
					'pembayaran_piutang_detail_pembayaran_parent' => $res['record']['pembayaran_id'],
					'pembayaran_piutang_detail_pembayaran_tanggal' => $data['pembayaran_piutang_detail_pembayaran_tanggal'][$key],
					'pembayaran_piutang_detail_pembayaran_cara_bayar' => $data['pembayaran_piutang_detail_pembayaran_cara_bayar'][$key],
					'pembayaran_piutang_detail_pembayaran_total' => $data['pembayaran_piutang_detail_pembayaran_total'][$key],
				];
				$det_opr_pembayaran = $this->multipayment->insert(gen_uuid($this->multipayment->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}
		});
		$operation['error'] = $error;
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();

		// destroy pembayaran lama 
		$this->db->where('pembayaran_detail_pembayaran_parent', $data['pembayaran_id']);
		$this->db->delete('pos_pembayaran_pembayaran_detail');


		$operation = $this->pembayaran->update($data['pembayaran_id'], $data, function (&$res) use ($data) {
			$detail = $id_detail = [];


			// Handle detail pembayaran  
			foreach ($data['pembayaran_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'pembayaran_detail_pembayaran_id' => $value,
					'pembayaran_detail_pembayaran_parent' => $data['pembayaran_id'],
					'pembayaran_detail_pembayaran_tanggal' => $data['pembayaran_detail_pembayaran_tanggal'][$key],
					'pembayaran_detail_pembayaran_cara_bayar' => $data['pembayaran_detail_pembayaran_cara_bayar'][$key],
					'pembayaran_detail_pembayaran_total' => $data['pembayaran_detail_pembayaran_total'][$key],
				];
				$det_opr_pembayaran = $this->multipayment->insert(gen_uuid($this->multipayment->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}


			foreach ($data['pembayaran_detail_pembelian_id'] as $key => $value) {
				$detail = [
					'pembayaran_detail_parent' 		=> $res['record']['pembayaran_id'],
					'pembayaran_detail_pembelian_id' => $value,
					'pembayaran_detail_jatuh_tempo'	=> $data['pembayaran_detail_jatuh_tempo'][$key],
					'pembayaran_detail_tagihan' 	=> $data['pembayaran_detail_tagihan'][$key],
					'pembayaran_detail_retur' 		=> $data['pembayaran_detail_retur'][$key],
					'pembayaran_detail_potongan' 	=> $data['pembayaran_detail_potongan'][$key],
					'pembayaran_detail_sisa' 		=> $data['pembayaran_detail_sisa'][$key],
					'pembayaran_detail_bayar' 		=> $data['pembayaran_detail_bayar'][$key],
					'pembayaran_detail_pembelian_kode' => $data['pembelian_kode'][$key],

				];
				$res_detail = $this->pembayarandetail->update($data['pembayaran_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$res_detail = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()), $detail);
					if ($res_detail['success']) $id_detail[] = $res_detail['id'];

					if ($data['pembayaran_status'] == '1') {
						/*$tag = $data['pembayaran_detail_tagihan'][$key]-$data['pembayaran_detail_retur'][$key];
						// bayar tidak boleh melebihi tagihan
						$bayar = intval($data['pembayaran_detail_bayar'][$key])+intval($data['pembayaran_detail_potongan'][$key])+intval($data['pembayaran_detail_retur'][$key]);
						$sisa = $tag - $bayar;	
						$this->db->set('pembelian_bayar_jumlah', 'pembelian_bayar_jumlah+'.$bayar, FALSE);
						$this->db->set('pembelian_bayar_sisa', $sisa);
						$this->db->where('pembelian_id', $value);
						$this->db->update('pos_pembelian_barang');*/
						$this->update_pembelian($value);
					}
				} else {
					if ($data['pembayaran_status'] == '1') {
						/*$bayar = $data['pembayaran_detail_bayar'][$key]+intval($data['pembayaran_detail_potongan'][$key])+intval($data['pembayaran_detail_retur'][$key])-$data['pembayaran_detail_bayar_last'][$key];
						$this->db->set('pembelian_bayar_jumlah', 'pembelian_bayar_jumlah+'.$bayar, FALSE);
						$this->db->set('pembelian_bayar_sisa', 'pembelian_bayar_sisa-'.$bayar, FALSE);
						$this->db->where('pembelian_id', $value);
						$this->db->update('pos_pembelian_barang');*/
						$this->update_pembelian($value);
						$beli = $this->db->affected_rows();
						if ($this->db->affected_rows() <= 0) $error[$key]['beli'] = $det_opr;
					}
					$id_detail[] = $res_detail['id'];
				}
				$id = implode(', ', $id_detail);
				$res['id_detail'] = $id;

				// if ($data['pembayaran_status'] == '1') {
				// 	$kredit = [];
				// 	$bfr_diskon = $data['pembayaran_bayar'];
				// 	if ($data['pembayaran_retur'] || $data['pembayaran_potongan']) {
				// 		$kredit = [
				// 			'5005' => ($data['pembayaran_retur'] + $data['pembayaran_potongan']),
				// 		];
				// 		$bfr_diskon += ($data['pembayaran_retur'] + $data['pembayaran_potongan']);
				// 		if ($data['pembayaran_retur'] && $data['pembayaran_potongan']) {
				// 			$kredit_keterangan = [
				// 				'5005' => 'Retur Pembelian ' . $res['record']['supplier_nama'],
				// 			];
				// 		} else {
				// 			if ($data['pembayaran_retur']) {
				// 				$kredit_keterangan = [
				// 					'5005' => 'Retur Pembelian ' . $res['record']['supplier_nama'],
				// 				];
				// 			} else {
				// 				$kredit_keterangan = [
				// 					'5005' => 'Potongan Pembelian ' . $res['record']['supplier_nama'],
				// 				];
				// 			}
				// 		}
				// 	}
				// 	$kredit[$data['pembayaran_akun_id']] = $data['pembayaran_bayar'];
				// 	$debit = [
				// 		'2112' => $data['pembayaran_tagihan']
				// 	];
				// 	$debit_keterangan = [
				// 		'2112' => 'Hutang Dagang ' . $res['record']['supplier_nama']
				// 	];
				// 	$kredit_keterangan[$data['pembayaran_akun_id']] = 'Pembayaran Hutang Dagang ' . $res['record']['supplier_nama'];
				// 	$jurnal = $this->jurnal->read(['jurnal_umum_reference_id' => $res['record']['pembayaran_id']]);
				// 	$trans = [
				// 		'jurnal_umum_id' 				=> $jurnal['jurnal_umum_id'],
				// 		'jurnal_umum_tanggal' 			=> $data['pembayaran_tanggal'],
				// 		'jurnal_umum_penerima' 			=> $data['pembayaran_supplier_id'],
				// 		'jurnal_umum_lawan_transaksi'   => $data['pembayaran_supplier_id'],
				// 		'jurnal_umum_keterangan'		=> 'Pembayaran Barang Dagang',
				// 		'jurnal_umum_reference'			=> 'persediaan_barang',
				// 		'jurnal_umum_reference_id'		=> $res['record']['pembayaran_id'],
				// 		'jurnal_umum_reference_kode'	=> $data['pembayaran_kode'],
				// 		'jurnal_umum_unit'				=> '1',
				// 	];
				// 	$this->jurnal->edit_jurnal($debit, $kredit, $trans, $debit_keterangan, $kredit_keterangan);
				// }
			}
		});
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->pembayarandetail->select(array('filters_static' => $data)));
	}

	public function get_detail_pembayaran()
	{
		$data = varPost();
		$this->response($this->multipayment->select(array('filters_static' => $data)));
	}

	public function batalBayar()
	{
		$id = varPost('id');
		// $pembayaran = $this->db->get_where('pos_pembayaran', ['pembayaran_id' => $id])->row_array();

		$pembayaran_detail = $this->db->get_where('pos_pembayaran_detail', ['pembayaran_detail_parent' => $id])->result_array();


		// penyesuain data pembayaran pada table pos_pembelian_barang
		foreach ($pembayaran_detail as $key => $value) {
			$pembelian = $this->db->get_where('pos_pembelian_barang', ['pembelian_id' => $value['pembayaran_detail_pembelian_id']])->row_array();
			$pembelian_bayar_sisa_baru = $pembelian['pembelian_bayar_sisa'] + $value['pembayaran_detail_bayar'];
			$pembelian_bayar_jumlah_baru = $pembelian['pembelian_bayar_jumlah'] - $value['pembayaran_detail_bayar'];

			$this->db->set('pembelian_bayar_sisa', $pembelian_bayar_sisa_baru);
			$this->db->set('pembelian_bayar_jumlah', $pembelian_bayar_jumlah_baru);
			$this->db->where('pembelian_id', $value['pembayaran_detail_pembelian_id']);
			$this->db->update('pos_pembelian_barang');
		}
		$this->db->set('pembayaran_deleted_at', date("Y-m-d H:i:s"));
		$this->db->set('pembayaran_aktif', 0);
		$this->db->where('pembayaran_id', $id);

		if ($this->db->update('pos_pembayaran')) {
			$response['success'] = true;
			$response['message'] = 'You have successfully deleted data.';
		} else {
			$response['success'] = false;
			$response['message'] = 'Delete data failure.';
		}

		$this->response($response);
	}

	public function destroy()
	{
		$data = varPost();
		$detail = $this->pembayarandetail->select(array('filters_static' => array('pembayaran_detail_parent' => $data['id'])))['data'];
		$operation = $this->pembayaran->update(varPost('id'), array('pembayaran_aktif' => 0));
		foreach ($detail as $key => $value) {
			$this->update_pembelian($value['pembayaran_detail_pembelian_id']);
		}
		$jurnal = $this->jurnal->read(['jurnal_umum_reference_id' => varPost('id')]);
		if ($jurnal['jurnal_umum_reference_id']) {
			$jurnal['jurnal_umum_reference'] = 'delete';
			$jurnal['jurnal_umum_status'] = 'deactive';
			$this->jurnal->edit_jurnal([], [], $jurnal);
		}
		$this->response($operation);
	}

	public function update_pembelian($id)
	{
		/*
		$update = $this->db->query('UPDATE pos_pembelian_barang 
						LEFT JOIN 
							(SELECT ifnull(SUM(pembayaran_detail_bayar),0) bayar_jumlah, ifnull(SUM(pembayaran_detail_retur),0) retur_jumlah, ifnull(SUM(pembayaran_detail_potongan),0) potongan_jumlah,  pembayaran_detail_pembelian_id FROM pos_pembayaran_detail LEFT JOIN pos_pembayaran on pembayaran_id = pembayaran_detail_parent WHERE pembayaran_aktif = "1" and pembayaran_detail_pembelian_id ="' . $id . '" GROUP BY pembayaran_detail_pembelian_id) as bayar 
						on pembayaran_detail_pembelian_id = pembelian_id 
						set pembelian_bayar_jumlah = ifnull(bayar_jumlah, 0), 
							pembelian_retur = ifnull(retur_jumlah, 0),
							pembelian_bayar_sisa = pembelian_bayar_grand_total-ifnull(retur_jumlah,0)-ifnull(bayar_jumlah,0)-ifnull(potongan_jumlah,0)
						WHERE pembelian_id ="' . $id . '"');
		*/
		/*
		// MYSQL
		$update = $this->db->query('UPDATE pos_pembelian_barang 
						LEFT JOIN 
							(SELECT ifnull(SUM(pembayaran_detail_bayar),0) bayar_jumlah, ifnull(SUM(pembayaran_detail_retur),0) retur_jumlah, ifnull(SUM(pembayaran_detail_potongan),0) potongan_jumlah,  pembayaran_detail_pembelian_id FROM pos_pembayaran_detail LEFT JOIN pos_pembayaran on pembayaran_id = pembayaran_detail_parent WHERE pembayaran_aktif = "1" and pembayaran_detail_pembelian_id ="' . $id . '" GROUP BY pembayaran_detail_pembelian_id) as bayar 
						on pembayaran_detail_pembelian_id = pembelian_id 
						set pembelian_bayar_jumlah = ifnull(bayar_jumlah, 0), 
							pembelian_bayar_sisa = pembelian_bayar_grand_total-ifnull(pembelian_retur,0)-ifnull(bayar_jumlah,0)-ifnull(potongan_jumlah,0)
						WHERE pembelian_id =\'' . $id . '\'');
		*/
		// MIGRATE PSQL
		$update = $this->db->query('UPDATE public.pos_pembelian_barang 
			set 
				pembelian_bayar_jumlah = COALESCE(bayar_jumlah, 0), 
				pembelian_retur = COALESCE(retur_jumlah, 0), 
				pembelian_bayar_sisa = pembelian_bayar_grand_total-COALESCE(retur_jumlah,0)-COALESCE(bayar_jumlah,0)-COALESCE(potongan_jumlah,0)
			from 
				(SELECT COALESCE(SUM(pembayaran_detail_bayar),0) bayar_jumlah, COALESCE(SUM(pembayaran_detail_retur),0) retur_jumlah, COALESCE(SUM(pembayaran_detail_potongan),0) potongan_jumlah, pembayaran_detail_pembelian_id 
					FROM public.pos_pembayaran_detail 
					LEFT JOIN public.pos_pembayaran on pembayaran_id = pembayaran_detail_parent 
					WHERE pembayaran_aktif = \'1\' and pembayaran_detail_pembelian_id =\'' . $id . '\' 
					GROUP BY pembayaran_detail_pembelian_id
				) as bayar
			where pembelian_id = \'' . $id . '\'');
		return $update;
	}

	public function loaddetail()
	{
		$data = varPost();
		$no = 1;
		$join =
			$detail = $this->pembayarandetail->select(array('filters_static' => array(
				'pembayaran_detail_parent' => $data['pembayaran_detail_parent']
			)));
		$html = '<table cellspacing="0" cellpadding="2" style="width:90%">
			<thead>
				<tr>
					<td>No</td>
					<td>No Beli</td>
					<td>Tgl Beli</td>
					<td>Total</td>
					<td>Retur</td>
					<td>Potongan</td>
					<td>Bayar</td>
				</tr>
			</thead>
			';
		$html .= '<tbody>';
		foreach ($detail['data'] as $key => $value) {
			$html .= '<tr>
						<td>' . $no++ . '</td>
						<td>' . $value['pembelian_kode'] . '</td>
						<td>' . $value['pembelian_tanggal'] . '</td>
						<td>' . $value['pembayaran_detail_tagihan'] . '</td>
						<td>' . $value['pembayaran_detail_retur'] . '</td>
						<td>' . $value['pembayaran_detail_potongan'] . '</td>
						<td>' . $value['pembayaran_detail_bayar'] . '</td>
						</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		echo json_encode(array(
			'success' 	=> true,
			'html' 		=> $html
		));
	}

	public function cetak($value = '')
	{
		if ($value) {
			$user = $this->session->userdata();
			$data = $this->db->where('pembayaran_id', $value)
				->get('v_pos_pembayaran')
				->row_array();
			$detail = $this->db->where('pembayaran_detail_parent', $value)
				->get('v_pos_pembayaran_detail')
				->result_array();
			$html = '<style>
				*, table, p, li{
					line-height:1.5;
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

			$html .= '<table style="width:100%;">
				<tr>
					<td class="left">
						<p>' . $user['toko_nama'] . '</p>
						<p>--- --- ----</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI PEMBAYARAN FAKTUR PEMBELIAN </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['pembayaran_tanggal'] ? date("d/m/Y", strtotime($data['pembayaran_tanggal']))  : "-") . '</td>
					<td>No Bukti : ' . ($data['pembayaran_kode'] ? $data['pembayaran_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td class="">Supplier : ' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
					<td class="">Akun : ' . ($data['akun_nama'] ? $data['akun_kode'] . ' - ' . $data['akun_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Alamat: ' . ($data['supplier_alamat'] ? $data['supplier_alamat'] : "-") . '</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">No Faktur</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Retur</th>
					<th class="t-center">Bayar</th>
					<th class="t-center">Sisa</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			foreach ($detail as $key => $value) {
				$percentase = 5;
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['pembelian_kode'] ? $value['pembelian_kode'] : "-") . '</td>
						<td>' . ($value['pembayaran_detail_tagihan'] ? number_format($value['pembayaran_detail_tagihan'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['pembayaran_detail_retur'] ? number_format($value['pembayaran_detail_retur'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['pembayaran_detail_bayar'] ? number_format($value['pembayaran_detail_bayar'], 2, ',', '.')  : "-") . '</td>
						<td>' . (number_format(($value['pembayaran_detail_tagihan'] - $value['pembayaran_detail_retur'] - $value['pembayaran_detail_bayar']), 2, ',', '.')) . '</td>
					</tr>';
				$totalJml += $value['pembayaran_detail_bayar'];
				// $totalQty += $value['pembayaran_detail_qty'];
			}


			$html .= '<tr>
					<td colspan="4" class="total">Total</td>
					<td class="total">' . number_format($totalJml, 2, ',', '.') . '</td>
					<td></td>
				</tr>
			</table>
			<br>
			<br>
			<table style="width:500px;" class="ttd">
				<tr>
					<td class="top">Dibuat :</td>
					<td class="top">Disetujui :</td>
					<td class="top">Diterima :</td>
				</tr>
				<tr>
					<td class="bottom">NURS</td>
					<td class="bottom"> - </td>
					<td class="bottom"> - </td>
				</tr>
			</table>
			';
			// print_r($html);
			// exit();
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4-L',
				'file_name'     => 'FAKTUR MUTASI BARANG',
				'title'         => 'FAKTUR MUTASI BARANG',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}
	public function print_nota($value = '')
	{
		$user = $this->session->userdata();
		if ($wp_id = $this->session->userdat('wajibpajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$data = $this->db->where('pembayaran_id', $value)
			->get('v_pos_pembayaran')
			->row_array();
		if ($wp_id = $this->session->userdat('wajibpajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$detail = $this->db->where('pembayaran_detail_parent', $value)
			->get('v_pos_pembayaran_detail')
			->result_array();
		// $jurnal = $this->db->select('akun_kode, akun_nama, jurnal_umum_nobukti, jurnal_umum_detail_debit, jurnal_umum_detail_kredit')
		// 	->where('jurnal_umum_reference_id', $value)
		// 	->order_by('jurnal_umum_detail_no', 'ASC')
		// 	->get('v_ak_jurnal_umum_detail_laporan')
		// 	->result_array();
		$jurnal = [];
		$hari = date('D', strtotime($data['pembayaran_tanggal']));
		$namahari = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		$tgl = phpChgDate(date('Y-m-d', strtotime($data['pembayaran_tanggal'])));
		$harini = date('d F Y');
		$huruf = $this->terbilang($data['pembayaran_bayar']);

		$header = '
			<div style="width:89.3%; border:1px solid #000;padding:5px;margin-right:50px;margin-left:17px">
				<table cellpadding="0" cellspacing="0" align="left"  border="0" class="" style="display:inline-table;border:1px solid black;width:9.4cm!important" rotate="-90.0deg">
					    <tr>
					      <td style="text-align:center; line-height:14px;padding:4px;" >
					        <p style="font-size:15px;font-weight:bold;">
					        ' . $user['toko_nama'] . '
					        </p>
					      </td>
					    </tr>
				</table>
			</div>

			<div style="margin-left:96px; margin-top:-362px;">
			  <table cellspacing="0" style="width:91%;border:1px solid black; line-height:16px">
			  	<tr>
			  		<td style="width:25%;padding:3px;font-size:11px;border-right:1px solid black">No. : ' . $data['pembayaran_kode'] . ' </td>
			  		<td style="padding:3px;font-size:11px;border-right:1px solid black;text-align:center"><b>BUKTI KAS KELUAR</b></td>
			  	</tr>
			  </table>
			  <table cellspacing="0" style="width:91%" cellpadding="4">
			  	<tr>
			  		<td style="padding-top:11px;width:20%;font-size:11px;border-left:1px solid black;">Dibayarkan kepada</td>
			  		<td style="padding-top:11px;width:3%;font-size:11px;">:</td>
			  		<td style="padding-top:11px;width:67%;font-size:11px;" colspan="2">' . $data['supplier_nama'] . '</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Banyaknya Uang</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:67%;font-size:11px;" colspan="2">' . $huruf . ' Rupiah</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Untuk Pembayaran</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:67%;font-size:11px;" colspan="2"> Pembayaran barang dagangan No. Faktur :</td></tr>';
		foreach ($detail as $key => $value) {
			// if($key==0){
			// 	$header .= $value['pembayaran_detail_pembelian_kode'].', <i style="float:right">JT.'.date('d/m/Y',strtotime($value['pembayaran_detail_jatuh_tempo'])).'</i></td>';				  		
			// }else{
			// }
			// <td style="border-left:1px solid black;" colspan="2"></td>
			$header .= '<tr>
	  					<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;">' . $value['pembelian_kode'] . ' (<i style="float:right;font-size:11px;">JT.' . date('d/m/Y', strtotime($value['pembayaran_detail_jatuh_tempo'])) . '</i>) &nbsp; : ' . number_format($value['pembayaran_detail_tagihan']) . '</td>
		  				<td style="font-size:11px; width:21%">' . ($value['pembayaran_detail_potongan'] ? '( Potongan ) : ' . number_format($value['pembayaran_detail_potongan']) : '') . '</td> 
		  				<td style="font-size:11px;">' . ($value['pembayaran_detail_retur'] ? '( Retur ) : ' . number_format($value['pembayaran_detail_retur']) : '') . '</td>
		
		  				</tr>';
			// if($value['pembayaran_detail_potongan'] || $value['pembayaran_detail_retur']){
			// 	$pot = $value['pembayaran_detail_potongan']+$value['pembayaran_detail_retur'];
			// 	$header .= '<tr>
			// 		<td style="border-left:1px solid black;" colspan="2"></td>
			// 		<td style="font-size:11px;text-align:right">Potongan</td>
			// 		<td style="font-size:11px;">: ('.number_format($pot).')</td>
			// 	</tr>';	
			// }

		}
		if ($data['akun_parent'] == '1112') {
			$header .= '<tr>
		  				<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;border-right:">Transfer ' . $data['akun_nama'] . ', No. Ref.' . ($data['pembayaran_referensi'] ? $data['pembayaran_referensi'] : '-') . '</td>
		  				<td style="font-size:11px;"></td>
		  			</tr>';
		}
		$header .= '<tr>
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;">' . $data['pembayaran_keterangan'] . '</td>
	  			</tr>';
		$header .= '<tr >
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;"></td>
	  			</tr>';


		$header .= '</table>
			  <table cellspacing="0" style="width:92%" cellpadding="4">
			  <tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Terbilang</td>
			  		<td style="width:5%;font-size:11px;">:</td>
			  		<td style="width:18%;font-size:11px;border:1px solid black;" colspan="2"> Rp. ' . number_format($data['pembayaran_bayar'], 0, '', '.') . '</td>
			  		<td style="width:47%;font-size:11px;"></td>
			  	</tr>
			  </table>';
		$shadow = '<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				</table>';
		$footer = '		
				<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Malang, </td>
				  		<td colspan="2" style="width:30%;font-size:11px;border-top:1px solid black;text-align:left">' . $tgl . '</td>
				  	</tr>
				  	<tr>
				  		<!--<td style="width:10%;font-size:11px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Analis</td>
				  		<td style="width:10%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Rek.</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Debet</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Kredit</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Mengetahui,</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Dibayar</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Diterima</td>
				  	</tr>
				  	<tr>
						<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[0]['akun_kode']) ? $jurnal[0]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[0]['jurnal_umum_detail_debit']) ? number_format($jurnal[0]['jurnal_umum_detail_debit']) : '<span style="color:#fff"></span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[0]['jurnal_umum_detail_kredit']) ? number_format($jurnal[0]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center">Kasir</td>
				  		<td style="width:15%;font-size:11px;text-align:center">oleh</td>
				  	</tr>
				  	<tr>
						<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[1]['akun_kode']) ? $jurnal[1]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[1]['jurnal_umum_detail_debit']) ? number_format($jurnal[1]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[1]['jurnal_umum_detail_kredit']) ? number_format($jurnal[1]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
						<!--<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[2]['akun_kode']) ? $jurnal[2]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[2]['jurnal_umum_detail_debit']) ? number_format($jurnal[2]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[2]['jurnal_umum_detail_kredit']) ? number_format($jurnal[2]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
						<!--<td style="width:10%;font-size:11px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">' . (isset($jurnal[3]['akun_kode']) ? $jurnal[3]['akun_kode'] : '') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[3]['jurnal_umum_detail_debit']) ? number_format($jurnal[3]['jurnal_umum_detail_debit']) : '<span style="color:#fff">hello!</span>') . '</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">' . (isset($jurnal[3]['jurnal_umum_detail_kredit']) ? number_format($jurnal[3]['jurnal_umum_detail_kredit']) : '') . '</td>-->
							<td colspan="4" style="border-bottom:1px solid black;border-left:1px solid black; border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;text-align:center"></td>
				  	</tr>
				  </table>
				</div>
			';
		// print_r('<pre>');print_r($header . $shadow . $footer);print_r('</pre>');exit;
		createPdf(array(
			'data'          => $header . $shadow . $footer,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'Kwitansi',
			'title'         => 'Kwitansi',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '5 5 0 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10'
		));
	}
	public function print_tanda_terima($value = '')
	{
		$data = varPost();
		$user = $this->session->userdata();
		if ($wp_id = $this->session->userdat('wajibpajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$pembayaran = $this->db->where('pembayaran_id', $data['pembayaran_id'])
			->get('v_pos_pembayaran')
			->row_array();
		// print_r($pembayaran);exit;
		// print_r('<pre>');print_r($pembayaran);print_r('</pre>');exit;
		if ($wp_id = $this->session->userdat('wajibpajak_id')) {
			$this->db->where('wajibpajak_id', $wp_id);
		}
		$detail = $this->db->where('pembayaran_detail_parent', $data['pembayaran_id'])
			->get('v_pos_pembayaran_detail')
			->result_array();
		$html = '
		<style>
			*{
				font-size:10px;
			}
			h3{
				font-size:15px
			}
			h1{
				font-size:18px; 
			}

			
		</style>
		<div style="border:1px solid #000; padding: 5px">
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:10%"><img src="' . $_ENV['PAJAK_URL'] . $user['toko']['toko_logo'] . '" alt="Logo" width="100"></td>
					<td colspan="4" style="vertical-align:top;line-height:20px; padding:6px 4px; width:88%"><h3>' . $user['toko_nama'] . '</h3><p>-</p><p>-</p></td>
				</tr>
				<tr>
					<td colspan="5" style="text-align:center;"></td>
				</tr>
				<hr style="margin-bottom:0;" border="2"><hr style="margin-top:2px">
				<tr>
					<td colspan="5" style="text-align:center;"><h1>TANDA TERIMA</h1></td>
				</tr>
				<tr>
					<td colspan="4" style="width:80%!important"></td>
					<td style="width:20%">Tangal. ' . date('d/m/Y', strtotime($pembayaran['pembayaran_tanggal'])) . '</td>
				</tr>
			</table>
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:20%!important" >Telah diterima dari </td>
					<td style="width:2%">:</td>
					<td style="width:75%!important" colspan="2">' . $pembayaran['supplier_nama'] . '</td>
				</tr>
				<tr>
					<td >Berupa </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2">Invoice No. ' . $pembayaran['pembayaran_invoice'] . '</td>
				</tr>
				<tr>
					<td >Keperluan </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2"> Pembayaran Faktur No. </td>';
		foreach ($detail as $key => $value) {
			if ($key > 0) {
				$html .= '<tr><td colspan="3"></td>';
			}
			$html .= '<td>' . $value['pembelian_faktur'] . ', JT. ' . date('d/m/Y', strtotime($value['pembelian_jatuh_tempo'])) . ', Senilai Rp.' . number_format($value['pembayaran_detail_tagihan'], 0, ',', '.') . '</td></tr>';
		}
		$html .= '
				<tr>
					<td style="padding-bottom:50px">Keterangan </td>
					<td style="padding-bottom:50px">:</td>
					<td style="width:75%!important;padding-bottom:50px" colspan="2">' . $pembayaran['pembayaran_keterangan'] . '</td>
				</tr>
				<tr>
					<td style="text-align:center;padding-bottom:50px">Pengirim</td>
					<td colspan="2"></td>
					<td style="text-align:center;padding-bottom:50px">Penerima</td>
				</tr>
				<tr>
					<td style="text-align:center">' . ($pembayaran['pembayaran_sales'] ? $pembayaran['pembayaran_sales'] : '-') . '</td>
					<td colspan="2"></td>
					<td style="text-align:center">' . ($pembayaran['pegawai_nama'] ? $pembayaran['pegawai_nama'] : '-') . '</td>
				</tr>
			</table>
		</div>';
		// echo $html;exit;

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A5-L',
			'file_name'     => 'Tanda Terima',
			'title'         => 'Tanda Terima',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '5 5 0 5',
			'font_face'     => 'sans_fonts',
			'font_size'     => '10'
		));
	}
}

/* End of file Pembayaran.php */
/* Location: ./application/modules/Pembayaran/controllers/Pembayaran.php */