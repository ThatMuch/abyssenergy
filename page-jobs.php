<?php

/**
 * Template Name: Jobs Listing
 *
 * Template pour afficher tous les postes de type "job"
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header(); ?>

<div class="jobs-listing-page">
	<!-- En-tête de la page -->
	<section class="page-header">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div>
						<h1 class=" mb-3"><?php the_title(); ?></h1>
						<?php if (get_the_content()) : ?>
							<h2 class="page-description">
								<?php the_content(); ?>
					</div>
				<?php else : ?>
					<h2 class="page-description">
						Connecting <span class="text-orange">skilled professionals</span> with global projects
					</h2>
				<?php endif; ?>
				<form action="" method="GET" class="job-search-form d-flex gap-2">
					<input
						type="text"
						id="job-search"
						name="job_search"
						class="form-control"
						placeholder="Find your next position"
						value="<?php echo esc_attr(get_query_var('job_search')); ?>">
					<button type="submit" class="btn btn--primary btn--icon"><i class="fas fa-search"></i></button>
				</form>
				</div>
				<div class="col-md-3">
					<?php if (has_post_thumbnail()) : ?>
						<div class="page-header-image">
							<?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<img src="<?php echo get_template_directory_uri(); ?>/images/Cloud-2.svg" alt="Cloud" class="page-header-background">
		<img src="<?php echo get_template_directory_uri(); ?>/images/Cloud-1.svg" alt="Cloud" class="page-header-background">
	</section>
	<!-- Filtres et recherche -->
	<section class="jobs-filters section--small">
		<div class="container">
			<div class="card">
				<div class="card__content">
					<form class="jobs-filter-form" method="GET" action="">
						<div class="row align-items-end">
							<div class="col-md-4 mb-3">
								<label for="job-search" class="text-blue mb-2">
									<strong>Rechercher un poste</strong>
								</label>
								<input
									type="text"
									id="job-search"
									name="job_search"
									class="form-control"
									placeholder="Titre du poste, mot-clé..."
									value="<?php echo esc_attr(get_query_var('job_search')); ?>">
							</div>

							<div class="col-md-3 mb-3">
								<label for="job-sector" class="text-blue mb-2">
									<strong>Secteur</strong>
								</label>
								<select id="job-sector" name="job_sector" class="form-control">
									<option value="">Tous les secteurs</option>
									<?php
									$sectors = get_terms('job-sector', array('hide_empty' => true));
									if (!is_wp_error($sectors) && !empty($sectors)) :
										foreach ($sectors as $sector) : ?>
											<option value="<?php echo esc_attr($sector->slug); ?>"
												<?php selected(get_query_var('job_sector'), $sector->slug); ?>>
												<?php echo esc_html($sector->name); ?>
											</option>
									<?php endforeach;
									endif; ?>
								</select>
							</div>

							<div class="col-md-3 mb-3">
								<label for="job-location" class="text-blue mb-2">
									<strong>Localisation</strong>
								</label>
								<select id="job-location" name="job_location" class="form-control">
									<option value="">Toutes les localisations</option>
									<?php
									$locations = get_terms('job-location', array('hide_empty' => true));
									if (!is_wp_error($locations) && !empty($locations)) :
										foreach ($locations as $location) : ?>
											<option value="<?php echo esc_attr($location->slug); ?>"
												<?php selected(get_query_var('job_location'), $location->slug); ?>>
												<?php echo esc_html($location->name); ?>
											</option>
									<?php endforeach;
									endif; ?>
								</select>
							</div>

							<div class="col-md-2 mb-3">
								<button type="submit" class="btn btn--primary w-100">
									Filtrer
								</button>
							</div>
						</div>

						<?php if (get_query_var('job_search') || get_query_var('job_sector') || get_query_var('job_location')) : ?>
							<div class="row mt-3">
								<div class="col-12">
									<a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn--outline btn--small">
										✕ Effacer les filtres
									</a>
								</div>
							</div>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</div>
	</section>

	<!-- Liste des emplois -->
	<section class="jobs-content section">
		<div class="container">
			<?php
			// Configuration de la requête personnalisée
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

			$job_args = array(
				'post_type' => 'job',
				'post_status' => 'publish',
				'posts_per_page' => 12,
				'paged' => $paged,
				'orderby' => 'date',
				'order' => 'DESC'
			);

			// Ajouter les filtres de taxonomie
			$tax_query = array();

			if (get_query_var('job_sector')) {
				$tax_query[] = array(
					'taxonomy' => 'job-sector',
					'field'    => 'slug',
					'terms'    => get_query_var('job_sector'),
				);
			}

			if (get_query_var('job_location')) {
				$tax_query[] = array(
					'taxonomy' => 'job-location',
					'field'    => 'slug',
					'terms'    => get_query_var('job_location'),
				);
			}

			if (!empty($tax_query)) {
				$job_args['tax_query'] = $tax_query;
			}

			// Ajouter la recherche textuelle
			if (get_query_var('job_search')) {
				$job_args['s'] = get_query_var('job_search');
			}

			$jobs_query = new WP_Query($job_args);
			?>

			<?php if ($jobs_query->have_posts()) : ?>

				<!-- Compteur de résultats -->
				<p class="">
					<?php
					printf(
						_n('Your search resulted in %s matching job', 'Your search resulted in %s matching jobs', $jobs_query->found_posts, 'text-domain'),
						'<strong class="text-orange">' . number_format_i18n($jobs_query->found_posts) . '</strong>'
					);
					?>
				</p>

				<!-- Grille des emplois -->
				<div class="jobs-grid" id="jobs-container">
					<?php while ($jobs_query->have_posts()) : $jobs_query->the_post();
						$sectors = get_the_terms(get_the_ID(), 'job-sector'); ?>

						<article class="job-card card <?php echo esc_html($sectors[0]->slug); ?>-card">
							<a href="<?php the_permalink(); ?>" class="job-card-link">
								<div class="card__content">
									<!-- Badges -->
									<div class="job-badges mb-3">
										<?php
										// Secteur

										if ($sectors && !is_wp_error($sectors)) :
											foreach ($sectors as $sector) : ?>
												<span class="job-sector"><?php echo esc_html($sector->name); ?></span>
										<?php endforeach;
										endif; ?>
									</div>

									<!-- Titre et localisation -->
									<div class="job-header mb-3">
										<h4 class="job-title">
											<?php echo mb_strtolower(get_the_title(), 'UTF-8'); ?>
										</h4>
									</div>

								</div>
								<div class="card__footer">
									<?php
									$city = get_field('job_city');
									$state = get_field('job_state');

									if ($city && !is_wp_error($city)) : ?>
										<p class="job-location">
											<i class="fas fa-map-marker-alt mr-2"></i>
											<?php echo esc_html($city); ?>, <?php echo esc_html($state); ?>
										</p>
									<?php endif; ?>
								</div>
							</a>
						</article>

					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<?php if ($jobs_query->max_num_pages > 1) : ?>
					<div class="jobs-pagination mt-5">
						<div class="d-flex justify-content-center">
							<?php
							$pagination_args = array(
								'total' => $jobs_query->max_num_pages,
								'current' => $paged,
								'mid_size' => 2,
								'prev_text' => '← Précédent',
								'next_text' => 'Suivant →',
								'type' => 'list'
							);
							echo paginate_links($pagination_args);
							?>
						</div>
					</div>
				<?php endif; ?>

			<?php else : ?>

				<!-- Aucun emploi trouvé -->
				<div class="no-jobs-found text-center">
					<div class="alert alert--warning">
						<h3>Aucun emploi trouvé</h3>
						<p>Désolé, aucun poste ne correspond à vos critères de recherche.</p>

						<?php if (get_query_var('job_search') || get_query_var('job_sector') || get_query_var('job_location')) : ?>
							<p>
								<a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn--primary">
									Voir tous les emplois
								</a>
							</p>
						<?php endif; ?>
					</div>

					<!-- CTA pour candidature spontanée -->
					<div class="mt-4">
						<div class="card bg-light">
							<div class="card__content text-center">
								<h4 class="text-blue">Vous n'avez pas trouvé le poste idéal ?</h4>
								<p>Envoyez-nous votre CV pour une candidature spontanée.</p>
								<a href="/contact-us/?position=Unsolicited%20Application" class="btn btn--outline">
									Envoyer mon CV
								</a>
							</div>
						</div>
					</div>
				</div>

			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>
	</section>
</div>

<script>
	// JavaScript pour la fonctionnalité de changement de vue
	document.addEventListener('DOMContentLoaded', function() {
		const viewGrid = document.getElementById('view-grid');
		const viewList = document.getElementById('view-list');
		const jobsContainer = document.getElementById('jobs-container');

		if (viewGrid && viewList && jobsContainer) {
			viewGrid.addEventListener('click', function() {
				jobsContainer.classList.remove('list-view');
				viewGrid.classList.add('active');
				viewList.classList.remove('active');
			});

			viewList.addEventListener('click', function() {
				jobsContainer.classList.add('list-view');
				viewList.classList.add('active');
				viewGrid.classList.remove('active');
			});
		}
	});
</script>

<?php get_footer(); ?>
