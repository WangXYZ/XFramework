<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_DB {

	static $_count;

	public $_file;
	public $_user;
	public $_pass;
	public $_host;
	public $_port;
	public $_name;
	public $_pre = '';
	public $_driver = 'mysql';
	public $_charset = 'UTF8';
	public $_newlink = false;
	public $_debug = false;

	protected $_id;
	protected $_rs;

	public $_table;
	public $_select = array();
	public $_join = array();
	public $_where = array();
	public $_group = array();
	public $_order = array();
	public $_limit;
	public $_perpage;

	function __construct($param) {
		if ( is_array($param) ) {
			foreach ( $param as $key => $val ) {
				$key = '_'.$key;
				$this->$key = $val;
			}
		}
		
		$this->init();
	}

	function init() {
		if ( $this->_id == false ) {
			$this->connect();
			$this->set_charset();
			$this->select_db();
		}
	}

	function connect($param=null) {
		if ( is_array($param) ) {
			foreach ( $param as $key => $val ) {
				$key = '_'.$key;
				$this->$key = $val;
			}
		}

		switch ( $this->_driver ) {
			case 'mysql':
				$host = $this->_host;
				if ( $this->_port ) $host .=':'.$this->_port;
				$id = $this->_connect($host,$this->_user,$this->_pass,$this->_newlink);
				break;
			case 'mysqli':
				$id = $this->_connect($this->_host,$this->_user,$this->_pass,$this->_port);
				break;
			case 'sqlite':
				$file = APP_PATH.$this->_file;
				$id = $this->_connect($file);
				break;
			case 'sqlsrv':
				$host = $this->_host;
				if ( $this->_port ) $host .=':'.$this->_port;
				$array = array(
					'Database' => $this->_name,
					'UID' => $this->_user,
					'PWD' => $this->_pass,
					'CharacterSet' => $this->_charset,
				);
				$id = $this->_connect($host,$array);
				break;
			default:
				E('err.db_driver_error');
		}

		if ( $id == false ) {
			E('err.db_connect_error');
		}

		$this->_id = $id;
	}

	function close() {
		$this->_close($this->_id);
	}

	function select_db($str=null) {
		if ( $str ) {
			$this->_name = $str;
		}

		$this->_select_db($this->_id,$this->_name);
		$this->check_error();
	}

	function set_charset($charset=null) {
		if ( $charset ) {
			$this->_charset = $charset;
		}

		$this->_set_charset($this->_id,$this->_charset);
		$this->check_error();
	}

	function query($sql) {
		self::$_count++;
		LogWrite("{$sql}",'sql');
		$this->_rs = $this->_query($this->_id,$sql);
		$this->check_error();
	}

	function execute($sql) {
		$this->query($sql);
		if ( $this->errno() ) {
			return false;
		} else {
			return true;
		}
	}

	function fecth_array() {
		$array = $this->_fecth_array($this->_rs);
		$this->check_error();
		return $array;
	}

	function fetch_assoc() {
		$array = $this->_fetch_assoc($this->_rs);
		$this->check_error();
		return $array;
	}

	function num_rows() {
		$result = $this->_num_rows($this->_rs);
		$this->check_error();
		return $result;
	}

	function check_error() {
		$errno = $this->errno();
		$error = $this->error();
		if ( empty($errno) ) {
			return false;
		}
		if ( $this->_debug == true ) {
			LogWrite($errno.' : '.$error,'error');
		}
		return true;
	}

	function errno() {
		return $this->_errno($this->_id);
	}

	function error() {
		return $this->_error($this->_id);
	}

	function set_table($table) {
		if ( $table ) {
			$this->_table = $table;
		}
	}

	function set_select($select) {
		if ( is_string($select) ) {
			$this->_select[] = $select;
		}
		if ( is_array($select) ) {
			$this->_select = array_merge($this->_select,$select);
		}
	}

	function set_where($where) {
		if ( is_string($where) ) {
			$this->_where[] = $where;
		}
		if ( is_array($where) ) {
			$this->_where = array_merge($this->_where,$where);
		}
	}

	function set_join($join) {
		if ( is_array($join) ) {
			$this->_join = $join;
		}
	}

	function set_limit($limit) {
		if ( is_numeric($limit) && $limit > 0 ) {
			$this->_limit = $limit;
		}
	}

	function set_perpage($perpage) {
		if ( is_numeric($perpage) && $perpage > 0 ) {
			$this->_perpage = $perpage;
		}
	}

	function set_order($order) {
		$this->_order[] = $order;
	}

	function set_group($group) {
		$this->_group = $group;
	}

	function reset() {
		$this->_select = array();
		$this->_join = array();
		$this->_where = array();
		$this->_order = array();
		$this->_group = '';
	}

	// 更新
	function update($array) {
		$kv = array();
		foreach ( $array as $k => $v ) {
			$kv[] = "`{$k}`='{$v}'";
		}
		if ( empty($kv) ) {
			return false;
		}
		$kv = implode(',',$kv);

		$table = $this->_table;
		$where = $this->parse_where();

		$sql = "UPDATE `{$table}` SET {$kv} $where";
		$this->query($sql);
		return true;
	}

	// 删除
	function del($where=null) {
		$this->set_where($where);
		$table = $this->_table;
		$where = $this->parse_where();

		$sql = "DELECT FROM `{$table}` $where";
		$this->query($sql);
		return true;
	}

	// 增加
	function create($array) {
		$str1 = $str2 = array();
		foreach ( $array as $k => $v ) {
			$str1[] = '`'.$k.'`';
			$str2[] = '\''.$v.'\'';
		}
		$str1 = '('.implode(',',$str1).')';
		$str2 = '('.implode(',',$str2).')';

		$table = $this->_table;

		$sql = "INSERT INTO `{$table}` {$str1} VALUES {$str2}";
		$this->query($sql);
		return true;
	}

	// 查询
	function find($table=null) {
		if ( empty($table) ) {
			$table = $this->_table;
		}

		$select = $this->parse_select();
		$where = $this->parse_where();
		$join = $this->parse_join();
		$order = $this->parse_order();
		$limit = $this->parse_limit();

		$sql = "SELECT {$select} FROM `{$table}`{$join}{$where}{$order}{$limit}";
		return $this->get($sql);
	}

	// 原生sql查询
	function get($sql) {
		$d = array(
			'total' => 0,
			'data' => array(),
		);
		$this->query($sql);
		$d['total'] = $this->num_rows();
		if ( $d['total'] ) {
			while ( $row = $this->fetch_assoc() ) {
				$d['data'][] = $row;
			}
		}
		return $d;
	}

	// 分页
	function page($query,$tag='p',$perpage=NULL) {
		$d = array(
			'tag' => $tag,
			'page' => 1,
			'page_max' => 1,
			'total' => 0,
			'count' => 0,
			'data' => array(),
		);

		if ( empty($perpage) ) {
			$perpage = C('perpage');
		}

		$tmp = preg_replace('/SELECT (.*) FROM/','SELECT count(*) as total FROM',$query);
		$rs = $this->get($tmp);
		$d['total'] = $rs['data'][0]['total'];

		if ( $d['total'] ) {
			$d['page_max'] = ceil($d['total']/$perpage);
		}
		$d['page'] = I('get.'.$tag,1);
		//if ( !is_numberic($page) ) $page=1;
		if ( $d['page'] > $d['page_max'] ) {
			$d['page'] = $d['page_max'];
		}

		if ( $d['total'] ) {
			$start = ($d['page']-1)*$perpage;
			$query .= " LIMIT {$start},{$perpage}";
			$rs1 = $this->get($query);
			$d['count'] = $rs1['total'];
			$d['data'] = $rs1['data'];
		}
		return $d;
	}

	// 解析select
	function parse_select() {
		$str = '';
		if ( empty($this->_select) ) {
			return '*';
		}

		$select = array();
		foreach ( $this->_select as $v ) {
			if ( strpos($v,'.') ) {
				$select[] = '`'.str_replace('.','`.`',$v).'`';
			} else {
				$select[] = "`{$v}`";
			}
		}

		if ( empty($select) ) {
			return '*';
		}

		$str = implode(',',$select);
		return $str;
	}

	// 解析where
	function parse_where() {
		$str = '';
		if ( empty($this->_where) ) {
			return $str;
		}

		$where = array();
		$logic = isset($this->_where['_logic']) ? $this->_where['_logic'] : 'AND';

		foreach ( $this->_where as $k => $v ) {
			if ( substr($k,1) == '_' ) {
				continue;
			}
			if ( strpos($k,'.') ) {
				$k = '`'.str_replace('.','`.`',$k).'`';
			} else {
				$k = "`{$k}`";
			}
			if ( is_array($v) ) {
				if ( !isset($v[0]) || !isset($v[1]) ) {
					continue;
				}
				$v[0]=strtoupper($v[0]);
				if ( !in_array($v[0],array('=','>','<','>=','<=','!=','<>','IN','~IN','LIKE','~LIKE','BETWEEN','~BETWEEN')) ) {
					$v[0] = '=';
				}
				$v[0] = str_replace('~','NOT ',$v[0]);
				$where[] = "{$k} {$v[0]} '{$v[1]}'";
			} else {
				$where[] = "{$k}='{$v}'";;
			}
		}

		if ( !empty($where) ) {
			$str = ' WHERE '.implode(" {$logic} ",$where);
		}
		return $str;
	}

	// 解析join
	function parse_join() {
		$str = '';
		if ( empty($this->_join) ) {
			return;
		}
		
		$join = $this->_join;
		$table = $this->_table;
		
		if ( !isset($join['type']) ) {
			return;
		}
		$type = strtoupper($join['type']);
		if ( !isset($join['table']) ) {
			return;
		}
		$tablejoin = $join['table'];

		if ( !in_array($type,array('','LEFT','RIGHT')) ) {
			return;
		}
		$str .= " {$type} JOIN `{$tablejoin}`";

		if ( isset($join['on']) ) {
			$on = $join['on'];
			if ( is_string($on) ) {
				$str .= " ON `{$table}`.`{$on}` = `{$tablejoin}`.`{$on}` ";
			}
			if ( is_array($on) ) {
				$str .= " ON `{$table}`.`{$on[0]}` = `{$tablejoin}`.`{$on[1]}` ";
			}
		}

		return $str;
	}

	function parse_order() {
		if ( empty($this->_order) ) {
			return;
		}

		$order = array();
		foreach ( $this->_order as $v ) {
			if ( is_array($v) ) {
				$order[] = "`{$v[0]}` {$v[1]}";
			} else {
				$order[] = "`{$v}`";
			}
		}
		
		if ( empty($order) ) {
			return;
		}

		$str = ' ORDER BY '.implode(',',$order);
		return $str;
	}

	function parse_limit() {
		if ( empty($this->_limit) ) {
			return;
		}
		$str = ' LIMIT '.$this->_limit;
		return $str;
	}

	function parse_group()
	{
		$sql = '';
		if ( $this->_group )
		{
			$sql = ' GROUP BY '.$this->_group;
		}
		return $sql;
	}

}
