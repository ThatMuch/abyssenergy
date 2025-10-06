<?php

/**
 * Block Template: Carte mondiale des opérations Abyss Energy
 *
 * @param array $block Les détails du bloc
 */

// Créer un ID unique pour les instances multiples du bloc
$id = 'abyss-global-map-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Créer des noms de classe basés sur le bloc
$className = 'abyss-global-map';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

// Gérer l'alignement
if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Variables ACF
$title = get_field('global_map_title');
$subtitle = get_field('global_map_subtitle');
$description = get_field('global_map_description');
$markers = get_field('global_map_markers');
$content = get_field('content');
$icon = get_field('icon');

// Préparer les données de la carte pour JavaScript
$map_data = array(
	'svgPath' => get_template_directory_uri() . '/blocks/map/svg/world-map.svg', // Chemin vers la carte SVG
	'markers' => array()
);

// Préparer les données des marqueurs
if ($markers) {
	foreach ($markers as $marker) {
		if (!empty($marker['lat']) && !empty($marker['lng'])) {
			// Préparer les données du secteur avec les labels
			$sector_value = $marker['sector'] ?: 'conventional';
			$sector_labels = array(
				'conventional' => 'Conventional Energy',
				'renewable' => 'Renewable Energy',
				'process' => 'Process Industry'
			);

			$marker_data = array(
				'lat' => $marker['lat'],
				'lng' => $marker['lng'],
				'country' => $marker['country'] ?: '',
				'project_name' => $marker['title'] ?: 'Projet Abyss Energy',
				'sector' => array(
					'value' => $sector_value,
					'label' => $sector_labels[$sector_value] ?: 'Conventional Energy'
				)
			);

			$map_data['markers'][] = $marker_data;
		}
	}
}
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p>Aperçu du bloc Carte Globale. La carte interactive s'affichera en mode front-end.</p>
		<div class="preview-map-placeholder">
			<div style="text-align: center;">
				<span class="dashicons dashicons-location" style="font-size: 48px; width: auto; height: auto; color: #F70;"></span>
				<p style="margin-top: 10px;">Carte interactive avec <?php echo count($map_data['markers']); ?> marqueur(s)</p>
			</div>
		</div>
	</div>
<?php else : ?>

	<section class="section section--map <?php if (!$title) : echo 'no-title';
											endif; ?>">
		<div class="container">
			<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
				<div class="global-map-container">
					<div class="row  global-map-header">
						<div class="col-md-6">
							<?php if ($subtitle) : ?>
								<h3 class="section--subtitle"><?php echo esc_html($subtitle); ?></h3>
							<?php endif; ?>
							<?php if ($title) : ?>
								<h2 class="section--title"><?php echo esc_html($title); ?></h2>
							<?php endif; ?>
							<?php if ($description) : ?>
								<div class="mb-4">
									<?php echo wp_kses_post($description); ?>
								</div>
							<?php endif; ?>
							<?php if ($content) : ?>
								<button class="btn btn--primary global-map-button" aria-label="Expand content">See more <i class="fa fa-plus"></i></button>
							<?php endif; ?>

						</div>
						<div class="col-md-6">
							<?php if ($icon) : ?>
								<div class="global-map-icon">
									<?php if (!empty($icon['url'])) : ?>
										<img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" />
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>


					<div class="global-map-wrapper">
						<div id="<?php echo esc_attr($id); ?>-map" class="global-map"></div>
						<div id="<?php echo esc_attr($id); ?>-tooltip" class="global-map-tooltip">
							<div class="border"></div>
							<div class="tooltip-arrow"></div>
							<div class="tooltip-close">&times;</div>
							<div class="tooltip-content"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<?php if ($content) : ?>
		<!-- Modal pour le contenu détaillé -->
		<div class="global-map-modal" id="<?php echo esc_attr($id); ?>-modal" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($id); ?>-modal-title">
			<div class="global-map-modal-content">
				<div class="global-map-modal-header">
					<?php if ($icon) : ?>
						<div class="global-map-modal-icon">
							<img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" loading="lazy">
						</div>
					<?php endif; ?>
					<?php if ($title) : ?>
						<h3 id="<?php echo esc_attr($id); ?>-modal-title" class="global-map-modal-title"><?php echo esc_html($title); ?></h3>
					<?php endif; ?>
					<button class="modal-close" aria-label="Close content"><i class="fa fa-times"></i></button>
				</div>
				<div class="global-map-modal-body">
					<div class="global-map-content">
						<?php echo wp_kses_post($content); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<script>
		// Transmettre les données à JavaScript
		var mapData_<?php echo str_replace('-', '_', $id); ?> = <?php echo json_encode($map_data); ?>;
		var mapId_<?php echo str_replace('-', '_', $id); ?> = '<?php echo esc_attr($id); ?>-map';
		var tooltipId_<?php echo str_replace('-', '_', $id); ?> = '<?php echo esc_attr($id); ?>-tooltip';
	</script>
<?php endif; ?>
