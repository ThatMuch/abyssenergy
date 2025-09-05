<?php

/**
 * Enregistrement du bloc ACF Clients
 */

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_clients_block');
}

function register_clients_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'clients',
		'title'             => __('Clients', 'abyssenergy'),
		'description'       => __('Un slider pour afficher les clients.', 'abyssenergy'),
		'render_template'   => 'blocks/clients/block-clients.php',
		'category'          => 'formatting',
		'icon'              => 'testimonial',
		'keywords'          => array('clients', 'slider', 'partenaires'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/clients/clients.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/clients/clients.js',
		'enqueue_assets'    => function () {
			// Assurez-vous que Swiper est chargé
			wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
			wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
		}
	));
}

/**
 * Création des champs ACF pour le bloc Testimonials Slider
 */
if (function_exists('acf_add_local_field_group')) {
	acf_add_local_field_group(array(
		'key' => 'group_testimonials_slider',
		'title' => 'Testimonials Slider Settings',
		'fields' => array(
			array(
				'key' => 'field_testimonials_slider_title',
				'label' => 'Titre de la section',
				'name' => 'title',
				'type' => 'text',
				'instructions' => 'Entrez le titre de la section des témoignages.',
				'default_value' => 'Témoignages de nos clients',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_testimonials_slider_show_title',
				'label' => 'Afficher le titre',
				'name' => 'show_title',
				'type' => 'true_false',
				'instructions' => 'Cochez pour afficher le titre de la section.',
				'default_value' => 1,
				'ui' => 1,
			)
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/clients',
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
