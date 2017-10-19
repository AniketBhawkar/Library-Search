<?php 
	include("../../../../wp-load.php");
	$val = $_POST['val'];
	
	$args1 = array(
		's' => $val,
		'post_type' => 'books',
		'posts_per_page' => '-1',
	);
	$the_query1 = new WP_Query( $args1 );		
	
	$args2 = array(
		'post_type' => 'books',
		'posts_per_page' => '-1',
		'meta_query' => array(
			'relation' => 'OR',
			array(
				'key'     => '_author',
				'value'   => $val,
				'compare' => 'LIKE',
			),
			array(
				'key'     => '_price',
				'value'   => $val,
				'compare' => '=',
			),
		),
	);
	$the_query2 = new WP_Query( $args2 );
	
	$wp_query = new WP_Query();
	$wp_query->posts = array_merge( $the_query1->posts, $the_query2->posts );
	$wp_query->post_count = $the_query1->post_count + $the_query2->post_count;
	
	$html = "<ul>";
	$i = 0;
	if ( $wp_query->post_count ) {
		while ( $wp_query->post_count > $i ) {
			$wp_query->the_post();
			$html .= "<li>Title: ".get_the_title()."<br/>Author: ".get_field('_author')."<br/>Price: ".get_field('_price')."</li><br/>";
			$i++;
		}
	}
	$html .= "</ul>";
	echo $html;
	return;
?>