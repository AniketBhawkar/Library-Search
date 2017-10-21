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
				'editor',
			),
			'has_archive' => true,
			'hierarchical' => false,
			'menu_icon'   => 'dashicons-images-alt',
			'register_meta_box_cb' => 'add_library_metaboxes'
		)
	);
}

function create_library_taxonomies() {
	$labels = array(
		'name'              => _x( 'AuthorCategory', 'taxonomy general name' ),
		'singular_name'     => _x( 'AuthorCategory', 'taxonomy singular name' ),
		'search_items'      => __( 'Search AuthorCategory' ),
		'all_items'         => __( 'All AuthorCategory' ),
		'parent_item'       => __( 'Parent AuthorCategory' ),
		'parent_item_colon' => __( 'Parent AuthorCategory:' ),
		'edit_item'         => __( 'Edit AuthorCategory' ),
		'update_item'       => __( 'Update AuthorCategory' ),
		'add_new_item'      => __( 'Add New AuthorCategory' ),
		'new_item_name'     => __( 'New AuthorCategory' ),
		'menu_name'         => __( 'Author Category' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);

	register_taxonomy( 'authorcategory', array( 'books' ), $args );
	
	$labels = array(
		'name'              => _x( 'PublisherCategory', 'taxonomy general name' ),
		'singular_name'     => _x( 'PublisherCategory', 'taxonomy singular name' ),
		'search_items'      => __( 'Search PublisherCategory' ),
		'all_items'         => __( 'All PublisherCategory' ),
		'parent_item'       => __( 'Parent PublisherCategory' ),
		'parent_item_colon' => __( 'Parent PublisherCategory:' ),
		'edit_item'         => __( 'Edit PublisherCategory' ),
		'update_item'       => __( 'Update PublisherCategory' ),
		'add_new_item'      => __( 'Add New PublisherCategory' ),
		'new_item_name'     => __( 'New PublisherCategory' ),
		'menu_name'         => __( 'Publisher Category' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
	);

	register_taxonomy( 'publishercategory', array( 'books' ), $args );	
}

add_action( 'init', 'create_library_taxonomies', 0 );

function add_library_metaboxes() {
	add_meta_box('wpt_library_author', 'Book Meta Information', 'wpt_library_author', 'books', 'normal', 'default');
}

function wpt_library_author() {
	global $post;
	echo 'Rating:<br/><input type="hidden" name="librarymeta_noncename" id="librarymeta_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$rating = get_post_meta($post->ID, '_rating', true);
	echo '<input type="text" name="_rating" value="' . $rating  . '" class="widefat" />';

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
	$library_meta['_rating'] = $_POST['_rating'];
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
		$html .= '<form name="library-form" class="library-form">';
			$html .= '<div class="col-xs-12">';
				$html .= '<div class="col-xs-12"><h4>Library Search Plugin</h4></div>';
				$html .= '<div class="col-xs-6">';
					$html .= 'Book Title<input type="text" class="field" name="book-title" placeholder="Book Title">';
				$html .= '</div>';
				$html .= '<div class="col-xs-6">';
					//$html .= '<input type="text" class="field" name="book-author" placeholder="Author">';
					$html .= 'Author<select class="field" name="book-author">';
						$authorterms = get_terms( array(
							'taxonomy' => 'authorcategory',
							'hide_empty' => false,
						) );
						//print_r($authorterms);
						foreach($authorterms as $at){
							$html .= '<option value="'.$at->slug.'">'.$at->name.'</option>';
						}
					$html .= '</select>';
				$html .= '</div>';		
				$html .= '<div class="col-xs-6">';
					//$html .= '<input type="text" class="field" name="book-publisher" placeholder="Publisher">';
					$html .= 'Publisher<select class="field" name="book-publisher">';
						$publisherterms = get_terms( array(
							'taxonomy' => 'publishercategory',
							'hide_empty' => false,
						) );
						foreach($publisherterms as $pt){
							$html .= '<option value="'.$pt->slug.'">'.$pt->name.'</option>';
						}
					$html .= '</select>';
				$html .= '</div>';
				$html .= '<div class="col-xs-6">';
					$html .= 'Rating<select class="field" name="book-rating" placeholder="Rating">';
						$html .= '<option value="1">1</option>';
						$html .= '<option value="2">2</option>';
						$html .= '<option value="3">3</option>';
						$html .= '<option value="4">4</option>';
						$html .= '<option value="5">5</option>';
					$html .= '</select>';
				$html .= '</div>';
				$html .= '<div class="col-xs-6">';
					$html .= 'Min Value<input type="text" class="field" name="book-min-value" placeholder="Min Value">';
				$html .= '</div>';
				$html .= '<div class="col-xs-6">';
					$html .= 'Max Value<input type="text" class="field" name="book-max-value" placeholder="Max Value">';
				$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="col-xs-12">';
				$html .= '<div class="col-xs-4">';
					$html .= '<input type="submit" name="book-meta-submit" value="Submit">';
				$html .= '</div>';
			$html .= '</div>';
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

/* Filter the single_template with our custom function*/
add_filter('single_template', 'library_custom_template');

function library_custom_template($single) {

    global $wp_query, $post;
    if ( $post->post_type == 'books' ) {
        if ( file_exists( dirname( __FILE__ ) . '\single-books.php' ) ) {
            return dirname( __FILE__ ) . '\single-books.php';
        }
    }
    return $single;

}

?>