<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
?>
<style type="text/css">
#sitemap {
	width:40%;
	margin:50px auto 25px auto;
	padding:25px 0px;
	text-align: center;
}
#sitemap ul {
	display:inline-block;
	padding:0px 25px;
	list-style:none;
}
#sitemap ul li a {
	font:normal 14px Helvetica, Verdana, Arial;
	letter-spacing: 2px;
	color: #999;
}
#sitemap ul li a:hover {
	color:#FFF;
}
</style>
<?php include "default/uniform.php"; ?>
<div id="sitemap" class="box">
	<ul dir="rtl" style="text-align:right; border-right:1px solid #FFF;">
		<li><a href="accessories.php">Accessories</a></li>
		<li><a href="store.php">Directions</a></li>
		<li><a href="index.php">Featured</a></li>
		<li><a href="feedback.php">Feedback</a></li>
		<li><a href="feedback.php">Giants Gear</a></li>
		<?php if (!is_logged_in()): ?><li><a href="login.php">Log In</a></li><?php endif; ?>
		<li><a href="men.php">Men</a></li>
		<?php if (is_logged_in()): ?><li><a href="profile.php">Profile</a></li><?php endif; ?>
		<li><a href="women.php">Women</a></li>
		<li><a href="kids.php">Kids</a></li>
	</ul>
	<ul style="text-align:left;">
		<li><a href="artists.php">The Artists</a></li>
		<li><a href="artists/ben/index.php" style="margin-left:10px;">Ben</a></li>
		<li><a href="artists/bud/index.php" style="margin-left:10px;">Bud</a></li>
		<li><a href="artists/chris/index.php" style="margin-left:10px;">Chris</a></li>
		<li><a href="artists/sheldon/index.php" style="margin-left:10px;">Sheldon</a></li>
	</ul>
</div>
<a href="http://inmotionhosting.com" target="_blank">
	<img src="http://creatives.inmotionhosting.com/micro-badge-blk.gif" alt="InMotion Hosting" style="display:block; height:15px; width:110px; margin:0px auto 50px auto;" />
</a>
<?php include "default/wrapper.php"; ?>