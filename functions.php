<?php

/**
 * Functions et hooks pour le thème enfant Abyss Energy
 *
 * Ce fichier charge correctement les styles du thème parent et permet d'ajouter
 * des fonctionnalités personnalisées sans modifier le thème parent.
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enqueue les styles du thème parent et enfant
 */
function squarechilli_child_enqueue_styles()
{
	// Style du thème parent
	wp_enqueue_style(
		'squarechilli-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme()->get('Version')
	);

	// Style du thème enfant (chargé après le parent)
	wp_enqueue_style(
		'squarechilli-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('squarechilli-parent-style'),
		wp_get_theme()->get('Version')
	);
}
add_action('wp_enqueue_scripts', 'squarechilli_child_enqueue_styles');

/**
 * Compilation automatique des fichiers SCSS (mode développement uniquement)
 */
function squarechilli_child_compile_scss()
{
	// Ne pas compiler en production
	if (defined('WP_ENV') && WP_ENV === 'production') {
		return;
	}

	$scss_file = get_stylesheet_directory() . '/scss/style.scss';
	$css_file = get_stylesheet_directory() . '/style.css';

	// Vérifier si le fichier SCSS existe et si il est plus récent que le CSS
	if (file_exists($scss_file)) {
		$scss_time = filemtime($scss_file);
		$css_time = file_exists($css_file) ? filemtime($css_file) : 0;

		// Si SCSS est plus récent ou si CSS n'existe pas
		if ($scss_time > $css_time) {
			// Essayer de compiler avec Sass si disponible
			if (function_exists('exec') && !empty(shell_exec('which sass'))) {
				$command = sprintf(
					'sass %s:%s --style expanded 2>&1',
					escapeshellarg($scss_file),
					escapeshellarg($css_file)
				);

				$output = shell_exec($command);

				// Log en cas d'erreur
				if (strpos($output, 'Error') !== false) {
					error_log('SCSS Compilation Error: ' . $output);
				}
			}
		}
	}
}

/**
 * Obtenir la version du fichier pour cache busting
 */
function squarechilli_child_get_file_version($file)
{
	$file_path = get_stylesheet_directory() . $file;
	return file_exists($file_path) ? filemtime($file_path) : wp_get_theme()->get('Version');
}

// Compiler SCSS au chargement de l'admin et du front-end
add_action('init', 'squarechilli_child_compile_scss');

/**
 * Version améliorée de l'enqueue des styles avec versioning automatique
 */
function squarechilli_child_enqueue_styles_improved()
{
	// Style du thème parent
	wp_enqueue_style(
		'squarechilli-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		squarechilli_child_get_file_version('/../squarechilli/style.css')
	);

	// Style du thème enfant
	wp_enqueue_style(
		'squarechilli-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('squarechilli-parent-style'),
		squarechilli_child_get_file_version('/style.css')
	);
}

// Remplacer l'ancienne fonction par la nouvelle
remove_action('wp_enqueue_scripts', 'squarechilli_child_enqueue_styles');
add_action('wp_enqueue_scripts', 'squarechilli_child_enqueue_styles_improved');

/**
 * Ajouter le support des fonctionnalités modernes de WordPress
 */
function squarechilli_child_theme_setup()
{
	// Support des images de fond personnalisées
	add_theme_support('custom-background');

	// Support du logo personnalisé
	add_theme_support('custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	));

	// Support des couleurs personnalisées
	add_theme_support('custom-header');

	// Support de l'éditeur de blocs
	add_theme_support('wp-block-styles');
	add_theme_support('align-wide');

	// Support des embeds responsive
	add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'squarechilli_child_theme_setup');

/**
 * Exemple de fonction personnalisée
 * Décommentez et modifiez selon vos besoins
 */
/*
function squarechilli_child_custom_function() {
    // Votre code personnalisé ici
}
*/

/**
 * Exemple de hook pour modifier le comportement du thème parent
 * Décommentez et modifiez selon vos besoins
 */
/*
function squarechilli_child_modify_parent_function() {
    // Supprimer une action du thème parent
    // remove_action('hook_name', 'parent_function_name');

    // Ajouter votre propre action
    // add_action('hook_name', 'your_custom_function');
}
add_action('after_setup_theme', 'squarechilli_child_modify_parent_function');
*/
