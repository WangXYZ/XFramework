<?php $this->load('/header') ?>

<form name="form1" method="post" action="<?=U('do_edit',array('id'=>$id))?>">
<input name="Content" value="<?=$dr['Content']?>"/>
<button type="submit">Submit</button>
</form>

<?php $this->load('/footer') ?>