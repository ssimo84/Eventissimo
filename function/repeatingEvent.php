<?php

function getArrayDateRepeat($date_begin,$date_end,$weekdayrepeat,$monthdayrepeat,$typeRepeating){
	$start_date = strtotime( Date($date_begin));
	$last_date  = strtotime( Date($date_end));
	// Get the time interval to get the tue and Thurs days
	$no_of_days = ($last_date - $start_date) / 86400; //the diff will be in timestamp hence dividing by timestamp for one day = 86400
	$get_tue_thu_days = array();
	if ($typeRepeating=="manyDays") {
		// Loop upto the $no_of_days
		//Loop  for  week days
		if (!is_array($weekdayrepeat))
			$date_week = explode(",",$weekdayrepeat);
		else
			$date_week = $weekdayrepeat;
			
		if (!is_array($monthdayrepeat))
			$date_month = explode(",",$monthdayrepeat);
		else
			$date_month = $monthdayrepeat;
			
		for($i = 0; $i <= $no_of_days; $i++) {
			$temp = date_i18n("D", $start_date);
			if (in_array($temp,$date_week)) {
				
			  $get_week_days[] =  $start_date; //formating date in Thu/May/2013 formate.
			}
			$start_date += 86400;
		}
		//Loop  for  week days
		for ($i=0; $i<count($get_week_days); $i++){
			$temp = date_i18n("F", $get_week_days[$i]);
			if (in_array($temp,$date_month)) {
			  $get_month_days[] = $get_week_days[$i]; 
			  //echo  date_i18n(get_option('date_format') , $get_week_days[$i]) . "<br/>";
			}
		}

		
		
		/*for ($i=0; $i<count($get_month_days); $i++){
			echo  date_i18n(get_option('date_format') , $get_week_days[$i]);
		}*/
		
		return $get_month_days;
	
	}
	
	
	if ($typeRepeating=="onlyDay") {
		$time_difference = $last_date - $start_date;
		$seconds_per_year = 60*60*24*365;
		$no_of_years = round($time_difference / $seconds_per_year);
		$get_years_days[0] = $start_date;
		for($i = 1; $i <= $no_of_years; $i++) {
			$dateadded = strtotime(date("Y-m-d", $get_years_days[$i-1]) . "+1 years");
			if ($dateadded <= $last_date){
				//echo date_i18n(get_option('date_format') , $dateadded);
				$get_years_days[] = $dateadded;
			}
		}

		return $get_years_days;
		
	}
	
	
}
