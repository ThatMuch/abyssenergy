<?php

/**
 * Template Name: Jobs Listing
 *
 * Template pour afficher tous les postes de type "job"
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

if (! defined('ABSPATH')) {
	exit;
}

get_header(); ?>

<?php
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

$jobs_query = new WP_Query($job_args);

$subtitle = safe_get_field_with_default('subtitle', false, '');
$description = safe_get_field_with_default('description', false, '');
?>

<div class="jobs-listing-page">
	<!-- En-tête de la page -->
	<div class="page-header <?php if (has_post_thumbnail()): ?>has-thumbnail<?php endif; ?>">
		<div class="container-fluid">
			<div class="row">
				<div class="col col-md-8">
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
				<div class="col col-md-4">
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
		<div class="container-fluid">
			<!-- Bouton pour afficher les filtres sur mobile (visible uniquement < 768px) -->
			<button class="btn btn--primary filters-toggle d-md-none mb-3" type="button" aria-expanded="false" aria-controls="jobs-filters-sidebar">
				<i class="fas fa-filter"></i>
				<span class="filter-text">Show Filters</span>
				<span class="filter-count"></span>
			</button>

			<div class="row">
				<div class="col-md-3">
					<!-- Sidebar des filtres (coulissante sur mobile, normale sur desktop) -->
					<div class="jobs-filters-sidebar" id="jobs-filters-sidebar">
						<!-- Header mobile uniquement -->
						<div class="filters-header d-md-none">
							<h3>Filters</h3>
							<button class="filters-close" type="button" aria-label="Close filters">
								<i class="fas fa-times"></i>
							</button>
						</div>
						<?php echo do_shortcode('[searchandfilter id="282"]'); ?>
					</div>
					<!-- Overlay mobile uniquement -->
					<div class="filters-overlay d-md-none"></div>
				</div>
				<div class="col-md-9">
					<?php echo do_shortcode('[searchandfilter id="282" show="results"]'); ?>
				</div>
			</div>
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
					<img loading="lazy" src="<?php echo get_template_directory_uri(); ?>/images/opentowork.webp" alt="Company">
				</div>
			</div>
		</div>
	</section>
</div>
<?php get_footer(); ?>
