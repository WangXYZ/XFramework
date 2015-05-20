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
	* ����hook
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

		// ����Ƿ��ж������
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
	* ִ��hook
	*
	* @param     array     Hook details
	* @return     bool     TRUE on success or FALSE on failure
	*/
	function _run($data) {
		if ( !is_array($data) ) {
			return FALSE;
		}

		// ����ص�����ѭ������
		if ( $this->_progress == TRUE ) {
			return;
		}

		// �����б���ָ���ļ�·�����ļ���
		if ( !isset($data['path']) OR !isset($data['file']) ) {
			return FALSE;
		}

		$file = APP_PATH.$data['path'].'/'.$data['file'].'.php';

		// �ж��ļ��Ƿ����
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

		// ��ʼ����
		$this->_progress = TRUE;

		if ( $class !== FALSE ) {
			// �Ƿ��Ѵ������
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

		// ���н���
		$this->_progress = FALSE;
		return TRUE;
	}

}
