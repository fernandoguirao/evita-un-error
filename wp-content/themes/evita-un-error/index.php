<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package 	WordPress
 * @subpackage 	Timber
 * @since 		Timber 0.1
 */

	if (!class_exists('Timber')){
		echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
		return;
	}

	$context = Timber::get_context();
	
	// USER
	$context['nombre'] = $current_user->user_firstname;
	$context['userid'] = $current_user->ID;
	if ( is_user_logged_in() ) {
		$context['status'] = 'logged';
	} else {
	$context['status'] =  'unlogged';
	}
	$context['logout_url'] = wp_logout_url( $redirect );

	// FIN USER


	$context['posts'] = Timber::get_posts();
	$context['foo'] = 'bar';
	$context['categories'] = Timber::get_terms('category', array('parent' => 0));
	
	$context['authorAvatar'] = get_avatar_url(get_avatar(get_the_author_meta('email'), '40' ));
	$context['email'] = get_the_author_meta('email');

/*	$context['postLikes'] = get_post_meta($post->ID, "votes_count", true);
*/
	$templates = array('index.twig');
	if (is_home()){
		array_unshift($templates, 'home.twig');
	}
	Timber::render($templates, $context);

	