	<?php get_header();
	$subtitle = get_field('subtitle');
	$description = get_field('description');
	?>
	<div class="page-header">
		<div class="container">
			<div class="row">
				<div class="col col-md-7">
					<h1><?php the_title(); ?></h1>
					<?php if ($subtitle): ?>
						<?php echo $subtitle; ?>
					<?php endif; ?>
					<?php if ($description): ?>
						<div class="page-description">
							<?php echo $description; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="col col-md-5">
					<?php if (has_post_thumbnail()): ?>
						<div class="page-thumbnail">
							<?php the_post_thumbnail('medium'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

	<main>
		<?php the_content(); ?>
	</main>

	<?php get_footer(); ?>
