<?php
/**
 * The template for displaying all single events
 *
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
				$post_id = get_the_ID();
				
				//dal  ore  al ore
				$data_begin = get_post_meta($post_id, 'data_inizio', true)!="" ? get_post_meta($post_id, 'data_inizio', true) : "";
				$data_end = get_post_meta($post_id, 'data_fine', true)!="" ? get_post_meta($post_id, 'data_fine', true) : "";
				$hour_begin = get_post_meta($post_id, 'ora_inizio', true)!="" ? get_post_meta($post_id, 'ora_inizio', true) : "";
				$hour_end = get_post_meta($post_id, 'ora_fine', true)!="" ? get_post_meta($post_id, 'ora_fine', true) : "";
				$city = get_post_meta( $post_id , 'city' , true )!="" ? get_post_meta( $post_id , 'city' , true ) : "";
				$address = get_post_meta( $post_id , 'address' , true )!="" ? get_post_meta( $post_id , 'address' , true ) : "";
				$latlong = get_post_meta( $post_id , 'latlongMaps' , true )!="" ? get_post_meta( $post_id , 'latlongMaps' , true ) : "";
				$idEventFfb = get_post_meta( $post_id , 'idEventFfb' , true )!="" ? get_post_meta( $post_id , 'idEventFfb' , true ) : "";
				$urlEventFB = get_post_meta( $post_id , 'urlEventFB' , true )!="" ? get_post_meta( $post_id , 'urlEventFB' , true ) : "";
				//get_template_part( 'content', get_post_format() ); ?>
				<article id="post-<?php echo $post_id;?>" class="post-<?php echo $post_id;?> events type-events status-publish hentry">
                <?php  if ( has_post_thumbnail() ) { ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail("fb_cover_image");?>
                      
					
                    	
    				</div>
                <?php } ?>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title() ?>
                    
                    <?php if ($urlEventFB!="") $urlEventFB = eventissimo_linkadded_protocol($urlEventFB,$protocol="https");
					if ($idEventFfb!="")   $urlEventFB = "https://www.facebook.com/events/" . $idEventFfb;
						
						if ($urlEventFB!=""){
						?>
                        <a target="new" href="<?php echo $urlEventFB;?>" id="linkto_facebook"><i class="fa fa-facebook"></i></a>
                     
						<?php } ?>
                    </h1>
                    
					
					<div class='events_category'>
					<?php
					$types = wp_get_post_terms( $post_id, 'typeEvents'); 
					
					if (count($types)>0) {
						echo "<p>" . __("Types","eventissimo") . ": ";
						foreach ($types as $term) {
							//Always check if it's an error before continuing. get_term_link() can be finicky sometimes
							$term_link = get_term_link( $term, 'typeEvents' );
							if( is_wp_error( $term_link ) )
								continue;
							//We successfully got a link. Print it out.
							echo ' - <a href="' . $term_link . '">' . $term->name . '</a> ';
						}
						echo "</p>";
					}
					$categories = wp_get_post_terms( $post_id, 'eventscategories'); 
					if (count($categories)){
						echo "<p>" .  __("Category","eventissimo") . ": ";
						foreach ($categories as $term) {
							//Always check if it's an error before continuing. get_term_link() can be finicky sometimes
							$term_link = get_term_link( $term, 'eventscategories' );
							if( is_wp_error( $term_link ) )
								continue;
							//We successfully got a link. Print it out.
							echo ' - <a href="' . $term_link . '">' . $term->name . '</a> ';
						}
						echo "</p>";
					}
					?>
					</div>
					
					
					<div class="entry-meta">
                        <span class="edit-link"><a class="post-edit-link" href="wp-admin/post.php?post=<?php echo $post_id;?>&amp;action=edit"><?php _e("Edit");?></a>
                        </span>		
                     </div><!-- .entry-meta -->
                </header><!-- .entry-header -->
            
                    <div class="entry-content">
                    	
                        <div class="author_eventissimo">
                        
                        <?php // echo __("Created by","eventissimo")  .  "  "; ?>
						<?php // the_author_posts_link();?>
                          
						<div class="img_author_circular">
                        	<a title="<?php the_author_meta( 'display_name' );?>" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_avatar(get_the_author_meta( 'ID' ), '64');?>
							<?php the_author_meta( 'display_name' );?></a>
                          </div>
                   
						
						
                        
                           </div><div class="when_eventissimo">
                        <?php 
							
							
							//ConverDate
							$data_begin_convert =  date_i18n(get_option('date_format') , $data_begin );
							$data_end_convert =  date_i18n(get_option('date_format') , $data_end );
							$stringdate = "";							
							if ($data_begin!=$data_end){
								if ($hour_begin!="" && $hour_end !="" ){
									$string[1] = $data_begin_convert;
									$string[2] = $hour_begin;
									$string[3] = $data_end_convert;
									$string[4] = $hour_end;
									$stringdate = __("{1} at {2} until {3} at {4}","eventissimo");
									for($i = 1; $i <= count($string); $i++)
									{
										$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
									}
								} else {
									$string[1] = $data_begin_convert;
									$string[2] = $data_end_convert;
									$stringdate = __("{1} until {2}","eventissimo");
									for($i = 1; $i <= count($string); $i++)
									{
										$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
									}
								}
							} else { 
								$string[1] = $data_begin_convert;
								$string[2] = $hour_begin;
								$string[3] = $hour_end;
								$stringdate = __("{1}  {2} - {3}","eventissimo");
								for($i = 1; $i <= count($string); $i++)
									{
										$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
									}
							}
							echo  $stringdate;
						
						$show_more = __("Show More","eventissimo");	
						$show_less = __("Show Less","eventissimo");	
						?>
                        </div>
                        <div class="description_event">
                        	<div class="text-content short-text">
                    		<?php echo "<p>" . nl2br(get_post_meta( $post_id , 'descrizione' , true )) . "</p>";?>
                        
                       		</div>
                        	<div class="show-more"><a class="button-readmore"><i class="fa fa-plus-square-o"></i><?php echo $show_more;?></a></div>
                    	</div>
                    	
						<?php 
						//Link
						$visited_link = get_post_meta( $post_id , 'urlEventSite' , true )!="" ? get_post_meta( $post_id , 'urlEventSite' , true ) : "";
						if ($visited_link!=""){
							$visited_link=eventissimo_linkadded_protocol($visited_link);
							echo "<p id='linkto_eventissimo'> <a  target='new' href='" . $visited_link . "'><i class ='fa fa-external-link'></i>" . __("More information","eventissimo") . "</a></p>";	
						}
						
						//Maps
						
						
						
						?>
                        <script>
							jQuery(document).ready(function() {
								
								jQuery('a.imagesEvent').colorbox({
									rel:'galleryEvent',
									current:'{current}/{total}',
									height:'90%'
								});
								
								var maxZoom ='<?php get_option('wp_locationZoom');?>';
								var address ='<?php echo $city . ' ' . $address;?>';
								getLangLat(address,'' ,maxZoom);
								
								jQuery(".show-more a").on("click", function() {
									var $link = jQuery(this);
									var $content = $link.parent().prev("div.text-content");
									var linkText = $link.text().toUpperCase();
									switchClasses($content);
									$link.html(getShowLinkText(linkText));
									return false;
								}); 
								function switchClasses($content){
									if($content.hasClass("short-text")){ 
										$content.switchClass("short-text", "full-text", 400);
									} else {
										$content.switchClass("full-text", "short-text", 400);
									}
								}
								function getShowLinkText(currentText){
									var newText = '';
									if (currentText === "<?php echo strtoupper($show_more);?>") {
										newText = "<i class='fa fa-minus-square-o'></i><?php echo $show_less;?>";
									} else {
										newText = "<i class='fa fa-plus-square-o'></i><?php echo $show_more;?>";			
									}
									return newText;
								}	
							});
							
							
							
						</script>
                       
                        <div id="eventissimo_images_container">
                            <ul class="eventissimo_images">
                                <?php
                                    if ( metadata_exists( 'post', $post_id, '_eventissimo_image_gallery' ) ) {
                                        $eventissimo_image_gallery = get_post_meta( $post_id, '_eventissimo_image_gallery', true );
                                    } else {
                                        // Backwards compat
                                        $attachment_ids = array_filter( array_diff( get_posts( 'post_parent=' . $post_id . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids' ), array( get_post_thumbnail_id() ) ) );
                                        $eventissimo_image_gallery = implode( ',', $attachment_ids );
                                    }
                                    
        
                                    $attachments = array_filter( explode( ',', $eventissimo_image_gallery ) );
                                    $thumbs = array();
									
                                    if ( $attachments ) {
                                        foreach ( $attachments as $attachment_id ) {
											$urlthumbs = wp_get_attachment_image_src( $attachment_id,'large');
                                            echo '<li class="image" data-attachment_id="' . $attachment_id . '"><a href="' . $urlthumbs[0] . '" class="imagesEvent" rel="galleryEvent" >' . wp_get_attachment_image( $attachment_id, array(100,100) ) . '</a></li>';
                                            $thumbs[$attachment_id] = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                                        }
                                    }								
                                ?>
                            </ul>
                        
                        </div>
                       <div style="clear:both;"></div>
                        
                        <b id='eventissimo_address'>
                        <i class="fa fa-map-marker"></i><?php echo $address . " " . $city;?></b>
                        <div id="maps"></div>
                    
                    	
                        
                    
						<?php if ($idEventFfb)  {  ?>
						<div class="eventissimo_fb">
						
							<?php
								$statusEventFB = get_post_meta( $post_id , 'statusEventFB' , true )!="" ? get_post_meta( $post_id , 'statusEventFB' , true ) : "";
				
								if ($statusEventFB=="PUBLIC") {
								/*$facebook = accessFacebook(FALSE);
								$response = $facebook->api("/" . $idEventFfb);
								*/
								}
							?>
						
						</div>
						<?php } ?>
					
                    </div><!-- .entry-content -->
                
                </article>
                
               
     
                <?php
				//facebook
				?>

				
                <?php
				//date future
				?>


			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>