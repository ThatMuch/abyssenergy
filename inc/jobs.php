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
