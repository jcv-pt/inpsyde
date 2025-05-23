<?php

declare(strict_types = 1);

?>
<h2><?= esc_html(__('Inpsyde Settings'));?></h2>
<form action="options.php" method="post">
    <?php
        settings_fields('inpsyde_settings');
        do_settings_sections('inpsyde');
    ?>
    <input 
        name="submit" 
        class="button button-primary" 
        type="submit" 
        value="<?= esc_html(__('Save')); ?>" 
    />
</form>