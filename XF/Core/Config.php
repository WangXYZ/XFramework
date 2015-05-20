<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Config {
	
	protected $config = array();

	protected $_loaded = array();

	/*
	* Class constructor
	*
	* @return     void
	*/
	function __construct() {
		// ÒýÓÃÅäÖÃ
		$this->config =& C();

		$this->_loaded['config'] = true;
		
		LogWrite('Config Class Initialized','debug');
	}
	
	/*
	* ¼ÓÔØÀ©Õ¹ÅäÖÃ
	*
	* @return     void
	*/
	public function LoadExtend() {
		$ext = $this->config['extend'];
		foreach ( explode(',',$ext) as $name ) {
			if ( $name ) {
				$this->load($file);
			}
		}
	}

	/*
	* ¼ÓÔØÅäÖÃ
	*
	* @param     string
	* @return     void
	*/
	public function Load($name) {
		if ( !$name ) {
			return;
		}

		$name = strtolower($name);
		if ( isset($this->_loaded[$name]) ) {
			return;
		}
		$this->_loaded[$name] = TRUE;

		$file = APP_PATH.'config/'.$name.'.php';
		if ( file_exists($file) ) {
			$tmp = include_once $file;
			if ( is_array($config) ) {
				$this->set($config);
			}
			LogWrite("Config File Loaded : {$file}",'file');
		} else {
			LogWrite("Config File not exists : {$file}",'error');
		}
	}

	/*
	* »ñÈ¡ÅäÖÃ
	*
	* @param     string
	* @param     string
	* @return     void
	*/
	function get($str,$index=NULL) {
		if ( isset($index) ) {
			return isset($this->config[$index],$this->config[$index][$str]) ? $this->config[$index][$str] : NULL;
		}
		return isset($this->config[$str]) ? $this->config[$str] : NULL;
	}

	/*
	* ÉèÖÃÅäÖÃ
	*
	* @param     string
	* @param     mixed
	* @return     void
	*/
	function set($str,$value=NULL) {
		if ( is_array($str) ) {
			$this->config = array_merge($this->config,$str);
		} else {
			if ( $value === NULL ) {
				if ( isset($this->config[$str]) ) {
					unset($this->config[$str]);
				}
			} else {
				$this->config[$str] = $value;
			}
		}
	}

}
