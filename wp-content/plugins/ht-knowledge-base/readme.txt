=== Heroic Knowledge Base ===
Contributors: herothemes
Tags: knowledge base, knowledge plugin, faq, widget
Requires at least: 4.1
Tested up to: 4.3
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

Add a Knowledge Base 


== Installation ==

It's easy to get started

1. Upload `ht-knowledge-base` unzipped file to the `/wp-content/plugins/` directory or goto Plugins>Add New and upload the `ht-knowledge-base` zip file.
2. Activate the plugin through the 'Plugins' menu in WordPress.



== Frequently Asked Questions ==

= Q. I have a question! =

A. Please consult the Heroic Knowledge Base Documentation accompanying this plugin or see http://herothemes.com/hkbdocs/

= Q. Category thumbnails are too big =

A. You need to use the `Regenerate Thumbnails` plugin to re-generate the thumbnails to the correct size.



== Screenshots ==



== Changelog ==

= 2.2.0 =

Fixed issue with breadcrumbs link
Reordered admin menu
Change voting to post request and removed link
Fixed article count of sub-subcategories
Fixed issue with category icon when creating new category
Improved table of content widget (beta)

= 2.1.0 =

Rebased versioning for new Hero Themes Version Control (HTVC)
Change textdomain hook
Added TOC widget
Fix for breadcrumbs in deep categories

= 2.0.8 =

Improved article and category ordering UI
Fixed bugs in demo installer

= 2.0.7 =

Added analytics core
Added article sorting

= 2.0.6 =

Display subcategories in parent category when option to hide in home/archive selected
Removed some legacy code

= 2.0.5 =

Category listing hotfix

= 2.0.4 =

Textdomain fix

= 2.0.3 =

Removed advanced validation for slugs to allow for more flexible permalink structure

= 2.0.2 =

Fixed issue with CMB2 activation resulting in invalid header error


= 2.0.1 =

Packaged voting module

= 2.0 =

Introduced new templating structure
Added search widget
Numerous bug fixes and coding enhancements
New helper functions
New styling options

= 1.4 =

Separated voting logic from knowledge base
Added welcome page
Added demo installer
Added auto updater functionality
Fixes and improvements for php and security issues
Updated Redux framework
Improved styling for theme compatibility
Improved title and SEO functionality
Improved general theme compatibility
Refined options UI
Various bug fixes and tweaks

= 1.3 =

Voting option improvements
Adding WPML support for knowledge base homepage
Fix for search placeholder when used with WPML
Updated translation strings
Added namespacing to some common namespacing functions
Fixed issue with kb as homepage not displaying posts in correct order
Updated namespacing on show all tag
Removed vote this comment text
Improved view count upgrade functionality
Fixed bug with subcategory article markup being displayed when there are no articles to display
Updated options wording
Added data attribute for category description
Updated HTML
Updating widget descriptions - make more consistent

= 1.2 =

Added HT Knowledge Base Archive dummy page
Article views visible in Knowledge Base post lists on backend
Added ability to set view count and usefulness manually
Added reset votes option
Article tag support
Added custom field support
Improved option to display number of articles in category or tag
Improved title output text
Added link to display remaining articles in category
Fixed voting option on individual articles
Fixed homepage option inconsistencies
Updated some translation texts to improve i18n support
Fixed display comments option for Heroic Knowledgebase


= 1.1 =

Removed ht_kb_homepage requirement (implementing themes must implement by default and declare support with ht_knowledge_base_templates)
Added loads of options for sorting and categorizing articles
Added rating indicator at various locations
Centralized display logic for plugin and supporting themes
Enhanced search and live search
Fixed breadcrumbs
Fixed page titles
Added options for archive display
Fixed where meta displays
Added support for video and standard post formats
Various other bug fixes, tweaks and enhancements

= 1.0 =

Initial release.


== Developer Notes ==

For using theme templates, add support for ht_knowledge_base_templates
For category icon support add support for ht_kb_category_icons
For category color support add support for ht_kb_category_colors
