<?php
/*
Viewver Shortcode:
1 - Calendar
2 - List Events
3 - Events filter
*/
add_shortcode( 'eventissimo', 'eventissimo_shortcode' );
function eventissimo_shortcode($atts) {
	$sh_meta = shortcode_atts(
		array(
			'type'=>'',
			'limit'=>'',
			'date'=>'false',
			'paginate'=>'false',
			'view'=>'',
			'defined'=>'',
			'backcolorHEX'=>'#069C88',
			'textcolorHEX'=>'#FFFFFF'
		), $atts);
	
	$type = strtoupper($sh_meta['type']);
	$date = strtoupper($sh_meta['date'])=='TRUE'? TRUE : FALSE;
	$paginate = strtoupper($sh_meta['paginate'])=='TRUE'? TRUE : FALSE;
	$defined = strtoupper($sh_meta['defined']);
	$post_per_page = $sh_meta['limit'];
	$view = strtoupper($sh_meta['view']);
	$backcolorHEX = strtoupper($sh_meta['backcolorHEX']);
	$textcolorHEX = strtoupper($sh_meta['textcolorHEX']);
	if (($paginate==TRUE) && ($post_per_page=="")) $post_per_page=10;
	
	switch ($type){
		case "CALENDAR":
		  	return eventissimo_frontend_calendar($backcolorHEX,$textcolorHEX);
		break;
		case "LIST":
		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"LIST",$defined);
		break;
		case "BLOCK":
		  	return eventissimo_frontend_list($post_per_page,$date,$view,$paginate,"BLOCK",$defined);
		break;
		case "CYCLE":
		  	return eventissimo_frontend_cycle($post_per_page,$view,$defined);
		break;
		default:
			return __("Invalid Shortcode","eventissimo");	
	}
}

?>
