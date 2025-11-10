<?php defined('BASEPATH') or exit('No direct script access allowed');

class V3 extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(

    ));

    $this->dbref = $this->load->database(multidb_connect('pos_reference'), true);
  }

  public function index(){
    $gettoko = $this->db->query('SELECT toko_kode, jenis_nama from v_pajak_toko where jenis_nama != \'PAJAK PARKIR\'')->result_array();
    $dbtoko = [];
    // print_r('<pre>');print_r($gettoko);print_r('</pre>');exit;
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
      $pos_barang = $this->table_pos_barang($this->dbchange);
      $column_penjualan_no_antrian = $this->column_penjualan_no_antrian($this->dbchange);
      $column_pos_penjualan_meja = $this->column_pos_penjualan_meja($this->dbchange);
      $pos_penjualan = $this->column_pos_penjualan($this->dbchange);
      $pos_meja = $this->pos_meja($this->dbchange);
      $v_pos_penjualan = $this->v_pos_penjualan($this->dbchange);
      $v_pos_penjualan_detail = $this->v_pos_penjualan_detail($this->dbchange);

      //new

      $pos_diskon = $this->pos_diskon($this->dbchange);
      $pos_room = $this->pos_room($this->dbchange);
      $v_pos_barang_v2 = $this->v_pos_barang_v2($this->dbchange);
      $v_pos_penjualan_detail_hiburan = $this->v_pos_penjualan_detail_hiburan($this->dbchange);
      $v_pos_penjualan_v2 = $this->v_pos_penjualan_v2($this->dbchange);
      $v_pos_penjualan3 = $this->v_pos_penjualan3($this->dbchange);

      $flag += 1;
      array_push($link, [$val['datname'] => [
        'pos_barang' => !$pos_barang,
        'column_penjualan_no_antrian' => !$column_penjualan_no_antrian,
        'column_pos_penjualan_meja' => !$column_pos_penjualan_meja,
        'pos_penjualan' => !$pos_penjualan,
        'pos_meja' => !$pos_meja,
        'v_pos_penjualan' => !$v_pos_penjualan,
        'v_pos_penjualan_detail' => !$v_pos_penjualan_detail,

        // new
        'pos_diskon' => !$pos_diskon,
        'pos_room' => !$pos_room,
        'v_pos_barang_v2' => !$v_pos_barang_v2,
        'v_pos_penjualan_detail_hiburan' => !$v_pos_penjualan_detail_hiburan,
        'v_pos_penjualan_v2' => !$v_pos_penjualan_v2,
        'v_pos_penjualan3' => !$v_pos_penjualan3,
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
  
  function table_pos_barang($db){
    $_change = $db->select('table_name, column_name')
    ->where('table_name = \'pos_barang\'')
    ->where('column_name=\'barang_keterangan\'')
    ->get('information_schema.columns')->row();

    // print_r('<pre>');print_r($db);print_r('</pre>');
    
    // */
    if(!$_change){
      $change = $db->query('ALTER TABLE public.pos_barang ADD barang_keterangan text NULL;');
      $_change = 'updated';
    }
    return $_change;
  }

  function column_penjualan_no_antrian($db){
    $_change = $db->select('table_name, column_name')
    ->where('table_name = \'pos_penjualan\'')
    ->where('column_name=\'penjualan_no_antrian\'')
    ->get('information_schema.columns')->row();
    
    // */
    if(!$_change){
      $change = $db->query('ALTER TABLE public.pos_penjualan ADD penjualan_no_antrian varchar(10) NULL;');
      $_change = 'updated';
    }
    return $_change;
  }

  function column_pos_penjualan_meja($db){
    $_change = $db->select('table_name, column_name')
    ->where('table_name = \'pos_penjualan\'')
    ->where('column_name=\'penjualan_meja_id\'')
    ->get('information_schema.columns')->row();
    
    // */
    if(!$_change){
      $change = $db->query('ALTER TABLE public.pos_penjualan ADD penjualan_meja_id varchar(32) NULL;');
      $_change = 'updated';
    }
    return $_change;
  }

  function column_pos_penjualan($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'pos_penjualan\'')
    ->where('column_name=\'penjualan_room_id\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if(!$_change){
      $change = $db->query('ALTER TABLE public.pos_penjualan ADD penjualan_custom_menu text NULL;

      ALTER TABLE public.pos_penjualan ADD penjualan_room_id varchar(32) NULL;');
      $_change = 'updated';
    }
    return $_change;
  }

  function pos_meja($db){
    $_change = $db->select('table_name, column_name')
    ->where('table_name = \'pos_meja\'')
    // ->where('column_name=\'barang_keterangan\'')
    ->get('information_schema.columns')->row();
    
    // */
    if(!$_change){
      $change = $db->query('-- public.pos_meja definition

      -- Drop table
      
      -- DROP TABLE pos_meja;
      
      CREATE TABLE pos_meja (
        meja_id varchar(32) NOT NULL,
        meja_kode varchar(32) NULL,
        meja_nama varchar(50) NULL,
        meja_keterangan varchar(115) NULL,
        meja_created_at timestamp(6) NULL,
        meja_status bpchar(1) NULL,
        meja_deleted_at timestamp(6) NULL,
        meja_updated_at timestamp(6) NULL,
        CONSTRAINT pos_meja_pk PRIMARY KEY (meja_id)
      );');
      $_change = 'updated';
    }
    return $_change;
  }

  function v_pos_penjualan($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_penjualan\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_penjualan source
      DROP VIEW public.v_pos_penjualan;
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
          pos_penjualan.penjualan_meja_id,
          pos_penjualan.penjualan_room_id
         FROM pos_penjualan
           LEFT JOIN pos_customer ON pos_penjualan.pos_penjualan_customer_id::text = pos_customer.customer_id::text
           LEFT JOIN pos_barang pb ON pb.barang_id::text = pos_penjualan.penjualan_first_item::text
           LEFT JOIN pos_meja pm ON pm.meja_id::text = pos_penjualan.penjualan_meja_id::text;');
      $_change = 'updated';
    }
    return $_change;
  }
  
  function v_pos_penjualan_detail($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_penjualan_detail\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_penjualan_detail source
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
          pos_jenis.jenis_include_stok,
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
           LEFT JOIN pos_barang ON pos_penjualan_detail.penjualan_detail_barang_id::text = pos_barang.barang_id::text
           LEFT JOIN pos_jenis ON pos_barang.barang_jenis_barang::text = pos_jenis.jenis_id::text;');
      $_change = 'updated';
    }
    return $_change;
  }

  function pos_diskon($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'pos_diskon\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if(!$_change){
      $change = $db->query('-- public.pos_diskon definition

      -- Drop table
      
      -- DROP TABLE pos_diskon;
      
      CREATE TABLE pos_diskon (
        diskon_id varchar(32) NOT NULL,
        diskon_name varchar(100) NULL,
        diskon_nominal int4 NULL,
        diskon_keterangan varchar(150) NULL,
        diskon_created_at timestamp(6) NULL,
        diskon_updated_at timestamp(6) NULL,
        diskon_deleted_at timestamp(6) NULL,
        diskon_foto varchar(150) NULL,
        CONSTRAINT pos_diskon_pk PRIMARY KEY (diskon_id)
      );');
      $_change = 'updated';
    }
    return $_change;
  }

  function pos_room($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'pos_room\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if(!$_change){
      $change = $db->query('-- public.pos_room definition

      -- Drop table
      
      -- DROP TABLE pos_room;
      
      CREATE TABLE pos_room (
        room_id varchar(32) NOT NULL,
        room_code varchar(50) NULL,
        room_name varchar(100) NULL,
        room_created_at timestamp NULL,
        room_updated_at timestamp NULL,
        room_deleted_at timestamp NULL,
        room_price int4 NULL,
        CONSTRAINT pos_room_pk PRIMARY KEY (room_id)
      );');
      $_change = 'updated';
    }
    return $_change;
  }

  function v_pos_barang_v2($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_barang_v2\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_barang_v2 source
      DROP VIEW public.v_pos_barang_v2;
      CREATE OR REPLACE VIEW public.v_pos_barang_v2
      AS SELECT pos_barang.barang_id,
          pos_barang.barang_kode,
          pos_barang.barang_nama,
          pos_barang.barang_jenis_barang,
          pos_barang.barang_kategori_barang,
          pos_barang.barang_kategori_parent,
          pos_barang.barang_satuan,
          pos_barang.barang_satuan_kode,
          pos_barang.barang_stok_min,
          pos_barang.barang_harga,
          pos_barang.barang_harga_pokok,
          pos_barang.barang_stok,
          pos_barang.barang_isi,
          pos_barang.barang_supplier_id,
          pos_kategori.kategori_barang_kode,
          pos_kategori.kategori_barang_nama,
          pos_kategori_parent.kategori_barang_nama AS kategori_barang_nama_parent,
          pos_barang.barang_is_konsinyasi,
          pos_barang.barang_barcode AS barang_barang_barcode,
          pos_barang.barang_user,
          pos_barang.barang_updated,
          pos_barang.barang_aktif,
          pos_barang.barang_satuan_opt,
          pos_barang.barang_satuan_opt_kode,
          pos_barang.barang_persen_untung,
          pos_barang.barang_harga_beli,
          pos_barang.barang_harga_opt,
          pos_barang.barang_satuan_opt2_kode,
          pos_barang.barang_yearly,
          pos_barang.barang_harga_opt2,
          pos_barang.barang_disc,
          pos_barang.barang_ppn,
          pos_barang.barang_deleted_at,
          pos_supplier.supplier_nama,
          pos_barang.barang_thumbnail,
          pos_barang.barang_image,
          pos_barang.barang_keterangan,
          pbb.barang_barcode_kode AS barang_barcode,
          pos_jenis.jenis_nama,
          pos_jenis.jenis_include_stok
         FROM pos_barang
           LEFT JOIN pos_kategori ON pos_barang.barang_kategori_barang::text = pos_kategori.kategori_barang_id::text
           LEFT JOIN pos_kategori pos_kategori_parent ON pos_barang.barang_kategori_parent::text = pos_kategori_parent.kategori_barang_id::text
           LEFT JOIN pos_supplier ON pos_barang.barang_supplier_id::text = pos_supplier.supplier_id::text
           LEFT JOIN pos_barang_barcode pbb ON pos_barang.barang_id::text = pbb.barang_barcode_parent::text AND pbb.barang_barcode_id::text = (( SELECT max(z.barang_barcode_id::text) AS barang_barcode_id
                 FROM pos_barang_barcode z
                WHERE z.barang_barcode_parent::text = pbb.barang_barcode_parent::text))
           LEFT JOIN pos_jenis ON pos_barang.barang_jenis_barang::text = pos_jenis.jenis_id::text
        ORDER BY pos_barang.barang_kode;');
      $_change = 'updated';
    }
    return $_change;
  }

  function v_pos_penjualan_detail_hiburan($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_penjualan_detail_hiburan\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_penjualan_detail_hiburan source
      DROP VIEW public.v_pos_penjualan_detail_hiburan;
      CREATE OR REPLACE VIEW public.v_pos_penjualan_detail_hiburan
      AS SELECT pos_penjualan_detail.penjualan_detail_id,
          pos_penjualan.penjualan_id,
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
          pos_jenis.jenis_include_stok,
          pos_room.room_id,
          pos_room.room_code,
          pos_room.room_name,
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
          pos_penjualan_detail.penjualan_detail_custom_menu,
          pos_room.room_price
         FROM pos_penjualan_detail
           LEFT JOIN pos_barang ON pos_penjualan_detail.penjualan_detail_barang_id::text = pos_barang.barang_id::text
           LEFT JOIN pos_jenis ON pos_barang.barang_jenis_barang::text = pos_jenis.jenis_id::text
           LEFT JOIN pos_penjualan ON pos_penjualan_detail.penjualan_detail_parent::text = pos_penjualan.penjualan_id::text
           LEFT JOIN pos_room ON pos_penjualan.penjualan_room_id::text = pos_room.room_id::text;');
      $_change = 'updated';
    }
    return $_change;
  }

  function v_pos_penjualan_v2($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_penjualan_v2\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_penjualan_v2 source
      DROP VIEW public.v_pos_penjualan_v2;
      CREATE OR REPLACE VIEW public.v_pos_penjualan_v2
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
          pos_penjualan.penjualan_room_id,
          pos_customer.customer_nama,
          pb.barang_nama,
          pos_penjualan.penjualan_first_item,
          pb.barang_aktif,
          pm.meja_nama,
          pos_penjualan.penjualan_no_antrian,
          pos_customer.customer_kode,
          pos_penjualan.penjualan_meja_id,
          pr.room_code,
          pr.room_name
         FROM pos_penjualan
           LEFT JOIN pos_customer ON pos_penjualan.pos_penjualan_customer_id::text = pos_customer.customer_id::text
           LEFT JOIN pos_barang pb ON pb.barang_id::text = pos_penjualan.penjualan_first_item::text
           LEFT JOIN pos_meja pm ON pm.meja_id::text = pos_penjualan.penjualan_meja_id::text
           LEFT JOIN pos_room pr ON pr.room_id::text = pos_penjualan.penjualan_room_id::text;');
      $_change = 'updated';
    }
    return $_change;
  }

  function v_pos_penjualan3($db){
    $_change = $db->select('table_name')
    ->where('table_name = \'v_pos_penjualan3\'')
    // ->get('penjualan_room_id')->result_array();
    ->get('information_schema.columns')->row();
    // */
    if($_change){
      $change = $db->query('-- public.v_pos_penjualan3 source
      DROP VIEW public.v_pos_penjualan3;
      CREATE OR REPLACE VIEW public.v_pos_penjualan3
      AS SELECT pp.penjualan_id,
          pp.penjualan_tanggal,
          pp.penjualan_kode,
          pp.penjualan_total_item,
          pp.penjualan_total_qty,
          pp.penjualan_total_harga,
          pp.penjualan_total_grand,
          pp.penjualan_total_bayar,
          pp.penjualan_total_retur,
          pp.penjualan_bayar_sisa,
          pp.penjualan_total_kredit,
          pp.penjualan_total_bayar_tunai,
          pp.penjualan_total_potongan_persen,
          pp.penjualan_pajak_persen,
          pp.penjualan_total_potongan,
          pp.penjualan_total_kembalian,
          pp.penjualan_total_cicilan,
          pp.penjualan_total_cicilan_qty,
          pp.penjualan_kredit_awal,
          pp.penjualan_jatuh_tempo,
          pp.penjualan_user_id,
          pp.penjualan_created,
          pp.penjualan_user_nama,
          pp.penjualan_keterangan,
          pp.penjualan_total_jasa,
          pp.penjualan_total_jasa_nilai,
          pp.penjualan_jenis_potongan,
          pp.penjualan_is_konsinyasi,
          pp.penjualan_metode,
          pp.penjualan_kasir,
          pp.penjualan_bank,
          pp.penjualan_bank_ref,
          pp.penjualan_jenis_barang,
          pp.penjualan_lock,
          pp.pos_penjualan_customer_id,
          pp.penjualan_total_bayar_bank,
          pp.penjualan_jasa,
          pp.penjualan_status_aktif,
          pp.penjualan_platform,
          pp.penjualan_first_item,
          pp.penjualan_no_antrian,
          pp.penjualan_meja_id,
          pp.penjualan_custom_menu,
          pp.penjualan_room_id,
          pc.customer_id,
          pc.customer_kode,
          pc.customer_nama,
          pc.customer_alamat,
          pc.customer_telp,
          pc.customer_membership,
          pc.customer_created_at,
          pc.customer_updated_at,
          pc.customer_deleted_at,
          pm.meja_id,
          pm.meja_kode,
          pm.meja_nama,
          pm.meja_keterangan,
          pm.meja_created_at,
          pm.meja_status,
          pm.meja_deleted_at,
          pm.meja_updated_at
         FROM pos_penjualan pp
           LEFT JOIN pos_customer pc ON pp.pos_penjualan_customer_id::text = pc.customer_id::text
           LEFT JOIN pos_meja pm ON pm.meja_id::text = pp.penjualan_meja_id::text;');
      $_change = 'updated';
    }
    return $_change;
  }
}
