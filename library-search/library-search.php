<?php
   /*
   Plugin Name: Library Search
   Description: A plugin to search books in the library.
   Version: 1.0
   Author: Mr. Aniket Bhawkar
   Author URI: https://www.linkedin.com/in/aniket-bhawkar-38908021/
   License: GPL2
   */

function library_search_scripts() {
	wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) .'/css/bootstrap.min.css' );
	wp_enqueue_style( 'jqueryui-style', plugin_dir_url( __FILE__ ) .'/css/jquery-ui.structure.min.css' );
	wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) .'/css/style.css' );
	wp_enqueue_script( 'jqueryui-script', plugin_dir_url( __FILE__ ) . '/js/jquery-ui.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'bootstrap-script', plugin_dir_url( __FILE__ ) . '/js/bootstrap.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'script-name', plugin_dir_url( __FILE__ ) . '/js/scripts.js', array(), '1.0.0', true );
	wp_localize_script('script-name', 'myScript', array(
		'pluginsUrl' => plugin_dir_url( __FILE__ ),
	));
}

add_action( 'wp_enqueue_scripts', 'library_search_scripts' );
   
add_action( 'init', 'create_libraryposttype' );
function create_libraryposttype() {
	register_post_type( 'books',
		array(
			'labels' => array(
			'name' => __( 'Books' ),
			'singular_name' => __( 'Book' )
		),
		'public' => true,
			'supports' => array(
				'title',
				'revisions',
			),
			'has_archive' => true,
			'hierarchical' => false,
			'menu_icon'   => 'dashicons-images-alt',
			'register_meta_box_cb' => 'add_library_metaboxes'
		)
	);
}


function add_library_metaboxes() {
	add_meta_box('wpt_library_author', 'Library Author', 'wpt_library_author', 'books', 'normal', 'default');
}

function wpt_library_author() {
	global $post;
	echo 'Author:<br/><input type="hidden" name="librarymeta_noncename" id="librarymeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$author = get_post_meta($post->ID, '_author', true);
	echo '<input type="text" name="_author" value="' . $author  . '" class="widefat" />';

	echo 'Price:<br/><input type="hidden" name="librarymeta_noncename" id="librarymeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$price = get_post_meta($post->ID, '_price', true);
	echo '<input type="text" name="_price" value="' . $price  . '" class="widefat" />';
}

function wpt_save_library_meta($post_id, $post) {
	if ( !wp_verify_nonce( $_POST['librarymeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	$library_meta['_author'] = $_POST['_author'];
	$library_meta['_price'] = $_POST['_price'];
	foreach ($library_meta as $key => $value){
		if( $post->post_type == 'revision' ) 
			return;
		$value = implode(',', (array)$value);
		if(get_post_meta($post->ID, $key, FALSE)){
			update_post_meta($post->ID, $key, $value);
		}else{
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) 
			delete_post_meta($post->ID, $key);
	}

}

add_action('save_post', 'wpt_save_library_meta', 1, 2);


function library_search_func() {
	$html = '';
	$html .= '<div class="container library-form-outer">';
		$html .= '<form name="library-form">';
			$html .= '<div class="col-xs-12">';
				$html .= '<div class="col-xs-12"><h4>Library Search Plugin</h4></div>';
				$html .= '<div class="col-xs-6">';
					$html .= '<input type="text" class="field" name="book-meta" placeholder="Book Title, Author, Price">';
				$html .= '</div>';
			$html .= '</div>';
			/* $html .= '<div class="col-xs-12">';
				$html .= '<div class="col-xs-4">';
					$html .= '<input type="submit" name="book-meta-submit" value="Submit">';
				$html .= '</div>';
			$html .= '</div>'; */
		$html .= '</form>';
		$html .= '<div class="container">';
			$html .= '<div class="col-xs-12">';
				$html .= '<div class="library-result-outer">';
				$html .= '</div>';	
			$html .= '</div>';	
		$html .= '</div>';	
	$html .= '</div>';	
	
	echo $html;
}
add_shortcode( 'library_search', 'library_search_func' );

?>