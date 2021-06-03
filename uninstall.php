<?php

// If uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit();
}

// Function to uninstall the 'Recommend' plugin
function wp_recommend_delete_plugin() {

  $wp_recommend_posts = get_posts( 
    array(
      'numberposts' => -1,
      'post_status' => 'any',
      'meta_query' => array(
        array(
          'key' => 'wp_recommend_likes',
          'compare' => 'EXISTS'
        ),
      )
    ) 
  );

  // Remove 'wp_recommend_likes' post meta from DB
  foreach ( $wp_recommend_posts as $wp_recommend_post ) {
    delete_post_meta( $wp_recommend_post->ID, 'wp_recommend_likes' );
  }

  // Remove 'wp_recommend_settings' options from DB 
  delete_option('wp_recommend_settings');

  // Remove 'wp_recommend_likes' session cookie
  setcookie('wp_recommend_likes', '', 1, '/');

}

wp_recommend_delete_plugin();