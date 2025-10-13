<?php

/**
 * Fonction utilitaire pour vérifier ACF avant get_field()
 */

function safe_get_field($field, $post_id = false)
{
	if (!function_exists('get_field')) {
		return false;
	}
	return get_field($field, $post_id);
}

// Exemple d'utilisation recommandée :
// $subtitle = safe_get_field('subtitle') ?: '';
