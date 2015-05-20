<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_DB_mysql extends X_DB {

	function _connect($addr,$user,$pass,$new=TRUE) {
		return mysql_connect($addr,$user,$pass,$new);
	}

	function _select_db($id,$name) {
		return mysql_select_db($name,$id);
	}

	function _set_charset($id,$charset) {
		return mysql_query("SET NAMES '{$charset}'",$id);
	}

	function _query($id,$sql) {
		return mysql_query($sql,$id);
	}

	function _error($id) {
		return mysql_error($id);
	}

	function _errno($id) {
		return mysql_errno($id);
	}

	function _result($rs=0,$row=0,$field=NULL) {
		return ($rs) ? mysql_result($rs,$row,$field) : FALSE;
	}

	function _fetch_array($rs=0) {
		return ($rs) ? mysql_fetch_array($rs) : FALSE;
	}

	function _fetch_assoc($rs=0) {
		return ($rs) ? mysql_fetch_assoc($rs) : FALSE;
	}

	function _num_fields($rs=0) {
		return ($rs) ? mysql_num_fields($rs) : FALSE;
	}

	function _num_rows($rs=0) {
		return ($rs) ? mysql_num_rows($rs) : FALSE;
	}

	function _affected_rows($rs=0) {
		return ($rs) ? mysql_affected_rows($rs) : FALSE;
	}

	function _insert_id($rs=0) {
		return ($rs) ? mysql_insert_id($rs) : FALSE;
	}

	function _free_result($rs=0) {
		return ($rs) ? mysql_free_result($rs) : FALSE;
	}

	function _field_type($rs=0,$field_offset) {
		return ($rs) ? mysql_field_type($id,$field_offset) : FALSE;
	}

	function _field_name($rs=0,$field_offset) {
		return ($rs) ? mysql_field_name($id,$field_offset) : FALSE;
	}

	function _close($rs=0) {
		return ($rs) ? mysql_close($rs) : FALSE;
	}

	function _start_transaction() {
		return;
	}

	function _end_transaction() {
		return;
	}

}
