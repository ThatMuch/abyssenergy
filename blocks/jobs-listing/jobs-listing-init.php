<?php

/**
 * Enregistrement du bloc ACF Jobs Listing
 */

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_jobs_listing_block');
}

function register_jobs_listing_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'jobs-listing',
		'title'             => __('Offres d\'emploi', 'abyssenergy'),
		'description'       => __('Affiche une liste d\'offres d\'emploi avec filtrage par secteur.', 'abyssenergy'),
		'render_template'   => 'blocks/jobs-listing/block-jobs-listing.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'businessman',
		'keywords'          => array('jobs', 'emplois', 'offres', 'carrières', 'sectors'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/jobs-listing/jobs-listing.css',
	));
}

/**
 * Création des champs ACF pour le bloc Jobs Listing
 */
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array(
		'key' => 'group_jobs_listing',
		'title' => 'Paramètres du bloc Offres d\'emploi',
		'fields' => array(
			array(
				'key' => 'field_jobs_listing_title',
				'label' => 'Titre de la section',
				'name' => 'title',
				'type' => 'text',
				'instructions' => 'Entrez le titre de la section des offres d\'emploi.',
				'default_value' => 'Nos offres d\'emploi',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_jobs_listing_subtitle',
				'label' => 'Sous-titre de la section',
				'name' => 'subtitle',
				'type' => 'text',
				'instructions' => 'Entrez le sous-titre de la section des offres d\'emploi.',
				'default_value' => 'Rejoignez notre équipe',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_jobs_listing_show_title',
				'label' => 'Afficher le titre',
				'name' => 'show_title',
				'type' => 'true_false',
				'instructions' => 'Cochez pour afficher le titre de la section.',
				'default_value' => 1,
				'ui' => 1,
			),
			array(
				'key' => 'field_jobs_listing_posts_per_page',
				'label' => 'Nombre d\'offres à afficher',
				'name' => 'posts_per_page',
				'type' => 'number',
				'instructions' => 'Entrez le nombre d\'offres d\'emploi à afficher.',
				'default_value' => 6,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => 1,
				'max' => 20,
				'step' => 1,
			),
			array(
				'key' => 'field_jobs_listing_job_sectors',
				'label' => 'Filtrer par secteur',
				'name' => 'job_sectors',
				'type' => 'taxonomy',
				'instructions' => 'Sélectionnez les secteurs d\'activité à afficher. Laissez vide pour afficher tous les secteurs.',
				'taxonomy' => 'job-sector',
				'field_type' => 'multi_select',
				'allow_null' => 1,
				'add_term' => 0,
				'save_terms' => 0,
				'load_terms' => 0,
				'return_format' => 'id',
				'multiple' => 1,
			),
			array(
				'key' => 'field_jobs_listing_show_button',
				'label' => 'Afficher le bouton "Voir plus"',
				'name' => 'show_button',
				'type' => 'true_false',
				'instructions' => 'Cochez pour afficher le bouton "Voir toutes les offres".',
				'default_value' => 1,
				'ui' => 1,
			),
			array(
				'key' => 'field_jobs_listing_button_text',
				'label' => 'Texte du bouton',
				'name' => 'button_text',
				'type' => 'text',
				'instructions' => 'Entrez le texte du bouton.',
				'default_value' => 'Voir toutes les offres',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_jobs_listing_show_button',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
			),
			array(
				'key' => 'field_jobs_listing_button_url',
				'label' => 'URL du bouton',
				'name' => 'button_url',
				'type' => 'url',
				'instructions' => 'Entrez l\'URL du bouton.',
				'default_value' => site_url('/search-jobs'),
				'placeholder' => '',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_jobs_listing_show_button',
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
					'value' => 'acf/jobs-listing',
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
