<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Controller {

	/*
	* ����������
	*
	* @var     object
	*/
	private static $instance;

	/*
	* ������ļ���
	*
	* @var     array
	*/
	private $x;

	/*
	* Class constructor
	*
	* @return     void
	*/
	public function __construct() {
		self::$instance =& $this;

		// �����Ѽ��ص���
		$this->x =& LoadClass();

		// ����loader��
		LoadClass('Loader');
		$this->Loader->_autoload();

		// ����ĳ�ʼ������ initialize����д
		if ( method_exists($this,'_init') ) {
			$this->_init();
		}

		LogWrite('Controller Class Initialized','debug');
	}

	/*
	* ���û�����
	*
	* @return     void
	*/
	public function __set($k,$v) {
		$this->x[$k] = $v;
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

	/*
	* ��ȡ������ʵ��
	*
	* @static
	* @return     object
	*/
	public static function & get_instance() {
		return self::$instance;
	}

	/*
	* ������ͼ
	*
	* @return     void
	*/
	public function _view($str=null,$data=null) {
		$this->x['View']->load($str,$data);
	}
	
	/*
	* ������ͼ�еı���
	*
	* @return     void
	*/
	public function _set($data,$val=null) {
		$this->x['View']->set_data($data,$val);
	}
	
	/*
	* ��ȡ��ͼ�еı���
	*
	* @return     void
	*/
	public function _get($data) {
		return $this->x['View']->get_data($data);
	}

	/*
	* ��ʾmsg
	*
	* @return     void
	*/
	public function msg() {
		$msg = $_SESSION['msg'];
		$this->_set('msg',$msg);
		$this->_view('/'.$msg['view']);
	}

}
