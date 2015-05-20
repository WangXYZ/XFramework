<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Loader {

	protected $_helper_path = array();
	protected $_library_path = array();
	protected $_model_path = array();
	public $_class = array();
	public $_model = array();

	/*
	* Class constructor
	*
	* @return     void
	*/
	public function __construct() {
		$this->_helper_path = array(
			XF_PATH.'helper/',
			APP_PATH.C('helper_dir').'/',
		);
		$this->_library_path = array(
			XF_PATH.'library/',
			APP_PATH.C('library_dir').'/',
		);
		$this->_model_path = array(APP_PATH.C('model_dir').'/');
		
		$this->_class =& LoadClass();

		LogWrite("Loader Class Initialized",'debug');
	}

	/*
	* 自动加载
	*
	* @return     void
	*/
	public function _autoload() {
		$this->database(C('autoload.database'));
		$this->helper(C('autoload.helper'));
		$this->library(C('autoload.library'));
		$this->model(C('autoload.model'));
	}

	public function config($str=null) {
		if ( is_array($str) ) {
			foreach ( $str as $v ) {
				$this->config($v);
			}
			return;
		}

		if ( !$str ) {
			return;
		}

		$config =& load_class('Config','core');
		$config->load($str);
	}

	public function lang($str=null) {
		if ( is_array($str) ) {
			foreach ( $str as $v ) {
				$this->lang($v);
			}
			return;
		}

		if ( !$str ) {
			return;
		}

		$lang =& load_class('Lang','core');
		$lang->load($str);
	}

	public function helper($str=null) {
		if ( is_array($str) ) {
			foreach ( $str as $v ) {
				$this->helper($v);
			}
			return;
		}

		if ( !$str ) {
			return;
		}

		$ext = 'php';
		foreach ( array_reverse($this->_helper_path) as $path ) {
			$file = $path.$str.'.'.$ext;
			if ( file_exists($file) ) {
				require_once($file);
				LogWrite("Helper File Loaded: {$file}",'debug');
				return;
			}
		}
		LogWrite("Helper Hile Loaded Error : {$str}.{$ext}",'error');
	}

	// 加载数据库
	public function database($str=null) {
		if ( is_array($str) ) {
			foreach ( $str as $v ) {
				$this->database($v);
			}
			return;
		}

		if ( !$str ) {
			return;
		}

		if ( isset($this->_class[$str]) ) {
			// 已存在对应实例名
			LogWrite("db class name exist : {$str}",'notice');
			return;
		}

		$arr = C('database.'.$str);
		$param = false;

		// 处理数据库连接参数
		if ( is_string($arr) && strpos($arr,'://') === true ) {
			$dns = parse_url($arr);
			if ( $dns === false ) {
				E('err.db_config_invalid');
			}

			$param = array(
				'driver' => $dns['scheme'],
				'host' => (isset($dns['host'])) ? rawurldecode($dns['host']) : '',
				'user' => (isset($dns['user'])) ? rawurldecode($dns['user']) : '',
				'pass' => (isset($dns['pass'])) ? rawurldecode($dns['pass']) : '',
				'name' => (isset($dns['path'])) ? rawurldecode(substr($dns['path'],1)) : '',
			);
			
			if ( isset($dns['query']) ) {
				parse_str($dns['query'],$extra);
				foreach ( $extra as $key => $val ) {
					if ( strtoupper($val) == "true" ) {
						$val = true;
					} else if ( strtoupper($val) == "false" ) {
						$val = false;
					}
					$param[$key] = $val;
				}
			}
		} else if ( is_array($arr) && count($arr) > 0 ) {
			$param = $arr;
		}

		if ( $param === false ) {
			E('err.db_config_invalid',$str);
		}

		// 检查数据库驱动
		if ( !isset($param['driver']) OR $param['driver'] == '') {
			E('err.db_driver_blank');
		}

		// 加载数据库驱动基类
		if ( !class_exists('X_DB',false) ) {
			$file = XF_PATH.'Core/DB.php';
			require_once $file;
		}

		require_once(XF_PATH.'DB/'.$param['driver'].'.php');
	
		$class = 'X_DB_'.$param['driver'];
		$this->_class[$str] = new $class($param);
		LogWrite("Database Driver Class Initialized : {$str}",'debug');
	}

	// 加载模型
	public function model() {

	}

	//加载类库
	public function library($class=null,$param=null,$pre='X_',$name=null) {
		if ( is_array($class) ) {
			foreach ( $class as $val ) {
				$this->library($val,$param,$pre);
			}
			return;
		}

		if ( !$class ) {
			return;
		}

		if ( $class == 'load' OR $class == 'view' ) {
			LogWrite("library class name ({$class}) vilaid!",'debug');
			return false;
		}

		$this->_load_class($class,$param,$pre,$name);
	}

	protected function _load_class($class,$param=null,$pre=null,$object_name=null) {
		// 读取类名，暂时剔除dir不做处理。
		$class = str_replace('.php','',trim($class,'/'));
		if ( strpos($class,'/') ) {
			$class_name = end(explode('/',$class));
		} else {
			$class_name = $class;
		}
		$pre_class_name = $pre.$class_name;

		// 实例的名称
		if ( !$object_name ) {
			$object_name = $class_name;
		}
		
		if ( isset($this->_class[$object_name]) ) {
			LogWrite("Library object name ({$object_name}) exist!",'debug');
			return;
		}

		// 判断是否需要加载类文件
		$bool = false;
		if ( !class_exists($pre_class_name,false) ) {
			foreach ( array_reverse($this->_library_path) as $path ) {
				$file = $path.$class.'.php';
				if ( file_exists($file) ) {
					require_once($file);
					$bool = true;
					break;
				}
			}
			if ( $bool == false ) {
				LogWrite("Library File ({$file}) not exist!",'debug');
				return;
			}
			if ( !class_exists($pre_class_name,false) ) {
				LogWrite("Library Class ({$pre_class_name}) not exist!",'debug');
				return;
			}
		} else {
			LogWrite("Library Class ({$pre_class_name}) exist!",'debug');
		}

		// 实例化
		$this->_class[$object_name] = new $pre_class_name($param);
	}

}
