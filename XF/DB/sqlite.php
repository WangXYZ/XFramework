<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_DB_sqlite extends X_DB {

	function _connect($file) {
		return new sqlite3($file);
	}

	function _query($id,$sql) {
		return $id->query($sql);
	}

	function _set_charset($id,$str='utf-8') {
		return;
	}

	function _select_db($id,$str=null) {
		return;
	}

	function _error($id) {
		return $id->lastErrorMsg();
	}

	function _errno($id) {
		$code = $id->lastErrorCode();
		// 0: SQLITE_OK
		// 100: SQLITE_ROW
		// 101: SQLITE_DONE
		if ( $code == 100 || $code == 101 ) {
			$code = 0;
		}
		return $code;
	}

	function _fetch_array($rs) {
		return ($rs) ? $rs->fetchArray(SQLITE3_BOTH) : false;
	}

	function _fetch_num($rs) {
		return ($rs) ? $rs->fetchArray(SQLITE3_NUM) : false;
	}

	function _fetch_assoc($rs=0) {
		return ($rs) ? $rs->fetchArray(SQLITE3_ASSOC) : false;
	}

	function _num_rows($rs=0) {
		$count = 0;
		while ($rs->fetchArray()) {
			$count++;
		}
		return $count;
	}

	function _close($id) {
		return ($id) ? $id->close() : false;
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
