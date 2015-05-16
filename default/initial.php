<?php

assert_options(ASSERT_ACTIVE, TRUE);
assert_options(ASSERT_BAIL  , TRUE);
date_default_timezone_set ("America/Los_Angeles");
set_include_path(".:".$_SERVER["DOCUMENT_ROOT"]);

if (!session_start()) throw new Exception("A session could not be started."); assert (isset($_SESSION));
$_SESSION["referer"] = $_SERVER["PHP_SELF"];

include "resources/extensions/database.php";
include "resources/extensions/accounts.php";
include "resources/extensions/mobile.php";
include "resources/extensions/custom.php";

$fresh = (!array_key_exists("status", $_SESSION));
if ($fresh) {
	$_SESSION["status"] = "fresh";
	
	if (login_cookie_exists()) {
		global $db;
		$email          = filter_var(strtok($_COOKIE["auto-login"], '|'), FILTER_SANITIZE_EMAIL);
		$member         = $db->read("members", "`email`='".$email."'");
		$hashedCookieID = trim(strtok('|'));
		$hashedMemberID = sha1(md5($member["id"].COOKIE_SALT));
		if ($hashedCookieID == $hashedMemberID) {
			login($email, TRUE);
			$_SESSION["status"] = "in";
			redirect("/profile.php");
		} else {
			delete_login_cookie();
			redirect("/login.php");
		}
	} else {
		$_SESSION["status"] = "out";
	}
} elseif (($_SESSION["mobile"]) && (!array_key_exists("show_full_site", $_COOKIE))) {
	redirect("http://m.bayfitted.com/index.php");
} elseif (array_key_exists("reset", $_REQUEST)) {
	logout(); 
	redirect("/message.php?success=reset");
} else {
	assert (in_array($_SESSION["status"], array("fresh", "out", "in")));
}

?>