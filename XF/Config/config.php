<?php
defined('XF_PATH') OR exit('No direct script access allowed');

return array(

// 网站模式，默认为production，另一种是development。
'app_mode' => 'production',

// 执行时间上限，默认为null，表示使用php.ini设置，0表示不限制
'time_limit' => null,

// 时区设置，默认为空，表示使用php.ini中的设置
'timezone' => '',

// 网站编码设置，默认为UTF-8
'charset' => 'UTF-8',

// 是否为ajax，默认为FALSE，当设置为TURE时debug.show不显示。
'is_ajax' => false,



/*
* 网站路径设置，相对于项目目录，不能为空，当前目录可以用“.”表示，不能用"/"开头或结尾。
*/

// 网站公共目录
'controller_dir' => 'Controller',

// 网站公共目录
'model_dir' => 'Model',

// 网站公共目录
'view_dir' => 'View',

// 网站公共目录
'public_dir' => 'Public',

// 网站上传目录
'upload_dir' => 'Upload',

// 函数库目录
'helper_dir' => 'Helper',

// 类库目录
'library_dir' => 'Library',

// 缓存目录
'cache_dir' => 'Cache',

// 数据目录
'data_dir' => 'Data',

// 语言包目录
'language_dir' => 'Language',

// 日志目录，默认为"Log"。
'log_dir' => 'Log',


/*
* Log 日志
*/

// 是否记录log信息，默认为false
'log.enabled' => false,

// 是否记录到log文件，默认为false
'log.file' => false,

// 是否在页面显示log信息，默认为false
'log.show' => false,

// 日志列表文件，默认为"log.csv"。
'log.list_file' => 'log.csv',

// 日志显示时间格式，默认为"Y-m-d H:i:s"。
'log.date_format' => 'Y-m-d H:i:s',

/*
* 日志记录级别，默认为"1"。
* 0 = 不显示
* 1 = Error (含php错误)
* 2 = Debug
* 3 = File
* 4 = Sql
* 5 = Info
* 6 = All
*/
'log.level' => 1,

/*
* Hook 钩子
*/

/*
每个 hook 格式如下：
array(
	'path' => '',
	'file' => '',
	'class' => '',
	'function' => '',
	'param' => '',
);
*/

// 是否开启hook
'hook.enabled' => false,

// 加载pre_system，系统开始之前。
'hook.pre_system' => array(),

// 加载pre_controller，控制器实例化之前。
'hook.pre_controller' => array(),

// 加载pre_action，执行action方法之前。
'hook.pre_action' => array(),

// 加载pre_display，显示页面之前。
'hook.pre_display' => array(),

// 加载post_system，系统结束之后。
'hook.post_system' => array(),



/*
* Uri
*/

// uri模式   PATH_INFO,QUERY_STRING,GET,CLI
'uri.mode' => 'PATH_INFO',

// 是否为rewrite模式，默认为true
'uri.rewrite' => true,

// GET模式下uri读取地址栏参数名，默认为u
'uri.trigger' => 'u',

// 分组对应地址栏参数名
'uri.group_trigger' => 'g',

// 控制器对应地址栏参数名
'uri.controller_trigger' => 'c',

// 页面方法对应地址栏参数名
'uri.action_trigger' => 'a',

// 页码对应地址栏参数名
'uri.page_trigger' => 'p',

// 排序对应地址栏参数名
'uri.order_trigger' => 'o',

// 排序方向对应地址栏参数名
'uri.dir_trigger' => 'd',



/*
* Router
*/

// 默认分组
'router.default_group' => '',

// 默认控制器
'router.default_controller' => 'Index',

// 默认方法
'router.default_action' => 'index',

// 路由规则
'router.route' => array(),

// 404路由规则，找不到控制器时调用
'router.r404' => '',



/*
* View
*/

// 视图引擎，默认php: 直接使用php代码
'view.engine' => 'php',

// 视图文件扩展名
'view.ext' => 'php',



/*
* 语言包
*/

// 语言设置，默认为chinese。
'language' => 'chinese',



// ---------- 以下扩展配置，可在扩展配置中重新设置。

/*
* 扩展配置
*/

// 附加配置文件名，多个使用","隔开。
'extend' => '',



/*
* 自动加载
*/

// 自动加载的数据库配置，配置database.db对应db。
'autoload.database' => array(),

// 自动加载的函数库
'autoload.helper' => array(),

// 自动加载的类库
'autoload.library' => array(),

// 自动加载的模型
'autoload.model' => array(),



/*
* 数据库连接配置
*/

// 数据库连接信息示例
'database.db' => array(
	'driver' => 'mysql',
	'host' => 'localhost',
	'user' => '',
	'pass' => '',
	'name' => '',
	'pconnect' => false,
	'charset' => 'UTF-8',
	'prefix' => '',
	'debug' => true,
),

/*
	'cache_on' => false,
	'cachedir' => '',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'autoinit' => true,
	'stricton' => false,
*/
// 另一种数据库连接信息表示
//'db' => 'driver://user:pass@host/db?pconnect=0&charset=utf8',

);
