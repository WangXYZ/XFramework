<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Lang {

	private $type = 'chinese';
	private $lang = array();
	public $path = array();

	function __construct() {
		if ( $type=C('language') ) {
			$this->type = $type;
		}

		$path = XF_PATH.'language/';
		$this->path = array($path=>TRUE);

		$this->add_path(C('language_dir'));
		
		LogWrite('Lang Class Initialized','debug');

		// 加载基础语言包，优先内核，其次项目
		$this->load('common');
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

	function load($name) {
		if ( !$name ) {
			return;
		}

		foreach ( $this->path as $key => $val ) {
			if ( $val == FALSE ) {
				continue;
			}
			$file = $key.$this->type.'/'.$name.'.php';
			if ( file_exists($file) ) {
				$lang = include_once $file;
				if ( is_array($lang) ) {
					$this->lang = array_merge($this->lang,$lang);
				}
				LogWrite("Lang File Loaded : {$file}",'file');
			} else {
				LogWrite("Lang File not exists : {$file}",'error');
			}
		}
	}

	function get($str) {
		if ( is_array($str) ) {
			$text = array_shift($str);
		} else {
			$text = $str;
		}

		if ( isset($this->lang[$text]) ) {
			$text = $this->lang[$text];
		}

		if ( is_array($str) && count($str) ) {
			$text = vsprintf($text,$str);
		}

		return $text;
	}

}
