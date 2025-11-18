<?php

/**
 * Fichier principal du thème
 *
 * Ce fichier charge tous les composants modulaires du thème
 * pour une meilleure organisation et maintenabilité.
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

// Définir les constantes du thème
define('ABYSSENERGY_VERSION', '1.0.0');
define('ABYSSENERGY_DIR', get_template_directory());
define('ABYSSENERGY_URI', get_template_directory_uri());

/**
 * Fonction pour charger les fichiers de modules du thème
 */
function abyssenergy_load_theme_modules()
{
	$modules = [
		'acf-safe-helpers.php', // Helpers sécurisés pour ACF
		'inc/setup.php',      // Configuration principale du thème
		'inc/utils.php',      // Fonctions utilitaires
		'inc/enqueue.php',    // Enregistrement des scripts et styles
		'inc/admin.php',      // Personnalisation de l'interface d'administration
		'inc/blocks.php',     // Blocs Gutenberg personnalisés
		'inc/acf.php',        // Intégration avec Advanced Custom Fields
		'inc/jobs.php',       // Fonctionnalités liées aux offres d'emploi
		'inc/widgets.php',    // Widgets personnalisés
		'inc/shortcodes.php', // Shortcodes personnalisés
		'inc/scss.php',       // Compilation SCSS (si présent)
		'inc/customizer.php', // Personnalisations du Customizer WordPress
		'blocks/map/map-init.php', // Initialisation de la carte SVG
		'blocks/map/global-map-init.php', // Initialisation du bloc carte globale ACF
		'blocks/testimonials-slider/testimonials-slider-init.php', // Initialisation du slider de témoignages
		'blocks/jobs-listing/jobs-listing-init.php', // Initialisation du bloc des offres d'emploi
		'blocks/clients/clients-init.php', // Initialisation du bloc des clients
		'blocks/google-reviews/google-reviews-init.php', // Initialisation du bloc d'avis Google
		'blocks/project-showcase/project-showcase-init.php', // Initialisation du bloc des projets showcase
		'blocks/features/features-init.php', // Initialisation du bloc des fonctionnalités
		'blocks/metrics/metrics-init.php', // Initialisation du bloc des métriques
		'blocks/fixed-jobs/fixed-jobs-init.php', // Initialisation du bloc des postes fixes
		'blocks/card/card-init.php', // Initialisation du bloc carte simple
		'blocks/timeline/timeline-init.php', // Initialisation du bloc timeline
		'blocks/tabs/tabs-init.php', // Initialisation du bloc tabs
		'blocks/sectors/sectors-init.php', // Initialisation du bloc sectors
		'blocks/jobs-search/jobs-search-init.php', // Initialisation du bloc recherche d'emplois
		'blocks/custom-buttons/custom-buttons-init.php', // Initialisation du bloc boutons personnalisés
		'inc/dynamic-job-filters.php', // Filtres dynamiques pour les jobs
		'inc/gravity-forms-optgroups.php', // Support des optgroups pour Gravity Forms

	];

	foreach ($modules as $module) {
		if (file_exists(get_template_directory() . '/' . $module)) {
			require_once get_template_directory() . '/' . $module;
		}
	}
}

// Charger tous les modules du thème
abyssenergy_load_theme_modules();

// Legacy code - sera migré progressivement vers l'architecture modulaire

//images
add_theme_support('post-thumbnails');

set_post_thumbnail_size(385, 288, true); //featured image - the true sets it to this size exactly, omitting this will scale longest side. Call by  the_post_thumbnail();
//medium add_image_size( 'two-col-standard', 534, 313, true ); //repeat this for other thumbs. Call by  the_post_thumbnail('blogfeatured');
add_image_size('barbers', 288, 360, true);
add_image_size('third-width', 377, 323, true);
add_image_size('page-header', 1440, 430, true);

// add menu support and fallback menu if menu doesn't exist
add_action('init', 'register_my_menus');

function register_my_menus()
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

//acf options page
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title'   => 'General Options',
		'menu_title'  => 'General Options',
		'menu_slug'   => 'theme-general-settings',
		'capability'  => 'edit_posts',
		'redirect'    => false
	));
}

// Filtre Gravity Forms pour désactiver les styles par défaut
add_filter('gform_disable_css', '__return_true');

/**
 * Vider les règles de réécriture lors de l'initialisation du thème
 */
function abyssenergy_flush_rewrite_rules()
{
	$flushed = get_option('abyssenergy_rewrite_rules_flushed');

	// Si les règles n'ont pas encore été vidées, le faire maintenant
	if (!$flushed) {
		flush_rewrite_rules();
		update_option('abyssenergy_rewrite_rules_flushed', true);
	}
}
add_action('init', 'abyssenergy_flush_rewrite_rules', 20);

/**
 * Ajouter automatiquement loading="lazy" aux images dans wp_get_attachment_image
 */
function abyssenergy_add_lazy_loading_to_images($attr, $attachment, $size)
{
	// Ne pas ajouter loading="lazy" si déjà présent ou si c'est le logo dans le header
	if (isset($attr['loading']) || (isset($attr['class']) && strpos($attr['class'], 'header__logo-image') !== false)) {
		return $attr;
	}

	// Ajouter loading="lazy" par défaut
	$attr['loading'] = 'lazy';

	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'abyssenergy_add_lazy_loading_to_images', 10, 3);

/**
 * Ajouter loading="lazy" aux images dans le contenu des posts
 */
function abyssenergy_add_lazy_loading_to_content_images($content)
{
	// Utiliser une regex pour ajouter loading="lazy" aux images qui ne l'ont pas déjà
	$content = preg_replace_callback(
		'/<img([^>]*)>/i',
		function ($matches) {
			$img_tag = $matches[0];

			// Si loading= est déjà présent, ne pas modifier
			if (strpos($img_tag, 'loading=') !== false) {
				return $img_tag;
			}

			// Si c'est le logo du header, ne pas ajouter loading="lazy"
			if (strpos($img_tag, 'header__logo-image') !== false) {
				return $img_tag;
			}

			// Ajouter loading="lazy" avant la fermeture du tag
			return str_replace('>', ' loading="lazy">', $img_tag);
		},
		$content
	);

	return $content;
}
add_filter('the_content', 'abyssenergy_add_lazy_loading_to_content_images');
add_filter('widget_text_content', 'abyssenergy_add_lazy_loading_to_content_images');

/**
 * Hook pour transmettre le post ID au plugin squarechilli-jobboard
 * lors de la soumission de formulaires Gravity Forms
 */
function abyssenergy_set_post_context_for_gravity_forms()
{
	// Vérifier si nous sommes dans une requête AJAX de Gravity Forms
	if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['action']) && $_POST['action'] === 'gf_submit') {
		// Récupérer le post_id depuis les données du formulaire
		$post_id = null;

		// Essayer différentes sources pour le post ID
		if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
			$post_id = intval($_POST['post_id']);
		} elseif (isset($_POST['input_999']) && !empty($_POST['input_999'])) {
			$post_id = intval($_POST['input_999']);
		} elseif (isset($_POST['gform_fields']) && !empty($_POST['gform_fields'])) {
			// Parser les champs pour trouver le post_id
			$fields = json_decode(stripslashes($_POST['gform_fields']), true);
			if (is_array($fields)) {
				foreach ($fields as $field) {
					if (isset($field['name']) && $field['name'] === 'post_id' && !empty($field['value'])) {
						$post_id = intval($field['value']);
						break;
					}
				}
			}
		}

		if ($post_id && $post_id > 0) {
			global $post;
			$post = get_post($post_id);

			// Définir la query globale pour que get_the_ID() fonctionne
			if ($post) {
				setup_postdata($post);
				$GLOBALS['wp_query']->post = $post;
				$GLOBALS['wp_query']->queried_object = $post;
				$GLOBALS['wp_query']->queried_object_id = $post_id;
				$GLOBALS['wp_query']->is_single = true;
				$GLOBALS['wp_query']->is_singular = true;

				// Ajouter le post ID aux superglobales pour être sûr
				$_POST['current_post_id'] = $post_id;
				$_GET['p'] = $post_id;
			}
		}
	}
}
add_action('wp_ajax_gf_submit', 'abyssenergy_set_post_context_for_gravity_forms', 1);
add_action('wp_ajax_nopriv_gf_submit', 'abyssenergy_set_post_context_for_gravity_forms', 1);

/**
 * Hook pour s'assurer que le post ID est disponible durant tout le processus
 */
function abyssenergy_set_post_context_early()
{
	if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['action']) && $_POST['action'] === 'gf_submit') {
		abyssenergy_set_post_context_for_gravity_forms();
	}
}
add_action('init', 'abyssenergy_set_post_context_early', 1);

/**
 * Ajouter le post ID comme variable dynamique dans Gravity Forms
 */
function abyssenergy_populate_post_id_field($value, $field, $name)
{
	if ($name == 'post_id') {
		global $post;
		if ($post && $post->post_type === 'job') {
			return $post->ID;
		}
		// Fallback: essayer de récupérer depuis get_the_ID()
		$current_post_id = get_the_ID();
		if ($current_post_id) {
			return $current_post_id;
		}
	}
	return $value;
}
add_filter('gform_field_value_post_id', 'abyssenergy_populate_post_id_field', 10, 3);

/**
 * Hook Gravity Forms avant traitement pour définir le contexte post
 */
function abyssenergy_gform_pre_submission_handler($form, $entry)
{
	if ($form['id'] == 1) { // Formulaire de candidature
		// Debug: enregistrer les données reçues
		error_log('GForm Pre-submission - POST data: ' . print_r($_POST, true));

		// Récupérer le post ID depuis les données du formulaire
		$post_id = null;

		if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
			$post_id = intval($_POST['post_id']);
		} elseif (isset($_POST['input_999']) && !empty($_POST['input_999'])) {
			$post_id = intval($_POST['input_999']);
		}

		if ($post_id && $post_id > 0) {
			error_log('Setting up WordPress context for post ID: ' . $post_id);

			global $post;
			$post = get_post($post_id);

			if ($post) {
				setup_postdata($post);
				$GLOBALS['wp_query']->post = $post;
				$GLOBALS['wp_query']->queried_object = $post;
				$GLOBALS['wp_query']->queried_object_id = $post_id;
				$GLOBALS['wp_query']->is_single = true;
				$GLOBALS['wp_query']->is_singular = true;

				// Ajouter aux superglobales
				$_GET['p'] = $post_id;
				$_REQUEST['p'] = $post_id;

				// Debug: vérifier le job_id du post
				$job_id = get_field('job_id', $post_id);
				error_log('WordPress context set successfully for post ID: ' . $post_id);
				error_log('get_the_ID() now returns: ' . get_the_ID());
				error_log('get_queried_object_id() now returns: ' . get_queried_object_id());
				error_log('ACF job_id field value: ' . $job_id);
			}
		} else {
			error_log('No post ID found in form submission data');
		}
	}
}
add_action('gform_pre_submission_1', 'abyssenergy_gform_pre_submission_handler', 5, 2);

function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('init', 'hide_update_notice_to_all_but_admin_users');

//ask the browser not to cache the job search page
add_action('template_redirect', 'update_header_cache');
function update_header_cache()
{
	if (is_page(36)) {
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Pragma: no-cache');
		header('Expires: Thu, 01 Dec 1990 16:00:00 GMT');
	}
}
/**
 * 1. Planifier l'événement quotidien si ce n'est pas déjà fait
 */
function mon_plugin_planifier_mise_a_jour()
{
	// Vérifie si l'événement est déjà planifié
	if (! wp_next_scheduled('mon_action_mise_a_jour_filtre')) {
		// Planifie l'action pour qu'elle s'exécute une fois par jour ('daily')
		// à partir de maintenant.
		wp_schedule_event(time(), 'daily', 'mon_action_mise_a_jour_filtre');
	}
}
// Lance la planification lors de l'activation du thème/plugin
add_action('wp', 'mon_plugin_planifier_mise_a_jour');

/**
 * Optionnel : Nettoyer l'événement si le plugin/thème est désactivé
 */
function mon_plugin_desactiver_mise_a_jour()
{
	wp_clear_scheduled_hook('mon_action_mise_a_jour_filtre');
}
register_deactivation_hook(__FILE__, 'mon_plugin_desactiver_mise_a_jour');


/**
 * 2. Fonction qui s'exécute chaque jour pour mettre à jour les posts
 */
function mon_plugin_executer_mise_a_jour_filtre()
{
	// 1. Définir les arguments de la requête
	$args = array(
		'post_type'      => 'jobs', // <-- REMPLACER CE SLUG
		'post_status'    => 'publish', // Ne met à jour que les posts publiés
		'posts_per_page' => -1,       // Récupère tous les posts
		'fields'         => 'ids',    // Ne récupère que les IDs pour l'efficacité
	);

	$posts_a_mettre_a_jour = new WP_Query($args);

	if ($posts_a_mettre_a_jour->have_posts()) {
		// 2. Parcourir chaque ID de post
		foreach ($posts_a_mettre_a_jour->posts as $post_id) {
			// 3. Forcer la mise à jour
			// wp_update_post( array( 'ID' => $post_id ) ) exécute toutes les hooks de sauvegarde
			// comme si vous aviez cliqué sur "Mettre à jour" dans l'admin.
			wp_update_post(array(
				'ID' => $post_id,
				// On peut ajouter une valeur pour s'assurer que la BDD voit un changement
				// Même si le contenu est le même, la simple présence de l'ID suffit souvent
			));
		}
	}

	// Optionnel : Ajouter une entrée dans les logs pour confirmer l'exécution
	error_log('WP-Cron : Mise à jour de l\'index Search & Filter déclenchée le ' . date('Y-m-d H:i:s'));
}
// Assigner la fonction à l'événement planifié
add_action('mon_action_mise_a_jour_filtre', 'mon_plugin_executer_mise_a_jour_filtre');
