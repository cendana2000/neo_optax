<?php defined('BASEPATH') or exit('No direct script access allowed');

class V3Oapi extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(

    ));

    $this->dbref = $this->load->database(multidb_connect('pos_reference'), true);
  }

  public function index(){
    $gettoko = $this->db->query('SELECT toko_kode from pajak_toko')->result_array();
    $dbtoko = [];
    foreach($gettoko as $key => $val){
      array_push($dbtoko, $_ENV['PREFIX_DBPOS'].$val['toko_kode']);
    }
    $dbtoko = implode('\',\'', $dbtoko);
    // print_r('<pre>');print_r($dbtoko);print_r('</pre>');
    $dbs = $this->db->query("SELECT datname from pg_database where datname IN ('$dbtoko')")->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /* IS TABLE EXIST?
      $pos_pooling = $this->add_table_pos_pooling($this->dbchange);
      $pos_pooling_detail = $this->add_table_pos_pooling_detail($this->dbchange);

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_penjualan_pooling' => !$pos_pooling,
        'pos_penjualan_pooling_detail' => !$pos_pooling_detail,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'DB: '.$flag,
      'note' => 'false mean table is ready',
      'data' => $link,
    ]);
  }

  /**
   * =======================================================
   * function updates
   */
  function add_table_pos_pooling($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'pos_penjualan_pooling\'')
    // ->get('pos_penjualan_detail')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if(!$_change){
      $change = $db->query('-- public.pos_penjualan_pooling definition

      -- Drop table
      
      -- DROP TABLE pos_penjualan_pooling;
      
      CREATE TABLE pos_penjualan_pooling (
        penjualan_id varchar(32) NOT NULL,
        penjualan_tanggal date NULL,
        penjualan_kode varchar(20) NULL,
        penjualan_total_item varchar NULL,
        penjualan_total_qty numeric(10, 2) NULL,
        penjualan_sub_total float8 NULL,
        penjualan_total_nilai_pajak float8 NULL,
        penjualan_total_grand float8 NULL,
        penjualan_nama_customer varchar(150) NULL,
        penjualan_user_nama varchar(50) NULL,
        penjualan_jasa float4 NULL,
        penjualan_source varchar(10) NULL,
        CONSTRAINT pos_penjualan_pooling_pkey PRIMARY KEY (penjualan_id)
      );');
      $_change = 'updated';
    }
    return $_change;
  }

  function add_table_pos_pooling_detail($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'pos_penjualan_detail_pooling\'')
    // ->get('pos_penjualan_detail')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if(!$_change){
      $change = $db->query('-- public.pos_penjualan_detail_pooling definition

      -- Drop table
      
      -- DROP TABLE pos_penjualan_detail_pooling;
      
      CREATE TABLE pos_penjualan_detail_pooling (
        penjualan_detail_id varchar(32) NOT NULL,
        penjualan_detail_parent varchar(32) NULL,
        penjualan_detail_nama_barang varchar(150) NULL,
        penjualan_detail_qty int4 NULL,
        penjualan_detail_custom_menu text NULL,
        CONSTRAINT pos_penjualan_detail_pooling_pkey PRIMARY KEY (penjualan_detail_id)
      );');
      $_change = 'updated';
    }
    return $_change;
  }
}