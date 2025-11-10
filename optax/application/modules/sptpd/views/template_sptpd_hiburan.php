<?php function templateHiburan($sptpd){
  
  return $html = '<!DOCTYPE html>
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
            <li>Masa Pajak : 12414124</li>
            <li>Tahun Pajak : ' . $sptpd['sptpd_tahun_pajak'] . '</li>
          </ul>
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td colspan="12" style="text-align: center; font-size: 11px">
          <strong>
            SPTPD <br />
            (SURAT PEMBERITAHUAN PAJAK DAERAH) <br />
            PAJAK HIBURAN <br />
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
          yth. -------------------------- <br />
          ------------------------------ <br />
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
              <strong> DIISI OLEH PENGUSAHA HIBURAN </strong>
            </span>
          </div>
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td colspan="12">
          <table
            style="
              border: none;
              margin: 0;
              padding: 0;
              table-layout: fixed;
              width: auto;
            "
          >
            <tr style="border: none">
              <td
                style="
                  border: none;
                  vertical-align: top;
                  padding: 0;
                  width: 30%;
                "
              >
                1. Hiburan yang diselenggarakan
                <span
                  style="
                    display: inline-block;
                    width: 5px;
                    height: 5px;
                    border: 0.5px solid black;
                  "
                >
                </span
                ><span
                  style="
                    display: inline-block;
                    width: 5px;
                    height: 5px;
                    border: 0.5px solid black;
                  "
                >
                </span>
              </td>
              <td style="border: none; vertical-align: top; padding: 0">
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
        </td>
      </tr>
      <!-- batas -->
      <tr>
        <td colspan="12">
          <ol start="2" style="padding: 0; margin-left: 15px;">
            <li>
              <div>
                <p>Harga tanda masuk yang berlaku</p>
                - Kelas .................... Rp. ............................ <br />
                - Kelas .................... Rp. ............................ <br />
                - Kelas .................... Rp. ............................ <br />
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                    Jumlah pertunjukan rata-rata pada hari biasa
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: ................. kali</td>
                  </tr>
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jumlah pertunjukan rata-rata pada hari libur/minggu (khusus
                      untuk pertunjukan Film, Kesenian dan Sejenisnya, Pagelaran
                      Musik dan Tari).
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: ................. kali</td>
                  </tr>
                </table>
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jumlah pengunjung/penonton rata-rata pada hari biasa 
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: ................. kali</td>
                  </tr>
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jumlah pengunjung/penonton rata-rata pada hari libur/minggu
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: ................. kali</td>
                  </tr>
                </table>
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jumlah Meja/Mesin (Khusus untuk Billyar, Permainan Ketangkasan)
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: .................... buah</td>
                  </tr>
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jumlah kamar/ruangan (khusus untuk Panti Pijat, Mandi Uap/Spa, Karaoke)
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: .................... buah</td>
                  </tr>
                </table>
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Apakah perusahaan menyediakan karcis bebas (free) kepada orang-orang tertentu
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none; display:flex;">
                      :
                      <div style="margin-left:20px;">
                        <span
                        style="
                          display: inline-block;
                          width: 5px;
                          height: 5px;
                          border: 0.5px solid black;
                        "
                      > 
                      <ol style="margin-left: 25px; padding:0px;">
                        <li>Ya</li>
                        <li>Tidak</li>
                      </ol>
                      </div>
                    </td>
                  </tr>
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Jika YA berapa jumlah yang beredar
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none">: .................... buah</td>
                  </tr>
                </table>
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Penjualan Karcis dengan mesin tiket 
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none; display:flex;">
                      :
                      <div style="margin-left:20px;">
                        <span
                        style="
                          display: inline-block;
                          width: 5px;
                          height: 5px;
                          border: 0.5px solid black;
                        "
                      > 
                      <ol style="margin-left: 25px; padding:0px;">
                        <li>Ya</li>
                        <li>Tidak</li>
                      </ol>
                      </div>
                    </td>
                  </tr>
                </table>
              </div>
            </li>
            <li>
              <div>
                <table style="border: none;">
                  <tr style="border: none">
                    <td style="text-align: left; vertical-align: top; border: none" width="70%">
                      Melaksanakan Pembukuan/Pencatatan
                    </td>
                    <td style="text-align: left; vertical-align: top; border: none; display:flex;">
                      :
                      <div style="margin-left:20px;">
                        <span
                        style="
                          display: inline-block;
                          width: 5px;
                          height: 5px;
                          border: 0.5px solid black;
                        "
                      > 
                      <ol style="margin-left: 25px; padding:0px;">
                        <li>Ya</li>
                        <li>Tidak</li>
                      </ol>
                      </div>
                    </td>
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
    <br><br><br><br><br><br><br><br><br>
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
