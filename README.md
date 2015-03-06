#One Page Sections
* Contributors: [Paul Craig](https://github.com/pcraig3), etc
* Tags: plugin, sections, one-page, whatever
* Requires at least: 3.8
* Requires PHP: 5.3
* Tested up to: 4.1
* Stable tag: mostly
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

###Short description

One Page Sections is a WordPress plugin that makes it possible to create a one-page layout within the context of another theme.  Plugin assumes authors are comfortable writing HTML and CSS.  

###Long description

One Page Sections is a WordPress plugin makes it possible to create the a one-page layout in a reasonably intuitive manner, as this behaviour is not very easy to achieve semantically.  

After installation, One Page Sections registers "Sections" as a [Custom Post Type](http://codex.wordpress.org/Post_Types), which will eventually represent blocks of content of a one-page scrolling layout.  However, since WordPress' default behaviour is to assume a separate page per post (~and One Page Sections doesn't allow Sections to have public-facing URLs~) **users must select an existing page** on which to display sections.  (Existing content on selected page will be disregarded.)

In addition, Sections can be re-ordered by dragging and dropping them in the Settings page (~there's a bug needs fixing~), and (~CSS overrides which have yet to be introduced, hmmm~).

#####Included third-party libraries

* [PureCSS](http://purecss.io/) for quickly prototyping UI elements on the front-end
* [page-scroll-to-id](https://github.com/malihu/page-scroll-to-id) for JavaScript-powered scrolling to elements on the same page
* [sticky](https://github.com/garand/sticky) for JavaScript-powered fixing elements in the browser
* [Gamajo Template Loader](https://github.com/GaryJones/Gamajo-Template-Loader) allows [custom templates to be bundled into the plugin that can easily be overwritten by an intermediate-level user](https://pippinsplugins.com/template-file-loaders-plugins/).
* [Admin Page Framework](https://wordpress.org/plugins/admin-page-framework/) so as to more easily create Admin Pages (and, especially, the forms therein)

####Unavoidable Issues 
* Since the default header and footer are preserved (at least, unless templates are overridden), you are always going to be fighting the theme's CSS.  
* I've included the Pure CSS framework (for those interested), which might cause CSS complications
* Secondly, writing HTML code in WordPress editor is totally a drag. Copy + pasting works, but it's not a particularly elegant solution

##TODOs

* Section URLs redirect to index, or to page with sections.
* Hunt down the 7 TODOs -- mostly this means more in-code documentation
* grunt or something.
* Settings Page with options to (de)queue libraries.
* Remove CSS file changes from being tracked.

##Installation

###Upload

* Download the latest ~~crazy unstable~~ (haha, kidding) probably-fine branch archive (choose the "zip" option).
* Go to the `Plugins` -> `Add New` screen and click the Upload tab.
* Upload the zipped archive directly.
* Go to the `Plugins` screen and click `Activate`.
* Shazam.

###Manual

* Download the latest tagged archive (choose the "zip" option).
* Unzip the archive
* Copy the folder to your /wp-content/plugins/ directory. (Like FTP or whatever)
* Go to the `Plugins` screen and click `Activate`.
* Also shazam.

Check out [the Codex for more information about installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

###Updates

Either 

1. Check this repository every so often for new updates and then manually install new version of plugin following aforementioned instructions.
 	* (*hint: try not to do this*)
2. Download and install the consistently excellent [Github Updater](https://github.com/afragen/github-updater) plugin.
	* GitHub Updater enables effortless updates for plugins like this one (i.e., plugins hosted on github rather than [the WordPress plugin respository](https://wordpress.org/plugins/)).
    * Any new appropriately-tagged updates are detected and this plugin can then be upgraded like any other.

##How to do things

###1. How to create a new Section

Create a new Section the same way you would create a new Post

 1. Sections. 
 2. Add New. 
 3. Do literally anything. 
 4. Publish.

###2. How to display your sections.

Sections can only be displayed on a selected Page.

 1. Create a Page which has no content (or maybe a meta description or something, to make your SEO happy).
 2. Settings page drop-down list.
 3. Select Page. 
 4. Save options.

###3. How to re-order your sections.

Sections can be re-ordered fairly easily, in case you decide your progression of arguments makes no sense.

 1. Sections Submenu. 
 2. Manage Sections. 
 3. Sections are pulled in ordered by your custom order. Default ordering is latest Sections first. 
 4. ~Small bug menu order incorrect look into it.~

###(4. CSS)
* (this is how you edit the CSS)

###(5. Templates) 
* (this is how you override a template)

###(6. Linking sections)
* (this is how the one-page scroll works)