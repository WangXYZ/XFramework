<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_DB_mysqli extends X_DB {

	function _connect($host,$user,$pass,$port) {
		return mysqli_connect($host,$user,$pass,NULL,$port);
	}

	function _select_db($id,$name) {
		return mysqli_select_db($id,$name);
	}

	function _set_charset($id,$charset) {
		return mysqli_set_charset($id,"SET NAMES '{$charset}'");
	}

	function _query($id,$sql) {
		return mysqli_query($id,$sql);
	}

	function _error($id) {
		return mysqli_error($id);
	}

	function _errno($id) {
		return mysqli_errno($id);
	}

	function _result($rs=0,$row=0,$field=NULL) {
		return ($rs) ? mysqli_result($rs,$row,$field) : FALSE;
	}

	function _fetch_array($rs=0) {
		return ($rs) ? mysqli_fetch_array($rs) : FALSE;
	}

	function _fetch_assoc($rs=0) {
		return ($rs) ? mysqli_fetch_assoc($rs) : FALSE;
	}

	function _num_fields($rs=0) {
		return ($rs) ? mysqli_num_fields($rs) : FALSE;
	}

	function _num_rows($rs=0) {
		return ($rs) ? mysqli_num_rows($rs) : FALSE;
	}

	function _affected_rows($rs=0) {
		return ($rs) ? mysqli_affected_rows($rs) : FALSE;
	}

	function _insert_id($rs=0) {
		return ($rs) ? mysqli_insert_id($rs) : FALSE;
	}

	function _free_result($rs=0) {
		return ($rs) ? mysqli_free_result($rs) : FALSE;
	}

	function _field_type($rs=0,$field_offset) {
		return ($rs) ? mysqli_field_type($id,$field_offset) : FALSE;
	}

	function _field_name($rs=0,$field_offset) {
		return ($rs) ? mysqli_field_name($id,$field_offset) : FALSE;
	}

	function _close($rs=0) {
		return ($rs) ? mysqli_close($rs) : FALSE;
	}

	function _start_transaction() {
		return;
	}

	function _end_transaction() {
		return;
	}

}
