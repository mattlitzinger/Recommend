<?php

// class Recommend_Custom_Route extends WP_REST_Controller {
// 	/**
//    * Register the routes for the objects of the controller.
//    */
//   public function register_routes() {
//     $version = '1';
//     $namespace = 'recommend/v' . $version;
//     $base = 'posts';
//     register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
//       array(
//         'methods'             => WP_REST_Server::READABLE,
//         'callback'            => array( $this, 'get_items' ),
//         'permission_callback' => array( $this, 'get_items_permissions_check' ),
//         'args'                => array(
//  					'context' => array(
//             'default' => 'view',
//           ),
//         ),
//       )
//     ) );
//   }

  

// }

// $recommend_rest_controller = new Recommend_Custom_Route; 
// add_action( 'rest_api_init', $recommend_rest_controller->register_routes() );

add_action( 'rest_api_init', function () {
	$version = '1';
  $namespace = 'recommend/v' . $version;
  $base = 'posts';
  register_rest_route( $namespace, '/' . $base, array(
  	'methods' => 'GET',
  	'callback' => 'wp_recommend_api_order_posts_by_like_count',
  	'permission_callback' => '__return_true',
    'args' => array(
      // 'id' => array(
      //   'validate_callback' => function($param, $request, $key) {
      //     return is_numeric( $param );
      //   }
      // ),
  	)
	));
	register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
  	'methods' => 'GET',
  	'callback' => 'wp_recommend_api_get_like_count',
  	'permission_callback' => '__return_true',
    'args' => array(
      'id' => array(
        'validate_callback' => function($param, $request, $key) {
          return is_numeric( $param );
        }
      ),
  	)
	));
});

/**
 * Return list of posts ordered by like count
 */
function wp_recommend_api_order_posts_by_like_count( $request ) {
  //get parameters from request
  $params = $request->get_params();

  $posts = get_posts( array(
    'posts_per_page'	=> -1,
		'meta_key'				=> 'wp_recommend_likes',
		'orderby'					=> 'meta_value',
		'order'						=> 'DESC',
  ) );

  $data = $posts;

  //return a response or error based on some conditional
  if ( $data ) {
    return new WP_REST_Response( $data, 200 );
  } else {
    return new WP_Error( 'no_data', 'No likes found for this post ID', array( 'status' => 404 ) );
  }
}

/**
 * Get the like count for a single post by ID
 */
function wp_recommend_api_get_like_count( $request ) {
  //get parameters from request
  $params = $request->get_params();

  $posts = get_posts( array(
    'ID' => $params['id'],
  ) );

  $data = array(
  	'post' => array(
  		'id' => $posts[0]->ID,
  		'post_type' => $posts[0]->post_type,
  		'post_title' => $posts[0]->post_title,
  		'post_date' => $posts[0]->post_date,
  	),
  	'like_count' => wp_recommend_get_like_count($params['id'])
  );

  //return a response or error based on some conditional
  if ( $data ) {
    return new WP_REST_Response( $data, 200 );
  } else {
    return new WP_Error( 'no_data', 'No likes found for this post ID', array( 'status' => 404 ) );
  }
}