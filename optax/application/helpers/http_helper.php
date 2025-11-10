<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * work for nginx
 */
if (!function_exists('apache_request_headers')) { 
        function apache_request_headers() { 
            foreach($_SERVER as $key=>$value) { 
                if (substr($key,0,5)=="HTTP_") { 
                    $key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5))))); 
                    $out[$key]=$value; 
                }else{ 
                    $out[$key]=$value; 
        } 
            } 
            return $out; 
        } 
} 
if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
           $headers = []; 
       foreach ($_SERVER as $name => $value) 
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
}

/**
 * function isHttpRequestHave
 */
if ( ! function_exists('isHttpRequestHave'))
{
    function isHttpRequestHave($key = null, $caseSensitive = false)
    {
        $CI =& get_instance();
        $headers = getHttpRequestHeader();

        if(!$caseSensitive)
        {
            $key = strtolower($key);
        }
        
        return array_key_exists($key, $headers);
    }
}

/**
 * function getHttpRequestHeader
 */
if ( ! function_exists('getHttpRequestHeader'))
{
	function getHttpRequestHeader()
    {
		$CI =& get_instance();
        $headers = getallheaders();

        // savemode, convert to lowercase
        if(!is_array($headers)) $headers = array();
        $_headers = array();
        foreach ($headers as $k => $v)
        {
            $_headers[strtolower($k)] = $v;   
        }
        $headers = $_headers;

        if(func_num_args() > 0)
        {
            $key = strtolower(func_get_arg(0)); // savemode using lowercase
            return array_key_exists($key, $headers) ? $headers[$key] : null;
        }else
        {
            return $headers;
        }
	}
}

/**
 * function getHttpRequestBody
 */
if ( ! function_exists('getHttpRequestBody'))
{
    function getHttpRequestBody($return_array = false, $setNullOnEmptyString = true)
    {
        $raw  = '';
        $httpContent = fopen('php://input', 'r');
        while ($kb = fread($httpContent, 5120)) { //default 2048
            $raw .= $kb;
        }

        $params = json_decode($raw);
        if ( ! (is_array($params) or is_object($params)) ) {
            $params = json_decode("[".stripslashes($raw)."]");
        }
        fclose($httpContent);
        
        if($setNullOnEmptyString === true){
            foreach((array)$params as $key => $value ){
                if(is_string($value)){
                    $value = trim($value);
                    if($value == ''){
                        $params->$key = null;
                    }
                }
            }
        }
        if( $return_array === true ){
            return (array) $params;
        }
        return $params;
    }
}

/**
 * function setHttpResponseStatusHeader
 */
if ( ! function_exists('setHttpResponseStatusHeader'))
{
    function setHttpResponseStatusHeader($code = 200, $text = '') {
        // copied helper function setHttpResponseStatusHeader() code from system/core/Common.php
        if (is_cli())
        {
            return;
        }

        if (empty($code) OR ! is_numeric($code))
        {
            show_error('Status codes must be numeric', 500);
        }

        if (empty($text))
        {
            is_int($code) OR $code = (int) $code;
            // Add your status codes/text in this array below
            $stati = array(
                100 => 'Continue',
                101 => 'Switching Protocols',

                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',

                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',

                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                429 => 'Too Many Requests',

                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported'
            );

            if (isset($stati[$code]))
            {
                $text = $stati[$code];
            }
            else
            {
                show_error('No status text available. Please check your status code number or supply your own message text.', 500);
            }
        }

        if (strpos(PHP_SAPI, 'cgi') === 0)
        {
            header('Status: '.$code.' '.$text, TRUE);
        }
        else
        {
            $server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
            header($server_protocol.' '.$code.' '.$text, TRUE, $code);
        }
    }

}