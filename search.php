<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
	include      "default/uniform.php";
?>
<div class="hanging">
	<div id="cse">Loading</div>
	<script src="//www.google.com/jsapi" type="text/javascript"></script>
	<script type="text/javascript"> 
	function parseQueryFromUrl () {
		var queryParamName = "q";
		var search = window.location.search.substr(1);
		var parts = search.split('&');
		for (var i = 0; i < parts.length; i++) {
			var keyvaluepair = parts[i].split('=');
			if (decodeURIComponent(keyvaluepair[0]) == queryParamName) {
				return decodeURIComponent(keyvaluepair[1].replace(/\+/g, ' '));
			}
		}
		return '';
	}
	google.load('search', '1', {language : 'en', style : google.loader.themes.MINIMALIST});
	google.setOnLoadCallback(function() {
		var customSearchControl = new google.search.CustomSearchControl('015948222393134286770:pos50w-diui');
		customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
		customSearchControl.draw('cse');
		var queryFromUrl = parseQueryFromUrl();
		if (queryFromUrl) {
			customSearchControl.execute(queryFromUrl);
		}
	}, true);
	</script>
</div>
<?php include "default/wrapper.php"; ?>