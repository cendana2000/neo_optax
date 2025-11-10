<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Returpenjualan extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'returpenjualanModel' 		=> 'returpenjualan',
			'returpenjualandetailModel' => 'returpenjualandetail',
			'transaksipenjualan/TransaksipenjualanModel' 		=> 'transaksipenjualan',
			'transaksipenjualan/TransaksipenjualandetailModel' 	=> 'transaksipenjualandetail',
			'stokkartu/stokkartuModel' 		=> 'stokkartu',
		));
	}

	public function index()
	{
		$filter = array(
			"retur_penjualan_deleted_at" => null
		);
		$this->response(
			$this->select_dt(varPost(), 'returpenjualan', 'table', false, array(), $filter)
		);
	}

	public function filter_date()
	{
		$var = varPost();
		$filter = array(
			"retur_penjualan_deleted_at" => null,
		);
		if(!empty($var['tanggal1'])){
			$filter['retur_penjualan_tanggal BETWEEN \'' . $var['tanggal1'] . '\' AND \'' . $var['tanggal2'] . '\' '] = null;
		}
		$this->response(
			$this->select_dt(varPost(), 'returpenjualan', 'table', false, $filter)
		);
	}

	public function table_detail_barang()
	{
		$var = varPost();
		$penjualan = $this->transaksipenjualan->read(array(
			'penjualan_id' => $var['jual']['penjualan_detail_parent']
		));

		if (intval($penjualan['penjualan_total_bayar']) > 0) {
			$operation = array(
				'iTotalRecords' => 0,
				'iTotalDisplayRecords' => 0,
				'sEcho' => 0,
				'sColumns' => '',
				'aaData' => [],
			);
		} else {
			// die(print_r(varPost('jual')['penjualan_detail_parent']));
			// EXISTS (SELECT 1 FROM pos_penjualan pp WHERE penjualan_id = penjualan_detail_parent AND penjualan_metode = 'K')
			$filter = varPost('jual');

			// $filterquery = "(SELECT pp.penjualan_id FROM pos_penjualan pp WHERE penjualan_id = penjualan_detail_parent AND penjualan_metode = 'K')";
			$operation = $this->select_dt(varPost(), 'transaksipenjualandetail', 'table', true, $filter);
			// print_r($operation);die();
			$queryreturdetail = $this->db->query("SELECT * FROM pos_retur_penjualan_detail prpdb 
			INNER JOIN pos_retur_penjualan_barang prpb 
			ON prpdb.retur_penjualan_detail_parent = prpb.retur_penjualan_id 
			WHERE prpb.retur_penjualan_penjualan_id = '" . varPost('jual')['penjualan_detail_parent'] . "'")->result_array();

			// print_r($this->select_dt(varPost(), 'transaksipenjualandetail', 'table'));die();

			foreach ($operation['aaData'] as $key => $value) {
				$operation['aaData'][$key]['retur_penjualan_detail_retur_qty_barang'] = 0;
				$operation['aaData'][$key]['final_penjualan_detail_qty'] = $operation['aaData'][$key]['penjualan_detail_qty'];
			}

			if (count($queryreturdetail) > 0) {
				foreach ($queryreturdetail as $returdetail) {
					$opindex = array_search($returdetail['retur_penjualan_detail_detail_id'], array_column($operation['aaData'], 'penjualan_detail_id'));
					$operation['aaData'][$opindex]['retur_penjualan_detail_retur_qty_barang'] = $returdetail['retur_penjualan_detail_retur_qty_barang'];
					$final_penjualan = $operation['aaData'][$opindex]['penjualan_detail_qty'] - $returdetail['retur_penjualan_detail_retur_qty_barang'];
					$operation['aaData'][$opindex]['final_penjualan_detail_qty'] = $final_penjualan;
				}
			}
		}

		$this->response(
			$operation
		);
	}

	function read_detail($value = '')
	{
		$operation = $this->transaksipenjualandetail->read(varPost());

		// die(print_r($operation));

		$queryreturdetail = $this->db->query("SELECT * FROM pos_retur_penjualan_detail prpd 
		WHERE prpd.retur_penjualan_detail_detail_id = '" . varPost('penjualan_detail_id') . "'")->result_array();

		// die(print_r($queryreturdetail));

		if (count($queryreturdetail) > 0) {
			$operation['final_penjualan_detail_qty'] = $operation['penjualan_detail_qty'];
			foreach ($queryreturdetail as $returdetail) {
				$operation['retur_penjualan_detail_retur_qty_barang'] = $returdetail['retur_penjualan_detail_retur_qty_barang'];
				$operation['final_penjualan_detail_qty'] -= $returdetail['retur_penjualan_detail_retur_qty_barang'];
			}
		} else {
			$operation['retur_penjualan_detail_retur_qty_barang'] = '0';
			$operation['final_penjualan_detail_qty'] = $operation['penjualan_detail_qty'];
		}

		$this->response($operation);
	}

	public function select_penjualan($value = '')
	{
		$data = varPost();
		// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$total = $this->db->query('SELECT count(penjualan_id) total FROM v_pos_penjualan WHERE concat(penjualan_tanggal,penjualan_kode, COALESCE(customer_nama, \'\')) like \'%' . $data['q'] . '%\'')->result_array();
		$return = $this->db->query('SELECT penjualan_id as id, concat(TO_CHAR(penjualan_tanggal, \'DD-MM-YYYY\'),\'/\', penjualan_kode, COALESCE(concat(\' - \', customer_nama), \'\')) as text FROM v_pos_penjualan 
		WHERE concat(penjualan_tanggal, penjualan_kode, COALESCE(customer_nama, \'\')) like \'%' . $data['q'] . '%\' 
		AND penjualan_metode = \'K\' order by penjualan_created desc 
		LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total'], 'qr' => $this->db->last_query()));
	}

	function read($value = '')
	{
		$this->response($this->returpenjualan->read(varPost()));
	}

	public function store()
	{
		// Transactional
		$this->db->trans_begin();

		$data = varPost();
		$data['retur_penjualan_pegawai_nama'] = $this->session->userdata('user_nama');

		// Change string to date retur_penjualan_tanggal
		$retur_penjualan_tanggal = str_replace('/', '-', $data['retur_penjualan_tanggal']);
		$data['retur_penjualan_tanggal'] = date("Y-m-d H:i:s", strtotime($retur_penjualan_tanggal));


		$up_beli = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur', 'COALESCE(penjualan_total_retur, 0) + ' . $data['retur_penjualan_total'], false)
			// ->set('penjualan_total_harga', 'penjualan_total_harga - ' . $data['retur_penjualan_total'], false)
			// ->set('penjualan_total_grand', 'penjualan_total_grand - ' . $data['retur_penjualan_total'], false)
			->set('penjualan_bayar_sisa', 'penjualan_bayar_sisa - ' . $data['retur_penjualan_total'], false)
			->update('pos_penjualan');

		foreach ($data['retur_penjualan_detail_detail_id'] as $key => $val) {
			// $datapenjualandetail = array(
			// 	'penjualan_detail_retur' => $data['retur_penjualan_detail_retur_qty'][$key],
			// );
			// $this->transaksipenjualandetail->update($val, $datapenjualandetail);
			$this->db->where('penjualan_detail_id', $val)
				->set('penjualan_detail_retur', 'COALESCE(penjualan_detail_retur, 0) + ' . $data['retur_penjualan_detail_retur_qty'][$key], false)
				->update('pos_penjualan_detail');
		}
		// print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;

		$data['retur_penjualan_kode'] = $this->returpenjualan->gen_kode_penjualan();
		$data['retur_penjualan_pegawai_id'] = $this->session->userdata('user_id');
		$data['retur_penjualan_created'] = date('Y-m-d H:i:s');
		$data['retur_penjualan_aktif'] = '1';

		// print_r($data);die();
		$operation = $this->returpenjualan->insert(gen_uuid($this->returpenjualan->get_table()), $data, function ($res) use ($data) {
			$detail = [];
			$n = 0;
			foreach ($data['retur_penjualan_detail_barang_id'] as $key => $value) {
				$detail = [
					'retur_penjualan_detail_id' 		=> gen_uuid($this->returpenjualandetail->get_table()),
					'retur_penjualan_detail_parent' 	=> $res['record']['retur_penjualan_id'],
					'retur_penjualan_detail_barang_id'	=> $value,
					'retur_penjualan_detail_detail_id' 	=> $data['retur_penjualan_detail_detail_id'][$key],
					'retur_penjualan_detail_satuan_id' 	=> $data['retur_penjualan_detail_satuan_id'][$key],
					'retur_penjualan_detail_harga' 		=> $data['retur_penjualan_detail_harga'][$key],
					'retur_penjualan_detail_qty' 		=> $data['retur_penjualan_detail_qty'][$key],
					'retur_penjualan_detail_retur_qty' 	=> $data['retur_penjualan_detail_retur_qty'][$key],
					'retur_penjualan_detail_retur_qty_barang' 	=> $data['retur_penjualan_detail_retur_qty_barang'][$key],
					'retur_penjualan_detail_sisa_qty' 	=> $data['retur_penjualan_detail_sisa_qty'][$key],
					'retur_penjualan_detail_jumlah' 	=> $data['retur_penjualan_detail_jumlah'][$key],
					'retur_penjualan_detail_tanggal' 	=> $data['retur_penjualan_tanggal'],
					'retur_penjualan_detail_order' 		=> $n,
				];

				$det_opr = $this->db->insert('pos_retur_penjualan_detail', $detail);
				$kartu = $this->stokkartu->insert_kartu([
					'kartu_id' 			=> $detail['retur_penjualan_detail_id'],
					'kartu_tanggal' 	=> $data['retur_penjualan_tanggal'],
					'kartu_barang_id' 	=> $data['retur_penjualan_detail_barang_id'][$key],
					'kartu_satuan_id' 	=> $data['retur_penjualan_detail_satuan_id'][$key],
					'kartu_stok_masuk' 	=> $data['retur_penjualan_detail_retur_qty_barang'][$key],
					'kartu_stok_keluar' => 0,
					'kartu_transaksi' 	=> 'Retur Penjualan',
					'kartu_keterangan' 	=> 'ON Insert',
					'kartu_harga'		=> $data['retur_penjualan_detail_harga'][$key],
					'kartu_transaksi_kode' => $data['retur_penjualan_kode'],
					'kartu_user' 		=> $data['retur_penjualan_pegawai_id'],
					'kartu_created_at' 	=> date('Y-m-d H:i:s'),
				], 'RJ');

				// SET BARANG TO AVAILABLE
				$barang_id = $data['retur_penjualan_detail_barang_id'][$key];
				$dc_barang = $this->db->get_where('v_pos_barang', ['barang_id' => $barang_id])->row_array();
				if ($dc_barang['jenis_include_stok'] == 2) {
					$this->db->set('barang_aktif', 2);
					$this->db->where('barang_id', $barang_id);
					$this->db->update('pos_barang');
				}

				// if(!$det_opr) $error[] = ['cannot insert dt'.$value => $detail];
				// else{
				/*
					$up_jual_detail = $this->db->where('penjualan_detail_id', $data['retur_penjualan_detail_detail_id'][$key])
					->set('penjualan_detail_retur','penjualan_detail_retur+'.$data['retur_penjualan_detail_qty'][$key], FALSE)
					->update('pos_penjualan_detail');
					*/
				// if(!$kartu) $error[] = [$kartu, $data['retur_penjualan_kode'], $value];
				// }
				$n++;
				if (!$det_opr['success']) $res['res'][] = $det_opr;
			}

			/*
			$penjualanexist = $this->transaksipenjualan->read(array('penjualan_id' => $data['retur_penjualan_penjualan_id']));
			if($penjualanexist['penjualan_total_retur'] == null){
				$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
				->set('penjualan_total_retur',$data['retur_penjualan_total_qty'])
				->update('pos_penjualan');
			}else{
				$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
				->set('penjualan_total_retur','penjualan_total_retur+'.$data['retur_penjualan_total_qty'], FALSE)
				->update('pos_penjualan');
			}
			if($penjualanexist['penjualan_bayar_sisa'] == null){
				$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
				->set('penjualan_bayar_sisa', '0')
				->update('pos_penjualan');
			}else{
				$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
				->set('penjualan_bayar_sisa', 'penjualan_bayar_sisa-'.$data['retur_penjualan_total'], FALSE)
				->update('pos_penjualan');
			}
			*/

			// print_r($this->db->last_query());
			// print_r($this->db->last_query());die();
		});

		// END Transactional
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();

		// Change string to date retur_penjualan_tanggal
		$retur_penjualan_tanggal = str_replace('/', '-', $data['retur_penjualan_tanggal']);
		$data['retur_penjualan_tanggal'] = date("Y-m-d H:i:s", strtotime($retur_penjualan_tanggal));

		$returpembelian = $this->db->query("SELECT * FROM pos_retur_penjualan_barang prpb 
		LEFT JOIN pos_penjualan pb ON prpb.retur_penjualan_penjualan_id = pb.penjualan_id 
		WHERE retur_penjualan_id = '" . $data['retur_penjualan_id'] . "'")->row_array();

		// $realgrand = $returpembelian['penjualan_total_grand'] + $returpembelian['penjualan_total_retur'];
		// $newgrand = $realgrand - $data['retur_penjualan_total'];

		// print_r('<pre>');print_r($data);print_r('</pre>');exit;
		$setsisa = $returpembelian['penjualan_total_grand'] - $data['retur_penjualan_total'];

		$up_beli = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur', $data['retur_penjualan_total'], false)
			// ->set('penjualan_total_harga', $newgrand, false)
			// ->set('penjualan_total_grand', $newgrand, false)
			->set('penjualan_bayar_sisa', $setsisa, false)
			->update('pos_penjualan');

		foreach ($data['retur_penjualan_detail_detail_id'] as $key => $val) {
			$returdetail = $this->returpenjualandetail->read($data['retur_penjualan_detail_id'][$key]);
			$this->db->where('penjualan_detail_id', $val)
				->set('penjualan_detail_retur', '(COALESCE(penjualan_detail_retur, 0) - ' . $returdetail['retur_penjualan_detail_retur_qty'] . ') + ' . $data['retur_penjualan_detail_retur_qty'][$key], false)
				->update('pos_penjualan_detail');
		}

		// $last_retur =$this->returpenjualan->read($data['retur_penjualan_id']);
		// $last_retur_detail =$this->returpenjualan->select(['filters_static'=>['retur_penjualan_id' =>$data['retur_penjualan_id']]]);
		$operation = $this->returpenjualan->update($data['retur_penjualan_id'], $data, function (&$res) use ($data) {
			$detail = $id_detail = [];
			$last_detail = $this->returpenjualandetail->select(array('filters_static' => array('retur_penjualan_detail_parent' => $data['retur_penjualan_id']), 'sort_static' => 'retur_penjualan_detail_order asc'))['data'];
			$delete = $last_detail;
			$n = 0;
			foreach ($data['retur_penjualan_detail_barang_id'] as $key => $value) {
				$detail = [
					'retur_penjualan_detail_id' 		=> $data['retur_penjualan_detail_id'][$key],
					'retur_penjualan_detail_parent' 	=> $res['record']['retur_penjualan_id'],
					'retur_penjualan_detail_barang_id' 	=> $value,
					'retur_penjualan_detail_detail_id' 	=> $data['retur_penjualan_detail_detail_id'][$key],
					'retur_penjualan_detail_satuan' 	=> $data['retur_penjualan_detail_satuan'][$key],
					'retur_penjualan_detail_harga' 		=> $data['retur_penjualan_detail_harga'][$key],
					'retur_penjualan_detail_qty' 		=> $data['retur_penjualan_detail_qty'][$key],
					'retur_penjualan_detail_retur_qty' 	=> $data['retur_penjualan_detail_retur_qty'][$key],
					'retur_penjualan_detail_retur_qty_barang' 	=> $data['retur_penjualan_detail_retur_qty_barang'][$key],
					'retur_penjualan_detail_sisa_qty' 	=> $data['retur_penjualan_detail_sisa_qty'][$key],
					'retur_penjualan_detail_jumlah' 	=> $data['retur_penjualan_detail_jumlah'][$key],
					'retur_penjualan_detail_tanggal' 	=> $data['retur_penjualan_tanggal'],
					'retur_penjualan_detail_order' 		=> $n,
				];
				$n++;
				$kartu = [
					'kartu_id' 			=> $detail['retur_penjualan_detail_id'],
					'kartu_tanggal' 	=> $data['retur_penjualan_tanggal'],
					'kartu_barang_id' 	=> $detail['retur_penjualan_detail_barang_id'],
					'kartu_satuan_id' 	=> $detail['retur_penjualan_detail_satuan'],
					'kartu_stok_masuk' 	=> $detail['retur_penjualan_detail_retur_qty_barang'],
					'kartu_transaksi' 	=> 'Retur Penjualan',
					'kartu_keterangan' 	=> 'ON Updated',
					'kartu_harga'		=> $detail['retur_penjualan_detail_harga'],
					'kartu_transaksi_kode' => $data['retur_penjualan_kode'],
					'kartu_user' 		=> $data['retur_penjualan_user'],
					'kartu_created_at' 	=> date('Y-m-d H:i:s'),
				];
				foreach ($last_detail as $i => $v) {
					if ($v['retur_penjualan_detail_id'] == $data['retur_penjualan_detail_id'][$key]) unset($delete[$i]);
				}
				$res_detail = $this->returpenjualandetail->update($data['retur_penjualan_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$detail_id = gen_uuid($this->returpenjualandetail->get_table());
					$kartu['kartu_id'] = $detail['retur_penjualan_detail_id'] = $detail_id;
					$kartu['kartu_stok_keluar'] = 0;
					$kartu['kartu_keterangan'] = 'Insert On Updated';
					$res_detail = $this->returpenjualandetail->insert($detail_id, $detail);
					if ($res_detail['success']) {
						$kartu = $this->stokkartu->insert_kartu($kartu, 'RJ');
						$id_detail[] = $res_detail['id'];
					}
				} else {
					$kartu = $this->stokkartu->update_kartu($kartu, 'RJ');
					$id_detail[] = $res_detail['id'];
				}
				// $id = implode(', ', $id_detail);
				// $res['id_detail'] = $id;

				foreach ($delete as $n => $value) {
					print_r('<pre>');
					print_r('indelete');
					print_r('</pre>');
					$del = $this->returpenjualandetail->delete($value['retur_penjualan_detail_id']);
					if ($del['success']) {
						$kartu = [
							'kartu_id' 				=> $value['retur_penjualan_detail_id'],
							'kartu_stok_masuk'	 	=> 0,
							'kartu_transaksi' 		=> 'Retur Penjualan',
							'kartu_keterangan' 		=> 'Deleted On Updated',
						];
						// $this->db->delete('pos_penjualan_detail', array('retur_penjualan_detail_id' => $value['retur_penjualan_detail_id']));
						$this->stokkartu->update_kartu($kartu, 'RJ');
					}
				}
			}
			/*
			$selisih = $data['retur_penjualan_total']-$last_retur['retur_penjualan_total'];
			$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur','penjualan_total_retur+'.$selisih, FALSE)
			->update('pos_penjualan');
			*/
		});
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->returpenjualandetail->select(array('filters_static' => $data)));
	}

	public function destroy()
	{
		$data = varPost();

		$returpenjualan = $this->db->query("SELECT * FROM pos_retur_penjualan_barang prpb 
		LEFT JOIN pos_penjualan pb ON prpb.retur_penjualan_penjualan_id = pb.penjualan_id 
		WHERE retur_penjualan_id = '" . $data['retur_penjualan_id'] . "'")->row_array();

		$up_beli = $this->db->where('penjualan_id', $returpenjualan['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur', 'penjualan_total_retur - ' . $returpenjualan['penjualan_total_retur'], false)
			// ->set('penjualan_total_harga', 'penjualan_total_harga + ' . $returpenjualan['penjualan_total_retur'], false)
			// ->set('penjualan_total_grand', 'penjualan_total_grand + ' . $returpenjualan['penjualan_total_retur'], false)
			->set('penjualan_bayar_sisa', 'penjualan_bayar_sisa + ' . $returpenjualan['penjualan_total_retur'], false)
			->update('pos_penjualan');

		$datapembeliandetail = array(
			'penjualan_detail_retur' => 0,
		);
		$this->transaksipenjualandetail->update(array(
			'penjualan_detail_parent' => $returpenjualan['retur_penjualan_penjualan_id']
		), $datapembeliandetail);

		$operation = $this->returpenjualan->delete(varPost('id', varExist($data, $this->returpenjualan->get_primary(true))));
		$last_detail = $this->returpenjualandetail->select(array('filters_static' => array('retur_penjualan_detail_parent' => $data['retur_penjualan_id']), 'sort_static' => 'retur_penjualan_detail_order asc'))['data'];
		$delete = $last_detail;
		foreach ($last_detail as $key => $value) {
			$kartu = [
				'kartu_id' 				=> $value['retur_penjualan_detail_id'],
				'kartu_barang_id'		=> $value['retur_penjualan_detail_barang_id'],
				'kartu_stok_masuk' 		=> 0,
				'kartu_transaksi' 		=> 'Retur Penjualan',
				'kartu_keterangan' 		=> 'Deleted On Updated',
			];
			$this->stokkartu->update_kartu($kartu, 'RJ');
		}
		$operation = $this->returpenjualandetail->delete(array('retur_penjualan_detail_parent' => $data['retur_penjualan_id']));
		$this->response($operation);
	}

	public function tprint($id)
	{
		die(json_encode($this->session->userdata()));

		$data = varPost();
		$user = $this->session->userdata();
		$jual = $this->db->select('retur_penjualan_kode, retur_penjualan_tanggal, retur_penjualan_nilai, retur_penjualan_total_qty, retur_penjualan_total_item, retur_penjualan_total, customer_nama, customer_alamat,penjualan_kode, pegawai_nama')
			->where('retur_penjualan_id', $id)
			->from('v_pos_retur_penjualan')->get()->result_array();
		$html = '';
		if ($jual) {
			$jual = $jual[0];
			$detail = $this->db->select('retur_penjualan_detail_retur_qty, retur_penjualan_detail_harga, barang_kode, barang_nama, retur_penjualan_detail_jumlah')
				->where('retur_penjualan_detail_parent', $id)
				->order_by('retur_penjualan_detail_order', 'asc')
				->from('v_pos_retur_penjualan_detail')->get()->result_array();
			$nprint = 1;

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
			<div class="print">';
			for ($i = 1; $i <= $nprint; $i++) {
				if ($i > 1) $html .= $break;
				$html .= '<h1 style="font-size:13px;text-align:center;margin-bottom:0">' . $user['toko_nama'] . '</h1>
				
					<hr style="border-top: 1px dashed black;">
					<h2 style="text-align:center;font-size:13px;">* NOTA RETUR PENJUALAN *</h2>
					<table>
						<tr>
							<td style="margin-left:400px;">Tgl</td>
							<td>:</td>
							<td>' . date('d/m/Y', strtotime($jual['retur_penjualan_tanggal'])) . '</td>
							<td>Opt </td>
							<td>:</td>
							<td style="text-transform:uppercase">' . $jual['pegawai_nama'] . '</td>
						</tr>
						<tr>
							<td>Nota Retur</td>
							<td>:</td>
							<td>' . $jual['retur_penjualan_kode'] . '</td>
							<td colspan="4">' . date('H:i:s', strtotime($jual['retur_penjualan_created'])) . '</td>
						</tr>
						<tr>
							<td>Nota Jual</td>
							<td>:</td>
							<td>' . $jual['penjualan_kode'] . '</td>
							<td colspan="4"></td>
						</tr>
					</table>';
				$html .= '
					<hr style="border-top: 1px dashed;margin:0">
					<table>
				';
				$totalpotongan = 0;
				foreach ($detail as $key => $value) {
					$html .= '<tr>
						<td class="text-left">' . substr($value['barang_nama'], 0, 13) . '</td>
						<td class="text-left">' . $value['retur_penjualan_detail_retur_qty'] . ' x</td>
						<td class="text-right">' . number_format($value['retur_penjualan_detail_harga']) . '</td>
						<td class="text-right">' . number_format($value['retur_penjualan_detail_jumlah']) . '</td>
					</tr>';
				}
				$html .= '
					</table>
					<table>
						<hr style="border-top: 1px dashed black;width:200px;" align="right">
						<tr>
								<td class="text-right" colspan="3" style="text-transform: capitalize;">Total  :</td>
								<td class="text-right">' . number_format($jual['retur_penjualan_total']) . '</td>
						</tr>';

				$html .= '
					</table>
					<hr style="border-top: 1px dashed black;">';
				$html .= '<table>';
				if ($jual['anggota_nama']) {
					$html .= '<tr>
										<td style="text-transform: capitalize;width:10%">Group</td>
										<td>:</td>
										<td colspan="4"> (' . $jual['grup_gaji_kode'] . ') ' . $jual['grup_gaji_nama'] . '</td>
								</tr>
								<tr>
										<td>Alamat</td>
										<td>:</td>
										<td colspan="4"> ' . $jual['anggota_kota'] . ' </td>
								</tr>
								<tr>
										<td>NIP</td>
										<td>:</td>
										<td colspan="4"> ' . $jual['anggota_nip'] . ' </td>
								</tr>
						</table>
						<hr style="border-top: 1px dashed black;">';
					$html .= '<table>              
							<tr>
									<td class="text-left" style="text-transform: capitalize;">' . (($jual['anggota_nama']) ? 'Nasabah :' . $jual['anggota_kode'] : '') . ' </td>
									<td class="" style="text-transform: capitalize;text-align:center">' . (($jual['anggota_nama']) ? 'Kasir ' : '') . '</td>
							</tr>
							<tr>
								<td colspan="2"><p></p></td>
							</tr>
							<tr>
								<td class="text-left">' . (($jual['anggota_nama']) ? '(' . $jual['anggota_nama'] . ')' : '') . '</td>
								<td class="text-right">' . (($jual['anggota_nama']) ? '(' . $jual['pegawai_nama'] . ')' : ' ') . '</td>
							</tr>
						</table>
						<hr style="border-top: 1px dashed black;">';
				} else {
					$html .= '</tbody>
						</table><hr style="border-top: 1px dashed black;">';
				}
				$html .= '
					<table>       
						<tr>
							<td style="font-size:11px!important">*Terimakasih atas kunjungan anda</td>
						</tr>
					</table>';
			}
			$html .= '</div>';
		}
		if (isset($data['tjson'])) $this->response(array('tprint' => $html));
		// print_r($html);die();
		// return $html;

		createPdf(array(
			'data'          => $html,
			'json'          => true,
			'paper_size'    => 'A4',
			'file_name'     => 'NOTA RETUR PENJUALAN',
			'title'         => 'NOTA RETUR PENJUALAN',
			'stylesheet'    => './assets/laporan/print.css',
			'margin'        => '10 5 10 5',
			// 'font_face'     => 'cour',
			'font_size'     => '10',
		));
	}

	public function cetak($value = '')
	{
		if ($value) {
			$user = $this->session->userdata();
			$data = $this->db->where('retur_penjualan_id', $value)
				->get('v_pos_retur_penjualan')
				->row_array();
			$detail = $this->db->where('retur_penjualan_detail_parent', $value)
				->get('v_pos_retur_penjualan_detail')
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
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
							<h4> NOTA RETUR PENJUALAN BARANG </h4><br>
					</td>
				</tr>	
				<tr>
					<td width="50%">
						<table>
							<tr>
								<td>Tgl</td>
								<td>:</td>
								<td>' . ($data['retur_penjualan_tanggal'] ? date("d/m/Y", strtotime($data['retur_penjualan_tanggal']))  : "-") . '</td>
							</tr>
							<tr>
								<td>Nota Retur</td>
								<td>:</td>
								<td>' . ($data['retur_penjualan_kode'] ? $data['retur_penjualan_kode'] : "-") . '</td>
							</tr>
							<tr>
								<td>Nota Jual</td>
								<td>:</td>
								<td>' . ($data['penjualan_kode'] ? $data['penjualan_kode'] : "-") . '</td>
							</tr>
						</table>
					</td>
					<td width="50%">
						<table>
							<tr>
								<td>Petugas</td>
								<td>:</td>
								<td>' . ($data['retur_penjualan_pegawai_nama'] ? $data['retur_penjualan_pegawai_nama'] : "-") . '</td>
							</tr>
							<tr>
								<td>Customer</td>
								<td>:</td>
								<td>' . ($data['customer_nama'] ? $data['customer_nama'] : "-") . '</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode</th>
					<th class="t-center">Nama Barang</th>
					<th class="t-center">Qty Beli</th>
					<th class="t-center">Qty Retur</th>
					<th class="t-center">Harga</th>
					<th class="t-center">Jumlah</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			// print_r('<pre>');print_r($detail);print_r('</pre>');exit;
			foreach ($detail as $key => $value) {
				$hrgJual = $value['pembelian_detail_harga'] + ($percentase / 100 * $value['pembelian_detail_harga']);
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
						<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
						<td>' . ($value['penjualan_detail_qty'] ? $value['penjualan_detail_qty'] : "-") . '</td>
						<td>' . ($value['retur_penjualan_detail_retur_qty'] ? number_format($value['retur_penjualan_detail_retur_qty']) : "") . '</td>
						<td>' . ($value['retur_penjualan_detail_harga'] ? number_format($value['retur_penjualan_detail_harga']) : "") . '</td>
						<td>' . ($value['retur_penjualan_detail_jumlah'] ? number_format($value['retur_penjualan_detail_jumlah']) : "-") . '</td>
					</tr>';
				$totalJml += $value['retur_penjualan_detail_jumlah'];
				$totalQty += $value['retur_penjualan_detail_qty'];
			}


			$html .= '<tr>
					<td colspan="4" class="total">Total</td>
					<td colspan="2" class="total">' . $totalQty . '</td>
					<td colspan="" class="total">' . number_format($totalJml) . '</td>
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
					<td class="bottom">' . $data['pegawai_nama'] . '</td>
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
				'paper_size'    => 'A4',
				'file_name'     => 'NOTA RETUR PENJUALAN',
				'title'         => 'NOTA RETUR PENJUALAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}
}

/* End of file Returpenjualan.php */
/* Location: ./application/modules/Returpenjualan/controllers/Returpenjualan.php */