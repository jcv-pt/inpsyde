<?php

//Get header

get_header();

?>

<main id="site-content" role="main">
	<div class="main-inner section-inner">
		<?= (isset($content) ? $content : '');?>
	</div>
</main>

<?php

//Get footer

get_footer();
