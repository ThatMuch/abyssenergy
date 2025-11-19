<section class="section section-jobs">
	<?php
	// Récupérer la requête passée en argument
	$query = isset($args['query']) ? $args['query'] : null;

	// Récupérer les arguments additionnels
	$button_text = isset($args['button_text']) ? $args['button_text'] : 'View more jobs';
	$button_url = isset($args['button_url']) ? $args['button_url'] : site_url('search-jobs');
	$show_button = isset($args['show_button']) ? $args['show_button'] : true;
	$title = isset($args['title']) ? $args['title'] : 'Latest roles in Energy sector';
	$subtitle = isset($args['subtitle']) ? $args['subtitle'] : 'Career opportunities';

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

	// Fonction pour calculer et appliquer les dimensions
	function calculateAndApplyDimensions() {
		const containerElement = wrapper.closest('.section').querySelector('.container');
		const containerWidth = containerElement ? containerElement.offsetWidth : wrapper.offsetWidth;
		const gap = 24; // component-md spacing (1.5rem = 24px)

		// Détecter le nombre de cartes visibles selon la taille d'écran
		const windowWidth = window.innerWidth;
		let cardsVisible;
		if (windowWidth < 768) {
			cardsVisible = 1; // Mobile
		} else if (windowWidth < 1024) {
			cardsVisible = 2; // Tablette
		} else {
			cardsVisible = 3; // Desktop
		}

		const totalGap = gap * (cardsVisible - 1);
		const cardWidth = Math.floor((containerWidth - totalGap) / cardsVisible);

		// Appliquer la largeur calculée à chaque carte
		jobCards.forEach(card => {
			card.style.width = `${cardWidth}px`;
		});

		// Définir la largeur totale du wrapper
		const totalWrapperWidth = (cardWidth * jobCardCount) + (gap * (jobCardCount - 1));
		document.querySelector('.similar-jobs-wrapper').style.width = `${totalWrapperWidth}px`;

		return cardWidth + gap; // Retourne le scrollAmount
	}

	// Initialiser les dimensions
	let scrollAmount = calculateAndApplyDimensions();

	scrollLeftBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: -scrollAmount,
			behavior: 'smooth'
		});
	});

	scrollRightBtn.addEventListener('click', () => {
		wrapper.scrollBy({
			top: 0,
			left: scrollAmount,
			behavior: 'smooth'
		});
	});

	// Désactiver les boutons quand on atteint les extrémités
	const disableButtons = () => {
		scrollLeftBtn.disabled = wrapper.scrollLeft === 0;
		scrollRightBtn.disabled = wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth;
	};

	wrapper.addEventListener('scroll', disableButtons);
	disableButtons();

	// Recalculer au redimensionnement de la fenêtre
	let resizeTimeout;
	window.addEventListener('resize', () => {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(() => {
			scrollAmount = calculateAndApplyDimensions();
			disableButtons();
		}, 150);
	});
</script>
