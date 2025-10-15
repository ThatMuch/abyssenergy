<?php

/**
 * Filtres dynamiques pour les jobs
 * Gère la mise à jour des compteurs en fonction des filtres actifs
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Calculer les compteurs de filtres en fonction des filtres actifs
 */
function get_dynamic_job_filter_counts($active_filters = array())
{
	$counts = array(
		'sectors' => array(),
		'skills' => array(),
		'locations' => array(),
		'countries' => array()
	);

	// Base query args
	$base_args = array(
		'post_type' => 'job',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids'
	);

	// Pour chaque type de filtre, calculer les compteurs
	foreach (array('sectors', 'skills', 'locations', 'countries') as $filter_type) {
		$counts[$filter_type] = calculate_filter_counts($filter_type, $active_filters, $base_args);
	}

	return $counts;
}

/**
 * Calculer les compteurs pour un type de filtre spécifique
 */
function calculate_filter_counts($filter_type, $active_filters, $base_args)
{
	$counts = array();

	// Construire la query en excluant le filtre current
	$query_args = $base_args;
	$tax_query = array('relation' => 'AND');
	$meta_query = array('relation' => 'AND');

	// Ajouter tous les filtres actifs sauf celui qu'on calcule
	foreach ($active_filters as $type => $values) {
		if ($type === $filter_type || empty($values)) continue;

		switch ($type) {
			case 'sectors':
				$tax_query[] = array(
					'taxonomy' => 'job-sector',
					'field' => 'slug',
					'terms' => $values,
					'operator' => 'IN'
				);
				break;
			case 'skills':
				$tax_query[] = array(
					'taxonomy' => 'job-skill',
					'field' => 'slug',
					'terms' => $values,
					'operator' => 'IN'
				);
				break;
			case 'locations':
				if (count($values) > 1) {
					$location_meta_query = array('relation' => 'OR');
					foreach ($values as $location) {
						$location_meta_query[] = array(
							'key' => 'job_city',
							'value' => $location,
							'compare' => 'LIKE'
						);
					}
					$meta_query[] = $location_meta_query;
				} else {
					$meta_query[] = array(
						'key' => 'job_city',
						'value' => $values[0],
						'compare' => 'LIKE'
					);
				}
				break;
			case 'countries':
				if (count($values) > 1) {
					$country_meta_query = array('relation' => 'OR');
					foreach ($values as $country) {
						$country_meta_query[] = array(
							'key' => 'job_country',
							'value' => $country,
							'compare' => 'LIKE'
						);
					}
					$meta_query[] = $country_meta_query;
				} else {
					$meta_query[] = array(
						'key' => 'job_country',
						'value' => $values[0],
						'compare' => 'LIKE'
					);
				}
				break;
		}
	}

	// Ajouter les queries à la requête principale
	if (!empty($tax_query) && count($tax_query) > 1) {
		$query_args['tax_query'] = $tax_query;
	}
	if (!empty($meta_query) && count($meta_query) > 1) {
		$query_args['meta_query'] = $meta_query;
	}

	// Exécuter la query filtrée
	$filtered_query = new WP_Query($query_args);
	$filtered_job_ids = $filtered_query->posts;

	// Maintenant calculer les compteurs pour le type de filtre spécifique
	switch ($filter_type) {
		case 'sectors':
			$terms = get_terms(array(
				'taxonomy' => 'job-sector',
				'hide_empty' => false
			));
			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					$count = 0;
					foreach ($filtered_job_ids as $job_id) {
						if (has_term($term->term_id, 'job-sector', $job_id)) {
							$count++;
						}
					}
					$counts[$term->slug] = $count;
				}
			}
			break;

		case 'skills':
			$terms = get_terms(array(
				'taxonomy' => 'job-skill',
				'hide_empty' => false
			));
			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					$count = 0;
					foreach ($filtered_job_ids as $job_id) {
						if (has_term($term->term_id, 'job-skill', $job_id)) {
							$count++;
						}
					}
					$counts[$term->slug] = $count;
				}
			}
			break;

		case 'locations':
			$all_cities = array();
			foreach ($filtered_job_ids as $job_id) {
				$city = safe_get_field('job_city', $job_id);
				if ($city) {
					$city = trim(str_ireplace('Nearby', '', $city));
					if ($city) {
						if (!isset($all_cities[$city])) {
							$all_cities[$city] = 0;
						}
						$all_cities[$city]++;
					}
				}
			}
			$counts = $all_cities;
			break;

		case 'countries':
			$all_countries = array();
			foreach ($filtered_job_ids as $job_id) {
				$country = safe_get_field('job_country', $job_id);
				if ($country) {
					if (!isset($all_countries[$country])) {
						$all_countries[$country] = 0;
					}
					$all_countries[$country]++;
				}
			}
			$counts = $all_countries;
			break;
	}

	wp_reset_postdata();
	return $counts;
}

/**
 * Endpoint AJAX pour récupérer les compteurs mis à jour
 */
add_action('wp_ajax_get_job_filter_counts', 'ajax_get_job_filter_counts');
add_action('wp_ajax_nopriv_get_job_filter_counts', 'ajax_get_job_filter_counts');

function ajax_get_job_filter_counts()
{
	// Vérifier la sécurité
	if (!wp_verify_nonce($_POST['nonce'], 'job_filter_nonce')) {
		wp_die('Security check failed');
	}

	// Récupérer les filtres actifs depuis POST
	$active_filters = array();

	if (!empty($_POST['sectors'])) {
		$active_filters['sectors'] = array_map('sanitize_text_field', $_POST['sectors']);
	}

	if (!empty($_POST['skills'])) {
		$active_filters['skills'] = array_map('sanitize_text_field', $_POST['skills']);
	}

	if (!empty($_POST['locations'])) {
		$active_filters['locations'] = array_map('sanitize_text_field', $_POST['locations']);
	}

	if (!empty($_POST['countries'])) {
		$active_filters['countries'] = array_map('sanitize_text_field', $_POST['countries']);
	}

	// Calculer les nouveaux compteurs
	$counts = get_dynamic_job_filter_counts($active_filters);

	// Retourner en JSON
	wp_send_json_success($counts);
}

/**
 * Enqueue les scripts pour les filtres dynamiques
 */
add_action('wp_enqueue_scripts', 'enqueue_dynamic_filters_scripts');

function enqueue_dynamic_filters_scripts()
{
	// Charger le script sur toutes les pages, il se désactivera automatiquement s'il n'y a pas de formulaire
	if (true) { // Simplifié pour debug
		wp_enqueue_script(
			'dynamic-job-filters',
			get_template_directory_uri() . '/js/dynamic-job-filters.js',
			array('jquery'),
			time() . rand(), // Force reload
			true
		);

		// Localiser le script pour AJAX
		wp_localize_script('dynamic-job-filters', 'jobFiltersAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('job_filter_nonce')
		));
	}
}
