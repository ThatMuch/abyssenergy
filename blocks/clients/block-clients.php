<?php

/**
 * Clients slider block template.
 * @param  array $block The block settings and attributes.
 * @param  string $content The block inner HTML (empty).
 * @param  bool $is_preview True during backend preview render.
 * @param  int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param  array $context The context provided to the block by the post or its parent block.
 *
 */

// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'clients-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Our clients';
$show_title = get_field('show_title') !== false;
$gallery = get_field('gallery') ?: array();

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du slider des clients. Les logos des clients ne seront pas affichés dans l\'éditeur.', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>
	<?php if ($gallery) : ?>
		<!-- Clients Slider Block -->

		<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>">
			<div class="container">
				<div class="d-flex gap-4 align-items-center mb-4">
					<?php if ($show_title) : ?>
						<h2 class="section-title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<div class="clients-slider">
						<div id="clients-slider-inner" class="clients-slider-inner">
							<?php foreach ($gallery as $image) : ?>
								<div class="client-logo">
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="img-fluid">
								</div>
							<?php endforeach; ?>

							<?php foreach ($gallery as $image) : ?>
								<div class="client-logo">
									<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="img-fluid">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			</div>
		</section>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php _e('Aucun client trouvé.', 'abyssenergy'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>



<script>
	// Configuration de l'animation du slider infini
	document.addEventListener('DOMContentLoaded', function() {
		const inner = document.getElementById('clients-slider-inner');
		if (!inner) return;

		// Nombre total de logos (inclut les doublons)
		const totalLogos = inner.children.length;
		// Nombre réel de logos uniques (la moitié du total puisqu'on les duplique)
		const uniqueLogos = totalLogos / 2;

		// Calcul de la largeur totale à déplacer (la largeur d'un ensemble de logos uniques)
		const logoWidth = 200; // largeur en pixels de chaque logo (définie dans le CSS)
		const slideDistance = -(logoWidth * uniqueLogos);

		// Définir la variable CSS pour la distance d'animation
		document.documentElement.style.setProperty('--slider-width', `${slideDistance}px`);

		// Configuration de la vitesse d'animation basée sur le nombre de logos
		// Plus il y a de logos, plus l'animation doit être lente
		const animationDuration = Math.max(20, uniqueLogos * 3); // 3 secondes par logo, minimum 20s
		inner.style.animationDuration = `${animationDuration}s`;

		// Configuration du grid pour assurer un affichage correct
		inner.style.gridTemplateColumns = `repeat(${totalLogos}, ${logoWidth}px)`;
	});
</script>
