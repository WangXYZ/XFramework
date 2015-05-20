<?php

class Index_Controller extends X_Controller {

	public function index() {
		$sql = "SELECT * FROM Message";
		$dt = $this->db->get($sql);

		$this->_set('dt',$dt);
		$this->_view();
	}

	public function add() {
		$this->_view();
	}

	public function do_add() {
		$Content = I('post.Content');

		if ( !$Content ) msg('err.empty_field');

		$sql = "INSERT INTO Message (Content) VALUES ('{$Content}')";
		$rs = $this->db->execute($sql);

		if ( !$rs ) msg('err.data_write_error');
		R('index');
	}

	public function edit() {
		$id = I('get.id');
		if ( !$id ) msg('err.parameter_error');

		$sql = "SELECT * FROM Message WHERE Id='{$id}'";
		$dt = $this->db->get($sql);

		if ( $dt['total'] == 0 ) msg('err.record_not_exist');

		$dr = $dt['data'][0];

		$this->_set('id',$id);
		$this->_set('dr',$dr);
		$this->_view();
	}

	public function do_edit() {
		$id = I('get.id');
		if ( !$id ) msg('err.parameter_error');

		$Content = I('post.Content');

		if ( !$Content ) msg('err.empty_field');

		$sql = "UPDATE Message SET Content='{$Content}' WHERE Id='{$id}'";
		$rs = $this->db->execute($sql);

		if ( !$rs ) msg('err.data_write_error');
		R('index');
	}

}
