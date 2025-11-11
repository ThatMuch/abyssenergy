<?php

/**
 * Search & Filter Pro
 *
 * Sample Results Template
 *
 * @package   Search_Filter
 * @author    Ross Morsali
 * @link      https://searchandfilter.com
 * @copyright 2018 Search & Filter
 *
 * Note: these templates are not full page templates, rather
 * just an encaspulation of the your results loop which should
 * be inserted in to other pages by using a shortcode - think
 * of it as a template part
 *
 * This template is an absolute base example showing you what
 * you can do, for more customisation see the WordPress docs
 * and using template tags -
 *
 * http://codex.wordpress.org/Template_Tags
 *
 */

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
	exit;
}
if ($query->have_posts()) : ?>
	<!-- Compteur de résultats -->
	<p class="">
		<?php
		printf(
			_n('Your search resulted in %s matching job', 'Your search resulted in %s matching jobs', $query->found_posts, 'text-domain'),
			'<strong class="text-orange">' . number_format_i18n($query->found_posts) . '</strong>'
		);
		?>
	</p>

	<!-- Grille des emplois -->
	<div class="jobs-grid" id="jobs-container" data-search-filter-results>
		<?php
		while ($query->have_posts()) :
			$query->the_post();
			get_template_part('template-parts/job-card');
		endwhile;
		?>
	</div>
	<!-- Pagination -->
	<?php if ($query->max_num_pages > 1) : ?>
		<div class="jobs-pagination mt-5">
			<div class="d-flex justify-content-center">
				<?php
				$pagination_args = array(
					'total' => $query->max_num_pages,
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
