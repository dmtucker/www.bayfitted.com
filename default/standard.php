<?php

/******************
SITE INITIALIZATION
******************/
session_start();

    ///////////////////
////// configuration //////
    ///////////////////
date_default_timezone_set ("America/Los_Angeles");
set_error_handler         ("error_handler", E_ALL);
#set_exception_handler     ("exception_handler");



/********
CONSTANTS
********/
if (!(define("SITE_TITLE"  ,           "bayfitted"    ))) throw new Exception("could not set the SITE_TITLE constant");
if (!(define("THIS_DOMAIN" ,           "bayfitted.com"))) throw new Exception("could not set the THIS_DOMAIN constant");
if (!(define("MYSQL_SERVER", "localhost"              ))) throw new Exception("could not set the MYSQL_SERVER constant");
if (!(define("DEFAULT_DB"  ,           "bayfitted"    ))) throw new Exception("could not set the DEFAULT_DB constant");
if (!(define("WEBMASTER"   , "webmaster@bayfitted.com"))) throw new Exception("could not set the WEBMASTER constant");

    /////////////////
////// preferences //////
    /////////////////
if (!(define("ACTION_ON_ERROR", "DEBUG"   ))) throw new Exception("could not set the ACTION_ON_ERROR constant"); // [DIE  | DEBUG | LOG | NONE | NORMAL | REDIRECT]
if (!(define("COOKIE_SALT"    , "changeme"))) throw new Exception("could not set the COOKIE_SALT constant");
if (!(define("MAIL_ON_ERROR"  ,  FALSE    ))) throw new Exception("could not set the MAIL_ON_ERROR constant");   // [TRUE | FALSE]


/********
FUNCTIONS
********/
function populate_section ($department, $type) {
	
	$page = (array_key_exists("page", $_GET)) ? $_GET["page"] : FALSE ;
	
	//show appropriate page
	switch ($page) {
		
		case 1:
			$link     = new_connection();
			$result   = mysql_query("SELECT * FROM `inventory` WHERE `department`='".$department."' AND `type`='".$type."'", $link);
			$rowcount = ($result === FALSE) ? FALSE : mysql_num_rows($result);
			
			if ($rowcount === FALSE) { trigger_error("could not populate a section", E_USER_WARNING); return FALSE; }
						
			?><table class="product">
				<tr><?php
			
			//cycle thru inventory
			for ($i=0; $i < $rowcount; $i++) {
				
				$item = mysql_fetch_assoc($result);
				
				//new row
				if (($i%4 == 0) && ($i != 0)) { ?></tr><tr><?php }
				
				//new cell with thumbnail
				?><td style="width:25%;">
					<?php echo thumbnail($item["id"], $link);?>
					<br />
					<br />
					<br />
				</td><?php
				
			}
			
			//include empty cells to complete the table
			$lastRowCells = $rowcount%4;
			$cellsNeeded  = ($lastRowCells == 0) ? 0 : 4-$lastRowCells;
			
			if ($cellsNeeded != 0) while ($cellsNeeded > 0) { ?><td></td><?php $cellsNeeded--; }
			
			  
			?></tr>
			</table><?php
			
	
			close_connection($link);
			
			break;
			
			
		default:
			$message = ($page === FALSE) ? "Error: A page number must be specified!" : "Error: ".$page." is an invalid page number!";
			
			//display error
			?><p style="color:#F00;"><?php echo $message; ?></p><?php
			
			break;
	
	}
	
}
	


    /////////////
////// default //////
	/////////////
	function is_spam ($email) { return !(filter_var($email, FILTER_VALIDATE_EMAIL)); }
	
	function redirect ($url="") { header("Location: ".$url); exit; }
	
	
	
	//////////////
////// accounts //////
	//////////////
	function auto_login_cookie () { return (array_key_exists("auto-login", $_COOKIE)); }
	
	function delete_login_cookie () { setcookie("auto_login", "", time()-31536000); } // destroy the cookie by setting the expiration in the past and the valid path to empty
	
	function is_logged_in ($connection=FALSE) { // boolean
		
		//check session status
		if (array_key_exists("member", $_SESSION)) return $_SESSION["member"];
		
		//check session memberId
		elseif ((array_key_exists("memberID", $_SESSION)) && ($_SESSION["memberID"] !== FALSE)) {
			
			$link = ($connection === FALSE) ? new_connection() : $connection;
			
			$matches = get_info("members", $_SESSION["memberID"], "id", $link);
			
			if ($connection === FALSE) close_connection($link);
			
			return ($matches == 1);
			
		}
		
		else return FALSE;
		
	}



	function login ($email, $remember) {
		
		assert(is_bool($remember));
		
		$LINK     = new_connection("edit");
		$email	  = filter_data($email, $LINK, FILTER_SANITIZE_EMAIL);
		$member	  = get_info("members", $email, "email", $LINK);
		$memberID = $member["id"];
		
		//set session
		$_SESSION["member"]     = TRUE;
		$_SESSION["memberID"]   = $memberID;
		$_SESSION["first_name"] = $member["first_name"];
		$_SESSION["load"]	    = TRUE;
		$_SESSION["greeting"]   = ($member["greeting"] == "true");
		
		//update last login
		if (!mysql_query("UPDATE `members` SET `last_visit`=NOW() WHERE `id`=".$memberID, $LINK)) trigger_error("could not update last login", E_USER_WARNING);
		
		//auto-login cookie
		if     ($remember) 								  set_login_cookie    ($email);
		elseif (array_key_exists("auto_login", $_COOKIE)) delete_login_cookie ();


		close_connection($LINK);
		
	}
	
	
	
	function logout () {
		
		if(array_key_exists("auto_login", $_COOKIE)) delete_login_cookie();
		
		session_unset();
		session_destroy();
		
	}
	
	
	
	function set_login_cookie ($email) {
		
		assert (array_key_exists("memberID", $_SESSION));
		
		$name		= "auto_login";
		$value		= $email."|".sha1(md5($_SESSION["memberID"].COOKIE_SALT));
		$expiration = time()+60*60*24*365;
		$directory  = '/';
		$domain		= ".".THIS_DOMAIN;
		
		//create cookie with a 1 year expiration
		setcookie($name, $value, $expiration, $directory, $domain);
		
	}



	/////////////
////// commerce //////
    /////////////
	function add_to_likes ($item) {

		$LINK = new_connection("edit");

		$member = get_info("members", $_SESSION["memberID"], "id", $LINK);
		
		$cart = unserialize($member["likes"]);
		$cart[] = $item;
		$cart = serialize($cart);
		
		if (!(mysql_query("UPDATE members SET likes='".$cart."' WHERE id=".$memberID))) throw new Exception("cant do shit");

		close_connection($LINK);
		
	}



	function remove_from_likes ($item){
		
		$LINK = new_connection("edit");

		$member = get_info("members", $_SESSION["memberID"], "id", $LINK);
		
		$cart = unserialize($member["likes"]);
		$key=array_search($item, $cart);
		unset($cart[$key]);
		$cart = serialize($cart);
		
		if (!(mysql_query("UPDATE members SET likes='".$cart."' WHERE id=".$memberID))) throw new Exception("cant do shit");

		close_connection($LINK);
		
	}
	
	
	
	function random_item ($howMany=1, $connection=FALSE) {
	
		$link   = ($connection === FALSE) ? new_connection() : $connection;
		$result = mysql_query("SELECT * FROM `inventory` ORDER BY RAND() LIMIT ".$howMany, $link);
		
		if     ($result === FALSE) throw new Exception("could not get a random item");
		
		else for ($i=0; $i < $howMany; $i++) $items[] = mysql_fetch_assoc($result);
		
		
		if ($connection === FALSE) close_connection($link);
		
		return $items;
		
	}
	
	function thumbnail ($itemID, $connection=FALSE) {
		
		$link = ($connection === FALSE) ? new_connection() : $connection;
		$item = get_info("inventory", $itemID, "id", $link);
		
		$dimensions = ($itemID-3000 >= 0) ? 'height="200" width="133"' : 'height="133" width="200"';
		
		//begin generating code
		$code = '<div class="thumbnail">
			<a href="/images/inventory/'.$itemID.'.jpg" class="highslide" onClick="return hs.expand(this)">
				<img src="/images/inventory/tn_'.$itemID.'.jpg" '.$dimensions.' alt="'.$itemID.'" />
			</a>
			<p>
				'.$item["description"].'<br />
				Price: $'.$item["price"].'
			</p>
			<br />
			<form target="paypal" action="https://paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick" />
				<input type="hidden" name="hosted_button_id" value="'.$item["paypal_id"].'" />';
		
		//decide if options are present
		$option["size"]         = ($item["size"]         != "na");
		$option["color"]        = ($item["color"]        != "na");
		$option["detail_color"] = ($item["detail_color"] != "na");
		
		if ($option["size"] || $option["color"] || $option["detail_color"]) {
			
			$i = 0;
			
			//cycle thru all applicable options
			foreach ($option as $optionName=>$optionApplies) if ($optionApplies) {
				
				//add message for inventory with unknown options
				if ((empty($item[$optionName])) || ($item[$optionName] == "unknown"))
				
					$code .= "<p>Ask us about our available ".str_replace('_', ' ', $optionName)."s!</p>";
				
				else {
					
					$optionContent = explode(",", $item[$optionName]);
					
					$code .= '<input type="hidden" name="on'.$i.'" value="'.$optionName.'" />'.ucfirst(str_replace('_', ' ', $optionName)).': <select name="os'.$i.'">';
							
					//add options
					foreach ($optionContent as $content) $code.='<option value="'.$content.'">'.$content.'</option>';
					
					$code .= '</select><br />';
					
				}
				
				$i++;
				
			}
			
			else $code .= '<br />';
			
			unset($i);
			
			$code .= '<br />';
			
		}
		
		//add to cart button
		$code .= '<input type="image" src="/images/buttons/add_to_cart.png" name="submit" alt="Add to Cart" />
		</form>
		<br />';
		
		//add to likes button
		if(is_logged_in()) 
		
			if (substr($_SERVER["PHP_SELF"],1) == "likes.php")
		
				$code .= '<form action="/likes.php" method="post">
					<input type="image" name="remove_from_likes" src="/images/buttons/remove_from_likes.gif" onmouseout="this.src=\'/images/buttons/remove_from_likes.gif\'" onmouseover="this.src=\'/images/buttons/remove_from_likes_down.gif\'" value="'.$itemID.'" alt="Remove" />
				</form>';
				
			else
			
				$code .= '<form action="/likes.php" method="post">
  					<input type="image" name="add_to_likes" src="/images/buttons/add_to_likes.gif" onmouseout="this.src=\'/images/buttons/add_to_likes.gif\';" onmouseover="this.src=\'/images/buttons/add_to_likes_down.gif\';" alt="I Like This" value="'.$itemID.'" />
				</form>';
		
		$code .= '</div>';
		
		
		if ($connection === FALSE) close_connection($link);
		
		return $code;
		
	}

	////////////////////
////// error handling //////
	////////////////////
	function assertion_handler($file, $line, $code){return FALSE;}

	function error_handler ($errorNumber, $errorMessage, $file, $lineNumber) {
		
		//send mail alert
		if (MAIL_ON_ERROR) {
			
			$emailMessage = "Error #".$errorNumber." ocurred in ".$file." at line #".$line_number." on ".date("F j, Y, g:i a").PHP_EOL.PHP_EOL.$errorMessage;
			
			mail(WEBMASTER, THIS_DOMAIN." Error", $emailMessage, "From: standard.php@".THIS_DOMAIN);

		}

		//stop page load and retitle the page
		?><script type="text/javascript"><!--//--><![CDATA[//><!--
			window.onload="";
			document.title="Error";
		//--><!]]></script><?php

		//handle error
		$alertMessage = "An error has occurred! We apologize for the inconvenience. There is no need to contact us, the error has been logged and will be repaired as soon as possible.";
		
		switch (ACTION_ON_ERROR) {
			
			default:case "DIE": //display alert and die
			
						?><p style="text-align:center; background:#FFF; color:#000;"><?php echo $alertMessage; ?></p><?php
						
						die();
						
					case "DEBUG": //display debug info and die
					
						$mysqlError = mysql_error();
						$phpError	= error_get_last();
					
						?><p style="text-align:left; font-family:'Courier New', Courier, monospace; font-size:14px; background-color:#FFF; color:#000;">
							<span style="font-weight:bold;">*Error Report: </span><br />
							---------------------------------------------<br />
							<span style="font-weight:bold;">Message: &nbsp;&nbsp;&nbsp;&nbsp;</span><?php echo $errorMessage; ?><br />
							<span style="font-weight:bold;">File: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><?php echo $file; ?><br />
							<span style="font-weight:bold;">Line: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><?php echo $lineNumber; ?><br />
							<br />
							<span style="color:#F00; margin:auto;"><span style="font-weight:bold;">*WARNING: </span>These may or may NOT be applicable!</span><br />
							<span style="font-weight:bold;">MySQL Error: </span><?php echo (empty($mysqlError)) ? "N/A" : $mysqlError; ?><br />
							<span style="font-weight:bold;">PHP Error: &nbsp;&nbsp;</span><?php echo ($phpError === NULL) ? "N/A" : "(type ".$phpError["type"].')'.$phpError["message"]; ?><br />
							<br />
							<span style="font-weight:bold;">*Debug Backtrace: </span><br />
							---------------------------------------------<br />
							<?php debug_print_backtrace(); ?>
						</p><?php
						
						die();
						
					case "LOG": break; //not ready
						
					case "NONE": exit; break; //do nothing
					
					case "NORMAL": break; //not ready
					
					case "REDIRECT": //take user to the home page
					
						?><script type="text/javascript"><!--//--><![CDATA[//><!--
							alert(<?php echo $alertMessage.PHP_EOL."You will now be taken back to the homepage."; ?>);
							window.location="/index.php";
						//--><!]]></script><?php
						
						break;
						
		}
	}

	/*
	function error_handler($message="(no message provided)", $line="(no line provided)", $file="(no file provided)"){
		$php_error   =error_get_last();
		$mysql_error =mysql_error();
		
		//check error returns
		if(empty($mysql_error)) $mysql_error="(no mysql_error provided)";
		if(empty($php_error))   $php_error=array("message"=>"(no error_get_last provided)");
		
		//send mail alert
		if(MAIL_ON_ERROR) mail("webmaster@austintailor.com", "Austin Tailor ERROR", $message.PHP_EOL.PHP_EOL.$mysql_error, "From: standard.php@austintailor.com");

		//handle error
		switch(ACTION_ON_ERROR){
			default:
			case "DIE":
				?><script type="text/javascript"><!--//--><![CDATA[//><!--
					window.onload="";
					document.title="Error";
				//--><!]]></script>
				<p style="text-align:center; width:80%; margin:auto;">
                	An error has occurred! We are sorry for the inconvenience. There is no need to contact us, the error has been logged and will be repaired as soon as possible.
                </p><?php
				
				die();
			case "DEBUG":
			 	?><script type="text/javascript"><!--//--><![CDATA[//><!--
					window.onload="";
					document.title="Error";
				//--><!]]></script>
                <p style="text-align:left; margin:auto; width:80%; font-family:'Courier New', Courier, monospace; font-size:14px; background-color:#FFF; color:#000;">
                    <span style="font-weight:bold;">*Error Report: </span><br />
                    ---------------------------------------------<br />
                    <span style="font-weight:bold;">Message: </span><?php echo $message; ?><br />
                    <span style="font-weight:bold;">File: </span><?php echo $file; ?><br />
                    <span style="font-weight:bold;">Line: </span><?php echo $line; ?><br />
                    <br /><br />
                    <span style="color:#F00; margin:auto;"><span style="font-weight:bold;">*WARNING: </span>These may or may NOT be applicable!</span><br />
                    ---------------------------------------------<br />
                    <span style="font-weight:bold;">MySQL Error: </span><?php echo $mysql_error; ?><br />
                    <br />
                    <span style="font-weight:bold;">PHP Error: </span><?php echo $php_error["message"]; ?><br />
                    <br />
                    <br />
                    <span style="font-weight:bold;">*Debug Backtrace: </span><br />
                    ---------------------------------------------<br /><?php debug_print_backtrace(); ?>
				</p><?php
				
				die();
			case "LOG": break;
			case "NONE": break;
			case "NORMAL": break;
			case "REDIRECT":
			 	?><script type="text/javascript"><!--//--><![CDATA[//><!--
					window.onload="";
					alert("An error has occurred!\nWe apologize for the inconvenience.\nThere is no need to contact us, the error has been logged and will be repaired as soon as possible.\nYou will now be taken back to the homepage.");
					window.location="/index.php";
				//--><!]]></script><?php
				
				break;
		}
	}
	*/

	#function exception_handler ($exception) { error_handler($exception.getCode(), $exception.getMessage(), $exception.getFile(), $exception.getLine()); } //not ready

	////////////
////// mobile //////
	////////////
	function is_mobile () { // boolean
	
		//check session status
		if (array_key_exists("mobile", $_SESSION)) return $_SESSION["mobile"];
		
		//check user agent
		assert (array_key_exists("HTTP_USER_AGENT", $_SERVER));
		
	 	$mobileUserAgent = strtolower(substr($_SERVER["HTTP_USER_AGENT"],0,4));
		$mobileAgents 	 = array("w3c ","acs-","alav","alca","amoi","audi","avan","benq","bird","blac",
							   	 "blaz","brew","cell","cldc","cmd-","dang","doco","eric","hipt","inno",
							   	 "ipaq","java","jigs","kddi","keji","leno","lg-c","lg-d","lg-g","lge-",
							   	 "maui","maxo","midp","mits","mmef","mobi","mot-","moto","mwbp","nec-",
							     "newt","noki","oper","palm","pana","pant","phil","play","port","prox",
							     "qwap","sage","sams","sany","sch-","sec-","send","seri","sgh-","shar",
							     "sie-","siem","smal","smar","sony","sph-","symb","t-mo","teli","tim-",
							     "tosh","tsm-","upg1","upsi","vk-v","voda","wap-","wapa","wapi","wapp",
							     "wapr","webc","winw","winw","xda","xda-");
		
		
		return ((in_array($mobileUserAgent, $mobileAgents))
		|| 	    (array_key_exists("HTTP_PROFILE"      , $_SERVER))
		|| 	    (array_key_exists("HTTP_X_WAP_PROFILE", $_SERVER))
		|| 	   ((array_key_exists("HTTP_ACCEPT", $_SERVER)) && (strpos(strtolower($_SERVER["HTTP_ACCEPT"]),"application/vnd.wap.xhtml+xml") > 0))
		|| 	   ((array_key_exists("ALL_HTTP"   , $_SERVER)) && (strpos(strtolower($_SERVER["ALL_HTTP"])   ,"OperaMini")                     > 0))
		|| 	    (preg_match("/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i", strtolower($_SERVER["HTTP_USER_AGENT"]))));
	
	}
	
	
	
	///////////
////// MySQL //////
	///////////	
	function close_connection ($connection) { if(!mysql_close($connection)) throw new Exception("could not close a connection"); }

	function filter_data ($data, $connection=FALSE, $filter=FALSE, $flags="") {
		
		$link  = ($connection === FALSE) ? new_connection() : $connection;
		
		//apply filters and escape
		$data         = ($filter === FALSE) ? trim($data) : filter_var(trim($data), $filter, $flags);
		$filteredData = mysql_real_escape_string($data, $link);
		
		if ($filteredData === FALSE) throw new Exception("could not filter data");
		
		
		if ($connection === FALSE) close_connection($link);
		
		return $filteredData;
		
	}
	
	
	
	function get_info ($tableName, $searchValue, $searchField="id", $connection=FALSE) {
		
		$link  = ($connection === FALSE) ? new_connection() : $connection;
		
		$table = mysql_real_escape_string($tableName  , $link);
		$field = mysql_real_escape_string($searchField, $link);
		$value = mysql_real_escape_string($searchValue, $link);
		
		if (($table === FALSE) || ($field === FALSE) || ($value === FALSE)) throw new Exception("could not escape a search parameter while getting database info");
		
		//get info
		$result = mysql_query("SELECT * FROM `".$table."` WHERE `".$field."`='".$value."'", $link);
		
		if ($result === FALSE) trigger_error("could not get info", E_USER_WARNING);
		
		$info = mysql_fetch_assoc($result);
		
		
		if ($connection === FALSE) close_connection($link);
		
		return $info;
	}
	
	
	
	function new_connection ($account="read") {
		
		$username = $account;
		
		//resolve password
		switch ($account) {
			
			default:case "read":    $password = "password"; break;
					case "create":  $password = "password"; break;
					case "destroy": $password = "password"; break;
					case "edit":    $password = "password"; break;
		
		}
		
		assert (array_key_exists("HTTP_HOST", $_SERVER));
		
		//connect to appropriate server
		$connection = (strstr($_SERVER["HTTP_HOST"], "localhost") !== FALSE) ?
			
			mysql_connect("localhost" , "root"   , "root") :
			mysql_connect(MYSQL_SERVER, $username, $password);
			
		//check connection
		if ($connection === FALSE) throw new Exception("could not connect to MySQL");
		
		//select database
		if (!mysql_select_db(DEFAULT_DB, $connection)) throw new Exception("could not connect to the default database");
			
		return $connection;
		
	}

/*****************
SESSION PROCESSING
*****************/
	///////////////
////// constants //////
	///////////////
	if (!array_key_exists("mobile"  , $_SESSION)) $_SESSION["mobile"]   = (is_mobile()); 	// boolean
	if (!array_key_exists("member"  , $_SESSION)) $_SESSION["member"]   = (is_logged_in()); // boolean
	if (!array_key_exists("memberID", $_SESSION)) $_SESSION["memberID"] =  FALSE;           // [$memberID | FALSE]
	if (!array_key_exists("status"  , $_SESSION)) $_SESSION["status"]   = "FRESH";		    // [FRESH     | OK | BAD_COOKIE]
	
	
	
	//////////////////
////// status check //////
	//////////////////
	
	//check for auto-login cookie
	if (($_SESSION["status"] == "FRESH") && (array_key_exists("auto_login", $_COOKIE))) {
		
		$link   = new_connection();
		$email  = filter_data(strtok($_COOKIE["auto_login"], '|'), $link, FILTER_SANITIZE_EMAIL);
		$member = get_info("members", $email, "email", $link);
		
		close_connection($link);
		
		//get hashes
		$hashedCookieID = trim(strtok('|'));
		$hashedMemberID = sha1(md5($member["id"].COOKIE_SALT));
		
		//check cookie
		if ($hashedCookieID == $hashedMemberID) {
			
			$_SESSION["status"] = "OK";
			login($email, TRUE);
			redirect("http://bayfitted.com/profile.php");
		
		}
		
		else {
			
			$_SESSION["status"] = "BAD_COOKIE";
			delete_login_cookie();
			redirect("http://bayfitted.com/login.php");	
		
		}
		
	}
	
	//redirect mobile users who havent requested the full site
	elseif (($_SESSION["mobile"]) && (!array_key_exists("show_full_site", $_COOKIE)))
		
		redirect("http://m.bayfitted.com/index.php");
		
	//reset the session without having to restart the browser
	elseif (array_key_exists("reset", $_REQUEST)) {
		
		logout(); 
		redirect("http://bayfitted.com/status.php?success=reset");
	
	}

?>