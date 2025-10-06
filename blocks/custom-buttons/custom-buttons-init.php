<?php

/**
 * Custom Buttons Block
 * Un bloc personnalisé basé sur le bloc Buttons de Gutenberg
 * avec support des styles et icônes FontAwesome
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistre les scripts et styles pour le bloc
 */
function abyssenergy_custom_buttons_block_assets()
{
	// Script de l'éditeur
	wp_register_script(
		'abyssenergy-custom-buttons-editor',
		get_template_directory_uri() . '/blocks/custom-buttons/custom-buttons-editor.js',
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-block-editor'),
		filemtime(get_template_directory() . '/blocks/custom-buttons/custom-buttons-editor.js')
	);

	// Style de l'éditeur
	wp_register_style(
		'abyssenergy-custom-buttons-editor-style',
		get_template_directory_uri() . '/blocks/custom-buttons/custom-buttons-editor.css',
		array('wp-edit-blocks'),
		filemtime(get_template_directory() . '/blocks/custom-buttons/custom-buttons-editor.css')
	);

	// Style frontend
	wp_register_style(
		'abyssenergy-custom-buttons-style',
		get_template_directory_uri() . '/blocks/custom-buttons/custom-buttons-style.css',
		array(),
		filemtime(get_template_directory() . '/blocks/custom-buttons/custom-buttons-style.css')
	);
}
add_action('init', 'abyssenergy_custom_buttons_block_assets', 5);

/**
 * Initialise le bloc Custom Buttons
 */
function abyssenergy_init_custom_buttons_block()
{
	// Enregistrer le bloc
	register_block_type(
		'abyssenergy/custom-buttons',
		array(
			'attributes' => array(
				'buttons' => array(
					'type' => 'array',
					'default' => array()
				),
				'alignment' => array(
					'type' => 'string',
					'default' => 'left'
				),
				'spacing' => array(
					'type' => 'string',
					'default' => 'normal'
				)
			),
			'render_callback' => 'abyssenergy_render_custom_buttons_block',
			'editor_script' => 'abyssenergy-custom-buttons-editor',
			'editor_style' => 'abyssenergy-custom-buttons-editor-style'
		)
	);
}
add_action('init', 'abyssenergy_init_custom_buttons_block', 10);

/**
 * Rendu du bloc Custom Buttons côté frontend
 */
function abyssenergy_render_custom_buttons_block($attributes, $content = '', $block = null)
{
	// Utiliser le template séparé
	ob_start();
	include get_template_directory() . '/blocks/custom-buttons/block-custom-buttons.php';
	return ob_get_clean();
}


/**
 * Enqueue les styles frontend
 */
function abyssenergy_custom_buttons_frontend_styles()
{
	if (has_block('abyssenergy/custom-buttons')) {
		wp_enqueue_style('abyssenergy-custom-buttons-style');
	}
}
add_action('wp_enqueue_scripts', 'abyssenergy_custom_buttons_frontend_styles');
