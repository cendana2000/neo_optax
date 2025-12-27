<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Base_model
 * 
 * @package     base_model
 * @author      eko dedy purnomo eko.dedy.purnomo@gmail.com
 * @copyright   sekawan media
 * @version     3.0.0
 * @access      public
 */

class Base_model extends CI_Model
{
    private $model = null;
    public $dboapi;
    public $dbmp;
    private $model_default = array(
        'table' => array(
            'name'      => null,
            'primary'   => null,
            'fields'    => array()
        ),
        'view' => array(
            'name'      => null,
            'limit'     => null,
            'mode'      => array()
        ),
        'operation' => array(
            'last_query'        => null,
            'insertid'          => null,
            'affected_rows'     => null
        ),
        'recorder' => array(
            'insert'    => 'inserted_by',
            'insert_at' => 'inserted_at',
            'update'    => 'updated_by',
            'update_at' => 'updated_at',
            'delete'    => 'deleted_by',
            'delete_at' => 'deleted_at'
        ),
        'soft_delete' => false,
        'response_message' => array(
            'request_invalid'   => 'The server could not read the request.',
            'insert_success'    => 'Successfully saved data.',
            'insert_failed'     => 'Failed to save data.',
            'insert_isexist'    => 'Data with <b> {primary} </ b> = <b> {primary_value} </ b> already exists listed.',
            'insert_null'       => 'Incomplete data. <br/> <b> {fields_notnull} </ b> can not be empty.',
            'update_success'    => 'Successfully changed data.',
            'update_failed'     => 'Failed to change data.',
            'update_null'       => 'Failed to change data. The data is not available.',
            'delete_success'    => 'Successfully deleted data.',
            'delete_failed'     => 'Failed to delete data, There was an error on the server.',
            'delete_null'       => 'Failed to delete data. The data is not available.',
            'delete_constraint' => 'Failed to delete data, The data is used in another table.',
            'unique'            => ' {field}  "{value}" is already in use.',
        ),
        'validation_message' => array(
            'format_message'    => '<b> {field} </ b> does not match the format.',
            'inclusion_message' => '<b> {field} </ b> must be between: <b> {inclusion} </ b>.',
            'exclusion_message' => '<b> {field} </ b> can not include from: <b> {exclusion} </ b>.',
            'minvalue_message'  => '<B> {field} </ b> value is at least <b> {minvalue} </ b>.',
            'maxvalue_message'  => 'The <b> {field} </ b> value is at least <b> {maxvalue} </ b>.',
            'above_message'     => '<B> {field} </ b> value must be above <b> {above} </ b>.',
            'below_message'     => '<B> {field} </ b> value should be below <b> {below} </ b>.',
            'minlength_message' => 'The length of <b> {field} </ b> is at least <b> {minlength} </ b> characters.',
            'maxlength_message' => 'The length of <b> {field} </ b> is at least <b> {maxlength} </ b> characters.',
        ),
        'event_listener' => array(
            'before_select'     => null,
            'after_select'      => null,
            'before_read'       => null,
            'after_read'        => null,
            'before_insert'     => null,
            'after_insert'      => null,
            'before_update'     => null,
            'after_update'      => null,
            'before_delete'     => null,
            'after_delete'      => null,
            'before_find'       => null,
            'after_find'        => null
        )
    );

    function __construct(&$model_config = null)
    {
        parent::__construct();
        $this->dbmp = $this->load->database(multidb_connect($_ENV['PAJAK_DBNAME']), true);

        $this->set_model($model_config);
    }

    function init_fields($fields = null)
    {
        $fields_initialized = array();
        foreach ((array)$fields as $index => $field) {
            if (is_array($field)) {
                if (!empty($field['name'])) {
                    $fkey = $field['name'];
                } else {
                    $fkey = $index;
                }
            } else {
                $fkey = (string)$field;
                $field = array();
            }

            $field = array_merge(array(
                'name'      => $fkey,
                'map'       => $fkey,
                'display'   => $fkey,
                'view'      => false,
                'public'    => true,
                'update'    => true,
                'insert'    => true,
                'notnull'   => false,
                'unique'    => false,
                'htmlspecial'    => false,
                'validation' => array(),
                'render'    => null,
                'prepare'   => null,
            ), $field);
            if ($field['view'] === true) {
                $field = array_merge($field, array(
                    'update'    => false,
                    'insert'    => false,
                    'notnull'   => false,
                    'unique'    => false,
                    'validation' => null,
                ));
            }
            if (is_string($field['render'])) {
                $field['render'] = str_replace('self::', get_class($this) . '::', $field['render']);
                $field['render'] = str_replace('{class}', get_class($this), $field['render']);
            }
            if (is_string($field['prepare'])) {
                $field['prepare'] = str_replace('self::', get_class($this) . '::', $field['prepare']);
                $field['prepare'] = str_replace('{class}', get_class($this), $field['prepare']);
            }
            $fields_initialized[$fkey] = $field;
        }

        return $fields_initialized;
    }

    function get_model()
    {
        return $this->model;
    }

    function set_model($model)
    {
        $model = array_merge($this->model_default, (array)$model);
        foreach ($model as $key => $value) {
            if (isset($this->model_default[$key]) and is_array($model[$key])) {
                $model[$key] = array_merge($this->model_default[$key], $model[$key]);
            }
        }
        // prepare the proper fields before model initializing
        $model['table']['fields'] = $this->init_fields($model['table']['fields']);

        // model initializing
        $this->model = $model;

        // table initializing
        $this->set_table($model['table']['name']);

        // view initializing
        $this->set_view($model['view']['name'], $model_config['view']['limit']);

        return $this->get_model();
    }

    function get_table()
    {
        return $this->model['table']['name'];
    }
    function get_tablename()
    {
        return $this->get_table();
    }

    function get_table_name()
    {
        return $this->get_table();
    }

    function set_table(&$tablename = null)
    {
        if (empty($tablename)) {
            $this->model['table']['name'] = preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this)));
        } else {
            $this->model['table']['name'] = $tablename;
        }
        return $this->get_table();
    }

    function set_tablename(&$tablename = null)
    {
        return $this->set_table($tablename);
    }

    function set_table_name(&$tablename = null)
    {
        return $this->set_table($tablename);
    }


    function get_primary($mapped = false)
    {
        if ($mapped === true) {
            return $this->map_fields_out($this->model['table']['primary']);
        } else {
            return $this->model['table']['primary'];
        }
    }


    function set_primary($fieldname)
    {
        $this->model['table']['primary'] = $fieldname;
        return $this->get_primary();
    }


    function get_view()
    {
        return $this->model['view']['name'];
    }


    function set_view(&$viewname, &$limit = null)
    {
        if (empty($viewname)) {
            $viewname = $this->get_table();
        }
        $this->model['view']['name'] = $viewname;

        $this->set_view_limit($limit);

        return $this->get_view();
    }


    function get_view_limit()
    {
        return $this->model['view']['limit'];
    }


    function set_view_limit(&$limit = null)
    {
        $this->model['view']['limit'] = (int)$limit;
        return $this->get_view_limit();
    }


    function get_view_mode($viewmode = null)
    {
        if (!empty($this->model['view']['mode'][$viewmode])) {
            return $this->model['view']['mode'][$viewmode];
        }
    }


    function get_view_modes()
    {
        return $this->model['view']['mode'];
    }


    function set_view_mode($viewmode = null, $purge = false)
    {
        if ($purge === true) {
            $this->model['view']['mode'] = $viewmode;
        } else {
            if (is_array($viewmode)) {
                foreach ($viewmode as $mode => $fields) {
                    $this->model['view']['mode'][$mode] = $fields;
                }
            }
        }
        return $this->get_view_mode();
    }


    function get_field($fieldname = null)
    {
        if (isset($this->model['table']['fields'][$fieldname])) {
            return $this->model['table']['fields'][$fieldname];
        } else {
            return null;
        }
    }


    function get_fields($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            $fields = $this->model['table']['fields'];
        } else {
            $fields = array_filter($this->model['table']['fields'], function ($field) {
                return ($field['view'] === false);
            });
        }
        return $fields;
    }


    function set_fields($fields = null)
    {
        $field_initialized = $this->init_fields($fields);
        foreach ($$fields_initialized as $fkey => $field) {
            if (!isset($this->model['table']['fields'][$fkey])) {
                $this->model['table']['fields'][$fkey] = $field;
            } else {
                $this->model['table']['fields'][$fkey] = array_merge($this->model['table']['fields'][$fkey], $field);
            }
        }
    }


    function get_fields_name($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            $fields = array_keys($this->model['table']['fields']);
        } else {
            $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
                return ($field['view'] === false);
            }));
        }
        return $fields;
    }


    function get_fields_map($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            foreach ($this->model['table']['fields'] as $index => $field) {
                $fields[$index] = $field['map'];
            }
        } else {
            foreach ($this->model['table']['fields'] as $index => $field) {
                if ($field['view'] === false) {
                    $fields[$index] = $field['map'];
                }
            }
        }
        return $fields;
    }


    function get_fields_display($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            foreach ($this->model['table']['fields'] as $index => $field) {
                $fields[$index] = $field['display'];
            }
        } else {
            foreach ($this->model['table']['fields'] as $index => $field) {
                if ($field['view'] === false) {
                    $fields[$index] = $field['display'];
                }
            }
        }
        return $fields;
    }


    function get_fields_private($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
                return ($field['public'] === false);
            }));
        } else {
            $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
                return ($field['public'] === false and $field['view'] === false);
            }));
        }
        return $fields;
    }


    function get_fields_public($includeview = false)
    {
        $fields = array();
        if ($includeview === true) {
            $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
                return ($field['public'] === true);
            }));
        } else {
            $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
                return ($field['public'] === true and $field['view'] === false);
            }));
        }
        return $fields;
    }


    function get_fields_view()
    {
        $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
            return ($field['view'] === true);
        }));
        return $fields;
    }


    function get_fields_notnull()
    {
        $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
            return ($field['notnull'] === true and $field['view'] === false);
        }));

        // print_r($this->model['table']['fields']);
        return $fields;
    }


    function get_fields_insert()
    {
        $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
            return ($field['insert'] === true and $field['view'] === false);
        }));
        return $fields;
    }


    function get_fields_update()
    {
        $fields = array_keys(array_filter($this->model['table']['fields'], function ($field) {
            return ($field['update'] === true and $field['view'] === false);
        }));
        return $fields;
    }



    function get_response($response)
    {
        if (isset($this->model['response_message'][$response])) {
            return $this->model['response_message'][$response];
        } else {
            return null;
        }
    }


    function get_field_attribute($attribute = null, $field = null)
    {
        if (isset($this->model['table']['fields'][$field])) {
            if (isset($this->model['table']['fields'][$field][$attribute])) {
                return $this->model['table']['fields'][$field][$attribute];
            }
        }
        return null;
    }


    function get_fields_attribute($attribute = null, $fields = null)
    {
        if (is_array($fields)) {
            $return_fields = array();
            foreach ((array)$fields as $idx => $field) {
                $return_fields[$field] = $this->get_field_attribute($attribute, $field);
            }
            return $return_fields;
        } else {
            return $this->get_field_attribute($attribute, $fields);
        }
    }



    function get_id($record = null)
    {
        $key_id = $this->map_fields_out($this->get_primary());
        if (isset($record[$key_id])) {
            return $record[$key_id];
        }
    }

    function generate_id($salt = null)
    {
        if (!$salt) $salt = $this->get_table();
        return md5($salt . strval(round(microtime(true) * 1000)) . rand(0, 1000));
    }


    function event($event = null)
    {
        if (!empty($this->model['event_listener'][$event])) {
            return callback($this->model['event_listener'][$event]);
        }
    }

    function trigger($event = null)
    {
        return $this->event($event);
    }

    function display_fields($fields = null)
    {
        $display = $this->get_fields_display();
        if (!is_string($fields)) {
            $field_display = $this->get_field_attribute('display', $fields);
            if (!empty($field_display)) {
                return $field_display;
            } else {
                return $field;
            }
        }
        return transform_value($fields, $display);
    }


    function alias_fields($fields = null, $alias = null)
    {
        if (empty($alias)) {
            $alias = $this->get_fields_map(true);
        }
        foreach ((array)$fields as $key => $value) {
            if (!empty($alias[$value])) {
                $fields[$key] = $value . ' AS "' . $alias[$value] . '"';
            }
        }
        return $fields;
    }


    function map_fields($fields)
    {
        return $this->map_fields_out($fields);
    }


    function map_fields_in($fields)
    {
        $transformator = array_flip($this->get_fields_map(true));
        if (is_string($fields)) {
            $fields = varIsset($transformator[$fields], $fields);
        } else if (is_array($fields)) {
            $fields = transform_key($fields, $transformator);
        }
        return $fields;
    }


    function map_fields_out($fields)
    {
        $transformator = $this->get_fields_map(true);
        if (is_string($fields)) {
            $fields = varIsset($transformator[$fields], $fields);
        } else if (is_array($fields)) {
            $fields = transform_key($fields, $transformator);
        }
        return $fields;
    }


    function validate_fields($fieldsvalue = null)
    {
        $response = array('valid' => true, 'message' => array());
        $fieldsvalidator = $this->get_fields_attribute('validation', $this->get_fields_name());

        foreach ($fieldsvalue as $field_key => $field_value) {
            if (!empty($fieldsvalidator[$field_key]) and is_array($fieldsvalidator[$field_key])) {
                $validation = new Validation(array(
                    'validator'         => $fieldsvalidator[$field_key],
                    'validation_message' => $this->model['validation_message']
                ));
                $validation_result = $validation->validate($field_value, $fieldsvalue);
                //print_r($validation_result);
                $tpl = new Template(array('template' => $validation_result['message']));
                $validation_result['message'] = $tpl->apply(array(
                    'field' => $this->display_fields($field_key)
                ));
                if ($validation_result['valid'] === false) {
                    $response['valid']   = false;
                    $response['message'] = array_merge((array)  $response['message'], (array) $validation_result['message']);
                }
            }
        }
        return $response;
    }

    function prepare($values, $preparer = null)
    {
        if (empty($preparer)) {
            $preparer = $this->get_fields_attribute('prepare', $this->get_fields_name());
        }
        if (!empty($preparer) and is_callable($preparer)) { // string mode or a function
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $values[$key] = call_user_func($preparer, $value, $values);
                }
            } else {
                $values = call_user_func($preparer, $values);
            }
        } else if (is_array($values) and is_array($preparer)) { // array of function
            foreach ($values as $key => $value) {
                if (isset($preparer[$key]) and is_callable($preparer[$key])) {
                    $values[$key] = call_user_func($preparer[$key], $value, $values);
                }
            }
        }
        return $values;
    }


    function render($records, $renderer = null, $mapped = false)
    {
        if (empty($renderer) and !is_array($renderer)) {
            if ($mapped === true) {
                $renderer = array();
                $fields = $this->get_fields_map(true);
                foreach ($fields as $field_key => $field_map) {
                    $renderer[$field_map] = $this->get_field_attribute('render', $field_key);
                }
            } else {
                $renderer = $this->get_fields_attribute('render', $this->get_fields_name(true));
            }
        }

        if (is_array($records)) {         // if the record is not fake record
            if (!isAssoc($records)) {  //check if $records are multiple records
                foreach ($records as $record_index => $record) {
                    $records[$record_index] = $this->render($record, $renderer);
                }
            } else {  // if $record is single record
                foreach ($records as $record_key => $record_value) {
                    if (isset($renderer[$record_key]) and is_callable($renderer[$record_key])) {
                        $records[$record_key] = call_user_func($renderer[$record_key], $record_value);
                    }
                }
            }
        }
        return $records;
    }


    function get_insertid()
    {
        return $this->model['operation']['insertid'];
    }


    function set_insertid($id = null)
    {
        $this->model['operation']['insertid'] = $id;
        return $this->get_insertid();
    }


    function get_lastquery()
    {
        return $this->model['operation']['last_query'];
    }


    function set_lastquery($query = null)
    {
        $this->model['operation']['last_query'] = $query;
        return $this->get_lastquery();
    }


    function exist($id = null, $untrack = false)
    {
        if (is_array($id)) {
            $result = $this->db->get_where($this->get_table(), $id);
        } else {
            $result = $this->db->get_where($this->get_table(), array($this->get_primary() => $id));
        }
        if ($untrack === false) {
            $this->set_lastquery($this->db->last_query());
        }
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    function isexist($id = null, $untrack = false)
    {
        return $this->exist($id, $untrack);
    }


    function count_exist($where = null, $with_view = false)
    {
        $sql_table = $this->get_table();
        if ($with_view) {
            $sql_table = $this->get_view();
            if (empty($sql_table)) {
                $sql_table = $this->get_table();
            }
        }
        $result = $this->db->get_where($sql_table, $where);
        $this->set_lastquery($this->db->last_query());
        return $result->num_rows();
    }

    function read($id = null, $rendered = false, $mapped = true, $untrack = false)
    {
        $this->event('before_read');
        $record = null;

        //$this->db->from($this->get_view());
        if (is_array($id)) {
            $where = $id;
            // $this->db->where($id);
        } else {
            $where = array($this->get_primary() => $id);
            // $this->db->where(array($this->get_primary()=>$id));
        }
        if ($this->db->field_exists('wajibpajak_id', $this->get_view())) {
            if ($wp_id = $this->session->userdata('wajibpajak_id')) {
                $where['wajibpajak_id'] = $wp_id;
            }
        }
        if ($this->db->field_exists('pemda_id', $this->get_view())) {
            if ($wp_id = $this->session->userdata('pemda_id')) {
                $where['pemda_id'] = $wp_id;
            }
        }
        if (!empty($id)) {
            if ($mapped) {
                $this->db->select($this->alias_fields($this->get_fields_name(true)));
            } else {
                $this->db->select(implode(' ,', $this->get_fields_name(true)));
            }
            $result = $this->db->get_where($this->get_view(), $where);
            if ($untrack === false) {
                $this->set_lastquery($this->db->last_query());
            }
            if ($row = $result->row_array()) {
                $record = $row;
                if ($rendered === true) {
                    $record = $this->render($row, null, $mapped);
                }
            }
        }

        $this->db->flush_cache();

        $this->event('after_read');
        return $record;
    }

    function find($where = null, $rendered = false, $mapped = true, $untrack = false, $order = null)
    {
        $this->event('before_find');
        $records = null;


        if (empty($where)) {
            $where = array();
        }

        if (!empty($order)) {
            if (is_array($order)) {
                foreach ($order as $key => $direction) {
                    $this->db->order_by($key, $direction);
                }
            } else {
                $this->db->order_by($order);
            }
        }

        if ($mapped) {
            $this->db->select($this->alias_fields($this->get_fields_name(true)));
        } else {
            $this->db->select(implode(' ,', $this->get_fields_name(true)));
        }
        $query = $this->db->get_where($this->get_view(), $where);

        if ($untrack === false) {
            $this->set_lastquery($this->db->last_query());
        }

        $records = $query->result_array();
        if ($rendered === true) {
            foreach ($records as $key => $record) {
                $records[$key] = $this->render($row, null, $mapped);
            }
        }

        $this->event('after_find');
        return $records;
    }

    function recorder($record = null, $by = null, $blame = 'insert')
    {
        if (is_array($record)) {
            $id = $record[$tis->map_fields_out($this->get_primary())];
        } else {
            $id = (string) $record;
        }
        if (!empty($id) and in_array($blame, array_keys($this->model['recorder']))) {
            $op = $this->db->update($this->get_table(), array(
                $this->model_default['recorder'][$blame]        => $by,
                $this->model_default['recorder'][$blame . '_at']  => date('Y-m-d H:i:s')
            ), array(
                $this->get_primary() => $id
            ));
            return $op;
        }
    }

    function get_recorder($record = null, $blame = 'insert')
    {
        if (is_array($record)) {
            $id = $record[$this->map_fields_out($this->get_primary())];
        } else {
            $id = (string) $record;
        }
        if (!empty($id) and in_array($blame, array_keys($this->model['recorder']))) {
            $query = $this->db->get_where($this->get_table(), array(
                $this->get_primary() => $id
            ));
            $exist_record = $query->row_array();
            return ($exist_record) ? array(
                $exist_record[$this->model_default['recorder'][$blame]],
                $exist_record[$this->model_default['recorder'][$blame . '_at']]
            ) : null;
        }
    }

    function insert($id = null, $data = null, $fn = null, $return_record = true)
    {
        $this->event('before_insert');

        $response = array('success' => false, 'message' => $this->get_response('insert_failed'), 'id' => null, 'record' => null);
        $replacer = array();

        // initializing data and id
        if (is_array($id)) {
            $data = $id;
            $id = null;
            if (isset($data[$this->get_primary()])) {
                $id = $data[$this->get_primary()];
            }
            if (is_callable($data)) {
                $fn = $data;
            }
        } else {
            $primary = $this->get_field($this->get_primary());
            $data[$primary['map']] = $id;
        }

        // mapping field before it goes deep execution
        $data = $this->map_fields_in($data);

        // execute procces
        if (isNull($data, $this->get_fields_notnull(), true)) {
            $response['success'] = false;
            $response['message'] = $this->get_response('insert_null');
            $replacer['fields_notnull'] = implode(', ', transform_value($this->get_fields_notnull(), $this->get_fields_display()));
        } else {
            if ($this->exist($id, true)) {
                $response['success'] = false;
                $response['message'] = $this->get_response('insert_isexist');
                $replacer['primary'] = $this->get_primary();
                $replacer['primary_value'] = $id;
            } else {
                // echo "string";
                $data = varMatch($data, $this->get_fields_insert());
                $validation = $this->validate_fields($data);
                if ($validation['valid'] === true) {
                    // echo "string";
                    $data = $this->prepare($data);
                    $run_query = true;
                    foreach ($data as $data_key => $data_value) {
                        $unique = $this->get_field_attribute('unique', $data_key);
                        if ($unique === true) {
                            if ($this->exist(array($data_key => $data_value))) {
                                $response['success'] = false;
                                $response['message'] = $this->get_response('unique');
                                $replacer['field']   = $this->display_fields($data_key);
                                $replacer['value']   = $data_value;
                                $run_query = false;
                                break;
                            }
                        }

                        /*Change Htmlspecialchars*/
                        $htmlspecial = $this->get_field_attribute('htmlspecial', $data_key);
                        if ($htmlspecial === true) {
                            $data[$data_key] = htmlspecialchars_decode($data_value);
                        }
                    }
                    if ($run_query === true) {
                        // echo "in";
                        if ($this->db->field_exists('pemda_id', $this->get_table())) {
                            if ($wp_id = $this->session->userdata('pemda_id')) {
                                $data['pemda_id'] = $wp_id;
                            }
                        }
                        $query = $this->db->insert($this->get_table(), $data);
                        // echo $this->db->last_query();
                        $this->set_lastquery($this->db->last_query());
                        if ($query) {
                            if ($id === null) {
                                $this->set_insertid($this->db->insert_id());
                            } else {
                                $this->set_insertid($id);
                            }
                            $response['success'] = true;
                            $response['message'] = $this->get_response('insert_success');
                            $response['id']      = $this->get_insertid();
                            $response['record']  = $return_record ? $this->read($this->get_insertid(), false, true) : null;
                        } else {
                            $response['success'] = false;
                            $response['message'] = $this->get_response('insert_failed');
                        }
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->get_response('insert_failed') . "<br/>" . implode('<br/>', $validation['message']);
                }
            }
        }
        $tpl = new Template($response['message']);
        $response['message'] = $tpl->apply($replacer);

        $this->event('after_insert');

        if (is_callable($fn)) {
            call_user_func($fn, $response);
        }
        return $response;
    }


    function update($id = null, $data = null, $fn = null, $return_record = true)
    {
        $this->event('before_update');

        $response = array('success' => false, 'message' => $this->get_response('update_failed'), 'id' => null, 'record' => null);
        $replacer = array();

        // initializing data and id
        if (is_array($id)) {
            // $sql_where = $id;
            $id = $sql_where = $this->map_fields_in($id);
        } else {
            $sql_where = array($this->get_primary() => $id);
        }

        // parsing with the match field
        $data = $this->map_fields_in($data);
        //print_r($data);
        // execute procces
        if (isNull($data, $this->get_fields_notnull(), false)) {
            $response['success'] = false;
            $response['message'] = $this->get_response('update_null');
        } else {
            if ($this->exist($id, true)) {
                $data = varMatch($data, $this->get_fields_update(), false);
                // print_r($data);
                $validation = $this->validate_fields($data);
                if ($validation['valid'] === true) {
                    $data = $this->prepare($data);

                    // print_r($data);
                    $run_query = true;
                    // echo $run_query;
                    // $data = ($data) != '' ? $data : ''; 
                    foreach ((array)$data as $data_key => $data_value) {
                        // echo "<pre>";
                        // print_r($data);
                        $unique = $this->get_field_attribute('unique', $data_key);
                        if ($unique === true) {
                            $where_count = $sql_where;
                            $where_count[$data_key] = $data_value;
                            if (isset($sql_where[$this->get_primary()]) and !is_array($sql_where[$this->get_primary()])) {
                                unset($where_count[$this->get_primary()]);
                                $where_count[$this->get_primary() . ' !='] = $sql_where[$this->get_primary()];
                            }
                            if ($this->count_exist($where_count) > 0) {
                                if ($this->get_primary() != $data_key) {
                                    $response['success'] = false;
                                    $response['message'] = $this->get_response('unique');
                                    $replacer['field']   = $this->display_fields($data_key);
                                    $replacer['value']   = $data_value;
                                    $run_query = false;
                                    break;
                                }
                            }
                        }

                        /*Change Htmlspecialchars*/
                        $htmlspecial = $this->get_field_attribute('htmlspecial', $data_key);
                        if ($htmlspecial === true) {
                            $data[$data_key] = htmlspecialchars_decode($data_value);
                        }
                    }

                    if ($run_query === true) {
                        if ($this->db->field_exists('pemda_id', $this->get_table())) {
                            if ($wp_id = $this->session->userdata('pemda_id')) {
                                $data['pemda_id'] = $wp_id;
                            }
                        }
                        $query = $this->db->update($this->get_table(), $data, $sql_where);
                        $this->set_lastquery($this->db->last_query());

                        if ($query) {
                            $response['success'] = true;
                            $response['message'] = $this->get_response('update_success');
                            if (!is_array($id)) {
                                $response['id']     = $id;
                                $response['record'] = $return_record ? $this->read($id, false, true) : null;
                            }
                        } else {
                            $response['success'] = false;
                            $response['message'] = $this->get_response('update_failed');
                        }
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->get_response('update_failed') . "<br/>" . implode('<br/>', $validation['message']);
                }
            } else {
                $response['success'] = false;
                $response['message'] = $this->get_response('update_null');
            }
        }
        $tpl = new Template($response['message']);
        $response['message'] = $tpl->apply($replacer);

        $this->event('after_update');

        if (is_callable($fn)) {
            call_user_func($fn, $response);
        }

        return $response;
    }


    function insert_update($id = null, $data = null, $fn = null, $return_record = true)
    {
        if ($this->exist($id)) {
            return $this->update($id, $data, $fn, $return_record);
        } else {
            $id = gen_uuid('direct_from_basemodel');
            return $this->insert($id, $data, $fn, $return_record);
        }
    }


    function delete($id, $fn = null)
    {
        $this->event('before_delete');

        $response = array('success' => false, 'message' => $this->get_response('delete_failed'), 'id' => null, 'record' => null);
        $replacer = array();

        // initializing id is single valu or array
        if (is_array($id)) {
            $id = $sql_where = $this->map_fields_in($id);
        } else {
            $sql_where = array($this->get_primary() => (string) $id);
        }

        if ($this->exist($id, true)) {
            $record = $this->read($id, false, true);
            $query = $this->db->delete($this->get_table(), $sql_where);
            $this->set_lastquery($this->db->last_query());
            if ($query) {
                $response['success'] = true;
                $response['message'] = $this->get_response('delete_success');
                $response['id']      = $id;
                $response['record']  = $record;
            } else {
                if ($this->db->error()['code'] == 1451) {
                    $response['success'] = false;
                    $response['message'] = $this->get_response('delete_constraint');
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->get_response('delete_failed');
                }
            }
        } else if ($this->db->error()['code'] == 1451) {
            $response['success'] = false;
            $response['message'] = $this->get_response('delete_constraint');
        } else {
            $response['success'] = false;
            $response['message'] = $this->get_response('delete_null');
        }

        $tpl = new Template($response['message']);
        $response['message'] = $tpl->apply($replacer);
        $this->event('after_delete');
        if (is_callable($fn)) {
            call_user_func($fn, $response);
        }
        return $response;
    }


    function select($config = null, $fn = null)
    {
        $this->event('before_select');

        // initializing variables
        $sql = $sql_table = $sql_fields = $sql_where = $sql_where_static = $sql_sort =  $sql_sort_static = $sql_where_query = $sql_limit = null;
        $response = array('success' => false, 'total' => null, 'data' => null);

        if (is_string($config)) {
            $config = array('fields' => $this->get_view_mode($config));
        }

        // table init
        if (isset($config['table']) and !empty($config['table'])) {
            $sql_table = $config['table'];
        } else {
            $sql_table = $this->get_view();
            if (empty($sql_table)) {
                $sql_table = $this->get_table();
            }
        }

        // fields init
        $fields = $fields_public = $this->get_fields_public(true);
        $fields_mapper = $this->get_fields_map(true);
        if (!empty($config['fields'])) {
            if (is_string($config['fields'])) {
                $config['fields'] = explode(',', $config['fields']);
            }
            if (is_array($config['fields'])) {
                if (isAssoc($config['fields'])) {
                    $fields_mapper = $fields = varMatch($config['fields'], $fields_public);
                    $fields = array_keys($fields);
                } else {
                    $fields = $config['fields'];
                }
            }
        } elseif (!empty($config['view_mode'])) {
            $fields = $this->get_view_mode($config['view_mode']);
        }
        $fields = array_intersect($fields, $fields_public);
        $fields = $this->alias_fields($fields, $fields_mapper);
        $sql_fields = implode(', ', $fields);
        //custom fields
        if (!empty($config['custom_fields'])) {
            $sql_fields = $config['custom_fields'];
        }
        // sort init
        if (!empty($config['sort'])) {
            if (!is_array($config['sort'])) {
                $sort = json_decode($config['sort']);
            } else {
                $sort = $config['sort'];
            }
            if (is_array($sort)) {
                $sql_sort_temp = array();
                foreach ($sort as $key => $order) {
                    if (isset($order->property)) {
                        $sql_sort_temp[] = $order->property . " " . ($order->direction ? $order->direction : 'ASC');
                    }
                }
                $sql_sort = implode(', ', $sql_sort_temp);
            }
        }

        // limit init
        if (!empty($config['start']) and is_int((int) $config['start'])) {
            $sql_limit_start = $config['start'];
        } else {
            $sql_limit_start = 0;
        }
        if (!empty($config['limit']) and is_int((int) $config['limit'])) {
            $sql_limit_finish = $config['limit'];
        } else {
            $sql_limit_finish = $this->get_view_limit();
        }
        if (!empty($sql_limit_finish)) {
            $sql_limit = " OFFSET " . $sql_limit_start . " LIMIT " . $sql_limit_finish;
        }

        // filters init
        $encoded = false;
        $sql_where   = ' 0 = 0';
        if (!isset($config['filters'])) {
            $config['filters'] = null;
        }
        if (!is_array($config['filters'])) {
            $encoded = true;
            $config['filters'] = json_decode($config['filters']);
        }
        if (is_array($config['filters'])) {
            $qs = '';
            for ($i = 0; $i < count($config['filters']); $i++) {
                $filter = $config['filters'][$i];
                if ($encoded) {
                    // var_dump($filter);
                    $field      = isset($filter->field)         ? $filter->field        : (isset($filter->property) ? $filter->property : null);
                    $value      = isset($filter->value)         ? $filter->value        : "";
                    $compare    = isset($filter->comparison)    ? $filter->comparison   : null;
                    $filterType = isset($filter->type)          ? $filter->type         : "string";
                } else {
                    $field      = isset($filter['field'])               ? $filter['field'] : $filter['property'];
                    $value      = isset($filter['data']['value'])       ? $filter['data']['value'] : "";
                    $compare    = isset($filter['data']['comparison'])  ? $filter['data']['comparison'] : null;
                    $filterType = isset($filter['data']['type'])        ? $filter['data']['type'] : 'string';
                }
                $field = $this->map_fields_in($field);
                switch ($filterType) {
                    case 'custom':
                        $qs .= $value;
                        break;

                    case 'string':
                        if (!empty($field)) {
                            $qs .= " AND " . $field . " LIKE '%" . $value . "%'";
                        }
                        break;

                    case 'boolean':
                        if (!empty($field)) {
                            $qs .= " AND " . $field . " = " . ((bool)$value ? '1' : '0');
                        }
                        break;

                    case 'exact':
                        if (empty($value)) {
                            $qs .= " AND " . $field . " IS NULL";
                        } else {
                            $qs .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;

                    case 'list':
                        if (is_array($value)) {
                            for ($q = 0; $q < count($value); $q++) {
                                if ($value[$q] === null) $value[$q] = 'NULL';
                            }
                            $value = implode(',', $value);
                            $qs .= " AND " . $field . " IN (" . $value . ")";
                        } else if (strstr($value, ',')) {
                            $fi = explode(',', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = "'" . $fi[$q] . "'";
                            }
                            $value = implode(',', $fi);
                            $qs .= " AND " . $field . " IN (" . $value . ")";
                        } else {
                            $qs .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;

                    case 'numeric':
                        switch ($compare) {
                            case 'eq':
                                $qs .= " AND " . $field . " = " . $value;
                                break;
                            case 'lt':
                                $qs .= " AND " . $field . " < " . $value;
                                break;
                            case 'gt':
                                $qs .= " AND " . $field . " > " . $value;
                                break;
                        }
                        break;

                    case 'date':
                        switch ($compare) {
                            case 'eq':
                                $qs .= " AND " . $field . " = '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                            case 'lt':
                                $qs .= " AND " . $field . " < '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                            case 'gt':
                                $qs .= " AND " . $field . " > '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                        }
                        break;
                }
            }
            $sql_where .= $qs;
        }

        // additional filter
        if (array_key_exists('filters_static', $config)) {
            $sql_where_static = $this->_where($config['filters_static']);
            if (!empty($sql_where_static)) {
                $sql_where_static = $sql_where_static . " AND ";
            }
        }

        if (!isset($config['without_global_scope'])) {
            if ($this->db->field_exists('pemda_id', $sql_table)) {
                $sql_where .= ' AND ' . $sql_table . '.pemda_id=' . $this->db->escape($this->session->userdata('pemda_id'));
            }
        }

        if (array_key_exists('without_global_scope', $config)) {
            if (!$config['without_global_scope']) {
                if ($this->db->field_exists('pemda_id', $sql_table)) {
                    $sql_where .= ' AND ' . $sql_table . '.pemda_id=' . $this->db->escape($this->session->userdata('pemda_id'));
                }
            }
        }

        // custom filters
        if (array_key_exists('filters_query', $config) and is_array($config['filters_query']) and !empty($config['filters_query'])) {
            $config['filters_query'] = varApplyIf($config['filters_query'], array(
                'fields' => array(),
                'value' => null,
                'strict' => false,
            ));
            if (!is_array($config['fields'])) $config['fields'] = array($config['fields']);

            $sql_where_query = array();
            foreach ($config['filters_query']['fields'] as $key => $value) {
                $sql_where_query[] = $this->map_fields_in($value) . ' LIKE \'%' . $config['filters_query']['value'] . '%\'';
            }
            if ($config['filters_query']['strict'] === true) {
                $sql_where_query = implode(' AND ', $sql_where_query);
            } else {
                $sql_where_query = implode(' OR ', $sql_where_query);
            }
            if (!empty($sql_where_query)) {
                $sql_where_query = "(" . $sql_where_query . ") AND ";
            }
        }

        // additional grouped
        $_sql_sort = array();
        if (array_key_exists('group_static', $config)) {
            if (is_string($config['group_static'])) $sql_group_static = $config['group_static'];
            if (is_array($config['group_static'])) {
                if (isAssoc($config['group_static'])) {
                    foreach ($config['group_static'] as $key => $value) {
                        $config['group_static'][$key] .= $key . ' ' . $value;
                    }
                }
                $sql_group_static = implode(', ', $config['group_static']);
            }
            $sql_group = trim($sql_group);
            $sql_group_static = trim($sql_group_static);
            $_sql_group = array();
            if (!empty($sql_group_static)) $_sql_group[] = $sql_group_static;
            if (!empty($sql_group)) $_sql_group[] = $sql_group;
        } else {
            $_sql_group = array($sql_group);
        }
        $_sql_group = trim(implode(', ', $_sql_group));
        if (!empty($_sql_group)) {
            $_sql_group = " GROUP BY " . $_sql_group;
        } else {
            $_sql_group = '';
        }

        // additional sorter
        $_sql_sort = array();
        if (array_key_exists('sort_static', $config)) {
            if (is_string($config['sort_static'])) $sql_sort_static = $config['sort_static'];
            if (is_array($config['sort_static'])) {
                if (isAssoc($config['sort_static'])) {
                    foreach ($config['sort_static'] as $key => $value) {
                        $config['sort_static'][$key] .= $key . ' ' . $value;
                    }
                }
                $sql_sort_static = implode(', ', $config['sort_static']);
            }
            $sql_sort = trim($sql_sort);
            $sql_sort_static = trim($sql_sort_static);
            $_sql_sort = array();
            if (!empty($sql_sort_static)) $_sql_sort[] = $sql_sort_static;
            if (!empty($sql_sort)) $_sql_sort[] = $sql_sort;
        } else {
            $_sql_sort = array($sql_sort);
        }
        $_sql_sort = trim(implode(', ', $_sql_sort));
        if (!empty($_sql_sort)) {
            $_sql_sort = " ORDER BY " . $_sql_sort;
        } else {
            $_sql_sort = '';
        }

        // fetching record
        $sql = "SELECT " . $sql_fields . " FROM " . $sql_table . " WHERE " . $sql_where_static . $sql_where_query . $sql_where . " " . $_sql_group . " " . $_sql_sort;
        if (array_key_exists('get_query', $config)) {
            $response['total'] = 0;
            $response['data'] = [];
            $response['sql'] = $sql;
            return $response;
        } else {
            $result     = $this->db->query($sql);
            $count      = $result->num_rows();
            if (!empty($sql_limit)) {
                $result = $this->db->query($sql . $sql_limit);
            }
            if ($result) {
                $response['success'] = true;
            }
            $records   = $result->result_array();
            $this->set_lastquery($this->db->last_query());

            // rendering output
            if (isset($config['rendered']) and $config['rendered'] === true) {
                $records = $this->render($records, null, true);
            }

            $response['total'] = $count;
            $response['data'] = $records;

            $this->event('after_select');
            callback($fn, $response);

            return $response;
        }
    }

    function truncate($soft = false)
    {
        if ($soft) {
            return $this->db->empty_table($this->get_table());
        } else {
            return $this->db->truncate($this->get_table());
        }
    }

    protected function _where($key, $value = NULL)
    {
        $ar_where = array();
        if (!is_array($key)) {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v) {
            if ($this->_has_operator($k)) {
                $k = explode(' ', trim($k));
                if ($k[0]) {
                    $k[0] = $this->map_fields_in($k[0]);
                }
                $k = implode(' ', $k);
            } else {
                $k = $this->map_fields_in($k);
            }
            if (is_null($v) && !$this->_has_operator($k)) {
                $k .= ' IS NULL';
            }
            if (!is_null($v) and !$this->_has_operator($k)) {
                $k .= ' = ';
            }
            if (!is_numeric($v) and !is_null($v)) {
                $v = "'" . $v . "'";
            } else if (gettype($v) == "string") { // migrate psql
                $v = "'" . $v . "'";
            }
            $ar_where[] = $k . $v;
        }
        return implode(' AND ', $ar_where);
    }

    protected function _has_operator($str)
    {
        $str = trim($str);
        if (!preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function filter_query($config = null)
    {
        // filters init
        $encoded = false;
        $sql_where_static = $sql_where = '';

        if (!isset($config['filters'])) {
            $config['filters'] = null;
        }
        if (!is_array($config['filters'])) {
            $encoded = true;
            $config['filters'] = json_decode($config['filters']);
        }
        if (is_array($config['filters'])) {
            $qs = '';
            for ($i = 0; $i < count($config['filters']); $i++) {
                $filter = $config['filters'][$i];
                if ($encoded) {
                    $field      = isset($filter->field)         ? $filter->field : $filter->property;
                    $value      = isset($filter->value)         ? $filter->value : "";
                    $compare    = isset($filter->comparison)    ? $filter->comparison : null;
                    $filterType = isset($filter->type)          ? $filter->type : "string";
                } else {
                    $field      = isset($filter['field'])               ? $filter['field'] : $filter['property'];
                    $value      = isset($filter['data']['value'])       ? $filter['data']['value'] : "";
                    $compare    = isset($filter['data']['comparison'])  ? $filter['data']['comparison'] : null;
                    $filterType = isset($filter['data']['type'])        ? $filter['data']['type'] : 'string';
                }
                $field = $this->map_fields_in($field);
                switch ($filterType) {
                    case 'custom':
                        $qs .= $value;
                        break;
                    case 'string':
                        $qs .= " AND " . $field . " LIKE '%" . $value . "%'";
                        break;
                    case 'boolean':
                        $qs .= " AND " . $field . " = " . ((bool)$value ? '1' : '0');
                        break;
                    case 'exact':
                        if (empty($value)) {
                            $qs .= " AND " . $field . " IS NULL";
                        } else {
                            $qs .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;
                    case 'list':
                        if (is_array($value)) {
                            for ($q = 0; $q < count($value); $q++) {
                                if ($value[$q] === null) $value[$q] = 'NULL';
                            }
                            $value = implode(',', $value);
                            $qs .= " AND " . $field . " IN (" . $value . ")";
                        } else if (strstr($value, ',')) {
                            $fi = explode(',', $value);
                            for ($q = 0; $q < count($fi); $q++) {
                                $fi[$q] = "'" . $fi[$q] . "'";
                            }
                            $value = implode(',', $fi);
                            $qs .= " AND " . $field . " IN (" . $value . ")";
                        } else {
                            $qs .= " AND " . $field . " = '" . $value . "'";
                        }
                        break;
                    case 'numeric':
                        switch ($compare) {
                            case 'eq':
                                $qs .= " AND " . $field . " = " . $value;
                                break;
                            case 'lt':
                                $qs .= " AND " . $field . " < " . $value;
                                break;
                            case 'gt':
                                $qs .= " AND " . $field . " > " . $value;
                                break;
                        }
                        break;
                    case 'date':
                        switch ($compare) {
                            case 'eq':
                                $qs .= " AND " . $field . " = '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                            case 'lt':
                                $qs .= " AND " . $field . " < '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                            case 'gt':
                                $qs .= " AND " . $field . " > '" . date('Y-m-d', strtotime($value)) . "'";
                                break;
                        }
                        break;
                }
            }
            $sql_where .= $qs;
        }

        if (array_key_exists('filters_static', $config)) {
            $sql_where_static = $this->_where($config['filters_static']);
            if (!empty($sql_where_static)) {
                $sql_where_static = $sql_where_static . " AND ";
            }
        }

        return $sql_where_static . $sql_where;
    }


    public function generate_kode($config = array())
    {
        $config = array_merge(array(
            'pattern'       => null,
            'date_format'   => 'ym',
            'field'         => $this->get_primary(),
            'index_format'  => '0000',
            'index_mask'    => false
        ), $config);

        $tpl = new Template($config['pattern']);
        $tpl = new Template($tpl->apply(array(
            'date' => date($config['date_format'])
        )));
        $tpl_query = $tpl->apply(array('#' => '%'));
        $this->db->select_max($config['field']);
        $this->db->where(array(
            $config['field'] . ' like' => $tpl_query
        ));

        $query = $this->db->get($this->get_tablename());
        $row = $query->row_array();
        if ($row and !empty($row[$config['field']])) {
            $same = join('', array_intersect_assoc(str_split($row[$config['field']]), str_split($tpl_query)));
            $diff = join('', array_diff_assoc(str_split($row[$config['field']]), str_split($tpl_query)));
            // $next = fillchar(((int)$diff + 1), strlen($config['index_format']),'0','left');
            $next = str_pad(((int)$diff + 1), strlen($config['index_format']), '0', STR_PAD_LEFT);
        } else {
            $next = str_pad(1, strlen($config['index_format']), '0', STR_PAD_LEFT);
            // $next = fillchar(1, strlen($config['index_format']),'0','left');
        }

        if ($config['index_mask'] === false) {
            return $tpl->apply(array('#' => $next));
        } else if ($config['index_mask'] === true) {
            return $next;
        } else {
            return $tpl->apply(array('#' => $config['index_mask']));
        }
    }

    public function get_data($select, $table, $where)
    {
        if (is_array($where)) {
            $operation = $this->db->select($select)->from($table)->where($where)->get()->result_array();
        } else {
            $operation = $this->db->select($select)->from($table)->where(array($this->get_field_primary($table) => $where))->get()->result_array();
        }
        return $operation;
    }

    function get_field_primary($table)
    {
        $result = $this->db->list_fields($table);
        foreach ($result as $field) {
            $data[] = $field;
            return $data[0];
        }
    }

    public function getTable($foreign_key = '')
    {
        return array(
            'name'          => $this->get_table(),
            'primary'       => $this->get_primary(),
            'view'          => $this->get_view(),
            'foreign_key'   => $foreign_key
        );
    }
}
