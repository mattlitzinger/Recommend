=== Recommend ===
Contributors: mattlitzinger
Tags: recommend, like, heart, thumbs up
Donate link: https://paypal.me/mattlitzinger
Requires at least: 4.7
Tested up to: 6.2
Requires PHP: 7.0
Stable tag: 0.6.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Recommend allows you to add a like user action to your content. Unlike social sharing or commenting, the like action is simple and intuitive. The like count can then be used to return more relevant search results or a collection of most liked posts. 

== Description == 

= Features =  
* Give users a "like" action on posts  
* Display the like count on a post  
* Custom label text for like count 
* Disable label text for count site-wide 
* Choose between a "Thumbs Up" or a "Heart" icon 
* Limit like action to specific post types
* Disable plugin CSS or add custom styling rules 

By default, the like count will be displayed below the content for individual posts across all post types. You can disable this in the plugin settings or define which post types to include. 

If you'd rather display the like count in your template files, use the below code:  

    <?php 
        if( function_exists('wp_recommend_show_likes') ) 
            wp_recommend_show_likes(); 
    ?>  

== Shortcodes == 

The following shortcode will display the like count on any post.  

``[recommend-likes]  

The following shortcode will display a list of most liked posts. There are two optional parameters to fine tune the displayed results: `post_type` and `posts_per_page`. The default values for these parameters are shown in the example below.  

``[recommend-liked-posts post_type="post" posts_per_page="5"]

== Changelog ==

= 0.6.3 =
*Release Date: August 9th, 2022*

* Updated to support WordPress 6.0.1 release

* Fixes:
	* Added `in_the_loop()` and `is_main_query()` conditionals for displaying like count after post content

= 0.6.2 =
*Release Date: January 25rd, 2022*

* Updated to support WordPress 5.9 release

= 0.6.1 =
*Release Date: June 3rd, 2021*

* Fixes:
	* Fixed issue w/ disabled admin field

= 0.6 =
*Release Date: June 3rd, 2021*

* Changes:
	* Renamed shortcodes (breaking change) 
	* Updated Readme to include clearer set up instructions 
	* General code cleanup (commments/spacing) 

* New features:
	* Admin field to show/hide like count after the content for all posts 
	* Admin field to define included post types  

= 0.5 =
*Release Date: May 6th, 2021*

* Initial beta release.

