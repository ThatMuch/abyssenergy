<?php $footer_logo = get_theme_mod('abyssenergy_footer_logo', ''); ?>
<footer class='footer'>
    <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>" class='footer-logo' loading="lazy">
    <div class='footer-links'>
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
        <p>Copyright &copy; All Rights Reserved Abyss-Energy <?php echo date("Y"); ?> | <a href='/privacy-policy/'>Privacy Policy</a> | <a href='/general-conditions/'>General Conditions</a> | <a href='https://www.linkedin.com/company/abyss-energy' target='_blank' aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></p>

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
