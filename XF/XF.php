<?php
defined('XF_PATH') OR exit('No direct script access allowed');

final class XF {

	// 初始化
	public static function init($array=array()) {
		// 定义项目目录的绝对路径
		if ( !defined('APP_PATH') ) {
			if ( isset($array['app_dir']) ) {
				define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/'.$array['app_dir'].'/');
			} else {
				define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
			}
		}

		// 加载函数库，优先项目，其次框架
		foreach ( array(APP_PATH,XF_PATH) as $path ) {
			$file = $path.'Common/common.php';
			if ( file_exists($file) ) {
				require_once $file;
			}
		}

		// 设定错误处理函数
		set_error_handler(array('xf','_error'));

		// 设定异常处理函数
		set_exception_handler(array('xf','_exception'));

		// 设定结束处理函数
		register_shutdown_function(array('xf','_shutdown'));

		// 设定自动加载函数
		spl_autoload_register(array('XF','_autoload_x'));
		spl_autoload_register(array('XF','_autoload_db'));
		spl_autoload_register(array('XF','_autoload_controller'));
		spl_autoload_register(array('XF','_autoload_model'));
		spl_autoload_register(array('XF','_autoload'));

		// 初始化配置
		C();
		
		// 设置错误报告机制
		switch ( C('app_mode') ) {
			case 'production':
				error_reporting(E_COMPILE_ERROR);
				ini_set('display_errors',0);
				break;
			case 'development':
				error_reporting(E_ALL);
				ini_set('display_errors',1);
				break;
			default:
				header('HTTP/1.1 503 Service Unavailable.',true,503);
				echo 'The application mode is not set correctly.';
				exit(1);
		}

		// 开始
		LogWrite('---------- Start ----------','debug');

		// 加载Config类
		LoadClass('Config');

		// 项目入口文件
		define('_FILE',$_SERVER["SCRIPT_NAME"]);
		// 项目目录
		define('_URL',dirname($_SERVER["SCRIPT_NAME"]).'/');
		// 访问时间
		define('_TIME',$_SERVER["REQUEST_TIME"]);
		// 客户端ip
		define('_IP',$_SERVER['REMOTE_ADDR']);

		// 设置页面执行允许的最大时间
		if ( C('time_limit') !== null ) {
			set_time_limit(C('time_limit'));
		}

		// 设置时区
		if ( C('timezone') && function_exists('date_default_timezone_set') ) {
			date_default_timezone_set(C('timezone'));
		}

		// 启用 session
		if ( !ini_get('session.auto_start') ) {
			session_start();
		}

		// 网站资源的目录
		if ( !defined('_PUBLIC') ) {
			define('_PUBLIC',_URL.C('public_dir').'/');
		}

		// 网站上传的目录
		if ( !defined('_UPLOAD') ) {
			define('_UPLOAD',_URL.C('upload_dir').'/');
		}

		// 设置字符编码
		if ( $charset = strtoupper(C('charset')) ) {
			ini_set('default_charset',$charset);
		}

		LogWrite('XF Initialized','debug');
	}

	public static function run() {
		$Hook =& LoadClass('Hook');
		
		// Hook加载pre_system
		$Hook->load('pre_system');

		// 加载Uri
		$Uri =& LoadClass('Uri','core');

		// 加载Router
		$Router =& LoadClass('Router');
		$Router->Routing();
		
		//$Lang =& LoadClass('Lang');
		$View =& LoadClass('View');

		// 加载config扩展
		$Config =& LoadClass('Config');
		$Config->LoadExtend();

		// 加载data
		LoadClass('Data');

		//$g = $Router->getGroup();
		$c = $Router->getController();
		$c_name = $c.'_Controller';
		$a = $Router->getAction();

		// Hook加载pre_controller
		$Hook->Load('pre_controller');

		// 实例化控制器
		$x = new $c_name();

		if ( method_exists($c_name,'_remap') ) {
			$param = array($a,array_slice($Uri->segment,2));
			$a = '_remap';
		} else if ( !method_exists($c_name,$a) ) {
			E('Action not found !');
		}

		if ( $a != '_remap' ) {
			$param = array_slice($Uri->segment,2);
		}

		// Hook加载pre_action
		$Hook->Load('pre_action');

		// 执行控制器的方法
		call_user_func_array(array(&$x,$a),$param);
		
		// Hook加载pre_display
		$Hook->Load('pre_display');

		// Hook加载post_system
		$Hook->Load('post_system');
	}

	// 错误处理函数
	public static function _error($num,$msg) {
		echo "[{$num}] {$msg}";
		$e = debug_backtrace();
		var_dump($e);
		exit();
	}

	// 异常处理函数
	public static function _exception($e) {
		var_dump($e);
		exit();
	}

	// 结束处理函数
	public static function _shutdown() {
		// 关闭数据库连接，测试中。应该在每次执行sql语句后自动关闭？
		$x =& LoadClass();
		if ( isset($x['db']) ) {
			$x['db']->close();
		}
		$Log =& LoadClass('Log');
		$Log->Finish();
	}

	public static function _autoload_x($str) {
		if ( substr($str,0,2) == 'X_' ) {
			$name = substr($str,2);
			$array = array(
				XF_PATH.'Core/',
			);
			foreach ( $array as $path ) {
				$file = $path.$name.'.php';
				if ( file_exists($file) ) {
					require_once $file;
					return;
				}
			}
		}
	}

	public static function _autoload_db($str) {
		 if ( substr($str,0,5) == 'X_DB_' ) {
			$name = substr($str,5);
			$array = array(
				XF_PATH.'DB/',
			);
			foreach ( $array as $path ) {
				$file = $path.$name.'.php';
				if ( file_exists($file) ) {
					require_once $file;
					return;
				}
			}
		}
	}
		
	public static function _autoload_controller($str) {
		if ( substr($str,-11) == '_Controller' ) {
			$name = substr($str,0,-11);
			$Router =& LoadClass("Router");
			$g = $Router->getGroup();
			$file = APP_PATH.C('controller_dir').'/'.$g.$name.'.php';
			if ( file_exists($file) ) {
				require_once $file;
				return;
			}
		}
	}
		
	public static function _autoload_model($str) {
		if ( substr($str,-6) == '_Model' ) {
			$name = substr($str,0,-6);
			$file = APP_PATH.C('model_dir').'/'.$name.'.php';
			if ( file_exists($file) ) {
				require_once $file;
				return;
			}
		}
	}
		
	public static function _autoload($str) {
		$array = array(
			APP_PATH.C('library_dir').'/',
			XF_PATH.'Library/',
		);
		foreach ( $array as $path ) {
			$file = $path.$str.'.php';
			if ( file_exists($file) ) {
				require_once $file;
				return;
			}
		}
	}

}
