<?php

/**
 * Project Showcase Block Template.
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
$class_name = 'project-showcase-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title');
$subtitle = get_field('subtitle');
$show_title = get_field('show_title') !== false;
$selected_sectors = get_field('project_sectors');
$projects_count = get_field('projects_count') ?: 3;
$layout = get_field('layout') ?: 'horizontal';
$show_excerpt = get_field('show_excerpt') !== false;
$show_sector = get_field('show_sector') !== false;
$show_button = get_field('show_button') !== false;
$button_text = get_field('button_text') ?: 'Voir tous nos projets';
$button_url = get_field('button_url');

// Ajouter la classe de layout
$class_name .= ' layout-' . $layout;

// Limiter le nombre de projets à 3 maximum
$projects_count = min($projects_count, 3);

// Définir les arguments de requête
$args = [
	'post_type' => 'project-showcase',
	'posts_per_page' => $projects_count,
	'orderby' => 'date',
	'order' => 'DESC',
	'post_status' => 'publish'
];

// Filtrer par secteur si des secteurs sont sélectionnés
if (!empty($selected_sectors)) {
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'project-sector',
			'field'    => 'id',
			'terms'    => $selected_sectors,
		),
	);
}

// Exécuter la requête
$query = new WP_Query($args);

// Vérifier si on est en mode aperçu
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc Projets Showcase. Les projets réels s\'afficheront sur le site.', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>

	<!-- Project Showcase Block -->
	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section">
		<div class="container">
			<?php if ($show_title) : ?>
				<div class="section-header mb-5">
					<?php if ($subtitle) : ?>
						<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
					<?php endif; ?>
					<?php if ($title) : ?>
						<h2 class="section--title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($query->have_posts()) : ?>
				<div class="projects-grid">
					<?php $project_index = 0; ?>
					<?php while ($query->have_posts()) : $query->the_post(); ?>
						<?php
						$project_index++;
						$is_featured = ($layout === 'featured' && $project_index === 1);
						$excerpt_limit = $is_featured ? 150 : 100;

						// Utiliser le template part pour afficher la carte
						get_template_part('template-parts/project-card', null, [
							'show_excerpt' => $show_excerpt,
							'show_sector' => $show_sector,
							'is_featured' => $is_featured,
							'excerpt_limit' => $excerpt_limit
						]);
						?>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>

				<?php if ($show_button && $button_url) : ?>
					<div class="text-center mt-4">
						<a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary">
							<?php echo esc_html($button_text); ?>
						</a>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<div class="alert alert-info">
					<p><?php _e('Aucun projet trouvé pour les critères sélectionnés.', 'abyssenergy'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

<?php endif; ?>
