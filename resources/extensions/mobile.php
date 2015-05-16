<?php

//  author:   David Tucker
//  reviewed: 8-7-2011
//  modified: 8-7-2011
//  status:   beta (debugged, untested)
//
//  Description
//      allows adaptability for users on mobile devices
//
//
//  Constants
//      [$_SESSION]
//          mobile
//              TRUE if the user is known to be mobile
//              FALSE otherwise
//
//  Database
//      none
//
//  Functions (1)
//      is_mobile
//
//  Requirements
//      none
//
//
//  Notes
//      This module makes use of a session if one is active, but it will not attempt to start one.

//CONSTANTS
if (isset($_SESSION) && !array_key_exists("mobile", $_SESSION)) $_SESSION["mobile"] = (is_mobile());

/*********************************************************************************
Description
	finds whether the user is on a mobile device

Order Θ(1)

Parameters
	none

Return Values
	TRUE if the user is on a mobile device
	FALSE otherwise

*********************************************************************************/
function is_mobile ()
{
	if (isset($_SESSION) && array_key_exists("mobile", $_SESSION)) return $_SESSION["mobile"];
	
	if (array_key_exists("HTTP_USER_AGENT", $_SERVER)) {
		$userAgent    = strtolower($_SERVER["HTTP_USER_AGENT"]);
		$mobileAgents = array("w3c ", "acs-", "alav", "alca", "amoi", "audi", "avan", "benq", "bird", "blac",
							  "blaz", "brew", "cell", "cldc", "cmd-", "dang", "doco", "eric", "hipt", "inno",
							  "ipaq", "java", "jigs", "kddi", "keji", "leno", "lg-c", "lg-d", "lg-g", "lge-",
							  "maui", "maxo", "midp", "mits", "mmef", "mobi", "mot-", "moto", "mwbp", "nec-",
							  "newt", "noki", "oper", "palm", "pana", "pant", "phil", "play", "port", "prox",
							  "qwap", "sage", "sams", "sany", "sch-", "sec-", "send", "seri", "sgh-", "shar",
							  "sie-", "siem", "smal", "smar", "sony", "sph-", "symb", "t-mo", "teli", "tim-",
							  "tosh", "tsm-", "upg1", "upsi", "vk-v", "voda", "wap-", "wapa", "wapi", "wapp",
							  "wapr", "webc", "winw", "winw", "xda" , "xda-");
		
		return (in_array(substr($userAgent, 0, 4), $mobileAgents)
		|| 	    preg_match("/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i", $userAgent));
	}
	
	return (array_key_exists("HTTP_PROFILE"      , $_SERVER)
	||      array_key_exists("HTTP_X_WAP_PROFILE", $_SERVER)
	||     (array_key_exists("HTTP_ACCEPT"       , $_SERVER) && strpos(strtolower($_SERVER["HTTP_ACCEPT"]), "application/vnd.wap.xhtml+xml") > 0)
	||     (array_key_exists("ALL_HTTP"          , $_SERVER) && strpos(strtolower($_SERVER["ALL_HTTP"])   , "OperaMini"                    ) > 0));
}

/**/

?>