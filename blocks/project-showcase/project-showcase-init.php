<?php

/**
 * Project Showcase Block Initialization
 *
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Project Showcase
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_project_showcase_block');
}

function register_project_showcase_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'project-showcase',
		'title'             => __('Projets Showcase', 'abyssenergy'),
		'description'       => __('Affiche une liste de projets showcase avec filtrage par secteur.', 'abyssenergy'),
		'render_template'   => 'blocks/project-showcase/block-project-showcase.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'portfolio',
		'keywords'          => array('projects', 'showcase', 'projets', 'portfolio', 'sectors'),
		'supports'          => array(
			'align' => true,
			'mode' => false,
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/project-showcase/project-showcase.css',
	));

	// Créer les champs ACF pour le bloc
	if (function_exists('acf_add_local_field_group')) {
		acf_add_local_field_group(array(
			'key' => 'group_project_showcase',
			'title' => 'Paramètres du bloc Projets Showcase',
			'fields' => array(
				array(
					'key' => 'field_project_showcase_title',
					'label' => 'Titre de la section',
					'name' => 'title',
					'type' => 'text',
					'instructions' => 'Entrez le titre de la section des projets.',
					'default_value' => 'Nos projets phares',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_project_showcase_subtitle',
					'label' => 'Sous-titre',
					'name' => 'subtitle',
					'type' => 'text',
					'instructions' => 'Entrez un sous-titre optionnel.',
					'default_value' => 'Découvrez nos réalisations',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_project_showcase_show_title',
					'label' => 'Afficher le titre',
					'name' => 'show_title',
					'type' => 'true_false',
					'instructions' => 'Cochez pour afficher le titre.',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_project_showcase_project_sectors',
					'label' => 'Filtrer par secteur',
					'name' => 'project_sectors',
					'type' => 'taxonomy',
					'instructions' => 'Sélectionnez les secteurs de projets à afficher. Laissez vide pour afficher tous les secteurs.',
					'taxonomy' => 'project-sector',
					'field_type' => 'multi_select',
					'allow_null' => 1,
					'add_term' => 0,
					'save_terms' => 0,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 1,
				),
				array(
					'key' => 'field_project_showcase_count',
					'label' => 'Nombre de projets',
					'name' => 'projects_count',
					'type' => 'number',
					'instructions' => 'Choisissez le nombre de projets à afficher (maximum 3).',
					'default_value' => 3,
					'min' => 1,
					'max' => 3,
					'step' => 1,
				),
				array(
					'key' => 'field_project_showcase_layout',
					'label' => 'Disposition',
					'name' => 'layout',
					'type' => 'select',
					'instructions' => 'Choisissez la disposition des cartes.',
					'choices' => array(
						'horizontal' => 'Horizontale (en ligne)',
						'featured' => 'Mise en avant (1 grande + 2 petites)',
						'grid' => 'Grille équilibrée',
					),
					'default_value' => 'horizontal',
					'return_format' => 'value',
					'multiple' => 0,
					'allow_null' => 0,
				),
				array(
					'key' => 'field_project_showcase_show_excerpt',
					'label' => 'Afficher l\'extrait',
					'name' => 'show_excerpt',
					'type' => 'true_false',
					'instructions' => 'Cochez pour afficher l\'extrait des projets.',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_project_showcase_show_sector',
					'label' => 'Afficher le secteur',
					'name' => 'show_sector',
					'type' => 'true_false',
					'instructions' => 'Cochez pour afficher le secteur des projets.',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_project_showcase_show_button',
					'label' => 'Afficher le bouton "Voir plus"',
					'name' => 'show_button',
					'type' => 'true_false',
					'instructions' => 'Cochez pour afficher un bouton vers tous les projets.',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_project_showcase_button_text',
					'label' => 'Texte du bouton',
					'name' => 'button_text',
					'type' => 'text',
					'instructions' => 'Entrez le texte du bouton.',
					'default_value' => 'Voir tous nos projets',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_project_showcase_show_button',
								'operator' => '==',
								'value' => '1',
							),
						),
					),
				),
				array(
					'key' => 'field_project_showcase_button_url',
					'label' => 'URL du bouton',
					'name' => 'button_url',
					'type' => 'url',
					'instructions' => 'Entrez l\'URL du bouton.',
					'default_value' => '',
					'placeholder' => '/projets',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_project_showcase_show_button',
								'operator' => '==',
								'value' => '1',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/project-showcase',
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
			'show_in_rest' => 0,
		));
	}
}
