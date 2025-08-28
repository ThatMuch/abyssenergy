<?php get_header(); ?>
<section class='content job-detail'>
    <div class="container">
        <a href="<?php // go back to previous page
                    echo esc_url(wp_get_referer());
                    ?>" class="btn btn--outline mb-5"><i class="fa fa-chevron-left"></i> Back</a>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <section class="job-detail-header">
                    <span class="job-sector">
                        <?php
                        $sector_meta = get_the_terms($post->ID, 'job-sector');
                        $sector = '';
                        if ($sector_meta && !is_wp_error($sector_meta)) {
                            $sector = join(', ', wp_list_pluck($sector_meta, 'name'));
                        }
                        echo $sector; ?>
                    </span>
                    <?php
                    if (get_the_time('U') > strtotime('-5 days')) {
                        echo '<span class="new">New</span>';
                    }
                    ?>
                    <h1 class="job-detail-title"><?php the_title(); ?></h1>
                    <span class="job-skill">
                        <?php
                        $skill_meta = get_the_terms($post->ID, 'job-skill');
                        $skill = '';
                        if ($skill_meta && !is_wp_error($skill_meta)) {
                            $skill = join(', ', wp_list_pluck($skill_meta, 'name'));
                        }
                        echo $skill;
                        ?>
                    </span>
                </section>

                <section class="job-detail-info">
                    <div class="row">
                        <div class="col col-md-3">
                            <p>Location</p>
                            <?php
                            $city = get_field('job_city');
                            $state = get_field('job_state');
                            ?>
                            <p class="job-location">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?php
                                if ($city) {
                                    echo esc_html($city);
                                    if ($state) {
                                        echo ', ' . esc_html($state);
                                    }
                                } elseif ($state) {
                                    echo esc_html($state);
                                } else {
                                    echo 'Location not specified';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col col-md-3">
                            <p>Category</p>
                            <?php
                            $category_meta = get_the_terms($post->ID, 'job-category');
                            $category = '';
                            if ($category_meta && !is_wp_error($category_meta)) {
                                $category = join(', ', wp_list_pluck($category_meta, 'name'));
                            }
                            ?>
                            <span class="tag tag-primary">
                                <?php echo esc_html($category); ?>
                            </span>
                        </div>
                        <div class="col col-md-3">
                            <p>Work type</p>
                            <?php
                            $emp_meta = get_the_terms($post->ID, 'job-type');
                            $emp = '';
                            if ($emp_meta && !is_wp_error($emp_meta)) {
                                $emp = join(', ', wp_list_pluck($emp_meta, 'name'));
                            }
                            ?>
                            <span class="tag tag-primary">
                                <?php echo esc_html($emp); ?>
                            </span>
                        </div>
                    </div>
                </section>
                <div class="row">
                    <div class="col col-md-9 pr-4">
                        <h2 style='margin-top: 28px;padding-bottom: 0'>Job Description</h2>
                        <?php the_content(); ?>
                    </div>
                    <div class="col col-md-3">
                        <div class="card">
                            <h2>Apply</h2>
                            <?php
                            $jobID = get_field('job_id');
                            $owner = get_field('recruiter_email');
                            $screeningQuestion = get_field('screening_question');

                            // Vérifier si le champ est une chaîne JSON et la convertir en tableau si nécessaire
                            if ($screeningQuestion && is_string($screeningQuestion)) {
                                // Essayer de décoder au cas où c'est une chaîne JSON
                                $decoded = json_decode($screeningQuestion, true);
                                if (is_array($decoded)) {
                                    $screeningQuestion = $decoded;
                                }
                            }

                            // S'assurer que nous avons un tableau pour l'implode
                            if ($screeningQuestion && is_array($screeningQuestion)) {
                                $screeningQuestion = implode(', ', $screeningQuestion);
                            } elseif ($screeningQuestion && !is_array($screeningQuestion)) {
                                // Si c'est une chaîne mais pas un JSON valide, on la garde telle quelle
                                $screeningQuestion = strval($screeningQuestion);
                            } else {
                                $screeningQuestion = '';
                            }

                            $skill_meta = get_the_terms($post->ID, 'job-skill');
                            $skill = '';
                            if ($skill_meta && !is_wp_error($skill_meta)) {
                                $skill = join(', ', wp_list_pluck($skill_meta, 'name'));
                            }
                            echo do_shortcode('[gravityform id="1" title="false" ajax="true" field_values="jobID=' . $jobID . '&owner=' . $owner . '&position=' . $skill . '&screening=' . $screeningQuestion . '"]');
                            ?>
                        </div>
                    </div>
                </div>
                <section class="related-jobs">
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

                            $category_meta = get_the_terms($post->ID, 'job-category');
                            $category = '';
                            if ($category_meta && !is_wp_error($category_meta)) {
                                $category = join(', ', wp_list_pluck($category_meta, 'name'));
                            }

                            $sector_meta = get_the_terms($post->ID, 'job-sector');
                            $sector = '';
                            if ($sector_meta && !is_wp_error($sector_meta)) {
                                $sector = join(', ', wp_list_pluck($sector_meta, 'name'));
                            }

                    ?>
                            <div class='job-result'>
                                <a href='<?php the_permalink(); ?>'>
                                    <?php
                                    if (get_the_time('U') > strtotime('-5 days')) {
                                        echo '<div class="new">New</div>';
                                    }
                                    ?>
                                    <h2><?php the_title(); ?></h2>
                                    <div class="job-location">
                                        <?php if ($category) {
                                            echo '<p>' . $category . '</p>';
                                        }; ?>
                                        <?php if ($sector) {
                                            echo '<p>' . $sector . '</p>';
                                        }; ?>
                                        <p><?php
                                            if ($sity) {
                                                echo esc_html($sity);
                                                if ($state) {
                                                    echo ', ' . esc_html($state);
                                                }
                                            } elseif ($state) {
                                                echo esc_html($state);
                                            }
                                            ?></p>
                                    </div>
                                </a>
                            </div>
                    <?php endwhile;
                        echo '</div>';
                    endif;
                    // Reset post data
                    wp_reset_postdata();
                    ?>
                </section>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
