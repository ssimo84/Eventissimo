<?php
if (isset($_POST["callAjax"])){
	header("Access-Control-Allow-Origin: *");
	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	$url_include = $parse_uri[0] . 'wp-load.php';

	if(@file_get_contents($url_include)){
		require_once($url_include);
		
	}
	require_once(BASE_URL . "/function.php");
	$list = "";

	$post_per_page = isset($_POST["post_per_page"]) ? $_POST["post_per_page"] : 1;
	$current = isset($_POST["current"]) ? $_POST["current"] : 1;
	$dateview = isset($_POST["dateview"]) ? $_POST["dateview"] : FALSE;
	$type = isset($_POST["type"]) ? $_POST["type"] : 'NEXT';
	$paginate = isset($_POST["paginate"]) ? $_POST["paginate"] : FALSE;
	if ($paginate) $number_page = 0;
	else $number_page = $post_per_page;
	$json = eventissimo_json_events($number_page,$type);
	$response = json_decode($json);
}
if ($paginate) {
	$new_array = eventissimo_paginate($response,$current,$post_per_page);
	$response = array();
	$response = $new_array;
}
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

if (isset($_POST["callAjax"])){
	echo $list;
	die();
}
?>