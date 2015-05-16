<?php
	require_once "default/initial.php";
	
	if (is_logged_in()) redirect("/profile.php");
	
	function check_credentials ()
	{
		$errorCodes = FALSE;
		if (empty($_POST["email"]))                                                $errorCodes[] = 20;
		if (is_spam($_POST["email"]))                                              $errorCodes[] = 21;
		if (empty($_POST["password"]))                                             $errorCodes[] = 30;
		if (strlen($_POST["password"]) < 4)                                        $errorCodes[] = 31;
		
		if ($errorCodes == FALSE) {
			global $db;
			$email          = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
			$hashedPassword = sha1(md5($_POST["password"]).md5($_POST["email"]));
			$record         = $db->read("members", "`email`='".$email."'");
			if (empty($record) || $record[0]["password"] != $hashedPassword)          $errorCodes[] = 10;
		}
		
		return $errorCodes;
	}
	
	function login_error_message ($errorCode)
	{
		switch ($errorCode) {
			default:case 0:  return  FALSE;
					case 10: return "The credentials you entered do not match our records.";
					case 20: return "We still need your email address!";
					case 21: return "You entered an invalid email address.";
					case 30: return "We still need your password!";
					case 31: return "Your password must be at least 4 characters long.";
			
		}
	}
	
	
	
	$newForm = (array_key_exists("submission", $_POST) && $_POST["submission"] == "credentials");
	$errors  = FALSE;
	
	if ($newForm) {
		//preconditions
		assert (array_key_exists("email", $_POST) && array_key_exists("password", $_POST));
		
		$errors = check_credentials();
			
		if ($errors === FALSE) {
			login($_POST["email"], array_key_exists("remember", $_POST));
			redirect("/profile.php");
		} else {
			$formFields = array("email", "password");
			foreach ($formFields as $field) $error[$field] = FALSE;
			foreach ($errors as $errorCode) {
				$errorField = $errorCode-($errorCode%10);
				switch ($errorField) {
					case 10: $error["password"] = TRUE;
					case 20: $error["email"]    = TRUE; break;
					case 30: $error["password"] = TRUE; break;
					default: report_error("An invalid error field (".$errorField.") has been detected.");
				}
			}
		}
	}
	
	assert (!$newForm || $errors !== FALSE); //only new or invalid apps should be displayed
	
	include_once "default/doctype.php";
?>
<style type="text/css">
#credentials {
	width: 80%;
	margin:25px auto;
	text-align:center;
}
#credentials input[type="email"],
#credentials input[type="password"] {
	width:180px;
	margin:2px auto;
	border:2px outset #FFF;
	border-radius:5px;
	padding:5px;
	text-align:center;
	font:bold 15px "Comic Sans MS";
}
</style>
<?php include "default/uniform.php"; ?>
<h1>Login</h1>
<?php if ($errors !== FALSE): ?><p class="error"><?php foreach ($errors as $errorCode) echo login_error_message($errorCode)."<br />"; ?></p><?php endif; ?>
<div class="form box" style="width:400px;">
	<form id="credentials" action="login.php" method="post">
		<h2>Credentials</h2>
		<input type="hidden" name="submission" value="credentials" />
		<div style="text-align:right; display:inline-block;">
			<label>E-mail:</label>
			<?php
				if ($newForm) {
					if ($error["email"]): ?><input type="email" name="email" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                : ?><input type="email" name="email" value="<?php echo $_POST["email"]; ?>" /><?php
					endif;
				} else { ?><input type="email" name="email" /><?php }
			?><br />
			<label>Password:</label>
			<?php
				if ($newForm) {
					if ($error["password"]): ?><input type="password" name="password" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                   : ?><input type="password" name="password" value="<?php echo $_POST["password"]; ?>" /><?php
					endif;
				} else { ?><input type="password" name="password" /><?php }
			?>
		</div>
		<div style="margin:25px auto;">
			<input type="checkbox" id="remember" name="remember" <?php if (array_key_exists("remember", $_POST)) echo "checked='checked'"; ?>/>&nbsp;
			<label for="remember">Automatically keep me logged in until I log out.</label><br />
		</div>
		<input type="submit" value="submit" />
	</form>
</div>
<p style="margin:25px auto; text-align:center;"><a href="create.php">create a profile</a></p>
<?php include "default/wrapper.php"; ?>