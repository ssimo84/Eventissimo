<?php
/*
Function plugin Frontend called from shortcode.php
*/

//Calendar
function eventissimo_frontend_calendar($backcolorHEX,$textColorHEX){
	$arrayCalendarAll = eventissimo_json_events_fullcalendar();
	return  eventissimo_stamp_calendar($arrayCalendarAll,$backcolorHEX,$textColorHEX,"month,basicWeek") . 
		"<script>
			jQuery(function() {
					callCalendar();
			});
		</script>";
}


function eventissimo_frontend_list($post_per_page,$dateview=FALSE,$type='NEXT',$paginate=FALSE,$view='LIST',$defined=''){

	//type => next, prev, null (all events)
	if ($paginate) $number_page = 0;
	else $number_page = $post_per_page;

	$json = eventissimo_json_events($number_page,$type,$defined);
	$response = json_decode($json);

	if ($paginate) {
		$count = count($response);
		$number_pages = ceil($count/$post_per_page);
	}
	$time = rand(5, time());
	$list  = "<div id='list_events' ";
	if ($view=="LIST") $list .= " class='list_" . $time . " listevents'";
	if ($view=="BLOCK") $list .= " class='list_" . $time . " blockevents'";
	$list .=">";
	$current = 1;
	eventissimo_listEvent($list,$response,$post_per_page,$current,$dateview,$defined,$type,$paginate);
	$list  .= "</div><div class='eventsLoading' id='load_" .  $time . "'></div><div style='clear:both;'></div>";

		
	if (($paginate)  && ($number_pages>1)){
		$list .="<div class='pagination' id='pag_"  . $time . "'></div>";
		$list .= '
		<script type="text/javascript">
		
        var options = {
            totalPages: ' . $number_pages . ',
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
				var ajaxurl = "' . admin_url('admin-ajax.php') . '";
				jQuery.ajax({
					url:   ajaxurl,
					type: "POST",
					dataType: "html",
					data: {
						action: "eventissimo_listEvent_ajax",
						post_per_page:' . $post_per_page  . ',
						current:page,
						dateview:' . $dateview  . ',
						defined:"' . $defined  . '",
						type:"' . $type  . '",
						paginate:' . $paginate  . '
					},
					
					beforeSend: function() {
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


function eventissimo_frontend_taxonomy($numview,$type){
	$categories = get_terms($type, 'orderby=count&hide_empty=1' );
	 $count = count($categories);
	 if ( $count > 0 ){
		 echo "<ul>";
		 foreach ( $categories as $term ) {
			 
		   if ($term->parent==0){	 
			   echo "<li><a href='" . get_term_link($term->slug,$type) . "'>" . $term->name . "</a>";
			   
			   $args = array(
					'parent' => $term->term_id,
					'orderby' => 'slug',
					'hide_empty' => true
				);
				$child_terms = get_terms( $type, $args );
				$count = $term->count;
				$children = '<ul>';
				foreach ($child_terms as $childterm) {
					$children .= '<li><a href="' . get_term_link( $childterm->slug,$type) . '">' . $childterm->name . '</a>';
					if ($numview) $children .= " (" . $childterm->count . ")";
					$children .= '</li>';
					$count +=    $childterm->count;
				}
				$children .= '</ul>';
		   		if ($numview) echo " (" . $count . ")";
			    echo $children;
			  
		   		echo "</li>";
		   }
		 }
		 echo "</ul>";
	 }
}

?>
