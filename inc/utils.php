<?php

/**
 * Fonctions utilitaires
 *
 * Diverses fonctions utilitaires pour le thème
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Note: Les fonctions de compilation SCSS ont été déplacées dans inc/scss.php
 * pour une meilleure organisation et pour éviter les déclarations dupliquées.
 *
 * @see inc/scss.php
 */

/**
 * Formater correctement un numéro de téléphone
 *
 * @param string $phone Numéro de téléphone brut
 * @return string Numéro de téléphone formaté
 */
function abyssenergy_format_phone($phone)
{
	if (empty($phone)) {
		return '';
	}

	// Supprimer tous les caractères non numériques
	$phone = preg_replace('/[^0-9]/', '', $phone);

	// Formater selon la longueur
	if (strlen($phone) === 10) {
		return preg_replace('/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/', '$1 $2 $3 $4 $5', $phone);
	}

	return $phone;
}

/**
 * Obtenir l'URL de l'image mise en avant
 *
 * @param int $post_id ID de l'article
 * @param string $size Taille de l'image
 * @return string URL de l'image ou image par défaut
 */
function abyssenergy_get_thumbnail_url($post_id = null, $size = 'thumbnail')
{
	if (!$post_id) {
		$post_id = get_the_ID();
	}

	if (has_post_thumbnail($post_id)) {
		return get_the_post_thumbnail_url($post_id, $size);
	}

	// Image par défaut
	return get_template_directory_uri() . '/images/default-image.png';
}

/**
 * Obtenir le slug d'un terme de taxonomie indépendamment de la langue active
 *
 * WPML duplique les termes traduits avec un slug différent (ex: "renewables-fr"),
 * ce qui casse les classes CSS et les correspondances basées sur le slug
 * (ex: .renewables-card). Cette fonction résout toujours le terme dans la
 * langue par défaut du site pour garder un slug stable quelle que soit la langue.
 *
 * @param WP_Term $term Terme de taxonomie
 * @return string Slug du terme dans la langue par défaut
 */
function abyssenergy_get_language_independent_slug($term)
{
	if (!($term instanceof WP_Term)) {
		return '';
	}

	if (has_filter('wpml_object_id')) {
		$default_language = apply_filters('wpml_default_language', null);
		$default_term_id = apply_filters('wpml_object_id', $term->term_id, $term->taxonomy, false, $default_language);

		if ($default_term_id && (int) $default_term_id !== (int) $term->term_id) {
			$default_term = get_term($default_term_id, $term->taxonomy);
			if ($default_term && !is_wp_error($default_term)) {
				return $default_term->slug;
			}
		}
	}

	return $term->slug;
}

/**
 * Limiter un extrait à un nombre spécifique de mots
 *
 * @param string $text Texte à tronquer
 * @param int $limit Nombre de mots
 * @param string $more Texte à ajouter en fin d'extrait
 * @return string Extrait tronqué
 */
function abyssenergy_excerpt($text, $limit = 25, $more = '...')
{
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . $more;
	}
	return $text;
}

// La fonction abyssenergy_modify_job_query a été déplacée dans inc/jobs.php

/**
 * Demander au navigateur de ne pas mettre en cache la page des offres d'emploi
 */
function abyssenergy_update_header_cache()
{
	if (is_page(36)) {
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: Thu, 01 Dec 1990 16:00:00 GMT');
	}
}
add_action('template_redirect', 'abyssenergy_update_header_cache');

/**
 * Supprimer les attributs hauteur et largeur des images
 */
function abyssenergy_remove_width_and_height_attribute($html)
{
	return preg_replace('/(height|width)="\d*"\s/', "", $html);
}

// Appliquer aux différents filtres d'images
add_filter('get_image_tag', 'abyssenergy_remove_width_and_height_attribute', 10);
add_filter('post_thumbnail_html', 'abyssenergy_remove_width_and_height_attribute', 10);
add_filter('image_send_to_editor', 'abyssenergy_remove_width_and_height_attribute', 10);
