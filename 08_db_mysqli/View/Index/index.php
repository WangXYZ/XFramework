<?php $this->load('/header') ?>

<h1>Message</h1>

<? if($dt['total']==0): ?>
<p><?=L('err.record_not_exist')?></p>
<? else: ?>
<ul>
<? foreach($dt['data'] as $v): ?>
	<li><a href="<?=U('edit',array('id'=>$v['Id']))?>"><?=L('edit')?></a> <?=$v['Content']?></li>
<? endforeach; ?>
</ul>
<? endif; ?>

<?php $this->load('/footer') ?>