<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Model {

	/*
	* 基础类的集合
	*
	* @var     array
	*/
	protected $x;

	/*
	* Class constructor
	*
	* @return     void
	*/
	function __construct() {
		$this->x =& LoadClass();

		// 自定义的初始化函数
		if ( method_exists($this,'_init') ) {
			$this->_init();
		}

		LogWrite('Model Class Initialized','debug');
	}

	/*
	* 设置基础类
	*
	* @return     void
	*/
	public function __set($k,$v) {
		if ( isset($this->x[$k]) ) {
			$this->x[$k] = $v;
		} else {
			$this->$k = $v;
		}
	}
	
	/*
	* 获取基础类
	*
	* @return     object
	*/
	public function __get($v) {
		if ( isset($this->x[$v]) ) {
			return $this->x[$v];
		}
		if ( isset($this->$v) ) {
			return $this->$v;
		}
		return null;
	}

}
