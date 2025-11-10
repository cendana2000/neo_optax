<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Returpembelian extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'returpembelianModel' 								=> 'returpembelian',
			'returpembeliandetailModel' 						=> 'returpembeliandetail',
			'transaksipembelian/TransaksipembelianModel' 		=> 'transaksipembelian',
			'transaksipembelian/TransaksipembeliandetailModel' 	=> 'transaksipembeliandetail',
			'stokkartu/stokkartuModel' 							=> 'stokkartu',
		));
	}

	public function index()
	{
		$var=varPost();
		$this->response(
			$this->select_dt($var,'returpembelian','table',false,array(
				'retur_pembelian_tanggal BETWEEN "'.$var['tanggal1'].'" AND "'.$var['tanggal2'].'" ' => null
			))
		);
	}

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(),'transaksipembeliandetail','table')
		);
	}

	function read_detail_pembelian($value = '')
	{
		$this->response($this->transaksipembeliandetail->read(varPost()));
	}
	public function select_pembelian($value='')
	{
		$data = varPost();
		$data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';
		$where = ($data['fdata']['pembelian_supplier_id'])?'AND pembelian_supplier_id = "'.$data['fdata']['pembelian_supplier_id'].'"':'';

		$total = $this->db->query('SELECT count(pembelian_id) total FROM v_pos_pembelian_barang WHERE concat(pembelian_kode, pembelian_faktur, supplier_nama) like "%'.$data['q'].'%" AND pembelian_bayar_sisa>0 '.$where)->result_array();

		$return = $this->db->query('SELECT pembelian_id as id, concat(pembelian_kode, " - (Rp. ", pembelian_bayar_sisa, ")") as text, concat(pembelian_kode, " (Rp. ", pembelian_bayar_sisa, ")") as kode FROM v_pos_pembelian_barang WHERE concat(pembelian_kode, pembelian_faktur, supplier_nama ) like "%'.$data['q'].'%" AND pembelian_bayar_sisa>0 '.$where.' ORDER BY pembelian_kode ASC LIMIT '.$data['page'].$data['limit'])->result_array();
		$this->response(array('items'=>$return, 'total_count'=>$total[0]['total']));
	}

	/*public function select_ajax($value='')
	{
		$data = varPost();
		if(strlen($data['q'])>10){
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if(isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id'])?'barang_supplier_id = "'.$data['fdata']['barang_supplier_id'].'" AND ':'';
		$data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';
		$total = $this->db->query('SELECT count(barang_id) total FROM ms_barang WHERE '.$where.' (barang_nama like "%'.$data['q'].'%" OR barang_kode like "%'.$data['q'].'%") ')->result_array();
		$return = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text, barang_is_konsinyasi as saved FROM v_ms_barang WHERE '.$where.' (barang_nama like "%'.$data['q'].'%" OR barang_kode like "%'.$data['q'].'%") ORDER BY barang_nama LIMIT '.$data['page'].$data['limit'])->result_array();
		$this->response(array('items'=>$return, 'total_count'=>$total[0]['total']));
	}*/

	public function select_ajax($value='')
	{
		$data = varPost();
		if(strlen($data['q'])>10){
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if(isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		
		$where = ($data['fdata']['barang_kategori_barang'])?'barang_kategori_barang = "'.$data['fdata']['barang_kategori_barang'].'" AND':'';
		$data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';

		$total = $this->db->query('SELECT count(barang_id) total FROM pos_barang WHERE '.$where.' (barang_nama like "'.$data['q'].'%" OR barang_kode like "'.$data['q'].'%") ')->result_array();

		$return = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama, barang_stok as saved FROM v_pos_barang WHERE '.$where.' (barang_nama like "'.$data['q'].'%" OR barang_kode like "'.$data['q'].'%") ORDER BY barang_nama LIMIT '.$data['page'].$data['limit'])->result_array();

		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 45px;">'.$value['barang_kode'].'</span><span class="detail-barang-select"  style="width: 280px;">'.$value['barang_nama'].'</span><span class="detail-barang-select" style="width: 60px;">'.number_format($value['barang_harga']).'</span><span class="detail-barang-select" style="width: 80px;">Stok : '.$value['saved'].'</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_nama']
			];
		}
		$this->response(array('items'=>$new_return, 'total_count'=>$total[0]['total']));
	}
	function read($value='')
	{
		$retur = $this->returpembelian->read(varPost());		
		$html = '';
		$retur_detail = [];
		if(isset($retur['retur_pembelian_id'])){
			$retur_detail = $this->returpembeliandetail->select(['filters_static' => ['retur_pembelian_detail_parent' => $retur['retur_pembelian_id']], 'sort_static' => 'retur_pembelian_detail_order']);
			foreach($retur_detail['data'] as $key => $value) {
				$row = $key+1;
				$stok = $value['barang_stok']+$value['retur_pembelian_detail_retur_qty'];
				$html .= '<tr class="barang barang_'.$row.'" data-id="'.$row.'">
					<td scope="row">
						<input type="hidden" class="form-control" name="retur_pembelian_detail_id['.$row.']" id="retur_pembelian_detail_id_'.$row.'" value="'.$value['retur_pembelian_detail_id'].'">	
						<select class="form-control" name="retur_pembelian_detail_barang_id['.$row.']" id="retur_pembelian_detail_barang_id_'.$row.'" style="width: 100%" data-id="'.$row.'" onchange="setSatuan('.$row.')">
								<option value="'.$value['retur_pembelian_detail_barang_id'].'">'.$value['barang_kode'].' - '.$value['barang_nama'].'</option>							
						</select>
					</td>
					<td>
						<input type="hidden" class="form-control" name="retur_pembelian_detail_satuan['.$row.']" id="retur_pembelian_detail_satuan_'.$row.'" value="'.$value['retur_pembelian_detail_satuan'].'" >	
						<input type="text" class="form-control" name="retur_pembelian_detail_satuan_kode['.$row.']" id="retur_pembelian_detail_satuan_kode_'.$row.'" value="'.$value['retur_pembelian_detail_satuan_kode'].'" readonly="">	
					</td>
					<td><input class="form-control number" type="text" name="retur_pembelian_detail_harga['.$row.']" id="retur_pembelian_detail_harga_'.$row.'" onkeyup="countRow('.$row.')" value="'.$value['retur_pembelian_detail_harga'].'"></td>
					<td><input class="form-control number" type="text" disabled="" name="barang_stok['.$row.']" id="barang_stok_'.$row.'" onchange="countRow('.$row.')" value="'.$stok.'"></td>
					<td>
						<input class="form-control number qty" type="text" name="retur_pembelian_detail_retur_qty['.$row.']" id="retur_pembelian_detail_retur_qty_'.$row.'" value="'.$value['retur_pembelian_detail_retur_qty'].'" onkeyup="countRow('.$row.')">
						<input class="form-control number" type="hidden" value="'.$value['retur_pembelian_detail_retur_qty_barang'].'" name="retur_pembelian_detail_retur_qty_barang['.$row.']" id="retur_pembelian_detail_retur_qty_barang_'.$row.'">
					</td>
					<td><input class="form-control number" disabled="" type="text" name="barang_stok_sisa['.$row.']" id="barang_stok_sisa_'.$row.'" value="'.$value['barang_stok'].'"></td>
					<td><input class="form-control number jumlah" type="text" name="retur_pembelian_detail_jumlah['.$row.']" id="retur_pembelian_detail_jumlah_'.$row.'"  value="'.$value['retur_pembelian_detail_jumlah'].'" readonly=""></td>
					<td><button type="button" data-id="'.$row.'" class="btn btn-sm btn-clean btn-icon btn-icon-md kt-font-bold kt-font-warning" title="Edit" onclick="remRow('.$row.')" >                      
              			<span class="la la-trash"></span> Hapus
                    </button></td>
				</tr>';
			}
		}
		$retur['html'] = $html;
		$retur['detail'] = $retur_detail;
		$this->response($retur);
	}

	public function store()
	{
		$data = varPost();
		$data['retur_pembelian_kode'] = ($data['retur_pembelian_kode'])?$data['retur_pembelian_kode']:$this->returpembelian->gen_kode();
		$data['retur_pembelian_user'] = $this->session->userdata('pegawai_id');
		$data['retur_pembelian_aktif'] = '1';
		$error = [];
		$operation = $this->returpembelian->insert(gen_uuid($this->returpembelian->get_table()), $data, function($res) use ($data)
		{
			$dt = $res['record'];
			$detail = [];
			$n = 0;
			foreach ($data['retur_pembelian_detail_barang_id'] as $key => $value) {
				$detail = [
					'retur_pembelian_detail_parent' 		=> $res['record']['retur_pembelian_id'],
					'retur_pembelian_detail_id'				=> gen_uuid($this->returpembeliandetail->get_table()),
					'retur_pembelian_detail_barang_id' 		=> $data['retur_pembelian_detail_barang_id'][$key],
					'retur_pembelian_detail_satuan' 		=> $data['retur_pembelian_detail_satuan'][$key],
					'retur_pembelian_detail_satuan_kode' 	=> $data['retur_pembelian_detail_satuan_kode'][$key],
					'retur_pembelian_detail_harga' 			=> $data['retur_pembelian_detail_harga'][$key],
					'retur_pembelian_detail_retur_qty' 		=> $data['retur_pembelian_detail_retur_qty'][$key],
					'retur_pembelian_detail_retur_qty_barang' => $data['retur_pembelian_detail_retur_qty'][$key],
					'retur_pembelian_detail_jumlah' 		=> $data['retur_pembelian_detail_jumlah'][$key],
					'retur_pembelian_detail_order' 			=> $n,
				];
				$det_opr = $this->db->insert('pos_retur_pembelian_barang_detail', $detail);				
				if(!$det_opr) $error[] = ['cannot insert dt'.$value => $detail];
				else{
					/*if($data['bayar-lunas']!== '1'){
						$up_beli_detail = $this->db->where('pembelian_detail_id', $data['retur_pembelian_detail_detail_id'][$key])
							->set('pembelian_detail_retur_qty', 'pembelian_detail_retur_qty+'.$data['retur_pembelian_detail_retur_qty'][$key], FALSE)
							->update('pos_pembelian_barang_detail');
					}*/

					$kartu = $this->stokkartu->insert_kartu([
						'kartu_id' 			=> $detail['retur_pembelian_detail_id'],
						'kartu_tanggal' 	=> $data['retur_pembelian_tanggal'],
						'kartu_barang_id' 	=> $data['retur_pembelian_detail_barang_id'][$key],
						'kartu_satuan_id' 	=> $data['retur_pembelian_detail_satuan'][$key],
						'kartu_stok_masuk' 	=> 0,
						'kartu_stok_keluar' => $data['retur_pembelian_detail_retur_qty'][$key],
						'kartu_transaksi' 	=> 'Retur Pembelian',
						'kartu_keterangan' 	=> 'ON Insert',
						'kartu_harga'		=> $data['retur_pembelian_detail_harga'][$key],
						'kartu_transaksi_kode' => $data['retur_pembelian_kode'],
						'kartu_user' 		=> $data['retur_pembelian_user'],
						'kartu_created' 	=> date('Y-m-d H:i:s'),
					], 'RB');
					if(!$kartu) $error[] = [$kartu, $data['retur_pembelian_kode'], $value];
				}
				$n++;
			}			
			// if($data['bayar-lunas'] !== '1'){
				$up_beli = $this->db->where('pembelian_id', $data['retur_pembelian_pembelian_id'])
					->set('pembelian_retur', 'pembelian_retur+'.$data['retur_pembelian_total'], FALSE)
					->update('pos_pembelian_barang');
			// }
		});
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$last_retur =$this->returpembelian->read($data['retur_pembelian_id']);
		$operation = $this->returpembelian->update($data['retur_pembelian_id'], $data, function(&$res) use ($data)
		{
			$detail = $id_detail = [];
			$last_detail = $this->returpembeliandetail->select(array('filters_static' => array('retur_pembelian_detail_parent' => $data['retur_pembelian_id']), 'sort_static' => 'retur_pembelian_detail_order asc'))['data'];
			$delete = $last_detail;
			$n = 0;
			foreach ($data['retur_pembelian_detail_barang_id'] as $key => $value) {
				$detail = [
					'retur_pembelian_detail_id'					=> $data['retur_pembelian_detail_id'][$key],
					'retur_pembelian_detail_parent' 			=> $res['record']['retur_pembelian_id'],
					'retur_pembelian_detail_barang_id' 			=> $data['retur_pembelian_detail_barang_id'][$key],
					'retur_pembelian_detail_satuan' 			=> $data['retur_pembelian_detail_satuan'][$key],
					'retur_pembelian_detail_harga' 				=> $data['retur_pembelian_detail_harga'][$key],
					'retur_pembelian_detail_retur_qty' 			=> $data['retur_pembelian_detail_retur_qty'][$key],
					'retur_pembelian_detail_retur_qty_barang' 	=> $data['retur_pembelian_detail_retur_qty_barang'][$key],
					'retur_pembelian_detail_jumlah' 			=> $data['retur_pembelian_detail_jumlah'][$key],
					'retur_pembelian_detail_order' 				=> $n,
				];
					// 'retur_pembelian_detail_sisa_qty' 			=> $data['retur_pembelian_detail_sisa_qty'][$key],
					// 'retur_pembelian_detail_id'			=> gen_uuid($this->returpembeliandetail->get_table()),
				$n++;
				$kartu = [
					'kartu_id' 			=> $detail['retur_pembelian_detail_id'],
					'kartu_tanggal' 	=> $data['retur_pembelian_tanggal'],
					'kartu_barang_id' 	=> $detail['retur_pembelian_detail_barang_id'],
					'kartu_satuan_id' 	=> $detail['retur_pembelian_detail_satuan'],
					'kartu_stok_keluar' => $detail['retur_pembelian_detail_retur_qty_barang'],
					'kartu_transaksi' 	=> 'Retur Pembelian',
					'kartu_keterangan' 	=> 'ON Updated',
					'kartu_harga'		=> $detail['retur_pembelian_detail_harga'],
					'kartu_transaksi_kode' => $data['retur_pembelian_kode'],
					'kartu_user' 		=> $data['retur_pembelian_user'],
					'kartu_created' 	=> date('Y-m-d H:i:s'),
				];

				foreach ($last_detail as $i => $v) {
					if($v['retur_pembelian_detail_id'] == $data['retur_pembelian_detail_id'][$key]) unset($delete[$i]);
				}
				$res_detail = $this->returpembeliandetail->update($data['retur_pembelian_detail_id'][$key], $detail);
                if(!$res_detail['success']){
                	$detail_id = gen_uuid($this->returpembeliandetail->get_table());
                	$kartu['kartu_id'] = $detail['retur_pembelian_detail_id'] = $detail_id;
                	$kartu['kartu_stok_masuk'] = 0;
                	$kartu['kartu_keterangan'] = 'ON Insert Updated';
					$res_detail = $this->returpembeliandetail->insert($detail_id, $detail);
					if($res_detail['success']){
						$kartu = $this->stokkartu->insert_kartu($kartu, 'RB');
						$id_detail[] = $res_detail['id'];
					}
                }else{
					$kartu = $this->stokkartu->update_kartu($kartu, 'RB');
                	$id_detail[] = $res_detail['id'];
                }
			}

			foreach ($delete as $n => $value) {
				$del = $this->returpembeliandetail->delete($value['retur_pembelian_detail_id']);
				if($del['success']){
					$kartu = [
						'kartu_id' 				=> $value['retur_pembelian_detail_id'],
						'kartu_stok_keluar' 	=> 0,
						'kartu_transaksi' 		=> 'Retur Pembelian',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					// $this->db->delete('pos_retur_pembelian_barang_detail', array('retur_pembelian_detail_id' => $value['retur_pembelian_detail_id']));
                	$this->stokkartu->update_kartu($kartu, 'B');
				}
			}
		});
		$selisih = $data['retur_pembelian_total']-$last_retur['retur_pembelian_total'];
		$up_beli = $this->db->where('pembelian_id', $data['retur_pembelian_pembelian_id'])
			->set('pembelian_retur', 'pembelian_retur+'.$selisih, FALSE)
			->update('pos_pembelian_barang');
		$operation['selisih'] =$selisih;
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->returpembeliandetail->select(array('filters_static'=> $data)));
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->returpembelian->delete(varPost('id', varExist($data, $this->returpembelian->get_primary(true))));

		$last_detail = $this->returpembeliandetail->select(array('filters_static' => array('retur_pembelian_detail_parent' => $data['id']), 'sort_static' => 'retur_pembelian_detail_order asc'))['data'];
		$delete = $last_detail;
		foreach ($last_detail as $key => $value) {
			$kartu = [
				'kartu_id' 				=> $value['retur_pembelian_detail_id'],
				'kartu_barang_id'		=> $value['retur_pembelian_detail_barang_id'],
				'kartu_stok_keluar' 	=> 0,
				'kartu_transaksi' 		=> 'Retur Pembelian',
				'kartu_keterangan' 		=> 'Deleted On Updated',
			];
	    	$this->stokkartu->update_kartu($kartu, 'RB');
		}
		$operation = $this->returpembeliandetail->delete(array('retur_pembelian_detail_parent'=>$data['id']));
		$this->response($operation);
	}

	public function cetak($value='')
	{
		if ($value) {
			$data = $this->db->where('retur_pembelian_id', $value)
							->get('v_pos_retur_pembelian_barang')
							->row_array();
			$detail = $this->db->where('retur_pembelian_detail_parent', $value)
							   ->get('v_pos_retur_pembelian_barang_detail')
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
						<p>UKM MART KPRI EKO KAPTI</p>
						<p>KANTOR REMENAG KAB.MALANG</p>
					</td>
					<td class="right" ><p>'.(date("d/m/Y")).'</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> NOTA RETUR PEMBELIAN BARANG </h4><br>
					</td>
				</tr>
				<tr>
					<td>Kode : '.$data['retur_pembelian_kode'].'</td>
				</tr>
				<tr>
					<td>Tanggal Retur : '.($data['retur_pembelian_tanggal'] ? date("d/m/Y",strtotime($data['retur_pembelian_tanggal']))  : "-").'</td>
					<td class="right">Supplier : '.($data['supplier_kode'] ? $data['supplier_kode'] : "-").'</td>
				</tr>
				<tr>
					<td>No. Pembelian : '.($data['pembelian_faktur'] ? $data['pembelian_faktur'] : "-").'</td>
					<td class="right">'.($data['supplier_nama'] ? $data['supplier_nama'] : "-").'</td>
				</tr>
				<tr>
					<td>Jatuh Tempo: '.($data['pembelian_jatuh_tempo'] ? date("d/m/Y", strtotime($data['pembelian_jatuh_tempo'])) : "-").'</td>
					<td class="right">'.($data['supplier_alamat'] ? $data['supplier_alamat'] : "-").' / '.($data['supplier_telp'] ? $data['supplier_telp'] : "-").'</td>
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

				$totalJml =0;
				$totalQty =0;
				foreach ($detail as $key => $value) {
					$hrgJual = $value['pembelian_detail_harga'] + ( $percentase/100 * $value['pembelian_detail_harga']);
					$html .= '<tr>
						<td>'.($key+1).'</td>
						<td class="divider">'.($value['barang_kode'] ? $value['barang_kode'] : "-").'</td>
						<td>'.($value['barang_nama'] ? $value['barang_nama'] : "-").'</td>
						<td>'.($value['pembelian_detail_qty'] ? $value['pembelian_detail_qty'] : "-").'</td>
						<td>'.($value['retur_pembelian_detail_retur_qty'] ? number_format($value['retur_pembelian_detail_retur_qty']) : "").'</td>
						<td>'.($value['retur_pembelian_detail_harga'] ? number_format($value['retur_pembelian_detail_harga']) : "").'</td>
						<td>'.($value['retur_pembelian_detail_jumlah'] ? number_format($value['retur_pembelian_detail_jumlah']) : "-").'</td>
					</tr>';
					$totalJml += $value['retur_pembelian_detail_jumlah'];
					$totalQty += $value['retur_pembelian_detail_retur_qty'];
				}
				

				$html .='<tr>
					<td colspan="4" class="total">Total</td>
					<td colspan="2" class="total">'.$totalQty.'</td>
					<td colspan="" class="total">'.number_format($totalJml).'</td>
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
					<td class="bottom">'.$data['pegawai_nama'].'</td>
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
				'file_name'     => 'NOTA RETUR PEMBELIAN',
				'title'         => 'NOTA RETUR PEMBELIAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));  
		}	
	}

	public function loaddetail(){
		$data = varPost();
		$no = 1;
		$detail = $this->returpembeliandetail->select(array('filters_static'=>array(
			'retur_pembelian_detail_parent'=> $data['retur_pembelian_detail_parent']
		)));
		// print_r($detail);
		$html = '<table cellspacing="0" cellpadding="2" style="width:90%">
			<thead>
				<tr>
					<td>No</td>
					<td>Barang</td>
					<td>Satuan</td>
					<td>Harga</td>
					<td>Qty Beli</td>
					<td>Retur</td>
					<td>Sisa</td>
					<td>Nilai</td>
				</tr>
			</thead>
			';
		$html .= '<tbody>';
			foreach ($detail['data'] as $key => $value) {
				$html .='<tr>
						<td>'.$no++.'</td>
						<td>'.$value['barang_nama'].'</td>
						<td>'.$value['barang_satuan_kode'].'</td>
						<td>'.$value['pembelian_detail_harga'].'</td>
						<td>'.$value['pembelian_detail_qty'].'</td>
						<td>'.$value['retur_pembelian_detail_retur_qty'].'</td>
						<td>'.$value['retur_pembelian_detail_sisa_qty'].'</td>
						<td>'.$value['retur_pembelian_detail_jumlah'].'</td>
						</tr>';
			}
		$html .= '</tbody>';		
		$html .= '</table>';
		echo json_encode(array(
			'success' 	=> true,
			'html' 		=> $html
		));
	}
}

/* End of file Returpembelian.php */
/* Location: ./application/modules/Returpembelian/controllers/Returpembelian.php */