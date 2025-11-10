
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Amp\Beanstalk\BeanstalkClient;
use \Amp\Loop;
use Carbon\Carbon;

class Worker extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function runWorker()
    {
        $baseQueue = $_SERVER['BASE_QUEUE'];
        Loop::run(function () use ($start, $beanstalk, $baseQueue) {
            $beanstalk = new BeanstalkClient("tcp://".$baseQueue);
            yield $beanstalk->watch('auto_db_project');

            while (list($jobId, $payload) = yield $beanstalk->reserve()) {

                $dataPayload = json_decode($payload, true);
                // $dataPayload = [
                //     'table' => 'borehole',
                //     'id'    => '123123123123',
                //     'project_id' => '423c9ade735d1f5db7044f4eaa2555c0',
                // ];
                switch ($dataPayload['table']) {
                    case 'borehole':
                        $this->actBorehole($dataPayload);
                        break;
                    case 'sample_dispatch':
                        $this->actSampleDispatch($dataPayload);
                        break;
                    case 'rock_logging':
                        $this->actRockLogging($dataPayload);
                        break;
                    
                    default:
                        break;
                }

                $beanstalk->delete($jobId);
            }
        });
    }

    protected function actBorehole($dataPayload)
    {
        $dataProject = $this->db->where(['project_id' => $dataPayload['project_id']])->get('project')->row_array();
        if ($dataProject) {
            $this->genTableBorehole($dataProject);

            if ($dataPayload['id']) {
                $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
                $configDB = $this->loadConfigDB($dbName);
                $dbproject = $this->load->database($configDB, TRUE);

                $borehole_id = $dataPayload['id'];
                $check = $dbproject->where(['borehole_id' => $borehole_id])->count_all_results('borehole');
                $dataGlobal = $this->db->where(['borehole_id' => $borehole_id])->get('v_borehole_full_detail')->row_array();
                if ($dataGlobal) {
                    if ($check > 0) {
                        $update = $dbproject->where(['borehole_id' => $borehole_id])->update('borehole', $dataGlobal);
                    }else{
                        $insert = $dbproject->insert('borehole', $dataGlobal);
                    }
                }
            }
        }
    }

    protected function genTableBorehole($dataProject)
    {
        $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
        $this->genDB($dbName, $dataProject);

        $tableName = 'borehole';
        $fieldsBorehole = [
            'borehole_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'unsigned' => TRUE,
            ),
            'borehole_project_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_project_block_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_borehole_method_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_hole_size_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_drilling_rig_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_date_start'   => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'borehole_date_end' => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'borehole_borehole_status_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_coordinate_system_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_utm_zone_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_loc_accuracy_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_easting'  => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_northing' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_elevation'    => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_plan_depth'   => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_actual_depth' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_azimuth'  => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_inclination'  => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_data_status_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'borehole_geophy_logged'    => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_geophy_depth' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'borehole_drill_rig_photo'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_geophy_log_pdf'   => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_geophy_log_las'   => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_log'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_geometry' => array(
                'type' => 'GEOMETRY',
                'null' => TRUE,
            ),
            'borehole_created_at'   => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'borehole_updated_at'   => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'borehole_deleted_at'   => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'project_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'project_block_name'    => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_method_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'hole_size_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'drilling_rig_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_status_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'coordinate_system_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'utm_zone_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'loc_accuracy_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'data_status_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
        ];
        $attributes = array('ENGINE' => 'InnoDB');

        $genConfig = [
            'fields'    => $fieldsBorehole,
            'attr'      => $attributes,
            'db'        => $dbName,
            'table'     => $tableName,
            'pk'        => 'borehole_id',
            'fk'        => null,
        ];
        return $this->runGen($genConfig);
    }



    protected function actSampleDispatch($dataPayload)
    {
        $dataProject = $this->db->where(['project_id' => $dataPayload['project_id']])->get('project')->row_array();
        if ($dataProject) {
            $this->genTableSampleDispatch($dataProject);

            if ($dataPayload['id']) {
                $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
                $configDB = $this->loadConfigDB($dbName);
                $dbproject = $this->load->database($configDB, TRUE);

                $sample_id = $dataPayload['id'];
                $check = $dbproject->where(['sample_id' => $sample_id])->count_all_results('sample');
                $dataGlobal = $this->db->where(['sample_id' => $sample_id])->get('v_sample_dispatch_full_detail')->row_array();
                if ($dataGlobal) {
                    if ($check > 0) {
                        $update = $dbproject->where(['sample_id' => $sample_id])->update('sample', $dataGlobal);
                    }else{
                        $insert = $dbproject->insert('sample', $dataGlobal);
                    }
                }
            }
        }
    }

    protected function genTableSampleDispatch($dataProject)
    {
        $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
        $this->genDB($dbName, $dataProject);

        $tableName = 'sample';
        $fields = [
            'sample_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'unsigned' => TRUE,
            ),
            'sample_project_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_borehole_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_sampel_status_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_qual_standard_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_sample_purpose_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_laboratory_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'sample_name'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'sample_from'   => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'sample_to' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'sample_field_mass' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'sample_seam'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'sample_ply'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'sample_coal_recovery'  => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE,
            ),
            'sample_date'   => array(
                'type' => 'DATE',
                'null' => TRUE,
            ),
            'sample_created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'sample_updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'sample_deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'project_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'borehole_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'sample_status_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'qual_standard_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'sample_purpose_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
            'laboratory_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => TRUE,
            ),
        ];
        $attributes = array('ENGINE' => 'InnoDB');

        $genConfig = [
            'fields'    => $fields,
            'attr'      => $attributes,
            'db'        => $dbName,
            'table'     => $tableName,
            'pk'        => 'sample_id',
            'fk'        => null,
        ];
        return $this->runGen($genConfig);
    }


    protected function actRockLogging($dataPayload)
    {
        $dataProject = $this->db->where(['project_id' => $dataPayload['project_id']])->get('project')->row_array();
        if ($dataProject) {
            $this->genTableRockLogging($dataProject);

            if ($dataPayload['id']) {
                $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
                $configDB = $this->loadConfigDB($dbName);
                $dbproject = $this->load->database($configDB, TRUE);

                $rock_logging_id = $dataPayload['id'];
                $check = $dbproject->where(['rock_logging_id' => $rock_logging_id])->count_all_results('rock_logging');
                $dataGlobal = $this->db->where(['rock_logging_id' => $rock_logging_id])->get('v_rock_logging_full_detail')->row_array();
                if ($dataGlobal) {
                    if ($check > 0) {
                        $update = $dbproject->where(['rock_logging_id' => $rock_logging_id])->update('rock_logging', $dataGlobal);
                    }else{
                        $insert = $dbproject->insert('rock_logging', $dataGlobal);
                    }
                }
            }
        }
    }

    protected function genTableRockLogging($dataProject)
    {
        $dbName = strtolower($_SERVER['PREFIX_DB'] . $dataProject['project_code']);
        $this->genDB($dbName, $dataProject);

        $tableName = 'rock_logging';
        $fields = [
            'rock_logging_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'unsigned' => TRUE,
            ),
            'rock_logging_project_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_borehole_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_interval_status_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_samp_purpose_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_shade_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_color_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_inter_relation_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_lithology_qual_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_weathering_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_estim_strange_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_bed_spacing_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_defect_type_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_intact_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_defect_spacing_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_core_state_id'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_mecha_state_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_texture_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_basal_contact_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_fos_abundance_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_fos_type_id'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_hue_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_fos_assoc_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_gas_id'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_lithology_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_from' => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'rock_logging_to'   => array(
                'type' => 'DOUBLE',
                'null' => TRUE,
            ),
            'rock_logging_seam' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_ply'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_horizon'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_sample_number'    => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => TRUE,
            ),
            'rock_logging_lithology'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_defect_dip'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_adjective_1'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_adjective_2'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_adjective_3'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_adjective_4'  => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_sed_feature_1'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_sed_feature_2'    => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_bedding_dip'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'rock_logging_created_at'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_updated_at'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'rock_logging_deleted_at'   => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => TRUE,
            ),
            'project_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'borehole_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'interval_status_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'sample_purpose_code'   => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'sample_purpose_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'shade_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'color_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'inter_relation_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'lithology_qualifier_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'lithology_qualifier_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'weathering_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'estimated_strange_code'    => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'estimated_strange_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'bed_spacing_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'defect_type_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'defect_type_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'intact_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'defect_spacing_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'core_state_description'    => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'mechanical_state_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'mechanical_state_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'texture_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'basal_contact_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'fossil_abundance_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'fossil_type_code'  => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'fossil_type_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'hue_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'fossil_assoc_description'  => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'gas_description'   => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
            'lithology_code'    => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'lithology_description' => array(
                'type' => 'VARCHAR',
                'constraint' => '240',
                'null' => TRUE,
            ),
        ];
        $attributes = array('ENGINE' => 'InnoDB');

        $genConfig = [
            'fields'    => $fields,
            'attr'      => $attributes,
            'db'        => $dbName,
            'table'     => $tableName,
            'pk'        => 'rock_logging_id',
            'fk'        => null,
        ];
        return $this->runGen($genConfig);
    }






    protected function runGen($config)
    {
        $configDB = $this->loadConfigDB($config['db']);
        $dbproject = $this->load->database($configDB, TRUE);

        if ($dbproject->table_exists($config['table'])){
            return true;
        }else{
            $dbprojectforge = $this->load->dbforge($dbproject, TRUE);
            $dbprojectforge->add_field($config['fields']);
            if ($config['pk']) {
                $dbprojectforge->add_key($config['pk'], TRUE);
            }
            if ($config['fk']) {
                $dbprojectforge->add_key($config['fk']);
            }
            $dbprojectforge->create_table($config['table'], TRUE, $config['attr']);
            return true;
        }
    }

    protected function genDB($dbName, $dataProject)
    {
        $this->load->dbforge();
        $dbExist = $this->db->query('select SCHEMA_NAME from information_schema.schemata where SCHEMA_NAME = "'.strtolower($dbName).'"')->row_array();
        if (is_null($dbExist['SCHEMA_NAME'])) {
            $this->dbforge->create_database(strtolower($dbName));

            $dbUser = strtolower($dataProject['project_code']);
            $dbPass = "password123";
            $this->db->query("CREATE USER '". $dbUser ."'@'%' IDENTIFIED WITH mysql_native_password BY '". $dbPass ."';");
            $this->db->query("GRANT CREATE, ALTER, DELETE, INSERT, SELECT, DROP, UPDATE  ON `".strtolower($dbName)."` . * TO '". $dbUser ."'@'%';");
            return true;
        }
        return false;
    }

    protected function loadConfigDB($dbName)
    {
        $config['hostname'] = $_SERVER['DB_HOST'];
        $config['username'] = $_SERVER['DB_USER'];
        $config['password'] = $_SERVER['DB_PASS'];
        $config['database'] = strtolower($dbName);
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'latin1';
        $config['dbcollat'] = 'latin1_swedish_ci';
        return $config;
    }

    protected function genAll()
    {
        // $databorehole = $this->db->select(['borehole_id', 'borehole_project_id'])->where(['borehole_deleted_at is null' => null])->order_by('borehole_created_at', 'ASC')->get('borehole')->result_array();
        // foreach ($databorehole as $key => $value) {
        //     $dataPayload = [
        //         'table' => 'borehole',
        //         'id'    => $value['borehole_id'],
        //         'project_id' => $value['borehole_project_id'],
        //     ];
        //     addJobToQueue('auto_db_project', $dataPayload);
        // }

        // $datasample = $this->db->select(['sample_id', 'sample_project_id'])->where(['sample_deleted_at is null' => null])->order_by('sample_created_at', 'ASC')->get('sample')->result_array();
        // foreach ($datasample as $key => $value) {
        //     $dataPayload = [
        //         'table' => 'sample_dispatch',
        //         'id'    => $value['sample_id'],
        //         'project_id' => $value['sample_project_id'],
        //     ];
        //     addJobToQueue('auto_db_project', $dataPayload);
        // }

        // $datarock = $this->db->select(['rock_logging_id', 'rock_logging_project_id'])->where(['rock_logging_deleted_at is null' => null])->order_by('rock_logging_created_at', 'ASC')->get('rock_logging')->result_array();
        // foreach ($datarock as $key => $value) {
        //     $dataPayload = [
        //         'table' => 'rock_logging',
        //         'id'    => $value['rock_logging_id'],
        //         'project_id' => $value['rock_logging_project_id'],
        //     ];
        //     addJobToQueue('auto_db_project', $dataPayload);
        // }

        echo 'oke';
    }


}

