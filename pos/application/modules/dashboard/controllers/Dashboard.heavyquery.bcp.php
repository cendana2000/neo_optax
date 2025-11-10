<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Khill\Duration\Duration;

class Dashboard extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
    $this->load->model(array(

    ));
  }
  
  public function total_stok_barang(){
    $op = $this->db->query("SELECT COALESCE(SUM(barang_stok), 0) as total_stok_barang FROM pos_barang");
    $this->response($op->row());
  }

  public function total_pembelian_barang(){
    $data = varPost();
    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }
    // print_r($begin);
    // print_r($end);
    // die();
    $op = $this->db->query("SELECT COALESCE(SUM(pembelian_jumlah_qty), 0) as total_pembelian_barang 
    FROM pos_pembelian_barang WHERE pembelian_tanggal BETWEEN '".$begin."' and '".$end."'");
    $this->response($op->row());
  }

  public function total_penjualan_barang(){
    $data = varPost();
    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }
    $op = $this->db->query("SELECT COALESCE(SUM(penjualan_total_qty), 0) as total_penjualan_barang 
    FROM pos_penjualan 
    WHERE penjualan_tanggal BETWEEN '".$begin."' and '".$end."'");
    $this->response($op->row());
  }

  public function barang_terlaris(){
    $data = varPost();
    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }
    $op = $this->db->query("SELECT sum(kartu_stok_keluar) as total_kartu_stok_keluar, pb.barang_nama 
      FROM pos_kartu_stok
      INNER JOIN pos_barang pb ON kartu_barang_id = pb.barang_id 
      WHERE kartu_transaksi = 'Penjualan'
      AND kartu_created_at BETWEEN '".$begin." 00:00:00' and '".$end." 23:59:59'
      GROUP BY kartu_barang_id 
      ORDER BY total_kartu_stok_keluar DESC
      LIMIT 10");
    $this->response($op->result());
  }

  public function statistik_pembelian(){
    $data = varPost();

    if($data['type'] == "tanggal"){
      $begin = new DateTime($data['awal_tanggal']);
      $end = (new DateTime($data['akhir_tanggal']))->modify('+1 day');
      $rawbegin = $data['awal_tanggal'];
      $rawend = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = new DateTime($bulan);
      $end = (new DateTime($bulan))->modify('+1 month');
      $rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
      $rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }

    $interval = DateInterval::createFromDateString('1 Day');
    $period = new DatePeriod($begin, $interval, $end);

    $tunai = array();
    $kredit = array();
    $categories = array();
    $date = array();

    foreach ($period as $dt) {
      // print_r($dt->format("l Y-m-d H:i:s\n").'</br>');
      array_push($tunai, "0");
      array_push($kredit, "0");
      array_push($categories, $dt->format("d M Y"));
      array_push($date, $dt->format("Y-m-d"));
    }

    // die(print_r($period));
    
    $optunai = $this->db->query("SELECT sum(pembelian_total) as pembelian_total, pembelian_tanggal 
    FROM pos_pembelian_barang pp 
    WHERE pembelian_bayar_opsi = 'T' 
    AND pembelian_tanggal BETWEEN '".$rawbegin."' and '".$rawend."' GROUP by pembelian_tanggal");

    foreach($optunai->result() as $key => $value){
      // search index of date
      $opdate = array_search($value->pembelian_tanggal, $date);
      $tunai[$opdate] = $value->pembelian_total;
    }

    $opkredit = $this->db->query("SELECT sum(pembelian_total) as pembelian_total, pembelian_tanggal 
    FROM pos_pembelian_barang pp 
    WHERE pembelian_bayar_opsi = 'K' 
    AND pembelian_tanggal BETWEEN '".$rawbegin."' and '".$rawend."' 
    GROUP by pembelian_tanggal");

    foreach($opkredit->result() as $key => $value){
      // search index of date
      $opdate = array_search($value->pembelian_tanggal, $date);
      $kredit[$opdate] = $value->pembelian_total;
    }

    // die();
    // $this->response($optunai->result());

    $oppattern = array(
      "tunai" => $tunai,
      "kredit" => $kredit,
      "categories" => $categories,
    );

    $this->response($oppattern);
  }

  public function statistik_penjualan(){
    $data = varPost();

    if($data['type'] == "tanggal"){
      $begin = new DateTime($data['awal_tanggal']);
      $end = (new DateTime($data['akhir_tanggal']))->modify('+1 day');
      $rawbegin = $data['awal_tanggal'];
      $rawend = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = new DateTime($bulan);
      $end = (new DateTime($bulan))->modify('+1 month');
      $rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
      $rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }

    $interval = DateInterval::createFromDateString('1 Day');
    $period = new DatePeriod($begin, $interval, $end);

    $tunai = array();
    $kredit = array();
    $categories = array();
    $date = array();

    foreach ($period as $dt) {
      // print_r($dt->format("l Y-m-d H:i:s\n").'</br>');
      array_push($tunai, "0");
      array_push($kredit, "0");
      array_push($categories, $dt->format("d M Y"));
      array_push($date, $dt->format("Y-m-d"));
    }

    // die(print_r($period));
    
    $optunai = $this->db->query("SELECT sum(penjualan_total_bayar) as penjualan_total, penjualan_tanggal 
    FROM pos_penjualan pp 
    WHERE penjualan_metode = 'T'
    AND penjualan_tanggal BETWEEN '".$rawbegin."' and '".$rawend."' 
    GROUP by penjualan_tanggal");

    foreach($optunai->result() as $key => $value){
      // search index of date
      $opdate = array_search($value->penjualan_tanggal, $date);
      $tunai[$opdate] = $value->penjualan_total;
    }

    $opkredit = $this->db->query("SELECT sum(penjualan_total_kredit) as penjualan_total, penjualan_tanggal 
    FROM pos_penjualan pp 
    WHERE penjualan_metode = 'K'  
    AND penjualan_tanggal BETWEEN '".$rawbegin."' and '".$rawend."'
    GROUP by penjualan_tanggal");

    foreach($opkredit->result() as $key => $value){
      // search index of date
      $opdate = array_search($value->penjualan_tanggal, $date);
      $kredit[$opdate] = $value->penjualan_total;
    }

    // die();
    // $this->response($optunai->result());

    $oppattern = array(
      "tunai" => $tunai,
      "kredit" => $kredit,
      "categories" => $categories,
    );

    $this->response($oppattern);
  }

  function pendapatan_bersih(){
    $data = varPost();

    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }

    $op = $this->db->query("SELECT
    sum(pb.barang_harga * penjualan_detail_qty_barang) - sum(pb.barang_harga_beli * penjualan_detail_qty_barang) as pendapatan_bersih
    FROM pos_penjualan_detail ppd 
    INNER JOIN pos_barang pb ON ppd.penjualan_detail_barang_id = pb.barang_id
    WHERE penjualan_detail_tanggal BETWEEN '".$begin."' and '".$end."'");

    $oprow = $op->row();
    $begin = new DateTime($begin);
    $end = new DateTime($end);
    $oprow->range_date = date_format($begin, 'd M Y').' - '.date_format($end, 'd M Y');
    // print_r($oprow);die();

    $this->response($oprow);
  }

  function total_hutang(){
    $data = varPost();

    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }
    
    $op = $this->db->query("SELECT sum(pembelian_bayar_grand_total) as total_pembelian, 
    sum(pembelian_bayar_jumlah) as total_pembayaran, 
    sum(pembelian_bayar_jumlah) / sum(pembelian_bayar_grand_total) * 100 as persentage
    FROM pos_pembelian_barang ppb 
    WHERE pembelian_bayar_opsi = 'K' AND pembelian_deleted_at IS NULL AND pembelian_tanggal BETWEEN '".$begin."' and '".$end."'");
    $this->response($op->row());
  }

  function total_piutang(){
    $data = varPost();

    if($data['type'] == "tanggal"){
      $begin = $data['awal_tanggal'];
      $end = $data['akhir_tanggal'];
    }else if($data['type'] == "bulan"){
      $bulan = $data['bulan'].'-01';
      $begin = date_format(new DateTime($bulan), 'Y-m-d');
      $end = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }

    $op = $this->db->query("SELECT sum(penjualan_total_kredit) as total_penjualan, 
    sum(penjualan_total_bayar) as total_pembayaran, 
    sum(penjualan_total_bayar) / sum(penjualan_total_kredit) * 100 as persentage 
    FROM pos_penjualan pp 
    WHERE penjualan_metode = 'K' AND penjualan_tanggal BETWEEN '".$begin."' and '".$end."'
    ");
    $this->response($op->row());
  }
}
?>