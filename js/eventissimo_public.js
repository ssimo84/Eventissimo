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
			}
		 }
	},
	"autofit" );
}




