<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
register_activation_hook( $plugin_file_path, 'fix161174_activation_hook' );
function fix161174_activation_hook() {
	// if (!$GLOBALS['wp_roles']->is_role( 'administrativo' )) {
		add_role( 
			'role-administrativo', 'role-administrativo', array( 
	            'contributor' => true,
	            'create_posts' => true,
	            'delete_others_posts' => true,
	            'delete_posts' => true,
	            'delete_private_posts' => true,
	            'delete_published_posts' => true,
	            'edit_others_posts' => true,
	            'edit_posts' => true,
	            'edit_private_posts' => true,
	            'edit_published_posts' => true,
	            'level_0' => true,
	            'publish_posts' => true,
	            'read' => true,
	            'read_private_posts' => true
			) 	
		);
	// }
}

/*
WP_Role Object
(
    [name] => role-administrativo
    [capabilities] => Array
        (
            [contributor] => 1
            [create_posts] => 1
            [delete_others_posts] => 1
            [delete_posts] => 1
            [delete_private_posts] => 1
            [delete_published_posts] => 1
            [edit_others_posts] => 1
            [edit_posts] => 1
            [edit_private_posts] => 1
            [edit_published_posts] => 1
            [level_0] => 1
            [publish_posts] => 1
            [read] => 1
            [read_private_posts] => 1
        )

)
*/