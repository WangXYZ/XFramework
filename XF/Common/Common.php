<?php
defined('XF_PATH') OR exit('No direct script access allowed');

if ( !function_exists('LoadClass') ) {
/*
* 框架基础类加载 load_class
*
* @param     string
* @param     mixed
* @param     string
* @return     object
*/
function& LoadClass($name='',$param=null,$pre='X_') {
	static $_class = array();

	if ( empty($name) ) {
		return $_class;
	}
	
	if ( isset($_class[$name]) ) {
		return $_class[$name];
	}
	
	$class_name = $pre.$name;
	if ( !class_exists($class_name) ) {
		exit("Load Class ({$class_name}) error !");
	}

	$_class[$name] =  isset($param) ? new $class_name($param) : new $class_name();

	return $_class[$name];
}
}

if ( !function_exists('LogWrite') ) {
/*
* 日志记录 LogWrite
*
* We use this as a simple mechanism to access the logging
* class and send messages to be logged.
*
* @param     string     the error message
* @param     string     the error level: 'error', 'debug', 'sql', 'info'
* @return     void
*/
function LogWrite($message,$type='debug') {
	$Log =& LoadClass('Log');
	$Log->Write($message,$type);
}
}

if ( !function_exists('C') ) {
/*
* 配置函数 C
*
* @param     string
* @param     mixed
* @return     mixed
*/
function& C($name=null,$value=null) {
	static $_config = array();
		
	if ( empty($_config) ) {
		foreach ( array(XF_PATH,APP_PATH) as $path ) {
			$file = $path.'Config/config.php';
			if ( file_exists($file) ) {
				$tmp = include_once $file;
				$_config = array_merge($_config,$tmp);
			}
		}
	}

	if ( empty($name) ) {
		return $_config;
	}

	if ( isset($value) ) {
		$_config[$name] = $value;
		return $_config[$name];
	} else if ( isset($_config[$name]) ) {
		return $_config[$name];
	} else {
		$return = null;
		return $return;
	}
}
}

if ( !function_exists('D') ) {
/*
* 数据函数 D
*
* @param     string
* @param     mixed
* @return     mixed
*/
function & D($name=null,$value=null) {
	static $_data = array();

	if ( empty($name) ) {
		return $_data;
	}

	if ( isset($value) ) {
		$_data[$name] = $value;
		return $_data[$name];
	} else if ( isset($_data[$name]) ) {
		return $_data[$name];
	} else {
		$return = null;
		return $return;
	}
}
}

if ( !function_exists('M') ) {
function& M($name=null,$db=null) {

	$model_name = $name.'_Model';
	
	if ( class_exists($model_name) ) {
		$class = new $model_name($name,$db);
	} else {
		$class = new X_Model($name,$db);
	}

	return $class;
}
}

if ( !function_exists('U') ) {
/*
* 地址处理函数 U
*
* @param     string
* @param     array
* @param     bool
* @return     void
*/
function U($str='',$get=null,$replace=false) {
	$Router =& LoadClass('Router');
	$g = $Router->getGroup();
	$c = $Router->getController();
	$a = $Router->getAction();

	$str = str_replace('\\','/',$str);
	if ( strpos($str,'/') === 0 ) {
		// "/"开头表示不需处理
		$str = trim($str,'/');
	} else {
		$str = trim($str,'/');
		$v = explode('/',$str);
		if ( $v[0] == '' ) {
			// 地址为空时，表示为当前地址
			$str = $g.$c.'/'.$a;
		} else if ( count($v) == 1 ) {
			// 只有1个时，表示改变当前控制器的方法
			$str = $g.$c.'/'.$str;
		} else {
			// 有多个时，前两个为控制器和方法，不会改变分组
			$str = $g.$str;
		}
	}

	// 地址参数
	$array = array();

	if ( is_string($get) ) {
		parse_str($get,$array);
	} else if ( is_array($get) ) {
		$array = $get;
	}

	// 读取当前页面地址参数，并修改部分地址参数。
	if ( $replace ) {
		$array = array_merge($_GET,$array);
	}

	if ( $array ) {
		$str .= '?'.http_build_query($array);
	}

	if ( C('uri.rewrite') ) {
		$str = _URL.$str;
	} else {
		$str = _FILE.'/'.$str;
	}
	return $str;

}
}

if ( !function_exists('R') ) {
/*
* 页面跳转函数 R
*
* @param     string,array
* @param     bool
* @return     void
*/
function R($url='',$bool=false) {
	// 默认使用U()处理跳转地址
	if ( $bool == false ) {
		if ( is_array($url) ) {
			$url = call_user_func('U',$url);
		} else {
			$url = U($url);
		}
	}

	if ( strpos($_SERVER['SERVER_SOFTWARE'],'Microsoft-IIS') === false ) {
		header('Location: '.$url);
		exit();
	} else {
		die('<html><head><meta http-equiv="refresh" content="0;URL='.$url.'" /></head></html>');
	}
}
}

// error
if ( !function_exists('E') ) {
function E($message,$title='error') {
	//$e =& LoadClass('Exception');
	//$e->show($title,$message);
	if ( is_array($message) ) $message = $message[0];
	echo $message;exit();
}
}

// lang
if ( !function_exists('L') ) {
function L($str) {
	$Lang =& LoadClass('Lang');
	return $Lang->get($str);
}
}

// input
if ( !function_exists('I') ) {
function I($str,$default=null) {
	list($type,$name) = explode('.',$str);
	if ( $type == 'post' ) {
		if ( isset($_POST[$name]) ) {
			$tmp = $_POST[$name];
		} else {
			return $default;
		}
	} else if ( $type == 'get' ) {
		if ( isset($_GET[$name]) ) {
			$tmp = $_GET[$name];
		} else {
			return $default;
		}
	}

	// 过滤
	$tmp = str_replace('\'','&acute;',$tmp);

	return $tmp;
}
}

if ( !function_exists('get_instance') ) {
/*
* Controller instance
*
* @return     void
*/
function& get_instance() {
	return X_Controller::get_instance();
}
}

// data get
// 找0列对应的数组，返回第$num列
if ( !function_exists('d_get') ) {
function d_get($str,$array,$num=2) {
	$num = $num - 1;
	if ( !is_array($array) ) {
		return null;
	}
	foreach ( $array as $v ) {
		$v = array_values($v);
		if ( $v[0] == $str && isset($v[$num]) ) {
			return $v[$num];
		}
	}
	return null;
}
}




// msg
// text   提示内容
// mode   1: success  2: notice   3: error
// url    确定按钮对应的跳转链接，仅在mode=1时有效
// view   加载的视图，默认msg
if ( !function_exists('msg') ) {
function msg($text='unkonwn',$mode=3,$url=null,$view='msg') {
	$msg['text'] = L($text);
	$msg['mode'] = $mode;
	$msg['url'] = $url;
	$msg['view'] = $view;
	$_SESSION['msg'] = $msg;
	R('msg');
}
}

function pagination(&$page) {
	$p = $page['page'];
	$tag = $page['tag'];
	$total = $page['total'];
	$count = $page['count'];
	$pm = $page['page_max'];
	$u = U('',$tag.'=',TRUE);

	$tmp = '<div class="page">';
	$tmp .= '<span class="total">'.L('page').' : '.$p.'/'.$pm.' &nbsp; '.L('page.count').' : '.$count.' &nbsp; '.L('page.total').': '.$total.'</span>';

	if ( $p > 1 ) $tmp .= '<a href="'.$u.'1'.'">'.L('page.first').'</a><a href="'.$u.($p-1).'">'.L('page.prev').'</a>';
	else $tmp .= '<span>'.L('page.first').'</span><span>'.L('page.prev').'</span>';

	if ( $p > 4 ) $tmp .= '<span>...</span>';
	if ( $p > 3 ) $tmp .= '<a href="'.$u.($p-3).'">'.($p-3).'</a>';
	if ( $p > 2 ) $tmp .= '<a href="'.$u.($p-2).'">'.($p-2).'</a>';
	if ( $p > 1 ) $tmp .= '<a href="'.$u.($p-1).'">'.($p-1).'</a>';
	$tmp .= '<b>'.$p.'</b>';
	if ( $p < $pm ) $tmp .= '<a href="'.$u.($p+1).'">'.($p+1).'</a>';
	if ( $p < $pm-1 ) $tmp .= '<a href="'.$u.($p+2).'">'.($p+2).'</a>';
	if ( $p < $pm-2 ) $tmp .= '<a href="'.$u.($p+3).'">'.($p+3).'</a>';
	if ( $p < $pm-3  ) $tmp .= '<span>...</span>';

	if ( $p < $pm ) $tmp .= '<a href="'.$u.($p+1).'">'.L('page.next').'</a><a href="'.$u.$pm.'">'.L('page.last').'</a>';
	else $tmp .= '<span>'.L('page.next').'</span><span>'.L('page.last').'</span>';
	
	$tmp .= '</div>';
	return $tmp;
}
