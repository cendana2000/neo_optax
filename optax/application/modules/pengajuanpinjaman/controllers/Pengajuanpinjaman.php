<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuanpinjaman extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'PengajuanPinjamanModel' 					=> 'pengajuan',
			'pengaturankredit/PengaturanKreditModel'	=> 'pengaturankredit',
			'anggota/AnggotaModel' 						=> 'anggota',
			'kartupinjaman/KartupinjamanModel' 			=> 'kartupinjaman',
			'pengajuantalangan/Pengajuantalanganmodel' 	=> 'pengajuan_talangan',
		));
	}

	public function index()
	{	
		$bulan = date("Y-m");
		$var = varPost();

		$this->response(
			$this->select_dt($var,'pengajuan','table',true,array(
				'pengajuan_status in ("0","1")' =>null,
				'pengajuan_aktif' =>1,
				// 'pengajuan_tgl BETWEEN "'.$var['tanggal1'].'" AND "'.$var['tanggal2'].'" '=> null
			))
		);
	}

	public function index_pinjaman(){
		$data = varPost('filter');
		$this->response($this->select_dt(
			$data,'pengajuan','table_pengajuan',true,array(
				'pengajuan_status NOT IN ("0","1")' =>null,
				'pengajuan_anggota' => $data['anggota_id']
			)
		));
	}

	public function modal_pinjaman()
	{	
		$bulan = date("Y-m");
		$var = varPost();
		
		$this->response(
			$this->select_dt($var,'pengajuan','modal_pinjaman',true,array(
				'pengajuan_anggota' => $var['pengajuan_anggota'],
				'pengajuan_status' 	=> 2
			))
		);
	}

	public function select_pinjaman($value='')
	{
		$data= varPost();
		$pengajuan = $this->pengajuan->select(array('filters_static'=>
			array(
				'pengajuan_anggota'=>$data['pengajuan_anggota'],
				'pengajuan_status NOT IN ("0","1","3")'=>null,
				'pengajuan_jenis like "%'.$data['pengajuan_jenis'].'%"'=> null
		), 'sort_static'=>'pengajuan_tgl_realisasi desc'));
		$data = [];
		foreach ($pengajuan['data'] as $key => $value) {
			$data[] =['pengajuan_id'			=>$value['pengajuan_id'],
			'pengajuan_no_pinjam'	=>$value['pengajuan_no_pinjam'],
			'pengajuan_jumlah_pinjaman'	=> number_format($value['pengajuan_jumlah_pinjaman']),
			'pengajuan_status_keterangan'	=> $value['pengajuan_status_keterangan']
			];
		}
		
		$a = [
			'success'=>1,
			'total'=>sizeof($pengajuan),
			'data'=>$data
		];
		
		$this->response($a);
	}

	public function insert_detail_kartu($id)
	{
		// $kartu = $this->kartupinjaman->select(array('filters_static'=>array(
		// 	'kartu_pinjaman_tanggal' => "2020-10-01",
		// 	'kartu_pinjaman_referensi_id'	=> null,
		// 	 LENGTH('kartu_pinjaman_id')	=> 5,
		// )));
		$pengajuan = $this->pengajuan->read(array('pengajuan_id'=>$id));
		$r=1;
		$saldo_awal = $pengajuan['pengajuan_sisa_angsuran']+$pengajuan['pengajuan_pokok_bulanan'];
		$kartu=[];
		/*for($i=0; $i<$pengajuan['pengajuan_angsur_jumlah'];$i++){*/
		$saldo_akhir = $pengajuan['pengajuan_sisa_angsuran'];
		$kartu = array(
			// 'kartu_pinjaman_tanggal'	=> "2020-05-02",
			'kartu_pinjaman_anggota'	=> $pengajuan['pengajuan_anggota'],
			'kartu_pinjaman_saldo_awal'	=> $saldo_awal,
			'kartu_pinjaman_saldo_pinjam'	=> $pengajuan['pengajuan_jumlah_pinjaman'],
			'kartu_pinjaman_saldo_bayar'	=> $pengajuan['pengajuan_pokok_bulanan'],
			'kartu_pinjaman_saldo_bunga'	=> $pengajuan['pengajuan_jasa_bulanan'],
			'kartu_pinjaman_saldo_akhir'	=> $saldo_akhir,
			'kartu_pinjaman_transaksi_kode'	=> $pengajuan['pengajuan_no_pinjam'],
			'kartu_pinjaman_order'			=> 1,
			'kartu_pinjaman_jenis'			=> $pengajuan['pengajuan_jenis'],
			'kartu_pinjaman_bayar_ke'		=> $pengajuan['pengajuan_angsuran']-1,
			'kartu_pinjaman_tenor'			=> $pengajuan['pengajuan_tenor'],
			'kartu_pinjaman_referensi_id'	=> $id
		);
		$this->kartupinjaman->insert(gen_uuid($this->kartupinjaman->get_table()), $kartu);	
		$r++;
		/*}*/
		$this->kartupinjaman->delete($id);
	}

	/*public function update_kartu_(){
		$pengajuan_lama = $this->db->query('select * from pinjaman_ekokapti
			where DATE_FORMAT(id_tgl_pinjam, "%Y-%m")="2020-09" AND status_pinj="4"
			ORDER BY id_tgl_pinjam ASC')->result_array();
		foreach ($pengajuan_lama as $key=> $value) {
			$pengajuan = $this->db->query('update ksp_pengajuan_pinjaman
				set pengajuan_angsuran="'.$value['angsuran_ke'].'" where pengajuan_id="'.$value['id_pinjam'].'"');
		}
	}*/

	public function read($value='')
	{
		$this->response($this->pengajuan->read(varPost()));
	}

	public function select($value='')
	{
		$pengajuan = $this->pengajuan->select(array('filters_static'=>varPost()));
		$this->response($pengajuan);
	}

	public function selectAll($value='')
	{
		$data = varPost();
		$pengajuan = $this->pengajuan->select(array('filters_static'=>$data));

		$pengajuan_talangan = $this->pengajuan_talangan->select(array('filters_static'=>array('pengajuan_talangan_anggota'=>$data['pengajuan_anggota'], 'pengajuan_talangan_status'=>$data['pengajuan_status'])));

		$sisa = 0;
		foreach ($pengajuan['data'] as $key => $value) {
			$sisa+=$value['pengajuan_sisa_angsuran'];
		}
		foreach ($pengajuan_talangan['data'] as $key => $value) {
			$sisa+=$value['pengajuan_talangan_sisa_angsuran'];
		}
		$this->response($sisa);
	}

	public function select_pinjaman_edit($value='')
	{
		$data= varPost();
		if($data['pengajuan_id']){
			$pengajuan = $this->db->query('SELECT * from v_ksp_pengajuan_pinjaman where pengajuan_id!="'.$data['pengajuan_id'].'" and pengajuan_aktif=1 and pengajuan_status=2 and pengajuan_anggota="'.$data['pengajuan_anggota'].'"')->result_array();
		}else{
			$pengajuan = $this->db->query('SELECT * from v_ksp_pengajuan_pinjaman where pengajuan_aktif=1 and pengajuan_status=2 and pengajuan_anggota="'.$data['pengajuan_anggota'].'"')->result_array();
		}
		$this->response($pengajuan);
	}

	public function selectNasabahPengajuan($value='')
	{
		$this->response($this->pengajuan->select(array('filters_static'=>array('pengajuan_status in("0", "1")' => null,'pengajuan_aktif'=>1))));
	}

	public function select_kredit($value='')
	{
		$data= varPost();
		$jasa = $this->config->item('base_jasa_pinjaman');
		
		$proteksi = $this->db->query('SELECT * FROM ms_pengaturan_kredit where '.$data['jml_pinjaman'].' between pengaturan_kredit_batas_min and pengaturan_kredit_batas_maks and '.$data['tenor'].' between pengaturan_kredit_bulan_min and pengaturan_kredit_bulan_maks')->result_array();
		$this->response(array(
			'jasa'=>$jasa,
			'proteksi'=>$proteksi
		));
	}

	public function store()
	{
		$data = varPost();		

		$data['pengajuan_aktif'] = 1;
		$data['pengajuan_create_by'] = $this->session->userdata('pegawai_id');
		$data['pengajuan_create_at'] = date('Y-m-d H:m:s');
		$data['pengajuan_no'] = $this->pengajuan->gen_kode();
		$data['pengajuan_status'] = 1;
		$data['pengajuan_alamat'] = $data['anggota_alamat'];
		$data['pengajuan_tunggakan_jasa'] = $data['pengajuan_tunggakan_pokok']= 0;
		$id = gen_uuid($this->pengajuan->get_table());
		$this->anggota->update($data['pengajuan_anggota'], array(
			'anggota_tgl_lahir'		=> 	$data['anggota_tgl_lahir'],
			'anggota_nip'			=>  $data['anggota_nip'],
			'anggota_pekerjaan'		=>  $data['anggota_pekerjaan'],
			'anggota_tgl_pensiun'	=>  $data['anggota_tgl_pensiun'],
			'anggota_alamat'		=>  $data['anggota_alamat'],
			'anggota_telp'			=> 	$data['pengajuan_telp']
		));
		$this->response($this->pengajuan->insert($id, $data));
	}

	public function update()
	{
		$data = varPost();

		$data['pengajuan_tunggakan_jasa'] = $data['pengajuan_tunggakan_pokok']= 0;
		$this->anggota->update($data['pengajuan_anggota'], array(
			'anggota_tgl_lahir'		=> 	$data['anggota_tgl_lahir'],
			'anggota_nip'			=>  $data['anggota_nip'],
			'anggota_pekerjaan'		=>  $data['anggota_pekerjaan'],
			'anggota_tgl_pensiun'	=>  $data['anggota_tgl_pensiun'],
			'anggota_alamat'		=>  $data['anggota_alamat'],
			'anggota_telp'			=> 	$data['pengajuan_telp']
		));
		$this->response($this->pengajuan->update(varPost('id', varExist($data, $this->pengajuan->get_primary(true))), $data));
	}

	public function destroy()
	{
		$data = varPost();
		$operation=$this->pengajuan->update($data['id'], array('pengajuan_aktif'=>0));
		$this->response($operation);
	}

	public function print()
	{
		$data  = varPost();
		$data = $this->pengajuan->read(array('pengajuan_id'=>$data['pengajuan_id']));
		
		$html  = $this->getHeader();
		$html .= $this->getBody($data);
		$html .= $this->getFooter($data);
		createPdf(array(
            'data'          => $html,
            'json'          => true,
            'paper_size'    => 'LEGAL',
            'file_name'     => 'Pengajuan Kredit',
            'title'         => 'Pengajuan Kredit',
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '16 18 10 16',
            'font_face'     => 'Tahoma',
            'font_size'     => '11pt',
            'footer'        => '{PAGENO}/{nb}'
        ));
	}

	public function getHeader()
	{
		$html = '
			<table style="width: 18cm;">
				<tr>
					<td class="t-center bb-2-double" style="padding-bottom: 0.3cm;">
						<h4 class="b" style="margin-bottom:">
							<span>PERMOHONAN KREDIT UANG</span><br/>
							<span class="i">KPRI EKO KAPTI</span><br/>
							<span>KANTOR KEMENTERIAN AGAMA KABUPATEN MALANG</span>
						</h4>
						<span class="f16">
							JL. Kolonel Sugiono 39 Telp. (0341) 834894 Malang
						</span>
					</td>
				</tr>
			</table>
		';
		return $html;
	}
	public function getBody($data)
	{
		
		$gaji_bersih   = (($data['pengajuan_gaji_bersih'] == '' || $data['pengajuan_gaji_bersih'] ==0)? '':'Rp. '.number_format($data['pengajuan_gaji_bersih'],0,'','.'));
		$gaji_lain 	   = (($data['pengajuan_gaji_lainnya'] == '')? '':$data['pengajuan_gaji_lainnya']);
		$pinjam_total  = (($data['pengajuan_jumlah_pinjaman'] == '')? '':number_format($data['pengajuan_jumlah_pinjaman'],0,'','.'));
		$pinjam_sisa   = (($data['pengajuan_sisa_pinjaman_kpri'] == '')? '':number_format($data['pengajuan_sisa_pinjaman_kpri'],0,'','.'));
		$pinjam_lain   = (($data['pengajuan_sisa_pinjaman_lainnya'] == '')? '':number_format($data['pengajuan_sisa_pinjaman_lainnya'],0,'','.'));
		$jml_angsur2 = (int)($data['pengajuan_pokok_bulanan'])+(int)($data['pengajuan_jasa_bulanan']);

		$jml_angsur = (($jml_angsur2 == 0)? '':number_format($jml_angsur2,0,'','.'));
		$pinjam_angsur = (($data['pengajuan_pokok_bulanan'] == '')? '':number_format($data['pengajuan_pokok_bulanan'],0,'','.'));
		$jml_keluarga  = (($data['pengajuan_jml_tanggungan'] == '')?'&nbsp; &nbsp; &nbsp;':$data['pengajuan_jml_tanggungan']);
		$tgl_pensiun   = (($data['pengajuan_waktu_pensiun'] == '0000-00-00')? '-':$data['pengajuan_waktu_pensiun']);
		$html = '
			<table style="width: 18cm;">
				<tr>
					<td class="t-left" colspan="3">Diterima Pengurus :</td>
				</tr>
				<tr>
					<td class="t-left" style="width: 1.2cm;">Tgl</td>
					<td class="t-center" style="width: 0.3cm;">:</td>
					<td class="t-left">'.date('d-m-Y', strtotime($data['pengajuan_tgl'])).'</td>
				</tr>
				<tr>
					<td class="t-left" style="width: 1.5cm;">No</td>
					<td class="t-center" style="width: 0.3cm;">:</td>
					<td class="t-left">'.$data['pengajuan_no'].'</td>
				</tr>
				<tr>
					<td class="t-left" colspan="3" style="padding: 0.4cm 0;">Yang bertanda tangan di bawah ini saya :</td>
				</tr>
			</table>
			<table style="width: 18cm;">
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">1.</td>
					<td class="t-left" style="width: 4.5cm;">N a m a / N I P</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">'.$data['anggota_nama'].' / '.($data['anggota_nip']==""||$data['anggota_nip']==null?"-":$data['anggota_nip']).'</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">2.</td>
					<td class="t-left">Pekerjaan / Jabatan</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">'.($data['anggota_pekerjaan']==''?'-':$data['anggota_pekerjaan']).'</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">3.</td>
					<td class="t-left">Wilayah Gaji</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">'.$data['grup_gaji_kode'].' - '.$data['grup_gaji_nama'].'</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">4.</td>
					<td class="t-left">Tanggal lahir</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3">'.date('d-m-Y', strtotime($data['anggota_tgl_lahir'])).'</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">5.</td>
					<td class="t-left">Gaji bersih sebulan</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left">
						'.$gaji_bersih.'
					</td>
					<td class="t-left" style="width: 4.5cm;">Penghasilan lain-lain</td>
					<td class="t-left">
						Rp. '.number_format($gaji_lain,0,'','.').'
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">6.</td>
					<td class="t-left" colspan="4">Masih mempunyai sisa pinjaman pada KPRI Eko Kapti sebesar</td>
					<td class="t-left">
						Rp. '.$pinjam_sisa.'
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;"></td>
					<td class="t-left" colspan="4">Pada pihak lain sebesar</td>
					<td class="t-left">
						Rp. '.number_format($pinjam_lain,0,'','.').'
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">7.</td>
					<td class="t-left" colspan="3">Jumlah keluarga yang menjadi tanggungan</td>
					<td class="t-left" style="0.3cm;" colspan="2">
						: '.$jml_keluarga.' orang
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">8.</td>
					<td class="t-left" colspan="3">Masa pensiun berlaku mulai bulan</td>
					<td class="t-left" style="0.3cm;" colspan="2">
						: '.$tgl_pensiun.'
					</td>
				</tr>
				<tr>
					<td class="t-left" style="padding-bottom: 0.1cm;">9.</td>
					<td class="t-left">Tempat Tinggal</td>
					<td class="t-center" style="0.3cm;">:</td>
					<td class="t-left" colspan="3" style="padding-bottom: 0.1cm;">'.$data['anggota_alamat'].'</td>
				</tr>
				<tr>
					<td class="t-left" colspan="3" style="padding-bottom: 0.1cm;"></td>
					<td class="t-left" colspan="3">TELP. '.($data['pengajuan_telp']==null||$data['pengajuan_telp']==""?$data['anggota_telp']:$data['pengajuan_telp']).'</td>
				</tr>
			</table>
			<table style="width: 18cm;">
				<tr>
					<td class="t-justify" style="line-height: 1.6;">
						<p>&emsp;&emsp; Dengan ini mengajukan permohonan pinjam / kredit uang kepada Pengurus KPRI Eko Kapti Kankemenag Kab. Malang sebesar <b>Rp. 
							'.$pinjam_total.' ( <i>'.ucfirst(strtolower($this->terbilang($data['pengajuan_jumlah_pinjaman']))).' rupiah</i> ) </b>
							untuk keperluan : '.$data['pengajuan_keperluan_tunai'].'</p>
						<p>&emsp;&emsp; Selanjutnya saya sanggup mengangsur tiap-tiap bulan sebesar <b>Rp. '.$jml_angsur.' </b>ditambah jasa sesuai dengan ketentuan yang berlaku selama <b>: '.$data['pengajuan_tenor'].' </b>bulan.</p>
						<p>&emsp;&emsp; Untuk membayar angsuran setiap bulan, saya memberi kuasa kepada Bendaharawan Kankemenag Kab. Malang atau Petugas Pembayar Gaji untuk memotong gaji saya sebesar tagihan yang ditetapkan oleh Pengurus KPRI Eko Kapti sampai lunas.</p>
						<p>&emsp;&emsp; Demikian atas perhatiannya saya sampaikan terimakasih.</p>
					</td>
				</tr>
			</table>
		';
		return $html;
	}
	public function getFooter($data)
	{
		$html = '
			<table style="width: 18cm;">
				<tr>
					<td class="t-center" style="width: 30%;"></td>
					<td class="t-center" style="width: 40%;"></td>
					<td class="t-center" style="width: 30%;">Malang, '.phpChgDate(date('Y-m-d')).'</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="t-center">P E M O H O N</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="bb-1 t-center v-bottom" style="height: 2cm;">'.$data['anggota_nama'].'</td>
				</tr>
				<tr>
					<td class="t-center"></td>
					<td class="t-center"></td>
					<td class="t-left">NIP. '.$data['anggota_nip'].'</td>
				</tr>
				<tr>
					<td></td>
					<td class="t-center">
						MENGETAHUI / MENYETUJUI<br/>
						Pembayar Gaji Pegawai / Guru 
					</td>
					<td style="color: red">Diajukan pada '.phpChgDate(date("Y-m-d", strtotime($data['pengajuan_create_at']))).' <br> pukul '.date("H:i:s", strtotime($data['pengajuan_create_at'])).'</td>
				</tr>';
				if($data['pengajuan_verified_at']!=null){
					$html.='<tr>
						<td></td>
						<td class="t-center" style="padding-top: 15px; color: red">Telah disetujui pada <br> '.phpChgDate(date("Y-m-d", strtotime($data['pengajuan_verified_at']))).' <br> pukul '.date("H:i:s", strtotime($data['pengajuan_verified_at'])).'</td>
					</tr>
					<tr>
						<td></td>
						<td class="t-center" style="padding-top: 15px">'.$data['juru_bayar_nama'].'</td>
						<td class="t-center"></td>
					</tr>
					<tr>
						<td></td>
						<td class="bt-1">NIP. '.$data['juru_bayar_nip'].'</td>
						<td class="t-center"></td>
					</tr>';
				}else{
					$html.='
					<tr>
						<td></td>
						<td class="t-center" style="padding-top: 75px"></td>
						<td class="t-center"></td>
					</tr>
					<tr>
						<td></td>
						<td class="bt-1"></td>
						<td class="t-center"></td>
					</tr>';
				}
			$html.='</table>';
		/*print_r($data);
		exit();*/
		if($data['pengajuan_juru_bayar_id']!=NULL || $data['pengajuan_verified_at']!=NULL){
			$html.='<table style="margin-top: 20px">
					<tr>
						<td class="bb-1 bt-1 br-1 bl-1 t-center" style="color:red">Dokumen ini ditandatangani secara elektronik dengan menggunakan aplikasi mobile Anggota KPRI Ekokapti dan aplikasi mobile Juru Bayar KPRI Ekokapti yang diterbitkan oleh KPRI Ekokapti</td>
					</tr>
				</table>
			';
		}
			$html.='<table style="width: 18cm;margin-top: 20px">
				<tr>
					<td class="t-left b u" colspan="3">Catatan lain-lain</td>
				</tr>
				<tr><td>'.$data['pengajuan_keterangan'].'</td></tr>
			</table>';
				/*<tr>
					<td class="t-left" style="width: 58%;"></td>
					<td class="t-left t-justify" style="width: 42%;">
						Telah dibahas Tim Simpan Pinjam pada<br/>
						Tanggal _______________ dan setuju di beri<br/>
						pinjam sebesar Rp. '.$pinjam_total.'
					</td>
				</tr>
				<tr>
					<td class="t-right" colspan="3">A.n Tim Simpan Pinjam</td>
				</tr>*/
		return $html;
	}
}

/* End of file Pengajuanpinjaman.php */
/* Location: ./application/modules/pengajuanpinjaman/controllers/Pengajuanpinjaman.php */