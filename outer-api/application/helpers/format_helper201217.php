<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('bulan_list'))
{
  function bulan_list($kosong = 0)
  {
    $CI =& get_instance();
    $CI->lang->load('calendar');

    if($kosong) $result[0] = 'Semua bulan';
    $result['01'] = $CI->lang->line('cal_january');
    $result['02'] = $CI->lang->line('cal_february');
    $result['03'] = $CI->lang->line('cal_march');
    $result['04'] = $CI->lang->line('cal_april');
    $result['05'] = $CI->lang->line('cal_may');
    $result['06'] = $CI->lang->line('cal_june');
    $result['07'] = $CI->lang->line('cal_july');
    $result['08'] = $CI->lang->line('cal_august');
    $result['09'] = $CI->lang->line('cal_september');
    $result['10'] = $CI->lang->line('cal_october');
    $result['11'] = $CI->lang->line('cal_november');
    $result['12'] = $CI->lang->line('cal_december');

    return $result;
  }
}

if ( ! function_exists('bln_list'))
{
  function bln_list($kosong = 0)
  {
    $CI =& get_instance();
    $CI->lang->load('calendar');

    if($kosong) $result[0] = 'Semua bulan';
    $result['01'] = $CI->lang->line('cal_jan');
    $result['02'] = $CI->lang->line('cal_feb');
    $result['03'] = $CI->lang->line('cal_mar');
    $result['04'] = $CI->lang->line('cal_apr');
    $result['05'] = $CI->lang->line('cal_may');
    $result['06'] = $CI->lang->line('cal_jun');
    $result['07'] = $CI->lang->line('cal_jul');
    $result['08'] = $CI->lang->line('cal_aug');
    $result['09'] = $CI->lang->line('cal_sep');
    $result['10'] = $CI->lang->line('cal_oct');
    $result['11'] = $CI->lang->line('cal_nov');
    $result['12'] = $CI->lang->line('cal_dec');

    return $result;
  }
}

if ( ! function_exists('nama_bulan'))
{
  function nama_bulan($bulan)
  {
    $array_bulan = bulan_list();
    if(strlen($bulan) == 1) $bulan = '0'.$bulan;
    return $array_bulan[$bulan];
  }
}

if ( ! function_exists('nama_bln'))
{
  function nama_bln($bulan)
  {
    $array_bulan = bln_list();
    if(strlen($bulan) == 1) $bulan = '0'.$bulan;
    return $array_bulan[$bulan];
  }
}

if ( ! function_exists('bulan_diff'))
{
	function bulan_diff($awal,$akhir)
	{
		$harilahir=gregoriantojd(substr($awal,5,2),substr($awal,-2,2),substr($awal,0,4));
		$hariini=gregoriantojd(substr($akhir,5,2),substr($akhir,-2,2),substr($akhir,0,4));
		
		$umur=$hariini-$harilahir;
		//$sisa=$umur%365;
		$bulan=$umur/30;

		return ceil($bulan); 
	}
}

if ( ! function_exists('to_rupiah'))
{
  function to_rupiah($value)
  {
    if($value < 0)
    {
      return '( Rp '.number_format(abs($value), 0, ',', '.').' )';
    }
    else
    {
      return 'Rp '.number_format($value, 0, ',', '.').'  ';
    }
  }
}

if ( ! function_exists('format_rupiah'))
{
  function format_rupiah($value)
  {
    if($value < 0)
    {
      return '( '.number_format(abs($value), 2, ',', '.').' )';
    }
    else
    {
      return '  '.number_format($value, 2, ',', '.').'  ';
    }
  }
}

if ( ! function_exists('prepare_numeric'))
{
  function prepare_numeric($value, $default = null)
  {
    if(isset($value) && $value != '')
      return floatval(str_replace('.','',$value)) * 1;
    return $default;
  }
}

if ( ! function_exists('format_integer'))
{
  function format_integer($value, $default = null)
  {
    if(isset($value) && $value != '')
      return floatval(str_replace('.00','',$value)) * 1;
    return $default;
  }
}

if ( ! function_exists('format_date'))
{
  function format_date($date, $style='d/m/Y')
  {
    if (isset($date))
      return date($style, strtotime( $date ) );
    return '';
  }
}

if ( ! function_exists('prepare_date'))
{
  function prepare_date($date)
  {
    if (isset($date) && $date != '')
      return implode( "-", array_reverse( explode("/", $date ) ) );
    return null;
  }
}

if ( ! function_exists('get_where_str'))
{
  function get_where_str($param, $fieldmap)
  {
    $wh = array();
    foreach($param as $key => $value){
      if (array_key_exists($key, $fieldmap))
      {
        $fld = "";
        $datatype = isset($value['search_datatype']) ? $value['search_datatype'] : null;
        $op = $value['search_op'];
        if ($datatype === 'date')
        {
          $fld = $fieldmap[ $key ];
          $str = isset($value['search_str']) ? strtoupper(prepare_date($value['search_str'])) : null;
          $str2 = isset($value['search_str2']) ? strtoupper(prepare_date($value['search_str2'])) : null;
        }
        else
        {
          $fld = "UPPER(".$fieldmap[ $key ].")";
          $str = isset($value['search_str']) ? strtoupper($value['search_str']) : null;
          $str2 = isset($value['search_str2']) ? strtoupper($value['search_str2']) : null;
        }

        if ($datatype === 'numeric')
        {
          switch($op)
          {
            case "eq" : $fld .= " = "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "ne" : $fld .= " != "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "lt" : $fld .= " < "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "le" : $fld .= " <= "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "gt" : $fld .= " > "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "ge" : $fld .= " <= "; $str = $str; $wh[ $fld ] = (double)$str; break;
            case "in" : $fld .= " >= "; $fld1 = $fieldmap[ $key ]." <= "; $wh[ $fld ] = (double)$str; $wh[ $fld1 ] = (double)$str2; break;
            default : ;
          }
        }
		else if ($datatype === 'time')
        {
          switch($op)
          {
            case "cn" : $fld .= " LIKE "; $str = "%".$str."%"; $wh[ $fld ] = $str; break;
            case "ne" : $fld .= " != "; $str = $str; $wh[ $fld ] = $str; break;
            case "lt" : $fld .= " < "; $str = $str; $wh[ $fld ] = $str; break;
            case "le" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "gt" : $fld .= " > "; $str = $str; $wh[ $fld ] = $str; break;
            case "ge" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "in" : $fld .= " >= "; $fld1 = $fieldmap[ $key ]." <= "; $wh[ $fld ] = $str; $wh[ $fld1 ] = $str2; break;
            default : ;
          }
		}
        else if ($datatype === 'date')
        {
          switch($op)
          {
            case "eq" : $fld .= " = "; $str = $str; $wh[ $fld ] = $str; break;
            case "ne" : $fld .= " != "; $str = $str; $wh[ $fld ] = $str; break;
            case "lt" : $fld .= " < "; $str = $str; $wh[ $fld ] = $str; break;
            case "le" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "gt" : $fld .= " > "; $str = $str; $wh[ $fld ] = $str; break;
            case "ge" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "in" : $fld .= " >= "; $fld1 = $fieldmap[ $key ]." <= "; $wh[ $fld ] = $str; $wh[ $fld1 ] = $str2; break;
            default : ;
          }
        }
        else if ($datatype === 'datetime')
        {
					$str = strtotime($str);
					$str = date('dd.mm.Y H:i:s', $str);
          switch($op)
          {
            case "eq" : $fld .= " = "; $str = $str; $wh[ $fld ] = $str; break;
            case "ne" : $fld .= " != "; $str = $str; $wh[ $fld ] = $str; break;
            case "lt" : $fld .= " < "; $str = $str; $wh[ $fld ] = $str; break;
            case "le" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "gt" : $fld .= " > "; $str = $str; $wh[ $fld ] = $str; break;
            case "ge" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "in" : $fld .= " >= "; $fld1 = $fieldmap[ $key ]." <= "; $wh[ $fld ] = $str; $wh[ $fld1 ] = $str2; break;
            default : ;
          }
        }
        else
        {
          switch($op)
          {
            case "eq" : $fld .= " = "; $str = $str; $wh[ $fld ] = $str; break;
            case "ne" : $fld .= " != "; $str = $str; $wh[ $fld ] = $str; break;
            case "lt" : $fld .= " < "; $str = $str; $wh[ $fld ] = $str; break;
            case "le" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "gt" : $fld .= " > "; $str = $str; $wh[ $fld ] = $str; break;
            case "ge" : $fld .= " <= "; $str = $str; $wh[ $fld ] = $str; break;
            case "bw" : $fld .= " LIKE "; $str = $str."%"; $wh[ $fld ] = $str; break;
            case "bn" : $fld .= " NOT LIKE "; $str = $str."%"; $wh[ $fld ] = $str; break;
            case "in" : $fld .= " >= "; $fld1 = "UPPER(".$fieldmap[ $key ].") <= "; $wh[ $fld ] = $str; $wh[ $fld1 ] = $str2; break;
            case "ew" : $fld .= " LIKE "; $str = "%".$str; $wh[ $fld ] = $str; break;
            case "en" : $fld .= " NOT LIKE "; $str = "%".$str; $wh[ $fld ] = $str; break;
            case "cn" : $fld .= " LIKE "; $str = "%".$str."%"; $wh[ $fld ] = $str; break;
            case "nc" : $fld .= " NOT LIKE "; $str = "%".$str."%"; $wh[ $fld ] = $str; break;
            case "nu" : $str = " NULL "; $wh[ $fld ] = $str; break;
            case "nn" : $str = " NOT NULL "; $wh[ $fld ] = $str; break;
            default : ;
          }
        }
      }
    }
    return $wh;
  }
}

if ( ! function_exists('get_order_by_str'))
{
  function get_order_by_str($param, $fieldmap)
  {
    $ob = '';
    if (array_key_exists($param, $fieldmap))
    {
      $ob = $fieldmap[ $param ];
    }
    return $ob;
  }
}

if ( ! function_exists('get_denda'))
{
	function get_denda($tgl_bayar,$tgl_jatuh_tempo,$nominal)
	{
		$selisih = bulan_diff(prepare_date($tgl_jatuh_tempo), prepare_date($tgl_bayar));
		if ($selisih >= 24) $selisih = 24;
				
		$denda = 0;
		if (prepare_date($tgl_bayar) > prepare_date($tgl_jatuh_tempo))
		  $denda = 0.02 * $nominal * $selisih;

		return ceil($denda);
	}
}

if ( ! function_exists('build_or_where'))
{
  function build_or_where($param)
  {
    $str = '';
    $prefix = ' OR ';
    foreach ($param as $k => $v)
    {
      if ($str !== '') $str .= $prefix;

      $str .= "(".$k." '".$v."')";
    }
    return "(".$str.")";
  }
}

if  (  !  function_exists('get_title'))
{
  function  get_title($modul_name  =  '')
  {
    $title  =  APP_NAME;
    if  ($modul_name)  $title  .=  '  -  '.$modul_name;
    return  $title;
  }
}

if  (  !  function_exists('simpan_logaktivitas'))
{
  function  simpan_logaktivitas($user, $modul, $aksi, $keterangan)
  {
    $ci =& get_instance();
    $ci->load->database();

    date_default_timezone_set('Asia/Jakarta');
    $waktu   = date('d.m.Y.H.i.s');

    $data = array(
        'USERNAME' => $user,
        'MODUL' => $modul,
        'AKSI' => $aksi,
        'KETERANGAN' => $keterangan,
        'WAKTU' => $waktu
      );
    $ci->db->insert('LOGAKTIVITAS', $data);
  }
}

if  (  !  function_exists('get_logo'))
{
	function  get_logo($id_spt=0)
	{
		$ci =& get_instance();
		$ci->load->database();
		$logo = $ci->db->select('gambar')->where('id_spt',$id_spt)->get('detil_reklame')->row_array();
		if ($logo && $logo['GAMBAR'] !== null) 
			return base_url().'/uploads/'.$logo['GAMBAR'];
		else 
			return  base_url().'assets/img/no-image.png'; 
	}
}

if  (  !  function_exists('get_nama_bulan'))
{
	function  get_nama_bulan($bulan)
	{
		
		switch($bulan)
		{
			case 1 : $nama_bulan= 'JANUARI';  break;
			case 2 : $nama_bulan= 'FEBRUARI';  break;
			case 3 : $nama_bulan= 'MARET';  break;
			case 4 : $nama_bulan= 'APRIL';  break;
			case 5 : $nama_bulan= 'MEI';  break;
			case 6 : $nama_bulan= 'JUNI';  break;
			case 7 : $nama_bulan= 'JULI';  break;
			case 8 : $nama_bulan= 'AGUSTUS';  break;
			case 9 : $nama_bulan= 'SEPTEMBER';  break;
			case 10 : $nama_bulan= 'OKTOBER';  break;
			case 11 : $nama_bulan= 'NOPEMBER';  break;
			case 12 : $nama_bulan= 'DESEMBER';  break;
			default : ;
		}
		return $nama_bulan;
	}
}
