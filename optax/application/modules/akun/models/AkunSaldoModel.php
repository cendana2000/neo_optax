<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AkunSaldoModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ak_saldo_akun',
				'primary' => 'saldo_id',
				'fields' => array(
					array('name' => 'saldo_id'),
					array('name' => 'saldo_akun_id'),
					array('name' => 'saldo_periode'),
					array('name' => 'saldo_debit_awal'),
					array('name' => 'saldo_kredit_awal'),
					array('name' => 'saldo_debit_perubahan'),
					array('name' => 'saldo_kredit_perubahan'),
					array('name' => 'saldo_debit_penyesuaian'),
					array('name' => 'saldo_kredit_penyesuaian'),
					array('name' => 'saldo_debit_akhir'),
					array('name' => 'saldo_kredit_akhir'),
					array('name' => 'saldo_created'),
					array('name' => 'saldo_user'),
				)
			),
			/*'view' => array(
				'mode' => array(
					'datatable' => array('akun_id','akun_key')
				)
			)*/
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function update_saldo($data, $trans)
	{
		$bulan = date('Y-m', strtotime($data['jurnal_umum_tanggal']));
        $saldo = $this->read(array(
            'saldo_akun_id' => $data['akun_id'],
            'saldo_periode' => $bulan,
        ));
        $dbt_awal = $kdt_awal = 0;
    	if($data['jurnal_umum_reference'] == 'saldo_awal'){
    		$dbt_awal = intval($trans['saldo_debit']);
        	$kdt_awal = intval($trans['saldo_kredit']);
    	}

		$jurnal = $this->db->query('SELECT SUM(jurnal_umum_detail_debit) total_debit, SUM(jurnal_umum_detail_kredit) total_kredit FROM ak_jurnal_umum_detail LEFT JOIN ak_jurnal_umum ON jurnal_umum_detail_jurnal_umum = jurnal_umum_id WHERE (jurnal_umum_reference not like "%elete%" OR jurnal_umum_reference not like "%saldo_awal%") AND jurnal_umum_nobukti not like "%elete%" AND DATE_FORMAT(jurnal_umum_tanggal, "%Y-%m") ="'.$bulan.'" AND jurnal_umum_detail_akun = "'.$data['akun_id'].'"')->row_array();
		if(isset($jurnal['total_debit']) || isset($jurnal['total_kredit'])){
			$debit = intval($jurnal['total_debit']);
			$kredit = intval($jurnal['total_kredit']);
		}
    	$debit_penyesuaian = $kredit_penyesuaian = $debit_akhir = $kredit_akhir = 0;
        if(!isset($saldo['saldo_id'])){
	        $saldo_prev = $this->read(array(
	            'saldo_akun_id' 	=> $data['akun_id'],
	            'saldo_periode <' 	=> $bulan,
	        ));
        	if(isset($saldo_prev['saldo_id']) && $data['jurnal_umum_reference'] !== 'saldo_awal'){
	        	$dbt_awal = intval($saldo_prev['saldo_debit_akhir']);
	        	$kdt_awal = intval($saldo_prev['saldo_kredit_akhir']);
        	}
        	$id = gen_uuid($this->get_table());
			$debit_akhir = $debit+$dbt_awal;
			$kredit_akhir = $kredit+$kdt_awal;
			if(isset($trans['saldo_debit_penyesuaian']) || isset($trans['saldo_kredit_penyesuaian'])){
				$debit_penyesuaian = intval($trans['saldo_debit_penyesuaian']);
				$kredit_penyesuaian = intval($trans['saldo_kredit_penyesuaian']);
				$debit_akhir = $kredit_penyesuaian;
				$kredit_akhir = 0;
			}
			// print_r($trans);exit;
            $save = $this->db->insert('ak_saldo_akun', array(
				'saldo_id' 					=> gen_uuid($this->get_table()),
				'saldo_akun_id'				=> $data['akun_id'],
				'saldo_periode'				=> date('Y-m', strtotime($data['jurnal_umum_tanggal'])),
				'saldo_debit_awal' 			=> $dbt_awal,
				'saldo_kredit_awal'			=> $kdt_awal,
				'saldo_debit_perubahan' 	=> $debit,
				'saldo_kredit_perubahan'	=> $kredit,
				'saldo_debit_penyesuaian' 	=> $debit_penyesuaian,
				'saldo_kredit_penyesuaian'	=> $kredit_penyesuaian,
				'saldo_debit_akhir'			=> $debit_akhir,
				'saldo_kredit_akhir'		=> $kredit_akhir,
				'saldo_created'				=> date('Y-m-d H:i:s'),
			));

        }else{
			$dbt_awal = intval($saldo['saldo_debit_awal']);
			$kdt_awal = intval($saldo['saldo_kredit_awal']);
			$debit_akhir = $debit+$dbt_awal;
			$kredit_akhir = $kredit+$kdt_awal;
			if(isset($trans['saldo_debit_penyesuaian']) || isset($trans['saldo_kredit_penyesuaian'])){
				$debit_penyesuaian = intval($trans['saldo_debit_penyesuaian']);
				$kredit_penyesuaian = intval($trans['saldo_kredit_penyesuaian']);
				$debit_akhir = $kredit_penyesuaian;
				$kredit_akhir = 0;
			}
        	$save = $this->db->where('saldo_id', $saldo['saldo_id'])
					->set('saldo_debit_awal', $dbt_awal)
					->set('saldo_kredit_awal', $kdt_awal)
					->set('saldo_debit_perubahan', $debit)
					->set('saldo_kredit_perubahan', $kredit)
					->set('saldo_debit_penyesuaian', $debit_penyesuaian)
					->set('saldo_kredit_penyesuaian', $kredit_penyesuaian)
					->set('saldo_debit_akhir', $debit_akhir)
					->set('saldo_kredit_akhir',	$kredit_akhir)
				->update('ak_saldo_akun');
        	// echo 'hall';exit;
        }
	}

	public function restart_saldo($data='')
	{
		$bulan_prev = date('Y-m', strtotime('-1 month', strtotime($data['bulan'].'-01')));
        $saldo = $this->read(array(
            'saldo_periode' => $bulan_prev,
        ));		
        if($saldo){
        	$delete = $this->db->query('DELETE from ak_saldo_akun WHERE saldo_periode = "'.$data['bulan'].'"');
			$insert = $this->db->query('INSERT INTO ak_saldo_akun(saldo_id, saldo_periode, saldo_akun_id, saldo_debit_awal, saldo_kredit_awal, saldo_debit_perubahan, saldo_kredit_perubahan,saldo_debit_penyesuaian, saldo_kredit_penyesuaian, saldo_debit_akhir, saldo_kredit_akhir)
			select md5(CONCAT(akun_id,"'.$data['bulan'].'")), "'.$data['bulan'].'", akun_id, debit_awal, kredit_awal, debit_nilai, kredit_nilai, 0, 0,  ifnull(debit_awal,0)+ifnull(debit_nilai,0),  ifnull(kredit_awal,0)+ifnull(kredit_nilai,0) from ak_akun 
				LEFT JOIN (SELECT saldo_akun_id, saldo_debit_akhir+ifnull(saldo_debit_tutup,0) as debit_awal, saldo_kredit_akhir+ifnull(saldo_kredit_tutup,0) as kredit_awal FROM ak_saldo_akun WHERE saldo_periode = "'.$bulan_prev.'") as jurnal_awal on jurnal_awal.saldo_akun_id = akun_id
				LEFT JOIN (SELECT jurnal_umum_detail_akun, sum(jurnal_umum_detail_debit) debit_nilai, sum(jurnal_umum_detail_kredit) kredit_nilai FROM ak_jurnal_umum_detail LEFT JOIN ak_jurnal_umum on jurnal_umum_detail_jurnal_umum = jurnal_umum_id WHERE (jurnal_umum_reference !="saldo_awal" AND jurnal_umum_reference not like "%elete%") AND jurnal_umum_nobukti not like "%elete%" AND DATE_FORMAT(jurnal_umum_tanggal, "%Y-%m") ="'.$data['bulan'].'" GROUP BY jurnal_umum_detail_akun ) as jurnal_perubahan on jurnal_perubahan.jurnal_umum_detail_akun = akun_id;
				');
        }else{
        	$delete = $this->db->query('DELETE from ak_saldo_akun WHERE saldo_periode = "'.$data['bulan'].'"');
			$insert = $this->db->query('INSERT INTO ak_saldo_akun(saldo_id, saldo_periode, saldo_akun_id, saldo_debit_awal, saldo_kredit_awal, saldo_debit_perubahan, saldo_kredit_perubahan,saldo_debit_penyesuaian, saldo_kredit_penyesuaian, saldo_debit_akhir, saldo_kredit_akhir)
			select md5(CONCAT(akun_id,"'.$data['bulan'].'")), "'.$data['bulan'].'",  akun_id, debit_awal, kredit_awal, debit_nilai, kredit_nilai, 0, 0,  ifnull(debit_awal,0)+ifnull(debit_nilai,0),  ifnull(kredit_awal,0)+ifnull(kredit_nilai,0) from ak_akun 
				LEFT JOIN (SELECT jurnal_umum_detail_akun, jurnal_umum_detail_debit debit_awal, jurnal_umum_detail_kredit kredit_awal FROM ak_jurnal_umum_detail LEFT JOIN ak_jurnal_umum on jurnal_umum_detail_jurnal_umum = jurnal_umum_id WHERE jurnal_umum_reference ="saldo_awal" AND DATE_FORMAT(jurnal_umum_tanggal, "%Y-%m") ="'.$data['bulan'].'") as jurnal_awal on jurnal_awal.jurnal_umum_detail_akun = akun_id
				LEFT JOIN (SELECT jurnal_umum_detail_akun, sum(jurnal_umum_detail_debit) debit_nilai, sum(jurnal_umum_detail_kredit) kredit_nilai FROM ak_jurnal_umum_detail LEFT JOIN ak_jurnal_umum on jurnal_umum_detail_jurnal_umum = jurnal_umum_id WHERE jurnal_umum_reference !="saldo_awal" AND DATE_FORMAT(jurnal_umum_tanggal, "%Y-%m") ="'.$data['bulan'].'") as jurnal_perubahan on jurnal_perubahan.jurnal_umum_detail_akun = akun_id;
				');
        }
	}

	public function add_saldo($data, $trans)
	{
        $saldo = $this->akunsaldo->read(array(
            'saldo_akun_id' => $data['akun_id'],
            'saldo_periode' => date('Y-m', strtotime($data['jurnal_umum_tanggal'])),
        ));
        $saldo_akhir = $debit_akhir = $kredit_akhir = 0;
    	// print_r($saldo);
    	// print_r($data);        
        if(isset($saldo['saldo_id'])){
        	// $akhir = ($data['jurnal_umum_detail_tipe'] == 'debit') ? $saldo['saldo_akhir']+$trans['saldo_debit'] : $saldo['saldo_akhir']-$trans['saldo_kredit'];

	        $selisih_awal_debit = 0;
			$selisih_awal_kredit = 0;
	        if($data['jurnal_umum_reference'] == 'saldo_awal'){
				$selisih_awal_debit = $trans['saldo_debit'];
				$selisih_awal_kredit = $trans['saldo_kredit'];
				$trans['saldo_debit'] = 0;
				$trans['saldo_kredit'] = 0;
        	}

	        $debit_akhir = $saldo['saldo_debit_akhir']+$trans['saldo_debit']+$trans['saldo_debit_penyesuaian']+$selisih_awal_debit;
	        $kredit_akhir = $saldo['saldo_kredit_akhir']+$trans['saldo_kredit']+$trans['saldo_kredit_penyesuaian']+$selisih_awal_kredit;
	        // $saldo_akhir = ($saldo['saldo_debit_akhir']-$saldo['saldo_kredit_akhir'])+($trans['saldo_debit']-$trans['saldo_kredit'])+($trans['saldo_debit_penyesuaian']-$trans['saldo_kredit_penyesuaian']);
	        /*if($saldo_akhir>0){
	        	$debit_akhir = $saldo_akhir;
	        }else{
	        	$kredit_akhir = -1*$saldo_akhir;
	        }*/
				/*->set('saldo_debit_awal', ($saldo['saldo_debit_akhir']?$saldo['saldo_debit_akhir']:0))
				->set('saldo_kredit_awal', ($saldo['saldo_kredit_akhir']?$saldo['saldo_kredit_akhir']:0))*/

        	// print_r($saldo);
        	// print_r($trans);exit;
            $save = $this->db->where('saldo_id', $saldo['saldo_id'])
				->set('saldo_debit_awal', 'saldo_debit_awal+'.$selisih_awal_debit, FALSE)
				->set('saldo_kredit_awal','saldo_kredit_awal+'.$selisih_awal_kredit, FALSE)
				->set('saldo_debit_perubahan', 'saldo_debit_perubahan+'.(($trans['saldo_debit'] && $trans['saldo_debit'] !== '') ?$trans['saldo_debit']: 0), FALSE)
				->set('saldo_kredit_perubahan','saldo_kredit_perubahan+'.(($trans['saldo_kredit'] && $trans['saldo_kredit']!== '') ?$trans['saldo_kredit']: 0), FALSE)
				->set('saldo_debit_penyesuaian', 'saldo_debit_penyesuaian+'.($trans['saldo_debit_penyesuaian'] ?? 0), FALSE)
				->set('saldo_kredit_penyesuaian', 'saldo_kredit_penyesuaian+'.($trans['saldo_kredit_penyesuaian'] ?? 0), FALSE)
				->set('saldo_debit_akhir', $debit_akhir)
				->set('saldo_kredit_akhir',	$kredit_akhir)
				->update('ak_saldo_akun');
			// echo 'nambah';
        }else{
        	$id = gen_uuid($this->akunsaldo->get_table());
        	$saldo = [];
        	if($data['jurnal_umum_reference'] == 'saldo_awal'){
				$saldo['saldo_debit_akhir'] = $trans['saldo_debit'];
				$saldo['saldo_kredit_akhir'] = $trans['saldo_kredit'];
				$trans['saldo_debit'] = 0;
				$trans['saldo_kredit'] = 0;
        	}else{
		        $saldo = $this->akunsaldo->read(array(
		            'saldo_akun_id' => $data['akun_id'],
		            'saldo_periode < "'.date('Y-m', strtotime($data['jurnal_umum_tanggal'])).'"' => null,
		        ));
        	}
			// echo 'ngedit';
			// echo 'saldo';
			// print_r($saldo);exit;
        	// print_r($data);
        	// print_r($saldo);
        	// print_r($trans);exit;
	        $debit_akhir = ($saldo['saldo_debit_akhir'] ?? 0)+$trans['saldo_debit']+$trans['saldo_debit_penyesuaian'];
	        $kredit_akhir = ($saldo['saldo_kredit_akhir'] ?? 0)+$trans['saldo_kredit']+$trans['saldo_kredit_penyesuaian'];

	        /*$saldo_akhir = ($saldo['saldo_debit_akhir']-$saldo['saldo_kredit_akhir'])+($trans['saldo_debit']-$trans['saldo_kredit'])+($trans['saldo_debit_penyesuaian']-$trans['saldo_kredit_penyesuaian']);
	        if($saldo_akhir>0){
	        	$debit_akhir = $saldo_akhir;
	        }else{
	        	$kredit_akhir = -1*$saldo_akhir;
	        }*/
            $save = $this->db->insert('ak_saldo_akun', array(
				'saldo_id' 					=> $id,
				'saldo_akun_id'				=> $data['akun_id'],
				'saldo_periode'				=> date('Y-m', strtotime($data['jurnal_umum_tanggal'])),
				'saldo_debit_awal' 			=> ($saldo['saldo_debit_akhir']?$saldo['saldo_debit_akhir']:0),
				'saldo_kredit_awal'			=> ($saldo['saldo_kredit_akhir']?$saldo['saldo_kredit_akhir']:0),
				'saldo_debit_perubahan' 	=> ($trans['saldo_debit']?$trans['saldo_debit']:0),
				'saldo_kredit_perubahan'	=> ($trans['saldo_kredit']?$trans['saldo_kredit']:0),
				'saldo_debit_penyesuaian' 	=> (isset($trans['saldo_debit_penyesuaian'])?$trans['saldo_debit_penyesuaian']:0),
				'saldo_kredit_penyesuaian'	=> (isset($trans['saldo_kredit_penyesuaian'])?$trans['saldo_kredit_penyesuaian']:0),
				'saldo_debit_akhir'			=> $debit_akhir,
				'saldo_kredit_akhir'		=> $kredit_akhir,
				'saldo_created'				=> date('Y-m-d H:i:s'),
			));
        }
	}

	public function reduce_saldo($data, $trans)
	{
		 $saldo = $this->akunsaldo->read(array(
            'saldo_akun_id' => $data['akun_id'],
            'saldo_periode' => date('Y-m', strtotime($data['jurnal_umum_tanggal'])),
        ));
		 // echo 'test';exit;
        if($saldo['saldo_id']){
         	/*$saldo_akhir = ($saldo['saldo_debit_akhir']-$saldo['saldo_kredit_akhir'])-($trans['saldo_debit']-$trans['saldo_kredit'])-($trans['saldo_debit_penyesuaian']-$trans['saldo_kredit_penyesuaian']);
	        if($saldo_akhir>0){
	        	$debit_akhir = $saldo_akhir;
	        }else{
	        	$kredit_akhir = -1*$saldo_akhir;
	        }*/
	        $selisih_awal_debit = 0;
			$selisih_awal_kredit = 0;
	        if($data['jurnal_umum_reference'] == 'saldo_awal'){
	        	// echo $saldo_awal;
				$selisih_awal_debit = $trans['saldo_debit'];
				$selisih_awal_kredit = $trans['saldo_kredit'];
				$trans['saldo_debit'] = 0;
				$trans['saldo_kredit'] = 0;
        	}
	        $debit_akhir = intval($saldo['saldo_debit_akhir'])-$trans['saldo_debit']-$trans['saldo_debit_penyesuaian']-$selisih_awal_debit;
	        $kredit_akhir = intval($saldo['saldo_kredit_akhir'])-$trans['saldo_kredit']-$trans['saldo_kredit_penyesuaian']-$selisih_awal_kredit;

			$saldo = $this->db->where('saldo_id', $saldo['saldo_id'])
				->set('saldo_debit_awal', 'saldo_debit_awal-'.intval($selisih_awal_debit), FALSE)
				->set('saldo_kredit_awal', 'saldo_kredit_awal-'.intval($selisih_awal_kredit), FALSE)
				->set('saldo_debit_perubahan', 'saldo_debit_perubahan-'.(intval($trans['saldo_debit'])), FALSE)
				->set('saldo_kredit_perubahan', 'saldo_kredit_perubahan-'.(intval($trans['saldo_kredit'])), FALSE)
				->set('saldo_debit_penyesuaian', 'saldo_debit_penyesuaian-'.(intval($trans['saldo_debit_penyesuaian'])), FALSE)
				->set('saldo_kredit_penyesuaian', 'saldo_kredit_penyesuaian-'.(intval($trans['saldo_kredit_penyesuaian'])), FALSE)
				->set('saldo_debit_akhir', $debit_akhir)
				->set('saldo_kredit_akhir',	$kredit_akhir)
				->update('ak_saldo_akun');
        }else{
        	if($data['jurnal_umum_reference'] == 'saldo_awal'){
				$saldo['saldo_debit_akhir'] = $trans['saldo_debit'];
				$saldo['saldo_kredit_akhir'] = $trans['saldo_kredit'];
				$trans['saldo_debit'] = 0;
				$trans['saldo_kredit'] = 0;
        	}else{
		        $saldo = $this->akunsaldo->read(array(
		            'saldo_akun_id' => $data['akun_id'],
		            'saldo_periode < "'.date('Y-m', strtotime($data['jurnal_umum_tanggal'])).'"' => null,
		        ));
        	}

        	$id = gen_uuid($this->akunsaldo->get_table());
            $save = $this->db->insert('ak_saldo_akun', array(
				'saldo_id' 					=> $id,
				'saldo_akun_id'				=> $data['akun_id'],
				'saldo_periode'				=> date('Y-m', strtotime($data['jurnal_umum_tanggal'])),
				'saldo_debit_awal' 			=> ($saldo['saldo_debit_akhir']?'-'.$saldo['saldo_debit_akhir']:0),
				'saldo_kredit_awal'			=> ($saldo['saldo_kredit_akhir']?'-'.$saldo['saldo_kredit_akhir']:0),
				'saldo_debit_perubahan' 	=> ($trans['saldo_debit']?'-'.$trans['saldo_debit']:0),
				'saldo_kredit_perubahan'	=> ($trans['saldo_kredit']?'-'.$trans['saldo_kredit']:0),
				'saldo_debit_penyesuaian' 	=> ($trans['saldo_debit_penyesuaian']?'-'.$trans['saldo_debit_penyesuaian']:0),
				'saldo_kredit_penyesuaian'	=> ($trans['saldo_kredit_penyesuaian']?'-'.$trans['saldo_kredit_penyesuaian']:0),
				'saldo_debit_akhir'			=> $debit_akhir,
				'saldo_kredit_akhir'		=> $kredit_akhir,
			));
        }

	}

}

/* End of file JenisanggotaModel.php */
/* Location: ./application/modules/jenisanggota/models/JenisanggotaModel.php */