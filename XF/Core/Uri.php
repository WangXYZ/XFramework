<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Uri {

	public $str = '';
	public $segment = array();

	/*
	* Class constructor
	*
	* @return     void
	*/
	public function __construct() {
		$mode = strtoupper(C('uri.mode'));
		
		if ( $mode == 'CLI' ) {
			
		} else if ( $mode == 'GET' ) {
			if ( isset($_GET[C('uri.trigger')]) ) {
				$this->_set_str($_GET[C('uri.trigger')]);
			}
		} else {
			$uri = isset($_SERVER[$mode]) ? $_SERVER[$mode] : @getenv($mode);
			$this->_set_str($uri);
		}

		LogWrite("Uri Class Initialized",'debug');
	}

	public function _set_str($str) {
		$this->str = $str;
		$this->_parse($str);
	}

	public function _parse($str) {
		$this->segment = array();
		$str = str_replace('\\','/',$str);
		foreach ( explode('/',$str) as $v ) {
			if ( $v ) {
				$this->segment[] = $v;
			}
		}
	}

}
