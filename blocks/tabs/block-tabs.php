<?php

/**
 * Tabs Block Template.
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
$class_name = 'tabs-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: '';
$tabs = get_field('tabs') ?: array();

// Générer un ID unique pour ce bloc
$block_id = 'tabs-' . uniqid();

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title ?: 'Bloc Tabs'); ?></h3>
		<p><?php _e('Aperçu du bloc de tabulation. Les onglets seront interactifs sur le site.', 'abyssenergy'); ?></p>
		<?php if (!empty($tabs)) : ?>
			<p><small><?php echo sprintf(__('%d onglets configurés', 'abyssenergy'), count($tabs)); ?></small></p>
		<?php endif; ?>
	</div>
<?php else : ?>

	<!-- Tabs Block -->
	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section" data-tabs-id="<?php echo esc_attr($block_id); ?>">
		<div class="container">
			<?php if (!empty($tabs)) : ?>
				<div class="tabs-container row gap-6 justify-content-between">
					<!-- Navigation des onglets (verticale à gauche) -->
					<div class="tabs-navigation col-md-4">
						<?php if ($title) : ?>
							<div class="section-header mb-5">
								<h2 class="section--title"><?php echo esc_html($title); ?></h2>
							</div>
						<?php endif; ?>
						<ul class="tabs-nav" role="tablist">
							<?php foreach ($tabs as $index => $tab) : ?>
								<li class="tabs-nav-item" role="presentation">
									<button
										class="tabs-nav-link <?php echo $index === 0 ? 'active' : ''; ?>"
										id="<?php echo esc_attr($block_id . '-tab-' . $index); ?>"
										data-tab-target="<?php echo esc_attr($block_id . '-panel-' . $index); ?>"
										type="button"
										role="tab"
										aria-controls="<?php echo esc_attr($block_id . '-panel-' . $index); ?>"
										aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
										<?php echo esc_html($tab['tab_title']); ?>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>

					<!-- Contenu des onglets (à droite) -->
					<div class="tabs-content col-md-6">
						<?php foreach ($tabs as $index => $tab) : ?>
							<div
								class="tabs-panel <?php echo $index === 0 ? 'active' : ''; ?>"
								id="<?php echo esc_attr($block_id . '-panel-' . $index); ?>"
								role="tabpanel"
								aria-labelledby="<?php echo esc_attr($block_id . '-tab-' . $index); ?>"
								<?php echo $index !== 0 ? 'hidden' : ''; ?>>
								<div class="tabs-panel-content">
									<?php if (!empty($tab['tab_image'])) : ?>
										<div class="tabs-panel-image">
											<?php echo wp_get_attachment_image($tab['tab_image'], 'large', false, array('loading' => 'lazy')); ?>
											<?php if (!empty($tab['tab_content_title'])) : ?>
												<h3 class="tabs-panel-title"><?php echo esc_html($tab['tab_content_title']); ?></h3>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<div class="tabs-panel-text">
										<?php if (!empty($tab['tab_content_text'])) : ?>
											<div class="tabs-panel-description">
												<?php echo wp_kses_post($tab['tab_content_text']); ?>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="alert alert-info">
					<p><?php _e('Veuillez ajouter au moins un onglet pour afficher le contenu.', 'abyssenergy'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

<?php endif; ?>
