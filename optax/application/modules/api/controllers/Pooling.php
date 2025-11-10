<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pooling extends Base_Controller {
    public function __construct()
	{
		parent::__construct();
		header("Access-Control-Allow-Headers: *");

		 $this->load->model(array(
			'api/PoolingModel' => 'poolingconfig',
			'api/PoolingSchemaModel' => 'poolingschema',
		)); 

	}

    public function store_pooling_config(){
        $data = varPost();

		$data_check  = $this->poolingconfig->read(['pajak_pooling_config_kode_store' => $data['pajak_pooling_config_kode_store']]);
		if($data_check){
			return $this->response([
				'success' => false,
				'message' => 'Ditemukan data duplikat',
				'data' => $data_check
			]);
		}

		$id_pooling_config = gen_uuid($this->poolingconfig->get_table());
		
        $insert = $this->poolingconfig->insert($id_pooling_config,$data);
        return $this->response($insert);
    }

	public function get_pooling_config(){
		$data = varGet();
		// $data = varPost();
		$edit_data = $this->poolingconfig->read(['pajak_pooling_config_id' => $data['id']]);
		if(!$edit_data){
			return $this->response([
				'success' => false,
				'message' => 'Data tidak ditemukan!'
			]);
		}

		return $this->response([
			'success' => true,
			'message' => 'Data ditemukan',
			'data' => $edit_data,
		]);
	}

	public function update_pooling_config(){
		$data = varPost();

		$update_data = $this->poolingconfig->read(['pajak_pooling_config_id' => $data['pajak_pooling_config_id']]);
		if(!$update_data){
			return $this->response([
				'success' => false,
				'message' => 'Data tidak ditemukan!'
			]);
		}

		$updated = $this->poolingconfig->update(['pajak_pooling_config_id' => $data['pajak_pooling_config_id']], $data);
		return $this->response([
			'success' => true,
			'message' => 'Data berhasil diupdate!',
			'data' => $updated
		]);
	}

	public function store_pooling_schema(){
		$data = varPost();

		if($data['pajak_pooling_config_id'] == null || empty($data['pajak_pooling_config_id'])){
			return $this->response([
				'success' => false,
				'message' => 'Data parent id diperlukan!'
			]);
		}

		$check_parent = $this->poolingconfig->read(['pajak_pooling_config_id' => $data['pajak_pooling_config_id']]);

		if(!$check_parent){
			return $this->response([
				'success' => false,
				'message' => 'Data parent tidak ditemukan!'
			]);
		}

		$schema_id = gen_uuid($this->poolingschema->get_table(), $data);

		$stored_data = $this->poolingschema->insert($schema_id, $data);

		return $this->response([
			'success' => true, 
			'message' => 'Data schema berhasil disimpan!',
			'data' => $stored_data,
		]);
	}
}