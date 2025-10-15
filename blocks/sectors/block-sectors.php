<?php

/** Block name: Sectors Grid
 * Description: Affiche une grille de secteurs avec leurs informations.
 */

// Créer le nom de la classe
$className = 'sectors-grid';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$title = get_field('title');
$subtitle = get_field('subtitle');
$description = get_field('description');
$selection_type = get_field('selection_type') ?: 'all';
$specific_sectors = get_field('specific_sectors');

// Requête pour obtenir les secteurs
$args = array(
	'post_type'      => 'sector',
	'posts_per_page' => -1, // Toujours afficher tous les secteurs
	'orderby'        => 'menu_order', // Tri par défaut par ordre personnalisé
	'order'          => 'DESC', // Toujours en ordre ascendant
	'post_status'    => 'publish',
);

// Si on veut des secteurs spécifiques
if ($selection_type === 'specific' && !empty($specific_sectors)) {
	$args['post__in'] = $specific_sectors;
	$args['orderby'] = 'post__in'; // Respecter l'ordre de sélection
}

$sectors_query = new WP_Query($args);
?>
<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<?php if ($subtitle) : ?>
			<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
		<?php endif; ?>
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc Sectors. Les secteurs réels s\'afficheront sur le site.', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>
	<section class="<?php echo esc_attr($className); ?>">
		<div class="container">
			<?php if ($subtitle) : ?>
				<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
			<?php endif; ?>
			<?php if ($title) : ?>
				<h2><?php echo esc_html($title); ?></h2>
			<?php endif; ?>
			<?php if ($description) : ?>
				<p class="sectors-description"><?php echo esc_html($description); ?></p>
			<?php endif; ?>

			<?php if ($sectors_query->have_posts()) : ?>
				<div class="sectors-list">
					<ul class="sectors-list__items">
						<?php while ($sectors_query->have_posts()) : $sectors_query->the_post();
							$sector_id = get_the_ID();
							$permalink = get_permalink($sector_id);
							$excerpt = get_the_excerpt();
							$category = get_the_terms($sector_id, 'sector-category')[0]->name;
							$image = get_field('image', $sector_id);

						?>
							<li class="sectors-list__item card <?php echo $category ?>">
								<div class="sectors-list__item-inner">
									<h3 class="sectors-list__item-title">
										<?php the_title(); ?>
									</h3>
									<?php if ($excerpt) : ?>
										<div class="sectors-list__excerpt">
											<?php echo wp_kses_post($excerpt); ?>
										</div>
									<?php endif; ?>

									<a href="<?php echo esc_url($permalink); ?>" class="btn btn--primary">
										Learn more about <?php the_title(); ?>
									</a>
								</div>
								<?php if (has_post_thumbnail()) : ?>
									<div class="sectors-list__item__image">
										<img src="<?php echo the_post_thumbnail_url('full'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
									</div>
								<?php endif; ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
			<?php else : ?>
				<div class="sectors-list__empty">
					<p>Aucun secteur trouvé.</p>
				</div>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
<?php endif; ?>
