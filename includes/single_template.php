<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('single_template', 'fix161174_template');
function fix161174_template($single) {
	global $post;

	if ($post->post_type == 'comercio-local') {
		$fix_dir_path = plugin_dir_path( __FILE__ );
		die($fix_dir_path);
        // $single_template = 'single-comercio-local.php'; 

    }

	return $single;
}