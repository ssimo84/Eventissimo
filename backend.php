<?php
/*
Function plugin Backend called from shortcode.php
*/
ob_start();
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
include_once(BASE_URL . "/plugin/facebook/sdk-facebook/facebook.php");

class simplissimo_list_fbevents extends WP_List_Table {

	function get_columns(){
	  $columns = array(
		'eventtitle' => __('Title','eventissimo'),
		'eventdate'    => __('Date','eventissimo'),
		'eventlocation'      => __('Location','eventissimo'),
	  );
	  return $columns;
	}

	function get_array(){
	
		$facebook = new Facebook(array(
			'appId'  => FACEBOOOK_API_KEY,
			'secret' => FACEBOOK_SECRET_KEY,
			 'cookie' => TRUE,
			 'allowSignedRequest' => false 
		));
		$accessToken = $facebook->getAccessToken();
		$user_profile = $facebook->api('/me','GET');

		$my_events = $facebook->api('/me/events','GET');
		
		$list_events = array();
		
		$events = $my_events["data"];
		foreach ($events as $event){
			
			$dataInizio =   date_i18n(get_option("date_format"),strtotime($event["start_time"]));
			$oraInizioarr =   explode("T",$event["start_time"]);
			$oraInizio = explode(":",$oraInizioarr[1]);
			if ($oraInizio[0] !="")
				$hourBegin = $oraInizio[0] . ":" . $oraInizio[1];
			
			$dataFine = date_i18n(get_option("date_format"),strtotime($event["end_time"]));
			$oraFinearr =   explode("T",$event["end_time"]);
			$oraFine =explode(":",$oraFinearr[1]);
			if ($oraFine[0] !="")
			$hourEnd = $oraFine[0] . ":" . $oraFine[1];
			
			$stampDate = eventissimo_text_date($dataInizio,$dataFine,$hourBegin,$hourEnd);
			
			//Filter Current Id (if exist not view)
			$query = new WP_Query( array( 'meta_key' => 'idEventFfb', 'meta_value' => $event["id"],'post_type' => 'events'));
			
			if (!$query->have_posts()){

				$list_events[]=array(
					'ID'=> $event["id"],
					'eventtitle' => $event["name"],
					'eventdate' => $stampDate,
					'eventlocation' =>		$event["location"],			
				);
			}
		}

		return $list_events;
	}
	
	function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="events[]" value="%s" />', $item['ID']
        );    
    }
	

	public function process_bulk_action() {

        // security check!
        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
            $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )
                wp_die( 'Nope! Security check failed!' );

        }
		$entry_id = ( is_array( $_REQUEST['events'] ) ) ? $_REQUEST['events'] : array( $_REQUEST['events'] );

        $action = $this->current_action();

        switch ( $action ) {

            
            case 'import':
			
				$facebook = new Facebook(array(
					'appId'  => FACEBOOOK_API_KEY,
					'secret' => FACEBOOK_SECRET_KEY,
					 'cookie' => TRUE,
					 'allowSignedRequest' => false 
				));
				$accessToken = $facebook->getAccessToken();
			
				foreach ( $entry_id as $id ) {
					
					$randomColor = '#' . strtoupper(substr(md5(rand()), 0, 6));

					$current_event = $facebook->api('/' . $id,'GET');

					$owner = $current_event["owner"]["id"];
					$id = $current_event["id"];
					$title = $current_event["name"];
					$description = $current_event["description"];
					$privacy = $current_event["privacy"];
					$location = $current_event["location"];
					$venue = $current_event["venue"];
					$dateBegin =  strtotime($current_event["start_time"]);
					
					$oraInizioarr =   explode("T",$current_event["start_time"]);
					$oraInizio = explode(":",$oraInizioarr[1]);
					if ($oraInizio[0] !="")
						$hourBegin = $oraInizio[0] . ":" . $oraInizio[1];
					
					if (isset($current_event["end_time"])){
						$dateEnd = strtotime($current_event["end_time"]);
						$oraFinearr =   explode("T",$current_event["end_time"]);
						$oraFine =explode(":",$oraFinearr[1]);
						if ($oraFine[0] !="")
						$hourEnd = $oraFine[0] . ":" . $oraFine[1];
					} else {
						$dateEnd = $dateBegin;
					}
					
					
					$post = array(
						  'post_content'   => "",
						  'post_name'      => "fb_" . $id,
						  'post_title'     => $title ,
						  'post_status'    => 'publish',
						  'post_type'      => "events",
						  'comment_status' => 'closed',
						  'post_category'  => ''
						);  
					
					 $the_post_id = wp_insert_post( $post);
					 
					 add_post_meta($the_post_id, 'descrizione', $description, true);
					 add_post_meta($the_post_id, 'statusEventFB', $privacy, true);
					 add_post_meta($the_post_id, 'idEventFfb', $id, true);
					 add_post_meta($the_post_id, 'urlEventFB', "https://www.facebook.com/" . $id, true);
					 add_post_meta($the_post_id, 'idAuthorFB', $owner, true);
					 add_post_meta($the_post_id, 'data_inizio', $dateBegin, true);
					 add_post_meta($the_post_id, 'data_fine', $dateEnd, true);
					 add_post_meta($the_post_id, 'ora_inizio', $hourBegin, true);
					 add_post_meta($the_post_id, 'ora_fine', $hourEnd, true);
					 add_post_meta($the_post_id, 'city', $location, true);
					 add_post_meta($the_post_id, 'address', $venue["address"] . " " . $venue["street"], true);
					 add_post_meta($the_post_id, 'latlongMaps', $venue["latitude"] . "," . $venue["longitude"], true);
		
					
					echo '<div class="updated">
						<p>' . sprintf(__( 'Import event %s successful', 'eventissimo' ), $title) . '| <a href="post.php?post=' . $the_post_id . '&action=edit">' . __("Edit") .  '</a></p>
					</div>';
					
				}		
           break;

            default:
                // do nothing or something else
                return;
                break;
        }

        return;
    }
	function prepare_items() {
	  $columns = $this->get_columns();
	  $hidden = array();
	  $sortable = $this->get_sortable_columns();
	  $this->process_bulk_action();
	  $this->_column_headers = array($columns, $hidden, $sortable);
	  
	  $array_list = $this->get_array();
	  
	  $this->items = $array_list ;
	  
	  $per_page = 5;
	  $current_page = $this->get_pagenum();
	  $total_items = count($array_list );

	  // only ncessary because we have sample data
	  $this->found_data = array_slice($array_list ,(($current_page-1)*$per_page),$per_page);

	  $this->set_pagination_args( array(
		'total_items' => $total_items,                  //WE have to calculate the total number of items
		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
	  ) );
	  $this->items = $this->found_data;
	  
	}
	
	function column_default( $item, $column_name ) {
		switch( $column_name ) { 
			case 'eventtitle':
			case 'eventdate':
			case 'eventlocation':
			  return $item[ $column_name ];
			default:
			  return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	
	function column_eventtitle($item) {
  $actions = array(
            'link'      => '<a target="_nfv" href="https://www.facebook.com/' . $item['ID'] . '">Link</a>',
            'import'    => sprintf('<a href="?post_type=events&page=%s&action=%s&events=%s">' . __("Import","eventissimo") .'</a>',$_REQUEST['page'],'import',$item['ID']),
        );

  return sprintf('%1$s %2$s', $item['eventtitle'], $this->row_actions($actions) );
}
	
	function get_sortable_columns() {
	  $sortable_columns = array(
		'eventtitle'  => array('eventtitle',false),
		'eventdate' => array('eventdate',false),
		'eventlocation'   => array('eventlocation',false)
	  );
	  return $sortable_columns;
	}
	
	
	
}

function eventissimo_fbupdating(){

	echo '<input type="hidden" name="appTokenFb" id="appTokenFb" value=""/>
	<input type="hidden" name="appTokenUidFb" id="appTokenUidFb" value=""/>
	<input type="hidden" name="idAuthorFB" id="idAuthorFB" value="' . $idAuthorFB . '"/>
	<input type="hidden" name="appIdFb" id="appIdFb" value="' . FACEBOOOK_API_KEY . '"/> 
	<div id="fb-root"></div>';

	echo '
	<script>
		jQuery(document).ready(function() {
			checkFBStatus();  	
		});
	</script>';

	echo '
	<div class="wrap">
		<h2>' . __("Import you events from Facebook","eventissimo") . '</h2>';

	$myListTable = new simplissimo_list_fbevents();
	
	$myListTable->prepare_items(); 
	$myListTable->display();
	echo '</div>';
	
}


?>