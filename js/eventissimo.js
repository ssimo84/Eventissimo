function getLangLat(input_address,latLong,maxZoom){

	if (latLong==""){

		var geocoder = new google.maps.Geocoder(); 
		geocoder.geocode( 
		{ address: input_address }, 
		function(results, status) {
			var latLng = [];
			if (status == google.maps.GeocoderStatus.OK) {  
				var lat = results[0].geometry.location.lat();  
				var lng = results[0].geometry.location.lng();
				var latLng = [lat,lng]; 
				passArrayMaps(latLng,maxZoom);
				jQuery("#latlongMaps").val(lat + "," + lng);
			}  
			else {  
				console.log("Google Maps not found address!");
				return false;
			}  
		});
	} else {
		latLng = latLong.split(",");
		passArrayMaps(latLng,maxZoom);
	}
}
function passArrayMaps(latLng,maxZoom){
	getMaps(latLng,maxZoom);
}

function getMaps(latLong,maxZoom){
	jQuery("#maps").gmap3("destroy");
	jQuery("#maps").gmap3({
		 map: {
			options: {
				maxZoom: maxZoom ,
			 	mapTypeControl: true,
				mapTypeControlOptions: {
			  		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
			 
			  	scrollwheel: true,
			   	streetViewControl: false,
			   	navigationControl: true,
			}
		 },
		 marker:{
			//address: "<?php echo $valueMapsDefault;?>",
			latLng: latLong,
			options: {
			 icon: new google.maps.MarkerImage(
				iconMarker,
				new google.maps.Size(50, 50, "px", "px")
			 ),
			 draggable:true,
		},
		
		events:{
			dragend: function(marker){
				{jQuery(this).gmap3({
					  getaddress:{
						latLng:marker.getPosition(),
						callback:function(results){
							var map = jQuery(this).gmap3("get");
							positionString = marker.getPosition();
							console.log(positionString);
							jQuery("#latlongMaps").val(positionString.ob + "," + positionString.pb);
								
							var geocoder = new google.maps.Geocoder();  
							geocoder.geocode( 
								{"latLng":marker.getPosition()},
								function(data,status){
									if(status == google.maps.GeocoderStatus.OK){
										if (results[1]) {
											jQuery("#city").val(results[0].address_components[2].long_name);
											jQuery("#address").val(results[0].address_components[1].long_name + " " + results[0].address_components[0].long_name);
										} else {
											alert("No results found");
										}
									}
								});
							}
						  }
						});
					}
				}
			},
		}
	},
	"autofit" );
}


function viewCalendarColorbox(title,dateBegin,dataUntil,typeRepeating,weekRepeat,monthRepeat,nweekRepeat){
	event.preventDefault();
	jQuery.ajax({
		url:   admin_ajax,
		type: "POST",
		dataType: "html",
		data: {
			action: "eventissimo_calendar",
			title: title,
			dataBegin: dateBegin,
			dataUntil: dataUntil,
			typeRepeating: typeRepeating,
			weekdayrepeat: weekRepeat,
			monthdayrepeat: monthRepeat,
			nweekdayrepeat: nweekRepeat
		},

		success: function(response) {
			jQuery.colorbox({
				title:title,
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

	
		//href: url_pathPlugin  + 'pages/calendar.php?title=' + title + '&dataBegin=' + dateBegin + '&dataUntil=' + dataUntil + '&typeRepeating=' + typeRepeating +'&weekdayrepeat=' + weekRepeat +  '&monthdayrepeat=' + monthRepeat + '&nweekdayrepeat=' +nweekRepeat

}


function viewRepeatForm(){
	var dataFine = jQuery('#data_fine_yy-mm-dd').val();
	var dataInizio = jQuery('#data_inizio_yy-mm-dd').val();
	if (dataFine==dataInizio){
		jQuery('#dayRepeat').show();
		jQuery('#dayRepeat').attr("style","display:block;");
		jQuery('.dayRepeatSelect').val("");
	} else {
		jQuery('#dayRepeat').hide();
		jQuery('.dayRepeatSelect').val("");
		jQuery('.dayRepeatMount').removeAttr('checked');
		jQuery('#AllCheckedMonth').removeAttr('checked');
		jQuery('#AllCheckedWeek').removeAttr('checked');
		jQuery('#chkRepeat').removeAttr('checked');
		jQuery('#EveryYear').val("");
		jQuery('#repeatSelect').hide();
		jQuery('.weekSelect').removeAttr('checked');
		jQuery('.dayRepeat').removeAttr('checked');
	}

}