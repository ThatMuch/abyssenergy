<?php get_header(); ?>



<section class='content job-detail-fixed'>
    
    <div class="wrapper">
    
        <?php if (have_posts()) : ?>
        
        <?php while (have_posts()) : the_post(); ?>
        
        <div id="jobsearchcta" class="wp-block-group has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color wp-elements-0856c7e6f7a84ee3058578ca6cd86a50 showOnScroll jobcta"><div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
<p class="has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color wp-elements-c68677137f738a803b62161fcc92fcc6">Didn’t find your dream job?<br /><a href="https://abyssenergy-fr.squarechilli-staging.co.uk/contact-us/?position=Unsolicited%20Application" data-type="page" data-id="38">Send us your CV</a></p>
</div></div>
        
        <h1 class="wp-block-heading has-colour-orange-color"><?php the_title(); ?></h1>
        
        <div>
                
            <h2 style='margin-top: 28px;padding-bottom: 0'>Job Description</h2>

            <?php the_content(); ?>

            <div class="wp-block-buttons is-layout-flex wp-container-core-buttons-is-layout-1 wp-block-buttons-is-layout-flex">
                <div class="wp-block-button is-style-fill showtabs">
                    <a href='/for-candidates/' class="wp-block-button__link has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color has-text-align-center wp-element-button" style="border-radius:10px">Back to all jobs</a>
                </div>
            </div>
            
            <br style='margin-bottom: 50px' />
                
               
                
            </div>

            
        
        
        
        

        <?php endwhile; ?>

        <?php else : ?>



        <?php endif; ?>
        
    </div>
    
</section>

<?php get_footer(); ?> 