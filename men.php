<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<h1 style="text-transform:uppercase;">Men</h1>
<hr />
<a id="shirts"><h2>Shirts</h2></a>
<?php populate_section("men", "shirt"); ?>
<a id="jackets"><h2>Jackets &amp; Hoodies</h2></a>
<?php
	populate_section("men", "jacket");
	include "default/wrapper.php";
?>
