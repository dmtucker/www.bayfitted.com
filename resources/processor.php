<?php

require_once "../default/initial.php";

assert (array_key_exists("action", $_GET) && !empty($_GET["action"]));

switch ($_GET["action"]) {
	case "logout":
		logout();
		redirect("/message.php?success=logout");
		break;
	
	case "update-manager-board":
		
		//preconditions
		if ($_SESSION["referer"] != "/management/index.php") throw new Exception("An unauthorized referer (".$_SESSION["referer"].") has been detected while attempting to update the manager board.");
		assert (count($_POST) == 1);
		
		global $db;
		$text = mysql_real_escape_string(filter_var($_POST["notes"], FILTER_SANITIZE_STRING));
		$db->edit("resources", "`data`='".$text."'", "`id`='manager-board'");
		break;
	default: throw new Exception("An unknown action (".$_GET["action"].") has been requested for processing.");
}

?>