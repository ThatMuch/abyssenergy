<?php

/**
 * Fonctionnalités liées aux offres d'emploi
 *
 * Configuration et gestion du CPT Job et des fonctionnalités associées
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration spécifique pour la page des emplois
 */
function abyssenergy_jobs_setup()
{
	// Ajouter les variables de requête personnalisées pour les filtres d'emploi
	add_action('init', 'abyssenergy_add_job_query_vars');

	// Modifier la requête principale pour les filtres d'emploi
	add_action('pre_get_posts', 'abyssenergy_modify_job_query');
}
add_action('after_setup_theme', 'abyssenergy_jobs_setup');

/**
 * Ajouter les variables de requête personnalisées pour les filtres d'emploi
 */
function abyssenergy_add_job_query_vars()
{
	global $wp;
	$wp->add_query_var('job_search');
	$wp->add_query_var('job_sector'); // Reste au singulier pour compatibilité
	$wp->add_query_var('job_location'); // Reste au singulier pour compatibilité
	$wp->add_query_var('job_country'); // Reste au singulier pour compatibilité
	$wp->add_query_var('job_type');
	$wp->add_query_var('job_skill');

	// Ajout des variables pour les multisélects
	$wp->add_query_var('job_sector_multi');
	$wp->add_query_var('job_location_multi');
	$wp->add_query_var('job_country_multi');
	$wp->add_query_var('job_skill_multi');

	// Hook pour transformer les paramètres des tableaux en variables de requête
	add_action('parse_request', 'abyssenergy_parse_multiselect_query');
}

/**
 * Convertit les paramètres de tableau (job_sector[], etc.) en variables de requête
 */
function abyssenergy_parse_multiselect_query($wp)
{
	// Traiter les paramètres multiples pour le secteur
	if (isset($_GET['job_sector']) && is_array($_GET['job_sector'])) {
		// Filtrer les valeurs vides
		$sectors = array_filter($_GET['job_sector']);
		if (!empty($sectors)) {
			$wp->query_vars['job_sector_multi'] = $sectors;
		}
	}

	// Traiter les paramètres multiples pour la localisation
	if (isset($_GET['job_location']) && is_array($_GET['job_location'])) {
		// Filtrer les valeurs vides
		$locations = array_filter($_GET['job_location']);
		if (!empty($locations)) {
			$wp->query_vars['job_location_multi'] = $locations;
		}
	}

	// Traiter les paramètres multiples pour le pays
	if (isset($_GET['job_country']) && is_array($_GET['job_country'])) {
		// Filtrer les valeurs vides
		$countries = array_filter($_GET['job_country']);
		if (!empty($countries)) {
			$wp->query_vars['job_country_multi'] = $countries;
		}
	}

	if (isset($_GET['job_skill']) && is_array($_GET['job_skill'])) {
		// Filtrer les valeurs vides
		$skills = array_filter($_GET['job_skill']);
		if (!empty($skills)) {
			$wp->query_vars['job_skill_multi'] = $skills;
		}
	}
}

/**
 * Modifier la requête principale pour les pages d'emploi
 */
function abyssenergy_modify_job_query($query)
{
	if (!is_admin() && $query->is_main_query()) {

		// Pour l'archive des emplois
		if (is_post_type_archive('job')) {
			$query->set('posts_per_page', 12);
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
		}

		// Pour les pages avec template de jobs
		if (is_page() && get_page_template_slug() === 'page-jobs.php') {
			// Les filtres sont gérés dans le template avec WP_Query
		}
	}
}
add_action('pre_get_posts', 'abyssenergy_modify_job_query');

/**
 * Shortcode pour afficher les emplois
 */
function abyssenergy_jobs_shortcode($atts)
{
	$atts = shortcode_atts(array(
		'number' => 6,
		'sector' => '',
		'location' => '',
		'type' => '',
		'layout' => 'grid' // grid ou list
	), $atts, 'jobs_list');

	$args = array(
		'post_type' => 'job',
		'post_status' => 'publish',
		'posts_per_page' => intval($atts['number']),
		'orderby' => 'date',
		'order' => 'DESC'
	);

	// Filtres de taxonomie
	$tax_query = array();

	if (!empty($atts['sector'])) {
		$tax_query[] = array(
			'taxonomy' => 'job-sector',
			'field'    => 'slug',
			'terms'    => explode(',', $atts['sector']),
		);
	}

	if (!empty($atts['location'])) {
		$tax_query[] = array(
			'taxonomy' => 'job-location',
			'field'    => 'slug',
			'terms'    => explode(',', $atts['location']),
		);
	}

	if (!empty($atts['type'])) {
		$tax_query[] = array(
			'taxonomy' => 'job-type',
			'field'    => 'slug',
			'terms'    => explode(',', $atts['type']),
		);
	}

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}

	$jobs = new WP_Query($args);

	ob_start();

	if ($jobs->have_posts()) :
		echo '<div class="jobs-listing jobs-layout-' . esc_attr($atts['layout']) . '">';

		while ($jobs->have_posts()) : $jobs->the_post();
			// Inclure le template de la carte d'emploi
			get_template_part('template-parts/content', 'job-card');
		endwhile;

		echo '</div>';
	else :
		echo '<p>' . __('Aucune offre d\'emploi trouvée.', 'abyssenergy') . '</p>';
	endif;

	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode('jobs_list', 'abyssenergy_jobs_shortcode');

/**
 * Configuration des URLs personnalisées pour les jobs
 */
function abyssenergy_job_rewrite_rules()
{
	// Ajouter la règle de réécriture pour /job/ID
	add_rewrite_rule(
		'^job/([0-9]+)/?$',
		'index.php?job_id=$matches[1]',
		'top'
	);
}
add_action('init', 'abyssenergy_job_rewrite_rules');

/**
 * Ajouter la variable de requête personnalisée pour job_id
 */
function abyssenergy_add_job_id_query_var($vars)
{
	$vars[] = 'job_id';
	return $vars;
}
add_filter('query_vars', 'abyssenergy_add_job_id_query_var');

/**
 * Intercepter les requêtes pour /job/ID et rediriger vers le bon post
 */
function abyssenergy_job_template_redirect()
{
	$job_id = get_query_var('job_id');

	if ($job_id) {
		// Créer une nouvelle query avec le post spécifique
		$query = new WP_Query(array(
			'p' => $job_id,
			'post_type' => 'job',
			'post_status' => 'publish'
		));

		if ($query->have_posts()) {
			// Remplacer la query globale par notre query personnalisée
			global $wp_query, $post;
			$wp_query = $query;

			// Configurer les flags de la query pour une page single
			$wp_query->is_single = true;
			$wp_query->is_singular = true;
			$wp_query->is_home = false;
			$wp_query->is_page = false;
			$wp_query->is_404 = false;
			$wp_query->is_archive = false;
			$wp_query->is_search = false;

			// Initialiser le post courant
			$wp_query->the_post();
			$post = $wp_query->post;
			$wp_query->rewind_posts();

			// Charger le template approprié
			if (file_exists(get_template_directory() . '/single-job.php')) {
				include(get_template_directory() . '/single-job.php');
			} else {
				include(get_template_directory() . '/single.php');
			}
			exit;
		} else {
			// Post non trouvé, renvoyer 404
			global $wp_query;
			$wp_query->set_404();
			status_header(404);
			include(get_404_template());
			exit;
		}
	}
}
add_action('template_redirect', 'abyssenergy_job_template_redirect');

/**
 * Modifier les permaliens pour les jobs - utiliser l'ID au lieu du slug
 */
/** function abyssenergy_job_permalink($post_link, $post)
{
	if ($post->post_type === 'job' && is_numeric($post->slug)) {
		return home_url('/job/' . $post->slug . '/');
	}
	return $post_link;
} */
// add_filter('post_type_link', 'abyssenergy_job_permalink', 10, 2);

/**
 * Vider les règles de réécriture lors de l'activation du thème
 */
function abyssenergy_flush_job_rewrite_rules()
{
	// Forcer le rechargement des règles de réécriture
	delete_option('abyssenergy_rewrite_rules_flushed');
	abyssenergy_job_rewrite_rules();
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'abyssenergy_flush_job_rewrite_rules');

/**
 * Fonction utilitaire pour forcer le flush des règles (à utiliser si nécessaire)
 */
function abyssenergy_force_flush_job_rules()
{
	abyssenergy_job_rewrite_rules();
	flush_rewrite_rules();
}
// Décommenter la ligne ci-dessous temporairement si vous avez besoin de forcer le flush
add_action('init', 'abyssenergy_force_flush_job_rules', 999);

/**
 * Debug function - afficher les règles de réécriture actives
 */
function abyssenergy_debug_rewrite_rules()
{
	if (current_user_can('manage_options') && isset($_GET['debug_rewrite'])) {
		global $wp_rewrite;
		echo '<pre>';
		echo "Règles de réécriture:\n";
		print_r($wp_rewrite->rules);
		echo "\nQuery vars:\n";
		print_r($wp_rewrite->query_vars);
		echo '</pre>';
		exit;
	}
}
add_action('init', 'abyssenergy_debug_rewrite_rules');
