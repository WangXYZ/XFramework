<?php

class Index_Controller extends X_Controller {

	public function index() {
		echo '
<h1>/Index/index</h1>
<p>/</p>
<p>/Index/index</p>
<p>/Home</p>
<p><a href="'.U('/Home').'">/Home/Index/index</a> | <a href="'.U('/Home/Index/show').'">/Home/Index/show</a></p>
<p>/Entry</p>
<p><a href="'.U('/Entry').'">/Entry/Index/index</a> | <a href="'.U('/Entry/Index/show').'">/Entry/Index/show</a></p>
';
	}

}
