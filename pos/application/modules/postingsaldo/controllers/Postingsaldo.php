<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Postingsaldo extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'PostingsaldoModel' 		=> 'Postingsaldo',
			'PostingsaldodetailModel' 	=> 'Postingsaldodetail',
			'stokkartu/stokkartuModel' 	=> 'stokkartu',
			'akun/AkunSaldoModel' 		=> 'akunsaldo',
		));
	}

	public function index()
	{
		$this->response($this->select_dt(varPost(), 'Postingsaldo', 'table', false));
	}

	public function read()
	{
		$this->response($this->Postingsaldo->read(['posting_id' => varPost('posting_id')]));
	}
	public function save_posting()
	{
		$data = varPost();

		/*
		echo 'SELECT 			
		md5(CONCAT(barang_id,"' . $data['posting_bulan'] . '")), 
		"' . $parent . '", 
		barang_id, 
		"' . $data['posting_bulan'] . '",
		barang_kategori_barang,
		barang_kategori_barang, 
		barang_satuan_kode,
		barang_harga_beli,
		@stok_awal := barang_awal, 
		@stok_awal_nilai := barang_awal_nilai,
		@stok_akhir := IFNULL(@stok_awal,0)+IFNULL(beli_current,0)+IFNULL(rjual_current,0)-IFNULL(jual_current,0)-IFNULL(rbeli_current,0)+IFNULL(opname_current,0),
		@stok_akhir*barang_harga_beli,
		@hpp := @stok_awal_nilai+beli_current_nilai-rbeli_current_nilai,
		jual_current_nilai-rjual_current_nilai - @hpp,
		beli_current, 
		beli_current_nilai, 
		jual_current, 
		jual_current_nilai, 
		rbeli_current, 
		rbeli_current_nilai, 
		rjual_current, 
		rjual_current_nilai, 	
		opname_current, 
		opname_current_nilai, 
		NOW()							
		FROM pos_barang
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) jual_current, SUM(penjualan_detail_subtotal) jual_current_nilai FROM pos_penjualan_detail WHERE DATE_FORMAT(penjualan_detail_tanggal, "%Y-%m") = "' . $data['posting_bulan'] . '" GROUP BY penjualan_detail_barang_id) jc ON jc.penjualan_detail_barang_id = barang_id
		LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) jual_last FROM pos_penjualan_detail WHERE DATE_FORMAT(penjualan_detail_tanggal, "%Y-%m") > "' . $data['posting_bulan'] . '" GROUP BY penjualan_detail_barang_id) jl ON jl.penjualan_detail_barang_id = barang_id
		LEFT JOIN (SELECT pembelian_detail_barang_id, SUM(pembelian_detail_qty_barang) beli_current, SUM(pembelian_detail_jumlah) beli_current_nilai FROM pos_pembelian_barang_detail WHERE DATE_FORMAT(pembelian_detail_tanggal, "%Y-%m") = "' . $data['posting_bulan'] . '" GROUP BY pembelian_detail_barang_id) bc ON bc.pembelian_detail_barang_id = barang_id
		LEFT JOIN (SELECT pembelian_detail_barang_id, SUM(pembelian_detail_qty_barang) beli_last FROM pos_pembelian_barang_detail WHERE DATE_FORMAT(pembelian_detail_tanggal, "%Y-%m") > "' . $data['posting_bulan'] . '" GROUP BY pembelian_detail_barang_id) bl ON bl.pembelian_detail_barang_id = barang_id
		LEFT JOIN (SELECT retur_pembelian_detail_barang_id, SUM(retur_pembelian_detail_retur_qty_barang) rbeli_current, SUM(retur_pembelian_detail_jumlah) rbeli_current_nilai FROM pos_retur_pembelian_barang_detail WHERE DATE_FORMAT(retur_pembelian_detail_tanggal, "%Y-%m") = "' . $data['posting_bulan'] . '" GROUP BY retur_pembelian_detail_barang_id) rbc ON rbc.retur_pembelian_detail_barang_id = barang_id
		LEFT JOIN (SELECT retur_pembelian_detail_barang_id, SUM(retur_pembelian_detail_retur_qty_barang) rbeli_last FROM pos_retur_pembelian_barang_detail WHERE DATE_FORMAT(retur_pembelian_detail_tanggal, "%Y-%m") > "' . $data['posting_bulan'] . '" GROUP BY retur_pembelian_detail_barang_id) rbl ON rbl.retur_pembelian_detail_barang_id = barang_id
		LEFT JOIN (SELECT retur_penjualan_detail_barang_id, SUM(retur_penjualan_detail_retur_qty_barang) rjual_last FROM pos_retur_penjualan_detail WHERE DATE_FORMAT(retur_penjualan_detail_tanggal, "%Y-%m") > "' . $data['posting_bulan'] . '" GROUP BY retur_penjualan_detail_barang_id) rjl ON rjl.retur_penjualan_detail_barang_id = barang_id
		LEFT JOIN (SELECT retur_penjualan_detail_barang_id, SUM(retur_penjualan_detail_retur_qty_barang) rjual_current, SUM(retur_penjualan_detail_jumlah) rjual_current_nilai FROM pos_retur_penjualan_detail WHERE DATE_FORMAT(retur_penjualan_detail_tanggal, "%Y-%m") = "' . $data['posting_bulan'] . '" GROUP BY retur_penjualan_detail_barang_id) rjc ON rjc.retur_penjualan_detail_barang_id = barang_id
		LEFT JOIN (SELECT opname_detail_barang_id, SUM(opname_detail_qty_koreksi) opname_current, SUM(opname_detail_nilai) opname_current_nilai FROM pos_stock_opname_detail WHERE DATE_FORMAT(opname_detail_tanggal, "%Y-%m") = "' . $data['posting_bulan'] . '" GROUP BY opname_detail_barang_id) op ON op.opname_detail_barang_id = barang_id
		WHERE barang_yearly > 0 ORDER BY barang_kode';
		exit;
		*/

		// getlast_posting
		$this->Postingsaldo->delete(['posting_bulan' => $data['posting_bulan']]);
		$this->Postingsaldodetail->delete(['posting_detail_bulan' => $data['posting_bulan']]);

		// Posting Saldo Detail
		$parent = gen_uuid($this->Postingsaldo->get_table());
		// $bulan = date('Y-m', strtotime("+1 month", $data['posting_bulan'].'-01'));

		// posting_gudang_id is commented
		// posting detail barang
		$event_posting_detail = $this->db->query('
			INSERT INTO pos_posting_saldo_detail(
			posting_detail_id, 
			posting_detail_parent, 
			posting_detail_barang_id, 
			posting_detail_bulan, 
			posting_detail_kategori_id, 
			posting_detail_kategori_parent, 
			posting_detail_satuan_kode, 
			posting_detail_hpp, 
			posting_detail_awal_stok, 
			posting_detail_awal_nilai,
			posting_detail_akhir_stok,
			posting_detail_akhir_nilai,
			posting_detail_hpp_nilai,
			posting_detail_laba,
			posting_detail_pembelian_qty,
			posting_detail_pembelian_nilai,
			posting_detail_penjualan_qty,
			posting_detail_penjualan_nilai,
			posting_detail_retur_pembelian_qty,
			posting_detail_retur_pembelian_nilai,
			posting_detail_retur_penjualan_qty,
			posting_detail_retur_penjualan_nilai,
			posting_detail_opname_qty,
			posting_detail_opname_nilai,
			posting_detail_created
		)
		SELECT 			
			md5(CONCAT(barang_id,\'' . $data['posting_bulan'] . '\')), 
			\'' . $parent . '\', 
			barang_id, 
			\'' . $data['posting_bulan'] . '\',
			barang_kategori_barang,
			barang_kategori_barang, 
			barang_satuan_kode,
			barang_harga_beli,
			/*
			@stok_awal := barang_awal, 
			@stok_awal_nilai := barang_awal_nilai,
			@stok_akhir := COALESCE(@stok_awal,0)+COALESCE(beli_current,0)+COALESCE(rjual_current,0)-COALESCE(jual_current,0)-COALESCE(rbeli_current,0)+COALESCE(opname_current,0),
			@stok_akhir*barang_harga_beli,
			@hpp := @stok_awal_nilai+beli_current_nilai-rbeli_current_nilai,
			jual_current_nilai-rjual_current_nilai - @hpp,
			*/
			barang_awal,
			barang_awal_nilai,
			COALESCE(barang_awal,0)+COALESCE(beli_current,0)+COALESCE(rjual_current,0)-COALESCE(jual_current,0)-COALESCE(rbeli_current,0)+COALESCE(opname_current,0),
			(COALESCE(barang_awal,0)+COALESCE(beli_current,0)+COALESCE(rjual_current,0)-COALESCE(jual_current,0)-COALESCE(rbeli_current,0)+COALESCE(opname_current,0))*barang_harga_beli,
			barang_awal_nilai+beli_current_nilai-rbeli_current_nilai,
			jual_current_nilai-rjual_current_nilai - barang_awal_nilai+beli_current_nilai-rbeli_current_nilai,
			beli_current, 
			beli_current_nilai, 
			jual_current, 
			jual_current_nilai, 
			rbeli_current, 
			rbeli_current_nilai, 
			rjual_current, 
			rjual_current_nilai, 	
			opname_current, 
			opname_current_nilai, 
			NOW()							
			FROM pos_barang
			LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) jual_current, SUM(penjualan_detail_subtotal) jual_current_nilai FROM pos_penjualan_detail WHERE to_char(penjualan_detail_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\' GROUP BY penjualan_detail_barang_id) jc ON jc.penjualan_detail_barang_id = barang_id
			LEFT JOIN (SELECT penjualan_detail_barang_id, SUM(penjualan_detail_qty_barang) jual_last FROM pos_penjualan_detail WHERE to_char(penjualan_detail_tanggal, \'YYYY-MM\') > \'' . $data['posting_bulan'] . '\' GROUP BY penjualan_detail_barang_id) jl ON jl.penjualan_detail_barang_id = barang_id
			LEFT JOIN (SELECT pembelian_detail_barang_id, SUM(pembelian_detail_qty_barang) beli_current, SUM(pembelian_detail_jumlah) beli_current_nilai FROM pos_pembelian_barang_detail WHERE to_char(pembelian_detail_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\' GROUP BY pembelian_detail_barang_id) bc ON bc.pembelian_detail_barang_id = barang_id
			LEFT JOIN (SELECT pembelian_detail_barang_id, SUM(pembelian_detail_qty_barang) beli_last FROM pos_pembelian_barang_detail WHERE to_char(pembelian_detail_tanggal, \'YYYY-MM\') > \'' . $data['posting_bulan'] . '\' GROUP BY pembelian_detail_barang_id) bl ON bl.pembelian_detail_barang_id = barang_id
			LEFT JOIN (SELECT retur_pembelian_detail_barang_id, SUM(retur_pembelian_detail_retur_qty_barang) rbeli_current, SUM(retur_pembelian_detail_jumlah) rbeli_current_nilai FROM pos_retur_pembelian_barang_detail WHERE to_char(retur_pembelian_detail_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\' GROUP BY retur_pembelian_detail_barang_id) rbc ON rbc.retur_pembelian_detail_barang_id = barang_id
			LEFT JOIN (SELECT retur_pembelian_detail_barang_id, SUM(retur_pembelian_detail_retur_qty_barang) rbeli_last FROM pos_retur_pembelian_barang_detail WHERE to_char(retur_pembelian_detail_tanggal, \'YYYY-MM\') > \'' . $data['posting_bulan'] . '\' GROUP BY retur_pembelian_detail_barang_id) rbl ON rbl.retur_pembelian_detail_barang_id = barang_id
			LEFT JOIN (SELECT retur_penjualan_detail_barang_id, SUM(retur_penjualan_detail_retur_qty_barang) rjual_last FROM pos_retur_penjualan_detail WHERE to_char(retur_penjualan_detail_tanggal, \'YYYY-MM\') > \'' . $data['posting_bulan'] . '\' GROUP BY retur_penjualan_detail_barang_id) rjl ON rjl.retur_penjualan_detail_barang_id = barang_id
			LEFT JOIN (SELECT retur_penjualan_detail_barang_id, SUM(retur_penjualan_detail_retur_qty_barang) rjual_current, SUM(retur_penjualan_detail_jumlah) rjual_current_nilai FROM pos_retur_penjualan_detail WHERE to_char(retur_penjualan_detail_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\' GROUP BY retur_penjualan_detail_barang_id) rjc ON rjc.retur_penjualan_detail_barang_id = barang_id
			LEFT JOIN (SELECT opname_detail_barang_id, SUM(opname_detail_qty_koreksi) opname_current, SUM(opname_detail_nilai) opname_current_nilai FROM pos_stock_opname_detail WHERE to_char(opname_detail_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\' GROUP BY opname_detail_barang_id) op ON op.opname_detail_barang_id = barang_id
		 WHERE barang_yearly > 0 ORDER BY barang_kode
		');
		// final posting
		$event_posting = $this->db->query('INSERT INTO pos_posting_saldo(
				posting_id,
				posting_bulan,
				posting_awal_qty,
				posting_awal_nilai,
				posting_penjualan_qty,
				posting_penjualan_nilai,
				posting_pembelian_qty,
				posting_pembelian_nilai,
				posting_pembelian_retur_qty,
				posting_pembelian_retur_nilai,
				posting_penjualan_retur_qty,
				posting_penjualan_retur_nilai,
				posting_masuk_qty,
				posting_masuk_nilai,
				posting_keluar_qty,
				posting_keluar_nilai,
				posting_opname_qty,
				posting_opname_nilai,
				posting_stok,
				posting_stok_nilai,
				posting_hpp,
				posting_laba,
				posting_persediaan_photobox,
				posting_persediaan_photocopy, 
				posting_created, 
				posting_aktif,
				posting_penjualan_potongan
				) SELECT 
				\'' . $parent . '\',
				\'' . $data['posting_bulan'] . '\',
				aw_stok,
				aw_stok_nilai,
				/*
				@penjualan_qty,
				@penjualan_nilai,
				@pembelian_qty,
				@pembelian_nilai,
				@retur_pembelian_qty,
				@retur_pembelian_nilai,
				@retur_penjualan_qty,
				@retur_penjualan_nilai,
				*/
				f1.posting_detail_penjualan_qty,
				f1.posting_detail_penjualan_nilai,
				f1.posting_detail_pembelian_qty,
				f1.posting_detail_pembelian_nilai,
				f1.posting_detail_retur_pembelian_qty,
				f1.posting_detail_retur_pembelian_nilai,
				f1.posting_detail_retur_penjualan_qty,
				f1.posting_detail_retur_penjualan_nilai,
				masuk_qty,
				masuk_nilai,
				keluar_qty,
				keluar_nilai,
				opname_qty,
				opname_nilai,
				ak_stok,
				ak_stok_nilai,
				hpp,
				laba,
				0,
				0,
				NOW(), 
				1,
				jual_potongan FROM (SELECT SUM(posting_detail_awal_stok) aw_stok,
				SUM(posting_detail_awal_nilai) aw_stok_nilai,
				/*
				@penjualan_qty := SUM(posting_detail_penjualan_qty),
				@penjualan_nilai := SUM(posting_detail_penjualan_nilai),
				@pembelian_qty := SUM(posting_detail_pembelian_qty),
				@pembelian_nilai := SUM(posting_detail_pembelian_nilai),
				@retur_pembelian_qty := SUM(posting_detail_retur_pembelian_qty),
				@retur_pembelian_nilai := SUM(posting_detail_retur_pembelian_nilai),
				@retur_penjualan_qty := SUM(posting_detail_retur_penjualan_qty),
				@retur_penjualan_nilai := SUM(posting_detail_retur_penjualan_nilai),
				*/
				SUM(posting_detail_penjualan_qty :: INTEGER) as posting_detail_penjualan_qty,
				SUM(posting_detail_penjualan_nilai :: INTEGER) as posting_detail_penjualan_nilai,
				SUM(posting_detail_pembelian_qty) as posting_detail_pembelian_qty,
				SUM(posting_detail_pembelian_nilai) as posting_detail_pembelian_nilai,
				SUM(posting_detail_retur_pembelian_qty) as posting_detail_retur_pembelian_qty,
				SUM(posting_detail_retur_pembelian_nilai) as posting_detail_retur_pembelian_nilai,
				SUM(posting_detail_retur_penjualan_qty) as posting_detail_retur_penjualan_qty,
				SUM(posting_detail_retur_penjualan_nilai) as posting_detail_retur_penjualan_nilai,

				(SUM(posting_detail_pembelian_qty) - COALESCE(SUM(posting_detail_retur_pembelian_qty),0)) masuk_qty,
				(SUM(posting_detail_pembelian_nilai) - COALESCE(SUM(posting_detail_retur_pembelian_nilai),0)) masuk_nilai,
				(SUM(posting_detail_penjualan_qty::INTEGER) - COALESCE(SUM(posting_detail_retur_penjualan_qty),0)) keluar_qty,
				(SUM(posting_detail_penjualan_nilai::INTEGER) - COALESCE(SUM(posting_detail_retur_penjualan_nilai),0)) keluar_nilai,
				SUM(posting_detail_opname_qty) opname_qty,
				SUM(posting_detail_opname_nilai) opname_nilai,
				
				SUM(posting_detail_akhir_stok) ak_stok,
				SUM(posting_detail_akhir_nilai) ak_stok_nilai,
				SUM(posting_detail_hpp_nilai) hpp,
				SUM(posting_detail_laba) laba FROM pos_posting_saldo_detail WHERE posting_detail_bulan = \'' . $data['posting_bulan'] . '\') f1 CROSS JOIN
				(SELECT SUM(penjualan_total_potongan) jual_potongan FROM pos_penjualan WHERE to_char(penjualan_tanggal, \'YYYY-MM\') = \'' . $data['posting_bulan'] . '\') f2
				');
			
		// print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;

		// update saldo awal barang
		/*
		$update_barang = $this->db->query('UPDATE pos_barang LEFT JOIN pos_posting_saldo_detail ON barang_id = posting_detail_barang_id 
											SET barang_awal = posting_detail_akhir_stok, barang_awal_nilai = posting_detail_akhir_nilai 
										   WHERE posting_detail_bulan = \'' . $data['posting_bulan'] . '\'');
		*/
		$update_barang = $this->db->query('UPDATE
			pos_barang pbu
		SET
			barang_awal = posting_detail_akhir_stok,
			barang_awal_nilai = posting_detail_akhir_nilai
		FROM
			pos_barang pbs
			LEFT JOIN pos_posting_saldo_detail ON pbs.barang_id = posting_detail_barang_id
		WHERE
			posting_detail_bulan = \'' . $data['posting_bulan'] . '\'');

		// Locking data transaction
		// $show = $this->db->update('pos_posting_saldo', ['posting_aktif'=>'1'], ['posting_bulan' => $data['posting_bulan'],'posting_gudang_id' => $this->config->item('base_gudang')]);
		$lock_pembelian = $this->db->update('pos_pembelian_barang', ['pembelian_lock' => '1'], ['to_char(pembelian_tanggal, \'YYYY-MM\')=' => $data['posting_bulan']]);
		$lock_penjualan = $this->db->update('pos_penjualan', ['penjualan_lock' => '1'], ['to_char(penjualan_tanggal, \'YYYY-MM\')=' => $data['posting_bulan']]);
		$lock_retur_pembelian = $this->db->update('pos_retur_pembelian_barang', ['retur_pembelian_lock' => '1'], ['to_char(retur_pembelian_tanggal, \'YYYY-MM\')=' => $data['posting_bulan']]);
		$lock_retur_penjualan = $this->db->update('pos_retur_penjualan_barang', ['retur_penjualan_lock' => '1'], ['to_char(retur_penjualan_tanggal, \'YYYY-MM\')=' => $data['posting_bulan']]);

		// $message = 'insert_detail is ' . $event_posting_detail . ', insert_final is ' . $event_posting . ', update_barang is ' . $update_barang;
		$message = '';
		$this->response(['success' => true, 'message' => $message]);
	}
}
