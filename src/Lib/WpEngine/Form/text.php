<?php

declare(strict_types = 1);

//Default template for textfield

?>
<input 
    id="<?= esc_html($id);?>" 
    name="<?= esc_html($name);?>"
    <?= (isset($class) ? esc_html(' class="'.$class.'" ') : esc_html(''));?>
    type="<?= esc_html($type);?>" 
    value="<?= esc_html($value);?>"
    <?= (isset($style) ? esc_html(' style="'.$style.'" ') : esc_html(''));?>
/>

<?php if (isset($note)) :?>
<br><small><?= esc_html($note);?></small>
<?php endif;?>