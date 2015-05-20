<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Controller {

	/*
	* 控制器对象
	*
	* @var     object
	*/
	private static $instance;

	/*
	* 基础类的集合
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

		// 引用已加载的类
		$this->x =& LoadClass();

		// 加载loader类
		LoadClass('Loader');
		$this->Loader->_autoload();

		// 子类的初始化函数 initialize的缩写
		if ( method_exists($this,'_init') ) {
			$this->_init();
		}

		LogWrite('Controller Class Initialized','debug');
	}

	/*
	* 设置基础类
	*
	* @return     void
	*/
	public function __set($k,$v) {
		$this->x[$k] = $v;
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

	/*
	* 获取控制器实例
	*
	* @static
	* @return     object
	*/
	public static function & get_instance() {
		return self::$instance;
	}

	/*
	* 加载视图
	*
	* @return     void
	*/
	public function _view($str=null,$data=null) {
		$this->x['View']->load($str,$data);
	}
	
	/*
	* 设置视图中的变量
	*
	* @return     void
	*/
	public function _set($data,$val=null) {
		$this->x['View']->set_data($data,$val);
	}
	
	/*
	* 获取视图中的变量
	*
	* @return     void
	*/
	public function _get($data) {
		return $this->x['View']->get_data($data);
	}

	/*
	* 显示msg
	*
	* @return     void
	*/
	public function msg() {
		$msg = $_SESSION['msg'];
		$this->_set('msg',$msg);
		$this->_view('/'.$msg['view']);
	}

}
