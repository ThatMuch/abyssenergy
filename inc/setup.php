<?php

/**
 * Configuration de base du thème
 *
 * Fonctions de configuration de base du thème WordPress,
 * y compris l'enregistrement des menus, les supports de thème,
 * et autres fonctionnalités fondamentales.
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration des supports de thème
 */
function abyssenergy_theme_setup()
{
	// Support des images mises en avant
	add_theme_support('post-thumbnails');

	// Tailles d'images personnalisées
	set_post_thumbnail_size(385, 288, true); // Image mise en avant
	add_image_size('barbers', 288, 360, true);
	add_image_size('third-width', 377, 323, true);
	add_image_size('page-header', 1440, 430, true);

	// Support pour le titre de document
	add_theme_support('title-tag');

	// Support pour les blocs larges et pleine largeur
	add_theme_support('align-wide');

	// Support pour les styles d'éditeur
	add_theme_support('editor-styles');

	// Support pour les fonctionnalités HTML5
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'abyssenergy_theme_setup');

/**
 * Enregistrement des menus du thème
 */
function abyssenergy_register_menus()
{
	register_nav_menus(
		array(
			'main-menu' => __('Main menu'),
			'footer-menu1' => __('Footer menu col 1'),
			'footer-menu2' => __('Footer menu col 2'),
			'footer-menu3' => __('Footer menu col 3'),
			'footer-menu4' => __('Footer menu col 4'),
			'footer-menu5' => __('Footer menu col 5'),
			'footer-menu6' => __('Footer menu col 6'),
		)
	);
}
add_action('init', 'abyssenergy_register_menus');

/**
 * Désactiver la barre d'administration pour tous sauf les administrateurs
 */
function abyssenergy_remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
add_action('after_setup_theme', 'abyssenergy_remove_admin_bar');

/**
 * Ajouter des tailles d'images dans le menu déroulant de l'interface d'administration
 */
function abyssenergy_show_image_sizes($sizes)
{
	return array_merge($sizes, array(
		'barbers' => __('Barber Size'),
		'third-width' => __('One Third Width'),
		'page-header' => __('Page Header')
	));
}
add_filter('image_size_names_choose', 'abyssenergy_show_image_sizes');
