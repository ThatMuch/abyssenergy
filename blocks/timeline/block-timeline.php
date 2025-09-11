<?php

/** Block name: Timeline
 * Description: A custom timeline block to showcase steps.
 */

// Créer le nom de la classe
$className = 'timeline-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}

if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

// Récupérer les champs
$title = get_field('timeline_title');
$steps = get_field('steps');
?>

<?php if ($is_preview) : ?>
	<div class="block-preview-info alert alert-info">
		<small><?php esc_html_e('Aperçu du bloc Timeline', 'abyssenergy'); ?></small>
	</div>
<?php else : ?>
	<?php if ($steps) : ?>
		<!-- Timeline Block -->
		<div class="<?php echo esc_attr($className); ?>">
			<div class="container">
				<h2 class="timeline-title"><?php echo wp_kses_post($title); ?></h2>
				<div class="timeline-steps" style="<?php echo 'grid-template-rows: repeat(' . count($steps) . ', 200px);'; ?>">
					<?php
					$count = 0;
					foreach ($steps as $step) :
						$count++;
					?>
						<div class="item <?php echo 'item-' . $count; ?>">
							<div class="timeline-step">
								<div class="timeline-step-header">
									<?php if (!empty($step['image'])) : ?>
										<div class="timeline-step-image">
											<img src="<?php echo esc_url($step['image']['url']); ?>"
												alt="<?php echo esc_attr($step['image']['alt']); ?>"
												loading="lazy">
										</div>
									<?php endif; ?>
									<h3 class="timeline-step-title h4"><?php echo wp_kses_post($step['title']); ?></h3>
								</div>
								<div class="timeline-step-content">
									<p class="timeline-step-description"><?php echo wp_kses_post($step['description']); ?></p>
								</div>
							</div>
							<span class="timeline-count"><?php echo $count; ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
