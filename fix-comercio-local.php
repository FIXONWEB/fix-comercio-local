<?php
/**
 * Plugin Name:     Fix Comércio Local
 * Plugin URI:      https://github.com/fixonweb/fix-comercio-local
 * Description:     Gerenciar o cadastro de pontos comerciais do seu bairro
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix-comercio-local
 * Domain Path:     /languages
 * Version:         0.1.6
 *
 * @package         Fix_Comercio_Local
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$fix1608230887_url_update 	= 'https://github.com/fixonweb/fix-comercio-local';
$fix1608230887_slug 		= 'fix-comercio-local/fix-comercio-local';
$fix1608230887_check 		= Puc_v4_Factory::buildUpdateChecker($fix1608230887_url_update,__FILE__,$fix1608230887_slug);

include "includes/parse_request.php";

register_activation_hook( __FILE__, 'fix161174_activation_hook' );
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








function fix161174_taxonomy_categoria() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name'              => 'Categorias de Comércio',
        'singular_name'     => 'Categoria de Comércio',
        'search_items'      => 'Localizar Categoria de Comércio',
        'all_items'         => 'Todas as Categorias de Comércio',
        'parent_item'       => 'Categoria Pai',
        'parent_item_colon' => 'Categoria superior (acima/pai):',
        'edit_item'         => 'EDITAR Categoria de Comércio',
        'update_item'       => 'ATUALIZAR Categoria de Comércio',
        'add_new_item'      => 'Adicionar NOVA categoria de Comércio',
        'new_item_name'     => 'Nome da NOVA categoria de Comércio',
        'menu_name'         => 'Categorias de Comércio',
    );
 
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'comercio-categoria' ),
    );
 
    register_taxonomy( 'comercio-categoria', array( 'comercio-local' ), $args );
 
    unset( $args );
    unset( $labels );
 
}
// hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'fix161174_taxonomy_categoria', 0 );






add_action( 'add_meta_boxes', 'fix161174_add_meta_box' );
function fix161174_add_meta_box(){
	add_meta_box( 'fix161174_add_meta_box', 'Comércio Local - Informações adicionais', 'fix161174_meta_box', 'comercio-local', 'normal', 'high' );
}

//FORMULARIO PARA SALVAS OS DADOS
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





add_shortcode("comercio_local_list", "fix161174_list_comercio_local");
add_shortcode("fix161174_list_comercio_local", "fix161174_list_comercio_local");
function fix161174_list_comercio_local($atts, $content = null){
	$comercio_categoria_all = get_terms( 'comercio-categoria' );
	$comercio_categoria_selecionada = '';
	$comercio_categoria_get = isset($_GET['comercio-categoria']) ? $_GET['comercio-categoria'] : '';


	?>
	<style type="text/css" media="screen">
		.fix161174_box {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
		}
		@media (max-width: 600px) {
			.fix161174_box {
				grid-template-columns: 1fr;
			}
		}
	</style>


	<div style="padding: 30px 100px;text-align: center;">
		<?php
			foreach ($comercio_categoria_all as $key => $comercio_categoria_alls) {
				if($comercio_categoria_get){
					if($comercio_categoria_alls->slug==$comercio_categoria_get){
						$comercio_categoria_selecionada = $comercio_categoria_alls->name;
					}
				}
				?>
					<a href="<?php echo '?'.$comercio_categoria_alls->taxonomy.'='.$comercio_categoria_alls->slug ?>" title=""><span style="border-radius: 5px;-moz-border-radius: 5px;border: 1px solid gray;padding: 0px 12px;margin: 10px; "><?php echo $comercio_categoria_alls->name ?> </span></a>
				<?php
			}
		?>
		<a href="<?php echo '?'.$comercio_categoria_alls->taxonomy.'=' ?>" title=""><span style="border-radius: 5px;-moz-border-radius: 5px;border: 1px solid gray;padding: 0px 12px;margin: 10px; ">Todas </span></a>
		<div style="height:50px;"></div>
		<dir><h3><?php echo $comercio_categoria_selecionada ?></h3></dir>
		<div style="height:50px;"></div>

	</div>
	<div style="height:50px;"></div>
	<?php 


	$comercio_local_args = array(
	    'post_type'  => 'comercio-local',
	    'numberposts' => -1,
	);
	$param_comercio_categoria = isset($_GET['comercio-categoria']) ? $_GET['comercio-categoria'] : '';
	if($param_comercio_categoria){
		$param_comercio_categoria_name = 
		$comercio_local_args['tax_query'] = array(
			array(
				'taxonomy' => 'comercio-categoria',
				'field'    => 'slug',
				'terms'    => array( $param_comercio_categoria )
			)
		);
	}
	$comercio_local = get_posts( $comercio_local_args );
	foreach ($comercio_local as $key => $comercio_locais) {
		$fix161174_telefone = get_post_meta( $comercio_locais->ID, 'fix161174_telefone', true );
		$fix161174_endereco = get_post_meta( $comercio_locais->ID, 'fix161174_endereco', true );
		$comercio_categoria = wp_get_post_terms( $comercio_locais->ID, 'comercio-categoria');
		$comercio_categoria_value = "";
		foreach ($comercio_categoria as $key => $comercio_categorias) {
			$comercio_categoria_value .= $comercio_categorias->name;
		}
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $comercio_locais->ID ), 'medium' );
		?>
		<div class="fix161174_box" style="">
			<div>
				.
			</div>
			<div>
				<h4 style="margin: 0px;padding: 0px;line-height: 1;"><?php echo $comercio_locais->post_title ?></h4>
				<strong><?php echo $comercio_categoria_value ?></strong>
				<div><?php echo $fix161174_endereco ?></div>
				<div><?php echo $fix161174_telefone ?></div>

			</div>
			<div>
				
			</div>
		</div>
		<div style="height:50px;"></div>
		<?php 
	}
}



function fix161174_access(){
	if( !is_user_logged_in()){
		wp_redirect( site_url('login'));
		exit;
	}

	
	if( is_user_logged_in()){
		$vai = 0;
		if(current_user_can('administrator')) $vai = 1;
		if(current_user_can('role-administrativo')) $vai = 1;
		if(!$vai) {	
			wp_redirect( site_url() );
			exit;
		}
	}
}




add_shortcode("fix161174_head", "fix161174_head");
function fix161174_head($atts, $content = null){
	echo do_shortcode('[fix161340_mnu_lateral]');
	?>
			<style type="text/css" media="screen">
				.fix161174 {
					width: 1200px;
					margin: 40px auto;
				}
			</style>
			<div class="fix161174">
				<div>
					<div style="margin: 0;padding: 0;line-height: 1;text-align: center;">administrativo</div>	
					<h2 style="margin: 0;padding: 0;line-height: 1;text-align: center;">COMERCIO LOCAL</h2>
				</div>
				
				
			</div>

	<?php
}






function fix161174_exec_upload_planilha(){
	$vai = 0;
	if(current_user_can('administrator')) $vai = 1;
	if(current_user_can('role-administrativo')) $vai = 1;

	if(!$vai) {	return '--não disponivel--';exit;}

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$plugin_active = is_plugin_active( "fix-php-excell/fix-php-excell.php" );
	if(!$plugin_active) { echo "Necessário instalar e ativar o plugin fix-php-excell."; exit; }
	require_once WP_PLUGIN_DIR . '/fix-php-excell/Classes/PHPExcel.php';
	$objReader = new PHPExcel_Reader_Excel2007();
	$objReader->setReadDataOnly(true);
	$path_planilha = plugin_dir_path( __FILE__ )."planilhas/listagem_comercio_local.xlsx";
	$objPHPExcel = $objReader->load($path_planilha);
	$colunas = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
	$total_colunas = PHPExcel_Cell::columnIndexFromString($colunas);
	$total_linhas = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

	$dados_planilha = array();
	for ($linha=1; $linha <= $total_linhas; $linha++) { 
		if($linha > 2 ) {
			$nome = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0,$linha)->getvalue();
			$segmento = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,$linha)->getvalue();
			$telefone = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,$linha)->getvalue();
			$endereco = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3,$linha)->getvalue();
			$horarios = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4,$linha)->getvalue();

			$data['nome'] = trim($nome);
			$data['segmento'] = trim($segmento);
			$data['telefone'] = trim($telefone);
			$data['endereco'] = trim($endereco);
			$data['horarios'] = trim($horarios);
			$data['post_id'] = fix_post_exists( $data['nome'] );
			if($data['nome']) {
				$is_t = term_exists( $data['segmento'], 'comercio-categoria' );
				$data['termo_id'] = isset($is_t['term_id']) ? $is_t['term_id'] : '';

				if(!$data['termo_id']){
					$data['termo_new'] = wp_insert_term(
					    $data['segmento'],   // the term 
					    'comercio-categoria' // the taxonomy
					);
					$data['termo_id'] = $data['termo_new']['term_id'];
				}



				if(!$data['post_id']){
					/*
					!!!!!!!!!!!!!!!!!!!!!!!!
					OBSERVE QUE, 
					PARA TAX_INPUT FUNCIONAR, 
					O USUÁRIO ATUALMENTE CONECTADO QUANDO O CÓDIGO 
					É EXECUTADO DEVE TER A CAPACIDADE DE GERENCIAR ESSA TAXONOMIA, 
					CASO CONTRÁRIO, FALHARÁ SILENCIOSAMENTE.
					*/
					$post_nnew = array(
						'post_title'    => $data['nome'],
						'post_status'   => 'publish',
						'post_type' 	=> 'comercio-local',
						'meta_input'   => array(
							'fix161174_telefone' => $data['telefone'],
							'fix161174_endereco' => $data['endereco'],
							'fix161174_horarios' => $data['horarios'],
						),
						'tax_input' 	=> array(
							'comercio-categoria' => $data['termo_id'] 
						),
					);
					$data['post_nnew'] = $post_nnew;
					$data['post_id'] = wp_insert_post( $post_nnew ); // --------- temporario 
				}
			}
			$dados_planilha[$linha] = $data;
		}
	}
	// ob_start();
		get_header();
		do_shortcode( '[fix161174_head]' );
		?>
		<div style="text-align: center">
			<div style="padding: 30px;">
				Dados processados.	
			</div>
			<a href="<?php echo site_url() ?>/administrativo/comercio-local/" title="">ir para <?php echo site_url() ?>/administrativo/comercio-local/</a>
		</div>
		<?php 
		get_footer();
	// echo ob_get_clean();
	exit;
}





function fix_post_exists( $title, $content = '', $date = '', $type = '' ) {
    global $wpdb;
 
    $post_title   = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
    $post_content = wp_unslash( sanitize_post_field( 'post_content', $content, 0, 'db' ) );
    $post_date    = wp_unslash( sanitize_post_field( 'post_date', $date, 0, 'db' ) );
    $post_type    = wp_unslash( sanitize_post_field( 'post_type', $type, 0, 'db' ) );
 
    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
    $args  = array();
 
    if ( ! empty( $date ) ) {
        $query .= ' AND post_date = %s';
        $args[] = $post_date;
    }
 
    if ( ! empty( $title ) ) {
        $query .= ' AND post_title = %s';
        $args[] = $post_title;
    }
 
    if ( ! empty( $content ) ) {
        $query .= ' AND post_content = %s';
        $args[] = $post_content;
    }
 
    if ( ! empty( $type ) ) {
        $query .= ' AND post_type = %s';
        $args[] = $post_type;
    }
 
    if ( ! empty( $args ) ) {
        return (int) $wpdb->get_var( $wpdb->prepare( $query, $args ) );
    }
 
    return 0;
}




add_shortcode("fix161174_form_upload_planilha", "fix161174_form_upload_planilha");
function fix161174_form_upload_planilha($atts, $content = null){

	if( !is_user_logged_in() ) {
		ob_start();
		?>
		<div style="text-align: center">
			<a href="<?php echo site_url()?>/login/ " title="">Login requerido.</a>
		</div>
		<?php 
		return ob_get_clean();
	}
	$vai = 0;
	if(current_user_can('administrator')) $vai = 1;
	if(current_user_can('role-administrativo')) $vai = 1;
	
	if(!$vai) {	
		ob_start();
		?>
		<div style="text-align: center">
			Permissão insuficiente.
		</div>
		<?php 
		return ob_get_clean();
	}

	global $post;
	ob_start();
	?>
		<form method="POST" action="<?php echo site_url() ?>/fix161174_recebe_form_upload_planilha/" enctype="multipart/form-data">
			<div class="upload-wrapper">
				<input type="file" id="file-upload" name="uploadedFile">
			</div>
			<input type="hidden" name="origin_upload" value="<?php echo $post->ID ?>"> 
			<input type="submit" name="uploadBtn" value="Upload" />
		</form>


	<?php
	return ob_get_clean();
}
