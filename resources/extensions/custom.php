<?php

function smart_date ($timestamp=FALSE)
{
	//pre-condition
	assert ($timestamp === FALSE || is_int($timestamp));
	$now = time();
	if ($timestamp == FALSE) $timestamp = $now;
	$age           = $now-$timestamp;
	$daysThisMonth = cal_days_in_month(CAL_GREGORIAN, date("n", $timestamp), date("Y", $timestamp));
	if     ($age > (60*60*24*365))              return date("Y", $timestamp);    //year
	elseif ($age > (60*60*24*7*$daysThisMonth)) return date("F Y", $timestamp);  //month
	elseif ($age > (60*60*24*7))                return date("F j", $timestamp);  //week
	elseif ($age > (60*60*24))                  return date("l", $timestamp);    //day
	elseif ($age > (60*60))                     return "today";                  //hour
	elseif ($age > 60)                          return date("g:ia", $timestamp); //minute
	elseif ($age < 61)                          return "just now";               //now
}

function is_spam ($email)
{
	return !(filter_var($email, FILTER_VALIDATE_EMAIL));
}

function populate_section ($department, $type)
{
	//preconditions
	assert (in_array($department, array("men", "women", "kids", "accessories", "giants")));
	assert (is_string($type));
	
	global $db;
	$page = (array_key_exists("page", $_GET)) ? $_GET["page"]: FALSE ;
	switch ($page) {
		default:
			/*
			$message = ($page === FALSE) ? "Error: A page number must be specified!" : "Error: ".$page." is an invalid page number!";
			?><p class="error"><?php echo $message; ?></p><?php
			break;
			*/
			
		case 1:
			$link   = $db->open_connection();
			$result = $db->read("inventory", "`department`='".$department."' AND `type`='".$type."'" , $link);
			if (empty($result)) return FALSE;
			
			?><table class="product"><?php
			
			$cells = 0;
			foreach ($result as $item) {
				if ($cells%4 == 0): ?><tr><?php endif; //4 cells per row row
				
				?><td style="padding:2px 4px 2px 0px;"><?php echo thumbnail($item["id"], $link); ?></td><?php
				
				if (++$cells%4 == 0): ?></tr><?php endif;
			}
			
			//include empty cells to complete the table
			while ($cells%4 != 0) {
				?><td></td><?php
				++$cells;
			}
			
			?></tr>
			</table><?php
			
			$db->close_connection($link);
			break;
	}
}

function random_item ($howMany=1, $connection=FALSE)
{
	global $db;
	
	//pre-conditions
	assert (is_int($howMany));
	assert ($connection === FALSE || mysql_ping($connection->handle()));
	
	$link   = ($connection === FALSE) ? $db->open_connection(): $connection;
	$result = mysql_query("SELECT * FROM `inventory` ORDER BY RAND() LIMIT ".$howMany, $link->handle());
	if ($result === FALSE) throw new Exception("A random item could not be retrieved.");
	for ($itemi=0; $itemi < $howMany; ++$itemi) $items[] = mysql_fetch_assoc($result);
	
	if ($connection === FALSE) $db->close_connection($link);
	return $items;
}

function redirect ($where, $external=FALSE)
{
	//pre-conditions
	assert(is_bool($external));
	
	if ($external) header("Location: ".$where);
	else           header("Location: http://".$_SERVER["SERVER_NAME"].$where);
	
	exit;
}

function thumbnail ($itemID, $connection=FALSE)
{
	global $db;
	
	//pre-conditions
	assert (is_numeric($itemID));
	assert ($connection === FALSE || mysql_ping($connection->handle()));
	
	$link   = ($connection === FALSE) ? $db->open_connection() : $connection;
	$result = $db->read("inventory", "`id`=".$itemID, $link);
	if (count($result) > 1) report_error("More than one item has been discovered with the ID #".$itemID." in the inventory.");
	$item       = $result[0];
	
	//description
	$dimensions       = ($itemID-3000 < 0) ? 'height:133px; width:200px; margin-top:67px;': 'height:200px; width:133px;';
	$code       = '<div class="thumbnail box">
		<a href="/resources/images/inventory/'.$itemID.'_1.jpg" class="highslide" onClick="return hs.expand(this)" title="'.$item["description"].'">
			<img src="/resources/images/inventory/tn_'.$itemID.'_1.jpg" alt="'.$itemID.'" style="color:#000; '.$dimensions.'" />
		</a>
		<div class="product-description">
			<p>
				'.$item["description"].'<br />
				$'.$item["price"].'
			</p>
			<form target="paypal" action="https://paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="'.$item["paypal_id"].'" />
				<div class="product-options">';
	
	//options
	$option["size"]         = ($item["size"]         != "na");
	$option["color"]        = ($item["color"]        != "na");
	$option["detail_color"] = ($item["detail_color"] != "na");
	if ($option["size"] || $option["color"] || $option["detail_color"]) {
		$optioni = 0;
		foreach ($option as $optionName => $applicable) if ($applicable) {
			
			if (empty($item[$optionName]) || ($item[$optionName] == "unknown")) $code .= "Ask us about available ".str_replace('_', ' ', $optionName)."s!<br />";
			else {
				$optionContent  = explode(",", $item[$optionName]);
				$code          .= '<input type="hidden" name="on'.$optioni.'" value="'.$optionName.'" />'.ucfirst(str_replace('_', ' ', $optionName)).' <select name="os'.$optioni.'">';
				
				foreach ($optionContent as $content) $code.='<option value="'.$content.'">'.$content.'</option>';
				
				$code .= '</select><br />';
				
			}
			
			++$optioni;
			
		}
		unset($optioni);
	}
	
	//add to cart button
	$code .= '</div>
	<input type="image" src="/resources/images/buttons/add_to_cart.png" name="submit" alt="Add to Cart" />';
	
	//add to likes button
	/*
	if(is_logged_in()) 
		$code .= (substr($_SERVER["PHP_SELF"],1) == "profile.php") ?
			'<form action="/profile.php" method="post">
				<input type="image" name="remove_from_likes" src="/resources/images/buttons/remove_from_likes.gif" onmouseout="this.src=\'/resources/images/buttons/remove_from_likes.gif\'" onmouseover="this.src=\'/resources/images/buttons/remove_from_likes_down.gif\'" value="'.$itemID.'" alt="Remove" />
			</form>':
			'<form action="/profile.php" method="post">
				<input type="image" name="add_to_likes" src="/resources/images/buttons/add_to_likes.gif" onmouseout="this.src=\'/resources/images/buttons/add_to_likes.gif\';" onmouseover="this.src=\'/resources/images/buttons/add_to_likes_down.gif\';" alt="I Like This" value="'.$itemID.'" />
			</form>';
	*/
	
	$code .= '</div></div>';
	
	if ($connection === FALSE) $db->close_connection($link);
	return $code;
	
}

?>