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
		// 加载路由规则
		$this->uri =& LoadClass('Uri','core');
		$this->route = C('router.route');
		//$this->r404 = C('router.r404');
		$this->path = APP_PATH.C('controller_dir').'/';
		
		// 加载默认配置
		$this->setGroup(C('router.default_group'));
		$this->setController(C('router.default_controller'));
		$this->setAction(C('router.default_action'));

		LogWrite('Router Class Initialized','debug');
	}

	function Routing() {
		// 处理自定义路由规则
		$this->_parse_route();

		// 设置分组、控制器、方法、参数
		$this->_parse($this->uri->segment);

		// 验证控制器
		$this->_validate();

		$this->_init();
	}

	// 处理自定义路由规则
	function _parse_route() {
		$str = $this->uri->str;
		if ( !$str ) {
			return;
		}

		// 没有路由规则
		if ( empty($this->route) ) {
			return;
		}

		// 直接匹配路由规则
		if ( isset($this->route[$str]) ) {
			$this->uri->_parse($this->routes[$str]);
			return;
		}

		// 正则匹配路由规则
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

	// 设置分组、控制器、方法、参数
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

	// 验证控制器
	function _validate() {
		// 验证当前控制器文件是否存在
		$c_name = $this->controller.'_Controller';
		if ( class_exists($c_name) ) {
			return;
		}
		LogWrite("Controller ({$c_name}) not found !",'error');
		
		// 调用404路由规则，存在问题，一般r404是字符串，需要转为数组，处理时可能存在空的控制器或方法，还是会调用之前的。或者说，必有控制器，再在404控制器中使用remap功能
		if ( $this->r404 ) {
			LogWrite('use 404 page','debug');
			$this->_parse($this->r404);
			$c_name = $this->controller.'_Controller';
			if ( class_exists($c_name) ) {
				return;
			}
			LogWrite('404 page not found !','debug');
		}

		// 验证不通过，页面不存在
		E(array('err.controller_not_found',$this->group.$this->controller));
	}

	// 定义常量
	function _init() {
		// 当前分组
		define('_G',$this->group);
		// 当前控制器
		define('_C',$this->controller);
		// 当前方法
		define('_A',$this->action);
		// 当前分组
		define('_U_G',_URL._G);
		// 当前控制器
		define('_U_C',_U_G._C.'/');
		// 当前页面
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
