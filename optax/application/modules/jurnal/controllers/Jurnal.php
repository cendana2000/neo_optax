<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
            'jurnal/JurnalModel'        => 'jurnal',
            'jurnal/JurnalDetailModel'  => 'jurnaldetail',
            'akun/AkunSaldoModel'       => 'akunsaldo',
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(),'jurnal','table', true, array('jurnal_umum_status != "deactive"'=> null, 'jurnal_umum_reference' => 'jurnal_umum'))
		);
	}

	public function read($value='')
	{
        $jurnal = $this->jurnal->read(varPost());
        $jurnal['detail'] = $this->jurnaldetail->select(['filters_static' => ['jurnal_umum_detail_jurnal_umum' =>varPost('jurnal_umum_id')], 'sort_static' => 'jurnal_umum_detail_no']);
		$this->response($jurnal);
	}

	public function select($value='')
	{
		$this->response($this->jurnal->select(array('filters_static'=>varPost())));
	}

    public function store($value='')
    {
        $data = varPost();
        $id = gen_uuid($this->jurnal->get_table());
        $userdata = $this->session->userdata('pegawai_id');
        if(!$data['jurnal_umum_nobukti']){
            $data['jurnal_umum_nobukti'] = $this->jurnal->generate_kode('BU', $data['jurnal_umum_tanggal']);
        }
        $data['jurnal_umum_status']         = 'active';
        $data['jurnal_umum_reference']      = 'jurnal_umum';
        $data['jurnal_umum_reference_id']   = $id;
        $data['jurnal_umum_reference_kode'] = $data['jurnal_umum_nobukti'];
        $data['jurnal_umum_create_at']      = date('Y-m-d H:m:s');
        $data['jurnal_umum_create_by']      = $userdata;

        $no_faktur =  $this->jurnal->read(array('jurnal_umum_nobukti' => $data['jurnal_umum_nobukti'], 'jurnal_umum_reference not like "%delete%"' => null));
        if($no_faktur){
            $operation = array(
                'success' => false,
                'message' => 'Data duplikat, No Faktur dengan no '.$data['jurnal_umum_nobukti'].' sudah terinput kedalam sistem',
                'record'  => $data
            );
            // print_r($no_faktur);exit;
            $this->response($operation);
        }else{
            $debit = $kredit = $debit_uraian = $kredit_uraian = [];
            foreach ($data['jurnal_umum_detail_akun'] as $key => $value) {
                if($data['jurnal_umum_detail_kredit'][$key]>0){
                    $kredit[$value][] = $data['jurnal_umum_detail_kredit'][$key];
                    $kredit_uraian[$value][] = $data['jurnal_umum_detail_uraian'][$key];                    
                }else{
                    $debit[$value][] = $data['jurnal_umum_detail_debit'][$key];
                    $debit_uraian[$value][] = $data['jurnal_umum_detail_uraian'][$key];                    
                }
            }

            if($this->jurnal->check_balance($debit, $kredit) == 'balance'){
                $jurnal = $this->jurnal->add_jurnal($debit, $kredit, $data, $debit_uraian, $kredit_uraian);
                $this->response($jurnal);
            }else{
                $this->response(['success' => false, 'message' => 'Nilai akun belum sesuai, silahkan lengkapi terlebih dahulu!.']);
            }
        }
    }

    public function update($value='')
    {
        $userdata = $this->session->userdata('data');
        $_POST['jurnal_umum_tanggal'] = date('Y-m-d', strtotime(varPost('jurnal_umum_tanggal')));
        $_POST['jurnal_umum_update_at'] = date('Y-m-d H:m:s');
        $_POST['jurnal_umum_update_by'] = $userdata['user_id'];
        $data = varPost();
        
        $data['jurnal_umum_status']         = 'active';
        $data['jurnal_umum_reference']      = 'jurnal_umum';
        $data['jurnal_umum_reference_id']   = $id;
        $data['jurnal_umum_reference_kode'] = $data['jurnal_umum_nobukti'];
        $data['jurnal_umum_create_at']      = date('Y-m-d H:m:s');
        $data['jurnal_umum_create_by']      = $userdata;

        $debit = $kredit = $debit_uraian = $kredit_uraian = [];
        foreach ($data['jurnal_umum_detail_akun'] as $key => $value) {
            if($data['jurnal_umum_detail_kredit'][$key]>0){
                $kredit[$value][] = $data['jurnal_umum_detail_kredit'][$key];
                $kredit_uraian[$value][] = $data['jurnal_umum_detail_uraian'][$key];                    
            }else{
                $debit[$value][] = $data['jurnal_umum_detail_debit'][$key];
                $debit_uraian[$value][] = $data['jurnal_umum_detail_uraian'][$key];                    
            }
        }
        // print_r($debit);print_r($kredit);exit;
        if($this->jurnal->check_balance($debit, $kredit) == 'balance'){
            $jurnal = $this->jurnal->edit_jurnal($debit, $kredit, $data, $debit_uraian, $kredit_uraian);
            $this->response($jurnal);
        }else{
            $this->response(['success' => false, 'message' => 'Nilai akun belum sesuai, silahkan lengkapi terlebih dahulu!.']);
        }
        
    }
	public function go_update($id, $data, $details){
		$me = $this;
        $opr_detail = array(
            "success" => true,
            "fail_data" => 0,
            "fail_record" => ""
        );

        $operation = $this->jurnal->update($id, $data, function($response) use ($me, $details,$opr_detail){
            $count_detail = 0;
            $data = $response['record'];
            $old_detail = $this->jurnaldetail->find(array(
                "jurnal_umum_detail_jurnal_umum" => $response['id']
            ));
            foreach ($old_detail as $old_record) {
                $me->fill_akun_balance($old_record['jurnal_umum_detail_akun'], date('m', strtotime($data['jurnal_umum_tanggal'])), date('Y', strtotime($data['jurnal_umum_tanggal'])), $this->reverse_type($old_record['jurnal_umum_detail_tipe']), $old_record['jurnal_umum_detail_total']);
            }
            $this->jurnaldetail->delete(array(
                "jurnal_umum_detail_jurnal_umum" => $response['id']
            ));
            foreach ($details as $records) {
                if($records){
                    $count_detail_fail = 0;
                    $record = json_decode(base64_decode($records),"[]");
                    
                    $record['jurnal_umum_detail_jurnal_umum'] = $response['id'];
                    $record['jurnal_umum_detail_lawan_transaksi'] = $record['jurnal_umum_detail_lawan_transaksi'];
                    $record['jurnal_umum_detail_tipe'] = "";
                    $record['jurnal_umum_detail_total'] = 0;
                    if($record['jurnal_umum_detail_debit'] <> 0){
                        $record['jurnal_umum_detail_tipe'] = "debit";
                        $record['jurnal_umum_detail_total'] = $record['jurnal_umum_detail_debit'];
                    }else{
                        $record['jurnal_umum_detail_tipe'] = "kredit";
                        $record['jurnal_umum_detail_total'] = $record['jurnal_umum_detail_kredit'];

                    }
                    $operation_detail = $this->jurnaldetail->insert(gen_uuid($this->jurnaldetail->get_table()),$record);
                    
                    // $me->fill_akun_balance($record['jurnal_umum_detail_akun'], date('m', strtotime($data['jurnal_umum_tanggal'])), date('Y', strtotime($data['jurnal_umum_tanggal'])), $record['jurnal_umum_detail_tipe'], $record['jurnal_umum_detail_total']);

                    if(!$operation_detail['success']){
                        $opr_detail["success"] = false;
                        $opr_detail["fail_data"] = ($count_detail + 1);
                        $opr_detail["fail_record"][$count_detail_fail] = $operation_detail;
                    }
                    $count_detail_fail++;
                    $count_detail++;
                }
            }
        });
        $operation['operation_detail'] = $opr_detail;
        return $operation;
	}

    public function destroy($value='')
    {
        $me = $this;
        $data = varPost();        
        $jurnal = $this->jurnal->read(array('jurnal_umum_id'=>$data['id']));
        $jurnal['jurnal_umum_reference'] = 'delete_';
        $jurnal['jurnal_umum_status'] = 'deactive';
        $operation = $this->jurnal->edit_jurnal([],[],$jurnal);
        if($operation['success']) $operation['message'] = 'Successfully deleted data';
        $this->response($operation);
    }

    public function destroy2($value='')
    {
        $me = $this;
        $data = varPost();

        // foreach ($data as $key => $value) {
        $jurnal = $this->jurnal->read(array('jurnal_umum_id'=>$data['id']));
        if (isset($jurnal['jurnal_umum_id']) && $jurnal['jurnal_umum_closed']) {
            $is_closed++;
        }else{
            $operation = $this->jurnal->update(varPost('id', varExist($data, $this->jurnal->get_primary(true))),array(
                'jurnal_umum_status' => 'deactive',
                'jurnal_umum_nobukti' => 'Deleted_'.$data['jurnal_umum_nobukti'],
                'jurnal_umum_delete_at' => date('Y-m-d H:i:s'),
                'jurnal_umum_delete_by' => $this->session->userdata('user_id')
            ), function($response) use ($me){
                $records = $response['record'];

                $data_detail = $me->jurnaldetail->find(array(
                    'jurnal_umum_detail_jurnal_umum' => $response['id']
                ));
                
                /*foreach ($data_detail as $record) {
                    $me->fill_akun_balance($record['jurnal_umum_detail_akun'], date('m', strtotime($records['jurnal_umum_tanggal'])), date('Y', strtotime($records['jurnal_umum_tanggal'])), $this->reverse_type($record['jurnal_umum_detail_tipe']), $record['jurnal_umum_detail_total']);
                }*/
            });
            if($operation['success']){
                $is_successs++;
            }
        }
        // }

        $message = "";
        if ($is_closed) {
            if ($is_closed == count($data)) {
                $message = "Gagal menghapus data. Data sudah masuk tutup buku.";
            }else{
                $message = "Terdapat data Gagal dihapus karena sudah masuk tutup buku.";
                $operation['success'] = false;
            }
        }else if ($is_successs>0) {
            $message = "Berhasil menghapus data.";
        }
        
        $operation['message'] = $message;
        $this->response($operation);
    }

    public function get_data_detail(){
        $id = varPost('jurnal_umum_id');
        $operation = $this->jurnaldetail->find(array(
            'jurnal_umum_detail_jurnal_umum' => $id
        ));
        $this->response($operation);
    }


    public function cetak2($value='')
    {
        if ($value) {
            $data = $this->db->where('jurnal_umum_id', $value)
                            ->get('ak_jurnal_umum')
                            ->row_array();
            $detail = $this->db->where('jurnal_umum_detail_jurnal_umum', $value)
                                ->order_by('jurnal_umum_detail_no', 'ASC')
                                ->get('v_ak_jurnal_umum_detail')
                                ->result_array();
            $html = '<style>
                *, table, p, li{
                    line-height:1.5;
                    font-size:9px;
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
                            <h4> JURNAL UMUM </h4><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="kop">
                            
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="kop">
                            
                    </td>
                </tr>
                <tr>
                    <td>Tanggal Transaksi : '.($data['jurnal_umum_tanggal'] ? date("d/m/Y",strtotime($data['jurnal_umum_tanggal']))  : "-").'</td>
                    <td>No. Faktur : '.($data['jurnal_umum_nobukti'] ? $data['jurnal_umum_nobukti'] : "-").'</td>
                </tr>
                <tr>
                    <td>Penerima : '.($data['jurnal_umum_penerima'] ? $data['jurnal_umum_penerima'] : "-").'</td>
                    <td>Keterangan : '.($data['jurnal_umum_keterangan'] ? $data['jurnal_umum_keterangan'] : "-").'</td>
                </tr>
            </table>
            <br>
            
            <table class="laporan" cellspacing=0 style="border:1px; width:100%; border-collapse: collapse;">
                <tr>
                    <th class="t-center">No.</th>
                    <th class="t-center">Kode</th>
                    <th class="t-center">Nama Akun</th>
                    <th class="t-center">Keterangan</th>
                    <th class="t-center">Debit</th>
                    <th class="t-center">Kredit</th>
                </tr>';

                
                foreach ($detail as $key => $value) {
                    $html .= '<tr>
                        <td>'.($key+1).'</td>
                        <td class="divider">'.($value['akun_kode'] ? $value['akun_kode'] : "-").'</td>
                        <td>'.($value['akun_nama'] ? $value['akun_nama'] : "-").'</td>
                        <td>'.($value['jurnal_umum_detail_uraian'] ? $value['jurnal_umum_detail_uraian'] : "").'</td>
                        <td>'.($value['jurnal_umum_detail_debit'] ? number_format($value['jurnal_umum_detail_debit'],2) : "").'</td>
                        <td>'.($value['jurnal_umum_detail_kredit'] ? number_format($value['jurnal_umum_detail_kredit'],2) : "").'</td>
                    </tr>';
                }
                

                $html .='<tr>
                    <td colspan="4" class="total">Total</td>
                    <td class="total">'.number_format($data['jurnal_umum_total_debit'],2).'</td>
                    <td class="total">'.number_format($data['jurnal_umum_total_kredit'],2).'</td>
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
                    <td class="bottom"> - </td>
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
                'file_name'     => 'Nota Jurnal Umum',
                'title'         => 'Nota Jurnal Umum',
                'stylesheet'    => './assets/laporan/print.css',
                'margin'        => '10 5 10 5',
                // 'font_face'     => 'cour',
                'font_size'     => '10',
                'json'          => true,
            ));  
        }
        
    }
    
    public function cetak($value){
        $data = varPost();
        $jurnal = $this->jurnal->read(array('jurnal_umum_id'=>$value));
        $tgl = phpChgDate(date('Y-m-d', strtotime($jurnal['jurnal_umum_tanggal'])));
        $huruf = $this->terbilang($jurnal['jurnal_umum_total']); 
        $jurnal_detail = $this->jurnaldetail->select(array('filters_static'=>array('jurnal_umum_detail_jurnal_umum'=>$jurnal['jurnal_umum_id']), 'sort_static' => 'jurnal_umum_detail_no'));

        // echo $jurnal['jurnal_umum_no_bukti'];exit;
        
        $header = '
            <div style="width:90%; border:1px solid #000;padding:5px">
                <table cellpadding="0" cellspacing="0" align="left"  border="0" class="" style="display:inline-table;border:1px solid black;width:9cm!important" rotate="-90.0deg">
                        <tr>
                          <td style="text-align:center; line-height:14px;padding:4px;" >
                            <p style="font-size:15px;font-weight:bold;">
                            KPRI "EKO KAPTI"
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

            <div style="margin-left:83px; margin-top:-347px;">
              <table cellspacing="0" style="width:89%;border:1px solid black; line-height:16px">
                <tr>
                    <td style="width:70%;font-size:11px;border-right:1px solid black; padding-left:10px">No. BU: '.explode('.',$jurnal['jurnal_umum_nobukti'])[1].' </td>
                    <td style="width:30%;font-size:11px;border-right:1px solid black" align="center"><b>BUKTI UMUM </b></td>
                </tr>
              </table>
              <table cellspacing="0" style="width:90%" cellpadding="4" style="vertical-align:top;border-left:1px solid black; padding-top:20px">
                <tr>
                    <td style="width:25%;font-size:11px;padding-left:20px ;"><b>Transaksi</b></td>
                    <td style="width:5%;font-size:11px; ">:</td>
                    <td style="width:70%;font-size:11px; height:122px;">'.(!empty($jurnal['jurnal_umum_keterangan']) ? $jurnal['jurnal_umum_keterangan'] : '................................................').'</td>
                </tr>

              </table>';
        $shadow ='<table cellspacing="0" cellpadding="2" style="width:90%">';
        for ($i=0; $i <6 ; $i++) { 
            $shadow.='<tr>
                        <td style="width:10%;border-left:1px solid black;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:15%;"></td>
                        <td style="width:10%;"></td>
                        <td style="width:15%;font-size:11px;text-align:center"> </td>
                        <td style="width:15%;font-size:11px;text-align:center"> </td>
                        <td style="width:15%;font-size:11px;text-align:center"></td>
                    </tr>';
        }
        $shadow.='</table>';
        $footer = '     
                <table cellspacing="0" cellpadding="2" style="width:90%">
                    <tr>
                        <td style="width:10%;font-size:11px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Analis</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Rek.</td>
                        <td style="width:15%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Debet</td>
                        <td style="width:10%;font-size:11px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;text-align:center">Kredit</td>
                        <td colspan="2" style="font-size:11px;border-top:1px solid black;text-align:center;border-bottom: 1px solid black;border-right:1px solid black;">Malang, '.$tgl.'</td>
                       
                    </tr>';
        $r = sizeof($jurnal_detail['data']);
        if($r<7){
            for ($i=0; $i <7 ; $i++) { 
                $value=$jurnal_detail['data'][$i];
                if(isset($jurnal_detail['data'][$i])){
                $footer.='<tr>
                        <td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;"></td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;padding-left:5px">'.$value['jurnal_umum_detail_akun_kode'].'</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right;padding-right:5px">'.number_format($value['jurnal_umum_detail_debit'], 0, '','.').'</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:right;padding-right:5px">'.number_format($value['jurnal_umum_detail_kredit'],0,'','.').'</td>';

                if($i == 0){
                    $footer .= '<td style="width:23%;font-size:11px;border-right:1px solid black;text-align:center">Mengetahui</td>
                        <td style="width:22%;font-size:11px;border-right:1px solid black;text-align:center">Dibuat Bag.Umum</td>';
                }else{
                    $footer .= '<td style="width:20%;font-size:11px;border-right:1px solid black;text-align:center">'.($i==1?"Ketua I":"").'</td>
                        <td style="width:20%;font-size:11px;border-right:1px solid black;text-align:center">'.($i==1?"Bendahara I":"").'</td>';
                }

                $footer .= '</tr>';
                }else{
                    $ketua = $this->db
                        ->select('pegawai_nama')
                        ->get_where('ms_pegawai', ['pegawai_jabatan' => 'Ketua I'])
                        ->row_array();
                    $bendahara = $this->db
                        ->select('pegawai_nama')
                        ->get_where('ms_pegawai', ['pegawai_jabatan' => 'Bendahara I'])
                        ->row_array();
                    $footer.='<tr>
                        '.($i==6?'<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">':'<td style="width:10%;font-size:11px;border-left:1px solid black;border-right:1px solid black;">').'&nbsp;</td>
                        <td style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        <td style="width:15%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black;text-align:center">&nbsp;</td>
                        '.($i==6?'<td align="center" style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black">':'<td style="width:10%;font-size:11px;border-right:1px solid black;">').''.($i==6 ? $ketua["pegawai_nama"] : "").'</td>
                        '.($i==6?'<td align="center" style="width:10%;font-size:11px;border-right:1px solid black;border-bottom:1px solid black">':'<td style="width:10%;font-size:11px;border-right:1px solid black;">').''.($i==6 ? $bendahara["pegawai_nama"] : "").'</td>
                    </tr>';
                }
            }
        }
        $footer.='</table>
                </div>';  
                // echo '<div>'.(!empty($jurnal['jurnal_umum_keterangan']) ? htmlspecialchars($jurnal['jurnal_umum_keterangan']) : '................................................').'</div>';exit;        
                // echo $header.$shadow.$footer;exit;     
        createPdf(array(
            'data'          => $header.$shadow.$footer,
            'json'          => true,
            // 'paper_size'    => array('85','240'),
            'paper_size'    => 'A4',
            // 'paper_size'    => array('85','240'),
            'file_name'     => 'Bukti Umum',
            'title'         => 'Bukti Umum',
            'stylesheet'    => './assets/laporan/print.css',
            'margin'        => '5 5 0 5',
            'font_face'     => 'sans_fonts',
            'font_size'     => '10'
        ));
    }
}

/* End of file jurnal.php */
/* Location: ./application/modules/jurnal/controllers/jurnal.php */