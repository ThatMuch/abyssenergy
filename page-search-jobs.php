<?php

/**
 * Template Name: Search Jobs
 *
 * Template pour la recherche d'emplois avec filtres
 * Cette page traite les paramètres de recherche de la page d'accueil
 */

get_header(); ?>

<?php
// Récupérer les paramètres de recherche
$search_query = isset($_GET['job_search']) ? sanitize_text_field($_GET['job_search']) : '';
$search_location = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
$search_skill = isset($_GET['skill']) ? sanitize_text_field($_GET['skill']) : '';

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
		$job_skill = get_the_terms($job_id, 'job-skill');
		$skill = '';
		if ($job_skill && !is_wp_error($job_skill)) {
			$skill = join(', ', wp_list_pluck($job_skill, 'name'));
		}
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

		if ($skill) {
			$job_skills[] = $skill;
		}
	}
}

// Trier les tableaux
sort($job_cities);
sort($job_countries);
$job_skills = array_unique($job_skills);
sort($job_skills);

// Construction de la requête WP_Query avec filtres
$meta_query = array('relation' => 'AND');
$tax_query = array('relation' => 'AND');

// Filtre par mots-clés (titre du poste ou description)
$search_args = array();
if (!empty($search_query)) {
	$search_args['s'] = $search_query;
}

// Filtre par ville
if (!empty($search_location)) {
	$meta_query[] = array(
		'key'     => 'job_city',
		'value'   => $search_location,
		'compare' => 'LIKE'
	);
}

// Filtre par compétence
if (!empty($search_skill)) {
	$tax_query[] = array(
		'taxonomy' => 'job-skill',
		'field'    => 'name',
		'terms'    => $search_skill,
		'operator' => 'IN'
	);
}

// Arguments de la requête principale
$jobs_args = array_merge($search_args, array(
	'post_type'      => 'job',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => get_query_var('paged') ? get_query_var('paged') : 1,
	'meta_query'     => $meta_query,
	'tax_query'      => $tax_query,
	'orderby'        => 'date',
	'order'          => 'DESC'
));

$jobs_query = new WP_Query($jobs_args);
$total_jobs = $jobs_query->found_posts;
?>

<div class="page-header">
	<div class="container">
		<h1 class="page-title">
			<?php if (!empty($search_query)): ?>
				Résultats pour "<?php echo esc_html($search_query); ?>"
			<?php else: ?>
				Recherche d'emplois
			<?php endif; ?>
		</h1>

		<?php if ($total_jobs > 0): ?>
			<p class="search-results-count">
				<?php printf(_n('%d poste trouvé', '%d postes trouvés', $total_jobs, 'abyssenergy'), $total_jobs); ?>
			</p>
		<?php endif; ?>

		<!-- Formulaire de recherche avec filtres -->
		<form method="GET" class="job-search-form job-filters" id="job-filters">
			<div class="filters-row">
				<div class="filter-group">
					<label for="job_search">Mots-clés</label>
					<input
						type="text"
						id="job_search"
						name="job_search"
						placeholder="Rechercher un poste..."
						value="<?php echo esc_attr($search_query); ?>">
				</div>

				<div class="filter-group">
					<label for="location">Lieu</label>
					<select id="location" name="location">
						<option value="">Toutes les villes</option>
						<?php foreach ($job_cities as $city): ?>
							<option value="<?php echo esc_attr($city); ?>" <?php selected($search_location, $city); ?>>
								<?php echo esc_html($city); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="filter-group">
					<label for="skill">Compétence</label>
					<select id="skill" name="skill">
						<option value="">Toutes les compétences</option>
						<?php foreach ($job_skills as $skill): ?>
							<option value="<?php echo esc_attr($skill); ?>" <?php selected($search_skill, $skill); ?>>
								<?php echo esc_html($skill); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="filter-group">
					<button type="submit" class="btn btn--primary">
						<i class="fas fa-search"></i> Rechercher
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<main class="jobs-listing-page">
	<div class="container">

		<?php if (!empty($search_query) || !empty($search_location) || !empty($search_skill)): ?>
			<div class="active-filters">
				<h3>Filtres actifs :</h3>
				<div class="filter-tags">
					<?php if (!empty($search_query)): ?>
						<span class="filter-tag">
							Mots-clés: "<?php echo esc_html($search_query); ?>"
							<a href="<?php echo esc_url(remove_query_arg('job_search')); ?>" class="remove-filter">×</a>
						</span>
					<?php endif; ?>

					<?php if (!empty($search_location)): ?>
						<span class="filter-tag">
							Lieu: <?php echo esc_html($search_location); ?>
							<a href="<?php echo esc_url(remove_query_arg('location')); ?>" class="remove-filter">×</a>
						</span>
					<?php endif; ?>

					<?php if (!empty($search_skill)): ?>
						<span class="filter-tag">
							Compétence: <?php echo esc_html($search_skill); ?>
							<a href="<?php echo esc_url(remove_query_arg('skill')); ?>" class="remove-filter">×</a>
						</span>
					<?php endif; ?>

					<a href="<?php echo esc_url(strtok($_SERVER["REQUEST_URI"], '?')); ?>" class="clear-all-filters">
						Effacer tous les filtres
					</a>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($jobs_query->have_posts()): ?>
			<div class="jobs-grid">
				<?php while ($jobs_query->have_posts()): $jobs_query->the_post(); ?>
					<article class="job-card">
						<div class="job-card-header">
							<h2 class="job-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<?php
							$job_city = get_field('job_city');
							$job_country = get_field('job_country');
							if ($job_city || $job_country): ?>
								<div class="job-location">
									<i class="fas fa-map-marker-alt"></i>
									<?php
									if ($job_city) echo esc_html($job_city);
									if ($job_city && $job_country) echo ', ';
									if ($job_country) echo esc_html($job_country);
									?>
								</div>
							<?php endif; ?>
						</div>

						<div class="job-card-content">
							<?php
							$excerpt = get_the_excerpt();
							if ($excerpt): ?>
								<p class="job-excerpt"><?php echo esc_html(wp_trim_words($excerpt, 20)); ?></p>
							<?php endif; ?>

							<?php
							$job_skills = get_the_terms(get_the_ID(), 'job-skill');
							if ($job_skills && !is_wp_error($job_skills)): ?>
								<div class="job-skills">
									<?php foreach (array_slice($job_skills, 0, 3) as $skill): ?>
										<span class="skill-tag"><?php echo esc_html($skill->name); ?></span>
									<?php endforeach; ?>
									<?php if (count($job_skills) > 3): ?>
										<span class="skill-tag more">+<?php echo (count($job_skills) - 3); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>

						<div class="job-card-footer">
							<div class="job-meta">
								<span class="job-date">
									<i class="fas fa-calendar"></i>
									<?php echo get_the_date(); ?>
								</span>
							</div>
							<a href="<?php the_permalink(); ?>" class="btn btn--outline btn--small">
								Voir le poste
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination
			$pagination = paginate_links(array(
				'total'     => $jobs_query->max_num_pages,
				'current'   => max(1, get_query_var('paged')),
				'format'    => '?paged=%#%',
				'prev_text' => '<i class="fas fa-chevron-left"></i> Précédent',
				'next_text' => 'Suivant <i class="fas fa-chevron-right"></i>',
				'type'      => 'array'
			));

			if ($pagination): ?>
				<nav class="jobs-pagination">
					<ul class="pagination">
						<?php foreach ($pagination as $page_link): ?>
							<li><?php echo $page_link; ?></li>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>

		<?php else: ?>
			<div class="no-jobs-found">
				<div class="no-results-content">
					<i class="fas fa-search fa-3x"></i>
					<h2>Aucun poste trouvé</h2>
					<?php if (!empty($search_query) || !empty($search_location) || !empty($search_skill)): ?>
						<p>Aucun poste ne correspond à vos critères de recherche. Essayez de modifier vos filtres.</p>
						<a href="<?php echo esc_url(strtok($_SERVER["REQUEST_URI"], '?')); ?>" class="btn btn--primary">
							Voir tous les postes
						</a>
					<?php else: ?>
						<p>Aucun poste n'est actuellement disponible.</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php wp_reset_postdata(); ?>
	</div>
</main>

<style>
	.search-results-count {
		margin-bottom: 2rem;
		font-size: 1.1rem;
		color: #666;
	}

	.job-filters .filters-row {
		display: grid;
		grid-template-columns: 2fr 1fr 1fr auto;
		gap: 1rem;
		align-items: end;
	}

	.filter-group label {
		display: block;
		margin-bottom: 0.5rem;
		font-weight: 600;
		color: #333;
	}

	.filter-group input,
	.filter-group select {
		width: 100%;
		padding: 0.75rem;
		border: 1px solid #ddd;
		border-radius: 0.25rem;
		font-size: 1rem;
	}

	.active-filters {
		margin: 2rem 0;
		padding: 1rem;
		background: #f8f9fa;
		border-radius: 0.5rem;
	}

	.filter-tags {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		align-items: center;
	}

	.filter-tag {
		display: inline-flex;
		align-items: center;
		gap: 0.5rem;
		padding: 0.25rem 0.75rem;
		background: #0a3f6a;
		color: white;
		border-radius: 1rem;
		font-size: 0.875rem;
	}

	.remove-filter {
		color: white;
		text-decoration: none;
		font-weight: bold;
		margin-left: 0.25rem;
	}

	.clear-all-filters {
		color: #666;
		text-decoration: underline;
		font-size: 0.875rem;
	}

	.jobs-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
		gap: 2rem;
		margin: 2rem 0;
	}

	.job-card {
		border: 1px solid #e2e8f0;
		border-radius: 0.5rem;
		padding: 1.5rem;
		background: white;
		transition: box-shadow 0.3s ease;
	}

	.job-card:hover {
		box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
	}

	.job-title a {
		color: #0a3f6a;
		text-decoration: none;
	}

	.job-location {
		color: #666;
		font-size: 0.9rem;
		margin-top: 0.5rem;
	}

	.job-skills {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
		margin: 1rem 0;
	}

	.skill-tag {
		padding: 0.25rem 0.5rem;
		background: #eef7fe;
		color: #0a3f6a;
		border-radius: 0.25rem;
		font-size: 0.75rem;
	}

	.job-card-footer {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-top: 1rem;
		padding-top: 1rem;
		border-top: 1px solid #eee;
	}

	.no-jobs-found {
		text-align: center;
		padding: 4rem 0;
	}

	.jobs-pagination {
		margin: 3rem 0;
		text-align: center;
	}

	.pagination {
		display: inline-flex;
		list-style: none;
		gap: 0.5rem;
		margin: 0;
		padding: 0;
	}

	.pagination li a,
	.pagination li span {
		padding: 0.5rem 1rem;
		border: 1px solid #ddd;
		border-radius: 0.25rem;
		text-decoration: none;
		color: #333;
	}

	.pagination li .current {
		background: #0a3f6a;
		color: white;
		border-color: #0a3f6a;
	}

	@media (max-width: 768px) {
		.job-filters .filters-row {
			grid-template-columns: 1fr;
			gap: 1rem;
		}

		.jobs-grid {
			grid-template-columns: 1fr;
		}
	}
</style>

<?php get_footer(); ?>
