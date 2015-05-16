<?php
	require_once "default/initial.php";
	if (!array_key_exists("id", $_GET) || !is_numeric($_GET["id"])) redirect("/index.php");
	global $db;
	$item = $matches = $db->read("inventory", "`id`=".$_GET["id"]);
	if ($matches !== FALSE) $item = $matches[0];
	include_once "default/doctype.php";
?>
<script type="text/css" src="resources/imageframes.css"></script>
<script type="text/javascript" src="resources/imageframes.js"></script>
<?php
	include "default/uniform.php";
	if ($item === FALSE) {
		?><h2>There is no item with id #<?php echo $_GET["item"]; ?></h2><?php
	} else {
		?><table style="margin:25px auto;">
			<tr>
				<td colspan="2" style="text-align:center;">
					<h2><?php echo $item["description"]; ?></h2>
				</td>
			</tr>
			<tr>
				<td>
					<div id="strip_of_thumbnails">
						<?php
							for ($noii=$item["noi"]; $noii > 0 ;--$noii) {
								?><div>
									<a href="#" onclick="showPreview('resources/images/inventory/<?php echo $item["id"].'_'.$noii; ?>.jpg', this); return false;">
										<img src="resources/images/inventory/tn_<?php echo $item["id"].'_'.$noii; ?>.jpg" alt="An image could not be found." />
									</a>
								</div><?php
							}
						?>
					</div>
				</td>
				<td style="vertical-align:top; padding-left:25px;">
					<div id="largeImage">
						<img src="resources/images/inventory/<?php echo $item["id"]; ?>_1.jpg" style="max-width:700px;" alt="An image could not be found." />
					</div>
				</td>
			</tr>
		</table>
		<div>
			$<?php
				echo $item["price"];
				if ($item["size"] != "na") {
					$sizes = explode(',',$item["size"]);
					?><form action="" method="post">
						<input type="hidden" name="addtocart" value="<?php echo $item; ?>" />
						<span style="font-size:12px;">Size:&nbsp;</span>
						<select name="size">
							<?php
								/*
								if(isLoggedIn()){
									$member=getMemberInfo($_SESSION["member_id"]);
									//print personal size
									if(!empty($member["size"])){
										?><option value="<?php echo $member["size"]; ?>" style="color:#000;"><?php echo $member["firstname"].' ('.$member["size"].')'; ?></option><?php
									}
								}
								*/
								foreach ($sizes as $size) {
									?><option value="<?php echo $size; ?>"><?php echo $size; ?></option><?php
								}
							?>
						</select>
						<input type="submit" value="Add to Cart" />
					</form><?php
				} else{
					?><form action="" method="post">
						<input type="hidden" name="addtocart" value="<?php echo $item; ?>" />
						<input type="submit" value="Add to Cart" style="color:#000;" />
					</form><?php
				}
			?>
		</div>
		<script type="text/javascript">
			initGalleryScript();
		</script><?php
	}
	
	include "default/wrapper.php";
?>