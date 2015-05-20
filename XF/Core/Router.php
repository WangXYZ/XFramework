<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Router {
	
	public $r404 = '';
	public $route = array();
	public $path = '';
	public $group = '';
	public $controller = 'Index';
	public $action = 'index';
	
	/*
	* Class constructor
	*
	* @return     void
	*/
	function __construct() {
		// ����·�ɹ���
		$this->uri =& LoadClass('Uri','core');
		$this->route = C('router.route');
		//$this->r404 = C('router.r404');
		$this->path = APP_PATH.C('controller_dir').'/';
		
		// ����Ĭ������
		$this->setGroup(C('router.default_group'));
		$this->setController(C('router.default_controller'));
		$this->setAction(C('router.default_action'));

		LogWrite('Router Class Initialized','debug');
	}

	function Routing() {
		// �����Զ���·�ɹ���
		$this->_parse_route();

		// ���÷��顢������������������
		$this->_parse($this->uri->segment);

		// ��֤������
		$this->_validate();

		$this->_init();
	}

	// �����Զ���·�ɹ���
	function _parse_route() {
		$str = $this->uri->str;
		if ( !$str ) {
			return;
		}

		// û��·�ɹ���
		if ( empty($this->route) ) {
			return;
		}

		// ֱ��ƥ��·�ɹ���
		if ( isset($this->route[$str]) ) {
			$this->uri->_parse($this->routes[$str]);
			return;
		}

		// ����ƥ��·�ɹ���
		foreach ( $this->route as $key => $val ) {
			$key = str_replace(':num','[0-9]+',$key);
			$key = str_replace(':any','.+',$key);
			if ( preg_match('#^'.$key.'$#',$str) ) {
				// Do we have a back-reference?
				if ( strpos($val,'$') !== FALSE AND strpos($key,'(') !== FALSE ) {
					$val = preg_replace('#^'.$key.'$#',$val,$str);
				}
				$this->uri->_parse($val);
				return;
			}
		}
	}

	// ���÷��顢������������������
	function _parse($s) {
		if ( is_string($s) ) {
			$s = explode('/',$s);
		}
		if ( count($s) == 0 ) {
			return;
		}

		if ( is_dir($this->path.$s[0]) ) {
			$this->setGroup($s[0]);
			array_shift($s);
		}
		if ( isset($s[0]) ) {
			$this->setController($s[0]);
		}
		if ( isset($s[1]) ) {
			$this->setAction($s[1]);
		}
		$this->uri->segment = $s;
	}

	// ��֤������
	function _validate() {
		// ��֤��ǰ�������ļ��Ƿ����
		$c_name = $this->controller.'_Controller';
		if ( class_exists($c_name) ) {
			return;
		}
		LogWrite("Controller ({$c_name}) not found !",'error');
		
		// ����404·�ɹ��򣬴������⣬һ��r404���ַ�������ҪתΪ���飬����ʱ���ܴ��ڿյĿ������򷽷������ǻ����֮ǰ�ġ�����˵�����п�����������404��������ʹ��remap����
		if ( $this->r404 ) {
			LogWrite('use 404 page','debug');
			$this->_parse($this->r404);
			$c_name = $this->controller.'_Controller';
			if ( class_exists($c_name) ) {
				return;
			}
			LogWrite('404 page not found !','debug');
		}

		// ��֤��ͨ����ҳ�治����
		E(array('err.controller_not_found',$this->group.$this->controller));
	}

	// ���峣��
	function _init() {
		// ��ǰ����
		define('_G',$this->group);
		// ��ǰ������
		define('_C',$this->controller);
		// ��ǰ����
		define('_A',$this->action);
		// ��ǰ����
		define('_U_G',_URL._G);
		// ��ǰ������
		define('_U_C',_U_G._C.'/');
		// ��ǰҳ��
		define('_U_A',_U_C._A.'/');
	}

	function setGroup($group) {
		//$group = str_replace(array('/','.'),'',$group);
		if ( $group && is_dir($this->path.$group) ) {
			$this->group = $group.'/';
		}
	}

	function getGroup() {
		return $this->group;
	}

	function setController($controller) {
		//$controller = str_replace(array('/','.'),'',$controller);
		$this->controller = $controller;
	}

	function getController() {
		return $this->controller;
	}

	function setAction($action) {
		//$action = trim($action,'_');
		$this->action = $action;
	}

	function getAction() {
		return $this->action;
	}

}
