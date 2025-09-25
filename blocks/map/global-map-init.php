<?php

/**
 * Global Map Block Initialization
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Global Map
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_global_map_block');
}

function register_global_map_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'global-map',
		'title'             => __('Carte Globale', 'abyssenergy'),
		'description'       => __('Affiche une carte interactive des opérations mondiales.', 'abyssenergy'),
		'render_template'   => 'blocks/map/block-global-map.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'location-alt',
		'keywords'          => array('map', 'global', 'locations', 'carte'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true
				)
			)
		),
		'enqueue_style'     => get_template_directory_uri() . '/blocks/map/global-map.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/map/global-map.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_global_map',
			'title' => 'Carte Globale',
			'fields' => array(
				array(
					'key' => 'field_global_map_title',
					'label' => 'Titre',
					'name' => 'global_map_title',
					'type' => 'text',
					'default_value' => 'Nos Opérations Mondiales',
				),
				array(
					'key' => 'field_global_map_subtitle',
					'label' => 'Sous-titre',
					'name' => 'global_map_subtitle',
					'type' => 'text',
					'default_value' => 'Explorez nos projets à travers le monde',
				),
				array(
					'key' => 'field_global_map_description',
					'label' => 'Description',
					'name' => 'global_map_description',
					'type' => 'textarea',
					'rows' => 3,
				),
				array(
					'key' => 'field_global_map_markers',
					'label' => 'Marqueurs de la carte',
					'name' => 'global_map_markers',
					'type' => 'repeater',
					'layout' => 'table',
					'button_label' => 'Ajouter un marqueur',
					'sub_fields' => array(
						array(
							'key' => 'field_marker_country',
							'label' => 'Pays',
							'name' => 'country',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_marker_title',
							'label' => 'Nom du projet',
							'name' => 'title',
							'type' => 'text',
							'required' => 1,
						),
						array(
							'key' => 'field_marker_sector',
							'label' => 'Secteur',
							'name' => 'sector',
							'type' => 'radio',
							'choices' => array(
								'conventional' => 'Conventional',
								'renewable' => 'Renewable',
								'process' => 'Process'
							),
							'layout' => 'vertical',
							'required' => 1,
							'default_value' => 'conventional',
							'return_format' => 'value',
						),
						array(
							'key' => 'field_marker_lat',
							'label' => 'Latitude',
							'name' => 'lat',
							'type' => 'number',
							'step' => 0.000001,
							'required' => 1,
						),
						array(
							'key' => 'field_marker_lng',
							'label' => 'Longitude',
							'name' => 'lng',
							'type' => 'number',
							'step' => 0.000001,
							'required' => 1,
						)
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/global-map',
					),
				),
			),
		));
	}
}
