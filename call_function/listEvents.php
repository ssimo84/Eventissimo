<?php

function eventissimo_listEvent(&$list,$response,$post_per_page,$current,$dateview,$defined,$type,$paginate){
	
	if ($paginate) {

		$new_array = eventissimo_paginate($response,$current,$post_per_page);
		$response = array();
		$response = $new_array;
	}
	if (count($response)>0){
		$list .= "<ul class='events_list'>";
		foreach ($response as $event){
			$list  .= "<li>";
			$list .= "<a href='" . $event->url . "'>" . $event->title;
			
			$list .= $event->thumbs!="" ? $event->thumbs : "<img src='" . BASE_URL_NOIMAGES . "' title='" . $event->title . "'>";
			
			$list .= "</a>";
			if ($dateview){
				$list .= "<span>";
				$list .= date_i18n(get_option('date_format'),$event->date_begin) . " " . $event->hour_begin;
				$list .= "-";	
				if ($event->date_begin!=$event->date_end)
					$list .=  date_i18n(get_option('date_format'),$event->date_end);
				$list .= " " .$event->hour_end;
				
				$list .= "</span>";
			
			}
	
			$list .= "</li>";
		}
	
		$list .= "</ul>";
	} else {
		$list = __("There are no events","eventissimo");
	}
	return $list;
}

function eventissimo_listEvent_ajax(){
	$list = "";
	$post_per_page = isset($_POST["post_per_page"]) ? $_POST["post_per_page"] : 1;
	$current = isset($_POST["current"]) ? $_POST["current"] : 1;
	$dateview = isset($_POST["dateview"]) ? $_POST["dateview"] : FALSE;
	$defined = $_POST["defined"];
	$type = isset($_POST["type"]) ? $_POST["type"] : 'NEXT';
	$paginate = isset($_POST["paginate"]) ? $_POST["paginate"] : FALSE;
	if ($paginate) $number_page = 0;
	else $number_page = $post_per_page;
	$json = eventissimo_json_events($number_page,$type,$defined);
	$response = json_decode($json);
 	$list = "";
	eventissimo_listEvent($list,$response,$post_per_page,$current,$dateview,$defined,$type,$paginate);
	//ob_clean();
	echo $list;
	die();
}

add_action( 'wp_ajax_eventissimo_listEvent_ajax', 'eventissimo_listEvent_ajax' );

?>