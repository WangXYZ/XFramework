<?php
defined('XF_PATH') OR exit('No direct script access allowed');

final class XF {

	// ��ʼ��
	public static function init($array=array()) {
		// ������ĿĿ¼�ľ���·��
		if ( !defined('APP_PATH') ) {
			if ( isset($array['app_dir']) ) {
				define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/'.$array['app_dir'].'/');
			} else {
				define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']).'/');
			}
		}

		// ���غ����⣬������Ŀ����ο��
		foreach ( array(APP_PATH,XF_PATH) as $path ) {
			$file = $path.'Common/common.php';
			if ( file_exists($file) ) {
				require_once $file;
			}
		}

		// �趨��������
		set_error_handler(array('xf','_error'));

		// �趨�쳣������
		set_exception_handler(array('xf','_exception'));

		// �趨����������
		register_shutdown_function(array('xf','_shutdown'));

		// �趨�Զ����غ���
		spl_autoload_register(array('XF','_autoload_x'));
		spl_autoload_register(array('XF','_autoload_db'));
		spl_autoload_register(array('XF','_autoload_controller'));
		spl_autoload_register(array('XF','_autoload_model'));
		spl_autoload_register(array('XF','_autoload'));

		// ��ʼ������
		C();
		
		// ���ô��󱨸����
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

		// ��ʼ
		LogWrite('---------- Start ----------','debug');

		// ����Config��
		LoadClass('Config');

		// ��Ŀ����ļ�
		define('_FILE',$_SERVER["SCRIPT_NAME"]);
		// ��ĿĿ¼
		define('_URL',dirname($_SERVER["SCRIPT_NAME"]).'/');
		// ����ʱ��
		define('_TIME',$_SERVER["REQUEST_TIME"]);
		// �ͻ���ip
		define('_IP',$_SERVER['REMOTE_ADDR']);

		// ����ҳ��ִ����������ʱ��
		if ( C('time_limit') !== null ) {
			set_time_limit(C('time_limit'));
		}

		// ����ʱ��
		if ( C('timezone') && function_exists('date_default_timezone_set') ) {
			date_default_timezone_set(C('timezone'));
		}

		// ���� session
		if ( !ini_get('session.auto_start') ) {
			session_start();
		}

		// ��վ��Դ��Ŀ¼
		if ( !defined('_PUBLIC') ) {
			define('_PUBLIC',_URL.C('public_dir').'/');
		}

		// ��վ�ϴ���Ŀ¼
		if ( !defined('_UPLOAD') ) {
			define('_UPLOAD',_URL.C('upload_dir').'/');
		}

		// �����ַ�����
		if ( $charset = strtoupper(C('charset')) ) {
			ini_set('default_charset',$charset);
		}

		LogWrite('XF Initialized','debug');
	}

	public static function run() {
		$Hook =& LoadClass('Hook');
		
		// Hook����pre_system
		$Hook->load('pre_system');

		// ����Uri
		$Uri =& LoadClass('Uri','core');

		// ����Router
		$Router =& LoadClass('Router');
		$Router->Routing();
		
		//$Lang =& LoadClass('Lang');
		$View =& LoadClass('View');

		// ����config��չ
		$Config =& LoadClass('Config');
		$Config->LoadExtend();

		// ����data
		LoadClass('Data');

		//$g = $Router->getGroup();
		$c = $Router->getController();
		$c_name = $c.'_Controller';
		$a = $Router->getAction();

		// Hook����pre_controller
		$Hook->Load('pre_controller');

		// ʵ����������
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

		// Hook����pre_action
		$Hook->Load('pre_action');

		// ִ�п������ķ���
		call_user_func_array(array(&$x,$a),$param);
		
		// Hook����pre_display
		$Hook->Load('pre_display');

		// Hook����post_system
		$Hook->Load('post_system');
	}

	// ��������
	public static function _error($num,$msg) {
		echo "[{$num}] {$msg}";
		$e = debug_backtrace();
		var_dump($e);
		exit();
	}

	// �쳣������
	public static function _exception($e) {
		var_dump($e);
		exit();
	}

	// ����������
	public static function _shutdown() {
		// �ر����ݿ����ӣ������С�Ӧ����ÿ��ִ��sql�����Զ��رգ�
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
