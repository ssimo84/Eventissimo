<?php
/*
General Function Front-end and administration
*/
function eventissimo_pageactual(){
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
	$host     = $_SERVER['HTTP_HOST'];
	$script   = $_SERVER['SCRIPT_NAME'];
	$params   = $_SERVER['QUERY_STRING'];
	$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	return $currentUrl;
}


function eventissimo_linkadded_protocol($url,$protocol="http"){
	if (!preg_match("/^(http|ftp|https):/", $url)) {
	   $url = $protocol . '://'.$url;
	}
    return $url;
}

function eventissimo_settstamp($dayRepeatSelect){
	global $wp_locale;
	$option ="";
	foreach ($wp_locale->weekday_abbrev as $idDay=>$day){
		$option .= "<div class='blockCheched'><input type='checkbox' class='dayRepeat' name='dayRepeatSelect[]' value='" . $day . "'";
		if ($dayRepeatSelect){
			if (in_array($day,$dayRepeatSelect)) $option .= "  checked='checked' ";
		}
		$option .= "> " . $idDay . "</div>";
		
	}
	return $option;
}

function eventissimo_monthtstamp($monthSelect){
	global $wp_locale;
	$option ='<div class="clear"></div>';
	foreach ($wp_locale->month as $month){
		$option .= "<div class='blockCheched'><input type='checkbox' class='dayRepeatMount' name='dayRepeatMount[]' value='" . $month . "'";
		if ($monthSelect){
			if (in_array($month,$monthSelect)) $option .= " checked='checked' ";
		}
		$option .= "> " . $month . "</div>";
	}
	return $option;
}

function eventissimo_weekstamp($weekSelect,$weekNumber){
	$option ='<div class="clear"></div>';
	foreach ($weekNumber as $nWeek=>$string){
		
		$option .= "<div class='blockCheched'><input type='checkbox' class='weekSelect' name='dayRepeatWeek[]' value='" . $nWeek . "'";
		if ($weekSelect){
			if (in_array($nWeek,$weekSelect)) $option .= " checked='checked' ";
		}
		$option .= "> " . $string . "</div>";
	}
	return $option;
}

function eventissimo_yesno($value){
	$option .= "<option value=''";
		if ($value=="") $option .= " selected='selected' ";
		$option .= ">"  . __("No") . "</option>";
	$option .= "<option value='true'";
		if ($value=="true") $option .= " selected='selected' ";
		$option .= ">" . __("Yes") . "</option>";
	return $option;
}

function eventissimo_strip_array_indices( $ArrayToStrip ) {
    foreach( $ArrayToStrip as $objArrayItem) {
        $NewArray[] =  $objArrayItem;
    }
 
    return( $NewArray );
}

function eventissimo_date_format_wp_to_js( $sFormat ) {
    $chars = array( 
        // Day
        'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
        // Month 
        'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M', 
        // Year 
        'Y' => 'yy', 'y' => 'y', 
    ); 

    return strtr((string)$sFormat, $chars); 
}


function eventissimo_time_format_wp_to_js( $sFormat ) {
    $chars = array( 
        'G' => 'HH', 'g' => 'hh',
		'A' => 'p',  'i' => 'mm',
		
    ); 

    return strtr((string)$sFormat, $chars); 
}



function eventissimo_offsetTime($offsetWP){

$offsetHours = round(abs($offsetWP)/3600); 
$offsetMinutes = round((abs($offsetWP) - $offsetHours * 3600) / 60); 
$offsetString = ($offsetWP < 0 ? '-' : '+') 
			. ($offsetHours < 10 ? '0' : '') . $offsetHours 
			. ':' 
			. ($offsetMinutes < 10 ? '0' : '') . $offsetMinutes; 
return $offsetString;
}

//If events is repeat, check date prox
function eventissimo_first_date_repeat(&$dataInizio,&$dataFine,$dayRepeatDate){

	if ((count($dayRepeatDate)>0) && (time()>$dataInizio)){	
		foreach ($dayRepeatDate as $value){
			if (time()<=$value) {
				$dataInizio = $value;
				$dataFine = $value;		
				return;
			}
		}
	}
}

/**
 * Sort a 2 dimensional array based on 1 or more indexes.
 * 
 * msort() can be used to sort a rowset like array on one or more
 * 'headers' (keys in the 2th array).
 * 
 * @param array        $array      The array to sort.
 * @param string|array $key        The index(es) to sort the array on.
 * @param int          $sort_flags The optional parameter to modify the sorting 
 *                                 behavior. This parameter does not work when 
 *                                 supplying an array in the $key parameter. 
 * 
 * @return array The sorted array.
 */
function eventissimo_msort($array, $key, $sort_flags = SORT_REGULAR) {
    if (is_array($array) && count($array) > 0) {
        if (!empty($key)) {
            $mapping = array();
            foreach ($array as $k => $v) {
                $sort_key = '';
                if (!is_array($key)) {
                    $sort_key = $v[$key];
                } else {
                    // @TODO This should be fixed, now it will be sorted as string
                    foreach ($key as $key_key) {
                        $sort_key .= $v[$key_key];
                    }
                    $sort_flags = SORT_STRING;
                }
                $mapping[$k] = $sort_key;
            }
            asort($mapping, $sort_flags);
            $sorted = array();
            foreach ($mapping as $k => $v) {
                $sorted[] = $array[$k];
            }
            return $sorted;
        }
    }
    return $array;
}


function eventissimo_json_events($post_per_page=0,$type='null',$defined="",$order="ASC"){
	$args = array( 'post_type' => 'events');
	$args["post_per_page"] = -1;
	$args["post_status"] = "publish";
	$loop =  get_posts( $args );
	
	$json_data = array();
	global $upload_dir;
	foreach ( $loop as $post ) : setup_postdata( $post ); 
		$id_events = $post->ID;
		$post_thumbnail_url = get_the_post_thumbnail( $id_events, "thumbnail");
		$post_cover_url = get_the_post_thumbnail( $id_events, "fb_cover_image");
		$post_large_url = get_the_post_thumbnail( $id_events, "large");
		$typeEvents = wp_get_post_terms( $id_events, 'typeEvents');
		$eventscategories = wp_get_post_terms( $id_events, 'eventscategories');
		$dataBegin = get_post_meta($id_events, 'data_inizio', true)!="" ? get_post_meta($id_events, 'data_inizio', true) : "";
		$json_data[] = array(
			"id" => 	$id_events,
			"classname" => 	$id_events,
			"randomColor" => 	get_post_meta($id_events, 'colorRandom', true),
			"id_author" => 	$post->post_author,
			"title" => 	$post->post_title,
			"url" => get_permalink($id_events),	
			"thumbs" => $post_thumbnail_url,
			"cover" => $post_cover_url,
			"coverBig" => $post_large_url,
			"types" => $typeEvents,	
			"categories" => $eventscategories,					
			//Date Is Timestamp
			"date_begin" => $dataBegin,
			"date_end" => get_post_meta($id_events, 'data_fine', true)!="" ? get_post_meta($id_events, 'data_fine', true) : "",
			//Hour
			"hour_begin" => get_post_meta($id_events, 'ora_inizio', true)!="" ? get_post_meta($id_events, 'ora_inizio', true) : "",
			"hour_end" => get_post_meta($id_events, 'ora_fine', true)!="" ? get_post_meta($id_events, 'ora_fine', true) : "",
		);

		//If events is repeat
		$dayRepeatSelect = get_post_meta($id_events, 'allDateRepeat', true)!="" ? get_post_meta($id_events, 'allDateRepeat', true) : "";
		$data_repeat = unserialize($dayRepeatSelect); 

		if (is_array($data_repeat)){
			$i=0;
			foreach ($data_repeat as $timestamp){
				$args = array(
					'date_begin' => $timestamp, 
					'date_end' =>  $timestamp
				);
				$args["hour_begin"] = get_post_meta($id_events, 'ora_inizio', true)!="" ? get_post_meta($id_events, 'ora_inizio', true) : "";
				$args["hour_end"] = get_post_meta($id_events, 'ora_fine', true)!="" ? get_post_meta($id_events, 'ora_fine', true) : "";
				$url = add_query_arg( $args, get_permalink($id_events));
				if ($dataBegin!=$timestamp) {
					$json_data[] = array(
						"id" => 	$id_events . "-" . $i,
						"classname" => 	$id_events,
						"title" => 	$post->post_title,
						"url" => $url,
						"randomColor" => 	get_post_meta($id_events, 'colorRandom', true),
						"id_author" => 	$post->post_author,
						"thumbs" => $post_thumbnail_url,
						"cover" => $post_cover_url,
						"coverBig" => $post_large_url,
								//Date Is Timestamp
						"date_begin" => $timestamp,
						"date_end" => $timestamp,
						//Hour
						"hour_begin" => get_post_meta($id_events, 'ora_inizio', true)!="" ? get_post_meta($id_events, 'ora_inizio', true) : "",
						"hour_end" => get_post_meta($id_events, 'ora_fine', true)!="" ? get_post_meta($id_events, 'ora_fine', true) : "",
					);
					$i++;
				}
			}
		}	
		
	endforeach;
	$json_data = eventissimo_msort($json_data, array('date_begin'));
	if ($order=="DESC")
		$json_data = array_reverse($json_data);
	
	$new_array = array();
	$i=0;
	
	//IF DEFINED IS MONTH or TODAY
	$timestampOnlyDMY = strtotime(date("Y-m-d"));
	if ((($defined=="MONTH") || ($defined=="TODAY")) || (eventissimo_isValidTimeStamp($defined)) ){
		foreach($json_data as $event){
			
			$data_begin1 = date_i18n(get_option('date_format'),$event["date_begin"]);
			$data_today1 = date_i18n(get_option('date_format'),$timestampOnlyDMY );
			$data_end1 = date_i18n(get_option('date_format'),$event["date_end"]);
	
			if ($defined=='TODAY'){
				$dateB = $event["date_begin"];
				$dateE = $event["date_end"];
			
				if (($dateB==$dateE) && ($data_today1!=$data_begin1))
					continue;
				if (($dateB!=$dateE) && ($timestampOnlyDMY>$dateE)){
					continue;
				}
			}
			
			if (eventissimo_isValidTimeStamp($defined)){
				$data_today1 = date_i18n(get_option('date_format'),$defined );
				$dateB = $event["date_begin"];
				$dateE = $event["date_end"];
			
				if (($dateB==$dateE) && ($data_today1!=$data_begin1))
					continue;
				if (($dateB!=$dateE) && ($defined>$dateE)){
					continue;
				}
			}
			
			if ($defined=='MONTH'){
				$dateB = $event["date_begin"];
				$dateE = $event["date_end"];
				$monthToday =  date('m Y', time());
				$monthB = date('m Y',$dateB);
				$monthE = date('m Y',$dateE);
				if (($monthToday!=$monthB) && ($monthToday!=$monthE)){
					continue;
				}
				
			}
			
			$new_array[$i]=	$event;
			$i++;
		}
		
		$json_data = $new_array;
	
	}

	
	$i=0;
	//NEXT or OLD
	if (($type=="NEXT") || ($type=="OLD")){
		$new_array = array();
		foreach($json_data as $event){
			if ($type=='NEXT'){
				$dateB = $event["date_begin"];
				$dateE = $event["date_end"];
				
				if (($dateB==$dateE) && ($timestampOnlyDMY >$dateB))
					continue;
					
				if (($dateB!=$dateE) ) {
					if ($timestampOnlyDMY >$dateE) 
						//echo $event["title"];
						continue;
				}
			}
			if ($type=='OLD'){
				$dateB = $event["date_begin"];
				$dateE = $event["date_end"];
				if (($dateB==$dateE) && ($timestampOnlyDMY <$dateB))
					continue;
				if (($dateB!=$dateE) ) {
					if ($timestampOnlyDMY <$dateB) 
						continue;
				}
			}
			$new_array[$i]=	$event;
			$i++;
			
		}
		$json_data = $new_array;
	}

	if ($post_per_page>0){
		$i=-1;
		$new_array = array();
		foreach($json_data as $event){
			$i++;
			if ($i==$post_per_page) break;
			$new_array[$i]=	$event;
		}
		$json_data = $new_array;
	}

	return json_encode($json_data);
}

function eventissimo_json_events_fullcalendar($post_per_page=0){
	$json = eventissimo_json_events($post_per_page);
	$array_for_fullcalendar = array();

	//title
	//start y-d-m hh:mm:ss
	//end y-d-m hh:mm:ss
	$response = json_decode($json);
	foreach ($response as $event){
		$array_for_fullcalendar[] = array(
			"title" => $event->title,
			"start" => date_i18n('Y-m-d',$event->date_begin) . " " . $event->hour_begin . ":00",
			"end" => date_i18n('Y-m-d',$event->date_end) . " " . $event->hour_end . ":00",
			"url" => $event->url,
		);
	}
	
	return $array_for_fullcalendar;
	
}

function eventissimo_stamp_calendar($array,$color,$textcolor="#FFFFFF",$type="month"){

	global $wp_locale;
		
	$script_calendar = "<div id='calendar'></div>";
	$script_calendar .= "<script type='text/javascript'>
	
	
			function callCalendar(){
		
				jQuery('#calendar').fullCalendar({

					events: [";
						foreach($array as $event){
							$script_calendar .= "{";
							$script_calendar .= "'title':'" . addslashes ($event["title"]) . "',";
							$script_calendar .= "'start':'" . $event["start"] . "',";
							$script_calendar .= "'end':'" . $event["end"] . "',";
							if (isset($event["url"]))
								$script_calendar .= "'url':'" . $event["url"] . "',";
							$script_calendar .= "},";	
						}
	$script_calendar .= "		],
	
					eventClick: function(event) {
						if (event.url) {
							consol.log(event.url);
							return false;
						}
					},
	
					monthNames:[";

						foreach ($wp_locale->month as $month){
							$script_calendar .=  "'" . strtoupper($month)  . "',";
						}
	$script_calendar .= "		],
					dayNamesShort:["; 
						foreach ($wp_locale->weekday_abbrev as $wd){
							$script_calendar .= "'" . strtoupper($wd)  . "',";
						}
	$script_calendar .= "],
					monthNamesShort:["; 
						foreach ($wp_locale->month_abbrev as $wd){
							$script_calendar .= "'" . strtoupper($wd)  . "',";
						}
	$script_calendar .= "],
					 buttonText: {
						prev: '&lt;',
						next: '&gt;',
						today: '" .  __("Today","eventissimo") . "',
						month: '" .  __("Month","eventissimo") . "',
						week: '" .  __("Week","eventissimo") . "'
					},
					header:{
						
						left:   '" . $type ."',
						center: 'title',
						right:  'today prev,next '

					},
					 eventColor: '" . $color . "',
					 eventTextColor: '" . $textcolor . "',
					 height:350,
					 defaultView:'month',
					 eventRender: function(calev, elt, view) {
						  var ntoday = new Date();
						  if (calev.start.getTime() < ntoday.getTime()){
							elt.addClass('pastEvents');
							elt.children().addClass('pastEvents');
						  }
					 }
				});
			}
	
	</script>";

	return $script_calendar;

}


function eventissimo_paginate($data, $page = 1, $perPage = 2) {
   $x = ($page - 1) * $perPage;
   $z = $page * $perPage;
   $y = ($z > count($data)) ? count($data) : $z;
   $new_data = array();
   for(; $x < $y; $x++) {
      $new_data[] = $data[$x];
   }
   return $new_data;
}

function eventissimo_searchJson($obj, $field, $string) {
	$items = json_decode($obj);
	
    foreach ( $items as $item ) {
		foreach ( $item as $key => $value) {
			if (($key == $field) && ($item->$field==$string)) {
				$array_filter[] = $item;
			}
		}
	}
    return $array_filter;
}


function eventissimo_searchJson_between($obj, $field1, $field2, $string1,$string2,$ext) {
	$items = json_decode($obj);
    foreach ( $items as $item ) {
		if ($ext){
			if (($item->$field1 >= $string1) && ($item->$field2 <= $string2)) {
				$array_filter[] = $item;
			}
		} else {
			if (($item->$field1 <= $string1) && ($item->$field2 >= $string2)) {
				$array_filter[] = $item;
			}
		}
	}
    return $array_filter;
}

function eventissimo_text_date($data_begin,$data_end,$hour_begin,$hour_end){
	if ($data_begin!=$data_end){
		if (trim($hour_begin)!="" && trim($hour_end)!="" ){
			$string[1] = $data_begin;
			$string[2] = $hour_begin;
			$string[3] = $data_end;
			$string[4] = $hour_end;
			$stringdate = __("{1} at {2} until {3} at {4}","eventissimo");
			for($i = 1; $i <= count($string); $i++){
				$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
			}
		} else {
			$string[1] = $data_begin;
			$string[2] = $data_end;
			$stringdate = __("{1} until {2}","eventissimo");
			for($i = 1; $i <= count($string); $i++){
				$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
			}
		}
	} else { 
		$string[1] = $data_begin;
		$string[2] = $hour_begin;
		$string[3] = $hour_end;
		if (trim($hour_begin)!="" && trim($hour_end)!="" ){
			$stringdate = __("{1}  {2} - {3}","eventissimo");
			for($i = 1; $i <= count($string); $i++){
				$stringdate = str_replace('{'.$i.'}', $string[$i], $stringdate);
			}
		}
		else
			$stringdate =$string[1];
	}	
	return $stringdate;
}


function eventissimo_isValidTimeStamp($timestamp,$format='Y-m-d'){
	if (is_numeric($timestamp)) {
		 if(strtotime(date_i18n($format,$timestamp)) == $timestamp) {
			return true;
		} else 
			return false;
	} else {
		return false;
	}
}