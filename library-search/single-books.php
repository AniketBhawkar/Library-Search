<?php get_header(); ?>
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="container">
			<div class="col-xs-12 singleBook">
				<h1 class="singleBookTitle"><?php echo get_the_title(); ?></h1>
				<div class="singleBookContent">
					<?php echo get_the_content(); ?>
				</div>
			</div>
		</div>
	<?php endwhile; endif; ?>	
<?php get_footer(); ?>