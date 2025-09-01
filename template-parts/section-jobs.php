<section class="section section-jobs">
	<?php
	$args = [
		'post_type' => 'job',
		'posts_per_page' => 6,
		'orderby' => 'date',
		'order' => 'DESC'
	];

	// Query the jobs
	$query = new WP_Query($args);

	// Display the jobs
	if ($query->have_posts()) : ?>

		<span class="section--subtitle">Jobs and offers</span>
		<h2 class="section--title">Connecting Professionals</h2>
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
