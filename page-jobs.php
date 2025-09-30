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
$job_skills = array();

if ($all_jobs_query->have_posts()) {
	foreach ($all_jobs_query->posts as $job_id) {
		$job_city = get_field('job_city', $job_id);
		$job_country = get_field('job_country', $job_id);
		$job_skill =  get_the_terms($job_id, 'job-skill');
		$skill = '';
		if ($job_skill && !is_wp_error($job_skill)) {
			$skill = join(', ', wp_list_pluck($job_skill, 'name'));
		}
		if ($job_city) {
			// Le texte "Nearby" ne doit pas être pris en compte dans le filtre.
			$job_city = trim(str_ireplace('Nearby', '', $job_city));
			if ($job_city) {
				if (!isset($job_cities[$job_city])) {
					$job_cities[$job_city] = 0;
				}
				$job_cities[$job_city]++;
			}
		}
		if ($job_country) {
			if (!isset($job_countries[$job_country])) {
				$job_countries[$job_country] = 0;
			}
			$job_countries[$job_country]++;
		}

		if ($skill) {
			$job_skills[] = $skill;
		}
	}
}

ksort($job_cities);
ksort($job_countries);
sort($job_skills);
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

// Filter par skill - Support multiselect
if (get_query_var('job_skill_multi')) {
	$skills = get_query_var('job_skill_multi');
	$tax_query[] = array(
		'taxonomy' => 'job-skill',
		'field'    => 'slug',
		'terms'    => $skills,
		'operator' => 'IN',
	);
} elseif (get_query_var('job_skill')) { // Compatibilité avec l'ancien format
	$tax_query[] = array(
		'taxonomy' => 'job-skill',
		'field'    => 'slug',
		'terms'    => get_query_var('job_skill'),
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

$subtitle = get_field('subtitle');
$description = get_field('description');
?>

<div class="jobs-listing-page">
	<!-- En-tête de la page -->
	<div class="page-header <?php if (has_post_thumbnail()): ?>has-thumbnail<?php endif; ?>">
		<div class="container">
			<div class="row">
				<div class="col col-md-6">
					<h1><?php the_title(); ?></h1>
					<?php if ($subtitle): ?>
						<?php echo $subtitle; ?>
					<?php endif; ?>
					<?php if ($description): ?>
						<div class="page-description">
							<?php echo $description; ?>
						</div>
					<?php endif; ?>
					<form action="" method="GET" class="job-search-form d-flex gap-2">
						<input
							type="text"
							id="job-search"
							name="job_search"
							placeholder="Find your next position"
							value="<?php echo esc_attr(get_query_var('job_search')); ?>">
						<button type="submit" class="btn btn--primary btn--icon" aria-label="Search"><i class="fas fa-search"></i></button>
					</form>
				</div>
				<div class="col col-md-6">
					<?php if (has_post_thumbnail()) : ?>
						<div class="page-thumbnail">
							<?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!-- Filtres et recherche -->
	<section class="jobs-filters">
		<div class="container">
			<form class="jobs-filter-form" method="GET" action="">

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
								<?php echo esc_html($sector->name); ?> (<?php echo $sector->count; ?>)
							</option>
					<?php endforeach;
					endif; ?>
				</select>
				<select name="job_skill[]" id="job-skill" class="abyss-multiselect" multiple data-search="true">
					<option value="">All positions</option>
					<?php
					$skills = get_terms('job-skill', array('hide_empty' => true));
					$selected_skills = get_query_var('job_skill_multi') ? get_query_var('job_skill_multi') : array();
					// Compatibilité avec l'ancien format
					if (empty($selected_skills) && get_query_var('job_skill')) {
						$selected_skills = array(get_query_var('job_skill'));
					}

					if (!is_wp_error($skills) && !empty($skills)) :
						foreach ($skills as $skill) : ?>
							<option value="<?php echo esc_attr($skill->slug); ?>"
								<?php echo in_array($skill->slug, $selected_skills) ? 'selected' : ''; ?>>
								<?php echo esc_html($skill->name); ?> (<?php echo $skill->count; ?>)
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
						foreach ($job_cities as $city => $count) : ?>
							<option value="<?php echo esc_attr($city); ?>"
								<?php echo in_array($city, $selected_locations) ? 'selected' : ''; ?>>
								<?php echo esc_html($city); ?> (<?php echo $count; ?>)
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
						foreach ($job_countries as $country => $count) : ?>
							<option value="<?php echo esc_attr($country); ?>"
								<?php echo in_array($country, $selected_countries) ? 'selected' : ''; ?>>
								<?php echo esc_html($country); ?> (<?php echo $count; ?>)
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
					get_query_var('job_country_multi') ||
					get_query_var('job_skill_multi') ||
					get_query_var('job_type_multi')
				) : ?>

					<a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn--outline btn--small">
						✕ Clear filters
					</a>

				<?php endif; ?>

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
								'prev_text' => '<i class="fa fa-chevron-left"></i>',
								'next_text' => '<i class="fa fa-chevron-right"></i>',
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
				</div>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>
	</section>
	<!-- CTA pour candidature spontanée -->
	<section class="jobs-cta">
		<div class="container">
			<div class="row">
				<div class="col-md-8 jobs-cta-content">
					<h2>You didn't find the ideal position?</h2>
					<p>Send us your CV for an unsolicited application.</p>
					<a href="/contact-us/?position=Unsolicited%20Application" class="btn btn--primary">
						Send my CV
					</a>
				</div>

				<div class="col-md-4">
					<img loading="lazy" class="jobs-cta-img" src="<?php echo get_template_directory_uri(); ?>/images/tile_company.webp" alt="Company">
				</div>
			</div>
		</div>
	</section>
</div>

<?php get_footer(); ?>
