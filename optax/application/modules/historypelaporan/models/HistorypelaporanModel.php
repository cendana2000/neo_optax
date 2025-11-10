<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistorypelaporanModel extends Base_Model
{
	public function __construct()
	{
		parent::__construct($model);
		//Do your magic here
	}

	public function getJenisPajakName($npwpd)
	{
		$sql = "select
				pw.wajibpajak_npwpd,
				lower(
					(
						select
							pj2.jenis_nama
						from
							pajak_jenis pj2
						where
							pj2.jenis_id = pj.jenis_parent
					)
				) as jenis_pajak
			from
				pajak_wajibpajak pw
				left join pajak_jenis pj on pj.jenis_id = pw.wajibpajak_sektor_nama
			where
				pw.wajibpajak_npwpd = '$npwpd'
		";

		$query = $this->db->query($sql);
		if ($query) {
			return $query->result_array();
		} else {
			return false;
		}
	}
}

/* End of file satuananggotaModel.php */
/* Location: ./application/modules/satuananggota/models/satuananggotaModel.php */