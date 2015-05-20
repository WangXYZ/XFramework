<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Model {

	/*
	* ������ļ���
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

		// �Զ���ĳ�ʼ������
		if ( method_exists($this,'_init') ) {
			$this->_init();
		}

		LogWrite('Model Class Initialized','debug');
	}

	/*
	* ���û�����
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
	* ��ȡ������
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
