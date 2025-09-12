<?php
get_header();
?>

<div class="page-header">
	<div class="container">
		<h1 class="page-title"><?php the_title(); ?></h1>
		<form action="<?php echo esc_url(home_url('/search-jobs/')); ?>" method="GET" class="job-search-form d-flex gap-2">
			<input
				type="text"
				id="job-search"
				name="job_search"
				placeholder="Find your next position"
				value="<?php echo esc_attr(get_query_var('job_search')); ?>">
			<button type="submit" class="btn btn--primary btn--icon"><i class="fas fa-search"></i></button>
		</form>
	</div>
	<img src="<?php echo esc_url(get_template_directory_uri() . '/images/ilots.webp'); ?>" alt="">
</div>
<main>
	<?php the_content(); ?>
</main>
<?php
get_footer()
?>
