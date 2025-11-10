<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sptpd extends Base_Controller
{

  private $npwpd_login_user;

  public function __construct()
  {
    parent::__construct();

    //Do your magic here
    $this->npwpd_login_user = $this->session->userdata()['wajibpajak_npwpd'];
    $this->load->model(array(
      'SptpdModel'         => 'sptpd',
      'realisasipajak/RealisasipajakparentfilterModelV7' => 'realisasipajakfilter',
			'conf/NotificationModel' 		=> 'Notification',
    ));
  }

  public function loadVerifikasi()
  {
    $where = [
      'sptpd_status is NULL' => null,
      'sptpd_npwpd' => $this->npwpd_login_user
    ];

    $data = $this->select_dt(varPost(), 'sptpd', 'table', false, $where);
    $this->response(
      $data
    );
  }

  public function loadVerifikasiSetuju()
  {
    $where = [
      "sptpd_status" => '1',
      'sptpd_npwpd' => $this->npwpd_login_user
    ];

    $data = $this->select_dt(varPost(), 'sptpd', 'table', false, $where);
    $this->response(
      $data
    );
  }

  public function loadVerifikasiTolak()
  {
    $where = [
      'sptpd_status' => '0',
      'sptpd_npwpd' => $this->npwpd_login_user
    ];

    $data = $this->select_dt(varPost(), 'sptpd', 'table', false, $where);
    $this->response(
      $data
    );
  }

  public function loadVerifikasiPembayaran()
  {
    $where = [
      'sptpd_status' => '1',
      'sptpd_status_pembayaran' => null,
      'sptpd_npwpd' => $this->npwpd_login_user
    ];

    $data = $this->select_dt(varPost(), 'sptpd', 'table', false, $where);
    $this->response(
      $data
    );
  }

  public function loadVerifikasiAll()
  {
    $where = [
      'sptpd_npwpd' => $this->npwpd_login_user
    ];

    $data = $this->select_dt(varPost(), 'sptpd', 'table', false, $where);
    $this->response(
      $data
    );
  }

  public function store()
  {
    $data = varPost();
        $session = $this->session->userdata();
    $rawDateBegin = '';
    $rawDateEnd = '';
    $bulan_30_hari = array("04", "06", "09", "11");
    $tahun_bulan = explode('-', $data['sptpd_bulan_tahun_pajak']);
    $data['sptpd_bulan_pajak'] = $tahun_bulan[1];
    $data['sptpd_tahun_pajak'] = $tahun_bulan[0];
    $rawDateBegin = $data['sptpd_bulan_tahun_pajak'] . '-01';

    if ($tahun_bulan[1] == '02') {
      if ($tahun_bulan[1] % 4 == 0) {
        $rawDateEnd = $data['sptpd_bulan_tahun_pajak'] . '-29';
      } else {
        $rawDateEnd = $data['sptpd_bulan_tahun_pajak'] . '-28';
      }
    } else if (in_array($tahun_bulan[1], $bulan_30_hari)) {
      $rawDateEnd = $data['sptpd_bulan_tahun_pajak'] . '-30';
    } else {
      $rawDateEnd = $data['sptpd_bulan_tahun_pajak'] . '-31';
    }

    $data['sptpd_created_at'] = date('Y-m-d h:i:s');
    $data_realisasi = $this->db->query('SELECT sum(realisasi_total) as nominal_etax_omzet, sum(realisasi_pajak) as nominal_etax_pajak FROM "pajak_realisasi" WHERE realisasi_tanggal BETWEEN \'' . $rawDateBegin . '\' AND \'' . $rawDateEnd .  '\' AND realisasi_wajibpajak_npwpd = \'' . $data['sptpd_npwpd'] . '\'')->result_array();
    $data['sptpd_etax_omzet'] = $data_realisasi[0]['nominal_etax_omzet'];
    $data['sptpd_etax_pajak'] = $data_realisasi[0]['nominal_etax_pajak'];
    // $sptpd_id = $client->formattedId($this->sptpd->get_table(), 13);
    // $this->response($this->sptpd->insert($sptpd_id, $data));
        $this->Notification->sendNotif("Permohonan Lapor SPTPD", "{$session['wajibpajak_nama']} Mengajukan Pelaporan SPTPD Periode {$data['sptpd_bulan_tahun_pajak']}", 'PERMOHONAN', 'SPTPD', null, 'PEMDA');
    $this->response($this->sptpd->insert(substr(gen_uuid($this->sptpd->get_table()), 0, 13), $data));
  }

  public function detail()
  {
    $where = [];
    $where['sptpd_id'] = varPost('sptpd_id');
    $where['sptpd_npwpd'] = $this->npwpd_login_user;
    $data = $this->sptpd->read($where);
    // $data = $this->select_dt(varPost(), 'sptpd', 'table', true, $where);
    $this->response($data);
  }

  public function get_omzet_ajax()
  {
    $data = varPost();

    $where = [
      'realisasi_parent_npwpd' => $data['sptpd_npwpd'],
      'realisasi_parent_tanggal' => $data['sptpd_tanggal']
    ];

    $data_ajax = $this->realisasipajakfilter->read($where);

    return $this->response($data_ajax);
  }

  public function print_sptpd()
  {
    $data = varPost();
    $sptpd = $this->db->query('SELECT * FROM v_pajak_unduh_sptpd WHERE sptpd_id = \'' . $data['id'] . '\'')->result()[0];
    $html = ''; //siapkan html untuk menjadi dasar print
    switch ($sptpd->parent_jenis_nama) {
      case 'PAJAK HOTEL':
        $html = $this->templateHotel($sptpd);
        break;
      case 'PAJAK RESTORAN':
        $html = $this->templateResto($sptpd);
        break;
      case 'PAJAK HIBURAN':
        $html = $this->templateHiburan($sptpd);
        break;
      case 'PAJAK PARKIR':
        $html = $this->templateParkir($sptpd);
        break;
      default:
        return $this->response([
          'title' => 'Gagal',
          'message' => 'Gagal mengidentifikasi jenis pajak',
          'success' => false
        ]);
        break;
    }

    createPdf(array(
      'data'          => $html,
      'json'          => true,
      'paper_size'    => 'A4',
      'file_name'     => 'Laporan Realisasi Pajak',
      'title'         => 'Laporan Realisasi Pajak',
      'orientation' => 'P',
      'margin'        => '10 5 10 5',
    ));
  }

  function templateHiburan($sptpd)
  {
    $bulan = $sptpd->sptpd_bulan_pajak;
    $tahun = $sptpd->sptpd_tahun_pajak;
    $tanggal_bawah = '01-' . $bulan . '-' . $tahun;
    $tanggal_atas = '';

    $monthsWith31Days = [
      "01", // Januari
      "03", // Maret
      "05", // Mei
      "07", // Juli
      "08", // Agustus
      "10", // Oktober
      "12"  // Desember
    ];

    if (in_array($bulan, $monthsWith31Days)) {
      $tanggal_atas = '31-' . $bulan . '-' . $tahun;
    } else if ($bulan == '02') {
      if ($tahun % 4 == 0) {
        $tanggal_atas = '29-' . $bulan . '-' . $tahun;
      } else {
        $tanggal_atas = '28-' . $bulan . '-' . $tahun;
      }
    } else {
      $tanggal_atas = '30-' . $bulan . '-' . $tahun;
    }

    return '
        <style>
            table {
              border-collapse: collapse;
              width: 100%;
              border: 1px solid; /* Setebal 1px */
            }
      
            th,
            td {
              border: 1px solid; /* Setebal 1px */
              padding: 8px;
              text-align: left;
            }
          </style>
          <table style="border-color: #000000">
            <tr>
              <td style="text-align: center;" colspan="7">
                <strong>
                  PEMERINTAH KOTA MALANG <br />
                  BADAN PELAYANAN PAJAK DAERAH <br />
                  Perkantoran Terpadu Pemerintah Kota Malang <br />
                  Jl. Mayjend Sungkono Gedung B lantai 1 Telp. (0341) 751532 <br />
                  Kel. Arjowinangun Kode Pos 65132
                </strong>
              </td>
              <td colspan="5" style="width: 40%">
                <ul style="list-style-type: none; margin-left: -30px">
                  <li>No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '</li>
                  <li>Masa Pajak : ' . $sptpd->sptpd_bulan_pajak . '-' . $sptpd->sptpd_tahun_pajak . '</li>
                  <li>Tahun Pajak : ' . $sptpd->sptpd_tahun_pajak . '</li>
                </ul>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12" style="text-align: center;">
                <strong>
                  SPTPD <br />
                  (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
                  PAJAK HIBURAN <br />
                </strong>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td style="padding: 16px;" colspan="6">
                <div style="margin-bottom: 25px">
                  <span>NPWPD (Nomor Pokok Wajib Pajak):*</span><br />
                </div>
                <div style="margin-bottom: 15px">
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                </div>
              </td>
              <td colspan="6" style="">
                <span style="margin-left: 20px">kepada</span><br />
                yth. Kepala Badan Pendapatan <br />
                Daerah Kota Malang <br />
                di <br />
                Malang
              </td>
            </tr>
            <!-- batas -->
            <tr style="">
              <td colspan="12">
                PERHATIAN : <br />
                <ol>
                  <li>
                    Harap diisi dalam rangkap 2 (dua) ditulis dengan HURUF KAPITAL;
                  </li>
                  <li>
                    Beri nomor pada kotak
                    <span
                      style="
                        display: inline-block;
                        width: 5px;
                        height: 5px;
                        border: 0.5px solid black;
                      "
                    >
                    </span>
                    yang tersedia untuk jawaban yang diberikan;
                  </li>
                  <li>
                    Setelah diisi dan ditandatangani wajib diserahkan kembali kepada
                    Badan Pelayanan Pajak Daerah Kota Malang paling lambat 10
                    (sepuluh) hari setelah berakhirnya masa pajak;
                  </li>
                  <li>
                    Keterlambatan penyerahan dari tanggal tersebut di atas, maka akan
                    dilakukan Penetapan Secara Jabatan (Official Assesment).
                  </li>
                </ol>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12" style="text-align: center;">
                    <strong> DIISI OLEH PENGUSAHA HIBURAN </strong>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12">
              <!-- isi content -->
                <table style="border:none; text-align: left; vertical-align: top;">
                  <tr>
                    <td style="border:none;">
                      1
                    </td>
                    <td style="border:none;">
                      <p>Hiburan yang diselenggarakan</p>
                      <span
                        style="
                          display: inline-block;
                          width: 5px;
                          height: 5px;
                          border: 0.5px solid black;
                          color: white;
                        "
                      >aa
                      </span
                      ><span
                        style="
                          display: inline-block;
                          width: 5px;
                          height: 5px;
                          border: 0.5px solid black;
                          color: white;
                        "
                      >aa
                      </span>
                    </td>
                    <td style="border:none;">
                      01. Tontonan film; <br />
                      02. Pagelaran kesenian, musik, tari, dan/atau busana;<br />
                      03. Kontes kecantikan, binaraga, dan sejenisnya;<br />
                      04. Pameran;<br />
                      05. Karaoke;<br />
                      06. Diskotik, klab malam, bar, dan sejenisnya;<br />
                      07. Sirkus, akrobat, dan sulap;<br />
                      08. Billyar;<br />
                      09. Golf ;<br />
                      10. Bolling;<br />
                      11. Pacuan kuda, kendaraan bermotor, dan permainan
                      ketangkasan;<br />
                      12. Panti pijat, refleksi, mandi uap/spa, dan pusat kebugaran
                      (fitness center), dan sejenisnya;<br />
                      13. Pertandingan olah raga;<br />
                      14. Hiburan kesenian rakyat/tradisional;<br />
                      15. Pijat tradisional.
                    </td>
                  </tr>
                </table>

              <!-- /isi content -->
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12">
                <table style="border:none; text-align: left; vertical-align: top;">
                  <tr>
                    <td style="border:none; width: 3%;">
                      2
                    </td>
                    <td style="border:none; width: 30%;">
                      <p>Harga tanda masuk yang berlaku</p>
                      <ul>
                        <li> Kelas .................... Rp. ............................</li>
                        <li> Kelas .................... Rp. ............................</li>
                        <li> Kelas .................... Rp. ............................</li>
                      </ul>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none;">
                      3
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Jumlah pertunjukan rata-rata pada hari biasa
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. kali
                          </td>
                        </tr>
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                          Jumlah pertunjukan rata-rata pada hari libur/minggu
                          (khusus untuk pertunjukan Film, Kesenian dan
                          Sejenisnya, Pagelaran Musik dan Tari).
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. kali
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none;">
                      4
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Jumlah pertunjukan rata-rata pada hari biasa
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. kali
                          </td>
                        </tr>
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                          Jumlah pertunjukan rata-rata pada hari libur/minggu
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. kali
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none;">
                      5
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Jumlah Meja/Mesin (Khusus untuk Billyar, Permainan Ketangkasan)
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. Buah
                          </td>
                        </tr>
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Jumlah kamar/ruangan (khusus untuk Panti Pijat, Mandi Uap/Spa, Karaoke)
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. buah
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      6
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Apakah perusahaan menyediakan karcis bebas (free) kepada orang-orang tertentu:
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            <span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span
                            >Ya<br><span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span>Tidak
                          </td>
                        </tr>
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Jika YA berapa jumlah yang beredar
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            : ................. buah
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      7
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                          Penjualan Karcis dengan mesin tiket:
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            <span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span
                            >Ya<br><span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span>Tidak
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      8
                    </td>
                    <td style="border:none;">
                      <table style="border:none; text-align: left; vertical-align: top;">
                        <tr>
                          <td style="border:none; padding:0%; width: 70%;">
                            Melaksanakan Pembukuan/Pencatatan:
                          </td>
                          <td style="border:none; padding:0%; width: 30%;">
                            <span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span
                            >Ya<br><span
                              style="
                                display: inline-block;
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color: white;
                              "
                            >aa
                            </span>Tidak
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      9
                    </td>
                    <td style="border:none; width: 30%;">
                      <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sebelumnya (akumulasi dari awal Masa Pajak dalam
                      Tahun Pajak Tertentu) :</p>
                      <table style="border:none;">
                        <tr>
                          <td style="border:none;">a</td>
                          <td style="border:none;">Masa Pajak</td>
                          <td style="border:none;">: Tgl. ................... s.d. Tgl. ......................</td>
                        </tr>
                        <tr>
                          <td style="border:none;">b</td>
                          <td style="border:none;">Dasar Pengenaan (Jumlah
                          pembayaran yang diterima)</td>
                          <td style="border:none;">: Rp. ....................</td>
                        </tr>
                        <tr>
                          <td style="border:none;">c</td>
                          <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                          <td style="border:none;">: ...................... %</td>
                        </tr>
                        <tr>
                          <td style="border:none;">d</td>
                          <td style="border:none;">Pajak Terutang ( b x c ) </td>
                          <td style="border:none;">: Rp. ....................</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      10
                    </td>
                    <td style="border:none; width: 30%;">
                      <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sekarang ( lampirkan foto copy dokumen) :
                      </p>
                      <table style="border:none;">
                        <tr>
                          <td style="border:none;">a</td>
                          <td style="border:none;">Masa Pajak</td>
                          <td style="border:none;">: Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '</td>
                        </tr>
                        <tr>
                          <td style="border:none;">b</td>
                          <td style="border:none;">Dasar Pengenaan (Jumlah
                          pembayaran yang diterima)</td>
                          <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '</td>
                        </tr>
                        <tr>
                          <td style="border:none;">c</td>
                          <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                          <td style="border:none;">: ' . $sptpd->jenis_tarif . ' %</td>
                        </tr>
                        <tr>
                          <td style="border:none;">d</td>
                          <td style="border:none;">Pajak Terutang ( b x c ) </td>
                          <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet * ($sptpd->jenis_tarif / 100)) . '</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:none; width: 3%;">
                      11
                    </td>
                    <td style="border:none; width: 30%;">
                      <table style="border:none;">
                        <tr>
                          <td style="border:none;">a</td>
                          <td style="border:none;">Masa Pajak</td>
                          <td style="border:none;">: Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '</td>
                        </tr>
                        <tr>
                          <td style="border:none;">b</td>
                          <td style="border:none;">Dasar Pengenaan (Jumlah
                          pembayaran yang diterima)</td>
                          <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12" style="text-align: center;">
                <table style="border: none; margin-bottom: 20px;">
                  <tr>
                    <td style="border: none; text-align: center;">
                     <strong>PERNYATAAN</strong>
                    </td>
                  </tr>
                  <tr>
                    <td style="border: none; text-align: justify;">
                      Dengan menyadari sepenuhnya akan segala akibat termasuk
                      sanksi–sanksi sesuai dengan ketentuan peraturan perundang-undangan,
                      saya atau yang saya beri kuasa menyatakan bahwa apa yang telah saya
                      beritahukan tersebut di atas beserta lampiran-lampirannya adalah
                      benar, lengkap dan jelas
                    </td>
                  </tr>
                </table>
                <table style="border: none">
                  <tr>
                    <td style="border: none; width: 35%"></td>
                    <td style="border: none; width: 35%"></td>
                    <td style="border: none; width: 30%; text-align: center;">
                      <div>
                        <span>
                          ............................. Tahun ............. <br />
                          Wajib Pajak,<br />
                          <br />
                          <br />
                          <br />
                          ' . $sptpd->wajibpajak_nama_penanggungjawab . ' <br />
                          Nama Jelas
                        </span>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="12" style="text-align: center">
                <div>
                  <strong> DIISI OLEH PETUGAS PENERIMA BPPD </strong>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="12">
                <table style="border: none">
                  <tr style="border: none">
                    <td style="border: none; width: 20%">Diterima Tanggal</td>
                    <td style="border: none">:</td>
                  </tr>
                  <tr style="border: none">
                    <td style="border: none; width: 20%">Nama Petugas</td>
                    <td style="border: none">:</td>
                  </tr>
                  <tr style="border: none">
                    <td style="border: none; width: 20%">NIP</td>
                    <td style="border: none">:</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
            </tr>
          </table>
          <table style="border: none;">
            <tr>
              <td style="border: none; text-align: center;">
                <p style="margin-bottom: 15px">
                  MODEL DPD-02................................potong
                  disini........................................................
                </p>
              </td>
            </tr>
          </table>
          <table>
            <tr>
              <td>
                <table style="border:none;">
                  <tr>
                    <td style="border:none; text-align: right;">
                      No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '
                    </td>
                  </tr>
                </table>
                <br />
                <br />
                <br />
                <div>
                  <center>
                    <strong style="text-decoration: underline"> TANDA TERIMA </strong>
                  </center>
                </div>
                <div>
                  <table style="border: none">
                    <tr style="border: none">
                      <td style="border: none; width: 17%">NPWPD</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->sptpd_npwpd . '
                      </td>
                    </tr>
                    <tr style="border: none">
                      <td style="border: none; width: 17%">Nama</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->wajibpajak_nama_penanggungjawab . '
                      </td>
                    </tr>
                    <tr style="border: none">
                      <td style="border: none; width: 17%">Alamat</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->wajibpajak_alamat . '
                      </td>
                    </tr>
                  </table>
                </div>
                <div>
                  <table style="border: none">
                    <tr style="border: none">
                      <td style="border: none; width: 35%"></td>
                      <td style="border: none; width: 35%"></td>
                      <td style="border: none; width: 30%; text-align: center;">
                        <div>
                          <span>
                            ............................. Tahun ............. <br />
                            Yang Menerima,<br />
                            <br />
                            <br />
                            <br />
                            ....................................... <br />
                            Nama Jelas
                          </span>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>';
  }

  function templateHotel($sptpd)
  {
    $bulan = $sptpd->sptpd_bulan_pajak;
    $tahun = $sptpd->sptpd_tahun_pajak;
    $tanggal_bawah = '01-' . $bulan . '-' . $tahun;
    $tanggal_atas = '';

    $monthsWith31Days = [
      "01", // Januari
      "03", // Maret
      "05", // Mei
      "07", // Juli
      "08", // Agustus
      "10", // Oktober
      "12"  // Desember
    ];

    if (in_array($bulan, $monthsWith31Days)) {
      $tanggal_atas = '31-' . $bulan . '-' . $tahun;
    } else if ($bulan == '02') {
      if ($tahun % 4 == 0) {
        $tanggal_atas = '29-' . $bulan . '-' . $tahun;
      } else {
        $tanggal_atas = '28-' . $bulan . '-' . $tahun;
      }
    } else {
      $tanggal_atas = '30-' . $bulan . '-' . $tahun;
    }

    return '
        <style>
            table {
              border-collapse: collapse;
              width: 100%;
              border: 1px solid; /* Setebal 1px */
            }
      
            th,
            td {
              border: 1px solid; /* Setebal 1px */
              padding: 8px;
              text-align: left;
            }
          </style>
          <table style="border-color: #000000">
            <tr>
              <td style="text-align: center;" colspan="7">
                <strong>
                  PEMERINTAH KOTA MALANG <br />
                  BADAN PELAYANAN PAJAK DAERAH <br />
                  Perkantoran Terpadu Pemerintah Kota Malang <br />
                  Jl. Mayjend Sungkono Gedung B lantai 1 Telp. (0341) 751532 <br />
                  Kel. Arjowinangun Kode Pos 65132
                </strong>
              </td>
              <td colspan="5" style="width: 40%">
                <ul style="list-style-type: none; margin-left: -30px">
                  <li>No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '</li>
                  <li>Masa Pajak : ' . $sptpd->sptpd_bulan_pajak . '-' . $sptpd->sptpd_tahun_pajak . '</li>
                  <li>Tahun Pajak : ' . $sptpd->sptpd_tahun_pajak . '</li>
                </ul>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12" style="text-align: center;">
                <strong>
                  SPTPD <br />
                  (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
                  PAJAK HOTEL <br />
                </strong>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td style="padding: 16px;" colspan="6">
                <div style="margin-bottom: 25px">
                  <span>NPWPD (Nomor Pokok Wajib Pajak):*</span><br />
                </div>
                <div style="margin-bottom: 15px">
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid white;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                  <span
                    style="
                      border: 3px solid black;
                      padding: 10px 7px;
                      text-align: left;
                    "
                  >
                  </span>
                </div>
              </td>
              <td colspan="6" style="">
                <span style="margin-left: 20px">kepada</span><br />
                yth. Kepala Badan Pendapatan <br />
                Daerah Kota Malang <br />
                di <br />
                Malang
              </td>
            </tr>
            <!-- batas -->
            <tr style="">
              <td colspan="12">
                PERHATIAN : <br />
                <ol>
                  <li>
                    Harap diisi dalam rangkap 2 (dua) ditulis dengan HURUF KAPITAL;
                  </li>
                  <li>
                    Beri nomor pada kotak
                    <span
                      style="
                        display: inline-block;
                        width: 5px;
                        height: 5px;
                        border: 0.5px solid black;
                      "
                    >
                    </span>
                    yang tersedia untuk jawaban yang diberikan;
                  </li>
                  <li>
                    Setelah diisi dan ditandatangani wajib diserahkan kembali kepada
                    Badan Pelayanan Pajak Daerah Kota Malang paling lambat 10
                    (sepuluh) hari setelah berakhirnya masa pajak;
                  </li>
                  <li>
                    Keterlambatan penyerahan dari tanggal tersebut di atas, maka akan
                    dilakukan Penetapan Secara Jabatan (Official Assesment).
                  </li>
                </ol>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12" style="text-align: center;">
                    <strong> DIISI OLEH WAJIB PAJAK HOTEL </strong>
              </td>
            </tr>
            <!-- batas -->
            <tr>
              <td colspan="12">
                <div>
                  <ol>
                    <li>
                    <span style="margin-bottom: 15px">Golongan Hotel</span>
                        <span
                          style="
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                            color: white;
                          "
                        >
                        aa
                        </span>
                        <span
                          style="
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                            color: white;
                          "
                        >
                        aa
                        </span>
                      <div style="margin-top: 10px">
                        <table style="border: none">
                          <tr style="border: none">
                              <td style="border: none; width: 20%; text-align: left; vertical-align:top;">
                                01. Bintang Lima <br />
                                02. Bintang Empat <br />
                                03. Bintang Tiga <br />
                                04. Bintang Dua <br />
                                05. Bintang Satu <br />
                              </td>
                              <td style="border: none; width: 80%"; text-align: left; vertical-align:top;>
                                06. Melati Tiga <br />
                                07. Melati Due <br />
                                08. Melati Satu <br />
                                09. Ekonomi <br />
                                10. Rumah Kos lebih 10 kamar <br />
                                11. Lainnya....
                              </td>
                          </tr>
                        </table>
                      </div>
                    </li>
                    <li style="margin-bottom: 10px">
                      <p>Tarif dan jumlah kamar hotel :</p>
                      <table style="margin-left: 10px;">
                        <tr>
                          <th>No.</th>
                          <th>Golongan Kamar</th>
                          <th>Tarif (Rp.)</th>
                          <th>Jumlah Kamar</th>
                          <th>Jumlah Kamar yg. Laku</th>
                        </tr>
                        <tr>
                          <td>1</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </table> <br>
                      <table style="margin-left: 10px; border: none;">
                        <tr>
                          <td style="border: none; text-align: left; vertical-align:top; width: 5%;">1</td>
                          <td style="border: none; text-align: left; vertical-align:top; width: 25%;">
                            <p>Menggunakan Kas Register</p>
                          </td>
                          <td style="border: none; text-align: left; vertical-align:top;">
                            <span
                              style="
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color:white
                              "
                            >aa
                            </span>
                            Ya <br>
                            <span
                              style="
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color:white
                              "
                            >aa
                            </span>
                            Tidak
                          </td>
                        </tr>
                        <tr>
                          <td style="border: none; text-align: left; vertical-align:top; width: 5%;">2</td>
                          <td style="border: none; text-align: left; vertical-align:top; width: 25%;">
                            <p>Mengadakan pembukuan/pencatatan</p>
                          </td>
                          <td style="border: none; text-align: left; vertical-align:top;">
                            <span
                              style="
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color:white
                              "
                            >aa
                            </span>
                            Ya <br>
                            <span
                              style="
                                width: 5px;
                                height: 5px;
                                border: 0.5px solid black;
                                color:white
                              "
                            >aa
                            </span>
                            Tidak
                          </td>
                        </tr>
                      </table>
                    </li>
                    <li>
                      <div style="margin-bottom: 15px">
                        Data Pengenaan Jumlah dan Pajak Terutang untuk masa pajak
                        sebelumnya (akumulasi dari awal masa pajak dalam Tahun Pajak
                        Tertentu) :
                      </div>
                      <div>
                        <table style="border: none">
                          <tr>
                            <td style="border: none; width: 5px">a.</td>
                            <td style="border: none; width: 250px">Masa Pajak</td>
                            <td style="border: none">
                              : Tgl. .......... s.d. Tgl. ..............
                            </td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">b.</td>
                            <td style="border: none; width: 250px">
                              Dasar Pengenaan (jumlah pembayaran yang diterima)
                            </td>
                            <td style="border: none">
                              : Rp. ...........................
                            </td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">c.</td>
                            <td style="border: none; width: 250px">
                              Tarif pajak (sesuai Perda)
                            </td>
                            <td style="border: none">: ................%</td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">d.</td>
                            <td style="border: none; width: 250px">
                              Pajak Terutang (b x c)
                            </td>
                            <td style="border: none">: Rp. ....................</td>
                          </tr>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div style="margin-bottom: 15px">
                        Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sekarang
                        (lampirkan foto copy <br />dokumen) :
                      </div>
                      <div>
                        <table style="border: none">
                          <tr>
                            <td style="border: none; width: 5px">a.</td>
                            <td style="border: none; width: 250px">Masa Pajak</td>
                            <td style="border: none">
                              : Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '
                            </td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">b.</td>
                            <td style="border: none; width: 250px">
                              Dasar Pengenaan (jumlah pembayaran yang diterima)
                            </td>
                            <td style="border: none">
                              : Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '
                            </td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">c.</td>
                            <td style="border: none; width: 250px">
                              Tarif pajak (sesuai Perda)
                            </td>
                            <td style="border: none">: ' . $sptpd->jenis_tarif . '%</td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">d.</td>
                            <td style="border: none; width: 250px">
                              Pajak Terutang (b x c)
                            </td>
                            <td style="border: none">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet * ($sptpd->jenis_tarif / 100)) . '</td>
                          </tr>
                        </table>
                      </div>
                    </li>
                    <li>
                      <div>
                        <table style="border: none">
                          <tr>
                            <td style="border: none; width: 5px">a.</td>
                            <td style="border: none; width: 250px">Masa Pajak</td>
                            <td style="border: none">
                              : Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '
                            </td>
                          </tr>
                          <tr>
                            <td style="border: none; width: 5px">b.</td>
                            <td style="border: none; width: 250px">
                              Dasar Pengenaan (jumlah pembayaran yang diterima)
                            </td>
                            <td style="border: none">
                              : Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '
                            </td>
                          </tr>
                        </table>
                      </div>
                    </li>
                  </ol>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="12" style="text-align: center;">
                <table style="border: none; margin-bottom: 20px;">
                  <tr>
                    <td style="border: none; text-align: center;">
                     <strong>PERNYATAAN</strong>
                    </td>
                  </tr>
                  <tr>
                    <td style="border: none; text-align: justify;">
                      Dengan menyadari sepenuhnya akan segala akibat termasuk
                      sanksi–sanksi sesuai dengan ketentuan peraturan perundang-undangan,
                      saya atau yang saya beri kuasa menyatakan bahwa apa yang telah saya
                      beritahukan tersebut di atas beserta lampiran-lampirannya adalah
                      benar, lengkap dan jelas
                    </td>
                  </tr>
                </table>
                <table style="border: none">
                  <tr>
                    <td style="border: none; width: 35%"></td>
                    <td style="border: none; width: 35%"></td>
                    <td style="border: none; width: 30%; text-align: center;">
                      <div>
                        <span>
                          ............................. Tahun ............. <br />
                          Wajib Pajak,<br />
                          <br />
                          <br />
                          <br />
                          ' . $sptpd->wajibpajak_nama_penanggungjawab . ' <br />
                          Nama Jelas
                        </span>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="12" style="text-align: center">
                <div>
                  <strong> DIISI OLEH PETUGAS PENERIMA BPPD </strong>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="12">
                <table style="border: none">
                  <tr style="border: none">
                    <td style="border: none; width: 20%">Diterima Tanggal</td>
                    <td style="border: none">:</td>
                  </tr>
                  <tr style="border: none">
                    <td style="border: none; width: 20%">Nama Petugas</td>
                    <td style="border: none">:</td>
                  </tr>
                  <tr style="border: none">
                    <td style="border: none; width: 20%">NIP</td>
                    <td style="border: none">:</td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
              <td style="border-color: white"></td>
            </tr>
          </table>
          <table style="border: none;">
            <tr>
              <td style="border: none; text-align: center;">
                <p style="margin-bottom: 15px">
                  MODEL DPD-02................................potong
                  disini........................................................
                </p>
              </td>
            </tr>
          </table>
          <table>
            <tr>
              <td>
                <table style="border:none;">
                  <tr>
                    <td style="border:none; text-align: right;">
                      No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '
                    </td>
                  </tr>
                </table>
                <br />
                <br />
                <br />
                <div>
                  <center>
                    <strong style="text-decoration: underline"> TANDA TERIMA </strong>
                  </center>
                </div>
                <div>
                  <table style="border: none">
                    <tr style="border: none">
                      <td style="border: none; width: 17%">NPWPD</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->sptpd_npwpd . '
                      </td>
                    </tr>
                    <tr style="border: none">
                      <td style="border: none; width: 17%">Nama</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->wajibpajak_nama_penanggungjawab . '
                      </td>
                    </tr>
                    <tr style="border: none">
                      <td style="border: none; width: 17%">Alamat</td>
                      <td style="border: none; text-align: left;">
                        : ' . $sptpd->wajibpajak_alamat . '
                      </td>
                    </tr>
                  </table>
                </div>
                <div>
                  <table style="border: none">
                    <tr style="border: none">
                      <td style="border: none; width: 35%"></td>
                      <td style="border: none; width: 35%"></td>
                      <td style="border: none; width: 30%; text-align: center;">
                        <div>
                          <span>
                            ............................. Tahun ............. <br />
                            Yang Menerima,<br />
                            <br />
                            <br />
                            <br />
                            ....................................... <br />
                            Nama Jelas
                          </span>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>';
  }

  function templateParkir($sptpd)
  {
    $bulan = $sptpd->sptpd_bulan_pajak;
    $tahun = $sptpd->sptpd_tahun_pajak;
    $tanggal_bawah = '01-' . $bulan . '-' . $tahun;
    $tanggal_atas = '';

    $monthsWith31Days = [
      "01", // Januari
      "03", // Maret
      "05", // Mei
      "07", // Juli
      "08", // Agustus
      "10", // Oktober
      "12"  // Desember
    ];

    if (in_array($bulan, $monthsWith31Days)) {
      $tanggal_atas = '31-' . $bulan . '-' . $tahun;
    } else if ($bulan == '02') {
      if ($tahun % 4 == 0) {
        $tanggal_atas = '29-' . $bulan . '-' . $tahun;
      } else {
        $tanggal_atas = '28-' . $bulan . '-' . $tahun;
      }
    } else {
      $tanggal_atas = '30-' . $bulan . '-' . $tahun;
    }

    return '
    <style>
        table {
          border-collapse: collapse;
          width: 100%;
          border: 1px solid; /* Setebal 1px */
        }
  
        th,
        td {
          border: 1px solid; /* Setebal 1px */
          padding: 8px;
          text-align: left;
        }
      </style>
      <table style="border-color: #000000">
        <tr>
          <td style="text-align: center;" colspan="7">
            <strong>
              PEMERINTAH KOTA MALANG <br />
              BADAN PELAYANAN PAJAK DAERAH <br />
              Perkantoran Terpadu Pemerintah Kota Malang <br />
              Jl. Mayjend Sungkono Gedung B lantai 1 Telp. (0341) 751532 <br />
              Kel. Arjowinangun Kode Pos 65132
            </strong>
          </td>
          <td colspan="5" style="width: 40%">
            <ul style="list-style-type: none; margin-left: -30px">
              <li>No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '</li>
              <li>Masa Pajak : ' . $sptpd->sptpd_bulan_pajak . '-' . $sptpd->sptpd_tahun_pajak . '</li>
              <li>Tahun Pajak : ' . $sptpd->sptpd_tahun_pajak . '</li>
            </ul>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
            <strong>
              SPTPD <br />
              (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
              PAJAK PARKIR <br />
            </strong>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td style="padding: 16px;" colspan="6">
            <div style="margin-bottom: 25px">
              <span>NPWPD (Nomor Pokok Wajib Pajak):*</span><br />
            </div>
            <div style="margin-bottom: 15px">
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
            </div>
          </td>
          <td colspan="6" style="">
            <span style="margin-left: 20px">kepada</span><br />
            yth. Kepala Badan Pendapatan <br />
            Daerah Kota Malang <br />
            di <br />
            Malang
          </td>
        </tr>
        <!-- batas -->
        <tr style="">
          <td colspan="12">
            PERHATIAN : <br />
            <ol>
              <li>
                Harap diisi dalam rangkap 2 (dua) ditulis dengan HURUF KAPITAL;
              </li>
              <li>
                Beri nomor pada kotak
                <span
                  style="
                    display: inline-block;
                    width: 5px;
                    height: 5px;
                    border: 0.5px solid black;
                  "
                >
                </span>
                yang tersedia untuk jawaban yang diberikan;
              </li>
              <li>
                Setelah diisi dan ditandatangani wajib diserahkan kembali kepada
                Badan Pelayanan Pajak Daerah Kota Malang paling lambat 10
                (sepuluh) hari setelah berakhirnya masa pajak;
              </li>
              <li>
                Keterlambatan penyerahan dari tanggal tersebut di atas, maka akan
                dilakukan Penetapan Secara Jabatan (Official Assesment).
              </li>
            </ol>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
                <strong> DIISI OLEH WAJIB PAJAK </strong>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12">
            <table style="border:none; text-align: left; vertical-align: top;">
              <tr>
                <td style="border:none; width: 3%;">
                  1
                </td>
                <td style="border:none;">
                  <table style="border:none; text-align: left; vertical-align: top;">
                    <tr>
                      <td style="border:none; padding:0%; width: 20%">
                        Jenis Kendaraan
                      </td>
                      <td style="border:none; padding:0%; width: 80%;">
                        <div style="">
                          <span
                            style="
                              border: 3px solid black;
                              padding: 5px 5px;
                              text-align: left;
                              color:white;
                            "
                          >aa
                          </span> Sepedah Motor
                        </div>
                        <div>
                          <span
                            style="
                              border: 3px solid black;
                              padding: 5px 5px;
                              text-align: left;
                              color:white;
                            "
                          >aa
                          </span> Mobil
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  2
                </td>
                <td style="border:none;">
                  <table style="border:none; text-align: left; vertical-align: top;">
                    <tr>
                      <td style="border:none; padding:0%; width: 20%">
                        Lokasi (Luas Lokasi) 
                      </td>
                      <td style="border:none; padding:0%; width: 80%;">
                        : ________________ m²
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  3
                </td>
                <td style="border:none;">
                  <p>Tarif dan jumlah kendaraan yang parkir :</p>
                  <table style="text-align: left; vertical-align: top;">
                    <tr>
                      <th>No.</th>
                      <th>Jenis Kendaraan</th>
                      <th>Tarif (Rp.)</th>
                      <th>Jumlah Kendaraan</th>
                      <th>Jumlah (C x D)</th>
                    </tr>
                    <tr>
                      <td>A</td>
                      <td>B</td>
                      <td>C</td>
                      <td>D</td>
                      <td>E</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="12" style="text-align: center;">
                <strong> DIISI OLEH WAJIB PAJAK SELF ASSESMENT </strong>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12">
            <table style="border:none; text-align: left; vertical-align: top;">
              <tr>
                <td style="border:none; width: 3%;">
                  1
                </td>
                <td style="border:none;">
                  <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sebelumnya (akumulasi dari awal Masa Pajak dalam
                  Tahun Pajak Tertentu) :</p>
                  <table style="border:none;">
                    <tr>
                      <td style="border:none;">a</td>
                      <td style="border:none;">Masa Pajak</td>
                      <td style="border:none;">: Tgl. ................... s.d. Tgl. ......................</td>
                    </tr>
                    <tr>
                      <td style="border:none;">b</td>
                      <td style="border:none;">Dasar Pengenaan (Jumlah
                      pembayaran yang diterima)</td>
                      <td style="border:none;">: Rp. ....................</td>
                    </tr>
                    <tr>
                      <td style="border:none;">c</td>
                      <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                      <td style="border:none;">: ...................... %</td>
                    </tr>
                    <tr>
                      <td style="border:none;">d</td>
                      <td style="border:none;">Pajak Terutang ( b x c ) </td>
                      <td style="border:none;">: Rp. ....................</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  2
                </td>
                <td style="border:none;">
                  <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sekarang ( lampirkan foto copy dokumen) :
                  </p>
                  <table style="border:none;">
                    <tr>
                      <td style="border:none;">a</td>
                      <td style="border:none;">Masa Pajak</td>
                      <td style="border:none;">: Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '</td>
                    </tr>
                    <tr>
                      <td style="border:none;">b</td>
                      <td style="border:none;">Dasar Pengenaan (Jumlah
                      pembayaran yang diterima)</td>
                      <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '</td>
                    </tr>
                    <tr>
                      <td style="border:none;">c</td>
                      <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                      <td style="border:none;">: ' . $sptpd->jenis_tarif . ' %</td>
                    </tr>
                    <tr>
                      <td style="border:none;">d</td>
                      <td style="border:none;">Pajak Terutang ( b x c ) </td>
                      <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet * ($sptpd->jenis_tarif / 100)) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
            <table style="border: none; margin-bottom: 20px;">
              <tr>
                <td style="border: none; text-align: center;">
                  <strong>PERNYATAAN</strong>
                </td>
              </tr>
              <tr>
                <td style="border: none; text-align: justify;">
                  Dengan menyadari sepenuhnya akan segala akibat termasuk
                  sanksi–sanksi sesuai dengan ketentuan peraturan perundang-undangan,
                  saya atau yang saya beri kuasa menyatakan bahwa apa yang telah saya
                  beritahukan tersebut di atas beserta lampiran-lampirannya adalah
                  benar, lengkap dan jelas
                </td>
              </tr>
            </table>
            <table style="border: none">
              <tr>
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 30%; text-align: center;">
                  <div>
                    <span>
                      ............................. Tahun ............. <br />
                      Wajib Pajak,<br />
                      <br />
                      <br />
                      <br />
                      ' . $sptpd->wajibpajak_nama_penanggungjawab . ' <br />
                      Nama Jelas
                    </span>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="12" style="text-align: center">
            <div>
              <strong> DIISI OLEH PETUGAS PENERIMA BPPD </strong>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="12">
            <table style="border: none">
              <tr style="border: none">
                <td style="border: none; width: 20%">Diterima Tanggal</td>
                <td style="border: none">:</td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 20%">Nama Petugas</td>
                <td style="border: none">:</td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 20%">NIP</td>
                <td style="border: none">:</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
        </tr>
      </table>
      <table style="border: none;">
        <tr>
          <td style="border: none; text-align: center;">
            <p style="margin-bottom: 15px">
              MODEL DPD-02................................potong
              disini........................................................
            </p>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td>
            <table style="border:none;">
              <tr>
                <td style="border:none; text-align: right;">
                  No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '
                </td>
              </tr>
            </table>
            <br />
            <br />
            <br />
            <div>
              <center>
                <strong style="text-decoration: underline"> TANDA TERIMA </strong>
              </center>
            </div>
            <div>
              <table style="border: none">
                <tr style="border: none">
                  <td style="border: none; width: 17%">NPWPD</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->sptpd_npwpd . '
                  </td>
                </tr>
                <tr style="border: none">
                  <td style="border: none; width: 17%">Nama</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->wajibpajak_nama_penanggungjawab . '
                  </td>
                </tr>
                <tr style="border: none">
                  <td style="border: none; width: 17%">Alamat</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->wajibpajak_alamat . '
                  </td>
                </tr>
              </table>
            </div>
            <div>
              <table style="border: none">
                <tr style="border: none">
                  <td style="border: none; width: 35%"></td>
                  <td style="border: none; width: 35%"></td>
                  <td style="border: none; width: 30%; text-align: center;">
                    <div>
                      <span>
                        ............................. Tahun ............. <br />
                        Yang Menerima,<br />
                        <br />
                        <br />
                        <br />
                        ....................................... <br />
                        Nama Jelas
                      </span>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
    ';
  }

  function templateResto($sptpd)
  {
    $bulan = $sptpd->sptpd_bulan_pajak;
    $tahun = $sptpd->sptpd_tahun_pajak;
    $tanggal_bawah = '01-' . $bulan . '-' . $tahun;
    $tanggal_atas = '';

    $monthsWith31Days = [
      "01", // Januari
      "03", // Maret
      "05", // Mei
      "07", // Juli
      "08", // Agustus
      "10", // Oktober
      "12"  // Desember
    ];

    if (in_array($bulan, $monthsWith31Days)) {
      $tanggal_atas = '31-' . $bulan . '-' . $tahun;
    } else if ($bulan == '02') {
      if ($tahun % 4 == 0) {
        $tanggal_atas = '29-' . $bulan . '-' . $tahun;
      } else {
        $tanggal_atas = '28-' . $bulan . '-' . $tahun;
      }
    } else {
      $tanggal_atas = '30-' . $bulan . '-' . $tahun;
    }

    return '
    <style>
        table {
          border-collapse: collapse;
          width: 100%;
          border: 1px solid; /* Setebal 1px */
        }
  
        th,
        td {
          border: 1px solid; /* Setebal 1px */
          padding: 8px;
          text-align: left;
        }
      </style>
      <table style="border-color: #000000">
        <tr>
          <td style="text-align: center;" colspan="7">
            <strong>
              PEMERINTAH KOTA MALANG <br />
              BADAN PELAYANAN PAJAK DAERAH <br />
              Perkantoran Terpadu Pemerintah Kota Malang <br />
              Jl. Mayjend Sungkono Gedung B lantai 1 Telp. (0341) 751532 <br />
              Kel. Arjowinangun Kode Pos 65132
            </strong>
          </td>
          <td colspan="5" style="width: 40%">
            <ul style="list-style-type: none; margin-left: -30px">
              <li>No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '</li>
              <li>Masa Pajak : ' . $sptpd->sptpd_bulan_pajak . '-' . $sptpd->sptpd_tahun_pajak . '</li>
              <li>Tahun Pajak : ' . $sptpd->sptpd_tahun_pajak . '</li>
            </ul>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
            <strong>
              SPTPD <br />
              (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
              PAJAK RESTORAN <br />
            </strong>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td style="padding: 16px;" colspan="6">
            <div style="margin-bottom: 25px">
              <span>NPWPD (Nomor Pokok Wajib Pajak):*</span><br />
            </div>
            <div style="margin-bottom: 15px">
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid white;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
              <span
                style="
                  border: 3px solid black;
                  padding: 10px 7px;
                  text-align: left;
                "
              >
              </span>
            </div>
          </td>
          <td colspan="6" style="">
            <span style="margin-left: 20px">kepada</span><br />
            yth. Kepala Badan Pendapatan <br />
            Daerah Kota Malang <br />
            di <br />
            Malang
          </td>
        </tr>
        <!-- batas -->
        <tr style="">
          <td colspan="12">
            PERHATIAN : <br />
            <ol>
              <li>
                Harap diisi dalam rangkap 2 (dua) ditulis dengan HURUF KAPITAL;
              </li>
              <li>
                Beri nomor pada kotak
                <span
                  style="
                    display: inline-block;
                    width: 5px;
                    height: 5px;
                    border: 0.5px solid black;
                  "
                >
                </span>
                yang tersedia untuk jawaban yang diberikan;
              </li>
              <li>
                Setelah diisi dan ditandatangani wajib diserahkan kembali kepada
                Badan Pelayanan Pajak Daerah Kota Malang paling lambat 10
                (sepuluh) hari setelah berakhirnya masa pajak;
              </li>
              <li>
                Keterlambatan penyerahan dari tanggal tersebut di atas, maka akan
                dilakukan Penetapan Secara Jabatan (Official Assesment).
              </li>
            </ol>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
                <strong> DIISI OLEH PENGUSAHA RESTORAN </strong>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12">
            <table style="border:none; text-align: left; vertical-align: top;">
              <tr>
                <td style="border:none; width: 3%;">
                  1
                </td>
                <td style="border:none;">
                  <p>Restoran :</p>
                  <table style="text-align: left; vertical-align: top;">
                    <tr>
                      <th>No.</th>
                      <th>Meja Tersedia</th>
                      <th>Jumlah Kursi</th>
                      <th>Jumlah Pengunjung Rata - Rata Per Hari</th>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  2
                </td>
                <td style="border:none;">
                  <table style="border:none; text-align: left; vertical-align: top;">
                    <tr>
                      <td style="border:none; padding:0%; width: 30%">
                        Menggunakan Kas Register
                      </td>
                      <td style="border:none; padding:0%; width: 70%;">
                        <div style="">
                          <span
                            style="
                              border: 3px solid black;
                              padding: 5px 5px;
                              text-align: left;
                              color:white;
                            "
                          >aa
                          </span> Ya
                        </div>
                        <div>
                          <span
                            style="
                              border: 3px solid black;
                              padding: 5px 5px;
                              text-align: left;
                              color:white;
                            "
                          >aa
                          </span> Tidak
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  3
                </td>
                <td style="border:none;">
                  <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sebelumnya (akumulasi dari awal Masa Pajak dalam
                  Tahun Pajak Tertentu) :</p>
                  <table style="border:none;">
                    <tr>
                      <td style="border:none;">a</td>
                      <td style="border:none;">Masa Pajak</td>
                      <td style="border:none;">: Tgl. ................... s.d. Tgl. ......................</td>
                    </tr>
                    <tr>
                      <td style="border:none;">b</td>
                      <td style="border:none;">Dasar Pengenaan (Jumlah
                      pembayaran yang diterima)</td>
                      <td style="border:none;">: Rp. ....................</td>
                    </tr>
                    <tr>
                      <td style="border:none;">c</td>
                      <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                      <td style="border:none;">: ...................... %</td>
                    </tr>
                    <tr>
                      <td style="border:none;">d</td>
                      <td style="border:none;">Pajak Terutang ( b x c ) </td>
                      <td style="border:none;">: Rp. ....................</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none; width: 3%;">
                  4
                </td>
                <td style="border:none;">
                  <p>Jumlah Pembayaran dan Pajak Terutang untuk Masa Pajak sekarang ( lampirkan foto copy dokumen) :
                  </p>
                  <table style="border:none;">
                    <tr>
                      <td style="border:none;">a</td>
                      <td style="border:none;">Masa Pajak</td>
                      <td style="border:none;">: Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '</td>
                    </tr>
                    <tr>
                      <td style="border:none;">b</td>
                      <td style="border:none;">Dasar Pengenaan (Jumlah
                      pembayaran yang diterima)</td>
                      <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '</td>
                    </tr>
                    <tr>
                      <td style="border:none;">c</td>
                      <td style="border:none;">Tarif Pajak (sesuai Perda)</td>
                      <td style="border:none;">: ' . $sptpd->jenis_tarif . ' %</td>
                    </tr>
                    <tr>
                      <td style="border:none;">d</td>
                      <td style="border:none;">Pajak Terutang ( b x c ) </td>
                      <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet * ($sptpd->jenis_tarif / 100)) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="border:none;">
                  5
                </td>
                <td style="border:none;">
                  <table style="border:none;">
                    <tr>
                      <td style="border:none;">a</td>
                      <td style="border:none;">Masa Pajak</td>
                      <td style="border:none;">: Tgl. ' . $tanggal_bawah . ' s.d. Tgl. ' . $tanggal_atas . '</td>
                    </tr>
                    <tr>
                      <td style="border:none;">b</td>
                      <td style="border:none;">Dasar Pengenaan (Jumlah
                      pembayaran yang diterima)</td>
                      <td style="border:none;">: Rp. ' . number_format($sptpd->sptpd_nominal_omzet) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <!-- batas -->
        <tr>
          <td colspan="12" style="text-align: center;">
            <table style="border: none; margin-bottom: 20px;">
              <tr>
                <td style="border: none; text-align: center;">
                  <strong>PERNYATAAN</strong>
                </td>
              </tr>
              <tr>
                <td style="border: none; text-align: justify;">
                  Dengan menyadari sepenuhnya akan segala akibat termasuk
                  sanksi–sanksi sesuai dengan ketentuan peraturan perundang-undangan,
                  saya atau yang saya beri kuasa menyatakan bahwa apa yang telah saya
                  beritahukan tersebut di atas beserta lampiran-lampirannya adalah
                  benar, lengkap dan jelas
                </td>
              </tr>
            </table>
            <table style="border: none">
              <tr>
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 30%; text-align: center;">
                  <div>
                    <span>
                      ............................. Tahun ............. <br />
                      Wajib Pajak,<br />
                      <br />
                      <br />
                      <br />
                      ' . $sptpd->wajibpajak_nama_penanggungjawab . ' <br />
                      Nama Jelas
                    </span>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="12" style="text-align: center">
            <div>
              <strong> DIISI OLEH PETUGAS PENERIMA BPPD </strong>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan="12">
            <table style="border: none">
              <tr style="border: none">
                <td style="border: none; width: 20%">Diterima Tanggal</td>
                <td style="border: none">:</td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 20%">Nama Petugas</td>
                <td style="border: none">:</td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 20%">NIP</td>
                <td style="border: none">:</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
          <td style="border-color: white"></td>
        </tr>
      </table>
      <table style="border: none;">
        <tr>
          <td style="border: none; text-align: center;">
            <p style="margin-bottom: 15px">
              MODEL DPD-02................................potong
              disini........................................................
            </p>
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td>
            <table style="border:none;">
              <tr>
                <td style="border:none; text-align: right;">
                  No. SPTPD : ' . $sptpd->sptpd_nomor_sptpd . '
                </td>
              </tr>
            </table>
            <br />
            <br />
            <br />
            <div>
              <center>
                <strong style="text-decoration: underline"> TANDA TERIMA </strong>
              </center>
            </div>
            <div>
              <table style="border: none">
                <tr style="border: none">
                  <td style="border: none; width: 17%">NPWPD</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->sptpd_npwpd . '
                  </td>
                </tr>
                <tr style="border: none">
                  <td style="border: none; width: 17%">Nama</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->wajibpajak_nama_penanggungjawab . '
                  </td>
                </tr>
                <tr style="border: none">
                  <td style="border: none; width: 17%">Alamat</td>
                  <td style="border: none; text-align: left;">
                    : ' . $sptpd->wajibpajak_alamat . '
                  </td>
                </tr>
              </table>
            </div>
            <div>
              <table style="border: none">
                <tr style="border: none">
                  <td style="border: none; width: 35%"></td>
                  <td style="border: none; width: 35%"></td>
                  <td style="border: none; width: 30%; text-align: center;">
                    <div>
                      <span>
                        ............................. Tahun ............. <br />
                        Yang Menerima,<br />
                        <br />
                        <br />
                        <br />
                        ....................................... <br />
                        Nama Jelas
                      </span>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
    ';
  }
}
