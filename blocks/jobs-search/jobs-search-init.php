<?php

/**
 * Jobs Search Block Initialization
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrer le bloc ACF jobs search
 */
function abyssenergy_register_jobs_search_block()
{
	// Vérifier si ACF est disponible
	if (!function_exists('acf_register_block_type')) {
		return;
	}

	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'jobs-search',
		'title'             => __('Jobs Search', 'abyssenergy'),
		'description'       => __('Un bloc avec titre, texte, recherche d\'emplois et image.', 'abyssenergy'),
		'render_template'   => get_template_directory() . '/blocks/jobs-search/block-jobs-search.php',
		'category'          => 'common',
		'icon'              => 'search',
		'keywords'          => array('jobs', 'search', 'employment', 'careers', 'emploi'),
		'supports'          => array(
			'align'         => array('wide', 'full'),
			'anchor'        => true,
			'customClassName' => true,
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'title' => 'Trouvez votre prochaine opportunité',
					'text' => 'Découvrez nos offres d\'emploi et rejoignez notre équipe dynamique.',
					'search_placeholder' => 'Rechercher un poste...',
					'image' => null,
				),
			),
		),
	));
}
add_action('acf/init', 'abyssenergy_register_jobs_search_block');

/**
 * Enregistrer les styles et scripts du bloc jobs search
 */
function abyssenergy_enqueue_jobs_search_block_assets()
{
	// CSS du bloc
	if (file_exists(get_stylesheet_directory() . '/blocks/jobs-search/jobs-search.css')) {
		wp_enqueue_style(
			'abyssenergy-jobs-search-block',
			get_stylesheet_directory_uri() . '/blocks/jobs-search/jobs-search.css',
			array(),
			abyssenergy_get_file_version('/blocks/jobs-search/jobs-search.css')
		);
	}

	// JavaScript du bloc
	if (file_exists(get_stylesheet_directory() . '/blocks/jobs-search/jobs-search.js')) {
		wp_enqueue_script(
			'abyssenergy-jobs-search-block',
			get_stylesheet_directory_uri() . '/blocks/jobs-search/jobs-search.js',
			array(),
			abyssenergy_get_file_version('/blocks/jobs-search/jobs-search.js'),
			true
		);
	}
}
add_action('wp_enqueue_scripts', 'abyssenergy_enqueue_jobs_search_block_assets');
add_action('admin_enqueue_scripts', 'abyssenergy_enqueue_jobs_search_block_assets');

/**
 * Ajouter les champs ACF pour le bloc jobs search
 */
function abyssenergy_add_jobs_search_block_fields()
{
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_jobs_search_block',
			'title' => 'Jobs Search Block',
			'fields' => array(
				array(
					'key' => 'field_jobs_search_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Titre principal du bloc',
					'required' => 1,
					'default_value' => 'Trouvez votre prochaine opportunité',
				),
				array(
					'key' => 'field_jobs_search_text',
					'label' => 'Texte descriptif',
					'name' => 'text',
					'type' => 'textarea',
					'instructions' => 'Texte descriptif affiché sous le titre',
					'required' => 1,
					'rows' => 3,
					'default_value' => 'Découvrez nos offres d\'emploi et rejoignez notre équipe dynamique.',
				),
				array(
					'key' => 'field_jobs_search_placeholder',
					'label' => 'Placeholder du champ de recherche',
					'name' => 'search_placeholder',
					'type' => 'text',
					'instructions' => 'Texte affiché dans le champ de recherche',
					'default_value' => 'Trouvez votre prochain poste...',
				),
				array(
					'key' => 'field_jobs_search_button_text',
					'label' => 'Texte du bouton',
					'name' => 'button_text',
					'type' => 'text',
					'instructions' => 'Texte affiché sur le bouton de recherche (optionnel - icône par défaut)',
					'default_value' => '',
				),
				array(
					'key' => 'field_jobs_search_image',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => 'Image affichée à droite du bloc',
					'required' => 1,
					'return_format' => 'id',
					'preview_size' => 'medium',
					'library' => 'all',
				)
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/jobs-search',
					),
				),
			),
		));
	}
}
add_action('acf/init', 'abyssenergy_add_jobs_search_block_fields');
