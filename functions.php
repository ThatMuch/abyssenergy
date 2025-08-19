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
 * Configuration spécifique pour la page des emplois
 */
function squarechilli_child_jobs_setup()
{
	// Ajouter les variables de requête personnalisées pour les filtres d'emploi
	add_action('init', 'squarechilli_child_add_job_query_vars');

	// Modifier la requête principale pour les filtres d'emploi
	add_action('pre_get_posts', 'squarechilli_child_modify_job_query');
}
add_action('after_setup_theme', 'squarechilli_child_jobs_setup');

/**
 * Ajouter les variables de requête personnalisées pour les filtres d'emploi
 */
function squarechilli_child_add_job_query_vars()
{
	global $wp;
	$wp->add_query_var('job_search');
	$wp->add_query_var('job_sector');
	$wp->add_query_var('job_location');
	$wp->add_query_var('job_type');
}

/**
 * Modifier la requête principale pour les pages d'emploi
 */
function squarechilli_child_modify_job_query($query)
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

/**
 * Shortcode pour afficher les emplois
 */
function squarechilli_child_jobs_shortcode($atts)
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

	if (!empty($tax_query)) {
		$args['tax_query'] = $tax_query;
	}

	$jobs_query = new WP_Query($args);

	if (!$jobs_query->have_posts()) {
		return '<p class="no-jobs">Aucun emploi trouvé.</p>';
	}

	ob_start();
?>

	<div class="jobs-shortcode jobs-grid <?php echo esc_attr($atts['layout'] === 'list' ? 'list-view' : ''); ?>">
		<?php while ($jobs_query->have_posts()) : $jobs_query->the_post(); ?>
			<article class="job-card card">
				<div class="card__content">

					<!-- Badges -->
					<div class="job-badges mb-3">
						<?php
						$job_type = get_field('job_type');
						if ($job_type) : ?>
							<span class="badge badge--primary"><?php echo esc_html($job_type); ?></span>
							<?php endif;

						$sectors = get_the_terms(get_the_ID(), 'job-sector');
						if ($sectors && !is_wp_error($sectors)) :
							foreach ($sectors as $sector) : ?>
								<span class="badge badge--secondary"><?php echo esc_html($sector->name); ?></span>
						<?php endforeach;
						endif; ?>
					</div>

					<!-- Titre -->
					<h3 class="job-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>

					<!-- Localisation -->
					<?php
					$locations = get_the_terms(get_the_ID(), 'job-location');
					if ($locations && !is_wp_error($locations)) : ?>
						<p class="job-location text-muted">
							📍 <?php echo esc_html($locations[0]->name); ?>
						</p>
					<?php endif; ?>

					<!-- Extrait -->
					<div class="job-excerpt">
						<?php
						$excerpt = get_the_excerpt();
						if ($excerpt) :
							echo '<p>' . wp_trim_words($excerpt, 20, '...') . '</p>';
						endif;
						?>
					</div>
				</div>

				<div class="card__footer">
					<a href="<?php the_permalink(); ?>" class="btn btn--primary">
						Voir le poste
					</a>
				</div>
			</article>
		<?php endwhile; ?>
	</div>

	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('jobs_list', 'squarechilli_child_jobs_shortcode');

/**
 * Widget pour afficher les emplois récents
 */
class Squarechilli_Child_Recent_Jobs_Widget extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
			'squarechilli_recent_jobs',
			'Emplois récents',
			array('description' => 'Affiche les emplois récents dans la sidebar.')
		);
	}

	public function widget($args, $instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : 'Emplois récents';
		$number = !empty($instance['number']) ? absint($instance['number']) : 5;

		echo $args['before_widget'];
		echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];

		$jobs_query = new WP_Query(array(
			'post_type' => 'job',
			'posts_per_page' => $number,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC'
		));

		if ($jobs_query->have_posts()) : ?>
			<ul class="recent-jobs-list">
				<?php while ($jobs_query->have_posts()) : $jobs_query->the_post(); ?>
					<li class="recent-job-item">
						<a href="<?php the_permalink(); ?>" class="recent-job-link">
							<strong><?php the_title(); ?></strong>
							<?php
							$locations = get_the_terms(get_the_ID(), 'job-location');
							if ($locations && !is_wp_error($locations)) : ?>
								<small class="text-muted d-block">
									📍 <?php echo esc_html($locations[0]->name); ?>
								</small>
							<?php endif; ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p>Aucun emploi disponible.</p>
		<?php endif;

		wp_reset_postdata();
		echo $args['after_widget'];
	}

	public function form($instance)
	{
		$title = !empty($instance['title']) ? $instance['title'] : '';
		$number = !empty($instance['number']) ? absint($instance['number']) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Titre :</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
				name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
				value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>">Nombre d'emplois :</label>
			<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>"
				name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number"
				step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
		</p>
<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
		$instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
		return $instance;
	}
}

/**
 * Enregistrer le widget des emplois récents
 */
function squarechilli_child_register_jobs_widget()
{
	register_widget('Squarechilli_Child_Recent_Jobs_Widget');
}
add_action('widgets_init', 'squarechilli_child_register_jobs_widget');
