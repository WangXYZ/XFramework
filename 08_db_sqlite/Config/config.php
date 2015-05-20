<?php

return array(

// 网站编码设置，默认为UTF-8
'charset' => 'UTF-8',

// 是否为rewrite模式，默认为true
'uri.rewrite' => true,

// 自动加载的数据库配置，配置database.db对应db。
'autoload.database' => array('db'),

// 数据库连接信息示例
'database.db' => array(
	'driver' => 'sqlite',
	'file' => 'Database/Database.db',
	'charset' => 'utf8',
	'debug' => true,
),

);
