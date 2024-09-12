<?php class WCPFC_DateTime
{
	function __construct()
	{
		
	}
	function time_is_greater_than($time1 , $time2)
	{
		if(!isset($time1) || $time1 == "")
			return false;
		
		$time2 == 'now' ? current_time('H:i') : $time2;
		
		if(strtotime($time1) < strtotime($time2))
			return false;
		
		return true;
	}
	function current_time()
	{
		return current_time('H:i');
	}
	function current_date()
	{
		return current_time('Y-m-d');
	}
	function get_formatted_datetime($value, $date_format, $lang = null)
	{
		global $wcpfc_wpml_model;
		$lang = !isset($lang) ? $wcpfc_wpml_model->get_current_locale() : $lang;
		$result = $value;
		$current_format = "";
		
		if( $date_format == "dd/mm/yyyy" )
			$current_format = "d/m/Y";
		else if( $date_format == "mm/dd/yyyy" )
			$current_format = "m/d/Y";
		else if( $date_format == "yyyy/mm/dd" )
			$current_format = "Y/m/d";
		else if( $date_format == "dd.mm.yyyy" )
			$current_format = "d.m.Y";
		else if( $date_format == "mm.dd.yyyy" )
			$current_format = "m.d.Y";
		else if( $date_format == "yyyy.mm.dd" )
			$current_format = "Y.m.d";
		else if( $date_format == "dd-mm-yyyy" )
			$current_format = "d-m-Y";
		else if( $date_format == "mm-dd-yyyy" )
			$current_format = "m-d-Y";
		else if( $date_format == "yyyy-dd-mm" )
			$current_format = "Y-d-m";
		else if( $date_format == "yyyy-mm-dd" )
			$current_format = "Y-m-d";
		else if( $date_format == "mmmm dd, yyyy" || $date_format == "mmmm d, yyyy")
		{
			
			{
				try 
				{
					$currentLocal = setlocale(LC_ALL, 0);
					setlocale(LC_ALL, $lang) ;		
					$date = new DateTime($value);					
					$result =  utf8_encode(strftime("%B %e, %G", $date->getTimestamp())); //crashes for en language?
					setlocale(LC_ALL, $currentLocal) ;
					return $result;
				}
				catch (Exception $e) {}
			}
			$current_format = "F j, Y"; 
		}
		
		else if( $date_format == "HH:i" )
			$current_format = "H:i";
		else if( $date_format == "h:i a" )
			$current_format = "g:i a";
		else if( $date_format == "h:i A" )
			$current_format = "g:i A";
		
		
		$date = new DateTime($value); //yyyy-mm-dd
		if(is_object($date))
			$result = $date->format($current_format);
		
		return $result;
	}
	
	function conver_wordpress_to_strftime_format($format)
	{
		//Day of Month
		$format = str_replace("d" ,"%d", $format);
		$format = str_replace("j" ,"%e", $format);
		$format = str_replace("S" ,"", $format);
		//Weekday
		$format = str_replace("l" ,"%A", $format);
		$format = str_replace("D" ,"%a", $format);
		//Month
		$format = str_replace("m" ,"%m", $format);
		$format = str_replace("n" ,"%m", $format);
		$format = str_replace("F" ,"%B", $format);
		$format = str_replace("M" ,"%h", $format);
		//Year
		$format = str_replace("Y" ,"%Y", $format);
		$format = str_replace("y" ,"%y", $format);
		//Time
		$format = str_replace("a" ,"", $format);
		$format = str_replace("A" ,"", $format);
		$format = str_replace("Y" ,"%y", $format);
		$format = str_replace("y" ,"%Y", $format);
		$format = str_replace("Y" ,"%y", $format);
		$format = str_replace("y" ,"%Y", $format);
		$format = str_replace("Y" ,"%y", $format);
		$format = str_replace("y" ,"%Y", $format);
		$format = str_replace("y" ,"%Y", $format);
		//Full Date/Time
	}
}
?>