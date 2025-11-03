<?php

/**
 * Template part for displaying a project showcase card
 *
 * @package AbyssEnergy
 */

// Récupérer les arguments passés via get_template_part
$show_excerpt = isset($args['show_excerpt']) ? $args['show_excerpt'] : true;
$excerpt_limit = isset($args['excerpt_limit']) ? $args['excerpt_limit'] : ($is_featured ? 150 : 100);

// Récupérer les données du projet
$sectors = get_the_terms(get_the_ID(), 'project-sector');
$project_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
$project_excerpt = get_the_excerpt();

$card_class = 'project-card ' . $sectors[0]->slug;
?>

<article class="<?php echo esc_attr($card_class); ?>">
	<a href="<?php the_permalink(); ?>" class="project-link">
		<?php if ($project_image) : ?>
			<div class="project-image">
				<img src="<?php echo esc_url($project_image); ?>"
					alt="<?php echo esc_attr(get_the_title()); ?>"
					class="img-fluid"
					loading="lazy">
				<div class="project-overlay">
					<span class="view-project"><?php _e('View the project', 'abyssenergy'); ?></span>
				</div>
			</div>
		<?php endif; ?>

		<div class="project-content">
			<h3 class="project-title"><?php the_title(); ?></h3>
			<?php if ($show_excerpt && $project_excerpt) : ?>
				<p class="project-excerpt">
					<?php
					echo strlen($project_excerpt) > $excerpt_limit
						? substr($project_excerpt, 0, $excerpt_limit) . '...'
						: $project_excerpt;
					?>
				</p>
			<?php endif; ?>
		</div>
	</a>
</article>
