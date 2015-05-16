<?php

//  author:   David Tucker
//  reviewed: 8-8-2011
//  modified: 10-12-2011
//  status:   beta (debugged, untested)
//
//  Description
//      enables the use of user accounts
//
//
//  Constants
//      $_SESSION
//          member
//              TRUE if the user is known to be logged in with a valid account
//              FALSE otherwise
//
//          member_id
//              the database ID of the member if $_SESSION["member"] is TRUE
//              FALSE otherwise
//
//          member_name
//              the name of the member if $_SESSION["member"] is TRUE
//              FALSE otherwise
//
//      $ACCOUNT
//          an associative array of any additional $_SESSION variables that should be set upon a successful login
//          If it is set to FALSE, no additional variables will be set.
//
//      LOGIN_COOKIE_SALT
//          a string to be appended to hashed data stored in a cookie to help prevent cracking
//          This should not be a dictionary-defined or common word.
//
//      LOGIN_COOKIE_TTL
//          the amount of time before the cookie of a user who opts for auto-login expires
//
//  Database
//      members
//          id         - a psuedo-randomly generated unique 6-digit identification number of the member
//          first_name - the first name of the member
//          last_name  - the last name of the member
//          last_login - a timestamp of the last time the member successfully logged in
//          email      - the unique email address of the member
//          password   - the hash of a secret key created by the member
//
//  Functions (7)
//      create_login_cookie
//      create_member
//      delete_login_cookie
//      destroy_member
//      is_admin
//      is_logged_in
//      login
//      login_cookie_exists
//      logout
//
//  Requirements
//      extensions
//          database
//          errors
//      $_SESSION
//
//
//  Notes
//      add functions: update profile, credential chk, dynamic database fields

require_once "database.php";
require_once "errors.php";

if (!(isset($_SESSION) || session_start())) throw new Exception("A session could not be started."); assert (isset($_SESSION));

define("LOGIN_COOKIE_SALT", "forshizzle"); assert (defined("LOGIN_COOKIE_SALT"));
define("LOGIN_COOKIE_TTL" , 60*60*24*365); assert (defined("LOGIN_COOKIE_TTL"));

$ACCOUNT = FALSE;

/********************************************
Description
	creates the automatic login cookie

Order Θ(1)

Parameters
	email
		the email address of the valid member

Return Values
	No value is returned.
	
********************************************/
function create_login_cookie ($email)
{
	//pre-conditions
	assert (is_string($email)
	&&      is_logged_in()
	&&      array_key_exists("SERVER_NAME", $_SERVER));
	
	if (login_cookie_exists()) return;
	
	$name       = "auto-login";
	$value      = $email."|".sha1(md5($_SESSION["member_id"].LOGIN_COOKIE_SALT));
	$expiration = time()+LOGIN_COOKIE_TTL;
	$directory  = "/";
	$domain     = ".".$_SERVER["SERVER_NAME"];
	if (!setcookie($name, $value, $expiration, $directory, $domain)) throw new Exception("Prior output has been detected while attempting to create the auto-login cookie.");
}

/*************************************************************************************************
Description
	creates a new member

Order Θ(n)

Parameters
	firstName
		the valid first name of the new member
	
	lastName
		the valid first name of the new member
	
	email
		the valid email address of the new member
	
	password
		the valid password of the new member
	
	[cConnection]
		a database link identifier in create mode
		If unspecified, a temporary one will be created for the life of the function.
	
	[extra]
		an associative array of any additional database fields and valid values that should be set
		If it is set to FALSE, no additional fields will be set.

Return Values
	No value is returned.

*************************************************************************************************/
function create_member ($firstName, $lastName, $email, $password, $cConnection=FALSE, $extra=FALSE)
{
	global $db;
	$link = ($cConnection === FALSE) ? $db->open_connection("create"): $cConnection;
	$firstName = filter_data($firstName, $link, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$lastName  = filter_data($lastName , $link, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	$email     = filter_data($email    , $link, FILTER_SANITIZE_EMAIL);
	$password  = filter_data($password , $link, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	$hash      = sha1(md5($password).md5($email));
	do {
		$memberID = rand(0,999999);
		while (strlen($memberID) < 6) $memberID .= rand(0,9);
		$matches = db_read("members", "id", $memberID, $link);
	} while (mysql_num_rows($matches) > 0);
	
	$fields = "`id`, `first_name`, `last_name`, `email`, `password`, `gender`, `last_edit`, `date_added`";
	$values = $memberID.", '".$firstName."', '".$lastName."', '".$email."', '".$hash."', '".$gender."', NOW(), NOW()";
	$db->create("members", $fields, $values, $link);
	
	if ($extra !== FALSE) foreach ($extra as $column=>$cell) {
		if (!is_numeric($column)) $column =     filter_data($column, $link, FILTER_SANITIZE_STRING);
		if (!is_numeric($cell))   $cell   = "'".filter_data($cell  , $link, FILTER_SANITIZE_STRING)."'";
		$db->edit("members", "`".$column."`=".$cell, "`id`=".$memberID);
	}
	
	if ($cConnection === FALSE) $db->close_connection($link);
}

/**************************************
Description
	destroys the automatic login cookie

Order Θ(1)

Parameters
	none

Return Values
	No value is returned.

**************************************/
function delete_login_cookie ()
{
	if (!login_cookie_exists()) return;
	if (!setcookie("auto-login", "", time()-LOGIN_COOKIE_TTL, "/", ".".$_SERVER["SERVER_NAME"]))
		throw new Exception("Prior output has been detected while attempting to remove the auto-login cookie.");
}

/**************************************
Description
	remove a member

Order Θ(1)

Parameters
	none

Return Values
	No value is returned.

**************************************/
function destroy_member ($memberID, $destroyConnection=FALSE)
{
	global $db;
	$link     = ($destroyConnection === FALSE) ? $db->open_connection("destroy") : $destroyConnection;
	$memberID = filter_data($memberID , $link, FILTER_SANITIZE_INT);
	
	$db->destroy("members", "`id`=".$memberID);
	
	if ($destroyConnection === FALSE) $db->close_connection($link);
}

/************************************************************************************
Description
	finds whether a user is recognized as an administrator

Order Θ(1)

Parameters
	[connection]
		the database link identifier
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	TRUE if the user is a recognized admin
	FALSE otherwise

************************************************************************************/
function is_admin ($connection=FALSE)
{
	if (!(array_key_exists("member", $_SESSION) && $_SESSION["member"])) return FALSE;
	
	global $db;
	$link    = ($connection === FALSE) ? $db->open_connection() : $connection;
	$matches = count($db->read("members", "`admin`=1 AND `id`=".$_SESSION["member_id"], $link));
	if ($matches > 1) throw new Exception("Duplicate admin members have been detected in the database for id #".$_SESSION["member_id"].'.');
	if ($connection === FALSE) $db->close_connection($link);
	
	return ($matches == 1);
}

/************************************************************************************
Description
	finds whether the user is logged in to a valid account

Order Θ(1)

Parameters
	[connection]
		the database link identifier
		If unspecified, a temporary one will be created for the life of the function.

Return Values
	TRUE if the user is logged in
	FALSE otherwise

************************************************************************************/
function is_logged_in ($connection=FALSE)
{
	if (array_key_exists("member", $_SESSION)) return $_SESSION["member"];
	
	if (array_key_exists("member_id", $_SESSION) && ($_SESSION["member_id"] !== FALSE)) {
		global $db;
		$link    = ($connection === FALSE) ? $db->open_connection() : $connection;
		$matches = count($db->read("members", "`id`=".$_SESSION["member_id"], $link));
		if ($matches > 1) throw new Exception("Duplicate member IDs have been detected in the database for id #".$_SESSION["member_id"].'.');
		if ($connection === FALSE) $db->close_connection($link);
		
		return ($matches == 1);
	}
	
	return FALSE;
}

/************************************************
Description
	logs a member in

Order Θ(1)

Parameters
	email
		the email address of the valid member
	
	[remember]
		TRUE creates the automatic login cookie
		FALSE (default) destroys it, if it exists

Return Values
	No value is returned.

************************************************/
function login ($email, $remember=FALSE)
{
	//pre-conditions
	assert (is_string($email));
	assert (is_bool($remember));
	
	global $db, $ACCOUNT;
	$link   = $db->open_connection("edit");
	$email  = filter_var($email, FILTER_SANITIZE_EMAIL);
	$member = $db->read("members", "`email`='".$email."'", $link);
	assert (!empty($member));
	
	$_SESSION["member"]      = TRUE;
	$_SESSION["member_id"]   = $member[0]["id"];
	$_SESSION["member_name"] = $member[0]["first_name"]." ".$member[0]["last_name"];
	if ($ACCOUNT != FALSE) foreach ($ACCOUNT as $key => $value) $_SESSION[$key] = $value;
	$db->edit("members", "`last_visit`=NOW()", "`id`=".$member[0]["id"], $link);
	
	if     ($remember)             create_login_cookie($email);
	elseif (login_cookie_exists()) delete_login_cookie();
	
	$db->close_connection($link);
}

/****************************************
Description
	checks for the automatic login cookie

Order Θ(1)

Parameters
	none

Return Values
	TRUE if it exists
	FALSE otherwise

****************************************/
function login_cookie_exists ()
{
	return (array_key_exists("auto-login", $_COOKIE));
}

/**************************
Description
	logs a valid member out

Order Θ(1)

Parameters
	none

Return Values
	No value is returned.

**************************/
function logout ()
{
	//pre-condition
	assert (is_logged_in());
	
	if (login_cookie_exists()) delete_login_cookie();
	session_unset();
	if (!session_destroy()) throw new Exception("An active session could not be destroyed.");
}

/**/

if (!array_key_exists("member"     , $_SESSION)) $_SESSION["member"]      = (is_logged_in());
if (!array_key_exists("member_id"  , $_SESSION)) $_SESSION["member_id"]   = FALSE;
if (!array_key_exists("member_name", $_SESSION)) $_SESSION["member_name"] = FALSE;

?>