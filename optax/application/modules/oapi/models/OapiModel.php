<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OapiModel extends Base_Model
{

	public function getToko()
	{
		return  $this->db->get_where('pajak_toko', ['toko_is_oapi' => 'ACTIVE'])->result_array();
	}

	public function insertToPenjualan($data, $data_detail, $code_store = 'dialoogi')
	{
		$this->db->trans_start();
		foreach ($data as $index => $value) {
			$query_penjualan = $this->db->query("SELECT * FROM pos_penjualan_pooling where penjualan_id = '" . $value['penjualan_id'] . "' OR penjualan_kode = '" . $value['penjualan_kode'] . "'");
			$result_cek 	= $query_penjualan->result_array();
			if (empty($result_cek)) {
				$insert = $this->db->insert('pos_penjualan_pooling', $value);
			}
		}

		foreach ($data_detail as $in => $val) {
			if (!empty($val['penjualan_detail_id'])) {
				$query_detail = $this->db->query("SELECT * FROM pos_penjualan_detail_pooling where penjualan_detail_parent = '" . $val['penjualan_detail_parent'] . "' OR penjualan_detail_nama_barang ='" . $val['penjualan_detail_nama_barang'] . "'");
				$result_detail 	= $query_detail->result_array();

				if (empty($result_detail)) {
					$detail = $this->db->insert('pos_penjualan_detail_pooling', $val);
				}
			}
		}
		$this->db->trans_complete();
	}

	public function insertToRealisasi($data)
	{
		$data_log = array();
		$this->db->trans_start();
		foreach ($data as $index => $value) {
			$query_pajak = $this->db->query("SELECT * FROM pajak_realisasi where realisasi_no = '" . $value['realisasi_no'] . "'");
			$result_pajak 	= $query_pajak->result_array();
			if (empty($result_pajak)) {
				if ($this->db->insert('pajak_realisasi', $value)) {
					$data_log[$index] = [
						'log_id' 			=> $value['realisasi_no'],
						'log_created_at' 	=> date("Y-m-d H:i:s"),
						'log_realisasi_no' 	=> $value['realisasi_no'],
						'log_is_success' 	=> 1,
						'log_is_failed' 	=> 0,
					];
				} else {
					$data_log[$index] = [
						'log_id' 			=> $value['realisasi_no'],
						'log_created_at' 	=> date("Y-m-d H:i:s"),
						'log_realisasi_no' 	=> $value['realisasi_no'],
						'log_is_success' 	=> 0,
						'log_is_failed' 	=> 1,
						'log_is_solve' 		=> 0,
					];
				}
			}
		}
		$this->db->trans_complete();

		if (!empty($data_log)) {
			$this->db->insert_batch('log_api', $data_log);
		}
	}
}
