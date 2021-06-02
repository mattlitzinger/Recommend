(function($) {
	$(function() {
		var el = null;
		var post_id = null;
		var liked_posts = [];

		// Check if cookie has been set
		if( getCookie('recommend_likes') != null ) {
			liked_posts = JSON.parse( getCookie('recommend_likes') );
		}

		// Create an click event
		$('.recommend-likes').click(function(){
			el = $(this);
			post_id = el.data('post-id');

			// Check if cookie already contains liked posts
			if( liked_posts ) {
				// Check if clicked post has already been liked
	    	if( $.inArray(post_id, liked_posts) == -1) {
    			// Add post ID to the 'liked_posts' array
    			liked_posts.push(post_id); 
    			// Update the 'recommend_likes' cookie
    			setCookie('recommend_likes', JSON.stringify(liked_posts), 30); 
    			// Make AJAX call to update the like count in the database
    			wp_recommend_increase_like_count( post_id ); 
	    	} else {
		    	// Find the index of the post_id in the lided_posts array and remove it from the array
	    		liked_posts.splice(liked_posts.indexOf( post_id ), 1); 
	    		// Update the 'recommend_likes' cookie
	    		setCookie('recommend_likes', JSON.stringify(liked_posts), 30);
	    		// Make AJAX call to update the like count in the database 
	    		wp_recommend_decrease_like_count( post_id );  
	    	}
	    // New like w/ no cookie set
	    } else { 
	    	// Add post ID to the 'liked_posts' array
    		liked_posts.push(post_id); 
    		// Create new 'recommend_likes' cookie
    		setCookie('recommend_likes', JSON.stringify(liked_posts), 30);
    		// Make AJAX call to update the like count in the database
    		wp_recommend_increase_like_count( post_id ); 
	    }
		});

		// Function to increase the like count via an AJAX request
		function wp_recommend_increase_like_count(post_id) {
			$.ajax({
				type: 'post',
				url: wp_recommend_ajax_url,
				data: {
					action: 'wp_recommend_add_like',
					post_id: post_id,
				},
				beforeSend: function() {
					el.children('.recommend-likes-count').html('-');
				},
				success: function(data){
					var data = JSON.parse(data);
					el.addClass('liked');
					el.attr('title', 'Unlike this');
					el.children('.recommend-likes-count').html(data['new_likes']);
					el.children('.recommend-likes-label').html(data['likes_label']);
				}
			});
		}

		// Function to decrease the like count via an AJAX request
		function wp_recommend_decrease_like_count(post_id) {
			$.ajax({
				type: 'post',
				url: wp_recommend_ajax_url,
				data: {
					action: 'wp_recommend_remove_like',
					post_id: post_id,
				},
				beforeSend: function() {
					el.children('.recommend-likes-count').html('-');
				},
				success: function(data){
					var data = JSON.parse(data);
					el.removeClass('liked');
					el.attr('title', 'Like this');
					el.children('.recommend-likes-count').html(data['new_likes']);
					el.children('.recommend-likes-label').html(data['likes_label']);
				}
			});
		}

		// Function to set cookie value
		function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = 'expires='+ d.toUTCString();
	    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/;SameSite=Lax';
		}

		// Function to get cookie value
		function getCookie(name) {
	    // Split cookie string and get all individual name=value pairs in an array
	    var cookieArr = document.cookie.split(";");
	    
	    // Loop through the array elements
	    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
          // Decode the cookie value and return
          return decodeURIComponent(cookiePair[1]);
        }
	    }
	    
	    // Return null if not found
	    return null;
		}

		// Function to remove/expire cookie
		function expireCookie(cname) {
			document.cookie = cname + '=;expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
		}

	});
})(jQuery);