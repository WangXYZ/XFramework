<?php

// ��¼ҳ�濪ʼʱ��
define('_START_TIME',microtime(true));

// ������Ŀ¼�ľ���·��
if ( !defined('XF_PATH') ) {
	define('XF_PATH',str_replace('\\','/',dirname(__FILE__)).'/');
}

// �汾
define('XF_VERSION', '20150601');

// ���ؿ��
require XF_PATH.'XF.php';
