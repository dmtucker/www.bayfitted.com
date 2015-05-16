<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
?>
<style type="text/css">
.product {
	width:50%;
	margin:auto;
}
#content {
	text-align: center;
}
</style>
<?php include "default/uniform.php"; ?>
<img src="resources/images/giants/giants.png" alt="Giants Gear" style="margin:50px auto 0px auto; width:400px;" />
<div style="padding-bottom:50px; width:100%; height:50px; background:url(resources/images/giants/logo-strip.png) top center no-repeat; margin:-25px auto 0px auto;"><h2 style="height:100%; color:#FFF; word-spacing:175px; padding-top:15px; letter-spacing:4px; font-size:13px; text-align:center; font-weight:normal; text-shadow: 1px 2px #666; border-bottom:2px ridge #111;">Get Giants Gear Here</h2></div>
<?php populate_section("giants", "shirt"); ?>
<?php include "default/wrapper.php";?>
