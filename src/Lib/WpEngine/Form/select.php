<?php

declare(strict_types = 1);

//Default template for select

?>
<select 
    id="<?= esc_html($id);?>" 
    name="<?= esc_html($name);?>"
    <?= (isset($class) ? esc_html(' class="'.$class.'" ') : esc_html(''));?> 
    <?= (isset($style) ? esc_html(' style="'.$style.'" ') : esc_html(''));?>
>
<?php if (isset($options)) :?>
    <?php foreach ($options as $key => $text) :
        $key = (string)$key;
    
        if (isset($value)) {
            $value = (string)$value;
        }
    
        ?>
    <option 
        value="<?= esc_html($key);?>" 
        <?= (isset($value) && $key === $value ? esc_html('selected="selected"'): esc_html(''));?>
    >
        <?= esc_html($text);?>
    </option>
    <?php endforeach;?>
<?php endif;?>
</select>

<?php if (isset($note)) :?>
<br><small><?= esc_html($note);?></small>
<?php endif;?>