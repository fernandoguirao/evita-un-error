<?php

	if (!class_exists('Timber')){
		add_action( 'admin_notices', function(){
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . admin_url('plugins.php#timber') . '">' . admin_url('plugins.php') . '</a></p></div>';
		});
		return;
	}

	class StarterSite extends TimberSite {

		function __construct(){
			add_theme_support('post-formats');
			add_theme_support('post-thumbnails');
			add_theme_support('menus');
			add_filter('timber_context', array($this, 'add_to_context'));
			add_filter('get_twig', array($this, 'add_to_twig'));
			add_action('init', array($this, 'register_post_types'));
			add_action('init', array($this, 'register_taxonomies'));
			parent::__construct();
		}

		function register_post_types(){
			//this is where you can register custom post types
		}

		function register_taxonomies(){
			//this is where you can register custom taxonomies
		}

		function add_to_context($context){
			$context['foo'] = 'bar';
			$context['stuff'] = 'I am a value set in your functions.php file';
			$context['notes'] = 'These values are available everytime you call Timber::get_context();';
			$context['menu'] = new TimberMenu();
			$context['site'] = $this;
			return $context;
		}

		function add_to_twig($twig){
			/* this is where you can add your own fuctions to twig */
			$twig->addExtension(new Twig_Extension_StringLoader());
			$twig->addFilter('myfoo', new Twig_Filter_Function('myfoo'));
			return $twig;
		}

	}

	new StarterSite();

	function myfoo($text){
    	$text .= ' bar!';
    	return $text;
	}

// FILTRA POPULARES
Timber::add_route('popular/', function($params){
    $query = 'meta_key=votes_count&orderby=meta_value_num&order=DESC&posts_per_page=10';
    	$context = Timber::get_context();
    	$context['posts'] = Timber::get_posts($query);
    	
    	$context['categories'] = Timber::get_terms('category', array('parent' => 0));
    	$context['authorAvatar'] = get_avatar_url(get_avatar(get_the_author_meta('email'), '40' ));
    	$context['email'] = get_the_author_meta('email');
    	Timber::render('index.twig', $context);
    	echo "<script>jQuery(document).ready(function() { jQuery('.main-content').last().hide(); });</script>";
});
// FIN FILTRA POPULARES
	
	function get_avatar_url($authorid,$size){

	    preg_match("/src='(.*?)'/i", get_avatar($authorid, $size ), $matches);
	    return $matches[1];
	}

	// LIKE DISLIKE
	$timebeforerevote = 1;

	add_action('wp_ajax_nopriv_post-like', 'post_like');
	add_action('wp_ajax_post-like', 'post_like');

	wp_enqueue_script('like_post', get_template_directory_uri().'/misc/postlike/post-like.js', array('jquery'), '1.0', 1 );
	wp_localize_script('like_post', 'ajax_var', array(
		'url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ajax-nonce')
	));

	/*function post_like()
	{
		$nonce = $_POST['nonce'];
	 
	    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
	        die ( 'Busted!');
			
		if(isset($_POST['post_like']))
		{

			$user_ID = get_current_user_id();

			$ip = $_SERVER['REMOTE_ADDR'];
			$post_id = $_POST['post_id'];
			
			$meta_IP = get_post_meta($post_id, "voted_IP");

			$voted_IP = $meta_IP[0];
			if(!is_array($voted_IP))
				$voted_IP = array();
			
			$meta_count = get_post_meta($post_id, "votes_count", true);

			if(!hasAlreadyVoted($post_id))
			{
				$voted_IP[$user_ID] = time();

				update_post_meta($post_id, "voted_IP", $voted_IP);
				update_post_meta($post_id, "votes_count", ++$meta_count);
				
				echo $meta_count;
			}
			else
				echo "already";
		}
		exit;
	}*/
	function post_like()
	{
	    // Check for nonce security
	    $nonce = $_POST['nonce'];
	  
	    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
	        die ( 'Busted!');
	     
	    if(isset($_POST['post_like']))
	    {
	        // Retrieve user IP address
	        $ip = $_SERVER['REMOTE_ADDR'];
	        $post_id = $_POST['post_id'];
	         
	        // Get voters'IPs for the current post
	        $meta_IP = get_post_meta($post_id, "voted_IP");
	        $voted_IP = $meta_IP[0];
	 
	        if(!is_array($voted_IP))
	            $voted_IP = array();
	         
	        // Get votes count for the current post
	        $meta_count = get_post_meta($post_id, "votes_count", true);
	 
	        // Use has already voted ?
	        if(!hasAlreadyVoted($post_id))
	        {
	            $voted_IP[$ip] = time();
	 
	            // Save IP and increase votes count
	            update_post_meta($post_id, "voted_IP", $voted_IP);
	            update_post_meta($post_id, "votes_count", ++$meta_count);
	             
	            // Display count (ie jQuery return value)
	            echo $meta_count;
	        }
	        else
	            echo "already";
	    }
	    exit;
		}

/*	function hasAlreadyVoted($post_id)
	{
		global $timebeforerevote;

		$meta_IP = get_post_meta($post_id, "voted_IP");
		$voted_IP = $meta_IP[0];
		if(!is_array($voted_IP))
			$voted_IP = array();

		$user_ID = get_current_user_id();
		
		if(in_array($user_ID, array_keys($voted_IP)))
		{
				
			return true;
		}
		
		return false;
	}*/
	function hasAlreadyVoted($post_id)
	{
	    global $timebeforerevote;
	 
	    // Retrieve post votes IPs
	    $meta_IP = get_post_meta($post_id, "voted_IP");
	    $voted_IP = $meta_IP[0];
	     
	    if(!is_array($voted_IP))
	        $voted_IP = array();
	         
	    // Retrieve current user IP
	    $ip = $_SERVER['REMOTE_ADDR'];
	     
	    // If user has already voted
	    if(in_array($ip, array_keys($voted_IP)))
	    {
	        $time = $voted_IP[$ip];
	        $now = time();
	         
	        // Compare between current time and vote time
	        if(round(($now - $time) / 60) > $timebeforerevote)
	            return false;
	             
	        return true;
	    }
	     
	    return false;
	}



	function getPostLikeLink($post_id)
	{

		$vote_count = get_post_meta($post_id, "votes_count", true);

		$output = '<div class="post-like">';

		if(hasAlreadyVoted($post_id)) {
			$output .= '<span class="count hasVoted" style="display:none">'.$vote_count.'</span>';
		}
		else {
			$output .= "<div class='poll notVoted'><div class='content'><div class='col-sm-2 col-xs-2'>";
			$output .= "<div class='img-thumbup' data-post_id='".$post_id."'></div>";
			$output .= "</div><div class='col-sm-8 col-xs-8'><p class='big poll-text'>¿Te ha ayudado esta experiencia?</p></div><div class='col-sm-2 col-xs-2'>";
			$output .= "<div class='img-thumbdown'></div>";
			$output .= "</div></div></div>";
		}
		
		$output .= '</div>';
		
		return $output;
	}

	// END LIKE DISLIKE

function SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}

add_filter('pre_get_posts','SearchFilter');

// PUBLICAR CON FORMULARIO CONTACT FORM

/*function my_wpcf7_save($cfdata) {
 
	$formtitle = $cfdata->title;
	$formdata = $cfdata->posted_data;	
 
	if ( $formtitle == 'Comparte tu experiencia') {
 
		// access data from the submitted form
		$formfield = $formdata['fieldname'];
 
		// create a new post
		$newpost = array( 'post_title' => $formdata['titulo'],
		     		  'post_content' => $formdata['contenido'],
		    		  'post_status' => 'publish');
 
		$newpostid = wp_insert_post($newpost);
	}
 
}
add_action('wpcf7_before_send_mail', 'my_wpcf7_save',1);
*/