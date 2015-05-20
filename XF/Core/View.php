<?php
defined('XF_PATH') OR exit('No direct script access allowed');

class X_View {
	
	public $_ob_level;
	public $_data = array();
	
	public function __construct() {
		// 引用已加载的类
		$this->x =& LoadClass();

		// 初始缓存等级
		$this->_ob_level = ob_get_level();

		LogWrite('View Class Initialized','debug');
	}

	// 输出模板
	public function load($str='',$data=array(),$return=FALSE) {
		$file = $this->_parse($str);
		if ( !file_exists($file) ) {
			LogWrite("Load View File error : {$file}",'error');
			return;
		}

		$this->set_data($data);

		ob_start();
		extract($this->_data,EXTR_OVERWRITE);

		$engine = C('view.engine');
		if ( $engine == 'php' ) {
			// 模板引擎直接使用php代码
			include($file);
		} else if ( $engine == 'test' ) {
			$content = file_get_contents($file);
			$left = C('view.left_tag');
			$right = C('view.right_tag');
			$pattern = array(
				'/('.$left.'=)(.*)('.$right.')/',
				'/('.$left.'#)(.*)('.$right.')/',
			);
			$replacement = array(
				'<?php echo $2 ?>',
				'<?php $2 ?>',
			);
			$content = preg_replace($pattern,$replacement,$content);
			eval('?>'.$content);
		} else {
			// 其他模板引擎
		}
		
		if ( $return == TRUE ) {
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		} else {
			ob_end_flush();
		}

	}

	// 定位模板文件
	public function _parse($str='') {
		if ( !$str ) {
			$str = _G._C.'/'._A;
		} else if ( strpos($str,'/') === FALSE ) {
			$str = _G._C.'/'.$str;
		} else if ( strpos($str,'/') === 0 ) {
			$str = trim($str,'/');
		} else {
			$str = _G.$str;
		}

		$file = APP_PATH.C('view_dir').'/'.$str.'.'.C('view.ext');

		return $file;
	}

	public function set_data($data=NULL,$val=NULL) {
		if ( $data === NULL ) {
			return;
		}
		if ( is_string($data) ) {
			$this->_data[$data] = $val;
		}
		if ( is_array($data) && count($data) > 0 ) {
			$this->_data = array_merge($this->_data,$data);
		}
		if ( is_object($data) ) {
			$this->_data = array_merge($this->_data,get_object_vars($data));
		}
	}

	public function get_data($key) {
		if ( isset($this->_data[$key]) ) {
			return $this->_data[$key];
		} else {
			return NULL;
		}
	}

}
