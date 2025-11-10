<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransaksiwpModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_pajak_penjualan_wp',
                'primary' => 'log_penjualan_id',
                'fields' => array(
                    array('name' => 'log_penjualan_id', 'view' => true),
                    array('name' => 'log_penjualan_wp_penjualan_id', 'view' => true),
                    array('name' => 'log_penjualan_wp_penjualan_tanggal', 'view' => true),
                    array('name' => 'log_penjualan_wp_total', 'view' => true),
                    array('name' => 'log_penjualan_code_store', 'view' => true),
                    array('name' => 'toko_nama', 'view' => true),
                    array('name' => 'toko_wajibpajak_npwpd', 'view' => true),
                    array('name' => 'log_penjualan_wp_penjualan_kode', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_pajak_penjualan_wp',
                'mode' => array(
                    'table' => array(
                        'log_penjualan_id',
                        'log_penjualan_code_store',
                        'toko_nama',
                        'toko_wajibpajak_npwpd',
                        'log_penjualan_wp_penjualan_tanggal',
                        'log_penjualan_wp_total',
                        'log_penjualan_wp_penjualan_kode',
                        'log_penjualan_wp_penjualan_id',
                    ),
                )
            )
        );
        parent::__construct($model);
        //Do your magic here
    }

    public function deleteTransaksi($data)
    {
        $this->dbpos = $this->load->database(multidb_connect($data['code_store']), true);

        $this->dbpos->where('penjualan_id', $data['penjualan_id']);
        $this->dbpos->where('penjualan_lock', null);
        $this->dbpos->delete('pos_penjualan');

        if ($this->dbpos->affected_rows() > 0) {
            $this->db->delete('log_penjualan_wp', array('log_penjualan_wp_penjualan_id' => $data['penjualan_id']));
            return [
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ];
        } {
            return [
                'success' => false,
                'message' => 'Data penjualan sudah melakukan upload pajak'
            ];
        }
    }

    //tambahan detailTransaksi
    function detailTransaksi($data)
    {
        $this->dbpos = $this->load->database(multidb_connect($data['code_store']), true);

        $this->dbpos->select('*');
        $this->dbpos->from('pos_penjualan');
        // $this->dbpos->join('pajak_toko', 'pos_penjualan.penjualan_id = pajak_toko.toko_id', 'left');
        $this->dbpos->where('penjualan_id', $data['penjualan_id']);
        $this->dbpos->where('penjualan_lock', null);

        $this->db->select('*');
        $this->db->from('pajak_wajibpajak');
        $this->db->join('pajak_toko', 'pajak_wajibpajak.wajibpajak_id = pajak_toko.toko_wajibpajak_id', 'left');
        $this->db->where('pajak_toko.toko_kode', str_replace("posprod_", "", $data['code_store']));

        return [
            'success' => true,
            'message' => 'Berhasil menampilkan data',
            'data'    => $this->dbpos->get()->result_array(),
            'data_wp' => $this->db->get()->result_array(),
            'sql'     => $this->db->last_query()
        ];
    }
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */