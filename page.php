	<?php get_header();
	$subtitle = safe_get_field_with_default('subtitle', false, '');
	$description = safe_get_field_with_default('description', false, '');
	$buttons = safe_get_field('buttons');
	?>
	<div class="page-header <?php if (has_post_thumbnail()): ?>has-thumbnail<?php endif; ?>">
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
					<?php if ($buttons): ?>
						<div class="page-buttons">
							<?php foreach ($buttons as $button): ?>
								<a href="<?php echo esc_url($button['link']['url']); ?>" class="btn <?php echo $button['style'] === 'fill' ? "btn--primary" : "btn--outline"; ?>" target="<?php echo $button['link']['target'] ? $button['link']['target'] : '_self'; ?>"><?php echo esc_html($button['link']['title']); ?>
									<?php if ($button['icon']): ?>
										<span class="btn__icon"><?php echo $button['icon']; ?></span>
									<?php endif; ?>
								</a>
							<?php endforeach; ?>
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
