<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori extends Base_Controller
{
	public function __construct()
	{

		parent::__construct();
		//Do your magic here
		$this->load->model(array(
			'kategori/KategoriModel' => 'kelompokbarang'
		));
	}

	public function index()
	{
		$this->response(
			$this->select_dt(varPost(), 'kelompokbarang', 'table')
		);
	}

	function read($value = '')
	{
		$data = $this->kelompokbarang->read(varPost());

		if ($data['kategori_barang_parent'] != '#') $data['kategori_barang_tipe'] = 'detail';

		// echo json_encode($data);
		// exit;

		$this->response($data);
	}

	public function store()
	{
		$data = varPost();
		$data['kategori_barang_kode'] = gen_uuid($this->kelompokbarang->get_table());
		$data['kategori_barang_aktif'] = 1;
		if (!isset($data['kategori_barang_parent']) || (!$data['kategori_barang_parent'])) $data['kategori_barang_parent'] = '#';
		$data['kategori_barang_key'] = str_replace(' ', '_', strtolower(varPost('kategori_barang_nama')));
		$operation = $this->kelompokbarang->insert(gen_uuid($this->kelompokbarang->get_table()), $data);
		$this->response($operation);
	}

	public function update()
	{
		$data = varPost();

		if ($data['kategori_barang_tipe'] == 'parent') $data['kategori_barang_parent'] = '#';

		if ($savemode === true) {
			$operation = $this->kelompokbarang->insert_update(varPost('id', varExist($data, $this->kelompokbarang->get_primary(true))), $data);
		} else {
			$operation = $this->kelompokbarang->update(varPost('id', varExist($data, $this->kelompokbarang->get_primary(true))), $data);
		}
		$this->response($operation);
	}

	public function destroy()
	{
		$data = varPost();
		// print_r($data);exit();
		$operation = $this->kelompokbarang->delete(varPost('id', varExist($data, $this->kelompokbarang->get_primary(true))));
		$this->response($operation);
	}

	public function select_tree($parent = '#', $company = null)
	{
		if (isset($_GET['id'])) {
			$parent = $_GET['id'];
		}
		$query = $this->db->query('SELECT kategori_barang_id as id, kategori_barang_parent as parent,  UPPER(kategori_barang_nama) as text, kategori_barang_tipe as children FROM pos_kategori WHERE kategori_barang_parent = "' . $parent . '" AND kategori_barang_aktif = 1 ORDER BY kategori_barang_kode ASC;');
		$result = $query->result_array();
		foreach ($result as &$record) {
			if ($record['children'] == 'parent') {
				$record['children'] = true;
			} else {
				$record['children'] = false;
			}
		}
		if (isset($_GET['id'])) {
			$this->response($result);
		} else {
			return $result;
		}
	}

	public function go_tree($value = '')
	{
		$kelompokbarang = $this->kelompokbarang->select(array(
			'filters_static' => array(
				'kategori_barang_aktif' => '1'
			), 'sort_static' => 'kategori_barang_nama asc'
		));
		$opr = $this->buildTree($kelompokbarang['data']);
		$operation = array(
			'success'   => true,
			'data'      => $opr
		);
		print_r($operation['data']);exit;
		$this->response($operation);
	}


	function buildTree(array $elements, $parentId = '#')
	{
		$branch = array();
		foreach ($elements as $element) {
			if ($element['kategori_barang_parent'] == $parentId) {
				$children = $this->buildTree($elements, $element['kategori_barang_id']);
				$element_new = array(
					'id'        => $element['kategori_barang_id'],
					'parent'    => $element['kategori_barang_parent'],
					'text'      => '->' . $element['kategori_barang_nama'],
					'tipe'      => $element['kategori_barang_tipe']
				);
				if ($children) {
					$element_new['children'] = true;
					$element_new['child'] = $children;
				}
				$branch[] = $element_new;
			}
		}
		return $branch;
	}

	public function go_tree_all($company = null, $level = '3')
	{
		$counter = 0;
		$elm = "";
		// $rendered=false, $mapped=, $untrack=false, $order=null
		$lv2 = $this->kelompokbarang->find(array(
			'kategori_barang_parent' => '#',
		), false, true, false, array(
			'kategori_barang_kode' => 'ASC'
		));
		// 'kategori_barang_company' => $company
		if ($level == 2) {
			foreach ($lv2 as $rec_lv2) {
				if ($counter == (count($lv2) - 1)) {
					$elm .= $rec_lv2['kategori_barang_kode'];
				} else {
					$elm .= $rec_lv2['kategori_barang_kode'] . "|";
				}
				$counter++;
			}
		} else {
			foreach ($lv2 as $rec_lv2) {
				$lv3 = $this->kelompokbarang->find(array(
					'kategori_barang_parent' => $rec_lv2['kategori_barang_id'],
				), false, true, false, array(
					'kategori_barang_kode' => 'ASC'
				));

				foreach ($lv3 as $rec_lv3) {
					$elm .= $rec_lv3['kategori_barang_kode'] . "|";
				}

				$counter++;
			}
		}
		$opr = $this->select_tree_recursive($elm, $company);
		$operation = array(
			'success' => true,
			'data' => $opr
		);
		$this->response($operation);
	}

	public function select_tree_recursive($parent = '#', $company = null)
	{
		$records = array();
		$parent = urldecode($parent);
		if (strpos($parent, '|') !== false) {
			$parent_array = explode('|', $parent);
			foreach ($parent_array as $key => $val_parent) {
				$temp_records = $this->data_tree_by_key($val_parent);
				foreach ($temp_records as $key => &$val) {
					if ($val['children']) {
						$val['child'] = $this->select_tree_recursive($val['kategori_barang_kode']);
					}
				}
				$records = array_merge($records, $temp_records);
			}
		} else if ($parent == 'level2') {
			$level2 = $this->kelompokbarang->find(array(
				'kategori_barang_parent' => '#',
				'kategori_barang_aktif' => 1
			), false, true, false, array(
				'kategori_barang_kode' => 'ASC'
			));
			foreach ($level2 as $key => $val_parent) {
				$temp_records = $this->kelompokbarang->data_tree($val_parent['kategori_barang_id']);
				foreach ($temp_records as $key => &$val) {
					if ($val['children']) {
						$val['child'] = $this->select_tree_recursive($val['kategori_barang_kode']);
					}
				}
				$records = array_merge($records, $temp_records);
			}
		} else {
			if ($parent == '#') {
				$records = $this->kelompokbarang->data_tree($parent);
			} else {
				$records = $this->data_tree_by_key($parent);
			}

			foreach ($records as $key => &$val) {
				if ($val['children']) {
					$val['child'] = $this->select_tree_recursive($val['kategori_barang_kode']);
				}
			}
			// print_r($records);
		}
		return $records;
	}

	public function data_tree_by_key($parent = '#', $company = null)
	{
		return $this->kelompokbarang->data_tree($this->kelompokbarang->get_kategori_barang_by_key($parent));
	}
}

/* End of file kategori.php */
/* Location: ./application/modules/kategori/controllers/kategori.php */