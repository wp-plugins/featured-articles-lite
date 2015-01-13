=== FA Lite - WP responsive slider plugin ===
Contributors: codeflavors
Tags: slider, slideshow, WordPress slider, video, YouTube, Vimeo, responsive, jQuery, themes, iPad, image, gallery, featured, articles, posts, pages, custom posts, seo, search engine optimized
Requires at least: 4.0
Tested up to: 4.0.1
Stable tag: trunk

Featured Articles is a responsive WordPress slider plugin that can create sliders from any existing WordPress content (posts, custom posts, pages or gallery images).

== Description ==

**Featured Articles for WordPress** slider plugin allows you to create sliders in your blog pages directly from your already written content, be it posts, custom posts, pages or WordPress Gallery images. With the ability to change any slider aspect by making use of slideshow themes that are delivered with the plugin, almost anything is possible. Also, it offers the possibility to create additional themes (with some PHP, CSS and JavaScript knowledge) that can have custom written animations by extending the base functionality of the main slider script.

https://vimeo.com/113921051

Slideshows can be published in WordPress by any of these ways (or all at the same time):

* manual placement directly into your WordPress theme template files (requires editing the template file you want to place a slideshow in and add a small piece of code);
* shortcode placement directly into a post or page content;
* widget placement into any widget areas your theme has;
* automatic placement in any page just above the page loop.

While Lite version of FeaturedArticles provides all the neccessary tools for creating very nice slideshows, PRO version of this same plugin comes in addition with:

* more slider themes, all video enabled and responsive;
* **video enabled** custom slides for **YouTube and Vimeo** that can embed videos in your WordPress sliders;
* custom slides that can be created using the visual editor;
* sliders can be created from any post type registered on your WordPress website;
* for posts and custom post types, selection of posts by taxonomies;
* mixing of posts, custom posts, pages and custom slides into the same slider;
* visual color scheme editor that allows you to blend-in slideshows into your overall blog design without having to write a single line of CSS;
* priority support and debugging for 3'rd party plugins and themes conflicts;
* cached sliders for faster page loading time;
* possibility to attach videos to any slide (post, custom post, page or custom slide);
* possibility to import video title, description and image as slide title, content and slide image or featured image;
* dynamic slider areas management that allow slider publishing in your WordPress website by simple drag and drop;
* sliders created from WordPress Gallery images;
* and more...

**Important links:**

* [Documentation](http://www.codeflavors.com/documents/featured-articles-pro-3/ "Featured Articles for WordPress documentation") on plugin usage and slideshow theme structure;
* [Forums](http://www.codeflavors.com/codeflavors-forums/forum/featured-articles-3-0/ "CodeFlavors Community Forums") (while we try to keep up with the forums here, please post any requests on our forums for a faster response);
* [FeaturedArticles homepage](http://www.codeflavors.com/featured-articles-pro/ "FeaturedArticles for WordPress")
* [CodeFlavors News](http://www.codeflavors.com/news/ "CodeFlavors news on FeaturesArticles for WordPress plugin") - [CodeFlavors](http://www.codeflavors.com/ "CodeFlavors - devoted to WordPress") is our new home.

**Features:**

* Add, remove, order any slideshow content made of pages or mixed content;
* Animation control (based on individual themes);
* Write custom slides by using the WordPress editor (PRO);
* Put videos in posts, custom posts, pages and custom slides from Vimeo or YouTube and import video image, video title and description as slide image, title and description;
* Customize posts and pages displayed into slideshows by specifying a different title, content, slide background color, image and more;
* Change themes by choosing from the available slider themes;
* Change theme color palette by simply creating a new color stylesheet that can skin the theme without messing with the CSS responsible for layout;
* Create color palette stylesheets using a visual editor(PRO);
* Preview slideshow directly in your website before publishing it in your pages.
* Display slideshows by widgets, shorcodes, manual code snippet or automatic display above any page loop;
* Create new themes that can completely change the default animations and can add new options fields custom for it in Slider editing in WordPress admin;
* Cache sliders for faster page loading;
* Allow different user groups access to plugin pages;
* Create dynamic areas that allow you to publish sliders by simply drag and drop;
* Select the areas where you want your sliders to be published;
* Customize slides contents by using the available options;
* and more...

These are just a few of the things this plugin can do so just go on and try it for yourself.

The plugin can be used to display your fresh content on your homepage, display related posts on single post pages, put special offers into sliders to create attention and any other way you see fit. 

== Installation ==

**Before updating, make sure you back-up all your custom made themes.**

Version 3.0 is a complete plugin rewrite. Previous slider themes won't be compatible by default with this version. You will be able to choose the previous themes but they will issue PHP errors when used.

Sliders created with Featured Articles 2.X will be converted to current version automatically when activating Featured Articles Lite 3.0.

Now, here are the update instructions (automatic update will also work):

If you are upgrading to the current version, make sure you backup all the files of the previous installation. After you backup, make sure that under plugin Setting page, the option to completely unistall the plugin (including content) is unchecked since version 3.X will automatically convert your existing sliders to current version when activating.

Next, either update version 3.X from WordPress admin page Plugins or by FTP. If updatng by FTP, make sure that you first remove the previous plugin version completely before uploading version 3.X.

For any clarifications ask for help on our [Forums] (http://www.codeflavors.com/codeflavors-forums/forum/featured-articles-3-0/ "Featured Articles 3 Forums").
We don't actively monitor the forum here on Wordpres.org so responses to questions posted here might take a while to get answered.

== Screenshots ==

https://vimeo.com/113921051

== Changelog ==

**Please note**, if you modified or created new slider themes, you should set up a slider themes folder outside the plugin folder to avoid having your modifications overwritten.
The process of moving the slider themes folder from within the plugin folder is described here: [How to move slider themes folder](http://www.codeflavors.com/documentation/featured-articles-pro-3/moving-slider-themes-folder/ "Move Featured Articles WP slider themes outside the plugin folder").

= 3.0.2 =
* Solved a bug in slider script that was displaying only a part of the image
* Solved a bug in admin slide edit modal screen that wasn't removing the slide image when action was initiated
* Introduced a new option for theme Simple that controls if the bar timer should be displayed or not when autoslide is on.


= 3.0.1 =
* Added new metabox in Slider edit page that displays the PHP code that can be used to display a slider directly into WP theme files
* Solved a bug related to manual slider placement by using the PHP code that was generating a PHP warning

= 3.0 =
* Complete plugin rewrite and internal functionality

Before updating, please make sure sure you have a back-up. While we don't expect anything to go wrong, it's better to be safe.
Please note, previous slider themes from version 2.5.4 are not compatible with Featured Articles 3.0.

= 2.5.4 =
* Solves compatibility with qTranslate plugin (slide text not being translated)

= 2.5.3 =
* New option to remove credits from sliders
* Credit image is self hosted
* HTTPS enabled
* Solves small bugs related to display tables in administration

= 2.5.2 =
* Solved issue in slideshow themes that was causing second slide to show for an instant when page was loading. Modified files: all display.php files in all slideshow themes.

= 2.5.1 =
* Solved WordPress 3.5 error fatal error related to $wpdb->prepare()

= 2.5 =
* Multibyte string support.
* Image preloaders for featured images in slides.
* Function to retrieve slideshow settings ( FA_get_option() ) can now take arrays as well as string as argument.
* WP Touch PRO compatibility.
* Template function ( the_fa_author() ) and options to display author name in slideshows.
* New option to link author name to author posts page.
* Image resize in slideshows to exact dimensions entered by user (uses default WordPress functionality; images will be cropped if bigger).
* New responsive theme ( Classic Responsive ) adapted from theme Classic.

= 2.4.9.2 =
* resolved podPress related bug that was displaying raw podPress player code into slides. Instead of applying all the_content filters, it applies only the default WordPress filters (wptexturize, convert_smilies, convert_chars, wpautop, shortcode_unautop).

= 2.4.9.1 =
* Solved bug that was causing remote images not to be displayed into the slideshow.

= 2.4.9 =
* New options to hide title, text, date and read more link in slides when editing or creating a slideshow.
* Re-designed automatic placement options in pages and categories.
* WordPress editor button to easily place slideshow shortcode in post or page.
* Solved bug that was displaying order of pages wrong when creating/editing a slideshow.
* Images in slideshows now have width and height set on them for increased overall page performance.
* New template function to display the date in slides ( the_fa_date() ).
* New template function that will hide any wrapping element if all other elements are hidden ( title, text, date ... ).
* New option to allow all tags in slide description.

= 2.4.8 =
* Compatibility with WPtouch plugin by verifying if WPtouch Restricted Mode option is on and preventing any slideshows from displaying. A new option under Settings can also prevent any slideshows from displaying in WPtouch mobile themes but if Restricted Mode is on, even if allowed from Featured Articles Lite, no slideshows will display.
* Compatibility with qTranslate plugin. No action needed to enable it.

= 2.4.7 =
* Solves problem of post/page text and title not being replaced by custom ones specified in post/page editing.

= 2.4.6 =
* Solves the problem of multiple slideshows displayed into the same page that have same slideshow theme but different color schemes in settings.

= 2.4.5 =
* Solved small bug related to linked slide titles target attribute value not being between double quotes.

= 2.4.4 =
* Compatibility with jQuery 1.7 from Wordpress 3.3 for mouse wheel navigation in front-end slideshows.
* Automatic placement enabled by default when installing/updating the plugin (previously set disabled and could be enabled from plugin Settings page).
* Wordpress 3.3 compatibility for gallery uploaded images with the new flash uploader.

= 2.4.3 =
* Backwards compatibility for old Light and Dark themes. Current version merged these 2 themes into a single one called Classic that uses color scheme option to change between Light and Dark. If in FeaturedArticles 2.3 you were using the default Light and/or Dark themes, on update they will get deleted and no longer available. This update solves the problem by automatically assigning Light and Dark to Classic with the appropriate color scheme.

= 2.4.2 =
* Repaired dead/wrong links from Wordpress admin plugin pages.

= 2.4.1 =
* Option to change the default slideshow themes folder from within the plugin contents to anywhere into wp_content folder. Option is available in plugin menu->Settings page.
* Solved bug that was keeping dark color palette selected by default for all themes making Classic Light unavailable.
* Solved bug that was removing the link that places custom featured image on posts/pages in Wordpress gallery when search of filtering was performed.

= 2.4 =
* Image attachment to slide is made using the default Wordpress Media Gallery
* Templating functions for themes to display information in slideshows
* Themes can extend the default slideshow script to create custom animations (requires jQuery knowledge)
* Themes can add custom fields to slideshow editing form that are unique for them (options that apply only to a theme)
* Custom written slides that can be created with the default Wordpress editor (PRO)
* Slideshows created from pages or featured content (mix of pages, posts and custom slides) have slides ordering option by drag-and-drop
* Custom titles, text and read-more link texts individual for every post/page or custom slide
* Themes can have multiple color palette stylesheets to allow a better blending with the website without having to change the main theme stylesheet
* And probably many other things that we forgot during all this time :)

= 2.3.7 =
* Solved bug that disabled custom image placement on post/page when Google Analyticator plugin was installed
* Solved bug that caused automatic image detection within post content not to be performed because of plugin stripping HTML from post content. Please note that you need to do a small change if you display the slider with a custom theme made by you or others. In display.php inside your theme folder change

`<?php echo FA_truncate_text($post->post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?>` 

into

`<?php echo FA_truncate_text($post->FA_post_content, $image ? $options['desc_truncate'] : $options['desc_truncate_noimg']);?>`

= 2.3.6 =
* Solved shortcode bug that caused the slider not to be displayed into the exact place the shortcode was placed in post content (changes made in file: featured_articles.php)
* Solved stylesheet issue not being loaded for developer link at the bottom of the slider (changes made in file: featured_articles.php)

= 2.3.5 =
* Solved IE8 bug that caused autoslide not to start (error message: Message: 'currentKey' is null or not an object)
* Solved issue with autoslide that wasn't correctly reset when navigation was clicked
* Made links in article description have the same color as the text

= 2.3.4 =
* Created shortcode support to display sliders inside post/page content.

= 2.3.3 =

* Solved Allowed tags option bug that wasn't taken into account by the plugin when displaying posts in slider.

= 2.3.2 =
* Solved z-index issue in themes causing side navigation not to be able to be clicked 

= 2.3.1 =
* Solved JavaScript bug that made only the first post clickable.

= 2.3 =
* Slider script developed with jQuery (this solves the conflict between different JavaScript frameworks)
* Creation/management and placement of multiple sliders into the same page or all over your blog
* Widget support provided (go to Appearance->Widgets and look for FA Lite Slider)
* Easy manual placement with code to be implemented provided for each slider created
* Slightly modified themes to make it work with multiple sliders

= 2.2 =
* User specified HTML tags allowed into featured post description displayed into the slider
* Meta box to ease the way custom images and featured posts are inserted into the slider
* Possibility to display posts or pages in random order into the slider
* Featured articles slider resizable from administration area ( default values get specified into stylesheet )
* Author link ( if you want to support the plugin ) that can be disabled from administration
* Slider settings access is restricted to administrators only with the possibility to give access to any other group of users available in wordpress
* Menu no longer available under Settings->Featured articles but directly in Wordpress admin sidebar ( look for FA Lite )
* Themes modified to support featured posts slider resizing ( both CSS files and display files have changed a little ). If you update the plugin and you made custom themes, back-up first your themes folder.
* Custom post/pages images improved usage and interface
* Easy setting for featured posts and pages to be displayed into the slider
* Image detection improved even more. Currently there are 2 ways to set an image for a certain post: by setting the image as a custom field and second by detecting the image from post content. For images detected in post content, the plugin tries to identify the exact attachment from the database and if found, it automatically sets the image into the custom field. The only thing it needs is for the image to have the width and height attributes set in HTML.
* New theme available (Smoke).

= 2.1 =
* Date format in featured post short description displays according to blog date format option setting
* Editable text for read more link
* New option to set featured post title as link
* Image detection no more made inside theme but done by function ( less code in slider theme )
* New option to display the slider manually by adding a function to theme files ( function is FA_display_slider - see installation for instructions ) 
* Slider mouse wheel navigation can be enabled/disabled from wp admin
* Links in featured posts text allowed 
* For automatic placement, option to choose loop to display on top of 

= 2.0 =
Initial release for the new redesigned Wordpress Featured Articles

== Troubleshooting ==

Plugin is guaranteed to work on a clean WordPress install. Since themes and other plugins don't always play nice, there are a few things you could check if slideshows won't work in your pages:

1. Check that only one jquery file is loaded. Most times, a manually loaded jQuery version in theme header or by a plugin may be the cause. Look in your page source in browser and see if that's the problem.
2. Plugins merging JavaScript and CSS files may also cause problems. In this case, it's all about god intentions with bad results.
3. Use the proper Wordpress version (4 +).
4. See if your theme footer.php file calls wp_footer().

If all options are exhausted, you can always ask for help on our forums at [CodeFlavors Featured Articles WP forum](http://www.codeflavors.com/codeflavors-forums/forum/featured-articles-3-0/ "Featured Articles 3 for WordPress forum"). We'll help you figure out things.

