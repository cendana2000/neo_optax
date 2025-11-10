<?php function templateHotel($sptpd){
  return '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Document</title>
    <style>
      table {
        border-collapse: collapse;
        width: 100%;
        border: 3px solid; /* Setebal 3px */
      }

      th,
      td {
        border: 3px solid; /* Setebal 3px */
        padding: 8px;
        text-align: left;
      }

      * {
        font-size: 11px;
      }
    </style>
  </head>
  <body style="margin: 70px">
    <table style="border-color: #000000">
      <tr>
        <td style="text-align: center; font-size: 11px" colspan="7">
          <strong>
            PEMERINTAH KOTA MALANG <br />
            BADAN PELAYANAN PAJAK DAERAH <br />
            Perkantoran Terpadu Pemerintah Kota Malang <br />
            Jl. Mayjend Sungkono Gedung B lantai 1 Telp. (0341) 751532 <br />
            Kel. Arjowinangun Kode Pos 65132
          </strong>
        </td>
        <td colspan="5" style="font-size: 11px; width: 40%">
          <ul style="list-style-type: none; margin-left: -30px">
            <li>No. SPTPD : ' . $sptpd['sptpd_nomor_sptpd'] . '</li>
            <li>Masa Pajak : ' . $sptpd['sptpd_bulan_pajak'] . $sptpd['sptpd_tahun_pajak'] . '</li>
            <li>Tahun Pajak : '. $sptpd['sptpd_tahun_pajak'] .'</li>
          </ul>
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td colspan="12" style="text-align: center; font-size: 11px">
          <strong>
            SPTPD <br />
            (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
            PAJAK HOTEL <br />
          </strong>
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td style="padding: 16px; font-size: 11px" colspan="6">
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
        <td colspan="6" style="font-size: 11px">
          <span style="margin-left: 20px">kepada</span><br />
          yth. Kepala Badan Pendapatan <br />
          Daerah Kota Malang <br />
          di <br />
          Malang
        </td>
      </tr>
      <!-- batas -->
      <tr style="font-size: 11px">
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
        <td colspan="12">
          <div style="text-align: center">
            <span>
              <strong> DIISI OLEH WAJIB PAJAK HOTEL </strong>
            </span>
          </div>
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td colspan="12">
          <div>
            <ol style="list-style-type: upper-roman">
              <li>
                <div>
                  <span style="margin-bottom: 15px">Golongan Hotel</span>
                  <span
                    style="
                      display: inline-block;
                      width: 5px;
                      height: 5px;
                      border: 0.5px solid black;
                    "
                  >
                  </span>
                  <span
                    style="
                      display: inline-block;
                      width: 5px;
                      height: 5px;
                      border: 0.5px solid black;
                    "
                  >
                  </span>
                </div>
                <div style="margin-top: 10px">
                  <table style="border: none">
                    <tr style="border: none">
                      <div style="object-position: left top">
                        <td style="border: none; width: 20%">
                          01. Bintang Lima <br />
                          02. Bintang Empat <br />
                          03. Bintang Tiga <br />
                          04. Bintang Dua <br />
                          05. Bintang Satu <br />
                        </td>
                      </div>
                      <div style="object-position: left top">
                        <td style="border: none; width: 80%">
                          06. Melati Tiga <br />
                          07. Melati Due <br />
                          08. Melati Satu <br />
                          09. Ekonomi <br />
                          10. Rumah Kos lebih 10 kamar <br />
                          11. Lainnya....
                        </td>
                      </div>
                    </tr>
                  </table>
                </div>
              </li>
              <li style="margin-bottom: 10px">
                <p>Tarif dan jumlah kamar hotel :</p>
                <div>
                  <table>
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
                  </table>
                </div>
                <div>
                  <ol>
                    <li>
                      <p>Menggunakan Kas Register</p>
                      <div>
                        1.<span
                          style="
                            display: inline-block;
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                          "
                        >
                        </span>
                        Ya
                      </div>
                      <div>
                        2.<span
                          style="
                            display: inline-block;
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                          "
                        >
                        </span>
                        Tidak
                      </div>
                    </li>
                    <li>
                      <p>Mengadakan pembukuan/pencatatan</p>
                      <div>
                        1.<span
                          style="
                            display: inline-block;
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                          "
                        >
                        </span>
                        Ya
                      </div>
                      <div>
                        2.<span
                          style="
                            display: inline-block;
                            width: 5px;
                            height: 5px;
                            border: 0.5px solid black;
                          "
                        >
                        </span>
                        Tidak
                      </div>
                    </li>
                  </ol>
                </div>
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
                        : Tgl. .......... s.d. Tgl. ..............
                      </td>
                    </tr>
                    <tr>
                      <td style="border: none; width: 5px">b.</td>
                      <td style="border: none; width: 250px">
                        Dasar Pengenaan (jumlah pembayaran yang diterima)
                      </td>
                      <td style="border: none">
                        : Rp. ' . $sptpd['sptpd_nominal_omzet'] . '
                      </td>
                    </tr>
                    <tr>
                      <td style="border: none; width: 5px">c.</td>
                      <td style="border: none; width: 250px">
                        Tarif pajak (sesuai Perda)
                      </td>
                      <td style="border: none">: ' . 'toko' . '%</td>
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
                  </table>
                </div>
              </li>
            </ol>
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="12">
          <div style="text-align: center; font-size: 12">
            <strong> PERNYATAAN </strong>
          </div>
          <div style="text-align: justify; margin-bottom: 20px">
            Dengan menyadari sepenuhnya akan segala akibat termasuk
            sanksi–sanksi sesuai dengan ketentuan peraturan perundang-undangan,
            saya atau yang saya beri kuasa menyatakan bahwa apa yang telah saya
            beritahukan tersebut di atas beserta lampiran-lampirannya adalah
            benar, lengkap dan jelas
          </div>

          <table style="border: none">
            <tr style="border: none">
              <td style="border: none; width: 35%"></td>
              <td style="border: none; width: 35%"></td>
              <td style="border: none; width: 30%">
                <div style="text-align: center">
                  <span>
                    ............................. Tahun ............. <br />
                    Wajib Pajak,<br />
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
        </td>
      </tr>
      <tr>
        <td colspan="12">
          <div style="text-align: center">
            <span>
              <strong> DIISI OLEH PETUGAS PENERIMA BPPD </strong>
            </span>
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
    <center style="margin-bottom: 15px">
      MODEL DPD-02................................potong
      disini........................................................
    </center>
    <table>
      <tr>
        <td>
          <div style="text-align: right">
            No. SPTPD :.........................
          </div>
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
                <td style="border: none">
                  :...............................................
                </td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 17%">Nama</td>
                <td style="border: none">
                  :...............................................
                </td>
              </tr>
              <tr style="border: none">
                <td style="border: none; width: 17%">Alamat</td>
                <td style="border: none">
                  :...............................................
                </td>
              </tr>
            </table>
          </div>
          <div>
            <table style="border: none">
              <tr style="border: none">
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 35%"></td>
                <td style="border: none; width: 30%">
                  <div style="text-align: center">
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
  </body>
</html>';}
