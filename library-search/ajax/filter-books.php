<?php 
	include("../../../../wp-load.php");
	$author = $_POST['author'];
	$publisher = $_POST['publisher'];
	$title = $_POST['title'];
	$rating = $_POST['rating'];
	$minprice = $_POST['minprice'];
	$maxprice = $_POST['maxprice'];

	if($title != ""){
		$args1 = array(
			's' => $title,
			'post_type' => 'books',
			'posts_per_page' => '-1',
		);
		$the_query1 = new WP_Query( $args1 );	
	}else{
		$the_query1 = new stdClass();
		$the_query1->posts = [];	
		$the_query1->post_count = 0;	
	}
	
	$args2 = array(
		'post_type' => 'books',
		'posts_per_page' => '-1',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'publishercategory',
				'field'    => 'slug',
				'terms'    => $publisher,
			),
			array(
				'taxonomy' => 'authorcategory',
				'field'    => 'slug',
				'terms'    => $author,
			),
		),		
		'meta_query' => array(
			'relation' => 'AND',
			array(
				'key'     => '_rating',
				'value'   => $rating,
				'compare' => '=',
			),
			array(
				'key'     => '_price',
				'value'   => array( $minprice, $maxprice ),
				'type'    => 'numeric',
				'compare' => 'BETWEEN',
			),
		),
	);
	$the_query2 = new WP_Query( $args2 );
	$wp_query = new WP_Query();
	$wp_query->posts = array_merge( $the_query1->posts, $the_query2->posts );
	$wp_query->post_count = $the_query1->post_count + $the_query2->post_count;
	
	
	$html = "<div>";
	$i = 1;
	if ( $wp_query->post_count ) {
		while ( $wp_query->post_count >= $i ) {
			$wp_query->the_post();
			
			global $post;

			$post_id=$post->ID;
			$current_term1 = wp_get_post_terms( $post_id, 'publishercategory', array("fields" => "all") );
			$current_term2 = wp_get_post_terms( $post_id, 'authorcategory', array("fields" => "all") );
			
			$html .= '<div class="col-xs-12"><div class="col-sm-1 hidden-xs">'.$i.'</div><div class="col-sm-3"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div><div class="col-sm-2">';
			foreach($current_term2 as $term) {
				$current_term_id = $term->term_id;
				$html .= $term->name;
			}
			$html .= '</div><div class="col-sm-3">';
			foreach($current_term1 as $term) {
				$current_term_id = $term->term_id;
				$html .= $term->name;
			}
			$html .= '</div><div class="col-sm-3">'.get_field('_rating').'</div></div>';
			$i++;
		}
	}
	$html .= "</div>";
	echo $html;
	return;
?>