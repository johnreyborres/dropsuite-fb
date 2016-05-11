$(document).ready(function() {
	$.ajaxSetup({ cache: true });
	$.getScript('//connect.facebook.net/en_US/sdk.js', function() {
	    FB.init({
	      appId: '2024199137805681',
	      version: 'v2.5'
	    });
	});

    $("#btnSendMsg").click(function() {
    	var uid = $("input[name=friends]:checked").val();

    	if(uid != undefined) {
			FB.ui({
				method: 'send',
			  	to: uid,
			  	link: 'http://www.nytimes.com/interactive/2015/04/15/travel/europe-favorite-streets.html',
			});
    	}
    });
});