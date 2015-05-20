<?php $this->load('/header') ?>

<form name="form1" method="post" action="<?=U('do_add')?>">
<input name="Content" />
<button type="submit"><?=L('submit')?></button>
</form>

<?php $this->load('/footer') ?>