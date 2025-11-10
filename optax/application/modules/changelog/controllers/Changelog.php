<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Changelog extends Base_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'ChangeLogModel' => 'ChangeLog',
        ));
    }
}

/* End of file Test.php */
/* Location: ./application/modules/test/controllers/Test.php */