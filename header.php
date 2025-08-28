<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="THATMUCH website and graphic design">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="apple-touch-icon"
        href="/wp-content/themes/abyssenergy/images/Home+screen+icon.png" />
    <link rel="icon" type="image/x-icon" href="/wp-content/themes/abyssenergy/images/site_favicon_16_1713442626756.ico" />
    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
    <?php wp_head(); ?>
    <script type="text/javascript">
        window.hfAccountId = "f2f9a176-d230-44d6-8344-1974943ba44f";
        window.hfDomain = "https://api.herefish.com";
        (function() {
            var hf = document.createElement('script');
            hf.type = 'text/javascript';
            hf.async = true;
            hf.src = window.hfDomain + '/scripts/hf.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(hf, s);
        })();
    </script>
</head>

<body <?php body_class(); ?>>

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
