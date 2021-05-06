# Recommend 

Add like/recommend functionality to posts and pages. This metric can then be used to return more relevant search results or a collection of most liked posts/pages.  
  
-----------------------

* Readme : https://github.com/mattlitzinger/Recommend/blob/master/README.md

## Description 

*Features:*  
* Give users to ability to like a post 
* Display the number of likes on a post 
* Add the like count to any post/page 

*Advanced Options:*  
* Custom label text for like count 
* Disable label text for count site-wide 
* Choose between a \"Thumbs Up\" or a \"Heart\" icon 
* Disable plugin CSS or add custom styling rules 

To display the like count/link in your template, add the below code:  
`<?php if( function_exists('wp_recommend_show_likes') ) wp_recommend_show_likes(); ?>`

Or use the shortcode:  
`Shortcode [wp-recommend-likes]`
