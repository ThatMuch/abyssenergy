<?php get_header();
$subtitle = get_field('subtitle');
$description = get_field('description');
// the job category
$job_category = get_the_terms($post->ID, 'job-category');
if ($job_category && !is_wp_error($job_category)) {
    $job_category = join(', ', wp_list_pluck($job_category, 'name'));
} else {
    $job_category = '';
}
?>


<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col col-md-7">
                <?php if ($job_category): ?>
                    <span class="section--subtitle"><?php echo $job_category; ?></span>
                <?php endif; ?>
                <h1 class="mt-0"><?php the_title(); ?></h1>
                <?php if ($description): ?>
                    <div class="page-description">
                        <?php echo $description; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col col-md-5">
                <?php if (has_post_thumbnail()): ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class='container'>
    <div class="row">
        <div class="col-lg-8">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="job-content">
                        <h2 class="job-content-title">Job Description</h2>
                        <div class="job-content-body">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <aside class="job-sidebar">
                <h3 class="sidebar-title">Other Job Categories</h3>

                <?php
                // Récupérer toutes les catégories d'emploi
                $job_categories = get_terms(array(
                    'taxonomy' => 'job-category',
                    'hide_empty' => true,
                ));

                if (!empty($job_categories) && !is_wp_error($job_categories)) :
                    foreach ($job_categories as $category) :
                        // Récupérer TOUS les postes de cette catégorie pour avoir le bon compte
                        $all_category_jobs = get_posts(array(
                            'post_type' => 'fixed-job',
                            'post_status' => 'publish',
                            'numberposts' => -1,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'job-category',
                                    'field' => 'term_id',
                                    'terms' => $category->term_id,
                                ),
                            ),
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ));

                        // Récupérer seulement les 5 premiers pour l'affichage initial
                        $category_jobs = array_slice($all_category_jobs, 0, 5);
                        $total_jobs_count = count($all_category_jobs);

                        if (!empty($category_jobs)) :
                ?>
                            <div class="job-category-card" data-category="<?php echo esc_attr($category->slug); ?>">
                                <div class="category-header">
                                    <h4 class="category-title"><?php echo esc_html($category->name); ?></h4>
                                    <span class="category-count"><?php echo $total_jobs_count; ?> positions</span>
                                </div>

                                <div class="category-jobs">
                                    <?php foreach ($category_jobs as $job) : ?>
                                        <div class="job-item <?php echo ($job->ID === get_the_ID()) ? 'current-job' : ''; ?>">
                                            <h5 class="job-item-title">
                                                <a href="<?php echo get_permalink($job->ID); ?>">
                                                    <?php echo esc_html($job->post_title); ?>
                                                </a>
                                            </h5>
                                            <?php if (!empty($job->post_excerpt)) : ?>
                                                <p class="job-item-excerpt"><?php echo wp_trim_words($job->post_excerpt, 15); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if ($total_jobs_count > 5) : ?>
                                    <div class="category-actions">
                                        <button class="btn btn--outline btn-show-more" data-category="<?php echo esc_attr($category->slug); ?>" data-loaded="5">
                                            Show More
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                <?php
                        endif;
                    endforeach;
                endif;
                ?>
            </aside>
        </div>
    </div>
</div>

<?php get_footer(); ?>
