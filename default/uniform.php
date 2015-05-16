</head>
<body>
	<div id="backdrop">
		<div id="overlay">
			<div id="header" class="section">
				<div id="search">
					<div id="cse-search-form">Loading</div>
					<script src="//www.google.com/jsapi" type="text/javascript"></script>
					<script type="text/javascript"> 
					google.load('search', '1', {language : 'en', style : google.loader.themes.MINIMALIST});
					google.setOnLoadCallback(function() {
						var customSearchControl = new google.search.CustomSearchControl('015948222393134286770:pos50w-diui');
						customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
						var options = new google.search.DrawOptions();
						options.enableSearchboxOnly(<?php echo '"http://'.$_SERVER["SERVER_NAME"].'/search.php"'; ?>);
						customSearchControl.draw('cse-search-form', options);
					}, true);
					</script>
				</div>
				<table id="account-menu">
					<tr>
						<?php 
							if (is_logged_in()) {
								?><td class="button" style="border-right:1px solid #666;">
									<?php
										if (substr($_SERVER["PHP_SELF"],1) == "profile.php") {
											assert (isset($editMode));
											if ($editMode): ?><a href="/profile.php?mode=delete">delete profile</a><?php
											else          : ?><a href="/profile.php?mode=edit">edit profile</a><?php
											endif;
										} else {
											$memberName = explode(' ', $_SESSION["member_name"]);
											?><a href="/profile.php"><?php echo $_SESSION["member_name"]; ?></a><?php
										}
									?>
								<td class="button"><a href="/resources/processor.php?action=logout">log out</a></td>
								<!--td id="cart-button" class="button"><div id="cart-icon"><a href="/cart.php" style="width:100%; height:100%;"></a></div></td-->
								
								
								
								
								
								<td id="cart-button" class="button">
									<div id="cart-icon">
										<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="width:100%; height:100%; margin:0px;">
											<input type="hidden" name="cmd" value="_s-xclick" />
											<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7----- " />
											<input type="image" src="/resources/images/buttons/shield.png" name="submit" alt="View Cart" style="width:100%; height:100%;" />
										</form>
									</div>
								</td><?php
							} else {
								?><td class="button"><a href="/login.php">log in</a></td>
								<!--td id="cart-button" class="button"><div id="cart-icon"><a href="/cart.php" style="width:100%; height:100%;"></a></div></td-->
								
								
								
								
								
								<td id="cart-button" class="button">
									<div id="cart-icon">
										<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" style="width:100%; height:100%; margin:0px;">
											<input type="hidden" name="cmd" value="_s-xclick" />
											<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIG1QYJKoZIhvcNAQcEoIIGxjCCBsICAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYATukwvBakrzZDjk+rK8IoFgj+7f0TiJkxfsRmmSmCQJijXTBmFEc8yBHtEgwkygy+gOt/heI4COMSAnVBJ8hvKV+HbRXSp4B6CZVwnBT3swPVptHxoFx0zAkTNA+GrQAm9sUNTe1j7LOVlLePgMptCJzsNOX+8p/vqnY96m5lA9jELMAkGBSsOAwIaBQAwUwYJKoZIhvcNAQcBMBQGCCqGSIb3DQMHBAiAspdrHkVpvYAw0/qwz8N6KGHHvzBPf+8w4AEwJs4O9S0j89vFu0ePD+s/9CUOGwpledqLluMJ2E0noIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMDkxMDIzMDA1MTU3WjAjBgkqhkiG9w0BCQQxFgQUKGG14STI2RItM9/N6OtXEdzVeq8wDQYJKoZIhvcNAQEBBQAEgYBMbPE9fGk0uR6jlxJrHs8s6ViYrF83CxSLX5ktL4P4D7mVpIIRTK93+dqnBapbUCyAPAF6bVKAxZkuiKZLlaYbPO+xiJHFoTwTRHgWZWV61Qq9tBrwYt3RDsu9oIKNsqY9t+9m1YSFOwNGL4/AK/16JiO/4fXbK9MXYdFJWv0hWw==-----END PKCS7----- " />
											<input type="image" src="/resources/images/buttons/shield.png" name="submit" alt="View Cart" style="width:100%; height:100%;" />
										</form>
									</div>
								</td><?php
							}
						?>
					</tr>
				</table>
				<!--h1 style="height:25px; margin-top:10px; text-align:right; font-:normal 24px sans-serif; letter-spacing:3px;">508 Ellis St. San Francisco, Calif.</h1-->
				<img src="/resources/images/address.png" alt="508 Ellis St. San Francisco, Calif." style="height:25px; width:550px; margin-top:10px;" />
			</div>
			<ul id="nav" class="sf-menu">
				<li><a href="/index.php">Featured</a></li>
				<li>
					<a href="/men.php?page=1">Men</a>
					<ul>
						<li><a href="/men.php?page=1#shirts">Shirts</a></li>
						<li><a href="/men.php?page=1#jackets">Jackets &amp; Hoodies</a></li>
					</ul>
				</li>
				<li>
					<a href="/women.php?page=1">Women</a>
					<ul>
						<li><a href="/women.php?page=1#tops">Tops</a></li>
						<li><a href="/women.php?page=1#jackets">Jackets &amp; Hoodies</a></li>
					</ul>
				</li>
				<li>
					<a href="/kids.php?page=1">Kids</a>
					<ul>
						<li><a href="/kids.php?page=1#shirts">Shirts</a></li>
						<li><a href="/kids.php?page=1#onesies">Onesies</a></li>
					</ul>
				</li>
				<li>
					<a href="/accessories.php?page=1">Accessories</a>
					<ul>
						<li><a href="/accessories.php?page=1#hats">Hats</a></li>
						<li><a href="/accessories.php?page=1#shoes">Shoes</a></li>
						<li><a href="/accessories.php?page=1#jewelry">Jewelry</a></li>
						<li><a href="/accessories.php?page=1#other">More</a></li>
					</ul>
				</li>
				<li>
					<a href="/artists.php">Artists</a>
					<ul>
						<li><a href="/artists/bud/index.php">Bud</a></li>
						<li><a href="/artists/sheldon/index.php">Sheldon</a></li>
						<li><a href="/artists/ben/index.php">Ben</a></li>
						<li><a href="/artists/chris/index.php">Chris</a></li>
					</ul>
				</li>
				<li><a href="/store.php">Directions</a></li>
			</ul>
			<div id="content" class="section">
