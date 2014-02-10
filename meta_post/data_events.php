<?php
/**
* Box Generator Data Event for WordPress
*
*
* @author: Digitalissimo <simona@digitalissimo.it>
* @version: 1.0
*
*/


function eventissimo_add_eventData() {
    add_meta_box(
        'eventsData',
        __( 'Event Data', 'eventissimo' ),
        'eventissimo_eventData_field',
        'events'
    );

}
add_action( 'add_meta_boxes', 'eventissimo_add_eventData' );


function eventissimo_eventData_field( $post ) {
	global $wp_locale;

	$values = get_post_custom( $post->ID );
	$checkedRepeat = "";	
	$post_parent = $post->post_parent;
	$text = isset( $values['descrizione'] ) ? esc_attr( $values['descrizione'][0] ) : "";

	if (isset($values['data_inizio']))
		$data_inizio = ($values['data_inizio'][0]!="") ? date_i18n(get_option('date_format') ,$values['data_inizio'][0] ) : get_the_date(get_option('date_format')); 
	else {
		$data_inizio =get_the_date(get_option('date_format'));
		$values['data_inizio'][0] = time();
	}
	$ora_inizio = isset( $values['ora_inizio'] ) ? esc_attr( $values['ora_inizio'][0] ) : ""; 
	if (isset($values['data_fine']))
		$data_fine = ($values['data_fine'][0]!="") ? date_i18n(get_option('date_format') , $values['data_fine'][0] ) : get_the_date(get_option('date_format')); 
	else {
		$data_fine = get_the_date(get_option('date_format'));
		$values['data_fine'][0] = time();
	}
	$ora_fine = isset( $values['ora_fine'] ) ? esc_attr( $values['ora_fine'][0] ) : ""; 
	$everyYear = isset( $values['EveryYear'] ) ? esc_attr( $values['EveryYear'][0] ) : ""; 
	$dayRepeat = isset( $values['dayRepeat'] ) ? esc_attr($values['dayRepeat'][0] ) : ""; 
	$dayRepeatSelect = isset( $values['dayRepeatSelect'] ) ? unserialize($values['dayRepeatSelect'][0]) : array();
	
	if (count($dayRepeatSelect ))
		$dayRepeatSelect = unserialize($dayRepeatSelect);
	if (isset($values['untilRepeat']) && ($values['untilRepeat'][0]!="")){
		$untilRepeat = date_i18n(get_option('date_format') , $values['untilRepeat'][0] ) ;
		$timestampUntil =  $values['untilRepeat'][0] ;
	} else{
		$untilRepeat =  date_i18n(get_option('date_format') ,strtotime('+1 years', time()));
		$timestampUntil = strtotime('+1 years', time());
	}
		
	$dayRepeatMount = isset( $values['dayRepeatMount'] ) ? unserialize ( $values['dayRepeatMount'][0]) :array();
	if (count($dayRepeatMount ))
		$dayRepeatMount = unserialize($dayRepeatMount);
	$is_parent = false;
	if ($post_parent >0 ) {
		$is_parent = true;
	}

	$randomColor = ($values['colorRandom']!="") ? esc_attr($values['colorRandom'][0]) : '#' . strtoupper(substr(md5(rand()), 0, 6));

	$oraArray = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13", "14","15","16","17","18","19","20","21","22","23");


	if ($data_fine=="") { 
		$display = "display:none";
	} else {
		$display = "display:block";
	}
	
	if ($data_inizio!=$data_fine) { 
		$displayday = "display:none";
	} else {
		$displayday = "display:block";
	}
		
	if (($dayRepeat!="") || ($everyYear!="")) { 
		$checkedRepeat = "checked='checked'";
		$displayuntilRepeat = "display:block";			
	} 
	
	
	if (($dayRepeat=="") && ($everyYear=="")) $displayuntilRepeat = "display:none";
	if ($data_inizio=="")  $displayday = "display:none";	
	if ($dayRepeat=="") $displayuntilRepeat2 = "display:none";	

	?> 
    
    <script>
		
	
	    jQuery(document).ready(function() {
			jQuery('#data_inizio').change(function(){
				jQuery('#ora_inizio').show();
			});
			jQuery('#data_inizio').click(function(){
				jQuery('#ora_inizio').show();
			});
			jQuery('#ora_inizio').change(function(){
				
				
				
				if (!jQuery('#ora_fine').is(":visible")) {
					var oraArray = [<?php 
					foreach ($oraArray as $hour){
						echo "'" .  $hour . "',";
					}
					echo "''";
					?>];
					jQuery('.dayRepeat').show();
					jQuery('.dayRepeat').attr("style","display:block;");
				}
				jQuery('#hourfine').show();
				jQuery('#hourfine').attr("style","display:block;");
			});
			
			jQuery('#ora_inizio').click(function(){
				jQuery("#hourfine").attr("style","display:block;");
			});	
			
			
			
			jQuery('#data_inizio,#data_fine,#ora_inizio,#ora_fine').change(function(){
				viewRepeatForm();
			});
			
			
			jQuery('#chkRepeat').click(function(){
				if (jQuery(this).attr('checked')){
					jQuery('#repeatSelect').show();
					jQuery('#untilRepeatDate').show();
					jQuery('#updateDate').show();
					jQuery('#EveryYear').val("true");

				} else {
					jQuery('#repeatSelect').hide();
					jQuery('.dayRepeatSelect').val("");
					jQuery('#untilRepeatDate').hide();
					jQuery('#chkRepeat').removeAttr('checked');
					jQuery('.dayRepeatMount').removeAttr('checked');
					jQuery('.weekSelect').removeAttr('checked');
					jQuery('.dayRepeat').removeAttr('checked');
					jQuery('#AllCheckedMonth').removeAttr('checked');
					jQuery('#AllCheckedWeek').removeAttr('checked');
					jQuery('#EveryYear').val("");
					jQuery('#updateDate').hide();
						
				}
			});
			
			jQuery('.dayRepeatSelect').change(function(){			
				var dayRepeat = jQuery(this).val();
				if (dayRepeat!=""){
					jQuery('#untilRepeatDate').show();
				}
				jQuery('#EveryYear').val("");
			});
			
			jQuery('#EveryYear').change(function(){
				if (jQuery(this).val()=="true"){
					jQuery('.dayRepeatSelect').val("");
					jQuery('.dayRepeat').removeAttr('checked');
					jQuery('.dayRepeatMount').removeAttr('checked');
					jQuery('.weekSelect').removeAttr('checked');
					jQuery('#AllCheckedMonth').removeAttr('checked');
					jQuery('#AllCheckedWeek').removeAttr('checked');
				}
			});
			
			jQuery('.blockCheched input').click(function(){
				
				if (jQuery(this).is(":checked")){
					jQuery("#EveryYear").val("");
				}
			});
			
			jQuery('#AllCheckedMonth').change(function(){
				if (jQuery(this).is(":checked"))
					jQuery('.dayRepeatMount').attr('checked','checked');
				else
					jQuery('.dayRepeatMount').removeAttr('checked');
			});
			jQuery('#AllCheckedWeek').change(function(){
				if (jQuery(this).is(":checked"))
					jQuery('.weekSelect').attr('checked','checked');
				else
					jQuery('.weekSelect').removeAttr('checked');
			});
			jQuery('.dayRepeatMount').change(function(){
				if (!jQuery(this).is(":checked"))
					jQuery('#AllCheckedMonth').removeAttr('checked');
			});
			jQuery('#updateDate').click(function(){
				
				onlyRepeatDay = jQuery("#EveryYear").val();
				typeRepeating = "onlyDay";
				weekRepeat = "";
				if (onlyRepeatDay=="") {
					typeRepeating = "manyDays";
					var weekRepeat = jQuery('input:checkbox:checked.dayRepeat').map(function () {
					  return this.value;
					}).get();
					var monthRepeat = jQuery('input:checkbox:checked.dayRepeatMount').map(function () {
					  return this.value;
					}).get();
					var nweekRepeat = jQuery('input:checkbox:checked.weekSelect').map(function () {
					  return this.value;
					}).get();
				}
				
				var t = jQuery('#data_inizio').datepicker('getDate');
				var dateBegin = jQuery.datepicker.formatDate('yy-mm-dd', t);
				
				var t = jQuery('#untilRepeat').datepicker('getDate');
				var dataUntil = jQuery.datepicker.formatDate('yy-mm-dd', t);
				
				
				jQuery.ajax({
					url:   admin_ajax,
					type: "POST",
					dataType: "html",
					data: {
						action: "eventissimo_calendar",
						title: jQuery('#title').val(),
						dataBegin: dateBegin,
						dataUntil: dataUntil,
						typeRepeating: typeRepeating,
						weekdayrepeat: weekRepeat,
						monthdayrepeat: monthRepeat,
						nweekdayrepeat: nweekRepeat
					},
					success: function(response) {
						jQuery.colorbox({
							title:'<?php  _e("Calendar","eventissimo");?>',
							html:response,
							width:'80%',
							height:'95%',
							onComplete:function() {
								callCalendar();
							}
						});
					},
					
					error: function(response) {	
						console.log(response);
					}
				});
				
			});
			jQuery.noConflict();
					
		});
	</script>
    
	<p>  
	    <h4><?php _e("Details","eventissimo") ?></h4>  
	    <textarea name="descrizione" style="width:100%;height:150px" id="descrizione" ><?php echo $text; ?></textarea>  
    </p>

	<input type="hidden" name="randomColor" id="randomColor" value="<?php echo $randomColor ?>"/>

    <input type="hidden" name="data_inizio_yy-mm-dd" id="data_inizio_yy-mm-dd" value="<?php echo  date_i18n( "Y-m-d",$values['data_inizio'][0]); ?>"/>
    <input type="hidden" name="data_fine_yy-mm-dd" id="data_fine_yy-mm-dd" value="<?php echo date_i18n( "Y-m-d",$values['data_fine'][0]); ?>"/>
    <input type="hidden" name="untilRepeat_yy-mm-dd" id="untilRepeat_yy-mm-dd" value="<?php echo date_i18n( "Y-m-d",$timestampUntil); ?>"/>
    <table id='day'>
    
    	 <tr><td colspan="3"><h4><?php _e("Date","eventissimo") ?></h4></td></tr>
    
	    <tr style='display:block;'>
		    <td width="60px">
			    <label for="data_inizio"><?php _e("Begin","eventissimo") ?></label>  
		    </td>
		    <td width="60px">
	    	  <input type="text" name="data_inizio" readonly='true' class="required" id="data_inizio" value="<?php echo $data_inizio; ?>"/>
		    </td>  
		    <td width="60px">
				<input  style="<?php echo $display; ?>" readonly='true' class="required"  type="text" name="ora_inizio" id="ora_inizio" value="<?php echo $ora_inizio; ?>"/>
			</td>       
        </tr>
        
        <tr id="hourfine" style="<?php echo $display; ?>">
        	<td width="60px">
			    <label for="data_fine"><?php _e("End","eventissimo") ?></label>  
		    </td>
		    <td width="60px">
	    	  <input type="text" name="data_fine" readonly='true'  class="required" id="data_fine" value="<?php echo $data_fine; ?>"/>
		    </td>
            
		    <td width="60px">
				<input type="text" name="ora_fine" readonly='true'  class="required" id="ora_fine" value="<?php echo $ora_fine; ?>"/>
		    </td>
        </tr>
        
      </table>
      
      <?php if (!$is_parent) { ?>
      
          <table id='dayRepeat' style='<?php echo $displayday; ?>'>
          
          
          
            <thead>
                <tr>
                    <td><h4><?php _e("Repeating event","eventissimo") ?> <input name="dayRepeat" id="chkRepeat" type="checkbox" <?php echo $checkedRepeat;?>/></h4></td>
                </tr>
                <tr>
                    <td  align="center"><strong style="<?php echo $displayuntilRepeat2; ?>" id="updateDate"><?php _e("View all dates","eventissimo") ?></strong></td>
                </tr>
            </thead>
            <tbody id="repeatSelect" style="<?php echo $displayuntilRepeat;?>">
            
                
                <tr id="dayEveryYear" >
                
                    <td style="width=100%">
                        
                        <label for="EveryYear"><?php _e("Every year on the same day","eventissimo") ?></label>  
                        <select   name="EveryYear" id="EveryYear">
                            <?php echo eventissimo_yesno($everyYear); ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><?php _e("OR","eventissimo") ?></h5>
                    </td>
                </tr>
                
                <tr valign="top">
                    
                    <td>
                        
                        <table>
                            <tr valign="top">
                                <td width="20%">
                                    <label for="dayRepeatSelect"><?php _e("Every","eventissimo") ?></label>				
                                </td>
                                <td width="80%">
                                    <?php echo eventissimo_settstamp($dayRepeatSelect); ?>
                                </td>
                            </tr>
                            
                            <tr> 
                                
                                <td valign="top">
                                    <label for="dayRepeatSelect"><?php _e("of mounth","eventissimo"); ?></label>				
                                </td>
                                
                                <td id='untilRepeatDate' style='<?php echo $displayuntilRepeat2; ?>'>
                                    <?php 
									$checked ="";
                                    if (count($dayRepeatMount)==12) $checked= "checked='checked'";
                                    echo "<div class='blockCheched'><b>";
                                    echo "  <input type='checkbox' id='AllCheckedMonth' " . $checked . "> ";
                                    _e("All Month","eventissimo");
                                    echo "</b></div>";
                                    ?>
                                    <?php echo eventissimo_monthtstamp($dayRepeatMount); ?>
                                    
                                   
                                </td>
                                
                                
                
                            </tr>
                              
                    
                            <tr>
                            
                                <td colspan="3">
                                    <?php _e("Until","eventissimo");?> 
                                        <input type="text" readonly='true' name="untilRepeat" id="untilRepeat" value="<?php echo $untilRepeat; ?>"/>
                                </td>
                            </tr>
                        </table>    
                    </td>
                  </tr>
                  
            </tbody>
            
        </table>
		<input type="hidden" name="isForm" id="isForm" value="eventissimo"/>
	
<?php  

	  }
        
}


add_action( 'save_post', 'eventissimo_save_eventData' );
function eventissimo_save_eventData()  {

	global $post;
	
	if (isset($post->ID)){
		
		$status = get_post_status($post->ID);
		if ( $status!="trash" && isset($_POST["isForm"])) {	
			
			
			update_post_meta($post->ID, "colorRandom", $_POST['randomColor']);
			update_post_meta($post->ID, "descrizione", isset( $_POST['descrizione'] ) ? esc_textarea( $_POST['descrizione'] ) : "");
			update_post_meta($post->ID, "data_inizio", strtotime($_POST["data_inizio_yy-mm-dd"]));
			update_post_meta($post->ID, "ora_inizio", $_POST["ora_inizio"]);
			update_post_meta($post->ID, "data_fine", strtotime($_POST["data_fine_yy-mm-dd"]));
			update_post_meta($post->ID, "ora_fine", $_POST["ora_fine"]);
			update_post_meta($post->ID, "dayRepeat",  isset( $_POST['dayRepeat'] ) ? esc_attr( $_POST['dayRepeat'] ) : "");
			update_post_meta($post->ID, "EveryYear", isset( $_POST['EveryYear'] ) ? esc_attr( $_POST['EveryYear'] ) : "");
			$arrayDayRepeat = isset( $_POST['dayRepeatSelect'] ) ? $_POST['dayRepeatSelect'] : array();
			update_post_meta($post->ID, "dayRepeatSelect",serialize($arrayDayRepeat));
			
			$arrayMontRepeat = isset( $_POST['dayRepeatMount'] ) ? $_POST['dayRepeatMount'] : array();
			update_post_meta($post->ID, "dayRepeatMount", serialize($arrayMontRepeat));
			
			if (isset($_POST["dayRepeat"])) {
				$typeRepeating = "onlyDay";
				$arrayDayRepeat = array();
				$arrayMontRepeat = array();
				if ($_POST["EveryYear"]=="") {
					$typeRepeating = "manyDays";
					$arrayDayRepeat = implode(",",$_POST['dayRepeatSelect']);
					$arrayMontRepeat = implode(",",$_POST['dayRepeatMount']);
				}
				
				
				
				$arrayDay= getArrayDateRepeat($_POST["data_inizio_yy-mm-dd"],$_POST["untilRepeat_yy-mm-dd"],$arrayDayRepeat,$arrayMontRepeat,$typeRepeating);
				
				update_post_meta($post->ID, "allDateRepeat", serialize($arrayDay));
			}
			
			update_post_meta($post->ID, "untilRepeat",  strtotime($_POST["untilRepeat_yy-mm-dd"]));
		}
	}
}  
