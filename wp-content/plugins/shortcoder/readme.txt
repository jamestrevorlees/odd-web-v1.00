=== Shortcoder ===
Contributors: vaakash
Author URI: http://www.aakashweb.com/
Plugin URI: http://www.aakashweb.com/wordpress-plugins/shortcoder/
Tags: shortcode, ads, adsense, advertising, bookmark, bookmarking, bookmarks, custom, embed, feed, feeds, rss, flash, html, image, images, javascript, jquery, link, links, media, page, pages, youtube, plugin, post, posts, share, social, social bookmarking, form, template, video, yahoo
Donate link: http://bit.ly/scDonate
License: GPLv2 or later
Requires at least: 3.3
Tested up to: 4.4
Stable tag: 3.4.1

Create custom "Shortcodes" with HTML, Javascript snippets stored in it and use that shortcode within posts and pages. Check the demo video.

== Description ==

Shortcoder is a plugin which allows to create a custom shortcode and store HTML, Javascript and other snippets in it. So if that shortcode is used in any post or pages, then the code stored in the shortcode get exceuted in that place.

= Features =

* Create **"custom shortcodes"** easily and use them within WordPress posts
* Use any name for the created shortcode (ex: `[sc name="youtube"]`)
* Use any kind of **HTML** as Shortcode content.
* Parameters can also added to HTML (ex: `<strong>%%mytext%%</strong> [sc name="testing" mytext="hello"]` )
* Visual editor for adding shortcode contents.
* Global tinyMCE button available in the editing toolbar for inserting created shortcodes.
* Globally disable the shortcode when not needed.
* Can disable the shortcode, showing it to admins.

[youtube="http://www.youtube.com/watch?v=GrlRADfvjII"]

= An example usage =

1. Create a shortcode named "adsenseAd" in the Shortcoder admin page.
1. Paste the adsense code in the box given and save it.
1. Use `[sc name="adsenseAd"]` in your posts and pages.
1. Tada !!! the ad appears in the post.

* Using this idea, shortcodes can be created for frequently used snippets.
* You can also add parameters (like `%%id%%`) inside the snippets, and vary it like `[sc name="youtube" id="GrlRADfvjII"]`
* This plugin will be hugely useful to all !!!

DONATE: If you like this plugin, you can show your [support by donating some amount](http://bit.ly/scDonate).

= Fix for WordPress 4.4 issue =

Starting Shortcoder 3.4.1 the syntax for shortcoder is [sc name="your shortcode"]. This is in effect to the shortcode API related changes made in WordPress core which made old the syntax unrecognizable. Version 3.4.1 automatically recognizes the old syntax only in posts where the shortcode is inserted directly and not in widgets or in drag and drop themes modules. Those areas need to be manually replaced from old `[sc:my_shortcode]` to the new `[sc name="my_shortcode"]` syntax. Please use the below resources for any queries.

= Resources =

* [Documentation](http://www.aakashweb.com/wordpress-plugins/shortcoder/)
* [FAQs](http://www.aakashweb.com/faqs/wordpress-plugins/shortcoder/)
* [Support](http://www.aakashweb.com/forum/)
* [Report Bugs](http://www.aakashweb.com/forum/)

== Installation ==

1. Extract the zipped file and upload the folder `Shortcoder` to to `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Go to the "Shortcoder" admin page. Admin page is under the "Settings" menu.
1. Enter a shortcode name.
1. Paste some code in it.
1. Then use the shortcode `[sc name="name of the shortcode"]` in your post. ex: If "youtube" is the shortcode name, then just use `[sc name="youtube"]` in your posts
1. That's all ! 

You can also insert some parameters within the post. Check this page to [learn more](http://www.aakashweb.com/wordpress-plugins/shortcoder/).

== Frequently Asked Questions ==

Please visit the [Plugin homepage](http://www.aakashweb.com/wordpress-plugins/shortcoder/) for lots of FAQ's. Selected are given here.

= I've created a shortcode, how to use it ? =

For example, consider you made a shortcode "advertisement". Then you should use the shortcode `[sc name="advertisement"]` in your post.

= How to temporarily disable a shortcode ? =

Just check the "Temporarily disable this shortcode" in the shortcode admin page to disable it. 
Note: When you disable a shortcode, the shortcode will not be executed in the page.

[More FAQs](http://www.aakashweb.com/docs/shortcoder-doc/)

== Screenshots ==

1. Shortcoder admin page with a Shortcode created (donateForm) with some "customParameters".
2. Using the shortcode in a post.
3. Output of the Shortcode (The shortcode gets replaced with the donate form and the parameters are replaced).
4. Insert shotcodes easily into editor using the TinyMCE post editor button.

[More Screenshots](http://www.aakashweb.com/wordpress-plugins/shortcoder/)

== Changelog ==

= 3.4.1 =
* Fixed Shortcoder not working in WordPress 4.4
* Changed the shortcoder syntax from `[sc:the_name]` to `[sc name="the_name"]` permanently in effect of WordPress 4.4 changes.

= 3.4 =
* New feature: Embedded/Nested shortcodes is now supported.
* New feature: Full fledged native WordPress editor for adding shortcode content with media buttons.
* Bug fix: "duplicate percentage" in content on plugin reactivate.
* Updated admin UI with fixed errors.
* Updated "insert shortcode" interface is revised and some issues are fixed.
* Updated with translatable texts in admin page.
* Minor code revision and changes.

= 3.3 =
* Fixed bug in loops using `foreach`.
* Fixed several PHP notices.

= 3.2 =
* Moved the shortcoder admin page to the "Settings" menu.
* Some admin page issues are fixed.

= 3.1 =
* Changed the "Custom parameter" syntax from %param% to %%param%%
* Code revision.

= 3.0.1 = 
* Added license tag to the readme file.

= 3.0 =
* Plugin code rewritten from scratch.
* Shortcode syntax is changed.
* Supports any custom parameters.
* Admin interface is redesigned and easy to use.
* Added a tinyMCE button to the editing toolbar for inserting the shortcodes in the post.
* Inbuilt shortcodes are removed.

= 2.3 =
* Can disable the shortcode to Administrators.
* Admin interface changed.

= 1.3.1 =
* Changed the folder name's case and some minor bugs.
* Code revision.

= 1.3 =
* Initial Version with 5 inbuilt shortcodes.

(Pre made versions are not released)

== Upgrade Notice ==

Major upgrade. Entire code is rewritten from scratch.