<?php

/**
 * Features Block Template.
 */

$anchor = '';
if (! empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'features-block';
if (! empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (! empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title');
$subtitle = get_field('subtitle');
$features = get_field('features') ?: array();
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title ?: 'Bloc Features'); ?></h3>
		<p><?php _e('Aperçu du bloc des fonctionnalités. Configurez les champs dans le panneau de droite.', 'abyssenergy'); ?></p>
		<?php if (empty($features)) : ?>
			<p><em><?php _e('Aucune fonctionnalité ajoutée. Cliquez sur "Ajouter un élément" pour commencer.', 'abyssenergy'); ?></em></p>
		<?php endif; ?>
	</div>
<?php endif; ?>

<!-- Features Block -->
<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>">
	<div class="container">
		<?php if ($title || $subtitle) : ?>
			<div class="section-header text-center mb-5">
				<?php if ($subtitle) : ?>
					<p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
				<?php endif; ?>
				<?php if ($title) : ?>
					<h2 class="section-title"><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ($features) : ?>
			<div class="row g-4">
				<?php foreach ($features as $feature) : ?>
					<div class="col-md-6 col-lg-4">
						<div class="feature-card card h-100 text-center p-4">
							<?php if (!empty($feature['icon'])) : ?>
								<div class="feature-icon mb-3">
									<img src="<?php echo esc_url(wp_get_attachment_image_url($feature['icon'], 'full')); ?>" alt="<?php echo esc_attr(get_post_meta($feature['icon'], '_wp_attachment_image_alt', true)); ?>" class="img-fluid" />
								</div>
							<?php endif; ?>
							<?php if (!empty($feature['title'])) : ?>
								<h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
							<?php endif; ?>
							<?php if (!empty($feature['description'])) : ?>
								<p class="feature-description"><?php echo esc_html($feature['description']); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<div class="no-features-message text-center">
				<p class="text-muted"><?php _e('Aucune fonctionnalité à afficher. Ajoutez des éléments dans les paramètres du bloc.', 'abyssenergy'); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
