/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */

function sectionsFilterLayout( ) {
	jQuery( ".section-layout-type" ).each( function( ) {
		var items = jQuery( this ).parent( ).parent( );
		var name = jQuery( this ).val( );

		items.find( "[data-layout-type]" ).hide( );
		items.find( "[data-layout-type~=" + name + "]" ).show( );
	} );
}

jQuery( document ).ready( function( ) {
	jQuery( document ).on( "click", ".site-section-title", function( evt ) {
		evt.preventDefault( );
		
		if ( jQuery( this ).next( ).hasClass( "opened" ) ) {
			jQuery( this ).next( ).removeClass( "opened" );
		} else {
			jQuery( this ).next( ).addClass( "opened" );
		}
	} );

	jQuery( document ).on( "click", ".meta-item-upload", function( e ) {
		e.preventDefault( );
		var area = jQuery( this ).prev( );

		frame = wp.media( {
			title: prodo_options_lng.insert_media,
			frame: "post",
			multiple: false,
			library: { type: "image" },
			button: { text: prodo_options_lng.insert_media },
		} );

		frame.on( "close", function( data ) {
			var imageArray = [];

			images = frame.state( ).get( "selection" );
			images.each( function( image ) {
				imageArray.push( image.attributes.url );
			} );

			area.val( imageArray.join( ", " ) );
		} );

		frame.open( );
	} );

	jQuery( document ).on( "change", ".section-layout-type", function( ) {
		var value = jQuery( this ).find( "option:selected" ).text( );

		jQuery( this ).parent( ).parent( ).parent( ).find( ".site-section-title-type" ).text( value );
		sectionsFilterLayout( );
	} );

	jQuery( document ).on( "change", ".section-title", function( ) {
		var value = jQuery( this ).children( ":selected" ).text( );

		jQuery( this ).parent( ).parent( ).parent( ).find( ".site-section-title-text" ).text( value );
	} );

	jQuery( document ).on( "click", ".site-section-remove", function( ) {
		jQuery( this ).parent( ).remove( );
	} );

	jQuery( ".add-new-section" ).click( function( evt ) {
		evt.preventDefault( );

		var top = jQuery( "#new-section-mockup" ).clone( ).appendTo( ".site-sections" ).removeAttr( "id" ).position( ).top;

		jQuery( "html, body" ).animate( { scrollTop: top }, 500 );
	} );

	jQuery( ".site-sections" ).sortable( );
	jQuery( ".portfolio-items" ).sortable( );

	sectionsFilterLayout( );
} );