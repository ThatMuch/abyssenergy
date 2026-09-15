<?php $footer_logo = get_theme_mod('abyssenergy_footer_logo', ''); ?>
<footer class='footer'>
    <div class='footer-links'>
        <div class='footer-logo'>
            <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" loading="lazy">
        </div>
        <div class='footer-container'>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu1', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu2', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu3', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu4', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu5', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>
            <?php wp_nav_menu(array('theme_location' => 'footer-menu6', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '')); ?>

        </div>
    </div>
</footer>

<div class='footer-bottom'>
    <div class='container d-flex justify-content-center align-items-center'>
        <div class='footer-bottom-row'>
            <p class='footer-bottom-copyright mr-3'><?php printf(esc_html__('Copyright © All Rights Reserved Abyss Energy %s', 'abyssenergy'), esc_html(date("Y"))); ?></p>
            <?php wp_nav_menu(array(
                'theme_location' => 'footer-bottom-menu',
                'container' => false,
                'menu_class' => 'footer-bottom-menu',
                'menu_id' => '',
                'depth' => 1,
                'fallback_cb' => false,
            )); ?>
            <a href='https://www.linkedin.com/company/abyss-energy' target='_blank' aria-label="LinkedIn" class="footer-bottom-linkedin"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</div>


<?php wp_footer(); ?>
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
</body>

</html>
