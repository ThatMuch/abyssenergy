<?php

/**
 * Fixed Jobs Block Initialization
 * @package AbyssEnergy
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrement du bloc Fixed Jobs
 */
if (function_exists('acf_register_block_type')) {
	add_action('acf/init', 'register_fixed_jobs_block');
}

function register_fixed_jobs_block()
{
	// Enregistrer le bloc
	acf_register_block_type(array(
		'name'              => 'fixed-jobs',
		'title'             => __('Fixed Jobs', 'abyssenergy'),
		'description'       => __('Affiche tous les postes fixes filtrés par catégorie dans des onglets.', 'abyssenergy'),
		'render_template'   => 'blocks/fixed-jobs/block-fixed-jobs.php',
		'category'          => 'abyss-blocks',
		'icon'              => 'businessperson',
		'keywords'          => array('jobs', 'fixed', 'careers', 'emploi'),
		'supports'          => array(
			'align' => true,
			'mode' => true,
			'jsx' => true
		),
		'example'           => array(
			'attributes' => array(
				'mode' => 'preview',
				'data' => array(
					'is_preview' => true
				)
			)
		),
		'enqueue_style'     => get_template_directory_uri() . '/blocks/fixed-jobs/fixed-jobs.css',
		'enqueue_script'    => get_template_directory_uri() . '/blocks/fixed-jobs/fixed-jobs.js',
	));

	if (function_exists('acf_add_local_field_group')) {
		// Créer les champs ACF pour le bloc
		acf_add_local_field_group(array(
			'key' => 'group_fixed_jobs_block',
			'title' => 'Fixed Jobs Block',
			'fields' => array(
				array(
					'key' => 'field_fixed_jobs_title',
					'label' => 'Titre',
					'name' => 'title',
					'type' => 'text',
					'default_value' => 'Nos Postes Fixes',
				),
				array(
					'key' => 'field_fixed_jobs_subtitle',
					'label' => 'Sous-titre',
					'name' => 'subtitle',
					'type' => 'text',
					'default_value' => 'Opportunités de Carrière',
				),
				array(
					'key' => 'field_fixed_jobs_description',
					'label' => 'Description',
					'name' => 'description',
					'type' => 'textarea',
					'rows' => 3,
				),
				array(
					'key' => 'field_fixed_jobs_show_all_tab',
					'label' => 'Afficher l\'onglet "Tous"',
					'name' => 'show_all_tab',
					'type' => 'true_false',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_fixed_jobs_posts_per_page',
					'label' => 'Nombre de postes par page',
					'name' => 'posts_per_page',
					'type' => 'number',
					'default_value' => 6,
					'min' => 1,
					'max' => 20,
				),
				array(
					'key' => 'field_fixed_jobs_show_excerpt',
					'label' => 'Afficher l\'extrait',
					'name' => 'show_excerpt',
					'type' => 'true_false',
					'default_value' => 1,
					'ui' => 1,
				),
				array(
					'key' => 'field_fixed_jobs_show_apply_button',
					'label' => 'Afficher le bouton candidater',
					'name' => 'show_apply_button',
					'type' => 'true_false',
					'default_value' => 1,
					'ui' => 1,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/fixed-jobs',
					),
				),
			),
		));
	}
}

/**
 * Enqueue des scripts avec les variables AJAX
 */
function enqueue_fixed_jobs_scripts()
{
	if (has_block('acf/fixed-jobs')) {
		wp_localize_script('wp-util', 'fixedJobsAjax', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('fixed_jobs_nonce')
		));
	}
}
add_action('wp_enqueue_scripts', 'enqueue_fixed_jobs_scripts');

/**
 * Handler AJAX pour charger plus de postes
 */
add_action('wp_ajax_load_more_fixed_jobs', 'load_more_fixed_jobs_ajax');
add_action('wp_ajax_nopriv_load_more_fixed_jobs', 'load_more_fixed_jobs_ajax');

function load_more_fixed_jobs_ajax()
{
	// Vérifier le nonce pour la sécurité
	if (!wp_verify_nonce($_POST['nonce'], 'fixed_jobs_nonce')) {
		wp_die('Nonce invalide');
	}

	$category = sanitize_text_field($_POST['category']);
	$page = intval($_POST['page']);
	$posts_per_page = intval($_POST['posts_per_page']);
	$show_excerpt = filter_var($_POST['show_excerpt'], FILTER_VALIDATE_BOOLEAN);
	$show_apply_button = filter_var($_POST['show_apply_button'], FILTER_VALIDATE_BOOLEAN);

	// Préparer les arguments de la requête
	$args = array(
		'post_type' => 'fixed-job',
		'post_status' => 'publish',
		'posts_per_page' => $posts_per_page,
		'paged' => $page,
	);

	// Ajouter le filtre par catégorie si nécessaire
	if ($category !== 'all') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'job-category',
				'field' => 'slug',
				'terms' => $category,
			),
		);
	}

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		ob_start();

		while ($query->have_posts()) : $query->the_post();
			$job_categories_list = get_the_terms(get_the_ID(), 'job-category');
?>
			<div class="fixed-job-card">
				<?php if (has_post_thumbnail()) : ?>
					<div class="job-thumbnail">
						<?php the_post_thumbnail('medium'); ?>
					</div>
				<?php endif; ?>

				<div class="job-content">
					<h3 class="job-title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>

					<?php if (!empty($job_categories_list)) : ?>
						<div class="job-categories">
							<?php foreach ($job_categories_list as $cat) : ?>
								<span class="job-category"><?php echo esc_html($cat->name); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ($show_excerpt && has_excerpt()) : ?>
						<div class="job-excerpt">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>

					<?php if ($show_apply_button) : ?>
						<div class="job-actions">
							<a href="<?php the_permalink(); ?>" class="btn btn-primary">
								<?php _e('Voir le poste', 'abyssenergy'); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php
		endwhile;

		$html = ob_get_clean();

		wp_send_json_success(array(
			'html' => $html,
			'has_more' => $page < $query->max_num_pages
		));
	} else {
		wp_send_json_success(array(
			'html' => '',
			'has_more' => false
		));
	}

	wp_reset_postdata();
	wp_die();
}

/**
 * Enqueue scripts et styles pour les pages single fixed-job
 */
function enqueue_single_fixed_job_assets()
{
	if (is_singular('fixed-job')) {
		// Enqueue JavaScript
		wp_enqueue_script(
			'single-fixed-job-script',
			get_template_directory_uri() . '/js/single-fixed-job.js',
			array('jquery'),
			filemtime(get_template_directory() . '/js/single-fixed-job.js'),
			true
		);

		// Localiser le script avec les variables AJAX
		wp_localize_script('single-fixed-job-script', 'fixedJobsSidebar', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('fixed_jobs_sidebar_nonce')
		));
	}
}
add_action('wp_enqueue_scripts', 'enqueue_single_fixed_job_assets');

/**
 * Handler AJAX pour charger plus de postes dans la sidebar
 */
add_action('wp_ajax_load_more_category_jobs', 'load_more_category_jobs_ajax');
add_action('wp_ajax_nopriv_load_more_category_jobs', 'load_more_category_jobs_ajax');

function load_more_category_jobs_ajax()
{
	// Vérifier le nonce pour la sécurité
	if (!wp_verify_nonce($_POST['nonce'], 'fixed_jobs_sidebar_nonce')) {
		wp_die('Nonce invalide');
	}

	$category = sanitize_text_field($_POST['category']);
	$loaded = intval($_POST['loaded']);

	// Récupérer la taxonomie pour obtenir le nombre total
	$term = get_term_by('slug', $category, 'job-category');
	if (!$term) {
		wp_send_json_error('Catégorie non trouvée');
	}

	// Récupérer TOUS les postes de cette catégorie pour éviter les doublons
	$args = array(
		'post_type' => 'fixed-job',
		'post_status' => 'publish',
		'numberposts' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'job-category',
				'field' => 'slug',
				'terms' => $category,
			),
		),
	);

	$all_jobs = get_posts($args);
	$total_jobs = count($all_jobs);

	// Récupérer seulement les postes à partir de l'offset pour éviter les doublons
	$remaining_jobs = array_slice($all_jobs, $loaded);

	if (!empty($remaining_jobs)) {
		ob_start();

		foreach ($remaining_jobs as $job) :
		?>
			<div class="job-item">
				<h5 class="job-item-title">
					<a href="<?php echo get_permalink($job->ID); ?>">
						<?php echo esc_html($job->post_title); ?>
					</a>
				</h5>
				<?php if (!empty($job->post_excerpt)) : ?>
					<p class="job-item-excerpt"><?php echo wp_trim_words($job->post_excerpt, 15); ?></p>
				<?php endif; ?>
			</div>
<?php
		endforeach;

		$html = ob_get_clean();

		wp_send_json_success(array(
			'html' => $html,
			'has_more' => false, // Tous les éléments restants ont été chargés
			'total' => $total_jobs,
			'loaded' => $loaded + count($remaining_jobs)
		));
	} else {
		wp_send_json_success(array(
			'html' => '',
			'has_more' => false,
			'total' => $total_jobs,
			'loaded' => $loaded
		));
	}

	wp_reset_postdata();
	wp_die();
}
