<?php

/**
 * Google Reviews Block Template.
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
$class_name = 'google-reviews-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Ce que nos clients disent de nous';
$subtitle = get_field('subtitle') ?: 'Avis Google';
$show_title = get_field('show_title') !== false;
$place_id = get_field('place_id');
$api_key = get_field('api_key');
$reviews_count = get_field('reviews_count') ?: 5;
$min_rating = get_field('min_rating') ?: 1;
$cache_time = get_field('cache_time') ?: 24;
$display_style = get_field('display_style') ?: 'slider';
$image = get_field('image');

// Ajouter la classe de style d'affichage
$class_name .= ' display-' . $display_style;

// Récupérer les avis
$reviews_data = array();
if ($place_id && $api_key) {
	$reviews_data = abyssenergy_get_google_reviews($place_id, $api_key, $reviews_count, $min_rating, $cache_time);
}

// Vérifier si on est en mode aperçu
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc d\'avis Google. Les avis réels s\'afficheront sur le site.', 'abyssenergy'); ?></p>
	</div>
<?php else : ?>

	<!-- Google Reviews Block -->
	<section <?php echo $anchor; ?>class="<?php echo esc_attr($class_name); ?> section section-reviews">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/blocks/google-reviews/trees.svg" alt="trees" class="trees-svg">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col col-md-7">
					<div class="d-flex gap-4">
						<?php if ($image) : ?>
							<div class="section--image">
								<?php echo wp_get_attachment_image($image, 'medium'); ?>
							</div>
						<?php endif; ?>
						<div>
							<?php if ($show_title) : ?>
								<?php if ($subtitle) : ?>
									<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
								<?php endif; ?>
								<?php if ($title) : ?>
									<h2 class="section--title"><?php echo esc_html($title); ?></h2>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php if (!empty($reviews_data) && !$reviews_data['error']) : ?>
					<div class="rating-summary col col-md-2">
						<div class="average-rating">
							<span class="rating-value"><?php echo number_format($reviews_data['rating'], 1); ?></span>
							<div class="rating-stars">
								<?php
								$rating = $reviews_data['rating'];
								for ($i = 1; $i <= 5; $i++) {
									if ($i <= $rating) {
										echo '<i class="fas fa-star"></i>';
									} elseif ($i - 0.5 <= $rating) {
										echo '<i class="fas fa-star-half-alt"></i>';
									} else {
										echo '<i class="far fa-star"></i>';
									}
								}
								?>
							</div>
							<p class="based-on"><?php echo sprintf(__('Basé sur %d avis', 'abyssenergy'), count($reviews_data['reviews'])); ?></p>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php if (!empty($reviews_data) && !$reviews_data['error']) : ?>
				<div class="google-reviews-summary">
					<div class="google-reviews-slider swiper-container">
						<div class="swiper-wrapper">
							<?php foreach ($reviews_data['reviews'] as $review) : ?>
								<div class="swiper-slide">
									<div class="review-card card">
										<div class="review-header">
											<div class="review-author">
												<h4 class="author-name"><?php echo esc_html($review['author']); ?></h4>
											</div>
										</div>
										<div class="review-text">
											<?php
											$text = esc_html($review['text']);
											echo strlen($text) > 200 ? substr($text, 0, 200) . '...' : $text;
											?>
										</div>
										<div class="review-rating">
											<span class="rating-google"><i class="fa-brands fa-google"></i></span>
											<div>
												<?php for ($i = 1; $i <= 5; $i++) : ?>
													<?php if ($i <= $review['rating']) : ?>
														<i class="fas fa-star"></i>
													<?php else : ?>
														<i class="far fa-star"></i>
													<?php endif; ?>
												<?php endfor; ?>
											</div>
										</div>

										<div class="review-footer">
											Posted
											<div class="review-date">
												<?php echo esc_html($review['relative_time']); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
							<?php foreach ($reviews_data['reviews'] as $review) : ?>
								<div class="swiper-slide">
									<div class="review-card card">
										<div class="review-header">
											<div class="review-author">
												<h4 class="author-name"><?php echo esc_html($review['author']); ?></h4>
											</div>
										</div>
										<div class="review-text">
											<?php
											$text = esc_html($review['text']);
											echo strlen($text) > 200 ? substr($text, 0, 200) . '...' : $text;
											?>
										</div>
										<div class="review-rating">
											<span class="rating-google"><i class="fa-brands fa-google"></i></span>
											<div>
												<?php for ($i = 1; $i <= 5; $i++) : ?>
													<?php if ($i <= $review['rating']) : ?>
														<i class="fas fa-star"></i>
													<?php else : ?>
														<i class="far fa-star"></i>
													<?php endif; ?>
												<?php endfor; ?>
											</div>
										</div>

										<div class="review-footer">
											Posted
											<div class="review-date">
												<?php echo esc_html($review['relative_time']); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<!-- Navigation -->
						<div class="d-flex justify-content-center align-items-center mt-4 gap-3">
							<div class="left btn btn--outline btn--icon"><i class="fa fa-chevron-left"></i></div>
							<div class="right btn btn--outline btn--icon"><i class="fa fa-chevron-right"></i></div>
						</div>
					</div>
				</div>
			<?php elseif (!empty($reviews_data) && $reviews_data['error']) : ?>
				<div class="alert alert-warning">
					<p><?php _e('Erreur lors de la récupération des avis Google:', 'abyssenergy'); ?> <?php echo esc_html($reviews_data['message']); ?></p>
				</div>
			<?php else : ?>
				<div class="alert alert-info">
					<p><?php _e('Veuillez configurer votre ID de lieu Google et votre clé API pour afficher les avis.', 'abyssenergy'); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

<?php endif; ?>
