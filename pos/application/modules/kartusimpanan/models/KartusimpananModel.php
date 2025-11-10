
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KartusimpananModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ksp_kartu_simpanan',
				'primary' => 'kartu_simpanan_id',
				'fields' => array(
					array('name' => 'kartu_simpanan_id'),
					array('name' => 'kartu_simpanan_kode'),
					array('name' => 'kartu_simpanan_tanggal'),
					array('name' => 'kartu_simpanan_anggota'),
					array('name' => 'kartu_simpanan_order'),
					array('name' => 'kartu_simpanan_saldo_awal'),
					array('name' => 'kartu_simpanan_saldo_masuk'),
					array('name' => 'kartu_simpanan_saldo_keluar'),
					array('name' => 'kartu_simpanan_saldo_akhir'),
					array('name' => 'kartu_simpanan_transaksi'),
					array('name' => 'kartu_simpanan_transaksi_kode'),
					array('name' => 'kartu_simpanan_keterangan'),
					array('name' => 'kartu_simpanan_create_by'),
					array('name' => 'kartu_simpanan_create_at'),
					array('name' => 'kartu_simpanan_update_by'),
					array('name' => 'kartu_simpanan_update_at'),
					array('name' => 'kartu_simpanan_delete_by'),
					array('name' => 'kartu_simpanan_delete_at'),
					array('name' => 'kartu_simpanan_is_posting'),
					array('name' => 'kartu_simpanan_saldo_bunga'),
					array('name' => 'kartu_simpanan_referensi_id'),
					array('name' => 'kartu_simpanan_cabang'),
					array('name' => 'kartu_simpanan_cabang_kasir'),
					array('name' => 'kartu_simpanan_deskripsi'),
					array('name' => 'anggota_id'			, 'view'=>true),
					array('name' => 'anggota_nama'			, 'view'=>true),
					array('name' => 'anggota_kode'			, 'view'=>true),
					array('name' => 'anggota_alamat'		, 'view'=>true),
					array('name' => 'anggota_is_aktif'		, 'view'=>true),
					array('name' => 'grup_gaji_nama'		, 'view'=>true),
					array('name' => 'grup_gaji_kode'		, 'view'=>true),
					array('name' => 'grup_gaji_id'		, 'view'=>true),
					array('name' => 'pegawai_nama'		, 'view'=>true),
				)
			),
			'view' => array(
				'name' => 'v_ksp_kartu_simpanan',
				'mode' => array(
					'table' => array('kartu_simpanan_id','kartu_simpanan_order', 'kartu_simpanan_tanggal', 'kartu_simpanan_saldo_awal','kartu_simpanan_saldo_masuk', 'kartu_simpanan_saldo_keluar', 'kartu_simpanan_saldo_akhir', 'kartu_simpanan_transaksi_kode', 'kartu_simpanan_deskripsi')
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value=false, $simpanan)
	{
		return parent::generate_kode(array(
				'pattern'       => 'KS-'.$simpanan.'.{date}.{#}',
	            'date_format'   =>'ymd',
	            'field'         =>'kartu_simpanan_kode',
	            'index_format'  =>'000',
	            'index_mask'    =>$value
		));
	}

	public function gen_kode_1($value=false, $simpanan)
	{
		return parent::generate_kode(array(
				'pattern'       => 'KS-'.$simpanan.'.{date}.{#}',
	            'date_format'   =>'201001',
	            'field'         =>'kartu_simpanan_kode',
	            'index_format'  =>'000',
	            'index_mask'    =>$value
		));
	}

	public function insert_kartu($data, $simpanan)
	{
		$ang = $this->db->select('anggota_saldo_simp_pokok, anggota_saldo_simp_manasuka, anggota_saldo_simp_wajib, anggota_saldo_simp_wajib_khusus, anggota_saldo_simp_tabungan_hari_tua, anggota_saldo_simp_titipan_belanja, anggota_saldo_voucher, anggota_saldo_bhr, anggota_saldo_simp_khusus, anggota_tagihan_bulan_last, anggota_tag_pokok, anggota_tag_manasuka, anggota_tag_wajib, anggota_tag_wajib_khusus, anggota_tag_titipan_belanja, anggota_tag_tabungan_hari_tua, anggota_tagihan_ke')
				->get_where('ms_anggota', array('anggota_id' => $data['kartu_simpanan_anggota']))->result_array();

		switch ($simpanan) {
			case 'MSK':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_manasuka']:0); break;
			case 'SP':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_pokok']:0); break;
			case 'SWK':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_wajib_khusus']:0); break;
			case 'SW':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_wajib']:0); break;
			case 'THT':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_tabungan_hari_tua']:0); break;
			case 'V':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_titipan_belanja']:0); break;
			case 'BHR':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_bhr']:0); break;
			case 'VB':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_voucher']:0); break;
			case 'KHS':
				$data['kartu_simpanan_saldo_awal'] = ($ang?$ang[0]['anggota_saldo_simp_khusus']:0); break;
			default: break;
		}
		$data['kartu_simpanan_order'] = $this->nomor_urut_kartu($simpanan, $data['kartu_simpanan_anggota']);
		
		$anggota = $ang[0];
		if(!isset($data['kartu_simpanan_saldo_akhir'])){
			$data['kartu_simpanan_saldo_akhir'] = $data['kartu_simpanan_saldo_awal']+$data['kartu_simpanan_saldo_masuk']-$data['kartu_simpanan_saldo_keluar'];
		}
		$data['kartu_simpanan_kode'] = $this->gen_kode(false, $simpanan);

		if($ang){
			switch ($simpanan) {
				case 'MSK':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_manasuka' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_manasuka']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_manasuka'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_manasuka', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "simpanan manasuka";
					$this->notifikasi_simpanan($data,$jenis);
					
				break;
				case 'SP':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_pokok' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_pokok']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_pokok'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_pokok', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "simpanan pokok";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				case 'SWK':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_wajib_khusus' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_wajib_khusus']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_wajib_khusus'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_wajib_khusus', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "simpanan wajib khusus";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				case 'SW':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_wajib' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_wajib']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_wajib'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_wajib', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "simpanan wajib";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				case 'THT':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_tabungan_hari_tua' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_tabungan_hari_tua']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_tabungan_hari_tua'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_tabungan_hari_tua', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "tabungan hari tua";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				case 'V':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_titipan_belanja' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					if($anggota['anggota_tag_titipan_belanja']==$data['kartu_simpanan_saldo_masuk']){
						$selisih = (int)$data['kartu_simpanan_saldo_masuk'] - (int)$anggota['anggota_tag_titipan_belanja'];
						$counter = 1;

						$update = $this->db->where(['anggota_id' => $data['kartu_simpanan_anggota']])
							->set('anggota_tag_titipan_belanja', $selisih)
							->set('anggota_tagihan_bulan_last', date("Y-m", strtotime("+1 months", strtotime('anggota_tagihan_bulan_last'))))
							->set('anggota_tagihan_ke', 'anggota_tagihan_ke-'.$counter)
							->update('ms_anggota');
					}
					$jenis = "titipan belanja";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				case 'VB':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_voucher' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'BHR':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_bhr' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'KHS':
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_khusus' => $data['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					$jenis = "simpanan khusus";
					$this->notifikasi_simpanan($data,$jenis);
				break;
				default: break;
			}
		}
		$data['kartu_simpanan_id'] = gen_uuid($this->get_table());		

		$this->db->insert('ksp_kartu_simpanan', $data);
		return $data['kartu_simpanan_kode'];
	}

	public function update_kartu($data, $simpanan)
	{
		$kartu_awal = $this->db->select('kartu_simpanan_id, kartu_simpanan_kode, kartu_simpanan_saldo_awal, kartu_simpanan_saldo_masuk, kartu_simpanan_saldo_keluar, kartu_simpanan_saldo_akhir, kartu_simpanan_transaksi, kartu_simpanan_transaksi_kode, kartu_simpanan_order')
				->get_where('ksp_kartu_simpanan', array('kartu_simpanan_anggota'=>$data['kartu_simpanan_anggota'], 'kartu_simpanan_transaksi'=>$data['kartu_simpanan_transaksi'], 'kartu_simpanan_referensi_id'=>$data['kartu_simpanan_referensi_id']))
				->result_array();
		/*print_r($kartu_awal);
		exit();*/
		if($kartu_awal){
			$selisih = 0;
			$kartu_awal=$kartu_awal[0];
			if($data['kartu_simpanan_saldo_masuk']>=0){
				$selisih = $data['kartu_simpanan_saldo_masuk']-$kartu_awal['kartu_simpanan_saldo_masuk'];
				$update = $this->db->where([
	        				'kartu_simpanan_transaksi' 		=> $data['kartu_simpanan_transaksi'],
	        				'kartu_simpanan_anggota'		=> $data['kartu_simpanan_anggota'],
	        				'kartu_simpanan_referensi_id'	=>$data['kartu_simpanan_referensi_id']
	        				])
						->set('kartu_simpanan_saldo_masuk', $data['kartu_simpanan_saldo_masuk'])
						->set('kartu_simpanan_saldo_akhir', 'kartu_simpanan_saldo_akhir+'.$selisih, FALSE)
						->update('ksp_kartu_simpanan');
				if($update){
	            	$after = $this->db->where([
	    					'kartu_simpanan_transaksi' 			=> $data['kartu_simpanan_transaksi'],
	    					'kartu_simpanan_anggota'			=> $data['kartu_simpanan_anggota'],
	    					'kartu_simpanan_order>'				=> $kartu_awal['kartu_simpanan_order']
	    				])
	            		->set('kartu_simpanan_keterangan', (isset($data['kartu_simpanan_keterangan'])?$data['kartu_simpanan_keterangan']:'kartu_simpanan_keterangan'), (isset($data['kartu_simpanan_keterangan'])?TRUE:FALSE))
	                	->set('kartu_simpanan_saldo_awal', 'kartu_simpanan_saldo_awal+'.$selisih, FALSE)
						->set('kartu_simpanan_saldo_akhir', 'kartu_simpanan_saldo_akhir+'.$selisih, FALSE)
						->update('ksp_kartu_simpanan');	
				}	
			}
			if($data['kartu_simpanan_saldo_keluar']>=0){
				$selisih = $data['kartu_simpanan_saldo_keluar']-$kartu_awal['kartu_simpanan_saldo_keluar'];
				$update = $this->db->where([
	        				'kartu_simpanan_transaksi' 		=> $data['kartu_simpanan_transaksi'],
	        				'kartu_simpanan_anggota'		=> $data['kartu_simpanan_anggota'],
	        				'kartu_simpanan_referensi_id'	=>$data['kartu_simpanan_referensi_id']
	        				])
						->set('kartu_simpanan_saldo_keluar', $data['kartu_simpanan_saldo_keluar'])
						->set('kartu_simpanan_saldo_akhir', 'kartu_simpanan_saldo_akhir-'.$selisih, FALSE)
						->update('ksp_kartu_simpanan');
				if($update){
	            	$after = $this->db->where([
	    					'kartu_simpanan_transaksi' 			=> $data['kartu_simpanan_transaksi'],
	    					'kartu_simpanan_anggota'			=> $data['kartu_simpanan_anggota'],
	    					'kartu_simpanan_order>'				=> $kartu_awal['kartu_simpanan_order']
	    				])
	            		->set('kartu_simpanan_keterangan', (isset($data['kartu_simpanan_keterangan'])?$data['kartu_simpanan_keterangan']:'kartu_simpanan_keterangan'), (isset($data['kartu_simpanan_keterangan'])?TRUE:FALSE))
	                	->set('kartu_simpanan_saldo_awal', 'kartu_simpanan_saldo_awal-'.$selisih, FALSE)
						->set('kartu_simpanan_saldo_akhir', 'kartu_simpanan_saldo_akhir-'.$selisih, FALSE)
						->update('ksp_kartu_simpanan');	
				}	
			}
	    	
			switch ($simpanan) {
				case 'MSK':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' 
						order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_manasuka' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
					$jenis = "simpanan manasuka";
					$this->notifikasi_edit_simpanan($data,$jenis);
				break;
				case 'SP':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_pokok' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'SWK':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_wajib_khusus' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'SW':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_wajib' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'THT':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_tabungan_hari_tua' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'V':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_titipan_belanja' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'VB':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_voucher' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'BHR':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_bhr' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				case 'KHS':
					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_simpanan WHERE 
						kartu_simpanan_transaksi = '".$data['kartu_simpanan_transaksi']."' AND
						kartu_simpanan_anggota ='".$data['kartu_simpanan_anggota']."' order by kartu_simpanan_order DESC LIMIT 1
					")->result_array();
					$this->db->where('anggota_id', $data['kartu_simpanan_anggota'])
						 ->update('ms_anggota', ['anggota_saldo_simp_khusus' => $kartu_akhir[0]['kartu_simpanan_saldo_akhir'], 'anggota_update_by'=>$this->session->userdata('pegawai_id'), 'anggota_update_at'=>date('Y-m-d H:i:s')]);
				break;
				default: break;
			}
	            
			return $after;
		}else{
			$this->insert_kartu($data, $simpanan);
		}
	}

	public function notifikasi_simpanan($data, $jenis){
		//penarikan
		if($data['kartu_simpanan_saldo_masuk']==0){
			if($jenis=="titipan belanja"){
				$nama_notifikasi = 'Penggunaan '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_keluar'],0,'','.').' berhasil dilakukan!';
			}else{
				$nama_notifikasi='Penarikan '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_keluar'],0,'','.').' berhasil dilakukan!';
			}
			$tipe_notifikasi = 'Penarikan Simpanan';
			$judul_notifikasi = 'Notifikasi Penarikan Simpanan';
			
		}else if($data['kartu_simpanan_saldo_keluar']==0){
			if(substr($data['kartu_simpanan_transaksi_kode'], 0, 3)=="SHU"){
				$nama_notifikasi = 'Transaksi setor '.$jenis.' (SHU) sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}else if(substr($data['kartu_simpanan_transaksi_kode'], 0, 2)=="JB"){
				$nama_notifikasi = 'Transaksi setor '.$jenis.' (Posting) sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}else{
				$nama_notifikasi = 'Transaksi setor '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}
			$tipe_notifikasi = 'Setor Simpanan';
			$judul_notifikasi = 'Notifikasi Setor Simpanan';
		}
		pushnotif(array(
			'sentto' 	=> $data['kartu_simpanan_anggota'],
			'tipe' 		=> $tipe_notifikasi,
			'notif_type'=> 'notif',
			'judul' 	=> $judul_notifikasi,
			'notifikasi'=> $nama_notifikasi
		));
		pushhistory(array(
			'anggota' 	=> $data['kartu_simpanan_anggota'],
			'title' 	=> $tipe_notifikasi,
			'type' 		=> 'Simpanan',
			'content' 	=> $nama_notifikasi,
			'reference' => $data['kartu_simpanan_referensi_id']
		));
	}

	public function notifikasi_edit_simpanan($data, $jenis){
		//penarikan
		if($data['kartu_simpanan_saldo_masuk']==0){
			if($jenis=="titipan belanja"){
				$nama_notifikasi = 'Penyesuaian penggunaan '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_keluar'],0,'','.').' berhasil dilakukan!';
			}else{
				$nama_notifikasi='Penyesuaian penarikan '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_keluar'],0,'','.').' berhasil dilakukan!';
			}
			$tipe_notifikasi = 'Penarikan Simpanan';
			$judul_notifikasi = 'Notifikasi Penarikan Simpanan';
			
		}else if($data['kartu_simpanan_saldo_keluar']==0){
			if(substr($data['kartu_simpanan_transaksi_kode'], 0, 3)=="SHU"){
				$nama_notifikasi = 'Penyesuaian transaksi setor '.$jenis.' (SHU) sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}else if(substr($data['kartu_simpanan_transaksi_kode'], 0, 2)=="JB"){
				$nama_notifikasi = 'Penyesuaian transaksi setor '.$jenis.' (Posting) sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}else{
				$nama_notifikasi = 'Penyesuaian transaksi setor '.$jenis.' sebesar '.number_format($data['kartu_simpanan_saldo_masuk']).' berhasil dilakukan!';
			}
			$tipe_notifikasi = 'Setor Simpanan';
			$judul_notifikasi = 'Notifikasi Setor Simpanan';
		}
		pushnotif(array(
			'sentto' 	=> $data['kartu_simpanan_anggota'],
			'tipe' 		=> $tipe_notifikasi,
			'notif_type'=> 'notif',
			'judul' 	=> $judul_notifikasi,
			'notifikasi'=> $nama_notifikasi
		));
		pushhistory(array(
			'anggota' 	=> $data['kartu_simpanan_anggota'],
			'title' 	=> $tipe_notifikasi,
			'type' 		=> 'Simpanan',
			'content' 	=> $nama_notifikasi,
			'reference' => $data['kartu_simpanan_referensi_id']
		));
	}

	public function nomor_urut_kartu($jenis_simpanan, $id_anggota)
	{
		switch ($jenis_simpanan) {
			case 'MSK':
				$simpanan = "Simpanan Manasuka"; break;
			case 'SP':
				$simpanan = "Simpanan Pokok"; break;
			case 'SWK':
				$simpanan = "Simpanan Wajib Khusus"; break;
			case 'SW':
				$simpanan = "Simpanan Wajib"; break;
			case 'THT':
				$simpanan = "Simpanan Tabungan Hari Tua"; break;
			case 'V':
				$simpanan = "Titipan Belanja"; break;
			case 'BHR':
				$simpanan = "Voucher BHR"; break;
			case 'VB':
				$simpanan = "Voucher Belanja"; break;
			default: break;
		}

		$sql = $this->db->query('
		 	SELECT * FROM `ksp_kartu_simpanan` WHERE kartu_simpanan_transaksi="'.$simpanan.'" AND kartu_simpanan_anggota="'.$id_anggota.'"
		 	ORDER BY kartu_simpanan_order DESC
		 	LIMIT 1
		')->result_array();
		if(isset($sql[0])){
			$num = $sql[0]['kartu_simpanan_order'];
			$num++;
		}else{
			$num = 1;
		}
		return $num;
	}

	public function gen_urut_cabang(){
		$this->db
		->select('kartu_simpanan_transaksi_kode as kode')
		->where('SUBSTR(kartu_simpanan_transaksi_kode,1,5)','DRUJU')
		->from('ksp_kartu_simpanan');
		$kode_simpan = $this->db->get_compiled_select();

		$this->db
		->select('kartu_pinjaman_transaksi_kode as kode')
		->where('SUBSTR(kartu_pinjaman_transaksi_kode,1,5)','DRUJU')
		->from('ksp_kartu_pinjaman');
		$kode_pinjam =  $this->db->get_compiled_select();
		$kode = $this->db->query($kode_simpan." UNION ".$kode_pinjam." ORDER BY SUBSTR(kode,14) DESC")->row_array();
		if($kode){
			$substr = substr($kode['kode'],13);
			$urut = (int)$substr+1;
			switch (strlen($urut)) {
				case 1:
				$gen = '000'.$urut;
				break;
				case 2:
				$gen = '00'.$urut;
				break;
				case 3:
				$gen = '0'.$urut;
				break;
				default:
				$gen= $urut;
				break;
			}
		}else{
			$gen = '0001';
		}
		return 'DRUJU-'.date('Y').date('m').'-'.$gen;
	}
}

/* End of file KartusimpananModel.php */
/* Location: ./application/modules/Kartusimpanan/models/KartusimpananModel.php */
