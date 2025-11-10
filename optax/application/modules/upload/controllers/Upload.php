<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;


class Upload extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'upload/uploadModel' => 'realisasi',
			'upload/uploadDetailModel' => 'realisasiDetail',
			'upload/uploadViewModel'	=> 'viewRealisasi'
		));
	}

	public function index()
	{
		//code ori

		// $data 									= varPost();
		// $user 									= $this->session->userdata();
		// $where['realisasi_deleted_at'] 			= null;
		// $where['realisasi_wajibpajak_npwpd'] 	= $user['wajibpajak_npwpd'];

		// if ($data['filterBulan'] != null) {
		// 	$data = explode('-', $data['filterBulan']);

		// 	$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $data[1] . '\''] = null;
		// 	$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $data[0] . '\''] = null;
		// }

		// $opr 									= $this->select_dt(varPost(), 'realisasi', 'table', true, $where);

		// $get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		// sum(realisasi_pajak) as total_pajak,
		// sum(realisasi_sub_total) as total_subtotal,
		// sum(realisasi_total) as total_total,")
		// 	->where($where)
		// 	->get('pajak_realisasi')
		// 	->row();

		//tambahan code menampilkan total perbulan
		$data 									= varPost();
		$user 									= $this->session->userdata();
		$where['realisasi_wajibpajak_npwpd'] 	= $user['wajibpajak_npwpd'];

		if ($data['filterBulan'] != null) {
			$data = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_masa_pajak) = \'' . $data[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_masa_pajak) = \'' . $data[0] . '\''] = null;
		}

		$opr 									= $this->select_dt(varPost(), 'viewRealisasi', 'table', true, $where);

		$get_total = $this->db->select("sum(realisasi_total_jasa) as last_total_jasa,
		sum(realisasi_total_pajak) as last_total_pajak,
		sum(realisasi_total_sub_total) as last_total_subtotal,
		sum(realisasi_total_grand_total) as last_total_grand_total,")
			->where($where)
			->get('v_realisasi_upload')
			->row();

		$opr['sumtotal'] = $get_total;
		$opr['npwpd'] 	 = $user['wajibpajak_npwpd'];
		$this->response(
			$opr
		);
	}

	function read($value = '')
	{
		$this->response($this->realisasi->read(varPost()));
	}

	public function detail()
	{
		$where['realisasi_detail_parent'] = varPost('realisasi_detail_parent');
		$opr = $this->select_dt(varPost(), 'realisasiDetail', 'table', true, $where);
		$get_total = $this->db
			->select("sum(realisasi_detail_jasa) as total_jasa,
		sum(realisasi_detail_pajak) as total_pajak,
		sum(realisasi_detail_sub_total) as total_subtotal,
		sum(realisasi_detail_total) as total_total,")
			->where($where)
			->get('pajak_realisasi_detail')
			->row();
		$opr['sumtotal'] = $get_total;
		$this->response(
			$opr
		);
	}

	function select($value = '')
	{
		$user = $this->session->userdata();

		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['wajibpajak_npwpd'];
		$this->response($this->realisasi->select(array('filters_static' => $where)));
	}

	public function store()
	{
		$data = varPost();
		$user = $this->session->userdata();

		// upload file realisasi pajak
		$file = $_FILES['laporan_realisasi']['name'];
		$new_name = date('d-m-y-H-i-s') . '.xlsx';
		$config['upload_path']  = FCPATH . 'assets/laporan/laporan_realisasi/';
		$config['allowed_types'] = 'xlsx';
		$config['max_size'] = 10000;
		$config['file_name'] = $new_name;

		$this->upload->initialize($config);
		if ($this->upload->do_upload('laporan_realisasi')) {
			if ($data['periode_upload'] != null) {
				$excel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$laporan_realisasi = FCPATH . 'assets/laporan/laporan_realisasi/' . $new_name;
				$spreadSheet  = $excel->load($laporan_realisasi);
				$dataAsAssocArray = $spreadSheet->getActiveSheet()->toArray();
				$dataDetail  = $dataAsAssocArray;

				$realisasi_id = gen_uuid($this->realisasi->get_table());
				$npwpd = $user['wajibpajak_npwpd'];
				$periode = $data['periode_upload'];
				$batch = [];

				// Insert Detail Realisasi
				unset($dataDetail[0], $dataDetail[1], $dataDetail[2], $dataDetail[3]);

				foreach ($dataDetail as $value) {
					if ($value[0] == 'Total') {
						$subtotal = $value[3];
						$serviceCharge = $value[4];
						$pajak = $value[5];
						$total = $value[6];
						continue;
					} else {
						if (
							$value[3] == 0 || $value[5] == 0 || $value[6] == 0
						) {
							// Skip this value if any of the values are 0 or null
							continue;
						}
						$batch[] = [
							'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
							'realisasi_detail_npwpd' => $user['wajibpajak_npwpd'],
							'realisasi_detail_parent' => $realisasi_id,
							'realisasi_detail_time' => $value[1],
							'realisasi_detail_penjualan_kode' => $value[2],
							'realisasi_detail_sub_total' => (float)str_replace(',', '', $value[3]),
							'realisasi_detail_jasa' => $value[4],
							'realisasi_detail_pajak' => $value[5],
							'realisasi_detail_total' => (float)str_replace(',', '', $value[6]),
						];
					}
				}
				// die(json_encode($batch));
				$this->db->insert_batch('pajak_realisasi_detail', $batch);

				// Insert data parent laporan realisasi
				$this->db->insert('pajak_realisasi', [
					'realisasi_id' => $realisasi_id,
					'realisasi_no' => 0,
					'realisasi_wajibpajak_id' => 0,
					'realisasi_wajibpajak_npwpd' => $npwpd,
					'realisasi_tanggal' => $periode,
					'realisasi_sub_total' => $subtotal,
					'realisasi_jasa' => $serviceCharge,
					'realisasi_pajak' => $pajak,
					'realisasi_total' => $total,
					'realisasi_created_at' => date("Y-m-d H:i:s")
				]);

				log_activity('Unggah laporan realisasi');

				$response = [
					'success' => true,
					'message' => 'Successfully saved data.',
					'id' => $realisasi_id
				];
			} else {
				log_activity('Periode laporan harus diisi');
				$response = [
					'success' => false,
				];
			}
		} else {
			log_activity('Gagal unggah laporan realisasi');
			$response = [
				'success' => false,
			];
		}

		$this->response($response);
	}


	public function update()
	{
		$data = varPost();
		$ops = $this->realisasi->update(varPost('id', varExist($data, $this->realisasi->get_primary(true))), $data);
		log_activity('Ubah laporan realisasi ' . $ops['realisasi_no']);
		$this->response($ops);
	}

	public function delete()
	{
		$data = varPost();
		$data['realisasi_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->realisasi->update($data['id'], $data);
		log_activity('Menghapus laporan realisasi ' . $operation['realisasi_no']);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->realisasi->delete(varPost('id', varExist($data, $this->realisasi->get_primary(true))));
		log_activity('Menghapus laporan realisasi ' . $operation['realisasi_no']);
		$this->response($operation);
	}
}

/* End of file realisasi.php */
/* Location: ./application/modules/realisasi/controllers/realisasi.php */