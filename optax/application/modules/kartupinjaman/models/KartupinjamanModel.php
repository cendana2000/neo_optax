<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KartupinjamanModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'ksp_kartu_pinjaman',
				'primary' => 'kartu_pinjaman_id',
				'fields' => array(
					array('name' => 'kartu_pinjaman_id'),
					array('name' => 'kartu_pinjaman_kode'),
					array('name' => 'kartu_pinjaman_tanggal'),
					array('name' => 'kartu_pinjaman_anggota'),
					array('name' => 'kartu_pinjaman_saldo_awal'),
					array('name' => 'kartu_pinjaman_saldo_pinjam'),
					array('name' => 'kartu_pinjaman_saldo_bayar'),
					array('name' => 'kartu_pinjaman_saldo_bunga'),
					array('name' => 'kartu_pinjaman_saldo_akhir'),
					array('name' => 'kartu_pinjaman_transaksi'),
					array('name' => 'kartu_pinjaman_transaksi_kode'),
					array('name' => 'kartu_pinjaman_transaksi_kwitansi'),
					array('name' => 'kartu_pinjaman_keterangan'),
					array('name' => 'kartu_pinjaman_create_by'),
					array('name' => 'kartu_pinjaman_create_at'),
					array('name' => 'kartu_pinjaman_order'),
					array('name' => 'kartu_pinjaman_referensi_id'),
					array('name' => 'kartu_pinjaman_transaksi_id'),
					array('name' => 'kartu_pinjaman_jenis'),
					array('name' => 'kartu_pinjaman_bayar_ke'),
					array('name' => 'kartu_pinjaman_tenor'),
					array('name' => 'kartu_pinjaman_cabang'),
					array('name' => 'kartu_pinjaman_cabang_kasir'),
					array('name' => 'anggota_id'			, 'view'=>true),
					array('name' => 'anggota_nama'			, 'view'=>true),
					array('name' => 'anggota_kode'			, 'view'=>true),
					array('name' => 'anggota_alamat'		, 'view'=>true),
					array('name' => 'grup_gaji_nama'		, 'view'=>true),
					array('name' => 'grup_gaji_id'			, 'view'=>true),
					array('name' => 'grup_gaji_kode'		, 'view'=>true),
					array('name' => 'pengajuan_tgl'		, 'view'=>true),
					array('name' => 'pengajuan_id'		, 'view'=>true),
				)
			),
			'view' => array(
				'name' => 'v_ksp_kartu_pinjaman',
				'mode' => array(
					'table' => array('kartu_pinjaman_id', 'kartu_pinjaman_tanggal', 'kartu_pinjaman_tenor','kartu_pinjaman_bayar_ke','kartu_pinjaman_saldo_awal','kartu_pinjaman_saldo_pinjam', 'kartu_pinjaman_saldo_bayar', 'kartu_pinjaman_saldo_akhir', 'kartu_pinjaman_transaksi_kode', 'kartu_pinjaman_transaksi')
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value=false, $jenis)
	{
		return parent::generate_kode(array(
				'pattern'       => 'KP-'.$jenis.'.{date}.{#}',
	            'date_format'   =>'ymd',
	            'field'         =>'kartu_pinjaman_kode',
	            'index_format'  =>'000',
	            'index_mask'    =>$value
		));
	}

	public function insert_kartu($data, $jenis)
	{
		$ang = $this->db->select('pengajuan_angsur_jumlah, pengajuan_angsuran, pengajuan_jasa_jumlah, pengajuan_sisa_angsuran, pengajuan_status, pengajuan_tag_bulan, pengajuan_tag_akhir')
							->get_where('ksp_pengajuan_pinjaman', array('pengajuan_id' => $data['kartu_pinjaman_referensi_id']))->result_array();
		$data['kartu_pinjaman_jenis'] = $jenis;

		if(isset($data['kartu_pinjaman_saldo_bayar'])){
			$data['kartu_pinjaman_saldo_bayar']=$data['kartu_pinjaman_saldo_bayar'];
		}else{
			$data['kartu_pinjaman_saldo_bayar'] = 0;
		}

		//saldo awal
		$data['kartu_pinjaman_saldo_awal'] = (isset($data['kartu_pinjaman_saldo_pinjam'])?0:$ang[0]['pengajuan_sisa_angsuran']);

		//jumlah jasa yang dibayar
		$pengajuan_jasa = ($ang[0]['pengajuan_jasa_jumlah']==null?$ang[0]['pengajuan_jasa_jumlah']:0);
		$jasa = $pengajuan_jasa+$data['kartu_pinjaman_saldo_bunga'];
		
		//saldo akhir
		$data['kartu_pinjaman_saldo_akhir'] = $data['kartu_pinjaman_saldo_awal']+$data['kartu_pinjaman_saldo_pinjam']-$data['kartu_pinjaman_saldo_bayar'];

		//cicilan ke
		$pengajuan_angsur = ($ang[0]['pengajuan_angsuran']==NULL?1:$ang[0]['pengajuan_angsuran']);
		$cicilan = $pengajuan_angsur+1;

		//sisa angusran
		if($data['kartu_pinjaman_saldo_awal']==0){
			$sisa_angsuran = $data['kartu_pinjaman_saldo_pinjam'];
		}else{
			$sisa_angsuran = $data['kartu_pinjaman_saldo_awal']-$data['kartu_pinjaman_saldo_bayar'];
		}

		$data['kartu_pinjaman_kode'] = $this->gen_kode(false, $jenis);

		$data['kartu_pinjaman_order'] = $this->nomor_urut_kartu($data['kartu_pinjaman_transaksi_kode'], $data['kartu_pinjaman_anggota']);

		if($ang){
			/*$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
				 ->update('ksp_pengajuan_pinjaman', [
				 	'pengajuan_angsur_jumlah' 	=> $data['kartu_pinjaman_saldo_bayar'], 
				 	'pengajuan_jasa_jumlah'		=> $jasa,
				 	'pengajuan_angsuran'		=> $cicilan,
				 	'pengajuan_sisa_angsuran'	=> $sisa_angsuran,
				 	'pengajuan_updated_by'		=> $this->session->userdata('pegawai_nama'), 
				 	'pengajuan_updated_at'		=> date('Y-m-d H:m:s')
			]);*/
			$this->db->where(['pengajuan_id' => $data['kartu_pinjaman_referensi_id']])
				->set('pengajuan_angsur_jumlah', 'pengajuan_angsur_jumlah+'.$data['kartu_pinjaman_saldo_bayar'], FALSE)
				->set('pengajuan_jasa_jumlah', 'pengajuan_jasa_jumlah+'.$jasa)
				// ->set('pengajuan_angsuran', $cicilan)
				->set('pengajuan_sisa_angsuran', $sisa_angsuran)
				->set('pengajuan_updated_by', $this->session->userdata('pegawai_nama'))
				->set('pengajuan_updated_at', date('Y-m-d H:m:s'))
				->update('ksp_pengajuan_pinjaman');

			//agar masuk sesuai tagihan
			if($data['kartu_pinjaman_saldo_bayar']>0){
				if((int)$data['kartu_pinjaman_saldo_bayar']==(int)$ang[0]['pengajuan_pokok_bulanan']){
					$this->db->where(['pengajuan_id' => $data['kartu_pinjaman_referensi_id']])
						->set('pengajuan_angsuran', $cicilan)
						->set('pengajuan_tunggakan_pokok', 'pengajuan_tunggakan_pokok-'.$data['kartu_pinjaman_saldo_bayar'], FALSE)
						->set('pengajuan_tunggakan_jasa', 'pengajuan_tunggakan_jasa-'.$data['kartu_pinjaman_saldo_bunga'], FALSE)
						->set('pengajuan_tag_akhir', 'pengajuan_tag_akhir-'.$ang[0]['pengajuan_tag_akhir'])
						->set('pengajuan_tag_bulan', date("Y-m", strtotime("+1 months", strtotime($ang[0]['pengajuan_tag_bulan']))))
						->update('ksp_pengajuan_pinjaman');
				}else if ((int)$data['kartu_pinjaman_saldo_bayar']>(int)$ang[0]['pengajuan_pokok_bulanan']){
					$this->db->where(['pengajuan_id' => $data['kartu_pinjaman_referensi_id']])
						->set('pengajuan_angsuran', $cicilan)
						->set('pengajuan_tag_akhir', 'pengajuan_tag_akhir-'.$ang[0]['pengajuan_tag_akhir'])
						// ->set('pengajuan_tag_bulan', date("Y-m", strtotime("+1 months", strtotime($ang[0]['pengajuan_tag_bulan']))))
						->update('ksp_pengajuan_pinjaman');
				}
			}

			//Jika sudah lunas
			if($data['kartu_pinjaman_saldo_akhir']==0){
				$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
						 ->update('ksp_pengajuan_pinjaman', [
				 			'pengajuan_status' 			=> 4, 
				 			'pengajuan_tag_akhir'		=> 0,
				 			'pengajuan_tunggakan_pokok'	=> 0,
				 			'pengajuan_tunggakan_jasa'	=> 0
				]);				
			}
		}
		if(empty($data['kartu_pinjaman_id'])){
			$data['kartu_pinjaman_id'] = gen_uuid($this->get_table());
		}
	
		$this->db->insert('ksp_kartu_pinjaman', $data);	
		return $data['kartu_pinjaman_kode'];
		
	}

	public function update_kartu($data, $jenis)
	{
		$kartu_awal = $this->db->select('kartu_pinjaman_id, kartu_pinjaman_kode, kartu_pinjaman_saldo_awal, kartu_pinjaman_saldo_pinjam, kartu_pinjaman_saldo_bayar, kartu_pinjaman_saldo_bunga, kartu_pinjaman_saldo_akhir, kartu_pinjaman_transaksi, kartu_pinjaman_transaksi_kode, kartu_pinjaman_order, kartu_pinjaman_bayar_ke, kartu_pinjaman_referensi_id, kartu_pinjaman_transaksi_id, kartu_pinjaman_anggota')
					 ->get_where('ksp_kartu_pinjaman', array(
					 	'kartu_pinjaman_referensi_id' 	=> $data['kartu_pinjaman_referensi_id'], 
					 	'kartu_pinjaman_transaksi_id' 	=> $data['kartu_pinjaman_transaksi_id'], 
					 	// 'kartu_pinjaman_transaksi_kode'	=>$data['kartu_pinjaman_transaksi_kode'],
					 	'kartu_pinjaman_anggota'		=>$data['kartu_pinjaman_anggota']))->result_array();

		
		if($kartu_awal){
			$selisih = 0;
			$kartu_awal = $kartu_awal[0];
			//edit ketika realisasi baik di penjualan maupun di usp
			if(isset($data['kartu_pinjaman_saldo_pinjam'])&&$data['kartu_pinjaman_saldo_bayar']==0){
				$selisih = $data['kartu_pinjaman_saldo_pinjam']-$kartu_awal['kartu_pinjaman_saldo_pinjam'];

				$update = $this->db->where([
								'kartu_pinjaman_referensi_id'		=> $data['kartu_pinjaman_referensi_id'],
								'kartu_pinjaman_transaksi_id'		=> $data['kartu_pinjaman_transaksi_id'],
								// 'kartu_pinjaman_transaksi_kode'		=> $data['kartu_pinjaman_transaksi_kode'],
								'kartu_pinjaman_anggota'			=> $data['kartu_pinjaman_anggota'],
							])
							->set('kartu_pinjaman_saldo_pinjam', $data['kartu_pinjaman_saldo_pinjam'])
							->set('kartu_pinjaman_tenor', $data['kartu_pinjaman_tenor'])
							->set('kartu_pinjaman_saldo_akhir', 'kartu_pinjaman_saldo_akhir+'.$selisih, FALSE)
							->update('ksp_kartu_pinjaman');
				
				$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
					 ->update('ksp_pengajuan_pinjaman', [
					 	'pengajuan_sisa_angsuran'	=> $data['kartu_pinjaman_saldo_pinjam'],
					 	'pengajuan_updated_by'		=> $this->session->userdata('pegawai_nama'), 
					 	'pengajuan_updated_at'		=> date('Y-m-d H:m:s')
				]);
			}

			//edit ketika trx pembayaran
			if(isset($data['kartu_pinjaman_saldo_bayar'])&&$data['kartu_pinjaman_saldo_bayar']>0){
				$selisih = $data['kartu_pinjaman_saldo_bayar']-$kartu_awal['kartu_pinjaman_saldo_bayar'];
				$update = $this->db->where([
								'kartu_pinjaman_referensi_id'		=> $data['kartu_pinjaman_referensi_id'],
								'kartu_pinjaman_transaksi_id'		=> $data['kartu_pinjaman_transaksi_id'],
								'kartu_pinjaman_transaksi_kode'		=> $data['kartu_pinjaman_transaksi_kode'],
								'kartu_pinjaman_anggota'			=> $data['kartu_pinjaman_anggota'],
							])
							->set('kartu_pinjaman_saldo_bayar', $data['kartu_pinjaman_saldo_bayar'])
							->set('kartu_pinjaman_saldo_bunga', $data['kartu_pinjaman_saldo_bunga'])
							->set('kartu_pinjaman_saldo_akhir', 'kartu_pinjaman_saldo_akhir-'.$selisih, FALSE)
							->update('ksp_kartu_pinjaman');

				if($selisih<0){
					$after = $this->db->where([
								'kartu_pinjaman_referensi_id'		=> $data['kartu_pinjaman_referensi_id'],
								'kartu_pinjaman_transaksi_id'		=> $data['kartu_pinjaman_transaksi_id'],
		    					'kartu_pinjaman_transaksi_kode' 	=> $data['kartu_pinjaman_transaksi_kode'],
		    					'kartu_pinjaman_anggota'			=> $data['kartu_pinjaman_anggota'],
		    					'kartu_pinjaman_bayar_ke>'		=> $kartu_awal['kartu_pinjaman_bayar_ke']
		    				])
		            		->set('kartu_pinjaman_keterangan', (isset($data['kartu_pinjaman_keterangan'])?$data['kartu_pinjaman_keterangan']:'kartu_pinjaman_keterangan'), (isset($data['kartu_pinjaman_keterangan'])?TRUE:FALSE))
		                	->set('kartu_pinjaman_saldo_awal', 'kartu_pinjaman_saldo_awal-'.$selisih, FALSE)
							->set('kartu_pinjaman_saldo_akhir', 'kartu_pinjaman_saldo_akhir-'.$selisih, FALSE)
							->update('ksp_kartu_pinjaman');	

					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_pinjaman WHERE 
						kartu_pinjaman_anggota ='".$data['kartu_pinjaman_anggota']."' AND
						kartu_pinjaman_referensi_id='".$data['kartu_pinjaman_referensi_id']."' AND
						kartu_pinjaman_transaksi_id='".$data['kartu_pinjaman_transaksi_id']."' 
						order by kartu_pinjaman_bayar_ke DESC LIMIT 1
					")->result_array();
			
					$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
						 ->update('ksp_pengajuan_pinjaman', [
						 	'pengajuan_status'			=> '2',
						 	'pengajuan_sisa_angsuran'	=> $kartu_akhir[0]['kartu_pinjaman_saldo_akhir'],
						 	'pengajuan_updated_by'		=> $this->session->userdata('pegawai_nama'), 
						 	'pengajuan_updated_at'		=> date('Y-m-d H:i:s')
					]);
				}else{
					$after = $this->db->where([
								'kartu_pinjaman_referensi_id'		=> $data['kartu_pinjaman_referensi_id'],
								'kartu_pinjaman_transaksi_id'		=> $data['kartu_pinjaman_transaksi_id'],
		    					'kartu_pinjaman_transaksi_kode' 	=> $data['kartu_pinjaman_transaksi_kode'],
		    					'kartu_pinjaman_anggota'			=> $data['kartu_pinjaman_anggota'],
		    					'kartu_pinjaman_bayar_ke>'		=> $kartu_awal['kartu_pinjaman_bayar_ke']
		    				])
		            		->set('kartu_pinjaman_keterangan', (isset($data['kartu_pinjaman_keterangan'])?$data['kartu_pinjaman_keterangan']:'kartu_pinjaman_keterangan'))
		                	->set('kartu_pinjaman_saldo_awal', 'kartu_pinjaman_saldo_awal+'.$selisih)
							->set('kartu_pinjaman_saldo_akhir', 'kartu_pinjaman_saldo_akhir+'.$selisih)
							->update('ksp_kartu_pinjaman');	

					$kartu_akhir = $this->db->query("SELECT * FROM ksp_kartu_pinjaman WHERE 
						kartu_pinjaman_anggota ='".$data['kartu_pinjaman_anggota']."' AND
						kartu_pinjaman_referensi_id='".$data['kartu_pinjaman_referensi_id']."' AND
						kartu_pinjaman_transaksi_id='".$data['kartu_pinjaman_transaksi_id']."' 
						order by kartu_pinjaman_bayar_ke DESC LIMIT 1
					")->result_array();
			
					$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
						 ->update('ksp_pengajuan_pinjaman', [
						 	'pengajuan_angsuran'		=> $kartu_akhir[0]['kartu_pinjaman_bayar_ke']+1,
						 	'pengajuan_sisa_angsuran'	=> $kartu_akhir[0]['kartu_pinjaman_saldo_akhir'],
						 	'pengajuan_updated_by'		=> $this->session->userdata('pegawai_nama'), 
						 	'pengajuan_updated_at'		=> date('Y-m-d H:i:s')
					]);

					if($kartu_akhir[0]['kartu_pinjaman_saldo_akhir']==0){
						$this->db->where('pengajuan_id', $data['kartu_pinjaman_referensi_id'])
								 ->update('ksp_pengajuan_pinjaman', [
						 			'pengajuan_status' 			=> 4, 
						 			'pengajuan_tag_akhir'		=> 0,
						 			'pengajuan_tunggakan_pokok'	=> 0,
						 			'pengajuan_tunggakan_jasa'	=> 0
						]);				
					}
				}
			}
		}else{
			$this->insert_kartu($data, $jenis);
		}
	}

	public function nomor_urut_kartu($kode, $id_anggota)
	{
		$sql = $this->db->query('
		 	SELECT * FROM `ksp_kartu_pinjaman` WHERE kartu_pinjaman_transaksi_kode="'.$kode.'" AND kartu_pinjaman_anggota="'.$id_anggota.'"
		 	ORDER BY kartu_pinjaman_order DESC
		 	LIMIT 1
		')->result_array();
		if($sql[0]){
			$num = $sql[0]['kartu_pinjaman_order'];
			$num++;
		}else{
			$num = 1;
		}
		return $num;
	}
}

/* End of file KartupinjamanModel.php */
/* Location: ./application/modules/Kartupinjaman/models/KartupinjamanModel.php */