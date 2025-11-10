<?php

class ExcelConnection{

	protected $connection;
	protected $query;
	public $query_count = 0;
	
	function __construct($_dbhost = null, $_dbuser = null, $_dbpass = null, $_dbname = null, $_charset = null)
	{
		# SET CONNECTION DB WITH CODEIGNITER 4
			$db = \Config\Database::connect();
	       	$dbhost  = ((!is_null($_dbhost))  ? $_dbhost  : $db->hostname);
	       	$dbuser  = ((!is_null($_dbuser))  ? $_dbuser  : $db->username);
	       	$dbpass  = ((!is_null($_dbpass))  ? $_dbpass  : $db->password);
	       	$dbname  = ((!is_null($_dbname))  ? $_dbname  : $db->database);
	       	$charset = ((!is_null($_charset)) ? $_charset : $db->char_set);
       	# END SET CONFIG DB

	       	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
			if (!$link) {
			    echo "Error: Unable to connect to MySQL." . PHP_EOL;
			    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			    exit;
			}
			$this->connection = $link;
			$this->connection->set_charset($charset);
			
			# $this->connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
			# if ($this->connection->connect_error) {
			# 	die('Failed to connect to MySQL - ' . $this->connection->connect_error);
			# }
			# $this->connection->set_charset($charset);
	}

	public function query($query) {
		if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
				$types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
					if (is_array($args[$k])) {
						foreach ($args[$k] as $j => &$a) {
							$types .= $this->_gettype($args[$k][$j]);
							$args_ref[] = &$a;
						}
					} else {
	                	$types .= $this->_gettype($args[$k]);
	                    $args_ref[] = &$arg;
					}
                }
				array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
           	if ($this->query->error) {
				die('Unable to process MySQL query (check your params) - ' . $this->query->error);
           	}
			$this->query_count++;
        } else {
            die('Unable to prepare statement (check your syntax) - ' . $this->connection->error);
        }
		return $this;
    }

    public function resultArray() {
	    $params = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            $result[] = $r;
        }
        $this->query->close();
		return $result;
	}

	public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function close() {
		return $this->connection->close();
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

	private function _gettype($var) {
	    if(is_string($var)) return 'string';
	    if(is_float($var)) return 'decimal';
	    if(is_int($var)) return 'integer';
	    return 'undefined';
	}
	
}