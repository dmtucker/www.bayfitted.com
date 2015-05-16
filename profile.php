<?php
	require_once "default/initial.php";
	
	if (!is_logged_in()) redirect("/login.php");
	if (!array_key_exists("mode", $_GET) || !in_array($_GET["mode"], array("overview","edit"))) redirect("/profile.php?mode=overview");
	
	function add_to_likes ()
	{
		//preconditions
		assert (is_numeric($_POST["add_to_likes"]));
		
		global $db;
		$link    = $db->open_connection("edit");
		$member  = $db->read("members", "`id`=".$_SESSION["member_id"], $link);
		assert (count($member) == 1);
		$member  = $member[0];
		$items   = (empty($member["likes"])) ? array(): explode(',', $member["likes"]);
		$items[] = $_POST["add_to_likes"];
		$db->edit("members", "likes='".implode(',', $items)."'", "id=".$_SESSION["member_id"], $link);
		$db->close_connection($link);
	}
	
	function remove_from_likes ()
	{
		//preconditions
		assert(is_numeric($_POST["remove_from_likes"]));
		
		global $db;
		$link    = $db->open_connection("edit");
		$member  = $db->read("members", "`id`=".$_SESSION["member_id"], $link);
		assert (count($member) == 1);
		$items = explode(',', $member[0]["likes"]);
		unset($items[array_search($_POST["remove_from_likes"], $items)]);
		$db->edit("members", "likes='".implode(',', $items)."'", "id=".$_SESSION["member_id"], $link);
		$db->close_connection($link);
	}
	
	function review_update ($member)
	{
		//preconditions
		assert (array_key_exists("first_name"      , $_POST));
		assert (array_key_exists("last_name"       , $_POST));
		assert (array_key_exists("newEmail"        , $_POST));
		assert (array_key_exists("newEmailCheck"   , $_POST));
		assert (array_key_exists("newPassword"     , $_POST));
		assert (array_key_exists("newPasswordCheck", $_POST));
		assert (array_key_exists("gender"          , $_POST));
		assert (array_key_exists("currentPassword" , $_POST));
		
		$errorCodes["indicate"] = "errors";
		$updated["indicate"]    = "valid";
		
		//authorization
		$updated["authorization"] = FALSE;
		$hashedPassword           = sha1(md5($_POST["currentPassword"]).md5($member["email"]));
		if     (empty($_POST["currentPassword"]))       $errorCodes[] = 80;
		elseif ($hashedPassword != $member["password"]) $errorCodes[] = 81;
		else $updated["authorization"] = $_POST["currentPassword"];
		
		//first name
		if     (empty($_POST["first_name"]))                        $errorCodes[] = 10;
		elseif (preg_match("/[^a-z ]/i", $_POST["first_name"]) > 0) $errorCodes[] = 11;
		else $updated["first_name"] = ($_POST["first_name"] == $member["first_name"]) ? $member["first_name"] : $_POST["first_name"];
		
		//last name
		if     (empty($_POST["last_name"]))                        $errorCodes[] = 20;
		elseif (strlen($_POST["last_name"]) < 2)                   $errorCodes[] = 21;
		elseif (preg_match("/[^a-z ]/i", $_POST["last_name"]) > 0) $errorCodes[] = 22;
		else $updated["last_name"] = ($_POST["last_name"] == $member["last_name"]) ? $member["last_name"] : $_POST["last_name"];
		
		//email
		if     (empty($_POST["newEmail"]) || $_POST["newEmail"] == $member["email"]) $updated["email"] = $member["email"];
		elseif (is_spam($_POST["newEmail"]))                   $errorCodes[] = 30;
		elseif (empty($_POST["newEmailCheck"]))                $errorCodes[] = 40;
		elseif ($_POST["newEmail"] != $_POST["newEmailCheck"]) $errorCodes[] = 41;
		else {
			$email  = filter_var($_POST["newEmail"], FILTER_SANITIZE_EMAIL);
			$result = $db->read("members", "`email`='".$email."'");
			if (count($result) > 0) $errorCodes[] = 31;
			else $updated["email"] = $email;
		}
		
		//password
		if     (empty($_POST["newPassword"]) || $_POST["newPassword"] == $updated["authorization"]) $updated["password"] = $updated["authorization"];
		elseif (strlen($_POST["newPassword"]) < 4)                   $errorCodes[] = 50;
		elseif (empty($_POST["newPasswordCheck"]))                   $errorCodes[] = 60;
		elseif ($_POST["newPassword"] != $_POST["newPasswordCheck"]) $errorCodes[] = 61;
		else $updated["password"] = filter_var($_POST["newPassword"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
		
		//gender
		if     (!array_key_exists("gender", $_POST) || empty($_POST["gender"])) $errorCodes[] = 70;
		elseif ($_POST["gender"] != 'm' && $_POST["gender"] != 'f')             $errorCodes[] = 71;
		else $updated["gender"] = $_POST["gender"];
		
		//remember
		$updated["remember"] = (array_key_exists("remember", $_POST));
		
		unset($updated["authorization"]);
		return (count($errorCodes) == 1) ? $updated : $errorCodes;
	}
	
	function find_errors ($results)
	{
		$formFields = array("first_name", "last_name", "newEmail", "newEmailCheck", "newPassword", "newPasswordCheck", "gender", "remember", "currentPassword");
		$error      = FALSE;
		foreach ($formFields as $field) $error[$field] = FALSE;
		foreach ($results as $errorCode) {
			$errorField = ($errorCode < 10) ? $errorCode : $errorCode-($errorCode%10);
			switch ($errorField) {
				case 10: $errors["first_name"]       = TRUE; break;
				case 20: $errors["last_name"]        = TRUE; break;
				case 30: $errors["newEmail"]         = TRUE;
				case 40: $errors["newEmailCheck"]    = TRUE; break;
				case 50: $errors["newPassword"]      = TRUE;
				case 60: $errors["newPasswordCheck"] = TRUE; break;
				case 70: $errors["gender"]           = TRUE; break;
				case 80: $errors["currentPassword"]  = TRUE; break;
				default: report_error("An invalid error field (".$errorField.") has been detected.");
			}
		}
		
		return $errors;
	}
	
	function update_error_message ($errorCode)
	{
		switch ($errorCode) {
			default:case  0: return  FALSE;
					case 10: return "We still need your first name!";
					case 11: return "You entered a non-alphabetic character in the first name field.";
					case 20: return "We still need your last name!";
					case 21: return "Your last name entry must be at least 2 characters long.";
					case 22: return "You entered a non-alphabetic character in the last name field.";
					case 30: return "You entered an invalid email address.";
					case 31: return "The email address you provided is already in use.";
					case 40: return "We still need something to verify your email address with!";
					case 41: return "The emails you entered do not match.";
					case 50: return "Your new password must be at least 4 characters long.";
					case 60: return "We still need something to verify your new password with!";
					case 61: return "The passwords you entered do not match.";
					case 70: return "We still need your gender!";
					case 71: return "Warning: Invalid gender.";
					case 80: return "Your current password is required to make any changes.";
					case 81: return "The current password you provided is incorrect.";
		}
	}
	
	function update_member_record ($member, $update)
	{
		$update["password"] = sha1(md5($update["password"]).md5($update["email"]));
		
		$memberName              = explode(' ', $_SESSION["member_name"]);
		$memberName[0]           = $update["first_name"];
		$memberName[1]           = $update["last_name"];
		$_SESSION["member_name"] = implode(' ', $memberName);
		
		if ($update["remember"]) create_login_cookie($update["email"]);
		else                     delete_login_cookie();
		unset($update["remember"]);
		
		global $db;
		$link = $db->open_connection("edit");
		foreach ($update as $field => $value) $db->edit("members", "`".$field."`='".$value."'", "`id`=".$member['id'], $link);
		$db->close_connection($link);
	}
	
	
	
	$itemAdded   = (array_key_exists("add_to_likes"     , $_POST));
	$itemRemoved = (array_key_exists("remove_from_likes", $_POST));
	if     ($itemAdded)   add_to_likes();
	elseif ($itemRemoved) remove_from_likes();
	
	$newForm  = (array_key_exists("submission", $_POST) && $_POST["submission"] == "update");
	$editMode = ($_GET["mode"] == "edit");
	$member   = $db->read("members", "`id`=".$_SESSION["member_id"]);
	assert (!empty($member));
	$member = $member[0];
	
	if ($newForm) {
		$results = review_update($member);
		if ($results["indicate"] == "errors") {
			unset($results["indicate"]);
			$error = find_errors($results);
			assert ($error !== FALSE);
		} else {
			unset($results["indicate"]);
			update_member_record($member, $results);
			redirect("/profile.php");
		}
	}
	
	assert (!$newForm || isset($error)); //only new or invalid apps should be displayed	
	
	include_once "default/doctype.php";
?>
<style type="text/css">
.product {
	margin-bottom:50px;
}
#update {
	width: 80%;
	margin:25px auto;
	text-align:center;
}
#update #gender-input,
#update input[type="text"],
#update input[type="email"],
#update input[type="password"] {
	width:180px;
	margin:2px auto;
	border:2px outset #FFF;
	border-radius:5px;
	padding:5px;
	text-align:center;
}
#update input[type="text"],
#update input[type="email"],
#update input[type="password"] {
	font:bold 15px "Comic Sans MS";

}
#update #gender-input {
	display:inline-block;
}
#update #gender-input div {
	display:inline-block;
	margin:auto 10px;
}
#update #gender-input label {
	margin-left:5px;
}
#update #gender-input #m {
	margin-left: 20px;
}
#update #gender-input input {
	vertical-align:top;
}
</style>
<?php include "default/uniform.php"; ?>
<h1 style="margin-bottom:5px;"><?php echo $_SESSION["member_name"]; ?></h1>
<p style="font-size:10px; color:#444; word-spacing:0px; letter-spacing:3px; font-style:italic; margin-bottom:25px; text-align:center;">Bayfitted since <?php echo smart_date(strtotime($member["date_added"])); ?></p>
<?php
	if ($editMode) {
		if ($newForm) { foreach ($results as $errorCode) : ?><p style="color:#F00;"><?php echo update_error_message($errorCode); ?></p><?php endforeach; }
		
		?><div class="form box" style="width:475px; margin-bottom:50px;">
			<form id="update" action="profile.php?edit=true" method="post">
				<h2 style="margin-top:0px;">Edit</h2>
				<input type="hidden" name="submission" value="update" />
					<div style="text-align:right; display:inline-block; margin-bottom:25px;">
						<label>First Name:</label>
						<?php
							if ($newForm) {
								if ($error["first_name"]): ?><input type="text" name="first_name" /><span style="color:#F00;">*</span><?php
								else                     : ?><input type="text" name="first_name" value="<?php echo $_POST["first_name"]; ?>" /><?php
								endif;
							} else { ?><input type="text" name="first_name" value="<?php echo $member["first_name"]; ?>" /><?php }
						?><br />
						<label>Last Name:</label>
						<?php
							if ($newForm) {
								if ($error["last_name"]): ?><input type="text" name="last_name" /><span style="color:#F00;">*</span><?php
								else                    : ?><input type="text" name="last_name" value="<?php echo $_POST["last_name"]; ?>" /><?php
								endif;
							} else { ?><input type="text" name="last_name" value="<?php echo $member["last_name"]; ?>" /><?php }
							?><br />
						<label>Gender:</label>
						<div id="gender-input"<?php if ($newForm && $error["gender"]): ?> style="background-color:#FC0;"<?php endif; ?>>
							<input type="radio" id="f" name="gender" value="f" <?php if ($member["gender"] == "f") echo 'checked="checked"'; ?> onclick="document.getElementById('gender-input').style.backgroundColor='#000';" /><label for="f">Female</label>
							<input type="radio" id="m" name="gender" value="m" <?php if ($member["gender"] == "m") echo 'checked="checked"'; ?> onclick="document.getElementById('gender-input').style.backgroundColor='#000';" /><label for="m">Male</label>
						</div>
						<h3 style="margin:10px auto; color:#666; margin-top:25px;"><?php echo $member["email"]; ?></h3>
						<label>New E-mail Address:</label>
						<?php
							if ($newForm) {
								if ($error["newEmail"]): ?><input type="email" name="newEmail" /><span style="color:#F00;">*</span><?php
								else                   : ?><input type="email" name="newEmail" value="<?php echo $_POST["newEmail"]; ?>" /><?php
								endif;
							} else { ?><input type="email" name="newEmail" /><?php }
						?><br />
						<label>Verify your new e-mail address:</label>
						<?php
							if ($newForm) {
								if ($error["newEmailCheck"]): ?><input type="email" name="newEmailCheck" /><span style="color:#F00;">*</span><?php
								else                        : ?><input type="email" name="newEmailCheck" value="<?php echo $_POST["newEmailCheck"]; ?>" /><?php
								endif;
							} else { ?><input type="email" name="newEmailCheck" /><?php }
						?><br />
						<label>New Password:</label>
						<?php
							if ($newForm) {
								if ($error["newPassword"]): ?><input type="password" name="newPassword" /><span style="color:#F00;">*</span><?php
								else                      : ?><input type="password" name="newPassword" value="<?php echo $_POST["newPassword"]; ?>" /><?php
								endif;
							} else { ?><input type="password" name="newPassword" /><?php }
						?><br />
						<label>Verify your new password:</label>
						<?php
							if ($newForm) {
								if ($error["newPasswordCheck"]): ?><input type="password" name="newPasswordCheck" /><span style="color:#F00;">*</span><?php
								else                           : ?><input type="password" name="newPasswordCheck" value="<?php echo $_POST["newPasswordCheck"]; ?>" /><?php
								endif;
							} else { ?><input type="password" name="newPasswordCheck" /><?php }
						?>
						<div style="margin:25px auto; text-align:center;">
							<input type="checkbox" id="remember" name="remember" <?php if (login_cookie_exists()) echo "checked='checked'"; ?>/>&nbsp;
							<label for="remember">Automatically keep me logged in until I log out.</label>
						</div>
						<div style="margin-top:25px;">
							<label>Current Password</label>
							<?php
								if ($newForm) {
									if ($error["currentPassword"]): ?><input type="password" name="currentPassword" /><span style="color:#F00;">*</span><?php
										else                          : ?><input type="password" name="currentPassword" value="<?php echo $_POST["currentPassword"]; ?>" /><?php
										endif;
									} else { ?><input type="password" name="currentPassword" /><?php }
							?><br />
							<p style="color:#F00; margin:5px 20px auto auto;">(required to make changes)</p>
						</div>
					</div>
					<input type="submit" value="save" />
				</form>
			</div><?php
	} else {
		if (login_cookie_exists()): ?><p style="text-align:center; margin:25px auto;">You will remain logged in automatically until you log out.</p><?php endif; 
		
		if (!empty($member["likes"])) {
			$link  = $db->open_connection();
			$likes = explode(',', $member["likes"]);
			
			?><hr style="margin:25px auto; clear:both;" />
			<h2>Things I Like</h2>
			<table class="product"><?php
			
			$cells = 0;
			foreach ($likes as $like) {
				if ($cells%4 == 0): ?><tr><?php endif; //4 cells per row row
				
				?><td style="vertical-align:top;"><?php
				echo thumbnail($like, $link);
				?></td><?php
				
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
		}
	}
	
	include "default/wrapper.php";
?>