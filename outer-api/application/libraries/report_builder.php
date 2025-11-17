<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Class build report for report menu
*/
class Report_builder
{
  var $CI = null;

  function __construct()
  {
    $this->CI = &get_instance();
  }

  function merge_xml(&$simplexml_to, &$simplexml_from)
  {
    // selain elemen REPORT di tambahkan jika id nya belum ada di tujuan
    // khusus untuk elemen REPORT, jika id sudah ada, maka elemen di tujuan ditimpa
    foreach ($simplexml_from->children() as $simplexml_child) {
      // cek apakah id elemen asal sudah ada di tujuan
      $found = false;

      $id_from = (string) $simplexml_child->attributes()->id;
      foreach ($simplexml_to->children() as $child) {
        $id_to = (string) $child->attributes()->id;
        if ($id_to == '') continue;  // elemen tidak punya id, misal elemen DATE, SELECT

        if ($id_from == $id_to) {
          $found = true;
          if ($child->getName() == 'REPORT') {
            // hapus children dari elemen
            $remove = array();
            foreach ($child->children() as $grand_children) {
              $remove[] = $grand_children;
            }
            foreach ($remove as $item) {
              unset($item[0]);
            }
            // ganti atribut dengan atribut tujuan
            $remove = array();
            foreach ($child->attributes() as $attr) {
              $remove[] = $attr;
            }
            foreach ($remove as $item) {
              unset($item[0]);
            }
            foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
              $child->addAttribute($attr_key, $attr_value);
            }
          }
          $simplexml_temp = $child;
          break;
        }
      }

      // jika elemen id belum ada di tujuan, tambahkan elemen
      if (!$found) {
        $simplexml_temp = $simplexml_to->addChild($simplexml_child->getName(), (string) $simplexml_child);
        foreach ($simplexml_child->attributes() as $attr_key => $attr_value) {
          $simplexml_temp->addAttribute($attr_key, $attr_value);
        }
      }

      $this->merge_xml($simplexml_temp, $simplexml_child);
    }
  }

  function get_html_menu($laporan, $laporan_custom, $laporan_temp)
  {
    $xml_path = FCPATH . 'assets/fr/fr3/';
    $xml = $xml_path . $laporan;
    $xml_custom = $xml_path . $laporan_custom;
    $xml_merge = $xml_path . $laporan_temp;

    if (file_exists($xml)) {
      $doc = simplexml_load_file($xml);
    } else {
      throw new Exception($xml . " does not exist");
    }

    if (file_exists($xml_custom)) {
      $doc_custom = simplexml_load_file($xml_custom);

      // jika ada laporan custom, tambahkan ke laporan
      $this->merge_xml($doc, $doc_custom);
    }

    $doc->asXML($xml_merge);

    $len_tipe = count($doc->TIPE);
    $html = '';
    for ($tp = 0; $tp < $len_tipe; $tp++) {
      if ((string)$doc->TIPE[$tp]['caption'] !== 'STANDAR') {
        $html .= '<li><span class="folder">' . $doc->TIPE[$tp]['caption'] . '</span>';
        $html .= '<ul>';
      }
      $len_area = count($doc->TIPE[$tp]->AREA);
      for ($ar = 0; $ar < $len_area; $ar++) {
        if ((string)$doc->TIPE[$tp]->AREA[$ar]['caption'] !== 'SEMUA') {
          $html .= '<li><span class="folder">' . $doc->TIPE[$tp]->AREA[$ar]['caption'] . '</span>';
          $html .= '<ul>';
        }
        $len_group = count($doc->TIPE[$tp]->AREA[$ar]->GROUP);
        for ($i = 0; $i < $len_group; $i++) {
          $html .= '<li><span class="folder">' . $doc->TIPE[$tp]->AREA[$ar]->GROUP[$i]['caption'] . '</span>';
          $html .= '<ul>';
          $len_report = count($doc->TIPE[$tp]->AREA[$ar]->GROUP[$i]->REPORT);
          for ($j = 0; $j < $len_report; $j++) {
            $show_dialog = $doc->TIPE[$tp]->AREA[$ar]->GROUP[$i]->REPORT[$j]['showfilter'];
            $html .= '<li><span class="file"><a href="#" class="preview" data-tipe="' . $tp . '" data-area="' . $ar . '" data-group="' . $i . '" data-unit="' . $j . '" data-show="' . $show_dialog . '" >';
            $html .= $doc->TIPE[$tp]->AREA[$ar]->GROUP[$i]->REPORT[$j]['caption'] . '</a></span></li>';
          }
          $html .= '</ul>';
          $html .= '</li>';
        }
        if ((string)$doc->TIPE[$tp]->AREA[$ar]['caption'] !== 'SEMUA') {
          $html .= '</ul>';
          $html .= '</li>';
        }
      }
      if ((string)$doc->TIPE[$tp]['caption'] !== 'STANDAR') {
        $html .= '</ul>';
        $html .= '</li>';
      }
    }
    return $html;
  }
}
