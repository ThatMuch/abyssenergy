<?php

/**
 * Template Name: Jobs Listing
 *
 * Template pour afficher tous les postes de type "job"
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header(); ?>

<?php
// Récupérer toutes les villes uniques pour le filtre
$all_jobs_query = new WP_Query(array(
	'post_type'      => 'job',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'fields'         => 'ids', // Plus performant
));

$job_cities = array();
$job_countries = array();

if ($all_jobs_query->have_posts()) {
	foreach ($all_jobs_query->posts as $job_id) {
		$job_city = get_field('job_city', $job_id);
		$job_country = get_field('job_country', $job_id);
		if ($job_city) {
			// Le texte "Nearby" ne doit pas être pris en compte dans le filtre.
			$job_city = trim(str_ireplace('Nearby', '', $job_city));
			if ($job_city && !in_array($job_city, $job_cities)) {
				$job_cities[] = $job_city;
			}
		}
		if ($job_country) {
			if ($job_country && !in_array($job_country, $job_countries)) {
				$job_countries[] = $job_country;
			}
		}
	}
}
sort($job_cities);
sort($job_countries);
wp_reset_postdata();

// Configuration de la requête personnalisée pour l'affichage
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$job_args = array(
	'post_type'      => 'job',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

// Ajouter les filtres
$tax_query = array('relation' => 'AND');
$meta_query = array('relation' => 'AND');

// Filtre par secteur (taxonomie) - Support multiselect
if (get_query_var('job_sector_multi')) {
	$sectors = get_query_var('job_sector_multi');
	$tax_query[] = array(
		'taxonomy' => 'job-sector',
		'field'    => 'slug',
		'terms'    => $sectors,
		'operator' => 'IN',
	);
} elseif (get_query_var('job_sector')) { // Compatibilité avec l'ancien format
	$tax_query[] = array(
		'taxonomy' => 'job-sector',
		'field'    => 'slug',
		'terms'    => get_query_var('job_sector'),
	);
}

// Filtre par ville (champ personnalisé) - Support multiselect
if (get_query_var('job_location_multi')) {
	$locations = get_query_var('job_location_multi');
	if (count($locations) > 1) {
		$location_meta_query = array('relation' => 'OR');
		foreach ($locations as $location) {
			$location_meta_query[] = array(
				'key'     => 'job_city',
				'value'   => $location,
				'compare' => 'LIKE',
			);
		}
		$meta_query[] = $location_meta_query;
	} else {
		$meta_query[] = array(
			'key'     => 'job_city',
			'value'   => $locations[0],
			'compare' => 'LIKE',
		);
	}
} elseif (get_query_var('job_location')) { // Compatibilité avec l'ancien format
	$meta_query[] = array(
		'key'     => 'job_city',
		'value'   => get_query_var('job_location'),
		'compare' => 'LIKE',
	);
}

// Filtre par country (champ personnalisé) - Support multiselect
if (get_query_var('job_country_multi')) {
	$countries = get_query_var('job_country_multi');
	if (count($countries) > 1) {
		$country_meta_query = array('relation' => 'OR');
		foreach ($countries as $country) {
			$country_meta_query[] = array(
				'key'     => 'job_country',
				'value'   => $country,
				'compare' => 'LIKE',
			);
		}
		$meta_query[] = $country_meta_query;
	} else {
		$meta_query[] = array(
			'key'     => 'job_country',
			'value'   => $countries[0],
			'compare' => 'LIKE',
		);
	}
} elseif (get_query_var('job_country')) { // Compatibilité avec l'ancien format
	$meta_query[] = array(
		'key'     => 'job_country',
		'value'   => get_query_var('job_country'),
		'compare' => 'LIKE',
	);
}

if (count($tax_query) > 1) {
	$job_args['tax_query'] = $tax_query;
}

if (count($meta_query) > 1) {
	$job_args['meta_query'] = $meta_query;
}

// Ajouter la recherche textuelle
if (get_query_var('job_search')) {
	$job_args['s'] = get_query_var('job_search');
}

$jobs_query = new WP_Query($job_args);
?>

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
	<section class="jobs-filters">
		<div class="container">
			<form class="jobs-filter-form" method="GET" action="">
				<div class="d-flex gap-2 flex-wrap">
					<select id="job-sector" name="job_sector[]" class="abyss-multiselect" multiple data-search="true">
						<option value="">All sectors</option>
						<?php
						$sectors = get_terms('job-sector', array('hide_empty' => true));
						$selected_sectors = get_query_var('job_sector_multi') ? get_query_var('job_sector_multi') : array();
						// Compatibilité avec l'ancien format
						if (empty($selected_sectors) && get_query_var('job_sector')) {
							$selected_sectors = array(get_query_var('job_sector'));
						}

						if (!is_wp_error($sectors) && !empty($sectors)) :
							foreach ($sectors as $sector) : ?>
								<option value="<?php echo esc_attr($sector->slug); ?>"
									<?php echo in_array($sector->slug, $selected_sectors) ? 'selected' : ''; ?>>
									<?php echo esc_html($sector->name); ?>
								</option>
						<?php endforeach;
						endif; ?>
					</select>
					<select id="job-location" name="job_location[]" class="abyss-multiselect" multiple data-search="true">
						<option value="">All cities</option>
						<?php
						// get all the job cities from the job posts
						$selected_locations = get_query_var('job_location_multi') ? get_query_var('job_location_multi') : array();
						// Compatibilité avec l'ancien format
						if (empty($selected_locations) && get_query_var('job_location')) {
							$selected_locations = array(get_query_var('job_location'));
						}

						if (!is_wp_error($job_cities) && !empty($job_cities)) :
							foreach ($job_cities as $city) : ?>
								<option value="<?php echo esc_attr($city); ?>"
									<?php echo in_array($city, $selected_locations) ? 'selected' : ''; ?>>
									<?php echo esc_html($city); ?>
								</option>
						<?php endforeach;
						endif; ?>
					</select>
					<select id="job-country" name="job_country[]" class="abyss-multiselect" multiple data-search="true">
						<option value="">All countries</option>
						<?php
						$selected_countries = get_query_var('job_country_multi') ? get_query_var('job_country_multi') : array();
						// Compatibilité avec l'ancien format
						if (empty($selected_countries) && get_query_var('job_country')) {
							$selected_countries = array(get_query_var('job_country'));
						}

						if (!is_wp_error($job_countries) && !empty($job_countries)) :
							foreach ($job_countries as $country) : ?>
								<option value="<?php echo esc_attr($country); ?>"
									<?php echo in_array($country, $selected_countries) ? 'selected' : ''; ?>>
									<?php echo esc_html($country); ?>
								</option>
						<?php endforeach;
						endif; ?>
					</select>
					<button type="submit" class="btn btn--primary">
						Apply filters
					</button>
					<?php if (
						get_query_var('job_search') ||
						get_query_var('job_sector') ||
						get_query_var('job_location') ||
						get_query_var('job_country') ||
						get_query_var('job_sector_multi') ||
						get_query_var('job_location_multi') ||
						get_query_var('job_country_multi')
					) : ?>

						<a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn--outline btn--small">
							✕ Clear filters
						</a>

					<?php endif; ?>
				</div>
			</form>
		</div>
	</section>

	<!-- Liste des emplois -->
	<section class="jobs-content section">
		<div class="container">
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
					<?php
					while ($jobs_query->have_posts()) :
						$jobs_query->the_post();
						get_template_part('template-parts/job-card');
					endwhile;
					?>
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
								'prev_text' => '←',
								'next_text' => '→',
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
					<div class="alert">
						<h3>No jobs found</h3>
						<p>Sorry, no positions match your search criteria.</p>

						<?php if (get_query_var('job_search') || get_query_var('job_sector') || get_query_var('job_location')) : ?>
							<p>
								<a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn--primary">
									See all jobs
								</a>
							</p>
						<?php endif; ?>
					</div>

					<!-- CTA pour candidature spontanée -->
					<div class="mt-4">
						<div class="card bg-light">
							<div class="card__content text-center">
								<h4 class="text-blue">You didn't find the ideal position?</h4>
								<p>Send us your CV for an unsolicited application.</p>
								<a href="/contact-us/?position=Unsolicited%20Application" class="btn btn--outline">
									Send my CV
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

<?php get_footer(); ?>
