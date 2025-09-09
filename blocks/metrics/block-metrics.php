<?php

/**
 * Block Metrics Template.
 *
 */

$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'metrics-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Our Achievements';
$subtitle = get_field('subtitle') ?: 'Performance';
$metrics = get_field('metrics') ?: array();
$content = get_field('content');
$icon = get_field('icon');
$excerpt = get_field('excerpt');
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc des métriques. Configurez les champs dans le panneau de droite.', 'abyssenergy'); ?></p>
		<?php if (empty($metrics)) : ?>
			<p><em><?php _e('Aucune métrique ajoutée. Cliquez sur "Ajouter un élément" pour commencer.', 'abyssenergy'); ?></em></p>
		<?php endif; ?>
	</div>
<?php endif; ?>

<!-- Metrics Block -->
<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>">
	<div class="container">
		<?php if ($title || $subtitle) : ?>
			<div class="section-header mb-5">
				<?php if ($subtitle) : ?>
					<p class="section--subtitle"><?php echo esc_html($subtitle); ?></p>
				<?php endif; ?>
				<?php if ($title) : ?>
					<h2 class="section--title"><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ($metrics) : ?>
			<div class="row metrics-grid">
				<?php foreach ($metrics as $metric) : ?>
					<div class="metric-card">
						<div class="metric-card-header">
							<?php if (!empty($metric['icon'])) :  ?>
								<div class="metric-icon">
									<img src="<?php echo esc_url($metric['icon']['url']); ?>" alt="<?php echo esc_attr($metric['icon']['alt']); ?>" class="img-fluid">
								</div>
							<?php endif; ?>
							<?php if (!empty($metric['value'])) : ?>
								<h3 class="metric-value"><?php echo esc_html($metric['value']); ?></h3>
							<?php endif; ?>
							<?php if (!empty($metric['label'])) : ?>
								<p class="metric-label"><?php echo esc_html($metric['label']); ?></p>
							<?php endif; ?>
						</div>
						<div class="metric-card-content">
							<?php if (!empty($metric['excerpt'])) : ?>
								<div class="metric-excerpt"><?php echo wp_kses_post($metric['excerpt']); ?></div>
							<?php endif; ?>
							<?php if (!empty($metric['content'])) : ?>
								<div class="metric-content"><?php echo wp_kses_post($metric['content']); ?></div>
							<?php endif; ?>
							<button class="metric-button"><i class="fa fa-plus"></i></button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
