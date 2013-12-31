<?php
/*
Plugin Name: Eventissimo
Plugin URI: http://www.digitalissimo.it
Viewver Shortcode:
1 - Calendar
2 - List Events
3 - Events filter
*/
add_shortcode( 'eventissimo', 'eventissimo_shortcode' );
function eventissimo_shortcode($atts) {
	$sh_meta = shortcode_atts(array('type'=>'','limit'=>'', 'date'=>'false', 'paginate'=>'false','view'=>'NEXT',), $atts);
	$type = strtoupper($sh_meta['type']);
	$date = strtoupper($sh_meta['date'])=='TRUE'? TRUE : FALSE;
	$paginate = strtoupper($sh_meta['paginate'])=='TRUE'? TRUE : FALSE;
	$post_per_page = $sh_meta['limit'];
	if (($paginate==TRUE) && ($post_per_page=="")) $post_per_page=10;
	$view = strtoupper($sh_meta['view']);
	switch ($type){
		case "CALENDAR":
		  	return eventissimo_frontend_calendar();
		break;
		case "LIST":
		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"LIST");
		break;
		case "BLOCK":
		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"BLOCK");
		break;
		default:
			return __("Invalid Shortcode","eventissimo");	
	}
}

?>
