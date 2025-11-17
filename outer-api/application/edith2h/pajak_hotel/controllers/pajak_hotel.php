<?php

class Pajak_hotel extends Pendataan_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->modul_name = 'pajak_hotel';
    $this->modul_display_sa = 'Pajak Hotel (Self Assessment)';
    $this->modul_display_oa = 'Pajak Hotel (Official Assessment)';
    $this->view_daftar = 'pajak_hotel_view';
    $this->view_form = 'pajak_hotel_form';
    $this->report_daftar = 'Daftar Pajak Hotel';
    $this->report_form = 'Form Pajak Hotel';
		$this->kode_pajak = PAJAK_HOTEL;
    $this->load->model('pajak_hotel_model', 'data_model');

		$this->message_dihapus = 'Pajak Hotel telah dihapus.';
		$this->message_gagal_dihapus = 'Pajak Hotel tidak bisa dihapus.';
  }

  public function index()
  {		
    
  }
  
  public function form_sa($id=0)
  {
    $this->load->model('auth/login_model', 'auth');
    $data['title'] = PRODUCT.' - '.$this->modul_display_sa;
    $data['breadcrumbs'] = $this->modul_display_sa;
    $data['modul'] = $this->modul_name;
    $data['akses'] = $this->access;
    $data['tipe'] = 'SA';
    $data['link_back'] = '/daftar_sa/';
    $data['form'] = '/form_sa';
		$data['skpd_pemda'] = $this->data_model->get_info_skpd_pemda();
		$data['nomor_spt'] = $this->data_model->get_no_spt(PAJAK_HOTEL);
    $data['report_form'] = $this->report_form;
		if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content'] = $this->view_form;
    $this->load->view('layout/template', $data);
  }

  public function form_oa($id=0)
  {
    $this->load->model('auth/login_model', 'auth');
    $data['title'] = PRODUCT.' - '.$this->modul_display_oa;
    $data['breadcrumbs'] = $this->modul_display_oa;
    $data['modul'] = $this->modul_name;
    $data['akses'] = $this->access;
    $data['tipe'] = 'OA';
    $data['link_back'] = '/daftar_oa/';
    $data['form'] = '/form_oa';
		$data['nomor_spt'] = $this->data_model->get_no_spt(PAJAK_HOTEL);
    $data['report_form'] = $this->report_form;
    if ($id!==0)
    {
      $data['data'] = $this->data_model->get_data_by_id($id);
    }

    $data['main_content'] = $this->view_form;
    $this->load->view('layout/template', $data);
  }

  public function get_daftar($tipe){
	parent::get_daftar($tipe);
    $aggregate = $this->data_model->get_data($this->search_param, TRUE);
	$count = $aggregate['CNT'];

    $response = (object) NULL;
    $response->sql = $this->db->queries;
    if($count == 0) // tidak ada data
    {
			// menambahkan userdata jika ada
			  $agg_fields = $this->data_model->fieldmap_daftar_aggregate;
			  foreach($agg_fields as $kolom => $value)
			  {
				$response->userdata[$kolom] = $aggregate[ strtoupper($kolom) ];
			  }
			  echo json_encode($response);
			  return;
    }

    $page = $this->page;
    $limit = $this->limit;
    $total_pages = ceil($count/$limit);

    if ($page > $total_pages) $page = $total_pages;
    $start = $limit * $page - $limit;
    if($start < 0) $start = 0;
    $this->search_param['limit'] = array(
        'start' => $start,
        'end' => $limit
    );

    $result = $this->data_model->get_data($this->search_param);

    $response->page = $page;
    $response->total = $total_pages;
    $response->records = $count;
    $response->sql = $this->db->queries;
	
	$jum_potensi = 0;
	$jum_pajak = 0;

    $fields = $this->data_model->fieldmap_daftar;

    for($i=0; $i<count($result); $i++)
    {
	  $jum_potensi+=$result[$i][$fields[9]];
	  $jum_pajak+=$result[$i][$fields[10]];
	  
      $response->rows[$i]['id'] = $result[$i][$fields[0]];
      $data = array();
      for ($n=1; $n < count($fields); $n++)
      {
        $data[] = $result[$i][$fields[$n]];
      }
      $response->rows[$i]['cell'] = $data;
    }
	
	// menambahkan userdata jika ada
		$agg_fields = $this->data_model->fieldmap_daftar_aggregate;
		//print_r($agg_fields);
		
		foreach($agg_fields as $kolom => $value)
		{
		  $response->userdata[$kolom] = $aggregate[ strtoupper($kolom) ];
		}
		
	$response->userdata['wp'] 	= 'JUMLAH';
	//$response->userdata['omset'] = $jum_potensi;
	//$response->userdata['nom'] 	= $jum_pajak;
		
    echo json_encode($response);
  }
 
	function validasi_form()
	{
    $this->form_validation->set_rules('nospt', 'Nomor SPT', 'required|trim|max_length[20]');
    $this->form_validation->set_rules('tgl', 'Tanggal', 'required|trim');
    $this->form_validation->set_rules('idrek', 'Jenis Pajak/Retribusi', 'required|trim');
    $this->form_validation->set_rules('npwpd', 'NPWPD', 'required|trim');
    $this->form_validation->set_rules('nama', 'Nama WP/WR', 'required|trim');
    $this->form_validation->set_rules('alamat', 'Alamat WP/WR', 'required|trim');
    $this->form_validation->set_rules('awal', 'Periode Awal', 'required|trim');
    $this->form_validation->set_rules('akhir', 'Periode Akhir', 'required|trim');
    $this->form_validation->set_rules('omset', 'Omset', 'required|trim');
	  $this->form_validation->set_rules('jml', 'Jumlah', 'required|trim');
    $this->form_validation->set_rules('lokasi', 'Lokasi', 'required|trim');
    $this->form_validation->set_rules('uraian', 'Uraian', 'required|trim');

    $this->form_validation->set_message('required', '%s tidak boleh kosong.');
    $this->form_validation->set_message('max_length', '%s tidak boleh melebihi %s karakter.');
    $this->form_validation->set_message('integer', '%s harus angka.');
	}
	
  public function generateReport($id)
  {
    // load view yang akan digenerate atau diconvert
    // contoh kita akan membuat pdf dari halaman welcome codeigniter
    $data['data'] = $this->data_model->get_data_by_id($id);
    $this->load->view('report',$data);
    // dapatkan output html
    
    $html = $this->output->get_output();
    
    // Load/panggil library dompdfnya
    $this->load->library('dompdf_gen');
    
    // Convert to PDF
    $this->dompdf->load_html($html);
    
    $this->dompdf->render();
    
    //utk menampilkan preview pdf
    $this->dompdf->stream("skpd_hotel.pdf",array('Attachment'=>0));
  }
	
	function get_wp($id=0)
	{
    $response = (object) NULL;

    if ($id === 0)
    {
      echo json_encode($response);
      return;
    }

    $result = $this->data_model->get_data_wp($id);

    if ($result){
      for($i=0; $i<count($result); $i++)
      {
        $response->rows[$i]['id'] = $result[$i]['ID_DETIL_HOTEL'];
        $response->rows[$i]['cell']=array(
            $result[$i]['ID_DETIL_HOTEL'],
            $result[$i]['GOLONGAN_KAMAR'],
            $result[$i]['TARIF'],
            $result[$i]['JUMLAH_KAMAR'],
            $result[$i]['JUMLAH_KAMAR_YG_LAKU'],
        );
      }
    }
    echo json_encode($response);
	}
	
}