<?php
	/*
	Plugin Name: Recommend
	Description: Recommend allows you to add a like user action to your content. Unlike social sharing or commenting, the like action is simple and intuitive. The like count can then be used to return more relevant search results or a collection of most liked posts. 
	Version: 0.6.2
	Author: Matt Litzinger
	Author URI: http://litzdigital.com
	License: GPL2
	*/

	/** 
	 * Function to initialize the plugin
	 */
	function wp_recommend_init() {
    if(!get_option('wp_recommend_settings')) {
      $options = array(
        'icon_type' => 'heart',
        'likes_disable_labels' => 0,
        'likes_singular_label' => 'Like',
        'likes_plural_label' => 'Likes',
        'included_post_types' => '',
        'show_after_content' => 1,
        'remove_css' => 0,
        'custom_styles' => '',
      );
      add_option('wp_recommend_settings', $options);
    }
	}
	wp_recommend_init();

	/**
	 * Function to add javascript code to wp_enqueue_scripts()
	 */ 
	function wp_recommend_add_frontend_script() {
		$options = get_option('wp_recommend_settings');
		if($options['remove_css'] != 1){
			wp_register_style('wp-recommend-css', plugin_dir_url(__FILE__) . 'assets/css/recommend.css');
			wp_enqueue_style('wp-recommend-css');
		}
		wp_register_script('wp-recommend-js', plugin_dir_url(__FILE__) . 'assets/js/like-action.js', array('jquery'), '0.5.0', true);
		wp_enqueue_script('wp-recommend-js'); 
	}
	add_action('wp_enqueue_scripts', 'wp_recommend_add_frontend_script');

	/**
	 * Function to add custom styles to wp_head()
	 */ 
	function wp_recommend_custom_styles() {
		$options = get_option('wp_recommend_settings');
		if(isset($options['custom_styles'])) {
			$html = '<style type="text/css" media="screen">';
  		$html .= $options['custom_styles'];
    	$html .= '</style>';
		} else {
			$html = '';
		}
    echo $html;
	}
	add_action('wp_head', 'wp_recommend_custom_styles');

	/**
	 * Function to add AJAX url to wp_head()
	 */ 
	function wp_recommend_ajax_url() {
    $html = '<script type="text/javascript">';
    $html .= 'var wp_recommend_ajax_url = "' . esc_js(admin_url('admin-ajax.php')) . '";';
    $html .= '</script>';
    echo $html;
	}
	add_action('wp_head', 'wp_recommend_ajax_url');

	/**
	 * Function to increase like count and save to database
	 */ 
	function wp_recommend_add_like() {
		$options = get_option('wp_recommend_settings');
		$post_id = (int)$_POST['post_id'];
	  $current_like_count = wp_recommend_get_like_count($post_id);
	  $new_likes = $current_like_count + 1;
	  update_post_meta($post_id, 'wp_recommend_likes', $new_likes);
	  if($options['likes_disable_labels'] != 1){
	  	if($new_likes == 1){
	  		$label = $options['likes_singular_label'];
	  	} else {
	  		$label = $options['likes_plural_label'];
	  	}
	  } else {
	  	$label = '';
	  }
	  $data = json_encode( array( 'new_likes' => $new_likes, 'likes_label' => $label ) );
		wp_die($data); 
	}
	add_action('wp_ajax_nopriv_wp_recommend_add_like', 'wp_recommend_add_like');
	add_action('wp_ajax_wp_recommend_add_like', 'wp_recommend_add_like');

	/**
	 * Function to decrease like count and save to database
	 */ 
	function wp_recommend_remove_like() {
		$options = get_option('wp_recommend_settings');
		$post_id = (int)$_POST['post_id'];
	  $current_like_count = wp_recommend_get_like_count($post_id);
	  if($current_like_count > 0) {
	  	$new_likes = $current_like_count - 1;
	  } else {
	  	$new_likes = 0;
	  }
	  update_post_meta($post_id, 'wp_recommend_likes', $new_likes);
	  if($options['likes_disable_labels'] != 1){
	  	if($new_likes == 1){
	  		$label = $options['likes_singular_label'];
	  	} else {
	  		$label = $options['likes_plural_label'];
	  	}
	  } else {
	  	$label = '';
	  }
	  $data = json_encode( array( 'new_likes' => $new_likes, 'likes_label' => $label ) );
	  wp_die($data); 
	}
	add_action('wp_ajax_nopriv_wp_recommend_remove_like', 'wp_recommend_remove_like');
	add_action('wp_ajax_wp_recommend_remove_like', 'wp_recommend_remove_like');
 
	/**
	 * Function to return number of likes on a post
	 */ 
	function wp_recommend_get_like_count($post_id) {
		$current_like_count = get_post_meta($post_id, 'wp_recommend_likes', true);
		if(!isset($current_like_count) || empty($current_like_count) || !is_numeric($current_like_count) ) {
			$current_like_count = 0;
		}
		return $current_like_count;
	}

	/**
	 * Function to show number of likes on a post 
	 */ 
	function wp_recommend_show_likes() {
		global $post;
		$options = get_option('wp_recommend_settings');
		if( isset($_COOKIE['wp_recommend_likes']) ) {
			$user_liked_posts = json_decode( $_COOKIE['wp_recommend_likes'] );
			$user_liked_posts = array_map( 'sanitize_text_field', $user_liked_posts );
		} else {
			$user_liked_posts = array();
		}
		$current_like_count = wp_recommend_get_like_count($post->ID);
		if( ( is_array($user_liked_posts) && in_array($post->ID, $user_liked_posts) ) || ( is_integer($user_liked_posts) && $post->ID == $user_liked_posts ) ) {
			$html = '<button class="recommend-likes liked" title="Unlike This" data-post-id="' . $post->ID .'">';
		} else {
			$html = '<button class="recommend-likes" title="Like This" data-post-id="' . $post->ID .'">';
		}
		if($options['icon_type'] == 'heart') {
			$html .= '<svg class="recommend-likes-icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" viewBox="0 0 492.7 492.7" xml:space="preserve"><path d="M492.7 166c0-73.5-59.6-133.1-133.1-133.1 -48 0-89.9 25.5-113.3 63.6 -23.4-38.1-65.3-63.6-113.3-63.6C59.6 33 0 92.5 0 166c0 40 17.7 75.8 45.7 100.2l188.5 188.6c3.2 3.2 7.6 5 12.1 5 4.6 0 8.9-1.8 12.1-5l188.5-188.6C475 241.8 492.7 206 492.7 166z"/></svg>';
		} else {
			$html .= '<svg class="recommend-likes-icon" xmlns="http://www.w3.org/2000/svg" version="1.1" x="0" y="0" viewBox="0 0 241.7 241.7" xml:space="preserve"><path d="M208.6 133.6c10.3 0.5 19.1-7.7 19.6-18.2 0.5-10.5-6.8-20-17.2-20.4l-68.7-8.6c0 0 14.3-24 14.3-59.5C156.6 3.2 139.7 0 129.8 0c-7.8 0-9.9 15.2-9.9 15.2h0c-1.8 9.7-4.1 18.2-12.1 33.8C98.8 66.5 86.6 64.8 72.3 80.4c-2.5 2.7-5.9 7.3-9.2 12.9 -0.3 0.3-0.5 0.7-0.8 1.3 -0.3 0.7-0.6 1.2-1 1.8 -0.5 1-1.1 2-1.6 3.1 -8.8 8.8-22.6 7.9-28.4 7.9 -11.7 0-17.9 6.8-17.9 17.9l0 81.8c0 12.4 5.1 16.6 17.9 16.6h17.9c9 0 16.1 5.2 26.8 8.9 14.8 5.1 36.8 9 74.8 9 6.6 0 27.3 0 27.3 0 6.3 0 11.4-2.9 15-6.4 1.4-1.3 2.8-3.2 3.5-7 0.1-0.6 0.2-3 0.2-3.3 0.5-10.7-6-14.6-9.7-15.8 0.1 0 0-0.1 0.2-0.1l11.7 0.5c10.4 0.5 20.6-7 20.6-19.7 0-10.5-8.5-17.9-18.8-18.4l6.2 0.3c10.4 0.5 19.1-7.7 19.6-18.2C227 143 219 134.1 208.6 133.6z"/></svg>';
		}
		$html .= '<span class="recommend-likes-count">' . $current_like_count . '</span>';

		// Get the value of disable labels checkbox
		if(isset($options['likes_disable_labels'])) {
			$disable_labels = $options['likes_disable_labels'];
		} else {
			$disable_labels = false;
		}
	
		// Check if labels have been disabled or that both exist
		if($disable_labels != 1 && $options['likes_singular_label'] && $options['likes_plural_label']) {
			$html .= ' <span class="recommend-likes-label">';
			if($current_like_count == 1) {
			  $html .= $options['likes_singular_label'];
			} else {
			  $html .= $options['likes_plural_label'];
			}
			$html .= '</span>';
		}
		
		$html .= '</button>';
		return $html;
	}

	/**
	 * Display the number of likes after the post content
	 */ 
	function wp_recommend_show_likes_after_content($content) {
		$options = get_option('wp_recommend_settings');
		$included_post_types = ($options['included_post_types'] !== '') ? explode(',', str_replace(' ', '', $options['included_post_types'])) : array();
		if( 
			( 
				is_single() || 
				is_page() 
			) && 
			(  
				in_array( get_post_type(), $included_post_types) || 
				empty($included_post_types)
			) &&
			isset($options['show_after_content']) && 
			$options['show_after_content'] == 1 
		) {
			$content .= '<p>' . wp_recommend_show_likes() . '</p>';
		}
		return $content;
	}
	add_filter('the_content', 'wp_recommend_show_likes_after_content');

	/**
	 * Shortcode to display recommend button/count on front-end
	 */ 
	function wp_recommend_likes_shortcode() {
		ob_start();
	  echo wp_recommend_show_likes();
	  return ob_get_clean();
	}
	add_shortcode('recommend-likes', 'wp_recommend_likes_shortcode');

	/**
	 * Shortcode to display list of most liked posts on front-end
	 */ 
	function wp_recommend_most_liked_posts_shortcode($atts) {
		$post_type = isset($atts['post_type']) ? $atts['post_type'] : 'post';
		$posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : 5;

		$posts = get_posts(array(
			'post_type'			 => $post_type,
			'posts_per_page' => $posts_per_page,
			'meta_key'			 => 'wp_recommend_likes',
			'orderby'				 => 'meta_value',
			'order'					 => 'DESC'
		));

		$html = '<ul>';

		foreach ($posts as $post) {
			$like_count = wp_recommend_get_like_count($post->ID);
			$html .= '<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
		}

		$html .= '</ul>';

	  return $html;
	}
	add_shortcode('recommend-liked-posts', 'wp_recommend_most_liked_posts_shortcode');

	/**
	 * Admin page for this plugin.
	 */
	require_once plugin_dir_path(__FILE__) . '/inc/admin.php';

	/**
	 * REST API Endpoints.
	 */
	// require_once plugin_dir_path(__FILE__) . '/inc/rest-api.php';

