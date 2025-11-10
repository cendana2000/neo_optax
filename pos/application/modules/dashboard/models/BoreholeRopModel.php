<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BoreholeRopModel extends Base_Model
{
    public function __construct()
    {
        $model = array(
            'table' => array(
                'name' => 'borehole',
                'primary' => 'borehole_id',
                'fields' => array(
                    array('name' => 'borehole_project_id'),
                    array('name' => 'borehole_drilling_rig_id'),
                    array('name' => 'borehole_borehole_status_id'),
                    array('name' => 'borehole_deleted_at'),

                    array('name' => 'drilling_rig_name', 'view' => true),
                    array('name' => 'drilling_rig_id', 'view' => true),
                )
            ),
            'view' => array(
                'name' => 'v_borehole_rop',
                'mode' => array(
                    'datatable' => array(
                        'borehole_project_id',
                        'borehole_drilling_rig_id',
                        'borehole_borehole_status_id',
                        'borehole_deleted_at',

                        'drilling_rig_name',
                        'drilling_rig_id',
                    )
                )
            )
        );
        parent::__construct($model);
    }
}
