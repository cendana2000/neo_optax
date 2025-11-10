<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LogoapiModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'v_log_oapi_v2',
                'primary' => 'toko_wajibpajak_id',
                'fields' => array(
                    array('name' => 'toko_wajibpajak_id', 'view' => true),
                    array('name' => 'realisasi_no', 'view' => true),
                    array('name' => 'realisasi_wajibpajak_npwpd', 'view' => true),
                    array('name' => 'toko_nama', 'view' => true),
                    array('name' => 'realisasi_sub_total', 'view' => true),
                    array('name' => 'realisasi_jasa', 'view' => true),
                    array('name' => 'realisasi_pajak', 'view' => true),
                    array('name' => 'realisasi_total', 'view' => true),
                    array('name' => 'realisasi_created_at', 'view' => true),
                    array('name' => 'toko_kode', 'view' => true),
                    array('name' => 'realisasi_tanggal', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_log_oapi_v2',
                'mode' => array(
                    'table' => array(
                        'toko_wajibpajak_id',
                        'toko_kode',
                        'toko_nama',
                        'realisasi_wajibpajak_npwpd',
                        'realisasi_tanggal',
                        'realisasi_sub_total',
                        'realisasi_no',
                        'realisasi_jasa',
                        'realisasi_pajak',
                        'realisasi_total',
                        'realisasi_created_at',
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
}

/* End of file BarangModel.php */
/* Location: ./application/modules/barang/models/BarangModel.php */