<?php
/*
Function Facebook integration
*/


function createStatusPublicFacebook($statusFB){
	$arrayStatusFB = array(
    'OPEN'  => __('Public','eventissimo'),
    'SECRET' => __('Invited only','eventissimo'),
    'FRIENDS' => __('Friends of Guests','eventissimo'),
	);
	foreach ($arrayStatusFB as $key=>$value){
		$option .= "<option value='" .  $key . "'";
		if ($key==$statusFB) $option .= " selected='selected' ";
		$option .= ">"  . $value . "</option>";
	}
	return $option;
}


//TO DO
function eventissimo_postCoverImage($url,$idevents){
	if (($url!="") && ($idevents!="")){
		$facebook = accessFacebook();
		
		$attachment['picture'] = '@' . $url; 
		$attachment['cover_url'] = '@' . $url; 
		$attachment['cover'] = '@' . $url; 
		$attachment['pic_cover'] = '@' . $url;
		$attachment['pic_big'] = '@' . $url;
		$attachment[ '@' . basename($url)] = '@' . $url;
		$attachment['image'] = '@' . $url;
		
		try{ 
			$result = $facebook->api('/' . $idevents , 'post', $attachment);
		}catch( Exception $e){
			 echo "<div class='error'>Remove events: " . $e . "</div>";
			 die();
		}
		
	}
}

function deleteEventsFacebook($idEvents){
	$facebook = accessFacebook();
	try{
		$response = $facebook->api("/" .$idEvents,"DELETE");
			return $response;
	}catch( Exception $e){
		 echo "<div class='error'>Remove events: " . $e . "</div>";
	}
}


function accessFacebook($fileUpload=TRUE){
	include_once(BASE_URL . "/plugin/facebook/sdk-facebook/facebook.php");
	$facebook = new Facebook(array(
                'appId'  => FACEBOOOK_API_KEY,
                'secret' => FACEBOOK_SECRET_KEY,
				 'cookie' => FALSE,
   				 'fileUpload' => $fileUpload
				));

	return $facebook;
}

function  eventissimo_postEventFacebook($token,$url,$description,$title,$idUserPages,$message){
	$facebook = accessFacebook();
	$message = array(
	'message'=> $message, 
    'link'=> $url,
    'description'=>$description,
    'name'=> $title,
    'caption'=>$url,
	);

	try{
		$post = $facebook->api( '/' .  $idUserPages . '/feed','POST', $message);
		
	}catch( Exception $e){
			echo "Post events: " . $e;
	}
}


//Attention: this function remove all events of app
function eventissimo_removeAllEventsApp(){
	$facebook = accessFacebook();
	$response = $facebook->api("/" . FACEBOOOK_API_KEY . "/events","GET");
	foreach ($response["data"] as $id){
		echo deleteEventsFacebook($id["id"]);
		echo "<br/>";
	}
}


function eventissimo_sameauthor($idevents,$idUserPages){
	$facebook = accessFacebook();
	$accessToken = $facebook->getAccessToken();
	
	try {
		
		$meUser = $facebook->api('/me','GET');
		$owner = $meUser["id"];
		
		if ($idUserPages == $owner) return true;
		else return false;
	} catch (Exception $e) {
		return false;
	}
}

?>