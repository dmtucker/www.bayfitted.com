<?php
	require_once "../default/initial.php";
	if (!is_admin()) redirect("/message.php?error=restricted");
	$modes = array("overview","edit","add");
	if (!array_key_exists("mode", $_GET) || !in_array($_GET["mode"], $modes)) redirect("/management/inventory.php?mode=overview");
	include_once "default/doctype.php";
?>
<style type="text/css">
td {
	border:1px solid #000;
	padding: 5px;
	text-align:center;
}
#inventory-header td {
	font-weight:bold;
}
</style>
<?php include "default/uniform.php"; ?>
<h1>Inventory Manager</h1>
<?php
	if ($_GET["mode"] == "overview") {
		?><table style="color:#000; text-align:center; margin:auto;">
			<tr id="inventory-header" style="background-color:#FFF;">
				<td></td>
				<td>Id.</td>
				<td>department</td>
				<td>type</td>
				<td>brand</td>
				<td>description</td>
				<td>PayPal Id.</td>
			</tr>
			<?php
				global $db;
				$db->open_connection();
				$inventory = $db->read("inventory","1=1");
				foreach ($inventory as $item => $itemAttribute) {
					?><tr style="background-color:<?php echo ($itemAttribute["index"]%2 == 0) ? "#FFF": "#CCC"; ?>;"><?php
					
					echo "<td>".$itemAttribute["index"]."</td>";
					echo "<td>".$itemAttribute["id"]."</td>";
					echo "<td>".$itemAttribute["department"]."</td>";
					echo "<td>".$itemAttribute["type"]."</td>";
					echo "<td>".$itemAttribute["brand"]."</td>";
					echo "<td style='text-align:left;'><a href='inventory.php?mode=edit&item=".$itemAttribute["id"]."'>".$itemAttribute["description"]."</a></td>";
					echo "<td>".$itemAttribute["paypal_id"]."</td>";
					
					?></tr><?php
				}
			?>
		</table><?php
	} elseif ($_GET["mode"] == "edit") {
		if (array_key_exists("item", $_GET)) {
			echo "editing item ".$_GET["item"];
		} else {
			?><p style="color:#F00; margin-bottom:25px;">Choose an item below to edit.</p><?php
		}
	} elseif ($_GET["mode"] == "add") {
		echo "adding an item";
	}
	
	include "default/wrapper.php";
?>
