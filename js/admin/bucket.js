(function(window, document, $, undefined){

	window.wpBucket = {};
	
	wpBucket.init = function() {
		// put your custom functions here
		console.log('Wp Bucket loaded');
		var commentContainer=$('textarea#comment');
		
		commentContainer.addClass('hi123');
	}

	$(document).on( 'ready', wpBucket.init );

})(window, document, jQuery);