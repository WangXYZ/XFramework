<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Hook {

	public $enabled = FALSE;
	protected $_progress = FALSE;
	protected $_object = array();

	/*
	* Class constructor
	*
	* @return     void
	*/
	function __construct() {
		if ( C('hook.enabled') == TRUE ) {
			$this->enabled = TRUE;
		}

		LogWrite('Hook Class Initialized','debug');
	}

	/*
	* 运行hook
	*
	* @uses	X_Hook::_run()
	*
	* @param     string     Hook name
	* @return     bool     TRUE on success or FALSE on failure
	*/
	function load($str='') {
		if ( $this->enabled == FALSE ) {
			return FALSE;
		}

		$data = C('hook.'.$str);

		if ( empty($data) OR !is_array($data) ) {
			return FALSE;
		}

		log_write("Hook load {$str} ",'debug');

		// 检查是否含有多个配置
		if ( isset($data['function']) ) {
			$this->_run($data);
		} else {
			foreach ( $data as $val ) {
				$this->_run($val);
			}
		}

		return TRUE;
	}

	/*
	* 执行hook
	*
	* @param     array     Hook details
	* @return     bool     TRUE on success or FALSE on failure
	*/
	function _run($data) {
		if ( !is_array($data) ) {
			return FALSE;
		}

		// 避免回调引起循环调用
		if ( $this->_progress == TRUE ) {
			return;
		}

		// 配置中必须指定文件路径与文件名
		if ( !isset($data['path']) OR !isset($data['file']) ) {
			return FALSE;
		}

		$file = APP_PATH.$data['path'].'/'.$data['file'].'.php';

		// 判断文件是否存在
		if ( !file_exists($file) ) {
			log_write("Load Hook file error : {$file}",'error');
			return FALSE;
		}

		$class = empty($data['class']) ? FALSE : $data['class'];
		$function = empty($data['function']) ? FALSE : $data['function'];
		$param = isset($data['param']) ? $data['param'] : NULL;

		if ( $function == FALSE ) {
			return FALSE;
		}

		// 开始运行
		$this->_progress = TRUE;

		if ( $class !== FALSE ) {
			// 是否已储存该类
			if ( isset($this->_object[$class]) ) {
				if ( method_exists($this->_objects[$class],$function) ) {
					$this->_objects[$class]->$function($params);
				}
				return $this->_in_progress = FALSE;
			}

			class_exists($class,FALSE) OR require_once $file;

			if ( !class_exists($class,FALSE) OR !method_exists($class,$function) ) {
				log_write("Hook run class error : {$class}/{$function}",'error');
				return $this->_progress = FALSE;
			}

			$this->_object[$class] = new $class;
			$this->_object[$class]->$function($param);
		} else {
			function_exists($function) OR require_once $file;
			$function($param);
		}

		// 运行结束
		$this->_progress = FALSE;
		return TRUE;
	}

}
