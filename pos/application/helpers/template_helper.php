<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('fillchar')) {
    function fillchar($value, $length, $fill_with=' ', $fill_in='right'){
        if($length-strlen($value) == 0) return; //cek if char fill needed is 0 lengt char;
        
        $charfill = "";
        for($i=0; $i<$length-strlen($value); $i++){
            $charfill .= $fill_with;
        }
        switch($fill_in){
            case 'right': return $value.$charfill; break;
            case 'left' : return $charfill.$value; break;
            case 'both' : return substr($charfill, 0, floor(strlen($charfill)/2)).$value.substr($charfill, floor(strlen($charfill)/2), strlen($charfill)); break;
            default     : return $charfill.$value; break;
        }
    }
}

if (!function_exists('template_marker')) {
    function template($str='', $replacement = false, $marker=false){
        if(empty($marker) and gettype($marker)!='array' ) $marker = array('{','}');
        if(empty($replacement) and gettype($marker)!='array' ) $replacement = array();
        foreach ($replacement as $key => $value) {
            $str = str_replace($marker[0].$key.$marker[1], $value, $str);
        }
        return $str;
    }
}