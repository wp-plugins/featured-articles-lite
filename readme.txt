=== Featured Articles Lite ===
Contributors: constantin.boiangiu
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3999592
Tags: slider, featured, articles, posts, pages
Requires at least: 2.7
Tested up to: 3.0.1
Stable tag: trunk

Put featured posts or pages into a fancy JavaScript slider that can be set to display on any category page, page or homepage.

== Description ==

Features

- SEO friendly
- Autoslide
- Automatic or manual placement
- Set the slider to animate at any interval when your visitors come to your website.
- Animation direction
- Make it yours! Choose between slides coming from top or from left to mimic a vertical or horizontal slider.
- Themes - Default themes available; easy implementation for new themes
- Options - various options available, from thumbnail display to categories to gather articles from and JavaScript settings, all available from Wordpress admin
- Navigation - Make a choice between forward-backwards navigation and individual navigation or use both with only 2 clicks. Also, mouse wheel navigation available.

More information can be found on [php-help.ro](http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/ "Wordpress Featured Articles plugin").

== Installation ==

1. Download and extract folder wp_featured_articles
2. Upload the whole *featured-articles-lite* folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to WP Admin->Settings->Featured articles and configure your slider
5. To display the slider, you can either choose sections from admin ( pages or categories ) or place the following code in your template: 

`<?php FA_display_slider(); ?>`

For any clarifications, please read or leave a comment on [WP featured articles Lite homepage](http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/ "WP Featured Articles plugin")

== Screenshots ==

1. Theme Light
2. Theme Dark
3. Sideway titles navigation

== Changelog ==

= 2.1 =
* Date format in article short description displays according to blog date format option setting
* Editable text for read more link
* New option to set article title as link
* Image detection no more made inside theme but done by function ( less code in slider theme )
* New option to display the slider manually by adding a function to theme files ( function is FA_display_slider - see installation for instructions ) 
* Slider mouse wheel navigation can be enabled/disabled from wp admin
* Links in article text allowed 
* For automatic placement, option to choose loop to display on top of 

= 2.0 =
Initial release for the new redesigned Wordpress Featured Articles