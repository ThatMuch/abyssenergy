<?php

/**
 * Testimonials Slider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 */

// Support custom "anchor" values.
$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'testimonials-slider-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Témoignages de nos clients';
$show_title = get_field('show_title') !== false;
$image = get_field('image');
$testimonials_limit = get_field('testimonials_limit');
// Déterminer le nombre de posts à récupérer
$posts_per_page = -1; // Par défaut, tous les témoignages
if (!empty($testimonials_limit) && is_numeric($testimonials_limit) && $testimonials_limit > 0) {
	$posts_per_page = intval($testimonials_limit);
}

// Récupération des témoignages
$args = array(
	'post_type' => 'testimonials',
	'posts_per_page' => $posts_per_page,
	'orderby' => 'date',
	'order' => 'DESC',
);

$testimonials = new WP_Query($args);
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php esc_html_e('Aperçu du bloc Témoignages', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>

	<?php if ($testimonials->have_posts()) : ?>
		<!-- Testimonials Slider Block -->
		<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>">
			<div class="container">
				<div class="testimonials-slider-block-header">
					<?php if ($show_title && $title) : ?>
						<h2 class="section-title"><?php echo esc_html($title); ?></h2>
					<?php endif; ?>
					<?php if ($image) : ?>
						<img src="<?php echo esc_url(wp_get_attachment_image_url($image, 'full')); ?>" alt="<?php echo esc_attr(get_post_meta($image, '_wp_attachment_image_alt', true)); ?>" class="img-fluid testimonial-section-image" loading="lazy" />
					<?php endif; ?>
				</div>
			</div>
			<div class="testimonials-slider swiper">
				<div class="swiper-wrapper">
					<?php while ($testimonials->have_posts()) : $testimonials->the_post();
						$sectors = get_the_terms(get_the_ID(), 'job-sector');
						$sector_class = '';
						if ($sectors && !is_wp_error($sectors) && !empty($sectors)) {
							$sector_class = esc_html($sectors[0]->slug) . '-card';
						}
					?>
						<div class="swiper-slide">
							<div class="testimonial-card card <?php echo esc_attr($sector_class); ?>">
								<div class="border"></div>
								<?php

								if ($sectors && !is_wp_error($sectors) && !empty($sectors)) {
									foreach ($sectors as $sector) {  ?>
										<span class="job-sector mb-3">
											<?php
											echo esc_html($sector->name);
											?></span>
								<?php
									}
								} ?>
								</span>

								<div class="testimonial-header">
									<h3 class="testimonial-title h4">
										<?php the_title(); ?>
									</h3>
									<?php if (has_post_thumbnail()) : ?>
										<div class="testimonial-logo">
											<?php the_post_thumbnail('medium'); ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="testimonial-content">
									<?php the_content(); ?>
								</div>


							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<!-- Navigation -->
				<div class="d-flex justify-content-center align-items-center mt-4 gap-3">
					<div class="left btn btn--outline  btn--icon"><i class="fa fa-chevron-left"></i></div>
					<div class="right btn btn--outline  btn--icon"><i class="fa fa-chevron-right"></i></div>
				</div>
			</div>
		</section>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php _e('Aucun témoignage trouvé.', 'abyssenergy'); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>
