<?php

/**
 * Template Name: Contact
 *
 * Template pour afficher le formulaire de contact
 * Ce template peut être assigné à n'importe quelle page depuis l'administration WordPress
 */

get_header();

$email = get_field('email');
$phone = get_field('phone');
$linkedin = get_field('linkedin');
// get_field google map
$location_nancy = get_field('nancy');
$location_paris = get_field('paris');
?>

<div class="page page_contact">
	<div class="page-header">
		<div class="container">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="container">
		<div class="form-wrapper">
			<?php echo do_shortcode('[gravityform id="3" title="false" description="false"]'); ?>
		</div>
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
	</div>

	<?php get_footer(); ?>
