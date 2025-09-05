<?php

/**
 * Jobs Listing Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 */

// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'jobs-listing-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Connecting Professionals';
$subtitle = get_field('subtitle') ?: 'Jobs & offers';
$show_title = get_field('show_title') !== false;
$posts_per_page = get_field('posts_per_page') ?: 6;
$show_button = get_field('show_button') !== false;
$button_text = get_field('button_text') ?: 'See more jobs';
$button_url = get_field('button_url') ?: site_url('/search-jobs');
$selected_sectors = get_field('job_sectors');

// Définir les arguments de requête
$args = [
	'post_type' => 'job',
	'posts_per_page' => $posts_per_page,
	'orderby' => 'date',
	'order' => 'DESC'
];

// Filtrer par secteur si des secteurs sont sélectionnés
if (!empty($selected_sectors)) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'job-sector',
			'field'    => 'id',
			'terms'    => $selected_sectors,
		),
	);
}

// Exécuter la requête
$query = new WP_Query($args);
?>

<!-- Jobs Listing Block -->
<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?>">
	<div class="container">
		<?php
		// Passer tous les paramètres nécessaires au template
		get_template_part('template-parts/section-jobs', null, [
			'query' => $query,
			'button_text' => $button_text,
			'button_url' => $button_url,
			'show_button' => $show_button,
			'title' => $title,
			'subtitle' => $subtitle
		]);
		?>
	</div>
</section>
