/**
 * Prodo
 * WordPress Theme
 *
 * Web: https://www.facebook.com/a.axminenko
 * Email: a.axminenko@gmail.com
 *
 * Copyright 2015 Alexander Axminenko
 */

function prodoShortcodePaste( editor, name, content, atts ) {
	var close = false, attsStr = '';

	if ( typeof atts === 'object' ) {
		jQuery.each( atts, function( index, value ) {
			if ( value != '' ) {
				attsStr += ' ' + index + '="' + value + '"';
			}
		} );
	}
	if ( typeof content !== 'boolean' ) {
		close = true;
	}

	var code = '[' + name + attsStr + ']' + ( close == true ? content + '[/' + name + ']' : '' );
	editor.insertContent( code );

	return true;
}

( function( ) {
	tinymce.PluginManager.add( 'prodoShortcodes', function( editor, url ) {
		editor.addButton( 'prodoShortcodes', {
			text: 'Prodo',
			icon: false,
			type: 'menubutton',
			menu: [
				{
					text: 'Sections',
					menu: [
						{
							text: 'Portfolio',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Portfolio',
									body: [
										{ type: 'listbox', name: 'order', label: 'Order By', values: [ { text: 'Default', value: '' }, { text: 'ID', value: 'ID' }, { text: 'Title', value: 'title' }, { text: 'Date', value: 'date' }, { text: 'Modified', value: 'modified' }, { text: 'Random', value: 'rand' } ] },
										{ type: 'listbox', name: 'filters', label: 'Categorized', values: [ { text: 'Yes', value: '' }, { text: 'No', value: 'no' } ] },
										{ type: 'textbox', name: 'limit', label: 'Limit', value: 'None', size: 40 },
									],
									onsubmit: function( e ) {
										var atts = { order: e.data.order, filters: e.data.filters, limit: ( e.data.limit == 'None' ? '' : parseInt( e.data.limit ) ) };
										prodoShortcodePaste( editor, 'portfolio', false, atts );
									}
								} );
							}
						},
						{
							text: 'Our Clients',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Our Clients',
									body: [
										{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3' }, { text: '1/4', value: '1/4', selected: true }, { text: '1/6', value: '1/6' } ] },
										{ type: 'textbox', name: 'limit', label: 'Limit', value: '4', size: 40 },
										{ type: 'textbox', name: 'class', label: 'CSS Class' },
									],
									onsubmit: function( e ) {
										var atts = { column: e.data.column, limit: parseInt( e.data.limit ), class: e.data.class };
										prodoShortcodePaste( editor, 'our-clients', false, atts );
									}
								} );
							}
						},
						{
							text: 'Our Team',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Our Team',
									body: [
										{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3', selected: true }, { text: '1/4', value: '1/4' }, { text: '1/6', value: '1/6' } ] },
										{ type: 'textbox', name: 'limit', label: 'Limit', value: '3', size: 40 },
										{ type: 'textbox', name: 'class', label: 'CSS Class' },
									],
									onsubmit: function( e ) {
										var atts = { column: e.data.column, limit: parseInt( e.data.limit ), class: e.data.class };
										prodoShortcodePaste( editor, 'our-team', false, atts );
									}
								} );
							}
						},
						{
							text: 'Contact Form',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Contact Form',
									body: [
										{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
									],
									onsubmit: function( e ) {
										var atts = { title: e.data.title };
										prodoShortcodePaste( editor, 'contact-form', false, atts );
									}
								} );
							}
						},
						{
							text: 'Twitter Feed',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Twitter Feed',
									body: [
										{ type: 'textbox', name: 'account', label: 'Account', size: 40 },
										{ type: 'label', label: ' ', text: 'Without @' },
										{ type: 'label', label: ' ', text: ' ' },
										{ type: 'textbox', name: 'limit', label: 'Count of Tweets', size: 40, value: '5' },
										{ type: 'listbox', name: 'delay', label: 'Auto Slideshow', values: [ { text: 'Disabled', value: '' }, { text: '5 Seconds', value: '5' }, { text: '10 Seconds', value: '10' }, { text: '15 Seconds', value: '15' }, { text: '20 Seconds', value: '20' }, { text: '30 Seconds', value: '30' }, { text: '1 Minute', value: '60' } ] },
									],
									onsubmit: function( e ) {
										var atts = { account: e.data.account, limit: e.data.limit, delay: e.data.delay };
										prodoShortcodePaste( editor, 'twitter-feed', false, atts );
									}
								} );
							}
						},
						{
							text: 'Services',
							menu: [
								{
									text: 'Services',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'services', '', false );
									}
								},
								{
									text: 'Service Item',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Service Item',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'textbox', name: 'text', label: 'Primary Text', multiline: true, minHeight: 60 },
												{ type: 'textbox', name: 'icon', label: 'Icon Name' },
												{ type: 'label', label: ' ', text: 'Example, asterisk' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'color', label: 'Icon Color', value: 'Inherit' },
												{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3', selected: true }, { text: '1/4', value: '1/4' }, { text: '1/6', value: '1/6' } ] },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'class', label: 'CSS Class' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'sticker', label: 'Sticker Text' },
												{ type: 'listbox', name: 'sticker_color', label: 'Sticker Color', values: [ { text: 'Default', value: '' }, { text: 'Red', value: 'red' }, { text: 'Orange', value: 'orange' }, { text: 'Green', value: 'green' }, { text: 'Blue', value: 'blue' } ] },
											],
											onsubmit: function( e ) {
												var atts = { title: e.data.title, icon: 'fa-' + e.data.icon, color: ( e.data.color == 'Inherit' ? '' : e.data.color ), column: e.data.column, class: e.data.class, sticker: e.data.sticker, sticker_color: e.data.sticker_color };
												prodoShortcodePaste( editor, 'service', e.data.text, atts );
											}
										} );
									}
								}
							]
						},
						{
							text: 'Infobox',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Infobox',
									body: [
										{ type: 'textbox', name: 'text', label: 'Primary Text', value: '', size: 40 },
										{ type: 'textbox', name: 'url', label: 'Button URL', value: '' },
										{ type: 'textbox', name: 'button', label: 'Button Label', value: '' },
										{ type: 'textbox', name: 'class', label: 'CSS Class' },
									],
									onsubmit: function( e ) {
										var atts = { url: e.data.url, button: e.data.button, class: e.data.class };
										prodoShortcodePaste( editor, 'info-box', e.data.text, atts );
									}
								} );
							}
						},
						{
							text: 'Blog Posts',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Blog Posts',
									body: [
										{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3' }, { text: '1/4', value: '1/4', selected: true } ] },
										{ type: 'textbox', name: 'limit', label: 'Limit', value: '3', size: 40 },
									],
									onsubmit: function( e ) {
										var atts = { column: e.data.column, limit: parseInt( e.data.limit ) };
										prodoShortcodePaste( editor, 'blog', false, atts );
									}
								} );
							}
						}
					]
				},
				{
					text: 'Columns',
					menu: [
						{ text: '1/2', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '1/2' } ); } },
						{ text: '1/3', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '1/3' } ); } },
						{ text: '1/4', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '1/4' } ); } },
						{ text: '1/6', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '1/6' } ); } },
						{ text: '2/3', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '2/3' } ); } },
						{ text: '3/2', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '3/2' } ); } },
						{ text: '3/4', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '3/4' } ); } },
						{ text: '5/6', onclick: function( ) { prodoShortcodePaste( editor, 'column', '<br />', { size: '5/6' } ); } },
					]
				},
				{
					text: 'Toggles',
					menu: [
						{
							text: 'Accordion',
							menu: [
								{
									text: 'Accordion',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'accordions', '<br />[accordion title="First" opened="yes"]First slide.[/accordion]<br />[accordion title="Second"]Second slide.[/accordion]<br />', false );
									}
								},
								{
									text: 'Accordion Slide',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Accordion Slide',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'textbox', name: 'text', label: 'Primary Text', multiline: true, minHeight: 100 },
												{ type: 'listbox', name: 'state', label: 'State', values: [ { text: 'Closed', value: '' }, { text: 'Opened', value: 'yes' } ] },
											],
											onsubmit: function( e ) {
												var atts = { title: e.data.title, state: e.data.state };
												prodoShortcodePaste( editor, 'accordion', e.data.text, atts );
											}
										} );
									}
								}
							]
						},
						{
							text: 'Tabs',
							menu: [
								{
									text: 'Tabs',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'tabs', '<br />[tab title="First"]First tab.[/tab]<br />[tab title="Second"]Second tab.[/tab]<br />', false );
									}
								},
								{
									text: 'Tab',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Tab',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'textbox', name: 'text', label: 'Primary Text', multiline: true, minHeight: 100 },
											],
											onsubmit: function( e ) {
												var atts = { title: e.data.title };
												prodoShortcodePaste( editor, 'tab', e.data.text, atts );
											}
										} );
									}
								}
							]
						},
					]
				},
				{
					text: 'Bars & Counters',
					menu: [
						{
							text: 'Circular Bars',
							menu: [
								{
									text: 'Bars',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'bars', '<br />[bar title="First" column="1/6" value="75"]<br />[bar title="Second" column="1/6" value="95"]<br />', false );
									}
								},
								{
									text: 'Bar',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Circular Bar',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3' }, { text: '1/4', value: '1/4' }, { text: '1/6', value: '1/6', selected: true } ] },
												{ type: 'textbox', name: 'value', label: 'Value' },
												{ type: 'label', label: ' ', text: 'Between 0 and 100%', value: '75' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'class', label: 'CSS Class' },
											],
											onsubmit: function( e ) {
												var atts = { title: e.data.title, column: e.data.column, value: e.data.value, class: e.data.class };
												prodoShortcodePaste( editor, 'bar', false, atts );
											}
										} );
									}
								}
							]
						},
						{
							text: 'Progress Bar',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Progress Bar',
									body: [
										{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
										{ type: 'textbox', name: 'value', label: 'Value', value: '75' },
										{ type: 'label', label: ' ', text: 'Between 0 and 100%' }
									],
									onsubmit: function( e ) {
										var atts = { title: e.data.title, value: e.data.value };
										prodoShortcodePaste( editor, 'progress', false, atts );
									}
								} );
							}
						},
						{
							text: 'Milestone Counter',
							menu: [
								{
									text: 'Milestone Counters',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'milestone', '<br />[counter title="First" column="1/4" from="1" to="100"]<br />[counter title="Second" column="1/4" from="1" to="85"]<br />', false );
									}
								},
								{
									text: 'Counter',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Milestone Counter',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3' }, { text: '1/4', value: '1/4', selected: true }, { text: '1/6', value: '1/6' } ] },
												{ type: 'textbox', name: 'from', label: 'From', value: '1' },
												{ type: 'label', label: ' ', text: 'Default, 1' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'to', label: 'To', value: '' },
												{ type: 'label', label: ' ', text: 'Example, 100' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'textbox', name: 'class', label: 'CSS Class' },
											],
											onsubmit: function( e ) {
												var atts = { title: e.data.title, column: e.data.column, from: ( e.data.from == 1 ) ? '' : e.data.from, to: e.data.to, class: e.data.class };
												prodoShortcodePaste( editor, 'counter', false, atts );
											}
										} );
									}
								}
							]
						}
					]
				},
				{
					text: 'Text Options',
					menu: [
						{
							text: 'Highlight',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Highlight',
									body: [
										{ type: 'textbox', name: 'text', label: 'Text', size: 40 },
										{ type: 'listbox', name: 'style', label: 'Style', values: [ { text: 'Normal', value: '' }, { text: 'Dark', value: 'dark' } ] }
									],
									onsubmit: function( e ) {
										var atts = { style: e.data.style };
										prodoShortcodePaste( editor, 'highlight', e.data.text, atts );
									}
								} );
							}
						},
						{
							text: 'Dropcap',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Dropcap',
									body: [
										{ type: 'textbox', name: 'letter', label: 'Letter', size: 40 },
										{ type: 'listbox', name: 'style', label: 'Style', values: [ { text: 'Default', value: '' }, { text: 'Alternative', value: 'alt' } ] }
									],
									onsubmit: function( e ) {
										var atts = { letter: e.data.letter, style: e.data.style };
										prodoShortcodePaste( editor, 'dropcap', false, atts );
									}
								} );
							}
						}
					]
				},
				{
					text: 'Pricing Tables',
					menu: [
						{
							text: 'Pricing Table',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Pricing Table',
									body: [
										{ type: 'listbox', name: 'column', label: 'Columns per plan', values: [ { text: '1/2 – Two Plans', value: '1/2' }, { text: '1/3 – Three Plans', value: '1/3', selected: true }, { text: '1/4 – Four Plans', value: '1/4' } ] },
									],
									onsubmit: function( e ) {
										var atts = { column: e.data.column };
										prodoShortcodePaste( editor, 'pricing-table', '<br />[plan title="First Plan" price="0$/month" button="Button" link="#"]<ul><li>Lorem ipsum</li><li>Dolor Sit Amet</li></ul>[/plan]<br />', atts );
									}
								} );
							}
						},
						{
							text: 'Plan',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Plan',
									body: [
										{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
										{ type: 'textbox', name: 'price', label: 'Price' },
										{ type: 'label', label: ' ', text: 'Example, 0$/month' },
										{ type: 'label', label: ' ', text: ' ' },
										{ type: 'textbox', name: 'button', label: 'Button', value: 'Purchase' },
										{ type: 'textbox', name: 'link', label: 'Link', value: 'http://' },
										{ type: 'label', label: ' ', text: ' ' },
										{ type: 'textbox', name: 'sticker', label: 'Sticker Text' },
										{ type: 'listbox', name: 'sticker_color', label: 'Sticker Color', values: [ { text: 'Default', value: '' }, { text: 'Red', value: 'red' }, { text: 'Orange', value: 'orange' }, { text: 'Green', value: 'green' }, { text: 'Blue', value: 'blue' } ] },
									],
									onsubmit: function( e ) {
										var atts = { title: e.data.title, price: e.data.price, button: e.data.button, link: e.data.link, sticker: e.data.sticker, sticker_color: e.data.sticker_color };
										prodoShortcodePaste( editor, 'plan', '<ul><li>Lorem ipsum</li><li>Dolor Sit Amet</li></ul>', atts );
									}
								} );
							}
						}
					]
				},
				{
					text: 'Button',
					onclick: function( ) {
						editor.windowManager.open( {
							title: 'Insert Button',
							body: [
								{ type: 'textbox', name: 'content', label: 'Text', size: 40 },
								{ type: 'textbox', name: 'url', label: 'URL' },
								{ type: 'listbox', name: 'target', label: 'Target', values: [ { text: 'Self', value: '' }, { text: 'Blank', value: '_blank' } ] },
								{ type: 'listbox', name: 'size', label: 'Size', values: [ { text: 'Normal', value: '' }, { text: 'Small', value: 'small' } ] },
								{ type: 'textbox', name: 'color', label: 'Color', value: 'Inherit' },
								{ type: 'listbox', name: 'rounded', label: 'Rounded', values: [ { text: 'No', value: '' }, { text: 'Yes', value: 'yes' } ] },
								{ type: 'listbox', name: 'inverse', label: 'Inverse', values: [ { text: 'No', value: '' }, { text: 'Yes', value: 'yes' } ] },
								{ type: 'listbox', name: 'link', label: 'Style', values: [ { text: 'Button', value: '' }, { text: 'Link', value: 'yes' } ] },
								{ type: 'textbox', name: 'class', label: 'CSS Class' },
							],
							onsubmit: function( e ) {
								var atts = { url: e.data.url, target: e.data.target, size: e.data.size, color: ( e.data.color == 'Inherit' ? '' : e.data.color ), rounded: e.data.rounded, inverse: e.data.inverse, link: e.data.link, class: e.data.class };
								prodoShortcodePaste( editor, 'button', e.data.content, atts );
							}
						} );
					}
				},
				{
					text: 'Icon',
					onclick: function( ) {
						editor.windowManager.open( {
							title: 'Insert Font-Awesome Icon',
							body: [
								{ type: 'textbox', name: 'name', label: 'Icon Name', size: 40 },
								{ type: 'label', label: ' ', text: 'Example, asterisk (without prefix "fa-")' },
								{ type: 'label', label: ' ', text: 'http://fontawesome.io/icons/' },
								{ type: 'label', label: ' ', text: ' ' },
								{ type: 'textbox', name: 'size', label: 'Size (In Pixels)', value: 'Inherit' },
								{ type: 'textbox', name: 'class', label: 'CSS Class' },
								{ type: 'listbox', name: 'spin', label: 'Spin', values: [ { text: 'No', value: 'false' }, { text: 'Yes', value: 'true' } ] },
								{ type: 'listbox', name: 'align', label: 'Align',  values: [ { text: 'Normal', value: '' }, { text: 'Left', value: 'left' }, { text: 'Right', value: 'right' } ] },
							],
							onsubmit: function( e ) {
								var atts = { name: 'fa-' + e.data.name, size: ( e.data.size == 'Inherit' ? '' : e.data.size ), class: e.data.class, spin: ( e.data.spin == 'false' ? '' : e.data.spin ), align: e.data.align };
								prodoShortcodePaste( editor, 'icon', false, atts );
							}
						} );
					}
				},
				{
					text: 'Clear',
					onclick: function( ) {
						editor.windowManager.open( {
							title: 'Insert Clear',
							body: [
								{ type: 'textbox', name: 'gap', label: 'Gap', value: '0', size: 40 },
								{ type: 'label', label: ' ', text: 'In Pixels' },
							],
							onsubmit: function( e ) {
								var atts = { gap: ( parseInt( e.data.gap ) > 0 ? parseInt( e.data.gap ) : false ) };
								prodoShortcodePaste( editor, 'clear', false, atts );
							}
						} );
					}
				},
				{
					text: 'Map',
					onclick: function( ) {
						editor.windowManager.open( {
							title: 'Insert Google Map',
							body: [
								{ type: 'textbox', name: 'address', label: 'Address', value: 'New York, United States', size: 40 },
								{ type: 'label', label: ' ', text: 'Or Latitude and Longitude:' },
								{ type: 'textbox', name: 'latitude', label: 'Latitude', value: '' },
								{ type: 'textbox', name: 'longitude', label: 'Longitude', value: '' },
								{ type: 'label',  label: ' ', text: ' ' },
								{ type: 'textbox', name: 'zoom', label: 'Zoom level', value: '15' },
								{ type: 'label', label: ' ', text: 'Zoom level between 0 to 21' },
								{ type: 'textbox', name: 'height', label: 'Height', value: '200' },
								{ type: 'label', label: ' ', text: 'In Pixels' },
							],
							onsubmit: function( e ) {
								var atts = { address: ( e.data.address != 'New York, United States' ? e.data.address : false ), latitude: e.data.latitude, longitude: e.data.longitude, zoom: ( e.data.zoom != 15 ? parseInt( e.data.zoom ) : false ), height: ( e.data.height != 200 ? parseInt( e.data.height ) : false ) };
								prodoShortcodePaste( editor, 'google-map', false, atts );
							}
						} );
					}
				},
				{
					text: 'Other',
					menu: [
						{
							text: 'Alert Message',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Alert Message',
									body: [
										{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
										{ type: 'textbox', name: 'text', label: 'Primary Text', multiline: true, minHeight: 100 },
										{ type: 'listbox', name: 'type', label: 'Type', values: [ { text: 'Information', value: 'info' }, { text: 'Success', value: 'success' }, { text: 'Warning', value: 'warning' }, { text: 'Danger', value: 'danger' } ] },
									],
									onsubmit: function( e ) {
										var atts = { title: e.data.title, type: e.data.type };
										prodoShortcodePaste( editor, 'alert', e.data.text, atts );
									}
								} );
							}
						},
						{
							text: 'Promotion Box',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Promotion Box',
									body: [
										{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
										{ type: 'textbox', name: 'text', label: 'Primary Text', multiline: true, minHeight: 100 },
										{ type: 'listbox', name: 'style', label: 'Style', values: [ { text: 'One', value: '' }, { text: 'Two', value: 'two' }, { text: 'Three', value: 'three' } ] },
									],
									onsubmit: function( e ) {
										var atts = { title: e.data.title, style: e.data.style };
										prodoShortcodePaste( editor, 'promotion', e.data.text, atts );
									}
								} );
							}
						},
						{
							text: 'Services – Small',
							menu: [
								{
									text: 'Services',
									onclick: function( ) {
										prodoShortcodePaste( editor, 'services-alt', '<br />[service-alt icon="fa-asterisk" column="1/6"]First[/service-alt]<br />[service-alt icon="fa-heart" column="1/6"]Second[/service-alt]<br />', false );
									}
								},
								{
									text: 'Service Item',
									onclick: function( ) {
										editor.windowManager.open( {
											title: 'Insert Service Item',
											body: [
												{ type: 'textbox', name: 'title', label: 'Title', size: 40 },
												{ type: 'textbox', name: 'icon', label: 'Icon Name' },
												{ type: 'label', label: ' ', text: 'Example, asterisk' },
												{ type: 'label', label: ' ', text: ' ' },
												{ type: 'listbox', name: 'column', label: 'Column', values: [ { text: '1/2', value: '1/2' }, { text: '1/3', value: '1/3' }, { text: '1/4', value: '1/4' }, { text: '1/6', value: '1/6', selected: true } ] },
												{ type: 'textbox', name: 'class', label: 'CSS Class' },
											],
											onsubmit: function( e ) {
												var atts = { icon: 'fa-' + e.data.icon, column: e.data.column, class: e.data.class };
												prodoShortcodePaste( editor, 'service-alt', e.data.title, atts );
											}
										} );
									}
								}
							]
						},
						{
							text: 'Details List',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Details List',
									body: [
										{ type: 'listbox', name: 'share', label: 'Share Panel', values: [ { text: 'Yes', value: 'yes' }, { text: 'No', value: '' } ] }
									],
									onsubmit: function( e ) {
										var atts = { share: e.data.share };
										prodoShortcodePaste( editor, 'details', '<ul><li>First Feature</li></ul>', atts );
									}
								} );
							}
						},
						{
							text: 'Sticker',
							onclick: function( ) {
								editor.windowManager.open( {
									title: 'Insert Sticker',
									body: [
										{ type: 'textbox', name: 'label', label: 'Label', size: 40 },
										{ type: 'listbox', name: 'color', label: 'Color', values: [ { text: 'Default', value: '' }, { text: 'Red', value: 'red' }, { text: 'Orange', value: 'orange' }, { text: 'Green', value: 'green' }, { text: 'Blue', value: 'blue' } ] },
									],
									onsubmit: function( e ) {
										var atts = { label: e.data.label, color: e.data.color };
										prodoShortcodePaste( editor, 'sticker', false, atts );
									}
								} );
							}
						}
					]
				}
			]
		} );
	} );
} )( );
