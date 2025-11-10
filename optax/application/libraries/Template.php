<?php

class Template{
    
    private $config = array();
    private $config_default = array(
        'template'  => '',
        'marker'    => array('{','}')
    );
    
    public function __construct($config=null){
        if(is_array($config)){
            $config = array_merge($this->config_default, (array)$config);
            $this->config = $config;
        }else{
            $this->config = $this->config_default;
            $this->config['template'] = $config;
        }
    }

    public function set_marker($marker=null){
        if(is_array($marker)){
            $marker = array_values($marker);
            if(isset($marker[0])) $this->config['marker'][0] = $marker[0];
            if(isset($marker[1])) $this->config['marker'][1] = $marker[1];
        }else{
            $marker = (string) $marker;
            $this->config['marker'][0] = $this->config['marker'][1] = $marker;
        }
    }

    public function get_marker(){
        return $this->config['marker'];
    }

    public function set_template($template=null){
        if(! is_array($template) ) $template = (string)$template;
        $this->config['template'] = $template;
    }

    public function get_template(){
        return $this->config['template'];
    }

    private function mark($str=null, $replacement=null){
        foreach ($replacement as $key => $value) {
            $str = str_replace((string)$this->config['marker'][0].$key.$this->config['marker'][1], (string)$value, (string)$str);
        }
        return $str;
    }

    public function apply($replacement=null){
        $str = $this->config['template'];
        if(is_array($str)){
            foreach ($str as $key => $value) {
                $str[$key] = $this->mark($value, $replacement);
            }
        }else{
            $str = $this->mark($str, $replacement);
        }
        return $str;
    }

}