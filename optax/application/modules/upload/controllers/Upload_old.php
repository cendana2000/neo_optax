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
			'upload/uploadDetailModel' => 'realisasiDetail'
		));
	}

	public function index()
	{
		$user = $this->session->userdata();
		$where['realisasi_deleted_at'] = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['wajibpajak_npwpd'];
		$this->response(
			$this->select_dt(varPost(), 'realisasi', 'table', true, $where)
		);
	}

	function read($value = '')
	{
		$this->response($this->realisasi->read(varPost()));
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
					$batch[] = [
						'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
						'realisasi_detail_npwpd' => $user['wajibpajak_npwpd'],
						'realisasi_detail_parent' => $realisasi_id,
						'realisasi_detail_time' => $value[1],
						'realisasi_detail_penjualan_kode' => $value[2],
						'realisasi_detail_sub_total' => $value[3],
						'realisasi_detail_jasa' => $value[4],
						'realisasi_detail_pajak' => $value[5],
						'realisasi_detail_total' => $value[6],
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
			]);

			log_activity('Unggah laporan realisasi');

			$response = [
				'success' => true,
				'message' => 'Successfully saved data.',
				'id' => $realisasi_id
			];
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
		log_activity('Ubah laporan realisasi '.$ops['realisasi_no']);
		$this->response($ops);
	}

	public function delete()
	{
		$data = varPost();
		$data['realisasi_deleted_at'] = date("Y-m-d H:i:s");
		$operation = $this->realisasi->update($data['id'], $data);
		log_activity('Menghapus laporan realisasi '.$operation['realisasi_no']);
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		$operation = $this->realisasi->delete(varPost('id', varExist($data, $this->realisasi->get_primary(true))));
		log_activity('Menghapus laporan realisasi '.$operation['realisasi_no']);
		$this->response($operation);
	}
}

/* End of file realisasi.php */
/* Location: ./application/modules/realisasi/controllers/realisasi.php */