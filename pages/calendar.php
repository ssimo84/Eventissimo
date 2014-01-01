<?php
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
$url_include = $parse_uri[0] . 'wp-load.php';
if(@file_get_contents($url_include)){
	require_once($url_include);	
}
global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header,$wp_locale;

$dayRepeat = explode (",",$_GET["weekdayrepeat"]);


if (is_array($dayRepeat))
	$arrayDay = getArrayDateRepeat($_GET["dataBegin"],$_GET["dataUntil"],$_GET["weekdayrepeat"],$_GET["monthdayrepeat"],$_GET["typeRepeating"]);
else
	$arrayDay = array(); 
$title = $_GET["title"];
if (trim($title)==""){
	$title = __("no title","eventissimo");
}

?>

<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title>Calendar <?php echo $title;?></title>
	<?php
	wp_head();
	?>
</head>
<body style="text-align:center;background-color:#FFF">  
	<?php
	
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
	?>

    
   
</body>
</html>