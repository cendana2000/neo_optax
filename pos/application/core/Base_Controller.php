<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . "third_party/MX/Controller.php";

class BASE_Controller extends MX_Controller
{

    protected $messages = array();
    private $CI;
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, Content-Length, Accept-Encoding");
        date_default_timezone_set("Asia/Jakarta");
        parent::__construct();
        $this->load->database();
        $this->CI = &get_instance();
        $this->CI->load->database();

        load_model(array(
            'conf/ConfigurationModel' => 'Configuration',
        ));

        $cnf = $this->Configuration->select();
        if ($cnf['success'] && $cnf['total'] > 0) {
            foreach ($cnf['data'] as $key => $value) {
                $this->config->set_item($value['conf_code'], $value['conf_value']);
            }

            $dataKeyFcm = [
                'apiKey'        => $this->config->item('fcm_api_key'),
                'authDomain'    => $this->config->item('fcm_auth_domain'),
                'projectId'     => $this->config->item('fcm_project_id'),
                'storageBucket' => $this->config->item('fcm_storage_bucket'),
                'messagingSenderId' => $this->config->item('fcm_messaging_sender_id'),
                'appId'         => $this->config->item('fcm_app_id'),
                'measurementId' => $this->config->item('fcm_measurement_id'),
            ];
            $this->config->set_item('config_fcm', base64_encode(base64_encode(json_encode($dataKeyFcm))));
        }
    }

    public function index() {}

    function model($modelName = null)
    {
        return $this->load->model($modelName);
    }


    public function select_dt($client, $modelname, $vmode = null, $serialNumber = false, $filterQuery = null, $filterRecord = null)
    {
        $client = is_null($client) ? $_POST : $client;


        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password
        );

        $table = $this->$modelname->get_view();
        $primaryKey = $this->$modelname->get_primary();
        $columns = array();
        foreach ($this->$modelname->get_view_mode($vmode) as $key => $value) {
            array_push($columns, array('db' => $value, 'dt' => $value));
        }

        if (!$filterQuery) {
            $filterQuery = [];
        }

        if ($this->db->field_exists('wajibpajak_id', $table)) {
            if ($wp_id = $this->session->userdata('wajibpajak_id')) {
                $filterQuery['wajibpajak_id'] = $wp_id;
            }
        }

        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;

        // print_r($table);
        $ssp_data = $this->ssp->complex($client, $db, $table, $primaryKey, $columns, $_filterRecord, $_filterQuery);
        // $print[] = $this->db->last_query();
        $data_rendered = array();
        // print_r($ssp_data);die();

        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {
            // $val_encoded = base64_encode(json_encode($this->$modelname->read($value[$primaryKey])));
            $val_encoded = base64_encode(json_encode([$primaryKey => $value[$primaryKey]]));
            $input       = '<input type="checkbox" name="checkbox" class="checkbox d-none mx-auto" data-record="' . $val_encoded . '" /><span></span>';
            // $input       = '<input type="hidden" name="checkbox" data-record="'.$val_encoded.'" />';  
            $value[0]     = '<span class="not-checkbox">' . $nomor . '.</span> ' . $input;
            $nomor++;
            // $i           = 0;
            // if ($serialNumber) {
            //     $i=1;
            // }
            // unset($value[$primaryKey]);
            // foreach ($value as $k => $v) {
            //     if ($i==0) {
            //         $data[$i] = '<span>'.$value[$k].'.</span> '.$input; 
            //     }else{
            //         $data[$i] = $value[$k];
            //     }
            //     $i++;
            // }
            array_push($data_rendered, $value);
            // print_r($data_rendered);

        }
        // print_r($data_rendered);
        return
            array(
                // 'draw'              => $ssp_data['draw'],
                // 'recordsFiltered'   => $ssp_data['recordsFiltered'],
                // 'recordsTotal'      => $ssp_data['recordsTotal'],
                // 'data'              => $data_rendered
                // 'oo' => $print,
                'iTotalRecords'      => $ssp_data['recordsTotal'],
                'iTotalDisplayRecords'   => $ssp_data['recordsFiltered'],
                "sEcho" => 0,
                "sColumns" => "",
                "aaData" => $data_rendered
            );
    }

    public function select_dt_($client, $modelname, $vmode = null, $serialNumber = false, $filterQuery = null, $filterRecord = null)
    {
        $client = is_null($client) ? $_POST : $client;
        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password,
        );


        $table = $this->$modelname->get_view();
        $primaryKey = $this->$modelname->get_primary();
        // print_r($db);die();
        $columns = array();
        foreach ($this->$modelname->get_view_mode($vmode) as $key => $value) {
            array_push($columns, array('db' => $value, 'dt' => $value));
        }
        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;

        // print_r($table);die();
        $ssp_data = $this->ssp->complex($client, $db, $table, $primaryKey, $columns, $_filterRecord, $_filterQuery);
        // $print[] = $this->db->last_query();
        $data_rendered = array();
        // print_r($ssp_data);die();   
        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {
            // $val_encoded = base64_encode(json_encode($this->$modelname->read($value[$primaryKey])));
            $val_encoded = base64_encode(json_encode([$primaryKey => $value[$primaryKey]]));
            $input       = '<input type="checkbox" name="checkbox" class="checkbox d-none" data-record="' . $val_encoded . '" />';
            // $input       = '<input type="hidden" name="checkbox" data-record="'.$val_encoded.'" />';  
            $value[0]     = '<span class="not-checkbox">' . $nomor . '.</span> ' . $input;
            $nomor++;
            // $i           = 0;
            // if ($serialNumber) {
            //     $i=1;
            // }
            // unset($value[$primaryKey]);
            // foreach ($value as $k => $v) {
            //     if ($i==0) {
            //         $data[$i] = '<span>'.$value[$k].'.</span> '.$input; 
            //     }else{
            //         $data[$i] = $value[$k];
            //     }
            //     $i++;
            // }
            array_push($data_rendered, $value);
            // print_r($data_rendered);

        }
        // print_r($data_rendered);
        return
            array(
                // 'draw'              => $ssp_data['draw'],
                // 'recordsFiltered'   => $ssp_data['recordsFiltered'],
                // 'recordsTotal'      => $ssp_data['recordsTotal'],
                // 'data'              => $data_rendered
                // 'oo' => $print,
                'iTotalRecords'      => $ssp_data['recordsTotal'],
                'iTotalDisplayRecords'   => $ssp_data['recordsFiltered'],
                "sEcho" => 0,
                "sColumns" => "",
                "aaData" => $data_rendered
            );
    }
    public function select_dt2($client, $modelname, $vmode = null, $serialNumber = false, $filterQuery = null, $filterRecord = null)
    {
        $client = is_null($client) ? $_POST : $client;
        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password,
        );

        $table = $this->$modelname->get_view();
        $primaryKey = $this->$modelname->get_primary();
        $columns = array();
        foreach ($this->$modelname->get_view_mode($vmode) as $key => $value) {
            array_push($columns, array('db' => $value, 'dt' => $value));
        }
        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;

        // print_r($table);
        $ssp_data = $this->ssp->complex($client, $db, $table, $primaryKey, $columns, $_filterRecord, $_filterQuery);
        // $print[] = $this->db->last_query();
        $data_rendered = array();
        // print_r($ssp_data);die();

        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {
            // $val_encoded = base64_encode(json_encode($this->$modelname->read($value[$primaryKey])));
            $val_encoded = base64_encode(json_encode([$primaryKey => $value[$primaryKey]]));
            $input       = '<input type="checkbox" name="checkbox" class="checkbox d-none mx-auto" data-record="' . $val_encoded . '" /><span></span>';
            // $input       = '<input type="hidden" name="checkbox" data-record="'.$val_encoded.'" />';  
            $value[0]     = '<span class="not-checkbox">' . $nomor . '.</span> ' . $input;
            $nomor++;
            // $i           = 0;
            // if ($serialNumber) {
            //     $i=1;
            // }
            // unset($value[$primaryKey]);
            // foreach ($value as $k => $v) {
            //     if ($i==0) {
            //         $data[$i] = '<span>'.$value[$k].'.</span> '.$input; 
            //     }else{
            //         $data[$i] = $value[$k];
            //     }
            //     $i++;
            // }
            array_push($data_rendered, $value);
            // print_r($data_rendered);

        }
        // print_r($data_rendered);
        return
            array(
                // 'draw'              => $ssp_data['draw'],
                // 'recordsFiltered'   => $ssp_data['recordsFiltered'],
                // 'recordsTotal'      => $ssp_data['recordsTotal'],
                // 'data'              => $data_rendered
                // 'oo' => $print,
                'iTotalRecords'      => $ssp_data['recordsTotal'],
                'iTotalDisplayRecords'   => $ssp_data['recordsFiltered'],
                "sEcho" => 0,
                "sColumns" => "",
                "aaData" => $data_rendered
            );
    }

    public function select_srv($client, $name_table, $query_table, $filterQuery = null, $filterRecord = null, $view_all = false, $multipe = false)
    {
        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password,
        );

        $name_table = $name_table;
        $primaryKey = '';

        $table  = $this->db->select($query_table)->from($name_table)->get();

        $columns = array();
        foreach ($table->list_fields() as $field) {
            $data[] = $field;
            $primaryKey = $data[0];
            array_push($columns, array('db' => $field, 'dt' => $field));
        }

        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;

        $ssp_data = $this->ssp->complex($client, $db, $name_table, $primaryKey, $columns, $_filterRecord, $_filterQuery);

        $data_rendered = array();
        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {

            if ($view_all == false) {
                $xy = array_chunk($value, 1, 1);
                $key_n = base64_encode(json_encode($xy[0]));
            } else {
                $key_n = base64_encode(json_encode($value));
            }

            if ($multipe === false) {
                $input = '<input type="checkbox" name="checkbox" class="checkbox hide" data-record="' . $key_n . '" />';
                $data[0] = '<span>' . $nomor . '.</span> ' . $input;
                $nomor++;
            } else {
                $input = '<input type="checkbox" name="checkbox" class="checkbox" data-record="' . $key_n . '" />';
                $data[0] =  $input;
            }

            $i = 1;
            unset($value[$primaryKey]);
            foreach ($value as $k => $v) {
                $data[$i] = $value[$k];
                $i++;
            }
            array_push($data_rendered, $data);
        }
        return array(
            'draw'              => $ssp_data['draw'],
            'recordsFiltered'   => $ssp_data['recordsFiltered'],
            'recordsTotal'      => $ssp_data['recordsTotal'],
            'data'              => $data_rendered
        );
    }

    public function select_dt_multiple($client, $modelname, $vmode = null, $filterQuery = null, $filterRecord = null, $nm_checkbox = null)
    {
        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password,
        );
        $ck = ($nm_checkbox == null) ? 'checkbox' : $nm_checkbox;

        $table = $this->$modelname->get_view();
        $primaryKey = $this->$modelname->get_primary();
        $columns = array();
        foreach ($this->$modelname->get_view_mode($vmode) as $key => $value) {
            array_push($columns, array('db' => $value, 'dt' => $value));
        }

        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;

        $ssp_data = $this->ssp->complex($client, $db, $table, $primaryKey, $columns, $_filterRecord, $_filterQuery);
        $data_rendered = array();
        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {
            $val_encoded = base64_encode(json_encode($value));
            $input = '<input type="checkbox" name="checkbox" class="' . $ck . '" data-record="' . $val_encoded . '" />';
            $data[0] = $input;
            $data[1] = $nomor++;
            $i = 2;
            unset($value[$primaryKey]);
            foreach ($value as $k => $v) {
                $data[$i] = $value[$k];
                $i++;
            }
            array_push($data_rendered, $data);
        }
        return array(
            'draw'              => $ssp_data['draw'],
            'recordsFiltered'   => $ssp_data['recordsFiltered'],
            'recordsTotal'      => $ssp_data['recordsTotal'],
            'data'              => $data_rendered
        );
    }

    public function select_md($client, $modelname, $vmode = null, $filterQuery = null, $filterRecord = null, $multipe = false, $nm_checkbox = null)
    {
        $db = array(
            'host'  => $this->db->hostname,
            'db'    => $this->db->database,
            'user'  => $this->db->username,
            'pass'  => $this->db->password,
        );
        $ck = ($nm_checkbox == null) ? 'checkbox' : $nm_checkbox;

        $table = $this->$modelname->get_view();
        $primaryKey = $this->$modelname->get_primary();
        $columns = array();
        foreach ($this->$modelname->get_view_mode($vmode) as $key => $value) {
            array_push($columns, array('db' => $value, 'dt' => $value));
        }

        $_filterQuery  = ($filterQuery !== null) ? $this->_where($filterQuery) : null;
        $_filterRecord = ($filterRecord !== null) ? $this->_where($filterRecord) : null;


        $ssp_data = $this->ssp->complex($client, $db, $table, $primaryKey, $columns, $_filterRecord, $_filterQuery);
        $data_rendered = array();
        $nomor = $client['start'] + 1;
        foreach ($ssp_data['data'] as $key => $value) {
            $val_encoded = base64_encode(json_encode($value));
            if ($multipe) {
                $input      = '<input type="checkbox" name="checkbox" class="' . $ck . '" data-record="' . $val_encoded . '" />';
                $data[0]    = $input;
                if ($multipe) {
                    $data[1]    = $nomor++;
                }
            } else {
                $input      = '<input type="checkbox" name="checkbox" class="' . $ck . ' hide" data-record="' . $val_encoded . '" />';
                $data[0]    = '<span>' . $nomor . '.</span> ' . $input;
                $nomor++;
            }
            $i = (($multipe) ? 2 : 1);
            unset($value[$primaryKey]);
            foreach ($value as $k => $v) {
                $data[$i] = $value[$k];
                $i++;
            }
            array_push($data_rendered, $data);
        }
        return array(
            'draw'              => $ssp_data['draw'],
            'recordsFiltered'   => $ssp_data['recordsFiltered'],
            'recordsTotal'      => $ssp_data['recordsTotal'],
            'data'              => $data_rendered
        );
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
                $k = implode(' ', $k);
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

    protected function get_message($key = null)
    {
        if (!empty($this->messages[$key])) return $this->messages[$key];
    }

    // public function response($operation = array()){
    //     if(empty($operation)){
    //         $operation = array('success'=>false);
    //     }
    //     $this->output->set_content_type('application/json');
    //     $this->output->set_output(json_encode($operation,JSON_UNESCAPED_UNICODE));
    // }
    public function response($operation = array())
    {
        if (empty($operation)) {
            $operation = array('success' => false);
        }
        /* elseif (is_array($operation)) {
            $operation = cleanResponse($operation);
        } */
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($operation, JSON_UNESCAPED_UNICODE));
    }



    public function response_dt($operation = array(), $modelname = '')
    {
        if (empty($operation)) {
            $operation = array('success' => false);
        }
        //little tweak for datatable
        $retval = array();
        foreach ($operation['data'] as $key => $value) {
            $val_encoded = base64_encode(json_encode($this->model($modelname)->read($value[$this->model($modelname)->get_primary()])));
            $input = array('<input type="radio" name="radio-grid" data-record="' . $val_encoded . '" />');
            $retval[] = array_values(array_merge($input, $value));
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array('aaData' => $retval)));

        /*if(empty($operation)){
            $operation = array('success'=>false);
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($operation,));*/
    }

    // === FIND DATA IN WHERE ARRAY PHP RETURN MULTIPLE DATA === \\
    public function findWhere($_array, $_matching)
    {
        $return = array();
        foreach ($_array as $item) {
            $is_match = true;
            foreach ($_matching as $key => $value) {

                if (is_object($item)) {
                    if (!isset($item->$key)) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if (!isset($item[$key])) {
                        $is_match = false;
                        break;
                    }
                }

                if (is_object($item)) {
                    if ($item->$key != $value) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if ($item[$key] != $value) {
                        $is_match = false;
                        break;
                    }
                }
            }

            if ($is_match) {
                array_push($return, $item);
            }
        }
        return $return;
    }
    // === FIND DATA IN WHERE ARRAY PHP RETURN SINGLE DATA === \\
    public function findRead($_array, $_matching)
    {
        foreach ($_array as $item) {
            $is_match = true;
            foreach ($_matching as $key => $value) {

                if (is_object($item)) {
                    if (!isset($item->$key)) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if (!isset($item[$key])) {
                        $is_match = false;
                        break;
                    }
                }

                if (is_object($item)) {
                    if ($item->$key != $value) {
                        $is_match = false;
                        break;
                    }
                } else {
                    if ($item[$key] != $value) {
                        $is_match = false;
                        break;
                    }
                }
            }

            if ($is_match) {
                return $item;
            }
        }
        return false;
    }
    // === SORT ARRAY BY VALUE ===
    function sortByValue($data, $subkey, $_sort = '')
    {
        foreach ($data as $k => $v) {
            $b[$k] = strtolower($v[$subkey]);
        }
        if ($_sort == 'asc') {
            asort($b);
        } else {
            arsort($b);
        }
        foreach ($b as $key => $val) {
            $c[] = $data[$key];
        }
        return $c;
    }
    // === INDONESIAN MONTH ===
    public function indoMonth($index)
    {
        $month = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        return $month[$index];
    }
    // === MONTH ===
    public function getMonth()
    {
        $month = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );

        return $month;
    }
    // === GENERATE PASSWORD ===
    public function password($password = null)
    {
        $passwd = $this->CI->db->query("SELECT MD5(MD5('" . $password . "')) AS password_generated");
        if ($a = $passwd->row_array()) {
            return $a['password_generated'];
        }
        return false;
    }

    public function sendEmail($to, $subject, $data)
    {
        $emailto = $to;
        $emailfrom = $this->config->item('app_email');
        $emailpass = $this->config->item('app_email_password');
        $emailfrom_name = $this->config->item('app_email_name');
        if ($data['emailfrom_name']) {
            $emailfrom_name = $data['emailfrom_name'];
        }
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 465,
            'smtp_user' => $emailfrom,
            'smtp_timeout' => '10',
            'smtp_pass' => $emailpass,
            'smtp_crypto' => 'ssl',
            'mailtype'  => 'html',
            '_smtp_auth'  => true,
            'charset'   => 'utf-8',
            'wordwrap' => true,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        );

        $this->load->library('email', $config);
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($emailfrom, $emailfrom_name);
        $this->email->to($emailto);

        $this->email->subject($subject);
        $this->email->message($data['message']);

        if (!$this->email->send()) {
            $result = [
                'success' => false,
                'message' => $this->email->print_debugger()
            ];
        } else {
            $result = [
                'success' => true,
                'message' => 'Success to send email',
            ];
        }
        return $result;
    }

    public function doUpload($configUpload = array(), $iscrop = false)
    {
        $operation['success'] = true;
        $filess = $_FILES[$configUpload['form_name']]['name'];
        $config['upload_path'] = './assets/' . $configUpload['directory'] . '/cover/asli';
        $config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|png|PNG';
        $config['max_size'] = 1024 * 1024;
        $string = preg_replace('/[^\p{L}\p{N}\s]/u', '', $configUpload['file_name']);
        $config['file_name'] = $string . time();
        $this->upload->initialize($config);
        if ($filess) {
            $_FILES['upload_field_name']['name']        = $_FILES[$configUpload['form_name']]['name'];
            $_FILES['upload_field_name']['type']        = $_FILES[$configUpload['form_name']]['type'];
            $_FILES['upload_field_name']['tmp_name']    = $_FILES[$configUpload['form_name']]['tmp_name'];
            $_FILES['upload_field_name']['error']       = $_FILES[$configUpload['form_name']]['error'];
            $_FILES['upload_field_name']['size']        = $_FILES[$configUpload['form_name']]['size'];
            if (!$this->upload->do_upload('upload_field_name')) {
                $status = false;
                $msg = $this->upload->display_errors('', '');
                $operation['success'] = $status;
                $operation['message'] = $msg;
            } else {
                $img = $this->upload->data();
                $operation['file_name'] = $img['file_name'];

                if ($iscrop) {
                    // //crop persegi
                    $config_crop['image_library'] = 'gd2';
                    $config_crop['source_image'] = './assets/' . $configUpload['directory'] . '/cover/asli/' . $img['file_name'];
                    $config_crop['new_image'] = './assets/' . $configUpload['directory'] . '/cover/persegi/' . $img['file_name'];
                    $config_crop['create_thumb'] = FALSE;
                    $config_crop['maintain_ratio'] = FALSE;
                    if ($img['image_width'] < $img['image_height']) {
                        $config_crop['width']        = $img['image_width'];
                        $config_crop['height']       = $img['image_width'];
                        $config_crop['x_axis']       = 0;
                        $config_crop['y_axis']       = ($config_crop['height'] - $img['image_width']) / 2;
                    } else if ($img['image_width'] > $img['image_height']) {
                        $config_crop['width']        = $img['image_height'];
                        $config_crop['height']       = $img['image_height'];
                        $config_crop['y_axis']       = ($config_crop['width'] - $img['image_height']) / 2;
                        $config_crop['x_axis']       = 0;
                    } else {
                        $config_crop['width']        = $img['image_width'];
                        $config_crop['height']       = $img['image_height'];
                        $config_crop['x_axis']       = 0;
                        $config_crop['y_axis']       = 0;
                    }
                    $this->load->library('image_lib', $config_crop);
                    $this->image_lib->initialize($config_crop);
                    if (!$this->image_lib->crop()) {
                        $operation['success'] = false;
                        $operation['message'] = $this->image_lib->display_errors();
                    }

                    //crop persegi panjang
                    $config_cropPP['image_library'] = 'gd2';
                    $config_cropPP['source_image'] = './assets/' . $configUpload['directory'] . '/cover/asli/' . $img['file_name'];
                    $config_cropPP['new_image'] = './assets/' . $configUpload['directory'] . '/cover/persegi_panjang/' . $img['file_name'];
                    $config_cropPP['create_thumb'] = FALSE;
                    $config_cropPP['width']        = ($img['image_height'] * 3) / 4;
                    $config_cropPP['height']       = $img['image_height'];
                    $config_cropPP['x_axis']       = 0;
                    $config_cropPP['y_axis']       = 0;
                    $this->load->library('image_lib', $config_cropPP);
                    $this->image_lib->initialize($config_cropPP);
                    if (!$this->image_lib->crop()) {
                        $operation['success'] = false;
                        $operation['message'] = $this->image_lib->display_errors();
                    }
                }
            }
        }
        return $operation;
    }

    public function terbilang($nilai)
    {
        if ($nilai < 0) {
            $hasil = "minus " . trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }
        return ucwords($hasil);
    }

    public function penyebut($nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->penyebut($nilai - 10) . " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai / 10) . " puluh" . $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai / 100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai / 1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai / 1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai / 1000000000) . " milyar" . $this->penyebut(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai / 1000000000000) . " trilyun" . $this->penyebut(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    public function sendFcm($ids, $data, $type = 'mobile')
    {
        if (is_array($ids)) {
            $resToken = $ids;
        } elseif (is_string($ids)) {
            $resToken[] = $ids;
        } else {
            return;
        }

        $fcm_api_key = $this->config->item('fcm_api_key');
        $fcm_api_server_key = $this->config->item('fcm_api_server_key');
        define('API_ACCESS_KEY', $fcm_api_key);
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = $data;
        if ($type == 'mobile') {
            $message['sound']        = 'default';
            $message['icon']         = 'ic_stat_onesignal_default';
            $message['android_channel_id']        = 'notif_fcm';
            $message['click_action'] = 'FCM_PLUGIN_ACTIVITY';
        }
        $message['icon']         = base_url() . 'assets/media/logos/timah/logotimah.png';

        $fields = array(
            'registration_ids' => $resToken,
            'data'     => $message,
            'notification' => $message,
            'android'  => [
                'notification' => [
                    'sound' => 'default'
                ]
            ],
            'priority'  => 'high',
        );
        $headers = array(
            'Authorization: key=' . $fcm_api_server_key,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }
}
