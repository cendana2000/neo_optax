<?php
/*
 * Class penomoran otomatis
 */
class autho
{
	var $CI = null;
	function __construct()
	{
		$this->CI = & get_instance();
	}

	function check_nomor_aktivitas($param)
	{
		$form 			= isset($param['tipe']) ? $param['tipe'] : '';
		$kode_objek 	= isset($param['kode_objek']) ? $param['kode_objek'] : '';
		$kode_rincian 	= isset($param['kode_rincian']) ? $param['kode_rincian'] : '';
		$tipe = '';

		switch ($form)
		{
			case 'SPT' 		: $tipe = 'SPT'; break;
			case 'KOHIR' 	: $tipe = 'KOHIR'; break;
			case 'REGISTER' : $tipe = 'REGISTER'; break;
		}

		/* ambil template sesuai tipe aktivitas */
		$db = $this->CI->db;
		$db->select('template')->from('penomoran')->where('tipe', $form);
		$result = $db->get()->row_array();

		if ( count($result) == 0 ){
			return false;
		}

		$only_nomor = FALSE;
		$template = $result['TEMPLATE'];
		$sql_cek = $template;

		/*  Proses {nomor}
		******************************************************************************************
		*/
		$pattern  = "{nomor:\d+(:AN)?}";
		preg_match_all($pattern, $template, $tmp);
		$tmp_nomor = $tmp[0];
		$digit_ = '';
		$digit = 1;

		//$first_digit = strpos ($template, "{nomor") + 1;
		if (count($tmp_nomor) > 0)
		{
			$nomor = $tmp_nomor[0];
			$tmp = explode(':', $nomor);
			$digit = $tmp[1];
			$digit_ = str_repeat('_', $digit);
			if (isset($tmp[2]) && $tmp[2] === 'AN')
			{
				$only_nomor =  TRUE;
				$tmp_nomor = str_replace(':AN', '', $nomor);
			}
			$sql_cek = str_replace("{".$nomor."}", $digit_, $sql_cek);
		}

		/*  Proses {bulan}
		******************************************************************************************
		*/
		$pattern  = "{bulan}";
		preg_match_all($pattern, $template, $tmp);
		$tmp_bulan = $tmp[0];
		if (count($tmp_bulan) > 0)
		{
			//$bulan = $this->bulan(date("m"));
			$bulan = date("m");
			$sql_cek = str_replace($pattern, $bulan, $sql_cek);
		}
		
		/*  Proses {BULAN}
		******************************************************************************************
		*/
		$pattern  = "{BULAN}";
		preg_match_all($pattern, $template, $tmp);
		$tmp_bulan = $tmp[0];
		if (count($tmp_bulan) > 0)
		{
			$bulan = $this->bulan_romawi(date("m"));
			$sql_cek = str_replace($pattern, $bulan, $sql_cek);
		}

		/*  Proses {tahun}
		******************************************************************************************
		*/
		$pattern  = "{tahun}";
		preg_match_all($pattern, $template, $tmp);
		$tmp_tahun = $tmp[0];
		if (count($tmp_tahun) > 0)
		{
			$tahun = date("Y");
			$sql_cek = str_replace($pattern, $tahun, $sql_cek);
		}

		/*  Proses {kode_objek}
		******************************************************************************************
		*/
		if($kode_objek){
			$pattern  = "{kode_objek}";
			preg_match_all($pattern, $template, $tmp);
			$tmp_ko = $tmp[0];
			if (count($tmp_ko) > 0)
			{
				$db->select('kode_objek')->from('modul_pr')->where('kode_pr', $param['kode_objek']);
				$skpd = $db->get()->row_array();
				$sql_cek = str_replace($pattern, $skpd['KODE_OBJEK'], $sql_cek);
			}
		}		
		/*  Proses {kode_rincian}
		******************************************************************************************
		*/
		if($kode_rincian){
			$pattern  = "{kode_rincian}";
			preg_match_all($pattern, $template, $tmp);
			$tmp_kr = $tmp[0];
			if (count($tmp_kr) > 0)
			{
				$db->select('kode_rincian')->from('rekening')->where('id_rekening', $param['kode_rincian']);
				$skpd = $db->get()->row_array();
				$sql_cek = str_replace($pattern, $skpd['KODE_RINCIAN'], $sql_cek);
			}
		}
		
		if ($digit_ != '')
		{
			$first_digit = strpos ($sql_cek, $digit_) + 1;

			/* cek nomor terakhir */
			switch ($tipe)
			{
				case 'SPT' : $tabel = 'SPT'; break;
				case 'KOHIR' : $tabel = 'PENETAPAN'; break;
				case 'REGISTER' : $tabel = 'WAJIB_PAJAK'; break;
			}

			if ($form=='REGISTER')
			{
				/* untuk form pendaftaran yang dicek dari NOMOR_REG */
				$kolom_nomor = 'a.nomor_reg';
			}
			else if ($form=='KOHIR'){
				/* untuk form penetapan yang dicek dari NOMOR_KOHIR */
				$kolom_nomor = 'a.nomor_kohir';
			}
			else if ($form=='SPT'){
				/* untuk form pendataan yang dicek dari NOMOR_SPT */
				$kolom_nomor = 'a.nomor_spt';
			}
			
			$db->select('coalesce(max(substring('.$kolom_nomor.' from '.$first_digit.' for '.$digit.')), 0) maxno');
			$db->from($tabel.' a');
			if ($form=='SPT')
			{
				$db->join('rekening r', 'r.id_rekening = a.id_rekening');
				$db->join('rekening_pr pr', 'r.id_rekening = pr.id_rekening');
				$db->join('modul_pr mpr', 'mpr.kode_pr = pr.kode_pr');
				$db->where('a.tipe', $param['tipe_oasa']);
			}
			
			
			if (!$only_nomor)
			{
				$db->where($kolom_nomor." like '".$sql_cek."'");
				if ($form=='SPT')
				{				
					if ($kode_objek && count($tmp_ko) > 0) $db->where('pr.kode_pr', $param['kode_objek']);
					if ($kode_rincian && count($tmp_kr) > 0) $db->where('a.id_rekening', $param['kode_rincian']);
				}
			}

			$result = $db->get()->row_array();

			if (count($result) > 0)
				$maxno = (int) $result['MAXNO'] == 0 ? 1 : (int) $result['MAXNO'] + 1;
			else
				$maxno = 1;

			$nomor = $this->FormatNoTrans($maxno, $digit);
			return substr_replace($sql_cek, $nomor, $first_digit - 1, $digit);
		}
		else
		return $sql_cek;
	}

	function FormatNoTrans($num,$panjang) {
		$pjg_kar = strlen($num);
		$rpt = $panjang - $pjg_kar;
		$prev = '';
		if($rpt > 0){
			for($u=0;$u<$rpt;$u++){
				$prev.="0";
			}
			$NoTrans = $prev.$num;
		}
		else{
			$NoTrans = $num;
		}

		return $NoTrans;
	}

	function bulan($bulan)
	{
		switch ($bulan)
		{
			case  1 : $bulan = "JAN"; break;
			case  2 : $bulan = "FEB"; break;
			case  3 : $bulan = "MAR"; break;
			case  4 : $bulan = "APR"; break;
			case  5 : $bulan = "MEI"; break;
			case  6 : $bulan = "JUN"; break;
			case  7 : $bulan = "JUL"; break;
			case  8 : $bulan = "AGS"; break;
			case  9 : $bulan = "SEP"; break;
			case 10 : $bulan = "OKT"; break;
			case 11 : $bulan = "NOV"; break;
			case 12 : $bulan = "DES"; break;
		}
		return $bulan;
	}
	
	function bulan_romawi($bulan)
	{
		switch ($bulan)
		{
			case  1 : $bulan = "I"; break;
			case  2 : $bulan = "II"; break;
			case  3 : $bulan = "III"; break;
			case  4 : $bulan = "IV"; break;
			case  5 : $bulan = "V"; break;
			case  6 : $bulan = "VI"; break;
			case  7 : $bulan = "VII"; break;
			case  8 : $bulan = "VIII"; break;
			case  9 : $bulan = "IX"; break;
			case 10 : $bulan = "X"; break;
			case 11 : $bulan = "XI"; break;
			case 12 : $bulan = "XII"; break;
		}
		return $bulan;
	}
}