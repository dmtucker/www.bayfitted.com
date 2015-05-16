<?php 
	require_once "default/initial.php";
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<h1 style="text-transform:uppercase;">Accessories</h1>
<hr />
<a id="hats"><h2>Hats</h2></a>
<?php if (populate_section("accessories", "hat") === FALSE): ?><p>Ask us about our hats!</p><?php endif; ?>
<a id="shoes"><h2>Shoes</h2></a>
<?php if (populate_section("accessories", "shoes") === FALSE): ?><p>Ask us about our shoes!</p><?php endif; ?>
<a id="jewelry"><h2>Jewelry</h2></a>
<?php if (populate_section("accessories", "jewelry") === FALSE): ?><p>Ask us about our jewelry!</p><?php endif; ?>
<a id="other"><h2>Other Accessories</h2></a>
<?php
	if (populate_section("accessories", "other") === FALSE): ?><p>Ask us about our other accessories!</p><?php endif;
	include "default/wrapper.php";
?>
