<?php
/**
* Box Generator Data Event for WordPress
*
*
* @author: Digitalissimo <simona@digitalissimo.it>
* @version: 1.0
*
*/


function eventissimo_add_eventLocation() {
    add_meta_box(
        'eventLocation',
        __( 'Event Location', 'eventissimo' ),
        'eventissimo_eventLocation_field',
        'events'
    );

}
add_action( 'add_meta_boxes', 'eventissimo_add_eventLocation' );


function eventissimo_eventLocation_field( $post ) {
	global $wp_locale;

	$values = get_post_custom( $post->ID );  
	$city = isset( $values['city'] ) ? esc_attr( $values['city'][0] ) : get_option('wp_locationCityDefault');
	$address = isset( $values['city'] ) ? esc_attr( $values['address'][0] ) : get_option('wp_locationAdressDefault');
	
	$urlLocationMaps = isset( $values['mapsurlLocation'] ) ? esc_attr( $values['mapsurlLocation'][0] ) : "";
	
	$latlongMaps = isset( $values['latlongMaps'] ) ? esc_attr( $values['latlongMaps'][0] ) : "";
	$latlongMaps = strstr($latlongMaps,"undefined") ? "" : $latlongMaps;
	$valueMapsDefault = $city  . "," . $address ;
	
	?>

	    <h4><?php _e("Location","eventissimo") ?></h4>  

    <table id='location'>
    
    
	    <tr style='display:block;'>
		    
            <td colspan="2"></td>
            
            <td width="40%">
			    
                <table>
							
                        <tr valign="top">
                        	<th scope="row"><?php _e("City","eventissimo");?></th>
                        	<td><input type="text" id="city" name="city" value="<?php echo $city; ?>" /></td>
                        </tr>
                         
                        <tr valign="top">
                       	 	<th scope="row"><?php _e("Address","eventissimo");?></th>
                        	<td><textarea id="address" name="address"><?php echo $address; ?></textarea>
                            <br/><strong id="updateMaps"><?php _e("Update Maps","eventissimo")?></strong>
                            
                        	</td>
                        </tr>
                        
                        
                    
                    </table>

		    </td>
		    
            <script>
			jQuery(document).ready(function() {
				
				var address = jQuery("#city").val() + " " + jQuery("#address").val();
				getLangLat(address,jQuery("#latlongMaps").val(),maxZoom);

				
				jQuery("#updateMaps").click(function(){
					var address = jQuery("#city").val() + " " + jQuery("#address").val();
					getLangLat(address,"",maxZoom);
				});
				
				
			});
		</script>
		    <td width="60%">
           		 <h5><?php _e("Move the placemark in the desired location on the map","eventissimo");?></h5>
                 <input type="hidden" name="latlongMaps" id="latlongMaps" value="<?php echo $latlongMaps; ?>"/>
            	<div id="maps"></div>
            
            </td>
        
        </tr>
     
            
        </tbody>
        
    </table>

<?php          
}


add_action( 'save_post', 'eventissimo_save_eventLocation' );
function eventissimo_save_eventLocation()  {

	global $post;
	if (isset($post->ID)) {
		$status = get_post_status($post->ID);
		if ( $status!="trash" && isset($_POST["isForm"])) {	
			update_post_meta($post->ID, "city", isset( $_POST['city'] ) ? esc_attr( $_POST['city'] ) : "");
			update_post_meta($post->ID, "address", isset( $_POST['address'] ) ? esc_attr( $_POST['address'] ) : "");
			update_post_meta($post->ID, "urlLocationMaps", isset( $_POST['urlLocationMaps'] ) ? esc_attr( $_POST['urlLocationMaps'] ) : "");
			update_post_meta($post->ID, "latlongMaps", isset( $_POST['latlongMaps'] ) ? esc_attr( $_POST['latlongMaps'] ) : "");
		}
	}
}  
