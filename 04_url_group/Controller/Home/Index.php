<?php

class Index_Controller extends X_Controller {

	public function index() {
		echo '
<h1>/Entry/Index/index</h1>
<p>/</p>
<p><a href="'.U('/').'">/Index/index</a></p>
<p>/Home</p>
<p>/Home/Index/index | <a href="'.U('show').'">/Home/Index/show</a></p>
<p>/Entry</p>
<p><a href="'.U('/Entry').'">/Entry/Index/index</a> | <a href="'.U('/Entry/Index/show').'">/Entry/Index/show</a></p>
';
	}

	public function show() {
		echo '
<h1>/Entry/Index/index</h1>
<p>/</p>
<p><a href="'.U('/').'">/Index/index</a></p>
<p>/Home</p>
<p><a href="'.U('index').'">/Home/Index/index</a> | /Home/Index/show</p>
<p>/Entry</p>
<p><a href="'.U('/Entry').'">/Entry/Index/index</a> | <a href="'.U('/Entry/Index/show').'">/Entry/Index/show</a></p>
';
	}

}
