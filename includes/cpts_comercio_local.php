<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
function fix161174_cpts_comercio_local() {

	$labels = array(
		"name" => "Comércios locais",
		"singular_name" => "Comércio local",
	);

	$args = array(
		"label" => "Comercio local",
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "comercio-local", "with_front" => true ),
		"query_var" => true,
		// "supports" => array( "title", "editor","thumbnail" ),
		"supports" => array( "title", "thumbnail" ),
	);

	register_post_type( "comercio-local", $args );
}
add_action( 'init', 'fix161174_cpts_comercio_local' );
