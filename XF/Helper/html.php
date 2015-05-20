<?php

// 创建链接
function html_anchor($text,$url,$part=NULL) {
	return '<a href="'.$url.'" '.$part.'>'.$text.'</a>';
}

// 创建按钮
function html_button($text,$type='submit',$part=NULL) {
	$array = array('submit','button');
	if ( array_search($array,$type) === FALSE ) {
		$type = $array[0];
	}
	return '<button type="'.$type.'">'.$text.'</button>';
}

// <select>
function html_select($name,$array=array(),$value=NULL,$default=NULL,$part=NULL) {
	$tmp = '<select name="'.$name.'" '.$part.'>';
	if ( $default !== NULL ) {
		$tmp .= '<option value="'.$default.'">'.L('please_select').'</option>';
	}
	foreach ( $array as $v ) {
		if ( is_array($v) ) {
			$v = array_values($v);
			$data1 = $v[0];
			$data2 = $v[1];
			$data3 = isset($v[2]) ? $v[2] : NULL;
		} else {
			$data1 = $data2 = $v;
			$data3 = NULL;
		}
		$select = $value==$data1 ? 'selected="selected"' : NULL;
		if ( $data3 ) {
			$tmp .= '<optgroup label="'.$data2.'" />';
		} else {
			$tmp .= '<option value="'.$data1.'" '.$select.'>'.$data2.'</option>';
		}
	}
	$tmp .= '</select>';
	return $tmp;
}

// input radio
function html_radio($name,$array,$value=NULL) {
	$tmp = '';
	foreach( $array as $v ){
		if ( is_array($v) ) {
			$v = array_values($v);
			$data1 = $v[0];
			$data2 = $v[1];
		} else {
			$data1 = $data2 = $v;
		}
		$check = $value==$data1 ? 'checked="checked"' : NULL;
		$id = $name.'_'.$data1;
		$tmp .= '<label for="'.$id.'"><input type="radio" name="'.$name.'" id="'.$id.'" value="'.$data1.'" '.$check.'/> '.$data2.'</label> &nbsp; ';
	}
	return $tmp;
}

// input checkbox
function html_checkbox($name,$array,$value=array()) {
	$tmp = '';
	foreach( $array as $v ){
		if ( is_array($v) ) {
			$v = array_values($v);
			$data1 = $v[0];
			$data2 = $v[1];
		} else {
			$data1 = $data2 = $v;
		}
		$check = array_search($data1,$value)===FALSE ? NULL : 'checked="checked"';
		$id = $name.'_'.$data1;
		$tmp .= '<label for="'.$id.'"><input type="checkbox" name="'.$name.'" id="'.$id.'" value="'.$data1.'" '.$check.'/> '.$data2.'</label> &nbsp; ';
	}
	return $tmp;
}

// 创建删除按钮
function html_button_del($id) {
	$tmp = '<a href="'.U('del').'?id='.$id.'" class="button" onclick="return confirm(\''.L('confirm_delete').'\')">'.L('delete').'</a>';
	return $tmp;
}
