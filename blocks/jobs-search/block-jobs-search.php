<?php

/**
 * Jobs Search Block Template.
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
$class_name = 'jobs-search-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Trouvez votre prochaine opportunité';
$text = get_field('text') ?: 'Découvrez nos offres d\'emploi et rejoignez notre équipe dynamique.';
$search_placeholder = get_field('search_placeholder') ?: 'Trouvez votre prochain poste...';
$button_text = get_field('button_text') ?: '';
$image = get_field('image');
$background_color = get_field('background_color') ?: 'light';

// Ajouter la classe de couleur de fond
if ($background_color && $background_color !== 'none') {
	$class_name .= ' bg-' . $background_color;
}

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc de recherche d\'emplois. Le formulaire sera fonctionnel sur le site.', 'abyssenergy'); ?></p>
		<?php if ($image) : ?>
			<p><small><?php _e('Image sélectionnée', 'abyssenergy'); ?></small></p>
		<?php endif; ?>
	</div>
<?php else : ?>

	<!-- Jobs Search Block -->
	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section">
		<div class="container">
			<div class="jobs-search-content">
				<!-- Contenu de gauche -->
				<div class="jobs-search-text-content">
					<div class="jobs-search-content-wrapper">
						<h2 class="jobs-search-title"><?php echo esc_html($title); ?></h2>

						<?php if ($text) : ?>
							<p class="jobs-search-description"><?php echo esc_html($text); ?></p>
						<?php endif; ?>

						<!-- Formulaire de recherche -->
						<div class="jobs-search-form-wrapper">
							<form action="<?php echo esc_url(home_url('/search-jobs/')); ?>" method="GET" class="jobs-search-form  d-flex gap-2">

								<input
									type="text"
									id="job-search-<?php echo uniqid(); ?>"
									name="job_search"
									placeholder="<?php echo esc_attr($search_placeholder); ?>"
									value="<?php echo esc_attr(get_query_var('job_search')); ?>"
									aria-label="<?php esc_attr_e('Rechercher un emploi', 'abyssenergy'); ?>">
								<button type="submit" class="btn btn--primary btn--icon" aria-label="Search"><i class="fas fa-search"></i></button>
							</form>
						</div>
					</div>
				</div>

				<!-- Image de droite -->
				<?php if ($image) : ?>
					<div class="jobs-search-image-content">
						<?php echo wp_get_attachment_image($image, 'full', false, array(
							'loading' => 'lazy',
							'class' => 'jobs-search-image'
						)); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

<?php endif; ?>
