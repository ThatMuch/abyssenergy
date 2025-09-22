<?php

/**
 * Template Name: Classic Page
 *
 * Template pour afficher le contenu d'une page classique
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

$subtitle = get_field('subtitle');
$description = get_field('description');

get_header()
?>
<div class="page-header">
	<div class="container">
		<div class="row">
			<div class="col col-md-7">
				<h1><?php the_title(); ?></h1>
				<?php if ($subtitle): ?>
					<?php echo $subtitle; ?>
				<?php endif; ?>
				<?php if ($description): ?>
					<div class="page-description">
						<?php echo $description; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="col col-md-5">
				<?php if (has_post_thumbnail()): ?>
					<div class="page-thumbnail">
						<?php the_post_thumbnail('medium'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</div>
</div>

<main>
	<div class="container my-5">
		<?php
		while (have_posts()) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</main>

<?php get_footer(); ?>
