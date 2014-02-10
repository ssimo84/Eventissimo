<?php
/*
Single Post Template: Single Events

*/

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
    
		<?php
        //COPY THIS CODE
		$orders_single=  explode(",",get_option('wp_order_singleevent'));
        while ( have_posts() ) : the_post();
            eventissimo_get_template_single($orders_single);
        endwhile;
		//END COPY
        ?>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>