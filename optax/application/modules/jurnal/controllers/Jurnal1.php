<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'jurnal/JurnalModel' => 'jurnal',
			'jurnal/JurnalDetailModel' => 'jurnaldetail'
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(),'jurnal','table', true, array('jurnal_umum_status != "deactive"'=> null))
		);
	}

	public function read($value='')
	{
		$this->response($this->jurnal->read(varPost()));
	}

	public function select($value='')
	{
		$this->response($this->jurnal->select(array('filters_static'=>varPost())));
	}

	public function store($value='', $import = false){
		$me = $this;
        $id = gen_uuid($this->jurnal->get_table());
        $data = varPost();
        // print_r($data);exit;
        $userdata = $this->session->userdata('user_nama');
        // $details = $data['data_rincian'];

        $opr_detail = array(
            "success"       => true,
            "fail_data"     => 0,
            "fail_record"   => ""
        );
        
        $data['jurnal_umum_nobukti'] = ($data['jurnal_umum_nobukti'] == '' || $data['jurnal_umum_nobukti'] == 'J-#') ? $this->jurnal->generate_kode() : $data['jurnal_umum_nobukti'];

        $data['jurnal_umum_tanggal'] = date('Y-m-d', strtotime($data['jurnal_umum_tanggal']));
        $data['jurnal_umum_status'] = 'active';
        $data['jurnal_umum_reference'] = 'jurnal_umum';
        $data['jurnal_umum_reference_id'] = $id;
        $data['jurnal_umum_reference_kode'] = $data['jurnal_umum_nobukti'];
        $data['jurnal_umum_create_at'] = date('Y-m-d H:m:s');
        $data['jurnal_umum_create_by'] = $userdata;

        $year = date('Y', strtotime($data['jurnal_umum_tanggal']));
       /* $closed = $this->TutupBuku->read(array(
            "DATE_FORMAT(tutup_buku_tanggal_awal,'%Y')" => $year,
            "tutup_buku_status" => 1
        ));

        if (count($closed)>0) {
            $operation = array(
                "message" => "Transaksi tidak bisa disimpan, tahun ".$year." sudah tutup buku",
                "record" => $data,
                "success" => false,
            );
        }else{}*/
            $no_faktur =  $this->jurnal->read(array('jurnal_umum_nobukti' => $data['jurnal_umum_nobukti']));
        
            if($no_faktur){
                $operation = array(
                    'success' => false,
                    'message' => 'Data duplikat, No Faktur dengan no '.$data['jurnal_umum_nobukti'].' sudah terinput kedalam sistem',
                    'record'  => $data
                );
            }else{
                $operation = $this->jurnal->insert($id, $data, function($response) use ($me, $data, $opr_detail){
                    $count_detail = 0;
                    // $data = $response['record'];
                    foreach ($data['jurnal_umum_detail_akun'] as $key => $record) {
                    	// $record = json_decode(base64_decode($key),"[]");
                    	/*echo $key;
                    	echo '<br>'.$record->jurnal_umum_detail_lawan_transaksi.'<br>';
                         print_r($record);exit;*/
                        if($record){
                            $count_detail_fail = 0;
                            $record['jurnal_umum_detail_jurnal_umum'] = $response['id'];
                            $record['jurnal_umum_detail_lawan_transaksi'] = $record['jurnal_umum_detail_lawan_transaksi'];
                            // $record['jurnal_umum_detail_tipe'] = "";
                            $record['jurnal_umum_detail_total'] = 0;
                            if($record['jurnal_umum_detail_debit'] <> 0){
                                $record['jurnal_umum_detail_tipe'] = "debit";
                                $record['jurnal_umum_detail_total'] = $record['jurnal_umum_detail_debit'];
                            }else{
                                $record['jurnal_umum_detail_tipe'] = "kredit";
                                $record['jurnal_umum_detail_total'] = $record['jurnal_umum_detail_kredit'];

                            }
                            $operation_detail = $this->jurnaldetail->insert(gen_uuid($this->jurnaldetail->get_table()),$record);
                            
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
            }
        $operation['operation_detail'] = $opr_detail;
        $this->response($operation);
	}

	public function update($savemode = false){
        $me = $this;
        $userdata = $this->session->userdata('data');
        // $details = json_decode(varPost('jurnal_umum_details'), '[]');
        // $details = json_decode($details,'[]');
        $details = varPost('data_rincian');

        $_POST['jurnal_umum_tanggal'] = date('Y-m-d', strtotime(varPost('jurnal_umum_tanggal')));
        $_POST['jurnal_umum_update_at'] = date('Y-m-d H:m:s');
        $_POST['jurnal_umum_update_by'] = $userdata['user_id'];
        $data = varPost();
        $operation = array();
        $no_faktur =  $this->jurnal->read(array('jurnal_umum_nobukti' => $data['jurnal_umum_nobukti']));
        
        if($no_faktur){
            if($no_faktur['jurnal_umum_id'] == $data['jurnal_umum_id']){
                $operation = $this->go_update(varPost('id', varExist($data, $this->jurnal->get_primary(true))), $data, $details);
            }else{
                $operation = array(
                    'success' => false,
                    'message' => 'Data duplikat, No Faktur dengan no '.$data['jurnal_umum_nobukti'].' sudah terinput kedalam sistem',
                    'record'  => varPost()
                );
            }
            
        }else{
            $operation = $this->go_update(varPost('id', varExist($data, $this->jurnal->get_primary(true))), $data, $details);
        }
        
        $this->response($operation);
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
                    
                    $me->fill_akun_balance($record['jurnal_umum_detail_akun'], date('m', strtotime($data['jurnal_umum_tanggal'])), date('Y', strtotime($data['jurnal_umum_tanggal'])), $record['jurnal_umum_detail_tipe'], $record['jurnal_umum_detail_total']);

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
    
}

/* End of file jurnal.php */
/* Location: ./application/modules/jurnal/controllers/jurnal.php */