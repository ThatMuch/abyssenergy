<?php

/**
 * Block Name: Sectors List
 * Description: Affiche les secteurs dans une liste verticale.
 */

// Paramètres du bloc avec valeurs par défaut
$title = get_field('title');
$selection_type = get_field('selection_type') ?: 'all';
$specific_sectors = get_field('specific_sectors');
// Classes du bloc
$classes = 'sectors-grid'; // On garde le nom de classe pour la compatibilité CSS
if (!empty($block['className'])) {
	$classes .= ' ' . $block['className'];
}

// Requête pour obtenir les secteurs
$args = array(
	'post_type'      => 'sector',
	'posts_per_page' => -1, // Toujours afficher tous les secteurs
	'orderby'        => 'menu_order', // Tri par défaut par ordre personnalisé
	'order'          => 'ASC', // Toujours en ordre ascendant
	'post_status'    => 'publish',
);

// Si on veut des secteurs spécifiques
if ($selection_type === 'specific' && !empty($specific_sectors)) {
	$args['post__in'] = $specific_sectors;
	$args['orderby'] = 'post__in'; // Respecter l'ordre de sélection
}

$sectors_query = new WP_Query($args);
?>

<section class="<?php echo esc_attr($classes); ?>">
	<div class="container">
		<?php if ($title) : ?>
			<div class="sectors-grid__header">
				<h2 class="sectors-grid__title"><?php echo esc_html($title); ?></h2>
			</div>
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
							<?php if ($image) : ?>
								<div class="sectors-list__item__image">
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>">
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
