<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="THATMUCH website and graphic design">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="apple-touch-icon"
        href="/wp-content/themes/abyssenergy/images/Home+screen+icon.png" />
    <link rel="icon" type="image/x-icon" href="/wp-content/themes/abyssenergy/images/site_favicon_16_1713442626756.ico" />
    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    <?php wp_head(); ?>
</head>

<?php
$sector = get_the_terms(get_the_ID(), 'sector-category');


if ($sector && !is_wp_error($sector)) {
    $sector = $sector[0]->slug;
} else {
    $sector = '';
}

// the page slug
$page_slug = get_post_field('post_name', get_post());
?>

<body <?php body_class(['page-' . esc_attr($sector), 'page-' . $page_slug]); ?>>

    <header class="header">
        <div class="header__logo">
            <?php
            if (function_exists('the_custom_logo')) {
                the_custom_logo();
            } else { ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" class="header__logo-image">
                </a>
            <?php } ?>
        </div>
        <div>
            <input class="side-menu" type="checkbox" id="side-menu" />
            <label class="hamb" for="side-menu"><span class="hamb-line"></span></label>
            <nav class='main-menu'>
                <?php wp_nav_menu(array('theme_location' => 'main-menu', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
            </nav>
        </div>
    </header>
