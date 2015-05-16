<?php
	require_once "default/initial.php";
	
	if (is_logged_in()) redirect("/profile.php");
	
	
	
	function application_error_message ($errorCode)
	{
		switch ($errorCode) {
			default:case 0:  return  FALSE;
					case 10: return "We still need your first name!";
					case 11: return "You entered a non-alphabetic character in the first name field.";
					case 20: return "We still need your last name!";
					case 21: return "Your last name entry must be at least 2 characters long.";
					case 22: return "You entered a non-alphabetic character in the last name field.";
					case 30: return "We still need your email address!";
					case 31: return "You entered an invalid email address.";
					case 32: return "The email address you provided is already in use.";
					case 40: return "We still need something to verify your email address with!";
					case 41: return "The emails you entered do not match.";
					case 50: return "We still need you to choose a password!";
					case 51: return "Your password must be at least 4 characters long.";
					case 60: return "We still need something to verify your password with!";
					case 61: return "The passwords you entered do not match.";
					case 70: return "We still need your gender!";
					case 71: return "Error: Invalid gender.";
		}
	}
	
	function create_new_member ()
	{
		global $db;
		$link           = $db->open_connection("create");
		$firstName      = filter_var($_POST["firstName"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		$lastName       = filter_var($_POST["lastName"] , FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		$email          = filter_var($_POST["email"]    , FILTER_SANITIZE_EMAIL);
		$password       = filter_var($_POST["password"] , FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		$gender         = filter_var($_POST["gender"]   , FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
		$hashedPassword = sha1(md5($password).md5($email));
		
		//generate member id
		do {
			$memberID=rand(0,999999);                                 //random
			while (strlen($memberID) < 6) $memberID .= rand(0,9);     //6-digits
			$result = $db->read("members", "`id`=".$memberID, $link); //unique
		} while (count($result) > 0);
		
		$db->create("members",
					"`id`, `first_name`, `last_name`, `email`, `password`, `gender`, `last_edit`, `date_added`",
					$memberID.", '".$firstName."', '".$lastName."', '".$email."', '".$hashedPassword."', '".$gender."', NOW(), NOW()",
					$link);
		
		$db->close_connection($link);
	}
	
	function review_application ()
	{
		$errorCodes = FALSE;
		if (empty($_POST["firstName"]))                                                   $errorCodes[] = 10;
		if (preg_match("/[^a-z ]/i", $_POST["firstName"]) > 0)                            $errorCodes[] = 11;
		if (empty($_POST["lastName"]))                                                    $errorCodes[] = 20;
		if (strlen($_POST["lastName"]) < 2)                                               $errorCodes[] = 21;
		if (preg_match("/[^a-z ]/i", $_POST["lastName"]) > 0)                             $errorCodes[] = 22;
		if (empty($_POST["email"]))                                                       $errorCodes[] = 30;
		if (is_spam($_POST["email"]))                                                     $errorCodes[] = 31;
		if (empty($_POST["emailCheck"]))                                                  $errorCodes[] = 40;
		if ($_POST["email"] != $_POST["emailCheck"])                                      $errorCodes[] = 41;
		if (empty($_POST["password"]))                                                    $errorCodes[] = 50;
		if (strlen($_POST["password"]) < 4)                                               $errorCodes[] = 51;
		if (empty($_POST["passwordCheck"]))                                               $errorCodes[] = 60;
		if ($_POST["password"] != $_POST["passwordCheck"])                                $errorCodes[] = 61;
		if (!array_key_exists("gender", $_POST) || empty($_POST["gender"]))               $errorCodes[] = 70;
		elseif ($_POST["gender"] != 'm' && $_POST["gender"] != 'f') {                     $errorCodes[] = 71;
			trigger_error("An invalid gender (".$_POST["gender"].") has been detected.");
		}
		
		//check for duplicate emails
		if ($errorCodes === FALSE) {
			global $db;
			$link           = $db->open_connection();
			$email          = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
			$matchingEmails = $db->read("members", "`email`='".$email."'", $link);
			if (count($matchingEmails) > 0)                                               $errorCodes[] = 32;
			$db->close_connection($link);
		}
		
		return $errorCodes;
	}
	
	
	
	$newForm = (array_key_exists("submission", $_POST) && $_POST["submission"] == "application");
	$errors  = FALSE;
	
	if ($newForm) {
		
		//preconditions
		assert ((array_key_exists("firstName"    , $_POST))
		&& 		(array_key_exists("lastName"     , $_POST))
		&& 		(array_key_exists("email"        , $_POST))
		&&		(array_key_exists("emailCheck"   , $_POST))
		&&		(array_key_exists("password"     , $_POST))
		&&		(array_key_exists("passwordCheck", $_POST)));
		
		$errors = review_application();
		
		if ($errors === FALSE) {
			create_new_member();
			login($_POST["email"], array_key_exists("remember", $_POST));
			redirect("/profile.php");
		} else {
			$formFields = array("firstName", "lastName", "email", "emailCheck", "password", "passwordCheck", "gender", "remember");
			foreach ($formFields as $field) $error[$field] = FALSE;
			foreach ($errors as $errorCode) {
				$errorField = $errorCode-($errorCode%10);
				switch ($errorField) {
					case 10: $error["firstName"]     = TRUE; break;
					case 20: $error["lastName"]      = TRUE; break;
					case 30: $error["email"]         = TRUE;
					case 40: $error["emailCheck"]    = TRUE; break;
					case 50: $error["password"]      = TRUE;
					case 60: $error["passwordCheck"] = TRUE; break;
					case 70: $error["gender"]        = TRUE; break;
					default: report_error("An invalid error field (".$errorField.") has been detected.");
				}
			}
		}
	}
	
	assert (!$newForm || $errors !== FALSE); //only new or invalid apps should be displayed
	
	include_once "default/doctype.php";
?>
<style type="text/css">
#application {
	width: 80%;
	margin:25px auto;
	text-align:center;
}
#application #gender-input,
#application input[type="text"],
#application input[type="email"],
#application input[type="password"] {
	width:180px;
	margin:2px auto;
	border:2px outset #FFF;
	border-radius:5px;
	padding:5px;
	text-align:center;
}
#application input[type="text"],
#application input[type="email"],
#application input[type="password"] {
	font:bold 15px "Comic Sans MS";

}
#application #gender-input {
	display:inline-block;
}
#application #gender-input div {
	display:inline-block;
	margin:auto 10px;
}
#application #gender-input label {
	margin-left:5px;
}
#application #gender-input #m {
	margin-left: 20px;
}
#application #gender-input input {
	vertical-align:top;
}
</style>
<?php include "default/uniform.php"; ?>
<h1>Join Bayfitted</h1>
<?php if ($errors !== FALSE): ?><p class="error"><?php foreach ($errors as $errorCode) echo application_error_message($errorCode)."<br />"; ?></p><?php endif; ?>
<div class="form box" style="width:400px;">
	<form id="application" action="create.php" method="post">
		<h2 style="margin-top:0px;">Application</h2>
		<input type="hidden" name="submission" value="application" />
		<div style="text-align:right; display:inline-block;">
			<label>First Name:</label>
			<?php
				if ($newForm) {
					if ($error["firstName"]): ?><input type="text" name="firstName" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                    : ?><input type="text" name="firstName" value="<?php echo $_POST["firstName"]; ?>" /><?php
					endif;
				} else { ?><input type="text" name="firstName" /><?php }
			?><br />
			<label>Last Name:</label>
			<?php
				if ($newForm) {
					if ($error["lastName"]): ?><input type="text" name="lastName" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                   : ?><input type="text" name="lastName" value="<?php echo $_POST["lastName"]; ?>" /><?php
					endif;
				} else { ?><input type="text" name="lastName" /><?php }
			?><br />
			<label>E-mail Address:</label>
			<?php
				if ($newForm) {
					if ($error["email"]): ?><input type="email" name="email" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                : ?><input type="email" name="email" value="<?php echo $_POST["email"]; ?>" /><?php
					endif;
				} else { ?><input type="email" name="email" /><?php }
			?><br />
			<label>Verify e-mail:</label>
			<?php
				if ($newForm)
					if ($error["emailCheck"]): ?><input type="email" name="emailCheck" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                     : ?><input type="email" name="emailCheck" value="<?php echo $_POST["emailCheck"]; ?>" /><?php
					endif;
				else { ?><input type="email" name="emailCheck" /><?php }
			?><br />
			<label>Password:</label>
			<?php
				if ($newForm)
					if ($error["password"]): ?><input type="password" name="password" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                   : ?><input type="password" name="password" value="<?php echo $_POST["password"]; ?>" /><?php
					endif;
				else { ?><input type="password" name="password" /><?php }
			?><br />
			<label>Verify password:</label>
			<?php
				if ($newForm)
					if ($error["passwordCheck"]): ?><input type="password" name="passwordCheck" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                        : ?><input type="password" name="passwordCheck" value="<?php echo $_POST["passwordCheck"]; ?>" /><?php
					endif;
				else { ?><input type="password" name="passwordCheck" /><?php }
			?><br />
			<label>Gender:</label>
			<div id="gender-input"<?php if ($newForm && $error["gender"]): ?> style="background-color:#FC0;"<?php endif; ?>>
				<input type="radio" id="f" name="gender" value="f" <?php if ($newForm && $_POST["gender"] == "f") echo 'checked="checked"'; ?> onclick="document.getElementById('gender-input').style.backgroundColor='#000';" /><label for="f">Female</label>
				<input type="radio" id="m" name="gender" value="m" <?php if ($newForm && $_POST["gender"] == "m") echo 'checked="checked"'; ?> onclick="document.getElementById('gender-input').style.backgroundColor='#000';" /><label for="m">Male</label>
			</div>
		</div>
		<div style="margin:25px auto;">
			<input type="checkbox" id="remember" name="remember" <?php if (array_key_exists("remember", $_POST)) echo "checked='checked'"; ?>/>&nbsp;
			<label for="remember">Automatically keep me logged in until I log out.</label><br />
		</div>
		<input type="submit" value="Create" />
	</form>
</div>
<p style="margin:25px auto; text-align:center;"><a href="login.php">Already have a profile?</a></p>
<?php include "default/wrapper.php"; ?>