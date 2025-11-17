<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/* 
  Base class digunakan untuk semua modul
*/
class Main_Controller extends CI_Controller
{
  var $css_plugin = array();
  var $js_plugin = array();
  var $data_user = null;

  public function __construct()
  {
    parent::__construct();

    $this->load->library('session');

    $this->css_plugin = array(
      base_url('assets/plugin/bootstrap/css/bootstrap.min.css'),

      //VENDOR CSS
      base_url('assets/vendor/fonts/boxicons.css'),
      base_url('assets/vendor/css/core.css'),
      base_url('assets/vendor/css/theme-default.css'),
      base_url('assets/css/demo.css'),
      base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css'),

      //CUSTOM CSS
      base_url('assets/css/style.css')
    );

    $this->js_plugin = array(
      //VENDOR
      base_url('assets/vendor/js/helpers.js'),
      base_url('assets/js/config.js'),
      base_url('assets/vendor/libs/popper/popper.js'),
      base_url('assets/vendor/js/bootstrap.js'),
      base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js'),
      base_url('assets/vendor/js/menu.js'),
      base_url('assets/js/main.js'),
    );
  }
}
