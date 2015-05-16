<?php
	require_once "default/initial.php";
	include_once "default/doctype.php";
?>
<style type="text/css">
#directions {
	padding:25px;
	text-align: center;
}
#directions div {
	display:inline-block;
}
#directions ul {
	vertical-align: top;
	list-style: none;
}
#directions-west ul li,
#directions-north ul li {
	margin:10px auto;
	line-height: 10px;
}
#directions #directions-west {
	text-align:right;
}
#directions #directions-north {
	text-align:left;
}
#directions #hours {
	vertical-align: top;
	margin:0px 10px;
	border-style: solid;
	border-color: #FFF;
	border-width: 0px 1px;
	border-radius: 25px;
	padding:20px;
}
#directions #hours h4,
#directions #directions-west h4,
#directions #directions-north h4 {
	margin:auto;
	margin-top:15px;
	text-decoration: underline;
	color:#999;
}
#map {
	height:500px;
}
</style>
<?php include "default/uniform.php"; ?>
<iframe id="map" class="hanging" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Bayfitted&amp;aq=&amp;sll=36.970031,-122.046038&amp;sspn=0.013903,0.027874&amp;vpsrc=6&amp;ie=UTF8&amp;hq=Bayfitted&amp;hnear=&amp;radius=15000&amp;t=h&amp;cid=14239145323138009815&amp;ll=37.794728,-122.414131&amp;spn=0.033912,0.077333&amp;z=14&amp;iwloc=A&amp;output=embed">
	<p>
		Your browser does not support iframes! This webpage is optimized for the
		<span style="font-weight:bold;">most updated</span> versions of
		Mozilla Firefox, Google Chrome, Apple Safari, &amp; Opera.
	</p>
</iframe>
<div id="directions">
	<div id="directions-west">
		<h4>From I-170W</h4>
		<ul dir="rtl">
			<li>Take the 5th St. exit on the left<br />
			(toward Golden Gate Bridge/US-101)</li>
			<li>Turn left at Harrison St<br />
			(signs for US-101/Harrison St)</li>
			<li>Turn right at 6th St</li>
			<li>Continue on Taylor St</li>
			<li>Turn left at Ellis St<br />
			(Bayfitted will be on the right)</li>
		</ul>
	</div>
	<div id="hours">
		<h2 style="margin:0px auto 20px auto;">(888) 4-Bayfitted</h2>
		<h3><a href="mailto:shop@bayfitted.com">shop@bayfitted.com</a></h3>
		<h4>Monday - Saturday:</h4>
		<h3>1:00 - 9:00pm</h3>
		<h4>Sunday:</h4>
		<h3>2:00 - 8:00pm</h3>
	</div>
	<div id="directions-north">
		<h4>From US-101N</h4>
		<ul>
			<li>Continue on I-170 E<br />
			(signs for Seventh St)</li>
			<li>Merge onto 7th St</li>
			<li>Continue on Charles J. Brenham Pl</li>
			<li>Turn left at McAllister St</li>
			<li>Turn right at Leavenworth St</li>
			<li>Turn left at Ellis St<br />
			(Bayfitted will be on the right)</li>
		</ul>
	</div>
</div>
<?php include "default/wrapper.php"; ?>
