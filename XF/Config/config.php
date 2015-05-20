<?php
defined('XF_PATH') OR exit('No direct script access allowed');

return array(

// ��վģʽ��Ĭ��Ϊproduction����һ����development��
'app_mode' => 'production',

// ִ��ʱ�����ޣ�Ĭ��Ϊnull����ʾʹ��php.ini���ã�0��ʾ������
'time_limit' => null,

// ʱ�����ã�Ĭ��Ϊ�գ���ʾʹ��php.ini�е�����
'timezone' => '',

// ��վ�������ã�Ĭ��ΪUTF-8
'charset' => 'UTF-8',

// �Ƿ�Ϊajax��Ĭ��ΪFALSE��������ΪTUREʱdebug.show����ʾ��
'is_ajax' => false,



/*
* ��վ·�����ã��������ĿĿ¼������Ϊ�գ���ǰĿ¼�����á�.����ʾ��������"/"��ͷ���β��
*/

// ��վ����Ŀ¼
'controller_dir' => 'Controller',

// ��վ����Ŀ¼
'model_dir' => 'Model',

// ��վ����Ŀ¼
'view_dir' => 'View',

// ��վ����Ŀ¼
'public_dir' => 'Public',

// ��վ�ϴ�Ŀ¼
'upload_dir' => 'Upload',

// ������Ŀ¼
'helper_dir' => 'Helper',

// ���Ŀ¼
'library_dir' => 'Library',

// ����Ŀ¼
'cache_dir' => 'Cache',

// ����Ŀ¼
'data_dir' => 'Data',

// ���԰�Ŀ¼
'language_dir' => 'Language',

// ��־Ŀ¼��Ĭ��Ϊ"Log"��
'log_dir' => 'Log',


/*
* Log ��־
*/

// �Ƿ��¼log��Ϣ��Ĭ��Ϊfalse
'log.enabled' => false,

// �Ƿ��¼��log�ļ���Ĭ��Ϊfalse
'log.file' => false,

// �Ƿ���ҳ����ʾlog��Ϣ��Ĭ��Ϊfalse
'log.show' => false,

// ��־�б��ļ���Ĭ��Ϊ"log.csv"��
'log.list_file' => 'log.csv',

// ��־��ʾʱ���ʽ��Ĭ��Ϊ"Y-m-d H:i:s"��
'log.date_format' => 'Y-m-d H:i:s',

/*
* ��־��¼����Ĭ��Ϊ"1"��
* 0 = ����ʾ
* 1 = Error (��php����)
* 2 = Debug
* 3 = File
* 4 = Sql
* 5 = Info
* 6 = All
*/
'log.level' => 1,

/*
* Hook ����
*/

/*
ÿ�� hook ��ʽ���£�
array(
	'path' => '',
	'file' => '',
	'class' => '',
	'function' => '',
	'param' => '',
);
*/

// �Ƿ���hook
'hook.enabled' => false,

// ����pre_system��ϵͳ��ʼ֮ǰ��
'hook.pre_system' => array(),

// ����pre_controller��������ʵ����֮ǰ��
'hook.pre_controller' => array(),

// ����pre_action��ִ��action����֮ǰ��
'hook.pre_action' => array(),

// ����pre_display����ʾҳ��֮ǰ��
'hook.pre_display' => array(),

// ����post_system��ϵͳ����֮��
'hook.post_system' => array(),



/*
* Uri
*/

// uriģʽ   PATH_INFO,QUERY_STRING,GET,CLI
'uri.mode' => 'PATH_INFO',

// �Ƿ�Ϊrewriteģʽ��Ĭ��Ϊtrue
'uri.rewrite' => true,

// GETģʽ��uri��ȡ��ַ����������Ĭ��Ϊu
'uri.trigger' => 'u',

// �����Ӧ��ַ��������
'uri.group_trigger' => 'g',

// ��������Ӧ��ַ��������
'uri.controller_trigger' => 'c',

// ҳ�淽����Ӧ��ַ��������
'uri.action_trigger' => 'a',

// ҳ���Ӧ��ַ��������
'uri.page_trigger' => 'p',

// �����Ӧ��ַ��������
'uri.order_trigger' => 'o',

// �������Ӧ��ַ��������
'uri.dir_trigger' => 'd',



/*
* Router
*/

// Ĭ�Ϸ���
'router.default_group' => '',

// Ĭ�Ͽ�����
'router.default_controller' => 'Index',

// Ĭ�Ϸ���
'router.default_action' => 'index',

// ·�ɹ���
'router.route' => array(),

// 404·�ɹ����Ҳ���������ʱ����
'router.r404' => '',



/*
* View
*/

// ��ͼ���棬Ĭ��php: ֱ��ʹ��php����
'view.engine' => 'php',

// ��ͼ�ļ���չ��
'view.ext' => 'php',



/*
* ���԰�
*/

// �������ã�Ĭ��Ϊchinese��
'language' => 'chinese',



// ---------- ������չ���ã�������չ�������������á�

/*
* ��չ����
*/

// ���������ļ��������ʹ��","������
'extend' => '',



/*
* �Զ�����
*/

// �Զ����ص����ݿ����ã�����database.db��Ӧdb��
'autoload.database' => array(),

// �Զ����صĺ�����
'autoload.helper' => array(),

// �Զ����ص����
'autoload.library' => array(),

// �Զ����ص�ģ��
'autoload.model' => array(),



/*
* ���ݿ���������
*/

// ���ݿ�������Ϣʾ��
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
// ��һ�����ݿ�������Ϣ��ʾ
//'db' => 'driver://user:pass@host/db?pconnect=0&charset=utf8',

);
