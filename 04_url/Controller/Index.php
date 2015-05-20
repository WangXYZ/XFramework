<?php

class Index_Controller extends X_Controller {

	public function index() {
		echo '
<h1>Index/index</h1>
<p>Index/index</p>
<p><a href="'.U('home').'">Index/home</a></p>
';
	}

	public function home() {
		echo '
<h1>Index/home</h1>
<p><a href="'.U('index').'">Index/index</a></p>
<p>Index/home</p>
';
	}

}
