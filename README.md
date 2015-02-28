=== One Page Sections ===
Contributors: pcraig3
Tags: one, page, sections
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Quick and Dirty Plugin Builds One (Scrolling) Page with Sections.

== Description ==

Quick and Dirty Plugin Builds One (Scrolling) Page with Sections.

Or so goes the thinking.

== Installation ==

1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= When will it be done =

It's always never going to be done.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== TODOs ==

* Hide metaboxes and editor in selected Page, as content is no longer used.
* Section URLs redirect to index, or to page with sections.
* Hunt down the 16 TODOs -- mostly this means more in-code documentation
* grunt or something.
* Settings Page with debug flag, as well as options to (de)queue libraries.
* Ability to update parameters in container using Settings pages.

== Changelog ==

#### 0.9.0


#### 0.8.2
* More CSS changes.
* Added jquery-sticky plugin.

#### 0.8.1
* Lots of CSS changes.  Added some fonts.  Basically, just bad practice stuff.

#### 0.8.0
* Added `pc3_section_link` shortcode to return on-page hashtag links to other Sections 
* Added method to return Sections by `ID`, by `post_name`, or by `post_title`
* Added Code Editor [AceCustomFieldType](https://github.com/soderlind/AceCustomFieldType) which extends the [AdminPageFramework](https://wordpress.org/plugins/admin-page-framework/)
* Added ability to edit custom CSS code from the Admin Dashboard
* Fixed small bug when updating 'order' meta values
* Hardened 'order' meta value logic

#### 0.7.1
* Removed Composer's vendor directory and instead just manually added two files :(
* [GitHub Updater](https://github.com/afragen/github-updater) was breaking my site because composer was using a git submodule for my vendors

#### 0.7.0
* Calls to WP_Query somewhat isolated
* Able to select the page where to display sections = Awesome
* Pulled a bunch of variables through = Less hardcoding = Awesome

#### 0.6.0
* Updated references to APF static methods that were crashing all over the place
* Added a debugging MetaBox to Sections
* Added a MetaLayer that handles logic related to the re-ordering of Sections
* Nearly standardized `WP_QUERY` calls, although they need to be harmonized
* Added new 'Order' column to Sections listing in Admin Dashboard

#### 0.5.0
* Unhid the vendor directory so that we can plug and play this thing.
* Embedded YouTube videos work without too much finagling.
* Added [page-scroll-to-id](https://github.com/malihu/page-scroll-to-id) with [Bower](http://bower.io/) for some smooth scrolling goodness.
* Set up the [Pure Marketing Layout](http://purecss.io/layouts/marketing/) as a proof of concept

#### 0.4.0
* One Page Sections page has template overridden: all sections listed at once.
* Using [Gamajo_Template_Loader](https://github.com/GaryJones/Gamajo-Template-Loader) to handle template overrides
* Added [Pure.css](http://purecss.io/) and [Fontawesome Icons](https://github.com/FortAwesome/Font-Awesome) using [Bower](http://bower.io/) so as to quickly do up a demo.

#### 0.3.0
* Submitting options preserves order of sortable Section fields.
* Defaults to error message and disabled submit button in case of no Sections
* Dynamically populating sortable fields with Post Titles and Ids of Sections
* Added a new page -- 'Manage Sections' -- in the Sections Post Type submenu
* Added namespace to Admin Page Framework because I was dumb and introduced a conflict.

#### 0.2.0
* Added a Custom Post Type: 'Sections'
* Added the [AdminPageFramework](https://wordpress.org/plugins/admin-page-framework/) to help build pages later

#### 0.1.0
* Just started

