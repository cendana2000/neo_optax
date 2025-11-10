<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JurnalModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ak_jurnal_umum',
				'primary' => 'jurnal_umum_id',
				'fields' => array(
					array('name' => 'jurnal_umum_id'),
					array('name' => 'jurnal_umum_company'),
					array('name' => 'jurnal_umum_tanggal'),
					array('name' => 'jurnal_umum_nobukti'),
					array('name' => 'jurnal_umum_lawan_transaksi'),
					array('name' => 'jurnal_umum_penerima'),
					array('name' => 'jurnal_umum_keterangan'),
					array('name' => 'jurnal_umum_total'),
					array('name' => 'jurnal_umum_total_debit'),
					array('name' => 'jurnal_umum_total_kredit'),
					array('name' => 'jurnal_umum_status'), 			//active / deactive
					array('name' => 'jurnal_umum_bank_jenis_id'), 
					array('name' => 'jurnal_umum_create_at'),
					array('name' => 'jurnal_umum_create_by'),
					array('name' => 'jurnal_umum_update_at'),
					array('name' => 'jurnal_umum_update_by'),
					array('name' => 'jurnal_umum_delete_at'),
					array('name' => 'jurnal_umum_delete_by'),
					array('name' => 'jurnal_umum_reference'),
					array('name' => 'jurnal_umum_reference_id'),
					array('name' => 'jurnal_umum_reference_kode'),
					array('name' => 'jurnal_umum_closed'),
					array('name' => 'jurnal_umum_is_penyesuaian'),
					array('name' => 'jurnal_umum_unit'),
					array('name' => 'jurnal_umum_akun_id'),
				)
			),
			'view' => array(
				'mode' => array(
					/*'datatable' => array('jurnal_umum_detail_jurnal_umum','jurnal_umum_detail_uraian','jurnal_umum_detail_akun','jurnal_umum_detail_tipe','jurnal_umum_detail_total')*/
					'table' => array(
						'jurnal_umum_id',
						'jurnal_umum_tanggal',
						'jurnal_umum_nobukti',
						'jurnal_umum_keterangan',
						'jurnal_umum_total',
					),
					'bank' => array(
						'jurnal_umum_id',
						'jurnal_umum_tanggal',
						'jurnal_umum_nobukti',
						'jurnal_umum_keterangan',
						'jurnal_umum_status',
						'jurnal_umum_lawan_transaksi',
						'jurnal_umum_total',
					),
					'kas' => array(
						'jurnal_umum_id',
						'jurnal_umum_tanggal',
						'jurnal_umum_nobukti',
						'jurnal_umum_keterangan',
						'jurnal_umum_status',
						'jurnal_umum_total',
					)
				)
			)
		);
		parent::__construct($model);		
	}
	
	public function generate_kode($prefix= 'BU', $month){
		$bulan = date('Ym', strtotime($month));
		if($prefix == 'BU') $bulan = date('Y', strtotime($month));
		$kode = $this->db->query('SELECT jurnal_umum_nobukti FROM ak_jurnal_umum WHERE jurnal_umum_nobukti like "%'.$bulan.'-'.$prefix.'%"  AND jurnal_umum_reference not like "%delete" order by jurnal_umum_nobukti desc limit 1')->result_array();
		if(isset($kode[0]['jurnal_umum_nobukti'])){
			$last_kode = explode('.',$kode[0]['jurnal_umum_nobukti']);
			$last_kode = str_pad($last_kode[1] + 1, 3, 0, STR_PAD_LEFT);			
		}else{
			$last_kode = '001';
		}
		return $bulan.'-'.$prefix.'.'.$last_kode;
	}
	public function check_balance($debit = [], $kredit = [])
	{
		$total_debit = $total_kredit = 0;
		foreach ($debit as $key => $value) {
			if($key !== '') {
				if(is_array($value)){
					foreach ($value as $k => $v) {
						$total_debit += $v;
					}
				}else{
					$total_debit += $value;
				}
			}
		}	
		foreach ($kredit as $key => $value) {
			if($key !== '') {
				if(is_array($value)){
					foreach ($value as $k => $v) {
						$total_kredit += $v;
					}
				}else{
					$total_kredit += $value;
				}
			}
		}
		if($total_debit == $total_kredit){
			if($total_debit !== 0) return 'balance';
			else return 'not balance';
		}else{
			return 'not balance';
		}
	}

	public function edit_jurnal($debit = [], $kredit = [], $trans=[], $debit_keterangan=[], $kredit_keterangan=[])
	{
		$this->load->model(array(
            'akun/AkunSaldoModel'       => 'akunsaldo',
            'jurnal/JurnalDetailModel'  => 'jurnaldetail',
		));
		$detail = $this->db->select('jurnal_umum_detail_akun, jurnal_umum_detail_tipe,  jurnal_umum_detail_total, jurnal_umum_detail_id')
				 ->order_by('jurnal_umum_detail_no', 'ASC')
				 ->get_where('v_ak_jurnal_umum_detail', array('jurnal_umum_detail_jurnal_umum' => $trans['jurnal_umum_id']))
				 ->result_array();
		$temp_detail = [];
		foreach ($detail as $key => $value) {
			$jurnal_umum_id = $value['jurnal_umum_id'];
			$temp_detail[] = $value['jurnal_umum_detail_id'];
			$this->akunsaldo->reduce_saldo(array(
                'akun_id' 					=> $value['jurnal_umum_detail_akun'],
                'jurnal_umum_tanggal' 		=> $trans['jurnal_umum_tanggal'],
                'jurnal_umum_detail_tipe' 	=> $value['jurnal_umum_detail_tipe'],
                'jurnal_umum_reference' 	=> $trans['jurnal_umum_reference']
            ), array(
                'saldo_debit' 				=> ($value['jurnal_umum_detail_tipe']=='debit'?$value['jurnal_umum_detail_total']:null),
                'saldo_kredit' 				=> ($value['jurnal_umum_detail_tipe']=='kredit'?$value['jurnal_umum_detail_total']:null),
            ));
		}
		// exit;
		$temp_detail = '"'.implode('","', $temp_detail).'"';
		$this->db->query('DELETE FROM ak_jurnal_umum_detail WHERE jurnal_umum_detail_jurnal_umum ="'.$trans['jurnal_umum_id'].'"');

		foreach ($debit as $key => $value) {
			$id = md5(rand().$trans['jurnal_umum_tanggal'].$key);
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$id = md5(rand().$trans['jurnal_umum_tanggal'].$key.$no);
					$qdebit[] = [
						'jurnal_umum_detail_id' 	=> $id,
						'jurnal_umum_detail_akun' 	=> $key,
						'jurnal_umum_detail_jurnal_umum' => $trans['jurnal_umum_id'],
						'jurnal_umum_detail_tipe' 	=> 'debit',
						'jurnal_umum_detail_debit' 	=> $v,
						'jurnal_umum_detail_kredit'	=> null,
						'jurnal_umum_detail_total' 	=> $v,
						'jurnal_umum_detail_no' 	=> $no,
						'jurnal_umum_detail_uraian' => $debit_keterangan[$key][$k] ?? null,
					];
					$tdebit += $v;
					$no++;
				}
			}else{
				$qdebit[] = [
					'jurnal_umum_detail_id' 	=> $id,
					'jurnal_umum_detail_akun' 	=> $key,
					'jurnal_umum_detail_jurnal_umum' => $trans['jurnal_umum_id'],
					'jurnal_umum_detail_tipe' 	=> 'debit',
					'jurnal_umum_detail_debit' 	=> $value,
					'jurnal_umum_detail_kredit'	=> null,
					'jurnal_umum_detail_total' 	=> $value,
					'jurnal_umum_detail_no' 	=> $no,
					'jurnal_umum_detail_uraian' => $debit_keterangan[$key] ?? null,
				];
				$tdebit += $value;
				$no++;
			}
		}
		foreach ($kredit as $key => $value) {
			$id = md5(rand().$trans['jurnal_umum_tanggal'].$key);
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$id = md5(rand().$trans['jurnal_umum_tanggal'].$key.$no);
					$qkredit[] = [
						'jurnal_umum_detail_id' 	=> $id,
						'jurnal_umum_detail_akun' 	=> $key,
						'jurnal_umum_detail_jurnal_umum' => $trans['jurnal_umum_id'],
						'jurnal_umum_detail_tipe' 	=> 'kredit',
						'jurnal_umum_detail_debit'	=> null,
						'jurnal_umum_detail_kredit'	=> $v,
						'jurnal_umum_detail_total' 	=> $v,
						'jurnal_umum_detail_no' 	=> $no,
						'jurnal_umum_detail_uraian' => $kredit_keterangan[$key][$k] ?? null,
					];
					$tkredit += $v;
					$no++;
				}
			}else{
				// $tkredit += $value;
				$no++;
				$id = md5(rand().$trans['jurnal_umum_tanggal'].$key);
				$qkredit[] = [
					'jurnal_umum_detail_id' 	=> $id,
					'jurnal_umum_detail_akun' 	=> $key,
					'jurnal_umum_detail_jurnal_umum' => $trans['jurnal_umum_id'],
					'jurnal_umum_detail_tipe' 	=> 'kredit',
					'jurnal_umum_detail_debit'	=> null,
					'jurnal_umum_detail_kredit'	=> $value,
					'jurnal_umum_detail_total' 	=> $value,
					'jurnal_umum_detail_no' 	=> $no,
					'jurnal_umum_detail_uraian' => $kredit_keterangan[$key] ?? null,
				];
				$tkredit += $value;
				$no++;
			}
		}
		$query = array_merge($qdebit, $qkredit);
			// 'jurnal_umum_nobukti'		=> $trans['jurnal_umum_nobukti'],
		$qjurnal = [
			'jurnal_umum_tanggal'		=> $trans['jurnal_umum_tanggal'],
			'jurnal_umum_status'		=> ($trans['jurnal_umum_status']?$trans['jurnal_umum_status']:'deactive'),
			'jurnal_umum_penerima' 		=> $trans['jurnal_umum_penerima'],
			'jurnal_umum_lawan_transaksi' => $trans['jurnal_umum_lawan_transaksi'],
			'jurnal_umum_keterangan'	=> $trans['jurnal_umum_keterangan'],
			'jurnal_umum_total'			=> $trans['jurnal_umum_total'],
			'jurnal_umum_total_debit'	=> $tdebit,
			'jurnal_umum_total_kredit'	=> $tkredit,
			'jurnal_umum_create_at'		=> date('Y-m-d H:i:s'),
			'jurnal_umum_create_by'		=> $this->session->userdata('user_id'),
			'jurnal_umum_reference'		=> $trans['jurnal_umum_reference'],
			'jurnal_umum_reference_id'	=> $trans['jurnal_umum_reference_id'],
			'jurnal_umum_reference_kode'=> $trans['jurnal_umum_reference_kode'],
			'jurnal_umum_unit'			=> $trans['jurnal_umum_unit'],
		];
		if(!empty($trans['jurnal_umum_akun_id'])){
			$qjurnal['jurnal_umum_akun_id'] = $trans['jurnal_umum_akun_id'];
		}
		// $this->db->where('jurnal_umum_reference_id', $trans['jurnal_umum_reference_id'])
		// 		 ->update('ak_jurnal_umum', array(
		// 			'jurnal_umum_total_debit'	=> $tdebit,
		// 			'jurnal_umum_total_kredit'	=> $tkredit,
		// 		 	'jurnal_umum_update_by' 	=> $this->session->userdata('user_id'),
		// 		 	'jurnal_umum_update_at' 	=> date('Y-m-d H:i:s'),
		// 		 ));

		$jurnal = $this->update($trans['jurnal_umum_id'], $qjurnal, function($response) use ($query,$trans){
			if($query){
				$this->db->insert_batch('ak_jurnal_umum_detail', $query);
				// print_r($query);exit;
				// $this->db->last_query();exit;
				foreach ($query as $key => $value){				
	                $this->akunsaldo->add_saldo(array(
	                    'akun_id' 					=> $value['jurnal_umum_detail_akun'],
	                    'jurnal_umum_tanggal' 		=> $trans['jurnal_umum_tanggal'],
	                    'jurnal_umum_detail_tipe' 	=> $value['jurnal_umum_detail_tipe'],
	                    'jurnal_umum_reference' 	=> $trans['jurnal_umum_reference'],
	                ), array(
	                    'saldo_debit' 				=> ($value['jurnal_umum_detail_tipe']=='debit'?$value['jurnal_umum_detail_total']:0),
	                    'saldo_kredit' 				=> ($value['jurnal_umum_detail_tipe']=='kredit'?$value['jurnal_umum_detail_total']:0),
	                ));
				}
			}
		});
		return $jurnal;
	}

	public function add_jurnal($debit = [], $kredit = [], $trans=[], $debit_keterangan=[], $kredit_keterangan=[])
	{
		$this->load->model(array(
            'akun/AkunSaldoModel'       => 'akunsaldo',
            'jurnal/JurnalDetailModel'  => 'jurnaldetail',
		));

		/*
		example how to use jurnaling account
		$debit = [
			//id => value
			'5001' => '400000'
		];
		$kredit = [
			//id => value
			'111101' => '320000',
			'5005' => '80000',
		];*/
		$qdebit = $qkredit = [];
		$tdebit = $tkredit = $no = 0;
		$parent = ($trans['jurnal_umum_id']?$trans['jurnal_umum_id']:gen_uuid($this->jurnal->get_table()));
		foreach ($debit as $key => $value) {
			$id = md5(rand().$trans['jurnal_umum_tanggal'].$key);
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$id = md5(rand().$trans['jurnal_umum_tanggal'].$key.$no);
					$qdebit[] = [
						'jurnal_umum_detail_id' 		=> $id,
						'jurnal_umum_detail_akun' 		=> $key,
						'jurnal_umum_detail_jurnal_umum'=> $parent,
						'jurnal_umum_detail_tipe' 		=> 'debit',
						'jurnal_umum_detail_debit' 		=> $v,
						'jurnal_umum_detail_kredit'		=> null,
						'jurnal_umum_detail_total' 		=> $v,
						'jurnal_umum_detail_no' 		=> $no,
						'jurnal_umum_detail_uraian' 	=> $debit_keterangan[$key][$k] ?? null,
					];
					$tkredit += $v;
					$no++;
				}
			}else{
				$qdebit[] = [
					'jurnal_umum_detail_id' 		=> $id,
					'jurnal_umum_detail_akun' 		=> $key,
					'jurnal_umum_detail_jurnal_umum'=> $parent,
					'jurnal_umum_detail_tipe' 		=> 'debit',
					'jurnal_umum_detail_debit' 		=> $value,
					'jurnal_umum_detail_kredit'		=> null,
					'jurnal_umum_detail_total' 		=> $value,
					'jurnal_umum_detail_no' 		=> $no,
					'jurnal_umum_detail_uraian' 	=> $debit_keterangan[$key] ?? null,
				];
				$tdebit += $value;
				$no++;
			}
		}
		foreach ($kredit as $key => $value) {
			$id = md5(rand().$trans['jurnal_umum_tanggal'].$key);
			if(is_array($value)){
				foreach ($value as $k => $v) {
					$id = md5(rand().$trans['jurnal_umum_tanggal'].$key.$no);
					$qkredit[] = [
						'jurnal_umum_detail_id' 		=> $id,
						'jurnal_umum_detail_akun' 		=> $key,
						'jurnal_umum_detail_jurnal_umum'=> $parent,
						'jurnal_umum_detail_tipe' 		=> 'kredit',
						'jurnal_umum_detail_debit'		=> null,
						'jurnal_umum_detail_kredit'		=> $v,
						'jurnal_umum_detail_total' 		=> $v,
						'jurnal_umum_detail_no' 		=> $no,
						'jurnal_umum_detail_uraian' 	=> $kredit_keterangan[$key][$k] ?? null,
					];
					$tkredit += $v;
					$no++;
				}
			}else{
				$qkredit[] = [
					'jurnal_umum_detail_id' 		=> $id,
					'jurnal_umum_detail_akun' 		=> $key,
					'jurnal_umum_detail_jurnal_umum'=> $parent,
					'jurnal_umum_detail_tipe' 		=> 'kredit',
					'jurnal_umum_detail_debit'		=> null,
					'jurnal_umum_detail_kredit'		=> $value,
					'jurnal_umum_detail_total' 		=> $value,
					'jurnal_umum_detail_no' 		=> $no,
					'jurnal_umum_detail_uraian' 	=> $kredit_keterangan[$key] ?? null,
				];
				$tkredit += $value;
			}
			$no++;
		}
		$query = array_merge($qdebit, $qkredit);
		// print_r($query);exit;
		$qjurnal = [
			'jurnal_umum_tanggal'		=> $trans['jurnal_umum_tanggal'],
			'jurnal_umum_status'		=> ($trans['jurnal_umum_status']?$trans['jurnal_umum_status']:'deactive'),
			'jurnal_umum_penerima' 		=> $trans['jurnal_umum_penerima'],
			'jurnal_umum_lawan_transaksi' => $trans['jurnal_umum_lawan_transaksi'],
			'jurnal_umum_nobukti'		=> $trans['jurnal_umum_nobukti'],
			'jurnal_umum_keterangan'	=> $trans['jurnal_umum_keterangan'],
			'jurnal_umum_total_debit'	=> $tdebit,
			'jurnal_umum_total_kredit'	=> $tkredit,
			'jurnal_umum_create_at'		=> date('Y-m-d H:i:s'),
			'jurnal_umum_create_by'		=> $this->session->userdata('user_id'),
			'jurnal_umum_reference'		=> $trans['jurnal_umum_reference'],
			'jurnal_umum_reference_id'	=> $trans['jurnal_umum_reference_id'],
			'jurnal_umum_reference_kode'=> $trans['jurnal_umum_reference_kode'],
			'jurnal_umum_unit'			=> $trans['jurnal_umum_unit'],
			'jurnal_umum_total'			=> $trans['jurnal_umum_total'],
		];
		if(!empty($trans['jurnal_umum_akun_id'])){
			$qjurnal['jurnal_umum_akun_id'] = $trans['jurnal_umum_akun_id'];
		}

		$jurnal = $this->insert($parent, $qjurnal, function($response) use ($query, $trans){
			$this->db->insert_batch('ak_jurnal_umum_detail', $query);
			// if(!$a){
			// 	echo 'yah salah';
			// }
			foreach ($query as $key => $value){				
                $this->akunsaldo->add_saldo(array(
                    'akun_id' 					=> $value['jurnal_umum_detail_akun'],
                    'jurnal_umum_tanggal' 		=> $trans['jurnal_umum_tanggal'],
                    'jurnal_umum_detail_tipe' 	=> $value['jurnal_umum_detail_tipe'],
                    'jurnal_umum_reference' 	=> $trans['jurnal_umum_reference']
                ), array(
                    'saldo_debit' 				=> (($value['jurnal_umum_detail_tipe']=='debit' && $value['jurnal_umum_detail_total']) ? $value['jurnal_umum_detail_total']: 0),
                    'saldo_kredit' 				=> (($value['jurnal_umum_detail_tipe']=='kredit' && $value['jurnal_umum_detail_total']) ? $value['jurnal_umum_detail_total']: 0),
                ));
			}
		});
		return $jurnal;
	}

}

/* End of file agamaModel.php */
/* Location: .//X/rsmh/app/modules/agama/models/agamaModel.php */