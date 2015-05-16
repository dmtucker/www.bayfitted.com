<?php
	require_once "../default/initial.php";
	if (!is_admin()) redirect("/message.php?error=restricted");
	global $db;
	$link = $db->open_connection();
	include_once "default/doctype.php";
?>
<script type="text/javascript">
	function updateBulletinBoard ()
	{
		document.forms["manager-board"].submit();
		document.getElementById("character-count").innerHTML=Math.round(((document.forms["manager-board"]["notes"].value.length)/65535)*100)+"% used";
	}
</script>
<style type="text/css">
h2 {
	text-align: left;
}
button {
	float:right;
	width:180px;
	margin:2px;
	padding:5px;
	border-radius:15px;
	border:2px outset #FFF;
}
</style>
<?php include "default/uniform.php"; ?>
<h1>Bayfitted Administration Overview</h1>
<hr />
<div style="margin:50px auto;">
	<a href="inventory.php"><button>Manage Inventory >></button></a>
	<p style="text-align:left;">There are currently <span style="color:#666;"><?php echo $db->count_all("inventory", $link); ?></span> unique items in the inventory.</p>
	<br />
	<button>Manage Accounts >></button>
	<p style="text-align:left;">There are currently <span style="color:#666;"><?php echo $db->count_all("members", $link); ?></span> members.</p>
	<br />
	<?php
		$errorLog   = ($db->testing_server() === FALSE || strstr($_SERVER["SERVER_NAME"], $db->testing_server()) === FALSE) ? "../documentation/error.log": "../documentation/testing.log";
		$lastAccess = fileatime($errorLog); assert ($lastAccess !== FALSE);
		$lastEdit   = filemtime($errorLog); assert ($lastEdit   !== FALSE);
		$errors     = file($errorLog, FILE_IGNORE_NEW_LINES);
		assert ($errors !== FALSE);
		$errorCount = count($errors);
	?>
	<button>Manage Errors >></button>
	<p style="text-align:left;">
		<?php
			if (!touch($errorLog, $lastEdit, time())) {
				?><span class="italic" style="color:#F00;">Error Log Not Found!</span><?php
			} elseif ($errorCount == 0) {
				?>There are <span style="color:#060;">no</span> errors to report<?php
			} else {
				?>There are <span style='color:#F00;'><?php echo $errorCount; ?></span> unresolved errors<?php
		
				if ($lastEdit >= $lastAccess): ?>, <span class="italic" style="color:#FF0;">including unreviewed problems</span><?php endif;
				echo '.';
			}
		?>
	</p>
</div>
<hr />
<h2>Bulletin Board</h2>
<div style="width:750px; height:500px; margin:50px auto; border:1px solid #FFF; padding:3px; background-color:#000;">
	<iframe src="http://corkboard.me/OQvgfL721R" style="height:100%; width:100%; border:none;"></iframe>
</div>
<hr />
<a id="manager-notes"><h2>Manager Notes</h2></a>
<div style="margin:50px auto;">
	<?php
		$note  = $db->read("resources", "`id`='manager-board'", $link);
		$board = $note[0]["data"];
		$board = str_replace('\\', '', $board);
	?>
	<iframe id="portal" name="portal" style="display:none;"></iframe>
	<form id="manager-board" action="../resources/processor.php?action=update-manager-board" target="portal" method="post">
		<textarea name="notes" style="width:750px; height:500px;" onkeyup="updateBulletinBoard()"><?php echo $board; ?></textarea>
	</form>
	<div id="character-count" style="font-size:12px;"><?php echo round((strlen($board)/65535)*100, 0); ?>% used</div>
</div>
<?php
	/*
                    //////////
                ////// MORE //////
                    //////////
                    ?><h3 style="text-align:left; margin-left:20px;">More:</h3>
                    <div style="width:90%; margin:auto; text-align:left;">
                        <span style="text-decoration:underline;">PHP</span><br /><?php
                
					//translate connection status
                    switch(connection_status()){
                        case 0:
                            echo 'Conection Status: Normal (0)<br />';
                        
                            break;
                        case 1:
                            echo 'Conection Status: Aborted (1)<br />';
                        
                            break;
                        case 2:
                            echo 'Conection Status: Timed Out (2)<br />';
                        
                            break;
                        default: break;
                    }
                	
					//translate magic quotes
                    echo (get_magic_quotes_gpc()) ? "Magic quotes are enabled.<br />" : "Magic quotes are disabled.<br />";
					
					//translate error reporting level
					$errorsetting=error_reporting();
				    $errorreportinglevels=array(
							1=>"E_ERROR (1)",
							2=>"E_WARNING (2)",
							4=>"E_PARSE (4)",
							8=>"E_NOTICE (8)",
						   16=>"E_CORE_ERROR (16)",
						   32=>"E_CORE_WARNING (32)",
						   64=>"E_COMPILE_ERROR (64)",
						  128=>"E_COMPILE_WARNING (128)",
						  256=>"E_USER_ERROR (256)",
						  512=>"E_USER_WARNING (512)",
						 1024=>"E_USER_NOTICE (1024)",
						 2048=>"E_STRICT (2048)", 
						 4096=>"E_RECOVERABLE_ERROR (4096)",
						 6135=>"E_ALL & ~E_NOTICE (6135)",
						 8191=>"E_ALL | E_STRICT (8191)",
						 8192=>"E_DEPRECATED (8192)",
						16384=>"E_USER_DEPRECATED (16384)",
						30719=>"E_ALL (30719)"
					);
					
					$errorlevel=(array_key_exists($errorsetting, $errorreportinglevels)) ? $errorreportinglevels[$errorsetting] : "UNKNOWN (".$errorsetting.")";
                ?>
                Error Reporting Level: <?php echo $errorlevel; ?><br />
                <br />
                <span style="text-decoration:underline;">FILESYSTEM</span><br />
                OS: <?php echo PHP_OS." (".php_uname('m').')'; ?><br />
                <?php
                    $TBfree  =((disk_free_space("/")/1024.0)/1024.0)/1024.0;
                    $TBtotal =((disk_total_space("/")/1024.0)/1024.0)/1024.0;
                    
                    echo round($TBfree, 3).' TB free of '.round($TBtotal, 3).' TB (~'.round(($TBfree/$TBtotal)*100, 1);
                ?>% free)<br />
                <br />
                <span style="text-decoration:underline;">SERVER</span><br />
                Hostname: <?php echo php_uname('n'); ?><br />
                HTTPS: <?php echo $_SERVER["HTTPS"]; ?><br />
                SERVER_SOFTWARE: <?php echo $_SERVER["SERVER_SOFTWARE"]; ?><br />
                SERVER_NAME: <?php echo $_SERVER["SERVER_NAME"]; ?><br />
                SERVER_ADDR: <?php echo $_SERVER["SERVER_ADDR"]; ?><br />
                SERVER_PORT: <?php echo $_SERVER["SERVER_PORT"]; ?><br />
                SERVER_ADMIN: <?php echo $_SERVER["SERVER_ADMIN"]; ?><br />
                DOCUMENT_ROOT: <?php echo $_SERVER["DOCUMENT_ROOT"]; ?><br />
                SERVER_SIGNATURE: <?php echo $_SERVER["SERVER_SIGNATURE"]; ?><br />
                <br />
                </div>
                <table style="width:600px;">
                    <tr>
                        <td style="width:50%;"><button onclick="window.location='info/phpinfo.php'">PHP Configuration Information</button></td>
                        <td style="width:50%;"><button onclick="window.location='info/mysqlinfo.php'">MySQL Configuration Information</button></td>
                    </tr>
                </table><br />*/ ?>
<?php
	$db->close_connection($link);
	include "default/wrapper.php";
?>
