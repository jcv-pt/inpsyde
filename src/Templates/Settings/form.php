<h2><?= __('Inpsyde Settings');?></h2>
<form action="options.php" method="post">
	<?php
		settings_fields('inpsyde_settings');
		do_settings_sections('inpsyde'); 
	?>
	<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
</form>