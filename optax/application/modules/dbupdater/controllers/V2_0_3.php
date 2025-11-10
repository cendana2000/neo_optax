<?php defined('BASEPATH') or exit('No direct script access allowed');

class V2_0_3 extends Base_Controller
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

  public function add_table(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname NOT IN (\'pos_04e8e\', \'pos_oapi_1269c\', \'pos_oapi_dialoogi\', \'pos_oapi_template\') and  datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /* IS TABLE EXIST?
      $_change = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_custom_menu\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change){
        $change = $this->dbchange->query('-- public.pos_custom_menu definition

        -- Drop table
        
        -- DROP TABLE pos_custom_menu;
        
        CREATE TABLE pos_custom_menu (
          custom_menu_id varchar(32) NOT NULL,
          custom_menu_nama varchar(150) NULL,
          custom_menu_harga int4 NULL,
          custom_menu_created_at timestamp NULL,
          custom_menu_deleted_at timestamp NULL,
          custom_menu_updated_at timestamp NULL,
          CONSTRAINT pos_custom_menu_pk PRIMARY KEY (custom_menu_id)
        );');
        $_change = 'updated';
      }

      // /* IS TABLE EXIST?
      $_change_2 = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_barang_custom_menu\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change_2){
        $change_2 = $this->dbchange->query('-- public.pos_barang_custom_menu definition

        -- Drop table
        
        -- DROP TABLE pos_barang_custom_menu;
        
        CREATE TABLE pos_barang_custom_menu (
          barang_custom_menu_barang_id varchar(32) NULL,
          barang_custom_menu_custom_menu_id varchar(32) NULL
        );');
        $_change_2 = 'updated';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_custom_menu' => $_change,
        'pos_barang_custom_menu' => $_change_2,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'DB: '.$flag,
      'data' => $link,
    ]);
  }

  public function add_column(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname NOT IN (\'pos_04e8e\', \'pos_oapi_1269c\', \'pos_oapi_dialoogi\', \'pos_oapi_template\') and  datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('column_name')
      ->where('table_name = \'pos_penjualan_detail\' and column_name=\'penjualan_detail_custom_menu\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change){
        $change = $this->dbchange->query('ALTER TABLE public.pos_penjualan_detail ADD penjualan_detail_custom_menu text NULL;');
        $_change = 'updated';
      }

      $_change_2 = $this->dbchange->select('column_name')
      ->where('table_name = \'pos_barang\' and column_name=\'barang_custom_menu_id\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change_2){
        $change_2 = $this->dbchange->query('ALTER TABLE public.pos_barang ADD barang_custom_menu_id varchar(32) NULL;--'.$val['datname'].'');
        $_change_2 = 'updated';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'penjualan_detail_custom_menu' => $_change,
        'barang_custom_menu_id' => $_change_2,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'DB: '.$flag,
      'data' => $link,
    ]);
  }

  public function add_view(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname NOT IN (\'pos_04e8e\', \'pos_oapi_1269c\', \'pos_oapi_dialoogi\', \'pos_oapi_template\') and  datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan_detail\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if($_change){
        $status = 'adding new views';
        $change = $this->dbchange->query('-- public.v_pos_penjualan_detail source
        DROP VIEW public.v_pos_penjualan_detail;
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
            pos_penjualan_detail.penjualan_detail_notes,
            pos_penjualan_detail.penjualan_detail_custom_menu
           FROM pos_penjualan_detail
             LEFT JOIN pos_barang ON pos_penjualan_detail.penjualan_detail_barang_id::text = pos_barang.barang_id::text;');
        $_change = 'updated';
      }else{
        $status = 'ready';
      }

      $_change_2 = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_barang_custom\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if(!$_change_2){
        $status = 'adding new views';
        $change_2 = $this->dbchange->query('-- public.v_pos_barang_custom source

        CREATE OR REPLACE VIEW public.v_pos_barang_custom
        AS SELECT pbcm.barang_custom_menu_barang_id,
            pbcm.barang_custom_menu_custom_menu_id,
            pb.barang_id,
            pb.barang_kode,
            pb.barang_nama,
            pb.barang_stok,
            pb.barang_jenis_barang,
            pb.barang_kategori_barang,
            pb.barang_kategori_parent,
            pb.barang_satuan,
            pb.barang_satuan_kode,
            pb.barang_satuan_opt,
            pb.barang_satuan_opt_kode,
            pb.barang_stok_min,
            pb.barang_harga_beli,
            pb.barang_harga,
            pb.barang_disc,
            pb.barang_harga_opt,
            pb.barang_harga_pokok,
            pb.barang_updated,
            pb.barang_user,
            pb.barang_aktif,
            pb.barang_isi,
            pb.barang_barcode,
            pb.barang_persen_untung,
            pb.barang_supplier_id,
            pb.barang_is_konsinyasi,
            pb.barang_satuan_opt2_kode,
            pb.barang_harga_opt2,
            pb.barang_ppn,
            pb.barang_yearly,
            pb.barang_awal,
            pb.barang_awal_nilai,
            pb.barang_created_at,
            pb.barang_updated_at,
            pb.barang_deleted_at,
            pb.barang_thumbnail,
            pb.barang_image,
            pb.barang_custom_menu_id,
            pcm.custom_menu_id,
            pcm.custom_menu_nama,
            pcm.custom_menu_harga,
            pcm.custom_menu_created_at,
            pcm.custom_menu_deleted_at,
            pcm.custom_menu_updated_at
           FROM pos_barang_custom_menu pbcm
             JOIN pos_barang pb ON pbcm.barang_custom_menu_barang_id::text = pb.barang_id::text
             JOIN pos_custom_menu pcm ON pbcm.barang_custom_menu_custom_menu_id::text = pcm.custom_menu_id::text;');
        $_change_2 = 'updated';
      }else{
        $status_2 = 'ready';
      }
      
      $flag += 1;
      array_push($link, [$val['datname'] => [
        'v_pos_penjualan_detail' => [
          'column' => $_change,
          'status' => $status,
        ],
        'v_pos_barang_custom' => [
          'column' => $_change_2,
          'status' => $status_2,
        ],
      ]]);
      // break;
    }
    $this->response([
      'success' => true,
      'message' => 'DB: '.$flag,
      'data' => $link,
    ]);
  }

  public function update_menu(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname NOT IN (\'pos_04e8e\', \'pos_oapi_1269c\', \'pos_oapi_dialoogi\', \'pos_oapi_template\') and  datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      
      $change_pos_menu = $this->dbchange->select('count(*) as total')
      ->where('menu_kode = \'Custommenu-Table\'')
      ->get('pos_menu')->row();
      if($change_pos_menu->total == 0){
        $this->dbchange->query("INSERT INTO pos_menu (menu_id, menu_kode, menu_title, menu_order, menu_parent, menu_link, menu_isaktif, menu_level, menu_icon, menu_hassub, menu_main, menu_description) VALUES('4d4dd4c5d919e444d39d69a7a11dbctm', 'Custommenu-Table', 'Custom Menu', '01.10       ', 'f63143cc466006cf36cfa827b822c321', 'javascript:void(0)', 1, 2, 'fa fa-dot', 0, 1, 'Custom Menu');
        INSERT INTO pos_menu (menu_id, menu_kode, menu_title, menu_order, menu_parent, menu_link, menu_isaktif, menu_level, menu_icon, menu_hassub, menu_main, menu_description) VALUES('24fa5d33e6d0c64a7b2bad092383ectm', 'Custommenu-Read', 'Custom Menu Read', '01.10.01    ', '4d4dd4c5d919e444d39d69a7a11dbctm', 'javascript:void(0)', 1, 3, NULL, 0, 0, NULL);
        INSERT INTO pos_menu (menu_id, menu_kode, menu_title, menu_order, menu_parent, menu_link, menu_isaktif, menu_level, menu_icon, menu_hassub, menu_main, menu_description) VALUES('5e8367f27c3e40d91fc64a2f52b33ctm', 'Custommenu-Create', 'Custom Menu Create', '01.10.02    ', '4d4dd4c5d919e444d39d69a7a11dbctm', 'javascript:void(0)', 1, 3, NULL, 0, 0, NULL);
        INSERT INTO pos_menu (menu_id, menu_kode, menu_title, menu_order, menu_parent, menu_link, menu_isaktif, menu_level, menu_icon, menu_hassub, menu_main, menu_description) VALUES('1ea160938511e7e058b64e5e7fec2ctm', 'Custommenu-Update', 'Custom Menu Update', '01.10.03    ', '4d4dd4c5d919e444d39d69a7a11dbctm', 'javascript:void(0)', 1, 3, NULL, 0, 0, NULL);
        INSERT INTO pos_menu (menu_id, menu_kode, menu_title, menu_order, menu_parent, menu_link, menu_isaktif, menu_level, menu_icon, menu_hassub, menu_main, menu_description) VALUES('8988433bd2306ab45173181befb2actm', 'Custommenu-Delete', 'Custom Menu Delete', '01.10.04    ', '4d4dd4c5d919e444d39d69a7a11dbctm', 'javascript:void(0)', 1, 3, NULL, 0, 0, NULL);
        ");
      }

      $change_pos_menu_role = $this->dbchange->select('count(*) as total')
      ->where('menu_role_menu in (\'4d4dd4c5d919e444d39d69a7a11dbctm\',\'24fa5d33e6d0c64a7b2bad092383ectm\',\'5e8367f27c3e40d91fc64a2f52b33ctm\',\'1ea160938511e7e058b64e5e7fec2ctm\',\'8988433bd2306ab45173181befb2actm\')')
      ->get('pos_menu_role')->row();
      if($change_pos_menu_role->total == 0){
        $this->dbchange->query("INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
        ('63ce4044d16c06.58125366','24fa5d33e6d0c64a7b2bad092383ectm','123'),
        ('63ce4044d16c11.33279573','5e8367f27c3e40d91fc64a2f52b33ctm','123'),
        ('63ce4044d16c26.96817133','1ea160938511e7e058b64e5e7fec2ctm','123'),
        ('63ce4044d16c34.70700172','8988433bd2306ab45173181befb2actm','123'),
        ('63ce4044d16f39.88349500','4d4dd4c5d919e444d39d69a7a11dbctm','123');     
        ");
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_menu-Custommenu' => $change_pos_menu,
        'pos_menu_role' => $change_pos_menu_role,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'DB: '.$flag,
      'data' => $link,
    ]);
  }
}