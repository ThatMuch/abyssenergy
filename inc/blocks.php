<?php

/**
 * Blocs Gutenberg personnalisés
 *
 * Enregistrement et configuration des blocs Gutenberg
 * personnalisés pour le thème.
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrer les blocs Gutenberg personnalisés
 */
function abyssenergy_register_blocks()
{
	// Vérifier si ACF est actif
	if (!function_exists('acf_register_block_type')) {
		return;
	}

	// Bloc Sectors List (anciennement Grid)
	acf_register_block_type(array(
		'name'              => 'sectors-list',
		'title'             => __('Liste de secteurs', 'abyssenergy'),
		'description'       => __('Affiche les secteurs dans une liste verticale', 'abyssenergy'),
		'render_template'   => 'blocks/sectors/block-sectors.php', // On garde le même nom de fichier pour la rétrocompatibilité
		'category'          => 'abyss-blocks',
		'icon'              => 'list-view',
		'keywords'          => array('secteurs', 'sectors', 'list', 'liste'),
		'supports'          => array(
			'align'         => array('wide', 'full'),
			'mode'          => true,
			'jsx'           => true,
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'title'         => 'Nos secteurs d\'activité',
					'is_preview'    => true
				)
			)
		),
	));

	// Pour rétrocompatibilité, gardons également l'ancien bloc mais en le redirigeant vers le même template
	acf_register_block_type(array(
		'name'              => 'sectors-grid',
		'title'             => __('Grille de secteurs (Déprécié)', 'abyssenergy'),
		'description'       => __('Version dépréciée - Utilisez plutôt la Liste de secteurs', 'abyssenergy'),
		'render_template'   => 'blocks/sectors/block-sectors.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'grid-view',
		'keywords'          => array('secteurs', 'sectors', 'grid', 'grille', 'déprécié'),
		'supports'          => array(
			'align'         => array('wide', 'full'),
			'mode'          => true,
			'jsx'           => true,
		),
	));
}
add_action('acf/init', 'abyssenergy_register_blocks');

/**
 * Ajouter les assets CSS/JS pour l'éditeur de blocs
 */
function abyssenergy_add_block_editor_assets()
{
	wp_enqueue_style(
		'abyssenergy-block-editor-css',
		get_template_directory_uri() . '/blocks/sectors/block-sector-style.css',
		array(),
		filemtime(get_template_directory() . '/blocks/sectors/block-sector-style.css')
	);
}
add_action('enqueue_block_editor_assets', 'abyssenergy_add_block_editor_assets');

/**
 * Ajouter une catégorie de blocs personnalisée
 */
function abyssenergy_block_categories($categories, $post)
{
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'abyss-blocks',
				'title' => __('Abyss Energy', 'abyssenergy'),
				'icon'  => 'admin-site',
			),
		)
	);
}
add_filter('block_categories_all', 'abyssenergy_block_categories', 10, 2);
