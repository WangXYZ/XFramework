<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_DB_sqlsrv extends X_DB {

	function _connect($host,$array) {
		return sqlsrv_connect($host,$array);
	}

	function _query($id,$sql) {
		$param = array();
		$option = array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		return sqlsrv_query($id,$sql,$param,$option);
	}
	
	function _execute($id,$sql) {
		return sqlsrv_execute($id,$sql);
	}

	function _set_charset($id,$str='utf-8') {
		return;
	}

	function _select_db($id,$str=null) {
		return;
	}

	function _error($id) {
		$e = sqlsrv_errors();
		if ( isset($e[0]) ) {
			return $e[0]['message'];
		}
		return ;
	}

	function _errno($id) {
		$e = sqlsrv_errors();
		if ( isset($e[0]) ) {
			return $e[0]['code'];
		}
		return;
	}

	function _fetch_array($rs=0) {
		return ($rs) ? sqlsrv_fetch_array($rs,SQLSRV_FETCH_BOTH) : false;
	}

	function _fetch_num($rs=0) {
		return ($rs) ? sqlsrv_fetch_array($rs,SQLSRV_FETCH_NUM) : false;
	}

	function _fetch_assoc($rs=0) {
		return ($rs) ? sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC) : false;
	}

	function _num_rows($rs=0) {
		return ($rs) ? sqlsrv_num_rows($rs) : false;
	}

	function _close($id) {
		return ($id) ? sqlsrv_close($id) : false;
	}



	function _result($rs=0,$row=0,$field=null) {
		return;
	}

	function _num_fields($rs=0) {
		return;
	}

	function _affected_rows($rs=0) {
		return;
	}

	function _insert_id($rs=0) {
		return;
	}

	function _free_result($rs=0) {
		return;
	}

	function _field_type($rs=0,$field_offset) {
		return;
	}

	function _field_name($rs=0,$field_offset) {
		return;
	}

	function _start_transaction() {
		return;
	}

	function _end_transaction() {
		return;
	}

}
