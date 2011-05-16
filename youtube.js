function preview() {	
	var Values = [];
	jQuery('input[name^="_YouTube_"],select[name^="_YouTube_"]').each(function() {
		var ThisID = jQuery(this).attr("name").replace('_YouTube_', '').toLowerCase();
		Values.push(ThisID + '="' + jQuery(this).val() + '"');
	});
	jQuery('#_YouTube_output').html('[youtube-embed ' + Values.join(' ') + ' /]');
}

jQuery(document).ready(
	function($) {		
		var post_type 	= $('[name="post_type"]').val();
		var meta_box	= $('#youtube-embed-' + post_type + '-meta-box').length;
		if( meta_box > 0 ) {
	
			var $Controls = {
				"inputs" : $('input[name^="_YouTube_"]'), 
				"selects" : $('select[name^="_YouTube_"]')
			};
	
			/* Set all selects to their first value */
			$Controls.selects.each(function() { this.selectedIndex = 0; });
			
			/* Watch the keys */
			$Controls.inputs.bind('keyup', preview).bind('keypress', function(e) {
				if( e.which == 13 )
					e.preventDefault();
			});
			$Controls.selects.bind('click change', preview);
			
			/* Advanced dropdown */
			$('#youtube-embed-post-meta-box p:not(#youtube-embed-post-meta-box p:first-child, #youtube-embed-post-meta-box p.output, #youtube-embed-post-meta-box p.howto, #youtube-embed-post-meta-box p.youtube-advanced-wrap)').appendTo('#youtube-advanced');
			$('a.youtube-advanced').click( function(e) {
				e.preventDefault();
				$('#youtube-advanced').slideToggle('slow');
			});
			
			/* Donate dropdown */
			$('a.frosty').click( function(e) {
				e.preventDefault();
				$('#frosty').slideToggle('slow');
			});
		
		} // end meta_box
		
	}
);