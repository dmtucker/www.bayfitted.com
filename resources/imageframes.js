	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, June 2006

	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

	Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.

	Thank you!

	www.dhtmlgoodies.com
	Alf Magne Kalleland

	************************************************************************************************************/




	var opacitySpeed = 40;	// Speed of opacity - switching between large images - Lower = faster
	var opacitySteps = 10; 	// Also speed of opacity - Higher = faster
	var slideSpeed = 5;	// Speed of thumbnail slide - Lower = faster
	var slideSteps = 8;	// Also speed of thumbnail slide - Higher = faster
	var columnsOfThumbnails = false;	// Hardcoded number of thumbnail columns, use false if you want the script to figure it out dynamically.

	/* Don't change anything below here */
	var largeImage = false;
	var imageToShow = false;
	var currentOpacity = 100;
	var slideWidth = false;
	var thumbTotalWidth = false;
	var viewableWidth = false;

	var currentUnqiueOpacityId = false;
	var currentActiveImage = false;
	var thumbDiv = false;
	var thumbSlideInProgress = false;

	var browserIsOpera = navigator.userAgent.indexOf('Opera')>=0?true:false;
	var thumbsColIndex = 1;
	var thumbsLeftPos = false;

	function initGalleryScript()
	{
		largeImage = document.getElementById('largeImage').getElementsByTagName('IMG')[0];
		thumbDiv = document.getElementById('strip_of_thumbnails');
		currentActiveImage = thumbDiv.getElementsByTagName('A')[0].getElementsByTagName('IMG')[0];
		currentActiveImage.className='activeImage';
	}
	
	function showPreview(imagePath,inputObj)
	{
		if(currentActiveImage){
			if(currentActiveImage==inputObj.getElementsByTagName('IMG')[0])return;
			currentActiveImage.className='';
		}
		currentActiveImage = inputObj.getElementsByTagName('IMG')[0];
		currentActiveImage.className='activeImage';

		imageToShow = imagePath;
		var tmpImage = new Image();
		tmpImage.src = imagePath;
		currentUnqiueOpacityId = Math.random();
		moveOpacity(opacitySteps*-1,currentUnqiueOpacityId);
	}

	function setOpacity()
	{
		if(document.all)
		{
			largeImage.style.filter = 'alpha(opacity=' + currentOpacity + ')';
		}else{
			largeImage.style.opacity = currentOpacity/100;
		}
	}
	function moveOpacity(speed,uniqueId)
	{

		if(browserIsOpera){
			largeImage.src = imageToShow;
			return;
		}

		currentOpacity = currentOpacity + speed;
		if(currentOpacity<=5 && speed<0){

			var tmpParent = largeImage.parentNode;
			largeImage.parentNode.removeChild(largeImage);
			largeImage = document.createElement('IMG');
			tmpParent.appendChild(largeImage);
			setOpacity();
			largeImage.src = imageToShow;

			speed=opacitySteps;
		}
		if(currentOpacity>=99 && speed>0)currentOpacity=99;
		setOpacity();
		if(currentOpacity>=99 && speed>0)return;
		if(uniqueId==currentUnqiueOpacityId)setTimeout('moveOpacity(' + speed + ',' + uniqueId + ')',opacitySpeed);
	}