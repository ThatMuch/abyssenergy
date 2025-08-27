<footer>
    <div class='footer-skyline'>
        <img src='<?php echo get_stylesheet_directory_uri(); ?>/images/footer-skyline.png' alt='Abyss Energy Recruitment Footer Image of energy creation' />
    </div>
    <div class='footer-links'>
        <div class='wrapper'>
            <div>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu1', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
            </div>
            <div>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu2', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
            </div>
            <div>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu3', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
            </div>
            <div>
                <?php wp_nav_menu(array('theme_location' => 'footer-menu4', 'container' => false, 'menu_class' => 'menu', 'menu_id' => '',)); ?>
            </div>
        </div>
    </div>
</footer>

<div class='footer-signoff'>
    <div class='wrapper'>
        <p>Copyright &copy; All Rights Reserved Abyss-Energy <?php echo date("Y"); ?> | <a href='/privacy-policy/'>Privacy Policy</a> &amp; <a href='/general-conditions/'>General Conditions</a> | <a href='https://www.linkedin.com/company/abyss-energy' target='_blank'>Follow on LinkedIn <i class="fab fa-linkedin-in"></i></a></p>
        <?php do_action('wpml_add_language_selector'); ?>
    </div>
</div>
<?php wp_footer(); ?>
</body>

</html>
