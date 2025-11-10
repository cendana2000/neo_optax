<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Returpenjualan extends Base_Controller {

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
		$this->response(
			$this->select_dt(varPost(),'returpenjualan','table')
		);
	}

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(),'transaksipenjualandetail','table')
		);
	}

	public function select_penjualan($value='')
	{
		$data = varPost();
		$data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';
		$total = $this->db->query('SELECT count(penjualan_id) total FROM v_pos_penjualan WHERE concat(penjualan_tanggal,penjualan_kode, COALESCE(customer_nama, "")) like "%'.$data['q'].'%"')->result_array();
		// echo $this->db->last_query();exit;
		$return = $this->db->query('SELECT penjualan_id as id, concat(DATE_FORMAT(penjualan_tanggal, "%d-%m-%Y"),"/", penjualan_kode, COALESCE(concat(" - ", customer_nama), "")) as text FROM v_pos_penjualan WHERE concat(penjualan_tanggal, penjualan_kode, COALESCE(customer_nama, "")) like "%'.$data['q'].'%" order by penjualan_created desc LIMIT '.$data['page'].$data['limit'])->result_array();
		$this->response(array('items'=>$return, 'total_count'=>$total[0]['total'], 'qr'=> $this->db->last_query()));
	}

	function read($value='')
	{
		$this->response($this->returpenjualan->read(varPost()));
	}

	public function store()
	{
		$data = varPost();
		$data['retur_penjualan_kode'] = $this->returpenjualan->gen_kode_penjualan();
		$data['retur_penjualan_user'] = $this->session->userdata('pegawai_id');
		$data['retur_penjualan_created'] = date('Y-m-d H:i:s');
		$data['retur_penjualan_aktif'] = '1';
		$operation = $this->returpenjualan->insert(gen_uuid($this->returpenjualan->get_table()), $data, function($res) use ($data)
		{
			$detail = [];
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
					'retur_penjualan_detail_order' 		=> $key,
				];

				$det_opr = $this->db->insert('pos_retur_penjualan_detail', $detail);				
				if(!$det_opr) $error[] = ['cannot insert dt'.$value => $detail];
				else{
					$up_jual_detail = $this->db->where('penjualan_detail_id', $data['retur_penjualan_detail_detail_id'][$key])
					->set('penjualan_detail_retur','penjualan_detail_retur+'.$data['retur_penjualan_detail_qty'][$key], FALSE)
					->update('pos_penjualan_detail');
					$kartu = $this->stokkartu->insert_kartu([
						'kartu_id' 			=> $detail['retur_penjualan_detail_id'],
						'kartu_tanggal' 	=> $data['retur_penjualan_tanggal'],
						'kartu_barang_id' 	=> $data['retur_penjualan_detail_barang_id'][$key],
						'kartu_satuan_id' 	=> $data['retur_penjualan_detail_satuan'][$key],
						'kartu_stok_masuk' 	=> $data['retur_penjualan_detail_retur_qty_barang'][$key],
						'kartu_stok_keluar' => 0,
						'kartu_transaksi' 	=> 'Retur Penjualan',
						'kartu_keterangan' 	=> 'ON Insert',
						'kartu_harga'		=> $data['retur_penjualan_detail_harga'][$key],
						'kartu_transaksi_kode' => $data['retur_penjualan_kode'],
						'kartu_user' 		=> $data['retur_penjualan_user'],
						'kartu_created' 	=> date('Y-m-d H:i:s'),
					], 'RJ');
					if(!$kartu) $error[] = [$kartu, $data['retur_penjualan_kode'], $value];
				}
				$n++;
				if(!$det_opr['success']) $res['res'][] = $det_opr;
			}
			$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur','penjualan_total_retur+'.$data['retur_penjualan_total'][$key], FALSE)
			->update('pos_penjualan');
		});
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$last_retur =$this->returpenjualan->read($data['retur_penjualan_id']);
		$last_retur_detail =$this->returpenjualan->select(['filters_static'=>['retur_penjualan_id' =>$data['retur_penjualan_id']]]);
		$operation = $this->returpenjualan->update($data['retur_penjualan_id'], $data, function(&$res) use ($data)
		{
			$detail = $id_detail = [];
			$last_detail = $this->returpenjualandetail->select(array('filters_static' => array('retur_penjualan_detail_parent' => $data['retur_penjualan_id']), 'sort_static' => 'retur_penjualan_detail_order asc'))['data'];
			$delete = $last_detail;
			foreach ($data['retur_penjualan_detail_barang_id'] as $key => $value) {
				$detail = [
					'retur_penjualan_detail_id' 		=> $data['retur_penjualan_detail_detail_id'][$key],
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
					'retur_penjualan_detail_order' 		=> $key,
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
					'kartu_created' 	=> date('Y-m-d H:i:s'),
				];
				foreach ($last_detail as $i => $v) {
					if($v['retur_penjualan_detail_id'] == $data['retur_penjualan_detail_id'][$key]) unset($delete[$i]);
				}
				$res_detail = $this->returpenjualandetail->update($data['retur_penjualan_detail_id'][$key], $detail);
                if(!$res_detail['success']){
                	$kartu['kartu_id'] = $detail_id = gen_uuid($this->returpenjualandetail->get_table());
                	$kartu['kartu_keterangan'] = 'Insert On Updated';
                	$kartu['kartu_stok_keluar'] = 0;
					$res_detail = $this->returpenjualandetail->insert($detail_id, $detail);
					// if($res_detail['success']) $id_detail[] = $res_detail['id'];
					if($res_detail['success']){
						$kartu = $this->stokkartu->insert_kartu($kartu, 'RJ');
						$id_detail[] = $res_detail['id'];
					}
                }else{
					$kartu = $this->stokkartu->update_kartu($kartu, 'RJ');
                	$id_detail[] = $res_detail['id'];
                }
                $id = implode(', ', $id_detail);
                $res['id_detail'] = $id;

				foreach ($delete as $n => $value) {
					$del = $this->returpenjualandetail->delete($value['retur_penjualan_detail_id']);
					if($del['success']){
						$kartu = [
							'kartu_id' 				=> $value['retur_penjualan_detail_id'],
							'kartu_stok_masuk'	 	=> 0,
							'kartu_transaksi' 		=> 'Retur Penjualan',
							'kartu_keterangan' 		=> 'Deleted  On Updated',
						];
						$this->db->delete('pos_penjualan_detail', array('retur_penjualan_detail_id' => $value['retur_penjualan_detail_id']));
	                	$this->stokkartu->update_kartu($kartu, 'RJ');
					}
				}
			}
			$selisih = $data['retur_penjualan_total']-$last_retur['retur_penjualan_total'];
			$up_jual = $this->db->where('penjualan_id', $data['retur_penjualan_penjualan_id'])
			->set('penjualan_total_retur','penjualan_total_retur+'.$selisih, FALSE)
			->update('pos_penjualan');
		});
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->returpenjualandetail->select(array('filters_static'=> $data)));
	}


	public function destroy()
	{
		$data = varPost();
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
		$operation = $this->returpenjualandetail->delete(array('retur_penjualan_detail_parent'=>$data['id']));
		$this->response($operation);
	}

	public function tprint($id)
	{	
		$data = varPost();
        $jual = $this->db->select('retur_penjualan_kode, retur_penjualan_tanggal, retur_penjualan_nilai, retur_penjualan_total_qty, retur_penjualan_total_item, retur_penjualan_total, anggota_nama, penjualan_kode, pegawai_nama,anggota_nip,grup_gaji_kode,grup_gaji_nama, anggota_kota')
				->where('retur_penjualan_id', $id)
				->from('v_pos_retur_penjualan')->get()->result_array();
        $html = '';
        if($jual){        	
        	$jual= $jual[0];
            $detail = $this->db->select('retur_penjualan_detail_retur_qty, retur_penjualan_detail_harga, barang_kode, barang_nama, retur_penjualan_detail_jumlah')
                ->where('retur_penjualan_detail_parent', $id)
                ->order_by('retur_penjualan_detail_order','asc')
				->from('v_pos_retur_penjualan_detail')->get()->result_array();
			$nprint = 1;

			$html .='
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

                <div class="section print">';
			for ($i=1; $i <= $nprint; $i++) { 
				if($i > 1) $html .= $break;
	                $html .='<h1 style="font-size:13px;text-align:center;margin-bottom:0">EKA MART KPRI EKO KAPTI <br>
							KANTOR KEMENAG KAB.MALANG</h1>
							<h2 style="font-size:12px;text-align:center; margin-top:2px">TELEPON (0341) 834894</h2>
						
						<hr style="border-top: 1px dashed black;">
						<h2 style="text-align:center;font-size:13px;">* NOTA RETUR PENJUALAN *</h2>
	                    <table>
	                        <tbody>
	                            <tr>
	                            	<td style="margin-left:400px;">Tgl</td>
	                            	<td>:</td>
	                            	<td>'.date('d/m/Y', strtotime($jual['retur_penjualan_tanggal'])).'</td>
	                            	<td>Opt </td>
	                            	<td>:</td>
	                            	<td style="text-transform:uppercase">'.$jual['pegawai_nama'].'</td>
	                            </tr>
	                            <tr>
	                            	<td>Nota Retur</td>
	                            	<td>:</td>
	                            	<td>'.$jual['retur_penjualan_kode'].'</td>
	                            	<td colspan="4">'.date('H:i:s', strtotime($jual['retur_penjualan_created'])).'</td>
	                            </tr>
	                            <tr>
	                            	<td>Nota Jual</td>
	                            	<td>:</td>
	                            	<td>'.$jual['penjualan_kode'].'</td>
	                            	<td colspan="4"></td>
	                            </tr>
	                        </tbody>
	                    </table>';
	                    $html .='<table>
	                    	<hr style="border-top: 1px dashed;margin:0">';
	                    	$totalpotongan = 0;
	                        foreach($detail as $key => $value){
	                            $html .='<tr>
	                                <td class="text-left">'.substr($value['barang_nama'], 0, 13).'</td>
	                                <td class="text-left">'.$value['retur_penjualan_detail_retur_qty'].' x</td>
	                                <td class="text-right">'.number_format($value['retur_penjualan_detail_harga']).'</td>
	                                <td class="text-right">'.number_format($value['retur_penjualan_detail_jumlah']).'</td>
	                            </tr>';
	                        }
	                        	
	                    $html .= '<table>
	                    <hr style="border-top: 1px dashed black;width:200px;" align="right"><tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Total  :</td>
	                        <td class="text-right">'.number_format($jual['retur_penjualan_total']).'</td>
	                    </tr>';

	                    $html .='
	                    </table>
	                    <hr style="border-top: 1px dashed black;">';
		                $html .='<table>
		                        <tbody>';

				if($jual['anggota_nama']){
					$html .= '<tr>
                                <td style="text-transform: capitalize;width:10%">Group</td>
                                <td>:</td>
                                <td colspan="4"> ('.$jual['grup_gaji_kode'].') '.$jual['grup_gaji_nama'].'</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td colspan="4"> '.$jual['anggota_kota'].' </td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td colspan="4"> '.$jual['anggota_nip'].' </td>
                            </tr>
	                    </table>
	                    <hr style="border-top: 1px dashed black;">';
	                $html .='<table>              
	                            <tr>
	                                <td class="text-left" style="text-transform: capitalize;">'.(($jual['anggota_nama'])?'Nasabah :'.$jual['anggota_kode']:'').' </td>
	                                <td class="" style="text-transform: capitalize;text-align:center">'.(($jual['anggota_nama'])?'Kasir ':'').'</td>
	                            </tr>
	                    	<tr>
	                    		<td colspan="2"><p></p></td>
	                    	</tr>
		                    <tr>
		                    	<td class="text-left">'.(($jual['anggota_nama'])?'('.$jual['anggota_nama'].')':'').'</td>
		                    	<td class="text-right">'.(($jual['anggota_nama'])?'('.$jual['pegawai_nama'].')':' ').'</td>
		                    </tr>
		            </table>
		            <hr style="border-top: 1px dashed black;">';
		        }else{
		        	$html .= '</tbody>
	                    </table><hr style="border-top: 1px dashed black;">';
		        }
		                    
                $html .='
                	<table>       
                            <tr>
                            	<td style="font-size:11px!important">*Terimakasih atas kunjungan anda</td>
                            </tr>
                    </table>';	
			}

			$html .='</div>';
            
        }
        if(isset($data['tjson'])) $this->response(array('tprint' => $html));
        return $html;    
	}
}

/* End of file Returpenjualan.php */
/* Location: ./application/modules/Returpenjualan/controllers/Returpenjualan.php */