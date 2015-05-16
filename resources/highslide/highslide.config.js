/**
*	Site-specific configuration settings for Highslide JS
*/
hs.graphicsDir = '/resources/highslide/graphics/';
hs.showCredits = false;
hs.outlineType = 'custom';
hs.dimmingOpacity = 0.5;
hs.easing = 'easeInBack';
hs.easingClose = 'easeOutBack';
hs.align = 'center';
hs.allowMultipleInstances = false;
hs.captionEval = 'this.a.title';
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'top right',
	useOnHtml: true,
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});

