<?php

/**
 * Block Template: Fixed Jobs
 * Affiche tous les postes fixes filtrés par catégorie dans des onglets
 */

$anchor = '';
if (!empty($block['anchor'])) {
	$anchor = 'id="' . esc_attr($block['anchor']) . '" ';
}

// Créer des noms de classe basés sur le bloc
$class_name = 'fixed-jobs-block';
if (!empty($block['className'])) {
	$class_name .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$class_name .= ' align' . $block['align'];
}

// Mode aperçu dans l'éditeur
$is_preview = isset($block['data']['is_preview']) && $block['data']['is_preview'];

// Récupération des paramètres du bloc
$title = get_field('title') ?: 'Explore EPC Project Jobs';

// Récupérer toutes les catégories d'emploi
$job_categories = get_terms(array(
	'taxonomy' => 'job-category',
	'hide_empty' => true,
));

// Récupérer tous les postes fixes
$all_fixed_jobs = get_posts(array(
	'post_type' => 'fixed-job',
	'post_status' => 'publish',
	'numberposts' => -1,
	'meta_key' => '_thumbnail_id', // Optionnel: seulement les postes avec image
));

?>

<?php if ($is_preview) : ?>
	<div class="block-preview-message">
		<h3><?php echo esc_html($title); ?></h3>
		<p><?php _e('Aperçu du bloc Fixed Jobs. Les onglets interactifs s\'afficheront en mode front-end.', 'abyssenergy'); ?></p>
		<div class="preview-jobs-stats">
			<?php if (!empty($job_categories)) : ?>
				<p><strong><?php echo count($job_categories); ?></strong> catégories d'emploi</p>
			<?php endif; ?>
			<p><strong><?php echo count($all_fixed_jobs); ?></strong> postes fixes disponibles</p>
		</div>
	</div>
<?php else : ?>

	<section <?php echo $anchor; ?>class="section <?php echo esc_attr($class_name); ?>">
		<div class="container">
			<div class="section-header">
				<?php if ($title) : ?>
					<h2 class="section--title"><?php echo esc_html($title); ?></h2>
				<?php endif; ?>
			</div>

			<div class="fixed-jobs-container">
				<!-- Onglets de navigation -->
				<div class="fixed-jobs-tabs">
					<?php if (!empty($job_categories)) : ?>
						<?php foreach ($job_categories as $category) : ?>
							<?php
							$category_jobs = get_posts(array(
								'post_type' => 'fixed-job',
								'post_status' => 'publish',
								'numberposts' => -1,
								'tax_query' => array(
									array(
										'taxonomy' => 'job-category',
										'field' => 'term_id',
										'terms' => $category->term_id,
									),
								),
							));
							?>
							<?php if (!empty($category_jobs)) : ?>
								<button class="fixed-jobs-tab<?php echo ($category === reset($job_categories)) ? ' active' : ''; ?>"
									data-category="<?php echo esc_attr($category->slug); ?>">
									<?php echo esc_html($category->name); ?>
									<span class="job-count"><?php echo count($category_jobs); ?></span>
								</button>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<!-- Contenu des onglets -->
				<div class="fixed-jobs-content">
					<!-- Onglets par catégorie -->
					<?php if (!empty($job_categories)) : ?>
						<?php foreach ($job_categories as $index => $category) : ?>
							<?php
							$category_jobs = get_posts(array(
								'post_type' => 'fixed-job',
								'post_status' => 'publish',
								'numberposts' => -1,
								'tax_query' => array(
									array(
										'taxonomy' => 'job-category',
										'field' => 'term_id',
										'terms' => $category->term_id,
									),
								),
							));
							?>
							<?php if (!empty($category_jobs)) : ?>
								<div class="fixed-jobs-tab-content<?php echo ($index === 0) ? ' active' : ''; ?>"
									data-category="<?php echo esc_attr($category->slug); ?>">
									<div class="fixed-jobs-grid">
										<?php
										foreach ($category_jobs as $job) :
										?>
											<a href="<?php echo get_permalink($job->ID); ?>">
												<?php echo esc_html($job->post_title); ?>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

<?php endif; ?>
