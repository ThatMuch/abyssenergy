<?php get_header();
$subtitle = get_field('subtitle');
$description = get_field('description');
?>


<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col col-md-7">
                <h1><?php the_title(); ?></h1>
                <?php if ($subtitle): ?>
                    <?php echo $subtitle; ?>
                <?php endif; ?>
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
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div>
                <h2 style='margin-top: 28px;padding-bottom: 0'>Job Description</h2>
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
