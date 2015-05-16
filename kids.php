<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<h1 style="text-transform:uppercase;">Kids</h1>
<hr />
<a id="shirts"><h2>Shirts</h2></a>
<?php populate_section("kids", "shirt"); ?>
<a id="onesies"><h2>Onesies</h2></a>
<?php
	populate_section("kids", "onesie");
	include "default/wrapper.php";
?>
