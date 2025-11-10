<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anggota extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'anggota/AnggotaModel' 			=> 'anggota',
			'grupgaji/GrupgajiModel' 		=> 'grup_gaji',
			'anggota/KabupatenModel' 		=> 'kabupaten',
			'anggota/KecamatanModel' 		=> 'kecamatan',
			'anggota/KelurahanModel' 		=> 'kelurahan',
			'transaksisimpanan/TransaksiSimpananModel'				=> 'transaksisimpanan',
			'transaksisimpanan/TransaksiSimpananDetailModel'		=> 'transaksisimpanandetail',
			'api/MobileActivationModel' 							=> 'activation',
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(),'anggota','table', true)
		);
	}

	public function read($value='')
	{
		$this->response($this->anggota->read(varPost()));
	}

	public function select($value='')
	{
		$data= varPost();
		$data['anggota_is_aktif']='';
		$this->response($this->anggota->select(array('filters_static'=>$data)));
	}

	public function selectAll($value='')
	{
		$data= varPost();
		$this->response($this->anggota->select(array('filters_static'=>$data)));
	}

	public function isRedundant($value='')
	{
		$data= varPost();
		$kode = substr($data['anggota_kode'], 0,4);
		$anggota = $this->anggota->select(array('filters_static'=>array('LEFT (anggota_kode,4) LIKE "%'.$kode.'%"'=>null)));
		
		if($anggota['total']>0){
			$operation = array("success"=>false);
		}else{
			$operation = array("success"=>true);
		}
		$this->response($operation);
	}

	public function isRedundantEdit($value='')
	{
		$data= varPost();
		$kode = $data['anggota_kode'];
		$anggota = $this->anggota->select(array('filters_static'=>array('anggota_kode'=>$kode)));
		if($anggota['total']==1){
			if($anggota['data'][0]['anggota_nama']==$data['anggota_nama']){
				$operation = array("success"=>true);
			}else{
				$operation = array("success"=>false);
			}
		}else{
			$kode2 = substr($data['anggota_kode'], 0,4);
			$anggota2 = $this->anggota->select(array('filters_static'=>array('LEFT (anggota_kode,4) LIKE "%'.$kode2.'%"'=>null)));
			if($anggota2['data'][0]['anggota_nama']==$data['anggota_nama']){
				$operation = array("success"=>true);
			}else{
				$operation = array("success"=>false);
			}
		}
		/*if($anggota['total']==1){
			$operation = array("success"=>true);
		}else{
			$operation = array("success"=>false);
		}*/
		$this->response($operation);
	}

	public function store()
	{
		$data = varPost();
		$data['anggota_kode'] = $this->anggota->gen_kode($data['anggota_kelompok']);
		$data['anggota_create'] = date("Y-m-d h:m:s");
		$data['anggota_create_by'] = $this->session->userdata('user_id');
		$data['anggota_is_aktif'] = Y;
		$data['anggota_tagihan_simp_pokok'] = $data['anggota_simp_pokok'];
		$data['anggota_tagihan_simp_manasuka'] = $data['anggota_simp_manasuka'];
		$data['anggota_tagihan_simp_tabungan_hari_tua'] = $data['anggota_simp_tabungan_hari_tua'];
		$data['anggota_tagihan_simp_titipan_belanja'] = $data['anggota_simp_titipan_belanja'];
		$data['anggota_tagihan_simp_wajib'] = $data['anggota_simp_wajib'];
		$data['anggota_tagihan_simp_wajib_khusus'] = $data['anggota_simp_wajib_khusus'];
		$data['anggota_password'] = $this->hf->password($data['anggota_password']);
		$tgl = explode('-', $data['anggota_tgl_gabung']);
		$data['tahun'] = $tgl[0];
		if($tgl[2]>20){
			$data['anggota_tagihan_bulan_last'] = $data['tahun']."-0".(int)($tgl[1]+2);
		}else{
			$data['anggota_tagihan_bulan_last'] = $data['tahun']."-0".(int)($tgl[1]+1);
		}
		$data['anggota_jml_tunggakan'] = 1;
		$id = gen_uuid($this->anggota->get_table());
		$data['anggota_simp_pokok'] = 0;

		$this->activation->insert(uuid($this->activation->get_table()), [
			'activate_kodeanggota'=> $data['anggota_kode'],
			'activate_namaanggota'=> $data['anggota_nama'],
			'activate_pin'=>substr($data['anggota_kode'], 0,4).'00',
			'activate_fcmtoken'=> '',
			'activate_device'=>'device metadata',
			'activate_status'=>'0',
			'activate_timestamp'=>date("Y-m-d h:m:s"),
			'activate_anggota_id' => $id
		]);
		$this->response($this->anggota->insert($id, $data));

	}

	public function update($savemode = false)
    {
        $data = varPost();
	    $anggota = $this->anggota->read(['anggota_id' => $data['anggota_id']]);
        if($anggota['anggota_kode'] != $data['anggota_kode']){
        	$this->activation->update(['activate_anggota_id' => $data['anggota_id']],['activate_kodeanggota' =>$data['anggota_kode']]);
        }
        $operation = $this->anggota->update($data['anggota_id'], $data);
        $this->response($operation);
    }

    public function destroy($value='')
    {
        $data = varPost();
        $operation = $this->anggota->update($data['anggota_id'], array('anggota_is_aktif'=>"Y"));
        // $operation = $this->anggota->delete(varPost('id', varExist($data, $this->anggota->get_primary(true))));
        $this->response($operation);
    }

    public function kabupaten()
	{
		$data = varPost();
		$data['prov_nama'] = 'Jawa Timur';
		$operation = $this->kabupaten->select(array(
			'filters_static'=>array(
				'kab_nama like "%'.$data['q'].'%"' => null,
				'prov_nama' => $data['prov_nama']
			),
			'sort_static'=>'kab_nama'));
		$arr_data = array();
		foreach ($operation['data'] as $key => $value) {
			$arr_data[$key]['id'] = $value['kab_nama'];
			$arr_data[$key]['text'] = $value['kab_nama'];
		}
		echo json_encode($arr_data);
	}

	public function kecamatan()
	{
		$data = varPost();
		$operation = $this->kecamatan->select(array(
			'filters_static'=>array(
				'kec_nama like "%'.$data['search'].'%"' => null,
				'kab_nama' => $data['kab_nama']
			),
			'sort_static'=>'kab_nama'));
		$arr_data = array();
		foreach ($operation['data'] as $key => $value) {
			$arr_data[$key]['id'] = $value['kec_nama'];
			$arr_data[$key]['text'] = $value['kec_nama'];
		}
		echo json_encode($arr_data);
	}

	public function kelurahan()
	{
		$data = varPost();
		$operation = $this->kelurahan->select(array(
			'filters_static'=>array(
				'kel_nama like "%'.$data['search'].'%"' => null,
				'kab_nama' => $data['kab_nama'],
				'kec_nama' => $data['kec_nama'],
			),
			'sort_static'=>'kab_nama'));
		$arr_data = array();
		foreach ($operation['data'] as $key => $value) {
			$arr_data[$key]['id'] = $value['kel_nama'];
			$arr_data[$key]['text'] = $value['kel_nama'];
		}
		echo json_encode($arr_data);
	}

	public function preview($value='')
	{
		$data = varPost();
		if ($data['jenis_nasabah'] == 'Baru') {
			$anggota = $this->anggota->select(array(
				'filters_static' => array(
					'anggota_tgl_gabung between "'.$data['tanggal_awal'].'" and "'.$data['tanggal_akhir'].'"' => null
				)
			));
		} else {
			$anggota = $this->anggota->select(array(
				'filters_static' => array(
					'anggota_tgl_keluar between "'.$data['tanggal_awal'].'" and "'.$data['tanggal_akhir'].'"' => null
				)
			));
		}
		$header = $this->getHeader($data);
		$html   = '';
		$html  .= '<table cellspacing="0" cellpadding="0" style="width:100%;">';
		foreach ($anggota['data'] as $key => $value) {
			$masuk  = (($value['anggota_tgl_gabung'] == '')? '':date('d-m-Y', strtotime($value['anggota_tgl_gabung'])));
			$keluar = (($value['anggota_tgl_keluar'] == '0000-00-00')? '':date('d-m-Y', strtotime($value['anggota_tgl_keluar'])));
			if ($anggota['total'] == ($key+1)) {
				$html .= '
					<tr>
						<td class="f11 t-left v-top bb-1" style="width: 5%">'.($key+1).'</td>
						<td class="f11 t-center v-top bb-1" style="width: 10%;">'.$value['anggota_kode'].'</td>
						<td class="f11 t-center v-top bb-1" style="width: 5%;">'.$value['grup_gaji_kode'].'</td>
						<td class="f11 t-left v-top bb-1" style="width: 20%;">'.strtoupper($value['anggota_nama']).'</td>
						<td class="f11 t-center v-top bb-1" style="width: 5%;">'.(($value['anggota_jk'])=='1'?'L':'P').'</td>
						<td class="f11 t-left v-top bb-1" style="width: 20%;">'.strtoupper($value['anggota_alamat']).'</td>
						<td class="f11 t-left v-top bb-1" style="width: 15%;">'.$value['anggota_kota'].' / '.$value['anggota_kecamatan'].'</td>
						<td class="f11 t-center v-top bb-1 bl-1" style="width: 10%;">'.$masuk.'</td>
						<td class="f11 t-center v-top bb-1 bl-1" style="width: 10%;">'.$keluar.'</td>
					</tr>
				';
			} else {
				$html .= '
					<tr>
						<td class="f11 t-left v-top" style="width: 5%;">'.($key+1).'</td>
						<td class="f11 t-center v-top" style="width: 10%;">'.$value['anggota_kode'].'</td>
						<td class="f11 t-center v-top" style="width: 5%;">'.$value['grup_gaji_kode'].'</td>
						<td class="f11 t-left v-top" style="width: 20%;">'.strtoupper($value['anggota_nama']).'</td>
						<td class="f11 t-center v-top" style="width: 5%;">'.(($value['anggota_jk'])=='1'?'L':'P').'</td>
						<td class="f11 t-left v-top" style="width: 20%;">'.strtoupper($value['anggota_alamat']).'</td>
						<td class="f11 t-left v-top" style="width: 15%;">'.$value['anggota_kota'].' / '.$value['anggota_kecamatan'].'</td>
						<td class="f11 t-center v-top bl-1" style="width: 10%;">'.$masuk.'</td>
						<td class="f11 t-center v-top bl-1" style="width: 10%;">'.$keluar.'</td>
					</tr>
				';
			}
		}
		$html.='</table>';
		createPdf(array(
            'data'          => $html,
            'json'          => true,
            'paper_size'    => 'LEGAL',
            'file_name'     => 'Daftar Anggota'.$data['jenis_nasabah'],
            'title'         => 'Daftar Anggota'.$data['jenis_nasabah'],
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '42 10 10 10',
            'font_face'     => 'Verdana',
            'font_size'     => '10pt',
            'header'        => $header
        ));
	}

	public function print($value=''){
		$excel = new Excel();
		$excel->loadTemplate('./assets/template/template_anggota.xlsx');

		$data = varPost();
		if ($data['jenis_nasabah'] == 'Baru') {
			$anggota = $this->anggota->select(array(
				'filters_static' => array(
					'anggota_tgl_gabung between "'.$data['tanggal_awal'].'" and "'.$data['tanggal_akhir'].'"' => null
				)
			));
		} else {
			$anggota = $this->anggota->select(array(
				'filters_static' => array(
					'anggota_tgl_keluar between "'.$data['tanggal_awal'].'" and "'.$data['tanggal_akhir'].'"' => null
				)
			));
		}
		$excel->setDataCells([
			'cell'=>'A5',
			'value'=>strtoupper($data['jenis_nasabah']).' Per '.date("d/m/Y", strtotime($data['tanggal_awal'])).' - '.date("d/m/Y", strtotime($data['tanggal_akhir'])),
			'style'=> ['font-size'=>10]
		]);
		$numrow = 7;
		foreach ($anggota['data'] as $key => $value) {
			$masuk  = (($value['anggota_tgl_gabung'] == '')? '':date('d-m-Y', strtotime($value['anggota_tgl_gabung'])));
			$keluar = (($value['anggota_tgl_keluar'] == '0000-00-00')? '':date('d-m-Y', strtotime($value['anggota_tgl_keluar'])));
			// if ($anggota['total'] == ($key+1)) {
				$excel->setDataCells([
					[
						'cell'=> 'A'.$numrow,
						'value'=> ($key+1),
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'B'.$numrow,
						'value'=> $value['anggota_kode'],
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'C'.$numrow,
						'value'=> $value['grup_gaji_kode'],
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'D'.$numrow,
						'value'=> strtoupper($value['anggota_nama']),
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'E'.$numrow,
						'value'=> (($value['anggota_jk'])=='1'?'L':'P'),
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'F'.$numrow,
						'value'=> strtoupper($value['anggota_alamat']),
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'G'.$numrow,
						'value'=> $value['anggota_kota'].' / '.$value['anggota_kecamatan'],
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'H'.$numrow,
						'value'=> $masuk,
						'style'=> ['font-size'=>'10']
					],
					[
						'cell'=> 'I'.$numrow,
						'value'=> $keluar,
						'style'=> ['font-size'=>'10']
					],
				]);
			// } else {
			// 	$excel->setDataCells([
			// 		[
			// 			'cell'=> 'A'.$numrow,
			// 			'value'=> ($key+1),
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'B'.$numrow,
			// 			'value'=> $value['anggota_kode'],
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'C'.$numrow,
			// 			'value'=> $value['grup_gaji_kode'],
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'D'.$numrow,
			// 			'value'=> strtoupper($value['anggota_nama']),
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'E'.$numrow,
			// 			'value'=> (($value['anggota_jk'])=='1'?'L':'P'),
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'F'.$numrow,
			// 			'value'=> strtoupper($value['anggota_alamat']),
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'G'.$numrow,
			// 			'value'=> $value['anggota_kota'].' / '.$value['anggota_kecamatan'],
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'H'.$numrow,
			// 			'value'=> $masuk,
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 		[
			// 			'cell'=> 'I'.$numrow,
			// 			'value'=> $keluar,
			// 			'style'=> ['font-size'=>'10']
			// 		],
			// 	]);
			// }
			$numrow++;
		}

		$excel->setSheetTitle('Daftar Anggota '.$data['jenis_nasabah']);
		$excel->setPaperSize('A4');
		$excel->exportXlsx('Daftar Anggota '.$data['jenis_nasabah']);
	}

	public function getHeader($data)
	{
		$html  = '
			<table style="width: 100%; padding-top: 1cm;">
				<tr>
					<td class="t-left" colspan="2">KPRI "EKO KAPTI" <br><u>KPRI KANKEMENAG KAB.MALANG</u></td>
				</tr>
				<tr>
					<td class="t-center" colspan="2">DAFTAR DATA ANGGOTA</td>
				</tr>
				<tr>
					<td class="t-left">'
						.strtoupper($data['jenis_nasabah']).' Per '.date("d/m/Y", strtotime($data['tanggal_awal'])).' - '.date("d/m/Y", strtotime($data['tanggal_akhir'])).
					'</td>
					<td class="t-right">Hal. {PAGENO}</td>
				</tr>
		   </table>
	   ';
		$html .= '
			<table cellspacing="0" style="width:100%;">
				<tr>
					<td class="f11 t-center bt-3-double br-1 bb-1 bl-1" style="width: 5%; padding: 0.2cm 0;">No.</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 10%; padding: 0.2cm 0;">NKop.</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 5%; padding: 0.2cm 0;">Grp</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 20%; padding: 0.2cm 0;">NAMA</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 5%; padding: 0.2cm 0;">L/P</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 20%; padding: 0.2cm 0;">ALAMAT</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 15%; padding: 0.2cm 0;">KOTA / KEC.</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 10%; padding: 0.2cm 0;">Msk.</td>
					<td class="f11 t-center bt-3-double br-1 bb-1" style="width: 10%; padding: 0.2cm 0;">Klr.</td>
				</tr>
			</table>
		';

		return $html;
	}
}


/* End of file anggota.php */
/* Location: ./application/modules/anggota/controllers/anggota.php */
