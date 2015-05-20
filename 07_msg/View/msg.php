<? $this->load('/header')?>

<h2><?=L('msg.title_'.$msg['mode'])?></h2>

<p><?=L($msg['text'])?></p>

<p>
<?if($msg['mode']==1):?>
	<?if($msg['url']):?>
		<button onclick="window.location.replace('<?=$msg['url']?>')"><?=L('ok')?></button>
	<?else:?>
		<button onclick="window.close()"><?=L('close')?></button>
	<?endif;?>
<?else:?>
	<button onclick="window.history.back()"><?=L('back')?></button>
<?endif;?>
</p>

<? $this->load('/footer')?>