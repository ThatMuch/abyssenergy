<?php get_header(); ?>

<section class='content job-detail'>
    <div class="container">
        <button onclick="history.back()" class="btn btn--outline mb-5">
            <i class="fa fa-chevron-left"></i> <?php esc_html_e('Back', 'abyssenergy'); ?>
        </button>
        <?php if ($post) : ?>
            <?php
            // get sector
            $sector_meta = get_the_terms(get_the_ID(), 'job-sector');
            $sector = '';
            $sector_slug = '';
            if ($sector_meta && !is_wp_error($sector_meta)) {
                $sector = join(', ', wp_list_pluck($sector_meta, 'name'));
                $sector_slug = join(', ', wp_list_pluck($sector_meta, 'slug'));
            }

            ?>
            <section class="job-detail-header">
                <span class="job-sector">
                    <?php echo $sector; ?>
                </span>
                <?php
                if (get_the_time('U') > strtotime('-5 days')) {
                    echo '<span class="tag tag-secondary">' . esc_html__('New', 'abyssenergy') . '</span>';
                }
                ?>
                <h1 class="job-detail-title"><?php the_title(); ?></h1>
                <span class="job-skill">
                    <?php
                    $skill_meta = get_the_terms(get_the_ID(), 'job-skill');
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
                        <p class="job-label"><?php esc_html_e('Location', 'abyssenergy'); ?></p>
                        <?php
                        $city = get_field('job_city');
                        $state = get_field('job_state');
                        ?>
                        <span class="job-location tag">
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
                                esc_html_e('Location not specified', 'abyssenergy');
                            }
                            ?>
                        </span>
                    </div>
                    <div class="col col-md-3">
                        <p class="job-label"><?php esc_html_e('Category', 'abyssenergy'); ?></p>
                        <?php
                        $category_meta = get_the_terms(get_the_ID(), 'job-category');
                        $category = '';
                        if ($category_meta && !is_wp_error($category_meta)) {
                            $category = join(', ', wp_list_pluck($category_meta, 'name'));
                        }
                        ?>
                        <span class="tag">
                            <?php echo esc_html($category); ?>
                        </span>
                    </div>
                    <div class="col col-md-5">
                        <p class="job-label"><?php esc_html_e('Work type', 'abyssenergy'); ?></p>
                        <div class="d-inline">
                            <?php
                            $emp_meta = get_the_terms(get_the_ID(), 'job-type');

                            // transform $emp_meta to string
                            if ($emp_meta && !is_wp_error($emp_meta)) {
                                $emp_meta = wp_list_pluck($emp_meta, 'name');
                                $emp_meta = array_map('trim', $emp_meta);
                            }
                            $work_types = explode('–', implode('–', $emp_meta));

                            foreach ($work_types as $type) {
                                echo '<span class="tag">' . esc_html($type) . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="share-job">
                    <p class="job-label"><?php esc_html_e('Share this job', 'abyssenergy'); ?></p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#" onclick="navigator.clipboard.writeText('<?php echo esc_url(get_permalink()); ?>'); alert('<?php echo esc_js(__('Job link copied to clipboard!', 'abyssenergy')); ?>'); return false;" class="btn btn--outline">
                            <i class="fas fa-link"></i> <?php esc_html_e('Copy Link', 'abyssenergy'); ?>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="btn btn--outline">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="btn btn--outline">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="mailto:?subject=<?php echo esc_attr(rawurlencode(__('Check out this job', 'abyssenergy'))); ?>&body=<?php echo esc_attr(rawurlencode(__('I found a job you might be interested in: ', 'abyssenergy'))); ?><?php echo urlencode(get_permalink()); ?>" class="btn btn--outline">
                            <i class="fas fa-envelope"></i> <?php esc_html_e('Email', 'abyssenergy'); ?>
                        </a>
                    </div>
                </div>
            </section>
            <div class="row">
                <div class="col col-xl-8">
                    <h2 style='margin-top: 28px;padding-bottom: 0'><?php esc_html_e('Job Description', 'abyssenergy'); ?></h2>
                    <?php the_content(); ?>
                </div>
                <div class="col col-xl-4">
                    <div class="card">
                        <h2 class="mt-0"><?php esc_html_e('Apply', 'abyssenergy'); ?></h2>
                        <script>
                            // Définir immédiatement le post ID pour les scripts Gravity Forms
                            window.abyssenergy_job_post_id = <?php echo get_the_ID(); ?>;
                        </script>
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

                        $skill_meta = get_the_terms(get_the_ID(), 'job-skill');
                        $skill = '';
                        if ($skill_meta && !is_wp_error($skill_meta)) {
                            $skill = join(', ', wp_list_pluck($skill_meta, 'name'));
                        }
                        echo do_shortcode('[gravityform id="1" title="false" ajax="true" field_values="jobID=' . $jobID . '&owner=' . $owner . '&screening=' . $screeningQuestion . '&position=' . $skill . '&post_id=' . get_the_ID() . '"]');
                        ?>
                    </div>
                </div>
            </div>


        <?php endif; ?>

        <?php
        // Logique pour les posts connexes
        // Get the current post's categories
        $current_post_id = get_the_ID();

        // Define query arguments
        $args = [
            'post_type' => 'job',
            'posts_per_page' => 6,
            'post__not_in' => [$current_post_id], // Exclude current post
            'orderby' => 'date',
            'order' => 'DESC'
        ];

        // Filtrer par secteur si des secteurs sont sélectionnés
        if (!empty($sector_slug)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'job-sector',
                    'field' => 'slug',
                    'terms' => $sector_slug,
                ),
            );
        }

        // Query the jobs
        $query = new WP_Query($args);

        // If no jobs found in the same category, fetch the latest 6 posts
        if (!$query->have_posts()) {
            $args = [
                'post_type' => 'job',
                'posts_per_page' => 6,
                'post__not_in' => [$current_post_id],
                'orderby' => 'date',
                'order' => 'DESC'
            ];
            $query = new WP_Query($args);
        }

        // Passer tous les paramètres nécessaires au template
        get_template_part('template-parts/section-jobs', null, [
            'query' => $query
        ]);
        ?>
    </div>
</section>

<?php get_footer(); ?>
