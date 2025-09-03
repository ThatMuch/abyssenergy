<?php

/**
 * Widgets et sidebars personnalisés
 *
 * Enregistrement et configuration des widgets et sidebars
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Enregistrer les sidebars du thème
 */
function abyssenergy_register_sidebars()
{
	// Sidebar principale
	register_sidebar(array(
		'name'          => __('Sidebar principale', 'abyssenergy'),
		'id'            => 'main-sidebar',
		'description'   => __('Widgets affichés dans la sidebar principale.', 'abyssenergy'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	// Sidebar du pied de page
	register_sidebar(array(
		'name'          => __('Footer Widgets', 'abyssenergy'),
		'id'            => 'footer-widgets',
		'description'   => __('Widgets affichés dans le pied de page.', 'abyssenergy'),
		'before_widget' => '<div id="%1$s" class="widget footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	));
}
add_action('widgets_init', 'abyssenergy_register_sidebars');

/**
 * Widget pour afficher les emplois récents
 */
class Abyssenergy_Recent_Jobs_Widget extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
			'abyssenergy_recent_jobs',
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
