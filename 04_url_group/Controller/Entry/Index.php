<?php

class Index_Controller extends X_Controller {

	public function index() {
		echo '
<h1>/Entry/Index/index</h1>
<p>/</p>
<p><a href="'.U('/').'">/Index/index</a></p>
<p>/Home</p>
<p><a href="'.U('/Home').'">/Home/Index/index</a> | <a href="'.U('/Home/Index/show').'">/Home/Index/show</a></p>
<p>/Entry</p>
<p>/Entry/Index/index | <a href="'.U('show').'">/Entry/Index/show</a></p>
';
	}

	public function show() {
		echo '
<h1>/Entry/Index/index</h1>
<p>/</p>
<p><a href="'.U('/').'">/Index/index</a></p>
<p>/Home</p>
<p><a href="'.U('/Home').'">/Home/Index/index</a> | <a href="'.U('/Home/Index/show').'">/Home/Index/show</a></p>
<p>/Entry</p>
<p><a href="'.U('index').'">/Entry/Index/index</a> | /Entry/Index/show</p>
';
	}

}
