<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
    'F' : to save pdf to local. 
    'S' : to return file as string.
    'D' : to download the file.
    'I' : force download... plug-in if available.
*/

if (!function_exists('createPdf')) {
    function createPdf($data = array())
    {
        $config = array();
        if (is_array($data)) {
            $config = array(
                'data'          => (!empty($data['data']))           ? $data['data']         : '',
                'paper_size'    => (!empty($data['paper_size']))     ? $data['paper_size']   : '',
                'file_name'     => (!empty($data['file_name']))      ? $data['file_name']    : '',
                'margin'        => (!empty($data['margin']))         ? $data['margin']       : '',
                'stylesheet'    => (!empty($data['stylesheet']))     ? $data['stylesheet']   : '',
                'font_face'     => (!empty($data['font_face']))      ? $data['font_face']    : '',
                'font_size'     => (!empty($data['font_size']))      ? $data['font_size']    : '',
                'orientation'   => (!empty($data['orientation']))    ? $data['orientation']  : '',
                'margin_hf'     => (!empty($data['margin_hf']))      ? $data['margin_hf']    : '',
                'download'      => (!empty($data['download'])       && $data['download'] == true)    ? true : false,
                'title'         => (!empty($data['title']))          ? $data['title']        : '',
                'header'        => (!empty($data['header']))         ? $data['header']       : '',
                'footer'        => (!empty($data['footer']))         ? $data['footer']       : '',
                'json'          => (!empty($data['json'])           && $data['json'] == true)     ? true : false,
                'kwt'           => (!empty($data['kwt'])            && $data['kwt'] == true)      ? true : false,
                'save'          => (!empty($data['save'])           && $data['save'] == true)     ? true : false,
                'return'        => (!empty($data['return'])         && $data['return'] == true)   ? true : false,
            );
        }

        $explode     = explode(' ', $config['margin']);
        $explode_hf  = explode(' ', $config['margin_hf']);
        $orientation = ($config['orientation'] == '') ? 'L' : $config['orientation'];
        $font_face   = ($config['font_face'] != '') ? $config['font_face'] : '';
        $font_size   = ($config['font_size'] != '') ? $config['font_size'] : '';
        $file_name   = ($config['file_name'] != '') ? $config['file_name'] : 'Laporan' . date('dMY');
        $title       = ($config['title'] != '')     ? $config['title']     : 'Laporan';
        $header      = ($config['header'] != '')    ? $config['header']    : '';
        $footer      = ($config['footer'] != '')    ? $config['footer']    : '';
        $json        = ($config['json'] != '')      ? true                 : false;
        $return        = ($config['return'] != '')      ? true                 : false;

        ob_clean();
        $CI = &get_instance();
        $pdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => $config['paper_size'],
            'orientation'   => $orientation,
            'default_font_size' => $font_size,
            'default_font'  => $font_face,
            'margin_left'   => (isset($explode[3]) != '') ? $explode[3] : '',
            'margin_right'  => (isset($explode[1]) != '') ? $explode[1] : '',
            'margin_top'    => (isset($explode[0]) != '') ? $explode[0] : '',
            'margin_bottom' => (isset($explode[2]) != '') ? $explode[2] : '',
            'margin_header' => (isset($explode_hf[0]) != '') ? $explode_hf[0] : '',
            'margin_footer' => (isset($explode_hf[1]) != '') ? $explode_hf[1] : '',
        ]);
        // $pdf->use_kwt = $config['kwt'];
        $xstylesheet = '';
        if (is_array($config['stylesheet'])) {
            for ($i = 0; $i < count($config['stylesheet']); $i++) {
                $xstylesheet .= file_get_contents($config['stylesheet'][$i]);
            }
        } else {
            $xstylesheet = file_get_contents($config['stylesheet']);
        }
        // $pdf->use_kwt = true;

        $pdf->WriteHTML($xstylesheet, 1);
        $pdf->SetTitle($title);
        // $pdf->SetWatermarkImage('../../assets/media/bapenda.png');
        $pdf->SetWatermarkImage(
            'https://pbs.twimg.com/profile_images/1480388481322930179/t2mAux9H_400x400.jpg',
            1,
            '',
            array(160, 10)
        );

        $pdf->SetHTMLHeader($header, '', TRUE);
        $pdf->SetHTMLFooter($footer);

        // $pdf->setFooter('{PAGENO} / {nb}');

        $pdf->WriteHTML($config['data']);
        if ($chart) {
        }

        ob_end_clean();
        if ($config['save'] == true) {
            $pdf->Output($file_name . '.pdf', 'F');
        } else {
            if ($json == true) {
                $pdfString = $pdf->Output('', 'S');
                $response =  array(
                    'success'   => true,
                    'id'        => $file_name,
                    'message'   => 'Berhasil',
                    'record'    => "data:application/pdf;base64," . base64_encode($pdfString),
                    'data'      => $data
                );
                if ($return) {
                    return $response;
                } else {
                    die(json_encode($response));
                }
            } else {
                if ($config['download'] == true) {
                    $pdf->Output($file_name . '.pdf', 'D');
                } else {
                    $pdf->Output($file_name . '.pdf', 'I');
                }
            }
        }
    }
}
