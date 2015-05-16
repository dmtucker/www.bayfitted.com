<?php
	require_once "default/initial.php";
	
	if     (array_key_exists("error"  , $_GET) && !empty($_GET["error"]))   $mode = "error";
	elseif (array_key_exists("success", $_GET) && !empty($_GET["success"])) $mode = "success";
	else redirect("/index.php");
?>
<style type="text/css">
p {
	text-align: center;
	font-size: 14px;
}
</style>
<?php
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<div class="box" style="width:500px; margin:50px auto; padding-bottom:25px;">
<?php
	switch ($_GET[$mode]) {
		case "403":
		case "404":
		case "not_found":
			//precondition
			assert ($mode == "error");
			
			?><h1>uuuuuuhhhhhh.....</h1>
			<p>
				The page you requested could not be found.<br />
				Check the URL for proper spelling and capitalization.<br />
				If you're having trouble finding something on Bayfitted,<br />
				you can access a list of all pages <a href="sitemap.php">here</a>.
			</p><?php
			
			break;
		
		case "logout":
			//precondition
			assert ($mode == "success");
			
			?><h1>It is done.</h1>
			<p>You have been logged out successfully.</p><?php
			
			break;
		
		case "reset":
			//precondition
			assert ($mode == "success");
			
			?><h1>Your session has been reset successfully.</h1><?php
			
			break;
		
		case "sent":
			//precondition
			assert ($mode == "success");
			
			?><h1>Thank You!</h1>
			<p>
				Your message has been successfully submitted!<br />
				Your feedback is very important to us and critical to providing the best service for our customers.
			</p><?php
			
			break;
		
		case "transaction":
			if ($mode == "success") {
				?><h1>Thank You!</h1>
				<p>
					Your purchase has been successfully completed!<br />
					We appreciate your business and hope you'll visit us again soon.
				</p><?php
			} else {
				?><h1>Oh No!</h1>
				<p>
					We have had a problem handling your purchase!<br />
					It's probably not your fault, and the issue has already been reported.<br />
					We appreciate your patience and will be in contact with you soon!
				</p><?php
			}
			
			break;
		
		case "test":
			?><h1>(<?php echo $mode; ?> mode)</h1><?php
			
			break;
		
		case "restricted":
			//precondition
			assert ($mode == "error");
			?><h1>Woah, hang on a sec...</h1>
			<p>
				You are trying to access an area of the site that is not meant for you.<br />
				<br />
				We appreciate your ambition though,<br />
				consider sending us your <a href="feedback.php" style="vertical-align:top;">suggestions & feedback.</a>
			</p><?php
			break;
		
		default:
			report_error("A requested message (".$_GET[$mode].") could not be found.");
			?><h1>(no message)</h1><?php
			
			break;
	}
?>
	<p style="font-style:italic; margin-top:25px;">
		Sincerely,<br />
		the Bayfitted team
	</p>
</div>
<?php include "default/wrapper.php"; ?>