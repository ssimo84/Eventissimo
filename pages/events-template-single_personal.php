<?php
/**
 * The template for displaying all single events
 *
 */

get_header(); ?>

<section class="content">
	
	<?php get_template_part('inc/page-title'); ?>
	
	<div class="pad group">
		
        <?php
		$orders_single=  explode(",",get_option('wp_order_singleevent'));
        while ( have_posts() ) : the_post();
            eventissimo_get_template_single($orders_single);
        endwhile;
		?>
		
		<div class="clear"></div>
		
		<?php the_tags('<p class="post-tags"><span>'.__('Tags:','hueman').'</span> ','','</p>'); ?>
		
		<?php if ( !ot_get_option( 'author-bio' ) && get_the_author_meta( 'description' ) ): ?>
			<div class="author-bio">
				<div class="bio-avatar"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></div>
				<p class="bio-name"><?php the_author_meta('display_name'); ?></p>
				<p class="bio-desc"><?php the_author_meta('description'); ?></p>
				<div class="clear"></div>
			</div>
		<?php endif; ?>
		

		

		
	</div><!--/.pad-->
	
</section><!--/.content-->

<?php get_sidebar(); ?>

<?php get_footer(); ?>