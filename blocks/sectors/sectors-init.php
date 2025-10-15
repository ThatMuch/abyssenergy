<?php

/**
 * Initialisation du bloc Sectors
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Sectors
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_sectors_block');
}

function register_sectors_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'sectors',
		'title'             => __('Sectors Grid', 'abyssenergy'),
		'description'       => __('Affiche une grille de secteurs avec leurs informations.', 'abyssenergy'),
		'render_template'   => 'blocks/sectors/block-sectors.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'grid-view',
		'keywords'          => array('sectors', 'secteurs', 'grille'),
		'supports'          => array(
			'align'            => array('wide', 'full'),
			'anchor'           => true,
		),
		'example'  => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'title'           => __('Nos Secteurs', 'abyssenergy'),
					'subtitle'        => __('Découvrez nos domaines d\'expertise', 'abyssenergy'),
					'selection_type'  => 'all'
				)
			)
		),
	));
}

/**
 * Enqueue des styles et scripts pour le bloc Sectors
 */
add_action('wp_enqueue_scripts', 'enqueue_sectors_block_assets');
add_action('admin_enqueue_scripts', 'enqueue_sectors_block_assets');

function enqueue_sectors_block_assets()
{
	// CSS du bloc
	wp_enqueue_style(
		'sectors-block-style',
		get_template_directory_uri() . '/blocks/sectors/block-sector-style.css',
		array(),
		filemtime(get_template_directory() . '/blocks/sectors/block-sector-style.css')
	);
}

/**
 * Ajouter les champs ACF pour le bloc Sectors
 */
add_action('acf/init', 'add_sectors_block_fields');

function add_sectors_block_fields()
{
	if (function_exists('acf_add_local_field_group')) {

		acf_add_local_field_group(array(
			'key' => 'group_sectors_block',
			'title' => 'Bloc Sectors - Paramètres',
			'fields' => array(
				array(
					'key' => 'field_sectors_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Titre principal du bloc (optionnel)',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_sectors_subtitle',
					'label' => 'Sous titre',
					'name' => 'subtitle',
					'type' => 'text',
					'instructions' => 'Sous titre du bloc (optionnel)',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_sectors_description',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'instructions' => 'Description du bloc (optionnel)',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_sectors_selection_type',
					'label' => 'Type de sélection',
					'name' => 'selection_type',
					'type' => 'radio',
					'instructions' => 'Choisir comment sélectionner les secteurs à afficher',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'all' => 'Tous les secteurs',
						'specific' => 'Secteurs spécifiques',
					),
					'allow_null' => 0,
					'other_choice' => 0,
					'default_value' => 'all',
					'layout' => 'horizontal',
					'return_format' => 'value',
				),
				array(
					'key' => 'field_sectors_specific',
					'label' => 'Sélectionner des secteurs',
					'name' => 'specific_sectors',
					'type' => 'relationship',
					'instructions' => 'Sélectionnez les secteurs spécifiques à afficher',
					'required' => 0,
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_sectors_selection_type',
								'operator' => '==',
								'value' => 'specific',
							),
						),
					),
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array(
						0 => 'sector',
					),
					'taxonomy' => '',
					'filters' => array(
						0 => 'search',
					),
					'elements' => array(
						0 => 'featured_image',
					),
					'min' => '',
					'max' => '',
					'return_format' => 'id',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sectors',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));
	}
}
