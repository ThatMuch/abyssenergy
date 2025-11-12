<?php
get_header();
$subtitle = safe_get_field_with_default('subtitle', false, '');
?>

<div class="page-header <?php if (has_post_thumbnail()): ?>has-thumbnail<?php endif; ?>">
	<div class="container">
		<?php if ($subtitle) :  echo $subtitle; ?>
		<?php
		else :  the_title('<h1 class="page-title">', '</h1>');
		endif; ?>
		<?php echo  do_shortcode('[searchandfilter id="2295"]') ?>

	</div>
	<div class="animation-wrapper">
		<dotlottie-wc
			src="https://lottie.host/5e7b71b8-c2cc-484d-a146-e1591728a60f/oE4g6fX5ZY.lottie"
			class="animation"
			autoplay
			speed="1"
			loop></dotlottie-wc>
		<dotlottie-wc
			src="https://lottie.host/de0fcd03-4596-4745-9419-9ab3b2b52051/kvXmER9NpT.lottie"
			class="animation animation-pales"
			autoplay
			loop></dotlottie-wc>
	</div>
</div>
<main>
	<?php the_content(); ?>
</main>
<?php
get_footer()
?>
