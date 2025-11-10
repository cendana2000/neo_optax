<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'pembayaranModel' 		=> 'pembayaran',
			'pembayarandetailModel' => 'pembayarandetail',
			'transaksipembelian/transaksipembelianModel' 		=> 'transaksipembelian',
			'jurnal/JurnalModel' 	=> 'jurnal',
		));
	}

	public function index()
	{
		$var=varPost();
		$this->response(
			$this->select_dt($var,'pembayaran','table',true,array('pembayaran_aktif' => 1,
				'pembayaran_tanggal BETWEEN "'.$var['tanggal1'].'" AND "'.$var['tanggal2'].'" ' => null,
			))
		);
	}

	function read($value='')
	{
		$this->response($this->pembayaran->read(varPost()));
	}

	public function store()
	{
		$data = varPost();
		$data['pembayaran_kode'] = $this->pembayaran->gen_kode_pembayaran();
		$data['pembayaran_user'] = $this->session->userdata('user_id');
		$data['pembayaran_aktif'] = '1';
		$error = [];
		$sales = $this->db->select('sales_id')
				 ->get_where('ms_sales', array(
					'sales_nama' 		=> $data['pembayaran_sales'], 
					'sales_supplier_id' => $data['pembayaran_supplier_id'],
				))
				->result_array();
		if(count($sales) < 1){			
			$this->db->insert('ms_sales', array(
				'sales_id' 			=> gen_uuid($this->pembayaran->get_table()), 
				'sales_supplier_id' => $data['pembayaran_supplier_id'],
				'sales_nama' 		=> $data['pembayaran_sales'], 
			));
		}
		// print_r($data);exit;
		$operation = $this->pembayaran->insert(gen_uuid($this->pembayaran->get_table()), $data, function($res) use ($data)
		{
			$detail = [];
			foreach ($data['pembayaran_detail_pembelian_id'] as $key => $value) {
				$detail = [
					'pembayaran_detail_parent' 		=> $res['record']['pembayaran_id'],
					'pembayaran_detail_pembelian_id'=> $value,
					'pembayaran_detail_jatuh_tempo'	=> $data['pembayaran_detail_jatuh_tempo'][$key],
					'pembayaran_detail_tagihan' 	=> $data['pembayaran_detail_tagihan'][$key],
					'pembayaran_detail_retur' 		=> $data['pembayaran_detail_retur'][$key],
					'pembayaran_detail_potongan' 	=> $data['pembayaran_detail_potongan'][$key],
					'pembayaran_detail_sisa' 		=> $data['pembayaran_detail_sisa'][$key],
					'pembayaran_detail_bayar' 		=> $data['pembayaran_detail_bayar'][$key],
				];
				$tag = $data['pembayaran_detail_tagihan'][$key]-$data['pembayaran_detail_retur'][$key];
				// bayar tidak boleh melebihi tagihan
				$bayar = intval($data['pembayaran_detail_bayar'][$key]);
				$sisa = $tag - $bayar;
				$det_opr = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()),$detail);
				if(!$det_opr['success']) $error[$key]['detail'] = $det_opr;
				else{
					if($data['pembayaran_status'] == '1'){
						/*$this->db->set('pembelian_bayar_jumlah', 'pembelian_bayar_jumlah+'.$bayar, FALSE);
						$this->db->set('pembelian_bayar_sisa', $sisa);
						$this->db->where('pembelian_id', $value);
						$this->db->update('pos_pembelian_barang');*/
						$this->update_pembelian($value);						
						$beli = $this->db->affected_rows();
						if($this->db->affected_rows() <= 0) $error[$key]['beli'] = $det_opr;
					}
				}
			}	

			if($data['pembayaran_status'] == '1'){
				$kredit = [];
				$bfr_diskon = $data['pembayaran_bayar'];
				if($data['pembayaran_retur'] || $data['pembayaran_potongan']){
					$kredit = [
						'5005' => ($data['pembayaran_retur']+$data['pembayaran_potongan']),
					];	
					$bfr_diskon += ($data['pembayaran_retur']+$data['pembayaran_potongan']);
				}
				$kredit[$data['pembayaran_akun_id']] = $data['pembayaran_bayar'];
				$debit = [
					'2112' => $data['pembayaran_tagihan']
				];
				$debit_keterangan = [
					'2112' => 'Hutang Dagang'
				];
				$kredit_keterangan = [$data['pembayaran_akun_id'] => 'Pembayaran Hutang Dagang'];
				$trans = [
					'jurnal_umum_nobukti' 			=> $this->jurnal->generate_kode('BKK', $data['pembayaran_tanggal']),
					'jurnal_umum_tanggal' 			=> $data['pembayaran_tanggal'],
					'jurnal_umum_penerima' 			=> $data['pembayaran_supplier_id'],
					'jurnal_umum_lawan_transaksi'   => $data['pembayaran_supplier_id'],
					'jurnal_umum_keterangan'		=> 'Pembayaran Pembelian Barang Dagang',
					'jurnal_umum_reference'			=> 'persediaan_barang',
					'jurnal_umum_reference_id'		=> $res['record']['pembayaran_id'],
					'jurnal_umum_reference_kode'	=> $data['pembayaran_kode'],
					'jurnal_umum_unit'				=> '1',
				];
				$this->jurnal->add_jurnal($debit, $kredit, $trans, $debit_keterangan, $kredit_keterangan);
			}
		});
		$operation['error'] = $error;
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();
		$operation = $this->pembayaran->update($data['pembayaran_id'], $data, function(&$res) use ($data)
		{
			$detail = $id_detail = [];
			foreach ($data['pembayaran_detail_pembelian_id'] as $key => $value) {
				$detail = [					
					'pembayaran_detail_parent' 		=> $res['record']['pembayaran_id'],
					'pembayaran_detail_pembelian_id'=> $value,
					'pembayaran_detail_jatuh_tempo'	=> $data['pembayaran_detail_jatuh_tempo'][$key],
					'pembayaran_detail_tagihan' 	=> $data['pembayaran_detail_tagihan'][$key],
					'pembayaran_detail_retur' 		=> $data['pembayaran_detail_retur'][$key],
					'pembayaran_detail_bayar' 		=> $data['pembayaran_detail_bayar'][$key],
				];
				$res_detail = $this->pembayarandetail->update($data['pembayaran_detail_id'][$key], $detail);
                if(!$res_detail['success']){
					$res_detail = $this->pembayarandetail->insert(gen_uuid($this->pembayarandetail->get_table()),$detail);
					if($res_detail['success']) $id_detail[] = $res_detail['id'];	
					
					if($data['pembayaran_status'] == '1'){
						/*$tag = $data['pembayaran_detail_tagihan'][$key]-$data['pembayaran_detail_retur'][$key];
						// bayar tidak boleh melebihi tagihan
						$bayar = intval($data['pembayaran_detail_bayar'][$key])+intval($data['pembayaran_detail_potongan'][$key])+intval($data['pembayaran_detail_retur'][$key]);
						$sisa = $tag - $bayar;	
						$this->db->set('pembelian_bayar_jumlah', 'pembelian_bayar_jumlah+'.$bayar, FALSE);
						$this->db->set('pembelian_bayar_sisa', $sisa);
						$this->db->where('pembelian_id', $value);
						$this->db->update('pos_pembelian_barang');*/
						$this->update_pembelian($value);
					}
                }else{                	
					if($data['pembayaran_status'] == '1'){
	                	/*$bayar = $data['pembayaran_detail_bayar'][$key]+intval($data['pembayaran_detail_potongan'][$key])+intval($data['pembayaran_detail_retur'][$key])-$data['pembayaran_detail_bayar_last'][$key];
						$this->db->set('pembelian_bayar_jumlah', 'pembelian_bayar_jumlah+'.$bayar, FALSE);
						$this->db->set('pembelian_bayar_sisa', 'pembelian_bayar_sisa-'.$bayar, FALSE);
						$this->db->where('pembelian_id', $value);
						$this->db->update('pos_pembelian_barang');*/
						$this->update_pembelian($value);						
						$beli = $this->db->affected_rows();
						if($this->db->affected_rows() <= 0) $error[$key]['beli'] = $det_opr;
					}
                	$id_detail[] = $res_detail['id'];
                }
                $id = implode(', ', $id_detail);
                $res['id_detail'] = $id;

				if($data['pembayaran_status'] == '1'){
					$kredit = [];
					$bfr_diskon = $data['pembayaran_bayar'];
					if($data['pembayaran_retur'] || $data['pembayaran_potongan']){
						$kredit = [
							'5005' => ($data['pembayaran_retur']+$data['pembayaran_potongan']),
						];	
						$bfr_diskon += ($data['pembayaran_retur']+$data['pembayaran_potongan']);
					}
					$kredit[$data['pembayaran_akun_id']] = $data['pembayaran_bayar'];
					$debit = [
						'2112' => $data['pembayaran_tagihan']
					];
					$debit_keterangan = [
						'2112' => 'Hutang Dagang'
					];
					$kredit_keterangan = [$data['pembayaran_akun_id'] => 'Pembayaran Hutang Dagang'];
					$jurnal = $this->jurnal->read(['jurnal_umum_reference_id' => $res['record']['pembayaran_id']]);
					$trans = [
						'jurnal_umum_id' 				=> $jurnal['jurnal_umum_id'],
						'jurnal_umum_tanggal' 			=> $data['pembayaran_tanggal'],
						'jurnal_umum_penerima' 			=> $data['pembayaran_supplier_id'],
						'jurnal_umum_lawan_transaksi'   => $data['pembayaran_supplier_id'],
						'jurnal_umum_keterangan'		=> 'Pembayaran Pembelian Barang Dagang',
						'jurnal_umum_reference'			=> 'persediaan_barang',
						'jurnal_umum_reference_id'		=> $res['record']['pembayaran_id'],
						'jurnal_umum_reference_kode'	=> $data['pembayaran_kode'],
						'jurnal_umum_unit'				=> '1',
					];
					$this->jurnal->edit_jurnal($debit, $kredit, $trans, $debit_keterangan, $kredit_keterangan);
				}
			}
		});
		$this->response($operation);
	}

	public function get_detail()
	{
		$data = varPost();
		$this->response($this->pembayarandetail->select(array('filters_static'=> $data)));
	}


	public function destroy()
	{
		$data = varPost();
		$detail = $this->pembayarandetail->select(array('filters_static' => array('pembayaran_detail_parent'=>$data['id'])))['data'];
		$operation = $this->pembayaran->update(varPost('id'), array('pembayaran_aktif'=>0));
		foreach ($detail as $key => $value) {
			$this->update_pembelian($value['pembayaran_detail_pembelian_id']);
		}
		$jurnal = $this->jurnal->read(['jurnal_umum_reference_id' => varPost('id')]);
		if($jurnal['jurnal_umum_reference_id']){
			$jurnal['jurnal_umum_reference'] = 'delete';
			$jurnal['jurnal_umum_status'] = 'deactive';
			$this->jurnal->edit_jurnal([],[],$jurnal);
		}
		$this->response($operation);
	}

	public function update_pembelian($id)
	{
		// echo $id;exit;
		$update = $this->db->query('UPDATE pos_pembelian_barang 
						LEFT JOIN 
							(SELECT SUM(pembayaran_detail_bayar) bayar_jumlah, SUM(pembayaran_detail_retur) retur_jumlah,  pembayaran_detail_pembelian_id FROM pos_pembayaran_detail LEFT JOIN pos_pembayaran on pembayaran_id = pembayaran_detail_parent WHERE pembayaran_aktif = "1" GROUP BY pembayaran_detail_pembelian_id) as bayar 
						on pembayaran_detail_pembelian_id = pembelian_id 
						set pembelian_bayar_jumlah = bayar_jumlah, 
							pembelian_retur = retur_jumlah,
							pembelian_bayar_sisa = pembelian_bayar_grand_total-retur_jumlah-bayar_jumlah
						WHERE pembelian_id ="'.$id.'"');
		// echo $this->db->last_query();exit;
		return $update;
	}

	public function loaddetail(){
		$data = varPost();
		$no = 1;
		$detail = $this->pembayarandetail->select(array('filters_static'=>array(
			'pembayaran_detail_parent'=> $data['pembayaran_detail_parent']
		)));
		$html = '<table cellspacing="0" cellpadding="2" style="width:90%">
			<thead>
				<tr>
					<td>No</td>
					<td>Barang</td>
					<td>Satuan</td>
					<td>Jumlah</td>
				</tr>
			</thead>
			';
		$html .= '<tbody>';
			foreach ($detail['data'] as $key => $value) {
				$html .='<tr>
						<td>'.$no++.'</td>
						<td>'.$value['barang_nama'].'</td>
						<td>'.$value['satuan_nama'].'</td>
						<td>'.$value['order_detail_qty'].'</td>
						</tr>';
			}
		$html .= '</tbody>';		
		$html .= '</table>';
		echo json_encode(array(
			'success' 	=> true,
			'html' 		=> $html
		));
	}

	public function cetak($value='')
	{
		if ($value) {
			$data = $this->db->where('pembayaran_id', $value)
							->get('v_pos_pembayaran')
							->row_array();
			$detail = $this->db->where('pembayaran_detail_parent', $value)
							   ->get('v_pos_pembayaran_detail')
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
							<h4> BUKTI PEMBAYARAN FAKTUR PEMBELIAN </h4><br>
					</td>
				</tr>
				<tr>
					<td>Tanggal Transaksi : '.($data['pembayaran_tanggal'] ? date("d/m/Y",strtotime($data['pembayaran_tanggal']))  : "-").'</td>
					<td>No Bukti : '.($data['pembayaran_kode'] ? $data['pembayaran_kode'] : "-").'</td>
				</tr>
				<tr>
					<td class="">Supplier : '.($data['supplier_nama'] ? $data['supplier_nama'] : "-").'</td>
					<td class="">Akun : '.($data['akun_nama'] ? $data['akun_kode'].' - '.$data['akun_nama'] : "-").'</td>
				</tr>
				<tr>
					<td>Alamat: '.($data['supplier_alamat'] ? $data['supplier_alamat'] : "-").'</td>
				</tr>
			</table>
			<br>
			
			<table class="laporan" cellspacing=0 style="width:100%; border-collapse: collapse;">
				<tr>
					<th class="t-center">No.</th>
					<th class="t-center">No Faktur</th>
					<th class="t-center">Jumlah</th>
					<th class="t-center">Retur</th>
					<th class="t-center">Bayar</th>
					<th class="t-center">Sisa</th>
				</tr>';

				$totalJml =0;
				$totalQty =0;
				foreach ($detail as $key => $value) {
					$percentase = 5;
					$html .= '<tr>
						<td>'.($key+1).'</td>
						<td class="divider">'.($value['pembelian_kode'] ? $value['pembelian_kode'] : "-").'</td>
						<td>'.($value['pembayaran_detail_tagihan'] ? number_format($value['pembayaran_detail_tagihan'],2,',','.')  : "").'</td>
						<td>'.($value['pembayaran_detail_retur'] ? number_format($value['pembayaran_detail_retur'],2,',','.')  : "").'</td>
						<td>'.($value['pembayaran_detail_bayar'] ? number_format($value['pembayaran_detail_bayar'],2,',','.')  : "-").'</td>
						<td>'.(number_format(($value['pembayaran_detail_tagihan']-$value['pembayaran_detail_retur']-$value['pembayaran_detail_bayar']),2,',','.')).'</td>
					</tr>';
					$totalJml += $value['pembayaran_detail_bayar'];
					// $totalQty += $value['pembayaran_detail_qty'];
				}
				

				$html .='<tr>
					<td colspan="4" class="total">Total</td>
					<td class="total">'.number_format($totalJml,2,',','.').'</td>
					<td></td>
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
				'file_name'     => 'FAKTUR MUTASI BARANG',
				'title'         => 'FAKTUR MUTASI BARANG',
				'stylesheet'    => './assets/laporan/print.css',
				'margin'        => '10 5 10 5',
				// 'font_face'     => 'cour',
				'font_size'     => '10',
				'json'          => true,
			));  
		}
		
	}
	public function print_nota($value=''){		
		$data = $this->db->where('pembayaran_id', $value)
						->get('v_pos_pembayaran')
						->row_array();
		$detail = $this->db->where('pembayaran_detail_parent', $value)
						   ->get('v_pos_pembayaran_detail')
						   ->result_array();
		$jurnal = $this->db->select('akun_kode, akun_nama, jurnal_umum_nobukti, jurnal_umum_detail_debit, jurnal_umum_detail_kredit')
						   ->where('jurnal_umum_reference_id', $value)
						   ->order_by('jurnal_umum_detail_no', 'ASC')
						   ->get('v_ak_jurnal_umum_detail_laporan')
						   ->result_array();
		$hari = date('D', strtotime($data['pembayaran_tanggal']));
		$namahari = array(
		    'Sun' => 'Minggu',
		    'Mon' => 'Senin',
		    'Tue' => 'Selasa',
		    'Wed' => 'Rabu',
		    'Thu' => 'Kamis',
		    'Fri' => 'Jumat',
		    'Sat' => 'Sabtu'
		);
		$tgl = phpChgDate(date('Y-m-d', strtotime($data['pembayaran_tanggal'])));
		$harini = date('d F Y');
		$huruf = $this->terbilang($data['pembayaran_bayar']); 

 		$header ='
			<div style="width:89.3%; border:1px solid #000;padding:5px;margin-right:50px;margin-left:17px">
				<table cellpadding="0" cellspacing="0" align="left"  border="0" class="" style="display:inline-table;border:1px solid black;width:9.4cm!important" rotate="-90.0deg">
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

			<div style="margin-left:96px; margin-top:-362px;">
			  <table cellspacing="0" style="width:91%;border:1px solid black; line-height:16px">
			  	<tr>
			  		<td style="width:25%;padding:3px;font-size:11px;border-right:1px solid black">No. BKK : '.explode('.', $jurnal[0]['jurnal_umum_nobukti'])[1].' </td>
			  		<td style="width:40%;padding:3px;font-size:11px;border-right:1px solid black;text-align:center"><b>BUKTI KAS KELUAR</b></td>
			  		<td style="width:35%;padding:3px;font-size:11px;">BKK</td>
			  	</tr>
			  </table>
			  <table cellspacing="0" style="width:91%" cellpadding="4">
			  	<tr>
			  		<td style="padding-top:11px;width:20%;font-size:11px;border-left:1px solid black;">Dibayarkan kepada</td>
			  		<td style="padding-top:11px;width:3%;font-size:11px;">:</td>
			  		<td style="padding-top:11px;width:77%;font-size:11px;" colspan="2">'.$data['supplier_nama'].'</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Banyaknya Uang</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:77%;font-size:11px;" colspan="2">'.$huruf.' Rupiah</td>
			  	</tr>
			  	<tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Untuk Pembayaran</td>
			  		<td style="width:3%;font-size:11px;">:</td>
			  		<td style="width:47%;font-size:11px;" colspan="2"> Pembayaran barang dagangan No. Faktur :</td></tr>';
			  	foreach ($detail as $key => $value) {
			  		// if($key==0){
				  	// 	$header .= $value['pembayaran_detail_pembelian_kode'].', <i style="float:right">JT.'.date('d/m/Y',strtotime($value['pembayaran_detail_jatuh_tempo'])).'</i></td>';				  		
			  		// }else{
			  		// }
		  				// <td style="border-left:1px solid black;" colspan="2"></td>
		  			$header .= '<tr>
	  					<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;">'.$value['pembelian_kode'].' (<i style="float:right;font-size:11px;">JT.'.date('d/m/Y',strtotime($value['pembayaran_detail_jatuh_tempo'])).'</i>) &nbsp; : '.number_format($value['pembayaran_detail_tagihan']).'</td>
		  				<td style="font-size:11px; width:21%">'.($value['pembayaran_detail_potongan']?'( Potongan ) : '.number_format($value['pembayaran_detail_potongan']):'').'</td> 
		  				<td style="font-size:11px; width:20%">'.($value['pembayaran_detail_retur']?'( Retur ) : '.number_format($value['pembayaran_detail_retur']):'').'</td>
		
		  				</tr>';
			  		// if($value['pembayaran_detail_potongan'] || $value['pembayaran_detail_retur']){
			  		// 	$pot = $value['pembayaran_detail_potongan']+$value['pembayaran_detail_retur'];
			  		// 	$header .= '<tr>
			  		// 		<td style="border-left:1px solid black;" colspan="2"></td>
			  		// 		<td style="font-size:11px;text-align:right">Potongan</td>
			  		// 		<td style="font-size:11px;">: ('.number_format($pot).')</td>
			  		// 	</tr>';	
			  		// }

			  	}
				if($data['akun_parent'] == '1112'){
		  			$header .= '<tr>
		  				<td style="border-left:1px solid black;" colspan="2"></td>
		  				<td style="font-size:11px;border-right:">Transfer '.$data['akun_nama'].', No. Ref.'.($data['pembayaran_referensi']?$data['pembayaran_referensi']:'-').'</td>
		  				<td style="font-size:11px;"></td>
		  			</tr>';					
				}
	  			$header .= '<tr>
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;">'.$data['pembayaran_keterangan'].'</td>
	  			</tr>';			
	  			$header .= '<tr >
	  				<td colspan="4" style="border-left:1px solid black;font-size:11px;"></td>
	  			</tr>';			


			  	$header .='</table>
			  <table cellspacing="0" style="width:92%" cellpadding="4">
			  <tr>
			  		<td style="width:20%;font-size:11px;border-left:1px solid black;">Terbilang</td>
			  		<td style="width:5%;font-size:11px;">:</td>
			  		<td style="width:10%;font-size:11px;border:1px solid black;text-align:right">'.number_format($data['pembayaran_bayar'],0,'','.').'</td>
			  		<td style="width:35%;font-size:11px;"></td>
			  		<td style="width:30%;font-size:11px;"></td>
			  	</tr>
			  </table>';
		$shadow = '<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"> </td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				</table>';
		$footer = '		
				<table cellspacing="0" cellpadding="2" style="width:92%">
				  	<tr>
				  		<td style="width:10%;border-left:1px solid black;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:15%;"></td>
				  		<td style="width:10%;border-right:1px solid black;"></td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Malang, </td>
				  		<td colspan="2" style="width:30%;font-size:11px;border-top:1px solid black;text-align:left">'.$tgl.'</td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;font-size:11px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Analis</td>
				  		<td style="width:10%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Rek.</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Debet</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Kredit</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Mengetahui,</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;text-align:center">Dibayar</td>
				  		<td style="width:15%;font-size:11px;border-top:1px solid black;text-align:center">Diterima</td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">'.(isset($jurnal[0]['akun_kode'])?$jurnal[0]['akun_kode']:'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[0]['jurnal_umum_detail_debit'])?number_format($jurnal[0]['jurnal_umum_detail_debit']):'<span style="color:#fff"></span>').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[0]['jurnal_umum_detail_kredit'])?number_format($jurnal[0]['jurnal_umum_detail_kredit']):'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center">Kasir</td>
				  		<td style="width:15%;font-size:11px;text-align:center">oleh</td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">'.(isset($jurnal[1]['akun_kode'])?$jurnal[1]['akun_kode']:'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[1]['jurnal_umum_detail_debit'])?number_format($jurnal[1]['jurnal_umum_detail_debit']):'<span style="color:#fff">hello!</span>').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[1]['jurnal_umum_detail_kredit'])?number_format($jurnal[1]['jurnal_umum_detail_kredit']):'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">'.(isset($jurnal[2]['akun_kode'])?$jurnal[2]['akun_kode']:'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[2]['jurnal_umum_detail_debit'])?number_format($jurnal[2]['jurnal_umum_detail_debit']):'<span style="color:#fff">hello!</span>').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[2]['jurnal_umum_detail_kredit'])?number_format($jurnal[2]['jurnal_umum_detail_kredit']):'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;text-align:center"></td>
				  	</tr>
				  	<tr>
				  		<td style="width:10%;font-size:11px;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;"></td>
				  		<td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:left; padding-left:20px">'.(isset($jurnal[3]['akun_kode'])?$jurnal[3]['akun_kode']:'').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[3]['jurnal_umum_detail_debit'])?number_format($jurnal[3]['jurnal_umum_detail_debit']):'<span style="color:#fff">hello!</span>').'</td>
				  		<td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right; padding-right:20px">'.(isset($jurnal[3]['jurnal_umum_detail_kredit'])?number_format($jurnal[3]['jurnal_umum_detail_kredit']):'').'</td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;border-right:1px solid black;text-align:center"></td>
				  		<td style="width:15%;font-size:11px;border-bottom:1px solid black;text-align:center"></td>
				  	</tr>
				  </table>
				</div>
			';
		createPdf(array(
    		'data'          => $header.$shadow.$footer,
    		'json'          => true,
            'paper_size'    => 'A4',
            'file_name'     => 'Kwitansi',
            'title'         => 'Kwitansi',
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '5 5 0 5',
            'font_face'     => 'sans_fonts',
            'font_size'     => '10'
    	));
	}
	public function print_tanda_terima($value='')
	{
		$data = varPost();
		$pembayaran = $this->db->where('pembayaran_id', $data['pembayaran_id'])
						->get('v_pos_pembayaran')
						->row_array();
		// print_r($pembayaran);exit;
		$detail = $this->db->where('pembayaran_detail_parent', $data['pembayaran_id'])
						   ->get('v_pos_pembayaran_detail')
						   ->result_array();
		$html = '
		<style>
			*{
				font-size:10px;
			}
			h3{
				font-size:15px
			}
			h1{
				font-size:18px; 
			}

			
		</style>
		<div style="border:1px solid #000; padding: 5px">
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:10%"><img src="'.base_url('assets/base_image/eka_border.png').'" alt="Logo" width="100"></td>
					<td colspan="4" style="vertical-align:top;line-height:20px; padding:6px 4px; width:88%"><h3>KPRI EKO KAPTI</h3><p>Kantor Kementrian Agama Kab Malang</p><p>Jl. Kolonel Sugiono No. 39 Gadang-Malang, Telp.834 894</p></td>
				</tr>
				<tr>
					<td colspan="5" style="text-align:center;"></td>
				</tr>
				<hr style="margin-bottom:0;" border="2"><hr style="margin-top:2px">
				<tr>
					<td colspan="5" style="text-align:center;"><h1>TANDA TERIMA</h1></td>
				</tr>
				<tr>
					<td colspan="4" style="width:80%!important"></td>
					<td style="width:20%">Tangal. '.date('d/m/Y',strtotime($pembayaran['pembayaran_tanggal_invoice'])).'</td>
				</tr>
			</table>
			<table autosize="1" style="border:none; width:100%; overflow: wrap">
				<tr>
					<td style="width:20%!important" >Telah diterima dari </td>
					<td style="width:2%">:</td>
					<td style="width:75%!important" colspan="2">'.$pembayaran['supplier_nama'].'</td>
				</tr>
				<tr>
					<td >Berupa </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2">Invoice No. '.$pembayaran['pembayaran_invoice'].'</td>
				</tr>
				<tr>
					<td >Keperluan </td>
					<td>:</td>
					<td style="width:75%!important" colspan="2"> Pembayaran Faktur No. </td>';
			foreach ($detail as $key => $value) {
				if($key > 0){
					$html .= '<tr><td colspan="3"></td>';
				}
				$html .= '<td>'.$value['pembelian_faktur'].', JT. '.date('d/m/Y', strtotime($value['pembelian_jatuh_tempo'])).', Senilai Rp.'.number_format($value['pembayaran_detail_tagihan'],0,',','.').'</td></tr>';
			}
			$html .= '
				<tr>
					<td style="padding-bottom:50px">Keterangan </td>
					<td style="padding-bottom:50px">:</td>
					<td style="width:75%!important;padding-bottom:50px" colspan="2">'.$pembayaran['pembayaran_keterangan'].'</td>
				</tr>
				<tr>
					<td style="text-align:center;padding-bottom:50px">Pengirim</td>
					<td colspan="2"></td>
					<td style="text-align:center;padding-bottom:50px">Penerima</td>
				</tr>
				<tr>
					<td style="text-align:center">'.($pembayaran['pembayaran_sales']?$pembayaran['pembayaran_sales']:'-').'</td>
					<td colspan="2"></td>
					<td style="text-align:center">'.($pembayaran['pegawai_nama']?$pembayaran['pegawai_nama']:'-').'</td>
				</tr>
			</table>
		</div>';
		// echo $html;exit;

		createPdf(array(
    		'data'          => $html,
    		'json'          => true,
            'paper_size'    => 'A5-L',
            'file_name'     => 'Tanda Terima',
            'title'         => 'Tanda Terima',
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '5 5 0 5',
            'font_face'     => 'sans_fonts',
            'font_size'     => '10'
    	));
	}
	
}

/* End of file Pembayaran.php */
/* Location: ./application/modules/Pembayaran/controllers/Pembayaran.php */