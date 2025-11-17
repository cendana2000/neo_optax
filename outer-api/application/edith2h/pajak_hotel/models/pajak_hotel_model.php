<?php
class Pajak_hotel_model extends Pendataan_Model {

	var $fieldmap_detil;
	var $data_detil;
	
	function __construct()
  {		
    // Call the Model constructor
    parent::__construct();
		
		$this->fieldmap_detil = array(
			'idspt' => 'ID_SPT',		
			'gol' => 'GOLONGAN_KAMAR',
			'tarif' => 'TARIF',
			'jmlkamar' => 'JUMLAH_KAMAR',
			'jmlkamarlaku' => 'JUMLAH_KAMAR_YG_LAKU',
		);
		
  }
	
	function fill_data()
  {
    parent::fill_data();	
		
		$this->purge_wp = $this->input->post('purge_wp'); $this->purge_wp = $this->purge_wp ? $this->purge_wp : NULL;
		$wp = $this->input->post('wp') ? $this->input->post('wp') : NULL;
		
		if ($wp)
    {
      $wp = json_decode($wp);
      for ($i=0; $i < count($wp); $i++) {
        foreach($this->fieldmap_detil as $key => $value){
          switch ($key)
          {						
            default : $$key = isset($wp[$i]->$key) && $wp[$i]->$key ? $wp[$i]->$key : NULL;
          }
          if(isset($$key))
            $this->data_detil[$i][$value] = $$key;
        }
      }
    }    
  }
  
	function build_query_daftar($param)
	{
    $this->db->select("
			a.id_spt,
			a.nomor_spt,
			a.tanggal_spt,
			a.periode_awal,
			a.periode_akhir,
			a.tanggal_jatuh_tempo,
			r.nama_rekening,
			a.npwpd,
			a.nama_wp,
			a.jumlah,
			a.jumlah_pajak,
			a.alamat_wp,
			a.name,
			a.keterangan,
			a.uraian,
			tgl_bayar,
			iif((select coalesce(sum(c.id_sts),0) from spt b LEFT JOIN rincian_tbp c ON c.id_spt = b.id_spt where b.id_spt = a.id_spt) > 0, 'Penyetoran', iif((select coalesce(sum(c.id_tbp),0) from spt b LEFT JOIN rincian_tbp c ON c.id_spt = b.id_spt where b.id_spt = a.id_spt) > 0, 'Pembayaran',iif((select coalesce(count(c.id_spt), 0) from spt b LEFT JOIN penetapan c ON c.id_spt = b.id_spt where b.id_spt = a.id_spt) > 0, 'Penetapan','-'))) status
    ");
    $this->db->from('spt a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
	$this->db->join('rekening_pr pr', 'r.id_rekening = pr.id_rekening');
	$this->db->join('rincian_tbp s', 'a.id_spt = s.id_spt','left');
	$this->db->join('tbp z', 's.id_tbp = z.id_tbp','left');
	$this->db->where('a.tipe', $param['tipe']);
		$this->db->where('pr.kode_pr', PAJAK_HOTEL);
	}
	
	function build_query_form($id=0)
	{
    $this->db->select('
        a.id_spt,
        a.nomor_spt,
        a.status_spt,
        a.tanggal_spt,
        a.periode_awal,
        a.periode_akhir,
        a.id_rekening,
        r.kode_rekening,
        r.nama_rekening,
        a.id_wajib_pajak,
        a.npwpd,
        a.nama_wp,
        a.alamat_wp,
        a.tarif_rp,
        a.tarif_persen,
        a.jumlah,
        a.jumlah_pajak,
        a.lokasi,
        a.uraian,
        w.id_kecamatan,
        w.id_kelurahan,
        kec.nama_kecamatan,
        kel.nama_kelurahan,
        a.id_bt,
		p.nama_pejabat,
		pp.id_penetapan,
        tb.id_tbp,
        tb.id_sts,
		a.tanggal_jatuh_tempo
    ');
    $this->db->from('spt a');
    $this->db->join('rekening r', 'r.id_rekening = a.id_rekening');
    $this->db->join('wajib_pajak w', 'w.id_wajib_pajak = a.id_wajib_pajak');
    $this->db->join('kecamatan kec', 'kec.id_kecamatan = w.id_kecamatan');
    $this->db->join('kelurahan kel', 'kel.id_kelurahan = w.id_kelurahan', 'left');
	$this->db->join('pejabat_skpd p', 'p.id_pejabat_skpd = a.id_bt', 'left');
	$this->db->join('penetapan pp','pp.id_spt = a.id_spt','left');
	$this->db->join('rincian_tbp tb','tb.id_spt =a.id_spt','left');
    $this->db->where('a.id_spt', $id);
	}
	
	 // TAMBAHAN=======================================
	function build_query_hapus($id)
	{
		//$idspt = implode("|", $id);
		$this->db->select('id_billing')->from('spt')->where('id_spt', $id);
		$billing = $this->db->get()->row_array();
		$id_billing = implode("|", $billing);
		
		$this->db->where('id_spt', $id)->delete('detil_hotel');
		$this->db->where('id_spt', $id)->delete('spt');
		$this->db->where('id_billing', $id_billing )->delete('billing');
	}

	//TAMBAHAN========================================

	
  function check_dependency($id)
  {
		$this->db->trans_start();
		$this->db->select('a.ID_SPT');
		$this->db->select('(select count(b.ID_SPT) from RINCIAN_TBP b where b.ID_SPT = a.ID_SPT) BAYAR_PAKAI');
		$this->db->select('(select count(b.ID_SPT) from PENETAPAN b where b.ID_SPT = a.ID_SPT) TETAP_PAKAI');
		$this->db->where('a.ID_SPT', $id);         
		$result = $this->db->get('SPT a')->row_array();		
		$this->db->trans_complete();
		
		if ( $result['BAYAR_PAKAI'] > 0 || $result['TETAP_PAKAI'] > 0) 
    {
			return FALSE;
		} 
		else
		{
			return TRUE;
		}		
  }
	
	function save_detail()
  {
    $this->insert_detil_hotel();
  }
	
	function insert_detil_hotel()
  {	
		$this->db->where('ID_SPT', $this->id);
		$this->db->delete('DETIL_HOTEL');
		
		for ($i=0; $i<=count($this->data_detil)-1; $i++)
    {
      $this->data_detil[$i]['ID_SPT'] = $this->id;
			$this->db->insert('DETIL_HOTEL', $this->data_detil[$i]);
    }	
	}
	
	public function get_info_skpd_pemda()
	{
		$this->db->select('p.id_skpd, s.kode_skpd, s.nama_skpd');
		$this->db->from('pemda p');
		$this->db->join('skpd s', 's.id_skpd = p.id_skpd');
		$result = $this->db->get()->row_array();
		
		return $result;
	}
	
	function get_data_wp($id=0)
	{
		$this->db->select("*");
		$this->db->from('detil_hotel');
		$this->db->where('id_spt', $id);
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
}
