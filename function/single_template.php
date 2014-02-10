<?php
function eventissimo_single_events($single_template) {
     global $post;
	if (get_option("UseSingleTemplateDefault")=="YES"){
     if ($post->post_type == 'events') {
          $single_template = BASE_URL . '/pages/events-template-single.php';
     }
	 	
     return $single_template;
	} else {
		if ($post->post_type == 'events') {
			$template = get_template();
			return get_theme_root() . "/" . $template . "/single-events.php";
		}
	}
}
add_filter( "single_template", "eventissimo_single_events" );

function eventissimo_order($key,$post_id){
	$data_begin = !isset($_GET["date_begin"])  ? get_post_meta($post_id, 'data_inizio', true) : $_GET["date_begin"];
	$data_end = !isset($_GET["date_end"])  ? get_post_meta($post_id, 'data_fine', true) : $_GET["date_end"];
	$hour_begin = !isset($_GET["hour_begin"])  ? get_post_meta($post_id, 'ora_inizio', true) : $_GET["hour_begin"];
	$hour_end = !isset($_GET["hour_end"])  ? get_post_meta($post_id, 'ora_fine', true) : $_GET["hour_end"];
	$city = get_post_meta( $post_id , 'city' , true )!="" ? get_post_meta( $post_id , 'city' , true ) : "";
	$address = get_post_meta( $post_id , 'address' , true )!="" ? get_post_meta( $post_id , 'address' , true ) : "";
	$latlong = get_post_meta( $post_id , 'latlongMaps' , true )!="" ? get_post_meta( $post_id , 'latlongMaps' , true ) : "";
	$idEventFfb = get_post_meta( $post_id , 'idEventFfb' , true )!="" ? get_post_meta( $post_id , 'idEventFfb' , true ) : "";
	$urlEventFB = get_post_meta( $post_id , 'urlEventFB' , true )!="" ? get_post_meta( $post_id , 'urlEventFB' , true ) : "";

	$return = "";

	switch ($key){
		
		case "TITLE":	
			$return = '
				<header class="entry-header">
                <h1 class="entry-title">' . get_the_title();
            
			if ($urlEventFB!="") 
				$urlEventFB = eventissimo_linkadded_protocol($urlEventFB,$protocol="https");
			if ($idEventFfb!="")
				$urlEventFB = "https://www.facebook.com/events/" . $idEventFfb;
			
			if ($urlEventFB!=""){
				$return .= '   <a target="new" href="' . $urlEventFB .'" id="linkto_facebook"><i class="fa fa-facebook"></i></a>';
              } 
              $return .= '</h1>	
                </header><!-- .entry-header -->';
		break;
		
		case "EVIDENCEIMG":

			if ( has_post_thumbnail() ) {
				$return = '<div class="post-thumbnail">';
				$return .= the_post_thumbnail("fb_cover_image");
				$return .= '</div>';
            }
		break;
		
		case "CATEGORY":
		
			$return = "<div class='events_category'>";
			$types = wp_get_post_terms( $post_id, 'typeEvents'); 
			if (count($types)>0) {
				$return .= "<p>" . __("Types","eventissimo") . ":";
				foreach ($types as $term) {
					$term_link = get_term_link( $term, 'typeEvents' );
					if( is_wp_error( $term_link ) )
						continue;
					$return .= ' - <a href="' . $term_link . '">' . $term->name . '</a> ';
				}
				$return .= "</p>";
			}
			$categories = wp_get_post_terms( $post_id, 'eventscategories'); 
			if (count($categories)){
				$return .= "<p>" .  __("Category","eventissimo") . ": ";
				foreach ($categories as $term) {
					$term_link = get_term_link( $term, 'eventscategories' );
					if( is_wp_error( $term_link ) )
						continue;
					$return .= ' - <a href="' . $term_link . '">' . $term->name . '</a> ';
				}
				$return .= "</p>";
			}
			$return .="</div>";
		
		break;
		
		case "AUTHOR":

			$return .='<div class="author_eventissimo">';
			$return .='<div class="img_author_circular">';
			$return .='<a title="' . get_the_author_meta( 'display_name' ) . '" href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_avatar(get_the_author_meta( 'ID' ), '64');
			$return .= get_the_author_meta( 'display_name' );
			$return .= '</a></div></div><div style="clear:both"></div>';
			
		break;
		
		case "DATE":
		
			$return = '<div class="when_eventissimo">';
           	$data_begin_convert =  date_i18n(get_option('date_format') , $data_begin );
			$data_end_convert =  date_i18n(get_option('date_format') , $data_end );
			$stringdate = eventissimo_text_date($data_begin_convert,$data_end_convert,$hour_begin,$hour_end);
			$return .=  $stringdate;
			
		 $return .= '</div>';
		
		break;
		
		case "DESCRIPTION":
			$show_more = __("Show More","eventissimo");	
			$show_less = __("Show Less","eventissimo");	
            $return .= '<div class="description_event">';
            $return .= '<div class="text-content short-text">';
            $return .= "<p>" . nl2br(get_post_meta( $post_id , 'descrizione' , true )) . "</p>";
			$return .= '</div>
			<div class="show-more">
				<a class="button-readmore">
					<i class="fa fa-plus-square-o"></i>
					' . $show_more .'</a></div></div>';
            $visited_link = get_post_meta( $post_id , 'urlEventSite' , true )!="" ? get_post_meta( $post_id , 'urlEventSite' , true ) : "";
			if ($visited_link!=""){
				$visited_link=eventissimo_linkadded_protocol($visited_link);
				$return .= "<p id='linkto_eventissimo'>
				 <a  target='new' href='" . $visited_link . "'><i id='more' class ='fa fa-external-link'></i>" . __("More information","eventissimo") . "</a></p>";	
			}
			
			$return .= "<script>";
			$return .= '
			jQuery(document).ready(function() {
					jQuery(".show-more a").on("click", function() {
						var $link = jQuery(this);
						var $content = $link.parent().prev("div.text-content");
		
						var linkText = $link.parent().children().children("i").attr("id");
						switchClasses($content);
						$link.html(getShowLinkText(linkText));
						return false;
					}); 
					function switchClasses($content){
						if($content.hasClass("short-text")){ 
								$content.addClass("full-text").removeClass("short-text");						
						} else {
							$content.addClass("short-text").removeClass("full-text");
						}
					}
					function getShowLinkText(currentText){
						var newText = "";
						if ((currentText =="less")) {
							newText = "<i id=\'more\' class=\'fa fa-minus-square-o\'></i>'. $show_more . '";	
						} else {
							newText = "<i id=\'less\' class=\'fa fa-plus-square-o\'></i>'.$show_less . '";		
						}
						return newText;
					}	
				
				});</script><div style="clear:both"></div>';
			
		break;
		
		case "MAPS":
			$return ="<div style='clear:both'></div><b id='eventissimo_address'>";
			$return .="<i class='fa fa-map-marker'></i>";
			$return .= $address . " " . $city . "</b>";
            $return .= "<div id='maps'></div>";
			$return .= '
			<script>
				jQuery(document).ready(function() {
					var maxZoom ="' . get_option("wp_locationZoom") . '";
					var address ="' . $address  . ' ' . $city . '";
					getLangLat(address, "' . $latlong . '" ,maxZoom);

				});
			</script>';
			
		break;
		
		case "GALLERY":
			$return .= '
			<script>
				jQuery(document).ready(function() {
					jQuery("a.imagesEvent").colorbox({
						rel:"galleryEvent",
						current:"{current}/{total}",
						height:"90%"
					});
					
				});
			</script>';
			$return .='<div id="eventissimo_images_container">
				<ul class="eventissimo_images">';
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
					$return .= '<li class="image" data-attachment_id="' . $attachment_id . '"><a href="' . $urlthumbs[0] . '" class="imagesEvent" rel="galleryEvent" >' . wp_get_attachment_image( $attachment_id, array(100,100) ) . '</a></li>';
					$thumbs[$attachment_id] = wp_get_attachment_image( $attachment_id, 'thumbnail' );
				}
			}								
			$return .='	</ul>
			
			</div><div style="clear:both"></div>';
		break;
		
	}
	return $return;
}


function eventissimo_get_template_single($orders_single){
	$post_id = get_the_ID();
	?>           
	<article id="post-<?php echo $post_id;?>" class="post-<?php echo $post_id;?> events type-events status-publish hentry">
		<div class="entry-content">
		<?php
		foreach ($orders_single as $key){
			echo eventissimo_order(strtoupper(trim($key)),$post_id);
		}
		?>
		</div>
	</article>
<?php
}

?>