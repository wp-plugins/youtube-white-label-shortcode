jQuery(document).ready(
	function($) {
		$('iframe#youtube-white-label.autosize').each(function() {
			var ratio	= $(this).height() / $(this).width();
			var parent	= $(this).parents().width();
			$(this).css('width', parent);
			$(this).css('height', (parent * ratio));
		});		
	}
);