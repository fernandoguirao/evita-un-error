<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

/*$usuario = new TimberUser();
$contexto['userid'] = get_user_meta($usuario,$usuario -> ID );*/



$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['wp_title'] .= ' - ' . $post->title();
$context['comment_form'] = TimberHelper::get_comment_form();

$context['likebtn'] = getPostLikeLink(get_the_ID());
// $context['postLikes'] = get_post_meta($post_id, "votes_count", true);

/*$context['hasvoted'] = hasAlreadyVoted($post->ID);*/

$context['postid'] = get_post_meta($post->ID, "voted_IP");

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

// AUTOR
  $context['facebook'] = do_shortcode('[usermeta key="Facebook" user="get_the_author_meta("ID")"]');
  $context['twitter'] = do_shortcode('[usermeta key="Twitter" user="get_the_author_meta("ID")"]');
  $context['instagram'] = do_shortcode('[usermeta key="Instagram" user="get_the_author_meta("ID")"]');
  $context['web'] = do_shortcode('[usermeta key="Web" user="get_the_author_meta("ID")"]');
// FIN AUTOR
if (post_password_required($post->ID)){
	Timber::render('single-password.twig', $context);
} else {
	Timber::render(array('single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig'), $context);
}


