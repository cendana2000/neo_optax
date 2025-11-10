<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Akun extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'akun/AkunModel'             => 'akun',
            'jurnal/JurnalModel'         => 'jurnal',
            'jurnal/JurnalDetailModel'   => 'jurnaldetail',
		));
	}

    public function test_env(){
        print_r('<pre>');print_r($_ENV);print_r('</pre>');exit;
    }

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(),'akun','table', false, ['akun_active' => '1'])
		);
	}

    public function select_bank($value='')
    {
        $data = varPost();
        $akun = $this->akun->select(array('filters_static' => $data,'sort_static'=>'akun_nama, akun_kode asc'));
        $this->response($akun);
    }
    
    public function akun_pembayaran()
    {
        $data = varPost();
        $data['akun_is_pembayaran'] = '1';
        $this->response($this->akun->select(array('filters_static'=>$data, 'sort_static'=>'akun_kode')));
    }

	function read($value='')
	{
		$this->response($this->akun->read(varPost()));
	}

    public function selectBank()
    {
        $data = varPost();
        $akun = $this->akun->select(array('filters_static' => $data,'sort_static'=>'akun_nama, akun_kode asc'));
        $this->response($akun);
    }

    public function select_ajax($value='')
    {
        $data = varPost();
        $where = '';
        if($data['fdata']['akun_unit']) $where = ' AND akun_unit ="'.$data['fdata']['akun_unit'].'"';
        $data['page'] = isset($data['page'])?((intval($data['page'])-1)*intval($data['limit'])).',':'';
        $total = $this->db->query('SELECT count(akun_id) total FROM ak_akun WHERE concat(akun_kode, akun_nama) like "%'.$data['q'].'%" AND akun_tipe = "detail"'.$where.' AND akun_active="1" ORDER BY akun_kode')->result_array();

        $return = $this->db->query('SELECT akun_id as id, concat(akun_kode, " - ", akun_nama) as text FROM ak_akun WHERE concat(akun_kode, akun_nama) like "%'.$data['q'].'%" AND akun_tipe = "detail" '.$where.' AND akun_active="1" ORDER BY akun_kode LIMIT '.$data['page'].$data['limit'])->result_array();
        $this->response(array('items'=>$return, 'total_count'=>$total[0]['total']));
    }

	public function store()
	{
		$data = varPost();
        $data['akun_active'] = 1;
        if($data['akun_parent'] !== '1112'){
            $data['akun_is_bank'] = $data['akun_bank_jenis_id'] = $data['akun_bank_rekening'] = null;
        }else{
            $data['akun_is_bank'] = 1;
        }
        $data['akun_golongan'] = $this->get_golongan($data['akun_parent'], '#');
        if(!isset($data['akun_parent']) || (!$data['akun_parent'])) $data['akun_parent'] = '#';
        $data['akun_key'] = str_replace(' ', '_', strtolower(varPost('akun_nama')));		
		$operation = $this->akun->insert(gen_uuid($this->akun->get_table()), $data);
        $this->response($operation);
	}

	public function update()
	{
		$data = varPost();
        $data['akun_is_pembayaran'] = $data['akun_is_pembayaran'] || null;
        if($data['akun_parent'] !== '1112'){
            $data['akun_is_bank'] = $data['akun_bank_jenis_id'] = $data['akun_bank_rekening'] = null;
        }else{
            $data['akun_is_bank'] = 1;
        }
        $data['akun_golongan'] = $this->get_golongan($data['akun_parent'], '#');
		if($savemode === true){
			$operation = $this->akun->insert_update(varPost('id', varExist($data, $this->akun->get_primary(true))), $data);
		}else{
			$operation = $this->akun->update(varPost('id', varExist($data, $this->akun->get_primary(true))), $data);
		}
		$this->response($operation);
	}

    public function get_golongan($parent='', $golongan='')
    {
        $new_golongan = $golongan;
        if($parent !== '#'){
            $akun_parent = $this->akun->read(['akun_id' => $parent]);
            $new_golongan = $this->get_golongan($akun_parent['akun_parent'], $akun_parent['akun_id']);
        }
        return $new_golongan;
    }

	public function destroy()
	{
		$data = varPost();
		$operation = $this->akun->read(varPost('id'));
        if($operation['akun_lock'] == '1'){
            $this->response(['success' => false, 'message'=>'Maaf akun yang anda pilih tidak dapat dirubah/hapus dikarenakan keperluan otomatisasi jurnal.']);
        }else{
            $operation = $this->akun->update($data['id'], ['akun_active' => null]);
    		$this->response($operation);
        }
	}

    public function save_saldo($value='')
    {
        $data = varPost();
        // print_r($data);exit;
        if($data['total_saldo_debit'] > 0 && $data['total_saldo_kredit'] > 0){
            $debit = $kredit = $debit_uraian = $kredit_uraian = [];
            foreach ($data['saldo_akun_id'] as $key => $value) {
                if($data['saldo_kredit'][$key]>0){
                    $kredit[$key] = $data['saldo_kredit'][$key];
                    $kredit_uraian[$key] = 'Set saldo akun '.$value;                    
                }else{
                    $debit[$key] = $data['saldo_debit'][$key];
                    $debit_uraian[$key] = 'Set saldo akun '.$value;                    
                }
            }
            $trans = [
                'jurnal_umum_nobukti'           => 'SA-'.str_replace('-','',$data['saldo_periode']),
                'jurnal_umum_tanggal'           => $data['saldo_periode'],
                'jurnal_umum_penerima'          => 'Saldo Awal',
                'jurnal_umum_lawan_transaksi'   => 'Saldo Awal',
                'jurnal_umum_keterangan'        => 'Saldo Awal',
                'jurnal_umum_reference'         => 'saldo_awal',
                'jurnal_umum_unit'              => '1',
                'jurnal_umum_reference_id'      => '',
                'jurnal_umum_reference_kode'    => '',
            ];
            if($data['akun_id_saldo']){
                $trans['jurnal_umum_id'] = $data['akun_id_saldo'];
                $this->jurnal->edit_jurnal($debit, $kredit, $trans, $debit_uraian, $kredit_uraian);
            }else{
                $this->jurnal->add_jurnal($debit, $kredit, $trans, $debit_uraian, $kredit_uraian);
            }
            // exit;
            $this->response(['success' => true, 'message'=> 'Berhasil menyimpan data!.']);
        }else{
            $this->response(['success' => false, 'message' => 'Silahkan isi saldo terlebih dahulu!']);
        }
    }

	public function select_tree($parent = '#', $company = null){
		if (isset($_GET['id'])) {
			$parent = $_GET['id'];
		}	
        $query = $this->db->query('SELECT a.akun_id as id, a.akun_parent as parent, CONCAT(a.akun_kode, " - ", a.akun_nama) as text, a.akun_tipe as children, a.akun_key, a.akun_saldo, ac.nakun as nchild FROM ak_akun a LEFT JOIN (SELECT count(akun_id) as nakun, akun_parent FROM ak_akun GROUP BY akun_parent) ac ON ac.akun_parent = a.akun_id WHERE a.akun_parent = "'.$parent.'" AND akun_active = 1 ORDER BY akun_kode ASC;');
        $result = $query->result_array();
		
		foreach ($result as &$record){
			if($record['children'] == 'parent' && $record['nchild'] > 0){
				$record['children'] = true;
			}else{
				$record['children'] = false;
			}
            $record['data'] = [
                'saldo' => $record['akun_saldo'],
                'id' => $record['id'],
            ];
		}
		if (isset($_GET['id'])) {
			$this->response($result);
		}else{
			return $result;
		}	
	}

    public function go_tree($value='')
    {
        $akun = $this->akun->select(array(
            'filters_static'=>array(
                'akun_active'=>'1'
            ),'sort_static'=>'akun_kode asc'));
        $opr = $this->buildTree($akun['data']);
        $operation = array(
            'success'   => true,
            'data'      => $opr
        );
        $this->response($operation);
    }

	public function go_saldo($value='')
    {
        // $month = date('Y-m');
        // $akun = $this->db->query('SELECT * FROM ak_akun LEFT JOIN (SELECT saldo_akun_id,saldo_periode, saldo_debit_awal, saldo_kredit_awal,saldo_debit_akhir, saldo_kredit_akhir FROM ak_saldo_akun WHERE  saldo_periode <= "'.$month.'" ORDER BY saldo_periode desc) ak_saldo ON akun_id = ak_saldo.saldo_akun_id ORDER BY akun_kode')->result_array();
        $last_jurnal = $this->jurnal->select([
                'filters_static' => ['jurnal_umum_reference' => 'saldo_awal'],
                'sort_static'    => 'jurnal_umum_tanggal desc',
                'limit'          => '1',
            ])['data'];
        if(!isset($last_jurnal[0]['jurnal_umum_id'])) {
            $last_jurnal[0]['jurnal_umum_id'] = null;
            $last_jurnal[0]['jurnal_umum_tanggal'] = null;
        }
        $akun = $this->db->query('SELECT * FROM ak_akun LEFT JOIN (SELECT jurnal_umum_detail_id, jurnal_umum_detail_akun,  jurnal_umum_detail_debit, jurnal_umum_detail_kredit FROM ak_jurnal_umum_detail WHERE jurnal_umum_detail_jurnal_umum = "'.$last_jurnal[0]['jurnal_umum_id'].'") jurnal_detail ON akun_id = jurnal_detail.jurnal_umum_detail_akun ORDER BY akun_kode')->result_array();
        foreach ($akun as $key => &$value) {
            $value['akun_debit'] = $value['jurnal_umum_detail_debit'];
            $value['akun_kredit'] = $value['jurnal_umum_detail_kredit'];
            // if($value['saldo_periode'] == $month){
            // }else{
            //     $saldo = $value['saldo_debit_akhir']-$value['saldo_kredit_akhir'];
            //     $value['akun_debit'] = $value['akun_kredit'] = 0;
            //     if($saldo > 0){
            //         $value['akun_debit'] = $saldo;
            //     }else{
            //         $value['akun_kredit'] = abs($saldo);
            //     }
            // }
            // if($value['saldo_periode']) $new_month = $value['saldo_periode'];
        }
        // if(count($akun) > 0){
        //     $jurnal = $this->jurnal->read(['jurnal_umum_nobukti' => 'SA-'.str_replace('-','',$new_month)]);
        //     if(isset($jurnal['jurnal_umum_nobukti'])) $reference_id = $jurnal['jurnal_umum_nobukti'];
        // }
        $opr = $this->buildTree($akun);
        $operation = array(
            'success'       => true,
            'periode'       => ($last_jurnal[0]['jurnal_umum_tanggal'] ?? date('Y-m-d')),
            'reference_id'  => $last_jurnal[0]['jurnal_umum_id'],
            'data'          => $opr,
            'jurnal'        => $last_jurnal[0]
        );
        $this->response($operation);
    }

    function buildTree(array $elements, $parentId = '#') {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['akun_parent'] == $parentId) {
                $children = $this->buildTree($elements, $element['akun_id']);
                $element_new = array(
                    'id'            => $element['akun_id'],
                    'parent'        => $element['akun_parent'],
                    'akun_key'      => $element['akun_key'],
                    'akun_debit'    => ($element['akun_debit'] ?? 0),
                    'akun_kredit'   => ($element['akun_kredit'] ?? 0),
                    'text'          => $element['akun_kode'].' - '.$element['akun_nama'],
                    'tipe'          => $element['akun_tipe']
                );
                if ($children) {
                    $element_new['children'] = true;
                    $element_new['child'] = $children;
                }
                $branch[] = $element_new;
            }
        }
        return $branch;
    }

    public function go_tree_all($company = null, $level = '3'){
        $counter = 0;
        $elm = "";
        // $rendered=false, $mapped=, $untrack=false, $order=null
        $lv2 = $this->akun->find(array(
            'akun_parent' => '#',
        ),false,true,false,array(
            'akun_kode' => 'ASC'
        ));
            // 'akun_company' => $company
        if($level == 2){
            foreach ($lv2 as $rec_lv2) {
                if($counter == (count($lv2)-1)){
                    $elm .= $rec_lv2['akun_key'];
                }else{
                    $elm .= $rec_lv2['akun_key']."|";
                }
                $counter++;
            }
        }else{
            foreach ($lv2 as $rec_lv2) {
                $lv3 = $this->akun->find(array(
                    'akun_parent' => $rec_lv2['akun_id'],
                ),false,true,false,array(
                    'akun_kode' => 'ASC'
                ));

                foreach ($lv3 as $rec_lv3) {
                    $elm .= $rec_lv3['akun_key']."|";
                }

                $counter++;
            }
        }
        $opr = $this->select_tree_recursive($elm, $company);
        $operation = array(
            'success' => true,
            'data' => $opr
        );
        $this->response($operation);
    }

    public function select_tree_recursive($parent = '#', $company = null){  
        $records = array();
        $parent = urldecode($parent); 
        if(strpos($parent, '|') !== false){
            $parent_array = explode('|', $parent);
            foreach ($parent_array as $key => $val_parent) {
                $temp_records = $this->data_tree_by_key($val_parent);
                foreach ($temp_records as $key => &$val) {
                    if($val['children']){
                        $val['child'] = $this->select_tree_recursive($val['akun_key']);
                    }
                }
                $records = array_merge($records, $temp_records);
            }
        }else if($parent == 'level2'){
            $level2 = $this->akun->find(array(
                'akun_parent' => '#',
                'akun_active' => 1
            ),false, true, false, array(
                'akun_kode' => 'ASC'
            ));
            foreach ($level2 as $key => $val_parent){
                $temp_records = $this->akun->data_tree($val_parent['akun_id']);
                foreach ($temp_records as $key => &$val) {
                    if($val['children']){
                        $val['child'] = $this->select_tree_recursive($val['akun_key']);
                    }
                }
                $records = array_merge($records, $temp_records);
            }
        }else{
            if($parent == '#'){
                $records = $this->akun->data_tree($parent);
            }else{
                $records = $this->data_tree_by_key($parent);
            }

            foreach ($records as $key => &$val) {
                if($val['children']){
                    $val['child'] = $this->select_tree_recursive($val['akun_key']);
                }
            }
            // print_r($records);
        }
        return $records;
    }

    public function data_tree_by_key($parent = '#', $company = null){
		return $this->akun->data_tree($this->akun->get_akun_by_key($parent));
	}
}

/* End of file akun.php */
/* Location: ./application/modules/akun/controllers/akun.php */