<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'add_meta_boxes', 'fix161174_add_meta_box' );
function fix161174_add_meta_box(){
	add_meta_box( 'fix161174_add_meta_box', 'Comércio Local - Informações adicionais', 'fix161174_meta_box', 'comercio-local', 'normal', 'high' );
}

function fix161174_meta_box(){
	$values = get_post_custom( $post->ID );
	$fix161174_endereco = isset( $values['fix161174_endereco'] ) ? esc_attr( $values['fix161174_endereco'][0] ) : '';
	$fix161174_telefone = isset( $values['fix161174_telefone'] ) ? esc_attr( $values['fix161174_telefone'][0] ) : '';
	
	// $selected = isset( $values['meta_box_select'] ) ? esc_attr( $values['meta_box_select'][0] ) : '';
	// $check = isset( $values['meta_box_check'] ) ? esc_attr( $values['meta_box_check'][0] ) : '';
	wp_nonce_field( 'fix161174_action_box_nonce', '_fix161174_nonce' );
	?>
		<p>
			<label for="fix161174_telefone">Telefone</label>
			<input type="text" name="fix161174_telefone" id="fix161174_telefone" value="<?php echo $fix161174_telefone ?>" style="width:100%;" />
		</p>
		<p>
			<label for="fix161174_endereco">Endereço</label>
			<input type="text" name="fix161174_endereco" id="fix161174_endereco" value="<?php echo $fix161174_endereco ?>" style="width:100%;" />
		</p>

	<?php
}



add_action( 'save_post', 'fix161174_meta_box_save' );
function fix161174_meta_box_save( $post_id ){
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['_fix161174_nonce'] ) || !wp_verify_nonce( $_POST['_fix161174_nonce'], 'fix161174_action_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post' ) ) return;
	$allowed = array(
		'a' => array(
			'href' => array()
		)
	);

	if( isset( $_POST['fix161174_telefone'] ) ){
		update_post_meta( $post_id, 'fix161174_telefone', wp_kses( $_POST['fix161174_telefone'], $allowed ) );
	}
	if( isset( $_POST['fix161174_endereco'] ) ){
		update_post_meta( $post_id, 'fix161174_endereco', wp_kses( $_POST['fix161174_endereco'], $allowed ) );	
	}



	// if( isset( $_POST['meta_box_select'] ) )
	// update_post_meta( $post_id, 'meta_box_select', esc_attr( $_POST['meta_box_select'] ) );

	// $chk = ( isset( $_POST['meta_box_check'] ) && $_POST['meta_box_check'] ) ? 'on' : 'off';
	// update_post_meta( $post_id, 'meta_box_check', $chk );
}
