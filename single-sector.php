<?php

/**
 * Template pour afficher un secteur unique avec ses emplois associés
 *
 * @package AbyssEnergy
 */

get_header();
get_template_part('template-parts/page-header', null, array(
	'thumbnail_size' => 'full',
	'row_classes' => 'align-items-center'
));
?>
<?php the_content(); ?>

<?php get_footer(); ?>
