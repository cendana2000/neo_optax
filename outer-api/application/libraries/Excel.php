<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style;
use PhpOffice\PhpSpreadsheet\Cell;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

// use PhpOffice\PhpSpreadsheet\Chart;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

require_once('ExcelConnection.php');
// use App\Libraries\dist\ExcelConnection;
class Excel
{
	/**
	 *
	 * @var new Spreadsheet
	 */
	public $spreadsheet;

	/**
	 *
	 * @var setActiveSheetIndex
	*/
	public $objset;

	/**
	 *
	 * @var getActiveSheet
	*/
	public $objget;
  	
  	/**
  	 *
  	 * @var set data upload @return array
  	 */
	private $data_upload    = [];

	/**
	 *
	 * @var set data error
	 */
	private $var_error 		= [];

	/**
	 *
	 * @var boolean
	 */
	private $include_chart 	= false;

	/**
	 *
	 * @var string format
	 */
	private $sheet_title 	= '';

	/**
	 *
	 * @var string format, setting db host
	 */
	private $dbhost = null;

	/**
	 *
	 * @var string format, setting username database
	 */
	private $dbuser = null;

	/**
	 *
	 * @var string format, setting password database
	 */
	private $dbpass = null;

	/**
	 *
	 * @var string format, setting name database
	 */
	private $dbname = null;

	/**
	 * setting connection (Class ExcelConnection)
	 *
	 * @var class
	 */
	private $db;

	/**
	 *
	 * @var boolean
	 */
	private $drawImage;

	/**
	 * Query basic SELECT data
	 *
	 * @var string
	 */
	private $select;

	/**
	 * name table
	 *
	 * @var string
	 */
	private $table;

	/**
	 * Query basic WHERE data
	 *
	 * @var string or array
	 */
	private $where;

	/**
	 * Query basic ORDER BY data
	 *
	 * @var string
	 */
	private $order_by;

	/**
	 * Query basic GROUP BY data
	 *
	 * @var string
	 */
	private $group_by;

	/**
	 * select() or query()
	 *
	 * @var boolean
	 */
	private $is_select  = false;
	private $create_query  = false;
	private $with_num  = false;

	/**
	 *
	 * @var string
	 */
	private $charset 	= '';

	private $paperSize 	= null;
	private $margin 	= null;

	/**
	 *
	 * @var string
	 */
	private $font 		= null;

	private $is_template = false;
	private $is_multiple = false;

	/**
	 *
	 * setting path file download
	 * @var string
	 */
	protected $set_path = '';
	protected $is_set_rowcol = false;
	protected $set_index = [];

	/**
	 *
	 * @var integer
	 */
	private $count_offset = 0;
	
	/**
	 *
	 * @var boolean
	 */
	private $is_offset = false;

	/**
	 *
	 * @var array
	 */
	private $config_upload = [];

	/**
	 * @return array
	 *
	 * @var array set data variable, call function $obj->var 
	 */
	private $var = [
		'data_head' 		=> [],
		'data_body' 		=> [],
		'top'				=> null,
		'right'				=> null,
		'bottom'			=> null,
		'left' 				=> null,
		'start_body'		=> null,
		'last_row'			=> null,
		'last_col'			=> null,
		'xls_name'			=> null,
		'set_auto'			=> true,
		'data_create'       => [],
		'io'				=> null,
		'styles' 			=> [],
	];

	private $set_array      = [
		'function_name' 	=> null,
		'data' 				=> [],
	];

	/**
     * 
     * @var array
     */
    private static $ext_type = [
        'Ods' => [
            'extension' => '.ods',
            'contentType' => 'application/vnd.oasis.opendocument.spreadsheet'
        ],
        'Xls' => [
            'extension' => '.xls',
            'contentType' => 'application/vnd.ms-excel'
        ],
        'Xlsx' => [
            'extension' => '.xlsx',
            'contentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        'Html' => [
            'extension' => '.html',
            'contentType' => 'text/html'
        ],
        'Csv' => [
            'extension' => '.csv',
            'contentType' => 'text/csv'
        ],
    ];

	/**
	 * batas top,right,bottom,left & charset
	 * @param array
	 * @param string
	*/
	function __construct($rowcol = [], $charset = '')
	{
		$this->charset = (($charset == '') ? 'UTF-8' : $charset);
		
       	$this->spreadsheet = new Spreadsheet();
       	$this->objset = ($this->objset==null || $this->objset=='') ?  $this->spreadsheet->setActiveSheetIndex(0) : $this->spreadsheet->setActiveSheetIndex($this->objset);
       	$this->objget = ($this->objget==null || $this->objget=='') ?  $this->spreadsheet->getActiveSheet(0) : $this->spreadsheet->getActiveSheet($this->objget);

       	if (!empty($rowcol))
       	{
       		# rowcol ini berfungsi untuk memberi batas top, right, bottom, left
       		$this->var['top'] 		 = (isset($rowcol[0]) && $rowcol[0] !='') ? $rowcol[0] : null;
			$this->var['start_body'] = ($this->var['top'] !=null) ? ($this->var['top']+1)  : null;
       		$this->var['right']		 = (isset($rowcol[1]) && $rowcol[1] !='') ? $rowcol[1] : null;
			$this->var['bottom']  	 = (isset($rowcol[2]) && $rowcol[2] !='') ? $rowcol[2] : null;
			$this->var['left']  	 = (isset($rowcol[3]) && $rowcol[3] !='') ? $rowcol[3] : null;
			$this->is_set_rowcol = true;
       	}
       	else
       	{
       		$this->var['top'] 		 = null;
			$this->var['start_body'] = null;
       		$this->var['right'] 	 = null;
       		$this->var['bottom'] 	 = null;
			$this->var['left']  	 = null;
       	}
	}

	function setSpreadsheet($value)
	{
		$this->spreadsheet = $value;
		return $this;
	}

	function getSpreadsheet()
	{
		return $this->spreadsheet;
	}

	function setVar($value)
	{
		
	}		

	function getVar()
	{
		return $this->var;
	}

	function setActiveSheetIndex($value = null)
	{
		$this->objset = ($value == null || $value == '') ? $this->spreadsheet->setActiveSheetIndex(0) : $this->spreadsheet->setActiveSheetIndex($value);
		return $this;
	}

	function getActiveSheetIndex(){
		return $this->objset;
	}

	function setActiveSheet($value = null)
	{
		$this->objget = ($value == null || $value == '') ? $this->spreadsheet->getActiveSheet(0) : $this->spreadsheet->getActiveSheet($value);
		return $this;
	}

	function IOFactoryLoad($value)
	{
		return IOFactory::load($value);
	}

	function IOFactoryCreateWriter()
	{

	}

	function setIstemplate($value='')
	{
		$this->is_template = (($value !='' && $value === true) ? $value : false);
		return $this;
	}

	function getIstemplate()
	{
		return $this->is_template;
	}

	function getActiveSheet()
	{
		return $this->objset;
	}

	function loadTemplate($template = '')
	{
		$this->is_template  = true;
		$this->spreadsheet 	= IOFactory::load($template);
		$this->objset 		= $this->spreadsheet->setActiveSheetIndex(0);
		$this->objget 		= $this->spreadsheet->getActiveSheet(0);
		return $this;
	}

	/**
	 * split sheets, save to some excel file
	 * @param array
	*/
	function splitSheet($config = null, $callback = null)
	{		
		$firstKey 	= (isset($config['input_name'])) ? $config['input_name'] : key($_FILES);
		$return 	= false;
		if ($_FILES[$firstKey]['tmp_name'] != '')
		{
			$name = $_FILES[$firstKey]['name'];
			$extend = strrchr ($name, '.');
			if($_FILES[$firstKey]['type'] == "application/vnd.ms-excel" 
				|| $_FILES[$firstKey]['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
			{
	            $obj_spreadsheet = IOFactory::load($_FILES[$firstKey]['tmp_name']);
				$get_sheet = $obj_spreadsheet->getSheetNames();
				foreach ($get_sheet as $key => $sheet_name) 
				{
					$sheet = $obj_spreadsheet->getSheet(0);
					$spreadsheet = $this->spreadsheet;
				    $spreadsheet->removeSheetByIndex(0);
					$spreadsheet->addExternalSheet($sheet);
				    $writer = IOFactory::createWriter($spreadsheet,'Xlsx');
				    $writer->save($sheet_name.'.xlsx');

				    $return = true;
				}
				if (is_callable($callback))
				{
					call_user_func($callback,array(
						'self'  => $get_sheet,
						'all'	=> $this->var
					));
				}
			}
		}
		else
		{
			$return = false;
			die("file not found");
		}
		return [
			'success' => $return
		];
	}

	/**
	 * 
	 * @param array
	*/
	function template($config = null, $callback = null)
	{
		$this->is_template = true;
		$firstKey 	= (isset($config['input_name'])) ? $config['input_name'] : key($_FILES);
		$return 	= false;
		if ($_FILES[$firstKey]['tmp_name'] != '')
		{
			if($_FILES[$firstKey]['type'] == "application/vnd.ms-excel" || $_FILES[$firstKey]['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
			{
	            $this->spreadsheet 	= IOFactory::load($_FILES[$firstKey]['tmp_name']);
	            $spreadsheet    	= $this->getSpreadsheet();
        		$get_sheet      	= $spreadsheet->getSheetNames();

        		if (is_callable($callback))
				{
					call_user_func($callback,array(
						'spreadsheet'	=> $spreadsheet,
	            		'sheet_name' 	=> $get_sheet,
					));
				}

	            return [
	            	'spreadsheet'	=> $spreadsheet,
	            	'sheet_name' 	=> $get_sheet,
	            ];
			}
			else
			{
				die("incorrect file extension excel");
			}
		}
		else
		{
			die("file not found");
		}
	}

	/**
	 * initialization new sheet
	 * @param boolean
	*/
	function createSheet($value = '')
	{
		$this->is_multiple = true;
		$this->objset = $this->spreadsheet->createSheet($value);
		return $this;
	}

	/**
	 * add creator
	 * @param string
	*/
	function setCreator($value = ''){
		$this->spreadsheet->getProperties()->setCreator($value);
		return $this;
	}

	/**
	 * copy worksheet
	 * @param sting
	*/
	function copyWorksheets($file_1, $file_2 = '')
	{
		$clonedWorksheet = clone $this->spreadsheet->getSheetByName($file_1);
		$clonedWorksheet->setTitle($file_2);
		$this->spreadsheet->addSheet($clonedWorksheet);
	}

	/**
	 * delete worksheet
	 * @param array atau string
	*/
	function removeWorksheets($data = null)
	{
		if ($data != null) 
		{
			if (is_array($data))
			{
				foreach ($variable as $key => $value)
				{
					$this->spreadsheet->removeSheetByIndex(
					    $this->spreadsheet->getIndex(
					        $this->spreadsheet->getSheetByName($value)
					    )
					);
				}
			}
			else
			{
				$this->spreadsheet->removeSheetByIndex(
				    $this->spreadsheet->getIndex(
				        $this->spreadsheet->getSheetByName($data)
				    )
				);
			}
		}
		else
		{
			return false;
		}
		return $this;
	}

	/**
	 * delete sheet
	 * @param array or string
	*/
	function removeSheet($data = null)
	{
		if ($data != null)
		{
			if (is_array($data))
			{
				foreach ($variable as $key => $value)
				{
					$this->spreadsheet->removeSheetByIndex(
					    $this->spreadsheet->getIndex(
					        $this->spreadsheet->getSheetByName($value)
					    )
					);
				}
			}
			else
			{
				$this->spreadsheet->removeSheetByIndex(
				    $this->spreadsheet->getIndex(
				        $this->spreadsheet->getSheetByName($data)
				    )
				);
			}
		}
		else
		{
			return false;
		}
		return $this;
	}

	/**
	 * set last modified by
	 * @param string
	*/
	function setLastModifiedBy($value = '')
	{
		$this->spreadsheet->getProperties()->setLastModifiedBy($value);
		return $this;
	}

	/**
	 * set title
	 * @param string
	*/
	function setTitle($value = '')
	{
		$this->spreadsheet->getProperties()->setTitle($value);
		return $this;
	}

	/**
	 * set sobject
	 * @param string
	*/
	function setSubject($value = '')
	{
		$this->spreadsheet->getProperties()->setSubject($value);
		return $this;
	}

	/**
	 * set deskripsi
	 * @param string
	*/
	function setDescription($value = '')
	{
		$this->spreadsheet->getProperties()->setDescription($value);
		return $this;
	}

	/**
	 * set keword/kata kunci
	 * @param string
	*/
	function setKeywords($value = '')
	{
		$this->spreadsheet->getProperties()->setKeywords($value);
		return $this;
	}

	/**
	 * set kategori
	 * @param string
	*/
	function setCategory($value = '')
	{
		$this->spreadsheet->getProperties()->setCategory($value);
		return $this;
	}

	// Page Setup: Scaling options
	function setFitToPage()
	{
		return $this;
	}
	
	function setScale()
	{
		return $this;
	}

	function setFitToWidth()
	{
		return $this;
	}

	function setFitToHeight()
	{
		return $this;
	}

	function getPageMargins()
	{
		return $this;
	}

	function setTop()
	{
		return $this;
	}

	function setRight()
	{
		return $this;
	}

	function setLeft()
	{
		return $this;
	}

	function setBottom()
	{
		return $this;
	}

	function setHorizontalCentered()
	{
		return $this;
	}

	function setVerticalCentered()
	{
		return $this;
	}

	function setPrintArea($cell = '')
	{
		$this->objget->getPageSetup()->setPrintArea($cell);
		return $this;
	}

	/**
	 * setting paper size landscpae or potrait
	 * @param string
	*/
	function setPaperSize($value = '')
	{
		$this->paperSize = $value;
		$explode = explode('-', $value);
		$this->objset->getPageSetup()
    		->setPaperSize(self::getPaperSize($explode[0]));
    		if (count($explode)>1)
    		{
    			if ($explode[1] == 'L')
    			{
					$this->objset->getPageSetup()->setOrientation(Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
				}
				else
				{
					$this->objset->getPageSetup()->setOrientation(Worksheet\PageSetup::ORIENTATION_PORTRAIT);
				}
    		}
		return $this;
	}

	/**
	 * setting landscpae or potrait
	 * @param string
	*/
	function setOrientation($value = '')
	{
		if ($value == 'L')
		{
			$this->objset->getPageSetup()->setOrientation(Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		}
		else
		{
			$this->objset->getPageSetup()->setOrientation(Worksheet\PageSetup::ORIENTATION_PORTRAIT);
		}
		return $this;
	}

	/**
	 * setting margin
	 * @param string
	*/
	function setMargin($value = '')
	{
		$this->margin = $value;
		return $this;
	}

	/**
	 * setting font
	 * @param string
	*/
	function setFont($value = '')
	{
		$this->font = $value;
		return $this;
	}

	/**
	 * setting sheet title
	 * @param string
	*/
	function setSheetTitle($value = '')
	{
		$this->objset->setTitle($value);
		$this->sheet_title = ($value !='') ? $value : 'Worksheet';
		return $this;
	}

	/**
	 * hide gridline
	 * @param boolean
	*/
	function setShowGridlines($value = '')
	{
		if ($value) 
		{
			$this->objget->setShowGridlines(TRUE);
		}else
		{
			$this->objget->setShowGridlines(FALSE);
		}
		return $this;
	}

	/**
	 * setting zoom scale
	 * @param int
	*/
	function setZoomScale($value = '')
	{
		if ($value !='')
		{
			$this->objget->getSheetView()->setZoomScale($value);
		}
		return $this;
	}

	/**
	 * create header
	 * @param array
	*/
	function createHeader($data = '', $callback = '')
	{
		$operation = null;
		if (!empty($data))
		{
			$operation = $this->create($data,null,'data_head');
		}
		$this->set_array = [
			'function_name' => 'createHeader',
			'data' 			=> $data
		];
		if (is_callable($callback)) {
			call_user_func($callback,[
				'self'  => $operation,
				'all'	=> $this->var
			]);
		}
		return $this;
	}

	/**
	 * set data value
	 * @param array
	*/
	function create($data = '', $callback = '', $jenis = '')
	{
		if (!empty($data))
		{
			$start_col  = self::setInput($this->var['left']);
			$start_head = ($this->is_offset) ? $this->count_offset : self::setInput($this->var['top']);
			if (self::isArrayMulti($data))
			{
				if (self::countDim($data)>1)
				{
					foreach ($data as $key => $value)
					{
						$format_text = (!empty($value['text-format'])) ? $value['text-format'] : null; // format text
						$is_merge = explode(':', $value['cell']);
						if (count($is_merge)>1)
						{
							$this->setMergeCells($value['cell'],$is_merge[0],$value['value'],$format_text);
						}
						else
						{
							$this->setCellValue($value['cell'],$value['value'],$format_text);
						}
						$cell = $value['cell'];
						unset($value['cell']);
						$this->addStyles($cell,$value);
					}
				}
				else
				{
					$is_merge = explode(':', $data['cell']);
					$format_text = (!empty($value['text-format'])) ? $value['text-format'] : null; // format text
					if (count($is_merge)>1)
					{
						$this->setMergeCells($data['cell'],$is_merge[0],$data['value'],$format_text);  
					}
					else
					{
						$this->setCellValue($data['cell'],$data['value'],$format_text);
					}
					$cell = $data['cell'];
					unset($data['cell']);
					$this->addStyles($cell,$data);
				}
			}
			else
			{
				foreach ($data as $key => $value)
				{
					$explode 		= explode('{', $value);
					$prop 			= $explode;
					$cssproperties 	= array();
					if (count($prop)>1)
					{
						unset($prop[0]);
						$prop = str_replace('}', '', $prop);
						foreach ($prop as $key2 => $value2)
						{
							$explode_prop = explode(';', $value2);
							foreach ($explode_prop as $key3 => $value3) {
								$explode_nn = explode(':', $value3);
								if (isset($explode_nn[0]) && !empty($explode_nn[0]  ))
								{
									$cssproperties[preg_replace('/\s+/', '', $explode_nn[0])] = rtrim(ltrim(str_replace(array("'",'"'), "", (isset($explode_nn[1])) ? $explode_nn[1] : '')));
								}
							}
						}
					}
					$cell = $this->getNameFromNumber($key+$start_col);
	        		$this->objset->setCellValue($cell.$start_head, $explode[0]);
					$cssproperties = array_diff($cssproperties, array(''));
					$cl = $cell.$start_head;
					$this->addStyles($cl,$cssproperties);
				}
			}

			if ($jenis != '')
			{
				return $this->var[$jenis] = $data;
			}
			else
			{
				$this->var['data_create'] = $data;
				if (is_callable($callback))
				{
					call_user_func($callback,array(
						'self'  => $data,
						'all'	=> $this->var
					));
				}
				return $this;
			}
		}
		else
		{
			if (is_callable($callback))
			{
				call_user_func($callback,array(
					'self'  => $data,
					'all'	=> $this->var
				));
			}
			return $this;
		}
	}

	/**
	 * Database configuration settings
	 * @param array or string
	*/
	function db($dinamis_db = null)
	{
		if (is_array($dinamis_db) && !is_null($dinamis_db))
		{
			$this->dbhost = (!empty($dinamis_db['hostname'])) ? $dinamis_db['hostname'] : null;
			$this->dbuser = (!empty($dinamis_db['username'])) ? $dinamis_db['username'] : null;
			$this->dbpass = (!empty($dinamis_db['password'])) ? $dinamis_db['password'] : null;
			$this->dbname = (!empty($dinamis_db['database'])) ? $dinamis_db['database'] : null;
		}
		else
		{
			$this->dbname = (!empty($dinamis_db)) ? $this->dinamis_db : $this->dbname;
		}
		$this->db = new ExcelConnection($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
		return $this;
	}

	/**
	 * syntax query mysql
	 * @param string
	*/
	function query($query, $callback = null)
	{
		if (!$this->db)
		{
			$this->db = new ExcelConnection(null, null, null, null);
		}
		$header 	= $this->var['data_head'];
		$start_head = (($this->is_offset) ? ($this->count_offset) : self::setInput($this->var['top']));
		$start_body = (($this->is_offset) ? ($this->count_offset) : self::setInput($this->var['start_body'])); //self::setInput($this->var['start_body']);
		$start_col  = self::setInput($this->var['left']);

		$vmode 		= array();
		$operation 	= $this->db->query($query)->resultArray();
		foreach ($operation[0] as $key => $value)
		{
			array_push($vmode, $key);
		}

		// set header
			if (empty($this->var['data_head']))
			{
				$start_h = ($start_body<=$start_head) ? ($start_head+1) : $start_body;
				$start_h = ($start_h-1);

				$start_col = $start_col; 
				foreach ($vmode as $key => $value)
				{
					$cell = $this->siConvert($start_col).$start_h;
					$this->objset->setCellValue($cell, $value);
					$start_col +=1;

				}
			}
		// set body
			$start = ($start_body<=$start_head) ? ($start_head+1) : $start_body;
			if (!empty($operation))
			{
				$set_data = array();
				foreach ($operation as $key => $value)
				{
					$sub = array();
					foreach ($vmode as $key2 => $value2)
					{
						$data_fields = $value[$value2];
						$cell = $this->getNameFromNumber($key2+self::setInput($this->var['left'])).($start+$key);
						$this->objset->setCellValue($cell, $data_fields);
		        		$sub[$value2] = $value[$value2];
					}
					array_push($set_data, $sub);
				}
				# 
				$this->count_offset += count($set_data);
	        	$this->set_array = [
					'function_name' => 'query',
					'data' => $set_data,
	        	];
			}
			else
			{

			}

			if (is_callable($callback))
			{
				call_user_func($callback,array(
					'self' 	=> array(
						'data'		 => $operation,
						'vmode'      => $vmode,
					),
					'all'	=> $this->var
				));
			}
		return $this;
	}

	/**
	 * set table name or view
	 * @param string
	*/
	function table($value = '')
	{
		$this->is_select = true;
		$this->table 	 = $value;
		return $this;
	}

	/**
	 * fields table
	 * @param string
	*/
	function select($value = '')
	{
		$this->select = $value;
		return $this;
	}

	/**
	 * added filter mysql
	 * @param array or string
	*/
	function where($field_1 = '', $field_2 = '', $field_3 = '')
	{
		if ($field_1)
		{
			if (is_array($field_1))
			{
	            $this->where = ' WHERE '.$this->_where($field_1);
	        }
	        else
	        {
	        	if ($field_1 !='' && $field_2 !='' && $field_3 !='') {
	        		$this->where = ' WHERE '.$field_1.' '.$field_2.' '.'"'.$field_3.'"';	
	        	}
	        	else
	        	{
	        		if ($field_2 !='') { 
	        			$this->where = ' WHERE '.$field_1.' = '.'"'.$field_2.'"';
	        		}
	        		else
	        		{
	        			$this->where = ' WHERE '.$field_1;
	        		}
	        	}
	        }
		}
        return $this;
	}

	/**
	 * order query
	 * @param array or string
	*/
	function orderBy($field_1 = '', $field_2 = '')
	{
		if ($field_1)
		{
			if (is_array($field_1))
			{
	            $queryOrderBy = '';
	            $i = 0 ;
	            foreach ($field_1 as $key => $field_1)
	            {
	                if ($i==0)
	                {
	                    $queryOrderBy .= $key.' '.$field_1;
	                }
	                else
	                {
	                    $queryOrderBy .= ', '.$key.' '.$field_1;
	                }
	                $i++;
	            }
	            $this->order_by = ' ORDER BY '.$queryOrderBy;
	        }
	        else
	        {
	        	if ($field_1 !='' && $field_2 !='')
	        	{
					$this->order_by = ' ORDER BY '.$field_1.' '.$field_2;        		
	        	}
	        	else
	        	{
	            	$this->order_by = ' ORDER BY '.$field_1;
	        	}
	        }
		}

        return $this;
	}

	/**
	 * group by
	 * @param array or string
	*/
	function groupBy($value = '')
	{
		$order = null;
        if (is_array($value))
        {
            $order = implode(',', $value);
        }
        else
        {
            $order = $value;
        }
        $this->group_by = ' GROUP BY '.$order;
        return $this;
	}

	/**
	 * generate to excel (.xlsx)
	 * @param string
	*/
	function exportXlsx($file_name = ''){
		$this->export($file_name);
	}

	/**
	 * @param string or array
	 * @param string (file name)
	 * @return file xls or xlsx default xlsx
	*/
	function htmlToExcel($html = '', $file_name = 'excel')
	{
		$file_info 		= pathinfo($file_name);
		$file_extension = (isset($file_info['extension']) && $file_info['extension'] != '') ? $file_info['extension'] : 'xlsx';

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		if (is_array($html))
		{
			foreach ($html as $key => $value)
			{
				if ($key == 0)
				{
					$spreadsheet = $reader->loadFromString($value);
				}
				else
				{
					$reader->setSheetIndex($key);
					$spreadhseet = $reader->loadFromString($value, $spreadsheet);
				}
			}
		}
		else
		{
			$spreadsheet = $reader->loadFromString($html);
		}

		$return = [];
		if ($file_extension == 'xlsx' || $file_extension == 'xls')
		{
			$dirname 		= (isset($file_info['dirname']) && $file_info['dirname'] == '.') ? '' : $file_info['dirname'].'/';
			$writer 		= \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, ucfirst($file_extension));
			$path 			= ($this->set_path && $this->set_path != '') ? $this->set_path : $dirname;
			$file_name 		= $file_info['filename'].'.'.$file_extension;
			$file_path 		= $path.$file_name; 
			$writer->save($file_path);

			$return = [
				'success' 	=> true,
				'message'	=> 'File created successfully.',
				'url'		=> $this->url().$file_path,
				'file_name' => $file_name
			];
		}
		else
		{
			$return = [
				'success' 	=> false,
				'message'	=> 'Failed to create file.'
			];
		}

		return $return;
	}

	/**
	 * @param string
	 * support xls, xlsx, ods, csv & html
	 * @return output
	 * default return xlsx
	*/
	function base64Encode($file_name = 'excel')
	{

		$file_info 		= pathinfo($file_name);
		$file_extension = (isset($file_info['extension']) && $file_info['extension'] != '') ? $file_info['extension'] : 'xlsx';

		$objWriter = IOFactory::createWriter($this->spreadsheet, ucfirst($file_extension));

		# hapus default worksheet
		if ($this->is_multiple) {
			$this->removeSheet('Worksheet');
		}

		# jika include chart
		if ($this->include_chart)
		{
		 	$objWriter->setIncludeCharts(TRUE);
		}

		# jika menggunakan query builder
		if ($this->is_select === true && $this->create_query === false)
		{
			$select = ($this->select) ? $this->select : '*';
			if ($this->with_num)
			{
				$query  = 'SELECT @no:=@no+1 nomor,'.$select.' FROM '.$this->table.',(SELECT @no:= 0) AS no'.$this->where.$this->order_by.$this->group_by;
			}
			else
			{
				$query  = 'SELECT '.$select.' FROM '.$this->table.$this->where.$this->order_by.$this->group_by;
			}
			$this->query($query);
		}

		$is_list_ext = isset(self::$ext_type[ucfirst($file_extension)]) ? true : false;
		$content_type = ($is_list_ext) ? self::$ext_type[ucfirst($file_extension)]['contentType'] : 'application/octet-stream';

		header("Content-Type: {$content_type}");

		$file_name = rawurlencode($file_info['filename']);
		$file_name = $file_name.'.'.$file_extension;
        header("Content-Disposition: attachment; filename={$file_name}; filename*=UTF-8''{$file_name};");
        header("Cache-Control: max-age=0");

        $objWriter->save('php://output');

        $file_data = ob_get_contents();
        ob_end_clean();
        return [
        	'success' 	=> true,
            'file_name' => $file_name,
            'file_data' => "data:{$content_type};base64,".base64_encode($file_data)
        ];
	}

	/**
	 * @param string
	 * support xls, xlsx, ods, csv & html
	 * @return output
	 * default return xlsx
	*/
	function output($file_name = 'excel')
	{
		$file_info 		= pathinfo($file_name);
		$file_extension = (isset($file_info['extension']) && $file_info['extension'] != '') ? $file_info['extension'] : 'xlsx';

		$objWriter = IOFactory::createWriter($this->spreadsheet, ucfirst($file_extension));

		# hapus default worksheet
		if ($this->is_multiple) {
			$this->removeSheet('Worksheet');
		}

		# jika include chart
		if ($this->include_chart)
		{
		 	$objWriter->setIncludeCharts(TRUE);
		}

		# jika menggunakan query builder
		if ($this->is_select === true && $this->create_query === false)
		{
			$select = ($this->select) ? $this->select : '*';
			if ($this->with_num)
			{
				$query  = 'SELECT @no:=@no+1 nomor,'.$select.' FROM '.$this->table.',(SELECT @no:= 0) AS no'.$this->where.$this->order_by.$this->group_by;
			}
			else
			{
				$query  = 'SELECT '.$select.' FROM '.$this->table.$this->where.$this->order_by.$this->group_by;
			}
			$this->query($query);
		}

		$is_list_ext = isset(self::$ext_type[ucfirst($file_extension)]) ? true : false;
		$content_type = ($is_list_ext) ? self::$ext_type[ucfirst($file_extension)]['contentType'] : 'application/octet-stream';

		header("Content-Type: {$content_type}");

		$file_name = rawurlencode($file_info['filename']);
		$file_name = $file_name.'.'.$file_extension;
        header("Content-Disposition: attachment; filename={$file_name}; filename*=UTF-8''{$file_name};");
        header("Cache-Control: max-age=0");

        $objWriter->save('php://output');
        exit;
	}

	/**
	 *
	 * @param string
	 * support xls, xlsx, ods, csv & html
	 * @return file
	 * default return xlsx
	*/
	function export($file_name = 'excel')
	{
		$file_info 		= pathinfo($file_name);
		$file_extension = (isset($file_info['extension']) && $file_info['extension'] != '') ? $file_info['extension'] : 'xlsx';

		# delete default worksheet
		if ($this->is_multiple) {
			$this->removeSheet('Worksheet');
		}

		$objWriter 		= IOFactory::createWriter($this->spreadsheet, ucfirst($file_extension));
		# include chart
		if ($this->include_chart)
		{
		 	$objWriter->setIncludeCharts(TRUE);
		}

		# query builder
		if ($this->is_select === true && $this->create_query === false)
		{
			$select = ($this->select) ? $this->select : '*';
			if ($this->with_num)
			{
				$query  = 'SELECT @no:=@no+1 nomor,'.$select.' FROM '.$this->table.',(SELECT @no:= 0) AS no'.$this->where.$this->order_by.$this->group_by;
			}
			else
			{
				$query  = 'SELECT '.$select.' FROM '.$this->table.$this->where.$this->order_by.$this->group_by;
			}
			$this->query($query);
		}

		$is_list_ext = isset(self::$ext_type[ucfirst($file_extension)]) ? true : false;
		$return = [];
		if ($is_list_ext)
		{
			$dirname 		= (isset($file_info['dirname']) && $file_info['dirname'] == '.') ? '' : $file_info['dirname'].'/';
			$path 			= ($this->set_path && $this->set_path != '') ? $this->set_path : $dirname;
			$file_name 		= $file_info['filename'].'.'.$file_extension;
			$file_path 		= $path.$file_name; 
			$objWriter->save($file_path);

			$return = [
				'success' 	=> true,
				'message'	=> 'File created successfully.',
				'url'		=> $this->url().$file_path,
				'file_name' => $file_name
			];
		}
		else
		{
			$return = [
				'success' 	=> false,
				'message'	=> 'Failed to create file.'
			];
		}
 		return $return;
		// unlink($this->url().$this->set_path.$file_name.$file_extension);		
	}

	function setOffset($value = '', $reset = false)
	{
		if ($reset)
		{
			$this->count_offset = ($value !='') ? 1 : $value;
		}
		else
		{
			$value = ($value == '') ? 1 : $value+1;
			$this->count_offset = ($this->count_offset + (int) $value);
		}
		$this->is_offset = true;
		return $this;
	}

	function getOffset()
	{
		return $this->count_offset;
	}

	function collect($data = [], $callback = '')
	{
		# is offset
		$start_row = ($this->is_offset) ? $this->count_offset : self::setInput($this->var['top']);
		if (is_array($data))
		{
			if (self::isArrayMulti($data))
			{
				foreach ($data as $key_1 => $value_1)
				{
					$col = 0;
					foreach ($value_1 as $key_2 => $value_2)
					{
						$cell = $this->getNameFromNumber($col+self::setInput($this->var['left'])).($key_1+$start_row);
						$this->objset->setCellValue($cell, $value_2);
						$col +=1;
					}
				}
				$this->count_offset += (count($data)-1);
			}
			else
			{
				$col = 0;
				foreach ($data as $key => $value)
				{
					$cell = $this->getNameFromNumber($col+self::setInput($this->var['left'])).($start_row);
					$this->objset->setCellValue($cell, $value);
					$col +=1;
				}
				$this->count_offset += 1;
			}
		}
		else
		{
			$this->var['error_collect'] = 'fields data must be a array';
		}
		if (is_callable($callback))
		{
			call_user_func($callback, [
				'self'  => $data,
				'all'	=> $this->var
			]);
		}
		return $this;
	}

	/**
	 * digunakan untuk generate excel dari array multidimensional
	 * bisa juga menambahkan sintak query
	 * @param array atau string
	*/
	function download($value = null, $file_name = '', $callback = '')
	{
		# jika parameter berupa array
		if (is_array($value))
		{
			if (self::isArrayMulti($value))
			{

				foreach ($value as $key_1 => $value_1)
				{
					$col = 0;
					foreach ($value_1 as $key_2 => $value_2)
					{
						$cell = $this->getNameFromNumber($col+self::setInput($this->var['left'])).($key_1+self::setInput($this->var['top']));
						$this->objset->setCellValue($cell, $value_2);
						$col +=1;
					}
				}
			}
			else
			{
				
			}
		}
		else
		{
			$query = '';
			# jika string terdapat kalimat from maka dianggap query penuh
			if (strpos(strtolower( preg_replace('/\s+/', '', $value) ), 'from') == true)
			{
				$query = $value;
			}
			else
			{
				$query = "SELECT * FROM ".$value;
			}
			# cek jika db belum terkoneksi
			if (! $this->db)
			{
				$this->db = new ExcelConnection(null, null, null, null);
			}
			# jalankan export excel dengan perintah query mysql
			$operation = $this->query($query);
			# jika query gagal tampilkan pesan
			if (!$operation)
			{
				die($operation);
			}
			$objWriter = new Xlsx($this->spreadsheet);
		}

		# jika file download adalah template
		if (!$this->is_template)
		{
			$objWriter = new Xlsx($this->spreadsheet);
		}
		else
		{
			$objWriter = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
		}

		# jika include chart
		if ($this->include_chart)
		{
		 	$objWriter->setIncludeCharts(TRUE);
		}

		# callback
		if (is_callable($callback))
		{
			call_user_func($callback, [
				'self'  => $operation,
				'all'	=> $this->var
			]);
		}

		#inisial nama
		$file_name  	= (($file_name !='') ? $file_name : 'download');
		$explode_file 	= explode('.', $file_name);
		$file_name 		= (count($explode_file)>1) ? $explode_file[0] : $file_name;
		# status bug => low (remove)
		# $file_extension = (count($explode_file)>1) ? '.'.$explode_file[1] : '.xlsx';
		# end status bug => low (remove)
		$file_extension = '.xlsx';
		# simpan di server
		if ($file_extension == '.xls') {
			# status bug => low (remove)
			# $objWriter = IOFactory::createWriter($this->spreadsheet, 'Excel5');
			# end status bug => low (remove)
		}
		# simpan ke server
		$objWriter->save($this->set_path.$file_name.$file_extension);
		# mengembalikan status export excel beserta link download berupa json
		return [
			'title' 	=> ($file_name.$file_extension) ? 'Berhasil' : 'Gagal',
			'success' 	=> ($file_name.$file_extension) ? true : false,
			'url' 		=> ($file_name.$file_extension) ? $this->url().$this->set_path.$file_name.$file_extension : null,
			'message' 	=> ($file_name.$file_extension) ? 'Excel berhasil di buat' : 'Excel gagal di buat',
		];
	}

	/**
	 * generate ke excel (.lsx)
	 * @param string
	*/
	function exportXls($filename = '')
	{
		$filename  = (($filename !='') ? $filename : 'Download');
		header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        ob_start();
        $objWriter = IOFactory::createWriter($this->spreadsheet, 'Excel5');
        $objWriter->save('php://output');
        $xlsData = ob_get_contents();
        ob_end_clean();
        return [
            'success' => true,
            'file_name' => $this->var['xls_name'],
            'message' => 'Berhasil',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        ];
	}

	/**
	 * generate ke pdf
	 * fugsi ini masih dalam tahap pengembangan mungkin saja bisa dihapus
	 * @param string
	*/
	function exportPdf($filename = '', $url = '')
	{
		$paper_size = (!is_null($this->paperSize)) ? $this->paperSize : '';
		$objWriter = IOFactory::createWriter($this->spreadsheet, 'Mpdf');
		$objWriter->setSheetIndex(0);
		$objWriter->save($filename);
		// $operation = $objWriter->export([
		// 	'file_name' 	=> ($filename != '') ? $filename : 'download',
		// 	'paper_size' 	=> $paper_size,
		// 	'margin'		=> (!is_null($this->margin)) ? $this->margin : '',
		// 	'font'			=> (!is_null($this->font)) ? $this->font : 'freesans',
		// 	'json'			=> false,
		// ]);
		$name = $filename;
		return [
			'success' 	  => true,
			'downloadURL' => $this->url().$url.$name,
		];
	}

	/**
	 * generate ke pdf
	 * fugsi ini masih dalam tahap pengembangan mungkin saja bisa dihapus
	 * @param string
	*/
	function exportJsonPdf($file_name = ''){
		$paper_size = (!is_null($this->paperSize)) ? $this->paperSize : '';
		$objWriter = IOFactory::createWriter($this->spreadsheet, 'PDF');
		$objWriter->setSheetIndex(0);
		return $objWriter->export([
			'file_name' 	=> ($file_name != '') ? $file_name : 'download',
			'paper_size' 	=> $paper_size,
			'json'			=> true,
			'margin'		=> (!is_null($this->margin)) ? $this->margin : '',
			'font'			=> (!is_null($this->font)) ? $this->font : 'freesans',
		]);
	}

	/**
	 * setting path
	 * @param string
	 * @param boolean
	*/
	function savePath($path = '', $create_folder = false)
	{
		$this->set_path = $path;
		if ($create_folder)
		{
			if (!file_exists($path))
			{
			    mkdir($path, 0775, true);
			}
		}
		return $this;
	}

	/**
	 * read url
	 * @param string
	*/
	function getPath($path = '')
	{
		$this->set_path = $path;
		return $this;
	}

	/**
	 * read url
	 * @param boolean
	*/
	function url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
	{
        if (isset($_SERVER['HTTP_HOST']))
        {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];

            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf( $tmplt, $http, $hostname, $end );
        }
    	else
    	{
    		$base_url = 'http://localhost/';
    	}

        if ($parse)
        {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }

        return $base_url;
    }

    /**
	 * 
	 * [
	 *	'cell' => 'A1',
	 *	'value' => 'Hello'
	 * ]
	 * @param array
	*/
    function setCellValues($data = '', $callback = '')
    {
    	$this->setDataCells($data);
    	return $this;
    }

    /**
	 *  
	 * [
	 *	'cell' => 'A1',
	 *	'value' => 'Hello'
	 * ]
	 * @param array
	*/
	function setDataCells($data = '', $callback = '')
	{
		if (is_array($data)) 
		{
			if (self::countDim($data)>1)
			{
				foreach ($data as $key => $value)
				{
					$cssproperties 	= array();
					$is_merge 		= explode(':', $value['cell']);
					$draw_image  	= (isset($value['path']) && !empty($value['path'])) ? true : false;
					$value['value'] = (($draw_image) ? '' : $value['value']);					
					$f_text = null;
					if (!empty($value['style']))
					{
						if (is_string($value['style']))
						{
							$prop  	 = str_replace(array('{','}'), '', $value['style']);
							$prop_ex = explode(';', $prop);
							foreach ($prop_ex as $key2 => $value2)
							{
								$explode = explode(':', $value2);
								if (isset($explode[0]) && !empty($explode[0]  ))
								{
									$cssproperties[preg_replace('/\s+/', '', $explode[0])] = rtrim(ltrim(str_replace(array("'",'"'), "", (isset($explode[1])) ? $explode[1] : '')));
								}
							}
						}
						else
						{
							$cssproperties = $value['style'];
						}
						$f_text = (isset($cssproperties['text-format'])) ? $cssproperties['text-format'] : '';
					}
					$format_text = (isset($value['text-format']) && !empty($value['text-format'])) ? $value['text-format'] : $f_text;
					if (count($is_merge)>1)
					{
						$this->setMergeCells($value['cell'],$is_merge[0],$value['value'],$format_text);
					}
					else
					{
						$this->setCellValue($value['cell'],$value['value'],$format_text);
					}


					if ($draw_image)
					{
						$this->drawImage = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
						$this->drawImage->setWorksheet($this->objset);
						$this->drawImage->setPath($value['path']);
						$this->drawImage->setCoordinates($value['cell']);
					}

					$cssproperties = array_diff($cssproperties, array(''));
					$this->addStyles($value['cell'],$cssproperties);
					$cssproperties = [];

					$last_cell = substr($value['cell'], -1);
					$this->count_offset = ($this->count_offset > $last_cell) ? $this->count_offset : $last_cell;
				}
			}
			else
			{
				$cssproperties 	= array();
				$is_merge 		= explode(':', $data['cell']);
				$draw_image  	= (isset($data['path']) && !empty($data['path'])) ? true : false;
				$data['value'] 	= (($draw_image) ? '' : $data['value']);

				$f_text 		= null;
				if (!empty($data['style']))
				{
					if (is_string($data['style']))
					{
						$prop = str_replace(array('{','}'), '', $data['style']);
						$prop_ex = explode(';', $prop);
						foreach ($prop_ex as $key => $value) {
							$explode = explode(':', $value);
							if (isset($explode[0]) && !empty($explode[0]  ))
							{
								$cssproperties[preg_replace('/\s+/', '', $explode[0])] = rtrim(ltrim(str_replace(array("'",'"'), "", (isset($explode[1])) ? $explode[1] : '')));
							}
						}
					}
					else
					{
						$cssproperties = $data['style'];
					}
					$f_text 	= (isset($cssproperties['text-format'])) ? $cssproperties['text-format'] : '';
				}

				$format_text = (isset($data['text-format']) && !empty($data['text-format'])) ? $data['text-format'] : $f_text;
				if (count($is_merge)>1)
				{
					$this->setMergeCells($data['cell'],$is_merge[0],$data['value'],$format_text);
				}
				else
				{
					$this->setCellValue($data['cell'],$data['value'],$format_text);
				}
				if ($draw_image)
				{
					$this->drawImage = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
					$this->drawImage->setWorksheet($this->objset);
					$this->drawImage->setPath($data['path']);
					$this->drawImage->setCoordinates($data['cell']);
				}

				$cssproperties = array_diff($cssproperties, array(''));
				$this->addStyles($data['cell'],$cssproperties);	
				
				$last_cell = substr($data['cell'], -1);
				$this->count_offset = ($this->count_offset > $last_cell) ? $this->count_offset : $last_cell;
			}
		}
		else
		{
			$this->var['error_setdatacells'] = 'fields data must be a array';
		}
		return $this;
	}

	/**
	 * merge celc (column atau row)
	 * @param string
	*/
	function setMergeCells($cell = '',$cell_set = '',$value = '', $format_text = '')
	{
		$value = html_entity_decode($value,ENT_QUOTES,$this->charset);
		if (!empty($format_text))
		{
			$this->objset->mergeCells($cell)->setCellValueExplicit($cell_set, $value, self::setCellFormatType($format_text));
		}
		else
		{
			$this->objset->mergeCells($cell)->setCellValue($cell_set,$value);
		}
	}

	/**
	 * set data cell & value beserta format
	 * @param string
	*/
	function setCellValue($cell = '', $value = '', $format_text = '')
	{
		$value = html_entity_decode($value,ENT_QUOTES,$this->charset);
		if (!empty($format_text))
		{
			$this->objset->setCellValueExplicit($cell, $value, self::setCellFormatType($format_text));
		}
		else
		{
			$this->objset->setCellValue($cell, $value);
		}
	}

	/**
	 * retrieving data value
	 * @param string
	*/
	function getCellValue($cell = '')
	{
		return $this->objget->getCell($cell)->getValue();
	}

	/**
	 * retrieving data from excel (support multiple sheet) local
	 * @param array
	*/
	function getDataFile($config = '')
	{
		# config 
		$file = $_FILES[key($_FILES)];
		# max size
		$max_size = ((isset($config['max_size']) && !empty($config['max_size'])) ? self::toMb($config['max_size']) : self::toMb(1000));
		# file is too large
		if ($file['size'] > $max_size)
		{
			die("File terlalu besar.");
		}

		$set_file_path = '';
		if ($this->set_path)
		{
			$set_file_path = $this->set_path;					
		}
		else
		{
			die('File not found.');
		}

		/*if (isset($config['filepath']) && $config['filepath'] == '') {
			die('File not found.');
		}*/

		$var 	= $this->var;
		$top  	= $var['top'];
		$right  = $var['right'];
		$bottom = $var['bottom'];
		$left 	= $var['left'];

		if($file['tmp_name'])
		{
			# upload files to temporary
			$name 		= $file['name'];
			$extend 	= strrchr ($name, '.');
			$file_mimes = array('application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			if(isset($file['name']) && in_array($file['type'], $file_mimes))
			{
				$file_path 		= \PhpOffice\PhpSpreadsheet\IOFactory::identify($set_file_path);
			    $obj_reader 	= \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_path);
			    $spreadsheet 	= $obj_reader->load($set_file_path);
				$sheet_count 	= $spreadsheet->getSheetCount();
		        $data_sheet 	= [];

				if (isset($config['sheet_targets']) && is_array($config['sheet_targets']))
				{
					# if array isAssoc
					if (!self::isAssoc($config['sheet_targets']))
					{
						foreach ($config['sheet_targets'] as $key => $value)
						{
							array_push($this->config_upload, [
								'table'	=> $value['table'],
								'where'	=> $value['where']
							]);
							# inisialisasi table
							// $table = (isset($value['table']) && $value['table'] !='') ? '.'.$value['table'] : '';
							$index = '';
							if (isset($value['target-name']))
							{
								# filter with sheet name
								$sheet = $spreadsheet->getSheetByName($value['target-name']);
								$index = $value['target-name'];
							}
							else
							{
								# filter with index
								$sheet = $spreadsheet->getSheet((isset($value['target-index'])) ? $value['target-index'] : $value['target']);
								$index = $value['target-index'];
							}

							$start_row 	= ((isset($value['start_row'])  && $value['start_row'] !== '') 	? $value['start_row'] 				: (($this->is_set_rowcol && $top != null) 	? $top : 1 ) );
					        $start_col 	= ((isset($value['start_col'])  && $value['start_col'] !== '') 	? $this->siConvert($value['start_col']) 	: (($this->is_set_rowcol && $left != null) 	? $this->siConvert($left) : 'A'));
							
							$end_row    = ((isset($value['end_row'])    && $value['end_row'] !== '')   	? $value['end_row'] 				: (($this->is_set_rowcol && $bottom != null)? $bottom : $sheet->getHighestDataRow()));
					        $end_col  	= ((isset($value['end_col'])    && $value['end_col'] !== '')   	? $value['end_col'] 				: (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				        	$end_col 	= ((is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col);

				        	for ($v=$start_row; $v <= $end_row ; $v++)
				        	{ 
					            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
					            if (!empty(array_diff($row_data[0], array(''))))
				            	{
				            		$data_sheet[$index][] = $row_data[0];
				            		// $data_sheet[$index.$table][] = $row_data[0];
				            	}
					        }
						}
					}
					else
					{
				        $type = 'index';
				        $sheet_data = [];
				        if (isset($config['sheet_targets']['target-name']))
				        {
				        	$type = 'name';
				        	$sheet_data = $config['sheet_targets']['target-name'];
				        }
				        else
				        {
				        	$sheet_data = (isset($config['sheet_targets']['target-index'])) ? $config['sheet_targets']['target-index'] : $config['sheet_targets']['sheet'];
				        }

				        # inisialisasi table
						// $table = (isset($config['sheet_targets']['table']) && $config['sheet_targets']['table'] !='') ? '.'.$config['sheet_targets']['table'] : ''; 

				        foreach ($sheet_data as $key => $value)
				        {
				        	if ($type == 'index')
				        	{
				        		# filter with index
								$sheet = $spreadsheet->getSheet($value);
				        	}
				        	else
				        	{
				        		# filter with sheet name
								$sheet = $spreadsheet->getSheetByName($value);
				        	}

				        	$start_row 	= ((isset($config['sheet_targets']['start_row']) && $config['sheet_targets']['start_row'] !== '') ? $config['sheet_targets']['start_row'] : (($this->is_set_rowcol && $top != null) ? $top : 1));
				        	$start_col 	= ((isset($config['sheet_targets']['start_col']) && $config['sheet_targets']['start_col'] !== '') ? $this->siConvert($config['sheet_targets']['start_col'])  : (($this->is_set_rowcol && $left != null) ? $this->siConvert($left) : 'A'));
							
							$end_row    = ((isset($config['sheet_targets']['end_row']) && $config['sheet_targets']['end_row'] !== '')  ? $config['sheet_targets']['end_row'] : (($this->is_set_rowcol && $bottom != null) ? $bottom : $sheet->getHighestDataRow()));
					      	$end_col  	= ((isset($config['sheet_targets']['end_col']) && $config['sheet_targets']['end_col'] !== '')  ? $config['sheet_targets']['end_col'] : (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				        	$end_col  	= (is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col;

				        	for ($v=$start_row; $v <= $end_row ; $v++)
				        	{ 
					            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
					            if (!empty(array_diff($row_data[0], array(''))))
				            	{
				            		$data_sheet[$value][] = $row_data[0];
				            		// $data_sheet[$value.$table][] = $row_data[0];
				            	}
					        }
				        }
					}
				}
				else
				{
					# chane the position cell
			        $start_row 	  = ((isset($config['start_row']) && $config['start_row'] !== '') ? $config['start_row'] : (($this->is_set_rowcol && $top != null) ? $top : 1));
			        $start_col 	  = ((isset($config['start_col']) && $config['start_col'] !== '') ? $this->siConvert($config['start_col'])  : (($this->is_set_rowcol && $left != null) ? $this->siConvert($left) : 'A'));

			    	for ($i=0; $i < $sheet_count ; $i++)
			    	{ 
			    		$sheet = $spreadsheet->getSheet($i);
						$end_row  = ((isset($config['end_row']) && $config['end_row'] !== '') ? $config['end_row'] : (($this->is_set_rowcol && $bottom != null) ? $bottom : $sheet->getHighestDataRow()));
				        $end_col  = ((isset($config['end_col']) && $config['end_col'] !== '') ? $config['end_col'] : (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				    	$end_col  = (is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col;
			    		
				    	for ($v=$start_row; $v <= $end_row ; $v++)
				    	{ 
				            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
				            if (!empty(array_diff($row_data[0], array(''))))
			            	{
			            		$data_sheet[$sheet->getTitle()][] = $row_data[0];
			            	}
				        }
			    	}

				}
				$this->data_upload = $data_sheet;
			}
			else
			{
				die("file not found");
			}
		}
		return $this;
	}

	/**
	 * retrieving data from excel (support multiple sheet)
	 * @param array
	*/
	function upload($config = '')
	{
		# config 
		$file = $_FILES[key($_FILES)];
		# max size
		$max_size = ((isset($config['max_size']) && !empty($config['max_size'])) ? self::toMb($config['max_size']) : self::toMb(1000));
		# file is too large
		if ($file['size'] > $max_size)
		{
			die("File terlalu besar.");
		}

		$var 	= $this->var;
		$top  	= $var['top'];
		$right  = $var['right'];
		$bottom = $var['bottom'];
		$left 	= $var['left'];

		if($file['tmp_name'])
		{
			# upload files to temporary
			$name 		= $file['name'];
			$extend 	= strrchr ($name, '.');
			$file_mimes = ['application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
			if(isset($file['name']) && in_array($file['type'], $file_mimes))
			{
				$file_extension = str_replace('.', '', $extend);
				$reader 		= \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($file_extension));
				# if set path
				if ($this->set_path)
				{
					$target_path = $this->set_path.'/'.$file['name'];
    				move_uploaded_file($file['tmp_name'], $target_path);
					$spreadsheet 	= $reader->load($target_path);
				}
				else
				{
					$spreadsheet 	= $reader->load($file['tmp_name']);
				}

				$sheet_count 	= $spreadsheet->getSheetCount();
		        $data_sheet 	= [];

				if (isset($config['sheet_targets']) && is_array($config['sheet_targets']))
				{
					# if array isAssoc
					if (!self::isAssoc($config['sheet_targets']))
					{
						foreach ($config['sheet_targets'] as $key => $value)
						{
							array_push($this->config_upload, [
								'table'	=> (isset($value['table']) && $value['table'] !='') ? $value['table'] : '',
								'where'	=> (isset($value['where']) && $value['where'] !='') ? $value['where'] : ''
							]);
							# inisialisasi table
							// $table = (isset($value['table']) && $value['table'] !='') ? '.'.$value['table'] : '';
							$index = '';
							if (isset($value['target-name']))
							{
								# filter with sheet name
								$sheet = $spreadsheet->getSheetByName($value['target-name']);
								$index = $value['target-name'];
							}
							else
							{
								# filter with index
								$sheet = $spreadsheet->getSheet((isset($value['target-index'])) ? $value['target-index'] : $value['target']);
								$index = $value['target-index'];
							}

							$start_row 	= ((isset($value['start_row'])  && $value['start_row'] !== '') 	? $value['start_row'] 				: (($this->is_set_rowcol && $top != null) 	? $top : 1 ) );
					        $start_col 	= ((isset($value['start_col'])  && $value['start_col'] !== '') 	? $this->siConvert($value['start_col']) 	: (($this->is_set_rowcol && $left != null) 	? $this->siConvert($left) : 'A'));
							
							$end_row    = ((isset($value['end_row'])    && $value['end_row'] !== '')   	? $value['end_row'] 				: (($this->is_set_rowcol && $bottom != null)? $bottom : $sheet->getHighestDataRow()));
					        $end_col  	= ((isset($value['end_col'])    && $value['end_col'] !== '')   	? $value['end_col'] 				: (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				        	$end_col 	= ((is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col);

				        	for ($v=$start_row; $v <= $end_row ; $v++)
				        	{ 
					            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
					            if (!empty(array_diff($row_data[0], array(''))))
				            	{
				            		$data_sheet[$index][] = $row_data[0];
				            		// $data_sheet[$index.$table][] = $row_data[0];
				            	}
					        }
						}
					}
					else
					{
						foreach ($config['sheet_targets']['target-name'] as $key => $value) {
							array_push($this->config_upload, [
								'table'	=> (isset($config['sheet_targets']['table']) && $config['sheet_targets']['table'] !='') ? $config['sheet_targets']['table'] : '',
								'where'	=> (isset($config['sheet_targets']['where']) && $config['sheet_targets']['where'] !='') ? $config['sheet_targets']['where'] : ''
							]);
						}
				        $type = 'index';
				        $sheet_data = [];
				        if (isset($config['sheet_targets']['target-name']))
				        {
				        	$type = 'name';
				        	$sheet_data = $config['sheet_targets']['target-name'];
				        }
				        else
				        {
				        	$sheet_data = (isset($config['sheet_targets']['target-index'])) ? $config['sheet_targets']['target-index'] : $config['sheet_targets']['sheet'];
				        }

				        # inisialisasi table
						//$table = (isset($config['sheet_targets']['table']) && $config['sheet_targets']['table'] !='') ? '.'.$config['sheet_targets']['table'] : ''; 

				        foreach ($sheet_data as $key => $value)
				        {
				        	if ($type == 'index')
				        	{
				        		# filter with index
								$sheet = $spreadsheet->getSheet($value);
				        	}
				        	else
				        	{
				        		# filter with sheet name
								$sheet = $spreadsheet->getSheetByName($value);
				        	}

				        	$start_row 	= ((isset($config['sheet_targets']['start_row']) && $config['sheet_targets']['start_row'] !== '') ? $config['sheet_targets']['start_row'] : (($this->is_set_rowcol && $top != null) ? $top : 1));
				        	$start_col 	= ((isset($config['sheet_targets']['start_col']) && $config['sheet_targets']['start_col'] !== '') ? $this->siConvert($config['sheet_targets']['start_col'])  : (($this->is_set_rowcol && $left != null) ? $this->siConvert($left) : 'A'));
							
							$end_row    = ((isset($config['sheet_targets']['end_row']) && $config['sheet_targets']['end_row'] !== '')  ? $config['sheet_targets']['end_row'] : (($this->is_set_rowcol && $bottom != null) ? $bottom : $sheet->getHighestDataRow()));
					      	$end_col  	= ((isset($config['sheet_targets']['end_col']) && $config['sheet_targets']['end_col'] !== '')  ? $config['sheet_targets']['end_col'] : (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				        	$end_col  	= (is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col;

				        	for ($v=$start_row; $v <= $end_row ; $v++)
				        	{ 
					            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
					            if (!empty(array_diff($row_data[0], array(''))))
				            	{
				            		$data_sheet[$value][] = $row_data[0];
				            		// $data_sheet[$value.$table][] = $row_data[0];
				            	}
					        }
				        }
					}
				}
				else
				{
					# chane the position cell
			        $start_row 	  = ((isset($config['start_row']) && $config['start_row'] !== '') ? $config['start_row'] : (($this->is_set_rowcol && $top != null) ? $top : 1));
			        $start_col 	  = ((isset($config['start_col']) && $config['start_col'] !== '') ? $this->siConvert($config['start_col'])  : (($this->is_set_rowcol && $left != null) ? $this->siConvert($left) : 'A'));

			    	for ($i=0; $i < $sheet_count ; $i++)
			    	{ 
			    		$sheet = $spreadsheet->getSheet($i);
						$end_row  = ((isset($config['end_row']) && $config['end_row'] !== '') ? $config['end_row'] : (($this->is_set_rowcol && $bottom != null) ? $bottom : $sheet->getHighestDataRow()));
				        $end_col  = ((isset($config['end_col']) && $config['end_col'] !== '') ? $config['end_col'] : (($this->is_set_rowcol && $right != null) ? $right : $sheet->getHighestDataColumn()));
				    	$end_col  = (is_numeric($end_col)) ? $this->siConvert($end_col) : $end_col;
			    		
				    	for ($v=$start_row; $v <= $end_row ; $v++)
				    	{ 
				            $row_data = $sheet->rangeToArray($start_col . ($v) . ':' . $end_col . ($v), NULL, TRUE, FALSE);
				            if (!empty(array_diff($row_data[0], array(''))))
			            	{
			            		$data_sheet[$sheet->getTitle()][] = $row_data[0];
			            	}
				        }
			    	}

				}
				$this->data_upload = $data_sheet;
			}
			else
			{
				die("file not found");
			}
		}
		return $this;
	}

	/**
	 * 
	 * @return array
	*/
	function toArray()
	{
		return ((!empty($this->set_index)) ? $this->set_index : $this->data_upload);
	}

	/**
	 * 
	 * @return object
	*/
	function toObject()
	{
		$cn = (($this->set_index) ? $this->set_index : $this->data_upload);
		return json_decode(json_encode($cn));
	}

	/**
	 * data yang di upload berupa array yang index nya berupa value header atau data array index pertama
	*/
	function setIndexFromTitle()
	{
		$data 	= $this->data_upload;
		$return = [];
		foreach ($data as $key => $value)
		{
			$title = (isset($value[0])) ? $value[0] : [];
			$data_title = [];

			foreach ($value as $key_2 => $value_2)
			{
				if ($key_2 > 0) {
					foreach ($title as $key_title => $value_title)
					{
						$data_title[$key_2][$value_title] = $value_2[$key_title];
					}
				}
			}
			$return[$key] = $data_title;
		}
		$this->set_index = $return;
		return $this;
	}

	/**
	 * delete table database
	 * @param array atau string
	*/
	function destroy($value = '')
	{
		if (!$this->db) {
			$this->db = new ExcelConnection(null, null, null, null);
		}
		$ww = '';
		if ($value !='') {
			if (is_array($value)) {
	            $ww = ' WHERE '.$this->_where($value);
	        }else{
	            $ww = ' WHERE '.$value;
	        }
		}else{
			$ww = '';
		}
		$this->db->query("DELETE FROM ".$this->table." ".$ww)->affectedRows();
        return $this;
	}

	/**
	 * insert data to database
	*/
	function store(){
		$_success = false;
		$_table 	= '';

		if (!$this->db) {
			$this->db = new ExcelConnection(null, null, null, null);
		}
		$index = 0;
		foreach ($this->data_upload as $key => $value)
		{
			# get table
			$table_name = (isset($this->config_upload[$index]['table']) && $this->config_upload[$index]['table'] !='') ? $this->config_upload[$index]['table'] : '';
			$remove_cell_empty = $this->removeNull($value);
			$data 	    = [];
			$title = array_filter($remove_cell_empty[0], function($a) {
			    return trim($a) !== "";
			});

			foreach ($remove_cell_empty as $key2 => $value_cell)
			{
				if ($key2>0)
				{
					array_push($data, $value_cell);
				}
			}

			$returnData = [];
			foreach ($data as $keyData => $valueData)
			{
				foreach ($valueData as $keyDataDetail => $valueDataDetail)
				{
					foreach ($title as $key_2 => $value_2)
					{
						$returnData[$keyData][$value_2] = $valueData[$key_2];
					}
				}
			}

			$newData = $this->_rmvQ($returnData);
			$_values  = '';
			foreach ($newData as $key => $value)
			{
				$implode = implode(',', $value);
				$_values .= '('.$implode.'),';
			}

			$_fields  = implode(',', $title);
			$_values  = substr_replace($_values,"",-1);

			$table_name = (isset($table_name) && $table_name !='') ? $table_name : $this->table;
			if ($table_name !='')
			{
				$op_store = $this->db->query("INSERT INTO ".$table_name." (".$_fields.") VALUES ".$_values." ")->affectedRows();

				if ($op_store > 0)
				{
					$_success = true;
				}
				else
				{
					die('Failed to save data. '.$table_name);
				}
			}
			else
			{
				die('table not found. '.$table_name);
			}

			$index +=1;
		}

		return [
			'success' 	=> $_success,
			'message' 	=> ($op_store >0) ? 'Berhasil menyimpan data.' : 'Gagal menyimpan data.',
		];
	}

	/**
	 * jika data belum ada maka akan ditambahkan
	 * jika data sudah ada maka akan di updata
	 * @param array atau string
	*/
	function save($callback = '')
	{
		if (!$this->db) {
			$this->db = new ExcelConnection(null, null, null, null);
		}

		foreach ($this->data_upload as $key => $value)
		{
			$remove_cell_empty = $this->removeNull($value);
			$data 	    = [];
			# set title
			$title = array_filter($remove_cell_empty[0], function($a) {
			    return trim($a) !== "";
			});

			# set data
			foreach ($remove_cell_empty as $key2 => $value_cell)
			{
				if ($key2>0)
				{
					array_push($data, $value_cell);
				}
			}

			$new_data = [];
			foreach ($data as $key_data => $value_data)
			{
				foreach ($value_data as $key_data_etail => $value_data_detail)
				{
					foreach ($title as $key_title => $value_title)
					{
						$new_data[$key_data][$value_title] = $value_data[$key_title];
					}
				}
			}

			print_r($key);
			print_r($this->config_upload);

			/*$return_data = [];
			foreach ($data as $key_data => $value_data)
			{
				foreach ($value_data as $key_data_detail => $value_data_detail)
				{
					foreach ($title as $key_2 => $value_2)
					{
						$return_data[$key_data][$value_2] = $value_data[$key_2];
					}
				}
			}

			$new_data = $this->_rmvQ($return_data);*/

			// print_r($data);
		}
		exit();

		$dataFile 		= $this->removeNull($this->data_upload['data']);
		$data 	   		= [];
		$saveData 		= [];
		$saveDataFilter = [];
		$operation  	= [];
		$title = array_filter($dataFile[0], function($a) {
		    return trim($a) !== "";
		});

		foreach ($dataFile as $key => $value) {
			if ($key>0) {
				array_push($data, $value);
			}
		}
		foreach ($data as $keyData => $valueData) {
			foreach ($valueData as $keyDataDetail => $valueDataDetail) {
				foreach ($title as $key => $value) {
					if ($this->removeIndexNo($value)) {}{
						$saveDataFilter[$keyData][$value] = $valueData[$key];
					}
				}
			}
		}

		$where_column = ((!empty((Array)$where_column)) ? (Array)$where_column : $title);
		foreach ($saveDataFilter as $key => $value) {
			$_where = array();
			foreach ((Array) $where_column as $key2 => $value2) {
				$_where[$value2] = $value[$value2];
			}
			$_where  = $this->_where($_where);
			$cekData = $this->db->query("SELECT * FROM ".$this->table." WHERE ".$_where)->resultArray();
			if (count($cekData) > 0) {
				# update
				$dt = '';
				foreach ($value as $key2 => $value2) {
					$dt .= $key2."='".$value2."',";
				}
				$dt  = substr_replace($dt,"",-1);
				$_update = $this->db->query("UPDATE ".$this->table." SET ".$dt." WHERE ".$_where)->affectedRows();
				$operation = ($_update) ? true : false;
			}else{
				# insert
				$_values = '';
				$_fields = '';
				foreach ($value as $key2 => $value2) {
					$_values .= "'".$value2."',";
					$_fields .= $key2.",";
				}
				$_fields  = "(".substr_replace($_fields,"",-1).")";
				$_values  = "(".substr_replace($_values, "", -1).")";

				$_insert = $this->db->query("INSERT INTO ".$this->table." ".$_fields." VALUES ".$_values." ")->affectedRows();
				$operation = ($_insert) ? true : false;
			}
		}

		if (is_callable($callback)) {
			call_user_func($callback,array(
				'self' 	=> $saveDataFilter,
				'all'	=> $this->var
			));
		}

		return array(
			'success' => ($operation) ? success : false,
			'message' => ($operation) ? 'Berhasil menyimpan data.' : 'Gagal menyimpan data.',
			'data'	  => $saveDataFilter
		);
	}

	/**
	 * 
	 * @param array
	*/
    function setStyles($style = '')
	{
		$array_data 	= $this->set_array['data'];
		$name_function 	= $this->set_array['function_name'];

		if (is_array($array_data)) {

			$start_col  = self::setInput($this->var['left']);
			$start_head = self::setInput($this->var['top']);
			$start_body = self::setInput($this->var['start_body']);

			if (self::isArrayMulti($array_data)) {
				if (self::countDim($style)>1) {
					# echo ">= 2 dimesnsi";
					# develop
					/*foreach ($array_data as $key => $value) {
						$is_merge = explode(':', $value['cell']);
						if (count($is_merge)>1) {
							$format_text = (!empty($value['text-format'])) ? $value['text-format'] : null; // format text
							$this->setMergeCells($value['cell'],$is_merge[0],$value['value'],$format_text);
						}else{
							$format_text = (!empty($value['text-format'])) ? $value['text-format'] : null; // format text
							$this->setCellValue($value['cell'],$value['value'],$format_text);
						}
					}*/
				}else{
					# echo "1 dimensi";
					$st 		= $this->siConvert($start_col).$start_body;
					$last_col 	= (count($array_data) + $start_body);
					$ls = null;
					foreach ($array_data as $key => $value) {
						$kk = 0;
						foreach ($value as $key2 => $value2) {
							$cell = $this->siConvert($start_col+$kk).($start_body+$key);
							$this->addStyles($cell,$style);
							$kk++;
							$ls = $cell;
						}
					}
					// format nambaer
					if (!empty($style['number-format'])) {
						if (!empty($style['number-format']['col'])) {
							foreach ($style['number-format']['col'] as $key => $value) {
								for ($i=$start_body; $i < $last_col ; $i++) { 
									$cell = $this->siConvert($value).$i;
									$this->addNumberFormat($cell, $style['number-format']['type']);
								}
							}
						}
					}

					$cl = $st.':'.$ls;
					if (isset($style['border-outside'])) {
						$this->setBorderOutside($cl,$style['border-outside']);
					}
				}
			}else{
				if ($name_function == 'createHeader') {
					$st = 0 + ($start_col - 1);
					foreach ($array_data as $key => $value) {
						$cell = $this->siConvert($start_col+$key).$start_head;
						$this->addStyles($cell,$style);
						$st +=1;
					}
					$start_row = $this->siConvert($start_col).$start_head;
					$last_row  = $this->siConvert($st).$start_head;

					$cl = $start_row.':'.$last_row;

					if (isset($style['border-outside'])) {
						$this->setBorderOutside($cl,$style['border-outside']);
					}
				}else{
					if (!empty($style['number-format'])) {
						$cell = $style['number-format']['col'];
						$this->addNumberFormat($cell, $style['number-format']['type']);
					}
				}
			}
		}else{
			$this->var['error_addArrayStyles'] = 'fields data must be a array';
		}
		return $this;
	}

	function createStyle($cell = '', $css = '')
	{
		if (!empty($cell) && !empty($css))
		{

			if (isset($this->drawImage)) 
			{
				$_css = array_filter(array_map('trim', $css));
				$name 		= (isset($_css['img-name'])  		? $_css['img-name'] 	    : '');
				$description= (isset($_css['img-description']) 	? $_css['img-description'] 	: '');
				$offsetX 	= (isset($_css['img-offset-x']) 	? $_css['img-offset-x'] 	: 0);
				$offsetY 	= (isset($_css['img-offset-y']) 	? $_css['img-offset-y'] 	: 0);
				$rotate 	= (isset($_css['img-rotate'])		? $_css['img-rotate'] 		: 0);

				$this->drawImage->setName($name);
				$this->drawImage->setDescription($description);
				$this->drawImage->setRotation($rotate);

				// width & height
				if (isset($_css['img-width']))
				{
					$this->drawImage->setWidth($_css['img-width']);
				}

				if (isset($_css['img-height']))
				{
					$this->drawImage->setHeight($_css['img-height']); 
				}
				// offset
				$this->drawImage->setOffsetX($offsetX);
				$this->drawImage->setOffsetY($offsetY);
			}

			$_style = [];
			# start borders
				# border default
				$_border = [];
				$_border_type = 'allBorders';
				$_diagonal_direction = false;

				if (isset($css['border']))
				{
					$css['border'] = (($css['border']=='default') ? 'allBorders' : $css['border']);
					$border = $this->borderProperties($css['border'],'border');
					$border['type'] = ($border['type'] == 'allborders') ? 'allBorders' : $border['type'];
					$_style['borders'][$border['type']]['borderStyle'] = $this->conBorder($border['style']);
					$_style['borders'][$border['type']]['color'] = ['rgb'=>str_replace('#', '', $border['color'])];
					$_style['borders']['diagonalDirection'] = $this->diagonaldirection($border['diagonaldirection']);
					$_border_type = $border['type'];
				}

				if (isset($css['border-type']))
				{
					$_border_type = $css['border-type'];
				}

				if (isset($css['border-style']))
				{
					$_style['borders'][$_border_type]['borderStyle'] = $this->conBorder($css['border-style']);
				}

				if (isset($css['border-color']))
				{
					$_style['borders'][$_border_type]['color'] = ['rgb'=>str_replace('#', '', $css['border-color'])];
				}

				if (isset($css['border-diagonaldirection']))
				{
					$_diagonal_direction = true;
					$_style['borders']['diagonalDirection'] = $this->diagonaldirection($css['border-diagonaldirection']);
				}
				
				# border top
				if (isset($css['border-top']))
				{
					$border_top = $this->borderProperties($css['border-top'],'top');
					$_style['borders'][$border_top['type']]['borderStyle'] = $this->conBorder($border_top['style']);
					$_style['borders'][$border_top['type']]['color'] = ['rgb'=>str_replace('#', '', $border_top['color'])];
					if (!$_diagonal_direction)
					{
						$_style['borders']['diagonalDirection'] = $this->diagonaldirection($border_top['diagonaldirection']);
					}
				}

				# border left
				if (isset($css['border-left']))
				{
					$border_left = $this->borderProperties($css['border-left'],'left');
					$_style['borders'][$border_left['type']]['borderStyle'] = $this->conBorder($border_left['style']);
					$_style['borders'][$border_left['type']]['color'] = ['rgb'=>str_replace('#', '', $border_left['color'])];
					if (!$_diagonal_direction)
					{
						$_style['borders']['diagonalDirection'] = $this->diagonaldirection($border_left['diagonaldirection']);
					}
				}

				# border right
				if (isset($css['border-right']))
				{
					$border_right = $this->borderProperties($css['border-right'],'right');
					$_style['borders'][$border_right['type']]['borderStyle'] = $this->conBorder($border_right['style']);
					$_style['borders'][$border_right['type']]['color'] = ['rgb'=>str_replace('#', '', $border_right['color'])];
					if (!$_diagonal_direction)
					{
						$_style['borders']['diagonalDirection'] = $this->diagonaldirection($border_right['diagonaldirection']);
					}
				}

				# border bottom
				if (isset($css['border-bottom']))
				{
					$border_bottom = $this->borderProperties($css['border-bottom'],'bottom');
					$_style['borders'][$border_bottom['type']]['borderStyle'] = $this->conBorder($border_bottom['style']);
					$_style['borders'][$border_bottom['type']]['color'] = ['rgb'=>str_replace('#', '', $border_bottom['color'])];
					if (!$_diagonal_direction)
					{
						$_style['borders']['diagonalDirection'] = $this->diagonaldirection($border_bottom['diagonaldirection']);
					}
				}
			# End borders

			# alignment
				if (isset($css['horizontal-align']) && $css['horizontal-align'] != '')
				{ 
					$this->setHorizontal($cell, $css['horizontal-align']); 
				}

				if (isset($css['vertical-align']) && $css['vertical-align'] != '') 
				{ 
					$this->setVertical($cell,$css['vertical-align']); 
				}

				if (isset($css['nowrap']) && $css['nowrap'] != '') 
				{
					$_style['alignment']['wrapText'] = (($css['nowrap']) ? boolval($css['nowrap']) : false);
				}

				if (isset($css['wrap']) && $css['wrap'] != '')
				{ 
					$_style['alignment']['wrapText'] = (($css['wrap']) ? true : false);
				}

				if (isset($css['wrapText']) && $css['wrapText'] !='')
				{ 
					$_style['alignment']['wrapText'] = (($css['wrapText']) ? true : false);
				}
				// shrinkToFit
				// indent

			# text 
				// if (isset($css['text-rotation'])) 		{ $_style['alignment']['textRotation'] = (($css['text-rotation']) ? true : false);}
				if (isset($css['text-rotate']) && $css['text-rotate'] != '')
				{
					$this->setTextRotate($cell,$css['text-rotate']); 
				}

				if (isset($css['text-align']) && $css['text-align'] != '') 
				{ 
					$this->setHorizontal($cell,$css['text-align']); 
				}
			
			# Fill
			# End Fill

			# Start font
				// name
				if (isset($css['font-name']) && $css['font-name'] != '')
				{
					$_style['font']['name'] = $css['font-name']; 
				}

				// bold
				if (isset($css['font-bold']) && $css['font-bold'] != '')
				{
					$_style['font']['bold'] = (($css['font-bold']) ? true : false); 
				}

				// italic
				if (isset($css['font-italic']) && $css['font-italic'] != '')
				{
					$_style['font']['italic'] = $css['font-italic']; 
				}

				// super script
				if (isset($css['font-superScript']) && $css['font-superScript'] != '')
				{
					$_style['font']['superscript'] = $css['font-superScript']; 
				}

				// sub script
				if (isset($css['font-subScript']) && $css['font-subScript'] != '') 
				{
					$_style['font']['subScript'] = $css['font-subScript']; 
				}

				// underline
				if (isset($css['font-underline']) && $css['font-underline'] != '')
				{
					$_style['font']['underline'] = $css['font-underline']; 
				}

				// strike
				if (isset($css['font-strike']) && $css['font-strike'] != '')
				{
					$_style['font']['strike'] = $css['font-strike']; 
				}

				// size
				if (isset($css['font-size']) && $css['font-size'] != '')
				{
					$_style['font']['size'] = $css['font-size']; 
				}

				// color
				if (isset($css['font-color']) && $css['font-color'] != '')
				{
					$_style['font']['color'] = array('rgb' => str_replace("#", '', self::color($css['font-color']))); 
				}

				if (isset($css['color']) && $css['color'] != '')
				{
					$_style['font']['color'] = array('rgb' => str_replace("#", '', self::color($css['color']))); 
				}
			# End font

				if (isset($css['background-color']) && $css['background-color'] != '') 
				{ 
					$this->setBackGroundColor($cell,array('color'=>str_replace('#', '', self::color($css['background-color'])))); 
				}

			# numberformat
				// if (!empty($css['number-format'])) 	 	{ $this->addNumberFormat($cell, $css['number-format']); }
				if (isset($css['number-format']) && $css['number-format'] != '')
				{ 
					$_style['numberFormat']['formatCode'] = $this->numberFormat($css['number-format']);
				}

			# 
				// height
				if (isset($css['height']) && $css['height'] != '')
				{ 
					$this->setCellHeight($cell, (int) $css['height']);
				}

				if (isset($css['width']) && $css['width'] != '') 
				{ 
					$this->setCellWidth($cell, (int) $css['width']);
				}

			# Protected the cell
				// protectCells
				if (isset($css['protect-cells']))
				{ 
					$this->protectCells($cell, $css['protect-cells']); 
				}
				
				if (isset($css['unprotect-cells']))
				{ 
					$this->unProtectCells($cell, $css['unprotect-cells']); 
				}
				
				if (isset($css['locked']))
				{ 
					$this->protectCells($cell, $css['locked']); 
				}
				
				if (isset($css['unlocked']))
				{ 
					$this->unProtectCells($cell, $css['unlocked']); 
				}
			# 
			#Indent Format Cell
				if (isset($css['indent']) && $css['indent'] != '')
				{ 
					$this->objset->getStyle($cell)->getAlignment()->setIndent(intval($css['indent']));
				}
			#
			$this->objset->getStyle($cell)->applyFromArray($_style);
		}
	}

	/**
	* 
	* @param string or @param array
		[
			[
				'cell'  => 'A5:C10',
				'style' => []
			],
			[
				'cell'  => 'A1:Z1',
				'style' => []
			]
		]
	*/
	function addStyles($cell = '',$css = '')
	{
		if (is_array($cell))
		{
			foreach ($cell as $key => $value) 
			{
				$this->createStyle($value['cell'], $value['style']);
			}
		}
		else
		{
			$this->createStyle($cell, $css);
		}
		return $this;
	}

	/**
	 * format number
	 * @param string
	*/
	function addNumberFormat($cell = '', $type = '')
	{
		$this->objset->getStyle($cell)->getNumberFormat()->setFormatCode($type);
	}

	/**
	 * example ('A1:F19', 'outline #000000')
	 * @param string
	 * @param string
	*/
	function setBorderOutside($cell,$css)
	{
		$border = $this->borderProperties($css,'border');
		$_style['borders']['outline']['borderStyle']  = $this->conBorder($border['style']);
		$_style['borders']['outline']['color']  = ['rgb'=>str_replace('#', '', $border['color'])];
		$_style['borders']['diagonaldirection'] = $this->diagonaldirection($border['diagonaldirection']);
		$this->objset->getStyle($cell)->applyFromArray($_style);
	}

	function convertDate($value, $isAll = false){
		if ($isAll) 
		{
			return (array) \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
		}
		else
		{
			$op = (array) \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
			return $op['date'];
		}
	}

	function borderOutline($cell = null, $type = null, $style = null)
	{
		if ($cell == null) 
		{
			$array_data = $this->set_array['data'];
			$start_col  = self::setInput($this->var['left']);
			$start_head = self::setInput($this->var['top']);
			$start_body = self::setInput($this->var['start_body']);
		}
		else
		{

		}

		$BStyle = array(
		  	'borders' => array(
		    	$type => array(
		      		'borderStyle' => Style\Border::BORDER_THIN
		    	)
		  )
		);
		return $this;
	}

	function diagonaldirection($value = '')
	{
		if (strtolower($value)=='up') 
		{
			return Style\Borders::DIAGONAL_UP;
		}
		else if (strtolower($value)=='both') 
		{
			return Style\Borders::DIAGONAL_BOTH;
		}
		else if (strtolower($value)=='down') 
		{
			return Style\Borders::DIAGONAL_DOWN;
		}
		else if (strtolower($value)=='none') 
		{
			return Style\Borders::DIAGONAL_NONE;
		}
		else
		{
			return $value;
		}
	}

	// PROTECT THE CELL RANGE
	function protectCells($cell = '')
	{
		// $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
		// $spreadsheet->getDefaultStyle()->getProtection()->setLocked(false);
		$this->protectSheet();
		$this->spreadsheet->getDefaultStyle()->getProtection()->setLocked(false);
		$this->objset->getStyle($cell)->getProtection()->setLocked(Style\Protection::PROTECTION_PROTECTED);
		return $this;
	}
	// UNPROTECT THE CELL RANGE
	function unProtectCells($cell = '')
	{
		$this->objset->getStyle($cell)->getProtection()->setLocked(Style\Protection::PROTECTION_UNPROTECTED);
		return $this;
	}
	// PROTECT THE WORKSHEET SHEET
	function protectSheet()
	{
		$this->objset->getProtection()->setSheet(true);
		return $this;
	}

	function setFontUnderLine($cell = '', $value = '')
	{	
		$rt = '';
		if ($value == true || $value == '') {
			$rt = Style\Font::UNDERLINE_SINGLE;
		}else{
			$arr = [
				['type'	=>	'none', 				'value'	=> Style\Font::UNDERLINE_NONE],
				['type'	=>	'double', 				'value'	=> Style\Font::UNDERLINE_DOUBLE],
				['type'	=>	'doubleAccounting', 	'value'	=> Style\Font::NE_DOUBLEACCOUNTING],
				['type'	=>	'single', 				'value'	=> Style\Font::UNDERLINE_SINGLE],
				['type'	=>	'singleAccounting', 	'value'	=> Style\Font::UNDERLINE_SINGLEACCOUNTING],
			];
			foreach ($arr as $key_1 => $value_1) {
				if ($value_1['type'] == $value) {
					$rt = $value_1['value'];
				}
			}
		}
		$styleArray = array(
	    	'font'  => array(
	        	'underline' => $rt,
	    	)
	    );
		$this->objset->getStyle($cell)->applyFromArray($styleArray);
		return $this;
	}
	#DEVELOP
	function getFontStrike()
	{
		
	}
	// END FONT

	// TEXT
	#DEVELOP
	function setTextWrap()
    {
    	
    }

    function setWrapText(){

    }

    function setTextRotate($cell = '', $value = '')
    {
    	$this->objset->getStyle($cell)->getAlignment()->setTextRotation($value);
    }
	// END TEXT

	function setCellHeight($cell = '',$value = '')
    {
    	$cls = self::siSplit($cell);
    	if (!empty($value) && is_array($cls))
    	{
    		$_a = is_string($cls[1]) ? $cls[1] : (int) $cls[1];
    		$this->objset->getRowDimension($_a)->setRowHeight($value);
    		// $this->spreadsheet->getActiveSheet()->getRowDimension($_a)->setRowHeight($value);
    	}
    	else
    	{
    		if (!empty($value))
    		{
    			$_a = is_string($value) ? $this->siConvert($value) : $value;
    			$this->objset->getRowDimension()->setRowHeight($_a);
    			// $this->spreadsheet->getActiveSheet()->getRowDimension()->setRowHeight($_a);
    		}
    		else
    		{
    			$this->var_error['error_getcellheight'] = 'this parameter not found';
    		}
    	}
    	return $this;
    }

	// STRAT BORDER
	function setBorder($cell = '', $prop = '')
	{
		if ($prop=='auto' || $prop=='default')
		{
			$BStyle = array(
			  	'borders' => array(
				    'allborders' => array(
				      	'borderStyle' => 'thin',
				      	'color' => array('rgb' => null)
				    )
				)
			);
			$this->objset->getStyle($cell)->applyFromArray($BStyle);
		}
		else
		{
			if (self::countDim($prop)>1)
			{
				foreach ($prop as $key => $value)
				{
					$BStyle = array(
					  	'borders' => array(
						    $key => array(
						      	'borderStyle' => (!empty($value['style'])) ? $value['style'] : 'thin',
						      	'color' => array('rgb' => (!empty($value['color'])) ? $value['color'] : null)
						    )
						)
					);
					$this->objset->getStyle($cell)->applyFromArray($BStyle);
				}
			}
			else
			{
				$key = (!empty($prop['type'])) ? $prop['type'] : 'allborders';
				$BStyle = array(
				  	'borders' => array(
					    $key => array(
					      	'borderStyle' => (!empty($prop['style'])) ? $prop['style'] : 'thin',
					      	'color' => array('rgb' => (!empty($prop['color'])) ? $prop['color'] : null)
					    )
					)
				);
				$this->objset->getStyle($cell)->applyFromArray($BStyle);
			}
		}
	}

	function setBackGroundColor($cell = '', $prop = '')
	{
		if (is_array($prop))
		{
			if (self::countDim($prop)>1)
			{
				
			}
			else
			{
				$this->objset->getStyle($cell)->getFill()
				    ->setFillType(Style\Fill::FILL_SOLID)
				    ->getStartColor()->setARGB($prop['color']);
			}
		}
		else
		{

		}
	}

	function setAutoBorder($config = '')
	{
		$data = $this->set_array;
		$sh = self::setInput($this->var['top']); 
		$sb = self::setInput($this->var['start_body']); 
		$sc = self::setInput($this->var['left']);  

		if (is_array($data['data']))
		{
			$config = (empty($config)) ? 'default' : $config;
			if (self::countDim($data['data'])>1)
			{
				$count_col = (count(array_count_values($data['data'][0]))+($sc-1));
				$count_row = (count($data['data']) + $sh);
				$first = $this->getNameFromNumber($sc).($sb);
				$last  = $this->getNameFromNumber($count_col).$count_row;
				$this->setBorder($first.':'.$last,$config);
			}
			else
			{
				if (!empty($data['data']) && $data['function_name']=='createHeader')
				{
					$first = $this->getNameFromNumber($sc).$sh;
					$last  = $this->getNameFromNumber(count($data['data'])+$sc).$sh;
					$this->setBorder($first.':'.$last,$config);
				}
				else
				{

				}
			}
		}
		else
		{
			$this->var['error_setdatacells'] = 'fields data must be a array';
		}
		return $this;
	}

	function setAutoWidth($config = '')
	{
		$data = $this->set_array;
		return $this;
	}

	function getTypeBorder()
	{

	}

	function setAutoSize($start='',$last='')
	{
		if ($last!="") 
		{
			foreach(range($start,$last) as $columnID)
			{
			    $this->objset->getColumnDimension($columnID)->setAutoSize(true);
			}
		}
		else
		{
			$this->objset->getColumnDimension($start)->setAutoSize(true);
		}
	}

	// END BORDER
	function setAlignment($cell='',$prop='')
	{
		if (!empty($prop['horizontal'])) 
		{ 
			$this->setHorizontal($cell,$prop['horizontal']); 
		}
		if (!empty($prop['vertical'])) 
		{ 
			$this->setVertical($cell,$prop['vertical']); 
		}
	}

	function setCellWidth($cell='',$prop='')
	{
		$col = self::siSplit($cell);
		if ($prop=='auto')
		{
    		$this->objset->getColumnDimension($col[0])->setAutoSize(true);
		}
		else
		{
			$this->objset->getColumnDimension($col[0])->setWidth($prop);
		}
    	if (count($col)>4) {
			$this->var_error['error_setcellwidth'] = 'format cell not supported';
		}
	}

	function setHorizontal($cell='',$value='')
    {
    	if ($value=='center')
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_CENTER);
    	}
    	elseif ($value=='right') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_RIGHT);
    	}
    	elseif ($value=='left') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_LEFT);
    	}
    	elseif ($value=='centerContinuous') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_CENTER_CONTINUOUS);
    	}
    	elseif ($value=='justify') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_JUSTIFY);
    	}
    	elseif ($value=='general') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_GENERAL);
    	}
    	elseif ($value=='fill') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_FILL);
    	}
    	elseif ($value=='distributed') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal(Style\Alignment::HORIZONTAL_DISTRIBUTED);
    	}
    	else
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setHorizontal($value);
    	}
    }

    function setVertical($cell='',$value='')
    {
    	if ($value=='center') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
    	}
    	elseif ($value=='middle') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical(Style\Alignment::VERTICAL_CENTER);
    	} 
    	elseif ($value=='bottom') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical(Style\Alignment::VERTICAL_BOTTOM);
    	}
    	elseif ($value=='top') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical(Style\Alignment::VERTICAL_TOP);
    	}
    	elseif ($value=='justify') 
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical(Style\Alignment::VERTICAL_JUSTIFY);
    	}
    	else
    	{
    		return $this->objset->getStyle($cell)->getAlignment()->setVertical($value);
    	}
    }

    /**
	 * jika input angka maka akan menjadi huruf atau sebaliknya
	 * @param string atau int
	*/
	
    function getNameFromNumber($num = '') 
    {
        return ($num !='') ?  Cell\Coordinate::stringFromColumnIndex($num) : '';
    }

    /**
	 * jika input angka maka akan menjadi huruf atau sebaliknya
	 * @param string atau int
	*/
    public static function siConvert($value='')
    {
    	if (is_int($value))
    	{
    		return ($value !='') ?  Cell\Coordinate::stringFromColumnIndex($value) : '';
    	}
    	else
    	{
    		return Cell\Coordinate::columnIndexFromString($value);
    	}
    }

	function hiddenSheet($data=null)
	{
		if ($data !=null)
		{
			if (is_array($data))
			{
				foreach ($data as $key => $value)
				{
					$this->spreadsheet->getSheetByName($value)->setSheetState(Worksheet\Worksheet::SHEETSTATE_HIDDEN);
				}
			}
			else
			{
				$this->spreadsheet->getSheetByName($data)->setSheetState(Worksheet\Worksheet::SHEETSTATE_HIDDEN);
			}
		}else{
			return false;
		}
	}

	function veryHiddenSheet($data=null)
	{
		if ($data !=null)
		{
			if (is_array($data))
			{
				foreach ($data as $key => $value) 
				{
					$this->spreadsheet->getSheetByName($value)->setSheetState(Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
				}
			}
			else
			{
				$this->spreadsheet->getSheetByName($data)->setSheetState(Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);
			}
		}
		else
		{
			return false;
		}
	}

	function visibleSheet($data=null)
	{
		if ($data !=null) {
			if (is_array($data)) {
				foreach ($data as $key => $value) {
					$this->spreadsheet->getSheetByName($value)->setSheetState(Worksheet\Worksheet::SHEETSTATE_VISIBLE);
				}
			}else{
				$this->spreadsheet->getSheetByName($data)->setSheetState(Worksheet\Worksheet::SHEETSTATE_VISIBLE);
			}
		}else{
			return false;
		}
	}

	function setWidthCol($data='',$at='')
	{
		if (is_array($data)) {
			if (self::countDim($data)>1) {
				foreach ($data as $key => $value) {
					$this->spreadsheet->getActiveSheet()->getColumnDimension($value['col'])->setWidth($value['width']);
				}
			}else{
				$v = array_count_values($data);
				if (count($v)==1) {
					$explode = explode('-', $data[0]);
					if (isset($explode[1]) && count($data)==1) {
						foreach(range($explode[0],$explode[1]) as $columnID) {
						    $this->spreadsheet->getActiveSheet()->getColumnDimension($columnID)
						        ->setWidth($at);
						}
					}else{
						foreach ($data as $key => $value) {
							$this->spreadsheet->getActiveSheet()->getColumnDimension($value)->setWidth($at);
						}
					}
				}else{
					foreach ($data as $key => $value) {
						$this->spreadsheet->getActiveSheet()->getColumnDimension($value)->setWidth($at);
					}
				}
			}
		}else{
			return false;
		}
	}

	function setStaticMerge($data='')
	{
		if ($data!='') {
			if (is_array($data)) {
				if (self::countDim($data)>1) {

				}else{
					$st = explode('-', $data['col']);
					$this->objset->mergeCells('L6:L5');
					foreach(range($st[0],$st[1]) as $columnID) {}
				}
			}else{

			}
		}else{
			return false;
		}
	}

	/**
		* @param array
		* @param sting
		* 'sheetTitle' => 'sheetTitle',
		* 'labels' => ['$R$5:$Z$5'], atau ['R5:Z5'] atau ['sheetTittle!$R$5:$Z$5']
		* 'labels' => ['$R$5'], atau [R5] atau ['sheetTitle!$R$5']
		* 'labels' => '$R$5', atau R5  atau 'sheetTitle!$R$5'
		* 'labels' => [
		* 	['cell' => '$R$5'], atau ['cell' => '!$R$5', 'type' => 'string']
		* ]

		* 'categories' 		=> '$S$3:$AD$4',
		* 'values' 			=> '$S$5:$AD$5',
		* 'title' 		    => 'Grafik Monitoring Tindak Lanjut',
		* 'xLabel'			=> '',
		* 'yLabel'			=> 'Jumlah',
		* 'positionLegend'	=> 'bottom',
		* 'name'			=> '',
		* 'type'			=> 'barChart',
		* 'grouping'		=> 'clustered',
		* 'positionChart' 	=> 'B10:K20' atau setTopLeftPosition, setBottomRightPosition

		Keterangan :
			untuk key categories & values value sama dengan labels
			
		chart([
			'labels' 		=> @param array || string,
			'categories' 	=> @param array || string,
			'values' 		=> @param array || string,
			'title' 		=> @param string,
			'xLabel'		=> @param string,
			'yLabel'		=> @param string,
			'positionLegend'=> @param string,
			'name'			=> @param string,
			'type'			=> @param string,
			'grouping'		=> @param string,
			'positionChart'	=> $param string
		]);
	*/

	function chart($config=[])
	{
		$this->include_chart = true;
		$sheet_title = '';
		if (isset($config['sheetTitle']) && !empty($config['sheetTitle']))
		{
			$sheet_title = $config['sheetTitle'];
		}
		else
		{
			$sheet_title = (($this->sheet_title) ? $this->sheet_title : 'Worksheet');
		}
		// Set the Labels for each data series we want to plot
		//     Datatype
		//     Cell reference for data
		//     Format Code
		//     Number of datapoints in series
		//     Data values
		//     Data Marker
		$labels = self::dataSeries($config['labels'], $sheet_title);
		// Set the X-Axis Labels
		//     Datatype
		//     Cell reference for data
		//     Format Code
		//     Number of datapoints in series
		//     Data values
		//     Data Marker
		$categories = self::dataSeries($config['categories'], $sheet_title);
		// Set the Data values for each data series we want to plot
		//     Datatype
		//     Cell reference for data
		//     Format Code
		//     Number of datapoints in series
		//     Data values
		//     Data Marker
		$values = self::dataSeries($config['values'],'Worksheet','Number');

		// Build the dataseries
		$type   = (isset($config['type']) && !empty($config['type'])) ? $config['type'] : 'lineChart';
		$series = new DataSeries(
		    $this->chartType($type), // DataSeries::TYPE_BARCHART, // plotType
		    (isset($value['grouping']) && !empty($value['grouping'])) ? $this->plotGrouping($value['grouping']) : NULL, // DataSeries::GROUPING_CLUSTERED, // plotGrouping
		    range(0, count($values) - 1), 	// plotOrder
		    $labels, 						// plotLabel
		    $categories, 					// plotCategory
		    $values 						// plotValues
		);
		// Set additional dataseries parameters
		//     Make it a vertical column rather than a horizontal bar graph
		$series->setPlotDirection(DataSeries::DIRECTION_COL);

		// Set the series in the plot area
		$plotArea = new PlotArea(null, [$series]);
		// Set the chart legend
		$position = (isset($config['positionLegend']) && !empty($config['positionLegend'])) ? substr(strtolower($config['positionLegend']), 0, 1) : 'b';
		$legend = new Legend($position, null, false);

		$title = null; $xLabel = null; $yLabel = null;
		if (isset($config['title']) && !empty($config['title'])) {
			$title = new Title($config['title']);
		}
		if (isset($config['xLabel']) && !empty($config['xLabel'])) {
			$xLabel = new Title($config['xLabel']);
		}
		if (isset($config['yLabel']) && !empty($config['yLabel'])) {
			$yLabel = new Title($config['yLabel']);
		}

		// Create the chart
		$chart = new Chart(
		    ((isset($config['name']) && !empty($config['name'])) ? $config['name'] : 'chart'), // name
		    $title, 	// title
		    $legend, 	// legend
		    $plotArea, 	// plotArea
		    true, 		// plotVisibleOnly
		    DataSeries::EMPTY_AS_GAP, // displayBlanksAs
		    $xLabel, 	// xLabel
		    $yLabel  	// yLabel
		);

		// Set the position where the chart should appear in the worksheet
		$top_left; $bottom_right;
		if (isset($config['positionChart']) && !empty($config['positionChart'])) {
			$dd = explode(':', $config['positionChart']);
			$top_left 		= $dd[0];
			$bottom_right 	= $dd[1];
		}else{
			$top_left 		= $config['setTopLeftPosition'];
			$bottom_right 	= $config['setBottomRightPosition'];
		}
		$chart->setTopLeftPosition($top_left);
		$chart->setBottomRightPosition($bottom_right);

		// Add the chart to the worksheet
		$this->objset->addChart($chart);

		return $this;
	}

	# TOOLS

	protected static function convertCellChart($value='')
	{
		$spser = explode('!', $value);
		$value = (count($spser)>1) ? $spser[1] : $value;
		$cek_colspane = explode(':', $value);
		$cell = '';
		if (count($cek_colspane)>1)
		{
			$cell_sub = '';
			foreach ($cek_colspane as $key_spane => $value_spane)
			{
				$is_dolar = substr($value_spane, 0, 1);
				if ($is_dolar == '$')
				{
					$cell_sub .= $value_spane.':';
				}
				else
				{
					$si = self::siSplit($value_spane);
					$cell_sub .= '$'.$si[0].'$'.$si[1].':';
				}
			}
			$cell = substr($cell_sub, 0, -1);
		}
		else
		{
			$is_dolar = substr($value, 0, 1);
			if ($is_dolar == '$')
			{
				$cell .= $value;
			}
			else
			{
				$si = self::siSplit($value);
				$cell .= '$'.$si[0].'$'.$si[1];
			}
		}
		return [
			'cell' => (count($spser)>1) ? $spser[0].'!'.$cell : $cell,
			'is_worksheet' => (count($spser)>1) ? true : false,
		];
	}

	protected static function dataSeries($config_series, $worksheet='Worksheet', $data_type = 'String')
	{
		$return = [];
		if (isset($config_series) && !empty($config_series))
		{
			if (is_array($config_series))
			{
				if (self::isArrayMulti($config_series))
				{
					foreach ($config_series as $key => $value)
					{
						$cell = self::convertCellChart($value['cell']);
						$type = (isset($value['type']) && $value['type']) ? ucfirst($value['type']) : DataSeriesValues::DATASERIES_TYPE_STRING;
						$worksheet_cell = ($cell['is_worksheet']) ? $cell['cell'] : $worksheet.'!'.$cell;
						array_push($return, new DataSeriesValues($type, $worksheet_cell, null, 1));
					}
				}
				else
				{
					foreach ($config_series as $key => $value)
					{
						$cell = self::convertCellChart($value);
						$worksheet_cell = ($cell['is_worksheet']) ? $cell['cell'] : $worksheet.'!'.$cell['cell'];
						array_push($return, new DataSeriesValues($data_type, $worksheet_cell, null, 1));
					}
				}
			}
			else
			{
				$cell = self::convertCellChart($config_series);
				$worksheet_cell = ($cell['is_worksheet']) ? $cell['cell'] : $worksheet.'!'.$cell['cell'];
				array_push($return, new DataSeriesValues($data_type, $worksheet_cell, null, 1));
			}
		}
		return $return;
	}

	function plotGrouping($type)
	{
		$data = [
			strtolower('standart')		=> DataSeries::GROUPING_STANDARD,
			strtolower('stacked')		=> DataSeries::GROUPING_STACKED,
			strtolower('percentStacked')=> DataSeries::GROUPING_PERCENT_STACKED,
			strtolower('clustered')		=> DataSeries::GROUPING_CLUSTERED,
		];
		return $data[strtolower($type)];
	}

	/**
	 * type chart
	 * @param string
	*/
	function chartType($type)
	{
		$type_chart = [
			strtolower('barChart') 		 => DataSeries::TYPE_BARCHART,
		    strtolower('bar3DChart') 	 => DataSeries::TYPE_BARCHART_3D,
		    strtolower('lineChart') 	 => DataSeries::TYPE_LINECHART,
		    strtolower('line3DChart') 	 => DataSeries::TYPE_LINECHART_3D,
		    strtolower('areaChart') 	 => DataSeries::TYPE_AREACHART,
		    strtolower('area3DChart') 	 => DataSeries::TYPE_AREACHART_3D,
		    strtolower('pieChart') 		 => DataSeries::TYPE_PIECHART,
		    strtolower('pie3DChart') 	 => DataSeries::TYPE_PIECHART_3D,
		    // strtolower('doughnutChart')  => DataSeries::TYPE_DOUGHTNUTCHART,
		    strtolower('scatterChart') 	 => DataSeries::TYPE_SCATTERCHART,
		    strtolower('surfaceChart') 	 => DataSeries::TYPE_SURFACECHART,
		    strtolower('surface3DChart') => DataSeries::TYPE_SURFACECHART_3D,
		    strtolower('radarChart') 	 => DataSeries::TYPE_RADARCHART,
		    strtolower('bubbleChart') 	 => DataSeries::TYPE_BUBBLECHART,
		    strtolower('stockChart') 	 => DataSeries::TYPE_STOCKCHART,
		];
		return $type_chart[strtolower($type)];
	}

	/**
	 * set paper
	 * @param string
	*/
	protected static function getPaperSize($value=''){
		if(strtoupper($value) == 'LETTER') {return Worksheet\PageSetup::PAPERSIZE_LETTER;}
		elseif(strtoupper($value) == 'LETTER_SMALL') {return Worksheet\PageSetup::PAPERSIZE_LETTER_SMALL;}
		elseif(strtoupper($value) == 'TABLOID') {return Worksheet\PageSetup::PAPERSIZE_TABLOID;}
		elseif(strtoupper($value) == 'LEDGER') {return Worksheet\PageSetup::PAPERSIZE_LEDGER;}
		elseif(strtoupper($value) == 'LEGAL') {return Worksheet\PageSetup::PAPERSIZE_LEGAL;}
		elseif(strtoupper($value) == 'STATEMENT') {return Worksheet\PageSetup::PAPERSIZE_STATEMENT;}
		elseif(strtoupper($value) == 'EXECUTIVE') {return Worksheet\PageSetup::PAPERSIZE_EXECUTIVE;}
		elseif(strtoupper($value) == 'A3') {return Worksheet\PageSetup::PAPERSIZE_A3;}
		elseif(strtoupper($value) == 'A4') {return Worksheet\PageSetup::PAPERSIZE_A4;}
		elseif(strtoupper($value) == 'A4_SMALL') {return Worksheet\PageSetup::PAPERSIZE_A4_SMALL;}
		elseif(strtoupper($value) == 'A5') {return Worksheet\PageSetup::PAPERSIZE_A5;}
		elseif(strtoupper($value) == 'B4') {return Worksheet\PageSetup::PAPERSIZE_B4;}
		elseif(strtoupper($value) == 'B5') {return Worksheet\PageSetup::PAPERSIZE_B5;}
		elseif(strtoupper($value) == 'FOLIO') {return Worksheet\PageSetup::PAPERSIZE_FOLIO;}
		elseif(strtoupper($value) == 'QUARTO') {return Worksheet\PageSetup::PAPERSIZE_QUARTO;}
		elseif(strtoupper($value) == 'STANDARD_1') {return Worksheet\PageSetup::PAPERSIZE_STANDARD_1;}
		elseif(strtoupper($value) == 'STANDARD_2') {return Worksheet\PageSetup::PAPERSIZE_STANDARD_2;}
		elseif(strtoupper($value) == 'NOTE') {return Worksheet\PageSetup::PAPERSIZE_NOTE;}
		elseif(strtoupper($value) == 'NO9_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_NO9_ENVELOPE;}
		elseif(strtoupper($value) == 'NO10_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_NO10_ENVELOPE;}
		elseif(strtoupper($value) == 'NO11_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_NO11_ENVELOPE;}
		elseif(strtoupper($value) == 'NO12_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_NO12_ENVELOPE;}
		elseif(strtoupper($value) == 'NO14_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_NO14_ENVELOPE;}
		elseif(strtoupper($value) == 'C') {return Worksheet\PageSetup::PAPERSIZE_C;}
		elseif(strtoupper($value) == 'D') {return Worksheet\PageSetup::PAPERSIZE_D;}
		elseif(strtoupper($value) == 'E') {return Worksheet\PageSetup::PAPERSIZE_E;}
		elseif(strtoupper($value) == 'DL_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_DL_ENVELOPE;}
		elseif(strtoupper($value) == 'C5_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_C5_ENVELOPE;}
		elseif(strtoupper($value) == 'C3_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_C3_ENVELOPE;}
		elseif(strtoupper($value) == 'C4_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_C4_ENVELOPE;}
		elseif(strtoupper($value) == 'C6_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_C6_ENVELOPE;}
		elseif(strtoupper($value) == 'C65_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_C65_ENVELOPE;}
		elseif(strtoupper($value) == 'B4_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_B4_ENVELOPE;}
		elseif(strtoupper($value) == 'B5_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_B5_ENVELOPE;}
		elseif(strtoupper($value) == 'B6_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_B6_ENVELOPE;}
		elseif(strtoupper($value) == 'ITALY_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_ITALY_ENVELOPE;}
		elseif(strtoupper($value) == 'MONARCH_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_MONARCH_ENVELOPE;}
		elseif(strtoupper($value) == '6_3_4_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_6_3_4_ENVELOPE;}
		elseif(strtoupper($value) == 'US_STANDARD_FANFOLD') {return Worksheet\PageSetup::PAPERSIZE_US_STANDARD_FANFOLD;}
		elseif(strtoupper($value) == 'GERMAN_STANDARD_FANFOLD') {return Worksheet\PageSetup::PAPERSIZE_GERMAN_STANDARD_FANFOLD;}
		elseif(strtoupper($value) == 'GERMAN_LEGAL_FANFOLD') {return Worksheet\PageSetup::PAPERSIZE_GERMAN_LEGAL_FANFOLD;}
		elseif(strtoupper($value) == 'ISO_B4') {return Worksheet\PageSetup::PAPERSIZE_ISO_B4;}
		elseif(strtoupper($value) == 'JAPANESE_DOUBLE_POSTCARD') {return Worksheet\PageSetup::PAPERSIZE_JAPANESE_DOUBLE_POSTCARD;}
		elseif(strtoupper($value) == 'STANDARD_PAPER_1') {return Worksheet\PageSetup::PAPERSIZE_STANDARD_PAPER_1;}
		elseif(strtoupper($value) == 'STANDARD_PAPER_2') {return Worksheet\PageSetup::PAPERSIZE_STANDARD_PAPER_2;}
		elseif(strtoupper($value) == 'STANDARD_PAPER_3') {return Worksheet\PageSetup::PAPERSIZE_STANDARD_PAPER_3;}
		elseif(strtoupper($value) == 'INVITE_ENVELOPE') {return Worksheet\PageSetup::PAPERSIZE_INVITE_ENVELOPE;}
		elseif(strtoupper($value) == 'LETTER_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_LETTER_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'LEGAL_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'TABLOID_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_TABLOID_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'A4_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A4_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'LETTER_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_LETTER_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'A4_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A4_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'LETTER_EXTRA_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_LETTER_EXTRA_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'SUPERA_SUPERA_A4_PAPER') {return Worksheet\PageSetup::PAPERSIZE_SUPERA_SUPERA_A4_PAPER;}
		elseif(strtoupper($value) == 'SUPERB_SUPERB_A3_PAPER') {return Worksheet\PageSetup::PAPERSIZE_SUPERB_SUPERB_A3_PAPER;}
		elseif(strtoupper($value) == 'LETTER_PLUS_PAPER') {return Worksheet\PageSetup::PAPERSIZE_LETTER_PLUS_PAPER;}
		elseif(strtoupper($value) == 'A4_PLUS_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A4_PLUS_PAPER;}
		elseif(strtoupper($value) == 'A5_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A5_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'JIS_B5_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_JIS_B5_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'A3_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A3_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'A5_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A5_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'ISO_B5_EXTRA_PAPER') {return Worksheet\PageSetup::PAPERSIZE_ISO_B5_EXTRA_PAPER;}
		elseif(strtoupper($value) == 'A2_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A2_PAPER;}
		elseif(strtoupper($value) == 'A3_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A3_TRANSVERSE_PAPER;}
		elseif(strtoupper($value) == 'A3_EXTRA_TRANSVERSE_PAPER') {return Worksheet\PageSetup::PAPERSIZE_A3_EXTRA_TRANSVERSE_PAPER;}
		else{
			return $value;
		}
	}

	protected static function setCellFormatType($value='')
    {
		if(strtoupper($value)==strtoupper('BOOL')) { return DataType::TYPE_BOOL; }
		else if(strtoupper($value)==strtoupper('ERROR')) { return DataType::TYPE_ERROR; }
		else if(strtoupper($value)==strtoupper('FORMULA')) { return DataType::TYPE_FORMULA; }
		else if(strtoupper($value)==strtoupper('INLINE')) { return DataType::TYPE_INLINE; }
		else if(strtoupper($value)==strtoupper('NULL')) { return DataType::TYPE_NULL; }
		else if(strtoupper($value)==strtoupper('NUMERIC')) { return DataType::TYPE_NUMERIC; }
		else if(strtoupper($value)==strtoupper('STRING')) { return DataType::TYPE_STRING; }
		else if(strtoupper($value)==strtoupper('STRING2')) { return DataType::TYPE_STRING2; }else{
			return $value;
		}
    }

    function numberFormat($value='')
    {
    	if(strtoupper($value)==strtoupper('CURRENCY_EUR_SIMPLE')) { return Style\NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE; }
		else if(strtoupper($value)==strtoupper('CURRENCY_USD')) { return Style\NumberFormat::FORMAT_CURRENCY_USD; }
		else if(strtoupper($value)==strtoupper('CURRENCY_USD_SIMPLE')) { return Style\NumberFormat::FORMAT_CURRENCY_USD_SIMPLE; }
		else if(strtoupper($value)==strtoupper('DATE_DATETIME')) { return Style\NumberFormat::FORMAT_DATE_DATETIME; }
		else if(strtoupper($value)==strtoupper('DATE_DDMMYYYY')) { return Style\NumberFormat::FORMAT_DATE_DDMMYYYY; }
		else if(strtoupper($value)==strtoupper('DATE_DMMINUS')) { return Style\NumberFormat::FORMAT_DATE_DMMINUS; }
		else if(strtoupper($value)==strtoupper('DATE_DMYMINUS')) { return Style\NumberFormat::FORMAT_DATE_DMYMINUS; }
		else if(strtoupper($value)==strtoupper('DATE_DMYSLASH')) { return Style\NumberFormat::FORMAT_DATE_DMYSLASH; }
		else if(strtoupper($value)==strtoupper('DATE_MYMINUS')) { return Style\NumberFormat::FORMAT_DATE_MYMINUS; }
		else if(strtoupper($value)==strtoupper('DATE_TIME1')) { return Style\NumberFormat::FORMAT_DATE_TIME1; }
		else if(strtoupper($value)==strtoupper('DATE_TIME2')) { return Style\NumberFormat::FORMAT_DATE_TIME2; }
		else if(strtoupper($value)==strtoupper('DATE_TIME3')) { return Style\NumberFormat::FORMAT_DATE_TIME3; }
		else if(strtoupper($value)==strtoupper('DATE_TIME4')) { return Style\NumberFormat::FORMAT_DATE_TIME4; }
		else if(strtoupper($value)==strtoupper('DATE_TIME5')) { return Style\NumberFormat::FORMAT_DATE_TIME5; }
		else if(strtoupper($value)==strtoupper('DATE_TIME6')) { return Style\NumberFormat::FORMAT_DATE_TIME6; }
		else if(strtoupper($value)==strtoupper('DATE_TIME7')) { return Style\NumberFormat::FORMAT_DATE_TIME7; }
		else if(strtoupper($value)==strtoupper('DATE_TIME8')) { return Style\NumberFormat::FORMAT_DATE_TIME8; }
		else if(strtoupper($value)==strtoupper('DATE_XLSX14')) { return Style\NumberFormat::FORMAT_DATE_XLSX14; }
		else if(strtoupper($value)==strtoupper('DATE_XLSX15')) { return Style\NumberFormat::FORMAT_DATE_XLSX15; }
		else if(strtoupper($value)==strtoupper('DATE_XLSX16')) { return Style\NumberFormat::FORMAT_DATE_XLSX16; }
		else if(strtoupper($value)==strtoupper('DATE_XLSX17')) { return Style\NumberFormat::FORMAT_DATE_XLSX17; }
		else if(strtoupper($value)==strtoupper('DATE_XLSX22')) { return Style\NumberFormat::FORMAT_DATE_XLSX22; }
		else if(strtoupper($value)==strtoupper('DATE_YYYYMMDD')) { return Style\NumberFormat::FORMAT_DATE_YYYYMMDD; }
		else if(strtoupper($value)==strtoupper('DATE_YYYYMMDD2')) { return Style\NumberFormat::FORMAT_DATE_YYYYMMDD2; }
		else if(strtoupper($value)==strtoupper('DATE_YYYYMMDDSLASH')) { return Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH; }
		else if(strtoupper($value)==strtoupper('GENERAL')) { return Style\NumberFormat::FORMAT_GENERAL; }
		else if(strtoupper($value)==strtoupper('TEXT')) { return Style\NumberFormat::FORMAT_TEXT; }
		else if(strtoupper($value)==strtoupper('NUMBER')) { return Style\NumberFormat::FORMAT_NUMBER; }
		else if(strtoupper($value)==strtoupper('NUMBER_00')) { return Style\NumberFormat::FORMAT_NUMBER_00; }
		else if(strtoupper($value)==strtoupper('NUMBER_COMMA_SEPARATED1')) { return Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1; }
		else if(strtoupper($value)==strtoupper('NUMBER_COMMA_SEPARATED2')) { return Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2; }
		else if(strtoupper($value)==strtoupper('PERCENTAGE')) { return Style\NumberFormat::FORMAT_PERCENTAGE; }
		else if(strtoupper($value)==strtoupper('PERCENTAGE_00')) { return Style\NumberFormat::FORMAT_PERCENTAGE_00; }
		else if(strtoupper($value)==strtoupper('CURRENCY_EUR')) { return Style\NumberFormat::FORMAT_CURRENCY_EUR; }
			else{return $value;}
    }

    function getBorder($value='')
    {
    	if (strtolower($value)==strtolower('DASHDOT')) {
    		return Style\Border::BORDER_DASHDOT;
    	}elseif (strtolower($value)==strtolower('DASHDOTDOT')) {
    		return Style\Border::BORDER_DASHDOTDOT;
    	}elseif (strtolower($value)==strtolower('DASHED')) {
    		return Style\Border::BORDER_DASHED;
    	}elseif (strtolower($value)==strtolower('DOTTED')) {
    		return Style\Border::BORDER_DOTTED;
    	}elseif (strtolower($value)==strtolower('DOUBLE')) {
    		return Style\Border::BORDER_DOUBLE;
    	}elseif (strtolower($value)==strtolower('HAIR')) {
    		return Style\Border::BORDER_HAIR;
    	}elseif (strtolower($value)==strtolower('MEDIUM')) {
    		return Style\Border::BORDER_MEDIUM;
    	}elseif (strtolower($value)==strtolower('MEDIUMDASHDOT')) {
    		return Style\Border::BORDER_MEDIUMDASHDOT;
    	}elseif (strtolower($value)==strtolower('MEDIUMDASHDOTDOT')) {
    		return Style\Border::BORDER_MEDIUMDASHDOTDOT;
    	}elseif (strtolower($value)==strtolower('MEDIUMDASHED')) {
    		return Style\Border::BORDER_MEDIUMDASHED;
    	}elseif (strtolower($value)==strtolower('NONE')) {
    		return Style\Border::BORDER_NONE;
    	}elseif (strtolower($value)==strtolower('SLANTDASHDOT')) {
    		return Style\Border::BORDER_SLANTDASHDOT;
    	}elseif (strtolower($value)==strtolower('THICK')) {
    		return Style\Border::BORDER_THICK;
    	}elseif (strtolower($value)==strtolower('THIN')) {
    		return Style\Border::BORDER_THIN;
    	}
    }

    function getFill($value='')
    {
    	if (strtolower($value)==strtolower('LINIER')) {
    		return Style\Fill::FILL_GRADIENT_LINEAR;
    	} else if (strtolower($value)==strtolower('PATH')) {
    		return Style\Fill::FILL_GRADIENT_PATH;
    	} else if (strtolower($value)==strtolower('NONE')) {
    		return Style\Fill::FILL_NONE;
    	} else if (strtolower($value)==strtolower('DARKDOWN')) {
    		return Style\Fill::FILL_PATTERN_DARKDOWN;
    	} else if (strtolower($value)==strtolower('DARKGRAY')) {
    		return Style\Fill::PATTERN_DARKGRAY;
    	} else if (strtolower($value)==strtolower('DARKGRID')) {
    		return Style\Fill::FILL_PATTERN_DARKGRID;
    	} else if (strtolower($value)==strtolower('DARKHORIZONTAL')) {
    		return Style\Fill::FILL_PATTERN_DARKHORIZONTAL;
    	} else if (strtolower($value)==strtolower('DARKTRELLIS')) {
    		return Style\Fill::FILL_PATTERN_DARKTRELLIS;
    	} else if (strtolower($value)==strtolower('DARKUP')) {
    		return Style\Fill::FILL_PATTERN_DARKUP;
    	} else if (strtolower($value)==strtolower('DARKVERTICAL')) {
    		return Style\Fill::FILL_PATTERN_DARKVERTICAL;
    	} else if (strtolower($value)==strtolower('GRAY0625')) {
    		return Style\Fill::FILL_PATTERN_GRAY0625;
    	} else if (strtolower($value)==strtolower('GRAY125')) {
    		return Style\Fill::FILL_PATTERN_GRAY125;
    	} else if (strtolower($value)==strtolower('LIGHTDOWN')) {
    		return Style\Fill::FILL_PATTERN_LIGHTDOWN;
    	} else if (strtolower($value)==strtolower('LIGHTGRAY')) {
    		return Style\Fill::FILL_PATTERN_LIGHTGRAY;
    	} else if (strtolower($value)==strtolower('LIGHTGRID')) {
    		return Style\Fill::FILL_PATTERN_LIGHTGRID;
    	} else if (strtolower($value)==strtolower('LIGHTTRELLIS')) {
    		return Style\Fill::FILL_PATTERN_LIGHTTRELLIS;
    	} else if (strtolower($value)==strtolower('LIGHTHORIZONTAL')) {
    		return Style\Fill::FILL_PATTERN_LIGHTHORIZONTAL;
    	} else if (strtolower($value)==strtolower('LIGHTUP')) {
    		return Style\Fill::FILL_PATTERN_LIGHTUP;
    	} else if (strtolower($value)==strtolower('LIGHTVERTICAL')) {
    		return Style\Fill::FILL_PATTERN_LIGHTVERTICAL;
    	} else if (strtolower($value)==strtolower('MEDIUMGRAY')) {
    		return Style\Fill::FILL_PATTERN_MEDIUMGRAY;
    	} else if (strtolower($value)==strtolower('SOLID')) {
    		return Style\Fill::FILL_SOLID;
    	}else{
    		return $value;
    	}
    }

	protected function _where($key, $value = NULL)
	{
        $ar_where = array();
        if ( ! is_array($key))
        {
            $key = array($key => $value);
        }

        foreach ($key as $k => $v)
        {
            if( $this->_has_operator($k) )
            {
                $k = explode(' ', trim($k));
                if($k[0]){
                    // $k[0] = $this->map_fields_in($k[0]);
                }
                $k = implode(' ', $k);
            }
            else
            {
                // $k = $this->map_fields_in($k);
            }

            if (is_null($v) && ! $this->_has_operator($k))
            {
                $k .= ' IS NULL';
            }
            if ( ! is_null($v) and ! $this->_has_operator($k))
            {
                $k .= ' = ';
            }
            if (is_string($v) and !is_null($v))
            {
                $v = "'".$v."'";
            }
            if (is_int($v) && !is_null($v))
            {
                $v = "'".$v."'";
            }
            $ar_where[] = $k.$v;
        }
        return implode(' AND ', $ar_where);
    }

    function removeValueEmpty($data)
	{
		$return = array();
		foreach ($data as $key => $value)
		{
			if ($value!='')
			{
				array_push($return, $value);
			}
		}
		return array_values($return);
	}

	function generateRandomString($length = 10)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function getUUID()
	{
		if (!$this->db) {
			$this->db = new ExcelConnection(null, null, null, null);
		}
		$random = generateRandomString();
		$res_id = $this->db->query("SELECT MD5(UUID()) as ID")->resultArray();
		$gen_id = $res_id[0]['ID'];
		return md5($random.$gen_id['ID']);
	}

    protected function _has_operator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str))
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

	static function isArrayMulti($arr)
    {
    	$multi = false;
    	foreach ($arr as $key => $value)
    	{
    		if (is_array($value))
    		{
    			$multi = true;
    		}
    		else
    		{
    			$multi = false;
    		}
    	}
    	return $multi;
    }

	static function siSplit($value='')
    {
		$pattern = "/(\d+)/";
		return preg_split($pattern, $value, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

    static function isAssoc($arr)
	{
	    // Is it set, is an array, not empty and keys are not sequentialy numeric from 0
	    return isset($arr) && is_array($arr) && count($arr)!=0 && array_keys($arr) !== range(0, count($arr) - 1);
	}

	static function countDim($array)
	{
	   if (is_array(reset($array)))
	    {
	    	$return = self::countDim(reset($array)) + 1;
	    }
	    else
	    {
	    	$return = 1;
	    }
	    return $return;
	}

	protected static function setInput($value='', $input='')
	{
		if ($value === '' || is_null($value))
		{
			return ($input == '') ? 1 : $input;			
		}
		else
		{
			return $value;
		}
	}

	function removeIndexNo($no){
		$return = false;
		$no = preg_replace('/\s+/', '', $no);
		if ($no == 'No') {
			$return = true;
		}else if ($no == 'no') {
			$return = true;
		}else if ($no == 'NO') {
			$return = true;
		}else if ($no == 'No.') {
			$return = true;
		}else if ($no == 'no.') {
			$return = true;
		}else if ($no == 'NO.') {
			$return = true;
		}
		return $return;
	}

	function removeNull($data){
        $return = array();
        foreach ($data as $key => $value) {
            $ck = 0;
            foreach ($value as $key_2 => $value_2) {
                if ($value_2!="") {
                    $ck +=1;
                }
            }
            if ($ck>0) {
                array_push($return, $value);
            }
        }
        return $return;
    }

    protected static function toMb($value){
		return (float) $value * 1000;
	}

	function _rmvQ($array=array())
	{
		$_return = array();
		foreach($array as $key => $value){
			$_push = array();
			foreach ($value as $key2 => $value2) {
				if ($key2 !='') {
					$_push[$key2] = "'".$value2."'";
				}
			}
			array_push($_return, $_push);
		}       
		return $_return;
	}

	function conBorder($value='')
	{
		$thick = '';
		if ($value=='1px') 
		{
			$thick = 'thin';
		}
		else if ($value=='2px') 
		{
			$thick = 'medium';
 		}
 		else
 		{
 			$thick = $value;
		}
		return $this->getBorder($thick);
	}

	function checkBorder($value='')
	{
		$check = ['default','allborders','diagonal','inside','outline','horizontal','top','left','vertical','bottom','right'];
		$return = false;
		foreach ($check as $_value)
		{
			if ($_value==$value)
			{
				$return = true;
			}
		}
		return $return;
	}

	function borderProperties($data='',$type_border='')
	{
		$_style = [];
		if (!empty($data)) {
			$explode = explode(' ', $data);
			$explode = array_values(array_diff($explode, array('')));
			if (count($explode)>0) {
				$prop = [];
				foreach ($explode as $key => $value) {
					$act = self::_isBorderProperties($value);
					if ($type_border=='border') {
						if (isset($act['detect']) && $act['detect']=='type') {
							$prop['type'] = $act['value'];
						}
					}else{
						$prop['type'] = $type_border;
					}
					if (isset($act['detect']) && $act['detect']=='style') {
						$prop['style'] = $act['value'];
					}
					if (isset($act['detect']) && $act['detect']=='color') {
						$prop['color'] = $act['value'];
					}
					if (isset($act['detect']) && $act['detect']=='diagonaldirection') {
						$prop['diagonaldirection'] = $act['value'];
					}
				}
				// print_r($prop);
				$type  = (isset($prop['type']) ? $prop['type'] : 'allBorders');
				$style = (isset($prop['style']) ? $prop['style'] : 'thin');
				$color = (isset($prop['color']) ? $prop['color'] : null);
				$diagonaldirection = (isset($prop['diagonaldirection']) ? $prop['diagonaldirection'] : 'none');
			}else{

			}
		}
		return [
			'type' => $type,
			'style' => $style,
			'color' => $color,
			'diagonaldirection' => $diagonaldirection
		];
	}

	protected static function _isBorderProperties($value='')
	{
		$return = [];
		$data 	= [
			[ 'detect'	=> 'type',	'name'  => 'default'],
			[ 'detect'	=> 'type', 	'name'  => 'allborders'],
			[ 'detect'	=> 'type', 	'name'  => 'diagonal'],
			[ 'detect'	=> 'type', 	'name'  => 'inside'],
			[ 'detect'	=> 'type', 	'name'  => 'outline'],
			[ 'detect'	=> 'type', 	'name'  => 'horizontal'],
			[ 'detect'	=> 'type', 	'name'  => 'top'],
			[ 'detect'	=> 'type', 	'name'  => 'left'],
			[ 'detect'	=> 'type', 	'name'  => 'vertical'],
			[ 'detect'	=> 'type', 	'name'  => 'bottom'],
			[ 'detect'	=> 'type', 	'name'  => 'right'],
			[ 'detect'	=> 'type', 	'name'  => 'border-top'],
			[ 'detect'	=> 'type', 	'name'  => 'border-left'],
			[ 'detect'	=> 'type', 	'name'  => 'border-bottom'],
			[ 'detect'	=> 'type', 	'name'  => 'border-right'],
			[ 'detect'	=> 'style', 'name'  => '1px'],
			[ 'detect'	=> 'style', 'name'  => '2px'],
			[ 'detect'  => 'style', 'name'  => 'dashdot'],
			[ 'detect'  => 'style', 'name'  => 'dashdotdot'],
			[ 'detect'  => 'style', 'name'  => 'dashhed'],
			[ 'detect'  => 'style', 'name'  => 'dotted'],
			[ 'detect'  => 'style', 'name'  => 'double'],
			[ 'detect'  => 'style', 'name'  => 'hair'],
			[ 'detect'  => 'style', 'name'  => 'medium'],
			[ 'detect'  => 'style', 'name'  => 'mediumdashdot'],
			[ 'detect'  => 'style', 'name'  => 'mediumdashdotdot'],
			[ 'detect'  => 'style', 'name'  => 'mediumdashed'],
			[ 'detect'  => 'style', 'name'  => 'none'],
			[ 'detect'  => 'style', 'name'  => 'slantdashdot'],
			[ 'detect'  => 'style', 'name'  => 'thick'],
			[ 'detect'  => 'style', 'name'  => 'thin'],
			[ 'detect'  => 'diagonaldirection', 'name'  => 'up'],
			[ 'detect'  => 'diagonaldirection', 'name'  => 'both'],
			[ 'detect'  => 'diagonaldirection', 'name'  => 'down'],
			[ 'detect'  => 'diagonaldirection', 'name'  => 'none'],
		];
		if (!empty($value)) {
			if ($value[0]=='#') {
				$return = [
					'detect' => 'color',
					'value' => str_replace('#', '', $value),
				];
			}else{
				foreach ($data as $key => $_value) {
					if ($_value['name']==$value) {
						$return = [
							'detect' => $_value['detect'],
							'value'  => $_value['name']
						];
					}
				}
			}
		}
		return $return;
	}

	/**
	* konversi warna
	* @param string
	*/
	protected static function color($value){
		switch ($value)
		{
		    case 'black':
		    	return 'FF000000';
		    	break;
		    case 'white':
		    	return 'FFFFFFFF';
		    	break;
		    case 'red':
		    	return 'FFFF0000';
		    	break;
		    case 'darkred':
		    	return 'FF800000';
		    	break;
		    case 'blue':
		    	return 'FF0000FF';
		    	break;
		    case 'darkblue':
		    	return 'FF000080';
		    	break;
		    case 'green':
		    	return 'FF00FF00';
		    	break;
		    case 'darkgreen':
		    	return 'FF008000';
		    	break;
		    case 'yellow':
		    	return 'FFFFFF00';
		    	break;
		    case 'darkyellow':
		    	return 'FF808000';
		    	break;
		    default :
		    	return $value;
		}
	}

	/**
	 * untuk mengetahui tipe variable atau data
	*/
	private function _gettype($var)
	{
	    if(is_string($var)) return 'string';
	    if(is_float($var)) return 'decimal';
	    if(is_int($var)) return 'integer';
	    return 'undefined';
	}

	/*
		Untuk membuat nomor otomatis jika menggunakan query builder
	*/
	function withNumber($val=false)
	{
		$this->with_num = $val;
		return $this;
	}

}
