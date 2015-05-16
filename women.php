<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<h1 style="text-transform:uppercase;">Women</h1>
<hr />
<a id="shirts"><h2>Shirts</h2></a>
<?php populate_section("women", "shirt"); ?>
<a id="jackets"><h2>Jackets &amp; Hoodies</h2></a>
<?php
	populate_section("women", "jacket");
	include "default/wrapper.php";
?>
