<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
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
Timber::render(array('page-' . $post->post_name . '.twig', 'page.twig'), $context);