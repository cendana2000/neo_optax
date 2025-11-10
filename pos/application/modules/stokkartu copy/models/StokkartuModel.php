<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StokkartuModel extends Base_Model {
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_kartu_stok',
				'primary' => 'kartu_id',
				'fields' => array(
					array('name' => 'kartu_id'),
					array('name' => 'kartu_kode'),
					array('name' => 'kartu_tanggal'),
					array('name' => 'kartu_barang_id'),
					array('name' => 'kartu_satuan_id'),
					array('name' => 'kartu_stok_awal'),
					array('name' => 'kartu_stok_masuk'),
					array('name' => 'kartu_stok_keluar'),
					array('name' => 'kartu_stok_akhir'),
					array('name' => 'kartu_harga'),
					array('name' => 'kartu_harga_transaksi'),
					array('name' => 'kartu_nilai'),
					array('name' => 'kartu_transaksi'),
					array('name' => 'kartu_transaksi_kode'),
					array('name' => 'kartu_user'),
					array('name' => 'kartu_created'),
					array('name' => 'kartu_updated'),
					array('name' => 'kartu_updated_by'),
					array('name' => 'barang_kode', 	'view'=>true),
					array('name' => 'barang_nama', 	'view'=>true),
					array('name' => 'satuan_kode', 	'view'=>true),
				)
			),	
			'view' => array(
				'name' => 'v_pos_kartu_stok',
				'mode' => array(
					'table' => [
						'kartu_id',
						'kartu_tanggal',
						'barang_nama',
						// 'satuan_kode',
						'kartu_stok_awal',
						'kartu_stok_masuk',
						'kartu_stok_keluar',
						'kartu_stok_akhir',
						'kartu_harga_transaksi',
						'kartu_nilai',
						'kartu_harga',
						'kartu_transaksi',
						'kartu_kode',
						'kartu_transaksi_kode',
					],
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function gen_kode($value=false, $trans)
	{
		return parent::generate_kode(array(
				'pattern'       => 'KS-.{date}.{#}',
	            'date_format'   =>'ymd',
	            'field'         =>'kartu_kode',
	            'index_format'  =>'0000',
	            'index_mask'    =>$value
		));
	}

	public function insert_kartu_new($data, $trans)
	{
		$br = $this->db->select('barang_stok, barang_satuan')
						->get_where('ms_barang', array('barang_id' => $data['kartu_barang_id']))
						->result_array();
		$gd = $this->db->select('*')
						->get_where('pos_posting_saldo_detail', array('posting_detail_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $this->config->item('base_gudang')))
						->result_array();
		if($br){
			$barang = $br[0];
			$gudang = $data['kartu_gudang_id'];
			unset($data['kartu_gudang_id']);
			$data['kartu_stok_awal'] = $barang['barang_stok'];
			// $data['kartu_satuan_id'] = $barang['barang_satuan'];
			$data['kartu_stok_akhir'] = $data['kartu_stok_akhir'] ?? ($data['kartu_stok_awal']+$data['kartu_stok_masuk']-$data['kartu_stok_keluar']);
			$data['kartu_kode'] = $this->gen_kode(false, $trans);
			$opt = $this->db->insert('pos_kartu_stok', $data);
			$this->db->where('barang_id', $data['kartu_barang_id'])
					 ->update('ms_barang', ['barang_stok' => $data['kartu_stok_akhir'], 'barang_harga_pokok' => $data['kartu_harga']]);
			if($gd){
				$this->db->where('stok_id', $gd[0]['stok_id'])
					 ->update('pos_stok', [
					 	'stok_qty' 			=> $data['kartu_stok_akhir'],
						'stok_updated'		=> date('Y-m-d H:i:s'),
						'stok_user_id'		=> $this->session->userdata('user_id'),
						'stok_satuan_id'	=> $data['kartu_satuan_id'],
					 ]);			
			}else{
				$this->db->insert('pos_stok', array(
					'stok_id' 			=> gen_uuid($this->get_table()),
					'stok_barang_id' 	=> $data['kartu_barang_id'],
					'stok_gudang_id' 	=> $this->config->item('base_gudang'),
					'stok_qty' 			=> $data['kartu_stok_akhir'],
					'stok_updated'		=> date('Y-m-d H:i:s'),
					'stok_user_id'		=> $this->session->userdata('user_id'),
					'stok_satuan_id'	=> $data['kartu_satuan_id'],
				));	
					// 'stok_user_nama'	=> $this->session->userdata('user_id'),
					// 'stok_kategori_id' 	=> $this->config->item('base_gudang'),
					// 'stok_kategori_parent' 	=> $this->config->item('base_gudang'),
			}

			if($data['kartu_transaksi'] == 'Mutasi'){
				$gd_m = $this->db->select('*')
					->get_where('pos_stok', array('stok_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $gudang))
					->result_array();
				if($gd_m){
					$this->db->where('stok_id', $gd_m[0]['stok_id'])
						->set('stok_qty', 'stok_qty+'.$data['kartu_stok_keluar'], FALSE)
						->set('stok_updated', date('Y-m-d H:i:s'))
						->set('stok_user_id', $this->session->userdata('user_id'))
						->set('stok_satuan_id', $data['kartu_satuan_id'])
						->update('pos_stok');
						 /*, [
						 	'stok_qty' 			=> $data['kartu_stok_akhir'],
							'stok_updated'		=> date('Y-m-d H:i:s'),
							'stok_user_id'		=> $this->session->userdata('user_id'),
							'stok_satuan_id'	=> $data['kartu_satuan_id'],
						 ]);*/

				}else{
					$this->db->insert('pos_stok', array(
						'stok_id' 			=> gen_uuid($this->get_table()),
						'stok_barang_id' 	=> $data['kartu_barang_id'],
						'stok_gudang_id' 	=> $gudang,
						'stok_qty' 			=> $data['kartu_stok_keluar'],
						'stok_updated'		=> date('Y-m-d H:i:s'),
						'stok_user_id'		=> $this->session->userdata('user_id'),
						'stok_satuan_id'	=> $data['kartu_satuan_id'],
					));	
				}
			}
			// $data['kartu_id'] = gen_uuid($this->get_table());
			return $opt;
		}
	}


	public function insert_kartu($data, $trans)
	{
		$br = $this->db->select('barang_stok, barang_satuan, barang_kategori_barang')
						->get_where('ms_barang', array('barang_id' => $data['kartu_barang_id']))
						->result_array();
		$gd = $this->db->select('*')
						->get_where('pos_stok', array('stok_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $this->config->item('base_gudang'), 'stok_periode' => date('Y-m',strtotime($data['kartu_tanggal']))))
						->result_array();
		if($br){
			$barang = $br[0];
			$gudang = $data['kartu_gudang_id'];
			unset($data['kartu_gudang_id']);
			$data['kartu_stok_awal'] = $barang['barang_stok'];
			// $data['kartu_satuan_id'] = $barang['barang_satuan'];
			// $data['kartu_stok_akhir'] = $data['kartu_stok_awal']+$data['kartu_stok_masuk']-$data['kartu_stok_keluar'];
			$data['kartu_stok_akhir'] = $data['kartu_stok_akhir'] ?? ($data['kartu_stok_awal']+$data['kartu_stok_masuk']-$data['kartu_stok_keluar']);
			$data['kartu_kode'] = $this->gen_kode(false, $trans);
			$opt = $this->db->insert('pos_kartu_stok', $data);
			$this->db->where('barang_id', $data['kartu_barang_id'])
					 ->update('ms_barang', ['barang_stok' => $data['kartu_stok_akhir'], 'barang_harga_pokok' => $data['kartu_harga']]);
			if($gd){
				$this->db->where('stok_id', $gd[0]['stok_id'])
					 ->update('pos_stok', [
					 	'stok_qty' 			=> $data['kartu_stok_akhir'],
						'stok_updated'		=> date('Y-m-d H:i:s'),
						'stok_user_id'		=> $this->session->userdata('user_id'),
						'stok_satuan_id'	=> $data['kartu_satuan_id'],
					 ]);
			}else{
				$this->db->insert('pos_stok', array(
					'stok_id' 			=> gen_uuid($this->get_table()),
					'stok_barang_id' 	=> $data['kartu_barang_id'],
					'stok_kategori_barang'	=> $br['barang_kategori_barang'],
					'stok_periode' 		=> date('Y-m',strtotime($data['kartu_tanggal'])),
					'stok_gudang_id' 	=> $this->config->item('base_gudang'),
					'stok_qty' 			=> $data['kartu_stok_akhir'],
					'stok_updated'		=> date('Y-m-d H:i:s'),
					'stok_user_id'		=> $this->session->userdata('user_id'),
					'stok_satuan_id'	=> $data['kartu_satuan_id'],
				));	
					// 'stok_user_nama'	=> $this->session->userdata('user_id'),
					// 'stok_kategori_id' 	=> $this->config->item('base_gudang'),
					// 'stok_kategori_parent' 	=> $this->config->item('base_gudang'),
			}

			if($data['kartu_transaksi'] == 'Mutasi'){
				$gd_m = $this->db->select('*')
					->get_where('pos_stok', array('stok_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $gudang))
					->result_array();
				if($gd_m){
					$this->db->where('stok_id', $gd_m[0]['stok_id'])
						->set('stok_qty', 'stok_qty+'.$data['kartu_stok_keluar'], FALSE)
						->set('stok_updated', date('Y-m-d H:i:s'))
						->set('stok_user_id', $this->session->userdata('user_id'))
						->set('stok_satuan_id', $data['kartu_satuan_id'])
						->update('pos_stok');
						 /*, [
						 	'stok_qty' 			=> $data['kartu_stok_akhir'],
							'stok_updated'		=> date('Y-m-d H:i:s'),
							'stok_user_id'		=> $this->session->userdata('user_id'),
							'stok_satuan_id'	=> $data['kartu_satuan_id'],
						 ]);*/
				}else{
					$this->db->insert('pos_stok', array(
						'stok_id' 			=> gen_uuid($this->get_table()),
						'stok_barang_id' 	=> $data['kartu_barang_id'],
						'stok_gudang_id' 	=> $gudang,
						'stok_qty' 			=> $data['kartu_stok_keluar'],
						'stok_updated'		=> date('Y-m-d H:i:s'),
						'stok_user_id'		=> $this->session->userdata('user_id'),
						'stok_satuan_id'	=> $data['kartu_satuan_id'],
					));	
				}
			}
			// $data['kartu_id'] = gen_uuid($this->get_table());
			return $opt;
		}
	}

	public function update_kartu($data, $trans)
	{
		$succes = true;
		/*else{
		}*/
		$kartu_awal = $this->db->select('kartu_id, kartu_kode, kartu_barang_id, kartu_stok_awal, kartu_stok_masuk, kartu_stok_keluar, kartu_stok_akhir, kartu_harga, kartu_transaksi, kartu_transaksi_kode')
				 				->get_where('pos_kartu_stok', array('kartu_id' => $data['kartu_id']))->result_array();
		$gd = $this->db->select('*')
						->get_where('pos_stok', array('stok_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $this->config->item('base_gudang')))
						->result_array();
		if($kartu_awal){
			$selisih = 0;
			$kartu_awal = $kartu_awal[0];
            $opname = true;

            if($data['kartu_transaksi'] == 'Opname'){   
		    	if(isset($kartu_awal['kartu_stok_awal'])){ 
					$kartu_awal['kartu_stok_akhir'] = $kartu_awal['kartu_stok_akhir'];
				}        	
	            $selisih = $data['kartu_stok_akhir']-$kartu_awal['kartu_stok_akhir']; 
	            // 84-83
	            if($selisih !== 0){
					$update = $this->db->where('kartu_id', $data['kartu_id']);
	               	$update->set('kartu_stok_akhir', 'kartu_stok_akhir+'.$selisih, FALSE)
							->set('kartu_stok_masuk', $data['kartu_stok_masuk'])
							->set('kartu_stok_keluar', $data['kartu_stok_keluar'])
	               			// ->set('kartu_stok_akhir', 'kartu_stok_akhir+'.$selisih, FALSE)
	               			->set('kartu_updated', date('Y-m-d H:i:s'))
	               			->set('kartu_updated_by', $this->session->userdata('user_id').'-'.$this->session->userdata('pegawai_nama'))
	               			->set('kartu_keterangan', (isset($data['kartu_keterangan'])?$data['kartu_keterangan']:'kartu_keterangan'), (isset($data['kartu_keterangan'])?TRUE:FALSE))
							->update('pos_kartu_stok');
	                if($update){
	                	$after = $this->db->where([
			        				'kartu_kode>' => $kartu_awal['kartu_kode'],
			    					'kartu_barang_id' => $data['kartu_barang_id']
		    					])
	                			->set('kartu_stok_awal', 'kartu_stok_awal+'.$selisih, FALSE)
								->set('kartu_stok_akhir', 'kartu_stok_akhir+'.$selisih, FALSE)
								->update('pos_kartu_stok');
	                }
	            }
	        }else{
	            if(isset($data['kartu_stok_masuk'])){
					$selisih = $data['kartu_stok_masuk']-$kartu_awal['kartu_stok_masuk'];
		            if($selisih !== 0){
						$update = $this->db->where('kartu_id', $data['kartu_id'])
									->set('kartu_stok_masuk', $data['kartu_stok_masuk'])
									->set('kartu_harga', $data['kartu_harga'])
									->set('kartu_harga_transaksi', $data['kartu_harga_transaksi'])
			               			->set('kartu_updated', date('Y-m-d H:i:s'))
			               			->set('kartu_updated_by', $this->session->userdata('user_id').'-'.$this->session->userdata('pegawai_nama'))
									->set('kartu_keterangan', (isset($data['kartu_keterangan'])?$data['kartu_keterangan']:'kartu_keterangan'), (isset($data['kartu_keterangan'])?TRUE:FALSE))
									->set('kartu_stok_akhir', 'kartu_stok_akhir+'.$selisih, FALSE)
									->update('pos_kartu_stok');
		                if($update){
		                	$after = $this->db->where([
			        				'kartu_kode>' => $kartu_awal['kartu_kode'],
			    					'kartu_barang_id' => $data['kartu_barang_id']
			    				])
		                    	->set('kartu_stok_awal', 'kartu_stok_awal+'.$selisih, FALSE)
								->set('kartu_stok_akhir', 'kartu_stok_akhir+'.$selisih, FALSE)
								->update('pos_kartu_stok');
		                }
		            }
	            }
	            if(isset($data['kartu_stok_keluar'])){
	            	$selisih = $data['kartu_stok_keluar']-$kartu_awal['kartu_stok_keluar'];            
		            if($selisih !== 0){
						$update = $this->db->where('kartu_id', $data['kartu_id'])
									->set('kartu_stok_keluar', $data['kartu_stok_keluar'])
									->set('kartu_harga', $data['kartu_harga'])
									->set('kartu_harga_transaksi', $data['kartu_harga_transaksi'])
			               			->set('kartu_updated', date('Y-m-d H:i:s'))
			               			->set('kartu_updated_by', $this->session->userdata('user_id').'-'.$this->session->userdata('pegawai_nama'))
									->set('kartu_stok_akhir', 'kartu_stok_akhir-'.$selisih, FALSE)
									->update('pos_kartu_stok');
		                if($update){
		                	$after = $this->db->where([
				        				'kartu_kode>' => $kartu_awal['kartu_kode'],
				    					'kartu_barang_id' => $data['kartu_barang_id']
			    					])
			                		->set('kartu_keterangan', (isset($data['kartu_keterangan'])?$data['kartu_keterangan']:'kartu_keterangan'), (isset($data['kartu_keterangan'])?TRUE:FALSE))
			                    	->set('kartu_stok_awal', 'kartu_stok_awal-'.$selisih, FALSE)
									->set('kartu_stok_akhir', 'kartu_stok_akhir-'.$selisih, FALSE)
									->update('pos_kartu_stok');
		                }
						$selisih = -$selisih;
		            }
		        }
		    }
			$test = $this->db->where('barang_id', $kartu_awal['kartu_barang_id'])
				->set('barang_stok','barang_stok+'.$selisih, FALSE)
				->update('ms_barang');
			if($gd){
				$this->db->where('stok_id', $gd[0]['stok_id'])
					->set('stok_qty','stok_qty+'.$selisih, FALSE)
					->update('pos_stok');
			}

			if($data['kartu_transaksi'] == 'Mutasi'){
				$gd_m = $this->db->select('*')
					->get_where('pos_stok', array('stok_barang_id' => $data['kartu_barang_id'], 'stok_gudang_id' => $gudang))
					->result_array();
				if($gd_m){
					$this->db->where('stok_id', $gd_m[0]['stok_id'])
						->set('stok_qty', 'stok_qty+'.$data['selisih'], FALSE)
						->set('stok_updated', date('Y-m-d H:i:s'))
						->set('stok_user_id', $this->session->userdata('user_id'))
						->update('pos_stok');
						 /*, [
						 	'stok_qty' 			=> $data['kartu_stok_akhir'],
							'stok_updated'		=> date('Y-m-d H:i:s'),
							'stok_user_id'		=> $this->session->userdata('user_id'),
							'stok_satuan_id'	=> $data['kartu_satuan_id'],
						 ]);*/
				}
			}
			return $test;
        }else{
		 	return array('succes' => true);
		}
	}
}
