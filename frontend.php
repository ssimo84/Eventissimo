<?php
/*
Function plugin Frontend called from shortcode.php
*/

//Calendar
function eventissimo_frontend_calendar(){
	$arrayCalendarAll = eventissimo_json_events_fullcalendar();
	return eventissimo_stamp_calendar($arrayCalendarAll,"#069c88","month,basicWeek");
}


function eventissimo_frontend_list($post_per_page,$dateview=FALSE,$type='NEXT',$paginate=FALSE,$view='LIST',$defined=''){

	//type => next, prev, null (all events)
	if ($paginate) $number_page = 0;
	else $number_page = $post_per_page;

	$json = eventissimo_json_events($number_page,$type,$defined);
	$response = json_decode($json);
	if ($paginate) {
		$count = count($response);
		$number_page = ceil($count/$post_per_page);
	}
	$time = rand(5, time());
	$list  = "<div id='list_events' ";
	if ($view=="LIST") $list .= " class='list_" . $time . " listevents'";
	if ($view=="BLOCK") $list .= " class='list_" . $time . " blockevents'";
	$list .=">";
	$current = 1;
	include ("call_function/listEvents.php");
	$list  .= "</div><div class='eventsLoading' id='load_" .  $time . "'></div><div style='clear:both;'></div>";

		
	if ($paginate){
		$list .="<div class='pagination' id='pag_"  . $time . "'></div>";
		$list .= '
		<script type="text/javascript">
		
        var options = {
            totalPages: ' . $number_page . ',
			tooltipTitles: function (type, page, current) {
				switch (type) {
					case "first":
						return "' . __("Go to first page","eventissimo") . '";
					case "prev":
						return "' . __("Go to previus page","eventissimo") . '";
					case "next":
						return "' . __("Go to next page","eventissimo") . '";
					case "last":
						return "' . __("Go to last page","eventissimo") . '";
					case "page":
						return "' . __("Go to page","eventissimo") . ' " + page;
				}
            },
			
			onPageClicked: function(e,originalEvent,type,page){

				jQuery.ajax({
					url:   url_pathPlugin  + "call_function/listEvents.php",
					dataType: "html",
					data: {
						callAjax:true,
						post_per_page:' . $post_per_page  . ',
						current:page,
						dateview:' . $dateview  . ',
						defined:"' . $defined  . '",
						type:"' . $type  . '",
						paginate:' . $paginate  . '
					},
					type: "POST",
					
					beforeSend: function(response) {
						jQuery("#load_' . $time .  '").show();
					},
					success: function(response) {
						jQuery("#load_' . $time .  '").hide();
						jQuery(".list_' . $time .  '").html(response);
					},
					
					error: function(response) {	
						console.log(response);
					}
				});

             
			}
			
        }

        jQuery("#pag_' . $time .  '").bootstrapPaginator(options);
		
		</script>
		';	
	}
	
	return $list;
}

?>
