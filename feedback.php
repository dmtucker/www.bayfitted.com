<?php
	require_once "default/initial.php";
	
	
	
	function feedback_error_message ($errorCode)
	{
		switch ($errorCode) {
			default:case 0:  return  FALSE;
					case 10: return "We still need your name or email address!";
					case 20: return "You must include a message.";
			
		}
	}
	
	function review_feedback ()
	{
		$errorCodes = FALSE;
		if (empty($_POST["sender"]))   $errorCodes[] = 10;
		if (empty($_POST["message"]))  $errorCodes[] = 20;
		
		return $errorCodes;
	}
	
	
	
	$newForm = (array_key_exists("submission", $_POST) && $_POST["submission"] == "feedback");
	$errors = FALSE;
	
	if ($newForm) {
		
		assert (array_key_exists("sender",  $_POST)
		&&      array_key_exists("message", $_POST));
		
		$errors = review_feedback();
		
		if ($errors === FALSE) {
			$link    = $db->open_connection();
			$sender  = filter_var($_POST["sender"],  FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			$message = filter_var($_POST["message"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
			$db->close_connection($link);
			
			if (!mail("david@bayfitted.com", "Bayfitted feedback", $message, "From: ".$sender))
				report_error("Looks like some feedback got lost in the mail! \"".$_POST["sender"]."\" wrote: \"".$_POST["message"]."\"");
			
			redirect("/message.php?success=sent");
		} else {
			$formFields = array("sender", "message");
			foreach ($formFields as $field) $error[$field] = FALSE;
			foreach ($errors as $errorCode) {
				$errorField = $errorCode-($errorCode%10);
				switch ($errorField) {
					case 10: $error["sender"]  = TRUE; break;
					case 20: $error["message"] = TRUE; break;
					default: report_error("An invalid error field (".$errorField.") has been detected.");
				}
			}
		}
	}
	
	assert (!$newForm || $errors !== FALSE); //only new or invalid apps should be displayed
		
	include_once "default/doctype.php";
?>
<style type="text/css">
#feedback {
	width:80%;
	margin:25px auto;
}
#feedback textarea, #feedback #sender {
	width:100%;
	margin-top:10px;
	margin-left:-5px;
	padding:5px;
	border:2px outset #EEE;
	border-radius:5px;
	font-family:"Comic Sans MS";
	font-weight:bold;
	font-size:15px;
}
#facebook {
	display:inline-block;
	margin-top:25px;
	text-align:center;
	color:#999;
}
#facebook:hover {
	color:#FFF;
}
</style>
<?php include "default/uniform.php"; ?>
<h1>How are we doing?</h1>
<?php if ($errors !== FALSE): ?><p class="error"><?php foreach ($errors as $errorCode) echo feedback_error_message($errorCode)."<br />"; ?></p><?php endif; ?>
<div class="form box" style="width:700px;">
	<form id="feedback" action="feedback.php" method="post">
		<h2 style="margin-top:0px;">Feedback</h2>
		<input type="hidden" name="submission" value="feedback" />
		<div>
			<label for="message">Comments</label>
			<?php
				if ($newForm)
					if ($error["message"]): ?><textarea id="message" name="message" rows="10" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';"></textarea><?php
					else                  : ?><textarea id="message" name="message" rows="10"><?php echo $_POST["message"]; ?></textarea><?php
					endif;
				else { ?><textarea id="message" name="message" rows="10"></textarea><?php }
			?>
		</div>
		<div style="margin:25px auto;">
			<label for="sender">Name</label><br />
			<?php
				if ($newForm)
					if ($error["sender"]): ?><input id="sender" name="sender" type="text" style="background-color:#FC0;" onfocus="this.style.backgroundColor='#FFF';" /><?php
					else                 : ?><input id="sender" name="sender" type="text" value="<?php echo $_POST["sender"]; ?>" /><?php
					endif;
				else { ?><input id="sender" name="sender" type="text" /><?php }
			?>
		</div>
		<div style="text-align:center;">
			<input type="submit" value="send" />
		</div>
	</form>
</div>
<div style="text-align:center;">
	<a href="http://www.facebook.com/sharer.php?post_form_id=d7ae4af1733bc309c33c82d5dddfb56c&amp;u=http%3A%2F%2Fwww.bayfitted.com&amp;appid=2309869772" onClick="return popup(this, 'Bayfitted')">
		<div id="facebook">
			<img src="resources/images/facebook-logo.png" alt="Share us with your friends on Facebook!" style="display:inline-block;" />
			<p style="display:inline-block; margin-bottom:50px;">Share us with your friends on Facebook!</p>
		</div>
	</a>
</div>
<?php include "default/wrapper.php"; ?>