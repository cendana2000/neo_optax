<?php defined('BASEPATH') or exit('No direct script access allowed');

class V2 extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(

    ));

    $this->dbref = $this->load->database(multidb_connect('pos_reference'), true);
  }

  public function index(){
    print_r('<pre>');print_r('hrerere');print_r('</pre>');exit;
  }

  public function change_v_pos_penjualan(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('view_definition')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      $pattern = "/pos_penjualan.penjualan_meja_id/i";
      if(!preg_match($pattern, $_change->view_definition)){
        $status = 'adding views';
        $change = $this->dbchange->query('-- public.v_pos_penjualan source

        CREATE OR REPLACE VIEW public.v_pos_penjualan
        AS SELECT pos_penjualan.penjualan_id,
            pos_penjualan.penjualan_tanggal,
            pos_penjualan.penjualan_kode,
            pos_penjualan.penjualan_total_qty,
            pos_penjualan.penjualan_total_item,
            pos_penjualan.penjualan_total_harga,
            pos_penjualan.penjualan_total_grand,
            pos_penjualan.penjualan_total_bayar,
            pos_penjualan.penjualan_total_bayar_tunai,
            pos_penjualan.penjualan_jenis_potongan,
            pos_penjualan.penjualan_total_potongan,
            pos_penjualan.penjualan_total_kembalian,
            pos_penjualan.penjualan_total_kredit,
            pos_penjualan.penjualan_total_retur,
            pos_penjualan.penjualan_user_id,
            pos_penjualan.penjualan_created,
            pos_penjualan.penjualan_metode,
            pos_penjualan.penjualan_user_nama,
            pos_penjualan.penjualan_keterangan,
            pos_penjualan.penjualan_kasir,
            pos_penjualan.penjualan_bank,
            pos_penjualan.penjualan_bank_ref,
            pos_penjualan.pos_penjualan_customer_id,
            pos_penjualan.penjualan_id AS detail_id,
            pos_penjualan.penjualan_total_potongan_persen,
            pos_penjualan.penjualan_total_cicilan,
            pos_penjualan.penjualan_total_cicilan_qty,
            pos_penjualan.penjualan_jatuh_tempo,
            pos_penjualan.penjualan_total_jasa,
            pos_penjualan.penjualan_jenis_barang,
            pos_penjualan.penjualan_total_jasa_nilai,
            pos_penjualan.penjualan_kredit_awal,
            pos_penjualan.penjualan_is_konsinyasi,
            pos_penjualan.penjualan_lock,
            pos_penjualan.penjualan_bayar_sisa,
            pos_penjualan.penjualan_jasa,
            pos_penjualan.penjualan_total_bayar_bank,
            pos_penjualan.penjualan_pajak_persen,
            pos_penjualan.penjualan_status_aktif,
            pos_penjualan.penjualan_platform,
            pos_customer.customer_nama,
            pb.barang_nama,
            pos_penjualan.penjualan_first_item,
            pb.barang_aktif,
            pm.meja_nama,
            pos_penjualan.penjualan_no_antrian,
            pos_customer.customer_kode,
            pos_penjualan.penjualan_meja_id
           FROM pos_penjualan
             LEFT JOIN pos_customer ON pos_penjualan.pos_penjualan_customer_id::text = pos_customer.customer_id::text
             LEFT JOIN pos_barang pb ON pb.barang_id::text = pos_penjualan.penjualan_first_item::text
             LEFT JOIN pos_meja pm ON pm.meja_id::text = pos_penjualan.penjualan_meja_id::text;');
             $_change = 'updated';
      }else{
        $status = 'already';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'v_pos_penjualan_detail' => [
          'column' => $_change,
          'status' => $status,
        ]
      ]]);
      // break;
    }
    $this->response([
      'success' => true,
      'message' => 'View updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function change_v_pos_penjualan2(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('view_definition')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan2\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      $pattern = "/pm.meja_nama/i";
      if(!preg_match($pattern, $_change->view_definition)){
        $status = 'adding views';
        $change = $this->dbchange->query('-- public.v_pos_penjualan2 source

        CREATE OR REPLACE VIEW public.v_pos_penjualan2
        AS SELECT pp.penjualan_id,
            pp.penjualan_kode,
            pp.penjualan_tanggal,
            pc.customer_nama,
            pc.customer_id,
            pp.penjualan_total_harga,
            pp.penjualan_total_grand,
            pp.penjualan_total_potongan,
            pp.pos_penjualan_customer_id,
            pp.penjualan_bank,
            pp.penjualan_total_potongan_persen,
            pp.penjualan_status_aktif,
            pp.penjualan_platform,
            pp.penjualan_meja_id,
            pm.meja_nama
           FROM pos_penjualan pp
             LEFT JOIN pos_customer pc ON pp.pos_penjualan_customer_id::text = pc.customer_id::text
             LEFT JOIN pos_meja pm ON pm.meja_id::text = pp.penjualan_meja_id::text;');
             $_change = 'updated';
      }else{
        $status = 'already';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'v_pos_penjualan_detail' => [
          'column' => $_change,
          'status' => $status,
        ]
      ]]);
      // break;
    }
    $this->response([
      'success' => true,
      'message' => 'View updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function change_column_length(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname != \'pos_oapi_dialoogi\' AND datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $change = $this->dbchange->select('column_name, character_maximum_length')
      ->where('table_name = \'pos_penjualan_detail\' and column_name=\'penjualan_detail_satuan_kode\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      $status = "already";
      if($change->character_maximum_length < 25){
        $_change = $this->dbchange->query('-- '.$val['datname'].' 
        DROP VIEW v_pos_penjualan_detail;

        ALTER TABLE public.pos_penjualan_detail ALTER COLUMN penjualan_detail_satuan_kode TYPE varchar(25) USING penjualan_detail_satuan_kode::varchar;
        
        CREATE OR REPLACE VIEW public.v_pos_penjualan_detail
        AS SELECT pos_penjualan_detail.penjualan_detail_id,
            pos_penjualan_detail.penjualan_detail_parent,
            pos_penjualan_detail.penjualan_detail_barang_id,
            pos_penjualan_detail.penjualan_detail_satuan_kode,
            pos_penjualan_detail.penjualan_detail_harga,
            pos_penjualan_detail.penjualan_detail_qty,
            pos_penjualan_detail.penjualan_detail_potongan,
            pos_penjualan_detail.penjualan_detail_potongan_persen,
            pos_penjualan_detail.penjualan_detail_subtotal,
            pos_penjualan_detail.penjualan_detail_order,
            pos_penjualan_detail.penjualan_detail_retur,
            pos_barang.barang_kode,
            pos_barang.barang_nama,
            pos_barang.barang_stok,
            pos_barang.barang_barcode,
            pos_barang.barang_thumbnail,
            pos_penjualan_detail.penjualan_detail_qty_barang,
            pos_barang.barang_satuan,
            pos_barang.barang_satuan_opt,
            pos_barang.barang_harga,
            pos_barang.barang_isi,
            pos_penjualan_detail.penjualan_detail_satuan,
            pos_penjualan_detail.penjualan_detail_tanggal,
            pos_penjualan_detail.penjualan_detail_harga_beli,
            pos_penjualan_detail.penjualan_detail_hpp,
            pos_penjualan_detail.penjualan_detail_qty -
                CASE
                    WHEN pos_penjualan_detail.penjualan_detail_retur IS NOT NULL THEN pos_penjualan_detail.penjualan_detail_retur::integer
                    ELSE 0
                END::numeric AS current_stok,
            pos_penjualan_detail.penjualan_detail_notes
           FROM pos_penjualan_detail
             LEFT JOIN pos_barang ON pos_penjualan_detail.penjualan_detail_barang_id::text = pos_barang.barang_id::text;');

        $status = "success";
      }
      $flag += 1;
      array_push($link, [$val['datname'] => [
        'value' => $change,
        'status' => $status
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'pos_penjualan_detail updated: '.$flag,
      'data' => $link,
    ]);
  }
}