<?php

/**
 * Ajouter les paramètres pour la carte SVG personnalisée
 */
function abyssenergy_add_map_params()
{
	// Enregistrer et charger le script de la carte globale
	wp_enqueue_script(
		'abyssenergy-global-map',
		get_template_directory_uri() . '/blocks/map/global-map.js',
		array('jquery'),
		filemtime(get_template_directory() . '/blocks/map/global-map.js'),
		true
	);

	wp_localize_script('abyssenergy-global-map', 'abyss_map_params', array(
		'theme_url' => get_template_directory_uri(),
		'ajax_url' => admin_url('admin-ajax.php')
	));
}
add_action('wp_enqueue_scripts', 'abyssenergy_add_map_params', 100);
add_action('admin_enqueue_scripts', 'abyssenergy_add_map_params', 100);

/**
 * Enregistrer et charger les styles de la carte
 */
function abyssenergy_enqueue_map_styles()
{
	wp_enqueue_style(
		'abyssenergy-global-map-style',
		get_template_directory_uri() . '/blocks/map/global-map.css',
		array(),
		filemtime(get_template_directory() . '/blocks/map/global-map.css')
	);
}
add_action('wp_enqueue_scripts', 'abyssenergy_enqueue_map_styles');
add_action('admin_enqueue_scripts', 'abyssenergy_enqueue_map_styles');
