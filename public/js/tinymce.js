(function() {
	function set_shortcodes_atts( editor, atts ) {

		// nom fenetre
		var titreFenetre = !_.isUndefined( atts.nom ) ? atts.nom : 'Add shortcode';
		// balise du shortcode
		var balise = !_.isUndefined( atts.balise ) ? atts.balise : false;

		fn = function() {
			editor.windowManager.open( {
				title: titreFenetre,
				body: atts.body,
				onsubmit: function( e ) {
					var out = '[mjcaroussel ' + balise;
					for ( var attr in e.data ) {
						out += ' ' + attr + '="' + e.data[ attr ] + '"';
					}
					out += '/]';
					editor.insertContent( out );
				}
			} );
		};
		return fn;
	}

	tinymce.PluginManager.add('scriptmjcaroussel', function( editor, url ) {
		editor.addButton('mjcaroussel_button', {
			icon: 'icon dashicons-smiley',
			title:'MjCaroussel',
			visual_anchor_class: 'mjcaroussel_button',
			onclick: set_shortcodes_atts( editor, {
				body: [
					{
						label: '',
						name: 'id',
						type: 'listbox',
						values: list
					}
				],
				balise: '',
				nom: 'Add MjCaroussel'
			} )
		});
	});

})();
