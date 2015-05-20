<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_Log {

	protected $enabled = false;
	protected $file = false;
	protected $show = false;

	protected $path;
	protected $level = 1;
	protected $date_fm = 'Y-m-d H:i:s';
	protected $option = array(
		'ERROR' => 1,
		'DEBUG' => 2,
		'FILE' => 3,
		'SQL' => 4,
		'INFO' => 5,
		'ALL' => 6
	);
	
	protected $elapsed_time;
	protected $start_time;
	protected $end_time;
	protected $decimals = 4;

	protected $list_file = '';
	protected $log_file = '';
	protected $data = array();

	//protected $ext = 'php';

	/*
	* Class constructor
	*
	* @return     void
	*/
	public function __construct() {
		$this->start_time = _START_TIME;

		if ( C('log.enabled') == true && C('app_mode') == 'development' ) {
			$this->enabled = true;
		} else {
			return;
		}

		if ( C('log.file') == true ) {
			$this->file = true;

			$dir = C('log_dir') ? C('log_dir') : 'Log' ;
			$this->path = APP_PATH.$dir.'/';
			file_exists($this->path) OR mkdir($this->path,0777,TRUE);

			if ( !is_dir($this->path) ) {
				$this->file = false;
			}

			$this->list_file = C('log.list_file');
		}

		if ( C('log.date_format') ) {
			$this->date_fm = C('log.date_format');
		}

		$this->level = C('log.level');
	}

	private function write_list() {
		if ( $this->enabled == false || $this->file == false ) {
			return false;
		}
		
		$array = array(
			'name' => substr(md5(rand()),0,8),
			'datetime' => date($this->date_fm,$_SERVER["REQUEST_TIME"]),
			'ip' => $_SERVER["REMOTE_ADDR"],
			'method' => $_SERVER["REQUEST_METHOD"],
			'url' => str_replace(',','',$_SERVER["REQUEST_URI"]),
			'time' => $_SERVER["REQUEST_TIME"],
		);
		$this->log_file = $array['name'];
		$msg = implode(',',$array)."\n";

		$file = $this->path.$this->list_file;
		if ( !$fp = @fopen($file,'ab') ) {
			$this->file = false;
			return false;
		}
		fwrite($fp,$msg);
		fclose($fp);
	}

	private function write_log() {
		if ( $this->enabled == false || $this->file == false ) {
			return false;
		}

		$file = $this->path.$this->log_file;
		if ( file_exists($file) ) {
			unlink($file);
		}
		
		$msg = '';
		foreach ( $this->data as $v ) {
			$msg .= "[{$v['level']}] [{$v['time']}] {$v['message']} \n";
		}

		if ( !$fp = @fopen($file,'ab') ) {
			$this->file = false;
			return false;
		}
		fwrite($fp,$msg);
		fclose($fp);
	}

	public function write($message,$level){
		$this->data[] = array(
			'level' => $level,
			'message' => $message,
			'time' => $this->diff()
		);
	}

	public function diff($end=NULL,$start=NULL) {
		if ( empty($end) ) $end = microtime(TRUE);
		if ( empty($start) ) $start = $this->start_time;
		return number_format($end-$start,$this->decimals);
	}

	public function Show() {
		$tmp = '<hr/><table border="1" cellpadding="5" cellspacing="0"><tbody>';
		foreach( $this->data as $v ) {
			$tmp .= "<tr><td>{$v['level']}</td><td>{$v['time']}</td><td>{$v['message']}</td></tr>";
		}
		$tmp .= '</tbody></table>';
		echo $tmp;
		var_dump($_SESSION);
		var_dump($_COOKIE);
	}

	public function Finish () {
		$this->write("---------- End ----------",'debug');
		$this->end_time = microtime(true);

		if ( C('log.show') && C('app_mode') == 'development' && C('is_ajax') == false ) {
			$this->Show();
		}

		$this->write_list();
		$this->write_log();
	}

}
