<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;


class RekapTransaksi extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'upload/uploadModel' => 'realisasi',
			'upload/uploadDetailModel' => 'realisasiDetail',
			'realisasipajak/RealisasipajakModel' => 'realisasipajak',
			'realisasipajak/RealisasipajakdetailModel' => 'realisasipajakdetail',
		));
	}

	public function index()
	{
		$data 								 = varPost();
		$user 								 = $this->session->userdata();
		$where['realisasi_deleted_at'] 		 = null;
		$where['realisasi_wajibpajak_npwpd'] = $user['wajibpajak_npwpd'];

		// print_r($user);

		if ($data['filterBulan'] != null) {
			$data = explode('-', $data['filterBulan']);

			$where['EXTRACT(\'month\' from  realisasi_tanggal) = \'' . $data[1] . '\''] = null;
			$where['EXTRACT(\'year\' from  realisasi_tanggal) = \'' . $data[0] . '\''] = null;
		}

		$opr = $this->select_dt(varPost(), 'realisasi', 'table', true, $where);

		$get_total = $this->db->select("sum(realisasi_jasa) as total_jasa,
		sum(realisasi_pajak) as total_pajak,
		sum(realisasi_sub_total) as total_subtotal,
		sum(realisasi_total) as total_total,")
			->where($where)
			->get('pajak_realisasi')
			->row();

		$opr['sumtotal'] = $get_total;
		$opr['tarif'] = (int)$this->db->select("jenis_tarif")
			->where("jenis_id", $user["wajibpajak_sektor_nama"])
			->get('pajak_jenis')
			->row()
			->jenis_tarif;
		$opr['npwpd'] = $user['wajibpajak_npwpd'];
		$this->response(
			$opr
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
		// print_r('<pre>');print_r($data);print_r('</pre>');exit;
		$user = $this->session->userdata();
		$wp_id = $user['wajibpajak_id'];
		$npwpd = $user['wajibpajak_npwpd'];
		$periode = $data['periode_upload'];
		$sum_subtotal = $data['sum_subtotal'];
		$sum_service = $data['sum_service'];
		$sum_tax = $data['sum_tax'];
		$sum_total = $data['sum_total'];

		$sum_subtotal = preg_replace('/\D/', '', $sum_subtotal);
		$sum_service = preg_replace('/\D/', '', $sum_service);
		$sum_tax = preg_replace('/\D/', '', $sum_tax);
		$sum_total = preg_replace('/\D/', '', $sum_total);

		$realisasi_id = gen_uuid($this->realisasi->get_table());

		foreach ($data['time'] as $key => $value) {
			$kode = $data['receiptno'][$key];
			$subtotal = $data['subtotal'][$key];
			$tax = $data['tax'][$key];
			$total = $data['total'][$key];
			$service = $data['service'][$key];
			$subtotal = preg_replace('/\D/', '', $subtotal);
			$tax = preg_replace('/\D/', '', $tax);
			$total = preg_replace('/\D/', '', $total);
			$service = preg_replace('/\D/', '', $service);
			$batch[] = [
				'realisasi_detail_id' => gen_uuid($this->realisasiDetail->get_table()),
				'realisasi_detail_npwpd' => $user['wajibpajak_npwpd'],
				'realisasi_detail_parent' => $realisasi_id,
				'realisasi_detail_time' => $value,
				'realisasi_detail_penjualan_kode' => $kode,
				'realisasi_detail_sub_total' => $subtotal,
				'realisasi_detail_jasa' => $service,
				'realisasi_detail_pajak' => $tax,
				'realisasi_detail_total' => $total,
			];
		}
		// die(json_encode($batch));
		$this->db->insert_batch('pajak_realisasi_detail', $batch);

		// Insert data parent laporan realisasi
		$this->db->insert('pajak_realisasi', [
			'realisasi_id' => $realisasi_id,
			'realisasi_no' => 0,
			'realisasi_wajibpajak_id' => $wp_id,
			'realisasi_wajibpajak_npwpd' => $npwpd,
			'realisasi_tanggal' => $periode,
			'realisasi_sub_total' => $sum_subtotal,
			'realisasi_jasa' => $sum_service,
			'realisasi_pajak' => $sum_tax,
			'realisasi_total' => $sum_total,
			'realisasi_created_at' => date("Y-m-d H:i:s")
		]);

		log_activity('Input form rekap transaksi');

		$response = [
			'success' => true,
			'message' => 'Successfully saved data.',
			'id' => $realisasi_id
		];

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