<?php

/**
 * Archive Template for Jobs
 *
 * Template automatique pour l'archive des emplois (/job/)
 * Ce template sera utilisé automatiquement par WordPress pour l'URL /job/
 */

get_header(); ?>

<div class="jobs-archive-page">
	<!-- En-tête de l'archive -->
	<section class="archive-header bg-blue text-white">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8 text-center">
					<h1 class="mb-3">Offres d'emploi</h1>
					<p class="archive-description">
						Découvrez nos opportunités de carrière et rejoignez notre équipe dynamique.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Contenu principal -->
	<section class="archive-content section">
		<div class="container">

			<?php if (have_posts()) : ?>

				<!-- Compteur de résultats -->
				<div class="archive-info mb-4">
					<div class="row align-items-center">
						<div class="col-md-6">
							<p class="text-muted mb-0">
								<?php
								global $wp_query;
								printf(
									_n('%s emploi disponible', '%s emplois disponibles', $wp_query->found_posts, 'text-domain'),
									'<strong>' . number_format_i18n($wp_query->found_posts) . '</strong>'
								);
								?>
							</p>
						</div>
						<div class="col-md-6 text-md-right">
							<a href="<?php echo esc_url(home_url('/emplois/')); ?>" class="btn btn--outline">
								Recherche avancée
							</a>
						</div>
					</div>
				</div>

				<!-- Grille des emplois -->
				<div class="posts-grid">
					<?php while (have_posts()) : the_post(); ?>

						<article <?php post_class('post-card'); ?>>
							<div class="card__content">

								<!-- Badges -->
								<div class="job-badges mb-3">
									<?php
									// Type d'emploi
									$job_type = get_field('job_type');
									if ($job_type) : ?>
										<span class="badge badge--primary"><?php echo esc_html($job_type); ?></span>
										<?php endif;

									// Secteur
									$sectors = get_the_terms(get_the_ID(), 'job-sector');
									if ($sectors && !is_wp_error($sectors)) :
										foreach ($sectors as $sector) : ?>
											<span class="badge badge--secondary"><?php echo esc_html($sector->name); ?></span>
									<?php endforeach;
									endif; ?>
								</div>

								<!-- Titre et métadonnées -->
								<h2 class="post-title">
									<a href="<?php the_permalink(); ?>">
										<?php the_title(); ?>
									</a>
								</h2>

								<?php
								$locations = get_the_terms(get_the_ID(), 'job-location');
								if ($locations && !is_wp_error($locations)) : ?>
									<p class="job-location text-muted mb-3">
										📍 <?php echo esc_html($locations[0]->name); ?>
									</p>
								<?php endif; ?>

								<!-- Extrait -->
								<div class="post-excerpt">
									<?php
									if (has_excerpt()) {
										echo '<p>' . get_the_excerpt() . '</p>';
									} else {
										echo '<p>' . wp_trim_words(get_the_content(), 30, '...') . '</p>';
									}
									?>
								</div>

								<!-- Métadonnées -->
								<div class="post-meta">
									<small class="post-date text-muted">
										Publié le <?php echo get_the_date('j F Y'); ?>
									</small>
								</div>
							</div>

							<div class="card__footer">
								<a href="<?php the_permalink(); ?>" class="btn btn--primary">
									Voir les détails
								</a>
							</div>
						</article>

					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<div class="archive-pagination mt-5">
					<div class="d-flex justify-content-center">
						<?php
						the_posts_pagination(array(
							'mid_size' => 2,
							'prev_text' => '← Précédent',
							'next_text' => 'Suivant →',
							'type' => 'list'
						));
						?>
					</div>
				</div>

			<?php else : ?>

				<!-- Aucun emploi trouvé -->
				<div class="no-posts-found text-center">
					<div class="alert alert--warning">
						<h3>Aucun emploi disponible</h3>
						<p>Il n'y a actuellement aucune offre d'emploi publiée.</p>
						<p>Revenez bientôt pour découvrir nos nouvelles opportunités !</p>
					</div>

					<!-- CTA pour candidature spontanée -->
					<div class="mt-4">
						<div class="card bg-light">
							<div class="card__content text-center">
								<h4 class="text-blue">Intéressé par rejoindre notre équipe ?</h4>
								<p>N'hésitez pas à nous envoyer votre CV pour une candidature spontanée.</p>
								<a href="/contact-us/?position=Unsolicited%20Application" class="btn btn--outline">
									Envoyer mon CV
								</a>
							</div>
						</div>
					</div>
				</div>

			<?php endif; ?>
		</div>
	</section>
</div>

<?php get_footer(); ?>
