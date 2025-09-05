<?php

/**
 * Personnalisations du Customizer WordPress
 *
 * Configurations pour le Customizer WordPress, incluant le CTA Header et autres options.
 *
 * @package AbyssEnergy
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration du CTA Header via le Customizer WordPress
 */
function abyssenergy_header_cta_customize_register($wp_customize)
{
	// Section CTA Header
	$wp_customize->add_section('abyssenergy_header_cta_section', array(
		'title'       => __('CTA Header', 'abyssenergy'),
		'description' => __('Configurez le bouton d\'appel à l\'action qui apparaît dans l\'en-tête du site.', 'abyssenergy'),
		'priority'    => 25,
	));

	// Activation du CTA Header
	$wp_customize->add_setting('abyssenergy_header_cta_enabled', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_enabled', array(
		'label'    => __('Activer le CTA dans le header', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 10,
	));

	// Texte du CTA
	$wp_customize->add_setting('abyssenergy_header_cta_text', array(
		'default'           => __('Nous contacter', 'abyssenergy'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('abyssenergy_header_cta_text', array(
		'label'    => __('Texte du bouton', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'text',
		'priority' => 20,
	));

	// URL du CTA
	$wp_customize->add_setting('abyssenergy_header_cta_url', array(
		'default'           => home_url('/contact/'),
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_url', array(
		'label'    => __('URL de destination', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'url',
		'priority' => 30,
	));

	// Style du CTA
	$wp_customize->add_setting('abyssenergy_header_cta_style', array(
		'default'           => 'primary',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_style', array(
		'label'    => __('Style du bouton', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'select',
		'priority' => 40,
		'choices'  => array(
			'primary'   => __('Primary', 'abyssenergy'),
			'secondary' => __('Secondary', 'abyssenergy'),
			'outline'   => __('Outline', 'abyssenergy'),
		),
	));

	// Taille du CTA
	$wp_customize->add_setting('abyssenergy_header_cta_size', array(
		'default'           => 'medium',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_size', array(
		'label'    => __('Taille du bouton', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'select',
		'priority' => 50,
		'choices'  => array(
			'small'  => __('Petit', 'abyssenergy'),
			'medium' => __('Moyen', 'abyssenergy'),
			'large'  => __('Grand', 'abyssenergy'),
		),
	));

	// Icône du CTA
	$wp_customize->add_setting('abyssenergy_header_cta_icon', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control('abyssenergy_header_cta_icon', array(
		'label'       => __('Icône (optionnel)', 'abyssenergy'),
		'description' => __('Code HTML de l\'icône (ex: &lt;i class="fas fa-phone"&gt;&lt;/i&gt;)', 'abyssenergy'),
		'section'     => 'abyssenergy_header_cta_section',
		'type'        => 'text',
		'priority'    => 60,
	));

	// Ouverture dans un nouvel onglet
	$wp_customize->add_setting('abyssenergy_header_cta_target', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_target', array(
		'label'    => __('Ouvrir dans un nouvel onglet', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 70,
	));

	// Masquer sur mobile
	$wp_customize->add_setting('abyssenergy_header_cta_hide_mobile', array(
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('abyssenergy_header_cta_hide_mobile', array(
		'label'    => __('Masquer sur mobile', 'abyssenergy'),
		'section'  => 'abyssenergy_header_cta_section',
		'type'     => 'checkbox',
		'priority' => 80,
	));
}
add_action('customize_register', 'abyssenergy_header_cta_customize_register');

/**
 * Enqueue des scripts pour le CTA Header
 */
function abyssenergy_enqueue_header_cta_assets()
{
	// Script pour le CTA Header
	if (get_theme_mod('abyssenergy_header_cta_enabled', false)) {
		wp_enqueue_script(
			'abyssenergy-header-cta',
			get_stylesheet_directory_uri() . '/js/header-cta.js',
			array(),
			abyssenergy_get_file_version('/js/header-cta.js'),
			true
		);

		// Variables pour le script header CTA
		wp_localize_script('abyssenergy-header-cta', 'abyssenergyHeaderCTA', array(
			'enabled' => get_theme_mod('abyssenergy_header_cta_enabled', false),
			'text' => get_theme_mod('abyssenergy_header_cta_text', __('Nous contacter', 'abyssenergy')),
			'url' => get_theme_mod('abyssenergy_header_cta_url', home_url('/contact/')),
			'style' => get_theme_mod('abyssenergy_header_cta_style', 'primary'),
			'size' => get_theme_mod('abyssenergy_header_cta_size', 'medium'),
			'icon' => get_theme_mod('abyssenergy_header_cta_icon', ''),
			'target' => get_theme_mod('abyssenergy_header_cta_target', false),
			'hide_mobile' => get_theme_mod('abyssenergy_header_cta_hide_mobile', false),
		));
	}
}
add_action('wp_enqueue_scripts', 'abyssenergy_enqueue_header_cta_assets');

/**
 * Fonction pour afficher le CTA Header (utilisée pour preview et fallback)
 */
function abyssenergy_display_header_cta()
{
	// Vérifier si le CTA est activé
	if (!get_theme_mod('abyssenergy_header_cta_enabled', false)) {
		return '';
	}

	// Récupérer les paramètres
	$text = get_theme_mod('abyssenergy_header_cta_text', __('Nous contacter', 'abyssenergy'));
	$url = get_theme_mod('abyssenergy_header_cta_url', home_url('/contact/'));
	$style = get_theme_mod('abyssenergy_header_cta_style', 'primary');
	$size = get_theme_mod('abyssenergy_header_cta_size', 'medium');
	$icon = get_theme_mod('abyssenergy_header_cta_icon', '');
	$target = get_theme_mod('abyssenergy_header_cta_target', false);
	$hide_mobile = get_theme_mod('abyssenergy_header_cta_hide_mobile', false);

	// Si pas de texte ou d'URL, ne pas afficher
	if (empty($text) || empty($url)) {
		return '';
	}

	// Classes CSS
	$button_classes = array(
		'header-cta-btn',
		'btn',
		'btn--' . $style,
		'btn--' . $size
	);

	if ($hide_mobile) {
		$button_classes[] = 'header-cta-btn--hide-mobile';
	}

	// Attributs du lien
	$link_attrs = array(
		'href' => esc_url($url),
		'class' => esc_attr(implode(' ', $button_classes))
	);

	if ($target) {
		$link_attrs['target'] = '_blank';
		$link_attrs['rel'] = 'noopener noreferrer';
	}

	// Construction du HTML
	$attributes = '';
	foreach ($link_attrs as $attr => $value) {
		$attributes .= sprintf(' %s="%s"', $attr, $value);
	}

	ob_start();
?>
	<div class="header-cta">
		<a<?php echo $attributes; ?>>
			<?php if (!empty($icon)) : ?>
				<span class="header-cta-btn__icon"><?php echo wp_kses_post($icon); ?></span>
			<?php endif; ?>
			<span class="header-cta-btn__text"><?php echo esc_html($text); ?></span>
			</a>
	</div>
<?php
	return ob_get_clean();
}

/**
 * Ajouter le logo du footer dans le Customizer
 */
function abyssenergy_customize_register_footer_logo($wp_customize)
{
	// Ajouter le logo du footer
	$wp_customize->add_setting('abyssenergy_footer_logo', array(
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control(
		$wp_customize,
		'abyssenergy_footer_logo',
		array(
			'label'    => __('Logo du Footer', 'abyssenergy'),
			'section'  => 'title_tagline',
			'settings' => 'abyssenergy_footer_logo',
			'priority' => 30,
		)
	));
}
add_action('customize_register', 'abyssenergy_customize_register_footer_logo');
