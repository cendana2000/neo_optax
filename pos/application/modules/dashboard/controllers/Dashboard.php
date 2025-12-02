<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Khill\Duration\Duration;

class Dashboard extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model(array());
  }

  public function get_all_penjualan_pembelian()
  {
    $data = varGet();

    if ($data['type'] == "tanggal") {
      $begin = new DateTime($data['awal_tanggal']);
      $end = (new DateTime($data['akhir_tanggal']))->modify('+1 day');
      $rawbegin = $data['awal_tanggal'];
      $rawend = $data['akhir_tanggal'];
    } else if ($data['type'] == "bulan") {
      $bulan = $data['bulan'] . '-01';
      $begin = new DateTime($bulan);
      $end = (new DateTime($bulan))->modify('+1 month');
      $rawbegin = date_format(new DateTime($bulan), 'Y-m-d');
      $rawend = date_format((new DateTime($bulan))->modify('+1 month')->modify('-1 day'), 'Y-m-d');
    }

    $interval = DateInterval::createFromDateString('1 Day');
    $period = new DatePeriod($begin, $interval, $end);

    /*
     * PEMBAYARAN
     * widget: $total_pembelian(untuk widget total pembelian barang), $total_pembelian_hutang, $total_pembayaran_hutang & persentage_hutang (untuk widget total hutang)
     * statistik: $pembelian_tunai, $pembelian_hutang
     */

    // statistik pembelian & pembayaran
    $pembelian_tunai = array();
    $pembelian_hutang = array();
    $penjualan_tunai = array();
    $penjualan_piutang = array();
    $categories = array();

    foreach ($period as $dt) {
      // print_r($dt->format("l Y-m-d H:i:s\n").'</br>');
      array_push($pembelian_tunai, "0");
      array_push($pembelian_hutang, "0");
      array_push($penjualan_tunai, "0");
      array_push($penjualan_piutang, "0");
      array_push($categories, $dt->format("d M Y"));
    }

    // QUERY 1
    $oppembelian = $this->db->query("SELECT SUM(COALESCE(pembelian_total, 0)) as pembelian_total, SUM(COALESCE(pembelian_bayar_jumlah, 0)) as total_pembayaran, pembelian_tanggal, pembelian_bayar_opsi 
    FROM pos_pembelian_barang pp 
    WHERE pembelian_tanggal BETWEEN '" . $rawbegin . "' and '" . $rawend . "' 
    AND pembelian_deleted_at IS NULL
    GROUP BY pembelian_tanggal, pembelian_bayar_opsi
    ORDER BY pembelian_tanggal ASC, pembelian_bayar_opsi ASC");

    // widget total pembelian barang & total hutang
    $total_pembelian = 0;
    $total_pembelian_hutang = 0;
    $total_pembayaran_hutang = 0;

    foreach ($oppembelian->result() as $key => $val) {
      $total_pembelian += $val->pembelian_total;
      if ($val->pembelian_bayar_opsi == "K") {
        $total_pembelian_hutang += $val->pembelian_total;
        $total_pembayaran_hutang += $val->total_pembayaran;
        $opdate = array_search(date_format(new DateTime($val->pembelian_tanggal), 'd M Y'), $categories);
        $pembelian_hutang[$opdate] += $val->pembelian_total;
      } else if ($val->pembelian_bayar_opsi == "T") {
        $opdate = array_search(date_format(new DateTime($val->pembelian_tanggal), 'd M Y'), $categories);
        $pembelian_tunai[$opdate] += $val->pembelian_total;
      }
    }

    // widget total hutang
    if ($total_pembelian_hutang != 0) {
      $persentage_hutang = ($total_pembayaran_hutang / $total_pembelian_hutang) * 100;
    } else {
      $persentage_hutang = 0;
    }

    $total_current_pembelian_hutang = $total_pembelian_hutang - $total_pembayaran_hutang;
    /*
     * PENJUALAN
     * widget: $total_penjualan(untuk widget total penjualan barang), $total_penjualan_piutang, $total_pembayaran_piutang & persentage_piutang (untuk widget total piutang)
     * statistik: $penjualan_tunai, $penjualan_piutang
     */

    // QUERY 2
    $oppenjualan = $this->db->query("SELECT SUM(COALESCE(penjualan_total_grand,0) - COALESCE(penjualan_total_retur,0)) as penjualan_total, SUM(COALESCE(penjualan_total_bayar, 0)) as total_pembayaran, penjualan_tanggal::date, penjualan_metode 
    FROM pos_penjualan pp
    WHERE penjualan_tanggal::date BETWEEN '" . $rawbegin . "' and '" . $rawend . "'
    AND penjualan_status_aktif IS NULL
    GROUP BY penjualan_tanggal::date, penjualan_metode
    ORDER BY penjualan_tanggal::date ASC, penjualan_metode ASC");
    // print_r('<pre>');print_r($oppenjualan->result());print_r('</pre>');
    // print_r('<pre>');print_r($this->db->last_query());print_r('</pre>');exit;

    // widget total penjualan barang & total piutang
    $total_penjualan = 0;
    $total_penjualan_piutang = 0;
    $total_pembayaran_piutang = 0;

    foreach ($oppenjualan->result() as $key => $val) {
      $total_penjualan += $val->penjualan_total;
      if ($val->penjualan_metode == "K") {
        $total_penjualan_piutang += $val->penjualan_total;
        $total_pembayaran_piutang += $val->total_pembayaran;
        $opdate = array_search(date_format(new DateTime($val->penjualan_tanggal), 'd M Y'), $categories);
        $penjualan_piutang[$opdate] += $val->penjualan_total;
      } else if ($val->penjualan_metode == "B" || $val->penjualan_metode == "T") {
        $opdate = array_search(date_format(new DateTime($val->penjualan_tanggal), 'd M Y'), $categories);
        $penjualan_tunai[$opdate] += $val->penjualan_total;
      }
    }

    // widget total piutang
    if ($total_penjualan_piutang != 0) {
      $persentage_piutang = ($total_pembayaran_piutang / $total_penjualan_piutang) * 100;
    } else {
      $persentage_piutang = 0;
    }

    $total_current_penjualan_piutang = $total_penjualan_piutang - $total_pembayaran_piutang;

    // PENDAPATAN BERSIH
    $pendapatan_bersih = $total_penjualan - $total_pembelian;
    $pendapatan_bersih_date = date_format($begin, 'd M Y') . ' - ' . date_format($end, 'd M Y');

    // QUERY 3
    $opstok = $this->db->query("SELECT SUM(COALESCE(barang_stok, 0)) as total_stok_barang 
    FROM pos_barang
    WHERE barang_deleted_at IS NULL");

    // QUERY 4
    // $opterlaris = $this->db->query("SELECT SUM(COALESCE(kartu_stok_keluar, 0)) as total_kartu_stok_keluar,
    // pb.barang_harga, 
    // pb.barang_nama,
    // pb.barang_thumbnail 
    // FROM pos_kartu_stok
    // LEFT JOIN pos_barang pb ON kartu_barang_id = pb.barang_id 
    // LEFT JOIN pos_penjualan pp ON kartu_transaksi_kode = pp.penjualan_kode
    // WHERE kartu_transaksi = 'Penjualan'
    // AND kartu_created_at BETWEEN '" . $rawbegin . " 00:00:00' and '" . $rawend . " 23:59:59'
    // AND pb.barang_deleted_at IS NULL
    // AND kartu_stok_keluar != 0
    // AND pp.penjualan_status_aktif IS NULL
    // GROUP BY kartu_barang_id, pb.barang_harga, pb.barang_nama, pb.barang_thumbnail
    // ORDER BY total_kartu_stok_keluar DESC
    // LIMIT 10");

    /*
     *  All barang (Stok, Non stok, and Rental) 
     */
    $opterlaris = $this->db->query("SELECT 
      sum(ppd.penjualan_detail_qty) total_kartu_stok_keluar, 
      pb.barang_harga, 
        pb.barang_nama,
        pb.barang_thumbnail
    from pos_penjualan_detail ppd 
    left join pos_barang pb on ppd.penjualan_detail_barang_id = pb.barang_id
    where ppd.penjualan_detail_tanggal between '" . $rawbegin . " 00:00:00' and '" . $rawend . " 23:59:59'
    AND pb.barang_deleted_at IS NULL
    group by ppd.penjualan_detail_barang_id, pb.barang_harga, pb.barang_nama, pb.barang_thumbnail
    order by total_kartu_stok_keluar desc
    limit 10");

    $this->response(array(
      "statistik_pembelian" => (object) [
        "tunai" => $pembelian_tunai,
        "hutang" => $pembelian_hutang
      ],
      "statistik_penjualan" => (object) [
        "tunai" => $penjualan_tunai,
        "hutang" => $penjualan_piutang,
      ],
      "statistik_categories" => $categories,
      "total_pembelian_barang" => $total_pembelian,
      "total_penjualan_barang" => $total_penjualan,
      "total_hutang" => (object)[
        // "total" => $total_pembelian_hutang,
        "total" => $total_current_pembelian_hutang,
        "terbayar" => $total_pembayaran_hutang,
        "persentage" => $persentage_hutang,
      ],
      "total_piutang" => (object)[
        // "total" => $total_penjualan_piutang,
        "total" => $total_current_penjualan_piutang,
        "terbayar" => $total_pembayaran_piutang,
        "persentage" => $persentage_piutang,
      ],
      "pendapatan_bersih" => (object)[
        "total" => $pendapatan_bersih,
        "range_date" => $pendapatan_bersih_date,
      ],
      "total_stok" => $opstok->row()->total_stok_barang,
      "barang_terlaris" => $opterlaris->result(),
    ));
  }
}
