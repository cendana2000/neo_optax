<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dbupdater extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(

    ));

    $this->dbref = $this->load->database(multidb_connect('pos_reference'), true);
  }

  public function index(){
    $this->response(array('message'=>'Hi, welcome'));
  }



  public function add_column_view(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('view_definition')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan_detail\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      $pattern = "/penjualan_detail_notes/i";
      if(!preg_match($pattern, $_change->view_definition)){
        $status = 'adding views';
        $change = $this->dbchange->query('-- public.v_pos_penjualan_detail source
        -- drop view public.v_pos_penjualan_detail;
        
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
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function add_column(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('column_name')
      ->where('table_name = \'pos_penjualan_detail\' and column_name=\'penjualan_detail_notes\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change){
        $change = $this->dbchange->query('ALTER TABLE public.pos_penjualan_detail ADD penjualan_detail_notes text NULL;');
        $_change = 'updated';
      }

      // /*
      $_change_penjualan_antrian = $this->dbchange->select('column_name')
      ->where('table_name = \'pos_penjualan\' and column_name=\'penjualan_no_antrian\'')
      // ->get('pos_penjualan')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change_penjualan_antrian){
        $change_penjualan_antrian = $this->dbchange->query('ALTER TABLE public.pos_penjualan ADD penjualan_no_antrian varchar(10) NULL;');
        $_change_penjualan_antrian = 'updated';
      }

      // /*
      $_change_penjualan_meja = $this->dbchange->select('column_name')
      ->where('table_name = \'pos_penjualan\' and column_name=\'penjualan_meja_id\'')
      // ->get('pos_penjualan')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change_penjualan_meja){
        $change_penjualan_meja = $this->dbchange->query('ALTER TABLE public.pos_penjualan ADD penjualan_meja_id varchar(32) NULL;');
        $_change_penjualan_meja = 'updated';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'penjualan_detail_notes' => $_change,
        'penjualan_no_antrian' => $_change_penjualan_antrian,
        'penjualan_meja_id' => $_change_penjualan_meja,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function add_table(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_menu_role_mobile\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      // */
      if(!$_change){
        $change = $this->dbchange->query('-- public.pos_menu_role_mobile definition

        -- Drop table
        
        -- DROP TABLE public.pos_menu_role_mobile;
        
        CREATE TABLE public.pos_menu_role_mobile (
          menu_role_id varchar(32) NOT NULL,
          menu_role_menu varchar(32) NULL,
          menu_role_role_access varchar(32) NULL,
          CONSTRAINT pos_menu_role_mobile_pkey PRIMARY KEY (menu_role_id)
        );');
        $_change = 'updated';
      }

      $_change_posmenu = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_menu_mobile\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      if(!$_change_posmenu){
        $change_posmenu = $this->dbchange->query('-- public.pos_menu_mobile definition

        -- Drop table
        
        -- DROP TABLE public.pos_menu_mobile;
        
        CREATE TABLE public.pos_menu_mobile (
          menu_id varchar(32) NOT NULL,
          menu_kode varchar(128) NULL,
          menu_title varchar(240) NULL,
          menu_order bpchar(12) NULL,
          menu_parent varchar(32) NULL,
          menu_link varchar(128) NULL,
          menu_isaktif int2 NULL,
          menu_level int2 NULL,
          menu_icon varchar(64) NULL,
          menu_hassub int2 NULL,
          menu_main int2 NULL,
          menu_description varchar(200) NULL,
          menu_type int2 NULL,
          CONSTRAINT pos_menu_mobile_pkey PRIMARY KEY (menu_id)
        );');
        $_change_posmenu = 'updated';
      }

      $_change_role_mobile = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_role_access_mobile\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      if(!$_change_role_mobile){
        $change_role_mobile = $this->dbchange->query('-- public.pos_role_access_mobile definition

        -- Drop table
        
        -- DROP TABLE public.pos_role_access_mobile;
        
        CREATE TABLE public.pos_role_access_mobile (
          role_access_id varchar(32) NOT NULL,
          role_access_kode varchar(12) NULL,
          role_access_nama varchar(64) NULL,
          role_access_status int2 NULL,
          role_access_keterangan text NULL,
          role_access_is_super int2 NULL,
          role_access_created_at timestamp(6) NULL,
          role_access_created_by varchar(32) NULL,
          role_access_updated_at timestamp(6) NULL,
          role_access_updated_by varchar(32) NULL,
          role_access_deleted_at timestamp(6) NULL,
          role_access_deleted_by varchar(32) NULL,
          CONSTRAINT pos_role_access_mobile_pkey PRIMARY KEY (role_access_id)
        );');
        $_change_role_mobile = 'updated';
      }

      $_change_meja = $this->dbchange->select('table_name')
      ->where('table_name = \'pos_meja\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.columns')->row();
      if(!$_change_meja){
        $_change_meja = $this->dbchange->query('-- public.pos_meja definition

        -- Drop table
        
        -- DROP TABLE public.pos_meja;
        
        CREATE TABLE public.pos_meja (
          meja_id varchar(32) NOT NULL,
          meja_kode varchar(32) NULL,
          meja_nama varchar(50) NULL,
          meja_keterangan varchar(115) NULL,
          meja_created_at timestamp NULL,
          meja_status bpchar(1) NULL,
          meja_deleted_at timestamp NULL,
          meja_updated_at timestamp NULL,
          CONSTRAINT pos_meja_pk PRIMARY KEY (meja_id)
        );');
        $_change_meja = 'updated';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_menu_role_mobile' => $_change,
        'pos_menu_mobile' => $_change_posmenu,
        'pos_role_access_mobile' => $_change_role_mobile,
        'pos_meja' => $_change_meja
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function add_view(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\' and datname != \'pos_app\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      // /*
      $_change = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_sys_menu_role_mobile\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if(!$_change){
        $status = 'adding new views';
        $change = $this->dbchange->query('-- public.v_sys_menu_role_mobile source

        CREATE OR REPLACE VIEW public.v_sys_menu_role_mobile
        AS SELECT pos_menu_role_mobile.menu_role_id,
            pos_menu_role_mobile.menu_role_menu,
            pos_menu_role_mobile.menu_role_role_access,
            pos_menu_mobile.menu_kode,
            pos_menu_mobile.menu_title,
            pos_menu_mobile.menu_order,
            pos_menu_mobile.menu_parent,
            pos_menu_mobile.menu_link,
            pos_menu_mobile.menu_isaktif,
            pos_menu_mobile.menu_icon,
            pos_menu_mobile.menu_level,
            pos_menu_mobile.menu_hassub,
            pos_menu_mobile.menu_main,
            pos_menu_mobile.menu_description,
            pos_user.user_id,
            pos_menu_mobile.menu_type
           FROM pos_menu_role_mobile
             LEFT JOIN pos_user ON pos_menu_role_mobile.menu_role_role_access::text = pos_user.user_role_access_id::text
             LEFT JOIN pos_menu_mobile ON pos_menu_role_mobile.menu_role_menu::text = pos_menu_mobile.menu_id::text;');
        $_change = 'updated';
      }else{
        $status = 'ready';
      }

      // /*
      $_change_pos_penjualan = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if(!$_change_pos_penjualan){
        $status_pos_penjualan = 'adding new views';
        $change_pos_penjualan = $this->dbchange->query('-- public.v_pos_penjualan source

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
            pos_customer.customer_kode
           FROM pos_penjualan
             LEFT JOIN pos_customer ON pos_penjualan.pos_penjualan_customer_id::text = pos_customer.customer_id::text
             LEFT JOIN pos_barang pb ON pb.barang_id::text = pos_penjualan.penjualan_first_item::text
             LEFT JOIN pos_meja pm ON pm.meja_id::text = pos_penjualan.penjualan_meja_id::text;');
        $_change_pos_penjualan = 'updated';
      }else{
        $status_pos_penjualan = 'ready';
      }

      // /*
      $_change_pos_penjualan2 = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan2\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if(!$_change_pos_penjualan2){
        $status_pos_penjualan2 = 'adding new views';
        $change_pos_penjualan2 = $this->dbchange->query('-- public.v_pos_penjualan2 source

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
            pp.penjualan_meja_id
           FROM pos_penjualan pp
             LEFT JOIN pos_customer pc ON pp.pos_penjualan_customer_id::text = pc.customer_id::text;');
        $_change_pos_penjualan2 = 'updated';
      }else{
        $status_pos_penjualan2 = 'ready';
      }

      // /*
      $_change_pos_penjualan_detail = $this->dbchange->select('table_name')
      ->where('table_schema NOT IN (\'pg_catalog\', \'information_schema\') and table_name !~ \'pg_\' and 
      table_name = \'v_pos_penjualan_detail\'')
      // ->get('pos_penjualan_detail')->result_array();
      ->get('information_schema.views')->row();
      // */
      
      if(!$_change_pos_penjualan_detail){
        $status_pos_penjualan_detail = 'adding new views';
        $change_pos_penjualan_detail = $this->dbchange->query('-- public.v_pos_penjualan_detail source

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
        $_change_pos_penjualan_detail = 'updated';
      }else{
        $status_pos_penjualan_detail = 'ready';
      }

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'v_sys_menu_role_mobile' => [
          'column' => $_change,
          'status' => $status,
        ],'v_pos_penjualan' => [
          'column' => $_change_pos_penjualan,
          'status' => $status_pos_penjualan,
        ],'v_pos_penjualan2' => [
          'column' => $_change_pos_penjualan2,
          'status' => $status_pos_penjualan2,
        ],'v_pos_penjualan_detail' => [
          'column' => $_change_pos_penjualan_detail,
          'status' => $status_pos_penjualan_detail,
        ],
      ]]);
      // break;
    }
    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function insert_table(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      $change = $this->dbchange->select('count(*) as total')
      // ->update('pos_menu', [
      //   'menu_link' => 'https://persada.malangkota.go.id/pos/index.php/kasir',
      // ]);
      ->get('pos_menu_mobile')->row();
      if($change->total == 0){
        $this->dbchange->query("INSERT INTO public.pos_menu_mobile (menu_id,menu_kode,menu_title,menu_order,menu_parent,menu_link,menu_isaktif,menu_level,menu_icon,menu_hassub,menu_main,menu_description,menu_type) VALUES
          ('f63143cc466006cf36cfa827b822c442','kasir','Kasir','00          ',NULL,'kasir.html',1,1,'fa fa-cash-register fa-w-16 fa-lg',0,1,'Kasir',0),
          ('f143cc4123126cf36cfa827b822c421','history-kasir','History Kasir','01          ',NULL,'history_kasir.html',1,1,'fa fa-history fa-w-16 fa-lg',0,1,'History',0),
          ('f1233c123126cf36cfa827b822c421','setup-printer','Setup Printer','02          ',NULL,'iniBtdevice()',1,1,'fab fa-bluetooth-b',0,1,'Setup Printer Bluetooth',1),
          ('f12h21123126cf36cfa827b822c421','customer-new','Customer','03          ',NULL,'customer.html',1,1,'fas fa-shopping-bag',0,1,'Add new customer',0),
          ('716221123126cf36cfa827b822c421','lapor-pajak','Lapor Pajak','04          ',NULL,'laporpajak.html',1,1,'fas fa-book',0,1,'Lapor Pajak',0);
        ");
      }

      $change_pos_menu = $this->dbchange->select('count(*) as total')
      ->where('menu_kode = \'Meja-Table\'')
      ->get('pos_menu')->row();
      // $this->dbchange->query("UPDATE public.pos_menu SET menu_isaktif=1 WHERE menu_id='3ecc31824dab3a1b8eede166acddf805';");
      // $this->dbchange->query("UPDATE public.pos_menu SET menu_kode='Pricelist-Report', menu_level=2 WHERE menu_id='a4520';
      //   UPDATE public.pos_menu SET menu_kode='Laporanpembelian-Report', menu_level=2 WHERE menu_id='a452';
      //   UPDATE public.pos_menu SET menu_kode='LaporanPenjualan-Report', menu_level=2 WHERE menu_id='a453';
      //   UPDATE public.pos_menu SET menu_kode='Laporanvarian-Report', menu_level=2 WHERE menu_id='a459';
      //   UPDATE public.pos_menu SET menu_kode='Laporanretur-Report', menu_level=2 WHERE menu_id='a454';
      //   UPDATE public.pos_menu SET menu_kode='Laporansaldo-Report', menu_level=2 WHERE menu_id='a455';
      //   UPDATE public.pos_menu SET menu_kode='Laporanlaris-Report', menu_level=2 WHERE menu_id='a457';
      //   UPDATE public.pos_menu SET menu_kode='Laporanpendapatan-Report', menu_level=2 WHERE menu_id='a458';");
      if($change_pos_menu->total == 0){
        $this->dbchange->query("INSERT INTO public.pos_menu (menu_id,menu_kode,menu_title,menu_order,menu_parent,menu_link,menu_isaktif,menu_level,menu_icon,menu_hassub,menu_main,menu_description) VALUES
          ('4d4dd4c5d919e444d39d69a7a11dbmja','Meja-Table','Nomor Meja','01.09       ','f63143cc466006cf36cfa827b822c321','javascript:void(0)',1,2,'fa fa-dot',0,1,'Meja'),
          ('24fa5d33e6d0c64a7b2bad092383emja','Meja-Read','Nomor Meja Read','01.09.01    ','4d4dd4c5d919e444d39d69a7a11dbmja','javascript:void(0)',1,3,'[NULL]',0,0,'[NULL]'),
          ('5e8367f27c3e40d91fc64a2f52b33mja','Meja-Create','Nomor Meja Create','01.09.02    ','4d4dd4c5d919e444d39d69a7a11dbmja','javascript:void(0)',1,3,'[NULL]',0,0,'[NULL]'),
          ('1ea160938511e7e058b64e5e7fec2mja','Meja-Update','Nomor Meja Update','01.09.03    ','4d4dd4c5d919e444d39d69a7a11dbmja','javascript:void(0)',1,3,'[NULL]',0,0,'[NULL]'),
          ('8988433bd2306ab45173181befb2amja','Meja-Delete','Nomor Meja Delete','01.09.04    ','4d4dd4c5d919e444d39d69a7a11dbmja','javascript:void(0)',1,3,'[NULL]',0,0,'[NULL]');
        INSERT INTO public.pos_menu (menu_id,menu_kode,menu_title,menu_order,menu_parent,menu_link,menu_isaktif,menu_level,menu_icon,menu_hassub,menu_main,menu_description) VALUES
          ('a459','Laporanvarian-Report','Laporan Varian','05.03.02    ','a451','javascript:void(0)',1,2,NULL,0,1,NULL);
        ");
      }

      $change_pos_config = $this->dbchange->select('count(*) as total')
      ->where('conf_code = \'struk_header\'')
      ->get('pos_config')->row();
      if($change_pos_config->total == 0){
        $this->dbchange->query("INSERT INTO public.pos_config (conf_id,conf_code,conf_title,conf_value,conf_info,conf_group,conf_type,conf_active) VALUES
          ('conf_11','struk_is_logo','Show Logo',NULL,NULL,'struk_header','text',1),
          ('conf_8','struk_header','Title Header',NULL,NULL,'struk_header','text',1),
          ('conf_9','struk_is_title_show','Nama Toko',NULL,NULL,'struk_header','text',1),
          ('conf_10','struk_is_antrian','Antrian',NULL,NULL,'struk_header','text',1),
          ('conf_6','struk_footer','Title Footer',NULL,NULL,'struk_footer','text',1),
          ('conf_1','struk_ig','Instagram',NULL,NULL,'struk_footer','text',1),
          ('conf_3','struk_fb','Facebook',NULL,NULL,'struk_footer','text',1),
          ('conf_2','struk_wa','Whatsapp',NULL,NULL,'struk_footer','text',1),
          ('conf_5','struk_tw','Twitter',NULL,NULL,'struk_footer','text',1),
          ('conf_4','struk_yt','Youtube',NULL,NULL,'struk_footer','text',1);
        INSERT INTO public.pos_config (conf_id,conf_code,conf_title,conf_value,conf_info,conf_group,conf_type,conf_active) VALUES
          ('conf_7','struk_logo','Logo',NULL,NULL,'struk_header','text',1);     
        ");
      }
      
      // print_r('<pre>');print_r($change);print_r('</pre>');exit;
      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_menu_mobile' => $change,
        'pos_menu' => $change_pos_menu,
        'pos_config' => $change_pos_config,
      ]]);
    }
    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function hak_akses(){
    $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' or datname ~ \'^pos_reference\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    foreach($dbs as $key => $val){
      $pieces_dbname = explode('_', $val['datname']);
      if($pieces_dbname[0] == 'posprod'){
        $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
        $jenis = 'DEFAULT';
        $toko = $this->db->get_where('v_pajak_pos', ['toko_kode' => $pieces_dbname[1]])->row_array();
        $get_jenis = $this->db->get_where('pajak_jenis', ['jenis_nama' => $toko['jenis_nama']])->row_array();
        $get_jenis_parent = $this->db->get_where('pajak_jenis', ['jenis_id' => $get_jenis['jenis_parent']])->row_array();
        if ($get_jenis_parent['jenis_nama'] == 'PAJAK RESTORAN') {
          $jenis = 'RESTO';

          $this->dbchange->query('DELETE FROM public.pos_menu_role WHERE menu_role_role_access=\'123\';');
          $this->dbchange->query("INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc3e5.14048891','01d37b21c69b02af1d8424079d5d0894','123'),
              ('63add681bcc436.19682925','0f00d16a936020ead8126de4f1e244b0','123'),
              ('63add681bcc1c9.48820155','15f07295c9e43a29764a91dfaaa025c9','123'),
              ('63add681bcc3b3.27803398','17e779a3f2b9e6b8db5489d6d2b16ed9','123'),
              ('63add681bcbec0.14621755','18005664505613f870062170ff916620','123'),
              ('63add681bcc145.22622558','1ea160938511e7e058b64e5e7fec20a3','123'),
              ('63add681bcc196.56938352','1ea160938511e7e058b64e5e7fec2mja','123'),
              ('63add681bcc121.38592866','24fa5d33e6d0c64a7b2bad092383e0d8','123'),
              ('63add681bcc176.63272269','24fa5d33e6d0c64a7b2bad092383emja','123'),
              ('63add681bcc2a8.86064909','3d52e874ac14910945ed87ab027fbbcd','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc3a6.83048366','3ecc31824dab3a1b8eede166acddf805','123'),
              ('63add681bcc227.34717554','3f04d2506c8fbcb8da11d97876fe94c5','123'),
              ('63add681bcc423.03041630','4c56dfa49c466abe43499230c8f47b0a','123'),
              ('63add681bcc111.60976156','4d4dd4c5d919e444d39d69a7a11db8d7','123'),
              ('63add681bcc167.89379293','4d4dd4c5d919e444d39d69a7a11dbmja','123'),
              ('63add681bcc131.10574154','5e8367f27c3e40d91fc64a2f52b337ae','123'),
              ('63add681bcc188.68629990','5e8367f27c3e40d91fc64a2f52b33mja','123'),
              ('63add681bcc243.01906729','67a3084db81a25aa07bff28540da1867','123'),
              ('63add681bcc236.15476988','680a5f074b1f8b3051f74e92952c3e2b','123'),
              ('63add681bcc459.78469393','76018d8e6dea2d11a8ebfa6f977e4634','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc1f9.71676894','7999610d4bd54f7c67af4a650c85e50b','123'),
              ('63add681bcc291.69536683','79b8f4c60bc57b6c7c68bd1d15feda6c','123'),
              ('63add681bcc286.10857523','8347fbf7fdf04a026a87c57364f9c976','123'),
              ('63add681bcc154.31262128','8988433bd2306ab45173181befb2ae28','123'),
              ('63add681bcc1a3.59150831','8988433bd2306ab45173181befb2amja','123'),
              ('63add681bcc1e0.17307906','8c4127274ed17ae4f7398a51d1d33ca7','123'),
              ('63add681bcc3f4.34405709','90f0c0052cabd5ab1b47a38236b2a34e','123'),
              ('63add681bcc3d1.28312574','a40aca9e3eb5201170e486a5912f8351','123'),
              ('63add681bcc309.01122459','a451','123'),
              ('63add681bcc321.21581958','a452','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc314.55585480','a4520','123'),
              ('63add681bcc336.28743806','a453','123'),
              ('63add681bcc355.85118978','a454','123'),
              ('63add681bcc365.05863337','a455','123'),
              ('63add681bcc378.51507776','a457','123'),
              ('63add681bcc386.97634262','a458','123'),
              ('63add681bcc343.66527682','a459','123'),
              ('63add681bcc2b4.75352648','abc5f95c0e46a1408151262a00ab001','123'),
              ('63add681bcc2c9.48235376','abc5f95c0e46a1408151262a00ab002','123'),
              ('63add681bcc2d3.37911362','abc5f95c0e46a1408151262a00ab003','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc2e5.42776654','abc5f95c0e46a1408151262a00ab004','123'),
              ('63add681bcc2f1.97221145','abc5f95c0e46a1408151262a00ab005','123'),
              ('63add681bcc397.19863994','b5a5a02b1385c26eb5106c61b00e9480','123'),
              ('63add681bcc259.22473765','c3ee7c6658699480f51c58d9551afd6e','123'),
              ('63add681bcc443.78115333','c5dd6c1f1512bd16379689c8d9872e1a','123'),
              ('63add681bcc3c5.79463035','dc2cd570218e02be3b78be7f4df0cbac','123'),
              ('63add681bcc411.39377860','e1e72f74c86831337fe4784ed86073a7','123'),
              ('63add681bcc217.14342581','e28988f0f0f5f1f59ab7b0ee9bf809ea','123'),
              ('63add681bcc1d8.38095930','e5b0fcbcd473a0ca9e366ace3499ac62','123'),
              ('63add681bcc268.93853183','e925f95c0e46a1408151262a00ab6b9a','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc405.49204668','ed973b48beac685f7cc05cfdfcbe7bc0','123'),
              ('63add681bcbef2.32713574','f3143cc466006cf36cfa827b822c1231','123'),
              ('63add681bcbf03.60756594','f3143cc466006cf36cfa827b822c1232','123'),
              ('63add681bcbf10.62946374','f3143cc466006cf36cfa827b822c1233','123'),
              ('63add681bcbf21.33371065','f3143cc466006cf36cfa827b822c1234','123'),
              ('63add681bcbf43.42247122','f4143cc466006cf36cfa827b822c1231','123'),
              ('63add681bcbf50.68072377','f4143cc466006cf36cfa827b822c1232','123'),
              ('63add681bcbf61.46263575','f4143cc466006cf36cfa827b822c1233','123'),
              ('63add681bcbf79.80785391','f4143cc466006cf36cfa827b822c1234','123'),
              ('63add681bcbf93.14796211','f5143cc466006cf36cfa827b822c1231','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcbfa4.34359146','f5143cc466006cf36cfa827b822c1232','123'),
              ('63add681bcbfb9.09202078','f5143cc466006cf36cfa827b822c1233','123'),
              ('63add681bcbfc9.78442451','f5143cc466006cf36cfa827b822c1234','123'),
              ('63add681bcc203.11844739','f57c32d82eeef5fb5f2dde3e7369b23c','123'),
              ('63add681bcc037.20190500','f6143cc466006cf36cfa827b822c1231','123'),
              ('63add681bcc041.94393269','f6143cc466006cf36cfa827b822c1232','123'),
              ('63add681bcc058.52398892','f6143cc466006cf36cfa827b822c1233','123'),
              ('63add681bcc061.24733705','f6143cc466006cf36cfa827b822c1234','123'),
              ('63add681bcc086.14233009','f6143cc466006cf36cfa827b822c1831','123'),
              ('63add681bcc092.67326040','f6143cc466006cf36cfa827b822c1832','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc0a1.44423471','f6143cc466006cf36cfa827b822c1833','123'),
              ('63add681bcc0b7.82491092','f6143cc466006cf36cfa827b822c1834','123'),
              ('63add681bcc0d0.27858137','f6143cc466006cf36cfa827b822c1931','123'),
              ('63add681bcc0e0.36632232','f6143cc466006cf36cfa827b822c1932','123'),
              ('63add681bcc0f2.37846441','f6143cc466006cf36cfa827b822c1933','123'),
              ('63add681bcc102.23265029','f6143cc466006cf36cfa827b822c1934','123'),
              ('63add681bcbee9.75191135','f63143cc466006cf36cfa827b822c123','123'),
              ('63add681bcbf33.25151101','f63143cc466006cf36cfa827b822c124','123'),
              ('63add681bcbf81.03008520','f63143cc466006cf36cfa827b822c125','123'),
              ('63add681bcbfd7.35591983','f63143cc466006cf36cfa827b822c126','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc026.11267805','f63143cc466006cf36cfa827b822c127','123'),
              ('63add681bcc076.88584327','f63143cc466006cf36cfa827b822c128','123'),
              ('63add681bcc0c7.43369691','f63143cc466006cf36cfa827b822c129','123'),
              ('63add681bcbed4.05375028','f63143cc466006cf36cfa827b822c321','123'),
              ('63add681bcc1b5.52735894','f63143cc466006cf36cfa827b822c332','123'),
              ('63add681bcbe70.52093002','f63143cc466006cf36cfa827b822c442','123'),
              ('63add681bcbfe8.26976560','f7143cc466006cf36cfa827b822c1231','123'),
              ('63add681bcbff2.92298825','f7143cc466006cf36cfa827b822c1232','123'),
              ('63add681bcc009.49422122','f7143cc466006cf36cfa827b822c1233','123'),
              ('63add681bcc011.80044661','f7143cc466006cf36cfa827b822c1234','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add681bcc278.29440852','f7b2cb632ddbfa56399d6ba2fcc00ec4','123');
          ");

        } else if ($get_jenis_parent['jenis_nama'] == 'PAJAK HOTEL') {
          $jenis = 'HOTEL';

          $this->dbchange->query('DELETE FROM public.pos_menu_role WHERE menu_role_role_access=\'123\';');
          $this->dbchange->query("INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add8933672f1.32858318','01d37b21c69b02af1d8424079d5d0894','123'),
              ('63add893367324.08266582','0f00d16a936020ead8126de4f1e244b0','123'),
              ('63add8933673d3.16775538','15f07295c9e43a29764a91dfaaa025c9','123'),
              ('63add8933672c0.65147387','17e779a3f2b9e6b8db5489d6d2b16ed9','123'),
              ('63add893366fb6.66117640','18005664505613f870062170ff916620','123'),
              ('63add8933671a3.00989047','1ea160938511e7e058b64e5e7fec20a3','123'),
              ('63add893367180.56159785','24fa5d33e6d0c64a7b2bad092383e0d8','123'),
              ('63add893367272.37225478','3d52e874ac14910945ed87ab027fbbcd','123'),
              ('63add893367425.91057429','3ecc31824dab3a1b8eede166acddf805','123'),
              ('63add893367206.65236992','3f04d2506c8fbcb8da11d97876fe94c5','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add893367319.34649145','4c56dfa49c466abe43499230c8f47b0a','123'),
              ('63add8933673c5.65383380','4d4dd4c5d919e444d39d69a7a11db8d7','123'),
              ('63add893367192.45414379','5e8367f27c3e40d91fc64a2f52b337ae','123'),
              ('63add893367227.46510057','67a3084db81a25aa07bff28540da1867','123'),
              ('63add893367212.34531233','680a5f074b1f8b3051f74e92952c3e2b','123'),
              ('63add893367340.44203065','76018d8e6dea2d11a8ebfa6f977e4634','123'),
              ('63add8933671e9.03598273','7999610d4bd54f7c67af4a650c85e50b','123'),
              ('63add893367268.24990928','79b8f4c60bc57b6c7c68bd1d15feda6c','123'),
              ('63add893367252.65543415','8347fbf7fdf04a026a87c57364f9c976','123'),
              ('63add8933671b2.68382089','8988433bd2306ab45173181befb2ae28','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add8933671d2.12063312','8c4127274ed17ae4f7398a51d1d33ca7','123'),
              ('63add893367430.85093255','90f0c0052cabd5ab1b47a38236b2a34e','123'),
              ('63add8933672e3.84657425','a40aca9e3eb5201170e486a5912f8351','123'),
              ('63add8933674d0.93816199','a451','123'),
              ('63add893367474.11000418','a4520','123'),
              ('63add893367482.06717385','a453','123'),
              ('63add8933674a5.32186332','a454','123'),
              ('63add8933674b4.34175356','a457','123'),
              ('63add8933674c1.79950821','a458','123'),
              ('63add893367494.28668802','a459','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add893367408.32680139','abc5f95c0e46a1408151262a00ab001','123'),
              ('63add893367283.09006321','abc5f95c0e46a1408151262a00ab002','123'),
              ('63add893367296.00903118','abc5f95c0e46a1408151262a00ab003','123'),
              ('63add8933672a1.84563950','abc5f95c0e46a1408151262a00ab004','123'),
              ('63add8933672b4.79208413','abc5f95c0e46a1408151262a00ab005','123'),
              ('63add893367450.96604196','b5a5a02b1385c26eb5106c61b00e9480','123'),
              ('63add893367234.77683595','c3ee7c6658699480f51c58d9551afd6e','123'),
              ('63add893367336.86131569','c5dd6c1f1512bd16379689c8d9872e1a','123'),
              ('63add8933672d6.14601034','dc2cd570218e02be3b78be7f4df0cbac','123'),
              ('63add893367445.90730084','e1e72f74c86831337fe4784ed86073a7','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add8933673e1.61148583','e28988f0f0f5f1f59ab7b0ee9bf809ea','123'),
              ('63add8933671c6.85907764','e5b0fcbcd473a0ca9e366ace3499ac62','123'),
              ('63add8933673f3.70390639','e925f95c0e46a1408151262a00ab6b9a','123'),
              ('63add893367309.98101480','ed973b48beac685f7cc05cfdfcbe7bc0','123'),
              ('63add893367007.30080227','f3143cc466006cf36cfa827b822c1231','123'),
              ('63add893367017.07588188','f3143cc466006cf36cfa827b822c1232','123'),
              ('63add893367022.93082208','f3143cc466006cf36cfa827b822c1233','123'),
              ('63add893367034.74338645','f3143cc466006cf36cfa827b822c1234','123'),
              ('63add893367041.85828090','f4143cc466006cf36cfa827b822c1231','123'),
              ('63add893367052.72128114','f4143cc466006cf36cfa827b822c1232','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add893367068.09277393','f4143cc466006cf36cfa827b822c1233','123'),
              ('63add893367073.82240742','f4143cc466006cf36cfa827b822c1234','123'),
              ('63add893367082.67696091','f5143cc466006cf36cfa827b822c1231','123'),
              ('63add893367097.45130400','f5143cc466006cf36cfa827b822c1232','123'),
              ('63add8933670a0.80943117','f5143cc466006cf36cfa827b822c1233','123'),
              ('63add8933670b8.87418415','f5143cc466006cf36cfa827b822c1234','123'),
              ('63add8933671f2.99095114','f57c32d82eeef5fb5f2dde3e7369b23c','123'),
              ('63add893367105.74034208','f6143cc466006cf36cfa827b822c1831','123'),
              ('63add893367112.89130774','f6143cc466006cf36cfa827b822c1832','123'),
              ('63add893367123.86721485','f6143cc466006cf36cfa827b822c1833','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add893367136.12314311','f6143cc466006cf36cfa827b822c1834','123'),
              ('63add893367143.72370969','f6143cc466006cf36cfa827b822c1931','123'),
              ('63add893367152.25587922','f6143cc466006cf36cfa827b822c1932','123'),
              ('63add893367165.16989788','f6143cc466006cf36cfa827b822c1933','123'),
              ('63add893367178.61457029','f6143cc466006cf36cfa827b822c1934','123'),
              ('63add893367361.98034637','f63143cc466006cf36cfa827b822c123','123'),
              ('63add893367374.51240795','f63143cc466006cf36cfa827b822c124','123'),
              ('63add893367385.15195427','f63143cc466006cf36cfa827b822c125','123'),
              ('63add893367397.46061721','f63143cc466006cf36cfa827b822c126','123'),
              ('63add8933673a3.87287807','f63143cc466006cf36cfa827b822c128','123');
          INSERT INTO public.pos_menu_role (menu_role_id,menu_role_menu,menu_role_role_access) VALUES
              ('63add8933673b1.19354776','f63143cc466006cf36cfa827b822c129','123'),
              ('63add893367462.18373318','f63143cc466006cf36cfa827b822c321','123'),
              ('63add893367415.63914760','f63143cc466006cf36cfa827b822c332','123'),
              ('63add893367350.48800286','f63143cc466006cf36cfa827b822c442','123'),
              ('63add8933670c2.72707682','f7143cc466006cf36cfa827b822c1231','123'),
              ('63add8933670d9.77793845','f7143cc466006cf36cfa827b822c1232','123'),
              ('63add8933670e7.97813891','f7143cc466006cf36cfa827b822c1233','123'),
              ('63add8933670f2.79560374','f7143cc466006cf36cfa827b822c1234','123'),
              ('63add893367249.11868386','f7b2cb632ddbfa56399d6ba2fcc00ec4','123');
          ");
        }
        $flag += 1;
        array_push($link, [$val['datname'] => $jenis]);
      }

    }

    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }

  public function menu_kasir(){
    // $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\' and datname != \'pos_oapi_dialoogi\'')->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $gettoko = $this->db->query('SELECT toko_kode, jenis_nama from v_pajak_toko where jenis_nama = \'PANTAI PIJAT, SPA\'')->result_array();
    $dbtoko = [];
    // print_r('<pre>');print_r($gettoko);print_r('</pre>');exit;
    foreach($gettoko as $key => $val){
      array_push($dbtoko, $_ENV['PREFIX_DBPOS'].$val['toko_kode']);
    }
    $dbtoko = implode('\',\'', $dbtoko);
    // print_r('<pre>');print_r($dbtoko);print_r('</pre>');
    $dbs = $this->db->query("SELECT datname from pg_database where datname IN ('$dbtoko')")->result_array();
    // print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    print_r('<pre>');print_r($dbs);print_r('</pre>');exit;
    $flag = 0;
    $link = [];
    foreach($dbs as $key => $val){
      $this->dbchange = $this->load->database(multidb_connect($val['datname']), true);
      $change = $this->dbchange->select('*, \''.$val['datname'].'\' as prefix')->where('menu_id in (\'7999610d4bd54f7c67af4a650c85e50b\',
      \'8c4127274ed17ae4f7398a51d1d33ca7\',
      \'15f07295c9e43a29764a91dfaaa025c9\',
      \'e5b0fcbcd473a0ca9e366ace3499ac62\')', null)
      ->update('pos_menu', [
        'menu_link' => 'https://persada.malangkota.go.id/pos/index.php/kasir',
      ]);
      // ->get('pos_menu')->result_array();
      print_r('<pre>');print_r($this->dbchange->last_query());print_r('</pre>');exit;
      $flag += 1;
      array_push($link, [$val['datname'] => $change[0]['menu_link']]);
    }
    $this->response([
      'success' => true,
      'message' => 'menu link kasir updated: '.$flag,
      'data' => $link,
    ]);
  }
  
  public function store(){
    $data = varPost();

    $this->db->trans_begin();
    $this->dbref->trans_begin();

    if(!empty($data['exec_jenis'])){
      // Get all DB name startswith pos_*
      $dbs = $this->db->query('SELECT datname from pg_database where datname ~ \'^'.$_ENV['PREFIX_DBPOS'].'\'')->result_array();

      // jenis tables
      if($data['exec_jenis'] == 'tables'){
        // Get all views name from db reference
        $views = $this->dbref->query('SELECT table_name from INFORMATION_SCHEMA.views where table_schema NOT IN (\'pg_catalog\', \'information_schema\')
        and table_name !~ \'pg_\';')->result_array();
    
        // save ddl views
        $ddlviews = [];
        foreach($views as $key => $value){
          $ddlviews[$value['table_name']] = $this->dbref->query('SELECT pg_get_viewdef(\''.$value['table_name'].'\'::regclass, true)')->row()->pg_get_viewdef;
        }
    
        // drop all views & execute alter table
        $dropviews = $this->dbref->query('SELECT \'DROP VIEW IF EXISTS \' || table_name || \';\' as qdrop
        FROM information_schema.views
        WHERE table_schema NOT IN (\'pg_catalog\', \'information_schema\')
        AND table_name !~ \'^pg_\';')->result_array();
    
        foreach($dbs as $key => $val){
          $m_db = $this->load->database(multidb_connect($val['datname']), true);
    
          $m_db->trans_begin();
    
          foreach($dropviews as $key => $val){
            $execdropviews = $m_db->query($val['qdrop']);
          }
    
          // execute alter table here
          // ...
          if(!empty($data['exec_query'])){
            $execalter = $m_db->query($data['exec_query']);
          }
          
          // create all views
          foreach($ddlviews as $key => $val){
            $executecreateviews = $m_db->query('CREATE OR REPLACE VIEW public.'.$key.' AS '.$val);
          }
    
          if ($m_db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'],
            ));
          }else if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'].'. When execute main db',
            ));
          }else if($this->dbref->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'].'. When execute reference db',
            ));
          }else{
            $this->db->trans_commit();
            $this->dbref->trans_commit();
            $m_db->trans_commit();
            $m_db->close();
          }
        }
      }
      // jenis views
      else if($data['exec_jenis'] == 'views'){
        foreach($dbs as $key => $val){
          $m_db = $this->load->database(multidb_connect($val['datname']), true);
          $m_db->trans_begin();
          if(!empty($data['exec_query'])){
            $execviews = $m_db->query($data['exec_query']);
          }

          if ($m_db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'],
            ));
          }else if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'].'. When execute main db',
            ));
          }else if($this->dbref->trans_status() === FALSE){
            $this->db->trans_rollback();
            $this->dbref->trans_rollback();
            $m_db->trans_rollback();
            $m_db->close();
            return $this->response(array(
              'success' => false,
              'message' => 'error execute query in drop progress at '.$val['datname'].'. When execute reference db',
            ));
          }else{
            $this->db->trans_commit();
            $this->dbref->trans_commit();
            $m_db->trans_commit();
            $m_db->close();
          }
        }
      }
      // jenis invalid
      else{
        return $this->response(array(
          'success' => false,
          'message' => 'jenis perubahan invalid.',
        ));
      }
    }else{
      return $this->response(array(
        'success' => false,
        'message' => 'jenis perubahan required.',
      ));
    }

    // end
    if ($this->db->trans_status() === FALSE){
      $this->db->trans_rollback();
      $this->dbref->trans_rollback();
      return $this->response(array(
        'success' => false,
        'message' => 'error execute query in main db',
      ));
    }else if($this->dbref->trans_status() === FALSE){
      $this->db->trans_rollback();
      $this->dbref->trans_rollback();
      return $this->response(array(
        'success' => false,
        'message' => 'error execute query in reference db',
      ));
    }else{
      $this->db->trans_commit();
      $this->dbref->trans_commit();
      return $this->response(array(
        'success' => true,
        'message' => 'successfully updated databases.',
      ));
    }

  }
}