<?php 
function wcpfc_url_exists($url) 
{
    $headers = @get_headers($url);
	if(strpos($headers[0],'200')===false) return false;
	
	return true;
}
function wcpfc_get_value_if_set($data, $nested_indexes, $default = false)
{
	if(!isset($data))
		return $default;
	
	$nested_indexes = is_array($nested_indexes) ? $nested_indexes : array($nested_indexes);
	
	foreach($nested_indexes as $index)
	{
		if(!isset($data[$index]))
			return $default;
		
		$data = $data[$index];
	}
	
	return $data;
}
$wcpfc_result = get_option("_".$wcpfc_id);
$wcpfc_notice = !$wcpfc_result || ($wcpfc_result != md5(wcpfc_giveHost($_SERVER['SERVER_NAME'])) && $wcpfc_result != md5($_SERVER['SERVER_NAME'])  && $wcpfc_result != md5(wcpfc_giveHost_deprecated($_SERVER['SERVER_NAME'])) );

function wcpfc_giveHost($host_with_subdomain) 
{
     $matches = [];
	preg_match('/\w+\..{2,3}(?:\..{2,3})?(?:$|(?=\/))/i', $host_with_subdomain, $matches);
	 
	if($matches[0] == "")
		 preg_match('/[\w-]+(?=(?:\.\w{2,6}){1,2}(?:\/|$))/', $host_with_subdomain, $matches);
	 
	if($matches[0] == "")
		return $host_with_subdomain;
    return $matches[0];
}
function wcpfc_giveHost_deprecated($host_with_subdomain)
{
	$array = explode(".", $host_with_subdomain);

    return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1];
}
if(!$wcpfc_notice)
	wcpfc_setup();
function wcpfc_write_log ( $log )  
{
  if ( is_array( $log ) || is_object( $log ) ) {
	 error_log( print_r( $log, true ) );
  } else {
	 error_log( $log );
  }
}
function wcpfc_start_with($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
function wcpfc_html_escape_allowing_special_tags($string, $echo = true)
{
	$allowed_tags = array('strong' => array(), 
						  'i' => array(), 
						  'bold' => array(),
						  'h4' => array(), 
						  'span' => array('class'=>array(), 'style' => array()), 
						  'br' => array(), 
						  'a' => array('href' => array()),
						  'ol' => array(),
						  'ul' => array(),
						  'li'=> array());
	if($echo) 
		echo wp_kses($string, $allowed_tags);
	else 
		return wp_kses($string, $allowed_tags);
}
?>