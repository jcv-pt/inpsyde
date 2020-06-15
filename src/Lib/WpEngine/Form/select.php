<?php

//Default template for select

?>
<select id="<?= $id;?>" name="<?= $name;?>"<?= (isset($class) ? ' class="'.$class.'" ' : '');?> <?= (isset($style) ? ' style="'.$style.'" ' : '');?>>
<?php if(isset($options)):?>
	<?php foreach($options as $key => $text):?>
	<option value="<?= $key;?>" <?= (isset($value) && $key == $value ? 'selected="selected"': '');?>><?= $text;?></option>
	<?php endforeach;?>
<?php endif;?>
</select>

<?php if(isset($note)):?>
<br><small><?= $note;?></small>
<?php endif;?>