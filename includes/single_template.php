<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('single_template', 'fix161174_template');
function fix161174_template($single) {
	global $post;


	return $single;
}