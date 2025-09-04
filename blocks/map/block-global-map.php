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

// Mode aperçu dans l'éditeur
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];

// Variables ACF
$title = get_field('global_map_title') ?: 'Nos opérations à travers le monde';
$description = get_field('global_map_description');
$markers = get_field('global_map_markers');

// Préparer les données de la carte pour JavaScript
$map_data = array(
	'svgPath' => get_template_directory_uri() . '/blocks/map/svg/world-map.svg', // Chemin vers la carte SVG
	'markers' => array()
);

// Préparer les données des marqueurs
if ($markers) {
	foreach ($markers as $marker) {
		if (!empty($marker['lat']) && !empty($marker['lng'])) {
			$marker_data = array(
				'lat' => $marker['lat'],
				'lng' => $marker['lng'],
				'country' => $marker['country'] ?: '',
				'project_name' => $marker['title'] ?: 'Projet Abyss Energy',
				'sector' => $marker['sector'] ?: ''
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
		<div class="preview-map-placeholder" style="background-color: #ddedfb; height: 300px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 2px solid #F70;">
			<div style="text-align: center;">
				<span class="dashicons dashicons-location" style="font-size: 48px; width: auto; height: auto; color: #F70;"></span>
				<p style="margin-top: 10px;">Carte interactive avec <?php echo count($map_data['markers']); ?> marqueur(s)</p>
			</div>
		</div>
	</div>
<?php else : ?>

	<section class="section section--map">
		<div class="container">
			<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
				<div class="global-map-container">
					<?php if ($title) : ?>
						<h2 class="global-map-title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>

					<?php if ($description) : ?>
						<div class="global-map-description">
							<?php echo wp_kses_post($description); ?>
						</div>
					<?php endif; ?>

					<div class="global-map-wrapper">
						<div id="<?php echo esc_attr($id); ?>-map" class="global-map"></div>
					</div>

					<div id="<?php echo esc_attr($id); ?>-popup" class="global-map-popup">
						<div class="global-map-popup-inner">
							<span class="global-map-popup-close">&times;</span>
							<div class="global-map-popup-content"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<script>
		// Transmettre les données à JavaScript
		var mapData_<?php echo str_replace('-', '_', $id); ?> = <?php echo json_encode($map_data); ?>;
		var mapId_<?php echo str_replace('-', '_', $id); ?> = '<?php echo esc_attr($id); ?>-map';
		var popupId_<?php echo str_replace('-', '_', $id); ?> = '<?php echo esc_attr($id); ?>-popup';
	</script>
<?php endif; ?>
