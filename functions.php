<?php

/**
 * Functions et hooks pour le thème Abyss Energy
 *
 * Thème WordPress autonome et moderne pour Abyss Energy
 * Comprend toutes les fonctionnalités nécessaires sans dépendance parent
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

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

//turn off the menu bar for all but admin
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
//align wide options
add_theme_support('align-wide');

add_theme_support('title-tag');

//remove inline block styling
remove_action('init', 'register_block_core_gallery');

//and the stylesheets
function prefix_remove_core_block_styles()
{
	wp_dequeue_style('wp-block-columns');
	wp_dequeue_style('wp-block-column');
}
add_action('wp_enqueue_scripts', 'prefix_remove_core_block_styles');


//gutenberg custom colour samples
add_theme_support('editor-color-palette', array(
	array(
		'name'  => __('Orange', 'thatmuch'),
		'slug'  => 'colour-orange',
		'color'  => '#ff6b01',
	),
	array(
		'name'  => __('Light Orange', 'thatmuch'),
		'slug'  => 'colour-lightorange',
		'color'  => '#F1D5C1',
	),
	array(
		'name'  => __('Dark Blue', 'thatmuch'),
		'slug'  => 'colour-darkblue',
		'color' => '#0d4a77',
	),
	array(
		'name'  => __('Light Grey', 'thatmuch'),
		'slug'  => 'colour-lightgrey',
		'color' => '#dcd7d4',
	),
	array(
		'name'  => __('Grey', 'thatmuch'),
		'slug'  => 'colour-grey',
		'color' => '#7b7672',
	),
	array(
		'name'  => __('Green', 'thatmuch'),
		'slug'  => 'colour-green',
		'color' => '#528326',
	),
	array(
		'name'  => __('Light Blue', 'thatmuch'),
		'slug'  => 'colour-lightblue',
		'color' => '#007ce0',
	),
	array(
		'name'  => __('Lightest Blue', 'thatmuch'),
		'slug'  => 'colour-lightestblue',
		'color' => '#e5f2fc',
	),
	array(
		'name'  => __('White', 'thatmuch'),
		'slug'  => 'colour-white',
		'color' => '#fff',
	),
	array(
		'name'  => __('Black', 'thatmuch'),
		'slug'  => 'colour-black',
		'color' => '#000',
	),

));

//editor styles
add_theme_support('editor-styles');
add_editor_style('css/admin-theme-squarechilli.css');


//customise wordpress bits
function custom_login_css()
{
	echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/css/login-styles.css" />';
}
add_action('login_head', 'custom_login_css');

function my_login_logo_url()
{
	return get_bloginfo('url');
}
add_filter('login_headerurl', 'my_login_logo_url');

function my_login_logo_url_title()
{
	return 'squarechilli';
}
add_filter('login_headertext', 'my_login_logo_url_title');

add_filter('login_errors', function ($a) {
	return null;
}); //remove login errors so bots can't hack as easily

//excerpt length
add_filter('excerpt_length', function ($length) {
	return 20;
});

function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}
add_action('admin_head', 'hide_update_notice_to_all_but_admin_users', 1);

//display wordpress image sizes in admin
function display_custom_image_sizes($sizes)
{
	global $_wp_additional_image_sizes;
	if (empty($_wp_additional_image_sizes))
		return $sizes;

	foreach ($_wp_additional_image_sizes as $id => $data) {
		if (!isset($sizes[$id]))
			$sizes[$id] = ucfirst(str_replace('-', ' ', $id));
	}

	return $sizes;
}
add_filter('image_size_names_choose', 'display_custom_image_sizes');

//remove height and width from images
add_filter('get_image_tag', 'remove_width_and_height_attribute', 10);
add_filter('post_thumbnail_html', 'remove_width_and_height_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_and_height_attribute', 10);

function remove_width_and_height_attribute($html)
{
	return preg_replace('/(height|width)="\d*"\s/', "", $html);
}

//admin footer text
function change_footer_admin()
{
	echo 'Made by <a href="https://www.thatmuch.fr">THATMUCH</a>.';
}

add_filter('admin_footer_text', 'change_footer_admin');

//remove wordpress logo
add_action('admin_bar_menu', 'remove_wp_logo', 999);

function remove_wp_logo($wp_admin_bar)
{
	$wp_admin_bar->remove_node('wp-logo');
}

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
 * Compilation automatique des fichiers SCSS (mode développement uniquement)
 */
function abyssenergy_compile_scss()
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
function abyssenergy_get_file_version($file)
{
	$file_path = get_stylesheet_directory() . $file;
	return file_exists($file_path) ? filemtime($file_path) : wp_get_theme()->get('Version');
}

// Compiler SCSS au chargement de l'admin et du front-end
add_action('init', 'abyssenergy_compile_scss');

/**
 * Enqueue des styles et scripts du thème
 */
function abyssenergy_enqueue_scripts()
{
	// Style principal du thème
	wp_enqueue_style(
		'abyssenergy-style',
		get_stylesheet_directory_uri() . '/style.min.css',
		array(),
		abyssenergy_get_file_version('/style.min.css')
	);

	// Script général du thème
	wp_enqueue_script(
		'abyssenergy-general',
		get_stylesheet_directory_uri() . '/js/general.js',
		array('jquery'),
		abyssenergy_get_file_version('/js/general.js'),
		true
	);
}

add_action('wp_enqueue_scripts', 'abyssenergy_enqueue_scripts');

/**
 * Configuration du thème
 */
function abyssenergy_theme_setup()
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
add_action('after_setup_theme', 'abyssenergy_theme_setup');

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
	$wp->add_query_var('job_sector');
	$wp->add_query_var('job_location');
	$wp->add_query_var('job_type');
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
add_shortcode('jobs_list', 'abyssenergy_jobs_shortcode');

/**
 * Widget pour afficher les emplois récents
 */
class Abyssenergy_Recent_Jobs_Widget extends WP_Widget
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
function abyssenergy_register_jobs_widget()
{
	register_widget('Abyssenergy_Recent_Jobs_Widget');
}
add_action('widgets_init', 'abyssenergy_register_jobs_widget');

/**
 * Configuration du CTA Header via le Customizer WordPress
 */
function abyssenergy_header_cta_customize_register($wp_customize)
{

	// Section CTA Header
	$wp_customize->add_section('squarechilli_header_cta_section', array(
		'title'       => __('CTA Header', 'abyssenergy'),
		'description' => __('Configurez le bouton d\'appel à l\'action qui apparaît dans l\'en-tête du site.', 'abyssenergy'),
		'priority'    => 25,
	));

	// Activation du CTA Header
	$wp_customize->add_setting('squarechilli_header_cta_enabled', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_enabled', array(
		'label'    => __('Activer le CTA dans le header', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 10,
	));

	// Texte du CTA
	$wp_customize->add_setting('squarechilli_header_cta_text', array(
		'default'           => __('Nous contacter', 'abyssenergy'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('squarechilli_header_cta_text', array(
		'label'    => __('Texte du bouton', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'text',
		'priority' => 20,
	));

	// URL du CTA
	$wp_customize->add_setting('squarechilli_header_cta_url', array(
		'default'           => home_url('/contact/'),
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_url', array(
		'label'    => __('URL de destination', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'url',
		'priority' => 30,
	));

	// Style du CTA
	$wp_customize->add_setting('squarechilli_header_cta_style', array(
		'default'           => 'primary',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_style', array(
		'label'    => __('Style du bouton', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'select',
		'priority' => 40,
		'choices'  => array(
			'primary'   => __('Primary', 'abyssenergy'),
			'secondary' => __('Secondary', 'abyssenergy'),
			'outline'   => __('Outline', 'abyssenergy'),

		),
	));

	// Taille du CTA
	$wp_customize->add_setting('squarechilli_header_cta_size', array(
		'default'           => 'medium',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_size', array(
		'label'    => __('Taille du bouton', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'select',
		'priority' => 50,
		'choices'  => array(
			'small'  => __('Petit', 'abyssenergy'),
			'medium' => __('Moyen', 'abyssenergy'),
			'large'  => __('Grand', 'abyssenergy'),
		),
	));

	// Icône du CTA
	$wp_customize->add_setting('squarechilli_header_cta_icon', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('squarechilli_header_cta_icon', array(
		'label'       => __('Icône (optionnel)', 'abyssenergy'),
		'description' => __('Code HTML de l\'icône (ex: &lt;i class="fas fa-phone"&gt;&lt;/i&gt;)', 'abyssenergy'),
		'section'     => 'squarechilli_header_cta_section',
		'type'        => 'text',
		'priority'    => 60,
	));

	// Ouverture dans un nouvel onglet
	$wp_customize->add_setting('squarechilli_header_cta_target', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_target', array(
		'label'    => __('Ouvrir dans un nouvel onglet', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 70,
	));

	// Masquer sur mobile
	$wp_customize->add_setting('squarechilli_header_cta_hide_mobile', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('squarechilli_header_cta_hide_mobile', array(
		'label'    => __('Masquer sur mobile', 'abyssenergy'),
		'section'  => 'squarechilli_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 80,
	));
}
add_action('customize_register', 'abyssenergy_header_cta_customize_register');

/**
 * Enqueue des scripts pour le CTA Header
 */
function abyssenergy_enqueue_header_cta_assets()
{
	// Script pour le CTA Header
	if (get_theme_mod('squarechilli_header_cta_enabled', false)) {
		wp_enqueue_script(
			'squarechilli-header-cta',
			get_stylesheet_directory_uri() . '/js/header-cta.js',
			array(),
			abyssenergy_get_file_version('/js/header-cta.js'),
			true
		);

		// Variables pour le script header CTA
		wp_localize_script('squarechilli-header-cta', 'squarechilliHeaderCTA', array(
			'enabled' => get_theme_mod('squarechilli_header_cta_enabled', false),
			'text' => get_theme_mod('squarechilli_header_cta_text', __('Nous contacter', 'abyssenergy')),
			'url' => get_theme_mod('squarechilli_header_cta_url', home_url('/contact/')),
			'style' => get_theme_mod('squarechilli_header_cta_style', 'primary'),
			'size' => get_theme_mod('squarechilli_header_cta_size', 'medium'),
			'icon' => get_theme_mod('squarechilli_header_cta_icon', ''),
			'target' => get_theme_mod('squarechilli_header_cta_target', false),
			'hide_mobile' => get_theme_mod('squarechilli_header_cta_hide_mobile', false),
		));
	}
}
add_action('wp_enqueue_scripts', 'abyssenergy_enqueue_header_cta_assets');

/**
 * Fonction pour afficher le CTA Header (utilisée pour preview et fallback)
 */
function abyssenergy_display_header_cta()
{
	// Vérifier si le CTA est activé
	if (!get_theme_mod('squarechilli_header_cta_enabled', false)) {
		return '';
	}

	// Récupérer les paramètres
	$text = get_theme_mod('squarechilli_header_cta_text', __('Nous contacter', 'abyssenergy'));
	$url = get_theme_mod('squarechilli_header_cta_url', home_url('/contact/'));
	$style = get_theme_mod('squarechilli_header_cta_style', 'primary');
	$size = get_theme_mod('squarechilli_header_cta_size', 'medium');
	$icon = get_theme_mod('squarechilli_header_cta_icon', '');
	$target = get_theme_mod('squarechilli_header_cta_target', false);
	$hide_mobile = get_theme_mod('squarechilli_header_cta_hide_mobile', false);

	// Si pas de texte ou d'URL, ne pas afficher
	if (empty($text) || empty($url)) {
		return '';
	}

	// Classes CSS
	$button_classes = array(
		'header-cta-btn',
		'btn',
		'btn--' . $style,
		'btn--' . $size
	);

	if ($hide_mobile) {
		$button_classes[] = 'header-cta-btn--hide-mobile';
	}

	// Attributs du lien
	$link_attrs = array(
		'href' => esc_url($url),
		'class' => esc_attr(implode(' ', $button_classes))
	);

	if ($target) {
		$link_attrs['target'] = '_blank';
		$link_attrs['rel'] = 'noopener noreferrer';
	}

	// Construction du HTML
	$attributes = '';
	foreach ($link_attrs as $attr => $value) {
		$attributes .= sprintf(' %s="%s"', $attr, $value);
	}

	ob_start();
	?>
	<div class="header-cta">
		<a<?php echo $attributes; ?>>
			<?php if (!empty($icon)) : ?>
				<span class="header-cta-btn__icon"><?php echo wp_kses_post($icon); ?></span>
			<?php endif; ?>
			<span class="header-cta-btn__text"><?php echo esc_html($text); ?></span>
			</a>
	</div>
<?php
	return ob_get_clean();
}

// add in customizer site identity the logo for the footer
function abyssenergy_customize_register_footer_logo($wp_customize)
{
	// Ajouter le logo du footer
	$wp_customize->add_setting('squarechilli_footer_logo', array(
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control(
		$wp_customize,
		'squarechilli_footer_logo',
		array(
			'label'    => __('Logo du Footer', 'abyssenergy'),
			'section'  => 'title_tagline',
			'settings' => 'squarechilli_footer_logo',
			'priority' => 30,
		)
	));
}
add_action('customize_register', 'abyssenergy_customize_register_footer_logo');
