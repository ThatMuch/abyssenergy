<section class="section section-jobs">
	<?php
	// Récupérer la requête passée en argument
	$query = isset($args['query']) ? $args['query'] : null;

	// Récupérer les arguments additionnels
	$button_text = isset($args['button_text']) ? $args['button_text'] : 'See more jobs';
	$button_url = isset($args['button_url']) ? $args['button_url'] : site_url('search-jobs');
	$show_button = isset($args['show_button']) ? $args['show_button'] : true;
	$title = isset($args['title']) ? $args['title'] : 'Connecting Professionals';
	$subtitle = isset($args['subtitle']) ? $args['subtitle'] : 'Jobs and offers';

	// Display the jobs
	if ($query && $query->have_posts()) : ?>
		<div class="container">
			<span class="section--subtitle"><?php echo esc_html($subtitle); ?></span>
			<h2 class="section--title"><?php echo esc_html($title); ?></h2>
		</div>
		<div class="similar-jobs">
			<div class="similar-jobs-wrapper">
				<?php while ($query->have_posts()) : $query->the_post(); ?>
					<?php get_template_part('template-parts/job-card'); ?>
				<?php endwhile; ?>
			</div>
		</div>

		<div class="similar-jobs-nav-buttons">
			<button class="similar-jobs-scroll-left btn btn--outline  btn--icon"><i class="fa fa-chevron-left"></i></button>
			<?php if ($show_button) : ?>
				<a href="<?php echo esc_url($button_url); ?>" class="btn btn-primary"><?php echo esc_html($button_text); ?></a>
			<?php endif; ?>
			<button class="similar-jobs-scroll-right btn btn--outline btn--icon"><i class="fa fa-chevron-right"></i></button>
		</div>
	<?php endif;
	wp_reset_postdata(); ?>
</section>

<script>
	const scrollLeftBtn = document.querySelector('.similar-jobs-scroll-left');
	const scrollRightBtn = document.querySelector('.similar-jobs-scroll-right');
	const wrapper = document.querySelector('.similar-jobs');
	const jobCards = document.querySelectorAll('.job-card');
	const jobCardCount = jobCards.length;
	const containerWidth = jobCardCount * 450; // Assuming each card is 460px wide
	document.querySelector('.similar-jobs-wrapper').style.width = `${containerWidth}px`;

	scrollLeftBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: -450,
			behavior: 'smooth'
		});
	});

	scrollRightBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: 450,
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
