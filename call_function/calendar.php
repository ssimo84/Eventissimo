<?php

function eventissimo_calendar(){
	global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header,$wp_locale;

	if (!is_array($_POST["weekdayrepeat"]))	
		$dayRepeat = explode (",",$_POST["weekdayrepeat"]);
	else
		$dayRepeat =$_POST["weekdayrepeat"];
	
	if (!is_array($_POST["monthdayrepeat"]))		
		$_POST["monthdayrepeat"] = explode (",",	$_POST["monthdayrepeat"]);

	if (is_array($dayRepeat))
		$arrayDay = getArrayDateRepeat($_POST["dataBegin"],$_POST["dataUntil"],$dayRepeat,$_POST["monthdayrepeat"],$_POST["typeRepeating"]);
	else
		$arrayDay = array(); 
	$title = $_POST["title"];
	if (trim($title)==""){
		$title = __("no title","eventissimo");
	}

	if (count($arrayDay)>0){
		$array = array();
		foreach($arrayDay as $timestamp){
			$array[] = array(
				"title"=>$title,
				"start"=>date('Y-m-d',$timestamp),
				"end" => ""
			);
		
		}
		echo eventissimo_stamp_calendar($array,"#069c88");
		
	} else {
	
		_e ("This events have not date repeat","eventissimo");
	}
	die();
}

add_action( 'wp_ajax_eventissimo_calendar', 'eventissimo_calendar' );

?>
