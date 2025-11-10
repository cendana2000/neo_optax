<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wp_outer extends Base_Controller
{

	public function __construct()
	{

		parent::__construct();
		//Do your magic here
	}

	public function index()
	{
		$this->load->model('WpOuterModel');
		$randomString = $this->WpOuterModel->generateRandomString();
		$this->load->helper('string');
		$result = $this->db->get('wp_outer')->result_array();


		$this->response([
			'message' => 'Get data succesfully',
			'data' => $result
		]);
	}

	public function autoinsert()
	{
		$this->load->model('WpOuterModel');
		//generate id
		$randomString = $this->WpOuterModel->generateRandomString();
		for ($i = 0; $i < 50; $i++) {
			for ($s = 0; $s < 50; $s++) {
				$random = md5(md5($randomString));
			}
			$getnumber = rand(1, 100);
			$itemsa = array("Michael", "Alice", "Bob", "Charlie", "David", "Eve", "John", "Mary", "Jane", "Adam", "Emily");
			$getname = $itemsa[array_rand($itemsa)];
			$itemsb = array("Lurch", "Gordon", "Norman", "Jim", "Linguina", "Niles", "Doe", "Mary", "Abraham", "Adam", "Emily");
			$getname2 = $itemsb[array_rand($itemsb)];

			$gettotalprice = rand(10000, 500000);
			if ($gettotalprice < 125000) {
				$gettotalservice = $gettotalprice * 2 / 10;
			} else {
				$gettotalservice = $gettotalprice * 3 / 20;
			}

			$irs = $gettotalprice * 1 / 10;

			$total = $gettotalprice + $gettotalservice + $irs;


			for ($x = 0; $x < 50; $x++) {
				$this->db->query("INSERT INTO wp_outer (sales_id, sales_code, sales_total_item, sales_total_qty, sales_sub_total, sales_service, sales_tax, sales_grand_total, sales_customer_name, sales_cashier_name) VALUES('{$random}', 'T001', {$getnumber}, {$getnumber}, {$gettotalprice}, {$gettotalservice}, {$irs}, {$total}, '{$getname}', '{$getname2}');
			");
			}
		}

	}
}


/* End of file satuan.php */
/* Location: ./application/modules/satuan/controllers/satuan.php */