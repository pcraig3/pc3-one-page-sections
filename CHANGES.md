#### 0.9.0
* Added offsets to scroll and sticky plugins.
* Added Pure responsive grids CSS files
* Changed custom CSS filename to the name of this plugin
* Added an autoloading class based on (ie, directly lifted) [Shashin's Autoloader](https://github.com/toppa/Shashin/blob/master/lib/ShashinAutoLoader.php)
    * This meant changing a lot of classnames
* Added a Container class based on (same idea) [Shashin's Container](https://github.com/toppa/Shashin/blob/master/lib/ShashinContainer.php)
* Added a FunctionsFacade class based on (guess who?) [Shashin's FunctionsFacade](https://github.com/toppa/Shashin/blob/master/lib/ShashinFunctionsFacade.php)
* Hunted down (nearly) all plugin-wide string variables and added them to the Container instead
* `pc3_get_parameter` shortcode pulls said variables into template files.
* Sections added to a key on `WP_Query`, making our template files cleaner. 
* QueryFacade, CSSEditor, and TemplateLoader injected by container
* Editor metabox (and, maybe, others) removed from Edit Screen of Page on which Sections will be displayed
* Cleaned up template calling
* Added Settings Page with `Debug` parameter
* Refactored SettingFields as their own classes because it's pretty helpful:
    * 1. Fields can be passed to objects (instead of copy-pasting arrays)
    * 2. Fields' values can be added to our Container parameters
    * 3. Fields can be type-hinted, where needed
* Subclassed my AdminPages and PageSettingFields because it was easy
* Commenting here and there

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
* Unhid the vendor directory so that we can plug and play this thing
* Embedded YouTube videos work without too much finagling
* Added [page-scroll-to-id](https://github.com/malihu/page-scroll-to-id) with [Bower](http://bower.io/) for some smooth scrolling goodness
* Set up the [Pure Marketing Layout](http://purecss.io/layouts/marketing/) as a proof of concept

#### 0.4.0
* One Page Sections page has template overridden: all sections listed at once
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
