<?php

return array(

// ��վ�������ã�Ĭ��ΪUTF-8
'charset' => 'UTF-8',

// �Ƿ�Ϊrewriteģʽ��Ĭ��Ϊtrue
'uri.rewrite' => true,

// �Զ����ص����ݿ����ã�����database.db��Ӧdb��
'autoload.database' => array('db'),

// ���ݿ�������Ϣʾ��
'database.db' => array(
	'driver' => 'mysqli',
	'host' => 'localhost',
	'user' => 'root',
	'pass' => '123456',
	'name' => 'example',
	'charset' => 'UTF-8',
	'debug' => true,
),

);
