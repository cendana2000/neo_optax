<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KategoriModel extends Base_Model
{
	public function __construct()
	{
		$model = array(
			'table' => array(
				'name' => 'pos_kategori',
				'primary' => 'kategori_barang_id',
				'fields' => array(
					array('name' => 'kategori_barang_id'),
					array('name' => 'kategori_barang_kode'),
					array('name' => 'kategori_barang_nama'),
					array('name' => 'kategori_barang_tipe'),
					array('name' => 'kategori_barang_parent'),
					array('name' => 'kategori_barang_aktif'),
					array('name' => 'kategori_barang_updated'),
					array('name' => 'kategori_barang_user'),
				)
			),
			'view' => array(
				'mode' => array(
					'table' => array('kategori_barang_id', 'kategori_barang_kode', 'UPPER(kategori_barang_nama)', 'kategori_barang_nama')
				)
			)
		);
		parent::__construct($model);
		//Do your magic here
	}

	public function data_tree($parent = '#', $company = null)
	{
		if (isset($_GET['id'])) {
			$parent = $_GET['id'];
		}
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id = ' . $this->db->escape($wp_id);
		}
		$query = $this->db->query("SELECT kategori_barang_id as id, kategori_barang_parent as parent, kategori_barang_kode as kode, kategori_barang_nama as nama, kategori_barang_tipe as tipe, CONCAT(kategori_barang_kode, ' - ', kategori_barang_nama) as text, kategori_barang_tipe as children, kategori_barang_key FROM ak_kategori_barang WHERE kategori_barang_parent = '" . $parent . "' AND kategori_barang_aktif = 1 $where ORDER BY kategori_barang_kode ASC;");
		$result = $query->result_array();
		foreach ($result as &$record) {
			if ($record['children'] == 'parent') {
				$record['children'] = true;
			} else {
				$record['children'] = false;
			}
		}
		return $result;
	}

	public function get_kategori_barang_by_number($num, $raw = false)
	{
		$data = $this->read(array(
			'kategori_barang_kode' => $num,
			'kategori_barang_aktif' => 1
		));
		// 'kategori_barang_company' => $this->company_model->company_detail('company_id'),

		if ($data) {
			if ($raw) {
				return $data;
			} else {
				return $data['kategori_barang_id'];
			}
		} else {
			return null;
		}
	}

	public function get_kategori_barang_by_key($key, $company = null)
	{
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id = ' . $this->db->escape($wp_id);
		}
		$data = $this->db->query('SELECT kategori_barang_id FROM ak_kategori_barang WHERE kategori_barang_parent = "' . $key . '" ' . $where . ' ORDER BY kategori_barang_kode DESC LIMIT 1')->result_array();
		if ($data) {
			return $data[0]['kategori_barang_id'];
		} else {
			return null;
		}
	}

	public function get_parent($id)
	{
		$where = '';
		if ($wp_id = $this->session->userdata('wajibpajak_id')) {
			$where = ' AND wajibpajak_id = ' . $this->db->escape($wp_id);
		}

		$query = $this->db->query("SELECT kategori_barang_nama, kategori_barang_parent FROM ak_kategori_barang WHERE kategori_barang_id = '" . $id . "' $where;");
		$result = $query->result_array();
		$data = $result[0];

		if ($data['kategori_barang_parent'] == "#") {
			return $data;
		} else {
			return $this->get_parent($data['kategori_barang_parent']);
		}
	}

	public function gen_kode($value = false, $kelompok = '')
	{
		return parent::generate_kode(array(
			'pattern'       => $kelompok . '.{#}',
			'field'         => 'kategori_barang_kode',
			'index_format'  => '0000',
			'index_mask'    => $value
		));
	}

	public function checkRelasi($data)
	{
		return $this->db->query("select count(kategori_barang_nama) as res from pos_barang join pos_kategori on barang_kategori_barang = kategori_barang_id  where kategori_barang_id = '{$data['id']}'")->row_array()['res'];
	}
}

/* End of file kategorianggotaModel.php */
/* Location: ./application/modules/kategorianggota/models/kategorianggotaModel.php */