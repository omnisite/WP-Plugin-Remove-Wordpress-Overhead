jQuery( document ).ready( function ( e ) {
	var remove_wordpress_overhead_checked_all = false;
	jQuery( '#remove_wordpress_overhead_selectall' ).click( function() {
		if ( ! remove_wordpress_overhead_checked_all ) {
			jQuery( '#remove_wordpress_overhead_settings td input' ).prop( 'checked', true );
			remove_wordpress_overhead_checked_all = true;
		} else {
			jQuery( '#remove_wordpress_overhead_settings td input' ).prop( 'checked', false );
			remove_wordpress_overhead_checked_all = false;
		}
	} );
} );