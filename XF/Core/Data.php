<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Data {

	public $data = array();
	public $path = array();

	function __construct() {
		$this->data =& D();
		
		$this->add_path(C('data_dir'));
		
		LogWrite('Data Class Initialized','debug');

		// 加载默认数据
		$this->load('common');
	}

	// 加载指定数据
	function load($name) {
		if ( !$name ) {
			return;
		}

		foreach ( $this->path as $k => $v ) {
			if ( $v == FALSE ) {
				continue;
			}
			$file = $k.$name.'.php';
			if ( !file_exists($file) ) {
				log_write("Load Data File error : {$file}",'error');
				continue;
			}
			$data = include_once $file;
			if ( is_array($data) ) {
				$this->data = array_merge($this->data,$data);
			}
			LogWrite("Data File Loaded : {$file}",'file');
		}
	}

	function add_path($dir) {
		if ( !$dir ) {
			return;
		}
		$dir = APP_PATH.$dir.'/';
		if ( isset($this->path[$dir]) AND $this->path[$dir] == TRUE ) {
			return;
		}
		if ( is_dir($dir) ) {
			$this->path[$dir] = TRUE;
		}
	}

	function get($str) {
		if ( isset($this->data[$str]) ) {
			return $this->data[$str];
		} else {
			return NULL;
		}
	}

	function set($str,$value) {
		if ( is_array($str) ) {
			$this->data = array_merge($this->data,$str);
		} else {
			if ( $value === NULL ) {
				if ( isset($this->data[$str]) ) {
					unset($this->data[$str]);
				}
			} else {
				$this->data[$str] = $value;
			}
		}
	}

}
