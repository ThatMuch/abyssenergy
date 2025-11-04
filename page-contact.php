<?php

/**
 * Template Name: Contact
 *
 * Template pour afficher le formulaire de contact
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header();
$subtitle = get_field('subtitle');
$email = get_field('email');
$phone = get_field('phone');
$linkedin = get_field('linkedin');
// get_field google map
$location_nancy = get_field('nancy');
$location_paris = get_field('paris');

// get post type consultants
$consultants = get_posts(array(
	'post_type' => 'consultants',
	'posts_per_page' => -1,
));
?>

<div class="page page_contact">
	<div class="page-header <?php if (has_post_thumbnail()): ?>has-thumbnail<?php endif; ?>">
		<div class="container">
			<div class="row">
				<div class="col col-md-8">
					<h1><?php the_title(); ?></h1>
					<?php if ($subtitle): ?>
						<?php echo $subtitle; ?>
					<?php endif; ?>
					<?php echo do_shortcode('[gravityform id="3" title="false" description="false"]'); ?>
				</div>
				<div class="col col-md-4">
					<?php if (has_post_thumbnail()): ?>
						<div class="page-thumbnail">
							<?php the_post_thumbnail('medium'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>
	<div class="container">
		<div class="page_contact_cards">
			<div class="card card--contact">

				<h3>Contact Information</h3>
				<p><i class="fas fa-envelope mr-2"></i> <a href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></p>
				<p><i class="fas fa-phone mr-2"></i> <a href="tel:<?php echo esc_html($phone); ?>"><?php echo esc_html($phone); ?></a></p>
				<p><i class="fab fa-linkedin mr-2"></i> <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer">Linkedin</a></p>

			</div>
			<div class="card card--contact">
				<h3>Locations</h3>
				<div class="d-flex gap-4">
					<div>
						<p class="b1">Nancy</p>
						<p><i class="fas fa-map-marker-alt mr-2"></i> <a href="<?php echo esc_html($location_nancy["googlemapurl"]); ?>" target="_blank"><?php echo esc_html($location_nancy["address"]); ?> <br>
								<?php echo esc_html($location_nancy["zipcode"]); ?>, <?php echo esc_html($location_nancy["city"]); ?></a></p>
					</div>
					<div>
						<p class="b1">Paris</p>
						<p><i class="fas fa-map-marker-alt mr-2"></i> <a href="<?php echo esc_html($location_paris["googlemapurl"]); ?>" target="_blank"><?php echo esc_html($location_paris["address"]); ?> <br>
								<?php echo esc_html($location_paris["zipcode"]); ?>, <?php echo esc_html($location_paris["city"]); ?></a></p>
					</div>
				</div>
			</div>
		</div>

		<section class="section page_team">
			<span class="section--subtitle"> The abyssien crew</span>
			<h2>Meet the team</h2>
			<div class="team-members">
				<div class="wrapper">
					<?php foreach ($consultants as $consultant) : ?>
						<div class="card card--consultant">
							<div class="card--consultant_img">
								<a href="<?php echo esc_html(get_field('linkedin', $consultant->ID)); ?>" class="card--consultant_link" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin mr-2"></i> </a>
								<img src="<?php echo esc_url(get_the_post_thumbnail_url($consultant->ID, 'full')); ?>" alt="<?php echo esc_attr($consultant->post_title); ?>" loading="lazy" />
							</div>
							<div class="card--consultant_footer">
								<h3 class="mt-0 b1"><?php echo esc_html($consultant->post_title); ?></h3>
								<p class="mb-0 mt-0"><?php echo esc_html(get_field('position', $consultant->ID)); ?></p>
							</div>
							<div class="card--consultant_back">
								<p><?php echo $consultant->post_content; ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="nav-buttons">
				<button class="scroll-left btn btn--outline  btn--icon" aria-label="Scroll left"><i class="fa fa-chevron-left"></i></button>
				<button class="scroll-right btn btn--outline btn--icon" aria-label="Scroll right"><i class="fa fa-chevron-right"></i></button>
			</div>
		</section>
	</div>

	<script>
		const scrollLeftBtn = document.querySelector('.scroll-left');
		const scrollRightBtn = document.querySelector('.scroll-right');
		const wrapper = document.querySelector('.team-members');

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

	<?php get_footer(); ?>
