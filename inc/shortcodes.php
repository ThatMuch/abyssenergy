<?php

/**
 * Shortcodes personnalisés
 *
 * Définitions de shortcodes pour le thème
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Note: Le shortcode pour afficher les emplois [jobs_list] est maintenant défini dans inc/jobs.php
 * Pour éviter les déclarations dupliquées, il a été supprimé de ce fichier.
 *
 * @see inc/jobs.php
 */

/**
 * Shortcode pour afficher un bouton stylisé
 */
function abyssenergy_button_shortcode($atts, $content = null)
{
	$atts = shortcode_atts(array(
		'url' => '#',
		'target' => '_self',
		'style' => 'primary', // primary, secondary, outline
		'size' => 'medium', // small, medium, large
		'icon' => '', // nom de l'icône (classe)
		'align' => '', // left, center, right
	), $atts, 'button');

	$classes = array('btn');
	$classes[] = 'btn--' . $atts['style'];
	$classes[] = 'btn--' . $atts['size'];

	$wrapper_class = '';
	if ($atts['align']) {
		$wrapper_class = 'text-' . $atts['align'];
	}

	$icon_html = '';
	if ($atts['icon']) {
		$icon_html = '<i class="' . esc_attr($atts['icon']) . '"></i> ';
	}

	$output = '';
	if ($wrapper_class) {
		$output .= '<div class="' . esc_attr($wrapper_class) . '">';
	}

	$output .= '<a href="' . esc_url($atts['url']) . '"
                  class="' . esc_attr(implode(' ', $classes)) . '"
                  target="' . esc_attr($atts['target']) . '">'
		. $icon_html . esc_html($content) .
		'</a>';

	if ($wrapper_class) {
		$output .= '</div>';
	}

	return $output;
}
add_shortcode('button', 'abyssenergy_button_shortcode');
