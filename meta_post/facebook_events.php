<?php
/**
* Box Generator Url & Social Link Event for WordPress
*
*
* @author: Digitalissimo <simona@digitalissimo.it>
* @version: 1.0
*
*/


function eventissimo_add_eventFacebook() {
    add_meta_box(
        'eventFacebook',
        __( 'Link & Social', 'eventissimo' ),
        'eventissimo_eventFacebook_field',
        'events'
    );

}
add_action( 'add_meta_boxes', 'eventissimo_add_eventFacebook' );


function eventissimo_eventFacebook_field( $post ) {
	global $wp_locale;	
	$disabled = "";
	$imagesCreated ="";
	$values = get_post_custom( $post->ID );  
	$idEventFb = isset( $values['idEventFfb'] ) ? esc_attr( $values['idEventFfb'][0] ) : "";
	$urlEventFb = isset( $values['urlEventFB'] ) ? esc_attr( $values['urlEventFB'][0] ) : "";
	$urlEventSite = isset( $values['urlEventSite'] ) ? esc_attr( $values['urlEventSite'][0] ) : "";
	$statusEventFB = isset( $values['statusEventFB'] ) ? esc_attr( $values['statusEventFB'][0] ) : "";
	$pagesFacebookAppId = get_option("wp_fbAppId");
	$pagesFacebookPrivateKey = get_option("wp_fbprivateKey");
	$idAuthorFB = isset( $values['idAuthorFB'] ) ? esc_attr( $values['idAuthorFB'][0] ) : "";

	?>

	 <script>
	 	  jQuery(document).ready(function() {

		  	jQuery('#createAutoFb').change(function(){
				if (jQuery(this).is(":checked")){
					jQuery('#urlFB').attr("disabled","disabled");
				} else
					jQuery('#urlFB').removeAttr("disabled");
			});

			<?php
			
			if (($pagesFacebookAppId!="") && ($pagesFacebookPrivateKey!="")){?>
				checkFBStatus();
		  	<?php
			}
			?>
		  });
	 </script>

	    <h4>
                <?php
				
				

				if (($pagesFacebookAppId!="") && ($pagesFacebookPrivateKey!="")){
				 ?>
					<div id="addfb">
                    <input type="hidden" name="appTokenFb" id="appTokenFb" value=""/>
                    <input type="hidden" name="appTokenUidFb" id="appTokenUidFb" value=""/>
                    <input type="hidden" name="idAuthorFB" id="idAuthorFB" value="<?php echo $idAuthorFB;?>"/>
					<input type="hidden" name="appIdFb" id="appIdFb" value="<?php echo FACEBOOOK_API_KEY;?>"/> 
                    <div id="fb-root"></div>
                 <?php
					if ($idEventFb==""){
						$sameAuthor = TRUE;
						_e("Do you want to allow the plugin to create the event on FACEBOOK automatically?","eventissimo") ?> 
						<input type="checkbox" name="autoFb" id="autoFb" value="Yes"/>
                        
                        
					<?php
					}else {
						$disabled = "disabled='disabled'";
						$sameAuthor = eventissimo_sameauthor($idEventFb,$idAuthorFB);
						if ($sameAuthor){
						
						?> <div id="updatefb"> <?php
						
						_e("This event will be updated automatically on Facebook","eventissimo");
						$imagesCreated = "<a target='_EventFb' href='" . $urlEventFb . "'><img src='" . BASE_URI_IMAGES . "/FB-created.png'></a>";
						?>
						<input type="hidden" name="autoFb" id="autoFb" value="<?php echo $idEventFb;?>"/>
                        <?php
						}
					}
					if ($sameAuthor){
						?>
						<select name="statusEventFB">
						<?php echo createStatusPublicFacebook($statusEventFB);?>
						</select></div>
					<?php
					}
				} else {
					_e("If you would create the event on FACEBOOK automatically update your configuration of Facebook","eventissimo");
					echo ' <a href="edit.php?post_type=events&page=eventissimo_setting"> '; 
					_e("in this page.","eventissimo");
					echo '</a>';	
				}
					
				?>	 
        </h4>  

    <table id='linkEvent'>
    
    
	    <tr>
            <td><label for="urlFB"><?php _e("Link Event Facebook","eventissimo") ?></label></td>
            <td width='80%'>
            	
                <input type="text" style="width:100%" name="urlEventFB" <?php echo $disabled;?> id="urlEventFB" value="<?php echo $urlEventFb; ?>"/> <?php echo $imagesCreated; ?>
            	<input type="hidden" name="idEventFb" id="idEventFb" value="<?php echo $idEventFb; ?>"/>
            </td>         
         </tr>
        
         <tr>
            <td><label for="urlEventSite"><?php _e("Another Link","eventissimo") ?></label></td>
            <td width='80%'><input type="text" style="width:100%" name="urlEventSite" id="urlEventSite" value="<?php echo $urlEventSite; ?>"/></td>
          
          </tr>
          
    </table>

<?php  

        
}

add_action( 'save_post', 'eventissimo_save_eventFacebook' );
function eventissimo_save_eventFacebook()  {

	global $post;
	if (isset($post->ID)){
		$status = get_post_status($post->ID);
		$privacy_type = isset( $_POST['statusEventFB'] ) ? esc_attr( $_POST['statusEventFB'] ) : "";
		if ( $status!="trash" && isset($_POST["isForm"])) {	
			update_post_meta($post->ID, "urlFB", isset( $_POST['urlFB'] ) ? esc_attr( $_POST['urlFB'] ) : "");
			update_post_meta($post->ID, "statusEventFB", isset( $_POST['statusEventFB'] ) ? esc_attr( $_POST['statusEventFB'] ) : "");
		}
		$autoFb = isset( $_POST['autoFb'] ) ? esc_attr( $_POST['autoFb'] ) : 0;
		//Connect to FACEBOOK URL
		switch ($autoFb) {
		
			case ("Yes" || $_POST["autoFb"]>0):
	
				$dateStart = $_POST["data_inizio_yy-mm-dd"];
				$dateEnd = $_POST["data_fine_yy-mm-dd"];
				$hourStart = $_POST["ora_inizio"];
				$hourEnd = $_POST["ora_fine"];
				
				$dateStart = $_POST["data_inizio_yy-mm-dd"];

				$dateEnd = $_POST["data_fine_yy-mm-dd"];

				$hourStart = $_POST["ora_inizio"];

				$hourEnd = $_POST["ora_fine"];

				$offset= get_option('gmt_offset');	
				
				
				$createTimeZoneStart = new DateTime($dateStart, new DateTimeZone(get_option('timezone_string')));

				$createTimeZoneEnd = new DateTime($dateEnd, new DateTimeZone(get_option('timezone_string')));

				$description = str_replace("\\","",$_POST["descrizione"]) . "\n\nPowered by " . get_site_url();

				$offsetStart = $createTimeZoneStart->getOffset();

				$offsetStart = eventissimo_offsetTime ($offsetStart);

				$offsetEnd = $createTimeZoneEnd->getOffset();

				$offsetEnd = eventissimo_offsetTime ($offsetEnd);

				if ($hourStart !=""){
					$hourStart = date("H:i", strtotime($hourStart));
					$start_event = $dateStart . "T" . $hourStart . ":00" . $offsetStart;
				}
				else
					$start_event = $dateStart;

				if ($hourEnd !=""){
					$hourEnd = date("H:i", strtotime($hourEnd));
					$end_event = $dateEnd . "T" . $hourEnd . ":00" . $offsetEnd;
				}
				else 
					$end_event = $dateEnd;

				if ($end_event==$start_event) $end_event="";
					
				$city = $_POST["city"]!="" ? $_POST["city"] : "";
				$address = $_POST["address"] !="" ? $_POST["address"] : "";
				
				if ($idAuthorFB!="")
					update_post_meta($post->ID, "idAuthorFB",$_POST["idAuthorFB"]);
				else
					update_post_meta($post->ID, "idAuthorFB",$_POST["appTokenUidFb"]);
					
				if ($_POST["autoFb"]=="Yes"){
					//Create Events
					$idEventFfb = eventissimo_eventsConnectFacebook($post->post_title, $post->guid,$description,$start_event,$end_event,$city,$address,$post->ID,$_POST["appTokenFb"],$_POST["appTokenUidFb"],$privacy_type);
					update_post_meta($post->ID, "idEventFfb", $idEventFfb);
					update_post_meta($post->ID, "urlEventFB", "https://www.facebook.com/events/" .$idEventFfb  . "/");
				} else {
					//Modify Events
					$ok = eventissimo_eventsConnectFacebook($post->post_title, $post->guid,$description,$start_event,$end_event,$city,$address,$post->ID,$_POST["appTokenFb"],$_POST["appTokenUidFb"],$privacy_type,$_POST["autoFb"]);
				}
			break;	
	
			default:
				if (isset($post->ID))
					update_post_meta($post->ID, "urlEventFB", isset( $_POST['urlEventFB'] ) ? esc_attr( $_POST['urlEventFB'] ) : "");
		}
		if (isset($post->ID))
			update_post_meta($post->ID, "urlEventSite", isset( $_POST['urlEventSite'] ) ? esc_attr( $_POST['urlEventSite'] ) : "");
	
	}  
}	
function eventissimo_onlyInsert($new_status, $old_status=null, $post) {
	$pagesFacebookAppId = get_option("wp_fbAppId");
	$pagesFacebookPrivateKey = get_option("wp_fbprivateKey");
	if (($old_status === "auto-draft" || $old_status === "new" || $old_status === "inherit" || $old_status === "draft") && ($new_status == "publish")){
		if ((isset($_POST["appTokenFb"])) && ($pagesFacebookAppId!="") && ($pagesFacebookPrivateKey!="")){
			if (FACEBOOK_PUBLICATEFEEDFB=="user"){
				$post_url = get_permalink($post->ID);
				$title = $post->post_title;
				$description = $_POST["descrizione"];
				$message = __("I have create a new event","eventissimo");
				eventissimo_postEventFacebook($_POST["appTokenFb"],$post_url,$description,$title,$_POST["appTokenUidFb"],$message);
			}
		} 
	}
}
add_action( 'transition_post_status', 'eventissimo_onlyInsert', 10, 3);