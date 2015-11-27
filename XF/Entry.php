<?php

// 记录页面开始时间
define('_START_TIME',microtime(true));

// 定义框架目录的绝对路径
if ( !defined('XF_PATH') ) {
	define('XF_PATH',str_replace('\\','/',dirname(__FILE__)).'/');
}

// 版本
define('XF_VERSION', '20150601');

// 加载框架
require XF_PATH.'XF.php';
