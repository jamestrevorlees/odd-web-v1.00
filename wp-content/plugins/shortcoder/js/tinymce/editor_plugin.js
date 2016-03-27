/*
 * Shortcoder TinyMCE plugin for inserting Shortcodes
 * http://www.aakashweb.com
 * v1.2
 * Added since WP Socializer v2.0
*/

// For adding button in the visual editing toolbox
(function() {

	tinymce.create('tinymce.plugins.SCButton', {
	
		init : function(ed, url) {	
			ed.addButton('scbutton', {
				title : 'Insert shortcodes created using Shortcoder',
				image : url + '/icon.png',
				onclick : function() {
					if( typeof sc_show_insert !== 'undefined' ) sc_show_insert();
                }
			});	
		},
		
		getInfo : function() {
			return {
				longname : 'Shortcoder',
				author : 'Aakash Chakravarthy',
				authorurl : 'http://www.aakashweb.com/',
				infourl : 'http://www.aakashweb.com/',
				version : '1.2'
			};
		}

	});
	
	tinymce.PluginManager.add('scbutton', tinymce.plugins.SCButton);
	
})();