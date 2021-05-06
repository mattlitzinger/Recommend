# Recommend 
Contributors: mattlitzinger  
Tags: recommend, like, heart, thumbs up  
Donate link: https://paypal.me/mattlitzinger  
Requires at least: 4.7  
Tested up to: 5.7  
Requires PHP: 7.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Add like/recommend functionality to posts and pages. This metric can then be used to return more relevant search results or a collection of most liked posts/pages.   

## Description
*Features:*  
* Display the number of likes on a post
* Saves cookie to disable repeat voting on the same post
* Add the like count/link to any page using shortcodes

*Advanced Options:*  
* Set label text for count (singular and plural)
* Disable labels text for count site-wide
* Choose between a \"Thumbs Up\" or a \"Heart\" icon
* Disable plugin CSS and add custom styling rules

To display the like count/link in your template, add the below code:  
`<?php if( function_exists('wp_recommend_show_likes') ) wp_recommend_show_likes(); ?>`

Or use the shortcode:  
`Shortcode [wp-recommend-likes]`
