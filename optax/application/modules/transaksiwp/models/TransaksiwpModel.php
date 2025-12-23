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
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id = pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')', NULL, FALSE);
        }

        $this->db->where('penjualan_id', $data['penjualan_id']);
        $this->db->where('penjualan_lock', null);
        $this->db->delete('pos_penjualan');

        if ($this->db->affected_rows() > 0) {
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
        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('EXISTS(SELECT 1 FROM pajak_wajibpajak WHERE pajak_wajibpajak.wajibpajak_id = pos_penjualan.wajibpajak_id AND pemda_id=' . $this->db->escape($pemda_id) . ')', NULL, FALSE);
        }

        $this->db->select('*');
        $this->db->from('pos_penjualan');
        // $this->db->join('pajak_toko', 'pos_penjualan.penjualan_id = pajak_toko.toko_id', 'left');
        $this->db->where('penjualan_id', $data['penjualan_id']);
        $this->db->where('penjualan_lock', null);

        if ($pemda_id = $this->session->userdata('pemda_id')) {
            $this->db->where('pemda_id', $pemda_id);
        }
        $this->db->select('*');
        $this->db->from('pajak_wajibpajak');
        $this->db->join('pajak_toko', 'pajak_wajibpajak.wajibpajak_id = pajak_toko.toko_wajibpajak_id', 'left');
        $this->db->where('pajak_toko.toko_kode', str_replace("posprod_", "", $data['code_store']));

        return [
            'success' => true,
            'message' => 'Berhasil menampilkan data',
            'data'    => $this->db->get()->result_array(),
            'data_wp' => $this->db->get()->result_array(),
            'sql'     => $this->db->last_query()
        ];
    }
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */