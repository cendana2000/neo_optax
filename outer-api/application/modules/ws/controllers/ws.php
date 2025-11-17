<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ws extends Main_Controller
{
    var $css_plugin = array();
    var $js_plugin = array();
    var $dirExceptions  = array(".", "..", "Thumbs.db");

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model("admin/admin_model", "data_model");
        $this->load->model("report/report_model", "report_model");
    }

    public function get_daftar_alasan_tolak()
    {
        $daftar_alasan_tolak = $this->data_model->get_daftar_alasan_tolak();

        $data = array(
            "data"      => $daftar_alasan_tolak["data"],
            "status"    => true,
            "msg"       => "OK."
        );

        print_r(json_encode($data));
    }

    public function cek_validasi_bonbil()
    {
        header("Content-type:application/json");

        $mode_validasi = $this->input->post("mode_validasi");
        $data = $this->input->post("data");
        if ($mode_validasi == "resi") {
            $cek_validasi_bonbil = $this->data_model->cek_validasi_bonbil_resi($data);
        } else {
            $cek_validasi_bonbil = $this->data_model->cek_validasi_bonbil_tanggal_jam($data);
        }

        print_r(json_encode($cek_validasi_bonbil));
    }

    public function detail_bonbil_by_id()
    {
        header("Content-type:application/json");

        $data = array(
            "data"      => null,
            "msg"       => "Server Belum Menerima Data.",
            "status"    => true
        );

        $id_bonbil = $this->input->post("id_bonbil") ? $this->input->post("id_bonbil") : "";
        if ($id_bonbil != "") {
            $get_detail_bonbil_by_id = $this->data_model->get_detail_bonbil_by_id($id_bonbil);

            $data = array(
                "data"      => ($get_detail_bonbil_by_id["num_rows"] > 0) ? $get_detail_bonbil_by_id["data"][0] : $get_detail_bonbil_by_id["data"],
                "status"    => true,
                "msg"       => "OK."
            );
        }

        print_r(json_encode($data));
    }

    public function get_data_wp_etax($keyword)
    {
        header("Content-type:application/json");
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pajak.malangkota.go.id/daftar_etax/home/caritempatusaha',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('return' => 'json', 'keyword' => $keyword),
            CURLOPT_HTTPHEADER => array(
                'Cookie: ci_session=5nr22gmtcojbv7enq78jhc6fldsrosih'
            ),
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
        ));

        $response = curl_exec($curl);
        $res = ($response) ? count(json_decode($response, true)["data"]) : 0;

        curl_close($curl);
        return $res;
    }

    public function move_uploaded_image()
    {
        //https://pajak.malangkota.go.id/gsp/assets/img/bonbil/2023/1695979108_554833.jpg
        $folder_path_url = $this->input->post('folder_path_url');
        $fileData = file_get_contents($folder_path_url);

        if ($this->input->post('folder_path_url')) {
            #1. dapatkan nilai tahun dari url dahulu
            preg_match("!bonbil\/(\d{4})\/!is", $folder_path_url, $match);

            if (count($match) > 0) {
                $tahun = $match[1];

                #2. generate folder dahulu
                $dir_name = FCPATH . '/assets/img/bonbil/' . $tahun;
                if (!file_exists($dir_name)) {
                    try {
                        if (!is_dir($dir_name)) {
                            mkdir($dir_name, 0755);
                        }
                    } catch (Exception $e) {
                    }
                }

                #3. get filename
                preg_match("!bonbil\/\d{4}\/(.*)!is", $folder_path_url, $match);
                if (count($match) > 0) {
                    $file_name = $match[1];
                    file_put_contents($dir_name . "/" . $file_name, $fileData);

                    #4. Resize Ukuran File Gambar
                    $resized_image = $this->resizeImage($tahun, $file_name);

                    $response = json_encode(array(
                        "status"        => true,
                        "msg"           => "Gambar Berhasil Dipindahkan.",
                        "file"          => $dir_name . "/" . $file_name,
                        "resizedImage"  => $resized_image
                    ));
                } else {
                    $response = json_encode(array(
                        "status"    => false,
                        "msg"       => "Server Tidak Menerima Data Nama File.",
                    ));
                }
            } else {
                $response = json_encode(array(
                    "status"    => false,
                    "msg"       => "Server Tidak Menerima Data Tahun.",
                ));
            }
        } else {
            $response = json_encode(array(
                "status"    => false,
                "msg"       => "Server Belum Menerima Data.",
            ));
        }

        print_r($response);
    }

    public function cetak_laporan_excel()
    {
        $post = $this->input->post();
        if (empty($post)) {
            print_r("<pre>" . json_encode([
                'success' => false,
                'msg' => "Server Belum Mererima Data.",
            ]) . "</pre>");
        } else {
            try {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Set Header
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'eaeaea',
                        ],
                        'endColor' => [
                            'argb' => 'eaeaea',
                        ],
                    ],
                ];
                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', 'DAFTAR PERMINTAAN BONBIL');
                $sheet->getStyle('A1')->applyFromArray($styleArray);

                foreach (range('A', 'M') as $columnID) {
                    $sheet->getColumnDimension($columnID)
                        ->setAutoSize(true);
                }

                // Set Table Header
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'eaeaea',
                        ],
                        'endColor' => [
                            'argb' => 'eaeaea',
                        ],
                    ],
                ];
                $sheet->getStyle('A2:M2')->applyFromArray($styleArray);
                $sheet->setCellValue('A2', 'NO');
                $sheet->setCellValue('B2', 'NO UNDIAN');
                $sheet->setCellValue('C2', 'TANGGAL UPLOAD');
                $sheet->setCellValue('D2', 'NAMA PENGIRIM');
                $sheet->setCellValue('E2', 'ALAMAT');
                $sheet->setCellValue('F2', 'NO TELP');
                $sheet->setCellValue('G2', 'LOKASI ETAX');
                $sheet->setCellValue('H2', 'JENIS ETAX');
                $sheet->setCellValue('I2', 'NO RESI BONBIL');
                $sheet->setCellValue('J2', 'TANGGAL & WAKTU BONBIL');
                $sheet->setCellValue('K2', 'STATUS');
                $sheet->setCellValue('L2', 'ALASAN TOLAK');
                $sheet->setCellValue('M2', 'LINK GAMBAR BONBIL');

                // Set Borders
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                if ($post["filter_mode"] == "periode") {
                    $where = array(
                        "tahun"     => $post["tahun"],
                        "tahap"     => $post["tahap"],
                        "status"    => $post["status"],
                    );
                } else {
                    $where = array(
                        "datestart" => $post["datestart"],
                        "dateend"   => $post["dateend"],
                        "status"    => $post["status"],
                    );
                }

                $get_all_daftar_bonbil_all = $this->report_model->get_all_daftar_bonbil($post["status_mode"], $post["search"]["value"], $post["order"][0], $where, array("page" => $post["page"], "length" => $post["length"]), true);

                $ops = $get_all_daftar_bonbil_all["data"];
                $no = 2;
                foreach ($ops as $key => $value) {
                    $gambar = "https://pajak.malangkota.go.id/gsp/ws/gsp-img-loader.php?fn=" . $value['gambar_bonbil'] . "&y=" . date("Y", strtotime($value['tanggal_bonbil_upload']));
                    $no += 1;
                    $status = ($value['is_valid'] == "0") ? "Proses Validasi" : (($value['is_valid'] == "1") ? "Valid" : "Ditolak");
                    $sheet->setCellValue('A' . $no, $key + 1);
                    $sheet->setCellValue('B' . $no, $value['no_undian']);
                    $sheet->setCellValue('C' . $no, date("Y-m-d H:i:s", strtotime($value['tanggal_bonbil_upload'])));
                    $sheet->setCellValue('D' . $no, $value['username']);
                    $sheet->setCellValue('E' . $no, $value['alamat']);
                    $sheet->setCellValue('F' . $no, $value['no_telp']);
                    $sheet->setCellValue('G' . $no, $value['lokasi_etax']);
                    $sheet->setCellValue('H' . $no, $value['jenis_etax']);
                    $sheet->setCellValue('I' . $no, $value['no_resi']);
                    $sheet->setCellValue('J' . $no, $value['tanggal_jam']);
                    $sheet->setCellValue('K' . $no, $status);
                    $sheet->setCellValue('L' . $no, $value['alasan']);
                    // $sheet->setCellValue('K' . $no, base_url('assets/img/bonbil/' . date("Y", strtotime($value['tanggal_bonbil_upload'])) . "/" . $value['gambar_bonbil']));
                    $sheet->setCellValue('M' . $no, $gambar);
                    $sheet->getStyle("A$no:M" . $no)->applyFromArray($styleArray);
                }

                // Write a new .xlsx file
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

                // Save the new .xlsx file
                $filename = 'laporan_gsp-' . date('Ymd_') . strtotime(date("Y-m-d H:i:s")) . '.xlsx';
                if (!file_exists(FCPATH . 'assets/laporan/')) {
                    mkdir(FCPATH . 'assets/laporan/', 0777, true);
                }
                $file = FCPATH . 'assets/laporan/' . $filename;
                $writer->save($file);

                header('Content-Type: application/json');

                print_r(json_encode([
                    'success' => true,
                    'file' => $filename
                ]));
            } catch (\Throwable $th) {
                print_r('<pre>');
                print_r($th);
                print_r('</pre>');
                exit;
                $this->response([
                    'success' => false,
                    'message' => $th
                ]);
            }
        }
    }

    public function cetak_laporan_pdf()
    {
        header('Content-Type: application/json');

        $this->load->library("dom_pdf");
        $post = $this->input->post();

        if (!empty($post)) {
            $dataCetak  = $post;
            $filename   = 'laporan_gsp-' . date('Ymd_') . strtotime(date("Y-m-d H:i:s")) . '.pdf';

            if ($post["filter_mode"] == "periode") {
                $where = array(
                    "tahun"     => $post["tahun"],
                    "tahap"     => $post["tahap"],
                    "status"    => $post["status"],
                );
            } else {
                $where = array(
                    "datestart" => $post["datestart"],
                    "dateend"   => $post["dateend"],
                    "status"    => $post["status"],
                );
            }

            $dataCetak["data_bonbil"] = $this->report_model->get_all_daftar_bonbil($post["status_mode"], $post["search"]["value"], $post["order"][0], $where, array("page" => $post["page"], "length" => $post["length"]), true);

            $output = $this->dom_pdf->get_output('report/laporan_gsp', $dataCetak, array(0, 0, 595, 935), 'landscape', $filename);
            if (!file_exists(FCPATH . 'assets/laporan/')) {
                mkdir(FCPATH . 'assets/laporan/', 0777, true);
            }
            file_put_contents(FCPATH . "assets/laporan/" . $filename, $output);

            print_r(json_encode([
                'success'   => true,
                'file'      => $filename
            ]));
        } else {
            print_r(json_encode(array(
                "status" => false,
                "msg" => "Server belum menerima data.",
            )));
        }
    }

    public function resizeImage($tahun = null, $file_name = "all", $return = "data")
    {
        $this->load->library('image_lib');
        $tahun          = (empty($tahun)) ? date("Y") : $tahun;
        $dir_name       = FCPATH . "/assets/img/bonbil/$tahun";
        $resized_image  = array();

        if ($file_name == "all") {
            $dir_files = array_values(array_diff(scandir($dir_name), $this->dirExceptions));
            foreach ($dir_files as $key => $val) {
                $proses_resize = $this->proceedResizeImage($dir_name, $tahun, $val);
                if (!empty($proses_resize)) {
                    $resized_image[] = $proses_resize;
                }
            }
        } else {
            $resized_image = $this->proceedResizeImage($dir_name, $tahun, $file_name);
        }

        if ($return == "data") {
            return $resized_image;
        } else {
            header("Content-type:application/json");
            print_r(json_encode($resized_image));
        }
    }

    public function proceedResizeImage($dir_name, $tahun, $file_name)
    {
        $file_full_path = $dir_name . "/" . $file_name;
        $file_size = filesize($file_full_path);
        if ($file_size > 1048576) {
            $config['image_library']  = 'gd2'; // Gunakan GD2 Library (atau pilih yang sesuai)
            $config['source_image']   = $file_full_path; // Lokasi file sumber
            $config['maintain_ratio'] = TRUE; // Tetap menjaga rasio aspek gambar
            $config['width'] = 1024; // Lebar maksimum yang diinginkan (opsional, sesuaikan dengan kebutuhan Anda)
            $config['height'] = 1024;

            $this->image_lib->initialize($config);

            if (!$this->image_lib->resize()) {
                $result = array(
                    "linkGambar" => base_url("/assets/img/bonbil/$tahun/$file_name"),
                    "file_size"  => filesize($file_full_path),
                    "msg"        => $this->image_lib->display_errors(),
                    "status"     => false
                );
            } else {
                $result = array(
                    "linkGambar" => base_url("/assets/img/bonbil/$tahun/$file_name"),
                    "file_size"  => filesize($file_full_path),
                    "msg"        => "Gambar berhasil dikompresi.",
                    "status"     => true
                );
            }
        } else {
            $result = array();
        }

        return $result;
    }
}
