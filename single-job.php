<?php get_header(); ?>



<section class='content job-detail'>
    
    <div class="wrapper">
    
        <?php if (have_posts()) : ?>
        
        <?php while (have_posts()) : the_post(); ?>
        
        <div id="jobsearchcta" class="wp-block-group has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color wp-elements-0856c7e6f7a84ee3058578ca6cd86a50 showOnScroll jobcta"><div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
<p class="has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color wp-elements-c68677137f738a803b62161fcc92fcc6">Didn’t find your dream job?<br /><a href="https://abyssenergy-fr.squarechilli-staging.co.uk/contact-us/?position=Unsolicited%20Application" data-type="page" data-id="38">Send us your CV</a></p>
</div></div>
        
        <h1 class="wp-block-heading has-colour-orange-color"><?php the_title(); ?></h1>
        
        <div>
            
            <div>
                <div>
                    <h2>Apply</h2>
                    <?php
                    $jobID = get_field('job_id');
                    $owner = get_field('recruiter_email');
                    $screeningQuestion = get_field('screening_question');
                    $screeningQuestion = implode(', ', $screeningQuestion);
                    
                    $skill_meta = get_the_terms( $post->ID, 'job-skill' );
                    $skill = join(', ', wp_list_pluck($skill_meta, 'name'));
                    

                    echo do_shortcode('[gravityform id="1" title="false" ajax="true" field_values="jobID='.$jobID.'&owner='.$owner.'&screening='.$screeningQuestion.'&position='.$skill.'"]');
                    ?> 

                    <hr style='margin-top: 50px;margin-bottom: 30px;background: #428bca;height: 1px;border: 0;' />



                    <?php
                        // Get the current post's categories
                        $current_post_id = get_the_ID();
                        $categories = get_the_category();

                        $category_ids = [];

                        if (!empty($categories)) {
                            foreach ($categories as $category) {
                                $category_ids[] = $category->term_id;
                            }
                        }

                        // Define query arguments
                        $args = [
                            'post_type' => 'job',
                            'posts_per_page' => 3,
                            'post__not_in' => [$current_post_id], // Exclude current post
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ];

                        // If categories exist, filter by category
                        if (!empty($category_ids)) {
                            $args['category__in'] = $category_ids;
                        }

                        // Query the jobs
                        $query = new WP_Query($args);

                        // If no jobs found in the same category, fetch the latest 5 posts
                        if (!$query->have_posts()) {
                            $args = [
                                'post_type' => 'job',
                                'posts_per_page' => 3,
                                'post__not_in' => [$current_post_id],
                                'orderby' => 'date',
                                'order' => 'DESC'
                            ];
                            $query = new WP_Query($args);
                        }

                        // Display the jobs
                        if ($query->have_posts()) :
                            echo '<h2>Similar Jobs</h2>';
                            echo '<div class="similar-jobs">';
                            while ($query->have_posts()) : $query->the_post(); 
                            
                            $client = get_field('job_client_name');
        
                            $sity = get_field('job_city');

                            $state = get_field('job_state');

                            $category_meta = get_the_terms( $post->ID, 'job-category' );
                            $category = join(', ', wp_list_pluck($category_meta, 'name'));

                            $sector_meta = get_the_terms( $post->ID, 'job-sector' );
                            $sector = join(', ', wp_list_pluck($sector_meta, 'name'));
                    
                    ?>
                                <div class='job-result'>
                                    <a href='<?php the_permalink(); ?>'>
                                    <?php
                                        if ( get_the_time('U') > strtotime('-5 days') ) {
                                            echo '<div class="new">New</div>';
                                        }
                                    ?>
                                    <h2><?php the_title(); ?></h2>
                                    <div class="job-location">
                                        <?php if ($category) { echo '<p>'.$category.'</p>';}; ?>
                                        <?php if ($sector) { echo '<p>'.$sector.'</p>';}; ?>
                                        <p><?php echo $sity; ?>, <?php echo $state; ?></p>
                                    </div>
                                    </a>
                                </div>
                            <?php endwhile;
                            echo '</div>';
                        endif;

                        // Reset post data
                        wp_reset_postdata();    


                    ?>

                
                
            </div>
        </div>
        
        
            
        
            <div>
                
                <h2 class='job-detail-title'>Job Detail</h2>
                
                <div class='job-detail-info'>
                    
                    <?php
                        $sity = get_field('job_city');
                        $state = get_field('job_state');
                    
                        $category_meta = get_the_terms( $post->ID, 'job-category' );
                        $category = join(', ', wp_list_pluck($category_meta, 'name'));

                        $emp_meta = get_the_terms( $post->ID, 'job-type' );
                        $emp = join(', ', wp_list_pluck($emp_meta, 'name'));
                    
                        $skill_meta = get_the_terms( $post->ID, 'job-skill' );
                        $skill = join(', ', wp_list_pluck($skill_meta, 'name'));
                    
                        $sector_meta = get_the_terms( $post->ID, 'job-sector' );
                        $sector = join(', ', wp_list_pluck($sector_meta, 'name'));

                    
                    ?>
                
                    <p><?php if ($emp) { echo '<span>Work Type:</span>'.$emp.'';}?></p>
                    <p><?php if ($sity) {  echo'<span>Location:</span>'.$sity.', '.$state.'';} ?></p>
                    <p><?php if ($sector) { echo '<span>Sector:</span>'.$sector.'';}?></p>
                    <p><?php if ($category) { echo '<span>Category:</span>'.$category.'';}?></p>
                    <p><?php if ($skill) { echo '<span>Role:</span>'.$skill.'';}?></p>
                    <div class='share'>
                        <?php 
                            $jobUrl = untrailingslashit(get_permalink());
                            $jobTitle = get_the_title(); 
                        ?>

                        <p><span><strong>Share this job:</strong></span>

                        <a class="btn btn-default btn-block btn-white job-social-share" data-social="linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $jobUrl; ?>&title=<?php echo $jobTitle; ?>&source=Abyss" target="_blank" style="display:inline"><i class="fa-brands fa-linkedin-in"></i></a>
                            

                        <a class="btn btn-default btn-block btn-white job-social-share" data-social="whatsapp" href="https://api.whatsapp.com/send?text=New job opening / Nouveau poste disponible - <?php echo $jobUrl; ?>" target="_blank"  style="display:inline"><i class="fa-brands fa-whatsapp"></i></a>

                        <a class="btn btn-default btn-block btn-white job-social-share" data-social="email" href="mailto:?subject=New job opening / Nouveau poste disponible&body=<?php echo $jobUrl; ?>" style="display:inline"><i class="fa-regular     fa-envelope"></i></a>
                            
                        </p>
                        <p><span>About Us:</span>Abyss Energy is a consulting firm specializing in engineering and recruitment</p>

                    </div>
                
                </div>
                
                <h2 style='margin-top: 28px;padding-bottom: 0'>Job Description</h2>
                
                <?php the_content(); ?>
                
                <div class="wp-block-buttons is-layout-flex wp-container-core-buttons-is-layout-1 wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button is-style-fill showtabs">
                        <a href='/search-jobs/' class="wp-block-button__link has-colour-white-color has-colour-orange-background-color has-text-color has-background has-link-color has-text-align-center wp-element-button" style="border-radius:10px">Back to all jobs</a>
                    </div>
                </div>
                
               
                
            </div>

            
        
        
        
        

        <?php endwhile; ?>

        <?php else : ?>



        <?php endif; ?>
        
    </div>
    
</section>

<?php get_footer(); ?> 