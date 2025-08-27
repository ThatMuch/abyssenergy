<?php

/**
 * Template partial: CTA personnalisé
 *
 * Ce fichier peut être inclus dans d'autres templates pour afficher le CTA
 *
 * @package abyssenergy_Child
 */

// Ne pas accéder directement
if (!defined('ABSPATH')) {
	exit;
}

// Vérifier si le CTA est activé
if (!get_theme_mod('abyssenergy_cta_enabled', false)) {
	return;
}

// Récupérer les paramètres du CTA
$cta_data = array(
	'title' => get_theme_mod('abyssenergy_cta_title', __('Rejoignez-nous dès aujourd\'hui', 'abyssenergy-child')),
	'subtitle' => get_theme_mod('abyssenergy_cta_subtitle', __('Découvrez nos opportunités et faites partie de notre équipe innovante.', 'abyssenergy-child')),
	'button_text' => get_theme_mod('abyssenergy_cta_button_text', __('Découvrir nos offres', 'abyssenergy-child')),
	'button_url' => get_theme_mod('abyssenergy_cta_button_url', home_url('/emplois/')),
	'secondary_text' => get_theme_mod('abyssenergy_cta_secondary_text', __('En savoir plus', 'abyssenergy-child')),
	'secondary_url' => get_theme_mod('abyssenergy_cta_secondary_url', home_url('/about/')),
	'style' => get_theme_mod('abyssenergy_cta_style', 'primary'),
);

// Permettre la personnalisation via des arguments
if (isset($args) && is_array($args)) {
	$cta_data = array_merge($cta_data, $args);
}

// Classes CSS
$cta_classes = array(
	'abyssenergy-cta',
	'abyssenergy-cta--' . esc_attr($cta_data['style'])
);

// Ajouter des classes personnalisées si fournies
if (isset($cta_data['extra_classes'])) {
	$cta_classes = array_merge($cta_classes, (array) $cta_data['extra_classes']);
}
?>

<section class="<?php echo esc_attr(implode(' ', $cta_classes)); ?>" data-cta-style="<?php echo esc_attr($cta_data['style']); ?>">
	<div class="container">
		<div class="abyssenergy-cta__content">

			<!-- Section texte -->
			<div class="abyssenergy-cta__text">
				<?php if (!empty($cta_data['title'])) : ?>
					<h2 class="abyssenergy-cta__title">
						<?php echo esc_html($cta_data['title']); ?>
					</h2>
				<?php endif; ?>

				<?php if (!empty($cta_data['subtitle'])) : ?>
					<p class="abyssenergy-cta__subtitle">
						<?php echo esc_html($cta_data['subtitle']); ?>
					</p>
				<?php endif; ?>
			</div>

			<!-- Section actions -->
			<div class="abyssenergy-cta__actions">
				<?php if (!empty($cta_data['button_text']) && !empty($cta_data['button_url'])) : ?>
					<a href="<?php echo esc_url($cta_data['button_url']); ?>"
						class="btn btn--primary btn--lg abyssenergy-cta__button"
						<?php if (isset($cta_data['button_target'])) : ?>
						target="<?php echo esc_attr($cta_data['button_target']); ?>"
						<?php endif; ?>
						<?php if (isset($cta_data['button_rel'])) : ?>
						rel="<?php echo esc_attr($cta_data['button_rel']); ?>"
						<?php endif; ?>>
						<?php echo esc_html($cta_data['button_text']); ?>
						<?php if (isset($cta_data['button_icon'])) : ?>
							<span class="btn__icon"><?php echo wp_kses_post($cta_data['button_icon']); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>

				<?php if (!empty($cta_data['secondary_text']) && !empty($cta_data['secondary_url'])) : ?>
					<a href="<?php echo esc_url($cta_data['secondary_url']); ?>"
						class="btn btn--outline btn--lg abyssenergy-cta__secondary"
						<?php if (isset($cta_data['secondary_target'])) : ?>
						target="<?php echo esc_attr($cta_data['secondary_target']); ?>"
						<?php endif; ?>
						<?php if (isset($cta_data['secondary_rel'])) : ?>
						rel="<?php echo esc_attr($cta_data['secondary_rel']); ?>"
						<?php endif; ?>>
						<?php echo esc_html($cta_data['secondary_text']); ?>
						<?php if (isset($cta_data['secondary_icon'])) : ?>
							<span class="btn__icon"><?php echo wp_kses_post($cta_data['secondary_icon']); ?></span>
						<?php endif; ?>
					</a>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<!-- Schema.org markup pour SEO -->
	<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "Action",
			"name": "<?php echo esc_js($cta_data['title']); ?>",
			"description": "<?php echo esc_js($cta_data['subtitle']); ?>",
			"url": "<?php echo esc_url($cta_data['button_url']); ?>"
		}
	</script>
</section>

<?php
// Hook pour permettre d'ajouter du contenu après le CTA
do_action('abyssenergy_cta_after', $cta_data);
?>
