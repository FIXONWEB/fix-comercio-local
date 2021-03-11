<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }

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
	//$path_planilha = plugin_dir_path( __FILE__ )."planilhas/listagem_comercio_local.xlsx";
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