<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksipembelian extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'transaksipembelianModel' 		=> 'transaksipembelian',
			'transaksipembeliandetailModel' => 'transaksipembeliandetail',
			'stokkartu/stokkartuModel' 		=> 'stokkartu',
			'jurnal/JurnalModel' 			=> 'jurnal',
			'jurnal/JurnalDetailModel' 		=> 'jurnaldetail',
			'barang/BarangsatuanModel'		=> 'barangsatuan',
			'barang/BarangModel'			=> 'barang',
			'satuan/SatuanModel'			=> 'satuan',
			'TransaksipembeliandetailpembayaranModel' => 'transaksipembeliandetailpembayaran'
		));
	}
	public function index2()
	{
		$where['pembelian_deleted_at'] = null;
		$this->response(
			$this->select_dt(varPost(), 'transaksipembelian', 'table', true, $where)
		);
	}

	public function index()
	{
		$var = varPost();

		if (!array_key_exists('tanggal1', $var) && !array_key_exists('tanggal2', $var)) {
			$var['tanggal1'] = date('Y-m-d');
			$var['tanggal2'] = date('Y-m-d');
		}


		$this->response(
			$this->select_dt($var, 'transaksipembelian', 'table', false, array(
				'pembelian_tanggal BETWEEN \'' . $var['tanggal1'] . '\' AND \'' . $var['tanggal2'] . '\' ' => null,
			))
		);
	}
	public function get_barang()
	{
		$barang = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama as text, TO_BASE64(concat(barang_satuan,"||",barang_satuan_opt,"||",IFNULL(barang_isi, 0),"||",IFNULL(barang_harga, 0),"||",IFNULL(barang_harga_pokok, 0))) saved FROM v_pos_barang WHERE concat(barang_kode,barang_barcode) like "%' . varPost('val') . '%" LIMIT 1')->result_array();
		if (!$barang) {
			$barang = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama as text FROM v_pos_barang_barcode WHERE barang_barcode_kode = "' . varPost('val') . '" LIMIT 1')->result_array();
		}
		$this->response($barang);
	}
	public function barang_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id']) ? 'barang_supplier_id = \'' . $data['fdata']['barang_supplier_id'] . '\' AND ' : '';
		// $data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$data['page'] = isset($data['page']) ? (intval($data['page']) - 1) : '0';
		$total = $this->db->query('SELECT 
			count(barang_id) total 
		FROM v_pos_barang 
		WHERE (barang_nama like \'%' . $data['q'] . '%\' OR barang_kode like \'%' . $data['q'] . '%\')
		AND barang_deleted_at IS NULL 
		AND jenis_include_stok = 1')->result_array();
		$return = $this->db->query('SELECT 
			barang_id as id, barang_kode, barang_nama, barang_harga_beli, barang_stok, barang_kode as saved 
		FROM v_pos_barang 
		WHERE barang_deleted_at IS NULL 
		AND jenis_include_stok = 1 
		AND (barang_nama like \'%' . $data['q'] . '%\' OR barang_kode like \'%' . $data['q'] . '%\') 
		ORDER BY barang_nama LIMIT ' . $data['limit'] . ' OFFSET ' . $data['page'])->result_array();
		// , " (stok: ", barang_stok, ")"
		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 100px">' . $value['barang_kode'] . '</span> - <span class="detail-barang-select"  style="width: 300px;">' . $value['barang_nama'] . '</span><span class="detail-barang-select" style="width: 100px;">' . 'Rp. ' . number_format($value['barang_harga_beli']) . '</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_nama']
			];
		}

		$this->response(array('items' => $new_return, 'total_count' => $total[0]['total'], 'page' => $data['page']));
	}

	public function get_harga()
	{
		$data = varPost();
		$detail_beli = $this->transaksipembeliandetail->select([
			'filters_static' => ['pembelian_detail_parent' => $data['pembelian_id']],
			'sort_static' => 'pembelian_detail_order asc'
		]);
		$satuan = $this->satuan->select(['sort_static' => 'satuan_kode']);
		$detail_beli['satuan'] = $satuan['data'];
		foreach ($detail_beli['data'] as $key => &$value) {
			$satuan = $this->db->query('SELECT * FROM pos_barang_satuan WHERE barang_satuan_parent = "' . $value['pembelian_detail_barang_id'] . '" ORDER BY barang_satuan_order asc')->result_array();
			$value['satuan'] = $satuan;
		}
		$this->response($detail_beli);
	}

	public function table_faktur()
	{
		$filter = varPost('pembelian_supplier_id');


		if ($filter != '') {
			// Query Baru
			$data['aaData'] = $this->db->query("SELECT * FROM v_pos_pembelian_barang WHERE pembelian_supplier_id = '$filter' AND pembelian_bayar_opsi = 'K' AND pembelian_bayar_sisa > 0")->result_array();
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

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(), 'transaksipembeliandetail', 'table', true, varPost('beli'))
		);
	}

	function read($value = '')
	{
		$pembelian = $this->transaksipembelian->read(varPost());
		$html = '';
		$detail = [];
		if (isset($pembelian['pembelian_id'])) {
			$detail = $this->transaksipembeliandetail->select(['filters_static' => ['pembelian_detail_parent' => $pembelian['pembelian_id']], 'sort_static' => 'pembelian_detail_order']);

			$pembayaran = $this->transaksipembeliandetailpembayaran->select(['filters_static' => ['order_detail_pembayaran_parent' => $pembelian['pembelian_id']], 'sort_static' => 'order_detail_pembayaran_parent']);
			$row = 1;
			foreach ($detail['data'] as $key => $value) {
				$satuan = $this->barangsatuan->select(['filters_static' => ['barang_satuan_parent' => $value['pembelian_detail_barang_id']], 'sort_static' => 'barang_satuan_order']);
				$html .= '<tr class="barang_' . $row . '">
						<td scope="row">
							<input type="hidden" class="form-control" name="pembelian_detail_id[' . $row . ']" id="pembelian_detail_id_' . $row . '" value="' . $value['pembelian_detail_id'] . '">						
							<input type="hidden" class="form-control barcode-scan" name="barang_kode[' . $row . ']" id="barang_kode_' . $row . '"  style="width:18%;display:inline;" data-id="' . $row . '" value="' . $value['barang_kode'] . '" readonly="">
							<select class="form-control barang_id" name="pembelian_detail_barang_id[' . $row . ']" id="pembelian_detail_barang_id_' . $row . '" data-id="' . $row . '" onchange="setSatuan(' . $row . ')" style="width: 285px;white-space: nowrap" readonly="">
								<option value="' . $value['pembelian_detail_barang_id'] . '" selected>' . $value['barang_nama'] . '</option>
							</select></td>
						<td><select class="form-control" name="pembelian_detail_satuan[' . $row . ']" id="pembelian_detail_satuan_' . $row . '" style="width: 100%" onchange="getHarga(' . $row . ')">';
				foreach ($satuan['data'] as $k => $v) {
					$html .= '<option value="' . $v['barang_satuan_id'] . '" data-barang_satuan_harga_beli="' . $v['barang_satuan_harga_beli'] . '" data-barang_satuan_konversi="' . $v['barang_satuan_konversi'] . '" data-barang_satuan_keuntungan="' . $v['barang_satuan_keuntungan'] . '" ' . ($v['barang_satuan_id'] == $value['pembelian_detail_satuan'] ? 'selected' : '') . '>' . $v['barang_satuan_kode'] . '</option>';
				}
				$html .= '</select></td>
						<td><input class="form-control number" type="text" name="pembelian_detail_harga[' . $row . ']" id="pembelian_detail_harga_' . $row . '" onkeyup="countRow(' . $row . ')" value="' . $value['pembelian_detail_harga'] . '"></td>
						<td>
							<input class="form-control number qty" type="text" name="pembelian_detail_qty[' . $row . ']" id="pembelian_detail_qty_' . $row . '" onkeyup="countRow(' . $row . ')" value="' . $value['pembelian_detail_qty'] . '">
							<input type="hidden" name="pembelian_detail_qty_barang[' . $row . ']" id="pembelian_detail_qty_barang_' . $row . '" value="' . $value['pembelian_detail_qty_barang'] . '">
							<input type="hidden" name="pembelian_detail_harga_barang[' . $row . ']" id="pembelian_detail_harga_barang_' . $row . '" value="' . $value['pembelian_detail_harga_barang'] . '">
							<input type="hidden" name="pembelian_detail_hpp[' . $row . ']" id="pembelian_detail_hpp_' . $row . '" value="' . $value['pembelian_detail_hpp'] . '">
							<input type="hidden" name="pembelian_detail_konversi[' . $row . ']" id="pembelian_detail_konversi_' . $row . '" value="' . $value['pembelian_detail_konversi'] . '">
						</td>
						<td>							
							<div class="kt-input-icon kt-input-icon--right">
								<input type="text" class="form-control disc" placeholder="%" id="pembelian_detail_diskon_' . $row . '" name="pembelian_detail_diskon[' . $row . ']" onkeyup="countRow(' . $row . ')" value="' . $value['pembelian_detail_diskon'] . '">
							</div>	
						</td>
						<td><input class="form-control number jumlah" type="text" name="pembelian_detail_jumlah[' . $row . ']" id="pembelian_detail_jumlah_' . $row . '" onchange="setHarga(' . $row . ')" value="' . $value['pembelian_detail_jumlah'] . '"></td>
						<td>
							<a href="javascript:;" data-id="' . $row . '" class="btn btn-light-warning btn-sm" onclick="remRow(this)" title="Hapus" >
	                  		<span class="la la-trash"></span></a>						
	                  	</td>
					</tr>';
				$row++;
			}
		}
		$pembelian['html'] = $html;
		$pembelian['detail'] = $detail['data'];
		$pembelian['pembayaran'] = $pembayaran['data'];
		$this->response($pembelian);
	}

	function read_detail($value = '')
	{
		$this->response($this->transaksipembeliandetail->read(varPost()));
	}

	public function store()
	{
		$data = varPost();

		$data['pembelian_created_at'] = date('Y-m-d');
		$kode = 'T';
		if ($data['pembelian_bayar_opsi'] == 'K') {
			$data['pembelian_akun_id'] = null;
			$data['pembelian_bayar_jumlah'] = '0';
			$data['pembelian_bayar_sisa'] = $data['pembelian_bayar_grand_total'];
			$kode = 'K';
		} else {
			$data['aaa'] = null;
			$data['pembelian_jatuh_tempo'] = null;
			$data['pembelian_bayar_sisa'] = '0';
			$data['pembelian_bayar_jumlah'] = $data['pembelian_bayar_grand_total'];
		}
		if ($data['pembelian_is_konsinyasi'] == '1') {
			$kode = 'KS';
		} else {
			$data['pembelian_is_konsinyasi'] = null;
		}
		$data['pembelian_kode'] = $this->transaksipembelian->generate_kode_pembelian($data['pembelian_tanggal'], $kode);
		$data['pembelian_aktif'] = '1';
		$data['pembelian_updated'] = date('Y-m-d H:i:s');
		$data['pembelian_bayar_jumlah'] = '0';
		$data['pembelian_retur'] = '0';
		$error = [];
		// SET $data value empty/'' TO null
		$data = cVarNull($data);

		$operation = $this->transaksipembelian->insert(gen_uuid($this->transaksipembelian->get_table()), $data, function ($res) use ($data, $kode) {
			$detail = [];
			$dt = $res['record'];
			// $trx = $this->transaksipembelian->read($res['id'], false, true);
			// print_r($this->db->last_query());
			// print_r($res['id']);
			$res['record'] = $this->db->get_where('v_pos_pembelian_barang', ['pembelian_id' => $res['id']])->row_array();

			foreach ($data['pembelian_detail_barang_id'] as $key => $value) {
				// $data['pembelian_detail_konversi'][$key] = ($data['pembelsian_detail_konversi'][$key] ? $data['pembelian_detail_konversi'][$key] : 1);
				$harga = ($data['pembelian_detail_jumlah'][$key] / $data['pembelian_detail_qty'][$key]);
				$diskon = $harga * $data['pembelian_diskon_persen'] / 100;
				$sjumlah = $harga - $diskon;
				$pajak = $sjumlah * $data['pembelian_pajak_persen'] / 100;
				$hpp = $sjumlah + $pajak;
				// $data['pembelian_detail_harga_barang'][$key] = $hpp/$data['pembelian_detail_konversi'][$key];				
				$hpp_ecer = $hpp;
				$data['pembelian_detail_harga_barang'][$key] = $harga / $data['pembelian_detail_konversi'][$key];
				if ($data['pembelian_detail_konversi'][$key]) {
					// $data['pembelian_detail_harga_barang'][$key] = $hpp/$data['pembelian_detail_konversi'][$key];
					$hpp_ecer = $hpp / $data['pembelian_detail_konversi'][$key];
				}/*else{
					$data['pembelian_detail_harga_barang'][$key] = $hpp;
				}*/
				$detail = [
					'pembelian_detail_id' 			=> gen_uuid($this->transaksipembeliandetail->get_table()),
					'pembelian_detail_parent' 		=> $dt['pembelian_id'],
					// 'pembelian_detail_parent' 		=> $res['id'],
					'pembelian_detail_barang_id'	=> $value,
					'pembelian_detail_satuan' 		=> $data['pembelian_detail_satuan'][$key],
					'pembelian_detail_harga' 		=> $data['pembelian_detail_harga'][$key],
					'pembelian_detail_hpp' 			=> $hpp,
					'pembelian_detail_harga_barang' => $data['pembelian_detail_harga_barang'][$key],
					'pembelian_detail_qty' 			=> $data['pembelian_detail_qty'][$key],
					'pembelian_detail_qty_barang' 	=> $data['pembelian_detail_qty_barang'][$key],
					'pembelian_detail_konversi' 	=> $data['pembelian_detail_konversi'][$key],
					'pembelian_detail_diskon' 		=> $data['pembelian_detail_diskon'][$key],
					'pembelian_detail_jumlah' 		=> $data['pembelian_detail_jumlah'][$key],
					'pembelian_detail_tanggal' 		=> $data['pembelian_tanggal'],
					'pembelian_detail_order' 		=> $key,
				];
				$det_opr = $this->db->insert('pos_pembelian_barang_detail', $detail);
				if (!$det_opr) $error[] = ['cannot insert dt' . $value => $detail];
				else {
					$br_satuan = $this->barangsatuan->read(['barang_satuan_parent' => $value, 'barang_satuan_order' => '1']);
					if (isset($br_satuan['barang_satuan_id'])) {
						$br_satuan_utama = $this->db->where('barang_satuan_id', $br_satuan['barang_satuan_id'])
							->set('barang_satuan_harga_beli', $hpp_ecer)
							->update('pos_barang_satuan');
						if ($br_satuan_utama && $br_satuan['barang_satuan_id'] !== $data['pembelian_detail_satuan'][$key]) {
							$br_satuan_lain = $this->db->where('barang_satuan_id', $data['pembelian_detail_satuan'][$key])
								->set('barang_satuan_harga_beli', $hpp)
								->update('pos_barang_satuan');
						}
					}
					$kartu = $this->stokkartu->insert_kartu([
						'kartu_id' 			=> $detail['pembelian_detail_id'],
						'kartu_tanggal' 	=> $dt['pembelian_tanggal'],
						'kartu_barang_id' 	=> $value,
						'kartu_satuan_id' 	=> $data['pembelian_detail_satuan'][$key],
						'kartu_stok_masuk' 	=> $data['pembelian_detail_qty_barang'][$key],
						'kartu_stok_keluar' => 0,
						'kartu_transaksi' 	=> 'Pembelian',
						'kartu_harga'		=> $hpp_ecer,
						'kartu_harga_transaksi'	=> $data['pembelian_detail_harga_barang'][$key],
						'kartu_nilai'		=> $data['pembelian_detail_jumlah'][$key],
						'kartu_transaksi_kode' => $dt['pembelian_kode'],
						'kartu_user' 		=> $dt['pembelian_user'],
						'kartu_created_at' 	=> date('Y-m-d H:i:s'),
						'kartu_keterangan' 	=> 'On Insert',
					], 'B');
					if (!$kartu) $error[] = [$kartu, $dt['pembelian_kode'], $value];
				}
			}

			// Add Jenis pembayaran detail jika tunai 
			foreach ($data['order_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'order_detail_pembayaran_id' => $value,
					'order_detail_pembayaran_parent' => $res['record']['pembelian_id'],
					'order_detail_pembayaran_tanggal' => $data['order_detail_pembayaran_tanggal'][$key],
					'order_detail_pembayaran_cara_bayar' => $data['order_detail_pembayaran_cara_bayar'][$key],
					'order_detail_pembayaran_total' => $data['order_detail_pembayaran_total'][$key],
				];
				$det_opr_pembayaran = $this->transaksipembeliandetailpembayaran->insert(gen_uuid($this->transaksipembeliandetailpembayaran->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}
		});


		$operation['error_log'] = $error;
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();

		// destroy pembayaran lama 
		$this->db->where('order_detail_pembayaran_parent', $data['pembelian_id']);
		$this->db->delete('pos_pembelian_pembayaran_detail');

		$data['pembelian_bayar_sisa'] = '0';
		if ($data['pembelian_bayar_opsi'] == 'K') {
			$data['pembelian_bayar_sisa'] = $data['pembelian_bayar_grand_total'];
			$data['pembelian_akun_id'] = null;
		} else $data['pembelian_jatuh_tempo'] = null;

		if ($data['pembelian_is_konsinyasi'] == '1') {
			$kode = 'KS';
		} else {
			$data['pembelian_is_konsinyasi'] = null;
		}
		$last_detail = $this->transaksipembeliandetail->select(array('filters_static' => array('pembelian_detail_parent' => $data['pembelian_id']), 'sort_static' => 'pembelian_detail_order asc'))['data'];
		$delete = $last_detail;

		// change value $data empty/'' to null
		$data = cVarNull($data);

		$operation = $this->transaksipembelian->update($data['pembelian_id'], $data, function ($res) use ($data, $delete, $last_detail) {
			$detail = $id_detail = [];
			$dt = $res['record'];

			// Handle detail pembayaran  
			foreach ($data['order_detail_pembayaran_cara_bayar'] as $key => $value) {
				$detail_pembayaran = [
					'order_detail_pembayaran_id' => $value,
					'order_detail_pembayaran_parent' => $data['pembelian_id'],
					'order_detail_pembayaran_tanggal' => $data['order_detail_pembayaran_tanggal'][$key],
					'order_detail_pembayaran_cara_bayar' => $data['order_detail_pembayaran_cara_bayar'][$key],
					'order_detail_pembayaran_total' => $data['order_detail_pembayaran_total'][$key],
				];
				$det_opr_pembayaran = $this->transaksipembeliandetailpembayaran->insert(gen_uuid($this->transaksipembeliandetailpembayaran->get_table()), $detail_pembayaran);
				if (!$det_opr_pembayaran['success']) $res['res'][] = $det_opr_pembayaran;
			}



			// update detail barang
			foreach ($data['pembelian_detail_barang_id'] as $key => $value) {
				$data['pembelian_detail_konversi'][$key] = ($data['pembelian_detail_konversi'][$key] ? $data['pembelian_detail_konversi'][$key] : 1);
				$harga = ($data['pembelian_detail_jumlah'][$key] / $data['pembelian_detail_qty'][$key]);
				$diskon = $harga * ($data['pembelian_diskon_persen'] / 100);
				$sjumlah = $harga - $diskon;
				$pajak = $sjumlah * ($data['pembelian_pajak_persen'] / 100);
				$hpp = $sjumlah + $pajak;
				$hpp_ecer = $hpp;
				if ($data['pembelian_detail_konversi'][$key]) {
					$data['pembelian_detail_harga_barang'][$key] = $harga / $data['pembelian_detail_konversi'][$key];
					$hpp_ecer = $hpp / $data['pembelian_detail_konversi'][$key];
				}/*else{
					$data['pembelian_detail_harga_barang'][$key] = $hpp;
				}*/

				$detail = [
					'pembelian_detail_parent' 		=> $res['record']['pembelian_id'],
					'pembelian_detail_barang_id'	=> $value,
					'pembelian_detail_satuan' 		=> $data['pembelian_detail_satuan'][$key],
					'pembelian_detail_harga' 		=> $data['pembelian_detail_harga'][$key],
					'pembelian_detail_harga_barang' => $data['pembelian_detail_harga_barang'][$key],
					'pembelian_detail_hpp' 			=> $hpp,
					'pembelian_detail_qty' 			=> $data['pembelian_detail_qty'][$key],
					'pembelian_detail_qty_barang' 	=> $data['pembelian_detail_qty_barang'][$key],
					'pembelian_detail_diskon' 		=> $data['pembelian_detail_diskon'][$key],
					'pembelian_detail_konversi' 	=> $data['pembelian_detail_konversi'][$key],
					'pembelian_detail_pajak' 		=> $data['pembelian_detail_pajak'][$key],
					'pembelian_detail_jumlah' 		=> $data['pembelian_detail_jumlah'][$key],
					'pembelian_detail_tanggal' 		=> $data['pembelian_tanggal'],
					'pembelian_detail_order' 		=> $key,
				];

				$kartu = [
					'kartu_id' 				=> $data['pembelian_detail_id'][$key],
					'kartu_tanggal' 		=> $dt['pembelian_tanggal'],
					'kartu_barang_id' 		=> $value,
					'kartu_satuan_id' 		=> $data['pembelian_detail_satuan'][$key],
					'kartu_stok_masuk' 		=> $data['pembelian_detail_qty_barang'][$key],
					'kartu_transaksi' 		=> 'Pembelian',
					'kartu_harga'			=> $hpp_ecer,
					'kartu_harga_transaksi'	=> $data['pembelian_detail_harga_barang'][$key],
					'kartu_nilai'			=> $data['pembelian_detail_jumlah'][$key],
					'kartu_transaksi_kode' 	=> $dt['pembelian_kode'],
					'kartu_user' 			=> $dt['pembelian_user'],
					'kartu_keterangan' 		=> 'On Updated',
					'kartu_created_at' 		=> date('Y-m-d H:i:s'),
				];

				foreach ($last_detail as $i => $v) {
					if ($v['pembelian_detail_id'] == $data['pembelian_detail_id'][$key]) {
						unset($delete[$i]);
					}
				}

				$res_detail = $this->transaksipembeliandetail->update($data['pembelian_detail_id'][$key], $detail);
				if (!$res_detail['success']) {
					$res_detail = $this->transaksipembeliandetail->insert(gen_uuid($this->transaksipembeliandetail->get_table()), $detail);
					if ($res_detail['success']) {
						$id_detail[] = $res_detail['id'];
						$kartu['kartu_id'] = $res_detail['id'];
						$kartu['kartu_stok_keluar'] = 0;
						$kartu['kartu_keterangan'] = 'Insert On Updated';
						$xkartu = $this->stokkartu->insert_kartu($kartu, 'B');
					}
				} else {
					$xkartu = $this->stokkartu->update_kartu($kartu, 'B');
					$id_detail[] = $res_detail['id'];
				}
				$br_satuan = $this->barangsatuan->read(['barang_satuan_parent' => $value, 'barang_satuan_order' => '1']);
				if (isset($br_satuan['barang_satuan_id'])) {
					$br_satuan_utama = $this->db->where('barang_satuan_id', $br_satuan['barang_satuan_id'])
						->set('barang_satuan_harga_beli', $hpp_ecer)
						->update('pos_barang_satuan');
					if ($br_satuan_utama && $br_satuan_utama['barang_satuan_id'] !== $data['pembelian_detail_satuan'][$key]) {
						$br_satuan_lain = $this->db->where('barang_satuan_id', $data['pembelian_detail_satuan'][$key])
							->set('barang_satuan_harga_beli', $hpp)
							->update('pos_barang_satuan');
					}
				}
				$id = implode(', ', $id_detail);
				$res['id_detail'] = $id;
			}
			foreach ($delete as $n => $value) {
				$del = $this->transaksipembeliandetail->delete($value['pembelian_detail_id']);
				if ($del['success']) {
					$kartu = [
						'kartu_id' 				=> $value['pembelian_detail_id'],
						'kartu_barang_id'		=> $value['pembelian_detail_barang_id'],
						'kartu_stok_masuk' 		=> 0,
						'kartu_transaksi' 		=> 'Pembelian',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					// $this->db->delete('pos_pembelian_barang_detail', array('pembelian_detail_id' => $value['pembelian_detail_id']));
					$this->stokkartu->update_kartu($kartu, 'B');
				}
			}
		});
		$this->response($operation);
	}

	public function save_haga()
	{
		$data = varPost();
		$success = true;
		foreach ($data['detail_barang_satuan_satuan_id'] as $key => $value) {
			$detail = [
				'barang_satuan_parent' 		=> $data['barang_satuan_parent'],
				'barang_satuan_satuan_id' 	=> $value,
				'barang_satuan_kode' 		=> $data['detail_barang_satuan_kode'][$key],
				'barang_satuan_konversi' 	=> $data['detail_barang_satuan_konversi'][$key],
				'barang_satuan_harga_beli'	=> $data['detail_barang_satuan_harga_beli'][$key],
				'barang_satuan_keuntungan'	=> $data['detail_barang_satuan_keuntungan'][$key],
				'barang_satuan_harga_jual'	=> $data['detail_barang_satuan_harga_jual'][$key],
				'barang_satuan_disc'		=> $data['detail_barang_satuan_disc'][$key],
				'barang_satuan_order'		=> $key,
			];
			if ($data['detail_barang_satuan_id'][$key]) {
				$res = $this->barangsatuan->update($data['detail_barang_satuan_id'][$key], $detail);
				$success = $res['success'];
			} else {
				$res = $this->barangsatuan->insert(gen_uuid($this->barangsatuan->get_table()), $detail);
				$success = $res['success'];
			}
		}
		// $update = $this->db->update_batch('pos_barang_satuan', $detail, 'barang_satuan_id');			
		/*$res = ['success' => false];
		if($update){
			$res['success'] = true;
		}*/
		$this->response(['success' => $success]);
	}

	public function save_detail_harga()
	{
		$data = varPost();
		// echo count($data['hg_detail_barang_satuan_id']);exit;
		$success = true;
		$dt = [];
		foreach ($data['hg_barang_id'] as $key => $value) {
			foreach ($data['hg_detail_barang_satuan_id'][$key] as $k => $v) {
				$detail = [
					'barang_satuan_parent' 		=> $value,
					'barang_satuan_satuan_id' 	=> $data['hg_detail_barang_satuan_satuan_id'][$key][$k],
					'barang_satuan_kode' 		=> $data['hg_detail_barang_satuan_kode'][$key][$k],
					'barang_satuan_konversi' 	=> $data['hg_detail_barang_satuan_konversi'][$key][$k],
					'barang_satuan_harga_beli'	=> $data['hg_detail_barang_satuan_harga_beli'][$key][$k],
					'barang_satuan_keuntungan'	=> $data['hg_detail_barang_satuan_keuntungan'][$key][$k],
					'barang_satuan_harga_jual'	=> $data['hg_detail_barang_satuan_harga_jual'][$key][$k],
					'barang_satuan_order'		=> $k,
				];
				if ($v) {
					$res = $this->barangsatuan->update($v, $detail);
				} else {
					$res = $this->barangsatuan->insert(gen_uuid($this->barangsatuan->get_table()), $detail);
				}
				if ($res['success'] == false) $success = false;
				else $dt[] = $res['record'];
			}
			$new_data = [];
			$new_data['barang_nama'] = $data['hg_barang_nama'][$key];
			$new_data['barang_satuan'] = $data['hg_detail_barang_satuan_satuan_id'][$key][1];
			$new_data['barang_satuan_kode'] = $data['hg_detail_barang_satuan_kode'][$key][1];
			$new_data['barang_harga'] = $data['hg_detail_barang_satuan_harga_jual'][$key][1];
			$new_data['barang_satuan_opt'] = $data['hg_detail_barang_satuan_satuan_id'][$key][2];
			$new_data['barang_satuan_opt_kode'] = $data['hg_detail_barang_satuan_kode'][$key][2];
			$new_data['barang_harga_opt'] = $data['hg_detail_barang_satuan_harga_jual'][$key][2];
			$new_data['barang_satuan_opt2_kode'] = $data['hg_detail_barang_satuan_kode'][$key][3];
			$new_data['barang_harga_opt2'] = $data['hg_detail_barang_satuan_harga_jual'][$key][3];
			$this->barang->update($value, $new_data);
		}
		$this->response(['success' => $success, 'record' => $dt]);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->transaksipembeliandetail->select(array('filters_static' => $data, 'sort_static' => 'pembelian_detail_order')));
	}

	public function delete()
	{
		$idPembelian = varPost();

		// Integrasi dengan stok
		$dPembelian = $this->db->get_where('pos_pembelian_barang', ['pembelian_id' => $idPembelian['id']])->row_array();
		$dPembelianDetail = $this->db->get_where('v_pos_pembelian_barang_detail', ['pembelian_detail_parent' => $idPembelian['id']])->result_array();

		foreach ($dPembelianDetail as $key => $value) {
			$cBarangStok = $this->db->get_where('pos_barang', ['barang_id' => $value['pembelian_detail_barang_id']])->row_array()['barang_stok'];

			$barangId = $value['pembelian_detail_barang_id'];
			$qty = $value['pembelian_detail_qty_barang'];
			$konversi = $value['pembelian_detail_konversi'];
			$hasilBarang = $cBarangStok + ($qty * $konversi);

			$this->db->set('barang_stok', $hasilBarang);
			$this->db->where('barang_id', $barangId);
			$this->db->update('pos_barang');

			$kartu = [
				'kartu_id' 				=> $value['pembelian_detail_id'],
				'kartu_barang_id'		=> $value['pembelian_detail_barang_id'],
				'kartu_stok_masuk' 		=> 0,
				'kartu_transaksi' 		=> 'Pembelian',
				'kartu_keterangan' 		=> 'Deleted On Updated',
			];
			$this->stokkartu->update_kartu($kartu, 'B');
		}
		// End integrasi dengan stok
		$idPembelian['pembelian_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->transaksipembelian->update($idPembelian['id'], $idPembelian);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		// $last_beli = $this->transaksipembelian->delete(varPost('id', varExist($data, $this->transaksipembelian->get_primary(true))));
		$operation = $this->transaksipembelian->delete(varPost('id', varExist($data, $this->transaksipembelian->get_primary(true))));

		$last_detail = $this->transaksipembeliandetail->select(array('filters_static' => array('pembelian_detail_parent' => $data['id']), 'sort_static' => 'pembelian_detail_order asc'))['data'];
		foreach ($last_detail as $key => $value) {
			$kartu = [
				'kartu_id' 				=> $value['pembelian_detail_id'],
				'kartu_barang_id'		=> $value['pembelian_detail_barang_id'],
				'kartu_stok_masuk' 		=> 0,
				'kartu_transaksi' 		=> 'Pembelian',
				'kartu_keterangan' 		=> 'Deleted On Updated',
			];
			$this->stokkartu->update_kartu($kartu, 'B');
		}
		$detail = $this->transaksipembeliandetail->delete(array('pembelian_detail_parent' => $data['id']));
		$last_jurnal = $this->db
			->select('jurnal_umum_id, jurnal_umum_tanggal, jurnal_umum_penerima, jurnal_umum_lawan_transaksi, jurnal_umum_reference_kode')
			->where(['jurnal_umum_reference_id' => $data['id']])
			->get('ak_jurnal_umum')
			->row_array();
		// if($data['pembelian_bayar_opsi'] == 'T'){
		if ($last_jurnal) {
			$trans = [
				// 'jurnal_umum_nobukti' 			=> $this->jurnal->generate_kode('BKK', $dt['pembelian_tanggal']),
				'jurnal_umum_total' 			=> 0,
				'jurnal_umum_tanggal' 			=> $last_jurnal['jurnal_umum_tanggal'],
				'jurnal_umum_penerima' 			=> $last_jurnal['jurnal_umum_penerima'],
				'jurnal_umum_lawan_transaksi'   => $last_jurnal['jurnal_umum_lawan_transaksi'],
				'jurnal_umum_keterangan'		=> 'Barang Dagangan Tunai',
				'jurnal_umum_reference'			=> 'persediaan_barang',
				'jurnal_umum_unit'				=> '1',
				'jurnal_umum_reference_id'		=> $data['id'],
				'jurnal_umum_reference_kode'	=> $last_jurnal['jurnal_umum_reference_kode'],
			];
			$debit = $kredit = $debit_uraian = $kredit_uraian = [];
			$trans['jurnal_umum_total'] = 0;
			$trans['jurnal_umum_id'] = $last_jurnal['jurnal_umum_id'];
			// print_r($trans);exit;
			$this->jurnal->edit_jurnal($debit, $kredit, $trans, $debit_uraian, $kredit_uraian);
		}
		// $this->rekap_jurnal($rekap_jurnal);
		// }
		$this->response($operation);
	}

	public function cetak($value = '')
	{
		if ($value != '') {
			$data = $this->db->where('pembelian_id', $value)
				->get('v_pos_pembelian_barang')
				->row_array();
			$detail = $this->db->where('pembelian_detail_parent', $value)
				->order_by('pembelian_detail_order')
				->get('v_pos_pembelian_barang_detail')
				->result_array();
			$html = '<style>
				*{
					font-family: Arial, Helvetica, sans-serif;
				}
				*, table, p, li{
					line-height:1.5;
				}

				table{
					border-radius: 25px;
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
					background-color : #6f7bd9;
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
					te	xt-align:center;
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
						<p>' . $this->session->userdata('toko_nama') . '</p>
					</td>
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI PEMBELIAN BARANG </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['pembelian_tanggal'] ? date("d/m/Y", strtotime($data['pembelian_tanggal']))  : "-") . '</td>
					<td class="right">Supplier : ' . ($data['supplier_kode'] ? $data['supplier_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td>No. Transaksi : ' . $data['pembelian_kode'] . '</td>
					<td class="right">' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Jatuh Tempo: ' . ($data['pembelian_jatuh_tempo'] ? date("d/m/Y", strtotime($data['pembelian_jatuh_tempo'])) : "-") . '</td>
					<td class="right">' . ($data['supplier_alamat'] ? $data['supplier_alamat'] : "-") . ' / ' . ($data['supplier_telp'] ? $data['supplier_telp'] : "-") . '</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">Kode</th>
					<th class="t-center">Nama Barang</th>
					<th class="t-center">Qty</th>
					<th class="t-center">Satuan</th>
					<th class="t-center">Harga</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Netto</th>
					<th class="t-center">H. Jual</th>
					<th class="t-center">H. Pack</th>
					<th class="t-center">(%)</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			foreach ($detail as $key => $value) {
				// $percentase =(($value['barang_satuan_harga_jual']-$value['pembelian_detail_harga_barang'])/$value['pembelian_detail_harga_barang'])*100;

				$harga = $value['pembelian_detail_harga_barang'];
				$diskon = $harga * ($data['pembelian_diskon_persen'] / 100);
				$sjumlah = $harga - $diskon;
				$pajak = $sjumlah * ($data['pembelian_pajak_persen'] / 100);
				$hpp = $sjumlah + $pajak;
				// $data['pembelian_detail_harga_barang'][$key] = $hpp/$data['pembelian_detail_konversi'][$key];
				$percentase = (($value['barang_harga'] - $hpp) / $hpp) * 100; //keuntungan dari harga per satuan terkecil dibandingkan dengan harga pokok

				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
						<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
						<td>' . ($value['pembelian_detail_qty'] ? $value['pembelian_detail_qty'] : "-") . '</td>
						<td>' . ($value['barang_satuan_kode'] ? $value['barang_satuan_kode'] . "(" . $value['barang_satuan_konversi'] . ")" : "-") . '</td>
						<td>' . ($value['pembelian_detail_harga'] ? number_format($value['pembelian_detail_harga'])  : "") . '</td>
						<td>' . ($value['pembelian_detail_jumlah'] ? number_format($value['pembelian_detail_jumlah'])  : "-") . '</td>
						<td class="divider">' . ($hpp ? number_format($hpp)  : "-") . '</td>
						<td>' . number_format($value['barang_harga']) . '</td>
						<td>' . number_format($value['barang_harga_opt2']) . '</td>
						<td>' . number_format($percentase, 2, ',', '.') . '</td>
					</tr>';
				// <td>'.($value['pembelian_detail_discount'] ? number_format($value['pembelian_detail_discount'],2,',','.')  : "").'</td>
				// <td>'.$percentase.' %</td>
				$totalJml += $value['pembelian_detail_jumlah'];
				$totalQty += $value['pembelian_detail_qty'];
			}

			$html .= '
				<tr>
					<td colspan="3" class="total">Total</td>
					<td  class="total">' . $totalQty . '</td>
					<td colspan="2">Subtotal</td>
					<td colspan="5" class="total">' . number_format($totalJml) . '</td>
				</tr>
				<tr>
					<td colspan="4" class="total"></td>
					<td colspan="2" class="total">Potongan</td>
					<td colspan="5" class="total">' . number_format($data['pembelian_diskon']) . '</td>
				</tr>
			<tr>
				<td colspan="4" class="total"></td>
				<td colspan="2" class="total">Pajak</td>
				<td colspan="5" class="total">' . number_format($data['pembelian_pajak']) . '</td>
			</tr>

				<tr>
					<td colspan="4" class="total">Grand Total</td>
					<td colspan="2" class="total"></td>
					<td colspan="5" class="total">' . number_format($data['pembelian_bayar_grand_total']) . '</td>
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

			// print_r('<pre>');print_r($html);print_r('</pre>');exit;
			// if ($data['pembelian_bayar_opsi'] == 'T') {
			// 	$html .= $this->print($data['pembelian_id']);
			// }
			createPdf(array(
				'data'          => $html,
				'json'          => true,
				'paper_size'    => 'A4',
				'file_name'     => 'BUKTI PEMBELIAN',
				'title'         => 'BUKTI PEMBELIAN',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));
		}
	}

	public function print($value)
	{
		$data = varPost();
		$jurnal = $this->jurnal->read(array('jurnal_umum_reference_id' => $value));
		$tgl = phpChgDate(date('Y-m-d', strtotime($jurnal['jurnal_umum_tanggal'])));
		$huruf = $this->terbilang($jurnal['jurnal_umum_total']);
		$jurnal_detail = $this->jurnaldetail->select(array('filters_static' => array('jurnal_umum_detail_jurnal_umum' => $jurnal['jurnal_umum_id'])));

		$header = '<div style="page-break-after: always"></div>
            <div style="width:89.3%; border:1px solid #000;padding:5px;padding:5px;margin-left:17px">
                <table cellpadding="0" cellspacing="0" align="left"  border="0" class="" style="display:inline-table;border:1px solid black;width:9cm!important" rotate="-90.0deg">
                        <tr>
                          <td style="text-align:center; line-height:14px;padding:4px;" >
                            <p style="font-size:15px;font-weight:bold;">
                            KPRI EKO KAPTI
                            </p>

                            <p style="font-family:Times New Roman;font-size:11px">
                            Kantor Kementerian Agama Kab Malang
                            </p>

                            <p style="font-family:Times New Roman;font-size:11px">
                            Badan Hukum : 168 B / BH / II / 17-69
                            </p>

                            <p style="font-family:Times New Roman;font-size:11px">
                            Jl. Kolonel Sugiono 39 Telp.834 894
                            </p>
                          </td>
                        </tr>
                </table>
            </div>

            <div style="margin-left:96px; margin-top:-345px;">
              <table cellspacing="0" style="width:91%;border:1px solid black; line-height:16px">
                <tr>
                    <td style="width:25%;font-size:11px;border-right:1px solid black">No. ' . ($jurnal['jurnal_umum_reference'] == 'kas_masuk' ? 'BKM' : 'BKK') . ': ' . explode('.', $jurnal['jurnal_umum_nobukti'])[1] . ' </td>
                    <td style="width:40%;font-size:11px;border-right:1px solid black"><b>BUKTI KAS ' . ($jurnal['jurnal_umum_reference'] == 'kas_masuk' ? 'MASUK' : 'KELUAR') . '</b></td>
                    <td style="width:35%;font-size:11px;">' . ($jurnal['jurnal_umum_reference'] == 'kas_masuk' ? 'BKM' : 'BKK') . '</td>
                </tr>
              </table>
              <table cellspacing="0" style="width:91%" cellpadding="4">
                <tr>
                    <td style="width:25%;font-size:11px;border-left:1px solid black;">' . ($jurnal['jurnal_umum_reference'] == 'kas_masuk' ? 'Diterima dari' : 'Dibayarkan Kepada') . '</td>
                    <td style="width:5%;font-size:11px;">:</td>
                    <td style="width:70%;font-size:11px;">' . $jurnal['jurnal_umum_penerima'] . '</td>
                </tr>
                <tr>
                    <td style="width:25%;font-size:11px;vertical-align:top;border-left:1px solid black;">Banyaknya Uang</td>
                    <td style="width:5%;font-size:11px;vertical-align:top;">:</td>
                    <td style="width:70%;font-size:11px;vertical-align:top;">' . $huruf . ' Rupiah</td>
                </tr>
                <tr>
                    <td style="width:25%;font-size:11px;border-left:1px solid black;">Untuk Pembayaran</td>
                    <td style="width:5%;font-size:11px;">:</td>
                    <td style="width:70%;font-size:11px;">' . $jurnal['jurnal_umum_keterangan'] . '</td>
                </tr>
                
              </table>
              <table cellspacing="0" style="width:92%" cellpadding="4">
              <tr>
                    <td style="width:25%;font-size:11px;border-left:1px solid black;">Terbilang</td>
                    <td style="width:5%;font-size:11px;">:</td>
                    <td colspan="2" style="font-size:11px;border:1px solid black;width:18%;">Rp. ' . number_format($jurnal['jurnal_umum_total'], 0, '', '.') . '</td>
	  				<td style="width:47%;font-size:11px;"></td>
                </tr>
              </table>';
		$shadow = '<table cellspacing="0" cellpadding="2" style="width:92%">';
		for ($i = 0; $i < 6; $i++) {
			$shadow .= '<tr>
                        <td style="width:10%;border-left:1px solid black;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:10%;"></td>
                        <td style="width:15%;font-size:11px;text-align:center"> </td>
                        <td style="width:15%;font-size:11px;text-align:center"> </td>
                        <td style="width:15%;font-size:11px;text-align:center"></td>
                    </tr>';
		}
		$shadow .= '</table>';
		$footer = '     
                <table cellspacing="0" cellpadding="2" style="width:92%">
                    <tr>
                        <td style="width:10%;border-left:1px solid black;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:10%;border-right:1px solid black;"></td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Malang,</td>
                        <td colspan="2" style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">' . $tgl . '</td>
                    </tr>
                    <tr>
                        <td style="width:10%;font-size:11px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Analis</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Rek.</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Debet</td>
                        <td style="width:10%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Kredit</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Mengetahui,</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Dibayar</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Diterima</td>
                    </tr>';
		$r = sizeof($jurnal_detail['data']);
		if ($r < 7) {
			for ($i = 0; $i < 7; $i++) {
				$value = $jurnal_detail['data'][$i];
				if (isset($jurnal_detail['data'][$i])) {
					$footer .= '<tr>
                        <td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
                        <td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;padding-left:5px">' . $value['jurnal_umum_detail_akun_kode'] . '</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right;padding-right:5px">' . number_format($value['jurnal_umum_detail_debit'], 0, '', '.') . '</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right;padding-right:5px">' . number_format($value['jurnal_umum_detail_kredit'], 0, '', '.') . '</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center">' . ($i == 0 ? "Pengurus" : "") . '</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center">' . ($i == 0 ? "Kasir" : "") . '</td>
                        <td style="width:15%;font-size:11px;text-align:center">' . ($i == 0 ? "oleh" : "") . '</td>
                    </tr>';
				} else {
					$footer .= '<tr>
                        ' . ($i == 6 ? '<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">' : '<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;">') . '&nbsp;</td>
                        <td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        ' . ($i == 6 ? '<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black">' : '<td style="width:10%;font-size:11px;border-right:1px solid black;">') . '&nbsp;</td>
                        ' . ($i == 6 ? '<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black">' : '<td style="width:10%;font-size:11px;border-right:1px solid black;">') . '&nbsp;</td>
                        ' . ($i == 6 ? '<td style="width:10%;font-size:11px;border-bottom:1px solid black">' : '<td style="width:10%;font-size:11px;">') . '&nbsp;</td>
                    </tr>';
				}
			}
		}
		$footer .= '</table>
                </div>';
		return $header . $shadow . $footer;
		/* createPdf(array(
            'data'          => $header.$shadow.$footer,
            'json'          => true,
            'paper_size'    => 'A4',
            // 'paper_size'    => array('85','240'),
            // 'paper_size'    => array('85','240'),
            'file_name'     => 'Bukti Kas',
            'title'         => 'Bukti Kas',
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '5 5 0 5',
            'font_face'     => 'sans_fonts',
            'font_size'     => '10'
        ));*/
	}

	public function loaddetail()
	{
		$data = varPost();
		$no = 1;
		$detail = $this->transaksipembeliandetail->select(array('filters_static' => array(
			'pembelian_detail_parent' => $data['pembelian_detail_parent']
		)));
		// print_r($detail);
		$html = '<table cellspacing="0" cellpadding="2" style="width:90%">
			<thead>
				<tr>
					<td>No</td>
					<td>Barang</td>
					<td>Satuan</td>
					<td>Harga</td>
					<td>Qty</td>
					<td>Subtotal</td>
				</tr>
			</thead>
			';
		$html .= '<tbody>';
		foreach ($detail['data'] as $key => $value) {
			$html .= '<tr>
						<td>' . $no++ . '</td>
						<td>' . $value['barang_nama'] . '</td>
						<td>' . $value['barang_satuan_kode'] . '</td>
						<td>' . $value['pembelian_detail_harga'] . '</td>
						<td>' . $value['pembelian_detail_qty'] . '</td>
						<td>' . $value['pembelian_detail_jumlah'] . '</td>
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

/* End of file Transaksipembelian.php */
/* Location: ./application/modules/Transaksipembelian/controllers/Transaksipembelian.php */