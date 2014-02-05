function preview() {	
	var Version,
		$this,
		ThisID,
		Values = [];
		
	jQuery('input[name^="_YouTube_"],select[name^="_YouTube_"]').each(function() {
		$this = jQuery(this);
				
		ThisID = $this.prop('name').replace('_YouTube_', '').toLowerCase();
		
		Values.push(ThisID + '="' + $this.val() + '"');
	});
	jQuery('#_YouTube_output').text('[youtube-white-label ' + Values.join(' ') + ' /]');
}

jQuery(document).ready(
	function($) {
		var post_type 	= $('[name="post_type"]').val();
		var meta_box	= $('#youtube-white-label-' + post_type + '-meta-box');
		if ( meta_box.length ) {
	
			var $Controls = {
				"inputs" 	: $('input[name^="_YouTube_"]'), 
				"selects" 	: $('select[name^="_YouTube_"]')
			};
	
			/* Set all selects to their first value */
			$Controls.selects.each(function() { this.selectedIndex = 0; });
			
			/* Watch the keys */
			$Controls.inputs.on('keyup', preview).on('keypress', function(e) {
				if( e.which == 13 )
					e.preventDefault();
			});
			$Controls.selects.on('click change', preview);
			
			/* Advanced dropdown */
			$('#youtube-white-label-' + post_type + '-meta-box p:not(#youtube-white-label-' + post_type + '-meta-box p:first-child, #youtube-white-label-' + post_type + '-meta-box p.output, #youtube-white-label-' + post_type + '-meta-box p.howto, #youtube-white-label-' + post_type + '-meta-box p.youtube-advanced-wrap)').appendTo('#youtube-advanced');
			$('a.youtube-advanced').on('click', function(e) {
				e.preventDefault();
				$('#youtube-advanced').slideToggle();
			});
		
		} // end meta_box
		
	}
);