<?php

/**
 * Enregistrement du bloc ACF Testimonials Slider
 */

if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_testimonials_slider_block');
}

function register_testimonials_slider_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'testimonials-slider',
		'title'             => __('Testimonials Slider', 'abyssenergy'),
		'description'       => __('Un slider pour afficher les témoignages clients.', 'abyssenergy'),
		'render_template'   => 'blocks/testimonials-slider/block-testimonials-slider.php',
		'category'          => 'formatting',
		'icon'              => 'testimonial',
		'keywords'          => array('testimonials', 'slider', 'témoignages', 'clients'),
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
		'enqueue_style'     => get_template_directory_uri() . '/blocks/testimonials-slider/testimonials-slider.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/testimonials-slider/testimonials-slider.js',
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
				'key' => 'field_testimonials_slider_subtitle',
				'label' => 'Sous-titre de la section',
				'name' => 'subtitle',
				'type' => 'text',
				'instructions' => 'Entrez le sous-titre de la section des témoignages.',
				'default_value' => 'Ce que disent nos clients',
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
			),
			array(
				'key' => 'field_testimonials_slider_limit',
				'label' => 'Nombre de témoignages à afficher',
				'name' => 'testimonials_limit',
				'type' => 'number',
				'instructions' => 'Entrez le nombre de témoignages à afficher. Laissez vide ou 0 pour afficher tous les témoignages.',
				'default_value' => '',
				'placeholder' => 'Tous les témoignages',
				'min' => 1,
				'max' => 50,
				'step' => 1,
			),
			array(
				'key' => 'field_testimonials_slider_image',
				'label' => 'Image de la section témoignage',
				'name' => 'image',
				'type' => 'image',
				'instructions' => 'Téléchargez une image pour la section témoignage.',
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
					'value' => 'acf/testimonials-slider',
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
