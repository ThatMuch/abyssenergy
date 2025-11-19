<?php

/**
 * Template Name: Classic Page
 *
 * Template pour afficher le contenu d'une page classique
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header();
get_template_part('template-parts/page-header');
?>

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
