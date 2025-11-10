<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksipenjualan extends Base_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'transaksipenjualanModel' 					=> 'transaksipenjualan',
			'transaksipenjualandetailModel' 			=> 'transaksipenjualandetail',
			'stokkartu/stokkartuModel' 					=> 'stokkartu',
			'anggota/anggotaModel' 						=> 'anggota',
			'barang/barangModel' 						=> 'barang',
			'pengajuanpinjaman/PengajuanPinjamanModel'	=> 'pengajuan',
			'kartupinjaman/kartupinjamanModel'			=> 'kartupinjaman',
			'kartusimpanan/kartusimpananModel'			=> 'kartusimpanan',
			'jurnal/JurnalModel' 						=> 'jurnal',
		));
	}
	public function index()
	{
		$var = varPost();
		$this->response(
			$this->select_dt(varPost(), 'transaksipenjualan', 'table', false, array(
				'penjualan_tanggal BETWEEN "' . $var['tanggal1'] . '" AND "' . $var['tanggal2'] . '" ' => null,
			))
		);
	}
	public function get_barang()
	{
		$barang = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text, TO_BASE64(concat(barang_satuan,"||",barang_satuan_opt,"||",IFNULL(barang_isi, 0),"||",IFNULL(barang_harga, 0),"||",IFNULL(barang_harga_pokok, 0))) saved FROM v_ms_barang WHERE barang_barcode = "' . varPost('val') . '" LIMIT 1')->result_array();
		if (!$barang) {
			$barang = $this->db->query('SELECT barang_id as id, concat(barang_kode, " - ", barang_nama) as text FROM v_ms_barang_barcode WHERE barang_barcode_kode = "' . varPost('val') . '" LIMIT 1')->result_array();
		}
		$this->response($barang);
	}
	public function select_ajax($value = '')
	{
		$data = varPost();
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(anggota_id) total FROM ms_anggota WHERE anggota_nama like "' . $data['q'] . '%" OR anggota_kode like "' . $data['q'] . '%" ORDER BY anggota_nama ASC')->result_array();

		$return = $this->db->query('SELECT anggota_id as id, concat(anggota_kode, " - ", anggota_nama) as text, anggota_kode FROM ms_anggota WHERE anggota_nama like "' . $data['q'] . '%" OR anggota_kode like "' . $data['q'] . '%" ORDER BY anggota_nama ASC LIMIT ' . $data['page'] . $data['limit'])->result_array();
		$this->response(array('items' => $return, 'total_count' => $total[0]['total']));
	}

	public function barang_ajax($value = '')
	{
		$data = varPost();
		if (strlen($data['q']) > 10) {
			$barcode =  $this->barangbarcode->read(array('barang_barcode_kode' => $data['q']));
			if (isset($barcode['barang_kode'])) $data['q'] = $barcode['barang_kode'];
		}
		$where = ($data['fdata']['barang_supplier_id']) ? 'barang_supplier_id = "' . $data['fdata']['barang_supplier_id'] . '" AND ' : '';
		$data['page'] = isset($data['page']) ? ((intval($data['page']) - 1) * intval($data['limit'])) . ',' : '';
		$total = $this->db->query('SELECT count(barang_id) total FROM ms_barang WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR barang_kode like "' . $data['q'] . '%") ')->result_array();
		$return = $this->db->query('SELECT barang_id as id, barang_kode, barang_nama, barang_harga, barang_stok, barang_is_konsinyasi as saved FROM v_ms_barang WHERE ' . $where . ' (barang_nama like "' . $data['q'] . '%" OR barang_kode like "' . $data['q'] . '%") ORDER BY barang_nama LIMIT ' . $data['page'] . $data['limit'])->result_array();
		// , " (stok: ", barang_stok, ")"
		$new_return = [];
		foreach ($return as $key => $value) {
			$new_return[] = [
				'id' 	=> $value['id'],
				'view' 	=> '<span class="detail-barang-select" style="width: 45px;">' . $value['barang_kode'] . '</span><span class="detail-barang-select"  style="width: 320px;">' . $value['barang_nama'] . '</span><span class="detail-barang-select" style="width: 100px;">' . number_format($value['barang_harga']) . '</span><span class="detail-barang-select" style="width: 65px;">Stok : ' . $value['barang_stok'] . '</span>',
				'saved'	=> $value['saved'],
				'text'	=> $value['barang_kode'] . '-' . $value['barang_nama']
			];
		}
		$this->response(array('items' => $new_return, 'total_count' => $total[0]['total']));
	}


	function read($value = '')
	{
		$this->response($this->transaksipenjualan->read(varPost()));
	}

	function read_detail($value = '')
	{
		$this->response($this->transaksipenjualandetail->read(varPost()));
	}

	public function table_detail_barang()
	{
		$this->response(
			$this->select_dt(varPost(), 'transaksipenjualandetail', 'table', true, varPost('jual'))
		);
	}

	public function store()
	{
		$data = varPost();
		$data['penjualan_tanggal'] = date('Y-m-d', strtotime($data['penjualan_tanggal']));
		$data['penjualan_kode'] 		= $this->transaksipenjualan->gen_kode_penjualan(false, [
			'penjualan_tanggal' => $data['penjualan_tanggal'],
			'penjualan_metode'  => $data['penjualan_metode'],
		]);
		$data['penjualan_user_id'] 		= $this->session->userdata('user_id');
		$data['penjualan_user_nama']	= $this->session->userdata('user_alias');
		$data['penjualan_aktif'] 		= '1';
		$data['penjualan_updated'] 		= date('Y-m-d H:i:s');
		$data['penjualan_created'] 		= date('Y-m-d H:i:s');
		$data['penjualan_bayar_jumlah'] = 0;
		$data['penjualan_retur'] 		= 0;
		// $data['penjualan_total_grand'] 	= $data['penjualan_total_harga']-$data['penjualan_total_harga'];
		$error = [];
		$debit = [];
		if ($data['penjualan_metode'] !== 'B') {
			$data['penjualan_bank_id'] = null;
			$data['penjualan_bank_reff'] = null;
		} else {
			$debit[$data['penjualan_bank_id']] = $data['penjualan_total_bayar_tunai'];
		}
		$id = gen_uuid($this->transaksipenjualan->get_table());

		if (in_array("1", $data['penjualan_detail_jenis_barang'])) {
			$data['penjualan_jenis_barang'] = 'K';
		} else {
			$data['penjualan_jenis_barang'] = 'N';
		}

		$operation = $this->transaksipenjualan->insert($id, $data, function ($res) use ($data) {
			$detail = [];
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$dt = $res['record'];
				$detail = [
					'penjualan_detail_parent' 		=> $dt['penjualan_id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_potongan_persen' => $data['penjualan_detail_potongan_persen'][$key],
					'penjualan_detail_potongan' 	=> $data['penjualan_detail_potongan'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_order' 		=> $key,
				];
				$jenis_barang = [];

				$id_detail = gen_uuid($this->transaksipenjualandetail->get_table());
				$det_opr = $this->transaksipenjualandetail->insert($id_detail, $detail);

				if (!$det_opr['success']) $error[] = $det_opr;
				else {
					$kartu = $this->stokkartu->insert_kartu([
						'kartu_id' 			=> $id_detail,
						'kartu_tanggal' 	=> $dt['penjualan_tanggal'],
						'kartu_barang_id' 	=> $value,
						'kartu_satuan_id' 	=> $data['penjualan_detail_satuan'][$key],
						'kartu_stok_keluar'	=> $data['penjualan_detail_qty_barang'][$key],
						'kartu_stok_masuk'  => 0,
						'kartu_transaksi' 	=> 'Penjualan',
						'kartu_keterangan' 	=> 'On Insert',
						'kartu_harga'		=> $data['pembelian_detail_harga_barang'][$key],
						'kartu_transaksi_kode' => $dt['penjualan_kode'],
						'kartu_user' 		=> $dt['penjualan_user'],
						'kartu_created' 	=> date('Y-m-d H:i:s'),
					], 'J');
					if ($kartu) $error[] = [$kartu, $dt['penjualan_kode'], $value];
				}
			}
		});
		$kredit = ['4101' => $data['penjualan_total_grand']];
		$operation['error_log'] = $error;
		if ($operation['success'] == true && $data['penjualan_total_bayar_voucher']) {
			$agt = $this->anggota->read($data['penjualan_anggota_id']);
			if (isset($agt['anggota_id'])) {
				$debit['2155'] = $data['penjualan_total_bayar_voucher'];
				if ($data['penjualan_total_bayar_voucher_khusus'] > 0) {
					$debit['111101'] = $data['penjualan_total_bayar_voucher_khusus'];
					/*$this->db->where('anggota_id', $data['penjualan_anggota_id'])
		                	->set('anggota_saldo_simp_titipan_belanja', 'anggota_saldo_simp_titipan_belanja-'.$data['penjualan_total_bayar_voucher'], FALSE)
		                	->set('anggota_saldo_voucher', 'anggota_saldo_voucher-'.$data['penjualan_total_bayar_voucher_khusus'], FALSE)
		                	->set('anggota_saldo_voucher_exp_date', null)
							->update('ms_anggota');*/
					$voucher_khusus = $this->kartusimpanan->insert_kartu([
						'kartu_simpanan_anggota'		=> $data['penjualan_anggota_id'],
						'kartu_simpanan_tanggal'		=> date("Y-m-d"),
						'kartu_simpanan_saldo_masuk'	=> 0,
						'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher_khusus'],
						'kartu_simpanan_transaksi'		=> 'Voucher BHR',
						'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
						'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
						'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
						'kartu_simpanan_keterangan'		=> 'On Insert',
					], 'BHR');
				}
				/*else{
					$this->db->where('anggota_id', $data['penjualan_anggota_id'])
		                	->set('anggota_saldo_simp_titipan_belanja', 'anggota_saldo_simp_titipan_belanja-'.$data['penjualan_total_bayar_voucher'], FALSE)
							->update('ms_anggota');
				}*/
				$voucher = $this->kartusimpanan->insert_kartu([
					'kartu_simpanan_anggota'		=> $data['penjualan_anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher'],
					'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
					'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
					'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
					'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_simpanan_keterangan'		=> 'On Insert',
				], 'V');
			}
		}
		if ($operation['success'] == true && $data['penjualan_metode'] == 'K') {
			$id_pinjam = gen_uuid($this->pengajuan->get_table());
			$kode_pinjam = $this->pengajuan->gen_kode();

			$d_current = date('d', strtotime($data['penjualan_tanggal']));
			if ($d_current >= 20) {
				$bulan_tertagih = date("Y-m", strtotime("+2 months", strtotime($data['penjualan_tanggal'])));
			} else {
				$bulan_tertagih = date("Y-m", strtotime("+1 months", strtotime($data['penjualan_tanggal'])));
			}

			$this->pengajuan->insert($id_pinjam, array(
				'pengajuan_tgl' 			=> $data['penjualan_tanggal'],
				'pengajuan_tgl_realisasi'  => $data['penjualan_tanggal'],
				'pengajuan_no' 				=> $kode_pinjam,
				'pengajuan_no_pinjam'		=> $data['penjualan_kode'],
				'pengajuan_anggota' 		=> $data['penjualan_anggota_id'],
				'pengajuan_jumlah_pinjaman' => $data['penjualan_total_kredit'],
				'pengajuan_tenor' 			=> $data['penjualan_total_cicilan_qty'],
				'pengajuan_jasa' 			=> $data['penjualan_total_jasa'], //test again
				'pengajuan_pokok' 			=> $data['penjualan_total_kredit'],
				'pengajuan_penjualan_id' 	=> $id,
				'pengajuan_jenis' 			=> 'B',
				'pengajuan_tag_jenis' 		=> $data['penjualan_jenis_potongan'],
				'pengajuan_tag_bulan'		=> $bulan_tertagih,
				'pengajuan_status' 			=> 2,
				'pengajuan_proteksi' 		=> 0,
				'pengajuan_proteksi_nilai'	=> 0,
				'pengajuan_tag_awal'		=> $data['penjualan_kredit_awal'],
				'pengajuan_jatuh_tempo'		=> $data['penjualan_jatuh_tempo'],
				'pengajuan_create_at'		=> date('Y-m-d H:i:s'),
				'pengajuan_create_by'		=> $this->session->userdata('pegawai_id'),
				'pengajuan_aktif'			=> 1,
				'pengajuan_pokok_bulanan'	=> $data['penjualan_total_cicilan'],
				'pengajuan_jasa_bulanan'	=> $data['penjualan_total_jasa_nilai'],
				'pengajuan_sisa_angsuran'	=> $data['penjualan_total_kredit'],
				'pengajuan_tunggakan_jasa'	=> $data['penjualan_total_jasa'],

			));

			$pinjaman = $this->kartupinjaman->insert_kartu([
				'kartu_pinjaman_anggota'		=> $data['penjualan_anggota_id'],
				'kartu_pinjaman_tanggal'		=> $data['penjualan_tanggal'],
				'kartu_pinjaman_id' 			=> $id_pinjam,
				'kartu_pinjaman_saldo_pinjam'	=> $data['pengajuan_jumlah_pinjaman'],
				'kartu_pinjaman_saldo_bayar'	=> 0,
				'kartu_pinjaman_transaksi'		=> 'Pencairan Pinjaman Barang',
				'kartu_pinjaman_transaksi_kode'	=> $data['penjualan_kode'],
				'kartu_pinjaman_create_by' 		=> $this->session->userdata('pegawai_id'),
				'kartu_pinjaman_create_at' 		=> date('Y-m-d H:i:s'),
			], 'B');
			$debit['1131'] = $data['penjualan_total_kredit'];
		}
		if ($data['penjualan_total_bayar_tunai']) {
			if (isset($debit['111101'])) $debit['111101'] += ($data['penjualan_total_bayar_tunai'] - $data['penjualan_total_kembalian']);
			else $debit['1111001'] = ($data['penjualan_total_bayar_tunai'] - $data['penjualan_total_kembalian']);
		}
		$trans = [
			'jurnal_umum_nobukti' 			=> $data['penjualan_kode'],
			'jurnal_umum_tanggal' 			=> $data['penjualan_tanggal'],
			'jurnal_umum_penerima' 			=> $data['penjualan_anggota_id'],
			'jurnal_umum_lawan_transaksi'   => $data['penjualan_anggota_id'],
			'jurnal_umum_keterangan'		=> 'Penjualan Barang Dagang',
			'jurnal_umum_reference'			=> 'persediaan_barang',
			'jurnal_umum_unit'				=> '1',
			'jurnal_umum_reference_id'		=> $data['penjualan_id'],
			'jurnal_umum_reference_kode'	=> $data['penjualan_kode'],
		];
		// $this->jurnal->add_jurnal($debit, $kredit, $trans);
		if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id']);
		$this->response($operation);
	}

	public function tprint($id)
	{
		$data = varPost();
		// if(isset($data['penjualan_id'])) $id = $data['penjualan_id'];
		$jual = $this->db->select('penjualan_id,penjualan_kode, penjualan_tanggal, penjualan_user_nama, penjualan_created, anggota_saldo_simp_titipan_belanja, grup_gaji_kode, grup_gaji_nama, anggota_alamat, anggota_kode ,anggota_nip, anggota_nama, anggota_kota, penjualan_total_potongan,penjualan_total_harga,penjualan_total_grand,penjualan_total_bayar_tunai,penjualan_total_bayar_voucher,penjualan_total_kembalian, penjualan_metode, penjualan_total_jasa_nilai, penjualan_total_cicilan, penjualan_total_cicilan_qty, penjualan_kredit_awal, penjualan_jatuh_tempo,penjualan_total_bayar_voucher_khusus, penjualan_total_kredit')
			->where('penjualan_id', $id)
			->from('v_pos_penjualan')->get()->result_array();
		$html = '';
		if ($jual) {
			$jual = $jual[0];
			$detail = $this->db->select('barang_nama, penjualan_detail_qty, penjualan_detail_potongan,penjualan_detail_harga, penjualan_detail_subtotal')
				->where('penjualan_detail_parent', $id)
				->order_by('penjualan_detail_order', 'asc')
				->from('v_pos_penjualan_detail')->get()->result_array();
			$nprint = 1;
			if ($jual['penjualan_total_voucher'] > 0) $nprint = 2;
			if ($jual['penjualan_metode'] == 'K') $nprint++;
			$break = '<div style="page-break-after: always;"></div>';

			$html .= '
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
			for ($i = 1; $i <= $nprint; $i++) {
				if ($i > 1) $html .= $break;
				$html .= '<h1 style="font-size:13px;text-align:center;margin-bottom:0">EKA MART KPRI EKO KAPTI <br>
							KANTOR KEMENAG KAB.MALANG</h1>
							<h2 style="font-size:12px;text-align:center; margin-top:2px">TELEPON (0341) 834894</h2>
						
						<hr style="border-top: 1px dashed black;">
						<h2 style="text-align:center;font-size:13px;">* NOTA ' . ($jual['penjualan_metode'] == 'C' ? 'TUNAI' : 'KREDIT') . ' *</h2>
	                    <table>
	                        <tbody>
	                            <tr>
	                            	<td style="margin-left:400px;">Tgl</td>
	                            	<td>:</td>
	                            	<td>' . date('d/m/Y', strtotime($jual['penjualan_tanggal'])) . '</td>
	                            	<td>Opt </td>
	                            	<td>:</td>
	                            	<td style="text-transform:uppercase">' . $jual['penjualan_user_nama'] . '</td>
	                            </tr>
	                            <tr>
	                            	<td>Nota</td>
	                            	<td>:</td>
	                            	<td>' . $jual['penjualan_kode'] . '</td>
	                            	<td colspan="4">' . date('H:i:s', strtotime($jual['penjualan_created'])) . '</td>
	                            </tr>
	                        </tbody>
	                    </table>';
				$html .= '<table>
	                    	<hr style="border-top: 1px dashed;margin:0">
	                    	
	                    	';
				$totalpotongan = 0;
				foreach ($detail as $key => $value) {
					$subtotal = $value['penjualan_detail_subtotal'] + $value['penjualan_detail_potongan'];
					$totalpotongan += $value['penjualan_detail_potongan'];
					$html .= '<tr>
	                                <td class="text-left">' . substr($value['barang_nama'], 0, 13) . '</td>
	                                <td class="text-left">' . $value['penjualan_detail_qty'] . ' x</td>
	                                <td class="text-right">' . number_format($value['penjualan_detail_harga']) . '</td>
	                                <td class="text-right">' . number_format($subtotal) . '</td>
	                            </tr>';
					if ($value['penjualan_detail_potongan']) {
						$html .= '<tr>
		                                <td class="text-right" colspan="3">DISKON :</td>
		                                <td class="text-right">(' . number_format($value['penjualan_detail_potongan']) . ')</td>
		                            </tr>';
					}
				}
				$totalpotongan += $jual['penjualan_total_potongan'];

				$html .= '<table>
	                    <hr style="border-top: 1px dashed black;width:200px;" align="right">
	                    <tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Subtotal :</td>
	                        <td class="text-right">' . number_format($jual['penjualan_total_harga']) . '</td>
	                    </tr><tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Potongan :</td>
	                        <td class="text-right">(' . number_format($jual['penjualan_total_potongan']) . ')</td>
	                    </tr><tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Total  :</td>
	                        <td class="text-right">' . number_format($jual['penjualan_total_grand']) . '</td>
	                    </tr>';

				if ($jual['anggota_nama']) {
					$html .= '<tr>
		                        <td class="text-right" colspan="3">Titip Belanja :</td>
		                        <td class="text-right">' . number_format($jual['penjualan_total_bayar_voucher']) . '</td>
		                    </tr><tr>
		                        <td class="text-right" colspan="3">V. Belanja :</td>
		                        <td class="text-right">' . number_format($jual['penjualan_total_bayar_voucher_khusus']) . '</td>
		                    </tr><tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Kredit :</td>
	                        <td class="text-right">' . number_format($jual['penjualan_total_kredit']) . '</td>
	                    </tr>';
				}
				$html .= '<tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Tunai :</td>
	                        <td class="text-right">' . number_format($jual['penjualan_total_bayar_tunai']) . '</td>
	                    </tr><tr>
	                        <td class="text-right" colspan="3" style="text-transform: capitalize;">Kembali :</td>
	                        <td class="text-right">' . number_format($jual['penjualan_total_kembalian']) . '</td>
	                    </tr>';
				$html .= '
	                    </table>
	                    <hr style="border-top: 1px dashed black;">';
				$html .= '<table>
		                        <tbody>';

				if ($jual['penjualan_metode'] == 'K') {
					$html .= '<tr>
	                                <td>Cicil</td>
	                                <td>:</td>
	                                <td>' . $jual['penjualan_total_cicilan'] . ' x ' . $jual['penjualan_total_cicilan_qty'] . '</td>
	                                <td>Jasa</td>
	                                <td>:</td>
	                                <td>' . $jual['penjualan_total_jasa_nilai'] . '</td>
	                            </tr>
	                            <tr>
	                                <td>Tagih</td>
	                                <td>:</td>
	                                <td>' . date('d/m/Y', $jual['penjualan_kredit_awal']) . '</td>
	                                <td>JTP</td>
	                                <td>:</td>
	                                <td>' . date('d/m/Y', $jual['penjualan_jatuh_tempo']) . '</td>
	                            </tr>';
					// $alamat = '';
				}

				if ($jual['anggota_nama']) {
					$html .= '<tr>
                                <td style="text-transform: capitalize;width:10%">Group</td>
                                <td>:</td>
                                <td colspan="4"> (' . $jual['grup_gaji_kode'] . ') ' . $jual['grup_gaji_nama'] . '</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td colspan="4"> ' . $jual['anggota_kota'] . ' </td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td colspan="4"> ' . $jual['anggota_nip'] . ' </td>
                            </tr>
	                    </table>
	                    <table>	                    
	                        <tbody>
	                            <tr>
	                                <td colspan="4" style="text-transform: capitalize;">Sld. Titipbelanja(' . date('d/m/Y', strtotime($jual['penjualan_tanggal'])) . ')</td>
	                                <td>:</td>
	                                <td colspan="2">' . number_format($jual['anggota_saldo_simp_titipan_belanja']) . ' </td>
	                            </tr>
	                        </tbody>
	                    </table>
	                    <hr style="border-top: 1px dashed black;">';
					$html .= '<table>              
	                            <tr>
	                                <td class="text-left" style="text-transform: capitalize;">' . (($jual['anggota_nama']) ? 'Nasabah :' . $jual['anggota_kode'] : '') . ' </td>
	                                <td class="" style="text-transform: capitalize;text-align:center">' . (($jual['anggota_nama']) ? 'Kasir ' : '') . '</td>
	                            </tr>
	                    	<tr>
	                    		<td colspan="2"><p></p></td>
	                    	</tr>
		                    <tr>
		                    	<td class="text-left">' . (($jual['anggota_nama']) ? '(' . $jual['anggota_nama'] . ')' : '') . '</td>
		                    	<td class="text-right">' . (($jual['anggota_nama']) ? '(' . $jual['penjualan_user_nama'] . ')' : ' ') . '</td>
		                    </tr>
		            </table>
		            <hr style="border-top: 1px dashed black;">';
				} else {
					$html .= '</tbody>
	                    </table><hr style="border-top: 1px dashed black;">';
				}

				if ($value['penjualan_detail_potongan']) {
					$html .= '
					    <h2 style="text-align:center;font-size:11px;margin-top:0px;margin-bottom:10px;">Selamat anda hemat sebesar(' . number_format($totalpotongan) . ')</h2>';
				}

				$html .= '
	                	<table>              
	                            <tr>
	                                <td style="font-size:11px!important">*Tidak menerima pengembalian barang</td>
	                            </tr>
	                            <tr>
	                            	<td style="font-size:11px!important">*Terimakasih atas kunjungan anda</td>
	                            </tr>
	                    </table>';
			}

			$html .= '</div>';
		}
		if (isset($data['tjson'])) $this->response(array('tprint' => $html));
		return $html;
	}
	public function update()
	{
		$data = varPost();
		unset($data['penjualan_tanggal']);
		$operation = $this->transaksipenjualan->update($data['penjualan_id'], $data, function (&$res) use ($data) {
			$detail = $id_detail = [];
			$last_detail = $this->transaksipenjualandetail->select(array('filters_static' => array('penjualan_detail_parent' => $data['penjualan_id']), 'sort_static' => 'penjualan_detail_order asc'))['data'];
			$delete = $last_detail;
			foreach ($data['penjualan_detail_barang_id'] as $key => $value) {
				$detail = [
					'penjualan_detail_parent' 		=> $res['record']['penjualan_id'],
					'penjualan_detail_barang_id'	=> $value,
					'penjualan_detail_satuan' 		=> $data['penjualan_detail_satuan'][$key],
					'penjualan_detail_harga' 		=> $data['penjualan_detail_harga'][$key],
					'penjualan_detail_qty' 			=> $data['penjualan_detail_qty'][$key],
					'penjualan_detail_qty_barang' 	=> $data['penjualan_detail_qty_barang'][$key],
					'penjualan_detail_subtotal' 	=> $data['penjualan_detail_subtotal'][$key],
					'penjualan_detail_tanggal' 		=> $dt['penjualan_tanggal'],
					'penjualan_detail_order' 		=> $key,
				];

				$kartu = [
					'kartu_id' 				=> $data['penjualan_detail_id'][$key],
					'kartu_tanggal' 		=> $dt['penjualan_tanggal'],
					'kartu_barang_id' 		=> $value,
					'kartu_satuan_id' 		=> $data['penjualan_detail_satuan'][$key],
					'kartu_stok_keluar' 	=> $data['penjualan_detail_qty_barang'][$key],
					'kartu_transaksi' 		=> 'Penjualan',
					'kartu_harga'			=> $data['penjualan_detail_harga'][$key],
					'kartu_transaksi_kode' 	=> $dt['penjualan_kode'],
					'kartu_user' 			=> $dt['penjualan_user'],
					'kartu_created' 		=> date('Y-m-d H:i:s'),
					'kartu_keterangan' 		=> 'On Updated',
				];

				foreach ($last_detail as $i => $v) {
					if ($v['penjualan_detail_id'] == $data['penjualan_detail_id'][$key]) unset($delete[$i]);
				}
				$res_detail = $this->transaksipenjualandetail->update($data['penjualan_detail_id'][$key], $detail);

				if (!$res_detail['success']) {
					$res_detail = $this->transaksipenjualandetail->insert(gen_uuid($this->transaksipenjualandetail->get_table()), $detail);
					if ($res_detail['success']) {
						$id_detail[] = $res_detail['id'];
						$kartu['kartu_id'] = $res_detail['id'];
						$kartu['kartu_stok_masuk'] = 0;
						$kartu['kartu_keterangan'] = 'Insert On Updated';
						$kartu = $this->stokkartu->insert_kartu($kartu, 'J');
					}
				} else {
					$id_detail[] = $res_detail['id'];
					$xkartu = $this->stokkartu->update_kartu($kartu, 'J');
				}

				if ($data['penjualan_total_bayar_voucher_khusus'] > 0) {
					$debit['111101'] = $data['penjualan_total_bayar_voucher_khusus'];

					$voucher_khusus = $this->kartusimpanan->update_kartu([
						'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
						'kartu_simpanan_tanggal'		=> date("Y-m-d"),
						'kartu_simpanan_saldo_masuk'	=> 0,
						'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher_khusus'],
						'kartu_simpanan_transaksi'		=> 'Voucher BHR',
						'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
						'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
						'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
						'kartu_simpanan_keterangan'		=> 'On Update',
					], 'BHR');
				}
				$voucher = $this->kartusimpanan->update_kartu([
					'kartu_simpanan_anggota'		=> $res['record']['penjualan_anggota_id'],
					'kartu_simpanan_tanggal'		=> date("Y-m-d"),
					'kartu_simpanan_saldo_masuk'	=> 0,
					'kartu_simpanan_saldo_keluar'	=> $data['penjualan_total_bayar_voucher'],
					'kartu_simpanan_transaksi'		=> 'Titipan Belanja',
					'kartu_simpanan_transaksi_kode'	=> $data['penjualan_kode'],
					'kartu_simpanan_create_by' 		=> $this->session->userdata('pegawai_id'),
					'kartu_simpanan_create_at' 		=> date('Y-m-d H:i:s'),
					'kartu_simpanan_keterangan'		=> 'On Update',
				], 'V');
				$id = implode(', ', $id_detail);
				$res['id_detail'] = $id;
			}

			foreach ($delete as $n => $value) {
				$del = $this->transaksipenjualandetail->delete($value['penjualan_detail_id']);
				if ($del['success']) {
					$kartu = [
						'kartu_id' 				=> $value['penjualan_detail_id'],
						'kartu_stok_keluar' 	=> 0,
						'kartu_transaksi' 		=> 'Penjualan',
						'kartu_keterangan' 		=> 'Deleted  On Updated',
					];
					$this->db->delete('pos_penjualan_detail', array('penjualan_detail_id' => $value['penjualan_detail_id']));
					$this->stokkartu->update_kartu($kartu, 'J');
				}
			}
		});
		if (isset($data['cetak']) && $data['cetak']) $operation['print'] = $this->tprint($operation['id']);
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->transaksipenjualandetail->select(array('filters_static' => $data, 'sort_static' => 'penjualan_detail_order')));
	}


	public function destroy()
	{
		$data = varPost();
		$operation = $this->transaksipenjualan->delete(varPost('id', varExist($data, $this->transaksipenjualan->get_primary(true))));
		$last_detail = $this->transaksipenjualandetail->select(array('filters_static' => array('penjualan_detail_parent' => $data['id']), 'sort_static' => 'penjualan_detail_order asc'))['data'];
		foreach ($last_detail as $key => $value) {
			$kartu = [
				'kartu_id' 				=> $value['penjualan_detail_id'],
				'kartu_barang_id'		=> $value['penjualan_detail_barang_id'],
				'kartu_stok_keluar'		=> 0,
				'kartu_transaksi' 		=> 'Penjualan',
				'kartu_keterangan' 		=> 'Deleted',
			];
			$this->stokkartu->update_kartu($kartu, 'J');
		}
		$operation = $this->transaksipenjualandetail->delete(array('penjualan_detail_parent' => $data['id']));
		$this->response($operation);
	}

	public function cetak($value = '')
	{
		if ($value) {
			$data = $this->db->where('penjualan_id', $value)
				->get('v_pos_penjualan_barang')
				->row_array();
			$detail = $this->db->where('penjualan_detail_parent', $value)
				->get('v_pos_penjualan_barang_detail')
				->result_array();
			// print_r(array("data"=>$data,"detail"=> $detail));
			// exit();

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
					<td class="right" ><p>' . (date("d/m/Y")) . '</p></td>
				</tr>
				<tr>
					<td colspan="2" class="kop">
							<h4> BUKTI PEMBELIAN BARANG </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : ' . ($data['penjualan_tanggal'] ? date("d/m/Y", strtotime($data['penjualan_tanggal']))  : "-") . '</td>
					<td class="right">Supplier : ' . ($data['anggota_kode'] ? $data['supplier_kode'] : "-") . '</td>
				</tr>
				<tr>
					<td>No. Transaksi : ' . ($data['penjualan_faktur'] ? $data['penjualan_faktur'] : "-") . '</td>
					<td class="right">' . ($data['supplier_nama'] ? $data['supplier_nama'] : "-") . '</td>
				</tr>
				<tr>
					<td>Jatuh Tempo: ' . ($data['penjualan_jatuh_tempo'] ? $data['penjualan_jatuh_tempo'] : "-") . '</td>
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
					<th class="t-center">Harga</th>
					<th class="t-center">Discount</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Netto</th>
					<th class="t-center">H. Jual</th>
					<th class="t-center">($)</th>
				</tr>';

			$totalJml = 0;
			$totalQty = 0;
			foreach ($detail as $key => $value) {
				$percentase = 5;
				$hrgJual = $value['penjualan_detail_harga'] + ($percentase / 100 * $value['penjualan_detail_harga']);
				$html .= '<tr>
						<td>' . ($key + 1) . '</td>
						<td class="divider">' . ($value['barang_kode'] ? $value['barang_kode'] : "-") . '</td>
						<td>' . ($value['barang_nama'] ? $value['barang_nama'] : "-") . '</td>
						<td>' . ($value['penjualan_detail_qty'] ? $value['penjualan_detail_qty'] : "-") . '</td>
						<td>' . ($value['penjualan_detail_harga'] ? number_format($value['penjualan_detail_harga'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['penjualan_detail_discount'] ? number_format($value['penjualan_detail_discount'], 2, ',', '.')  : "") . '</td>
						<td>' . ($value['penjualan_detail_jumlah'] ? number_format($value['penjualan_detail_jumlah'], 2, ',', '.')  : "-") . '</td>
						<td class="divider">' . ($value['penjualan_detail_harga'] ? number_format($value['penjualan_detail_harga'], 2, ',', '.')  : "-") . '</td>
						<td>' . number_format($hrgJual, 2, ',', '.') . '</td>
						<td>' . $percentase . '</td>
					</tr>';
				$totalJml += $value['penjualan_detail_jumlah'];
				$totalQty += $value['penjualan_detail_qty'];
			}


			$html .= '<tr>
					<td colspan="3" class="total">Total</td>
					<td colspan="3" class="total">' . $totalQty . '</td>
					<td colspan="4" class="total">' . number_format($totalJml, 2, ',', '.') . '</td>
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
					<td class="bottom">NURS</td>
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
				'paper_size'    => 'A4-L',
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
}

/* End of file Transaksipenjualan.php */
/* Location: ./application/modules/Transaksipenjualan/controllers/Transaksipenjualan.php */