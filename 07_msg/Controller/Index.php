<?php

class Index_Controller extends X_Controller {

	public function index() {
		$this->_view();
	}

	public function page1() {
		msg('成功信息',1);
	}

	public function page2() {
		msg('提示信息',2);
	}

	public function page3() {
		msg('错误信息',3);
	}

	public function page4() {
		msg('成功信息',1,U('index'));
	}

}
