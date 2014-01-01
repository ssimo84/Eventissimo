<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
$url_include = $parse_uri[0] . 'wp-load.php';
if(@file_get_contents($url_include)){
	require_once($url_include);	
}
$date_begin = $_POST["dataBegin"];
$dateadded = strtotime(date("Y-m-d", strtotime($date_begin)) . $_POST["typeAdded"]);
echo date_i18n(get_option('date_format') , $dateadded);