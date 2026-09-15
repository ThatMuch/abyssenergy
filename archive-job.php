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
					<h1 class="mb-3"><?php esc_html_e("Offres d'emploi", 'abyssenergy'); ?></h1>
					<p class="archive-description">
						<?php esc_html_e('Découvrez nos opportunités de carrière et rejoignez notre équipe dynamique.', 'abyssenergy'); ?>
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
									_n('%s emploi disponible', '%s emplois disponibles', $wp_query->found_posts, 'abyssenergy'),
									'<strong>' . number_format_i18n($wp_query->found_posts) . '</strong>'
								);
								?>
							</p>
						</div>
						<div class="col-md-6 text-md-right">
							<a href="<?php echo esc_url(home_url('/emplois/')); ?>" class="btn btn--outline">
								<?php esc_html_e('Recherche avancée', 'abyssenergy'); ?>
							</a>
						</div>
					</div>
				</div>

				<!-- Grille des emplois -->
				<div class="jobs-grid">
					<?php
					while (have_posts()) :
						the_post();
						get_template_part('template-parts/job-card');
					endwhile;
					?>
				</div>

				<!-- Pagination -->
				<div class="archive-pagination mt-5">
					<div class="d-flex justify-content-center">
						<?php
						the_posts_pagination(array(
							'mid_size' => 2,
							'prev_text' => '← ' . __('Précédent', 'abyssenergy'),
							'next_text' => __('Suivant', 'abyssenergy') . ' →',
							'type' => 'list'
						));
						?>
					</div>
				</div>

			<?php else : ?>
				<!-- Aucun emploi trouvé -->
				<div class="no-posts-found text-center">
					<div class="alert alert--warning">
						<h3><?php esc_html_e('Aucun emploi disponible', 'abyssenergy'); ?></h3>
						<p><?php esc_html_e("Il n'y a actuellement aucune offre d'emploi publiée.", 'abyssenergy'); ?></p>
						<p><?php esc_html_e('Revenez bientôt pour découvrir nos nouvelles opportunités !', 'abyssenergy'); ?></p>
					</div>

					<!-- CTA pour candidature spontanée -->
					<div class="mt-4">
						<div class="card bg-light">
							<div class="card__content text-center">
								<h4 class="text-blue"><?php esc_html_e('Intéressé par rejoindre notre équipe ?', 'abyssenergy'); ?></h4>
								<p><?php esc_html_e("N'hésitez pas à nous envoyer votre CV pour une candidature spontanée.", 'abyssenergy'); ?></p>
								<a href="/contact-us/?position=Unsolicited%20Application" class="btn btn--outline">
									<?php esc_html_e('Envoyer mon CV', 'abyssenergy'); ?>
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
