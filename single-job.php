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
                            <p class="job-label">Location</p>
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
                            <p class="job-label">Category</p>
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
                        <div class="col col-md-5">
                            <p class="job-label">Work type</p>
                            <div class="d-inline">
                                <?php
                                $emp_meta = get_the_terms($post->ID, 'job-type');

                                // transform $emp_meta to string
                                if ($emp_meta && !is_wp_error($emp_meta)) {
                                    $emp_meta = wp_list_pluck($emp_meta, 'name');
                                    $emp_meta = array_map('trim', $emp_meta);
                                }
                                $work_types = explode('–', implode('–', $emp_meta));

                                foreach ($work_types as $type) {
                                    echo '<span class="tag mr-2">' . esc_html($type) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="row">
                    <div class="col col-md-9">
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

            <?php endwhile; ?>
        <?php endif; ?>
        <?php get_template_part('template-parts/section-similar-jobs'); ?>
    </div>
</section>

<?php get_footer(); ?>
