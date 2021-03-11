<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
register_activation_hook( $plugin_dir_path, 'fix161174_activation_hook' );
function fix161174_activation_hook() {
	if (!$GLOBALS['wp_roles']->is_role( 'administrativo' )) {
		add_role( 
			'role-administrativo', 
			'role-administrativo', array( 
				'read' => true, 
				'contributor' => true, 
				'level_0' => true 
			) 	
		);
	}
}