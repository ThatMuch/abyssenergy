<?php

/**
 * Tabs Block Initialization
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'abyssenergy_register_tabs_block');
}

/**
 * Enregistrer le bloc ACF tabs
 */
function abyssenergy_register_tabs_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'tabs',
		'title'             => __('Tabs', 'abyssenergy'),
		'description'       => __('Un bloc de tabulation avec onglets verticaux et contenu.', 'abyssenergy'),
		'render_template'   => get_template_directory() . '/blocks/tabs/block-tabs.php',
		'category'          => 'common',
		'icon'              => 'welcome-widgets-menus',
		'keywords'          => array('tabs', 'tabulation', 'onglets'),
		'supports'          => array(
			'align'         => array('wide', 'full'),
			'anchor'        => true,
			'customClassName' => true,
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'title' => 'Nos Services',
					'tabs' => array(
						array(
							'tab_title' => 'Conception',
							'tab_image' => null,
							'tab_content_title' => 'Solutions de Conception',
							'tab_content_text' => 'Description du service de conception...',
						),
						array(
							'tab_title' => 'Développement',
							'tab_image' => null,
							'tab_content_title' => 'Développement Sur Mesure',
							'tab_content_text' => 'Description du service de développement...',
						),
					),
				),
			),
		),
		'enqueue_style'     => get_template_directory_uri() . '/blocks/tabs/tabs.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/tabs/tabs.js',
	));
}
/**
 * Ajouter les champs ACF pour le bloc tabs
 */
function abyssenergy_add_tabs_block_fields()
{
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_tabs_block',
			'title' => 'Tabs Block',
			'fields' => array(
				array(
					'key' => 'field_tabs_block_title',
					'label' => 'Titre du bloc',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Titre principal affiché au-dessus des onglets',
					'default_value' => '',
				),
				array(
					'key' => 'field_tabs_block_tabs',
					'label' => 'Onglets',
					'name' => 'tabs',
					'type' => 'repeater',
					'instructions' => 'Ajouter les onglets et leur contenu',
					'min' => 1,
					'max' => 10,
					'layout' => 'block',
					'button_label' => 'Ajouter un onglet',
					'sub_fields' => array(
						array(
							'key' => 'field_tab_title',
							'label' => 'Titre de l\'onglet',
							'name' => 'tab_title',
							'type' => 'text',
							'instructions' => 'Texte affiché dans l\'onglet (navigation)',
							'required' => 1,
							'wrapper' => array(
								'width' => '50',
							),
						),
						array(
							'key' => 'field_tab_content_title',
							'label' => 'Titre du contenu',
							'name' => 'tab_content_title',
							'type' => 'text',
							'instructions' => 'Titre affiché dans le contenu de l\'onglet',
							'required' => 1,
							'wrapper' => array(
								'width' => '50',
							),
						),
						array(
							'key' => 'field_tab_image',
							'label' => 'Image',
							'name' => 'tab_image',
							'type' => 'image',
							'instructions' => 'Image affichée dans le contenu de l\'onglet',
							'return_format' => 'id',
							'preview_size' => 'medium',
							'library' => 'all',
						),
						array(
							'key' => 'field_tab_content_text',
							'label' => 'Texte du contenu',
							'name' => 'tab_content_text',
							'type' => 'wysiwyg',
							'instructions' => 'Contenu textuel de l\'onglet',
							'required' => 1,
							'tabs' => 'all',
							'toolbar' => 'basic',
							'media_upload' => 0,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/tabs',
					),
				),
			),
		));
	}
}
add_action('acf/init', 'abyssenergy_add_tabs_block_fields');
