<?php

/**
 * Template part for displaying a job card
 *
 * @package AbyssEnergy
 */

// Récupère les termes de taxonomie pour le secteur
$sectors = get_the_terms(get_the_ID(), 'job-sector');
$city = get_field('job_city', $post->ID);
$state = get_field('job_state', $post->ID);
$country = get_field('job_country', $post->ID);

// Détermine la classe CSS basée sur le secteur (si disponible)
$sector_class = '';
if ($sectors && !is_wp_error($sectors) && !empty($sectors)) {
	$sector_class = esc_html($sectors[0]->slug) . '-card';
}

// Vérifie si le job est nouveau (moins de 5 jours)
$is_new = get_the_time('U') > strtotime('-5 days');
?>

<article class="job-card card <?php echo $sector_class; ?>">
	<a href="<?php the_permalink(); ?>" class="job-card-link">
		<div class="card__content">
			<!-- Badges des secteurs -->
			<?php if ($sectors && !is_wp_error($sectors) && !empty($sectors)) : ?>
				<?php foreach ($sectors as $sector) : ?>
					<span class="job-sector mb-3"><?php echo esc_html($sector->name); ?></span>
				<?php endforeach; ?>

			<?php endif; ?>

			<!-- Badge "Nouveau" si le job a moins de 5 jours -->
			<?php if ($is_new) : ?>
				<span class="tag tag-secondary">New</span>
			<?php endif; ?>

			<!-- Titre du job -->
			<div class="job-header mb-3">
				<h4 class="job-title">
					<?php echo mb_strtolower(get_the_title(), 'UTF-8'); ?>
				</h4>
			</div>
		</div>

		<!-- Pied de carte avec la localisation -->
		<div class="card__footer">
			<?php if ($city) : ?>
				<p class="job-location">
					<i class="fas fa-map-marker-alt mr-2"></i>
					<?php
					echo esc_html($city);
					if ($state) {
						echo ', ' . esc_html($state);
					}
					?>
				</p>
			<?php endif; ?>
		</div>
	</a>
</article>
