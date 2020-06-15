<?php

//Default template for textfield

?>
<input id="<?= $id;?>" name="<?= $name;?>"<?= (isset($class) ? ' class="'.$class.'" ' : '');?>type="<?= $type;?>" value="<?= $value;?>"<?= (isset($style) ? ' style="'.$style.'" ' : '');?>/>

<?php if(isset($note)):?>
<br><small><?= $note;?></small>
<?php endif;?>