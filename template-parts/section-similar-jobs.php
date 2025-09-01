<section class="section section-jobs">
	<?php
	// Get the current post's categories
	$current_post_id = get_the_ID();
	$categories = get_the_category();

	$category_ids = [];

	if (!empty($categories)) {
		foreach ($categories as $category) {
			$category_ids[] = $category->term_id;
		}
	}

	// Define query arguments
	$args = [
		'post_type' => 'job',
		'posts_per_page' => 6,
		'post__not_in' => [$current_post_id], // Exclude current post
		'orderby' => 'date',
		'order' => 'DESC'
	];

	// If categories exist, filter by category
	if (!empty($category_ids)) {
		$args['category__in'] = $category_ids;
	}

	// Query the jobs
	$query = new WP_Query($args);

	// If no jobs found in the same category, fetch the latest 5 posts
	if (!$query->have_posts()) {
		$args = [
			'post_type' => 'job',
			'posts_per_page' => 6,
			'post__not_in' => [$current_post_id],
			'orderby' => 'date',
			'order' => 'DESC'
		];
		$query = new WP_Query($args);
	}

	// Display the jobs
	if ($query->have_posts()) : ?>

		<span class="section--subtitle">Jobs and offers</span>
		<h2 class="section--title">Similar Jobs</h2>
		<div class="similar-jobs">
			<div class="wrapper">
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<?php get_template_part('template-parts/job-card'); ?>
				<?php endwhile; ?>
			</div>
		</div>

		<div class="similar-jobs-nav-buttons">
			<button class="scroll-left btn btn--outline  btn--icon"><i class="fa fa-chevron-left"></i></button>
			<a href="<?php echo esc_url(site_url("search-jobs")); ?>" class="btn btn-primary">See more jobs</a>
			<button class="scroll-right btn btn--outline btn--icon"><i class="fa fa-chevron-right"></i></button>
		</div>
	<?php endif;
	wp_reset_postdata(); ?>
</section>

<script>
	const scrollLeftBtn = document.querySelector('.scroll-left');
	const scrollRightBtn = document.querySelector('.scroll-right');
	const wrapper = document.querySelector('.similar-jobs');

	scrollLeftBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: -460,
			behavior: 'smooth'
		});
	});

	scrollRightBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: 460,
			behavior: 'smooth'
		});
	});

	// disabled the button when reaching the end
	const disableButtons = () => {
		scrollLeftBtn.disabled = wrapper.scrollLeft === 0;
		scrollRightBtn.disabled = wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth;
	};

	wrapper.addEventListener('scroll', disableButtons);
	disableButtons();
</script>
